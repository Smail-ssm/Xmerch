
# Project Full Analysis

## 1. Executive Summary
- FACT: The project is a Laravel 8.65 server-rendered e-commerce monolith intended to support a multi-vendor marketplace with print-on-demand (POD), vendor subscriptions, checkout, vendor verification, printing, and manufacturing workflows. Evidence: `project/composer.json`, `project/routes/web.php`, `FULL_WORKFLOW_GUIDE.md`.
- FACT: Core capabilities implemented in code include customer registration/login, vendor registration/login, vendor product CRUD, guest checkout, order creation, payment gateway routing, admin back office, vendor verification, print job management, and manufacturing dashboards. Evidence: `project/routes/web.php`, `project/app/Http/Controllers/Vendor/ProductController.php`, `project/app/Http/Controllers/Admin/PrinterController.php`, `project/app/Http/Controllers/Admin/ManufacturingController.php`.
- FACT: The active architecture is a single Laravel application under `project/`, bootstrapped from repo-root `index.php`, with the public path rebound to the repository root instead of the Laravel default `public/` directory. Evidence: `index.php`, `project/bootstrap/app.php`.
- FACT: The current runtime surface is large: `808` web routes, `0` active API routes, `165` controllers, and `422` Blade view files. Evidence: `php artisan route:list --json`, filesystem counts under `project/app/Http/Controllers`, filesystem counts under `project/resources/views`.
- OPINION: The codebase is deployable as a containerized Laravel application, but it is not production-ready as an internet-facing commerce platform in its current state because security-critical legacy endpoints remain exposed, authorization boundaries are weak, database state is drifting from code assumptions, and several business-critical flows are only partially wired.

### Top 5 Critical Risks

| Risk | Severity | Impact | Likelihood | Evidence |
|---|---|---:|---:|---|
| Public installer-style endpoint can write and delete arbitrary files | Critical | Full server compromise, defacement, data loss | High | `project/routes/web.php:1773`, `project/routes/web.php:1788`, `project/app/Http/Controllers/Front/FrontendController.php:539-553` |
| Vendor object-level authorization gaps on product edit/delete and order detail access | Critical | Cross-tenant data exposure and unauthorized modification | High | `project/app/Http/Controllers/Vendor/ProductController.php:816-865`, `project/app/Http/Controllers/Vendor/ProductController.php:1550-1608`, `project/app/Http/Controllers/Vendor/OrderController.php:63-95` |
| Schema and migration ledger are inconsistent | High | Failed deploys, broken rollbacks, non-reproducible environments | High | `php artisan migrate:status`, `project/database/migrations/2026_03_28_200000_align_legacy_schema_for_pod_manufacturing.php` |
| POD/manufacturing logic is configured in code but not aligned in live data | High | Empty queues, unusable manufacturing/printer roles, false readiness | High | live DB: `orders.print_status = manufacturing` count `38`, `orders.status='processing'` count `0`, roles only `16/17/18`, no `print_jobs`, no `mockup_templates`, no `pod_pricing_options` |
| Tunisia-specific payment support exists in routes/docs but not in gateway data | High | Checkout failures or missing payment methods in production | High | `project/routes/web.php:1413-1430`, `project/routes/web.php:1720-1732`, live DB `payment_gateways` rows lack `flouci`, `konnect`, `paymee` |

- Final decision: **No-Go**
- Justification:
  - FACT: There is an unauthenticated arbitrary file write/delete route reachable through normal web middleware.
  - FACT: Vendor users can fetch or mutate resources by raw IDs/slugs without consistent ownership scoping.
  - FACT: The database state required for manufacturing, Tunisia-only gateways, and worker queues is not aligned with the code and deployment templates.
  - OPINION: Those three conditions together are blocking issues for a public production launch.

## 2. Project Decomposition

### 2.1 Domains / Modules

| Domain / Module | Responsibilities | Evidence | Assessment |
|---|---|---|---|
| Storefront / Catalog | Homepage, product browsing, category/tag filtering, vendor storefronts, product detail pages | `project/routes/web.php:1570-1607`, `project/app/Http/Controllers/Front/*` | FACT: Implemented |
| Customer Accounts | Registration, login, forgot password, profile reset, subscriptions, deposits | `project/routes/web.php:1294-1432`, `project/app/Http/Controllers/Auth/User/*`, `project/app/Http/Controllers/User/*` | FACT: Implemented with custom auth flows |
| Vendor / Designer Portal | Vendor dashboard, profile, verification, product CRUD/import, income, orders | `project/routes/web.php:1109-1174`, `project/app/Http/Controllers/Vendor/*`, `project/resources/views/vendor/*` | FACT: Broadly implemented, but authorization is weak |
| Checkout & Payments | Cart checkout, gateway loading, wallet checks, gateway submit/notify/success/cancel | `project/app/Http/Controllers/Front/CheckoutController.php`, `project/routes/web.php` payment sections | FACT: Implemented with many gateway adapters |
| Admin Back Office | Orders, products, categories, users, gateways, subscriptions, verification, settings | `project/routes/web.php` admin groups, `project/app/Http/Controllers/Admin/*` | FACT: Implemented as large monolithic admin area |
| Print Production | Print queue, line-item print jobs, print file retrieval, shipping labels | `project/app/Http/Controllers/Admin/PrinterController.php`, `project/app/Http/Controllers/Admin/PrintJobController.php`, `project/app/Models/PrintJob.php` | FACT: Code exists; live data is empty |
| Manufacturing | Capacity planning, schedule, analytics, printer assignment, quality view | `project/app/Http/Controllers/Admin/ManufacturingController.php` | FACT: Code exists; some KPIs are placeholders |
| Platform Settings / Theming | General settings, pagesettings, SEO, social settings, theme config by category/user | `project/app/Providers/AppServiceProvider.php`, `project/app/Http/Controllers/Front/FrontBaseController.php`, schema columns on `categories` and `users` | FACT: Implemented but tightly coupled to views |
| Deployment / Operations | Docker image, VPS deployment, reverse proxy setup, backup/restore scripts | `.github/workflows/deploy-vps.yml`, `deploy/production/docker-compose.yml`, `project/docs/DEPLOYMENT_VPS_DOCKER.md`, `project/docs/VPS_OVH_SETUP_GUIDE.md` | FACT: Recently scaffolded |

### 2.2 Actors

| Actor | Current Representation | Permissions / Behavior | Evidence | Status |
|---|---|---|---|---|
| Guest Buyer | No auth session | Browse, cart, guest checkout if enabled | `project/app/Http/Controllers/Front/CheckoutController.php`, live `generalsettings.guest_checkout = 1` | FACT: Enabled |
| Registered Buyer | `users` row, `web` guard | Login, order history, deposits, subscription purchase | `project/config/auth.php`, `project/routes/web.php` user routes | FACT: Implemented |
| Vendor / Designer | `users.is_vendor == 2` in active logic | Vendor dashboard, verify account, create products, view vendor orders | `project/app/Models/User.php`, `project/app/Http/Middleware/Vendor.php`, `project/routes/web.php` vendor routes | FACT: Legacy vendor semantics are active |
| Admin / Super Admin | `admins` table, `admin` guard | Full admin UI; ID `1` bypasses permission checks | `project/app/Models/Admin.php`, `project/app/Http/Middleware/Permissions.php` | FACT: Implemented with hard-coded bypass |
| Printer Operator | Intended via admin role section `print_production` | Printer dashboard and print actions | `project/routes/web.php:402-415`, `project/app/Http/Controllers/Admin/PrinterController.php` | FACT: Implemented in code; live roles do not provision it |
| Manufacturing Lead | Intended via admin role section `manufacturing` | Capacity, analytics, schedule, queue | `project/routes/web.php:423-437`, `project/app/Http/Controllers/Admin/ManufacturingController.php` | FACT: Implemented in code; live roles do not provision it |
| Manufacturer / Printer as `users.role_type` | New schema enum on `users.role_type` | [MISSING] No active controller or middleware path was found using `role_type` for access control | live DB `users` schema, absence in `project/app/Models/User.php` and route middleware | FACT: Schema exists; active logic does not use it |

