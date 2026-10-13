-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2026 at 10:11 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_ci4`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Store Admin', 'admin1@example.com', '$2y$10$wkNzzjjgymZiQTSci./3eOkPaZMNa91AS6WGg8DiAZcXVfY1DiPZu', '2026-09-30 15:54:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `link`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Everyday essentials, delivered', 'Fresh arrivals across home, fashion and gadgets', NULL, '/shop', 1, 1, '2026-09-30 15:54:56', NULL),
(2, 'Festive season sale', 'Up to 40% off on selected products', NULL, '/shop', 2, 1, '2026-09-30 15:54:56', NULL),
(3, 'New gadgets are in', 'Headphones, watches and more', NULL, '/shop?cat=electronics', 3, 1, '2026-09-30 15:54:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', 'electronics', NULL, 1, '2026-09-30 15:54:56', NULL),
(2, 'Fashion', 'fashion', NULL, 1, '2026-09-30 15:54:56', NULL),
(3, 'Home & Kitchen', 'home-kitchen', NULL, 1, '2026-09-30 15:54:56', NULL),
(4, 'Beauty', 'beauty', NULL, 1, '2026-09-30 15:54:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enquiries`
--

CREATE TABLE `enquiries` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `source` enum('product','contact') NOT NULL DEFAULT 'product',
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_no` varchar(30) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `pincode` varchar(12) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(30) NOT NULL DEFAULT 'dummy_gateway',
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `txn_id` varchar(60) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `status` enum('placed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'placed',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_no`, `user_id`, `name`, `email`, `phone`, `address`, `city`, `pincode`, `subtotal`, `shipping`, `total`, `payment_method`, `payment_status`, `txn_id`, `ip_address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ORD26100894E5F', 1, 'Demo Customer', 'user@example.com', '9876543210', '12 Beach Road', 'Puducherry', '605001', '1647.00', '0.00', '1647.00', 'dummy_gateway', 'paid', 'TXNEC549EEE63', NULL, 'processing', '2026-10-08 11:00:40', '2026-10-08 11:01:16'),
(2, 'ORD261008301CE', 1, 'Demo Customer', 'user@example.com', '9876543210', '12 Beach Road', 'Puducherry', '605001', '4048.00', '0.00', '4048.00', 'dummy_gateway', 'paid', 'TXN3E10FF3288', NULL, 'processing', '2026-10-08 11:45:11', '2026-10-08 11:45:32'),
(3, 'ORD26100837C59', 1, 'Demo Customer', 'user@example.com', '9876543210', '12 Beach Road', 'Puducherry', '605001', '1098.00', '0.00', '1098.00', 'dummy_gateway', 'paid', 'TXN4B7158FF5B', NULL, 'processing', '2026-10-08 11:58:48', '2026-10-08 11:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `name`, `price`, `qty`, `total`) VALUES
(1, 1, 8, 'Vitamin C Face Serum', '549.00', 3, '1647.00'),
(2, 2, 3, 'Smart Watch Pro', '3499.00', 1, '3499.00'),
(3, 2, 8, 'Vitamin C Face Serum', '549.00', 1, '549.00'),
(4, 3, 8, 'Vitamin C Face Serum', '549.00', 2, '1098.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `subcategory_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `sku` varchar(60) DEFAULT NULL,
  `short_desc` varchar(300) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `is_peak` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `slug`, `sku`, `short_desc`, `description`, `price`, `sale_price`, `stock`, `image`, `featured`, `is_current`, `is_peak`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Wireless Headphones', 'wireless-headphones', 'EL-001', '40-hour battery, deep bass, foldable design.', 'Over-ear wireless headphones with active noise reduction, soft ear cushions and a 40-hour battery. Comes with a carry pouch and a charging cable.', '2999.00', '1999.00', 25, NULL, 1, 0, 0, 1, '2026-09-30 15:54:56', '2026-10-08 12:18:29'),
