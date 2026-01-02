

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_headquarters` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `company_id`, `name`, `location`, `phone`, `is_headquarters`, `created_at`) VALUES
(8, 4, 'Maxim Lusaka', 'Novera', '+260 77756578', 0, '2025-12-15 20:33:08'),
(9, 5, 'Milo Lusaka', 'Novera', '+260 77756578', 0, '2025-12-15 20:33:28'),
(10, 6, 'Mukurich', 'Novera', '+260 77756578', 0, '2025-12-15 20:33:48'),
(11, 4, 'Maxim Kitwe', 'Kitwe', '+260 77756578', 0, '2025-12-15 20:35:28');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `description`, `email`, `phone`, `address`, `logo_url`, `created_at`) VALUES
(4, 'Maxim maintenance solutions', NULL, 'maxim@gmail.com', '+260 77756578', 'Behind Novera Mall', NULL, '2025-12-15 20:28:34'),
(5, 'Milotech Solutions LTD', NULL, 'Milotech@gmail.com', '+260 77756578', 'Behind Novera Mall', NULL, '2025-12-15 20:31:01'),
(6, 'Mukurich Solutions LTD', NULL, 'Mukurich@gmail.com', '+260 77756578', 'Behind Novera Mall', NULL, '2025-12-15 20:32:08');

-- --------------------------------------------------------

--
-- Table structure for table `currency_rates`
--

CREATE TABLE `currency_rates` (
  `id` int(11) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_name` varchar(100) DEFAULT NULL,
  `rate_to_zmw` decimal(15,6) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currency_rates`
--

INSERT INTO `currency_rates` (`id`, `currency_code`, `currency_name`, `rate_to_zmw`, `last_updated`) VALUES
(1, 'ZMW', 'Zambian Kwacha', 1.000000, '2025-12-15 20:12:08'),
(2, 'USD', 'US Dollar', 23.040000, '2025-12-15 20:12:07'),
(3, 'EUR', 'Euro', 27.042254, '2025-12-15 20:12:07'),
(4, 'GBP', 'British Pound', 30.802139, '2025-12-15 20:12:07'),
(5, 'ZAR', 'South African Rand', 1.365738, '2025-12-15 20:12:08'),
(6, 'CNY', 'Chinese Yuan', 3.263456, '2025-12-15 20:12:07'),
(8, 'AED', NULL, 6.277929, '2025-12-15 20:12:07'),
(9, 'AFN', NULL, 0.348563, '2025-12-15 20:12:07'),
(10, 'ALL', NULL, 0.279951, '2025-12-15 20:12:07'),
(11, 'AMD', NULL, 0.060346, '2025-12-15 20:12:07'),
(12, 'ANG', NULL, 12.871508, '2025-12-15 20:12:07'),
(13, 'AOA', NULL, 0.025047, '2025-12-15 20:12:07'),
(14, 'ARS', NULL, 0.016018, '2025-12-15 20:12:07'),
(15, 'AUD', NULL, 15.360000, '2025-12-15 20:12:07'),
(16, 'AWG', NULL, 12.871508, '2025-12-15 20:12:07'),
(17, 'AZN', NULL, 13.552941, '2025-12-15 20:12:07'),
(18, 'BAM', NULL, 13.796407, '2025-12-15 20:12:07'),
(19, 'BBD', NULL, 11.520000, '2025-12-15 20:12:07'),
(20, 'BDT', NULL, 0.188543, '2025-12-15 20:12:07'),
(21, 'BGN', NULL, 13.796407, '2025-12-15 20:12:07'),
(22, 'BHD', NULL, 61.276596, '2025-12-15 20:12:07'),
(23, 'BIF', NULL, 0.007769, '2025-12-15 20:12:07'),
(24, 'BMD', NULL, 23.040000, '2025-12-15 20:12:07'),
(25, 'BND', NULL, 17.860465, '2025-12-15 20:12:07'),
(26, 'BOB', NULL, 3.334298, '2025-12-15 20:12:07'),
(27, 'BRL', NULL, 4.258780, '2025-12-15 20:12:07'),
(28, 'BSD', NULL, 23.040000, '2025-12-15 20:12:07'),
(29, 'BTN', NULL, 0.254136, '2025-12-15 20:12:07'),
(30, 'BWP', NULL, 1.687912, '2025-12-15 20:12:07'),
(31, 'BYN', NULL, 7.863481, '2025-12-15 20:12:07'),
(32, 'BZD', NULL, 11.520000, '2025-12-15 20:12:07'),
(33, 'CAD', NULL, 16.695652, '2025-12-15 20:12:07'),
(34, 'CDF', NULL, 0.010093, '2025-12-15 20:12:07'),
(35, 'CHF', NULL, 28.944724, '2025-12-15 20:12:07'),
(36, 'CLF', NULL, 997.402597, '2025-12-15 20:12:07'),
(37, 'CLP', NULL, 0.025249, '2025-12-15 20:12:07'),
(38, 'CNH', NULL, 3.268085, '2025-12-15 20:12:07'),
(40, 'COP', NULL, 0.006046, '2025-12-15 20:12:07'),
(41, 'CRC', NULL, 0.046158, '2025-12-15 20:12:07'),
(42, 'CUP', NULL, 0.960000, '2025-12-15 20:12:07'),
(43, 'CVE', NULL, 0.245263, '2025-12-15 20:12:07'),
(44, 'CZK', NULL, 1.114659, '2025-12-15 20:12:07'),
(45, 'DJF', NULL, 0.129642, '2025-12-15 20:12:07'),
(46, 'DKK', NULL, 3.622642, '2025-12-15 20:12:07'),
(47, 'DOP', NULL, 0.360959, '2025-12-15 20:12:07'),
(48, 'DZD', NULL, 0.177559, '2025-12-15 20:12:07'),
(49, 'EGP', NULL, 0.484848, '2025-12-15 20:12:07'),
(50, 'ERN', NULL, 1.536000, '2025-12-15 20:12:07'),
(51, 'ETB', NULL, 0.148751, '2025-12-15 20:12:07'),
(53, 'FJD', NULL, 10.149780, '2025-12-15 20:12:07'),
(54, 'FKP', NULL, 30.802139, '2025-12-15 20:12:07'),
(55, 'FOK', NULL, 3.622642, '2025-12-15 20:12:07'),
(57, 'GEL', NULL, 8.533333, '2025-12-15 20:12:07'),
(58, 'GGP', NULL, 30.802139, '2025-12-15 20:12:07'),
(59, 'GHS', NULL, 1.998265, '2025-12-15 20:12:07'),
(60, 'GIP', NULL, 30.802139, '2025-12-15 20:12:07'),
(61, 'GMD', NULL, 0.310512, '2025-12-15 20:12:07'),
(62, 'GNF', NULL, 0.002642, '2025-12-15 20:12:07'),
(63, 'GTQ', NULL, 3.007833, '2025-12-15 20:12:07'),
(64, 'GYD', NULL, 0.110113, '2025-12-15 20:12:07'),
(65, 'HKD', NULL, 2.961440, '2025-12-15 20:12:07'),
(66, 'HNL', NULL, 0.876046, '2025-12-15 20:12:07'),
(67, 'HRK', NULL, 3.588785, '2025-12-15 20:12:07'),
(68, 'HTG', NULL, 0.176120, '2025-12-15 20:12:07'),
(69, 'HUF', NULL, 0.070306, '2025-12-15 20:12:07'),
(70, 'IDR', NULL, 0.001384, '2025-12-15 20:12:07'),
(71, 'ILS', NULL, 7.177570, '2025-12-15 20:12:07'),
(72, 'IMP', NULL, 30.802139, '2025-12-15 20:12:07'),
(73, 'INR', NULL, 0.254136, '2025-12-15 20:12:07'),
(74, 'IQD', NULL, 0.017574, '2025-12-15 20:12:07'),
(75, 'IRR', NULL, 0.000534, '2025-12-15 20:12:07'),
(76, 'ISK', NULL, 0.182336, '2025-12-15 20:12:07'),
(77, 'JEP', NULL, 30.802139, '2025-12-15 20:12:07'),
(78, 'JMD', NULL, 0.144018, '2025-12-15 20:12:07'),
(79, 'JOD', NULL, 32.496474, '2025-12-15 20:12:07'),
(80, 'JPY', NULL, 0.147872, '2025-12-15 20:12:07'),
(81, 'KES', NULL, 0.178799, '2025-12-15 20:12:08'),
(82, 'KGS', NULL, 0.263525, '2025-12-15 20:12:08'),
(83, 'KHR', NULL, 0.005733, '2025-12-15 20:12:08'),
(84, 'KID', NULL, 15.360000, '2025-12-15 20:12:08'),
(85, 'KMF', NULL, 0.054972, '2025-12-15 20:12:08'),
(86, 'KRW', NULL, 0.015608, '2025-12-15 20:12:08'),
(87, 'KWD', NULL, 75.294118, '2025-12-15 20:12:08'),
(88, 'KYD', NULL, 27.659064, '2025-12-15 20:12:08'),
(89, 'KZT', NULL, 0.044183, '2025-12-15 20:12:08'),
(90, 'LAK', NULL, 0.001061, '2025-12-15 20:12:08'),
(91, 'LBP', NULL, 0.000257, '2025-12-15 20:12:08'),
(92, 'LKR', NULL, 0.074582, '2025-12-15 20:12:08'),
(93, 'LRD', NULL, 0.130583, '2025-12-15 20:12:08'),
(94, 'LSL', NULL, 1.365738, '2025-12-15 20:12:08'),
(95, 'LYD', NULL, 4.243094, '2025-12-15 20:12:08'),
(96, 'MAD', NULL, 2.507073, '2025-12-15 20:12:08'),
(97, 'MDL', NULL, 1.361702, '2025-12-15 20:12:08'),
(98, 'MGA', NULL, 0.005132, '2025-12-15 20:12:08'),
(99, 'MKD', NULL, 0.438607, '2025-12-15 20:12:08'),
(100, 'MMK', NULL, 0.010958, '2025-12-15 20:12:08'),
(101, 'MNT', NULL, 0.006537, '2025-12-15 20:12:08'),
(102, 'MOP', NULL, 2.872818, '2025-12-15 20:12:08'),
(103, 'MRU', NULL, 0.577733, '2025-12-15 20:12:08'),
(104, 'MUR', NULL, 0.501633, '2025-12-15 20:12:08'),
(105, 'MVR', NULL, 1.492228, '2025-12-15 20:12:08'),
(106, 'MWK', NULL, 0.013258, '2025-12-15 20:12:08'),
(107, 'MXN', NULL, 1.278579, '2025-12-15 20:12:08'),
(108, 'MYR', NULL, 5.619512, '2025-12-15 20:12:08'),
(109, 'MZN', NULL, 0.362150, '2025-12-15 20:12:08'),
(110, 'NAD', NULL, 1.365738, '2025-12-15 20:12:08'),
(111, 'NGN', NULL, 0.015893, '2025-12-15 20:12:08'),
(112, 'NIO', NULL, 0.626427, '2025-12-15 20:12:08'),
(113, 'NOK', NULL, 2.276680, '2025-12-15 20:12:08'),
(114, 'NPR', NULL, 0.158831, '2025-12-15 20:12:08'),
(115, 'NZD', NULL, 13.395349, '2025-12-15 20:12:08'),
(116, 'OMR', NULL, 60.000000, '2025-12-15 20:12:08'),
(117, 'PAB', NULL, 23.040000, '2025-12-15 20:12:08'),
(118, 'PEN', NULL, 6.836795, '2025-12-15 20:12:08'),
(119, 'PGK', NULL, 5.395785, '2025-12-15 20:12:08'),
(120, 'PHP', NULL, 0.389716, '2025-12-15 20:12:08'),
(121, 'PKR', NULL, 0.082154, '2025-12-15 20:12:08'),
(122, 'PLN', NULL, 6.400000, '2025-12-15 20:12:08'),
(123, 'PYG', NULL, 0.003376, '2025-12-15 20:12:08'),
(124, 'QAR', NULL, 6.329670, '2025-12-15 20:12:08'),
(125, 'RON', NULL, 5.308756, '2025-12-15 20:12:08'),
(126, 'RSD', NULL, 0.230331, '2025-12-15 20:12:08'),
(127, 'RUB', NULL, 0.288686, '2025-12-15 20:12:08'),
(128, 'RWF', NULL, 0.015822, '2025-12-15 20:12:08'),
(129, 'SAR', NULL, 6.144000, '2025-12-15 20:12:08'),
(130, 'SBD', NULL, 2.833948, '2025-12-15 20:12:08'),
(131, 'SCR', NULL, 1.619115, '2025-12-15 20:12:08'),
(132, 'SDG', NULL, 0.050195, '2025-12-15 20:12:08'),
(133, 'SEK', NULL, 2.485437, '2025-12-15 20:12:08'),
(134, 'SGD', NULL, 17.860465, '2025-12-15 20:12:08'),
(135, 'SHP', NULL, 30.802139, '2025-12-15 20:12:08'),
(136, 'SLE', NULL, 0.956017, '2025-12-15 20:12:08'),
(137, 'SLL', NULL, 0.000956, '2025-12-15 20:12:08'),
(138, 'SOS', NULL, 0.040352, '2025-12-15 20:12:08'),
(139, 'SRD', NULL, 0.599532, '2025-12-15 20:12:08'),
(140, 'SSP', NULL, 0.004931, '2025-12-15 20:12:08'),
(141, 'STN', NULL, 1.103977, '2025-12-15 20:12:08'),
(142, 'SYP', NULL, 0.002093, '2025-12-15 20:12:08'),
(143, 'SZL', NULL, 1.365738, '2025-12-15 20:12:08'),
(144, 'THB', NULL, 0.729807, '2025-12-15 20:12:08'),
(145, 'TJS', NULL, 2.493506, '2025-12-15 20:12:08'),
(146, 'TMT', NULL, 6.582857, '2025-12-15 20:12:08'),
(147, 'TND', NULL, 7.890411, '2025-12-15 20:12:08'),
(148, 'TOP', NULL, 9.600000, '2025-12-15 20:12:08'),
(149, 'TRY', NULL, 0.539452, '2025-12-15 20:12:08'),
(150, 'TTD', NULL, 3.398230, '2025-12-15 20:12:08'),
(151, 'TVD', NULL, 15.360000, '2025-12-15 20:12:08'),
(152, 'TWD', NULL, 0.735397, '2025-12-15 20:12:08'),
(153, 'TZS', NULL, 0.009390, '2025-12-15 20:12:08'),
(154, 'UAH', NULL, 0.545972, '2025-12-15 20:12:08'),
(155, 'UGX', NULL, 0.006491, '2025-12-15 20:12:08'),
(156, 'UYU', NULL, 0.586857, '2025-12-15 20:12:08'),
(157, 'UZS', NULL, 0.001892, '2025-12-15 20:12:08'),
(158, 'VES', NULL, 0.085084, '2025-12-15 20:12:08'),
(159, 'VND', NULL, 0.000879, '2025-12-15 20:12:08'),
(160, 'VUV', NULL, 0.190162, '2025-12-15 20:12:08'),
(161, 'WST', NULL, 8.378182, '2025-12-15 20:12:08'),
(162, 'XAF', NULL, 0.041229, '2025-12-15 20:12:08'),
(163, 'XCD', NULL, 8.533333, '2025-12-15 20:12:08'),
(164, 'XCG', NULL, 12.871508, '2025-12-15 20:12:08'),
(165, 'XDR', NULL, 31.691884, '2025-12-15 20:12:08'),
(166, 'XOF', NULL, 0.041229, '2025-12-15 20:12:08'),
(167, 'XPF', NULL, 0.226638, '2025-12-15 20:12:08'),
(168, 'YER', NULL, 0.096636, '2025-12-15 20:12:08'),
(171, 'ZWG', NULL, 0.882759, '2025-12-15 20:12:08'),
(172, 'ZWL', NULL, 0.882759, '2025-12-15 20:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `branch_id`, `quantity`, `last_updated`) VALUES
(90, 33, 8, 0, '2025-12-15 21:34:00'),
(91, 33, 11, 0, '2025-12-15 21:34:00'),
(92, 33, 9, 15, '2025-12-17 04:25:46'),
(93, 33, 10, 0, '2025-12-15 21:34:00'),
(94, 34, 10, 300, '2025-12-15 23:50:34'),
(95, 35, 10, 299, '2025-12-15 23:55:21');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `link`, `created_at`, `read_at`) VALUES
(36, 13, 'Low Stock Alert', '1 product(s) are running low on stock. Please reorder soon.', 'warning', 0, '/inventory/stock', '2025-12-15 21:36:37', NULL),
(38, 13, 'Low Stock Alert', '1 product(s) are running low on stock. Please reorder soon.', 'warning', 0, '/inventory/stock', '2025-12-15 23:01:06', NULL),
(39, 20, 'New Sales Order', 'Sales order #SO-20251216-7537 has been created successfully.', 'success', 0, '/sales', '2025-12-15 23:52:35', NULL),
(41, 20, 'New Purchase Order', 'Purchase order #PO-20251216-9818 has been created successfully.', 'success', 0, '/purchase', '2025-12-15 23:55:10', NULL),
(42, 20, 'Purchase Order Received', 'Purchase order #PO-20251216-9818 has been received and inventory updated.', 'success', 0, '/purchases/viewOrder/6', '2025-12-15 23:55:21', NULL),
(52, 15, 'New Purchase Order', 'Purchase order #PO-20251217-6790 has been created successfully.', 'success', 1, '/purchase', '2025-12-17 04:17:10', '2025-12-17 04:32:44'),
(53, 15, 'Purchase Order Received', 'Purchase order #PO-20251217-6790 has been received and inventory updated.', 'success', 1, '/purchases/viewOrder/8', '2025-12-17 04:17:14', '2025-12-17 04:32:37'),
(54, 15, 'New Purchase Order', 'Purchase order #PO-20251217-2343 has been created successfully.', 'success', 1, '/purchase', '2025-12-17 04:25:38', '2025-12-17 04:32:53');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` enum('stock','service','asset') NOT NULL DEFAULT 'stock',
  `unit` varchar(20) DEFAULT 'pcs',
  `price` decimal(10,2) DEFAULT 0.00,
  `reorder_level` int(11) DEFAULT 10,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Product status: 1=Active, 0=Inactive',
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `company_id`, `category_id`, `name`, `sku`, `barcode`, `description`, `type`, `unit`, `price`, `reorder_level`, `is_active`, `image_url`, `created_at`) VALUES
(33, 5, NULL, 'Printers', 'MILO-PRI-001', NULL, '', 'asset', 'pcs', 4000.00, 10, 1, NULL, '2025-12-15 21:34:00'),
(34, 6, NULL, 'T-SHIRTS', 'MUK-TSH-001', NULL, '', 'asset', 'pcs', 0.00, 25, 0, NULL, '2025-12-15 23:50:34'),
(35, 6, NULL, 'T-SHIRTS', 'MUK-TSH-002', NULL, '', 'asset', 'pcs', 100.00, 25, 1, NULL, '2025-12-15 23:51:29');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `purchase_number` varchar(50) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `purchase_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','received','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_phone` varchar(50) DEFAULT NULL,
  `supplier_email` varchar(100) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','received','cancelled') DEFAULT 'pending',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `received_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `order_number`, `branch_id`, `supplier_name`, `supplier_phone`, `supplier_email`, `total_amount`, `status`, `created_by`, `created_at`, `received_at`, `updated_at`) VALUES
