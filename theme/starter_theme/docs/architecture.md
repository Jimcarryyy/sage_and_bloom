# Sage & Bloom — Architecture

This project is a **WordPress-native fullstack demo**: a custom theme for the storefront plus a companion plugin that provides real backend logic.

## Stack

| Layer | Technology |
|-------|------------|
| CMS | WordPress 6+ |
| Theme | Sage & Bloom (`starter_theme`) |
| Backend plugin | Sage & Bloom Core (`sage-bloom-core`) |
| Content | ACF + Custom Post Types |
| Commerce | WooCommerce |
| Payments | Stripe test mode (Payment Intents) |
| Database | WordPress tables + custom plugin tables |

## High-level flow

```
Visitor → Theme templates / JS
              ↓
       WordPress REST API (/wp-json/sage-bloom/v1/)
              ↓
       sage-bloom-core plugin
              ↓
    Custom tables (bookings, availability, activity log)
              ↓
    WooCommerce orders + Stripe test gateway
```

## Plugin responsibilities

- **REST API** — services, availability, bookings, business info, admin stats
- **Booking engine** — slot generation, double-booking prevention, status lifecycle
- **Custom tables** — relational data outside post meta
- **Stripe gateway** — WooCommerce payment method (local/test only)
- **Webhooks** — `payment_intent.succeeded` order automation
- **Admin dashboard** — KPIs, booking inbox, calendar, settings
- **My Account** — customer login/signup (WooCommerce) + booking history tab

## Theme responsibilities

- Visual design, homepage sections, WooCommerce styling
- Booking UI (`booking-api.js`) consuming plugin REST endpoints
- Business content via ACF (hours, services, testimonials)

## Custom database tables

| Table | Purpose |
|-------|---------|
| `{prefix}sb_bookings` | Appointment records |
| `{prefix}sb_availability_rules` | Weekly hours + slot/buffer settings |
| `{prefix}sb_blocked_dates` | Blocked calendar dates |
| `{prefix}sb_activity_log` | Audit trail for bookings/orders |

## Local-only safety

- Stripe gateway requires `sk_test_` keys and local/development environment
- Gateway hidden automatically on non-local hosts
- Secrets via **Sage & Bloom → Settings** or `wp-config.php` constants:

```php
define( 'SBC_STRIPE_PUBLISHABLE_KEY', 'pk_test_...' );
define( 'SBC_STRIPE_SECRET_KEY', 'sk_test_...' );
define( 'SBC_STRIPE_WEBHOOK_SECRET', 'whsec_...' );
```

## File locations

```
wp-content/
├── plugins/sage-bloom-core/     # Backend plugin
└── themes/starter_theme/        # Frontend theme
```

See also: [api.md](./api.md), [demo-walkthrough.md](./demo-walkthrough.md)
