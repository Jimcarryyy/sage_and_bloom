# Sage & Bloom Core

WordPress backend plugin for the Sage & Bloom portfolio demo.

## Features

- REST API (`/wp-json/sage-bloom/v1/`)
- Appointment booking with availability slots
- Custom database tables + activity log
- WooCommerce Stripe test payment gateway
- Admin dashboard (Overview, Bookings, Settings)
- My Account bookings tab

## Activation

1. Activate plugin in wp-admin
2. Configure Stripe test keys under **Sage & Bloom → Settings**
3. Flush permalinks if My Account → Bookings returns 404

## Documentation

See the theme `docs/` folder:

- `architecture.md`
- `api.md`
- `local-setup.md`
- `demo-walkthrough.md`
