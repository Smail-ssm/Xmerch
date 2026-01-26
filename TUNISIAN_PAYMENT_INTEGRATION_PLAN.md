# Tunisian Payment Gateway Integration Plan

## Overview

This document outlines the implementation plan for integrating Tunisian payment services into XMerch, enabling local designers and customers to pay using familiar methods.

---

## Target Payment Gateways

### 1. **Flouci** (Priority: HIGH)

- **Type:** Mobile Wallet / Online Payments
- **API:** REST API with OAuth2
- **Documentation:** https://flouci.com/developers
- **Features:**
  - QR Code payments
  - Mobile app integration
  - Instant transfers
- **Fees:** ~2.5% per transaction
- **Integration Effort:** Medium

### 2. **D17** (Priority: HIGH)

- **Type:** Mobile Money (Ooredoo Tunisia)
- **API:** REST API
- **Documentation:** Contact Ooredoo for API access
- **Features:**
  - SMS confirmation
  - USSD payments
  - Wide mobile user base
- **Fees:** ~2-3% per transaction
- **Integration Effort:** Medium

### 3. **Konnect** (Priority: MEDIUM)

- **Type:** Payment Gateway Aggregator
- **API:** REST API
- **Documentation:** https://konnect.network/docs
- **Features:**
  - Supports cards + e-wallets
  - Subscription billing
  - Multi-currency (TND focus)
- **Fees:** ~2.9% + fixed fee
- **Integration Effort:** Easy (well-documented)

### 4. **e-Dinar** (Priority: MEDIUM)

- **Type:** Government-backed Digital Currency (La Poste Tunisienne)
- **API:** SOAP/REST (older API)
- **Documentation:** https://www.poste.tn/e-dinar.php
- **Features:**
  - Trusted by government users
  - Postal network integration
- **Fees:** Low (~1.5%)
- **Integration Effort:** Medium-High (older API style)

### 5. **Paymee** (Priority: MEDIUM)

- **Type:** Online Payment Gateway
- **API:** REST API
- **Documentation:** https://paymee.tn/developers
- **Features:**
  - Card payments (local + international)
  - Bank transfers
  - E-wallet top-ups
- **Fees:** ~3%
- **Integration Effort:** Easy

### 6. **Sobflous** (Priority: LOW)

- **Type:** Mobile Wallet
- **API:** Limited public documentation
- **Features:**
  - P2P transfers
  - Bill payments
- **Integration Effort:** High (requires partnership)

### 7. **Cash on Delivery (COD)** (Priority: HIGH)

- **Type:** Offline Payment
- **Already Implemented:** Yes (verify functionality)
- **Features:**
  - No technical integration needed
  - Highest trust for new customers
- **Fees:** Courier handling fees only

---

## Implementation Phases

### Phase 1: Foundation (Week 1)

1. **Audit existing payment infrastructure**
   - Review `App\Http\Controllers\Payment\` structure
   - Identify reusable patterns (Stripe, PayPal, etc.)
   - Check `PaymentGateway` model schema

2. **Create base Tunisian gateway controller**
   - `App\Http\Controllers\Payment\Checkout\TunisianBaseController.php`
   - Common methods: `initPayment()`, `verifyPayment()`, `handleCallback()`

3. **Database migration for gateway configs**
   - Add Flouci, D17, Konnect, Paymee, e-Dinar to `payment_gateways` table
   - Store API keys, secrets, sandbox mode flags

### Phase 2: Flouci Integration (Week 2)

1. **Register for Flouci Developer Account**
   - Get `app_token` and `app_secret`
   - Configure webhook URL

2. **Create Controller**
   - `App\Http\Controllers\Payment\Checkout\FlouciController.php`
   - Methods: `store()`, `notify()`, `success()`, `cancel()`

3. **Create Routes**

   ```php
   Route::post('/checkout/flouci-submit', 'Payment\Checkout\FlouciController@store')->name('checkout.flouci.submit');
   Route::get('/checkout/flouci-notify', 'Payment\Checkout\FlouciController@notify')->name('checkout.flouci.notify');
   ```

4. **Add to Checkout UI**
   - Payment method selector in checkout view
   - Flouci button/QR code display

### Phase 3: D17 Integration (Week 3)

1. **Partner with Ooredoo Tunisia**
   - Apply for merchant API access
   - Get sandbox credentials

2. **Create Controller**
   - `App\Http\Controllers\Payment\Checkout\D17Controller.php`

3. **SMS/USSD Flow**
   - Initiate payment request
   - User confirms via phone
   - Webhook receives confirmation

### Phase 4: Konnect Integration (Week 3-4)

1. **Register at Konnect**
   - Dashboard: https://konnect.network
   - Get API keys

2. **Create Controller**
   - `App\Http\Controllers\Payment\Checkout\KonnectController.php`

3. **Unified checkout flow**
   - Konnect handles multiple payment methods internally

### Phase 5: e-Dinar & Paymee (Week 4-5)

1. **e-Dinar**
   - Contact La Poste for integration guide
   - May require SOAP client

2. **Paymee**
   - Similar to Flouci integration
   - REST API with redirect flow

### Phase 6: Testing & Go-Live (Week 5-6)

1. **Sandbox testing for all gateways**
2. **User acceptance testing**
3. **Switch to production credentials**
4. **Monitor first transactions**

---

## File Structure (Proposed)

```
app/Http/Controllers/Payment/
├── Checkout/
│   ├── FlouciController.php          # NEW
│   ├── D17Controller.php             # NEW
│   ├── KonnectController.php         # NEW
│   ├── PaymeeController.php          # NEW
│   ├── EDinarController.php          # NEW
│   ├── CashOnDeliveryController.php  # EXISTS
│   ├── StripeController.php          # EXISTS
│   └── PaypalController.php          # EXISTS
├── Deposit/
│   └── (same structure for wallet deposits)
└── Subscription/
    └── (same structure for plan payments)
```

---

## Admin Configuration UI

Add to `Admin > Payment Settings`:

- Toggle each gateway ON/OFF
- Input API credentials
- Set sandbox/live mode
- Configure webhook URLs (auto-generated)

---

## Priority Order (Recommended)

1. **COD** - Already works, verify it's enabled
2. **Flouci** - Most popular, good docs
3. **Konnect** - Aggregator, covers multiple methods
4. **D17** - Wide mobile reach
5. **Paymee** - Card payments
6. **e-Dinar** - Government users

---

## Quick Start: Flouci API Example

```php
// Initialize payment
$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . config('services.flouci.token'),
])->post('https://api.flouci.com/v1/payments', [
    'amount' => $order->total * 1000, // Amount in millimes
    'accept_url' => route('checkout.flouci.success'),
    'cancel_url' => route('checkout.flouci.cancel'),
    'webhook_url' => route('checkout.flouci.notify'),
    'order_id' => $order->order_number,
]);

return redirect($response['payment_url']);
```

---

## Next Steps

1. **You:** Register for Flouci and Konnect developer accounts
2. **Me:** Create the base controller and Flouci integration
3. **You:** Provide API credentials (I'll show you where to store them securely)
4. **Me:** Implement remaining gateways

Ready to start with Flouci?
