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

class PaymeeController extends CheckoutBaseControlller
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

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/payments/create', [
                'amount' => floatval($total),
                'note' => $this->gs->title . ' Order #' . $orderNumber,
                'first_name' => $input['customer_name'] ?? 'Customer',
                'last_name' => '',
                'email' => $input['customer_email'] ?? '',
                'phone' => $input['customer_phone'] ?? '',
                'return_url' => route('front.paymee.success'),
                'cancel_url' => route('front.paymee.cancel'),
                'webhook_url' => route('front.paymee.notify'),
                'order_id' => $orderNumber,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['data']['paymentUrl'])) {
                Session::put('paymee_input', $input);
                Session::put('paymee_order_number', $orderNumber);
                Session::put('paymee_amount', $total);
                Session::put('paymee_token', $result['data']['token'] ?? null);

                return redirect()->away($result['data']['paymentUrl']);
            }

            $errorMsg = $result['message'] ?? 'Paymee payment initiation failed.';
            return redirect()->back()->with('unsuccess', __($errorMsg));

        } catch (\Exception $e) {
            return redirect()->back()->with('unsuccess', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $token = $request->token ?? Session::get('paymee_token');
        
        if (!$token) {
            return redirect()->route('front.payment.cancle')->with('unsuccess', __('Payment token missing.'));
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
            ])->get($this->baseUrl . '/payments/' . $token . '/check');

            $result = $response->json();

            if ($response->successful() && isset($result['data']['payment_status']) && $result['data']['payment_status'] === true) {
                $input = Session::get('paymee_input');
                $orderNumber = Session::get('paymee_order_number');
                $total = Session::get('paymee_amount');

                return $this->createOrder($input, $orderNumber, $total, $token);
            }

            return redirect()->route('front.payment.cancle')->with('unsuccess', __('Payment not completed.'));

        } catch (\Exception $e) {
            return redirect()->route('front.payment.cancle')->with('unsuccess', $e->getMessage());
        }
    }

    public function notify(Request $request)
    {
        // Paymee webhook
        return response()->json(['status' => 'received']);
    }

    public function cancel()
    {
        Session::forget('paymee_input');
        Session::forget('paymee_order_number');
        Session::forget('paymee_amount');
        Session::forget('paymee_token');
        
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
        $input['method'] = "Paymee";
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
        Session::forget('paymee_input');
        Session::forget('paymee_order_number');
        Session::forget('paymee_amount');
        Session::forget('paymee_token');

        if ($order->user_id != 0 && $order->wallet_price != 0) {
            OrderHelper::add_to_transaction($order, $order->wallet_price);
        }

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
            'body' => "Hello Admin!<br>Your store has received a new order via Paymee.<br>Order Number is ".$order->order_number.".<br>Thank you.",
        ];
        $mailer = new XMerchMailer();
        $mailer->sendCustomMail($data);

        return redirect($success_url);
    }
}
