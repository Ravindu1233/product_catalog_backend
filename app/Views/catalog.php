<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mini Product Catalog</title>

  <!-- Bootstrap 5 CSS (CDN) -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  />

  <!-- Bootstrap Icons (CDN) -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
  />

  <!-- Custom styles -->
  <link rel="stylesheet" href="/assets/css/catalog.css" />
</head>
<body>

<!-- ─── Navbar ─────────────────────────────────────────────────────────────── -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">
      <i class="bi bi-shop me-2"></i>ShopLite
    </a>
    <span class="navbar-text text-secondary small">
      <?= count($products) ?> product<?= count($products) !== 1 ? 's' : '' ?> available
    </span>
  </div>
</nav>

<!-- ─── Hero ────────────────────────────────────────────────────────────────── -->
<header class="hero-section py-5 mb-4">
  <div class="container text-center">
    <h1 class="display-5 fw-bold mb-2">Our Product Catalog</h1>
    <p class="text-muted lead mb-0">Click <strong>View Details</strong> on any card for full product information.</p>
  </div>
</header>

<!-- ─── Product Grid ─────────────────────────────────────────────────────────── -->
<main class="container mb-5">

  <?php if (empty($products)): ?>
    <div class="alert alert-warning text-center" role="alert">
      <i class="bi bi-exclamation-triangle me-2"></i>
      No products found. Make sure you have imported <code>database/products.sql</code>.
    </div>
  <?php else: ?>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
      <?php foreach ($products as $product): ?>
        <?php
          // Sanitise all output to prevent XSS.
          $id    = (int)   $product['id'];
          $name  = htmlspecialchars($product['name'],      ENT_QUOTES, 'UTF-8');
          $sku   = htmlspecialchars($product['sku'],       ENT_QUOTES, 'UTF-8');
          $price = number_format((float) $product['price'], 2);
          $img   = !empty($product['image_url'])
                     ? htmlspecialchars($product['image_url'], ENT_QUOTES, 'UTF-8')
                     : '/assets/images/placeholder.svg';
        ?>
        <div class="col">
          <div class="card product-card h-100 shadow-sm border-0">

            <!-- Product Image -->
            <div class="product-img-wrapper">
              <img
                src="<?= $img ?>"
                alt="<?= $name ?>"
                class="card-img-top product-img"
                loading="lazy"
                onerror="this.src='/assets/images/placeholder.svg'"
              />
            </div>

            <div class="card-body d-flex flex-column">
              <!-- SKU badge -->
              <span class="badge bg-secondary mb-2 align-self-start small">
                <i class="bi bi-upc me-1"></i><?= $sku ?>
              </span>

              <h5 class="card-title fw-semibold mb-1"><?= $name ?></h5>

              <p class="product-price fs-5 fw-bold mt-auto mb-3">
                $<?= $price ?>
              </p>

              <!-- "View Details" triggers fetch() — data-id is used by JS -->
              <button
                class="btn btn-primary btn-view-details w-100"
                data-product-id="<?= $id ?>"
                aria-label="View details for <?= $name ?>"
              >
                <i class="bi bi-eye me-1"></i>View Details
              </button>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>

  <?php endif; ?>
</main>

<!-- ─── Product Detail Modal ──────────────────────────────────────────────── -->
<div
  class="modal fade"
  id="productDetailModal"
  tabindex="-1"
  aria-labelledby="modalProductName"
  aria-hidden="true"
>
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">

      <!-- Modal Header -->
      <div class="modal-header bg-dark text-white border-0">
        <h5 class="modal-title fw-bold" id="modalProductName">
          <i class="bi bi-box-seam me-2"></i>Product Details
        </h5>
        <button
          type="button"
          class="btn-close btn-close-white"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-0">

        <!-- Loading Spinner (shown while fetch is in-flight) -->
        <div id="modalLoader" class="text-center py-5">
          <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;">
            <span class="visually-hidden">Loading…</span>
          </div>
          <p class="text-muted mt-3 mb-0">Fetching product details…</p>
        </div>

        <!-- Error Message (shown on fetch failure) -->
        <div id="modalError" class="alert alert-danger m-3 d-none" role="alert">
          <i class="bi bi-x-circle me-2"></i>
          <span id="modalErrorText">Something went wrong. Please try again.</span>
        </div>

        <!-- Product Detail Content (populated by JS) -->
        <div id="modalContent" class="d-none">
          <div class="row g-0">

            <!-- Left: Image -->
            <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-3">
              <img
                id="modalImage"
                src=""
                alt=""
                class="img-fluid rounded modal-product-img"
                onerror="this.src='/assets/images/placeholder.svg'"
              />
            </div>

            <!-- Right: Details -->
            <div class="col-md-7 p-4">

              <span id="modalSku" class="badge bg-secondary mb-3 fs-6">
                <i class="bi bi-upc me-1"></i>
              </span>

              <h4 id="modalName" class="fw-bold mb-3"></h4>

              <p id="modalPrice" class="fs-3 fw-bold text-primary mb-3"></p>

              <hr />

              <h6 class="text-muted text-uppercase small fw-semibold mb-2">Description</h6>
              <p id="modalDescription" class="text-secondary"></p>

            </div>
          </div>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer border-0 bg-light">
        <button
          type="button"
          class="btn btn-outline-secondary"
          data-bs-dismiss="modal"
        >
          <i class="bi bi-x me-1"></i>Close
        </button>
      </div>

    </div>
  </div>
</div>

<!-- ─── Footer ───────────────────────────────────────────────────────────────── -->
<footer class="footer bg-dark text-secondary py-3 mt-auto">
  <div class="container text-center small">
    &copy; <?= date('Y') ?> ShopLite &mdash; Mini Product Catalog
  </div>
</footer>

<!-- Bootstrap 5 JS Bundle (CDN) -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmXIInRzp1oqWg4Yk+LnuSLlOMRr"
  crossorigin="anonymous"
></script>

<!-- Custom JS -->
<script src="/assets/js/catalog.js"></script>

</body>
</html>
