# 🇹🇳 Tunisia Quick Setup - SQL Script

## Run this script to configure XMerch for Tunisia market

-- ============================================
-- 1. CURRENCY CONFIGURATION
-- ============================================

-- Update default currency to TND
UPDATE generalsettings
SET
    currency_code = 'TND',
    currency_sign = 'د.ت',
    currency_format = '{amount} {symbol}'
WHERE
    id = 1;

-- Add TND currency if not exists
INSERT INTO
    currencies (
        name,
        sign,
        value,
        is_default,
        language_id,
        created_at,
        updated_at
    )
VALUES (
        'Tunisian Dinar',
        'د.ت',
        1.00,
        1,
        1,
        NOW(),
        NOW()
    )
ON DUPLICATE KEY UPDATE
    is_default = 1;

-- ============================================
-- 2. TIMEZONE & LOCALE
-- ============================================

-- Update timezone (done in .env file)
-- APP_TIMEZONE=Africa/Tunis
-- APP_LOCALE=fr

-- ============================================
-- 3. ENABLE CASH ON DELIVERY
-- ============================================

-- Enable COD payment gateway
UPDATE payment_gateways SET status = 1 WHERE keyword = 'cod';

-- Configure COD settings
UPDATE payment_gateways
SET
    information = '{"fee":"0","text":"Pay cash when you receive your order"}'
WHERE
    keyword = 'cod';

-- ============================================
-- 4. TUNISIA SHIPPING ZONES
-- ============================================

-- Clear existing shipping if needed
-- DELETE FROM shippings WHERE language_id = 1;

-- Add Tunisia shipping zones (adjust language_id as needed)
INSERT INTO
    shippings (
        location,
        price,
        language_id,
        user_id,
        created_at,
        updated_at
    )
VALUES (
        'Tunis',
        7.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Ariana',
        7.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Ben Arous',
        7.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Manouba',
        7.500,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Sfax',
        8.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Sousse',
        7.500,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Monastir',
        7.500,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Mahdia',
        8.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Kairouan',
        8.500,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Bizerte',
        7.500,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Nabeul',
        7.500,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Zaghouan',
        8.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Gabès',
        9.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Médenine',
        9.500,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Tataouine',
        10.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Gafsa',
        9.500,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Tozeur',
        10.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Kebili',
        10.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Kasserine',
        9.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Sidi Bouzid',
        8.500,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Béja',
        8.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Jendouba',
        9.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Le Kef',
        9.000,
        1,
        0,
        NOW(),
        NOW()
    ),
    (
        'Siliana',
        8.500,
        1,
        0,
        NOW(),
        NOW()
    );

-- ============================================
-- 5. ADD TUNISIAN PAYMENT GATEWAYS
-- ============================================

-- Paymee Gateway
INSERT INTO
    payment_gateways (
        subtitle,
        title,
        details,
        name,
        keyword,
        type,
        information,
        status,
        language_id,
        created_at,
        updated_at
    )
VALUES (
        NULL,
        'Paymee',
        'Pay securely with Tunisian bank cards or e-Dinar',
        'Paymee',
        'paymee',
        'automatic',
        '{"api_key":"","merchant_id":"","sandbox":"1"}',
        0,
        1,
        NOW(),
        NOW()
    )
ON DUPLICATE KEY UPDATE
    status = 0;

-- Konnect Gateway
INSERT INTO
    payment_gateways (
        subtitle,
        title,
        details,
        name,
        keyword,
        type,
        information,
        status,
        language_id,
        created_at,
        updated_at
    )
VALUES (
        NULL,
        'Konnect',
        'Pay with Konnect e-wallet',
        'Konnect',
        'konnect',
        'automatic',
        '{"api_key":"","wallet_id":"","sandbox":"1"}',
        0,
        1,
        NOW(),
        NOW()
    )
ON DUPLICATE KEY UPDATE
    status = 0;

-- e-Dinar Gateway
INSERT INTO
    payment_gateways (
        subtitle,
        title,
        details,
        name,
        keyword,
        type,
        information,
        status,
        language_id,
        created_at,
        updated_at
    )