### 2.3 Core Workflows

- Buyer signup and login
  - FACT: `POST /user/register` creates a `users` row and sets `verification_link` using MD5.
  - FACT: Email verification is optional and currently disabled in live settings (`is_verification_email = 0`), so registration marks users verified immediately.
  - FACT: `POST /user/login` authenticates with session auth and rejects users with `email_verified = 'No'` or `ban = 1`.
  - RISK: No route-level throttling was observed on login routes.
  - Evidence: `project/app/Http/Controllers/Auth/User/RegisterController.php`, `project/app/Http/Controllers/Auth/User/LoginController.php`, route middleware from `php artisan route:list --json`.

- Vendor onboarding and verification
  - FACT: Vendor registration sets `is_vendor = 1`, then active vendor access requires `is_vendor == 2`.
  - FACT: Vendor product creation is blocked when `generalsettings.verify_product = 1` and `User::checkStatus()` is false.
  - FACT: Verification submissions create or update rows in `verifications` with `status = 'Pending'`.
  - RISK: The model uses verification-table state, while the database also contains unused `users.is_pod_verified` and `users.pod_verification_status` fields.
  - Evidence: `project/app/Http/Controllers/Auth/User/RegisterController.php`, `project/app/Models/User.php`, `project/app/Http/Controllers/Vendor/VendorController.php`, live DB `users` schema.

- Designer product upload
  - FACT: Vendor product creation supports standard image upload, base64 image payloads, downloadable ZIP files, gallery uploads, and POD `print_image` base64 payloads.
  - FACT: Files are written directly into public asset folders such as `assets/images/products` and `assets/files/designs`.
  - RISK: Owner scoping is not enforced on edit/update/delete routes.
  - Evidence: `project/app/Http/Controllers/Vendor/ProductController.php:380-520`, `project/app/Http/Controllers/Vendor/ProductController.php:816-930`, `project/routes/web.php:1146-1174`.

- Checkout to order creation
  - FACT: Checkout uses session cart data, shipping/package lookup, and gateway filtering by current currency.
  - FACT: Tunisia-only mode can restrict gateways to `cod`, `flouci`, `konnect`, `paymee`, `d17`.
  - FACT: Orders store the full cart as serialized JSON/TEXT in `orders.cart`.
  - RISK: Live gateway data does not currently include the Tunisia-specific gateways used by the controller and docs.
  - Evidence: `project/app/Http/Controllers/Front/CheckoutController.php`, `project/app/Models/PaymentGateway.php`, live DB `payment_gateways`.

- POD order fulfillment
  - FACT: `OrderHelper::create_print_jobs()` creates line-item `print_jobs` for products where `is_pod = 1` and moves eligible orders to `print_status = manufacturing`.
  - FACT: Manufacturing queues only include orders where `orders.status = 'processing'` and `orders.print_status` is in manufacturing/print-ready states.
  - FACT: Live data has `38` orders with `print_status = manufacturing` but `0` orders with `status = processing`.
  - RISK: Current live orders will not appear in manufacturing/printer operational queues.
  - Evidence: `project/app/Helpers/OrderHelper.php`, `project/app/Models/Order.php`, `project/app/Http/Controllers/Admin/ManufacturingController.php`, live DB counts.

### 2.4 Integrations

| System | Purpose | Protocol | Risk |
|---|---|---|---|
| MariaDB / MySQL | Primary application database | PDO MySQL | FACT: Only primary keys were observed on several hot tables; migration ledger is inconsistent |
| Redis | Optional cache/queue backend | TCP | FACT: Configured, but current default cache/session/queue are file or sync unless env overrides |
| SMTP mail server | Registration, reset, order mail | SMTP | FACT: Implemented via custom `XMerchMailer`; custom token flows have weak token generation |
| Payment gateways | Checkout, deposit, subscription flows | HTTPS callbacks/redirects | FACT: Many integrations exist; live DB lacks Tunisia-specific rows; CSRF exemptions are incomplete for Tunisia notify routes |
| GHCR | Container registry for VPS deployment | HTTPS | FACT: Deployment workflow depends on registry access and PAT on VPS |
| Nginx / Apache | Reverse proxy + app web server | HTTP/HTTPS | FACT: Planned Docker deployment uses Apache in container and Nginx on VPS |
| DNSExit / OVH DNS | Domain resolution | DNS | FACT: Deployment docs assume external DNS and TLS via Certbot |
| Social login | Package installed (`laravel/socialite`) | OAuth2/HTTPS | [MISSING] No validated route inventory was produced for current active providers |

## 3. Business Analysis

- INFERENCE: The product goal is to operate a Tunisia-focused multi-vendor POD marketplace where designers upload products, buyers purchase them, and internal operations complete manufacturing and printing. Evidence: `FULL_WORKFLOW_GUIDE.md`, Tunisia gateway routes in `project/routes/web.php`, market filter in `project/app/Http/Controllers/Front/CheckoutController.php`.
- INFERENCE: The expected business KPIs are likely GMV, order conversion, vendor activation, time from order to shipped, production-capacity utilization, and payment success rate. Evidence: manufacturing analytics code in `project/app/Http/Controllers/Admin/ManufacturingController.php`, vendor income/order modules, checkout/payment breadth.
- FACT: The application currently stores only `3` users, `0` products, `38` orders, `33` vendor_orders, `0` print_jobs, `0` mockup_templates, and `0` pod_pricing_options in the local database, so there is not yet evidence of a fully exercised live POD marketplace dataset.

### Value Proposition
- FACT: The codebase attempts to combine marketplace commerce, vendor subscriptions, verification, and production operations in one product.
- OPINION: That integrated value proposition is strong for a Tunisia-localized POD business if the operational states are normalized and the gateway/role data is actually provisioned.
### Key Use Cases

| Use case | Actors | Outcome | Evidence |
|---|---|---|---|
| Register as customer | Guest buyer | Account created, optional email verification, session login | `project/app/Http/Controllers/Auth/User/RegisterController.php` |
| Register as vendor | Guest user | Vendor-intent account created, later activated into vendor mode | `project/app/Http/Controllers/Auth/User/RegisterController.php` |
| Submit vendor verification | Vendor | Documents stored, admin review required | `project/app/Http/Controllers/Vendor/VendorController.php` |
| Upload and sell a product | Vendor | Product saved, publicly visible if approved | `project/app/Http/Controllers/Vendor/ProductController.php` |
| Buy a product | Guest or customer | Order created and payment processed | `project/app/Http/Controllers/Front/CheckoutController.php`, payment route groups |
| Create print jobs for POD order | System | Line-item print jobs queued | `project/app/Helpers/OrderHelper.php` |
| Manage print queue | Printer operator / admin | Start, print, ship fulfillment steps | `project/app/Http/Controllers/Admin/PrinterController.php` |
| Plan manufacturing capacity | Manufacturing lead / admin | Capacity and queue review | `project/app/Http/Controllers/Admin/ManufacturingController.php` |

### Business Rules Extracted

| Rule | Type | Evidence | Notes |
|---|---|---|---|
| Vendor product creation can require verification | FACT | `project/app/Http/Controllers/Vendor/ProductController.php` checks `Generalsetting::find(1)->verify_product` and `User::checkStatus()` | Live DB `verify_product = 1` |
| Vendor access requires `is_vendor == 2` | FACT | `project/app/Models/User.php`, `project/app/Http/Middleware/Vendor.php` | Registration sets `is_vendor = 1`, so an upgrade path must exist elsewhere |
| Tunisia-only checkout filters allowed gateway keywords | FACT | `project/app/Http/Controllers/Front/CheckoutController.php` | Live gateway data does not match this rule |
| POD orders should enter manufacturing before printing | FACT | `project/app/Helpers/OrderHelper.php`, `project/app/Models/Order.php` | Current code uses `manufacturing -> print_ready -> printing -> printed -> shipped` |
| Print job creation is intended to be idempotent | FACT | existence guard in `OrderHelper::create_print_jobs()`, test `PodManufacturingWorkflowTest.php` | Positive implementation detail |
| Admin ID `1` bypasses permission checks | FACT | `project/app/Http/Middleware/Permissions.php` | Operationally risky |

