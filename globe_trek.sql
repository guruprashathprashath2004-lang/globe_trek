-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2026 at 04:43 PM
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
-- Database: `globe_trek`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `package_name` varchar(50) DEFAULT NULL,
  `vehicle_key` varchar(50) DEFAULT NULL,
  `meal_key` varchar(20) DEFAULT NULL,
  `room_key` varchar(20) DEFAULT NULL,
  `hotel_type` varchar(20) DEFAULT NULL,
  `adults` int(11) DEFAULT 1,
  `children` int(11) DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `num_days` int(11) DEFAULT NULL,
  `guide_included` tinyint(4) DEFAULT 0,
  `payment_method` varchar(20) DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `advance_amount` int(11) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

-- (sample bookings removed)

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `province_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `province_id`, `name`) VALUES
(1, 1, 'Colombo'),
(2, 1, 'Gampaha'),
(3, 1, 'Kalutara'),
(4, 2, 'Kandy'),
(5, 2, 'Matale'),
(6, 2, 'Nuwara Eliya'),
(7, 3, 'Galle'),
(8, 3, 'Matara'),
(9, 3, 'Hambantota'),
(10, 4, 'Jaffna'),
(11, 4, 'Kilinochchi'),
(12, 4, 'Mannar'),
(13, 4, 'Mullaitivu'),
(14, 4, 'Vavuniya'),
(15, 5, 'Trincomalee'),
(16, 5, 'Batticaloa'),
(17, 5, 'Ampara'),
(18, 6, 'Kurunegala'),
(19, 6, 'Puttalam'),
(20, 7, 'Anuradhapura'),
(21, 7, 'Polonnaruwa'),
(22, 8, 'Badulla'),
(23, 8, 'Monaragala'),
(24, 9, 'Ratnapura'),
(25, 9, 'Kegalle');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_district`
--

