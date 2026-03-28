# Project Remediation Plan

## 1. Purpose

This plan translates the findings from `C:\laragon\www\xmerch\project\docs\PROJECT_FULL_ANALYSIS.md` into a concrete remediation program for making the project deployable and supportable in production.

- Current audit verdict: `No-Go`
- Current readiness score: `38/100`
- Planning goal: reach a `Conditional Go` state first, then a full `Go`
- [ASSUMPTION] Initial launch scope remains focused on the Tunisian market and the Tunisia-specific checkout/payment path

## 2. Planning Principles

1. Fix security-critical exposure before feature expansion.
2. Align code, schema, and live data before adding new business logic.
3. Narrow the launch scope to what is configured, testable, and supportable.
4. Prefer short stabilization phases with acceptance criteria over large refactors without checkpoints.
5. Keep the app as a monolith for now, but refactor it into clearer domain modules inside the monolith.

## 3. Target Outcomes

By the end of this plan, the project should have:

- No critical public attack surface left from legacy installer/update code
- Enforced object ownership for vendor resources
- A canonical schema and migration baseline
- Tunisia launch configuration aligned across routes, DB seed data, env, and UI
- Reliable buyer -> vendor -> admin -> manufacturing -> printer -> shipped flow
- Queue, logging, backup, restore, and deployment paths that work in production
- A release gate with automated tests for the highest-risk flows

## 4. Risk-to-Workstream Map

| Audit Risk | Issue | Workstream | Priority |
|---|---|---|---|
| R1 | Arbitrary file write/delete endpoint | Security Containment | P0 |
| R2 | Vendor product ownership gap | Authorization Hardening | P0 |
| R3 | Vendor order ownership gap | Authorization Hardening | P0 |
| R4 | Migration/schema mismatch | Schema Alignment | P0 |
| R5 | Tunisia gateways missing from live data | Payment Alignment | P0 |
| R6 | Fulfillment queue state mismatch | Fulfillment Stabilization | P1 |
| R7 | No `jobs` table with database queue target | Queue/Infra Alignment | P1 |
| R8 | Printer/manufacturing roles not provisioned | RBAC Alignment | P1 |
| R9 | Weak MD5 verification/reset tokens | Identity Hardening | P1 |
| R10 | Tunisia notify routes not aligned with CSRF/webhook handling | Payment Hardening | P1 |
| R11 | SSRF risk in remote import fetch | Upload/Import Hardening | P1 |
| R12 | Sparse indexing | Performance Hardening | P2 |
| R13 | No structured observability | Observability | P2 |
| R14 | Docs contradict implementation | Documentation Alignment | P2 |
| R15 | Root-level public path and mixed asset layout | Architecture Cleanup | P2 |

## 5. Delivery Phases

## Phase 0: Emergency Containment
Duration: `1-2 days`
Goal: remove production-blocking security exposure immediately

### Actions

| Action | Owner Role | Notes | Acceptance Criteria |
|---|---|---|---|
| Remove public routes `the/genius/ocean/2441139`, `finalize`, and `update-finalize` | Senior Backend Engineer | Delete route entries and controller methods or guard them behind local-only install mode | Routes no longer exist in `php artisan route:list` |
| Remove duplicate finalize/subscription legacy route declarations | Senior Backend Engineer | Clean route file drift | Only one authoritative definition remains for each kept route |
| Review filesystem for signs of prior misuse | Security Auditor | Compare current web root, bootstrap cache, and writable directories | No unexplained executable or modified files remain |
| Rotate all sensitive secrets before internet exposure | DevOps/SRE | App key if needed, DB creds, mail creds, gateway creds, GHCR token, VPS SSH key if compromise suspected | New secrets are generated and stored in GitHub/VPS/env |
| Put admin access behind temporary IP allowlist or VPN during remediation | DevOps/SRE | Optional but recommended for staging/prelaunch period | Admin UI is not broadly internet exposed |

### Exit Criteria

- No critical unauthenticated write/delete endpoint remains
- Secrets are rotated where risk justifies rotation
- Route inventory is re-exported and reviewed