### Gaps and Ambiguities
- [MISSING] No authoritative business document was found that reconciles legacy marketplace behavior (`is_vendor`, vendor subscriptions) with newer schema fields (`role_type`, `is_pod_verified`, `pod_verification_status`).
- [RISK] `FULL_WORKFLOW_GUIDE.md` describes preconfigured printer/manufacturing roles and Tunisia gateway support, but the live database does not contain matching role sections or gateway rows.
- [RISK] `POD_SIMPLIFIED_WORKFLOW.md` states the complex mockup system was removed, but `MockupTemplate`, `PodPricingOption`, `mockup_template_id`, and print-file fields remain in the active code and schema.

## 4. Functional Analysis

### Feature Inventory by Module

| Module | Feature | Current State | Evidence |
|---|---|---|---|
| Auth | Customer register/login/logout | FACT: Implemented | `project/app/Http/Controllers/Auth/User/*` |
| Auth | Admin login/logout | FACT: Implemented | `project/app/Http/Controllers/Auth/Admin/LoginController.php` |
| Auth | Forgot/reset password | FACT: Implemented via custom token fields, not framework broker | `project/app/Http/Controllers/Auth/User/ForgotController.php`, `project/app/Http/Controllers/Auth/Admin/ForgotController.php`, `project/config/auth.php` |
| Vendor | Verification submission | FACT: Implemented | `project/app/Http/Controllers/Vendor/VendorController.php` |
| Vendor | Product CRUD | FACT: Implemented | `project/app/Http/Controllers/Vendor/ProductController.php` |
| Vendor | Product CSV import | FACT: Implemented | `project/app/Http/Controllers/Vendor/ProductController.php` |
| Vendor | Order management | FACT: Implemented | `project/app/Http/Controllers/Vendor/OrderController.php` |
| Checkout | Guest checkout | FACT: Enabled in live settings | live DB `generalsettings.guest_checkout = 1` |
| Checkout | Wallet check | FACT: Implemented | `project/app/Http/Controllers/Front/CheckoutController.php` |
| Payments | International gateways | FACT: Implemented in code and seeded in DB | routes + live `payment_gateways` rows |
| Payments | Tunisia gateways | FACT: Implemented in code; not seeded in DB | routes + live `payment_gateways` rows |
| POD | Print job creation | FACT: Implemented | `project/app/Helpers/OrderHelper.php`, `project/app/Models/PrintJob.php` |
| POD | Manufacturing dashboard | FACT: Implemented with placeholder KPIs | `project/app/Http/Controllers/Admin/ManufacturingController.php` |
| POD | Printer dashboard | FACT: Implemented | `project/app/Http/Controllers/Admin/PrinterController.php` |
| POD | Mockup template library | FACT: Modeled in schema/code; live data empty | `project/app/Models/MockupTemplate.php`, live DB count `0` |
| POD | Pricing options | FACT: Modeled in schema/code; live data empty | `project/app/Models/PodPricingOption.php`, live DB count `0` |
| API | Public API | FACT: Not implemented | `project/routes/api.php` is empty |

### CRUD and Domain Operations
- FACT: `users`, `admins`, `roles`, `products`, `orders`, `vendor_orders`, `verifications`, `payment_gateways`, `print_jobs`, `mockup_templates`, and `pod_pricing_options` are active domain entities in code or schema.
- FACT: `orders.cart` stores item lines as serialized JSON/TEXT instead of normalized `order_items`.
- FACT: Vendor product edit/update/delete is handled by raw product ID routes without observed ownership scoping.
- FACT: Vendor order detail, invoice, print, and license-update actions load orders by `order_number` without verifying that the current vendor owns the order.

### Validation Rules

| Area | Explicit Rules | Implicit / Missing Rules | Evidence |
|---|---|---|---|
| User registration | `email` required+unique, `password` confirmed, optional captcha | [RISK] No password strength, no rate limiting, MD5 verification token | `project/app/Http/Controllers/Auth/User/RegisterController.php` |
| User login | email/password required | [RISK] No throttle middleware | `project/app/Http/Controllers/Auth/User/LoginController.php`, route list |
| Vendor verification | attachments limited to `jpeg,jpg,png,svg` and `10MB` | [RISK] Files are stored in public path, no antivirus/quarantine | `project/app/Http/Controllers/Vendor/VendorController.php` |
| Product creation | downloadable `file` limited to ZIP | [RISK] product photo upload accepts file or base64 string; print image accepts base64 payload; no image dimension or content validation | `project/app/Http/Controllers/Vendor/ProductController.php:380-520` |
| Product update | downloadable `file` limited to ZIP | [RISK] `print_file` upload has no MIME rule in the shown update path | `project/app/Http/Controllers/Vendor/ProductController.php:849-930` |
| Checkout wallet check | none beyond numeric casts from raw query params | [RISK] Uses `$_GET` directly | `project/app/Http/Controllers/Front/CheckoutController.php` |

### Error Scenarios and Handling
- FACT: Most auth and vendor AJAX endpoints return JSON error arrays or redirect strings rather than a normalized error contract.
- FACT: Admin printer/manufacturing actions mostly redirect back with flash messages.
- FACT: The manufacturing controller uses placeholder KPI values (`quality_score = 98`, `defect_rate = 2`, `success_rate = 98`).
- RISK: `Admin\VerificationController` contains dead `edit()` and `update()` methods referencing `Order` without an import, indicating copy-paste residue in a sensitive admin flow.

### Conflicts or Undefined Behaviors
- [RISK] Duplicate route registration exists for `the/genius/ocean/2441139` and `finalize`. Evidence: `project/routes/web.php:1773-1775`, `project/routes/web.php:1788-1789`.
- [RISK] `printjob_routes.php` documents routes but is not loaded by `RouteServiceProvider`; active routes were copied into `web.php`, increasing drift risk. Evidence: `project/routes/printjob_routes.php`, `project/app/Providers/RouteServiceProvider.php`.
- [RISK] `users.role_type` suggests multi-role actors (`customer`, `vendor`, `printer`, `manufacturer`, `admin`), but active middleware and models still depend on `is_vendor` and admin roles.
- [RISK] `products` contains both legacy POD fields (`is_pod`, `production_cap`) and newer POD fields (`is_pod_product`, `pod_type`, `requires_manufacturing`, `requires_printing`) with no single canonical model contract.

## 5. Non-Functional Requirements

