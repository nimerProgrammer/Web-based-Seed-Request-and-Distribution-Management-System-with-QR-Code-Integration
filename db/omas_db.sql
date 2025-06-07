-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 02:49 PM
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
-- Table structure for table `cropping_season`
--

CREATE TABLE `cropping_season` (
  `cropping_season_tbl_id` int(11) NOT NULL,
  `season` varchar(12) NOT NULL,
  `year` varchar(4) NOT NULL,
  `date_start` varchar(10) NOT NULL,
  `date_end` varchar(10) DEFAULT NULL,
  `status` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_tbl_id` int(11) NOT NULL,
  `seed_name` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL,
  `date_stored` varchar(10) NOT NULL,
  `cropping_season_tbl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_tbl_id` int(11) NOT NULL,
  `email` varchar(64) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_tbl_id`, `email`, `username`, `password`) VALUES
(1, 'clarckcruz25@gmail.com', 'admin', '$2y$10$FPgfDL592NUvCTG1YMm6TOnh9WNsbNYVqgdCFzMD7s6/Cya1maxu2');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_tbl_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cropping_season`
--
ALTER TABLE `cropping_season`
  MODIFY `cropping_season_tbl_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_tbl_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`cropping_season_tbl_id`) REFERENCES `cropping_season` (`cropping_season_tbl_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
