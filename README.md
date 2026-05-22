# Mini Product Catalog Backend

A lightweight product catalog backend built with native object-oriented PHP, MySQL, PDO, Bootstrap 5, and vanilla JavaScript.

## Requirements

- PHP 8.0 or newer
- MySQL 5.7+ or MariaDB 10.3+
- PDO and `pdo_mysql` enabled in PHP
- A terminal such as PowerShell

## Project Structure

```text
product_catalog_backend/
|-- app/
|   |-- Controllers/
|   |-- Models/
|   `-- Views/
|-- config/
|   |-- database.php
|   `-- DbConnection.php
|-- database/
|   `-- products.sql
|-- public/
|   `-- assets/
|-- .htaccess
|-- index.php
|-- router.php
`-- README.md
```

## How to Run

### 1. Open the backend folder

```powershell
cd "C:\Users\Dell\Desktop\Product Catalog\product_catalog_backend"
```

### 2. Start MySQL

Start MySQL from your local server tool, such as XAMPP, WAMP, Laragon, or MySQL installed directly on Windows.

### 3. Import the database

Run this command from the backend folder:

```powershell
mysql -u root -p < database\products.sql
```

When prompted, enter your MySQL password.

This creates the `product_catalog` database, creates the `products` table, and inserts sample products.

If your MySQL user has no password, use:

```powershell
mysql -u root < database\products.sql
```

### 4. Check database credentials

Open `config/database.php` and make sure these values match your local MySQL setup:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'product_catalog');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');
```

Change `DB_PASS` if your MySQL password is different. For many XAMPP setups, the password is empty:

```php
define('DB_PASS', '');
```

### 5. Start the PHP development server

Run this command from the backend folder:

```powershell
php -S localhost:8000 router.php
```

### 6. Open the application

Open this URL in your browser:

```text
http://localhost:8000
```

## API Endpoints

Get the product catalog page:

```text
GET /
```

Get product details as JSON:

```text
GET /?action=detail&id=1
```

Example response:

```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Wireless Noise-Cancelling Headphones",
    "description": "Experience crystal-clear audio...",
    "price": 149.99,
    "sku": "ELEC-HDPH-001",
    "image_url": "https://..."
  }
}
```

## Troubleshooting

- If `mysql` is not recognized, add MySQL to your Windows PATH or run the command from the MySQL `bin` folder.
- If `php` is not recognized, add PHP to your Windows PATH or use the PHP executable path directly.
- If the page shows a database error, check `config/database.php`.
- If port `8000` is already in use, run the server on another port:

```powershell
php -S localhost:8080 router.php
```

Then open:

```text
http://localhost:8080
```