| Requirement | CURRENT STATE | RISK | TARGET |
|---|---|---|---|
| Scalability | FACT: No benchmark evidence. Live dataset is tiny (`3` users, `38` orders, `0` products). Routes and controllers are monolithic. | [RISK] Front-end requests perform repeated DB lookups and counter writes in `FrontBaseController`; no async job offloading in the current default runtime. | [RECOMMENDATION] Target `P95 < 400ms` for catalog/checkout pages at `20-30` req/s steady state for initial Tunisia launch; add load tests before go-live. |
| Performance | FACT: `orders.cart` JSON is parsed repeatedly, product/order models query related state inside accessors, and only minimal indexes exist on hot tables. | [RISK] Order and fulfillment dashboards will degrade quickly as order count grows. | [RECOMMENDATION] Add normalized order items, composite indexes on `orders(status, print_status, created_at)`, `vendor_orders(user_id, status)`, `products(user_id, status, is_pod)`. |
| Availability | FACT: Planned deployment is a single Docker Compose stack with one web container, one MariaDB container, and one Redis container. | [RISK] Single-host, single-DB deployment has a single point of failure. | [RECOMMENDATION] Define `99.5%` SLA initially, with tested restore and redeploy paths before public launch. |
| Reliability | FACT: Migration ledger is inconsistent, queues are optional, and there is no jobs table for database queues. | [RISK] Recovery, worker enablement, and deploy rollback are unreliable. | [RECOMMENDATION] Establish reproducible schema migration state and add queue schema before enabling workers. |
| Maintainability | FACT: `165` controllers, `422` views, active record models with UI/business logic mixing, and docs contradict code. | [RISK] High change cost and regression risk. | [RECOMMENDATION] Modularize by domain inside the monolith and remove dead/legacy paths. |
| Extensibility | FACT: Role permissions are comma-separated strings and `payment_gateways.currency_id` is matched by `LIKE`. | [RISK] Adding new roles, markets, or gateways will keep increasing drift. | [RECOMMENDATION] Normalize permissions, gateway-market mapping, and POD workflow state. |
| Auditability | FACT: Default logging is single-file debug; no structured audit log or correlation IDs were found. | [RISK] Hard to investigate fraud, data access, or webhook issues. | [RECOMMENDATION] Add structured application/audit logs for auth, payment, product, and fulfillment actions. |
| Accessibility / i18n | FACT: Locale and currency switching exist, default app locale/timezone are `en`/`UTC`; no accessibility policy or tests were found. | [RISK] Tunisia-localized deployment may still behave as generic English/UTC app. | [RECOMMENDATION] Set production locale/timezone explicitly, run WCAG checks on core pages, and verify RTL/LTR expectations if Arabic/French are planned. |
| Offline support | FACT: No mobile client or offline-capable frontend architecture was found. | [MISSING] Not applicable today; future mobile work would need a new API layer. | [RECOMMENDATION] Treat offline/mobile as a separate product stream. |
| Data retention & recovery | FACT: Manual backup/restore scripts and deployment docs exist; no automated retention job or RPO/RTO policy was found. | [RISK] Restore readiness is undocumented operationally and untested in CI/CD. | [RECOMMENDATION] Define RPO `<= 24h`, RTO `<= 4h`, and automate daily DB backups plus periodic restore drills. |
## 6. Architecture Analysis

### 6.1 Style
- FACT: The application is a **server-rendered Laravel monolith** with a non-standard public path and root-level asset directory. Evidence: `index.php`, `project/bootstrap/app.php`, `project/resources/views/layouts/front.blade.php`.
- INFERENCE: It is not a modular monolith in a strict domain-driven sense because domain logic, persistence, presentation helpers, and file-system operations are heavily intermixed inside controllers and Eloquent models.

### 6.2 Boundaries

| Bounded Context | Current Boundary | Quality |
|---|---|---|
| Commerce Catalog | Shared models/controllers/views inside same namespace tree | FACT: Weak boundary |
| Accounts & Identity | Shared `users` table, custom token logic, multiple auth controllers | FACT: Weak boundary |
| Vendor Operations | Separate vendor controllers/views, but shared core tables and weak ownership checks | FACT: Weak boundary |
| Payments | Separate controllers per gateway, shared gateway table | FACT: Moderate boundary |
| Fulfillment | Separate admin printer/manufacturing controllers, shared `orders` and `print_jobs` | FACT: Moderate boundary |
| Platform Settings | Global singleton tables injected into all views | FACT: Cross-cutting and tightly coupled |

### 6.3 Communication
- FACT: Application communication is mostly synchronous HTTP request/response and direct database queries.
- FACT: Payment systems communicate through synchronous redirects and POST notify/callback endpoints.
- FACT: Queue, broadcast, and scheduler capabilities exist in deployment scaffolding, but the runtime defaults remain `sync` queue and `null` broadcast unless production env overrides.
- [MISSING] No domain-event model or durable async message architecture was observed.

### 6.4 Dependencies
- FACT: Controllers depend directly on Eloquent models, DB facades, filesystem operations, session state, and sometimes global PHP variables.
- FACT: Models such as `Product`, `Order`, and `PrintJob` contain presentation helpers and workflow logic.
- OPINION: Coupling is high and cohesion is inconsistent; this slows safe change.

### 6.5 Strengths / Weaknesses

**Strengths**
- FACT: The project covers the entire marketplace-to-fulfillment journey in one codebase.
- FACT: Session auth, CSRF, and basic Laravel middleware are in place for web flows.
- FACT: A Docker/VPS deployment path now exists with clear environment separation guidance.
- FACT: A small readiness test suite was added and currently passes (`8` tests, `96` assertions).

**Weaknesses**
- FACT: Security-critical legacy endpoints remain exposed.
- FACT: Ownership checks are inconsistent in vendor flows.
- FACT: Schema drift is visible between database columns, models, docs, and controllers.
- FACT: Queue/database/deployment defaults are inconsistent.
- FACT: Operational analytics use placeholders and empty live manufacturing data.
- FACT: Documentation overstates readiness relative to the live database.

### 6.6 Target Architecture (RECOMMENDED)

| Change | Rationale | Priority |
|---|---|---|
| Remove installer/update endpoints and all duplicate finalize routes | Eliminates critical remote-write risk | Immediate |
| Keep monolith, but split by domains (`Identity`, `Catalog`, `Checkout`, `Fulfillment`, `Admin`) with service classes | Reduces coupling without a risky microservice rewrite | Immediate |
| Normalize order items and fulfillment tables | Enables reliable manufacturing, analytics, and indexing | Short-term |
| Replace string-based RBAC with normalized permission tables | Removes brittle role parsing and allows printer/manufacturing provisioning | Short-term |
| Define one POD schema contract and deprecate the other | Prevents future drift between `is_pod` and `is_pod_product` models | Short-term |
| Add explicit webhook/auth boundary layer for payment callbacks | Improves security, replay handling, and idempotency | Short-term |
| Add structured logs, health metrics, and backup automation | Required for operational maturity | Mid-term |

## 7. Backend Analysis

- FACT: Backend stack is Laravel `8.65`, PHP `^7.3|^8.0` in app dependencies, and PHP `8.1-apache-bookworm` in the production image. Evidence: `project/composer.json`, `deploy/docker/apache/Dockerfile`.
- FACT: API design is not REST-centric; the system exposes `808` mostly web/session routes and an empty `routes/api.php`.
- FACT: `config/auth.php` defines an `api` guard with `driver = 'jwt'`, but no JWT package is declared in `project/composer.json`.
- FACT: DTO/entity separation was not observed; controllers pass request arrays directly into model `fill()` and `update()` operations.
- FACT: Transactions were not observed around high-mutation flows such as product create/update, checkout, verification, or multi-step admin actions.
- FACT: Idempotency exists in one important place: `OrderHelper::create_print_jobs()` avoids duplicate print jobs for the same order/product/quantity/tier/design combination.
- FACT: Validation is present but inconsistent and often minimal.
- FACT: Logging exists, but default channel is `stack -> single` at `debug`, with no structured format or request correlation.
- FACT: Code organization is controller-heavy and model-heavy; service-layer isolation is rare.

### Missing Concerns
- [MISSING] No explicit anti-fraud or payment replay protection policy was observed.
- [MISSING] No centralized authorization policy layer (e.g., Laravel policies/gates for object ownership) was observed.
- [MISSING] No persistent domain event or outbox pattern was observed.
- [MISSING] No evidence of optimistic locking or version control for high-contention records.

## 8. Frontend / Client Analysis

- FACT: The frontend is server-rendered Blade with jQuery-era asset inclusion rather than a client SPA.
- FACT: `project/resources/views/layouts/front.blade.php` contains large inline CSS blocks, inline JS globals, and a direct DB query for `generalsettings`.
- FACT: The same layout defines extensive dark-theme CSS but then forces `data-theme = light` and writes `theme = light` into localStorage.
- FACT: Vendor UI in `project/resources/views/layouts/vendor.blade.php` exposes POD-oriented navigation items such as `My Design Studio`, `Bulk Design Upload`, and `My Royalties`, but these are partially gated by `pod_designer_mode`, which is `0` in live settings.
- FACT: Asset compilation via Laravel Mix exists in `project/webpack.mix.js`, but active layouts primarily reference root-level `/assets/...` files, so the modern build pipeline is not the primary frontend delivery path.

