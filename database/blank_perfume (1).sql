-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2026 at 11:10 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blank_perfume`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(9, 1, 1, 1, '2026-05-16 08:44:06'),
(10, 2, 2, 1, '2026-05-16 08:45:43');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `voucher_code` varchar(50) DEFAULT NULL,
  `points_earned` int(11) DEFAULT 0,
  `status` enum('pending','paid','shipped','completed','cancelled') DEFAULT 'paid',
  `payment_method` varchar(50) DEFAULT 'Online Banking',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivery_name` varchar(150) DEFAULT NULL,
  `delivery_phone` varchar(30) DEFAULT NULL,
  `delivery_email` varchar(150) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `payment_type` enum('card','fpx') DEFAULT 'card',
  `card_holder` varchar(150) DEFAULT NULL,
  `card_number_masked` varchar(30) DEFAULT NULL,
  `card_expiry` varchar(7) DEFAULT NULL,
  `card_brand` varchar(30) DEFAULT NULL,
  `fpx_bank` varchar(100) DEFAULT NULL,
  `points_redeemed` int(11) NOT NULL DEFAULT 0,
  `points_discount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `subtotal`, `tax`, `discount`, `total`, `voucher_code`, `points_earned`, `status`, `payment_method`, `created_at`, `delivery_name`, `delivery_phone`, `delivery_email`, `delivery_address`, `payment_type`, `card_holder`, `card_number_masked`, `card_expiry`, `card_brand`, `fpx_bank`, `points_redeemed`, `points_discount`) VALUES
(1, 1, 22.00, 1.32, 0.00, 23.32, NULL, 22, 'paid', 'FPX', '2026-05-16 07:54:10', 'test12345', '0123456789', 'test@gmail.com', 'Rawang Selangor', 'fpx', NULL, NULL, NULL, NULL, 'Maybank2u', 0, 0.00),
(2, 1, 50.00, 2.70, 5.00, 47.70, 'WELCOME10', 50, 'paid', 'FPX', '2026-05-16 08:42:57', 'test12345', '0123456789', 'test@gmail.com', 'Rawang Selangor', 'fpx', NULL, NULL, NULL, NULL, 'Maybank2u', 0, 0.00),
(3, 1, 950.00, 57.00, 0.00, 1007.00, NULL, 950, 'paid', 'FPX', '2026-05-16 08:43:58', 'test12345', '0123456789', 'test@gmail.com', 'Rawang Selangor', 'fpx', NULL, NULL, NULL, NULL, 'Maybank2u', 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 1, 5, 1, 22.00),
(2, 2, 2, 2, 25.00),
(3, 3, 1, 38, 25.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `variant` varchar(100) NOT NULL,
  `type` enum('gel','liquid') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `stock` int(11) DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `variant`, `type`, `price`, `image`, `stock`, `created_at`, `description`) VALUES
(1, 'Blank Gel', 'Sweet Nectar/Peach (Soft)', 'gel', 25.00, 'blank-black-rose.png', 62, '2026-05-02 17:31:00', 'A gentle peach fragrance with a smooth and comforting scent experience. Lightly sweet, clean, and calming — perfect for those who enjoy a softer fruity aroma that feels relaxing without being overpowering. Creates a cozy atmosphere while staying fresh and easy on the senses.'),
(2, 'Blank Gel', 'Sweet Nectar/Peach (Strong)', 'gel', 25.00, 'blank-black-rose.png', 98, '2026-05-02 17:31:00', 'A richer peach scent with a stronger premium fragrance that instantly freshens up your space. Sweet, fruity, and long-lasting with a bolder presence — while still maintaining a smooth finish that doesn\'t feel too sharp or overwhelming. Perfect for those who prefer a more noticeable scent without causing headaches.'),
(3, 'Blank Gel', 'Dreamy Melon', 'gel', 25.00, 'blank-black.png', 100, '2026-05-02 17:31:00', 'A fresh and soft honeydew-inspired scent that brings a calming atmosphere to every drive. Smooth, clean, and comforting with just the right touch of sweetness — never too overpowering. Perfect for creating a relaxing, peaceful vibe in your car anytime, anywhere.'),
(4, 'Blank Liquid', 'HoneyDew', 'liquid', 22.00, 'blank-lemon.png', 100, '2026-05-02 17:31:00', 'A bright citrus fragrance that keeps your car feeling clean and energised throughout the day.'),
(5, 'Blank Liquid', 'Peach', 'liquid', 22.00, 'blank-rose.png', 99, '2026-05-02 17:31:00', 'A playful fruity scent blended with a sweet bubble gum twist that instantly brightens your mood. Fresh, fun, and youthful without being too heavy — perfect for adding a cheerful vibe to every drive.'),
(6, 'Blank Liquid', 'Summer Paradise', 'liquid', 22.00, 'blank-summer.png', 100, '2026-05-02 17:31:00', 'A refreshing fruity blend with soft melon notes balanced by hints of peach, lychee, and apple. Lightly sweet, smooth, and tropical — creating a relaxing summer-like atmosphere that feels fresh and clean all day long.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `phone`, `address`) VALUES
(1, 'test12345', 'test@gmail.com', '$2y$10$A4Sspjf6.Kqzc2Vat5hSvuvJwPUMckD8wUa.76bU.mX6uKsNaoHMq', '2026-04-19 04:04:14', '0123456789', 'Rawang Selangor'),
(2, 'test2', 'test2@gmail.com', '$2y$10$W4hfkaU68pIxnUENh3WuQ.eEH/6CNJycIR1c.s/rtDI5ElBjDm/sq', '2026-05-16 08:45:23', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_points`
--

CREATE TABLE `user_points` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_points`
--

INSERT INTO `user_points` (`id`, `user_id`, `total_points`) VALUES
(1, 1, 1022);

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `min_order` decimal(10,2) DEFAULT 0.00,
  `max_uses` int(11) DEFAULT 100,
  `used_count` int(11) DEFAULT 0,
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `new_user_only` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `discount_percent`, `min_order`, `max_uses`, `used_count`, `expires_at`, `is_active`, `new_user_only`) VALUES
(1, 'WELCOME10', 10.00, 0.00, 100, 1, '2026-12-31', 1, 0),
(2, 'BLANK20', 20.00, 50.00, 100, 0, '2026-12-31', 1, 0),
(3, 'NEWUSER15', 15.00, 0.00, 9999, 0, '2026-12-31', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_uses`
--

CREATE TABLE `voucher_uses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voucher_uses`
--

INSERT INTO `voucher_uses` (`id`, `user_id`, `voucher_id`, `order_id`, `used_at`) VALUES
(1, 1, 1, 2, '2026-05-16 08:42:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_points`
--
ALTER TABLE `user_points`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `voucher_uses`
--
ALTER TABLE `voucher_uses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_voucher` (`user_id`,`voucher_id`),
  ADD KEY `voucher_id` (`voucher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_points`
--
ALTER TABLE `user_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `voucher_uses`
--
ALTER TABLE `voucher_uses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `user_points`
--
ALTER TABLE `user_points`
  ADD CONSTRAINT `user_points_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `voucher_uses`
--
ALTER TABLE `voucher_uses`
  ADD CONSTRAINT `voucher_uses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_uses_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
