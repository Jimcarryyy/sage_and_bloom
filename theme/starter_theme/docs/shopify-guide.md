# Shopify Guide for WordPress Developers

A practical intro to how Shopify works — especially if you're coming from WordPress/WooCommerce (like the Sage & Bloom theme in this repo).

---

## What Shopify Is (In One Sentence)

**Shopify is a hosted e-commerce platform:** you build and customize a **storefront theme**, manage products/orders in **Shopify Admin**, and Shopify runs the servers, checkout, and payments infrastructure for you.

It is **not** a general-purpose CMS like WordPress. Content pages exist, but the product catalog and checkout are the center of the system.

---

## WordPress vs Shopify — Mental Model

| | **WordPress (your current stack)** | **Shopify** |
|---|-----------------------------------|-------------|
| **Hosting** | You choose (XAMPP, Hostinger, etc.) | Shopify hosts everything |
| **Core product** | Flexible CMS + plugins | Online store SaaS |
| **Shop** | WooCommerce plugin | Built-in |
| **Theme code** | PHP templates | **Liquid** templates |
| **Extend functionality** | Plugins (PHP) | **Apps** (often Node/React) + theme code |
| **Database access** | Full (MySQL) | No direct DB access |
| **“Edit layout visually”** | Elementor, blocks, ACF | Theme editor + sections (Online Store 2.0) |
| **Typical dev workflow** | Theme files + plugins on server | Theme dev locally → push to store via CLI or GitHub |

**Analogy:** WordPress is an empty building you configure. Shopify is a pre-built retail store where you customize the front and use their back office.

---

## How Shopify Operates

### 1. Your store has two main sides

**Shopify Admin** (`your-store.myshopify.com/admin`)
- Products, collections, inventory, orders, customers
- Discounts, shipping, taxes, payments
- Apps, theme assignment, settings

**Storefront** (what customers see)
- Rendered by an active **theme**
- Product pages, collection pages, cart, checkout (checkout is mostly Shopify-controlled)

### 2. Themes control the storefront

A Shopify theme is a folder of files:

```
theme/
├── layout/          # Base HTML shell (theme.liquid)
├── templates/       # Page types (product, collection, cart, etc.)
├── sections/        # Reusable, merchant-editable blocks (OS 2.0)
├── snippets/        # Small reusable partials
├── assets/          # CSS, JS, images
├── config/          # Theme settings schema
└── locales/         # Translations
```

Themes use **Liquid** — Shopify’s templating language (similar vibe to Jinja/Django templates, **not** PHP).

Example Liquid:

```liquid
{% for product in collection.products %}
  <h2>{{ product.title }}</h2>
  <p>{{ product.price | money }}</p>
{% endfor %}
```

### 3. Online Store 2.0 (important concept)

Modern Shopify themes use **sections everywhere**. Merchants can:
- Add/reorder sections on pages in the theme editor
- Edit text/images in section settings

This is Shopify’s answer to “drag and drop” — but it’s **section-based**, not free-form canvas like Elementor.

### 4. Apps extend what themes can’t

Need subscriptions, complex bundles, ERP sync, custom checkout rules? That usually becomes a **Shopify App** (separate codebase, OAuth, Admin API).

As a theme developer, you often integrate with apps via app blocks or metafields — you don’t rewrite checkout in the theme.

### 5. Data you work with

Instead of custom post types in MySQL, Shopify has built-in objects:
- **Products**, **Variants**, **Collections**
- **Pages**, **Blog posts**
- **Metafields** (custom data on products, pages, shop, etc.) — closest thing to ACF
- **Metaobjects** (custom structured content types)

---

## Is Shopify Custom Code Based?

**Yes and no** — depends on your role.

### Merchant / no-code path
- Pick a theme from the Theme Store
- Customize colors, fonts, sections in the theme editor
- Add apps from the App Store
- Minimal or no code

### Developer path (closer to what you do now)
- Build or customize themes with **Liquid + HTML + CSS + JavaScript**
- Use **Shopify CLI** for local development
- Connect theme to GitHub for version control
- Use **metafields/metaobjects** for structured content (ACF-like)
- Build **apps** when logic goes beyond theme scope

### Compared to your WordPress workflow

| Task | WordPress | Shopify |
|------|-----------|---------|
| Custom homepage sections | PHP template + ACF | Theme sections + schema settings |
| Product layout | WooCommerce PHP templates | `product.liquid` + sections |
| Global business info | ACF Options / Business Settings page | Theme settings or shop metafields |
| AJAX shop sorting | Custom JS in theme | Theme JS + Storefront API / section rendering |
| Checkout customization | WooCommerce hooks/plugins | Shopify Plus / checkout extensibility (advanced) |

**Bottom line:** Shopify storefront work **is code-based** (Liquid/CSS/JS), but you operate inside Shopify’s platform rules — no PHP, no wp-admin hooks, no direct database.

---

## Can You Show a Sample Site Live Without Paying?

**Yes — this is the best path for learning and portfolio samples.**

### Option A: Shopify Partner development store (recommended)

1. Create a free **Shopify Partner** account: https://partners.shopify.com  
2. In the **Dev Dashboard**, create a **development store**
3. Build your theme, add products, customize the storefront

**Cost:** $0 for the dev store itself.

**What you get:**
- Full admin access
- Unlimited test products and test orders
- Realistic Shopify environment for theme/app work
- **Shopify CLI** local preview against the dev store

**Limitations (important for portfolio):**
- **Password-protected storefront** — visitors need the password (you can share the link + password in your portfolio)
- **No real payments** — test checkout only
- **Not meant for production** — dev stores aren’t transferred to clients like a live merchant store
- Some features (custom domains, removing password page) require upgrading or using a **client transfer store**

