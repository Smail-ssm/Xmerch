# XMerch Payment Gateway Architecture Analysis

## Overview

The XMerch platform uses a modular payment gateway architecture that can be easily extended for new providers like Flouci, D17, Konnect, etc.

---

## Database Structure

### `payment_gateways` Table

| Column         | Type    | Purpose                                                              |
| -------------- | ------- | -------------------------------------------------------------------- |
| `id`           | int     | Primary key                                                          |
| `title`        | string  | Display name (e.g., "Cash On Delivery")                              |
| `subtitle`     | string  | Short description shown to users                                     |
| `name`         | string  | Internal name                                                        |
| `type`         | string  | `manual` or `automatic`                                              |
| `keyword`      | string  | **Unique identifier** used in code (e.g., `cod`, `stripe`, `paypal`) |
| `information`  | json    | API credentials stored as JSON                                       |
| `details`      | text    | Additional info/instructions                                         |
| `currency_id`  | string  | Supported currency IDs (JSON array or `*` for all)                   |
| `checkout`     | boolean | Enable for checkout                                                  |
| `deposit`      | boolean | Enable for user deposits                                             |
| `subscription` | boolean | Enable for vendor subscriptions                                      |

### Example `information` JSON:

```json
{
  "client_id": "YOUR_CLIENT_ID",
  "client_secret": "YOUR_SECRET",
  "sandbox_check": 1,
  "text": "Pay securely via Flouci"
}
```

---

## Controller Architecture

### Base Controller

**File:** `App\Http\Controllers\Payment\Checkout\CheckoutBaseControlller.php`

```php
class CheckoutBaseControlller extends Controller
{
    protected $gs;   // GeneralSettings
    protected $ps;   // PageSettings
    protected $curr; // Current Currency

    public function __construct()
    {
        $this->gs = DB::table('generalsettings')->find(1);
        $this->ps = DB::table('pagesettings')->find(1);
        // Currency loaded via middleware
    }
}
```

**Provides:**

- Access to `$this->gs` (general settings)
- Access to `$this->ps` (page settings)
- Access to `$this->curr` (current currency)

### Payment Flow Patterns

#### Pattern A: Synchronous (COD, Stripe)

1. User submits form → `store()` method
2. Process payment immediately
3. Create Order
4. Redirect to success

```
User → store() → Process → Create Order → Success Page
```

#### Pattern B: Redirect-Based (PayPal, Flouci)

1. User submits form → `store()` method
2. Save data to Session
3. Redirect to external payment page
4. User completes payment
5. Gateway calls `notify()` webhook
6. Create Order in `notify()`
7. Redirect to success

```
User → store() → Session → Redirect to Gateway
                              ↓
         notify() ← Gateway Callback
              ↓
       Create Order → Success Page
```

---

## Files Per Payment Gateway (Checkout)

```
app/Http/Controllers/Payment/Checkout/
├── {Gateway}Controller.php     # Main controller
│   ├── __construct()           # Load API credentials
│   ├── store()                 # Initiate payment
│   └── notify()                # Handle callback (optional)
```

**Three Contexts Exist:**

1. `Payment/Checkout/` - Product purchases
2. `Payment/Deposit/` - User wallet deposits
3. `Payment/Subscription/` - Vendor plan payments

Each context has similar controllers but different order creation logic.

---

## Key Helper: `OrderHelper`

**Location:** `App\Helpers\OrderHelper`

Provides reusable functions:

- `auth_check($input)` - Verify user authentication
- `license_check($cart)` - Check digital license availability
- `coupon_check($coupon_id)` - Apply coupon
- `stock_check($cart)` - Reduce stock
- `vendor_order_check($cart, $order)` - Create vendor sub-orders
- `add_to_transaction($order, $amount)` - Record wallet transaction
- `create_print_jobs($cart, $order)` - POD print job creation

---

## PaymentGateway Model Methods

**File:** `App\Models\PaymentGateway.php`

| Method                   | Purpose                            |
| ------------------------ | ---------------------------------- |
| `convertAutoData()`      | Decode `information` JSON to array |
| `showKeyword()`          | Get the `keyword` or 'other'       |
| `showCheckoutLink()`     | Map keyword → checkout route       |
| `showSubscriptionLink()` | Map keyword → subscription route   |
| `showDepositLink()`      | Map keyword → deposit route        |
| `showForm()`             | Determine if card form needed      |

### Adding New Gateway to Model:

In `showCheckoutLink()`:

```php
}else if($data == 'flouci'){
    $link = route('front.flouci.submit');
}
```

---

## How to Add a New Gateway (Step-by-Step)

### Step 1: Database Entry

```sql
INSERT INTO payment_gateways (
    title, subtitle, keyword, type, information,
    currency_id, checkout, deposit, subscription
) VALUES (
    'Flouci',
    'Pay with Flouci Mobile Wallet',
    'flouci',
    'automatic',
    '{"app_token":"","app_secret":"","sandbox_check":1,"text":"Pay via Flouci"}',
    '["5"]',  -- TND currency ID
    1, 1, 1   -- Enable all contexts
);
```

### Step 2: Create Controller

**File:** `App\Http\Controllers\Payment\Checkout\FlouciController.php`

