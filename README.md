# Mini Product Catalog

A lightweight e-commerce product showcase built with native object-oriented PHP, MySQL, Bootstrap 5, and vanilla ES6 JavaScript. No PHP framework is used.

## Project Structure

```text
product_catalog_backend/
|-- app/
|   |-- Controllers/
|   |   `-- ProductController.php
|   |-- Models/
|   |   `-- Product.php
|   `-- Views/
|       `-- catalog.php
|-- config/
|   |-- database.php
|   `-- DbConnection.php
|-- database/
|   `-- products.sql
|-- public/
|   `-- assets/
|       |-- css/
|       |   `-- catalog.css
|       |-- images/
|       |   `-- placeholder.svg
|       `-- js/
|           `-- catalog.js
|-- .htaccess
|-- index.php
|-- router.php
`-- README.md
```

## Requirements

- PHP 8.0 or newer
- MySQL 5.7+ or MariaDB 10.3+
- PDO and pdo_mysql enabled

## Setup

1. Import the database:

```bash
mysql -u root -p < database/products.sql
```

This creates the `product_catalog` database, the `products` table, and four sample products.

2. Update the database credentials in `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'product_catalog');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
```

3. Run the application from the project root:

```bash
php -S localhost:8000 router.php
```

4. Open the catalog:

```text
http://localhost:8000
```

## API Endpoint

Product detail JSON is returned by:

```text
GET /?action=detail&id=1
```

Example success response:

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

Invalid IDs return `400 Bad Request`; missing products return `404 Not Found`.

## Assignment Coverage

- `database/products.sql` creates the required `products` table and inserts four sample records.
- `app/Models/Product.php` uses PDO and includes `getAll()` and `getById($id)`.
- `app/Controllers/ProductController.php` routes normal catalog requests and JSON API detail requests.
- `app/Views/catalog.php` renders a Bootstrap product grid and a single reusable detail modal.
- `public/assets/js/catalog.js` uses native `fetch()` to load modal content without reloading the page.
