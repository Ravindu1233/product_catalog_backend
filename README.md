# Mini Product Catalog

This is a small product catalog project made with raw PHP, MySQL, Bootstrap and normal JavaScript.

The page shows products in a grid. When the user clicks the View Details button, product details are loaded using `fetch()` and shown inside a modal without refreshing the page.

## Used Technologies

- PHP
- MySQL
- PDO
- Bootstrap 5
- JavaScript

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
|-- index.php
|-- router.php
`-- README.md
```

## Setup Instructions

### 1. Start MySQL

Start MySQL using XAMPP, WAMP, Laragon or any other local MySQL setup.

### 2. Import the database

Open terminal inside the project folder:

```powershell
cd "C:\Users\Dell\Desktop\Product Catalog\product_catalog_backend"
```

Then run:

```powershell
mysql -u root -p < database\products.sql
```

After that enter your MySQL password.

If your MySQL root user has no password, use this command:

```powershell
mysql -u root < database\products.sql
```

This will create the database, products table and sample product records.

### 3. Update database details

Open this file:

```text
config/database.php
```

Check these values and change them if needed:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'product_catalog');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');
```

For XAMPP, password is usually empty, so use:

```php
define('DB_PASS', '');
```

### 4. Run the project

Run this command from the backend folder:

```powershell
php -S localhost:8000 router.php
```

Then open this URL in the browser:

```text
http://localhost:8000
```

## API Details

Main product page:

```text
GET /
```

Product detail API:

```text
GET /?action=detail&id=1
```

Example JSON response:

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

## Notes

- The database file is in `database/products.sql`.
- The model file is `app/Models/Product.php`.
- The controller file is `app/Controllers/ProductController.php`.
- The main view file is `app/Views/catalog.php`.
- JavaScript file is `public/assets/js/catalog.js`.

## Common Issues

If `php` command is not working, PHP may not be added to PATH.

If `mysql` command is not working, MySQL may not be added to PATH.

If database connection fails, check the username and password in `config/database.php`.

If port `8000` is already used, run with another port:

```powershell
php -S localhost:8080 router.php
```

Then open:

```text
http://localhost:8080
```
