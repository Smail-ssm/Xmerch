<?php

namespace Tests\Feature\Readiness;

use App\Http\Controllers\Auth\User\LoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthLoginVerificationGateTest extends TestCase
{
    use DatabaseTransactions;

    protected function makeUser(array $overrides = [])
    {
        $defaults = [
            'name' => 'Login User ' . Str::random(6),
            'email' => 'login_' . Str::random(8) . '@example.com',
            'password' => bcrypt('secret123'),
            'email_verified' => 'Yes',
            'is_vendor' => 0,
            'ban' => 0,
        ];

        $data = array_merge($defaults, $overrides);
        $ban = $data['ban'] ?? 0;
        unset($data['ban']); // not fillable on User model

        $user = User::create($data);
        $user->ban = (int) $ban;
        $user->save();

        return $user;
    }

    public function test_login_rejects_unverified_users()
    {
        $user = $this->makeUser(['email_verified' => 'No']);

        $controller = app(LoginController::class);
        $request = Request::create('/user/login', 'POST', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);
        $response = $controller->login($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Your Email is not Verified', $response->getContent());
    }

    public function test_login_rejects_banned_users()
    {
        $user = $this->makeUser(['ban' => 1]);

        $controller = app(LoginController::class);
        $request = Request::create('/user/login', 'POST', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);
        $response = $controller->login($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Your Account Has Been Banned', $response->getContent());
    }
}
