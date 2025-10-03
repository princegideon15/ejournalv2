-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 12, 2025 at 01:38 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbej`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblage_group`
--

DROP TABLE IF EXISTS `tblage_group`;
CREATE TABLE IF NOT EXISTS `tblage_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `min_age` varchar(255) DEFAULT NULL,
  `max_age` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Create Time',
  `last_updated` datetime DEFAULT NULL COMMENT 'Update Time',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblage_group`
--

INSERT INTO `tblage_group` (`id`, `min_age`, `max_age`, `created_at`, `last_updated`) VALUES
(1, '70', '100', NULL, NULL),
(2, '65', '69', NULL, NULL),
(3, '60', '64', NULL, NULL),
(4, '55', '59', NULL, NULL),
(5, '50', '54', NULL, NULL),
(6, '45', '49', NULL, NULL),
(7, '40', '44', NULL, NULL),
(8, '35', '39', NULL, NULL),
(9, '30', '34', NULL, NULL),
(10, '25', '29', NULL, NULL),
(11, '20', '24', NULL, NULL),
(12, '1', '19', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcsf_arta`
--

DROP TABLE IF EXISTS `tblcsf_arta`;
CREATE TABLE IF NOT EXISTS `tblcsf_arta` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `arta_user_id` varchar(255) DEFAULT NULL,
  `arta_age` varchar(2) NOT NULL,
  `arta_sex` int(1) NOT NULL,
  `arta_region` int(2) NOT NULL,
  `arta_agency` varchar(64) NOT NULL DEFAULT 'NRCP',
  `arta_service` varchar(64) NOT NULL,
  `arta_ctype` int(11) NOT NULL,
  `arta_cc1` int(11) NOT NULL,
  `arta_cc2` int(11) NOT NULL,
  `arta_cc3` int(11) NOT NULL,
  `arta_sqd1` int(11) NOT NULL,
  `arta_sqd2` int(11) NOT NULL,
  `arta_sqd3` int(11) NOT NULL,
  `arta_sqd4` int(11) NOT NULL,
  `arta_sqd5` int(11) NOT NULL,
  `arta_sqd6` int(11) NOT NULL,
  `arta_sqd7` int(11) NOT NULL,
  `arta_sqd8` int(11) NOT NULL,
  `arta_suggestion` text NOT NULL,
  `arta_email` varchar(255) DEFAULT NULL,
  `arta_ref_code` char(16) NOT NULL,
  `arta_created_at` datetime DEFAULT NULL,
  `arta_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblcsf_cc1`
--

DROP TABLE IF EXISTS `tblcsf_cc1`;
CREATE TABLE IF NOT EXISTS `tblcsf_cc1` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `c1_desc` varchar(255) NOT NULL,
  `c1_value` varchar(255) DEFAULT NULL,
  `c1_created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcsf_cc1`
--

INSERT INTO `tblcsf_cc1` (`id`, `c1_desc`, `c1_value`, `c1_created_at`) VALUES
(1, 'I know what a CC is and I saw this office\'s CC.', '1', NULL),
(2, 'I know what a CC is but I did NOT see this office\'s CC.', '2', NULL),
(3, 'I learned of the CC only when I saw this office\'s CC.', '3', NULL),
(4, 'I do not know what a CC is and I did not see one in this office', '4', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcsf_cc2`
--

DROP TABLE IF EXISTS `tblcsf_cc2`;
CREATE TABLE IF NOT EXISTS `tblcsf_cc2` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `c2_desc` varchar(255) DEFAULT NULL,
  `c2_value` varchar(255) DEFAULT NULL,
  `c2_created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcsf_cc2`
--

INSERT INTO `tblcsf_cc2` (`id`, `c2_desc`, `c2_value`, `c2_created_at`) VALUES
(1, 'Easy to see', '1', NULL),
(2, 'Somewhat easy to see', '2', NULL),
(3, 'Difficult to see', '3', NULL),
(4, 'Not visible at all', '4', NULL),
(5, 'N/A', '5', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcsf_cc3`
--

DROP TABLE IF EXISTS `tblcsf_cc3`;
CREATE TABLE IF NOT EXISTS `tblcsf_cc3` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `c3_desc` varchar(255) DEFAULT NULL,
  `c3_value` varchar(255) DEFAULT NULL,
  `c3_created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcsf_cc3`
--

INSERT INTO `tblcsf_cc3` (`id`, `c3_desc`, `c3_value`, `c3_created_at`) VALUES
(1, 'Helped very much', '1', NULL),
(2, 'Somewhat helped', '2', NULL),
(3, 'Did not help', '3', NULL),
(4, 'N/A', '4', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcsf_client_type`
--

DROP TABLE IF EXISTS `tblcsf_client_type`;
CREATE TABLE IF NOT EXISTS `tblcsf_client_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `ctype_desc` varchar(255) DEFAULT NULL,
  `ctype_value` varchar(255) DEFAULT NULL,
  `ctype_created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcsf_client_type`
--

INSERT INTO `tblcsf_client_type` (`id`, `ctype_desc`, `ctype_value`, `ctype_created_at`) VALUES
(1, 'Citizen', '1', NULL),
(2, 'Business', '2', NULL),
(3, 'Government', '3', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcsf_sqd`
--

DROP TABLE IF EXISTS `tblcsf_sqd`;
CREATE TABLE IF NOT EXISTS `tblcsf_sqd` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `sqd_desc` varchar(255) DEFAULT NULL,
  `sqd_value` varchar(255) DEFAULT NULL,
  `sqd_created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcsf_sqd`
--

INSERT INTO `tblcsf_sqd` (`id`, `sqd_desc`, `sqd_value`, `sqd_created_at`) VALUES
(1, 'I am satisfied with the service that I availed.', '1', NULL),
(2, 'I spent a reasonable amount of time for my transaction.', '2', NULL),
(3, 'The office followed the transaction\'s requirements and steps based on the information provided.', '3', NULL),
(4, 'The steps (including payment) I needed to do for my transaction were easy and simple.', '4', NULL),
(5, 'I paid a reasonable amount of fees for my transacion.', '5', NULL),
(6, 'I feel the office was fair to everyone, or \"walang palakasan\", during my transaction.', '6', NULL),
(7, 'I was treated courteously by the staff, and (If asked for help) the staff was helpful.', '7', NULL),
(8, 'I got what i needed from the government office, or (if denied) denial of request was sufficiently explained to me.', '8', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcsf_uiux`
--

DROP TABLE IF EXISTS `tblcsf_uiux`;
CREATE TABLE IF NOT EXISTS `tblcsf_uiux` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `csf_user_id` varchar(255) NOT NULL,
  `csf_rate_ui` int(11) NOT NULL,
  `csf_ui_suggestions` varchar(300) DEFAULT NULL,
  `csf_rate_ux` int(11) NOT NULL,
  `csf_ux_suggestions` varchar(300) DEFAULT NULL,
  `csf_system` char(16) NOT NULL,
  `csf_created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=199 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcsf_uiux`
--

INSERT INTO `tblcsf_uiux` (`id`, `csf_user_id`, `csf_rate_ui`, `csf_ui_suggestions`, `csf_rate_ux`, `csf_ux_suggestions`, `csf_system`, `csf_created_at`) VALUES
(1, 'NRCP-EJ-2024-000001', 5, 'test', 0, '', 'eJournal Client', '2024-11-26 20:54:23'),
(2, 'NRCP-EJ-2024-000001', 5, 'test', 5, '', 'eJournal Client', '2024-11-26 20:54:47'),
(3, 'NRCP-EJ-2024-000001', 5, 'test asdf a', 5, 'sdfasdfadsfadsf adf asdf asdf', 'eJournal Client', '2024-11-26 20:55:03'),
(4, 'NRCP-EJ-2024-000001', 4, '', 3, '', 'eJournal Client', '2024-11-26 20:56:20'),
(5, 'NRCP-EJ-2024-000001', 4, '', 3, '', 'eJournal Client', '2024-11-26 20:56:48'),
(6, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-11-26 20:57:13'),
(7, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-11-26 20:57:18'),
(8, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-11-26 20:57:24'),
(9, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-11-26 20:57:27'),
(10, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-11-26 20:57:31'),
(11, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-11-26 20:57:39'),
(12, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-11-26 20:57:43'),
(13, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-11-26 20:57:51'),
(14, 'SA530', 4, '', 5, '', 'eJournal Client', '2024-11-26 20:58:44'),
(15, 'NRCP-EJ-2024-000001', 4, '', 5, '', 'eJournal Client', '2024-11-26 20:58:58'),
(16, 'NRCP-EJ-2024-000001', 4, '', 5, '', 'eJournal Client', '2024-11-26 20:59:02'),
(17, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-11-26 20:59:27'),
(18, 'NRCP-EJ-2024-000001', 0, '', 5, '', 'eJournal Client', '2024-11-26 21:02:55'),
(19, 'NRCP-EJ-2024-000001', 4, '', 5, '', 'eJournal Client', '2024-11-26 21:03:34'),
(20, 'NRCP-EJ-2024-000001', 4, '', 5, '', 'eJournal Client', '2024-11-26 21:04:12'),
(21, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-11-26 21:04:48'),
(22, 'NRCP-EJ-2024-000001', 4, 'df', 5, 'dsf', 'eJournal Client', '2024-11-29 21:46:26'),
(23, 'NRCP-EJ-2024-000001', 4, 'sdf', 5, '', 'eJournal Client', '2024-11-29 21:47:36'),
(24, 'NRCP-EJ-2024-000001', 4, '', 5, '', 'eJournal Client', '2024-11-29 21:55:34'),
(25, 'NRCP-EJ-2024-000001', 3, '', 5, '', 'eJournal Client', '2024-11-29 21:56:33'),
(26, 'NRCP-EJ-2024-000001', 3, '', 5, '', 'eJournal Client', '2024-11-29 21:58:15'),
(27, 'NRCP-EJ-2024-000001', 3, 'sdf', 5, '', 'eJournal Client', '2024-11-29 21:59:57'),
(28, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-11-29 22:01:43'),
(29, 'NRCP-EJ-2024-000001', 3, '', 4, '', 'eJournal Client', '2024-11-29 22:03:41'),
(30, 'NRCP-EJ-2024-000001', 3, '', 4, '', 'eJournal Client', '2024-11-29 22:04:29'),
(31, 'NRCP-EJ-2024-000001', 4, '', 4, '', 'eJournal Client', '2024-12-02 21:31:42'),
(32, 'NRCP-EJ-2024-000001', 5, 'fd', 0, 'sdfdf', 'eJournal Client', '2024-12-12 12:38:15'),
(33, 'NRCP-EJ-2024-000001', 0, '', 0, '', 'eJournal Client', '2024-12-12 18:25:00'),
(34, 'SA530', 5, 'dasd', 3, 'sad', 'eReview', '2024-12-13 20:45:17'),
(35, 'SA530', 4, '', 4, '', 'eReview', '2024-12-16 19:43:20'),
(36, '7', 4, 'test ejournal admin', 4, 'test ejournal admin', 'eReview', '2024-12-24 13:58:20'),
(37, '7', 4, '', 4, '', 'eReview', '2024-12-24 14:19:42'),
(38, '7', 5, 'test', 4, 'test', 'eJournal Admin', '2024-12-31 11:18:55'),
(39, '7', 5, '', 5, '', 'eReview', '2025-01-05 17:51:42'),
(40, '7', 4, '', 4, '', 'eReview', '2025-01-05 17:55:44'),
(41, '7', 4, '', 5, '', 'eReview', '2025-01-06 16:37:20'),
(42, '7', 5, '', 5, '', 'eReview', '2025-01-06 16:37:32'),
(43, '7', 5, 'sdfsadf', 5, '', 'eReview', '2025-01-06 20:48:17'),
(44, '7', 5, '', 5, '', 'eReview', '2025-01-06 21:22:55'),
(45, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eJournal Client', '2025-01-06 21:37:33'),
(46, 'NRCP-EJ-2024-000001', 5, 'sdf', 0, 'sdf', 'eJournal Client', '2025-01-06 22:04:10'),
(47, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-06 22:34:50'),
(48, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eJournal Client', '2025-01-06 23:04:48'),
(49, 'NRCP-EJ-2024-000001', 5, 'asdf', 5, '', 'eReview', '2025-01-08 20:16:53'),
(50, '7', 5, '', 5, '', 'eReview', '2025-01-08 20:24:16'),
(51, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-08 20:36:49'),
(52, '7', 5, '', 5, '', 'eReview', '2025-01-08 20:39:53'),
(53, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-09 16:19:04'),
(54, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-09 16:49:45'),
(55, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-09 16:57:14'),
(56, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-09 18:36:35'),
(57, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-09 21:03:04'),
(58, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-09 21:15:02'),
(59, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eJournal Client', '2025-01-11 20:17:14'),
(60, '7', 5, '', 5, '', 'eJournal Admin', '2025-01-11 20:30:28'),
(61, '7', 5, '', 5, '', 'eJournal Admin', '2025-01-13 11:52:07'),
(62, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-13 12:06:39'),
(63, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-13 21:36:26'),
(64, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-13 22:04:01'),
(65, '7', 5, '', 5, '', 'eReview', '2025-01-13 22:09:22'),
(66, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-13 22:39:10'),
(67, '7', 5, '', 5, '', 'eReview', '2025-01-13 22:45:46'),
(68, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-15 19:19:48'),
(69, '3', 5, '', 5, '', 'eReview', '2025-01-15 20:22:16'),
(70, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-15 20:26:04'),
(71, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-15 20:45:02'),
(72, '7', 5, '', 5, '', 'eReview', '2025-01-16 17:09:19'),
(73, '7', 5, '', 5, '', 'eReview', '2025-01-16 19:57:29'),
(74, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-16 21:35:53'),
(75, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-16 21:42:23'),
(76, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-16 21:42:37'),
(77, '7', 5, '', 5, '', 'eReview', '2025-01-17 17:09:04'),
(78, '7', 5, '', 5, '', 'eReview', '2025-01-17 17:10:26'),
(79, '7', 5, '', 5, '', 'eReview', '2025-01-17 17:20:42'),
(80, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-17 22:33:35'),
(81, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-17 22:39:08'),
(82, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-17 22:40:09'),
(83, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-17 22:42:36'),
(84, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-17 22:48:19'),
(85, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-18 12:57:41'),
(86, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-18 13:43:35'),
(87, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-18 13:56:10'),
(88, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-18 14:03:20'),
(89, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-18 15:12:02'),
(90, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-18 15:13:25'),
(91, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, 'adf', 'eReview', '2025-01-18 15:51:00'),
(92, 'SAec942f88fe9d7cf05d40c30b5dac4fca', 5, '', 5, '', 'eReview', '2025-01-19 17:25:16'),
(93, 'SAec942f88fe9d7cf05d40c30b5dac4fcf', 5, '', 5, '', 'eReview', '2025-01-19 20:07:31'),
(94, 'SAec942f88fe9d7cf05d40c30b5dac4fca', 5, '', 5, '', 'eReview', '2025-01-20 19:16:07'),
(95, 'SAec942f88fe9d7cf05d40c30b5dac4fcg', 5, '', 5, '', 'eReview', '2025-01-20 21:18:36'),
(96, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-21 16:43:21'),
(97, 'SAec942f88fe9d7cf05d40c30b5dac4fcg', 5, '', 5, '', 'eReview', '2025-01-21 21:03:09'),
(98, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-22 16:13:19'),
(99, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-22 16:23:47'),
(100, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-22 16:29:56'),
(101, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-22 16:45:31'),
(102, 'SAec942f88fe9d7cf05d40c30b5dac4fca', 5, '', 5, '', 'eReview', '2025-01-22 16:46:36'),
(103, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-22 16:48:23'),
(104, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-22 17:21:56'),
(105, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-22 18:19:19'),
(106, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-22 20:02:59'),
(107, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-25 20:21:06'),
(108, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-27 12:29:05'),
(109, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-27 22:31:39'),
(110, 'SAeeaff83f17b1e513a9abd4b6018968c9', 5, '', 5, '', 'eReview', '2025-01-28 20:15:36'),
(111, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-28 20:58:02'),
(112, 'SAeeaff83f17b1e513a9abd4b6018968d8', 5, '', 4, '', 'eReview', '2025-01-29 10:30:47'),
(113, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-29 15:35:36'),
(114, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-29 17:02:33'),
(115, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-29 17:17:12'),
(116, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-29 19:20:16'),
(117, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-29 19:34:22'),
(118, 'SAec942f88fe9d7cf05d40c30b5dac4fca', 5, '', 5, '', 'eReview', '2025-01-29 19:35:25'),
(119, 'SAec942f88fe9d7cf05d40c30b5dac4fca', 5, '', 5, '', 'eReview', '2025-01-29 19:49:00'),
(120, 'SAec942f88fe9d7cf05d40c30b5dac4fcf', 5, '', 5, '', 'eReview', '2025-01-29 20:12:16'),
(121, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-30 09:12:15'),
(122, '4854', 5, '', 5, '', 'eReview', '2025-01-30 09:33:44'),
(123, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-30 09:54:27'),
(124, 'SAeeaff83f17b1e513a9abd4b6018968c9', 5, '', 5, '', 'eReview', '2025-01-30 10:04:07'),
(125, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-30 10:14:33'),
(126, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-01-30 12:31:59'),
(127, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-30 12:52:20'),
(128, 'SAeeaff83f17b1e513a9abd4b6018968d8', 5, '', 5, '', 'eReview', '2025-01-30 13:01:18'),
(129, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-01-30 13:07:50'),
(130, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-01-30 15:10:03'),
(131, '1', 5, '', 5, '', 'eReview', '2025-02-01 07:41:16'),
(132, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-01 07:47:17'),
(133, '1', 5, '', 5, '', 'eReview', '2025-02-01 07:52:36'),
(134, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-01 08:14:36'),
(135, '1', 5, '', 5, '', 'eReview', '2025-02-02 09:18:01'),
(136, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-02 09:34:14'),
(137, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-02-02 10:08:35'),
(138, '1', 5, '', 5, '', 'eReview', '2025-02-02 12:43:30'),
(139, '1', 5, '', 5, '', 'eReview', '2025-02-02 12:46:09'),
(140, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-02-02 17:27:14'),
(141, 'SAec942f88fe9d7cf05d40c30b5dac4fca', 5, '', 5, '', 'eReview', '2025-02-02 17:29:06'),
(142, '1', 5, '', 5, '', 'eReview', '2025-02-02 17:31:33'),
(143, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-02 17:36:30'),
(144, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-02-02 17:39:46'),
(145, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eJournal Client', '2025-02-02 18:46:45'),
(146, '7', 5, '', 5, '', 'eReview', '2025-02-04 21:19:10'),
(147, '7', 5, '', 5, '', 'eReview', '2025-02-04 22:05:24'),
(148, 'NRCP-EJ-2024-000001', 4, '', 5, '', 'eJournal Client', '2025-02-05 08:15:45'),
(149, '7', 5, '', 5, '', 'eReview', '2025-02-05 08:17:29'),
(150, '7', 5, '', 5, '', 'eJournal Admin', '2025-02-05 09:01:48'),
(151, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-05 09:10:36'),
(152, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-02-05 09:14:13'),
(153, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-05 09:25:26'),
(154, 'R6a70d868a6daed0b4af3bae50b8508c2', 5, '', 5, '', 'eReview', '2025-02-05 09:30:31'),
(155, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-05 09:34:17'),
(156, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eReview', '2025-02-05 09:39:08'),
(157, '1', 5, '', 5, '', 'eReview', '2025-02-05 09:42:10'),
(158, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, 'sdf', 5, '', 'eReview', '2025-02-05 09:44:50'),
(159, 'SAeeaff83f17b1e513a9abd4b6018968d8', 5, '', 5, '', 'eReview', '2025-02-05 09:47:57'),
(160, '1', 5, '', 5, '', 'eReview', '2025-02-05 09:49:47'),
(161, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-02-05 09:52:58'),
(162, 'SAeeaff83f17b1e513a9abd4b6018968c9', 5, '', 5, '', 'eReview', '2025-02-05 09:53:53'),
(163, '7', 5, '', 5, '', 'eReview', '2025-02-05 09:58:31'),
(164, '7', 5, '', 5, '', 'eReview', '2025-02-05 19:36:00'),
(165, '1', 5, '', 5, '', 'eReview', '2025-02-05 19:44:29'),
(166, '7', 5, '', 5, '', 'eReview', '2025-02-08 09:23:33'),
(167, 'SAeeaff83f17b1e513a9abd4b6018968c9', 5, '', 5, '', 'eReview', '2025-02-08 09:29:33'),
(168, 'SA07a6cc39be3517c3f952423c057cf816', 5, '', 5, '', 'eReview', '2025-02-08 20:18:29'),
(169, 'SA07a6cc39be3517c3f952423c057cf816', 5, '', 5, '', 'eReview', '2025-02-08 20:30:47'),
(170, '7', 5, '', 5, '', 'eReview', '2025-02-10 21:17:47'),
(171, '7', 5, '', 5, '', 'eReview', '2025-02-10 21:19:16'),
(172, '7', 5, '', 5, '', 'eReview', '2025-02-10 21:35:17'),
(173, '1', 5, '', 5, '', 'eReview', '2025-02-11 12:58:35'),
(174, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-11 16:42:47'),
(175, '7', 5, '', 5, '', 'eReview', '2025-02-11 16:43:30'),
(176, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-02-11 16:44:47'),
(177, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-11 16:54:37'),
(178, '1', 5, '', 5, '', 'eReview', '2025-02-11 16:57:53'),
(179, '7', 5, '', 5, '', 'eReview', '2025-02-11 17:03:53'),
(180, '7', 5, '', 5, '', 'eReview', '2025-02-11 17:04:01'),
(181, '1102', 5, '', 5, '', 'eReview', '2025-02-11 17:11:21'),
(182, 'NRCP-EJ-2024-000001', 5, '', 5, '', 'eJournal Client', '2025-02-11 19:17:49'),
(183, '7', 5, '', 5, '', 'eJournal Admin', '2025-02-11 19:44:16'),
(184, '1', 5, '', 5, '', 'eReview', '2025-02-11 19:55:30'),
(185, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-11 20:14:22'),
(186, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-11 20:36:02'),
(187, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-02-11 20:37:48'),
(188, 'SAec942f88fe9d7cf05d40c30b5dac4fca', 5, '', 5, '', 'eReview', '2025-02-11 20:41:03'),
(189, 'SAec942f88fe9d7cf05d40c30b5dac4fcf', 5, '', 5, '', 'eReview', '2025-02-11 20:44:13'),
(190, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-11 21:09:52'),
(191, '1102', 5, '', 5, '', 'eReview', '2025-02-11 21:19:46'),
(192, 'SAeeaff83f17b1e513a9abd4b6018968b4', 5, '', 5, '', 'eReview', '2025-02-11 21:34:23'),
(193, 'SAeeaff83f17b1e513a9abd4b6018968c9', 5, '', 5, '', 'eReview', '2025-02-11 21:36:20'),
(194, 'SAeeaff83f17b1e513a9abd4b6018968c9', 5, '', 5, '', 'eReview', '2025-02-11 21:38:22'),
(195, '1', 5, '', 5, '', 'eReview', '2025-02-11 21:39:39'),
(196, 'SAec942f88fe9d7cf05d40c30b5dac4fce', 5, '', 5, '', 'eReview', '2025-02-11 22:06:09'),
(197, 'SAeeaff83f17b1e513a9abd4b6018968d8', 5, '', 5, '', 'eReview', '2025-02-11 22:10:34'),
(198, '1', 5, '', 5, '', 'eReview', '2025-02-11 22:15:31');

-- --------------------------------------------------------

--
-- Table structure for table `tbleditorial_policy`
--

DROP TABLE IF EXISTS `tbleditorial_policy`;
CREATE TABLE IF NOT EXISTS `tbleditorial_policy` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `ep_content` text DEFAULT NULL,
  `ep_file` varchar(100) DEFAULT NULL,
  `ep_is_archive` int(11) DEFAULT 0 COMMENT '0-not archived 1-archived',
  `created_at` varchar(24) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbleditorial_policy`
--

INSERT INTO `tbleditorial_policy` (`id`, `ep_content`, `ep_file`, `ep_is_archive`, `created_at`) VALUES
(1, '<h3 class=\"mb-5 text-center\" xss=removed>NRCP RESEARCH JOURNAL EDITORIAL POLICY</h3>\n<p>The NRCP Research Journal is the official journal of the National Research Council of the Philippines. All articles published therein are refereed and evaluated by the Editorial Board and external reviewers on overall quality, correctness, originality, clarity of presentation, and contribution to scientific knowledge. It is open to all the members of the Council who wish to have their research published. Both the author/s and the reviewers play significant roles in the efficiency and effectiveness of the editorial review process.</p>\n<h5 class=\"fw-bold\">Aim and Scope</h5>\n<p>The NRCP Research Journal publishes articles on topics across the thirteen (13) divisions of the Council, namely: Division I, Governmental, Educational and International Policies; Division II, Mathematical Sciences; Division III, Medical Sciences; Division IV, Pharmaceutical Sciences; Division V, Biological Sciences; Division VI, Agriculture and Forestry; Division VII, Engineering and Industrial Research; Division VIII, Social Sciences; Division IX, Physics; Division X, Chemical Sciences; Division XI, Humanities, Division XII, Earth and Space Sciences; and Division XIII, Veterinary Medicine.</p>\n<p>The journal aspires to become a valuable platform that nurtures cross-disciplinary research and collaboration which may lead to understanding and solving of complex challenges society faces. The NRCP Research Journal envisions itself to become a top-tier peer-reviewed open access multi-disciplinary journal that publishes rigorous and valuable research that broadly spans the entire spectrum of life, physical, earth, engineering, humanities, social and medical science, which contribute to basic, conceptual and practical scientific advancements including the translation of research to public policy.</p>\n<p>The NRCP Research Journal publishes two issues per year, with accepted papers uploaded immediately. Only unpublished papers and those that are not under consideration for publication in other journals are accepted and included in the NRCP Research Journal publication. The journal publishes articles in English or Filipino. For Filipino articles, authors must supply the English translation of the title and abstract, and Non-English references. All co-authors should have full knowledge and consent that the manuscript is submitted for publication in the NRCP Research Journal and all shall be kept informed of all correspondence about the submitted manuscript.</p>\n<h5 class=\"text-center fw-bold mt-5\">Types of Publications</h5>\n<h5 class=\"fw-bold\">Regular Research Articles</h5>\n<p>This publication type presents a comprehensive description of original research. Regular research articles do not have page limits, and a detailed account of the undertaken methodology in order to facilitate reproducibility is encouraged.</p>\n<h5 class=\"fw-bold\">Review Papers</h5>\n<p>Review papers are expected to provide a critical overview of the topic being reviewed and provide readers clear information on the current state of the topic. Submitted review paper must clearly demonstrate the rationale or importance of undertaking the review.</p>\n<h5 class=\"fw-bold\">Systematic Review and Meta-Analysis</h5>\n<p>Submitted systematic review and meta-analysis must follow PRISMA protocols. A detailed methodology on how the studies were pooled and analyzed is needed.</p>\n<h5 class=\"fw-bold\">Short communications</h5>\n<p>Short communications must only contain 4,000 words and include 3 images or tables.</p>\n<h5 class=\"fw-bold\">Microarticles</h5>\n<p>A microarticle is a very short research paper, typically no more than 2 pages long. It is designed to communicate a single, new, and significant scientific result in a concise and easy-to-understand way. Microarticles are often used to publish preliminary results, incremental yet significant result, describe new methods or instrumentation, or to report on failed experiments that provide new insights. Microarticles published by the NRCP Research Journal are subjected to the same peer review process.</p>\n<h5 class=\"fw-bold\">Perspective</h5>\n<p>Perspective articles are invited contributions from experts and top researchers in their respective disciplines. Perspective articles focus on providing a critical analysis or interpretation of existing research, or on discussing the implications of research for policy or practice.</p>\n<h5 class=\"fw-bold\">Case Studies</h5>\n<p>A case study is an in-depth, detailed examination of particular case/s within a real-world context.</p>\n<h5 class=\"fw-bold\">Clinical case reports</h5>\n<p>A clinical case report is a detailed description of a single patient or group of patients with a particular condition or disease.</p>\n<h5 class=\"fw-bold\">Policy briefs</h5>\n<p>A policy brief serves as a succinct overview of a specific problem, providing insights into potential policy options and offering recommendations for the most effective course of action. The brief typically includes a concise analysis of the issue at hand, explores available policy alternatives, and concludes with recommendations designed to guide policymakers toward optimal solutions.</p>\n<h5 class=\"fw-bold\">Book reviews</h5>\n<p>Book reviews are concise evaluations of new books or new editions of previously published books. Reviews of Filipino books are highly encouraged.</p>\n<p>In each issue, an editor’s choice article will be selected. Authors of the selected paper will receive a certificate of recognition from the NRCP.</p>\n<h5 class=\"fw-bold\">Editorial Criteria</h5>\n<p>All articles published by the NRCP Research Journal are reviewed. The main criterion for publication is academic rigor and technical soundness. All articles must be written using clear, concise, and inclusive language. Poorly written manuscripts will be returned to the authors and will not be sent for peer review.</p>\n<h5 class=\"fw-bold\">Editorial Process</h5>\n<p>Upon receipt of the submitted manuscript, the Managing Editor of the NRCP Research Journal will perform preliminary review which includes checking for plagiarism, language. Manuscripts that passed the preliminary review will be forwarded to the EIC, which will then delegate the manuscript to the suitable Associate Editor (AE). The AE will then initiate the peer-review process (at least 2-3 reviewers). Revision shall be completed based on the reviewers’ recommendations within the period allotted. Based on the revisions made and recommendations of the reviewers, the AE will make a decision. The EIC will formalize the decision.</p>\n<h5 class=\"text-center fw-bold mt-5\">Detailed Peer Review Process</h5>\n<p class=\"text-decoration-underline mb-5\">The refereeing system shall adopt a three-stage evaluation process:</p>\n<ul>\n<ul>\n<li class=\"h5 fw-bold\">Initial Asssessment</li>\n</ul>\n</ul>\n<p>Upon arrival, each submitted manuscript undergoes a thorough evaluation by the Publication Team. This initial assessment encompasses a plagiarism check and aims to align the manuscript with the journal\'s objectives and standards. It evaluates the paper\'s importance, technical robustness, and originality (ensuring a similarity index below 10%). The outcome of this evaluation determines if the manuscript proceeds to external review or requires revisions from the author/s. Authors are allotted two to four weeks for revisions, contingent upon the extent and urgency of the needed modifications.</p>\n<ul>\n<ul>\n<li class=\"h5 fw-bold\">Delegating to an Associate Editor</li>\n</ul>\n</ul>\n<p>Manuscripts that meet basic criteria set by the journal will then be forwarded to the EIC. At this point, the EIC can desk reject the manuscript if grave deficiencies have been found. If not, the manuscript will be forwarded to an Associate Editor for assessment. The Associate Editor may likewise desk reject the manuscript. The AE has the option to delegate the manuscript to any of the editorial board member whose expertise is aligned with the topic of the manuscript or initiate peer-review.</p>\n<ul>\n<ul>\n<li class=\"h5 fw-bold\">Recruiting Referees</li>\n</ul>\n</ul>\n<p>The task of selecting reviewers lies with the Editorial Board of the NRCP Research Journal. When a manuscript comes in, an editor solicits reviews from the NRCP pool of experts to review the manuscript. The identities of the reviewers are kept unknown to authors and vice versa. Members of the editorial board can act as reviewers provided that they have no conflict of interest with the manuscript under consideration.</p>\n<ul>\n<ul>\n<li class=\"h5 fw-bold\">External Review [Double Blind]</li>\n</ul>\n</ul>\n<p>The refined manuscripts, following an initial assessment, will undergo double-blind refereeing, involving a minimum of two external reviewers. Submissions must include a completed and signed commitment form.</p>\n<p>The external reviewers will have a month to assess the manuscripts, providing consolidated feedback and suggestions to the authors. Authors will then be afforded a period of two (2) weeks to a maximum of one month to revise and resubmit the manuscripts in response to the received feedback.</p>\n<h5 class=\"mt-5 fw-bold\">Editorial Review</h5>\n<p>The revised manuscript will undergo a conclusive review by the handling editor and EIC, ensuring the integration of external reviewers\' recommendations. The acceptance of the proposed article for publication hinges upon this process.</p>\n<p>Upon acceptance, papers undergo language editing by the copy editor. Authors receive proofs for final inspection, expected to respond within a week. Failure to provide feedback within this period implies acceptance of proofs without corrections.</p>\n<p>Once the camera-ready copy of the journal volume is prepared, it will be sent to the Web-Editor for online publication of the new journal issue.</p>\n<h5 class=\"h5 fw-bold\">Communications</h5>\n<p>All communications related to the NRCP Research Journal should be sent to this email address nrcp.ejournal@gmail.com.</p>\n<h5 class=\"h5 fw-bold\">Open Access Policy</h5>\n<p>All communications related to the NRCP Research Journal should be sent to this email address nrcp.ejournal@gmail.com.</p>\n<h5 class=\"h5 fw-bold\">Copyright Policy</h5>\n<p>The Editorial Board of the NRCP Research Journal follows the Creative Commons Attribution 4.0 International License that governs all journal articles and allows the unrestricted use, distribution, and reproduction in any format, even for commercial purposes, provided that the original work is properly cited.</p>\n<h5 class=\"h5 fw-bold\">Retraction Policy</h5>\n<p>The Editorial Board of the NRCP Research Journal shall remove a published article from the digital file as a result of the post-publication discovery of fraudulent research claims, plagiarism, or serious methodological errors that eluded detection during the quality assurance process.</p>\n<p>Validated complaints by third party researcher/s on any of the grounds mentioned shall initiate retraction process but only after the writer has been notified and allowed to present his side in compliance to due process.</p>\n<p>Upon detecting plagiarism, the NRCP Editorial Board will act according to the specific type identified. Unless otherwise determined during the investigation, all authors share both individual and collective responsibility for the content of a plagiarized paper. The Board prioritizes the investigation and resolution of each plagiarism claim with utmost importance, ensuring appropriate action is taken.</p>\n<h5 class=\"h5 fw-bold\">Digital Archiving and Preservation Policy</h5>\n<p>The NRCP Research Journal is electronically made available and accessible through an online platform known as eJournal with URL <a href=\"https://researchjournal.nrcp.dost.gov.ph/\" target=\"_blank\" rel=\"noopener\">https://researchjournal.nrcp.dost.gov.ph</a>. The primary objective of this initiative is to digitally preserve the contents and ensure long-term and perpetual access to the information of the NRCP Research Journal.</p>\n<p>The NRCP Research Journal is indexed in C&E Adaptive Learning Solutions’ Philippine eJournals and all articles are made accessible globally through a single Web-based platform with URL <a href=\"https://ejournals.ph/index.p\" target=\"_blank\" rel=\"noopener\">https://ejournals.ph/index.p</a>.</p>\n<ul>\n<ul>\n<li class=\"h5 fw-bold\">Website (electronic) Archiving</li>\n</ul>\n</ul>\n<p>The electronic contents of the NRCP Research Journal shall implement the 3-2-1 backup strategy, i.e., three (3) copies of the data: retaining one (1) copy in the production server; two (2) copies which will be distributed to two (2) media – one (1) copy for the local Network Attached Storage machine and one (1) for offsite location for recovery purposes of the production server in the event of unwanted catastrophe.</p>\n<ul>\n<ul>\n<li class=\"h5 fw-bold\">Abstracting/Indexing Services</li>\n</ul>\n</ul>\n<p>The electronic contents of the NRCP Research Journal shall implement the 3-2-1 backup strategy, i.e., three (3) copies of the data: retaining one (1) copy in the production server; two (2) copies which will be distributed to two (2) media – one (1) copy for the local Network Attached Storage machine and one (1) for offsite location for recovery purposes of the production server in the event of unwanted catastrophe.</p>\n<ul>\n<ul>\n<li class=\"h5 fw-bold\">Self-archiving</li>\n</ul>\n</ul>\n<p>The NRCP holds the copyright of the NRCP Research Journal and shares it with the authors of the published research articles. The authors can archive the final published version of their articles in their personal repositories immediately after publication.</p>\n<h5 class=\"fw-bold mt-5 mb-3\">Policy on Handling Complaints</h5>\n<ol>\n<ol>\n<li>The Editorial Board of the NRCP Research Journal believes that complaints are expressions of dissatisfaction to the services provided to the authors and to the public, who are the readers of the journal articles.</li>\n<li>The Editorial Board assures that the response to complaints is prompt, polite, appropriate, and confidential.</li>\n<li>The Editorial Board uses complaints as learning experience to improve the services and processes of publication of the NRCP Research Journal.</li>\n<li>The Editorial Board has the right to initiate investigation of any complaint related to infringement of copyright or other intellectual property rights, accuracy of data, libelous or otherwise materials.</li>\n</ol>\n</ol>\n<p class=\"text-decoration-underline\">Factors considered iun initiating investigation:</p>\n<ol>\n<ul>\n<li>receipt of a formal complaint with supporting documents substantiating the claims reflecting sufficient and well-founded evidence or grounds of complaint/s</li>\n<li>infringement of copyright and intellectual property right</li>\n<li>libelous statements</li>\n</ul>\n<li>If found guilty after investigation, the article shall be subject to retraction policy.</li>\n</ol>\n<h5 class=\"h5 fw-bold\">Use of Human Subjects in Research Policy</h5>\n<p>The Editorial Board of the NRCP Research Journal will only publish research articles involving human subjects after the authors have confirmed and submitted proof of their compliance with all laws and regulations regarding the protections granted to human subjects in research studies within the jurisdiction where the research study was conducted.</p>\n<p>The Editorial Board adheres to the prohibition of techniques or situations that may or will coerce human subjects into taking part in research studies undermining the voluntary nature of the subjects\' participation.</p>\n<h5 class=\"h5 fw-bold\">Conflicts of interest / Competing interests Policy</h5>\n<p>The author/s including the corresponding author, upon submission of the manuscript, shall declare conflict of interest with any of the members of the Editorial Board of the NRCP Research Journal whether financial or not, professional or personal. Failure to disclose may cause immediate rejection of the manuscript.</p>\n<p>If an undisclosed conflict of interest is discovered after the publication, the Editorial Board will take action in accordance with COPE (Committee on Publication Ethics) guideline and issue a public notification to the community.</p>\n<p><a href=\"https://publicationethics.org/\" target=\"_blank\" rel=\"noopener\">https://publicationethics.org/</a></p>\n<h5 class=\"h5 fw-bold\">Publication Ethics and Malpractice</h5>\n<p>The NRCP Research Journal is committed to uphold the highest standards of publication ethics and takes all possible measures against any publication malpractice.</p>\n<p> </p>\n<p>To prevent any actual or potential conflict of interest between and among the members of the Editorial Board, review personnel, and submitted manuscript, NRCP Research Journal is also committed to have an objective and fair double- blind peer- review of all submitted manuscripts for publication.</p>\n<p>Reviewers and editors are expected to provide responsible, constructive, and prompt evaluation of submitted research papers based on the significance of their contribution and in the rigors of analysis and presentation. Publication of articles is free of charge. It can be accessed online through this link <a href=\"https://skms.nrcp.dost.gov.ph/\" target=\"_blank\" rel=\"noopener\">https://skms.nrcp.dost.gov.ph</a>.</p>\n<h5 class=\"h5 fw-bold\">Authorship and Co-authorship</h5>\n<p>All authors must collectively review and endorse the submitted manuscript. Each author must attest to their substantial contributions, encompassing various aspects such as study conceptualization, data acquisition, analysis, manuscript drafting, or revisions.</p>\n<p>Collectively, authors are accountable for the study\'s validity, integrity, and impartiality. Authors with financial ties to a commercial entity relevant to the manuscript\'s subject must disclose these relationships in the acknowledgment section, especially if the work involves products or services of that organization or its competitors.</p>\n<p>Authors retain the rights to create print copies for personal scientific or academic use, reuse figures and tables in subsequent articles or works, and post a single PDF on their educational website post-publication. Any potential disputes among authors and institutions represented shall be settled and managed by the authors and should not involve NRCP.</p>\n<p>Author contribution must be clearly stated at the end of the publication.</p>\n<h5 class=\"h5 fw-bold\">Originality and Plagiarism</h5>\n<p>The submission (pertaining to research, notes, brief communication, and review) shall be an original work and must be a result of recently concluded research (preferably funded by the NRCP). The submission must be neither previously nor simultaneously submitted to any journal nor published elsewhere except in a preliminary form.</p>\n<p>The article shall state when and where the study was conducted. If the paper has been published as part of the proceedings of a convention, the paper submitted to the NRCP Research Journal must be substantially different from the one published in the said proceedings.</p>\n<p>Plagiarism will be checked initially using Turnitin, a reliable detection application and by the reviewers.</p>\n<h5 class=\"h5 fw-bold\">Ethical Oversight</h5>\n<p>The authors must disclose if research conducted involved human and/or animal subjects, bioprospecting and confidential data and business/marketing practices. The authors must provide the necessary supporting documents declaring permission granted for ethical conduct of the research should this be required by the reviewers and/or the NRCP e-journal managing team. Failure to secure required certification and/or permit may result in an immediate rejection of the manuscript. Any potential disputes that may arise from this oversight shall be settled and managed by the authors and should not involve NRCP.</p>\n<h5 class=\"h5 fw-bold\">Policy on the Use of Generative AI</h5>\n<p>The NRCP Research Journal recognizes the potential of generative AI in improving scholarly communication and discourse. However, extreme caution must be exercised whenever generative AI is used in manuscript preparation. Thus, authors must declare whenever AI was used in manuscript preparation and explicitly state that they have reviewed the accuracy of the generated information.</p>\n<p> </p>', '1736594313_editorial_policy', 1, '2025-01-11 19:18:33'),
(6, NULL, '1736596580_editorial_policy', 0, '2025-01-11 19:56:20'),
(5, NULL, '1736595617_editorial_policy', 1, '2025-01-11 19:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `tblguidelines`
--

DROP TABLE IF EXISTS `tblguidelines`;
CREATE TABLE IF NOT EXISTS `tblguidelines` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `gd_content` text DEFAULT NULL,
  `last_updated` varchar(24) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblguidelines`
--

INSERT INTO `tblguidelines` (`id`, `gd_content`, `last_updated`) VALUES
(1, '<h3 xss=removed>SUBMISSION OF MANUSCRIPTS</h3>\n<ol xss=removed>\n<li xss=removed><span xss=removed>ONLINE SUBMISSION</span>\n<ol xss=removed type=\"a\">\n<li xss=removed>Login to your Author account by clicking the \"Start Submission\" button</li>\n<li xss=removed>Click Upload manuscript</li>\n<li xss=removed>Provide/upload the necessary data/information</li>\n<li xss=removed>Click proceed</li>\n</ol>\n</li>\n<li xss=removed>The manuscript shall be submitted both in Microsoft Word and PDF document format.</li>\n<li xss=removed>The text and format of the manuscript shall adhere to the style and bibliographic requirements outlined in the Instructions to Authors.</li>\n<li xss=removed>The submission of the manuscript for publication in the NRCP Research Journal implies that it has not been published and/or has not been considered for publication by other journals.</li>\n<li xss=removed>Once the manuscript is accepted for publication, the authors shall agree that the same will no longer be submitted elsewhere.</li>\n</ol>\n<h3 xss=removed>GENERAL INSTRUCTIONS TO AUTHORS</h3>\n<ol xss=removed>\n<li xss=removed>Submit manuscripts in word and pdf format as required by the online form. This includes an anonymized copy of the manuscript for distribution to referees (pdf format).</li>\n<li xss=removed>\n<h6 xss=removed>Title page shall include:</h6>\n<ul xss=removed>\n<li xss=removed>TITLE of the Manuscript</li>\n<li xss=removed>AUTHORS, full names, institutional and email addresses of the authors. An asterisk shall be affixed to corresponding author’s name</li>\n<li xss=removed>KEYWORDS, three to ten keywords shall also be included, representing the main content of the manuscript.</li>\n<li xss=removed>ABSTRACT, a single paragraph with at least 150-words summarizing the content of the manuscript. It shall not contain bibliographic citations unless otherwise fully specified.</li>\n</ul>\n</li>\n<li xss=removed>\n<h6 xss=removed>FORMAT</h6>\n<table class=\"table table-bordered\" xss=removed>\n<tbody xss=removed>\n<tr xss=removed>\n<th xss=removed>Number of Words</th>\n<td xss=removed>Minimum of 5000 and maximum 6000 words</td>\n</tr>\n<tr xss=removed>\n<th xss=removed>Title of Manuscript</th>\n<td xss=removed>\n<ul xss=removed>\n<li xss=removed>Alignment: Center</li>\n<li xss=removed>Font Style: Cambria, Bold, All Caps</li>\n<li xss=removed>Font Size: 14</li>\n</ul>\n</td>\n</tr>\n<tr xss=removed>\n<th xss=removed>Author’s Name</th>\n<td xss=removed>\n<ul xss=removed>\n<li xss=removed>Alignment: Center</li>\n<li xss=removed>Font Style: Cambria, Bold, Sentence Case</li>\n<li xss=removed>Font Size: 12</li>\n<li xss=removed>Line Spacing: 1.5</li>\n</ul>\n</td>\n</tr>\n<tr xss=removed>\n<th xss=removed>Affiliations and Addresses</th>\n<td xss=removed>\n<ul xss=removed>\n<li xss=removed>Alignment: Center</li>\n<li xss=removed>Font Style: Cambria, Regular, Sentence Case</li>\n<li xss=removed>Font Size: 9</li>\n<li xss=removed>Line Spacing: 1.5</li>\n</ul>\n</td>\n</tr>\n<tr xss=removed>\n<th xss=removed>Section Headings</th>\n<td xss=removed>\n<ul xss=removed>\n<li xss=removed>Alignment: Center</li>\n<li xss=removed>Font Style: Cambria, Bold, All Caps</li>\n<li xss=removed>Font Size: 11</li>\n<li xss=removed>Line Spacing: 1.5</li>\n</ul>\n</td>\n</tr>\n<tr xss=removed>\n<th xss=removed>Subheadings</th>\n<td xss=removed>\n<ul xss=removed>\n<li xss=removed>Alignment: Flash Left</li>\n<li xss=removed>Font Style: Cambria, Bold, Sentence Case</li>\n<li xss=removed>Font Size: 11</li>\n<li xss=removed>Line Spacing: 1.5</li>\n</ul>\n</td>\n</tr>\n<tr xss=removed>\n<th xss=removed>Manuscript</th>\n<td xss=removed>\n<ul xss=removed>\n<li xss=removed>Alignment: Justified, No Indention</li>\n<li xss=removed>Font Style: Cambria</li>\n<li xss=removed>Font Size: 11</li>\n<li xss=removed>Line Spacing: 1.5, one blank line separating paragraphs</li>\n</ul>\n</td>\n</tr>\n<tr xss=removed>\n<th xss=removed>PAPER SIZE</th>\n<td xss=removed>7 X 10 inches / 177 x 254 mm</td>\n</tr>\n<tr xss=removed>\n<th xss=removed>LEFT MARGIN</th>\n<td xss=removed>1.0 inch / 2.54 cm</td>\n</tr>\n<tr xss=removed>\n<th xss=removed>RIGHT MARGIN</th>\n<td xss=removed>0.5 inch / 1.27 cm</td>\n</tr>\n<tr xss=removed>\n<th xss=removed>TOP/BOTTOM MARGIN</th>\n<td xss=removed>0.787 inch / 2 cm</td>\n</tr>\n</tbody>\n</table>\n</li>\n<li xss=removed>Tables and figures shall follow the APA 7th Edition and must be properly captioned, numbered, and placed at the center of the pages. The titles of the tables and figures shall have a font size 10, bold, and in sentence case.</li>\n<li xss=removed>The references shall also follow the APA Style, 7th Edition with a font size of 10, regular, and in sentence case.</li>\n<li xss=removed>The accomplished Author Agreement Form is a required attachment of the submitted manuscript.</li>\n<li xss=removed>The Acknowledgements shall be placed under a separate heading before the References.</li>\n<li xss=removed>For other details regarding manuscript submission, please email: <a class=\"main-link\" xss=removed href=\"mailto:ejournal@nrcp.dost.gov.ph\">ejournal@nrcp.dost.gov.ph</a></li>\n<li xss=removed>To track the status of submissions, log in to SKMS Account.</li>\n<li xss=removed>For studies with multiple authors, accounting for material contributions is needed. (e.g., A, did the literature review; B, undertook the analysis, etc.)</li>\n</ol>', '2025-01-11 22:17:55');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