## Phase 1: Authorization and Identity Hardening
Duration: `3-5 days`
Goal: close cross-user access gaps and harden auth flows

### Actions

| Action | Owner Role | Notes | Acceptance Criteria |
|---|---|---|---|
| Add ownership scoping to vendor product edit/update/delete | Senior Backend Engineer | Replace raw `Product::findOrFail($id)` with vendor-owned query or policy | Vendor cannot edit/delete another vendor’s product |
| Add ownership scoping to vendor order detail/invoice/license actions | Senior Backend Engineer | Scope through `vendor_orders.user_id` | Vendor cannot view another vendor’s order data |
| Add route throttling to user/admin login and forgot-password routes | Senior Backend Engineer | Use Laravel throttle middleware with sensible limits | Repeated auth abuse attempts are rate-limited |
| Replace MD5 verification and reset tokens | Senior Backend Engineer | Use high-entropy random tokens with expiry or Laravel-native password reset | Tokens are unguessable and expire |
| Decide canonical identity model | Principal Software Architect | Choose one active model: legacy `is_vendor` or newer `role_type`-based approach | Written decision recorded and implemented |
| Align verification state source | Backend Engineer | Choose either `verifications` table as source of truth or sync it into user-level fields | Verification checks read from one consistent state model |

### Exit Criteria

- Vendor BOLA/IDOR tests pass
- Auth routes are throttled
- Token generation is no longer MD5-based
- Identity model decision is documented

## Phase 2: Schema and Data Alignment
Duration: `1 week`
Goal: make schema reproducible and remove drift between code, DB, and migrations

### Actions

| Action | Owner Role | Notes | Acceptance Criteria |
|---|---|---|---|
| Build a canonical schema inventory from live DB and current code | Principal Software Architect + Backend Engineer | Compare live DB, migrations, models, and active queries | Canonical table/column matrix approved |
| Decide POD schema contract | Principal Software Architect | Standardize on either legacy POD columns (`is_pod`, `production_cap`, `print_file`) or the newer extended POD model | One active POD schema contract selected |
| Create a clean migration baseline for current schema | Senior Backend Engineer | Avoid destructive migrations; create a reproducible path for fresh environments | Fresh DB can be built from migrations only |
| Add missing operational schema such as `jobs` if database queue remains the production target | Backend Engineer | Match production env template | Queue worker can start successfully |
| Seed or migrate roles required for `print_production` and `manufacturing` | Backend Engineer | Remove hidden dependency on hard-coded role IDs | Roles exist and route access works |
| Seed Tunisia launch payment gateways | Backend Engineer + Business Analyst | Add `flouci`, `konnect`, `paymee`, optional `d17` if actually supported | Checkout gateway list matches launch scope |
| Add missing indexes on hot paths | Backend Engineer | `orders`, `vendor_orders`, `products`, `verifications`, gateway lookup | Query plans improve for high-use dashboards |

### Exit Criteria

- `php artisan migrate:fresh --seed` works in a disposable environment
- No required operational column/table is created only by manual DB edits
- Tunisia launch data exists in DB after seeding

## Phase 3: Payment and Checkout Hardening
Duration: `4-6 days`
Goal: make checkout safe, localized, and operationally correct

### Actions

| Action | Owner Role | Notes | Acceptance Criteria |
|---|---|---|---|
| Validate launch gateway set for Tunisia | Business Analyst + Backend Engineer | Restrict to gateways that are both implemented and credentialed | UI shows only supported gateways |
| Move gateway notify routes to a proper webhook strategy | Senior Backend Engineer | Either explicit CSRF exemptions with signature verification or a dedicated webhook group | Tunisia notify callbacks work without weakening web session security |
| Add callback idempotency and replay protection | Senior Backend Engineer | Especially for payment completion/order creation | Duplicate callbacks do not create duplicate orders |
| Replace raw `$_GET` access in `walletcheck()` with validated request handling | Backend Engineer | Normalize request validation | Wallet check rejects malformed input safely |
| Add gateway health/config checks in admin or deploy validation | Backend Engineer + DevOps/SRE | Fail fast on missing credentials | Production deploy warns on unconfigured active gateways |
| Test TN-only mode end to end | QA Lead | Verify buyer journey with Tunisia-only filtering | Checkout behaves consistently for Tunisia scope |

