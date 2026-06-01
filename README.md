# Luna Commerce

Luna Commerce is a Laravel ecommerce project with a public storefront, customer authentication, wishlist and reviews, a protected admin panel, and a public JSON API.

## Overview

This project now includes:

- multi-role user management with admin protection
- storefront catalog with categories, brands, filters, and reviews
- wishlist and customer account activity
- admin CRUD for products, categories, brands, orders, and review moderation
- public API endpoints for products, categories, and brands

## Feature Roadmap Implemented

### 1. Role Management

- Added a `roles` table
- Added a `role_user` pivot table for many-to-many relationships
- Created a `Role` model
- Updated the `User` model with:
  - `roles()`
  - `hasRole()`
  - `hasAnyRole()`

### 2. Authentication and Authorization

- Kept the existing login and registration system
- Automatically assigns the `user` role after registration
- Added a custom `RoleMiddleware`
- Protected admin routes with `auth` and `role:admin`

### 3. Catalog Expansion

- Added `brands`
- Added category hierarchy support with `parent_id`
- Added product SEO and gallery fields
- Added review metrics:
  - `average_rating`
  - `reviews_count`

### 4. Customer Features

- Added wishlist support
- Added order ownership with user-linked orders
- Added product reviews for authenticated purchasers
- Expanded account page with recent order history and saved products access

### 5. Admin Features

- Admin dashboard at `/admin`
- Product CRUD
- Category management
- Brand management
- Order management
- Review moderation

### 6. Public API

Available endpoints:

- `GET /api/products`
- `GET /api/products/featured`
- `GET /api/products/{slug}`
- `GET /api/categories`
- `GET /api/brands`

Supported product filters:

- `q`
- `category`
- `brand`
- `min_price`
- `max_price`
- `per_page`

### 7. Demo Seed Data

- seeded admin user
- seeded customer user
- seeded roles
- seeded brands, categories, products
- seeded sample order
- seeded sample approved review
- seeded sample wishlist item

### 8. Testing

The test suite covers:

- admin access control
- registration and login flows
- storefront filtering
- checkout order creation
- wishlist toggling
- review submission
- API catalog endpoints

## Demo Accounts

Admin:

- Email: `admin@example.com`
- Password: `12345678`

Customer:

- Email: `customer@example.com`
- Password: `12345678`

## Database Configuration

The project uses SQLite by default.

```env
DB_CONNECTION=sqlite
# DB_DATABASE=database/database.sqlite
```

If you want to switch to MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=luna_commerce
DB_USERNAME=root
DB_PASSWORD=
```

Then run:

```bash
php artisan migrate:fresh --seed
```

## Run the Project

```bash
composer install
php artisan migrate:fresh --seed
php artisan serve
```

Application URLs:

- Storefront: [http://127.0.0.1:8000](http://127.0.0.1:8000)
- Admin panel: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)
- Wishlist: [http://127.0.0.1:8000/wishlist](http://127.0.0.1:8000/wishlist)

## Run Tests

```bash
php artisan test
```
