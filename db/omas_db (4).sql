-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2025 at 06:54 AM
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
(56, '1st CROPPING-2025-RC18 (Rice)-Improved-34343', 'REF-07112025-RC18 (RICE)-3RJK21CZLAP7', '07-15-2025 10:39:38 PM', 'Received', 14),
(57, '1st CROPPING-2025-RC18 (Rice)-Improved-2208404', 'REF-07142025-RC18 (RICE)-Z9FUNYD514TL', NULL, 'For Receiving', 16),
(58, '1st CROPPING-2025-Sometimes-Hybrid-2208404', 'REF-07142025-SOMETIMES-1FQ03EA8KI4S', NULL, 'For Receiving', 17),
(59, '1st CROPPING-2025-Karabasa-Hybrid-2208404', 'REF-07142025-KARABASA-UPK9XREM3WGY', NULL, 'For Receiving', 15);

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
(12, 'Pajanustan', 'Jhon Mark', 'Montallana', 'Jr.', 'Female', '18', '2007-03-09', 'Agsam', 'Oras', 'Eastern Samar', '2.37', 'Karlo', '34343', 26),
(13, 'Santos', 'Jhon Mark', 'Dfdf', NULL, 'Female', '20', '2005-05-27', 'Cagdine', 'Oras', 'Eastern Samar', '4.7', 'Ddsds', '2344332232', 28),
(14, 'Basa', 'Jude Vincent', 'B.', NULL, 'Male', '20', '2004-09-13', 'Cadi-an', 'Oras', 'Eastern Samar', '1.2', 'Jude Vincent', '2208404', 29);

-- --------------------------------------------------------

--
-- Table structure for table `cropping_season`
--