### Component Modularity / Reuse
- FACT: The view surface is large (`422` Blade files). No component system or frontend state store was observed.
- OPINION: Reuse appears mostly layout-partial based, not component-driven, which is workable for a monolith but expensive to maintain at this scale.

### Forms / Validation / Error UX
- FACT: Many forms expect JSON responses with string errors or redirect URLs, indicating AJAX-heavy legacy form handling.
- RISK: Error contracts are inconsistent across auth, vendor, and admin flows, increasing frontend brittleness.

### Performance
- FACT: Front requests initialize settings, locale, currency, popup state, theme resolution, and analytics counters inside `FrontBaseController`.
- FACT: `FrontBaseController` writes to `counters` based on `HTTP_REFERER` and `HTTP_USER_AGENT` on page requests.
- RISK: This introduces avoidable synchronous database writes on read traffic and will amplify load.

### Security
- FACT: The client primarily relies on session cookies, not browser-stored bearer tokens.
- FACT: No CSP, HSTS, X-Frame-Options, Referrer-Policy, or Permissions-Policy configuration was found in app/config code.
- RISK: Public file paths and inline scripting increase the blast radius of any template injection or upload bypass.

### Accessibility / Responsiveness
- FACT: The UI uses Bootstrap-era patterns and appears responsive by design intent, but no accessibility audit artifacts were found.
- [MISSING] No automated accessibility tests or semantic compliance checks were found.
## 9. Mobile Analysis (if applicable)

- FACT: No `android`, `ios`, `flutter`, `react-native`, or other mobile-client directories were found in the repository.
- FACT: No mobile-specific API, sync model, or local persistence layer was observed.
- CURRENT STATE: Not applicable to the current codebase.
- [MISSING] Offline-first design.
- [MISSING] Local persistence model.
- [MISSING] Sync strategy, retries, backoff, and conflict resolution for mobile use cases.
- [RISK] If a mobile app is planned later, the current lack of versioned API endpoints and the misconfigured `api` auth guard will block it.

## 10. Data & Database Analysis

### Entities and Relationships

| Entity | Purpose | Relationships | Evidence |
|---|---|---|---|
| `users` | Buyers and active vendors in legacy logic | `hasMany orders`, `products`, `verifies`, etc. | `project/app/Models/User.php`, live schema |
| `admins` | Back-office operators | `belongsTo role` | `project/app/Models/Admin.php`, live schema |
| `roles` | Admin permission groups | `hasMany admins`; permissions stored in `section` text | `project/app/Models/Role.php`, live schema |
| `verifications` | Vendor verification submissions/warnings | `belongsTo user` | `project/app/Models/Verification.php`, live schema |
| `products` | Catalog items including POD flags | `belongsTo user`, used inside cart blobs | `project/app/Models/Product.php`, live schema |
| `orders` | Customer order header plus cart snapshot | `hasMany vendororders`, `tracks`, `notifications`, `belongsTo printer` | `project/app/Models/Order.php`, live schema |
| `vendor_orders` | Vendor-specific order projections | Links vendor to order number and status | live DB counts, vendor controllers |
| `payment_gateways` | Gateway definitions and credentials | `belongsTo currency`; route-link helper methods | `project/app/Models/PaymentGateway.php`, live schema |
| `print_jobs` | POD line-item production work | `belongsTo order`, `product`, `printer` | `project/app/Models/PrintJob.php`, live schema |
| `mockup_templates` | POD mockup positioning metadata | linked by `products.mockup_template_id` | `project/app/Models/MockupTemplate.php`, live schema |
| `pod_pricing_options` | POD option pricing | grouped by category | `project/app/Models/PodPricingOption.php`, live schema |

### Normalization vs Pragmatism
- FACT: `orders.cart` stores embedded item snapshots as JSON/TEXT; this is pragmatic for snapshotting but weak for relational queries and integrity.
- FACT: `roles.section` stores permissions as comma-separated text.
- FACT: `payment_gateways.currency_id` is matched by `LIKE` or wildcard `*`, implying a denormalized mapping.
- OPINION: These choices accelerated legacy feature delivery but now materially hurt reporting, authorization, and market/gateway correctness.

### Indexing Strategy
- FACT: `print_jobs` migration adds useful indexes on `status, priority, created_at`, `printer_id`, `order_id`, and `product_id`.
- FACT: Live `orders`, `users`, `verifications`, `payment_gateways`, and `vendor_orders` tables show only primary-key indexes in the inspected database; `products` only adds FULLTEXT on `name` and `attributes`.
- RISK: Current fulfillment and vendor dashboards rely on filters like `status`, `print_status`, `user_id`, and `order_number` without corresponding inspected indexes.

### Query Patterns and Hotspots
- FACT: Manufacturing and order models repeatedly `json_decode()` `orders.cart` to infer product-level state.
- FACT: `FrontBaseController` performs per-request DB reads and writes for settings, language, currency, theme, and counters.
- FACT: `Order::getPrintFileAttribute()` queries `Product` per cart item.
- FACT: `PaymentGateway::scopeHasGateway()` uses `LIKE` against `currency_id`.
- OPINION: These patterns will become hotspots before the system hits moderate production traffic.

### Constraints and Integrity
- FACT: The alignment migration intentionally avoids foreign keys because of legacy ID-type differences.
- FACT: No soft delete or audit versioning pattern was observed on core commerce entities.
- RISK: Referential integrity is mostly application-enforced rather than database-enforced.

### Soft Delete / Audit / Versioning
- FACT: No Eloquent `SoftDeletes` usage was observed in the reviewed core models.
- FACT: No row-level audit trail table or version history mechanism was observed.

### Multi-tenancy
- FACT: This is not a true multi-tenant architecture; it is a single-tenant application with multi-vendor business data sharing a database.
- RISK: Weak vendor scoping creates cross-vendor exposure risk.

### Backup / Restore Strategy
- FACT: Manual backup/restore scripts and deployment guides exist.
- FACT: No automated backup schedule or retention enforcement was found in CI/CD or compose configuration.

### Data Lifecycle Risks
- [RISK] Customer PII is duplicated into `orders` and `users` without observed retention/anonymization policy.
- [RISK] Public file storage under root assets mixes user uploads, product images, and temporary files.
- [RISK] Schema drift between live data and migration history makes restore validation harder.

## 11. API & Integration Analysis

- FACT: There is no meaningful API layer; the application surface is primarily web routes and gateway callback endpoints.
- FACT: The table below catalogs the business-critical externally relevant endpoints, not all `808` routes.

| Endpoint | Method | Auth | Idempotent | Notes |
|---|---|---|---|---|
| `/user/register` | POST | Public | No | Customer/vendor registration, MD5 verification token generation |
| `/user/register/verify/{token}` | GET | Public | Partially | Email verification link |
| `/user/login` | POST | Public | No | Session login, no observed throttle |
| `/admin/login` | POST | Public | No | Admin session login, no observed throttle |
| `/vendor/verify` | POST | Vendor session | No | Uploads verification attachments to public assets |
| `/vendor/products/edit/{id}` | POST | Vendor session | No | [RISK] No ownership check observed |
| `/vendor/products/delete/{id}` | DELETE | Vendor session | No | [RISK] No ownership check observed |
| `/vendor/orders/{slug}` | GET | Vendor session | Yes | [RISK] Loads order by slug without vendor ownership check |
| `/checkout/payment/{slug1}/{slug2}` | GET | Public | Yes | Loads gateway UI and filters by Tunisia mode |
| `/checkout/payment/wallet-check` | GET | Auth session | Yes | Uses raw `$_GET` query params |
| `/checkout/payment/flouci-submit` | POST | Public | No | Tunisia gateway submit route exists |
| `/checkout/payment/flouci-notify` | POST | Public | Depends | Webhook route exists but is not in CSRF exception list |
| `/checkout/payment/konnect-notify` | POST | Public | Depends | Same risk as above |
| `/checkout/payment/paymee-notify` | POST | Public | Depends | Same risk as above |
| `/admin/printer/*` | GET/POST | Admin + `print_production` | Mixed | Printer workflow routes |
| `/admin/manufacturing/*` | GET/POST | Admin + `manufacturing` | Mixed | Manufacturing workflow routes |
| `/admin/printjobs/*` | GET/POST | Admin + `print_production` | Mixed | Line-item print jobs |
| `/the/genius/ocean/2441139` | POST | Public | No | **Critical:** file write/delete endpoint from installer logic |
| `/finalize` | GET | Public | No | Installer cleanup route, duplicated in route file |
| `/update-finalize` | GET | Public | No | Updates version and clears caches |