CREATE TABLE `hotel_district` (
  `id` int(11) NOT NULL,
  `hotel_name` varchar(200) NOT NULL,
  `hotel_type` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `district_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_district`
--

INSERT INTO `hotel_district` (`id`, `hotel_name`, `hotel_type`, `price`, `district_id`) VALUES
(1, 'CityRest Fort', 'normal', 12000, 1),
(2, 'Shangri-La Colombo', 'luxury', 80000, 1),
(3, 'Airport Green View Resort', 'normal', 10000, 2),
(4, 'Jetwing Blue', 'luxury', 50000, 2),
(5, 'Hotel Coconut Bay', 'normal', 15000, 3),
(6, 'Anantara Kalutara Resort', 'luxury', 90000, 3),
(7, 'Hotel Topaz', 'normal', 18000, 4),
(8, 'Earl\'s Regency Hotel', 'luxury', 60000, 4),
(9, 'Hotel Riverston', 'normal', 12000, 5),
(10, 'Jim\'s Farm Villas', 'luxury', 70000, 5),
(11, 'Heaven Seven', 'normal', 16000, 6),
(12, 'Grand Hotel Nuwara Eliya', 'luxury', 70000, 6),
(13, 'Fort Bliss Galle', 'normal', 15000, 7),
(14, 'Jetwing Lighthouse', 'luxury', 80000, 7),
(15, 'Beach Inns Resort', 'normal', 12000, 8),
(16, 'Cape Weligama', 'luxury', 120000, 8),
(17, 'Peacock Beach Hotel', 'normal', 14000, 9),
(18, 'Shangri-La Hambantota', 'luxury', 90000, 9),
(19, 'Jetwing Jaffna', 'normal', 25000, 10),
(20, 'North Gate by Jetwing', 'luxury', 35000, 10),
(21, 'Ahi House', 'normal', 10000, 11),
(22, 'Reecha Resort', 'luxury', 30000, 11),
(23, 'Hotel Agape', 'normal', 12000, 12),
(24, 'Palmyrah House', 'luxury', 40000, 12),
(25, 'Beach Hotel', 'normal', 10000, 13),
(26, 'Akkarai Resort', 'luxury', 30000, 13),
(27, 'Hotel NorthWay', 'normal', 12000, 14),
(28, 'Nelly Star Hotel', 'luxury', 25000, 14),
(29, 'Hotel Tobiko', 'normal', 12000, 15),
(30, 'Amaranthe Bay Resort', 'luxury', 50000, 15),
(31, 'Riviera Resort', 'normal', 12000, 16),
(32, 'Amaya Beach Pasikudah', 'luxury', 60000, 16),
(33, 'Panama Beach Resort', 'normal', 14000, 17),
(34, 'Jetwing Surf', 'luxury', 70000, 17),
(35, 'Hotel Blue Sky', 'normal', 12000, 18),
(36, 'The Kandyan Reach', 'luxury', 35000, 18),
(37, 'Anantaya Chilaw', 'normal', 18000, 19),
(38, 'Club Hotel Dolphin', 'luxury', 50000, 19),
(39, 'Hotel Alakamanda', 'normal', 12000, 20),
(40, 'Uga Ulagalla', 'luxury', 120000, 20),
(41, 'Hotel Sudu Araliya', 'normal', 14000, 21),
(42, 'EKHO Lake House', 'luxury', 40000, 21),
(43, 'Ella Mount Heaven', 'normal', 15000, 22),
(44, '98 Acres Resort', 'luxury', 80000, 22),
(45, 'Elephant Reach Udawalawa', 'normal', 14000, 23),
(46, 'Grand Udawalawe Safari Resort', 'luxury', 40000, 23),
(47, 'Centauria Hill Resort', 'normal', 15000, 24),
(48, 'Palmstone Retreat', 'luxury', 50000, 24),
(49, 'Hotel Elephant Bay', 'normal', 12000, 25),
(50, 'Hotel Pinnalanda', 'luxury', 35000, 25);

-- --------------------------------------------------------

--
-- Table structure for table `meal_plans`
--

CREATE TABLE `meal_plans` (
  `id` int(11) NOT NULL,
  `meal_key` varchar(20) NOT NULL,
  `meal_name` varchar(50) NOT NULL,
  `cost_per_person` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_plans`
--

INSERT INTO `meal_plans` (`id`, `meal_key`, `meal_name`, `cost_per_person`) VALUES
(1, 'bo', 'Breakfast Only', 0),
(2, 'hb', 'Half Board (Breakfast + Dinner)', 2500),
(3, 'fb', 'Full Board (All Meals)', 4500);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `display_name`, `description`) VALUES
(1, 'budget', 'Budget Package', 'Best for budget-conscious travelers. Comfortable essentials with maximum savings.'),
(2, 'normal', 'Normal Package', 'Balanced comfort and affordability. Great mid-range experience.'),
(3, 'premium', 'Premium Package', 'Elevated comfort and curated experiences. Travel in style.'),
(4, 'luxury', 'Luxury Package', 'The ultimate Sri Lanka experience. Private vehicles and 5-star stays.');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(50) DEFAULT NULL,
  `cardholder_name` varchar(100) DEFAULT NULL,
  `card_number` varchar(20) DEFAULT NULL,
  `expiry` varchar(10) DEFAULT NULL,
  `cvv` varchar(5) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'paid',
  `paid_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

-- (sample payments removed)

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `recommended_days` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `name`, `recommended_days`) VALUES
(1, 'Western Province', 13),
(2, 'Central Province', 12),
(3, 'Southern Province', 12),
(4, 'Northern Province', 17),
(5, 'Eastern Province', 12),
(6, 'North Western Province', 8),
(7, 'North Central Province', 8),
(8, 'Uva Province', 9),
(9, 'Sabaragamuwa Province', 8);

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(50) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_by` varchar(100) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

-- (sample receipts removed)

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int(11) NOT NULL,
  `room_key` varchar(20) NOT NULL,
  `room_name` varchar(50) NOT NULL,
  `multiplier` decimal(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `room_key`, `room_name`, `multiplier`) VALUES
(1, 'single', 'Single Room (1 person)', 1.00),
(2, 'double', 'Double Room (2 persons)', 1.50),
(3, 'family', 'Family Room (4 persons)', 2.20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--
INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '+94000000000', '$2y$10$REPLACE_WITH_YOUR_OWN_HASH', 'admin', '2026-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `package_name` varchar(50) NOT NULL,
  `vehicle_key` varchar(50) NOT NULL,
  `vehicle_label` varchar(100) NOT NULL,
  `cost` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `package_name`, `vehicle_key`, `vehicle_label`, `cost`) VALUES
(1, 'budget', 'bus', 'Bus (Standard)', 20000),
(2, 'budget', 'van', 'Van (Standard)', 25000),
(3, 'normal', 'bus', 'Bus (AC)', 22000),
(4, 'normal', 'van', 'Van (AC)', 28000),
(5, 'normal', 'car', 'Car (Sedan)', 20000),
(6, 'premium', 'van', 'Van (Premium)', 35000),
(7, 'premium', 'car', 'Car (SUV)', 30000),
(8, 'luxury', 'car', 'Car (Luxury)', 45000),
(9, 'luxury', 'vipVan', 'VIP Van (Luxury)', 40000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `hotel_district`
--
ALTER TABLE `hotel_district`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_hotel_type` (`hotel_name`,`hotel_type`),
  ADD KEY `district_id` (`district_id`);

--
-- Indexes for table `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `hotel_district`
--
ALTER TABLE `hotel_district`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`);

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_district`
--
ALTER TABLE `hotel_district`
  ADD CONSTRAINT `hotel_district_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