### Exit Criteria

- Active Tunisia payment methods are seeded, configured, and tested
- Notify/callback flows succeed in staging
- Order creation is idempotent under duplicate callback conditions

## Phase 4: Fulfillment and POD Workflow Stabilization
Duration: `1-2 weeks`
Goal: make POD manufacturing and printing truly usable

### Actions

| Action | Owner Role | Notes | Acceptance Criteria |
|---|---|---|---|
| Define canonical fulfillment state machine | Principal Software Architect | Align `status`, `print_status`, and `print_jobs.status` | Transition table is documented and enforced |
| Reconcile current live order states | Backend Engineer | Existing `orders.print_status = manufacturing` with no `processing` status must be normalized | Orders appear in correct operational queues |
| Decide whether `print_jobs` or `orders.print_status` is the operational source of truth | Principal Software Architect | One should drive dashboards, the other should derive from it | Printer/manufacturing dashboards are consistent |
| Provision printer/manufacturing roles in admin | Backend Engineer | Remove dependency on role IDs `[21,22]` and section-string assumptions only | Assigned staff can access the correct dashboards |
| Replace placeholder analytics metrics | Backend Engineer + Business Analyst | `quality_score`, `defect_rate`, `success_rate` should come from real data or be removed | Dashboard only shows metrics backed by actual data |
| Decide whether mockup templates and POD pricing options remain in launch scope | Business Analyst + Architect | Keep only if they are actively used | Dead POD branches are removed or clearly staged for later |

### Exit Criteria

- One documented fulfillment state machine exists
- Admin manufacturing and printer dashboards reflect real operational data
- End-to-end POD order flows complete in staging

## Phase 5: Frontend and Product Surface Cleanup
Duration: `1 week`
Goal: reduce UI drift and remove fragile frontend coupling

### Actions

| Action | Owner Role | Notes | Acceptance Criteria |
|---|---|---|---|
| Remove direct DB queries from Blade layouts | Senior Backend Engineer | Use composed view data only | Layouts render without querying DB inline |
| Clean theme handling logic | Frontend Architect | Remove forced light-mode contradictions if dark mode is not supported | Theme behavior is consistent |
| Audit vendor/dashboard navigation against actual feature flags | Frontend Architect + BA | `pod_designer_mode`, studio links, royalties links | UI only exposes supported launch features |
| Standardize public asset strategy | Frontend Architect + DevOps/SRE | Decide whether to keep root `assets/` or move to standard Laravel public handling | Asset pipeline is documented and reproducible |
| Run accessibility pass on home, login, checkout, vendor dashboard, admin queue | Frontend Architect + QA Lead | Focus on forms, keyboard nav, semantics, contrast | Core pages meet baseline accessibility checks |

### Exit Criteria

- Core UI reflects actual launch scope
- Layout data flow is cleaner and more maintainable
- Asset handling is documented and consistent

## Phase 6: Observability, QA, and Release Readiness
Duration: `1-2 weeks`
Goal: make production operation measurable and releasable

### Actions

| Action | Owner Role | Notes | Acceptance Criteria |
|---|---|---|---|
| Add structured logs and request correlation IDs | DevOps/SRE + Backend Engineer | Cover auth, checkout, gateway callbacks, fulfillment actions | Key flows are traceable in logs |
| Add smoke health checks and environment diagnostics | DevOps/SRE | DB, Redis, queue, storage, mail, gateway config status | Deployment validation can fail early |
| Expand automated test suite | QA Lead + Backend Engineer | Add auth, authorization, checkout, callback, and fulfillment tests | Critical flows are covered in CI |
| Add dependency and image security scans to CI | DevOps/SRE + Security Auditor | Composer audit + container scan + secret checks | Security regressions fail CI |
| Automate backup schedule and restore drill | DevOps/SRE | Daily DB backup, retention, monthly restore test | RPO/RTO process is proven |
| Add staging deployment gate before production | DevOps/SRE | Promote only from validated builds | Production deploy is not first integration run |

