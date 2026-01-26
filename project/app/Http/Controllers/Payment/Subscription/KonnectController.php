<?php

namespace App\Http\Controllers\Payment\Subscription;

use App\{
    Models\Subscription,
    Models\UserSubscription,
    Models\PaymentGateway,
    Classes\XMerchMailer
};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Session;

class KonnectController extends SubscriptionBaseController
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
        $user = $this->user;
        $subs = Subscription::findOrFail($request->subs_id);
        $paymentId = 'SUBS-' . time() . '-' . $user->id;

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/payments/init-payment', [
                'receiverWalletId' => $this->walletId,
                'amount' => intval($subs->price * 1000),
                'token' => 'TND',
                'type' => 'immediate',
                'description' => $subs->title . ' Subscription',
                'acceptedPaymentMethods' => ['wallet', 'bank_card', 'e-DINAR'],
                'lifespan' => 20,
                'checkoutForm' => true,
                'addPaymentFeesToAmount' => false,
                'orderId' => $paymentId,
                'successUrl' => route('user.konnect.success'),
                'failUrl' => route('user.konnect.cancel'),
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['payUrl'])) {
                Session::put('konnect_subs_id', $subs->id);
                Session::put('konnect_payment_ref', $result['paymentRef'] ?? null);
                Session::put('konnect_user_input', $request->all());

                return redirect()->away($result['payUrl']);
            }

            return redirect()->back()->with('unsuccess', __('Payment initiation failed.'));

        } catch (\Exception $e) {
            return redirect()->back()->with('unsuccess', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $subsId = Session::get('konnect_subs_id');
        $paymentRef = $request->payment_ref ?? Session::get('konnect_payment_ref');
        $input = Session::get('konnect_user_input');

        if (!$subsId || !$paymentRef) {
            return redirect()->route('user.payment.cancle')->with('unsuccess', __('Session expired.'));
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])->get($this->baseUrl . '/payments/' . $paymentRef);

            $result = $response->json();

            if ($response->successful() && isset($result['payment']['status']) && $result['payment']['status'] === 'completed') {
                return $this->activateSubscription($subsId, $paymentRef, $input);
            }

            return redirect()->route('user.payment.cancle')->with('unsuccess', __('Payment not completed.'));

        } catch (\Exception $e) {
            return redirect()->route('user.payment.cancle')->with('unsuccess', $e->getMessage());
        }
    }

    public function cancel()
    {
        Session::forget('konnect_subs_id');
        Session::forget('konnect_payment_ref');
        Session::forget('konnect_user_input');
        
        return redirect()->route('user.payment.cancle')->with('unsuccess', __('Payment was cancelled.'));
    }

    private function activateSubscription($subsId, $txnId, $input)
    {
        $user = $this->user;
        $subs = Subscription::findOrFail($subsId);

        $user->is_vendor = 2;
        $user->date = date('Y-m-d', strtotime(Carbon::now()->format('Y-m-d').' + '.$subs->days.' days'));
        $user->mail_sent = 1;
        $user->update($input);

        $sub = new UserSubscription;
        $data = json_decode(json_encode($subs), true);
        $data['user_id'] = $user->id;
        $data['subscription_id'] = $subs->id;
        $data['method'] = 'Konnect';
        $data['txnid'] = $txnId;
        $data['status'] = 1;
        $sub->fill($data)->save();

        $emailData = [
            'to' => $user->email,
            'type' => "vendor_accept",
            'cname' => $user->name,
            'oamount' => "",
            'aname' => "",
            'aemail' => "",
            'onumber' => "",
        ];
        $mailer = new XMerchMailer();
        $mailer->sendAutoMail($emailData);

        Session::forget('konnect_subs_id');
        Session::forget('konnect_payment_ref');
        Session::forget('konnect_user_input');

        return redirect()->route('user.payment.return')->with('success', __('Subscription Activated Successfully'));
    }
}
