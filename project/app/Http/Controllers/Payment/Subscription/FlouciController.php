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

class FlouciController extends SubscriptionBaseController
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
        $user = $this->user;
        $subs = Subscription::findOrFail($request->subs_id);
        $paymentId = 'SUBS-' . time() . '-' . $user->id;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/generate_payment', [
                'app_token' => $this->appToken,
                'app_secret' => $this->appSecret,
                'amount' => intval($subs->price * 1000),
                'accept_url' => route('user.flouci.success'),
                'cancel_url' => route('user.flouci.cancel'),
                'decline_url' => route('user.flouci.cancel'),
                'session_timeout_secs' => 1200,
                'developer_tracking_id' => $paymentId,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['result']['link'])) {
                Session::put('flouci_subs_id', $subs->id);
                Session::put('flouci_payment_id', $result['result']['payment_id'] ?? null);
                Session::put('flouci_user_input', $request->all());

                return redirect()->away($result['result']['link']);
            }

            return redirect()->back()->with('unsuccess', __('Payment initiation failed.'));

        } catch (\Exception $e) {
            return redirect()->back()->with('unsuccess', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $subsId = Session::get('flouci_subs_id');
        $paymentId = Session::get('flouci_payment_id');
        $input = Session::get('flouci_user_input');

        if (!$subsId || !$paymentId) {
            return redirect()->route('user.payment.cancle')->with('unsuccess', __('Session expired.'));
        }

        try {
            $response = Http::get($this->baseUrl . '/verify_payment/' . $paymentId, [
                'app_token' => $this->appToken,
                'app_secret' => $this->appSecret,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['result']['status']) && $result['result']['status'] === 'SUCCESS') {
                return $this->activateSubscription($subsId, $paymentId, $input);
            }

            return redirect()->route('user.payment.cancle')->with('unsuccess', __('Payment verification failed.'));

        } catch (\Exception $e) {
            return redirect()->route('user.payment.cancle')->with('unsuccess', $e->getMessage());
        }
    }

    public function cancel()
    {
        Session::forget('flouci_subs_id');
        Session::forget('flouci_payment_id');
        Session::forget('flouci_user_input');
        
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
        $data['method'] = 'Flouci';
        $data['txnid'] = $txnId;
        $data['status'] = 1;
        $sub->fill($data)->save();

        // Send email
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

        Session::forget('flouci_subs_id');
        Session::forget('flouci_payment_id');
        Session::forget('flouci_user_input');

        return redirect()->route('user.payment.return')->with('success', __('Subscription Activated Successfully'));
    }
}
