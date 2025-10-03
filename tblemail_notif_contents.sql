-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 13, 2025 at 12:58 PM
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
-- Table structure for table `tblemail_notif_contents`
--

DROP TABLE IF EXISTS `tblemail_notif_contents`;
CREATE TABLE IF NOT EXISTS `tblemail_notif_contents` (
  `row_id` int(11) NOT NULL AUTO_INCREMENT,
  `enc_process_id` int(11) NOT NULL,
  `enc_description` text NOT NULL,
  `enc_subject` text NOT NULL,
  `enc_cc` text DEFAULT NULL,
  `enc_bcc` text DEFAULT NULL,
  `enc_content` text NOT NULL,
  `enc_user_group` varchar(64) NOT NULL,
  `last_updated` datetime NOT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblemail_notif_contents`
--

INSERT INTO `tblemail_notif_contents` (`row_id`, `enc_process_id`, `enc_description`, `enc_subject`, `enc_cc`, `enc_bcc`, `enc_content`, `enc_user_group`, `last_updated`) VALUES
(1, 1, 'Notify author when client cited his/her article ', 'NRCP Journal Citation', NULL, 'gerard_balde@yahoo.com', '<div>Dear <strong>[FULL NAME],<br><br></strong>This is to inform you that your research article entitled <strong>[ARTICLE] </strong>was cited by:<br><br>Name : <strong>[NAME] [MEMBER] <br></strong>\n<div>Affiliation : <strong>[AFFILIATION]</strong></div>\n<div>Country : <strong>[COUNTRY]</strong></div>\nEmail : <strong>[EMAIL]</strong></div>\n<div>Source : <strong>[LINK]</strong><strong><br></strong></div>\n<br><br>Sincerely,<br><br>NRCP Research Journal<br><br><br>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **<br>\n<div> </div>', '3', '2025-01-10 21:24:30'),
(2, 2, 'Notify author when client downloaded his/her article', 'NRCP Article Downloaded', NULL, 'gerard_balde@yahoo.com', '<div>Dear <strong>[FULL NAME]</strong>,<br><br></div>\n<div>This is to inform you that your research article entitled <strong>[ARTICLE] </strong>was downloaded by:  </div>\n<div> </div>\n<div>Name : <strong>[NAME] [MEMBER]</strong></div>\n<div>Affiliation : <strong>[AFFILIATION]</strong></div>\n<div>Country : <strong>[COUNTRY]</strong></div>\n<div>Purpose : <strong>[PURPOSE]</strong></div>\n<div>Source : <strong>[LINK]</strong></div>\n<div><br><br>Sincerely,<br><br>NRCP Research Journal<br><br><br>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</div>\n<div> </div>', '3', '2023-02-07 09:53:04'),
(3, 3, 'Client downloaded full text', 'Downloaded Full Text PDF', NULL, 'gerard_balde@yahoo.com', '<div>[CLIENT_TITLE] [CLIENT_NAME]</div>\n<div>[CLIENT_AFFILIATION]</div>\n<div>[CLIENT_COUNTRY]</div>\n<div><br>Good day!,</div>\n<div> </div>\n<div>Thank you for providing your information. Please, see the attached article you have requested.</div>\n<div><br>Title : <strong>[TITLE]</strong></div>\n<div>Author : <strong>[AUTHOR]</strong></div>\n<div>Affiliation : <strong>[AFFILIATION]</strong></div>\n<div>Email : <strong>[EMAIL]</strong></div>\n<div>Filename : <strong>[FILE]</strong></div>\n<div> </div>\n<div><strong>To improve our services please leave your comments and feedback.<br><br>[LINK]</strong></div>\n<div><br><br>Sincerely,<br><br>NRCP Research Journal<br><br><br>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</div>\n<div> </div>\n<div> </div>', '3', '2023-02-07 09:53:23'),
(11, 4, 'Client cited an an article', 'Article Citation', NULL, 'gerard_balde@yahoo.com', '<div>\n<div>[CLIENT_TITLE] [CLIENT_NAME]</div>\n<div>[CLIENT_AFFILIATION]</div>\n<div>[CLIENT_COUNTRY]</div>\n<div><br>Good day!,</div>\n<div> </div>\n</div>\n<div>Thank you for providing your information. Provided below is the copy of citation in APA format.</div>\n<div><br><strong>[CITATION]</strong></div>\n<div> </div>\n<div><strong>To improve our services please leave your comments and feedback.<br><br>[LINK]</strong></div>\n<div><br><br>Sincerely,<br><br>NRCP Research Journal<br><br><br>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</div>\n<div> </div>\n<div> </div>', '3', '2023-02-07 09:53:16'),
(12, 5, 'Client downloaded an article', 'NRCP Feedback', NULL, 'gerard_balde@yahoo.com', '<div>\n<div>Dear valued client,</div>\n<div> </div>\n</div>\n<div>Thank you for downloading the \"<strong>[TITLE]</strong>\". To improve our service may we get your feedback by clicking on this [LINK] link.</div>\n<div> </div>\n<div>Sincerely,<br><br>NRCP Research Journal<br><br><br>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</div>\n<div> </div>\n<div> </div>', '3', '2024-11-29 19:21:40'),
(13, 6, 'Notify client for an unsbumitted csf arta after 1 month ', 'NRCP Feedback', NULL, 'gerard_balde@yahoo.com', '<div>\n<div>Dear valued client,</div>\n<div> </div>\n</div>\n<div>Thank you for downloading the \"<strong>[TITLE]</strong>\". To improve our service may we get your feedback by clicking on this [LINK] link.</div>\n<div> </div>\n<div>Sincerely,<br><br>NRCP Research Journal<br><br><br>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</div>\n<div> </div>\n<div> </div>', '3', '2024-11-30 11:20:26');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
