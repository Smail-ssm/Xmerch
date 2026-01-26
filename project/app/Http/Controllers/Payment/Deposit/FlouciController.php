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

class FlouciController extends DepositBaseController
{
    private $appToken;
    private $appSecret;
    private $sandbox;
    private $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $data = PaymentGateway::whereKeyword('flouci')->first();
        if ($data) {
            $paydata = $data->convertAutoData();
            $this->appToken = $paydata['app_token'] ?? '';
            $this->appSecret = $paydata['app_secret'] ?? '';
            $this->sandbox = $paydata['sandbox_check'] ?? 1;
            $this->baseUrl = $this->sandbox 
                ? 'https://developers.flouci.com/api' 
                : 'https://api.flouci.com';
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $amount = $request->amount;
        $depositId = 'DEP-' . Str::random(6) . '-' . time();

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/generate_payment', [
                'app_token' => $this->appToken,
                'app_secret' => $this->appSecret,
                'amount' => intval($amount * 1000),
                'accept_url' => route('deposit.flouci.success'),
                'cancel_url' => route('deposit.flouci.cancel'),
                'decline_url' => route('deposit.flouci.cancel'),
                'session_timeout_secs' => 1200,
                'developer_tracking_id' => $depositId,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['result']['link'])) {
                Session::put('flouci_deposit_amount', $amount);
                Session::put('flouci_deposit_id', $depositId);
                Session::put('flouci_payment_id', $result['result']['payment_id'] ?? null);

                return redirect()->away($result['result']['link']);
            }

            return redirect()->back()->with('unsuccess', __('Payment initiation failed.'));

        } catch (\Exception $e) {
            return redirect()->back()->with('unsuccess', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $amount = Session::get('flouci_deposit_amount');
        $depositId = Session::get('flouci_deposit_id');
        $paymentId = Session::get('flouci_payment_id');

        if (!$amount || !$paymentId) {
            return redirect()->route('deposit.payment.cancle')->with('unsuccess', __('Session expired.'));
        }

        try {
            $response = Http::get($this->baseUrl . '/verify_payment/' . $paymentId, [
                'app_token' => $this->appToken,
                'app_secret' => $this->appSecret,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['result']['status']) && $result['result']['status'] === 'SUCCESS') {
                return $this->processDeposit($amount, $paymentId);
            }

            return redirect()->route('deposit.payment.cancle')->with('unsuccess', __('Payment verification failed.'));

        } catch (\Exception $e) {
            return redirect()->route('deposit.payment.cancle')->with('unsuccess', $e->getMessage());
        }
    }

    public function cancel()
    {
        Session::forget('flouci_deposit_amount');
        Session::forget('flouci_deposit_id');
        Session::forget('flouci_payment_id');
        
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
        $deposit->method = 'Flouci';
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
        $trans->method = 'Flouci';
        $trans->save();

        Session::forget('flouci_deposit_amount');
        Session::forget('flouci_deposit_id');
        Session::forget('flouci_payment_id');

        return redirect()->route('deposit.payment.return')->with('success', __('Deposit Successful'));
    }
}