(2, 1, 1, 'Bluetooth Speaker', 'bluetooth-speaker', 'EL-002', 'Splash-proof speaker with 12 hours of playtime.', 'Portable speaker with 360 degree sound, IPX5 splash protection and a built-in mic for calls.', '1799.00', '1299.00', 40, NULL, 1, 0, 0, 1, '2026-09-30 15:54:56', NULL),
(3, 1, 2, 'Smart Watch Pro', 'smart-watch-pro', 'EL-003', 'Heart rate, sleep tracking and 7-day battery.', 'AMOLED smart watch with heart-rate, SpO2 and sleep tracking, 100+ watch faces and 7-day battery life.', '4999.00', '3499.00', 14, NULL, 1, 0, 0, 1, '2026-09-30 15:54:56', NULL),
(4, 2, 3, 'Cotton Casual Shirt', 'cotton-casual-shirt', 'FA-001', 'Breathable 100% cotton, regular fit.', 'A everyday shirt in soft washed cotton. Regular fit, available in multiple sizes.', '1299.00', '899.00', 60, NULL, 0, 0, 0, 1, '2026-09-30 15:54:56', NULL),
(5, 2, 4, 'Printed Kurti', 'printed-kurti', 'FA-002', 'Light rayon kurti with block print.', 'Comfortable rayon kurti with a hand block-inspired print and three-quarter sleeves.', '1099.00', NULL, 50, NULL, 1, 0, 0, 1, '2026-09-30 15:54:56', NULL),
(6, 3, 5, 'Non-stick Cookware Set', 'nonstick-cookware-set', 'HK-001', '3-piece set: kadai, tawa and fry pan.', 'Induction-friendly non-stick cookware set with cool-touch handles.', '2499.00', '1899.00', 20, NULL, 1, 0, 0, 1, '2026-09-30 15:54:56', NULL),
(7, 3, 6, 'Ceramic Table Vase', 'ceramic-table-vase', 'HK-002', 'Hand-finished ceramic vase, 25 cm.', 'Minimal ceramic vase with a matte glaze. Suits fresh and dried flowers.', '799.00', NULL, 35, NULL, 0, 0, 0, 1, '2026-09-30 15:54:56', NULL),
(8, 4, 7, 'Vitamin C Face Serum', 'vitamin-c-face-serum', 'BT-001', '30 ml brightening serum for daily use.', 'Lightweight serum with 10% vitamin C and hyaluronic acid. Dermatologist tested.', '699.00', '549.00', 74, NULL, 1, 0, 0, 1, '2026-09-30 15:54:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `skey` varchar(60) NOT NULL,
  `svalue` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`skey`, `svalue`, `updated_at`) VALUES
