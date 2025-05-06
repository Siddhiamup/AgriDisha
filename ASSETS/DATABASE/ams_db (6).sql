-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 20, 2025 at 06:04 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ams_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_info`
--

DROP TABLE IF EXISTS `admin_info`;
CREATE TABLE IF NOT EXISTS `admin_info` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `admin_email` varchar(100) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `admin_email` (`admin_email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_info`
--

INSERT INTO `admin_info` (`admin_id`, `admin_email`, `admin_password`, `admin_name`) VALUES
(2, 'shrutihode19@gmail.com', 'shruti12345', 'shruti');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `buyer_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

DROP TABLE IF EXISTS `contact_us`;
CREATE TABLE IF NOT EXISTS `contact_us` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `comment` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `mobile`, `comment`) VALUES
(1, 'siddhi', 'amupsiddhi4@gmail.com', '9322657949', 'xxxx'),
(2, 'siddhi', 'amupsiddhi4@gmail.com', '9322657949', 'xxxx'),
(3, 'siddhi', 'amupsiddhi4@gmail.com', '8979694590', 'XXXXXXXXXXX');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `current_stock` int NOT NULL,
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `status`, `created_at`) VALUES
(2, 7, 'New order received for Wheat (x1)', 'unread', '2025-02-28 09:32:05'),
(3, 7, 'New order received for moong dal (x90)', 'unread', '2025-02-28 09:32:05'),
(4, 7, 'New order received for your product. Order ID: 4', 'unread', '2025-03-04 04:12:00'),
(5, 7, 'New order received for your product. Order ID: 5', 'unread', '2025-03-04 04:12:00'),
(6, 7, 'New order received for your product. Order ID: 6', 'unread', '2025-03-04 04:12:00'),
(10, 7, 'New order received for your product. Order ID: 9', 'unread', '2025-03-04 04:18:35'),
(14, 8, 'New order received for your product. Order ID: 11', 'unread', '2025-03-04 04:57:30'),
(15, 8, 'New order received for your product. Order ID: 12', 'unread', '2025-03-04 04:57:30'),
(17, 8, 'New order received for your product. Order ID: 13', 'unread', '2025-03-04 06:24:20'),
(18, 8, 'New order received for your product. Order ID: 14', 'unread', '2025-03-04 06:24:20'),
(20, 7, 'New order received for your product. Order ID: 15', 'unread', '2025-03-04 06:44:37'),
(22, 7, 'New order received for your product. Order ID: 16', 'unread', '2025-03-04 17:16:36'),
(25, 7, 'New order received for your product. Order ID: 18', 'unread', '2025-03-04 17:19:06'),
(30, 7, 'New order received for your product. Order ID: 21', 'unread', '2025-03-05 11:24:14'),
(31, 7, 'New order received for your product. Order ID: 22', 'unread', '2025-03-05 11:24:14'),
(33, 7, 'New order received for your product. Order ID: 23', 'unread', '2025-03-05 11:29:45'),
(35, 7, 'New order received for your product. Order ID: 27', 'unread', '2025-03-05 11:35:45'),
(37, 7, 'New order received for your product. Order ID: 28', 'unread', '2025-03-05 11:37:03'),
(39, 8, 'New order received for your product. Order ID: 29', 'unread', '2025-03-07 06:33:27'),
(40, 8, 'New order received for your product. Order ID: 30', 'unread', '2025-03-07 06:33:27'),
(42, 8, 'New order received for your product. Order ID: 31', 'unread', '2025-03-07 10:28:33'),
(44, 8, 'New order received for your product. Order ID: 32', 'unread', '2025-03-08 09:56:04'),
(45, 8, 'New order received for your product. Order ID: 33', 'unread', '2025-03-08 09:56:04'),
(47, 8, 'New order received for your product. Order ID: 34', 'unread', '2025-03-08 18:50:29'),
(48, 7, 'New order received for your product. Order ID: 35', 'unread', '2025-03-08 18:50:29'),
(50, 7, 'New order received for your product. Order ID: 36', 'unread', '2025-03-08 19:02:07'),
(51, 7, 'New order received for your product. Order ID: 37', 'unread', '2025-03-08 19:02:07'),
(58, 8, 'New order received for your product. Order ID: 38', 'unread', '2025-03-10 10:52:08'),
(60, 8, 'New order received for your product. Order ID: 39', 'unread', '2025-03-10 11:15:19'),
(62, 7, 'New order received for your product. Order ID: 40', 'unread', '2025-03-10 11:16:12'),
(64, 7, 'New order received for your product. Order ID: 41', 'unread', '2025-03-10 11:37:15'),
(66, 7, 'New order received for your product. Order ID: 42', 'unread', '2025-03-10 11:38:55'),
(70, 8, 'New order received for your product. Order ID: 43', 'unread', '2025-03-11 06:14:36'),
(76, 8, 'New order received for your product. Order ID: 46', 'unread', '2025-03-11 11:50:59'),
(83, 8, 'New order received for your product. Order ID: 50', 'unread', '2025-03-11 13:06:30'),
(85, 7, 'New order received for your product. Order ID: 51', 'unread', '2025-03-11 14:47:29'),
(86, 8, 'New order received for your product. Order ID: 52', 'unread', '2025-03-11 14:47:29'),
(87, 7, 'New order received for your product. Order ID: 53', 'unread', '2025-03-11 14:47:29'),
(88, 11, 'Your order(s) #51, 52, 53 have been placed successfully. Total amount: ₹4,590.00. Payment successful.', 'unread', '2025-03-11 14:47:29');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `buyer_id` int NOT NULL,
  `seller_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `order_date` date NOT NULL,
  `status` enum('Pending','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `seller_id` (`seller_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `seller_id`, `product_id`, `quantity`, `total_price`, `created_at`, `order_date`, `status`) VALUES
