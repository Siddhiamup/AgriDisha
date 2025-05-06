-- phpMyAdmin SQL Dump
-- Version: 5.2.1
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 04, 2025 at 05:51 AM
-- Server Version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Ensure character set compatibility
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Database: ams_db
-- --------------------------------------------------------

-- Create users table first
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('Farmer','Buyer','Admin') NOT NULL,
    status ENUM('Active', 'Inactive', 'Pending') NOT NULL DEFAULT 'Pending',
    otp VARCHAR(6) NOT NULL,
    otp_verified TINYINT(1) DEFAULT '0',
    otp_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reset_token VARCHAR(300) DEFAULT NULL,
    reset_token_expiry DATETIME DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Then create products table
DROP TABLE IF EXISTS products;
CREATE TABLE products (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    category VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    seller_id INT NOT NULL,
    organic_certified TINYINT(1) DEFAULT '0',
    in_stock TINYINT(1) DEFAULT '1',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create orders table
DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
    id INT NOT NULL AUTO_INCREMENT,
    buyer_id INT NOT NULL,
    seller_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    order_date DATE NOT NULL,
    status ENUM('Pending','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
    PRIMARY KEY (id),
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create inventory table
DROP TABLE IF EXISTS inventory;
CREATE TABLE inventory (
    id INT NOT NULL AUTO_INCREMENT,
    product_id INT NOT NULL,
    current_stock INT NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create wishlist table
DROP TABLE IF EXISTS wishlist;
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    product_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create cart table
DROP TABLE IF EXISTS cart;
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create transactions table
DROP TABLE IF EXISTS transactions;
CREATE TABLE transactions (
    id INT NOT NULL AUTO_INCREMENT,
    order_id INT NOT NULL,
    buyer_id INT NOT NULL,
    payment_method ENUM('Credit Card', 'Debit Card', 'UPI', 'Net Banking', 'Cash on Delivery') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('Paid', 'Unpaid', 'Refunded') NOT NULL DEFAULT 'Unpaid',
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create settings table
DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    notification_preference ENUM('all', 'important', 'none') DEFAULT 'all',
    language VARCHAR(10) DEFAULT 'en',
    currency VARCHAR(10) DEFAULT 'INR',
    privacy ENUM('public', 'friends', 'private') DEFAULT 'public',
    account_security ENUM('basic', '2FA', 'high') DEFAULT 'basic',
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create notifications table
DROP TABLE IF EXISTS notifications;
CREATE TABLE notifications (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create contact_us table
DROP TABLE IF EXISTS contact_us;
CREATE TABLE contact_us (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    comment VARCHAR(500) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create admin_info table
DROP TABLE IF EXISTS admin_info;
CREATE TABLE admin_info (
    admin_id INT NOT NULL AUTO_INCREMENT,
    admin_email VARCHAR(100) NOT NULL UNIQUE,
    admin_password VARCHAR(255) NOT NULL,
    admin_name VARCHAR(100) NOT NULL,
    PRIMARY KEY (admin_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

COMMIT;