(6, 'PO-20251216-9818', 10, 'her', '0773465434', 'm@gmail.com', 400.00, 'received', 20, '2025-12-15 23:55:10', '2025-12-15 23:55:21', '2025-12-15 23:55:21'),
(7, 'PO-20251216-9099', 9, 'hericon', '+260 77756578', 'maxim@gmail.com', 28000.00, 'received', 15, '2025-12-16 19:50:35', '2025-12-16 19:50:52', '2025-12-16 19:50:52'),
(8, 'PO-20251217-6790', 9, 'hericon', '+260 77756578', 'maxim@gmail.com', 16000.00, 'received', 15, '2025-12-17 04:17:10', '2025-12-17 04:17:14', '2025-12-17 04:17:14'),
(9, 'PO-20251217-2343', 9, 'hericon', '+260 77756578', 'maxim@gmail.com', 20000.00, 'received', 15, '2025-12-17 04:25:38', '2025-12-17 04:25:46', '2025-12-17 04:25:46');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(11) NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(3, 6, 35, 4, 100.00, 400.00),
(4, 7, 33, 7, 4000.00, 28000.00),
(5, 8, 33, 4, 4000.00, 16000.00),
(6, 9, 33, 5, 4000.00, 20000.00);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `sale_number` varchar(50) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','card','mobile_money','bank_transfer') NOT NULL DEFAULT 'cash',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_method` varchar(50) DEFAULT 'cash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_orders`
--

INSERT INTO `sales_orders` (`id`, `order_number`, `branch_id`, `customer_name`, `customer_phone`, `customer_email`, `total_amount`, `status`, `created_by`, `created_at`, `updated_at`, `payment_method`) VALUES
(11, 'SO-20251216-7537', 10, 'MATHEWS', '0773465434', 'm@gmail.com', 500.00, 'completed', 20, '2025-12-15 23:52:35', '2025-12-15 23:52:35', 'mobile_money'),
(12, 'SO-20251216-9877', 9, 'heric', '077771064', 'maxim@gmail.com', 24000.00, 'completed', 15, '2025-12-16 19:52:43', '2025-12-16 19:52:43', 'cash');

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_items`
--

