<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/DbConnection.php';
require_once __DIR__ . '/app/Models/Product.php';
require_once __DIR__ . '/app/Controllers/ProductController.php';

try {
    $db = Database::getInstance();
    $productModel = new Product($db);
    $controller = new ProductController($productModel);

    $controller->handleRequest();
} catch (RuntimeException $e) {
    http_response_code(500);

    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $isApiRequest = strncmp($path, '/api/', 5) === 0
        || (isset($_GET['action']) && in_array($_GET['action'], ['detail', 'list'], true));

    if ($isApiRequest) {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
        ]);
        exit;
    }

    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">
          <title>Server Error</title></head><body>
          <h2 style="color:#c0392b;font-family:sans-serif;">Application Error</h2>
          <p style="font-family:sans-serif;">' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</p>
          <p style="font-family:sans-serif;">Please check your <code>config/database.php</code> settings.</p>
          </body></html>';
}
