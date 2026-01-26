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

class PaymeeController extends SubscriptionBaseController
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
        $user = $this->user;
        $subs = Subscription::findOrFail($request->subs_id);
        $paymentId = 'SUBS-' . time() . '-' . $user->id;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/payments/create', [
                'amount' => floatval($subs->price),
                'note' => $subs->title . ' Subscription',
                'first_name' => $user->name,
                'last_name' => '',
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'return_url' => route('user.paymee.success'),
                'cancel_url' => route('user.paymee.cancel'),
                'order_id' => $paymentId,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['data']['paymentUrl'])) {
                Session::put('paymee_subs_id', $subs->id);
                Session::put('paymee_token', $result['data']['token'] ?? null);
                Session::put('paymee_user_input', $request->all());

                return redirect()->away($result['data']['paymentUrl']);
            }

            return redirect()->back()->with('unsuccess', __('Payment initiation failed.'));

        } catch (\Exception $e) {
            return redirect()->back()->with('unsuccess', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $subsId = Session::get('paymee_subs_id');
        $token = $request->token ?? Session::get('paymee_token');
        $input = Session::get('paymee_user_input');

        if (!$subsId || !$token) {
            return redirect()->route('user.payment.cancle')->with('unsuccess', __('Session expired.'));
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
            ])->get($this->baseUrl . '/payments/' . $token . '/check');

            $result = $response->json();

            if ($response->successful() && isset($result['data']['payment_status']) && $result['data']['payment_status'] === true) {
                return $this->activateSubscription($subsId, $token, $input);
            }

            return redirect()->route('user.payment.cancle')->with('unsuccess', __('Payment not completed.'));

        } catch (\Exception $e) {
            return redirect()->route('user.payment.cancle')->with('unsuccess', $e->getMessage());
        }
    }

    public function cancel()
    {
        Session::forget('paymee_subs_id');
        Session::forget('paymee_token');
        Session::forget('paymee_user_input');
        
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
        $data['method'] = 'Paymee';
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

        Session::forget('paymee_subs_id');
        Session::forget('paymee_token');
        Session::forget('paymee_user_input');

        return redirect()->route('user.payment.return')->with('success', __('Subscription Activated Successfully'));
    }
}
