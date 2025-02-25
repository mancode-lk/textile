-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 11:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_textile`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`id`, `name`) VALUES
(1, 'Women'),
(2, 'Assocories');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_credit_payment`
--

CREATE TABLE `tbl_credit_payment` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `c_id` int(11) NOT NULL,
  `c_name` varchar(255) NOT NULL,
  `c_phone` varchar(20) DEFAULT NULL,
  `c_email` varchar(255) DEFAULT NULL,
  `c_address` text DEFAULT NULL,
  `c_city` varchar(100) DEFAULT NULL,
  `credit_balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`c_id`, `c_name`, `c_phone`, `c_email`, `c_address`, `c_city`, `credit_balance`) VALUES
(1, 'SHareeq', '0778535552', '', '', '', 0.00),
(2, 'Ahamedh', '0778522552', '', '', '', 0.00),
(3, 'Hammadh', '0778556566', '', '', '', 0.00),
(4, 'aham', '07744545', '', '', '', 5400.00),
(5, 'aadil', '776994569', '', '', '', 3900.00),
(6, 'ahamed', '0778522112', '', '', '', 5400.00),
(7, 'aadil', '776994569', '', '', '', 0.00),
(8, 'aadil', '776994569', '', '', '', 0.00),
(9, '', '', '', NULL, NULL, 0.00),
(10, 'sss', '445484511', '', '', '', 0.00),
(11, 'aa', '121212', '', '', '', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_payment`
--

CREATE TABLE `tbl_customer_payment` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expenses`
--

CREATE TABLE `tbl_expenses` (
  `expense_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `expense_date` date NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_expenses`
--

INSERT INTO `tbl_expenses` (`expense_id`, `amount`, `description`, `category`, `expense_date`, `vendor_id`, `created_at`) VALUES
(2, 5000.00, 'habaya', 'vendor', '2025-02-23', 1, '2025-02-23 07:23:44'),
(3, 2000.00, 'top', 'vendor', '2025-02-23', 1, '2025-02-23 14:31:54'),
(4, 5000.00, 'Top', 'vendor', '2025-02-23', 1, '2025-02-23 15:38:53');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expiry_date`
--

