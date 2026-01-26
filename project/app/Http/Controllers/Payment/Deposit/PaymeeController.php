<?php

namespace App\Http\Controllers\Payment\Deposit;

use App\{
    Models\Deposit,
    Models\PaymentGateway,
    Models\Transaction
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Session;
use Illuminate\Support\Str;

class PaymeeController extends DepositBaseController
{
    private $apiKey;
    private $sandbox;
    private $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $data = PaymentGateway::whereKeyword('paymee')->first();
        if ($data) {
            $paydata = $data->convertAutoData();
            $this->apiKey = $paydata['api_key'] ?? '';
            $this->sandbox = $paydata['sandbox_check'] ?? 1;
            $this->baseUrl = $this->sandbox 
                ? 'https://sandbox.paymee.tn/api/v2' 
                : 'https://app.paymee.tn/api/v2';
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $amount = $request->amount;
        $depositId = 'DEP-' . Str::random(6) . '-' . time();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/payments/create', [
                'amount' => floatval($amount),
                'note' => 'Wallet Deposit',
                'first_name' => $user->name,
                'last_name' => '',
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'return_url' => route('deposit.paymee.success'),
                'cancel_url' => route('deposit.paymee.cancel'),
                'order_id' => $depositId,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['data']['paymentUrl'])) {
                Session::put('paymee_deposit_amount', $amount);
                Session::put('paymee_deposit_id', $depositId);
                Session::put('paymee_token', $result['data']['token'] ?? null);

                return redirect()->away($result['data']['paymentUrl']);
            }

            return redirect()->back()->with('unsuccess', __('Payment initiation failed.'));

        } catch (\Exception $e) {
            return redirect()->back()->with('unsuccess', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $amount = Session::get('paymee_deposit_amount');
        $token = $request->token ?? Session::get('paymee_token');

        if (!$amount || !$token) {
            return redirect()->route('deposit.payment.cancle')->with('unsuccess', __('Session expired.'));
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
            ])->get($this->baseUrl . '/payments/' . $token . '/check');

            $result = $response->json();

            if ($response->successful() && isset($result['data']['payment_status']) && $result['data']['payment_status'] === true) {
                return $this->processDeposit($amount, $token);
            }

            return redirect()->route('deposit.payment.cancle')->with('unsuccess', __('Payment not completed.'));

        } catch (\Exception $e) {
            return redirect()->route('deposit.payment.cancle')->with('unsuccess', $e->getMessage());
        }
    }

    public function cancel()
    {
        Session::forget('paymee_deposit_amount');
        Session::forget('paymee_deposit_id');
        Session::forget('paymee_token');
        
        return redirect()->route('deposit.payment.cancle')->with('unsuccess', __('Payment was cancelled.'));
    }

    private function processDeposit($amount, $txnId)
    {
        $user = Auth::user();

        $deposit = new Deposit;
        $deposit->user_id = $user->id;
        $deposit->currency = $this->curr->name;
        $deposit->currency_value = $this->curr->value;
        $deposit->amount = $amount / $this->curr->value;
        $deposit->method = 'Paymee';
        $deposit->txnid = $txnId;
        $deposit->status = 1;
        $deposit->save();

        $user->balance = $user->balance + ($amount / $this->curr->value);
        $user->save();

        $trans = new Transaction;
        $trans->user_id = $user->id;
        $trans->amount = $amount / $this->curr->value;
        $trans->type = 'Deposit';
        $trans->txn_number = $txnId;
        $trans->method = 'Paymee';
        $trans->save();

        Session::forget('paymee_deposit_amount');
        Session::forget('paymee_deposit_id');
        Session::forget('paymee_token');

        return redirect()->route('deposit.payment.return')->with('success', __('Deposit Successful'));
    }
}
