-- Mini Product Catalog - Database Schema & Sample Data
-- -------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `product_catalog`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `product_catalog`;

-- Drop table if it already exists (for clean re-runs)
DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id`          INT            NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(255)   NOT NULL,
  `description` TEXT           NOT NULL,
  `price`       DECIMAL(10,2)  NOT NULL,
  `sku`         VARCHAR(50)    NOT NULL,
  `image_url`   VARCHAR(255)   NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_products_sku` (`sku`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Sample Data (4 records across different categories)
-- -------------------------------------------------------

INSERT INTO `products` (`name`, `description`, `price`, `sku`, `image_url`) VALUES
(
  'Wireless Noise-Cancelling Headphones',
  'Experience crystal-clear audio with industry-leading noise cancellation. These over-ear headphones deliver up to 30 hours of battery life, foldable design for portability, and premium 40mm drivers for rich, balanced sound. Compatible with all Bluetooth 5.0 devices.',
  149.99,
  'ELEC-HDPH-001',
  'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80'
),
(
  'Men\'s Classic Slim-Fit Chinos',
  'Crafted from a soft, stretch-cotton blend, these slim-fit chinos offer all-day comfort without sacrificing style. Features a mid-rise waist, zip fly, and four-pocket styling. Machine washable and available in multiple colours. Perfect for the office or a casual weekend.',
  59.95,
  'CLTH-CHIN-002',
  'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600&q=80'
),
(
  'Stainless Steel Insulated Water Bottle',
  'Keep beverages cold for 24 hours or hot for 12 hours with this double-wall vacuum-insulated bottle. The wide-mouth opening accommodates ice cubes and makes cleaning easy. BPA-free, leak-proof lid, and durable powder-coat finish. Capacity: 32 oz (946 ml).',
  34.99,
  'HOME-BOTL-003',
  'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=600&q=80'
),
(
  'Mechanical Gaming Keyboard',
  'Dominate every session with tactile Cherry MX Brown switches, per-key RGB backlighting with 16.8 million colour options, and a durable aluminium top plate. Features N-key rollover, a detachable USB-C cable, and dedicated macro keys. Compatible with Windows and macOS.',
  119.00,
  'ELEC-KBRD-004',
  'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=600&q=80'
);