```php
<?php

namespace App\Http\Controllers\Payment\Checkout;

use App\{
    Models\Cart,
    Models\Order,
    Models\PaymentGateway,
    Classes\XMerchMailer
};
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

    public function __construct()
    {
        parent::__construct();
        $data = PaymentGateway::whereKeyword('flouci')->first();
        $paydata = $data->convertAutoData();
        $this->appToken = $paydata['app_token'];
        $this->appSecret = $paydata['app_secret'];
        $this->sandbox = $paydata['sandbox_check'] ?? 1;
    }

    public function store(Request $request)
    {
        // 1. Validate cart exists
        if (!Session::has('cart')) {
            return redirect()->route('front.cart')
                ->with('unsuccess', __("No products in cart."));
        }

        // 2. Auth check if needed
        $input = $request->all();
        if($request->pass_check) {
            $auth = OrderHelper::auth_check($input);
            if(!$auth['auth_success']){
                return redirect()->back()->with('unsuccess',$auth['error_message']);
            }
        }

        // 3. Prepare payment data
        $total = $request->total; // In TND
        $orderNumber = Str::random(4).time();

        // 4. Call Flouci API
        $baseUrl = $this->sandbox
            ? 'https://sandbox.flouci.com/api/v1'
            : 'https://api.flouci.com/v1';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->appToken,
            'Content-Type' => 'application/json',
        ])->post($baseUrl . '/payments', [
            'amount' => $total * 1000, // Flouci uses millimes
            'accept_url' => route('front.flouci.success'),
            'cancel_url' => route('front.flouci.cancel'),
            'webhook_url' => route('front.flouci.notify'),
            'order_id' => $orderNumber,
        ]);

        if ($response->successful() && isset($response['payment_url'])) {
            // 5. Store data in session
            Session::put('flouci_input', $input);
            Session::put('flouci_order_number', $orderNumber);
            Session::put('flouci_amount', $total);
            Session::put('flouci_payment_id', $response['payment_id']);

            // 6. Redirect to Flouci
            return redirect()->away($response['payment_url']);
        }

        return redirect()->back()->with('unsuccess', __('Payment initiation failed.'));
    }

    public function notify(Request $request)
    {
        // Verify webhook signature
        // ...

        $paymentId = $request->payment_id;
        $status = $request->status;

        if ($status === 'completed') {
            // Create order (similar to PayPal notify)
            // Use Session::get('flouci_input'), etc.
        }

        return response()->json(['status' => 'received']);
    }

    public function success(Request $request)
    {
        // User returned from Flouci successfully
        // Verify payment, create order, redirect to thank you
        $input = Session::get('flouci_input');
        $orderNumber = Session::get('flouci_order_number');
        $total = Session::get('flouci_amount');

        // Create order logic (copy from PayPal notify)
        // ...

        return redirect()->route('front.payment.return');
    }

    public function cancel()
    {
        Session::forget('flouci_input');
        return redirect()->route('front.payment.cancle');
    }
}
```

### Step 3: Add Routes

**File:** `routes/web.php`

```php
// Flouci Checkout
Route::post('/checkout/flouci-submit', 'Payment\Checkout\FlouciController@store')
    ->name('front.flouci.submit');
Route::get('/checkout/flouci-success', 'Payment\Checkout\FlouciController@success')
    ->name('front.flouci.success');
Route::get('/checkout/flouci-cancel', 'Payment\Checkout\FlouciController@cancel')
    ->name('front.flouci.cancel');
Route::post('/checkout/flouci-notify', 'Payment\Checkout\FlouciController@notify')
    ->name('front.flouci.notify');
```

### Step 4: Update PaymentGateway Model

**File:** `App\Models\PaymentGateway.php`

Add to `showCheckoutLink()`:

```php
}else if($data == 'flouci'){
    $link = route('front.flouci.submit');
}
```

Add to `showSubscriptionLink()` and `showDepositLink()` similarly.

Add to `showForm()`:

```php
$values = ['cod','voguepay',...,'flouci']; // Add flouci
```

### Step 5: Admin Configuration (Optional)

Create a view in `resources/views/admin/payment/` for managing Flouci credentials.

---

## Reusable Components Summary

| Component                 | Reuse Level | Notes                            |
| ------------------------- | ----------- | -------------------------------- |
| `CheckoutBaseControlller` | 100%        | Extend directly                  |
| `OrderHelper`             | 100%        | Call all methods as-is           |
| Session pattern           | 100%        | Store input, restore in callback |
| Order creation logic      | 90%         | Copy from existing controller    |
| Email sending             | 100%        | Use `XMerchMailer`               |
| PaymentGateway model      | Modify      | Add keyword mappings             |

---

## Implementation Priority for Tunisia

1. **Flouci** - Mobile-first, popular, good API
2. **Konnect** - Aggregator, minimal code changes
3. **D17** - Wide reach, SMS-based
4. **Paymee** - Card payments
5. **e-Dinar** - Government users

---

## Next Steps

1. Create database entries for each gateway
2. Create `FlouciController.php` (template above)
3. Add routes
4. Update `PaymentGateway.php` model
5. Test in sandbox mode
6. Go live

Ready to implement?
