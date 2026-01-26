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

class KonnectController extends DepositBaseController
{
    private $apiKey;
    private $walletId;
    private $sandbox;
    private $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $data = PaymentGateway::whereKeyword('konnect')->first();
        if ($data) {
            $paydata = $data->convertAutoData();
            $this->apiKey = $paydata['api_key'] ?? '';
            $this->walletId = $paydata['wallet_id'] ?? '';
            $this->sandbox = $paydata['sandbox_check'] ?? 1;
            $this->baseUrl = $this->sandbox 
                ? 'https://api.preprod.konnect.network/api/v2' 
                : 'https://api.konnect.network/api/v2';
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $amount = $request->amount;
        $depositId = 'DEP-' . Str::random(6) . '-' . time();

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/payments/init-payment', [
                'receiverWalletId' => $this->walletId,
                'amount' => intval($amount * 1000),
                'token' => 'TND',
                'type' => 'immediate',
                'description' => 'Wallet Deposit',
                'acceptedPaymentMethods' => ['wallet', 'bank_card', 'e-DINAR'],
                'lifespan' => 20,
                'checkoutForm' => true,
                'addPaymentFeesToAmount' => false,
                'orderId' => $depositId,
                'successUrl' => route('deposit.konnect.success'),
                'failUrl' => route('deposit.konnect.cancel'),
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['payUrl'])) {
                Session::put('konnect_deposit_amount', $amount);
                Session::put('konnect_deposit_id', $depositId);
                Session::put('konnect_payment_ref', $result['paymentRef'] ?? null);

                return redirect()->away($result['payUrl']);
            }

            return redirect()->back()->with('unsuccess', __('Payment initiation failed.'));

        } catch (\Exception $e) {
            return redirect()->back()->with('unsuccess', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $amount = Session::get('konnect_deposit_amount');
        $paymentRef = $request->payment_ref ?? Session::get('konnect_payment_ref');

        if (!$amount || !$paymentRef) {
            return redirect()->route('deposit.payment.cancle')->with('unsuccess', __('Session expired.'));
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])->get($this->baseUrl . '/payments/' . $paymentRef);

            $result = $response->json();

            if ($response->successful() && isset($result['payment']['status']) && $result['payment']['status'] === 'completed') {
                return $this->processDeposit($amount, $paymentRef);
            }

            return redirect()->route('deposit.payment.cancle')->with('unsuccess', __('Payment not completed.'));

        } catch (\Exception $e) {
            return redirect()->route('deposit.payment.cancle')->with('unsuccess', $e->getMessage());
        }
    }

    public function cancel()
    {
        Session::forget('konnect_deposit_amount');
        Session::forget('konnect_deposit_id');
        Session::forget('konnect_payment_ref');
        
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
        $deposit->method = 'Konnect';
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
        $trans->method = 'Konnect';
        $trans->save();

        Session::forget('konnect_deposit_amount');
        Session::forget('konnect_deposit_id');
        Session::forget('konnect_payment_ref');

        return redirect()->route('deposit.payment.return')->with('success', __('Deposit Successful'));
    }
}
