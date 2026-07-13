# Sage & Bloom — REST API

Base URL: `/wp-json/sage-bloom/v1/`

## Public endpoints

### GET `/services`

Returns all `service` CPT posts with price, duration, and categories.

### GET `/testimonials`

Returns testimonial CPT entries.

### GET `/business`

Returns business name, phone, email, address, and hours from ACF options.

### GET `/availability`

Query parameters:

| Param | Required | Description |
|-------|----------|-------------|
| `service_id` | yes | Service post ID |
| `date` | yes | Date in `Y-m-d` format |

Response:

```json
{
  "service_id": 12,
  "date": "2026-07-15",
  "slots": [
    { "time": "09:00:00", "label": "9:00 AM", "duration": 60 }
  ]
}
```

### POST `/bookings`

Requires header: `X-WP-Nonce: {wp_rest nonce}`

Body (JSON):

```json
{
  "service_id": 12,
  "booking_date": "2026-07-15",
  "booking_time": "09:00:00",
  "customer_name": "Jane Doe",
  "customer_email": "jane@example.com",
  "customer_phone": "555-0100",
  "message": "First visit"
}
```

Response includes `access_token` for status lookup.

### GET `/bookings/{id}?token={access_token}`

Returns booking status for the customer confirmation flow.

## Admin endpoints

Require authenticated user with `manage_options` (cookie session or Application Password).

### GET `/admin/bookings`

Query: `status`, `date_from`, `date_to`, `limit`

### PATCH `/admin/bookings`

Body:

```json
{
  "id": 1,
  "status": "confirmed",
  "staff_id": 5
}
```

Valid statuses: `pending`, `confirmed`, `completed`, `cancelled`

### GET `/admin/stats`

Dashboard KPIs: bookings, revenue, low stock, activity data.

### PUT `/admin/availability`

Update weekly availability rules.

## Webhooks

### POST `/webhooks/stripe`

Stripe webhook endpoint. Configure in Stripe dashboard or via Stripe CLI:

```bash
stripe listen --forward-to http://localhost/wordpress/wp-json/sage-bloom/v1/webhooks/stripe
```

Events handled:

- `payment_intent.succeeded` — marks WooCommerce order paid/completed

## Frontend usage

The booking page uses `assets/js/booking-api.js` with localized config:

```javascript
window.sbcBooking = {
  restUrl: '/wordpress/wp-json/sage-bloom/v1/',
  nonce: '...'
};
```

## Authentication notes

- **Public writes** — WordPress REST nonce (`wp_create_nonce('wp_rest')`)
- **Admin API** — log in to wp-admin or use Application Passwords
