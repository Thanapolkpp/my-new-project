-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 27, 2026 at 02:41 PM
-- Server version: 8.0.44-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it_std6730202181`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `image`, `title`, `subtitle`, `link`, `is_active`, `sort_order`, `created_at`) VALUES
(1, '1771910003_9741.png', 'Open1New', 'รับสิทธิพิเศษต้อนรับ OPEN1NEW แจกคูปองส่วนลดมูลค่า 100 บาท สำหรับลูกค้าทุกท่าน ใช้เป็นส่วนลดเมื่อสั่งซื้อสินค้าภายในเว็บไซต์ได้ทันที เพียงเลือกสินค้าและใช้คูปองตามเงื่อนไขที่กำหนด โปรโมชันมีจำนวนจำกัด และสามารถใช้งานได้ถึงวันที่ 26 กุมภาพันธ์ 2569 เท่านั้น รีบรับสิทธิ์ก่อนหมด 1/6 คนต่อสิทธิ์', '', 1, 1, '2026-02-24 04:43:59'),
(2, '1771924329_8777.png', '', '', '', 1, 2, '2026-02-24 09:12:09');

-- --------------------------------------------------------


--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `shipping_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `address` text COLLATE utf8mb4_unicode_ci,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `promo_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `shipping_method`, `status`, `address`, `payment_status`, `promo_code`, `discount_total`, `created_at`, `updated_at`, `phone`) VALUES
(8, 3, 21500.00, 'KERRY', 'completed', NULL, 'PROMPTPAY', NULL, 0.00, NULL, NULL, NULL),
(9, 5, 26000.00, 'Standard', 'completed', '810/693 จ.ชลบุรี อ.สัตหีบ ต.สัตหีบ 20180', 'Paid', NULL, 0.00, '2026-02-24 02:09:47', '2026-02-24 02:09:47', NULL),
(15, 1, 0.00, 'TNP_Express', 'completed', 'บ้าน 16/27', 'Send_telepathy', 'free100', 74800.00, NULL, NULL, '0957685818'),
(16, 1, 224400.00, 'TNP_Express', 'completed', 'บ้าน 16/27', 'PROMPTPAY', NULL, 0.00, NULL, NULL, '0957685818'),
(17, 1, 21500.00, 'TNP_Express', 'completed', 'บ้าน 16/27', 'COD', NULL, 0.00, NULL, NULL, '0957685818'),
(18, 1, 6290.00, 'EMS', 'completed', 'บ้าน 16/27', 'CREDIT_CARD', NULL, 0.00, NULL, NULL, '0957685818');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 1, 5000.00, '2026-02-21 21:46:11', '2026-02-21 21:46:11'),
(2, 2, 8, 1, 4250.00, '2026-02-21 22:02:08', '2026-02-21 22:02:08'),
(3, 3, 7, 1, 5000.00, '2026-02-21 22:11:42', '2026-02-21 22:11:42'),
(4, 4, 7, 1, 4550.00, '2026-02-21 23:04:53', '2026-02-21 23:04:53'),
(5, 5, 7, 1, 4550.00, '2026-02-22 00:05:49', '2026-02-22 00:05:49'),
(6, 6, 20, 2, 21500.00, NULL, NULL),
(7, 6, 11, 1, 23900.00, NULL, NULL),
(10, 8, 20, 1, 21500.00, NULL, NULL),
(11, 9, 10, 1, 4500.00, '2026-02-24 02:09:47', '2026-02-24 02:09:47'),
(12, 9, 20, 1, 21500.00, '2026-02-24 02:09:47', '2026-02-24 02:09:47'),
(13, 10, 20, 5, 21500.00, NULL, NULL),
(14, 11, 22, 2, 6500.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `img` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warranty_value` int NOT NULL,
  `warranty_unit` enum('day','month') COLLATE utf8mb4_unicode_ci NOT NULL,
  `certifications` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `serial_number`, `detail`, `price`, `discount`, `stock`, `img`, `warranty_value`, `warranty_unit`, `certifications`, `updated_at`) VALUES
