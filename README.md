# ibm555 — Blank Perfume

PHP e-commerce website assignment for **Blank Perfume** (car fragrances).

## Requirements

- XAMPP (Apache + PHP 8.x + MySQL/MariaDB)
- Database: `blank_perfume` (import your SQL dump in phpMyAdmin)

## Setup

1. Place project in `htdocs/ibm555/ibm555`
2. Import the `blank_perfume` database in phpMyAdmin
3. Optional: run `database/schema_update.sql` to fix product image names
4. On first page load, `config.php` auto-adds delivery/payment columns to `orders` if missing
5. Open `http://localhost/ibm555/ibm555/`

## User flow

| Role | Flow |
|------|------|
| Guest | `index.php` → shop, about, FAQ → register/login |
| Member | Login → `homepage.php` → `products.php` → cart → checkout → receipt |

## Test voucher codes

- `WELCOME10` — 10% off
- `BLANK20` — 20% off (min order RM 50)

## Payment (demo)

Checkout supports **Debit/Credit Card** and **FPX** (no real payment API). Card numbers are stored **masked** only (e.g. `**** **** **** 1234`). Delivery and payment details are saved on the `orders` table.

## Key files

- `config.php` — DB + session + helpers
- `includes/helpers.php` — cart count, product images, order schema migration
- `products.php` — shop (add to cart)
- `cart.php` / `cart_action.php` — cart
- `checkout.php` — delivery + payment
- `receipt.php` — confirmation + print