CREATE TABLE `cropping_season` (
  `cropping_season_tbl_id` int(11) NOT NULL,
  `season` varchar(12) NOT NULL,
  `year` varchar(4) NOT NULL,
  `date_start` varchar(12) NOT NULL,
  `date_end` varchar(12) NOT NULL,
  `status` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cropping_season`
--

INSERT INTO `cropping_season` (`cropping_season_tbl_id`, `season`, `year`, `date_start`, `date_end`, `status`) VALUES
(1, '1st CROPPING', '2025', '07-16-2025', '07-30-2025', 'Current'),
(5, '2nd CROPPING', '2026', '07-28-2026', '07-30-2026', 'Ongoing');

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
(2, 'RC18 (Rice)', 'Improved', 48, NULL, '06-12-2025 08:14:12 AM', 1),
(10, 'Karabasa', 'Hybrid', 544, NULL, '06-23-2025 01:44:01 PM', 1),
(13, 'Sometimes', 'Hybrid', 347, NULL, '07-03-2025 10:14:08 PM', 1);

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
(1, '07-11-2025 06:51 PM', 'Edit Seed Request', 'User Jhon Mark Montallana Pajanustan Jr. edited a seed request and selected a new seed.', 26),
(2, '07-11-2025 06:52:33 PM', 'Edit Seed Request', 'User Jhon Mark Montallana Pajanustan Jr. edited a seed request and selected a new seed.', 26),
(3, '07-11-2025 09:14:50 PM', 'Login', 'Login attempt failed due to incorrect password for username: farmer123, user type: farmer', 26),
(4, '07-11-2025 09:14:58 PM', 'Login', 'Login attempt failed due to incorrect password for username: farmer123, user type: farmer', 26),
(5, '07-11-2025 09:15:11 PM', 'Login', 'User Jhon Mark Montallana Pajanustan Jr. logged in successfully.', 26),
(6, '07-11-2025 09:19:36 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(7, '07-12-2025 10:48:28 AM', 'Login', 'User Jhon Mark Montallana Pajanustan Jr. logged in successfully.', 26),
(8, '07-12-2025 10:49:05 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(9, '07-12-2025 08:09:38 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(10, '07-12-2025 08:10:58 PM', 'Login', 'User Jhon Mark Montallana Pajanustan Jr. logged in successfully.', 26),
(11, '07-12-2025 11:50:45 PM', 'Login', 'User Jhon Mark Montallana Pajanustan Jr. logged in successfully.', 26),
(12, '07-12-2025 11:50:55 PM', 'Edit Seed Request', 'User Jhon Mark Montallana Pajanustan Jr. edited a seed request and selected a new seed.', 26),
(13, '07-13-2025 01:32:45 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(14, '07-14-2025 10:17:30 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(15, '07-14-2025 10:48:52 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(16, '07-14-2025 10:59:16 AM', 'Login', 'Login attempt failed — email not found: clarkcruz25@gmail.com', NULL),
(17, '07-14-2025 10:59:22 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(18, '07-14-2025 11:00:14 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(19, '07-14-2025 11:05:57 AM', 'Create Account', 'Farmer \"Jude Vincent B. Basa\" signed up and created his account.', 29),
(20, '07-14-2025 11:06:27 AM', 'Login', 'Login attempt failed due to incorrect password for username: judevincent, user type: farmer', 29),
(21, '07-14-2025 11:06:31 AM', 'Login', 'User Jude Vincent B. Basa logged in successfully.', 29),
(22, '07-14-2025 11:06:55 AM', 'Seed Request Submitt', 'Farmer Jude Vincent B. Basa (RSBSA No. 2208404) submitted a seed request for Karabasa.', 29),
(23, '07-14-2025 11:07:10 AM', 'Seed Request Submitt', 'Farmer Jude Vincent B. Basa (RSBSA No. 2208404) submitted a seed request for RC18 (Rice).', 29),
(24, '07-14-2025 11:08:49 AM', 'Approved Seed Reques', 'Clarck Dela Cruz approved the seed request of \"Jude Vincent B. Basa\" (RSBSA: 2208404).', 1),
(25, '07-14-2025 11:10:33 AM', 'Seed Request Submitt', 'Farmer Jude Vincent B. Basa (RSBSA No. 2208404) submitted a seed request for Sometimes.', 29),
(26, '07-14-2025 11:10:59 AM', 'Approved Seed Reques', 'Clarck Dela Cruz approved the seed request of \"Jude Vincent B. Basa\" (RSBSA: 2208404).', 1),
(27, '07-14-2025 11:11:08 AM', 'Approved Seed Reques', 'Clarck Dela Cruz approved the seed request of \"Jude Vincent B. Basa\" (RSBSA: 2208404).', 1),
(28, '07-14-2025 11:16:42 AM', 'Login', 'Login attempt failed due to incorrect password for username: judevincent, user type: farmer', 29),
(29, '07-14-2025 11:16:45 AM', 'Login', 'Login attempt failed due to incorrect password for username: judevincent, user type: farmer', 29),
(30, '07-14-2025 11:16:54 AM', 'Login', 'Login attempt failed due to incorrect password for username: judevincent, user type: farmer', 29),
(31, '07-14-2025 11:16:57 AM', 'Login', 'User Jude Vincent B. Basa logged in successfully.', 29),
(32, '07-14-2025 09:54:47 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(33, '07-14-2025 10:10:18 PM', 'Login', 'Login attempt failed due to incorrect password for email: clarckcruz25@gmail.com', 1),
(34, '07-14-2025 10:10:26 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(35, '07-14-2025 11:08:27 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(36, '07-15-2025 12:29:12 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(37, '07-15-2025 06:25:42 PM', 'Login', 'Login attempt failed due to incorrect password for email: clarckcruz25@gmail.com', 1),
(38, '07-15-2025 06:25:46 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(39, '07-15-2025 06:28:18 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(40, '07-15-2025 10:20:16 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(41, '07-15-2025 10:39:38 PM', 'Marked as Received', 'Clarck Dela Cruz marked beneficiary \"Jhon Mark Montallana Pajanustan Jr.\" (RSBSA: 34343) as received.', 1),
(42, '07-16-2025 05:54:08 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(43, '07-16-2025 06:16:27 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(44, '07-16-2025 07:02:05 PM', 'Approved Seed Reques', 'Clarck Dela Cruz approved the seed request of \"Jhon Mark Montallana Pajanustan Jr.\" (RSBSA: 34343).', 1),
(45, '07-16-2025 10:10:38 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(46, '07-16-2025 11:46:56 PM', 'Add Cropping Season', 'Clarck Dela Cruz added a new cropping season: \"2nd CROPPING\" (2027) from 03-16-2027 to 04-16-2027.', 1),
(47, '07-17-2025 01:15:34 AM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2026) from 12-17-2026 to 02-16-2027.', 1),
(48, '07-17-2025 01:42:49 AM', 'Delete Cropping Seas', 'Clarck Dela Cruz deleted cropping season \"2nd CROPPING\" (2027).', 1),
(49, '07-17-2025 02:53:17 AM', 'Login', 'Login attempt failed due to incorrect password for email: clarckcruz25@gmail.com', 1),
(50, '07-17-2025 02:53:24 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(51, '07-17-2025 04:34:08 AM', 'Undo Approval', 'Clarck Dela Cruz reverted approval for \"Jhon Mark Montallana Pajanustan Jr.\" (RSBSA: 34343).', 1),
(52, '07-17-2025 02:56:47 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(53, '07-19-2025 09:49:31 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(54, '07-19-2025 09:49:49 PM', 'Delete Cropping Seas', 'Clarck Dela Cruz deleted cropping season \"2nd CROPPING\" (2025).', 1),
(55, '07-19-2025 10:31:05 PM', 'Login', 'User Jhon Mark Montallana Pajanustan Jr. logged in successfully.', 26),
(56, '07-19-2025 11:00:12 PM', 'Update Fullname', 'Admin Clarck Dela Cruz changed suffix/ext from \'(none)\' to \'VI\'.', 1),
(57, '07-19-2025 11:00:34 PM', 'Update Fullname', 'Admin Clarck Dela Cruz VI changed suffix/ext from \'VI\' to \'(none)\'.', 1),
(58, '07-19-2025 11:00:48 PM', 'Update Gender', 'Admin Clarck Dela Cruz updated their gender.', 1),
(59, '07-19-2025 11:01:01 PM', 'Update Gender', 'Admin Clarck Dela Cruz updated their gender.', 1),
(60, '07-19-2025 11:01:14 PM', 'Update Gender', 'User Jhon Mark Montallana Pajanustan Jr. updated their gender.', 26),
(61, '07-19-2025 11:01:26 PM', 'Update Gender', 'User Jhon Mark Montallana Pajanustan Jr. updated their gender.', 26),
(62, '07-19-2025 11:01:38 PM', 'Update Gender', 'Admin Clarck Dela Cruz updated their gender.', 1),
(63, '07-19-2025 11:03:13 PM', 'Update Gender', 'User Jhon Mark Montallana Pajanustan Jr. updated their gender.', 26),
(64, '07-19-2025 11:03:21 PM', 'Update Gender', 'Admin Clarck Dela Cruz updated their gender.', 1),
(65, '07-19-2025 11:03:30 PM', 'Update Gender', 'Admin Clarck Dela Cruz updated their gender.', 1),
(66, '07-19-2025 11:06:59 PM', 'Update Birthdate', 'User Jhon Mark Montallana Pajanustan Jr. updated their birthdate.', 26),
(67, '07-19-2025 11:07:40 PM', 'Update Birthdate', 'Admin Clarck Dela Cruz updated their birthdate.', 1),
(68, '07-19-2025 11:08:16 PM', 'Update Birthdate', 'Admin Clarck Dela Cruz updated their birthdate.', 1),
(69, '07-19-2025 11:08:36 PM', 'Update Birthdate', 'Admin Clarck Dela Cruz updated their birthdate.', 1),
(70, '07-19-2025 11:08:56 PM', 'Update Birthdate', 'Admin Clarck Dela Cruz updated their birthdate.', 1),
(71, '07-19-2025 11:10:09 PM', 'Update Birthdate', 'Admin Clarck Dela Cruz updated their birthdate.', 1),
(72, '07-19-2025 11:10:21 PM', 'Update Contact Numbe', 'Admin Clarck Dela Cruz updated their contact number.', 1),
(73, '07-19-2025 11:10:38 PM', 'Update Contact Numbe', 'Admin Clarck Dela Cruz updated their contact number.', 1),
(74, '07-19-2025 11:11:04 PM', 'Update Email Address', 'Admin Clarck Dela Cruz updated their email address.', 1),
(75, '07-19-2025 11:12:07 PM', 'Update Email Address', 'Admin Clarck Dela Cruz updated their email address.', 1),
(76, '07-19-2025 11:12:35 PM', 'Update Username', 'Admin Clarck Dela Cruz updated their username.', 1),
(77, '07-19-2025 11:13:19 PM', 'Change Password', 'Admin Clarck Dela Cruz changed their password.', 1),
(78, '07-19-2025 11:13:56 PM', 'Change Password', 'User Jhon Mark Montallana Pajanustan Jr. changed their password.', 26),
(79, '07-19-2025 11:16:58 PM', 'Change Password', 'User Jhon Mark Montallana Pajanustan Jr. changed their password.', 26),
(80, '07-19-2025 11:17:36 PM', 'Change Password', 'Admin Clarck Dela Cruz changed their password.', 1),
(81, '07-19-2025 11:23:12 PM', 'Change Password', 'User Jhon Mark Montallana Pajanustan Jr. changed their password.', 26),
(82, '07-19-2025 11:24:15 PM', 'Change Password', 'Admin Clarck Dela Cruz changed their password.', 1),
(83, '07-19-2025 11:51:20 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(84, '07-28-2025 11:43:53 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(85, '07-28-2025 11:44:16 AM', 'Login', 'User Jhon Mark Montallana Pajanustan Jr. logged in successfully.', 26),
(86, '07-28-2025 12:22:31 PM', 'Login', 'User Jhon Mark Montallana Pajanustan Jr. logged in successfully.', 26),
(87, '07-28-2025 10:15:45 PM', 'Login', 'Login attempt failed — username not found: admin', NULL),
(88, '07-28-2025 10:15:52 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(89, '07-28-2025 10:16:05 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(90, '07-28-2025 10:57:47 PM', 'Delete Cropping Seas', 'Clarck Dela Cruz deleted cropping season \"1st CROPPING\" (2026).', 1),
(91, '07-28-2025 10:58:44 PM', 'Add Cropping Season', 'Clarck Dela Cruz added a new cropping season: \"2nd CROPPING\" (2025) from 07-28-2025 to 07-30-2025.', 1),
(92, '07-28-2025 11:49:39 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"2nd CROPPING\" (2025) from 07-28-2025 to 07-30-2025.', 1),
(93, '07-29-2025 08:04:52 AM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(94, '07-30-2025 12:10:37 PM', 'Login', 'User Clarck Dela Cruz logged in successfully.', 1),
(95, '07-30-2025 12:11:17 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"2nd CROPPING\" (2025) from 07-28-2025 to 07-30-2025.', 1),
(96, '07-30-2025 12:11:47 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"2nd CROPPING\" (2025) from 07-28-2025 to 07-30-2026.', 1),
(97, '07-30-2025 12:12:15 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"2nd CROPPING\" (2026) from 07-28-2026 to 07-30-2026.', 1),
(98, '07-30-2025 12:16:42 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(99, '07-30-2025 12:43:36 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-18-2025.', 1),
(100, '07-30-2025 12:59:26 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(101, '07-30-2025 01:01:35 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-18-2025.', 1),
(102, '07-30-2025 01:01:49 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(103, '07-30-2025 01:05:00 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(104, '07-30-2025 01:05:59 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-29-2025.', 1),
(105, '07-30-2025 01:07:55 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(106, '07-30-2025 01:09:28 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(107, '07-30-2025 01:11:13 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(108, '07-30-2025 01:11:44 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(109, '07-30-2025 01:14:00 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(110, '07-30-2025 01:14:20 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(111, '07-30-2025 01:17:59 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(112, '07-30-2025 01:38:55 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(113, '07-30-2025 01:39:23 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(114, '07-30-2025 01:48:27 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(115, '07-30-2025 01:52:51 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-29-2025.', 1),
(116, '07-30-2025 01:55:06 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(117, '07-30-2025 01:56:32 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(118, '07-30-2025 01:57:32 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(119, '07-30-2025 02:10:20 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(120, '07-30-2025 02:17:45 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-29-2025.', 1),
(121, '07-30-2025 02:18:23 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(122, '07-30-2025 02:40:47 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(123, '07-30-2025 02:42:08 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(124, '07-30-2025 02:43:25 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(125, '07-30-2025 02:44:34 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(126, '07-30-2025 02:46:27 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(127, '07-30-2025 02:46:48 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(128, '07-30-2025 02:52:35 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1),
(129, '07-30-2025 02:52:54 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(130, '07-30-2025 02:53:13 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-29-2025.', 1),
(131, '07-30-2025 02:55:47 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-31-2025.', 1),
(132, '07-30-2025 03:21:57 PM', 'Update Cropping Seas', 'Clarck Dela Cruz updated cropping season: \"1st CROPPING\" (2025) from 07-16-2025 to 07-30-2025.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `post_description`
--

CREATE TABLE `post_description` (
  `post_description_tbl_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(22) NOT NULL,
  `updated_at` varchar(22) DEFAULT NULL,
  `users_tbl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_description`
--

INSERT INTO `post_description` (`post_description_tbl_id`, `description`, `created_at`, `updated_at`, `users_tbl_id`) VALUES
(10, 'Gwapo nga tawo inggisa niyo. Bas kam ni Elya pag kukuon hhahaahah. Loyal in hiya, dre maiyo, mabubot la hahahahaa. 😜🥰😝😝😚😋🤣', '07-14-2025 10:56:40 AM', NULL, 1),
(14, 'Acomplisment.', '07-14-2025 09:57:58 PM', NULL, 1),
(15, 'sdfsdfsd', '07-14-2025 11:16:49 PM', NULL, 1),
(17, 'sdss', '07-14-2025 11:18:45 PM', NULL, 1),
(18, 'dfgdfgdfg', '07-14-2025 11:22:34 PM', NULL, 1),
(20, 'Nono.\r\n	Nanaasdasd\r\nasdsdddddf frrttt edfff\r\nGrfgtg', '07-14-2025 11:23:12 PM', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `post_image`
--

CREATE TABLE `post_image` (
  `post_image_tbl_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `post_description_tbl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_image`
--

INSERT INTO `post_image` (`post_image_tbl_id`, `image_path`, `post_description_tbl_id`) VALUES
(3, 'uploads/images/1752346889_7b8a6325cd08e0035d44.jpg', 9),
(4, 'uploads/images/1752346889_0a1cbb08ed6a254eeea2.jpg', 9),
(5, 'uploads/images/1752346889_fdaf1df8d69c13eed020.jpg', 9),
(6, 'uploads/images/1752461800_12c442216943b6ce1014.jpg', 10),
(15, 'uploads/images/1752501478_beb51d1a22e0f36ab087.jpg', 14),
(16, 'uploads/images/1752501478_c6b6234c8fa60f495af8.jpg', 14),
(17, 'uploads/images/1752501478_2f57781ba23e64b8d263.jpg', 14);

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
(12, '07-07-2025 07:21:00 AM', NULL, NULL, 'Pending', 2, 9),
(13, '07-08-2025 11:16:54 PM', NULL, NULL, 'Pending', 10, 12),
(14, '07-08-2025 11:17:28 PM', '07-11-2025 01:07:58 AM', NULL, 'Approved', 2, 12),
(15, '07-14-2025 11:06:55 AM', '07-14-2025 11:11:08 AM', NULL, 'Approved', 10, 14),
(16, '07-14-2025 11:07:10 AM', '07-14-2025 11:08:49 AM', NULL, 'Approved', 2, 14),
(17, '07-14-2025 11:10:33 AM', '07-14-2025 11:10:59 AM', NULL, 'Approved', 13, 14);

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
(1, '645grtt', 'Cruz', 'Clarck', 'Dela', NULL, 'Male', '24', '2001-02-14', 1);

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
(1, '09122137558', 'clarckcruz25@gmail.com', 'admin123', '$2y$10$q9Ou9Imm8Nv9PUXsbtLHhegnOtDMdBjb.ZtmE6U2VAApztCXzHDEW', 'admin', 'active'),
(26, '09678343433', 'nimje032500@gmail.com', 'farmer123', '$2y$10$SQQgl0spEKP3XSINBXyiVODj672HlvEpCq7H8bWyeSzHwlbxFX5VO', 'farmer', 'active'),
(28, '09678343432', 'nimer3d@gmail.com', 'farmer1235', '$2y$10$okq9p/EhDdrO1D4uOz9..u348py5sxMlN9CjKjVK88QH6T6ptdXW2', 'farmer', 'active'),
(29, '09855858861', 'judevincentbejar@gmail.com', 'judevincent', '$2y$10$d4RQKkaMdwuEIB2x9iRSyOl1Tklvk68dlMdcBGg3h8zC8aYQqoduO', 'farmer', 'active');

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
-- Indexes for table `post_description`
--
ALTER TABLE `post_description`
  ADD PRIMARY KEY (`post_description_tbl_id`),
  ADD KEY `post_description_ibfk_1` (`users_tbl_id`);

--
-- Indexes for table `post_image`
--
ALTER TABLE `post_image`
  ADD PRIMARY KEY (`post_image_tbl_id`),
  ADD KEY `post_description_tbl_id` (`post_description_tbl_id`);

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
  MODIFY `beneficiaries_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `client_info`
--
ALTER TABLE `client_info`
  MODIFY `client_info_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cropping_season`
--
ALTER TABLE `cropping_season`
  MODIFY `cropping_season_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `logs_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `post_description`
--
ALTER TABLE `post_description`
  MODIFY `post_description_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `post_image`
--
ALTER TABLE `post_image`
  MODIFY `post_image_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `seed_requests`
--
ALTER TABLE `seed_requests`
  MODIFY `seed_requests_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `staff_info`
--
ALTER TABLE `staff_info`
  MODIFY `staff_info_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
-- Constraints for table `post_description`
--
ALTER TABLE `post_description`
  ADD CONSTRAINT `post_description_ibfk_1` FOREIGN KEY (`users_tbl_id`) REFERENCES `users` (`users_tbl_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_image`
--
ALTER TABLE `post_image`
  ADD CONSTRAINT `post_image_ibfk_1` FOREIGN KEY (`post_description_tbl_id`) REFERENCES `post_description` (`post_description_tbl_id`);

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
