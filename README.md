# Purchase Entry Module

Laravel based Purchase Entry Module using Livewire, Alpine.js, and role based access control.

## Features

- Purchase listing, view, create, edit, and delete screens.
- Livewire purchase form with dynamic rows.
- Alpine.js + Livewire entangle for reactive total calculation.
- Duplicate item + brand combinations are blocked.
- Role based permissions:
  - Admin can create, edit, delete purchases and run the legacy migration command.
  - User can only view purchases.
- Legacy purchase migration command that maps legacy `item_name` and `brand_name` into normalized tables.
- Secure MySQLi debugging task solution in the `extras` directory.

## Requirements

- PHP 8.3 or higher
- Composer
- Node.js and npm
- MySQL or MariaDB

## Installation Steps

### 1. Clone the repository

```bash
git clone <repository-url>
cd purchase-entry-module
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install frontend dependencies

```bash
npm install
```

### 4. Create environment file

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Create database

Recommended database name:

```text
purchase-entry-module
```

Because this name contains hyphens, create it in MySQL using backticks:

```sql
CREATE DATABASE `purchase-entry-module` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

You can also use another database name, but then update both `.env` and `extras/required.php` if you want to test the standalone debugging task.

### 7. Configure database in `.env`

For MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=purchase-entry-module
DB_USERNAME=root
DB_PASSWORD=
```

Update `DB_USERNAME` and `DB_PASSWORD` according to your local MySQL setup.

### 8. Run migrations and seeders

For first setup:

```bash
php artisan migrate --seed
```

If you want a clean reset:

```bash
php artisan migrate:fresh --seed
```

Seeders create the required roles, users, items, and brands.

## Default Login Users

Admin user:

```text
Email: admin@test.com
Password: password
```

Normal user:

```text
Email: user@test.com
Password: password
```

## Run the Project

Start the Laravel server:

```bash
php artisan serve
```


## Legacy Purchase Migration

The legacy migration command is located at:

```text
app/Console/Commands/MigrateLegacyPurchases.php
```

Run it after migrations:

```bash
php artisan legacy:migrate-purchases
```

The command currently uses this sample legacy data:

```php
$legacyPurchases = [
    [
        'item_name' => 'Sugar',
        'brand_name' => 'ABC',
        'qty' => 10,
        'price' => 100,
    ],
];
```

What it does:

- Finds or creates the item by `item_name`.
- Finds or creates the brand by `brand_name`.
- Creates purchase and purchase item records.
- Calculates purchase total as `qty * price`.
- Runs inside a database transaction.
- Checks existing normalized records so the command can be run multiple times without creating duplicates.

## Debugging Task

The PHP MySQLi debugging task has been placed in:

```text
extras/debugging_task.php
```

Database constants for this standalone file are in:

```text
extras/required.php
```

The corrected version includes:

- `mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT)`
- Integer validation for `id`
- Prepared statements
- Bound parameters
- Querying only the required `name` column
- Basic error handling
- Statement and connection closing
