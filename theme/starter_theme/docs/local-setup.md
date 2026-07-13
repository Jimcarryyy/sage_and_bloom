# Local Development Setup

## Requirements

- XAMPP (Apache + MySQL + PHP 7.4+)
- WordPress 6+ at `http://localhost/wordpress/`
- Plugins: **Advanced Custom Fields**, **WooCommerce**, **Sage & Bloom Core**
- Theme: **starter_theme** (Sage & Bloom)

## 1. Install the backend plugin

1. Copy or symlink `wp-content/plugins/sage-bloom-core/` into your WordPress install
2. Go to **Plugins → Installed Plugins**
3. Activate **Sage & Bloom Core**
4. Visit **Settings → Permalinks** and click Save (flushes rewrite rules)

## 2. Configure Stripe test keys

1. Create a free [Stripe](https://stripe.com) account
2. Open **Developers → API keys** and copy test keys
3. In WordPress: **Sage & Bloom → Settings**
4. Paste `pk_test_...` and `sk_test_...`

Alternatively in `wp-config.php`:

```php
define( 'SBC_STRIPE_PUBLISHABLE_KEY', 'pk_test_...' );
define( 'SBC_STRIPE_SECRET_KEY', 'sk_test_...' );
define( 'SBC_STRIPE_WEBHOOK_SECRET', 'whsec_...' );
```

## 3. Email capture (Mailpit)

Install [Mailpit](https://github.com/axllent/mailpit) and point WordPress mail to it:

- SMTP plugin, or
- Configure `sendmail` in XAMPP to route to Mailpit

View captured mail at `http://localhost:8025`

## 4. Stripe webhooks (optional)

Install [Stripe CLI](https://stripe.com/docs/stripe-cli):

```bash
stripe login
stripe listen --forward-to http://localhost/wordpress/wp-json/sage-bloom/v1/webhooks/stripe
```

Copy the webhook signing secret (`whsec_...`) into plugin settings.

## 5. Customer accounts

The theme auto-enables WooCommerce customer registration on first load:

- **Log in / Sign up** links appear in the site header
- **My Account** page: orders, addresses, profile
- **My Account → Bookings** tab (from Sage & Bloom Core plugin)

Manual check: **WooCommerce → Settings → Accounts & Privacy** — “Allow customers to create an account on the My account page” should be enabled.

## 6. Checkout payment method

The **Stripe (Test Mode)** gateway appears only on local/development environments when test keys are configured.

For **WooCommerce Blocks checkout**, enable classic checkout if the card form does not appear:

- **WooCommerce → Settings → Advanced → Features**
- Enable **Classic checkout** (or use `[woocommerce_checkout]` shortcode on a test page)

Test cards:

| Card | Result |
|------|--------|
| `4242 4242 4242 4242` | Success |
| `4000 0000 0000 9995` | Decline |

## 7. Verify the API

```bash
curl http://localhost/wordpress/wp-json/sage-bloom/v1/services
curl "http://localhost/wordpress/wp-json/sage-bloom/v1/availability?service_id=1&date=2026-07-15"
```

## Demo coupons

Auto-seeded on plugin activation:

- `WELCOME15` — 15% off
- `BRIDAL10` — 10% off

## Related docs

- [architecture.md](./architecture.md)
- [api.md](./api.md)
- [demo-walkthrough.md](./demo-walkthrough.md)
