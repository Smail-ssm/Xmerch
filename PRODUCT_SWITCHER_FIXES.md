# Product Switcher Fixes - Alignment & Bidirectional Switching

## Issues Fixed

### 1. ✅ **Mockups Not Aligned**
**Problem:** When switching between T-Shirt (Low Res) and Hoodie (High Res), the design canvas didn't resize, and design elements appeared tiny or misaligned on the larger hoodie image.

**Root Cause:**
1. Limits of `loadTemplate`: Did not force resize after all views loaded.
2. No Layer Scaling: Absolute pixel coordinates meant 200px wide logo looked huge on 500px shirt but tiny on 2000px hoodie.

**Solution:**
1. **Added `.then()` handler** to `loadTemplate()` to ensure canvas is properly resized.
2. **Implemented `scaleLayers(factor)`** method in `MockupPreview` class.
3. **Automatic Scaling:** Calculated ratio `newWidth / oldWidth` and applied to all layers when switching templates.

```javascript
// In physical.blade.php
const oldWidth = oldTemplate ? oldTemplate.width : null;
// ... load template ...
if (oldWidth && val.width !== oldWidth) {
    const scale = val.width / oldWidth;
    mockupPreview.scaleLayers(scale);
}
```

### 2. ✅ **One-Way Switching**
**Problem:** Switching back to T-Shirt failed silently.
**Solution:** Improved promise handling ensures the switching logic waits for the previous template to fully load/fail before rendering.

## Code Changes

### `assets/admin/js/mockup-preview.js`
- Added `scaleLayers(factor)` method to resize designs, text, and drawings.
- Updated `loadTemplate` to ensure canvas dimensions are correct after loading.

### `resources/views/vendor/product/create/physical.blade.php`
- Updated click handler to calculate scale ratio.
- Fixed syntax errors (extra braces).
- Added error logging.

## Verification

- **T-Shirt (Small) → Hoodie (Large):** Design scales UP. Canvas resizes.
- **Hoodie (Large) → T-Shirt (Small):** Design scales DOWN. Canvas resizes.
- **Alignment:** Design maintains relative size and position on the product chest.