('about_body', 'We began as a two-person shop selling home and kitchen items to neighbours. Today we ship electronics, fashion, home and beauty products to customers across the country.\n\nEvery product listed here is checked by our team before it goes live. If something is not right, you can return it within 7 days.', '2026-10-08 17:49:44'),
('about_heading', 'Started in Puducherry, shipping across India', '2026-10-08 17:49:44'),
('about_image', '', '2026-10-08 17:49:44'),
('about_promises', '[{\"title\":\"Genuine products\",\"text\":\"Sourced from brands and verified suppliers.\"},{\"title\":\"Careful packing\",\"text\":\"Fragile items are double-wrapped.\"},{\"title\":\"Human support\",\"text\":\"Write to us and a real person answers.\"}]', '2026-10-08 17:49:44'),
('about_stats', '[{\"value\":\"10k+\",\"label\":\"Orders delivered\"},{\"value\":\"500+\",\"label\":\"Products\"},{\"value\":\"4.7/5\",\"label\":\"Average rating\"},{\"value\":\"48 hrs\",\"label\":\"Typical dispatch time\"}]', '2026-10-08 17:49:44'),
('about_tagline', 'A small team selling things we would use ourselves.', '2026-10-08 17:49:44'),
('contact_address', '12 Beach Road, Puducherry 605001', '2026-10-08 17:49:44'),
('contact_email', 'support@shopkart.test', '2026-10-08 17:49:44'),
('contact_hours', 'Mon–Sat, 9am – 7pm', '2026-10-08 17:49:44'),
('contact_map', '', '2026-10-08 17:49:44'),
('contact_phone', '+91 98765 43210', '2026-10-08 17:49:44'),
('contact_tagline', 'Send us a message and we will reply within one working day.', '2026-10-08 17:49:44');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Audio', 'audio', 1, '2026-09-30 15:54:56', NULL),
(2, 1, 'Wearables', 'wearables', 1, '2026-09-30 15:54:56', NULL),
(3, 2, 'Men', 'men', 1, '2026-09-30 15:54:56', NULL),
(4, 2, 'Women', 'women', 1, '2026-09-30 15:54:56', NULL),
(5, 3, 'Cookware', 'cookware', 1, '2026-09-30 15:54:56', NULL),
(6, 3, 'Decor', 'decor', 1, '2026-09-30 15:54:56', NULL),
(7, 4, 'Skincare', 'skincare', 1, '2026-09-30 15:54:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(120) DEFAULT NULL,
  `message` text NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 5,
  `photo` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pincode` varchar(12) DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `address`, `city`, `pincode`, `reset_token`, `reset_expires`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Demo Customer', 'user@example.com', '9876543210', '$2y$10$uc8bTl/sRYQs4yPsJO0rxu0bGgQBfTbhtZot6TJTpT0q87pUd.yhG', '12 Beach Road', 'Puducherry', '605001', NULL, NULL, 1, '2026-09-30 15:54:56', '2026-10-08 11:58:48');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `visit_date` date NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `last_page` varchar(255) DEFAULT NULL,
  `hits` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `ip_address`, `visit_date`, `user_id`, `user_agent`, `last_page`, `hits`, `created_at`, `updated_at`) VALUES
(1, '192.168.1.103', '2026-10-08', 1, 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.19041.6456', '/shop?q=phone', 18, '2026-10-08 13:14:58', '2026-10-08 13:42:29'),
(19, '192.168.1.103', '2026-10-12', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '/', 2, '2026-10-12 14:15:16', '2026-10-12 14:15:22'),
(21, '192.168.1.2', '2026-10-13', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '/', 1, '2026-10-13 08:09:45', '2026-10-13 08:09:45');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_page_views`
--

CREATE TABLE `visitor_page_views` (
  `id` int(10) UNSIGNED NOT NULL,
  `visitor_id` int(10) UNSIGNED NOT NULL,
  `page` varchar(255) NOT NULL,
  `viewed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `visitor_page_views`
--

INSERT INTO `visitor_page_views` (`id`, `visitor_id`, `page`, `viewed_at`) VALUES
(1, 1, '/', '2026-10-08 13:42:27'),
(2, 1, '/shop', '2026-10-08 13:42:28'),
(3, 1, '/about', '2026-10-08 13:42:29'),
(4, 1, '/shop?q=phone', '2026-10-08 13:42:29'),
(5, 19, '/', '2026-10-12 14:15:16'),
(6, 19, '/', '2026-10-12 14:15:22'),
(7, 21, '/', '2026-10-13 08:09:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_no` (`order_no`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`skey`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_day` (`ip_address`,`visit_date`),
  ADD KEY `visit_date` (`visit_date`);

--
-- Indexes for table `visitor_page_views`
--
ALTER TABLE `visitor_page_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visitor_id` (`visitor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `visitor_page_views`
--
ALTER TABLE `visitor_page_views`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `visitor_page_views`
--
ALTER TABLE `visitor_page_views`
  ADD CONSTRAINT `visitor_page_views_ibfk_1` FOREIGN KEY (`visitor_id`) REFERENCES `visitors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