VALUES (
        NULL,
        'e-Dinar',
        'Pay with your e-Dinar account (La Poste)',
        'e-Dinar',
        'edinar',
        'automatic',
        '{"merchant_id":"","terminal_id":"","sandbox":"1"}',
        0,
        1,
        NOW(),
        NOW()
    )
ON DUPLICATE KEY UPDATE
    status = 0;

-- Flouci Gateway
INSERT INTO
    payment_gateways (
        subtitle,
        title,
        details,
        name,
        keyword,
        type,
        information,
        status,
        language_id,
        created_at,
        updated_at
    )
VALUES (
        NULL,
        'Flouci',
        'Pay with Flouci mobile wallet',
        'Flouci',
        'flouci',
        'automatic',
        '{"app_token":"","app_secret":"","sandbox":"1"}',
        0,
        1,
        NOW(),
        NOW()
    )
ON DUPLICATE KEY UPDATE
    status = 0;

-- ============================================
-- 6. UPDATE POD PRICING FOR TUNISIA (TND)
-- ============================================

-- Update product base prices to TND
-- Example: Convert USD prices to TND (1 USD ≈ 3.1 TND)
UPDATE products SET price = price * 3.1 WHERE currency_id = 1;
-- Adjust based on your currency setup

-- Update POD base costs
UPDATE products SET base_cost = base_cost * 3.1 WHERE is_pod = 1;

-- ============================================
-- 7. LANGUAGE SETUP
-- ============================================

-- Add French language if not exists
INSERT INTO
    languages (
        language,
        is_default,
        rtl,
        file,
        name,
        created_at,
        updated_at
    )
VALUES (
        'fr',
        1,
        0,
        'fr.json',
        'Français',
        NOW(),
        NOW()
    )
ON DUPLICATE KEY UPDATE
    is_default = 1;

-- Add Arabic language if not exists
INSERT INTO
    languages (
        language,
        is_default,
        rtl,
        file,
        name,
        created_at,
        updated_at
    )
VALUES (
        'ar',
        0,
        1,
        'ar.json',
        'العربية',
        NOW(),
        NOW()
    )
ON DUPLICATE KEY UPDATE
    rtl = 1;

-- ============================================
-- 8. FREE SHIPPING THRESHOLD
-- ============================================

-- Set free shipping for orders over 100 TND
UPDATE generalsettings SET free_shipping = 100.000 WHERE id = 1;

-- ============================================
-- 9. TAX CONFIGURATION (Tunisia VAT = 19%)
-- ============================================

-- Enable tax
UPDATE generalsettings SET tax = 19.00 WHERE id = 1;

-- ============================================
-- 10. PICKUP LOCATIONS (Optional)
-- ============================================

-- Add pickup location in Tunis
INSERT INTO
    pickups (
        location,
        language_id,
        created_at,
        updated_at
    )
VALUES (
        'Tunis - Centre Ville, Avenue Habib Bourguiba',
        1,
        NOW(),
        NOW()
    );

INSERT INTO
    pickups (
        location,
        language_id,
        created_at,
        updated_at
    )
VALUES (
        'Sfax - Centre Ville, Avenue Hedi Chaker',
        1,
        NOW(),
        NOW()
    );

-- ============================================
-- VERIFICATION QUERIES
-- ============================================

-- Check currency
SELECT * FROM generalsettings WHERE id = 1;

-- Check payment gateways
SELECT name, keyword, status FROM payment_gateways;

-- Check shipping zones
SELECT location, price FROM shippings ORDER BY price;

-- Check languages
SELECT name, language, is_default, rtl FROM languages;

-- ============================================
-- NOTES
-- ============================================

/*
After running this script:

1. Update .env file:
APP_TIMEZONE=Africa/Tunis
APP_LOCALE=fr
DEFAULT_CURRENCY=TND

2. Clear cache:
php artisan config:clear
php artisan cache:clear

3. Configure payment gateway credentials in admin panel:
- Paymee: Add API key and merchant ID
- Konnect: Add API key and wallet ID
- e-Dinar: Add merchant and terminal ID
- Flouci: Add app token and secret

4. Test payment flows:
- Cash on Delivery
- Each payment gateway
- Shipping calculations

5. Translate content:
- Create resources/lang/fr.json
- Create resources/lang/ar.json
- Translate product descriptions
- Translate static pages
*/