### Exit Criteria

- CI blocks insecure or broken builds
- Restore drill is documented and successful
- Launch checklist is defined and rehearsed

## 6. Suggested Timeline

| Week | Focus | Expected Output |
|---|---|---|
| Week 1 | Phase 0 + Phase 1 | Critical exposure removed, ownership enforced, auth hardened |
| Week 2 | Phase 2 | Schema baseline, seeds, queue schema, role/gateway alignment |
| Week 3 | Phase 3 | Tunisia checkout hardened and callback-safe |
| Week 4 | Phase 4 | POD/manufacturing/printer flow stabilized |
| Week 5 | Phase 5 + start Phase 6 | UI cleanup, observability, tests, release gates |
| Week 6 | Finish Phase 6 | Staging validation, backup drill, launch decision review |

## 7. Minimum Launch Scope

To reduce delivery risk, the first production launch should include only:

- Buyer registration/login
- Vendor onboarding and verification
- Vendor product upload for the chosen POD model
- Tunisia-only checkout with only configured and tested gateways
- Admin order review
- Manufacturing queue
- Printer queue
- Shipment completion

The following should be deferred unless fully validated:

- Unused POD branches (`mockup_templates`, `pod_pricing_options`) if not active
- Any unused international gateways
- Any API/mobile surface
- Any theme feature not essential to launch

## 8. Launch Readiness Gates

The project can move from `No-Go` to `Conditional Go` only when all of the following are true:

| Gate | Requirement |
|---|---|
| Security | No critical findings remain open |
| Authorization | Vendor ownership tests pass for products and orders |
| Schema | Fresh environment builds from migrations and seeds |
| Payments | Active Tunisia gateway callbacks pass in staging |
| Fulfillment | Buyer -> admin -> manufacturing -> printer -> shipped flow passes |
| Operations | Backups run and a restore drill succeeds |
| CI/CD | Tests and security scans run before deploy |

The project can move from `Conditional Go` to `Go` when:

- staging is stable for one full test cycle
- production secrets/config are fully aligned
- launch checklist is signed off by engineering, QA, security, and business

## 9. Immediate Next Backlog

Recommended first execution order:

1. Remove installer/update routes and controller code
2. Fix vendor ownership on products and orders
3. Decide the canonical identity model (`is_vendor` vs `role_type`)
4. Decide the canonical POD schema model
5. Create migration baseline and add queue schema
6. Seed Tunisia gateway and role data
7. Harden callbacks and auth throttling
8. Stabilize fulfillment state transitions
9. Add CI release gates and backup automation

## 10. Deliverables to Create During Execution

| Deliverable | Purpose |
|---|---|
| `SECURITY_REMEDIATION_LOG.md` | Track closure of critical/high findings |
| `SCHEMA_CANONICAL_MAP.md` | Source of truth for tables/columns and active usage |
| `FULFILLMENT_STATE_MACHINE.md` | Define valid order/print/print-job transitions |
| `LAUNCH_SCOPE_TN.md` | Freeze Tunisia launch scope and gateway list |
| `RELEASE_CHECKLIST.md` | Final deployment and rollback checklist |

## 11. Recommended Ownership Model

| Area | Primary Owner | Supporting Roles |
|---|---|---|
| Security containment | Senior Security Auditor | Backend Engineer, DevOps/SRE |
| Authorization and identity | Senior Backend Engineer | Principal Architect |
| Schema and migration baseline | Principal Software Architect | Backend Engineer |
| Checkout and payments | Senior Backend Engineer | Business Analyst, QA Lead |
| Fulfillment and POD logic | Principal Architect | Backend Engineer, Business Analyst |
| Frontend cleanup | Frontend Architect | QA Lead |
| CI/CD and backups | DevOps/SRE | Security Auditor, QA Lead |

## 12. Final Planning Note

This plan intentionally favors stabilization over expansion. The fastest route to production is not to implement more features; it is to reduce scope, remove legacy risk, align the data model, and make the existing Tunisia launch path reliable.
