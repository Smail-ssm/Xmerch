# 🚀 POD QUICK REFERENCE CARD

## ⚡ **15-Minute Setup**

### **1. Fix Database (2 min)**

```
Laragon → Stop All → Start All
```

### **2. Run Migrations (1 min)**

```bash
cd c:\laragon\www\xmerch\project
php artisan migrate
```

### **3. Add Routes (2 min)**

Copy from `routes/printjob_routes.php` → `routes/web.php`

### **4. Test POD Product (5 min)**

```sql
UPDATE products SET is_pod = 1, production_cap = 50 WHERE id = 1;
```

Then add to cart and place order.

### **5. Check Dashboard (5 min)**

Visit: `http://localhost/xmerch/admin/printjobs`

---

## 📋 **Key Files**

| File                                                | Purpose                |
| --------------------------------------------------- | ---------------------- |
| `app/Models/PrintJob.php`                           | Print job model        |
| `app/Models/Cart.php`                               | Capacity checking      |
| `app/Helpers/OrderHelper.php`                       | Auto-create print jobs |
| `app/Http/Controllers/Admin/PrintJobController.php` | Queue management       |
| `app/Http/Controllers/Front/CartController.php`     | POD errors             |

---

## 🔧 **Common Tasks**

### **Mark Product as POD**

```sql
UPDATE products
SET is_pod = 1,
    production_cap = 50,
    print_time_minutes = 30
WHERE id = 1;
```

### **Create Print Job Manually**

```php
$product = Product::find(1);
$job = $product->createPrintJob(
    orderId: 100,
    quantity: 5,
    qualityTier: 'premium'
);
```

### **Check Capacity**

```php
$product = Product::find(1);
if ($product->isAvailable(10)) {
    echo "Can produce 10 units";
} else {
    echo "Only {$product->remaining_capacity} available";
}
```

### **Get Today's Production**

```php
$product = Product::find(1);
$count = $product->getTodayProductionCount();
echo "Produced {$count} today";
```

---

## 🎯 **Integration Points**

### **Payment Success Handler**

```php
// After creating order
use App\Helpers\OrderHelper;

$cart = Session::get('cart');
$order = Order::find($order_id);

// Create print jobs
OrderHelper::create_print_jobs($cart, $order);

// POD-aware stock check
OrderHelper::stock_check_pod_aware($cart);
```

### **Admin Sidebar**

```html
<li>
    <a href="{{ route('admin-printjob-index') }}">
        <i class="fas fa-print"></i>
        <span>Print Queue</span>
    </a>
</li>
```

---

## 📊 **Dashboard URLs**

| URL                          | Purpose            |
| ---------------------------- | ------------------ |
| `/admin/printjobs`           | Main dashboard     |
| `/admin/printjobs/queue`     | Queued jobs        |
| `/admin/printjobs/printing`  | Currently printing |
| `/admin/printjobs/completed` | Completed jobs     |
| `/admin/printjobs/failed`    | Failed jobs        |

---

## 🐛 **Quick Troubleshooting**

| Problem                 | Solution                              |
| ----------------------- | ------------------------------------- |
| Print jobs not creating | Check `is_pod = 1` on product         |
| Capacity not working    | Set `production_cap` on product       |
| Dashboard 404           | Add routes from `printjob_routes.php` |
| Cart errors             | Check Product model has POD methods   |
| Migration fails         | Fix MySQL connection first            |

---

## ✅ **Verification Checklist**

-   [ ] MySQL connected
-   [ ] Migrations run successfully
-   [ ] Routes added to web.php
-   [ ] Test product marked as POD
-   [ ] Can add POD product to cart
-   [ ] Capacity limit enforced
-   [ ] Order creates print job
-   [ ] Dashboard loads
-   [ ] Can start/complete jobs

---

## 📝 **Quick SQL Queries**

### **View All Print Jobs**

```sql
SELECT * FROM print_jobs ORDER BY created_at DESC LIMIT 10;
```

### **View POD Products**

```sql
SELECT id, name, is_pod, production_cap, remaining_capacity
FROM products
WHERE is_pod = 1;
```

### **Today's Production**

```sql
SELECT product_id, SUM(quantity) as total
FROM print_jobs
WHERE DATE(created_at) = CURDATE()
GROUP BY product_id;
```

### **Queue Status**

```sql
SELECT status, COUNT(*) as count
FROM print_jobs
GROUP BY status;
```

---

## 🎉 **You're Ready!**

Everything is set up. Just:

1. Fix database connection
2. Run migrations
3. Add routes
4. Test!

**Total time: ~15 minutes** 🚀
