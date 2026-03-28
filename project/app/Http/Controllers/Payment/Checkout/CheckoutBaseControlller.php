<?php

namespace App\Http\Controllers\Payment\Checkout;

use App\Http\Controllers\Controller;
use DB;
use App;
use Session;
use Illuminate\Http\Request;

class CheckoutBaseControlller extends Controller
{
    protected $gs;
    protected $ps;
    protected $curr;

    public function __construct()
    {

        $this->gs = DB::table('generalsettings')->find(1);

        $this->ps = DB::table('pagesettings')->find(1);

        $this->middleware(function ($request, $next) {

            if (Session::has('language')) 
            {
                $this->language = DB::table('languages')->find(Session::get('language'));
            }
            else
            {
                $this->language = DB::table('languages')->where('is_default','=',1)->first();
            }  

            App::setlocale($this->language->name);
            view()->share('langg', $this->language);
            if (Session::has('currency')) {
                $this->curr = DB::table('currencies')->find(Session::get('currency'));
            }
            else {
                $this->curr = DB::table('currencies')->where('is_default','=',1)->first();
            }

            if ($this->isTunisiaOnlyMode() && $request->isMethod('post') && $request->is('checkout/payment/*-submit')) {
                $validationError = $this->validateTunisiaCheckoutSubmit($request);
                if ($validationError !== null) {
                    return redirect()->route('front.checkout')->with('unsuccess', __($validationError));
                }
            }
    
            return $next($request);
        });
    }

    protected function isTunisiaOnlyMode()
    {
        return filter_var(env('TN_ONLY_MODE', false), FILTER_VALIDATE_BOOLEAN);
    }

    protected function tunisiaAllowedSubmitGateways()
    {
        return ['cod', 'flouci', 'konnect', 'paymee', 'd17', 'wallet', 'manual'];
    }

    protected function resolveSubmitGatewayKeyword(Request $request)
    {
        $parts = explode('/', trim($request->path(), '/'));
        $action = strtolower((string) end($parts));
        if (!str_ends_with($action, '-submit')) {
            return null;
        }

        $gateway = str_replace('-submit', '', $action);
        $aliases = [
            'authorize' => 'authorize.net',
            'molly' => 'mollie',
            'twocheckout' => '2checkout',
            'ssl' => 'sslcommerz',
        ];

        return $aliases[$gateway] ?? $gateway;
    }

    protected function isTunisiaCountry($country)
    {
        $normalized = strtolower(trim((string) $country));
        return in_array($normalized, ['tunisia', 'tunisie'], true);
    }

    protected function isValidTunisiaPhone($phone)
    {
        $normalized = preg_replace('/[^0-9+]/', '', (string) $phone);
        if (str_starts_with($normalized, '+216')) {
            $normalized = substr($normalized, 4);
        } elseif (str_starts_with($normalized, '00216')) {
            $normalized = substr($normalized, 5);
        }

        return preg_match('/^[2-9][0-9]{7}$/', $normalized) === 1;
    }

    protected function isValidTunisiaPostalCode($postalCode)
    {
        return preg_match('/^[0-9]{4}$/', trim((string) $postalCode)) === 1;
    }

    protected function validateTunisiaCheckoutSubmit(Request $request)
    {
        $gateway = $this->resolveSubmitGatewayKeyword($request);
        if ($gateway !== null && !in_array($gateway, $this->tunisiaAllowedSubmitGateways(), true)) {
            return 'Tunisia-only checkout: this payment method is blocked.';
        }

        $customerCountry = $request->input('customer_country', '');
        if (!$this->isTunisiaCountry($customerCountry)) {
            return 'Tunisia-only checkout: customer country must be Tunisia.';
        }

        $shippingCountry = trim((string) $request->input('shipping_country', ''));
        if ($shippingCountry !== '' && !$this->isTunisiaCountry($shippingCountry)) {
            return 'Tunisia-only checkout: shipping country must be Tunisia.';
        }

        if (!$this->isValidTunisiaPhone($request->input('customer_phone', ''))) {
            return 'Invalid Tunisia phone format for customer phone.';
        }

        if ($request->filled('shipping_phone') && !$this->isValidTunisiaPhone($request->input('shipping_phone'))) {
            return 'Invalid Tunisia phone format for shipping phone.';
        }

        if (!$this->isValidTunisiaPostalCode($request->input('customer_zip', ''))) {
            return 'Customer ZIP code must be a 4-digit Tunisia postal code.';
        }

        if ($request->filled('shipping_zip') && !$this->isValidTunisiaPostalCode($request->input('shipping_zip'))) {
            return 'Shipping ZIP code must be a 4-digit Tunisia postal code.';
        }

        return null;
    }
}
