<?php

namespace Tests\Feature\Readiness;

use App\Models\User;
use App\Models\Verification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserVerificationStateTest extends TestCase
{
    use DatabaseTransactions;

    protected function makeUser(array $overrides = [])
    {
        $defaults = [
            'name' => 'User ' . Str::random(6),
            'email' => 'user_' . Str::random(8) . '@example.com',
            'password' => bcrypt('secret123'),
            'email_verified' => 'Yes',
            'is_vendor' => 2,
            'ban' => 0,
        ];

        return User::create(array_merge($defaults, $overrides));
    }

    public function test_verified_submission_remains_verified_even_with_new_admin_warning()
    {
        $user = $this->makeUser();

        Verification::create([
            'user_id' => $user->id,
            'admin_warning' => 0,
            'status' => 'Verified',
        ]);

        Verification::create([
            'user_id' => $user->id,
            'admin_warning' => 1,
            'warning_reason' => 'Need additional docs',
            'status' => null,
        ]);

        $user->refresh();

        $this->assertTrue($user->checkStatus());
        $this->assertTrue($user->checkWarning());
        $this->assertSame('Need additional docs', $user->displayWarning());
    }

    public function test_pending_submission_sets_pending_state()
    {
        $user = $this->makeUser();

        Verification::create([
            'user_id' => $user->id,
            'admin_warning' => 0,
            'status' => 'Pending',
        ]);

        $user->refresh();

        $this->assertTrue($user->checkVerification());
        $this->assertFalse($user->checkStatus());
    }

    public function test_display_warning_is_empty_when_no_warning_record_exists()
    {
        $user = $this->makeUser();

        $this->assertFalse($user->checkWarning());
        $this->assertSame('', $user->displayWarning());
    }
}

