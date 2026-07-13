# Sage & Bloom — WordPress Local Business Stack

Portfolio-ready WordPress site for a **salon & boutique** local business: editable homepage, services catalog, appointment booking, WooCommerce shop, and a branded customer account area.

**Demo brand:** Sage & Bloom (salon + boutique)  
**Local URL:** `http://localhost/wordpress/`

---

## Project status

**Yes — this project is complete** for portfolio and local demo use.

| Area | Status |
|------|--------|
| Custom theme (`starter_theme`) | Done |
| Custom plugin (`sage-bloom-core`) | Done |
| Homepage, About, Services, Shop, Book, Contact | Done |
| WooCommerce cart, checkout, order received | Done |
| My Account (AJAX nav, dashboard, orders, bookings, addresses, profile) | Done |
| Guest login / register + member intro banner | Done |
| Demo content seeder | Done |
| Admin booking dashboard + REST API | Done |
| Stripe test payments (local) | Done |
| Documentation | Done |

**Not stored in this repo** (install separately on each machine):

- WordPress core
- Advanced Custom Fields, WooCommerce, Contact Form 7
- Database / uploaded media (optional export — see [docs/github-setup.md](docs/github-setup.md))
- Stripe API keys (configure per environment)

---

## Features

### Theme (`starter_theme`)

- **Editable homepage** — Hero, hours bar, about, featured services, shop products, testimonials, CTA (ACF)
- **Business Settings** — Global phone, email, address, hours, social links (ACF Options)
- **Custom post types** — Services (+ categories), Team, Testimonials
- **Page templates** — About, Book Now, Contact
- **WooCommerce** — Shop archive (AJAX sort), product pages, cart, checkout, thank-you page
- **Customer accounts** — Header Log in / Sign up; My Account with AJAX sidebar navigation
- **Account UX** — Branded dashboard, orders, downloads, addresses, account details, bookings tab (plugin)
- **Guest auth** — Styled login/register cards and member benefits intro
- **Forms** — Booking API form + Contact Form 7 support + theme fallbacks
- **Modular CSS** — Token-based design system in `assets/css/`
- **Demo content** — Auto-imports on theme activation

### Plugin (`sage-bloom-core`)

- REST API at `/wp-json/sage-bloom/v1/`
- Booking engine with availability slots
- Custom DB tables + activity log
- WooCommerce Stripe **test** gateway
- Admin: Overview, Bookings, Settings
- My Account **Bookings** endpoint

---

## Tech stack

| Layer | Technology |
|-------|------------|
| CMS | WordPress 6+ |
| Theme base | Underscores (_s) |
| Custom plugin | **Sage & Bloom Core** |
| Custom fields | Advanced Custom Fields (ACF) |
| E-commerce | WooCommerce |
| Forms | Contact Form 7 (optional) + theme fallbacks |
| Payments | Stripe test mode (plugin) |

---

## Requirements

- WordPress 6.0+
- PHP 7.4+ (8.x recommended)
- MySQL / MariaDB
- Plugins: **ACF**, **WooCommerce**, **Sage & Bloom Core** (included in monorepo — see GitHub guide)
- Optional: Contact Form 7, Mailpit (local email)

---

## Quick setup (local / XAMPP)

1. Install WordPress in XAMPP (`htdocs/wordpress`)
2. Install plugins: **ACF**, **WooCommerce**
3. Copy or clone:
   - Theme → `wp-content/themes/starter_theme/`
   - Plugin → `wp-content/plugins/sage-bloom-core/`
4. Activate **Sage & Bloom Core**, then theme **starter_theme**
5. **Settings → Permalinks** → Save
6. **Business Settings** + **Sage & Bloom → Settings** (Stripe test keys)
7. **Settings → Reading** → static front page = Home

Full details: [docs/local-setup.md](docs/local-setup.md)

---

## Back up to GitHub (recommended)

This theme and the custom plugin should live in **one GitHub repository** so you can restore everything if you lose your local copy.

**→ Step-by-step guide: [docs/github-setup.md](docs/github-setup.md)**

Summary:

1. Create a folder with `theme/starter_theme` + `plugins/sage-bloom-core`
2. `git init`, commit, push to GitHub
3. Third-party plugins (ACF, WooCommerce) are installed from WordPress.org on restore — not committed
4. Optionally export DB/media for full content backup

---

## Site map

| Page | Template | Editable via |
|------|----------|--------------|
| Home | `front-page.php` | ACF Homepage Content |
| About | About template | ACF + Team CPT |
| Services | `archive-service.php` | Service CPT |
| Shop | WooCommerce | Products |
| Book Now | Booking template | ACF + booking API |
| Contact | Contact template | ACF + Business Settings |
| My Account | WooCommerce | WC + plugin bookings |
| Blog | `archive.php` | Posts |

---

## CSS architecture

| File | Purpose |
|------|---------|
| `assets/css/variables.css` | Colors, fonts, spacing |
| `assets/css/layout.css` | Header, footer, containers |
| `assets/css/components.css` | Buttons, loaders, shared UI |
| `assets/css/homepage.css` | Homepage sections |
| `assets/css/services.css` | Services archive/single |
| `assets/css/shop.css` | WooCommerce + My Account |
| `assets/css/pages.css` | About, contact, blog |
| `assets/css/forms.css` | Form styling |
| `assets/css/overrides.css` | Final overrides |

Rebrand via `variables.css` first.

---

## Repository layout (for GitHub)

```
sage-and-bloom/                    ← suggested Git root
├── README.md
├── .gitignore
├── theme/
│   └── starter_theme/             ← this repository folder
│       ├── inc/
│       ├── templates/
│       ├── template-parts/
│       ├── woocommerce/
│       ├── assets/
│       └── docs/
└── plugins/
    └── sage-bloom-core/           ← companion plugin (required)
```

---

## Theme structure (high level)

```
starter_theme/
├── inc/
│   ├── business.php, post-types.php, acf-*.php
│   ├── woocommerce.php, my-account.php
│   ├── forms.php, demo-content.php
│   └── service-helpers.php, homepage.php
├── templates/          # Page templates
├── template-parts/     # Sections, forms, WooCommerce parts
├── woocommerce/        # WC template overrides
├── assets/css|js/
└── docs/               # Architecture, API, setup, GitHub guide
```

---

## Documentation

| Doc | Description |
|-----|-------------|
| [docs/local-setup.md](docs/local-setup.md) | XAMPP, Stripe, Mailpit, permalinks |
| [docs/github-setup.md](docs/github-setup.md) | **GitHub backup & restore** |
| [docs/architecture.md](docs/architecture.md) | System design |
| [docs/api.md](docs/api.md) | REST API reference |
| [docs/demo-walkthrough.md](docs/demo-walkthrough.md) | Portfolio demo script |

---

## Re-import demo content

In wp-admin or via WP-CLI, delete seed flags and reload admin:

```php
delete_option( 'starter_theme_demo_seeded' );
delete_option( 'starter_theme_products_seeded' );
```

Plugin demo coupons re-seed on plugin re-activation.

---

## Portfolio one-liner

*A full-stack local business WordPress build — custom theme, booking REST API, WooCommerce shop, Stripe test checkout, and a polished member account experience.*

---

## License

GPL v2 or later — same as WordPress.