CREATE TABLE `tbl_expiry_date` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `grm_ref` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_expiry_date`
--

INSERT INTO `tbl_expiry_date` (`id`, `product_id`, `quantity`, `barcode`, `grm_ref`, `user_id`, `vendor_id`, `created_at`) VALUES
(2, 2, 1, '1000', '2', 1, 0, '2025-02-22 18:03:07'),
(3, 3, 41, '1001', '3', 1, 0, '2025-02-22 19:01:27'),
(4, 2, 1, '1000', '2', 0, 0, '2025-02-22 20:06:02'),
(5, 3, 1, '1001', '3', 0, 0, '2025-02-22 20:06:02'),
(6, 2, 1, '1000', '2', 0, 0, '2025-02-22 20:14:59'),
(7, 3, 1, '1001', '3', 0, 0, '2025-02-22 20:14:59'),
(8, 3, 1, '1001', '3', 0, 0, '2025-02-22 20:36:18'),
(9, 3, 1, '1001', '3', 0, 0, '2025-02-23 07:05:39'),
(10, 2, 1, '1000', '2', 0, 0, '2025-02-23 07:14:32'),
(11, 2, 1, '1000', '2', 0, 0, '2025-02-23 07:41:09'),
(12, 3, 1, '1001', '3', 0, 0, '2025-02-23 07:41:09'),
(13, 4, 1, '1002', '4', 1, 0, '2025-02-23 07:43:25'),
(14, 4, 4, '1002', '4', 0, 0, '2025-02-23 07:44:20'),
(15, 2, 1, '1000', '2', 0, 0, '2025-02-23 07:44:20'),
(16, 3, 1, '1001', '3', 0, 0, '2025-02-23 18:04:33'),
(17, 3, 1, '1001', '3', 0, 0, '2025-02-23 18:12:30'),
(18, 3, 1, '1001', '3', 0, 0, '2025-02-23 18:50:50'),
(19, 3, 1, '1001', '3', 0, 0, '2025-02-23 18:53:26'),
(20, 3, 1, '1001', '3', 0, 0, '2025-02-23 18:53:29'),
(21, 3, 1, '1001', '3', 1, 0, '2025-02-23 18:55:09'),
(22, 2, 1, '1000', '2', 1, 0, '2025-02-23 18:55:16'),
(23, 2, 1, '1000', '2', 0, 0, '2025-02-23 18:57:09'),
(24, 3, 1, '1001', '3', 0, 1, '2025-02-23 19:07:24'),
(25, 3, 5, '1001', '3', 0, 1, '2025-02-23 19:16:04'),
(26, 3, 2, '1001', '3', 1, 0, '2025-02-23 19:24:56'),
(27, 3, 5, '1001', '3', 1, 0, '2025-02-23 19:25:31'),
(28, 3, -10, '1001', '3', 1, 0, '2025-02-23 19:28:02'),
(29, 3, -15, '1001', '3', 1, 0, '2025-02-23 19:28:10'),
(30, 2, -2, '1000', '2', 1, 0, '2025-02-23 19:28:17'),
(31, 2, 4, '1000', '2', 0, 1, '2025-02-23 19:28:32'),
(32, 3, -5, '1001', '3', 1, 0, '2025-02-23 19:29:14'),
(33, 2, -2, '1000', '2', 1, 0, '2025-02-23 19:29:20'),
(34, 3, 1, '1001', '3', 1, 0, '2025-02-23 19:31:20'),
(35, 5, 1, '1002', '5', 1, 0, '2025-02-23 19:32:03'),
(36, 5, 3, '1002', '5', 0, 1, '2025-02-23 19:32:39'),
(37, 5, 2, '1002', '5', 0, 1, '2025-02-23 19:33:35'),
(38, 5, -2, '1002', '5', 1, 0, '2025-02-23 19:33:53');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `grm_ref` varchar(100) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `discount_type` varchar(2) DEFAULT NULL,
  `bill_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `m_price` decimal(10,2) DEFAULT NULL,
  `ref_st` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`id`, `product_id`, `quantity`, `customer_id`, `grm_ref`, `discount`, `discount_type`, `bill_date`, `m_price`, `ref_st`) VALUES
