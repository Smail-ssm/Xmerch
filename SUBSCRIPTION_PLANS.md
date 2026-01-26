# XMerch Designer Subscription Plans: **Relic Signal Edition**

A tier system inspired by a **frontier-tech** world: lost systems, signal discovery, and upgrading your capabilities as you grow.

---

## 1) **Initiate Signal** (Starter / Trial)

- **Theme:** First connection — you’ve detected a signal and start decoding the platform.
- **Cost:** Free
- **Duration:** 14 days
- **Product limit:** 10 uploads
- **Features:**
  - Basic design tools
  - Standard support
  - Good for testing the workflow end-to-end

---

## 2) **Merchant Relay** (Basic)

- **Theme:** Trade network — you can now relay designs reliably and track what sells.
- **Cost:** **29 TND / month**
- **Duration:** 30 days
- **Product limit:** 50 uploads
- **Features:**
  - Advanced editor access
  - Relay analytics (views, clicks, conversions)
  - Priority support

---

## 3) **Vanguard Sync** (Pro)

- **Theme:** Full synchronization — your catalog stays aligned across channels and you get growth tools.
- **Cost:** **79 TND / month**
- **Duration:** 30 days
- **Product limit:** 200 uploads
- **Features:**
  - Auto-sync to connected channels
  - Vanguard boosts (featured placements / promo slots)
  - **No marketplace commission on your own store** _(payment provider fees still apply)_

---

## 3B) **Vanguard Sync — Weekly Pass** (Pro Weekly)

> Designed for the Tunisian market: lower commitment, easy to try, easy to upgrade.

- **Theme:** Weekly sync burst — activate Pro power for a short sprint.
- **Cost:** **25 TND / week**
- **Duration:** 7 days
- **Product limit:** 60 uploads _(weekly cap to prevent abuse)_
- **Features:** Same as **Vanguard Sync (Pro)**:
  - Auto-sync to connected channels
  - Vanguard boosts (featured placements / promo slots)
  - No marketplace commission on your own store _(payment provider fees still apply)_
- **Upgrade logic (recommended):**
  - If user upgrades to Monthly Pro within the same week, **credit 100% of the Weekly price** toward the Monthly plan.
  - Example: paid 25 TND weekly → monthly becomes 79-25 = **54 TND** at upgrade time.

---

## 4) **Ascendant Core** (Enterprise)

- **Theme:** Core access — maximum capacity, priority operations, and intelligent guidance.
- **Cost:** **599 TND / year**
- **Duration:** 365 days
- **Product limit:** Unlimited
- **Features:**
  - Priority manufacturing queue
  - Dedicated account manager
  - AI-assisted trend insights (keywords, niches, seasonal spikes)
  - Multi-team access + brand spaces

---

# Improvements for Tunisia (Practical Add-ons)

## A) Add **Pro Weekly** (already included above)

- Solves the “monthly commitment” problem.
- Works well with local payment habits (small frequent payments).

## B) Add a **Student / Starter Monthly**

- **12–15 TND / month**, 20 uploads, basic analytics.
- Purpose: bring in new designers cheaply, then convert them to Pro.

## C) Make AI features quota-based

- Basic: 10 trend checks / month
- Pro: 60 trend checks / month
- Enterprise: unlimited
- Prevents AI costs from exploding.

## D) Clarify “No commission”

- Say clearly: **No XMerch marketplace cut on your own store**.
- Payment gateway fees + shipping costs still apply (transparent pricing reduces disputes).

## E) Tunisian-friendly pricing display

- Always show **TND** by default.
- Optionally show USD only in settings (for international users).

---

## Technical Note

These plans are configured in the `subscriptions` database table.

To update programmatically, run:

```bash
php update_plans.php
```