(10, 'AMD Radeon™ RX 6000 Series', 'AMD - 1', 'VGA(การ์ดจอ) POWERCOLOR FIGHTER RADEON RX 6500 XT - 4GB GDDR6 V3 (AXRX 6500XT 4GBD6-DHV3) (3Y)', 4700.00, 200.00, 25, 'https://ihcupload.s3.ap-southeast-1.amazonaws.com/img/product/products103815_800.jpg', 36, 'month', 'CE, TIS', '2026-02-26 02:20:39'),
(11, 'NVIDIA GeForce RTX 3050 Laptop GPU', 'GPU-RTX3050-001', 'การ์ดจอสำหรับโน้ตบุ๊ค NVIDIA RTX 3050 VRAM 6GB GDDR6 เหมาะสำหรับ gaming และงาน graphic', 25900.00, 2000.00, 14, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_4wwKshNDCZMVVK04scC5uX7Hl1HDYUWgIZ_ejWNZASiTona7GT9PPTGL&s=10', 24, 'month', 'NVIDIA Certified', '2026-02-24 03:24:18'),
(15, 'NVIDIA GeForce RTX 5050 Laptop GPU', 'GPU-RTX5050-005', 'RTX 5050 Laptop GPU รุ่นใหม่ รองรับเทคโนโลยีล่าสุด', 39900.00, 2500.00, 7, 'https://img.advice.co.th/images_nas/pic_product4/A0170925/A0170925OK_BIG_1.jpg', 24, 'month', 'NVIDIA Certified', '2026-02-27 09:49:41'),
(19, 'GIGABYTE RADEON RX 9070', 'GPU-UHD-009', 'GIGABYTE RADEON RX 9070 GAMING OC - 16GB GDDR6', 42000.00, 300.00, 25, 'https://img.advice.co.th/images_nas/pic_product4/A0167289/A0167289OK_BIG_1.jpg', 12, 'month', 'AMD Certified', '2026-02-22 11:58:25'),
(20, 'GIGABYTE RADEON RX 7700XT', 'GPU-R680M-010', 'GIGABYTE RADEON RX 7700XT ของดี', 22000.00, 500.00, 9, 'https://img.advice.co.th/images_nas/pic_product4/A0155093/A0155093OK_BIG_1.jpg', 24, 'month', 'AMD Certified', '2026-02-26 03:05:00'),
(21, 'GIGABYTE GEFORCE RTX 3050', 'SKU-250127778', '', 6290.00, 0.00, 49, 'https://ihcupload.s3.ap-southeast-1.amazonaws.com/img/product/products77610_800.jpg', 36, 'month', '', '2026-02-27 09:51:22');

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `is_percentage` tinyint(1) NOT NULL DEFAULT '0',
  `usage_limit` int NOT NULL DEFAULT '0',
  `max_uses_per_user` int DEFAULT '1',
  `used_count` int NOT NULL DEFAULT '0',
  `expiry_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `show_on_home` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promo_codes`
--

INSERT INTO `promo_codes` (`id`, `code`, `discount_amount`, `is_percentage`, `usage_limit`, `max_uses_per_user`, `used_count`, `expiry_date`, `is_active`, `show_on_home`, `created_at`, `updated_at`) VALUES
(4, 'free100', 100.00, 1, 100, 1, 2, '2026-02-27 03:24:00', 1, 0, NULL, NULL),
(5, 'Open1new', 100.00, 0, 99, 6, 0, '2026-02-27 04:47:00', 1, 1, NULL, NULL);

-- ------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL COMMENT '1-5 stars',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(8, 20, 1, 5, '5 ดาว', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `review_reactions`
--

CREATE TABLE `review_reactions` (
  `id` bigint UNSIGNED NOT NULL,
  `review_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` enum('heart','dislike') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_replies`
--

CREATE TABLE `review_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `review_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `otp_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `address`, `phone`, `remember_token`, `created_at`, `updated_at`, `otp_code`, `otp_expires_at`) VALUES
(1, 'Admin', 'tanapolkampimpit@gmail.com', NULL, '$2y$10$8ikbn/sWkymJGwNuHOcOSOCCCppwWdPclIZmvJD0soIBjIRBmaNcG', 1, 'บ้าน 16/27', '0957685818', NULL, '2026-02-21 20:17:59', '2026-02-25 19:24:58', NULL, NULL),
(2, 'test', 'test@t', NULL, '$2y$12$l908NkDivYNNiVQFIwLaze/S.fcyTaHzVggEItuUaFhXVbQjSYcNe', 0, NULL, NULL, NULL, '2026-02-21 20:39:32', '2026-02-22 05:18:47', NULL, NULL),
(3, 't', 't@T', NULL, '$2y$10$4RB5QMWpVz1.cdTUeAnzYeft.FXX6D4OQ2FiaFILXsHrmnA8BYTmW', 0, '555/555 หมู่บ้าน ดาเมก แขวง ดาวอังคาร เขต ดวงอาทิตย์ จังหวัด ทางช้างเผือก 10101', '111111111', NULL, NULL, NULL, NULL, NULL),
(4, 'Stayfan', 'stayfan@gmail.com', NULL, '$2y$12$ryyvgOXxObM/eEnfR/2G7e2ykmyawae2JdBhVtCuZ03eko1K58Wiu', 0, NULL, NULL, NULL, '2026-02-24 01:59:19', '2026-02-24 01:59:51', '674297', '2026-02-24 02:09:51'),
(5, 'Stayfan', 'poraweelammana@gmail.com', NULL, '$2y$12$viy0r0AyM6S7NJkg.r88aOASOHmpUlL3.JGEyvvvXdUYLxEH75Qaa', 0, NULL, NULL, NULL, '2026-02-24 02:01:02', '2026-02-24 07:20:15', NULL, NULL),
(6, 'nichakamon', 'nichakamon.ta@ku.th', NULL, '$2y$10$Kd.6qmLopySuezIEyLTJ4OgIckqBRlsBD26ZcHEiGrzQu2AEYkOji', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--


--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_index` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promo_codes_code_unique` (`code`);


--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review_reactions`
--
ALTER TABLE `review_reactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_reaction` (`review_id`,`user_id`),
  ADD KEY `idx_review_id` (`review_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `review_replies`
--
ALTER TABLE `review_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reply_review_id` (`review_id`),
  ADD KEY `idx_reply_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `review_reactions`
--
ALTER TABLE `review_reactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `review_replies`
--
ALTER TABLE `review_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `review_reactions`
--
ALTER TABLE `review_reactions`
  ADD CONSTRAINT `fk_reaction_review` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reaction_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review_replies`
--
ALTER TABLE `review_replies`
  ADD CONSTRAINT `fk_reply_review` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reply_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
