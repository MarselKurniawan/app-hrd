-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2024 at 01:43 PM
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
-- Database: `hrd`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `years_worked` int(11) NOT NULL,
  `employment_status` varchar(255) NOT NULL,
  `position` varchar(50) NOT NULL,
  `contract_document` varchar(255) DEFAULT NULL,
  `property_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `age`, `salary`, `years_worked`, `employment_status`, `position`, `contract_document`, `property_id`) VALUES
(17, 'Joko Laksono', 27, 9500000.00, 5, 'active', 'Branch Manager', NULL, 1),
(24, 'Joko Laksono', 27, 9500000.00, 5, 'active', 'Branch Manageree', NULL, 2),
(25, 'Agung ', 27, 7800000.00, 6, 'active', 'Web', NULL, 3),
(27, 'Marsle', 27, 9500000.00, 5, 'active', 'Branch Manageree', NULL, 0),
(28, 'Marsleqq', 27, 9500000.00, 5, 'active', 'Branch Manageree', NULL, 0),
(29, 'Bagus', 27, 9500000.00, 5, 'active', 'Branch Manageree', NULL, 0),
(30, 'Test', 12, 1231.00, 123, 'active', 'Web', NULL, 2),
(31, 'Bagus222', 27, 9500000.00, 5, 'active', 'Branch Manageree', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hrd_properties`
--

CREATE TABLE `hrd_properties` (
  `id` int(11) NOT NULL,
  `hrd_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hrd_properties`
--

INSERT INTO `hrd_properties` (`id`, `hrd_id`, `property_id`) VALUES
(1, 8, 2),
(2, 1, 2),
(3, 9, 3);

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `name`) VALUES
(1, 'Cozzy'),
(3, 'omni\r\n'),
(2, 'Youstay');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'hrd');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`) VALUES
(1, 'Marsel Kurniawan', 'marsel@gmail.com', '$2y$10$t1YjZzGh1MHooIevFDg1ruf95DKVMQX15fer9HuULueohLTKKsc/G', 1),
(2, 'joko', 'admin@admin.com', '$2y$10$dfrIoxj0P7RBYFIXcjCxyu71oMDPfxGURH7dKYaZXphheJV79V9BG', 2),
(3, 'joko', 'marshel@sinergimanajemen.com', '$2y$10$oAxmxtYgJ7uVQCu7wE03/uxTIi38tWNgkXZwrLkaqOs2Fa4McHCci', 2),
(8, 'joko', 'marselbgs345@gmail.com', '$2y$10$IN7dZy6VL8l6gyMDov1ua.ntdh/VKCeFuRSCLODq.uauWU7uX7QmG', 2),
(9, 'joko', 'info@sinergimax.com', '$2y$10$Er0C92zzCCDwlN0DA1NJOO.ImXYOFEiXkhXBAXAgRD6HXhgCx2sey', 2),
(10, 'joko', 'time@time.com', '$2y$10$iEs8R/6DpVc1mEUUWbjme.DysJ2ZPi0l1mzmOOx8QrOIfCIZF3hYy', 2),
(11, 'joko', 'coba@gmail.com', '$2y$10$eKNLjHPfr6bIINOc2h3M4O9TtCzVtZYk4iJs0S86LDnwydqBWNDTK', 2),
(12, 'joko', 'test@gmail.com', '$2y$10$BxrEGbCxUiYMHSBgHlBm.eZzEGe1q2xuzS5TLIQJJb2Dt7NyoLvGS', 2),
(13, 'joko', 'hrdindah@jauhsekali.com', '$2y$10$U9geHBWSuZuZAnvklhlTxOJEPa9fsf/6VVtcECAJ6BJVvSqUTmKua', 2),
(14, 'joko', 'sinergi.ecommerceenabler@gmail.com', '$2y$10$oYEhEya6NTN7j5HrtIPdLux5jHxm6zoTYFuDzDrqsWnubcf.B2Yg.', 2),
(15, 'joko', 'sinergyhotel.solutions@gmail.com', '$2y$10$jJ0wrjmLEzfqErwBeD.YaOQTPHfnaAQCz3FeKUYNVwO7yatjxhOqC', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrd_properties`
--
ALTER TABLE `hrd_properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrd_id` (`hrd_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `hrd_properties`
--
ALTER TABLE `hrd_properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hrd_properties`
--
ALTER TABLE `hrd_properties`
  ADD CONSTRAINT `hrd_properties_ibfk_1` FOREIGN KEY (`hrd_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hrd_properties_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
