<?php

namespace App\Http\Controllers\Payment\Checkout;

use App\{
    Models\Cart,
    Models\Order,
    Models\PaymentGateway,
    Classes\XMerchMailer
};
use App\Models\Country;
use App\Models\Reward;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Session;
use OrderHelper;
use Illuminate\Support\Str;

class FlouciController extends CheckoutBaseControlller
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
        $input = $request->all();

        if($request->pass_check) {
            $auth = OrderHelper::auth_check($input);
            if(!$auth['auth_success']){
                return redirect()->back()->with('unsuccess', $auth['error_message']);
            }
        }

        if (!Session::has('cart')) {
            return redirect()->route('front.cart')
                ->with('unsuccess', __("You don't have any product to checkout."));
        }

        $total = $request->total;
        $orderNumber = Str::random(4).time();

        // Flouci API - Generate Payment
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/generate_payment', [
                'app_token' => $this->appToken,
                'app_secret' => $this->appSecret,
                'amount' => intval($total * 1000), // Flouci uses millimes
                'accept_url' => route('front.flouci.success'),
                'cancel_url' => route('front.flouci.cancel'),
                'decline_url' => route('front.flouci.cancel'),
                'session_timeout_secs' => 1200,
                'developer_tracking_id' => $orderNumber,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['result']['link'])) {
                // Store data in session
                Session::put('flouci_input', $input);
                Session::put('flouci_order_number', $orderNumber);
                Session::put('flouci_amount', $total);
                Session::put('flouci_payment_id', $result['result']['payment_id'] ?? null);

                // Redirect to Flouci payment page
                return redirect()->away($result['result']['link']);
            }

            return redirect()->back()->with('unsuccess', __('Flouci payment initiation failed. Please try again.'));

        } catch (\Exception $e) {
            return redirect()->back()->with('unsuccess', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $input = Session::get('flouci_input');
        $orderNumber = Session::get('flouci_order_number');
        $total = Session::get('flouci_amount');
        $paymentId = Session::get('flouci_payment_id');

        if (!$input || !$orderNumber) {
            return redirect()->route('front.payment.cancle')->with('unsuccess', __('Session expired.'));
        }

        // Verify payment with Flouci
        try {
            $response = Http::get($this->baseUrl . '/verify_payment/' . $paymentId, [
                'app_token' => $this->appToken,
                'app_secret' => $this->appSecret,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['result']['status']) && $result['result']['status'] === 'SUCCESS') {
                // Payment verified - Create Order
                return $this->createOrder($input, $orderNumber, $total, $paymentId);
            }

            return redirect()->route('front.payment.cancle')->with('unsuccess', __('Payment verification failed.'));

        } catch (\Exception $e) {
            return redirect()->route('front.payment.cancle')->with('unsuccess', $e->getMessage());
        }
    }

    public function cancel()
    {
        Session::forget('flouci_input');
        Session::forget('flouci_order_number');
        Session::forget('flouci_amount');
        Session::forget('flouci_payment_id');
        
        return redirect()->route('front.payment.cancle')->with('unsuccess', __('Payment was cancelled.'));
    }

    private function createOrder($input, $orderNumber, $total, $txnId)
    {
        $success_url = route('front.payment.return');

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        OrderHelper::license_check($cart);

        $t_oldCart = Session::get('cart');
        $t_cart = new Cart($t_oldCart);
        $new_cart = [];
        $new_cart['totalQty'] = $t_cart->totalQty;
        $new_cart['totalPrice'] = $t_cart->totalPrice;
        $new_cart['items'] = $t_cart->items;
        $new_cart = json_encode($new_cart);

        $temp_affilate_users = OrderHelper::product_affilate_check($cart);
        $affilate_users = $temp_affilate_users == null ? null : json_encode($temp_affilate_users);

        $order = new Order;
        $input['cart'] = $new_cart;
        $input['user_id'] = Auth::check() ? Auth::user()->id : NULL;
        $input['affilate_users'] = $affilate_users;
        $input['pay_amount'] = $total / $this->curr->value;
        $input['order_number'] = $orderNumber;
        $input['wallet_price'] = ($input['wallet_price'] ?? 0) / $this->curr->value;
        $input['payment_status'] = "Completed";
        $input['method'] = "Flouci";
        $input['txnid'] = $txnId;

        if(isset($input['tax_type']) && $input['tax_type'] == 'state_tax'){
            $input['tax_location'] = State::findOrFail($input['tax'])->state;
        } else if(isset($input['tax'])) {
            $input['tax_location'] = Country::findOrFail($input['tax'])->country_name;
        }
        $input['tax'] = Session::get('current_tax');

        if(isset($input['dp']) && $input['dp'] == 1){
            $input['status'] = 'completed';
        }

        if (Session::has('affilate')) {
            $val = $total / $this->curr->value;
            $val = $val / 100;
            $sub = $val * $this->gs->affilate_charge;
            if($temp_affilate_users != null){
                $t_sub = 0;
                foreach($temp_affilate_users as $t_cost){
                    $t_sub += $t_cost['charge'];
                }
                $sub = $sub - $t_sub;
            }
            if($sub > 0){
                OrderHelper::affilate_check(Session::get('affilate'), $sub, $input['dp'] ?? 0);
                $input['affilate_user'] = Session::get('affilate');
                $input['affilate_charge'] = $sub;
            }
        }

        $order->fill($input)->save();
        $order->tracks()->create(['title' => 'Pending', 'text' => 'You have successfully placed your order.']);
        $order->notifications()->create();

        // POD: Create Print Jobs
        OrderHelper::create_print_jobs($cart, $order);

        if(isset($input['coupon_id']) && $input['coupon_id'] != "") {
            OrderHelper::coupon_check($input['coupon_id']);
        }

        if(Auth::check() && $this->gs->is_reward == 1){
            $num = $order->pay_amount;
            $rewards = Reward::get();
            $smallest = [];
            foreach ($rewards as $i) {
                $smallest[$i->order_amount] = abs($i->order_amount - $num);
            }
            if(count($smallest) > 0) {
                asort($smallest);
                $final_reword = Reward::where('order_amount', key($smallest))->first();
                if($final_reword) {
                    Auth::user()->update(['reward' => (Auth::user()->reward + $final_reword->reward)]);
                }
            }
        }

        OrderHelper::size_qty_check($cart);
        OrderHelper::stock_check($cart);
        OrderHelper::vendor_order_check($cart, $order);

        Session::put('temporder', $order);
        Session::put('tempcart', $cart);
        Session::forget('cart');
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');
        Session::forget('flouci_input');
        Session::forget('flouci_order_number');
        Session::forget('flouci_amount');
        Session::forget('flouci_payment_id');

        if ($order->user_id != 0 && $order->wallet_price != 0) {
            OrderHelper::add_to_transaction($order, $order->wallet_price);
        }

        // Send emails
        $data = [
            'to' => $order->customer_email,
            'type' => "new_order",
            'cname' => $order->customer_name,
            'oamount' => "",
            'aname' => "",
            'aemail' => "",
            'wtitle' => "",
            'onumber' => $order->order_number,
        ];
        $mailer = new XMerchMailer();
        $mailer->sendAutoOrderMail($data, $order->id);

        $data = [
            'to' => $this->ps->contact_email,
            'subject' => "New Order Received!!",
            'body' => "Hello Admin!<br>Your store has received a new order via Flouci.<br>Order Number is ".$order->order_number.". Please login to your panel to check.<br>Thank you.",
        ];
        $mailer = new XMerchMailer();
        $mailer->sendCustomMail($data);

        return redirect($success_url);
    }
}
