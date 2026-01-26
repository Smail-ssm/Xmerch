# 🇹🇳 Tunisia Setup - Quick Reference Card

## ⚡ **5-Minute Setup**

### **Step 1: Run SQL Script (2 min)**

```bash
# Import Tunisia configuration
mysql -u root xmerch < tunisia_setup.sql
```

### **Step 2: Update .env (1 min)**

```env
APP_TIMEZONE=Africa/Tunis
APP_LOCALE=fr
DEFAULT_CURRENCY=TND
CURRENCY_SYMBOL=د.ت
```

### **Step 3: Clear Cache (1 min)**

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### **Step 4: Configure Payment Gateways (1 min)**

Go to: **Admin Panel → Payment Settings**

-   Enable Cash on Delivery ✅
-   Add Paymee credentials (when ready)
-   Add Konnect credentials (when ready)

---

## 💳 **Payment Gateways Priority**

| Gateway              | Priority         | Status          | Setup Time |
| -------------------- | ---------------- | --------------- | ---------- |
| **Cash on Delivery** | ⭐⭐⭐ Must Have | ✅ Built-in     | 0 min      |
| **Paymee**           | ⭐⭐⭐ Essential | Register needed | 1-2 days   |
| **e-Dinar**          | ⭐⭐ Important   | Register needed | 2-3 days   |
| **Konnect**          | ⭐⭐ Important   | Register needed | 1-2 days   |
| **Flouci**           | ⭐ Nice to have  | Register needed | 2-3 days   |

---

## 💰 **Pricing Quick Reference (TND)**

### **POD Products**

| Product         | Base Cost | Suggested Retail | Profit |
| --------------- | --------- | ---------------- | ------ |
| T-Shirt Basic   | 25 TND    | 45 TND           | 20 TND |
| T-Shirt Premium | 40 TND    | 70 TND           | 30 TND |
| Hoodie Basic    | 60 TND    | 100 TND          | 40 TND |
| Hoodie Premium  | 85 TND    | 140 TND          | 55 TND |
| Mug             | 15 TND    | 25 TND           | 10 TND |
| Poster          | 20 TND    | 35 TND           | 15 TND |

### **Shipping Costs**

| Zone              | Cost         | Delivery |
| ----------------- | ------------ | -------- |
| Tunis/Ariana      | 7 TND        | 1-2 days |
| Sousse/Sfax       | 7.5-8 TND    | 2-3 days |
| Other Cities      | 8-10 TND     | 3-5 days |
| **Free Shipping** | Over 100 TND | -        |

---

## 🌍 **Tunisia Market Facts**

-   **Currency**: TND (Tunisian Dinar) - Non-convertible
-   **Preferred Payment**: Cash on Delivery (80%+)
-   **Languages**: French (primary), Arabic (secondary)
-   **Timezone**: Africa/Tunis (GMT+1)
-   **VAT**: 19%
-   **Mobile Usage**: 70%+ of traffic

---

## 📱 **Contact Information for Gateways**

### **Paymee**

-   Website: paymee.tn
-   Email: contact@paymee.tn
-   Setup: 1-2 business days

### **Konnect**

-   Website: konnect.network
-   Email: support@konnect.network
-   Setup: 1-2 business days

### **e-Dinar**

-   Provider: La Poste Tunisienne
-   Visit: Local post office
-   Setup: 2-3 business days

### **Flouci**

-   Website: flouci.com
-   App: Available on iOS/Android
-   Setup: 2-3 business days

---

## ✅ **Launch Checklist**

### **Before Launch**

-   [ ] SQL script executed
-   [ ] .env file updated
-   [ ] Cache cleared
-   [ ] Currency set to TND
-   [ ] COD enabled
-   [ ] Shipping zones configured
-   [ ] Prices converted to TND
-   [ ] French language active
-   [ ] Test order placed (COD)

### **Week 1**

-   [ ] Register with Paymee
-   [ ] Register with Konnect
-   [ ] Test payment flows
-   [ ] Add product descriptions in French

### **Week 2**

-   [ ] Integrate Paymee
-   [ ] Integrate e-Dinar
-   [ ] Add Arabic translations
-   [ ] Mobile optimization check

### **Week 3**

-   [ ] Add Flouci
-   [ ] Marketing campaign ready
-   [ ] Social media setup
-   [ ] Customer support ready

### **Week 4**

-   [ ] Soft launch
-   [ ] Gather feedback
-   [ ] Fix issues
-   [ ] Full launch

---

## 🚀 **Quick Commands**

### **Database**

```bash
# Import Tunisia setup
mysql -u root xmerch < tunisia_setup.sql

# Verify currency
mysql -u root xmerch -e "SELECT currency_code, currency_sign FROM generalsettings"

# Check payment gateways
mysql -u root xmerch -e "SELECT name, status FROM payment_gateways"
```

### **Laravel**

```bash
# Clear all caches
php artisan optimize:clear

# Set timezone
php artisan config:cache

# Test email (optional)
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com'); });
```

---

## 📊 **Success Metrics**

### **Month 1 Goals**

-   Orders: 50+
-   COD Rate: 80%
-   Online Payment: 20%
-   Average Order: 75 TND

### **Month 3 Goals**

-   Orders: 200+
-   COD Rate: 70%
-   Online Payment: 30%
-   Average Order: 85 TND

### **Month 6 Goals**

-   Orders: 500+
-   COD Rate: 60%
-   Online Payment: 40%
-   Average Order: 95 TND

---

## 🆘 **Troubleshooting**

### **Currency Not Showing**

```bash
php artisan config:clear
php artisan cache:clear
```

### **Shipping Not Calculated**

Check: Admin → Shipping Settings → Verify zones

### **Payment Gateway Not Working**

1. Check credentials in admin panel
2. Verify API keys
3. Check sandbox mode
4. Contact gateway support

### **Language Not Switching**

1. Check language files exist
2. Clear view cache
3. Verify language_id in database

---

## 📞 **Support**

### **XMerch Support**

-   Check documentation files
-   Review error logs: `storage/logs/laravel.log`

### **Payment Gateway Support**

-   Paymee: contact@paymee.tn
-   Konnect: support@konnect.network
-   e-Dinar: Visit local post office

---

## 🎯 **Next Steps**

1. ✅ Run `tunisia_setup.sql`
2. ✅ Update `.env` file
3. ✅ Clear caches
4. ✅ Test COD checkout
5. ⏳ Register with Paymee
6. ⏳ Register with Konnect
7. ⏳ Add product content
8. ⏳ Launch!

---

**Your XMerch platform is Tunisia-ready!** 🇹🇳🚀