CREATE TABLE `sales_order_items` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_order_items`
--

INSERT INTO `sales_order_items` (`id`, `sales_order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(7, 11, 35, 5, 100.00, 500.00),
(8, 12, 33, 6, 4000.00, 24000.00);

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'company_name', 'IVM System', '2025-12-04 01:00:02'),
(2, 'currency', 'ZMW', '2025-12-04 01:00:02'),
(3, 'tax_rate', '16', '2025-12-04 01:00:02'),
(4, 'receipt_footer', 'Thank you for your business!', '2025-12-04 01:00:02'),
(5, 'low_stock_alert', '1', '2025-12-04 01:00:02'),
(6, 'allow_registration', '1', '2025-12-04 08:11:38'),
(7, 'system_name', 'IVM System', '2025-12-04 08:11:24'),
(8, 'currency_symbol', '$', '2025-12-16 21:27:05'),
(10, 'email_notifications', '1', '2025-12-04 08:11:43'),
(11, 'low_stock_alerts', '1', '2025-12-04 08:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` int(11) NOT NULL,
  `transfer_number` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `from_branch_id` int(11) NOT NULL,
  `to_branch_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `transfer_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('purchase','sale','transfer_in','transfer_out','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `product_id`, `branch_id`, `user_id`, `type`, `quantity`, `reference`, `notes`, `created_at`) VALUES
(15, 33, 9, 15, 'adjustment', 5, 'Opening Stock', NULL, '2025-12-15 21:34:00'),
(16, 34, 10, 20, 'adjustment', 300, 'Opening Stock', NULL, '2025-12-15 23:50:34'),
(17, 35, 10, 20, 'adjustment', 300, 'Opening Stock', NULL, '2025-12-15 23:51:29'),
(18, 35, 10, 20, 'sale', -5, 'SO-20251216-7537', NULL, '2025-12-15 23:52:35'),
(19, 35, 10, 20, 'purchase', 4, 'PO-20251216-9818', NULL, '2025-12-15 23:55:21'),
(20, 33, 9, 15, 'purchase', 7, 'PO-20251216-9099', NULL, '2025-12-16 19:50:52'),
(21, 33, 9, 15, 'sale', -6, 'SO-20251216-9877', NULL, '2025-12-16 19:52:43'),
(22, 33, 9, 15, 'purchase', 4, 'PO-20251217-6790', NULL, '2025-12-17 04:17:14'),
(23, 33, 9, 15, 'purchase', 5, 'PO-20251217-2343', NULL, '2025-12-17 04:25:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('super_admin','admin','manager','staff') NOT NULL DEFAULT 'staff',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Active, 0 = Inactive/Deleted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branch_id`, `username`, `email`, `phone`, `password`, `full_name`, `profile_picture`, `role`, `is_active`, `created_at`, `last_login`) VALUES
(1, NULL, 'admin', NULL, NULL, '$2y$10$q/HSG0HkNVrvYjy1Zmc8e.mv0hhTqWuVupmBZwLY89OBtuQ8zcSEK', 'System Administrator', NULL, 'super_admin', 0, '2025-12-04 01:00:31', NULL),
(11, NULL, 'MUKURICH', NULL, NULL, '$2y$10$ZB0vHxUcoNyedEOc2AuL8Os3CGZ3ypJUj4xVag34qTzq4ZwBqggPa', 'MADAM PAMELA', 'uploads/profiles/user_11_1765369831.jpg', 'super_admin', 0, '2025-12-10 12:29:33', '2025-12-11 07:22:59'),
(13, NULL, 'MAXIM', NULL, NULL, '$2y$10$7xPKwxQMvhkEmfvkcfyPl./o6pib0eSaVsjmgYGzpSKKAhrjDfWyK', 'MR BANDA', NULL, 'super_admin', 1, '2025-12-10 13:43:32', '2025-12-15 20:37:09'),
(14, 8, 'Accountant', NULL, NULL, '$2y$10$V7w79D0n5H3OqdFTie2e4.Cd7cILhZ7Ihig/nhHRIPGuyqLE4xaBW', 'Madam Kunda', NULL, 'admin', 1, '2025-12-15 20:43:22', NULL),
(15, 9, 'Team Lead', NULL, NULL, '$2y$10$EJlgpQoJjgWDjmwXyg6b7uQQtfs4ezstWCDg2bjGCsD5iwaXjyPF6', 'Mr Banda', 'uploads/profiles/user_15_1765945800.png', 'super_admin', 1, '2025-12-15 20:47:35', '2025-12-17 05:14:55'),
(16, 10, 'Manager', NULL, NULL, '$2y$10$N03YfCQnLB9RLk.iyZY66eT3Lj5/1crMrxsd2RU918rBt4J0uJB1u', 'Pamela Sakala', NULL, 'admin', 1, '2025-12-15 20:49:52', NULL),
(20, 10, 'Manager1', NULL, NULL, '$2y$10$2ZNo49qHjtbiRkjqlDEULOaKGNUeOx7K1PIGLe1G7cdZ3vwvvsD/K', 'Pamela Sakala', NULL, 'admin', 0, '2025-12-15 21:24:06', '2025-12-15 21:25:04'),
(21, 9, 'Staff', NULL, NULL, '$2y$10$5VeELUjcgBHGUi21jgJh5uBLtwmQ.0ycOGCecDbFoYBn8GXgF/PtK', 'Peter Ngulube', NULL, 'staff', 1, '2025-12-17 05:13:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_invitations`
--

CREATE TABLE `user_invitations` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `status` enum('pending','used','expired') DEFAULT 'pending',
  `expires_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency_rates`
--
ALTER TABLE `currency_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currency_code` (`currency_code`),
  ADD KEY `idx_currency_code` (`currency_code`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_branch` (`product_id`,`branch_id`),
  ADD KEY `fk_inv_branch` (`branch_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_number` (`purchase_number`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_id` (`purchase_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_id` (`purchase_order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sale_number` (`sale_number`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `sales_order_items`
--
ALTER TABLE `sales_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_order_id` (`sales_order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transfer_number` (`transfer_number`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `from_branch_id` (`from_branch_id`),
  ADD KEY `to_branch_id` (`to_branch_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `user_invitations`
--
ALTER TABLE `user_invitations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `currency_rates`
--
ALTER TABLE `currency_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1510;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sales_order_items`
--
ALTER TABLE `sales_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_invitations`
--
ALTER TABLE `user_invitations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `fk_branch_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_category_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_inv_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inv_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_product_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `fk_purchase_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_purchase_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_purchase_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `fk_pitem_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pitem_purchase` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_ibfk_1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sale_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sale_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_sale_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD CONSTRAINT `sales_orders_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `sales_orders_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sales_order_items`
--
ALTER TABLE `sales_order_items`
  ADD CONSTRAINT `sales_order_items_ibfk_1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `fk_sitem_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sitem_sale` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD CONSTRAINT `fk_transfer_from` FOREIGN KEY (`from_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transfer_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transfer_to` FOREIGN KEY (`to_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transfer_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_trans_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_trans_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL;
COMMIT;
