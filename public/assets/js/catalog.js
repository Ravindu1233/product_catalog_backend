/**
 * catalog.js
 *
 * Handles all client-side behaviour for the Mini Product Catalog.
 *
 * Responsibilities:
 *  1. Listen for "View Details" button clicks on product cards.
 *  2. Fire a native fetch() request to the PHP JSON endpoint.
 *  3. Populate the Bootstrap modal with the returned data.
 *  4. Handle loading states and graceful error display.
 *
 * No jQuery or third-party helpers — pure ES6+.
 */

'use strict';

// ─── DOM references ──────────────────────────────────────────────────────────

const modalEl          = document.getElementById('productDetailModal');
const modalLoader      = document.getElementById('modalLoader');
const modalError       = document.getElementById('modalError');
const modalErrorText   = document.getElementById('modalErrorText');
const modalContent     = document.getElementById('modalContent');

// Fields inside the modal content panel
const modalImage       = document.getElementById('modalImage');
const modalSku         = document.getElementById('modalSku');
const modalName        = document.getElementById('modalName');
const modalPrice       = document.getElementById('modalPrice');
const modalDescription = document.getElementById('modalDescription');

// Bootstrap Modal instance (created once, reused for every click)
const bsModal = new bootstrap.Modal(modalEl);

// ─── Event delegation ────────────────────────────────────────────────────────

/**
 * Attach a single delegated listener to the document body.
 * This works for dynamically added cards as well as static ones.
 */
document.body.addEventListener('click', (event) => {
  const btn = event.target.closest('.btn-view-details');
  if (!btn) return;

  const productId = btn.dataset.productId;
  if (!productId) return;

  openProductModal(productId);
});

// ─── Core functions ───────────────────────────────────────────────────────────

/**
 * Shows the modal in its loading state, then fetches product data.
 *
 * @param {string|number} productId
 */
async function openProductModal(productId) {
  showLoadingState();
  bsModal.show();

  try {
    const product = await fetchProduct(productId);
    populateModal(product);
    showContentState();
  } catch (err) {
    showErrorState(err.message || 'An unexpected error occurred. Please try again.');
  }
}

/**
 * Fetches a single product from the PHP JSON endpoint.
 *
 * @param {string|number} productId
 * @returns {Promise<Object>}  Resolves with the product data object.
 * @throws {Error}             On network failure or non-OK HTTP status.
 */
async function fetchProduct(productId) {
  const url = `/?action=detail&id=${encodeURIComponent(productId)}`;

  const response = await fetch(url, {
    method:  'GET',
    headers: { 'Accept': 'application/json' },
  });

  // Parse the JSON body regardless of HTTP status so we can read error messages.
  let payload;
  try {
    payload = await response.json();
  } catch {
    throw new Error(`Server returned an invalid response (HTTP ${response.status}).`);
  }

  if (!response.ok || !payload.success) {
    throw new Error(payload.error || `HTTP error: ${response.status}`);
  }

  return payload.data;
}

/**
 * Writes product data into the modal DOM elements.
 *
 * @param {Object} product
 * @param {number} product.id
 * @param {string} product.name
 * @param {string} product.sku
 * @param {string} product.description
 * @param {number} product.price
 * @param {string|null} product.image_url
 */
function populateModal(product) {
  const formattedPrice = `$${product.price.toFixed(2)}`;
  const imageUrl       = product.image_url || '/assets/images/placeholder.svg';

  modalImage.src             = imageUrl;
  modalImage.alt             = product.name;
  modalSku.textContent       = `\u{1F4CB} ${product.sku}`;       // clipboard emoji prefix
  modalSku.innerHTML         = `<i class="bi bi-upc me-1"></i>${escapeHtml(product.sku)}`;
  modalName.textContent      = product.name;
  modalPrice.textContent     = formattedPrice;
  modalDescription.textContent = product.description;

  // Update the modal's aria-label for accessibility
  modalEl.querySelector('.modal-title').innerHTML =
    `<i class="bi bi-box-seam me-2"></i>${escapeHtml(product.name)}`;
}

// ─── UI state helpers ─────────────────────────────────────────────────────────

/** Shows the spinner; hides content and error panels. */
function showLoadingState() {
  modalLoader.classList.remove('d-none');
  modalContent.classList.add('d-none');
  modalError.classList.add('d-none');
}

/** Hides the spinner and error; reveals the populated content panel. */
function showContentState() {
  modalLoader.classList.add('d-none');
  modalError.classList.add('d-none');
  modalContent.classList.remove('d-none');
}

/** Hides the spinner and content; shows the error panel with a message. */
function showErrorState(message) {
  modalLoader.classList.add('d-none');
  modalContent.classList.add('d-none');
  modalErrorText.textContent = message;
  modalError.classList.remove('d-none');
}

// ─── Utility ──────────────────────────────────────────────────────────────────

/**
 * Escapes a string for safe insertion as HTML text.
 *
 * @param {string} str
 * @returns {string}
 */
function escapeHtml(str) {
  return String(str)
    .replace(/&/g,  '&amp;')
    .replace(/</g,  '&lt;')
    .replace(/>/g,  '&gt;')
    .replace(/"/g,  '&quot;')
    .replace(/'/g,  '&#039;');
}
