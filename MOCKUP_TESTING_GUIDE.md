# Mockup Overlay - Testing Guide

## ✅ Fixes Applied

### 1. **Product Model** (`app/Models/Product.php`)
- ✅ Added `mockup_template_id` to `$fillable` array (line 14)
- ✅ `getMockupStyleAttribute()` method exists (lines 626-649)
- ✅ `mockupTemplate()` relationship exists (lines 548-550)

### 2. **Product Details Controller** (`app/Http/Controllers/Front/ProductDetailsController.php`)
- ✅ Updated line 56 to eager load `mockupTemplate` relationship:
  ```php
  $productt = Product::with('mockupTemplate')->where('slug','=',$slug)->firstOrFail();
  ```

### 3. **Product Details View** (`resources/views/partials/product-details/top.blade.php`)
- ✅ Lines 9-22: Conditional rendering of mockup overlay for POD products

### 4. **Database**
- ✅ Migration exists: `2024_12_31_000002_add_pod_fields_to_products_table.php`
- ✅ Migration has been run (confirmed via `migrate:status`)
- ✅ `mockup_template_id` column exists in `products` table

---

## 🧪 How to Test

### Prerequisites
For the mockup overlay to display, a product MUST have:

1. **`is_pod` = 1** (marked as Print on Demand)
2. **`mockup_template_id`** set (linked to a mockup template)
3. **`print_file`** populated (the designer's uploaded artwork)
4. A valid **MockupTemplate** record with:
   - `image` (the mockup background)
   - `design_x`, `design_y`, `design_width`, `design_height` (print area coordinates)

### Testing Steps

#### Step 1: Verify Product Data
Run this SQL query to check the product at your URL:

```sql
SELECT id, name, slug, is_pod, mockup_template_id, print_file 
FROM products 
WHERE slug = 'dzvs-pxs8692wwl';
```

**Expected Result:**
- `is_pod` should be `1`
- `mockup_template_id` should NOT be NULL
- `print_file` should NOT be NULL

#### Step 2: Verify Mockup Template Exists
```sql
SELECT * FROM mockup_templates 
WHERE id = (SELECT mockup_template_id FROM products WHERE slug = 'dzvs-pxs8692wwl');
```

**Expected Result:**
- Should return a record with:
  - `image` (filename of mockup)
  - `design_x`, `design_y`, `design_width`, `design_height` (print area coordinates)
  - `status` = 1 (active)

#### Step 3: Verify Files Exist
Check these directories:
- **Mockup image**: `public/assets/images/mockups/{mockup_template.image}`
- **Design file**: `public/assets/images/products/{product.print_file}`

#### Step 4: Visit the Product Page
Navigate to: `http://localhost/xmerch/item/dzvs-pxs8692wwl`

**Expected Behavior:**
- You should see the mockup image as the base
- The design (`print_file`) should be overlaid on top
- The design should be positioned according to the template's `design_x/y/width/height`

---

## 🔍 Troubleshooting

### Issue: Still Seeing Standard Product Photo
**Cause:** Product is not marked as POD or missing mockup template.

**Fix:**
```sql
-- Mark product as POD
UPDATE products SET is_pod = 1 WHERE slug = 'dzvs-pxs8692wwl';

-- Link to mockup template (replace 1 with actual template ID)
UPDATE products SET mockup_template_id = 1 WHERE slug = 'dzvs-pxs8692wwl';
```

### Issue: Design Not Overlaying
**Cause:** `print_file` is NULL or file doesn't exist.

**Fix:**
1. Verify the product has a `print_file` value
2. Check the file exists in `public/assets/images/products/`

### Issue: Design Positioned Incorrectly
**Cause:** Mockup template coordinates are incorrect.

**Fix:**
1. Go to Admin → Mockup Templates
2. Edit the template
3. Use the visual editor to redefine the print area

### Issue: "Trying to get property of non-object" Error
**Cause:** Mockup template ID points to non-existent template.

**Fix:**
```sql
-- Find orphaned products
SELECT p.id, p.name, p.mockup_template_id 
FROM products p 
LEFT JOIN mockup_templates mt ON p.mockup_template_id = mt.id 
WHERE p.is_pod = 1 AND p.mockup_template_id IS NOT NULL AND mt.id IS NULL;

-- Fix by setting to NULL or assigning valid template
UPDATE products SET mockup_template_id = NULL WHERE id = <product_id>;
```

---

## 📊 Quick Debug Check

Add this to your blade template temporarily (after line 22):

```blade
@if($productt->is_pod)
    <div class="alert alert-info">
        <strong>Debug:</strong><br>
        is_pod: {{ $productt->is_pod }}<br>
        mockup_template_id: {{ $productt->mockup_template_id ?? 'NULL' }}<br>
        print_file: {{ $productt->print_file ?? 'NULL' }}<br>
        mockupTemplate exists: {{ $productt->mockupTemplate ? 'YES' : 'NO' }}<br>
        @if($productt->mockupTemplate)
            mockup image: {{ $productt->mockupTemplate->image }}<br>
            mockup_style: {{ $productt->mockup_style }}
        @endif
    </div>
@endif
```

This will show you exactly what data is available.

---

## 🎯 Expected SQL State for Working Example

```sql
-- Product record
INSERT INTO products (slug, name, is_pod, mockup_template_id, print_file) 
VALUES ('dzvs-pxs8692wwl', 'Test POD Product', 1, 1, 'print_123456.png');

-- Mockup template record
INSERT INTO mockup_templates (id, name, product_type, image, design_x, design_y, design_width, design_height, status) 
VALUES (1, 'White T-Shirt Front', 'tshirt', 'tshirt_white_front.png', 150, 200, 250, 300, 1);
```

---

## 🚀 Next Steps After Verification

Once you confirm it's working:
1. Remove debug output
2. Test with multiple mockup templates
3. Test responsive behavior (mobile/tablet)
4. Consider implementing the cached version for better performance
5. Add mockup overlay to product listing pages (optional)