### Auth Flows
- FACT: Web/customer auth uses Laravel session guard `web`; admin auth uses session guard `admin`.
- FACT: Password reset is custom, not Laravel broker-based, and stores reset tokens in `users.email_token` / `admins.email_token`.
- FACT: The `api` guard is configured as `jwt`, but there is no active API surface and no JWT dependency in composer.

### Versioning Strategy
- FACT: No API versioning strategy exists because no meaningful API exists.
- [RISK] Any future mobile or third-party integration will need a new versioned contract.

### Error Model Consistency
- FACT: Error handling is inconsistent between JSON arrays, string payloads, redirect URLs, and flash messages.
- OPINION: This is acceptable for a legacy jQuery Blade app internally, but it is poor for automation or client extensibility.

### Rate Limiting and Quotas
- FACT: Only the empty `api` middleware group has `throttle:60,1`.
- FACT: Login, forgot password, verification, and checkout endpoints do not show route-level throttling in route inventory.

### Retries, Timeouts, Circuit Breakers
- FACT: Deployment/worker configs do not define application-level circuit breakers.
- FACT: Vendor CSV import uses cURL with `CURLOPT_CONNECTTIMEOUT = 20` and follows redirects.
- [MISSING] No generic external-call retry policy was observed.

### External Dependency Risks
- [RISK] Payment gateway route breadth increases operational burden without corresponding seed/config discipline.
- [RISK] SMTP/email flows are synchronous in critical user journeys.
- [RISK] GHCR/VPS deployment depends on secret hygiene and a single remote host.
## 12. Security Analysis (DETAILED)

| Description | Severity | Impact | Likelihood | Evidence | Mitigation |
|---|---|---:|---:|---|---|
| Public installer endpoint writes arbitrary file path from `p1` and deletes arbitrary file path from `p2` | Critical | RCE, defacement, data loss | High | `project/routes/web.php:1773`, `project/routes/web.php:1788`, `project/app/Http/Controllers/Front/FrontendController.php:539-553` | Remove route and controller method immediately; rotate secrets and review filesystem integrity |
| Vendor product edit/delete actions do not scope by `user_id` | Critical | Cross-vendor product takeover or deletion | High | `project/app/Http/Controllers/Vendor/ProductController.php:816-865`, `project/app/Http/Controllers/Vendor/ProductController.php:1550-1608` | Scope queries by current vendor or use policies |
| Vendor order detail/invoice/license routes load orders by slug only | Critical | Cross-vendor order/PII exposure and unauthorized license edits | High | `project/app/Http/Controllers/Vendor/OrderController.php:63-95` | Scope order reads/writes by vendor ownership via `vendor_orders` |
| Authentication has no observed brute-force throttling on customer/admin login or forgot-password flows | High | Credential stuffing, account enumeration | High | route middleware from `php artisan route:list --json`, `project/app/Http/Kernel.php` | Add route throttling and lockout telemetry |
| Verification and reset tokens use MD5 of predictable inputs/time | High | Token prediction / weak entropy | Medium | `project/app/Http/Controllers/Auth/User/RegisterController.php`, `project/app/Http/Controllers/Auth/User/ForgotController.php`, `project/app/Http/Controllers/Auth/Admin/ForgotController.php` | Replace with `Str::random()` or signed, expiring tokens |
| Password reset tokens are stored on user/admin rows with no expiry policy | High | Replay window remains open | Medium | forgot controllers, absence of broker config in `project/config/auth.php` | Use Laravel password broker or expiring signed tokens |
| Admin authorization relies on comma-separated text permissions and hard-coded admin ID `1` bypass | High | Privilege escalation and fragile RBAC | Medium | `project/app/Http/Middleware/Permissions.php`, `project/app/Models/Admin.php`, `project/app/Models/Role.php` | Normalize permissions and remove hard-coded superuser bypass |
| Tunisia payment notify routes are under `web` middleware but absent from CSRF exception list | High | Legitimate callbacks may fail or tempt insecure workaround patches | High | `project/routes/web.php:1720-1732`, `project/app/Http/Middleware/VerifyCsrfToken.php` | Move callbacks to signed API/webhook group or explicitly exempt/verify them correctly |
| Product import fetches remote URLs with cURL and follows redirects | High | SSRF and internal network probing | Medium | `project/app/Http/Controllers/Vendor/ProductController.php:308-318` | Validate destination hosts, disallow private IP ranges, proxy downloads through a safe fetcher |
| File uploads are written directly to public asset paths | High | Malicious content hosting, unsafe file exposure | Medium | vendor/product controller file moves to `assets/...` | Store uploads outside public root and serve via controlled responses |
| `walletcheck()` uses raw `$_GET` instead of validated request input | Medium | Input confusion and inconsistent validation | Medium | `project/app/Http/Controllers/Front/CheckoutController.php:74-93` | Use Laravel request validation and typed input |
| No security headers (CSP/HSTS/X-Frame-Options/Referrer-Policy) were found | Medium | Increases XSS/clickjacking impact | Medium | repo-wide search of `project/app` and `project/config` | Add reverse-proxy and app headers |
| CORS config allows all origins/methods/headers on `api/*` | Medium | Over-permissive if API routes are later added | Low today | `project/config/cors.php` | Restrict by environment and real origins before exposing APIs |
| Session config leaves `same_site` null and `secure` env-driven | Medium | Cookie behavior depends on deployment correctness | Medium | `project/config/session.php` | Set `same_site=lax` or `strict`; enforce secure cookies in production |
| Trust proxy config leaves proxy list unset | Medium | Incorrect client IP/scheme handling behind reverse proxy | Medium | `project/app/Http/Middleware/TrustProxies.php` | Explicitly configure trusted proxy/network in production |
| Payment and mail configuration appear partly DB-driven and partly env-driven | Medium | Secret sprawl and audit difficulty | Medium | `payment_gateways.information`, `generalsettings` mail fields, env templates | Standardize secret source of truth and rotate credentials |
| Logging is not structured and may hinder incident response | Medium | Slow investigations and incomplete audit trail | Medium | `project/config/logging.php` | Add JSON logs and audit events |
| Dependency vulnerability posture is [MISSING] | Medium | Unknown exposure from outdated packages | Medium | No audit report present; Laravel 8 and legacy gateway SDKs in composer | Run `composer audit`, OS package scans, and container image scans in CI |
| API guard configured as JWT without supporting package | Low | Confusing auth surface and future misconfiguration | Medium | `project/config/auth.php`, `project/composer.json` | Remove dead guard or add supported implementation |
| No mobile-specific security model exists | Low | Not currently applicable | Low | no mobile client found | Create a dedicated API/auth design before any mobile effort |
| Infrastructure is single-VPS single-database by design | Medium | Host compromise or outage causes full service outage | Medium | `deploy/production/docker-compose.yml` | Add backups, hardened host config, and documented restore/rollback |

## 13. Performance & Scalability

### Bottlenecks
- FACT: Database access is the main bottleneck risk because hot workflows decode cart blobs, query related records inside loops, and lack supporting indexes.
- FACT: Frontend requests perform synchronous counter writes from `FrontBaseController`.
- FACT: Fulfillment dashboards depend on scanning `orders.cart` JSON for quantities.
- FACT: Active asset delivery bypasses a modern build pipeline and uses many legacy root-level assets.

