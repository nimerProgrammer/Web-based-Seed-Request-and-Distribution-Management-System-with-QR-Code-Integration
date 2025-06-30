-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 11:53 PM
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
-- Database: `omas_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `beneficiaries_tbl_id` int(11) NOT NULL,
  `qr_code` varchar(100) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `date_time_received` varchar(22) DEFAULT NULL,
  `status` varchar(13) NOT NULL,
  `seed_requests_tbl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficiaries`
--

INSERT INTO `beneficiaries` (`beneficiaries_tbl_id`, `qr_code`, `ref_no`, `date_time_received`, `status`, `seed_requests_tbl_id`) VALUES
(35, '1st CROPPING-2025-RC18 (Rice)-Improved-03488343', 'REF-20250628-RC18 (RICE)-OCXGFMBQPKAU', NULL, 'For Receiving', 2);

-- --------------------------------------------------------

--
-- Table structure for table `client_info`
--

CREATE TABLE `client_info` (
  `client_info_tbl_id` int(11) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `middle_name` varchar(20) DEFAULT NULL,
  `suffix_and_ext` varchar(3) DEFAULT NULL,
  `gender` varchar(6) NOT NULL,
  `age` varchar(2) NOT NULL,
  `b_date` varchar(10) NOT NULL,
  `brgy` varchar(30) NOT NULL,
  `mun` varchar(4) NOT NULL,
  `prov` varchar(13) NOT NULL,
  `farm_area` varchar(14) NOT NULL,
  `name_land_owner` varchar(60) NOT NULL,
  `rsbsa_ref_no` varchar(30) NOT NULL,
  `users_tbl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_info`
--

INSERT INTO `client_info` (`client_info_tbl_id`, `last_name`, `first_name`, `middle_name`, `suffix_and_ext`, `gender`, `age`, `b_date`, `brgy`, `mun`, `prov`, `farm_area`, `name_land_owner`, `rsbsa_ref_no`, `users_tbl_id`) VALUES
(2, 'Nimer', 'Gerald', 'Montallana', NULL, 'Male', '25', '03-25-2000', 'Cadi-an', 'Oras', 'Eastern Samar', ' 1', 'Clacrk Cruz', '03488343', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cropping_season`
--

CREATE TABLE `cropping_season` (
  `cropping_season_tbl_id` int(11) NOT NULL,
  `season` varchar(12) NOT NULL,
  `year` varchar(4) NOT NULL,
  `date_start` varchar(12) DEFAULT NULL,
  `date_end` varchar(12) DEFAULT NULL,
  `status` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cropping_season`
--

INSERT INTO `cropping_season` (`cropping_season_tbl_id`, `season`, `year`, `date_start`, `date_end`, `status`) VALUES
(1, '1st CROPPING', '2025', '10-10-2025', NULL, 'Current'),
(2, '2nd CROPPING', '2025', '10-10-2025', NULL, 'Ended');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_tbl_id` int(11) NOT NULL,
  `seed_name` varchar(50) NOT NULL,
  `seed_class` varchar(10) NOT NULL,
  `stock` int(11) NOT NULL,
  `distributed` int(11) DEFAULT NULL,
  `date_stored` varchar(22) NOT NULL,
  `cropping_season_tbl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_tbl_id`, `seed_name`, `seed_class`, `stock`, `distributed`, `date_stored`, `cropping_season_tbl_id`) VALUES