(1, 2, 1, 0, '1', 0.00, 'a', '2025-02-22 18:30:00', 1800.00, NULL),
(2, 2, 1, 0, '2', 0.00, 'p', '2025-02-22 18:30:00', 1800.00, NULL),
(3, 3, 1, 0, '2', 0.00, 'p', '2025-02-22 18:30:00', 2200.00, NULL),
(4, 2, 1, 0, '3', 10.00, 'p', '2025-02-22 18:30:00', 1800.00, NULL),
(8, 2, 1, 0, '6', 10.00, 'p', '2025-02-22 18:30:00', 1800.00, NULL),
(9, 3, 1, 0, '6', 200.00, 'a', '2025-02-22 18:30:00', 2200.00, NULL),
(10, 2, 2, 0, '7', 0.00, 'p', '2025-02-23 18:30:00', 1800.00, NULL),
(11, 2, 1, 0, '8', 10.00, 'p', '2025-02-23 18:30:00', 1800.00, NULL),
(12, 3, 2, 0, '8', 200.00, 'a', '2025-02-23 18:30:00', 2200.00, NULL),
(13, 5, 1, 0, '8', 15.00, 'p', '2025-02-23 18:30:00', 3200.00, NULL),
(14, 3, 2, 1, '9', 200.00, 'a', '2025-02-23 18:30:00', 2200.00, NULL),
(16, 3, 1, 2, '11', 0.00, 'p', '2025-02-23 18:30:00', 2200.00, NULL),
(22, 3, 1, 4, '16', 0.00, 'p', '2025-02-23 18:30:00', 2200.00, NULL),
(23, 5, 1, 4, '16', 0.00, 'p', '2025-02-23 18:30:00', 3200.00, NULL),
(24, 3, 2, 5, '17', 250.00, 'a', '2025-02-23 18:30:00', 2200.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_grm`
--

CREATE TABLE `tbl_order_grm` (
  `id` int(11) NOT NULL,
  `order_ref` varchar(100) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_type` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `del_ref` varchar(100) DEFAULT NULL,
  `pay_st` int(1) DEFAULT NULL,
  `ret_st` enum('none','returned','partial') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order_grm`
--

INSERT INTO `tbl_order_grm` (`id`, `order_ref`, `order_date`, `payment_type`, `customer_id`, `del_ref`, `pay_st`, `ret_st`) VALUES
(1, '2025-02-23-1', '2025-02-22 18:30:00', 0, 0, '', 2, NULL),
(2, '2025-02-23-2', '2025-02-22 18:30:00', 1, 0, '', 2, NULL),
(3, '2025-02-23-3', '2025-02-22 18:30:00', 0, 0, '', 2, NULL),
(6, '2025-02-23-4', '2025-02-22 18:30:00', 2, 0, '', 2, NULL),
(7, '2025-02-24-1', '2025-02-23 18:30:00', 1, 0, '', 2, NULL),
(8, '2025-02-24-2', '2025-02-23 18:30:00', 2, 0, '', 2, NULL),
(9, '2025-02-24-3', '2025-02-23 18:30:00', 0, 1, '', 2, NULL),
(11, '2025-02-24-4', '2025-02-23 18:30:00', 0, 2, '', 2, NULL),
(16, '2025-02-24-9', '2025-02-23 18:30:00', 3, 4, '', 1, NULL),
(17, '2025-02-24-10', '2025-02-23 18:30:00', 3, 5, '', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `grm_ref` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `vendor_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`id`, `name`, `category_id`, `unit`, `barcode`, `quantity`, `price`, `cost_price`, `grm_ref`, `status`, `vendor_id`, `created_at`, `stock`) VALUES
(2, 'Top', 1, '0', '1000', 5, 1800.00, 1200.00, '2', 'active', 1, '2025-02-22 18:03:07', 0),
(3, 'Habaya', 1, '0', '1001', 6, 2200.00, 1500.00, '3', 'active', 1, '2025-02-22 19:01:27', 0),
(5, 'watch', 2, '0', '1002', 6, 3200.00, 2500.00, '5', 'active', 1, '2025-02-23 19:32:03', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchases`
--

CREATE TABLE `tbl_purchases` (
  `purchase_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `purchase_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_purchases`
--

INSERT INTO `tbl_purchases` (`purchase_id`, `vendor_id`, `purchase_date`, `total_amount`, `amount_paid`, `created_at`) VALUES
(7, 1, '2025-02-23', 3900.00, 0.00, '2025-02-23 01:36:02'),
(8, 1, '2025-02-23', 7200.00, 0.00, '2025-02-23 01:44:59'),
(9, 1, '2025-02-23', 4500.00, 0.00, '2025-02-23 02:06:18'),
(10, 1, '2025-02-23', 3000.00, 0.00, '2025-02-23 12:35:39'),
(11, 1, '2025-02-23', 1200.00, 0.00, '2025-02-23 12:44:32'),
(12, 1, '2025-02-23', 9300.00, 0.00, '2025-02-23 13:11:09'),
(13, 1, '2025-02-23', 22400.00, 0.00, '2025-02-23 13:14:20'),
(18, 1, '2025-02-23', 3000.00, 0.00, '2025-02-23 23:34:33'),
(19, 1, '2025-02-23', 1500.00, 0.00, '2025-02-23 23:42:30'),
(20, 1, '2025-02-24', 12000.00, 0.00, '2025-02-24 00:27:09'),
(21, 1, '2025-02-24', 4500.00, 0.00, '2025-02-24 00:37:24'),
(22, 1, '2025-02-24', 7500.00, 0.00, '2025-02-24 00:46:04'),
(23, 1, '2025-02-24', 4800.00, 0.00, '2025-02-24 00:58:32'),
(24, 1, '2025-02-24', 7500.00, 0.00, '2025-02-24 01:02:39'),
(25, 1, '2025-02-24', 5000.00, 0.00, '2025-02-24 01:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_items`
--

CREATE TABLE `tbl_purchase_items` (
  `item_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `grm_ref` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_purchase_items`
--

INSERT INTO `tbl_purchase_items` (`item_id`, `purchase_id`, `product_id`, `quantity`, `unit_price`, `grm_ref`) VALUES
(1, 7, 2, 2, 1200.00, 2),
(2, 7, 3, 1, 1500.00, 3),
(3, 8, 2, 1, 1200.00, 2),
(4, 8, 3, 4, 1500.00, 3),
(5, 9, 3, 3, 1500.00, 3),
(6, 10, 3, 2, 1500.00, 3),
(7, 11, 2, 1, 1200.00, 2),
(8, 12, 2, 4, 1200.00, 2),
(9, 12, 3, 3, 1500.00, 3),
(11, 13, 2, 2, 1200.00, 2),
(12, 18, 3, 2, 1500.00, 3),
(13, 19, 3, 1, 1500.00, 3),
(14, 20, 2, 10, 1200.00, 2),
(15, 21, 3, 3, 1500.00, 3),
(16, 22, 3, 5, 1500.00, 3),
(17, 23, 2, 4, 1200.00, 2),
(18, 24, 5, 3, 2500.00, 5),
(19, 25, 5, 2, 2500.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_payments`
--

CREATE TABLE `tbl_purchase_payments` (
  `payment_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quantity`
--

CREATE TABLE `tbl_quantity` (
  `id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_quantity`
--

INSERT INTO `tbl_quantity` (`id`, `p_id`, `quantity`) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock`
--

CREATE TABLE `tbl_stock` (
  `id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_stock`
--

INSERT INTO `tbl_stock` (`id`, `p_id`, `emp_id`, `stock`, `created_at`) VALUES
(1, 1, 0, 2, '2025-02-22 17:18:41'),
(2, 2, 0, 1, '2025-02-22 18:03:07'),
(3, 3, 0, 1, '2025-02-22 19:01:27'),
(4, 4, 0, 1, '2025-02-23 07:43:25'),
(5, 5, 0, 1, '2025-02-23 19:32:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_grm`
--

CREATE TABLE `tbl_stock_grm` (
  `id` int(11) NOT NULL,
  `stock_ref` varchar(100) NOT NULL,
  `stock_hs_price` decimal(10,2) NOT NULL,
  `stock_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_stock_grm`
--

INSERT INTO `tbl_stock_grm` (`id`, `stock_ref`, `stock_hs_price`, `stock_date`, `user_id`, `created_at`) VALUES
(1, 'AAAA', 1100.00, '0000-00-00', 1, '2025-02-22 17:18:41'),
(2, 'AABB', 1200.00, '0000-00-00', 1, '2025-02-22 18:03:07'),
(3, 'BBCA', 1500.00, '0000-00-00', 1, '2025-02-22 19:01:27'),
(4, 'SSAW', 5000.00, '0000-00-00', 1, '2025-02-23 07:43:25'),
(5, 'ACCA', 2500.00, '0000-00-00', 1, '2025-02-23 19:32:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unit`
--

CREATE TABLE `tbl_unit` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `password`) VALUES
(1, 'admin', '123');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vendors`
--

CREATE TABLE `tbl_vendors` (
  `vendor_id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_vendors`
--

INSERT INTO `tbl_vendors` (`vendor_id`, `vendor_name`, `phone`, `address`, `created_at`) VALUES
(1, 'Shareeq', '94778535552', 'Gampola', '2025-02-22 17:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vendor_discounts`
--

CREATE TABLE `tbl_vendor_discounts` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `discount_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_vendor_discounts`
--

INSERT INTO `tbl_vendor_discounts` (`id`, `vendor_id`, `discount_amount`, `discount_date`) VALUES
(1, 1, 1500.00, '2025-02-23 12:47:49'),
(2, 1, 1000.00, '2025-02-23 14:33:46'),
(3, 1, 2000.00, '2025-02-23 15:37:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vendor_payments`
--

CREATE TABLE `tbl_vendor_payments` (
  `payment_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `expense_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_vendor_payments`
--

INSERT INTO `tbl_vendor_payments` (`payment_id`, `vendor_id`, `expense_id`, `amount`, `payment_date`, `payment_method`, `reference_number`, `remarks`) VALUES
(1, 1, 3, 2000.00, '2025-02-23', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_credit_payment`
--
ALTER TABLE `tbl_credit_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `tbl_customer_payment`
--
ALTER TABLE `tbl_customer_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `tbl_expiry_date`
--
ALTER TABLE `tbl_expiry_date`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_grm`
--
ALTER TABLE `tbl_order_grm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`);

--
-- Indexes for table `tbl_purchases`
--
ALTER TABLE `tbl_purchases`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `tbl_purchase_items`
--
ALTER TABLE `tbl_purchase_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `purchase_id` (`purchase_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `grm_ref` (`grm_ref`);

--
-- Indexes for table `tbl_purchase_payments`
--
ALTER TABLE `tbl_purchase_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `purchase_id` (`purchase_id`);

--
-- Indexes for table `tbl_quantity`
--
ALTER TABLE `tbl_quantity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_stock_grm`
--
ALTER TABLE `tbl_stock_grm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_unit`
--
ALTER TABLE `tbl_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tbl_vendors`
--
ALTER TABLE `tbl_vendors`
  ADD PRIMARY KEY (`vendor_id`);

--
-- Indexes for table `tbl_vendor_discounts`
--
ALTER TABLE `tbl_vendor_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `tbl_vendor_payments`
--
ALTER TABLE `tbl_vendor_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `expense_id` (`expense_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_credit_payment`
--
ALTER TABLE `tbl_credit_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_customer_payment`
--
ALTER TABLE `tbl_customer_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_expiry_date`
--
ALTER TABLE `tbl_expiry_date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_order_grm`
--
ALTER TABLE `tbl_order_grm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_purchases`
--
ALTER TABLE `tbl_purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_purchase_items`
--
ALTER TABLE `tbl_purchase_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_purchase_payments`
--
ALTER TABLE `tbl_purchase_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_quantity`
--
ALTER TABLE `tbl_quantity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_stock_grm`
--
ALTER TABLE `tbl_stock_grm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_unit`
--
ALTER TABLE `tbl_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_vendors`
--
ALTER TABLE `tbl_vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_vendor_discounts`
--
ALTER TABLE `tbl_vendor_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_vendor_payments`
--
ALTER TABLE `tbl_vendor_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_credit_payment`
--
ALTER TABLE `tbl_credit_payment`
  ADD CONSTRAINT `tbl_credit_payment_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customer` (`c_id`);

--
-- Constraints for table `tbl_customer_payment`
--
ALTER TABLE `tbl_customer_payment`
  ADD CONSTRAINT `tbl_customer_payment_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customer` (`c_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_purchases`
--
ALTER TABLE `tbl_purchases`
  ADD CONSTRAINT `tbl_purchases_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `tbl_vendors` (`vendor_id`);

--
-- Constraints for table `tbl_purchase_items`
--
ALTER TABLE `tbl_purchase_items`
  ADD CONSTRAINT `tbl_purchase_items_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `tbl_purchases` (`purchase_id`),
  ADD CONSTRAINT `tbl_purchase_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`id`),
  ADD CONSTRAINT `tbl_purchase_items_ibfk_3` FOREIGN KEY (`grm_ref`) REFERENCES `tbl_stock_grm` (`id`);

--
-- Constraints for table `tbl_purchase_payments`
--
ALTER TABLE `tbl_purchase_payments`
  ADD CONSTRAINT `tbl_purchase_payments_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `tbl_purchases` (`purchase_id`);

--
-- Constraints for table `tbl_vendor_discounts`
--
ALTER TABLE `tbl_vendor_discounts`
  ADD CONSTRAINT `tbl_vendor_discounts_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `tbl_vendors` (`vendor_id`);

--
-- Constraints for table `tbl_vendor_payments`
--
ALTER TABLE `tbl_vendor_payments`
  ADD CONSTRAINT `tbl_vendor_payments_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `tbl_vendors` (`vendor_id`),
  ADD CONSTRAINT `tbl_vendor_payments_ibfk_2` FOREIGN KEY (`expense_id`) REFERENCES `tbl_expenses` (`expense_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
