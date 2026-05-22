<?php

/**
 * ProductController
 *
 * Central routing class.  All incoming HTTP requests pass through here.
 *
 *  - Standard request  → renders the HTML catalog view
 *  - ?action=detail&id=X → returns JSON for the requested product
 *
 * No framework is used; routing is handled manually via query-string inspection.
 */
class ProductController
{
    /** @var Product */
    private Product $productModel;

    /**
     * @param Product $productModel  Injected model instance.
     */
    public function __construct(Product $productModel)
    {
        $this->productModel = $productModel;
    }

    /**
     * Entry point — inspect the request and delegate accordingly.
     *
     * @return void
     */
    public function handleRequest(): void
    {
        $this->sendCorsHeaders();

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            return;
        }

        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $action = isset($_GET['action']) ? trim($_GET['action']) : '';

        if ($path === '/api/products' || $action === 'list') {
            $this->handleApiList();
        } elseif (preg_match('#^/api/products/(\d+)$#', $path, $matches) === 1) {
            $this->handleApiDetail((int) $matches[1]);
        } elseif ($action === 'detail') {
            $this->handleApiDetail();
        } else {
            $this->renderCatalogView();
        }
    }

    // -------------------------------------------------------------------------
    // Private handlers
    // -------------------------------------------------------------------------

    private function sendCorsHeaders(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Accept');
    }

    private function handleApiList(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $products = array_map(static function (array $product): array {
            $product['price'] = (float) $product['price'];
            return $product;
        }, $this->productModel->getAll());

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data'    => $products,
        ]);
    }

    /**
     * API endpoint: ?action=detail&id=X
     *
     * Responds with JSON.  Sets Content-Type header and appropriate HTTP status.
     *
     * @return void
     */
    private function handleApiDetail(?int $routeId = null): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = $routeId;

        if ($id === null) {
            // Validate that `id` is a positive integer.
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1],
            ]);
        }

        if ($id === false || $id === null || $id < 1) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error'   => 'A valid numeric product ID is required.',
            ]);
            return;
        }

        $product = $this->productModel->getById($id);

        if ($product === null) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error'   => 'Product not found.',
            ]);
            return;
        }

        // Cast price to float so JSON encodes it as a number, not a string.
        $product['price'] = (float) $product['price'];

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data'    => $product,
        ]);
    }

    /**
     * Standard request: renders the full HTML catalog page.
     *
     * Passes the product list to the view via a local variable.
     *
     * @return void
     */
    private function renderCatalogView(): void
    {
        $products = $this->productModel->getAll();

        // Include the view — it has access to $products.
        require_once __DIR__ . '/../Views/catalog.php';
    }
}
