# 🎨 Professional Apparel Mockup Resources

## ✅ Recommended Freepik Collections (Free, High-Quality, Multiple Views)

### **Hoodies**

1. **Realistic White Hoodie - Side & Back View**
   - **URL:** https://www.freepik.com/free-vector/realistic-vector-icon-white-mockup-hoodie-side-back-view-men-sweatshirt_24033895.htm
   - **Views:** Front, Side, Back
   - **Format:** EPS, SVG
   - **License:** Free for commercial use with attribution
   - **Quality:** ⭐⭐⭐⭐⭐ (Photorealistic)

2. **Black & White Men's Hoodies - Front & Side**
   - **URL:** https://www.freepik.com/free-vector/realistic-vector-icons-black-white-mens-hoodies-front-side-view_24033894.htm
   - **Views:** Front, Side (both colors)
   - **Format:** EPS, SVG
   - **License:** Free with attribution

3. **Sweatshirt on Hanger - 3 Views**
   - **Search:** "black sweatshirt hanger front side back view freepik"
   - **Views:** Front, Side, Back
   - **Format:** EPS, SVG

### **T-Shirts**

1. **T-Shirt Technical Flat (Front & Back)**
   - **URL:** Search "t-shirt mockup front back view freepik"
   - **Views:** Front, Back
   - **Format:** EPS, SVG, AI
   - **Recommended:** Filter by "Technical Drawing" or "Flat Sketch"

2. **Realistic T-Shirt Mockup Set**
   - **Search:** "realistic t-shirt mockup multiple views freepik"
   - **Views:** Front, Back, Side
   - **Format:** EPS, SVG

## 📥 Download Process

### **Step-by-Step:**

1. **Create Free Freepik Account** (if you don't have one)
   - Go to https://www.freepik.com/
   - Click "Sign Up" (top right)
   - Use Google/Email to register

2. **Download the Mockup**
   - Click the resource link above
   - Click "Free Download" button
   - Select format: **SVG** (preferred) or EPS
   - Click "Download"

3. **Extract the Files**
   - If you get a ZIP file, extract it
   - You'll typically get:
     - `hoodie_mockup.eps` or `hoodie_mockup.svg`
     - Sometimes a README with license info

4. **Separate the Views (if needed)**

   **Option A: Using Inkscape (Free)**

   ```
   1. Open the EPS/SVG in Inkscape
   2. Select each view (front/back/side/right)
   3. Edit → Copy
   4. File → New
   5. Edit → Paste
   6. File → Save As → Optimized SVG
   7. Name it: hoodie_white_front.svg
   8. Repeat for back, left, right
   ```

   **Option B: Using Adobe Illustrator**

   ```
   1. Open the EPS file in Illustrator
   2. Select the "Front View" group
   3. File → Export → Export As
   4. Format: SVG
   5. SVG Options: Presentation Attributes
   6. Save as: hoodie_white_front.svg
   7. Repeat for other views
   ```

5. **Place in Your Project**
   ```
   public/
     assets/
       svg/
         mockups/
           hoodie_white_front.svg
           hoodie_white_back.svg
           hoodie_white_left.svg
           hoodie_white_right.svg
           hoodie_black_front.svg
           hoodie_black_back.svg
           (etc.)
   ```

## 🔧 Integration Code

Update your `physical.blade.php` JavaScript section:

```javascript
mockupPreview.templates = {
  hoodie_white: {
    name: "White Hoodie",
    frontUrl: "{{ asset('assets/svg/mockups/hoodie_white_front.svg') }}",
    backUrl: "{{ asset('assets/svg/mockups/hoodie_white_back.svg') }}",
    leftUrl: "{{ asset('assets/svg/mockups/hoodie_white_left.svg') }}",
    rightUrl: "{{ asset('assets/svg/mockups/hoodie_white_right.svg') }}",
    productType: "hoodie",
    color: "white",
  },
};
```

## 📝 License & Attribution

Freepik resources are **free for commercial use** but require attribution:

**How to attribute:**

- Add this line to your website footer or credits page:
  ```html
  Mockup templates by <a href="https://www.freepik.com">Freepik</a>
  ```

**Premium Option:**

- Subscribe to Freepik Premium ($9.99/month)
- No attribution required
- Access to exclusive mockups

## 🎯 Alternative Sources (If Freepik doesn't have all views)

1. **Vecteezy** - https://www.vecteezy.com/
   - Search: "hoodie mockup front back side"
   - Free tier available, attribution required

2. **VectorStock** - https://www.vectorstock.com/
   - Search: "apparel technical flat 4 views"
   - Mix of free and paid

3. **Printful Mockup Generator API** (Dynamic Option)
   - https://developers.printful.com/
   - Generate mockups programmatically
   - Free tier: 1000 requests/month

## ⚠️ Important Notes

- **File Size:** SVG files from Freepik can be 500KB-2MB. Optimize them with SVGO:

  ```bash
  npm install -g svgo
  svgo hoodie_front.svg -o hoodie_front_optimized.svg
  ```

- **View Consistency:** Make sure all 4 views are from the same mockup set (same style, same proportions) so switching views looks smooth.

- **Color Variants:** If you need multiple colors (white, black, gray), download separate mockups for each color and name them accordingly.

## 🚀 Quick Test

After placing the SVG files:

1. Open: http://localhost:8000/vendor/products/physical/create
2. Click "Front" button → Should show front view
3. Click "Back" button → Should show back view
4. Click "Left" button → Should show left side
5. Click "Right" button → Should show right side

Done! ✅
