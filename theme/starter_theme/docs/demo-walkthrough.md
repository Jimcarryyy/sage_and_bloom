# Sage & Bloom — Demo Walkthrough

Use this script when presenting the project to clients.

## Before the demo

1. Start XAMPP (Apache + MySQL)
2. Activate **Sage & Bloom Core** plugin (`wp-content/plugins/sage-bloom-core`)
3. Ensure **WooCommerce** and **ACF** are active
4. Add Stripe test keys in **Sage & Bloom → Settings**
5. Optional: run Mailpit to capture emails at `http://localhost:8025`
6. Optional: `stripe listen --forward-to http://localhost/wordpress/wp-json/sage-bloom/v1/webhooks/stripe`

## 1. Homepage (2 min)

- Show branded hero, services, shop products, testimonials
- Point out **Log in / Sign up** and cart in the header — standard e-commerce account flow
- Point out editable ACF content — no code changes needed for copy/images

## 2. Appointment booking (3 min)

1. Go to **Book Now**
2. Select a service → pick a date → live time slots appear
3. Submit booking request
4. Open **wp-admin → Sage & Bloom → Bookings**
5. Confirm the booking → customer receives confirmation email

**Talking point:** Real backend with custom DB tables, availability logic, and REST API — not just a contact form.

## 3. E-commerce checkout (3 min)

1. Add a product to cart from **Shop**
2. Proceed to checkout
3. Pay with **Credit / Debit Card (Test)**
4. Use test card `4242 4242 4242 4242`, any future expiry, any CVC
5. Show order confirmation page

**Talking point:** Stripe Payment Intents integration with webhook automation. Guest checkout works; customers can also create an account at checkout.

## 4. Customer account — sign up & login (2 min)

1. Click **Sign up** in the site header (or **Log in** on mobile)
2. Create a customer account on the **My Account** page (email + password)
3. Show **My Account → Orders** after a purchase
4. Show **My Account → Bookings** — appointment history from the custom plugin
5. Optional: log out and log back in to show returning customer flow

**Talking point:** Customer auth uses **WooCommerce + WordPress** (industry standard). Our custom plugin extends My Account with bookings — no reinvented login system.

## 5. Admin dashboard (2 min)

- **Sage & Bloom → Overview** — today's bookings, revenue, low stock
- **Bookings → Calendar** — upcoming appointments
- **Settings** — Stripe keys, slot duration

## 6. Promo codes (30 sec)

At checkout, apply demo coupons:

- `WELCOME15` — 15% off
- `BRIDAL10` — 10% off

## Portfolio one-liner

> A WordPress salon & boutique site with a custom backend plugin — REST API, appointment booking engine, Stripe test payments, WooCommerce customer accounts, and admin dashboard.

## Troubleshooting

| Issue | Fix |
|-------|-----|
| No time slots | Check **Sage & Bloom → Settings** slot duration; verify date is not Sunday |
| Stripe not at checkout | Add test keys; confirm site is on localhost |
| Bookings tab 404 | Deactivate/reactivate plugin to flush rewrite rules |
| No Sign up link | Reload any page once (theme auto-enables WooCommerce registration) |
| Emails not received | Use Mailpit or check WP mail configuration |