(2, 'RC18 (Rice)', 'Improved', 47, NULL, '06-12-2025 08:14:12 AM', 1),
(10, 'Karabasa', 'Hybrid', 544, NULL, '06-23-2025 01:44:01 PM', 1);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `logs_tbl_id` int(11) NOT NULL,
  `timestamp` varchar(22) NOT NULL,
  `action` varchar(20) NOT NULL,
  `details` varchar(255) NOT NULL,
  `users_tbl_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`logs_tbl_id`, `timestamp`, `action`, `details`, `users_tbl_id`) VALUES
(1, '06-28-2025 03:24:16 AM', 'Marked as Received', 'Clarck Dela Cruz marked beneficiary \"Gerald Montallana Nimer\" (RSBSA: 03488343) as received.', 1),
(2, '06-28-2025 06:02:22 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(3, '06-29-2025 09:55:40 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(4, '06-29-2025 05:03:49 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(5, '06-29-2025 05:12:22 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(6, '06-29-2025 05:32:31 PM', 'Undo Received', 'Clarck Dela Cruz reverted status of beneficiary \"Gerald Montallana Nimer\" (RSBSA: 03488343) to \"For Receiving\".', 1),
(7, '06-29-2025 09:07:39 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(8, '06-30-2025 02:39:29 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(9, '06-30-2025 03:34:32 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(10, '06-30-2025 08:42:14 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `seed_requests`
--

CREATE TABLE `seed_requests` (
  `seed_requests_tbl_id` int(11) NOT NULL,
  `date_time_requested` varchar(22) NOT NULL,
  `date_time_approved` varchar(22) DEFAULT NULL,
  `date_time_rejected` varchar(22) DEFAULT NULL,
  `status` varchar(9) NOT NULL,
  `inventory_tbl_id` int(11) NOT NULL,
  `client_info_tbl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seed_requests`
--

INSERT INTO `seed_requests` (`seed_requests_tbl_id`, `date_time_requested`, `date_time_approved`, `date_time_rejected`, `status`, `inventory_tbl_id`, `client_info_tbl_id`) VALUES
(2, '06-12-2025 08:14:24 AM', '06-28-2025 03:20:39 AM', NULL, 'Approved', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `staff_info`
--

CREATE TABLE `staff_info` (
  `staff_info_tbl_id` int(11) NOT NULL,
  `emp_id` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `middle_name` varchar(20) NOT NULL,
  `suffix_and_ext` varchar(3) DEFAULT NULL,
  `gender` varchar(6) NOT NULL,
  `age` varchar(2) NOT NULL,
  `b_date` varchar(10) NOT NULL,
  `users_tbl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_info`
--

INSERT INTO `staff_info` (`staff_info_tbl_id`, `emp_id`, `last_name`, `first_name`, `middle_name`, `suffix_and_ext`, `gender`, `age`, `b_date`, `users_tbl_id`) VALUES
(1, '645grtt', 'Cruz', 'Clarck', 'Dela', NULL, 'Male', '25', '03-25-2000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_tbl_id` int(11) NOT NULL,
  `contact_no` varchar(13) NOT NULL,
  `email` varchar(64) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(6) NOT NULL,
  `account_status` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_tbl_id`, `contact_no`, `email`, `username`, `password`, `user_type`, `account_status`) VALUES
(1, '09122137559', 'clarckcruz25@gmail.com', 'admin', '$2y$10$FPgfDL592NUvCTG1YMm6TOnh9WNsbNYVqgdCFzMD7s6/Cya1maxu2', 'admin', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`beneficiaries_tbl_id`),
  ADD UNIQUE KEY `qr_code` (`qr_code`),
  ADD UNIQUE KEY `ref_no` (`ref_no`),
  ADD KEY `seed_requests_tbl_id` (`seed_requests_tbl_id`);

--
-- Indexes for table `client_info`
--
ALTER TABLE `client_info`
  ADD PRIMARY KEY (`client_info_tbl_id`),
  ADD KEY `users_tbl_id` (`users_tbl_id`);

--
-- Indexes for table `cropping_season`
--
ALTER TABLE `cropping_season`
  ADD PRIMARY KEY (`cropping_season_tbl_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_tbl_id`),
  ADD KEY `cropping_season_tbl_id` (`cropping_season_tbl_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`logs_tbl_id`),
  ADD KEY `users_tbl_id` (`users_tbl_id`);

--
-- Indexes for table `seed_requests`
--
ALTER TABLE `seed_requests`
  ADD PRIMARY KEY (`seed_requests_tbl_id`),
  ADD KEY `client_info_tbl_id` (`client_info_tbl_id`),
  ADD KEY `inventory_tbl_id` (`inventory_tbl_id`);

--
-- Indexes for table `staff_info`
--
ALTER TABLE `staff_info`
  ADD PRIMARY KEY (`staff_info_tbl_id`),
  ADD UNIQUE KEY `emp_id` (`emp_id`),
  ADD KEY `users_tbl_id` (`users_tbl_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_tbl_id`),
  ADD UNIQUE KEY `contact_no` (`contact_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `beneficiaries_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `client_info`
--
ALTER TABLE `client_info`
  MODIFY `client_info_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cropping_season`
--
ALTER TABLE `cropping_season`
  MODIFY `cropping_season_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `logs_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `seed_requests`
--
ALTER TABLE `seed_requests`
  MODIFY `seed_requests_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff_info`
--
ALTER TABLE `staff_info`
  MODIFY `staff_info_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `beneficiaries_ibfk_1` FOREIGN KEY (`seed_requests_tbl_id`) REFERENCES `seed_requests` (`seed_requests_tbl_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_info`
--
ALTER TABLE `client_info`
  ADD CONSTRAINT `client_info_ibfk_1` FOREIGN KEY (`users_tbl_id`) REFERENCES `users` (`users_tbl_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`cropping_season_tbl_id`) REFERENCES `cropping_season` (`cropping_season_tbl_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`users_tbl_id`) REFERENCES `users` (`users_tbl_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `seed_requests`
--
ALTER TABLE `seed_requests`
  ADD CONSTRAINT `seed_requests_ibfk_1` FOREIGN KEY (`client_info_tbl_id`) REFERENCES `client_info` (`client_info_tbl_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seed_requests_ibfk_2` FOREIGN KEY (`inventory_tbl_id`) REFERENCES `inventory` (`inventory_tbl_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `staff_info`
--
ALTER TABLE `staff_info`
  ADD CONSTRAINT `staff_info_ibfk_1` FOREIGN KEY (`users_tbl_id`) REFERENCES `users` (`users_tbl_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
