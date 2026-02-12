# XMerch: Complete Platform Architecture & Workflow Documentation

> **Version**: 2.0 (February 2026)  
> **Author**: System Analysis  
> **Scope**: Full end-to-end platform documentation covering all user journeys, technical implementations, and operational workflows.

---

## Table of Contents

1. [Executive Summary](#executive-summary)
2. [Platform Architecture Overview](#platform-architecture-overview)
3. [User Roles & Permission System](#user-roles--permission-system)
4. [Designer (Vendor) Module](#designer-vendor-module)
5. [Customer Journey](#customer-journey)
6. [Order Processing Pipeline](#order-processing-pipeline)
7. [Manufacturing & Print Production](#manufacturing--print-production)
8. [Shipping & Fulfillment](#shipping--fulfillment)
9. [Analytics & Reporting](#analytics--reporting)
10. [Payment Gateway Integration](#payment-gateway-integration)
11. [Database Schema Reference](#database-schema-reference)
12. [API & Route Reference](#api--route-reference)
13. [Configuration & Settings](#configuration--settings)
14. [Troubleshooting & Edge Cases](#troubleshooting--edge-cases)

---

## Executive Summary

XMerch is a **Print-on-Demand (POD) E-Commerce Platform** built on Laravel 8. It enables designers to upload artwork, which is then manufactured on-demand when customers place orders. The platform handles the complete lifecycle from design upload to product delivery, with specialized dashboards for different operational roles.

### Key Differentiators
- **Zero-Inventory Model**: Products are manufactured only after purchase
- **Role-Based Manufacturing**: Separate interfaces for printers vs. operations managers
- **Integrated Design System**: Optional visual customization for end customers
- **Multi-Gateway Payments**: Support for international (Stripe, PayPal) and local (Flouci, Konnect, Paymee) payment methods

---

## Platform Architecture Overview

### Technology Stack

| Layer | Technology | Version |
|-------|------------|---------|
| Backend Framework | Laravel | 8.65 |
| PHP Version | PHP | 7.3 - 8.0 |
| Database | MySQL | 5.7+ |
| Frontend | Blade Templates + jQuery | - |
| Image Processing | Intervention/Image | 2.5 |
| PDF Generation | Barryvdh/DomPDF | 0.8.6 |
| DataTables | Yajra/DataTables | 9.10 |

### Directory Structure

```
project/
├── app/
│   ├── Classes/           # Custom utility classes (XMerchMailer)
│   ├── Helpers/           # Global helpers (OrderHelper, PriceHelper)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/     # Admin panel controllers (64 files)
│   │   │   ├── Front/     # Frontend controllers
│   │   │   ├── Payment/   # Payment gateway controllers
│   │   │   ├── User/      # User account controllers
│   │   │   └── Vendor/    # Designer/vendor controllers
│   │   └── Middleware/    # Auth, permissions, locale
│   ├── Models/            # Eloquent models (73 files)
│   └── Providers/         # Service providers
├── database/
│   ├── migrations/        # Schema migrations
│   └── seeders/           # Data seeders
├── resources/
│   └── views/
│       ├── admin/         # Admin panel views (57 subdirectories)
│       ├── frontend/      # Customer-facing views
│       ├── vendor/        # Designer panel views
│       └── layouts/       # Base layouts
└── routes/
    ├── web.php            # Main routes (1773 lines)
    └── printjob_routes.php # POD-specific routes
```

---

## User Roles & Permission System

### Role Architecture

The system uses a **Section-Based Permission Model** stored in the `roles` table:

```sql
CREATE TABLE roles (
    id INT PRIMARY KEY,
    name VARCHAR(255),
    section TEXT  -- Comma-separated permissions
);
```

### Permission Sections Available

| Section Key | Description | Typical Routes |
|-------------|-------------|----------------|
| `orders` | Order management, sales dashboard | `/admin/orders/*` |
| `products` | Product CRUD, catalog management | `/admin/products/*` |
| `categories` | Category hierarchy management | `/admin/category/*` |
| `print_production` | Printer queue, print actions | `/admin/printer/*` |
| `manufacturing` | Analytics, capacity planning | `/admin/manufacturing/*` |
| `earning` | Revenue reports, commission | `/admin/earning/*` |
| `customer_deposits` | Wallet management | `/admin/deposits/*` |
| `affilate_products` | Affiliate product management | `/admin/affiliate/*` |
| `set_coupons` | Coupon/discount management | `/admin/coupon/*` |

### Pre-Configured Roles

| Role Name | Sections | Use Case |
|-----------|----------|----------|
| **Super Admin** | `role_id = 0` (bypass) | Full system access |
| **Sales Manager** | `orders, earning, customer_deposits` | Financial operations |
| **Inventory Manager** | `categories, products, affilate_products` | Catalog management |
| **Printer Operator** | `print_production` | Production floor worker |
| **Manufacturing Lead** | `manufacturing` | Production analytics |
| **Operations Manager** | `print_production, manufacturing` | Full production oversight |

### Permission Check Implementation

**Middleware** (`app/Http/Middleware/Permissions.php`):
```php
public function handle($request, Closure $next, $section)
{
    $user = Auth::guard('admin')->user();
    if ($user->IsSuper()) return $next($request);
    if ($user->sectionCheck($section)) return $next($request);
    return redirect()->route('admin.dashboard')->with('error', 'Unauthorized');
}
```

**Model Method** (`app/Models/Admin.php`):
```php
public function sectionCheck($section)
{
    $sections = explode(',', $this->role->section);
    return in_array(trim($section), array_map('trim', $sections));
}
```

---

## Designer (Vendor) Module

### Registration Flow

**Route**: `POST /user/register` with `vendor=1`  
**Controller**: `User\RegisterController@register`

```
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│  Registration   │ ──▶ │  Email Verify    │ ──▶ │   Subscribe     │
│     Form        │     │  (Optional)      │     │   to Plan       │
└─────────────────┘     └──────────────────┘     └─────────────────┘
                                                          │
                                                          ▼
                                                 ┌─────────────────┐
                                                 │  Vendor Panel   │
                                                 │    Active       │
                                                 └─────────────────┘
```

**Database Changes**:
- `users.is_vendor` = 1
- `users.shop_name` = Unique store identifier
- Links to `user_subscriptions` for plan limits

### Subscription Plans

Defined in `subscriptions` table, controlling:
- `allowed_products`: Maximum products vendor can create
- `duration`: Subscription validity in days
- `price`: Subscription cost

### Product Creation Workflow

**Route**: `GET /vendor/products/physical/create?mode=pod`  
**Controller**: `Vendor\ProductController@create`

#### Standard vs POD Product

| Attribute | Standard Product | POD Product |
|-----------|------------------|-------------|
| `is_pod` | 0 | 1 |
| `stock` | Manual inventory count | Ignored (infinite) |
| `print_file` | NULL | High-res design file |
| `production_cap` | NULL | Daily manufacturing limit |
| `quality_tier` | NULL | `standard`, `premium`, `deluxe` |

#### Product Creation Code Flow

```php
// Vendor\ProductController@store (excerpt)
public function store(Request $request)
{
    // 1. Subscription limit check
    $package = $user->subscribes()->latest('id')->first();
    if ($prods >= $package->allowed_products) {
        return error('Product limit reached');
    }

    // 2. Handle photo upload (supports base64 from designer tool)
    if (strpos($request->photo, ';base64,') !== false) {
        $image_base64 = base64_decode($image_parts[1]);
        file_put_contents('assets/images/products/'.$image_name, $image_base64);
    }

    // 3. Handle print file (high-res)
    if ($file = $request->file('file')) {
        $file->move('assets/files', $name);
        $input['file'] = $name;
    }

    // 4. POD-specific fields
    $input['is_pod'] = $request->has('is_pod') ? 1 : 0;
    $input['production_cap'] = $request->production_cap;
    $input['quality_tier'] = $request->quality_tier ?? 'standard';

    // 5. Save product
    $data->fill($input)->save();
}
```

### Designer Dashboard Features

| Feature | Route | Description |
|---------|-------|-------------|
| Product List | `/vendor/products` | View/edit all products |
| Sales Analytics | `/vendor/total/earning` | Revenue tracking |
| Order History | `/vendor/orders` | Orders containing their products |
| Withdraw Funds | `/vendor/withdraw` | Request payout |
| Shop Settings | `/vendor/profile` | Banner, social links |

---

## Customer Journey

### Phase 1: Product Discovery

**Routes**:
- Homepage: `GET /`
- Category: `GET /category/{slug}`
- Search: `GET /search?q={term}`
- Product Detail: `GET /item/{slug}`

**Controller**: `Front\CatalogController`, `Front\ProductDetailsController`

### Phase 2: Product Customization (POD)

If the product has `is_pod = 1` and customization is enabled:

```
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│  View Product   │ ──▶ │  Open Designer   │ ──▶ │  Save Canvas    │
│     Page        │     │  (Fabric.js)     │     │  as Base64      │
└─────────────────┘     └──────────────────┘     └─────────────────┘
```

**Design Data Storage**:
- Canvas JSON → `order.design_data`
- Exported Image → `order.design_image`

### Phase 3: Cart & Checkout

**Cart Model** (`app/Models/Cart.php`):
```php
class Cart
{
    public $items = [];      // Product items with quantities
    public $totalQty = 0;    // Total item count
    public $totalPrice = 0;  // Total price
    
    public function add($item, $id, $size, $color, $size_qty, $size_price, ...) {
        // Adds item to cart with all variant info
        // POD items include quality_tier
    }
}
```

**Checkout Process**:

```
┌─────────────┐   ┌──────────────┐   ┌──────────────┐   ┌──────────────┐
│    Cart     │ ▶ │   Checkout   │ ▶ │   Payment    │ ▶ │    Order     │
│    Page     │   │     Form     │   │   Gateway    │   │   Created    │
└─────────────┘   └──────────────┘   └──────────────┘   └──────────────┘
```

---

## Order Processing Pipeline

### Order Creation

**Trigger**: Successful payment callback  
**Controller**: `Payment\Checkout\*Controller@store`

**Core Logic** (from `CashOnDeliveryController` as example):

```php
public function store(Request $request)
{
    // 1. Create order record
    $order = new Order;
    $order->fill($input)->save();
    
    // 2. Create order tracking entry
    $order->tracks()->create([
        'title' => 'Pending',
        'text' => 'Order placed successfully'
    ]);
    
    // 3. Create admin notification
    $order->notifications()->create();
    
    // 4. POD: Create print jobs for POD items
    OrderHelper::create_print_jobs($cart, $order);
    
    // 5. Update coupon usage
    if ($input['coupon_id'] != "") {
        OrderHelper::coupon_check($input['coupon_id']);
    }
    
    // 6. Vendor order entries
    OrderHelper::vendor_order_check($cart, $order);
    
    // 7. Send confirmation email
    $mailer->sendAutoOrderMail($data, $order->id);
}
```

### Order Status Lifecycle

```
┌─────────────┐   ┌──────────────┐   ┌──────────────┐   ┌──────────────┐
│   pending   │ ▶ │  processing  │ ▶ │  completed   │   │   declined   │
└─────────────┘   └──────────────┘   └──────────────┘   └──────────────┘
                                                               ▲
                                            (if payment fails) │
```

### Print Status Lifecycle (POD Orders)

```
┌───────────────┐   ┌──────────────┐   ┌──────────────┐   ┌──────────────┐
│ pending_print │ ▶ │   printing   │ ▶ │   printed    │ ▶ │   shipped    │
└───────────────┘   └──────────────┘   └──────────────┘   └──────────────┘
       │                   │                  │                   │
       │                   │                  │                   │
       ▼                   ▼                  ▼                   ▼
   Order placed      Printer starts      Ready for           Handed to
   by customer       production          packaging           courier
```

### Order Model Scopes

```php
// app/Models/Order.php
public function scopePendingPrint($query)
{
    return $query->where('print_status', 'pending_print');
}

public function scopePrinting($query)
{
    return $query->where('print_status', 'printing');
}

public function scopePrinted($query)
{
    return $query->where('print_status', 'printed');
}

public function scopeShippedPrint($query)
{
    return $query->where('print_status', 'shipped');
}
```

---

## Manufacturing & Print Production

### Printer Dashboard

**Route**: `GET /admin/printer/dashboard`  
**Controller**: `Admin\PrinterController@dashboard`

**Dashboard Statistics**:
```php
$stats = [
    'pending' => Order::pendingPrint()->where('status', 'processing')->count(),
    'eligible' => $eligibleCount, // Products within production cap
    'printing' => Order::printing()->count(),
    'printed' => Order::printed()->count(),
    'shipped_today' => Order::shippedPrint()->whereDate('shipped_at', today())->count(),
];
```

### Print Queue Management

**Route**: `GET /admin/printer/queue`  
**View**: `admin.printer.queue`

| Column | Description |
|--------|-------------|
| Order # | Clickable link to order details |
| Customer | Name + contact info |
| Products | List of items with quantities |
| Design | Preview of custom design (if any) |
| Actions | Start Print, View Details |

### Production Actions

| Action | Route | Effect |
|--------|-------|--------|
| Start Printing | `POST /admin/printer/{id}/start` | Sets `print_status = printing`, records `printer_id` |
| Mark Printed | `POST /admin/printer/{id}/printed` | Sets `print_status = printed`, records `printed_at` |
| Mark Shipped | `POST /admin/printer/{id}/shipped` | Sets `print_status = shipped`, `status = completed` |

### Production Capacity System

**Check Logic** (`app/Models/Order.php`):
```php
public function isEligibleForProduction()
{
    $cart = json_decode($this->cart, true);
    foreach ($cart['items'] as $item) {
        $product = Product::find($item['item']['id']);
        if ($product && $product->is_pod) {
            $todayCount = PrintJob::where('product_id', $product->id)
                ->whereDate('created_at', today())
                ->sum('quantity');
            if ($todayCount + $item['qty'] > $product->production_cap) {
                return false;
            }
        }
    }
    return true;
}
```

### PrintJob Model (Advanced Tracking)

**Note**: This model exists but routes are not active in `web.php`.

```php
// app/Models/PrintJob.php
class PrintJob extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'design_file', 'mockup_preview',
        'quantity', 'quality_tier', 'status', 'priority',
        'printer_id', 'started_at', 'completed_at', 'notes', 'failure_reason'
    ];

    // Status: queued, printing, completed, failed, on_hold
}
```

---

## Shipping & Fulfillment

### Shipping Label Generation

**Route**: `GET /admin/printer/{id}/label`  
**Controller**: `Admin\PrinterController@shippingLabel`

Generates a printable label with:
- Recipient address
- Order number barcode
- Product list
- Shipping method

### Order Completion Trigger

When "Mark Shipped" is clicked:

```php
public function markShipped(Request $request, $id)
{
    $order = Order::findOrFail($id);
    $order->print_status = Order::PRINT_STATUS_SHIPPED;
    $order->shipped_at = now();
    $order->status = 'completed';
    $order->save();
    
    // Trigger: Send shipping notification email
    // Trigger: Update vendor earnings if applicable
}
```

---

## Analytics & Reporting

### Manufacturing Analytics Dashboard

**Route**: `GET /admin/manufacturing/analytics`  
**Controller**: `Admin\ManufacturingController@analytics`

**Data Aggregation**:
```php
public function analytics(Request $request)
{
    $month = $request->input('month', now()->month);
    $year = $request->input('year', now()->year);
    
    // Get all printed orders for period
    $manufacturedOrders = Order::printed()
        ->whereBetween('printed_at', [$monthStart, $monthEnd])
        ->get();
    
    // Aggregate by product
    foreach ($manufacturedOrders as $order) {
        $cart = json_decode($order->cart, true);
        foreach ($cart['items'] as $item) {
            $pid = $item['item']['id'];
            $manufacturedProducts[$pid]['total_qty'] += $item['qty'];
            $manufacturedProducts[$pid]['orders'][] = [
                'order_number' => $order->order_number,
                'qty' => $item['qty'],
                'date' => $order->printed_at->format('Y-m-d H:i')
            ];
        }
    }
    
    return view('admin.manufacturing.analytics', [
        'manufacturedProducts' => $manufacturedProducts,
        'periodInfo' => [
            'month_name' => $monthStart->translatedFormat('F Y'),
            'total_orders' => $manufacturedOrders->count(),
            'total_units' => $totalUnits
        ]
    ]);
}
```

### Key Performance Indicators (KPIs)

| Metric | Calculation | Purpose |
|--------|-------------|---------|
| Total Runs | Count of orders printed | Volume indicator |
| Units Produced | Sum of all item quantities | Output measurement |
| Product Types | Count of unique products | SKU diversity |
| Efficiency | Units / Runs | Batch size indicator |
| Utilization | Daily production / Capacity | Capacity planning |

---

## Payment Gateway Integration

### Supported Gateways

| Gateway | Type | Currency | Controller |
|---------|------|----------|------------|
| PayPal | International | USD, EUR | `PaypalController` |
| Stripe | International | Multi | `StripeController` |
| Razorpay | India | INR | `RazorpayController` |
| Mollie | Europe | EUR | `MollieController` |
| Flouci | Tunisia | TND | `FlouciController` |
| Konnect | Tunisia | TND | `KonnectController` |
| Paymee | Tunisia | TND | `PaymeeController` |
| Cash on Delivery | Local | Any | `CashOnDeliveryController` |

### Gateway Configuration

**Model**: `PaymentGateway`  
**Table**: `payment_gateways`

```php
// Retrieve active gateway
$gateway = PaymentGateway::whereKeyword('flouci')->first();
$config = $gateway->convertAutoData(); // Returns decoded JSON config
```

### Payment Flow Pattern

```
Frontend Checkout
       │
       ▼
┌──────────────────┐
│  Gateway Submit  │  POST /checkout/payment/{gateway}-submit
└──────────────────┘
       │
       ├──── Synchronous (Stripe, Authorize) ────▶ Direct charge, redirect to success
       │
       └──── Redirect (PayPal, Flouci) ────▶ External payment page
                                                      │
                                                      ▼
                                              ┌──────────────────┐
                                              │  Callback/Notify │
                                              └──────────────────┘
                                                      │
                                                      ▼
                                              ┌──────────────────┐
                                              │   Order Created  │
                                              └──────────────────┘
```

---

## Database Schema Reference

### Core Tables

#### `orders`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| user_id | INT | Customer (nullable for guests) |
| order_number | VARCHAR | Unique order identifier |
| cart | JSON/TEXT | Serialized cart data |
| status | ENUM | pending, processing, completed, declined |
| print_status | VARCHAR | pending_print, printing, printed, shipped |
| printed_at | TIMESTAMP | When marked as printed |
| shipped_at | TIMESTAMP | When marked as shipped |
| printer_id | INT | Staff who processed |
| design_data | TEXT | Canvas JSON for custom designs |
| design_image | VARCHAR | Exported design image path |
| pay_amount | DECIMAL | Total paid |
| method | VARCHAR | Payment method used |

#### `products`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| user_id | INT | Vendor who created (0 = admin) |
| name | VARCHAR | Product name |
| slug | VARCHAR | URL-friendly identifier |
| photo | VARCHAR | Display image |
| is_pod | TINYINT | 1 = Print on Demand |
| print_file | VARCHAR | High-res design file for printing |
| production_cap | INT | Daily manufacturing limit |
| quality_tier | VARCHAR | standard, premium, deluxe |
| price | DECIMAL | Base price |
| stock | INT | Inventory (ignored for POD) |
| status | TINYINT | 1 = active, 0 = inactive |

#### `print_jobs`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| order_id | INT | Parent order |
| product_id | INT | Product being printed |
| quantity | INT | Number of units |
| quality_tier | VARCHAR | Print quality |
| status | VARCHAR | queued, printing, completed, failed |
| printer_id | INT | Assigned printer |
| priority | INT | 1=high, 2=medium, 3=low |
| started_at | TIMESTAMP | When printing began |
| completed_at | TIMESTAMP | When printing finished |
| failure_reason | TEXT | If failed, why |

#### `roles`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| name | VARCHAR | Role display name |
| section | TEXT | Comma-separated permissions |

---

## API & Route Reference

### Admin Routes Summary

| Route Group | Middleware | Count | Purpose |
|-------------|------------|-------|---------|
| `/admin/orders/*` | `permissions:orders` | 25 | Order management |
| `/admin/products/*` | `permissions:products` | 20 | Product CRUD |
| `/admin/printer/*` | `permissions:print_production` | 12 | Print queue |
| `/admin/manufacturing/*` | `permissions:manufacturing` | 8 | Analytics |
| `/admin/category/*` | `permissions:categories` | 10 | Categories |

### Vendor Routes Summary

| Route | Method | Controller | Purpose |
|-------|--------|------------|---------|
| `/vendor/dashboard` | GET | `VendorController@index` | Dashboard |
| `/vendor/products` | GET | `ProductController@index` | Product list |
| `/vendor/products/{type}/create` | GET | `ProductController@create` | New product |
| `/vendor/products/store` | POST | `ProductController@store` | Save product |
| `/vendor/orders` | GET | `OrderController@index` | Sales |

### Frontend Routes Summary

| Route | Method | Controller | Purpose |
|-------|--------|------------|---------|
| `/` | GET | `FrontendController@index` | Homepage |
| `/item/{slug}` | GET | `ProductDetailsController@product` | Product page |
| `/carts` | GET | `CartController@cart` | Cart page |
| `/checkout` | GET | `CheckoutController@checkout` | Checkout |
| `/checkout/payment/{gateway}-submit` | POST | `*Controller@store` | Process payment |

---

## Configuration & Settings

### General Settings

**Model**: `Generalsetting` (singleton, ID=1)

Key configuration options:
- `is_verification_email`: Require email verification
- `guest_checkout`: Allow non-registered purchases
- `multiple_shipping`: Vendor-specific shipping
- `verify_product`: Require vendor approval for products
- `affilate_charge`: Affiliate commission percentage
- `pod_designer_mode`: Enable product customization

### Environment Variables

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=xmerch
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525

# Payment (Example: Flouci)
FLOUCI_APP_TOKEN=your_token
FLOUCI_APP_SECRET=your_secret
FLOUCI_ENVIRONMENT=sandbox
```

---

## Troubleshooting & Edge Cases

### Common Issues

#### 1. Order Stuck in "Pending Print"
**Cause**: Order status is not "processing"  
**Solution**: Admin must update order status to "processing" first

```php
// Orders only appear in printer queue if:
Order::pendingPrint()->where('status', 'processing')->get();
```

#### 2. Production Capacity Exceeded
**Cause**: Daily production limit reached  
**Indicator**: Order not showing as "eligible"  
**Solution**: Wait for next day or increase `production_cap`

#### 3. Designer Panel Shows "No Products Allowed"
**Cause**: Subscription expired or limit reached  
**Solution**: Renew subscription or upgrade plan

#### 4. Custom Design Not Saving
**Cause**: Base64 image too large or malformed  
**Solution**: Check browser console for JavaScript errors; ensure canvas export is successful

### Maintenance Commands

```bash
# Clear all caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Run migrations
php artisan migrate

# Seed manufacturing roles
php artisan db:seed --class=ManufacturingDataSeeder
```

---

## Appendix: Visual Workflow Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              XMerch: Complete Order Flow                            │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│  DESIGNER                    CUSTOMER                     PRINTER                   │
│  ────────                    ────────                     ───────                   │
│                                                                                     │
│  ┌──────────┐               ┌──────────┐                                           │
│  │ Register │               │  Browse  │                                           │
│  │ as Vendor│               │ Products │                                           │
│  └────┬─────┘               └────┬─────┘                                           │
│       │                          │                                                  │
│       ▼                          ▼                                                  │
│  ┌──────────┐               ┌──────────┐                                           │
│  │ Upload   │               │ Add to   │                                           │
│  │ Design   │◀──────────────│  Cart    │                                           │
│  └────┬─────┘   Product     └────┬─────┘                                           │
│       │         Listed           │                                                  │
│       ▼                          ▼                                                  │
│  ┌──────────┐               ┌──────────┐                                           │
│  │  Earn    │               │ Checkout │                                           │
│  │ Revenue  │◀──────────────│ & Pay    │                                           │
│  └──────────┘   Order       └────┬─────┘                                           │
│                 Placed           │                                                  │
│                                  │                                                  │
│                                  │                     ┌──────────┐                 │
│                                  └────────────────────▶│  Order   │                 │
│                                        Appears in      │  Queue   │                 │
│                                        Queue           └────┬─────┘                 │
│                                                              │                       │
│                                                              ▼                       │
│                                                        ┌──────────┐                 │
│                                                        │  Print   │                 │
│                                                        │  Product │                 │
│                                                        └────┬─────┘                 │
│                                                              │                       │
│                                                              ▼                       │
│                                                        ┌──────────┐                 │
│                              ┌──────────────────────── │  Ship to │                 │
│                              │     Customer receives   │ Customer │                 │
│                              │     tracking info       └──────────┘                 │
│                              ▼                                                      │
│                         ┌──────────┐                                               │
│                         │ Receive  │                                               │
│                         │ Product  │                                               │
│                         └──────────┘                                               │
│                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

---

**Document End**