For a portfolio “sample site,” this is usually **good enough**:  
> Live Demo: [store URL] — Password: `yourpassword`

### Option B: Client transfer store (when handing off to a real client)

Partners can create a store meant to be **transferred to a merchant** when ready. Still free while you build under your Partner org, but structured for real launch.

Use this when building for an actual client, not just learning.

### Option C: Shopify free trial (merchant path)

Shopify sometimes offers short trials for merchants signing up directly. Trials are for **starting a real paid store**, not ideal as a permanent free portfolio sandbox.

**For learning + portfolio samples, use a Partner dev store — not a trial.**

### Option D: Local theme preview only (no public URL)

With **Shopify CLI**:

```bash
shopify theme dev --store your-dev-store.myshopify.com
```

This runs a local preview tunnel while you edit theme files. Great for development; for portfolio you still typically share the dev store URL with password.

---

## Portfolio: Showing Shopify Work Like WordPress

| Goal | WordPress approach | Shopify approach |
|------|-------------------|------------------|
| Quick demo link | ngrok / Cloudflare Tunnel on localhost | Dev store URL + storefront password |
| Permanent public demo | Cheap staging host | Client transfer → paid plan, or keep dev store with password |
| Code showcase | GitHub theme repo | GitHub theme repo (very portfolio-friendly) |
| No hosting cost | Local XAMPP + tunnel | Partner dev store ($0) |

**Strong portfolio combo for Shopify:**
1. **GitHub repo** with your theme code
2. **Dev store link** with password in README
3. **Short case study** (screenshots + what you built in Liquid/JS)

---

## Typical Developer Tooling

| Tool | Purpose |
|------|---------|
| **Shopify CLI** | Local theme dev, push/pull themes, app scaffolding |
| **Theme Check** | Lint Liquid themes |
| **GitHub integration** | Version control + deploy on push |
| **Shopify Dev Docs** | https://shopify.dev |
| ** Dawn theme** | Shopify’s reference theme — best starting point for learning |

### Suggested first steps

1. Sign up for **Shopify Partner**
2. Create a **dev store** with “Generate test data”
3. Clone **Dawn**: `shopify theme init --clone-url https://github.com/Shopify/dawn`
4. Run `shopify theme dev` and edit `sections/`, `templates/`, `assets/`
5. Push to dev store: `shopify theme push`

---

## Drag-and-Drop on Shopify

Shopify’s merchant-facing editing is **section-based**:
- Merchants reorder **sections** (hero, featured collection, image with text)
- They don’t freely drag pixels like Elementor

As a developer, you **define** which sections exist and what settings they expose (text fields, images, collection pickers) in section schema JSON inside `.liquid` files.

If you prefer **code over visual builders**, Shopify theme development will feel familiar — it’s file-based, just with Liquid instead of PHP.

---

## When to Recommend Shopify vs WordPress

**Recommend Shopify when:**
- Client’s business is mostly **selling products online**
- They want reliable checkout, inventory, shipping with minimal maintenance
- Budget supports Shopify plan + apps

**Recommend WordPress when:**
- They need a **full business website** (services, booking, rich content) **plus** a shop
- Heavy custom UX (like Sage & Bloom: services CPT, ACF homepage, custom WooCommerce layouts)
- They want cheap hosting and full code ownership

Your Sage & Bloom project is a strong **WordPress** case study. A simple product-focused boutique with 20 SKUs might be a better **Shopify** case study if you want one of each in your portfolio.

---

## Key Limitations to Know Before You Start

1. **No PHP** — learn Liquid
2. **No direct database** — use Admin API, metafields, metaobjects
3. **Checkout is locked down** — deep checkout customization needs Plus / advanced extensibility
4. **Monthly cost for real stores** — dev stores are free; live merchant stores pay Shopify plans
5. **App ecosystem dependency** — some WordPress plugin features = paid Shopify apps

---

## Glossary

| Term | Meaning |
|------|---------|
| **Liquid** | Shopify’s template language |
| **Section** | Editable theme component (OS 2.0) |
| **Snippet** | Reusable partial template |
| **Metafield** | Custom field on Shopify objects |
| **Storefront** | Customer-facing site |
| **Admin** | Backend dashboard |
| **Dev store** | Free Partner sandbox store |
| **App** | Plugin equivalent (separate product) |
| **Dawn** | Shopify’s default open-source reference theme |

---

## Quick FAQ

**Can I build a sample store for my portfolio without paying Shopify?**  
Yes — use a **Partner development store**. Share the URL and storefront password.

**Is it custom code?**  
Storefront themes are **Liquid + CSS + JS**. That’s custom code. The admin and checkout core are Shopify’s platform.

**Do I need to learn drag-and-drop?**  
Not really. Theme developers work in files. Merchants use the section editor; you build the sections.

**Is it like WooCommerce?**  
WooCommerce is a plugin on WordPress. Shopify commerce is **built into the platform** — tighter, less flexible, usually simpler for pure retail.

**Can Cursor help me build Shopify themes?**  
Yes — same as WordPress themes. Cursor edits theme files locally; you use Shopify CLI to preview and push.

---

## Official Resources

- Shopify Dev Docs: https://shopify.dev/docs/storefronts/themes
- Partner signup: https://partners.shopify.com
- Dawn theme (reference): https://github.com/Shopify/dawn
- Liquid reference: https://shopify.dev/docs/api/liquid
- Development stores: https://shopify.dev/docs/storefronts/themes/tools/development-stores

---

*Created as a learning reference for comparing Shopify with the WordPress/WooCommerce workflow used in this theme project.*