### Capacity Estimates
- FACT: There is no benchmark or throughput test evidence.
- [ASSUMPTION] For an initial Tunisia-market launch, a realistic first target is `1,000-5,000` registered users, `100-300` daily orders, and `P95 < 400ms` on non-payment pages.
- [RISK] The current schema/query design is unlikely to handle that cleanly without index and normalization work.

### Caching Strategy
- FACT: App-level settings, pagesettings, SEO, social settings, fonts, currencies, and languages are cached using Laravel cache with one-day TTLs in `AppServiceProvider`.
- FACT: Default cache store is file-based; production env template also uses file cache.
- RISK: Multi-node scaling would not share cache or session state under current defaults.

### Async Processing Opportunities
- RECOMMENDATION: Move mail sends, image processing, gateway reconciliation, and manufacturing analytics aggregation into queued jobs.
- RECOMMENDATION: Add durable webhook processing jobs with idempotency keys.

### Horizontal Scaling Readiness
- FACT: Not ready. Sessions and cache are file-based by default; uploads live on local volumes; there is no shared object store.

### Load Test Plan
- RECOMMENDATION: Run at least three scenarios before release:
  - Catalog browse: `20 req/s` for `15` minutes across homepage, category, product pages.
  - Checkout auth flow: `5 req/s` registration/login/cart/checkout without payment callback.
  - Fulfillment dashboard: concurrent admin/manufacturing/printer access against `10k+` seeded orders.
## 14. DevOps & Infrastructure

- FACT: Deployment now targets Docker on a VPS using GitHub Actions, GHCR, Apache in-container, MariaDB, Redis, and optional queue/scheduler services.
- FACT: Workflow triggers on `main`, `master`, and `release/**`.
- FACT: Deployment runs migrations and Laravel cache commands after container startup.
- FACT: The production env template sets `QUEUE_CONNECTION=database`, `SESSION_DRIVER=file`, and `CACHE_DRIVER=file`.
- FACT: The compose stack exposes one HTTP port, mounts named volumes for storage/uploads/temp files, and provisions MariaDB and Redis containers.
- FACT: Backup/restore guidance exists in docs, but operational automation is not in the workflow or compose file.

### Environments
- FACT: Dev and production patterns are present; stage/preprod environment definitions were not observed.
- [MISSING] No separate staging workflow or environment promotion process was found.

### CI/CD Pipeline
- FACT: The pipeline builds and pushes a Docker image, SCPs deployment files, SSHes into the VPS, starts containers, runs migrations, and warms caches.
- [RISK] There are no quality gates for static analysis, dependency audit, security scan, or full test suite before deploy.

### Containerization and Images
- FACT: Production image uses Apache + PHP 8.1 and installs extensions for GD, intl, mbstring, pdo_mysql, zip, etc.
- FACT: Entry point clears stale cached config/routes and prepares writable directories.

### Reverse Proxy / Gateway Config
- FACT: Docs assume Nginx on VPS forwarding to the container’s bound HTTP port.
- [MISSING] No committed Nginx config file exists inside the repo for enforcement or linting.

### Secrets Handling
- FACT: GitHub Actions expects many deployment secrets, including full `.env` content.
- RISK: Gateway settings also appear to live in DB rows and `generalsettings`, creating split secret storage.

### Monitoring / Logging / Alerting
- FACT: No Prometheus, Grafana, Sentry, ELK, health dashboard, or alerting stack was observed in repo/deploy files.
- [MISSING] Incident escalation and on-call process documentation.

### Backup / DR
- FACT: Manual scripts/docs exist.
- [MISSING] No automated retention, offsite replication, or restore test cadence.

### Rollback Strategy
- FACT: Docs describe rolling back by setting an older GHCR image tag in `.stack.env` and recreating services.
- RISK: Rollback does not address incompatible forward migrations already applied.

### Infrastructure Risks
- [RISK] Single-host deployment.
- [RISK] No observed automated security patching or image vulnerability scanning.
- [RISK] Queue profile can be enabled, but the required `jobs` table is absent in the current DB and no create-jobs migration was found.

## 15. Observability

- Logs
  - FACT: Logging is configured via Monolog with `stack -> single` by default.
  - [MISSING] No JSON log formatter, request ID middleware, or audit log channel.

- Metrics
  - FACT: The app records traffic counters in the database through `FrontBaseController`, but this is not RED/USE instrumentation.
  - [MISSING] No latency, error-rate, saturation, queue-depth, or payment success metrics pipeline.

- Tracing
  - FACT: No distributed tracing instrumentation was found.

- Dashboards and Alerts
  - FACT: Manufacturing analytics pages exist for business reporting.
  - [MISSING] No operational dashboards or automated alerts were found.

- Incident Response Readiness
  - OPINION: Low. The system currently lacks the structured logs, metrics, and procedures needed for efficient live-incident handling.

## 16. Quality Assurance

- FACT: The repository currently contains only the base test harness plus `5` readiness feature test files.
- FACT: Running `php artisan test --testsuite=Feature --filter=Readiness` passes `8` tests and `96` assertions.
- FACT: Those tests cover schema presence, login gating, verification state, string-based permissions, and idempotent print-job creation.
- [MISSING] No unit test suite, browser/e2e suite, performance suite, security regression suite, or API contract suite was found.
- [MISSING] No factories/seeders dedicated to large-scale manufacturing or gateway callback scenarios were reviewed.
- RECOMMENDATION: Release criteria should require:
  - unit coverage for helpers/models with state transitions,
  - integration tests for checkout/payment callbacks,
  - authorization tests for vendor/admin boundaries,
  - smoke tests against deployment containers.

## 17. Compliance & Governance

- Data privacy
  - FACT: Customer PII is stored in `users` and duplicated inside `orders`.
  - [MISSING] No privacy policy, data minimization policy, or anonymization strategy was found in code/docs.

- Audit requirements
  - [MISSING] No formal audit event log or compliance mapping was found.

- Retention policies
  - [MISSING] No retention/deletion job was found for orders, uploads, or verification documents.

- Access governance
  - FACT: Access control exists but is role-string based and partially hard-coded.
  - [RISK] This is not a strong governance model for production operations.

- Change management
  - FACT: Deployment is branch-based through GitHub Actions.
  - [MISSING] No required review gates or protected environment approvals were found in the workflow.

- Documentation completeness
  - FACT: Repo-level workflow docs exist.
  - RISK: Docs and live code/data are currently inconsistent, which reduces their governance value.
## 18. Risk Register

| ID | Risk | Area | Severity | Probability | Impact | Evidence | Mitigation | Priority |
|---|---|---|---|---|---|---|---|---|
| R1 | Arbitrary file write/delete endpoint | Security | Critical | High | Critical | `FrontendController::subscription`, public route | Remove endpoint, review compromise, rotate secrets | P0 |
| R2 | Vendor can edit/delete products by raw ID | Security | Critical | High | High | `Vendor\ProductController` | Add policy/ownership scoping | P0 |
| R3 | Vendor can access order details by slug without ownership check | Security | Critical | High | High | `Vendor\OrderController` | Scope via `vendor_orders` ownership | P0 |
| R4 | Migration history does not match live schema | Data/DevOps | High | High | High | `migrate:status`, alignment migration | Rebuild canonical schema baseline | P0 |
| R5 | Tunisia gateway code not reflected in gateway data | Functional | High | High | High | live `payment_gateways`, Tunisia routes | Seed/configure Tunisia gateways | P0 |
| R6 | Fulfillment queues depend on `orders.status='processing'`, but live orders are not in that state | Functional | High | High | Medium | live order counts, manufacturing controller | Normalize order/print transitions | P1 |
| R7 | No `jobs` table for database queue profile | DevOps | High | High | Medium | production env template, no jobs table | Add migration and validate workers | P1 |
| R8 | Printer/manufacturing roles absent in live `roles` data | Authorization | High | High | Medium | live roles table, controller expectations | Seed normalized roles/permissions | P1 |
| R9 | Weak MD5 verification/reset tokens without expiry | Security | High | Medium | Medium | auth controllers | Replace with signed expiring tokens | P1 |
| R10 | Payment notify routes not aligned with CSRF exceptions | Security/Reliability | High | High | Medium | routes + `VerifyCsrfToken` | Move to webhook middleware or signed verification | P1 |
| R11 | SSRF risk in vendor CSV import remote fetch | Security | High | Medium | Medium | cURL usage in import path | Restrict outbound fetch destinations | P1 |
| R12 | Sparse indexing on hot tables | Performance | Medium | High | Medium | `SHOW INDEX` results | Add composite indexes | P2 |
| R13 | No structured observability stack | Ops | Medium | High | Medium | logging/deploy config | Add logs/metrics/alerts | P2 |
| R14 | Docs contradict live implementation | Governance | Medium | High | Medium | workflow docs vs DB/code | Reconcile docs after code/data cleanup | P2 |
| R15 | Root-level public path and mixed asset layout increase deploy complexity | Architecture | Medium | Medium | Medium | `bootstrap/app.php`, root assets | Standardize public/file storage layout | P2 |

