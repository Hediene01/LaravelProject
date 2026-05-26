# Luna Commerce

Luna Commerce is a Laravel ecommerce project with a public storefront, customer authentication, a protected admin panel, and a full product CRUD system.

## Overview

This project was extended to match the Laravel course requirements for:

- product CRUD operations
- multi-role user management
- admin login protection
- admin-only route access with middleware

## Project Roadmap

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

### 3. Admin Dashboard

- Added a protected admin panel at `/admin`
- Added a dedicated admin layout
- Displayed store statistics such as:
  - total products
  - total categories
  - total orders
  - total users

### 4. Product CRUD

- Added a complete admin product CRUD under `/admin/product`
- Supported operations:
  - Create
  - Read
  - Update
  - Delete

### 5. Product Table Enhancements

The `products` table was extended with additional admin-related fields:

- `user_id`
- `keywords`
- `detail`
- `min_stock`
- `discount`

### 6. Seeders

- Added `RoleSeeder`
- Added `AdminUserSeeder`
- Seeded default roles:
  - `admin`
  - `user`
  - `editor`
  - `moderator`
- Seeded a default admin account

### 7. Admin Interface

- Added admin views for:
  - dashboard
  - product list
  - create product
  - edit product
  - show product details

### 8. Testing

Added feature tests for:

- guest access restriction to admin panel
- non-admin access restriction
- admin access permission
- admin product creation
- automatic `user` role assignment after registration

## Demo Admin Account

- Email: `admin@example.com`
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

## Run Tests

```bash
php artisan test
```
