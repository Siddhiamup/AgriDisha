-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 07, 2025 at 08:12 AM
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `mobile`, `comment`) VALUES
(1, 'siddhi', 'amupsiddhi4@gmail.com', '9322657949', 'xxxx'),
(2, 'siddhi', 'amupsiddhi4@gmail.com', '9322657949', 'xxxx');

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
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `status`, `created_at`) VALUES
(1, 2, 'New order received for Toor Dal (x1)', 'unread', '2025-02-28 09:32:05'),
(2, 7, 'New order received for Wheat (x1)', 'unread', '2025-02-28 09:32:05'),
(3, 7, 'New order received for moong dal (x90)', 'unread', '2025-02-28 09:32:05'),
(4, 7, 'New order received for your product. Order ID: 4', 'unread', '2025-03-04 04:12:00'),
(5, 7, 'New order received for your product. Order ID: 5', 'unread', '2025-03-04 04:12:00'),
(6, 7, 'New order received for your product. Order ID: 6', 'unread', '2025-03-04 04:12:00'),
(7, 2, 'New order received for your product. Order ID: 7', 'unread', '2025-03-04 04:12:00'),
(8, 9, 'Your order(s) #4, 5, 6, 7 have been placed successfully. Total amount: ₹61,900.00. Payment will be collected on delivery.', 'unread', '2025-03-04 04:12:00'),
(9, 2, 'New order received for your product. Order ID: 8', 'unread', '2025-03-04 04:18:35'),
(10, 7, 'New order received for your product. Order ID: 9', 'unread', '2025-03-04 04:18:35'),
(11, 9, 'Your order(s) #8, 9 have been placed successfully. Total amount: ₹30,940.00. Payment will be collected on delivery.', 'unread', '2025-03-04 04:18:35'),
(12, 2, 'New order received for your product. Order ID: 10', 'unread', '2025-03-04 04:24:23'),
(13, 9, 'Your order(s) #10 have been placed successfully. Total amount: ₹50,000.00. Payment will be collected on delivery.', 'unread', '2025-03-04 04:24:23'),
(14, 8, 'New order received for your product. Order ID: 11', 'unread', '2025-03-04 04:57:30'),
(15, 8, 'New order received for your product. Order ID: 12', 'unread', '2025-03-04 04:57:30'),
(16, 9, 'Your order(s) #11, 12 have been placed successfully. Total amount: ₹13,750.00. Payment will be collected on delivery.', 'unread', '2025-03-04 04:57:30'),
(17, 8, 'New order received for your product. Order ID: 13', 'unread', '2025-03-04 06:24:20'),
(18, 8, 'New order received for your product. Order ID: 14', 'unread', '2025-03-04 06:24:20'),
(19, 9, 'Your order(s) #13, 14 have been placed successfully. Total amount: ₹14,235.00. Payment will be collected on delivery.', 'unread', '2025-03-04 06:24:20'),
(20, 7, 'New order received for your product. Order ID: 15', 'unread', '2025-03-04 06:44:37'),
(21, 9, 'Your order(s) #15 have been placed successfully. Total amount: ₹27,360.00. Payment will be collected on delivery.', 'unread', '2025-03-04 06:44:37'),
(22, 7, 'New order received for your product. Order ID: 16', 'unread', '2025-03-04 17:16:36'),
(23, 2, 'New order received for your product. Order ID: 17', 'unread', '2025-03-04 17:16:36'),
(24, 9, 'Your order(s) #16, 17 have been placed successfully. Total amount: ₹28,600.00. Payment will be collected on delivery.', 'unread', '2025-03-04 17:16:36'),
(25, 7, 'New order received for your product. Order ID: 18', 'unread', '2025-03-04 17:19:06'),
(26, 2, 'New order received for your product. Order ID: 19', 'unread', '2025-03-04 17:19:06'),
(27, 9, 'Your order(s) #18, 19 have been placed successfully. Total amount: ₹14,900.00. Payment successful.', 'unread', '2025-03-04 17:19:06'),
(28, 2, 'New order received for your product. Order ID: 20', 'unread', '2025-03-05 06:09:02'),
(29, 9, 'Your order(s) #20 have been placed successfully. Total amount: ₹37,500.00. Payment will be collected on delivery.', 'unread', '2025-03-05 06:09:02'),
(30, 7, 'New order received for your product. Order ID: 21', 'unread', '2025-03-05 11:24:14'),
(31, 7, 'New order received for your product. Order ID: 22', 'unread', '2025-03-05 11:24:14'),
(32, 9, 'Your order(s) #21, 22 have been placed successfully. Total amount: ₹5,200.00. Payment successful.', 'unread', '2025-03-05 11:24:14'),
(33, 7, 'New order received for your product. Order ID: 23', 'unread', '2025-03-05 11:29:45'),
(34, 9, 'Your order(s) #23 have been placed successfully. Total amount: ₹2,100.00. Payment will be collected on delivery.', 'unread', '2025-03-05 11:29:45'),
(35, 7, 'New order received for your product. Order ID: 27', 'unread', '2025-03-05 11:35:45'),
(36, 9, 'Your order(s) #27 have been placed successfully. Total amount: ₹2,400.00. Payment will be collected on delivery.', 'unread', '2025-03-05 11:35:45'),
(37, 7, 'New order received for your product. Order ID: 28', 'unread', '2025-03-05 11:37:03'),
(38, 9, 'Your order(s) #28 have been placed successfully. Total amount: ₹30,000.00. Payment will be collected on delivery.', 'unread', '2025-03-05 11:37:03'),
(39, 8, 'New order received for your product. Order ID: 29', 'unread', '2025-03-07 06:33:27'),
(40, 8, 'New order received for your product. Order ID: 30', 'unread', '2025-03-07 06:33:27'),
(41, 9, 'Your order(s) #29, 30 have been placed successfully. Total amount: ₹8,550.00. Payment will be collected on delivery.', 'unread', '2025-03-07 06:33:27');

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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `seller_id`, `product_id`, `quantity`, `total_price`, `created_at`, `order_date`, `status`) VALUES
(29, 9, 8, 18, 150, 7500.00, '2025-03-07 06:33:27', '2025-03-07', 'Pending'),
(30, 9, 8, 17, 30, 1050.00, '2025-03-07 06:33:27', '2025-03-07', 'Pending');

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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `seller_id` (`seller_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `unit`, `quantity`, `category`, `location`, `image_url`, `seller_id`, `organic_certified`, `in_stock`, `created_at`, `updated_at`) VALUES
(4, 'Laung', ' strong, warm aroma and natural antiseptic properties', 500.00, '', 200, 'pulses', '', 'uploads/laung.jpg', 2, 0, 1, '2025-02-24 03:51:53', '2025-03-05 00:39:02'),
(5, 'Wheat', 'rich in fiber and protein', 50.00, '', 200, 'grains', '', 'uploads/Desi.jpg', 2, 0, 1, '2025-02-24 03:53:17', '2025-03-03 17:18:35'),
(6, 'Toor Dal', 'Farm-fresh Toor Dal with a rich, nutty taste', 100.00, '', 128, 'pulses', '', 'uploads/toor.jpg', 2, 0, 1, '2025-02-24 03:55:04', '2025-03-03 17:12:00'),
(11, 'Wheat', 'rich in protiens and fibre', 60.00, '', 360, 'grains', '', 'uploads/Desi.jpg', 7, 0, 1, '2025-02-26 18:02:52', '2025-03-05 06:07:03'),
(12, 'Elaichi', ' sweet, floral fragrance', 100.00, '', 189, 'spices', '', 'uploads/elaichi.jpg', 7, 0, 1, '2025-02-26 18:04:08', '2025-03-05 05:59:45'),
(14, 'Basmati Rice', 'Long-grain aromatic', 80.00, '', 280, 'grains', '', 'uploads/jasmine.jpg', 7, 0, 1, '2025-02-26 18:06:50', '2025-03-05 06:05:45'),
(15, 'moong dal', ' lightweight and easy to digest', 60.00, '', 234, 'pulses', '', 'uploads/Moong.jpg', 7, 0, 1, '2025-02-26 18:09:10', '2025-03-04 01:14:37'),
(17, 'Rice', 'XXXXXXXXXXXX', 35.00, '', 320, 'grains', '', 'uploads/basmati.jpg', 8, 0, 1, '2025-03-03 17:36:31', '2025-03-07 06:33:27'),
(18, 'Toor Dal', 'XXXXXXXXX', 50.00, '', 0, 'pulses', '', 'uploads/toor.jpg', 8, 0, 0, '2025-03-03 17:37:01', '2025-03-07 06:33:27');

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `buyer_id`, `payment_method`, `amount`, `payment_status`, `transaction_date`, `created_at`) VALUES
(27, 29, 9, 'Cash on Delivery', 7500.00, 'Unpaid', '2025-03-07 06:33:27', '2025-03-07 06:33:27'),
(28, 30, 9, 'Cash on Delivery', 1050.00, 'Unpaid', '2025-03-07 06:33:27', '2025-03-07 06:33:27');

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
  `status` enum('Active','Inactive','Pending') NOT NULL DEFAULT 'Pending',
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `status`, `otp`, `otp_verified`, `otp_timestamp`, `reset_token`, `reset_token_expiry`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `phone`) VALUES
(2, 'Sanjay ', 'sanjayhode74@gmail.com', '$2y$10$JoMZr4xUgjyQO7D1y09je.egfk9t0/wvPuw3oOmqJJKYDq.2Zo04i', 'Farmer', 'Pending', '', 0, '2025-02-24 14:49:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'roopali', 'roopalishilimkar2@gmail.com', '$2y$10$FoOEBgVOPrIVN.pILZBWVejsAtNe0RZDH6vU9SOWEBxthwOdSQ8Qq', 'Farmer', 'Pending', '', 0, '2025-02-27 05:01:51', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'siddhi', 'amupsiddhi4@gmail.com', '$2y$10$SBiN8IdyG2pnqJtfk/aoQeZZdkK1URmPD.CikIxvQGf1Fugf3PXia', 'Farmer', 'Pending', '', 0, '2025-02-27 05:23:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'shruti', 'shrutihode19@gmail.com', '$2y$10$h6fbn0Mwm.e0Oq.abZTuq.GvSUzYFm3iy/VXANy8H2z52HrJzi9za', 'Buyer', 'Pending', '', 0, '2025-02-27 05:26:41', NULL, NULL, 'Flat No. 206,Sai Nisarg Apartment,', 'Shivane', 'Pune', 'Maharashtra', '411023', 'India', '9021237270');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `buyer_id`, `product_id`, `added_at`) VALUES
(4, 9, 18, '2025-03-07 06:31:59');

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