## 19. Gaps & Unknowns

- [MISSING] No Swagger/Postman/OpenAPI documentation was found.
  - Impact: External integration and future mobile/API work lack a stable contract.
  - Required decision: Decide whether the platform remains web-only or a public API will be introduced.

- [MISSING] No staging environment definition or promotion workflow was found.
  - Impact: Production deploys may be the first real integration test.
  - Required decision: Add a stage environment before public launch.

- [MISSING] No vulnerability scan report for PHP/composer or container images was found.
  - Impact: Security exposure from legacy dependencies is unknown.
  - Required decision: Add dependency and image scanning to CI.

- [MISSING] No explicit retention/compliance policy was found for customer and verification documents.
  - Impact: Privacy and legal obligations are unclear.
  - Required decision: Define retention windows and deletion workflows.

- [MISSING] No production load test evidence was found.
  - Impact: Capacity and latency claims cannot be trusted.
  - Required decision: Run baseline load tests before launch.

- [MISSING] No evidence was found that the live payment credentials for Tunisia gateways are configured.
  - Impact: Tunisia-localized checkout may fail at launch.
  - Required decision: Confirm which gateways are in-scope for launch and seed them.

## 20. Recommendations

### Immediate (0–2 weeks)

| Action | Owner Role | Expected Impact |
|---|---|---|
| Remove `the/genius/ocean/2441139`, `finalize`, and `update-finalize` public routes and associated installer logic | Senior Backend Engineer + Security Auditor | Eliminates critical compromise path |
| Add ownership policies/scoping for vendor product and order actions | Senior Backend Engineer | Closes critical BOLA/IDOR exposure |
| Normalize and seed launch data for roles, payment gateways, and POD configuration | Business Analyst + Backend Engineer | Makes launch flows reflect intended Tunisia scope |
| Reconcile migration history with a fresh canonical schema baseline | Principal Software Architect + Backend Engineer | Stabilizes deploy/rollback and future schema changes |
| Add route throttling to login, forgot-password, verification, and payment initiation endpoints | Security Auditor + Backend Engineer | Reduces brute-force and abuse risk |

### Short-term (2–6 weeks)

| Action | Owner Role | Expected Impact |
|---|---|---|
| Replace MD5 token flows with signed expiring tokens and Laravel-native password reset where possible | Senior Backend Engineer | Hardens identity flows |
| Introduce normalized permission tables or at least a structured permission schema | Principal Software Architect | Removes brittle role parsing |
| Add `jobs` table, queue tests, and webhook/job idempotency | Backend Engineer + DevOps/SRE | Makes workers and async tasks reliable |
| Normalize order items and fulfillment state transition rules | Principal Software Architect + Backend Engineer | Improves reporting and production queue correctness |
| Add structured logs, request IDs, and payment/audit events | DevOps/SRE + Backend Engineer | Improves observability and incident handling |

### Mid-term (6–12 weeks)

| Action | Owner Role | Expected Impact |
|---|---|---|
| Refactor monolith into domain-oriented service modules | Principal Software Architect | Improves maintainability and testability |
| Move uploads to managed storage outside public root | Backend Engineer + DevOps/SRE | Improves security and scale-readiness |
| Add performance indexing and load-tested fulfillment dashboards | Backend Engineer + QA Lead | Prevents scale regressions |
| Build staging environment and release gates | DevOps/SRE + QA Lead | Reduces deployment risk |

### Long-term (3–6 months)

| Action | Owner Role | Expected Impact |
|---|---|---|
| Introduce versioned API if mobile/partner integrations are a business goal | Principal Software Architect + Frontend/Mobile Architect | Enables controlled external consumption |
| Implement full operational monitoring and alerting | DevOps/SRE | Supports reliable 24/7 operations |
| Add compliance-grade retention, audit, and access governance | Security Auditor + Business Analyst | Supports scaling and regulatory confidence |
## 21. Roadmap

### Phase 0: Discovery
- Deliverables:
  - Canonical schema map
  - Launch-scope gateway/role matrix
  - Security remediation list
- Acceptance criteria:
  - All critical public endpoints inventoried
  - Launch actors and gateways approved
  - Migration baseline agreed

### Phase 1: Architecture/Foundation
- Deliverables:
  - Clean migration baseline
  - Domain ownership checks
  - Queue schema and worker readiness
- Acceptance criteria:
  - Fresh environment boots from migrations
  - Vendor BOLA tests pass
  - Queue worker starts successfully in Compose

### Phase 2: Security Hardening
- Deliverables:
  - Token-flow rewrite
  - Route throttling
  - Upload/storage hardening
  - Security headers
- Acceptance criteria:
  - Critical and high findings R1-R5 resolved
  - Security regression tests added

### Phase 3: Functional Completion
- Deliverables:
  - Tunisia gateway seed/config
  - Fulfillment state normalization
  - Role provisioning for printer/manufacturing
- Acceptance criteria:
  - End-to-end buyer -> vendor -> admin -> printer/manufacturing flow passes on staging

### Phase 4: Performance & Scale
- Deliverables:
  - Index improvements
  - Load test reports
  - Async mail/webhook processing
- Acceptance criteria:
  - P95 latency targets met on staging dataset
  - No critical N+1 or queue bottlenecks in core flows

### Phase 5: Operational Maturity
- Deliverables:
  - Monitoring/alerting
  - Automated backups and restore drills
  - Release gates and rollback playbook
- Acceptance criteria:
  - Restore test completed within defined RTO
  - Production deploy checklist and rollback approved

## 22. Final Verdict

- Production readiness score: **38 / 100**

### Rubric
- Security: `10/30`
  - FACT: Multiple high/critical findings remain.
- Functional completeness: `12/20`
  - FACT: Major flows exist, but data/config drift blocks reliable use.
- Data integrity: `4/15`
  - FACT: Migration/schema drift and denormalized hotspots remain.
- DevOps/Operations: `6/15`
  - FACT: Container deploy exists; observability and DR are immature.
- QA: `3/10`
  - FACT: Only a small readiness suite exists.
- Maintainability: `3/10`
  - FACT: High coupling and documentation drift.

### Blocking Issues
- Critical public installer-style file write/delete endpoint.
- Vendor authorization gaps on products and orders.
- Migration ledger does not represent actual schema state.
- Tunisia payment configuration is incomplete in live data.
- Queue/deployment template mismatch (`QUEUE_CONNECTION=database` without `jobs` table).

### Key Strengths
- Broad feature ambition across marketplace and POD operations.
- Working Docker/VPS deployment scaffold.
- Existing readiness tests and improved print-job idempotency.
- Clear opportunity to localize for Tunisia through gateway filtering and market rules already present in code.

### Final decision: **No-Go**
- Justification:
  - FACT: Internet-facing launch would expose critical security vulnerabilities and broken authorization boundaries.
  - FACT: Operational data/configuration does not yet support the documented Tunisia/POD workflow reliably.
  - OPINION: The project can become viable, but only after immediate hardening, schema normalization, and launch-scope cleanup.