(51, 11, 7, 28, 15, 1020.00, '2025-03-11 14:47:29', '2025-03-11', 'Pending'),
(52, 11, 8, 24, 24, 1320.00, '2025-03-11 14:47:29', '2025-03-11', 'Pending'),
(53, 11, 7, 29, 15, 2250.00, '2025-03-11 14:47:29', '2025-03-11', 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `order_tracking`
--

DROP TABLE IF EXISTS `order_tracking`;
CREATE TABLE IF NOT EXISTS `order_tracking` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `status` varchar(50) NOT NULL,
  `update_time` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `notes` text,
  `is_current` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_current` (`is_current`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_tracking`
--

INSERT INTO `order_tracking` (`id`, `order_id`, `status`, `update_time`, `location`, `notes`, `is_current`) VALUES
(1, 38, 'Cancelled', '2025-03-11 10:29:03', NULL, 'Order cancelled by buyer', 0),
(2, 38, 'Cancelled', '2025-03-11 10:31:31', NULL, 'Order cancelled by buyer', 0),
(3, 38, 'Cancelled', '2025-03-11 10:33:32', NULL, 'Order cancelled by buyer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `quantity` int NOT NULL,
  `category` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `seller_id` int NOT NULL,
  `organic_certified` tinyint(1) DEFAULT '0',
  `in_stock` tinyint(1) DEFAULT '1',
  `delivery_options` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `seller_id` (`seller_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `unit`, `quantity`, `category`, `location`, `image_url`, `seller_id`, `organic_certified`, `in_stock`, `delivery_options`, `created_at`, `updated_at`) VALUES
(22, 'Toor Dal', 'Yellow', 55.00, 'kg', 159, 'pulses', 'Nanded', 'uploads/toor.jpg', 8, 1, 1, NULL, '2025-03-08 09:52:29', '2025-03-11 13:06:30'),
(24, 'Kolam Rice', 'fine quality and distinct taste', 55.00, 'kg', 254, 'grains', 'Ratnagiri', 'uploads/ambemohor.jpg', 8, 1, 1, 'bulk_order', '2025-03-11 13:09:38', '2025-03-11 14:47:29'),
(25, 'Turmeric Powder', 'Naturally dried and finely ground ', 56.00, 'kg', 100, 'spices', 'Sangli', 'uploads/haldi.jpg', 8, 1, 1, 'local_pickup,home_delivery', '2025-03-11 14:17:57', '2025-03-11 14:17:57'),
(26, 'Rice', 'Naturally polished and free from artificial chemicals for a healthy diet.', 65.00, 'kg', 346, 'grains', 'Ratnagiri', 'uploads/indrayani2.jpg', 8, 1, 1, 'bulk_order', '2025-03-11 14:20:51', '2025-03-11 14:20:51'),
(27, 'Wheat', 'Grown using natural farming methods', 1350.00, 'quintal', 235, 'grains', 'Haryana', 'uploads/Desi.jpg', 7, 0, 1, 'bulk_order', '2025-03-11 14:28:11', '2025-03-11 14:28:11'),
(28, 'Chana Dal', 'Naturally packed with protein and fiber', 68.00, 'kg', 164, 'pulses', 'Haryana', 'uploads/chana.jpg', 7, 1, 1, 'local_pickup', '2025-03-11 14:31:43', '2025-03-11 14:47:29'),
(29, 'coriander', ' seasoning and spice blends', 150.00, 'kg', 196, 'spices', 'Haryana', 'uploads/coriander.jpg', 7, 1, 1, 'home_delivery', '2025-03-11 14:34:41', '2025-03-11 14:47:29');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `notification_preference` enum('all','important','none') DEFAULT 'all',
  `language` varchar(10) DEFAULT 'en',
  `currency` varchar(10) DEFAULT 'INR',
  `privacy` enum('public','friends','private') DEFAULT 'public',
  `account_security` enum('basic','2FA','high') DEFAULT 'basic',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `buyer_id` int NOT NULL,
  `payment_method` enum('Credit Card','Debit Card','UPI','Net Banking','Cash on Delivery') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('Paid','Unpaid','Refunded') NOT NULL DEFAULT 'Unpaid',
  `transaction_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `buyer_id` (`buyer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `buyer_id`, `payment_method`, `amount`, `payment_status`, `transaction_date`, `created_at`) VALUES
(49, 51, 11, 'UPI', 1020.00, 'Paid', '2025-03-11 14:47:29', '2025-03-11 14:47:29'),
(50, 52, 11, 'UPI', 1320.00, 'Paid', '2025-03-11 14:47:29', '2025-03-11 14:47:29'),
(51, 53, 11, 'UPI', 2250.00, 'Paid', '2025-03-11 14:47:29', '2025-03-11 14:47:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Farmer','Buyer','Admin') NOT NULL,
  `status` enum('approved','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
  `otp` varchar(6) NOT NULL,
  `otp_verified` tinyint(1) DEFAULT '0',
  `otp_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(300) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `status`, `otp`, `otp_verified`, `otp_timestamp`, `reset_token`, `reset_token_expiry`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `phone`) VALUES
(7, 'roopa', 'roopalishilimkar2@gmail.com', '$2y$10$FoOEBgVOPrIVN.pILZBWVejsAtNe0RZDH6vU9SOWEBxthwOdSQ8Qq', 'Farmer', 'approved', '', 0, '2025-02-27 05:01:51', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(8, 'siddhi123', 'amupsiddhi4@gmail.com', '$2y$10$zQiXCRPiyF.x3xXFOOSM9OM5N0Yz9m44S0/wMwuNoq230DCbQ7qXy', 'Farmer', 'approved', '', 0, '2025-02-27 05:23:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(11, 'Shruti Hode', 'shrutihode19@gmail.com', '$2y$10$YXvzox/zJVBlWIXmz30wbOksRtPHBWKujsspV1XR25LtF1dK.Tp2S', 'Buyer', 'pending', '', 0, '2025-03-11 14:41:24', NULL, NULL, 'flat no.206,Sai Nisarg Apartment,', '', 'Pune', 'Maharashtra', '411023', 'India', '9021237270'),
(12, 'siddhi', 'amupsiddhi83@gmail.com', '$2y$10$qcrYC2C4TbWtGCpouldcZef47HVwEu/xru2ns.TeEFiJ39YfeZQdi', 'Farmer', 'approved', '', 0, '2025-03-19 11:34:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `buyer_id` int NOT NULL,
  `product_id` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `buyer_id`, `product_id`, `added_at`) VALUES
(19, 11, 27, '2025-03-11 14:48:04');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
