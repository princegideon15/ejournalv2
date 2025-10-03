-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 12, 2025 at 01:15 PM
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
-- Database: `dboprs`
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
-- Table structure for table `tblconsolidations`
--

DROP TABLE IF EXISTS `tblconsolidations`;
CREATE TABLE IF NOT EXISTS `tblconsolidations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `cons_man_id` int(11) NOT NULL,
  `cons_usr_id` varchar(255) NOT NULL,
  `cons_file` varchar(255) NOT NULL,
  `cons_status` int(11) DEFAULT NULL COMMENT '1-revision 2-no revision',
  `cons_remarks` text DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbleditors_review`
--

DROP TABLE IF EXISTS `tbleditors_review`;
CREATE TABLE IF NOT EXISTS `tbleditors_review` (
  `row_id` int(11) NOT NULL AUTO_INCREMENT,
  `edit_man_id` int(11) NOT NULL,
  `edit_usr_id` varchar(255) NOT NULL,
  `edit_file` text DEFAULT NULL,
  `edit_remarks` text DEFAULT NULL,
  `edit_status` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4;

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
  `enc_process_owner` int(2) DEFAULT NULL,
  `enc_target_user` int(2) DEFAULT NULL,
  `enc_cc` text DEFAULT NULL,
  `enc_bcc` text DEFAULT NULL,
  `enc_content` text NOT NULL,
  `enc_user_group` varchar(64) NOT NULL,
  `enc_process_duration` varchar(16) DEFAULT NULL,
  `last_updated` datetime NOT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblemail_notif_contents`
--

INSERT INTO `tblemail_notif_contents` (`row_id`, `enc_process_id`, `enc_description`, `enc_subject`, `enc_process_owner`, `enc_target_user`, `enc_cc`, `enc_bcc`, `enc_content`, `enc_user_group`, `enc_process_duration`, `last_updated`) VALUES
(1, 1, 'Manuscript submission', 'NRCP Journal Publication : Acknowledgement of Manuscript Submission', 0, 1, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>[TITLE] </strong><strong>[FULL NAME],</strong><br><br>Thank you for submitting your manuscript. This will be processed and subjected to the next step.<br><br>You can track the status of your submission by logging in your account.<br><br>Click this link to login : <strong>[LINK]<br><br></strong>Should you have any further questions or require assistance, please do not hesitate to send us an email.\n<p>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph</p>\n<br>Best regards,<br><br>NRCP Online Peer Review System<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '0', '2025-01-29 18:58:36'),
(2, 2, 'Regret to inform about the failure to meet the criteria', 'NRCP Journal Publication : Technical Desk Editor Review', 5, 1, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear&nbsp;<strong>[TITLE] </strong><strong>[FULL NAME],</strong><br><br><br>Thank you for submitting your manuscript titled \"<strong>[MANUSCRIPT]</strong>\". We greatly appreciate your interest in sharing your valuable research with our publication.\n<p>After careful review, we regret to inform you that your submission does not meet the criteria required for publication in our journal at this time.</p>\n<p>We value the effort and dedication that went into your work and encourage you to consider revising your manuscript or submitting it to a journal better aligned with its focus. If you\'d like, we can provide additional feedback from our reviewers that might help you in this process.<br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph</p>\n<br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3', '1', '2025-01-24 21:13:04'),
(3, 3, 'Endorse tech review results to EIC', 'NRCP Journal Publication : Technical Desk Editor Revew Results', 5, 6, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>Editor-in-Chief,<br></strong><br>This is to inform you that the technical review for the manuscript \"<strong>[MANUSCRIPT]</strong>\" has been completed.\n<p>Click this link to login : <strong>[LINK]<br><br></strong>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph</p>\n<br><br>Best regards,<br><br>NRCP Online Peer Review System<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '7', '2025-01-29 19:18:16'),
(4, 4, 'Inform the author of the non-acceptance', 'NRCP Journal Publication : Notification of Non-acceptance', 6, 1, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear<strong> [TITLE] [FULL NAME],<br><br></strong><br>Thank you for submitting your manuscript, \"<strong>[MANUSCRIPT]</strong>\". After careful consideration, we regret to inform you that your submission has not been accepted for publication.\n<p>We sincerely appreciate your effort and contribution to the field and encourage you to consider submitting to other journals that may be better suited to your work.</p>\n<p>Thank you again for your interest in NRCP Journal Publication, and we wish you the best in your future research endeavors.<br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph</p>\n<br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '1', '2025-01-24 21:13:58'),
(5, 5, 'Review request', 'NRCP Journal Publication : Manuscript Review Request', 5, 16, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', '<strong>[FULL NAME]<br></strong><strong>[POSITION]</strong><br><strong>[AFFILIATION]</strong><br><strong>[ADDRESS]</strong> <br><br>Dear <strong>[TITLE]</strong> <strong>[LAST NAME]</strong>, <br><br><br>The NRCP Research Journal issued twice a year, features completed research projects of members of the National Research Council of the Philippines (NRCP), particularly those funded by the Council. This is one of NRCP\'s contributions to the body of knowledge in the realm of basic research which, we believe, paves the way for further research. <br><br>For [ISSUE], [VOLUME] ([YEAR]), we have selected manuscripts for possible publication and one of these is entitled \"<strong>[MANUSCRIPT]</strong>\". <br><br>In recognition of your expertise particularly in [SPECIALIZATION], may we request you to be a Reviewer of the above-mentioned manuscript. We are certain that as a Reviewer, you will render the paper a sound technical perspective worthy of publication in the NRCP Research Journal. Please refer to the attached Abstract to provide you with an overview of the paper. <br><br>[TIME] <br><br>With your acceptance of this invitation, we will send you the full manuscript, the manuscript evaluation form, and the Non-Disclosure Agreement (NDA) automatically once you log in as Reviewer. <br><br>[DUE] <br><br>[ACCEPT/DECLINE] <br><br>A modest token of appreciation will be given upon submission of your review.<br><br>Thank you for your favorable action on this request. <br><br>If you have already addressed this matter, kindly ignore or disregard this email.<br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '5', '2025-01-24 21:14:17'),
(6, 6, 'Decline Request', 'NRCP Journal Publication : Manuscript Review Request Declined', 16, 5, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>Technical Desk Editor</strong>,<br><br><br>Thank you for considering me to review the manuscript titled \"<strong>[MANUSCRIPT]</strong>\".\n<p>I regret to inform you that I am unable to accept this request at this time.</p>\n<p>Thank you for your understanding.<br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph</p>\n<br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '1', '2025-01-24 21:14:35'),
(7, 7, 'Accepted the review request', 'NRCP Journal Publication : Manuscript Review Request Accepted', 16, 5, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>[TITLE] </strong><strong>[FULL NAME]</strong>,<br><br><br>Thank you for accepting our invitation to be a reviewer. <br><br>Please login to your temporary account:<br><br>Username: <strong>[EMAIL]</strong><br>Password: <strong>[PASSWORD]</strong><br><br>Click this link to login : <strong>[LINK]</strong><br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '30', '2025-01-24 20:31:32'),
(8, 8, 'Remind to accept/decline review request', 'NRCP Journal Publication : Request for Manuscript Review', 0, 0, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>[TITLE] [FULL NAME],</strong><br><br><br>Please be reminded that there are only <strong>[DAYS]</strong> day/s left for you to <strong>[ACCEPT/DECLINE]</strong> the request for review on manuscript title \"<strong>[MANUSCRIPT]</strong>\" for publication in the NRCP Research Journal. <br><br>If you have already addressed this matter, kindly ignore or disregard this email.<br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3', '3', '2025-01-24 21:14:57'),
(9, 9, 'Remind to review manuscript', 'NRCP Journal Publication : Manuscript Review', 0, 0, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>[TITLE] [FULL NAME],</strong><br><br><br>May we remind you of your Review of the manuscript title \"<strong>[MANUSCRIPT]</strong>\" which is being considered for publication in the NRCP Research Journal. Given the deadline set, may we expect the accomplished evaluation/score sheet within the next <strong>[DAYS]</strong> day/s? <br><br>In case we do not hear from you, we shall automatically render your username and password deactivated.<br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3', '3', '2025-01-24 21:15:20'),
(10, 10, 'Lapsed Review Task', 'NRCP Journal Publication : Manuscript Review Lapse', 0, 5, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>[TITLE] [FULL NAME],</strong><br><br><br>We understand your busy schedule. We are looking forward to working with you on future issues.<br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best Regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '3', '2025-01-24 21:17:24'),
(11, 11, 'Submit the reviews of PeRev', 'NRCP Journal Publication : Manuscript Review Submitted', 0, 5, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>Technical Desk Editor</strong>,<br><br><br>We are pleased to inform you that the review for the manuscript titled <strong>\"[MANUSCRIPT]\"</strong> has been successfully submitted.<br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '3', '2025-01-24 21:15:44'),
(12, 12, 'Transmit the consolidated review', 'NRCP Journal Publication : Consolidation of Manuscript Review Results', 5, 0, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>Copy Editor</strong><strong>,<br></strong><br><br>We are pleased to inform you that the review results of the manuscript titled \"<strong>[MANUSCRIPT]</strong>\", including recommendations and feedback from all reviewers, have been successfully consolidated.\n<p>You may now review the consolidated report by logging into your account.</p>\n<br>Click this link to login: <strong>[LINK]</strong><br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '14', '2025-01-24 21:17:53'),
(13, 13, 'Inform the Author of manuscript the need to revise', 'NRCP Journal Publication : Manuscript Revision Requested', 0, 1, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>[TITLE] </strong><strong>[FULL NAME],<br><br><br></strong>This is to notify you that the review process for your manuscript titled <strong>\"[MANUSCRIPT]\"</strong> has been completed. Based on the reviewers\' evaluations, revisions are required before further processing.<br><br>Click this link to login: <strong>[LINK]</strong><br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '3', '2025-01-24 21:18:15'),
(14, 14, 'Submit author revision', 'NRCP Journal Publication : Manuscript Revision Submitted', 1, 5, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>Technical Desk Editor</strong><strong>,</strong><br><br><br>This is to notify you that the revised version of the manuscript titled <strong>\"[MANUSCRIPT]\"</strong> has been successfully submitted by the author.<br><br>Click this link to login: <strong>[LINK]</strong><br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '7', '2025-01-26 20:39:26'),
(15, 15, 'Endorse the proofread/edited manuscript', 'NRCP Journal Publication : Proofread/Revised Manuscript Endorsed', 13, 6, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>Editor-in-Chief</strong><strong>,</strong><br><br><br>This is to notify you that the proofread or revised manuscript titled <strong>\"[MANUSCRIPT]\"</strong> has been successfully endorsed and is now ready for the next stage in the process.<br><br>Click this link to login: <strong>[LINK]</strong><br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '2', '2025-01-24 21:18:51'),
(16, 16, 'Send edited manuscript for proofread', 'NRCP Journal Publication : Edited Manuscript Submitted for Proofreading', 6, 1, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear&nbsp;<strong>[TITLE] </strong><strong>[FULL NAME],</strong><br><br><br>This is to notify you that the edited manuscript titled <strong>\"[MANUSCRIPT]\"</strong> has been successfully submitted for proofreading.<br><br>Click this link to login: <strong>[LINK]</strong><br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '3', '2025-01-24 21:19:08'),
(17, 17, 'Submit final review of EIC', 'NRCP Journal Publication : Proofread Manuscript Transmitted', 1, 14, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear <strong>Layout Artist,<br></strong><br><br>This is to notify you that the final review of manuscript titled <strong>\"[MANUSCRIPT]\"</strong> has been successfully transmitted for further processing.<br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '3', '2025-01-24 21:19:25'),
(18, 18, 'transmit the draft layout to EIC', 'NRCP Journal Publication : Draft Layout of Manuscript Transmitted', 14, 6, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear&nbsp;<strong>[TITLE]</strong> <strong>[FULL NAME],<br></strong><br><br>This is to notify you that the draft layout of the manuscript titled <strong>\"[MANUSCRIPT]\"</strong> has been successfully transmitted.<br><br>Click this link to login: <strong>[LINK]</strong><br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '3', '2025-01-24 21:19:47'),
(19, 19, 'Informs author\'s that his/her manuscript is published to the eJournal website', 'NRCP Journal Publication : Manuscript is Published', 0, 1, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear&nbsp;<strong>[TITLE] </strong><strong>[FULL NAME],<br><br></strong><br>Please be informed that your submitted manuscript title \"<strong>[MANUSCRIPT]</strong>\" is now published to research journal wesbite.<br><br><strong>[LINK]</strong><br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>>', '3,5', '0', '2025-01-24 21:20:04'),
(20, 20, 'Certification template', 'Certification', 0, 0, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', '<div>This is to certify that [REVIEWER] was engaged by the National Research Council of the Philippines as REVIEWER of the NRCP-funded Project entitled \"[MANUSCRIPT]\" which is being considered for publication in the NRCP Research Journal.<br><br>[TITLE] [LAST NAME] was requested to do a review of the manuscript being an NRCP member whose expertise is in [SPECIALIZATION].<br><br></div>\n<div>This Certification was issued on [DATE].</div>', '3,5', '0', '2025-01-14 19:18:16'),
(21, 21, 'Reviewer will receive certification after submission of manusript review', 'NRCP Journal Publication : Certification', 0, 12, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear&nbsp;<strong>[TITLE]</strong> <strong>[FULL NAME],<br></strong><br><br>Thank you for reviewing the manuscript entitled \"[MANUSCRIPT]\". Please click the button below to download your certification.<br><br>[CERTIFICATION]<br><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '0', '2025-01-24 21:20:21'),
(22, 22, 'Endorsement of EIC to AssocEd', 'NRCP Journal Publication : Manuscript Review Request', 6, 11, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear&nbsp;<strong>Associate Editor,</strong><br><br>You have been assigned to review manuscript titled \"<strong>[MANUSCRIPT]</strong>\".<br>\n<p>Click this link to login : <strong>[LINK]<br></strong><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.</p>\n<br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Peer Review System<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '3', '2025-01-29 19:37:58'),
(23, 23, 'Enorsement of AssocEd to CluEd', 'NRCP Journal Publication : Manuscript Review Request', 11, 7, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', 'Dear&nbsp;<strong>Cluster Editor,</strong><br><br><br>You have been assigned to review manuscript titled \"<strong>[MANUSCRIPT]</strong>\".<br>\n<p>Click this link to login : <strong>[LINK]<br><br></strong>Should you have any further questions or require assistance, please do not hesitate to send us an email.</p>\n<br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph<br><br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '3', '2025-01-24 21:20:55'),
(24, 24, 'Endorsement for Peer Review', 'NRCP Journal Publication : Manuscript Review Request', 0, 5, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', '<p>Dear <strong>Technical Desk Editor</strong>,<br><br><br>We are pleased to inform you that the manuscript titled \"<strong>[MANUSCRIPT]</strong>\" has been accepted for endorsement to peer review.<br><br>Click this link to login : <strong>[LINK]<br></strong><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph</p>\n<br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '3', '2025-01-24 21:21:09'),
(40, 25, 'Inform\'s TeDeEd for a submitted manuscript', 'NRCP Journal Publication : Notification of Submitted Manuscript', 1, 5, 'gerard_balde@yahoo.com', 'gerard_balde@yahoo.com', '<p>Dear <strong>Technical Desk Editor</strong>,<br><br><br>We are pleased to inform you that the manuscript titled \"<strong>[MANUSCRIPT]</strong>\" has been submitted.<br><br>Click this link to login : <strong>[LINK]<br></strong><br>Should you have any further questions or require assistance, please do not hesitate to send us an email.<br><br>1. For publication-related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com<br><br>2. For technical concerns - email: ejournal@nrcp.dost.gov.ph</p> <br><br>Best regards,<br><br>NRCP Online Research Journal<br><br><br><em>** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **</em>', '3,5', '3', '2025-01-24 21:21:09');

-- --------------------------------------------------------

--
-- Table structure for table `tblfinal_revisions`
--

DROP TABLE IF EXISTS `tblfinal_revisions`;
CREATE TABLE IF NOT EXISTS `tblfinal_revisions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `final_rev_man_id` int(11) NOT NULL,
  `final_rev_file` varchar(255) NOT NULL,
  `final_rev_usr_id` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbllayouts`
--

DROP TABLE IF EXISTS `tbllayouts`;
CREATE TABLE IF NOT EXISTS `tbllayouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `lay_man_id` int(11) NOT NULL,
  `lay_usr_id` varchar(255) NOT NULL,
  `lay_file` varchar(255) NOT NULL,
  `lay_remarks` text DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbllogin_attempts`
--

DROP TABLE IF EXISTS `tbllogin_attempts`;
CREATE TABLE IF NOT EXISTS `tbllogin_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(64) DEFAULT NULL,
  `user_email` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `attempt_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblmodule_access`
--

DROP TABLE IF EXISTS `tblmodule_access`;
CREATE TABLE IF NOT EXISTS `tblmodule_access` (
  `acc_id` int(11) NOT NULL AUTO_INCREMENT,
  `acc_usr_id` varchar(64) NOT NULL,
  `acc_dashboard` int(2) NOT NULL,
  `acc_reports` int(2) NOT NULL,
  `acc_user_mgt` int(2) NOT NULL,
  `acc_lib` int(2) NOT NULL,
  `acc_settings` int(2) NOT NULL,
  `acc_feedbacks` int(2) NOT NULL,
  `acc_logs` int(2) NOT NULL,
  `acc_date_created` datetime DEFAULT NULL,
  `acc_last_updated` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`acc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblmodule_access`
--

INSERT INTO `tblmodule_access` (`acc_id`, `acc_usr_id`, `acc_dashboard`, `acc_reports`, `acc_user_mgt`, `acc_lib`, `acc_settings`, `acc_feedbacks`, `acc_logs`, `acc_date_created`, `acc_last_updated`) VALUES
(24, '7', 1, 1, 1, 1, 1, 1, 1, NULL, NULL),
(25, 'SA07a6cc39be3517c3f952423c057cf816', 1, 1, 0, 1, 0, 1, 0, '2025-02-08 13:33:27', NULL),
(26, 'SA530', 1, 1, 0, 0, 0, 0, 0, NULL, NULL),
(27, 'SAeeaff83f17b1e513a9abd4b6018968b4', 1, 1, 0, 1, 0, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblpeer_rev_criterias`
--

DROP TABLE IF EXISTS `tblpeer_rev_criterias`;
CREATE TABLE IF NOT EXISTS `tblpeer_rev_criterias` (
  `pcrt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `pcrt_code` varchar(16) DEFAULT NULL,
  `pcrt_desc` text DEFAULT NULL,
  `pcrt_score` int(2) DEFAULT NULL,
  `created_at` varchar(16) DEFAULT NULL,
  `last_updated` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`pcrt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblpeer_rev_criterias`
--

INSERT INTO `tblpeer_rev_criterias` (`pcrt_id`, `pcrt_code`, `pcrt_desc`, `pcrt_score`, `created_at`, `last_updated`) VALUES
(1, 'Criteria 22', 'Quality2', 30, NULL, '2024-12-30 14:20'),
(2, 'Criteria 2', 'Scope/Content', 30, NULL, NULL),
(3, 'Criteria 3', 'Quality', 20, NULL, NULL),
(4, 'Criteria 4', 'Timeliness', 20, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblpublication_types`
--

DROP TABLE IF EXISTS `tblpublication_types`;
CREATE TABLE IF NOT EXISTS `tblpublication_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `publication_desc` varchar(255) DEFAULT NULL,
  `publication_status` int(11) NOT NULL DEFAULT 1 COMMENT '1-Enabled 2-Disabled',
  `created_at` varchar(16) DEFAULT NULL COMMENT 'Create Time',
  `last_updated` varchar(16) DEFAULT NULL COMMENT 'Update Time',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblpublication_types`
--

INSERT INTO `tblpublication_types` (`id`, `publication_desc`, `publication_status`, `created_at`, `last_updated`) VALUES
(1, 'Regular research articles', 1, NULL, NULL),
(2, 'Review papers', 1, NULL, NULL),
(3, 'Systematic review and meta-analysis', 1, NULL, NULL),
(4, 'Short communications', 1, NULL, NULL),
(5, 'Microarticles', 1, NULL, NULL),
(7, 'Perspective', 1, NULL, NULL),
(8, 'Case Studies', 1, NULL, NULL),
(9, 'Clinical case reports', 1, NULL, NULL),
(10, 'Policy briefs', 1, NULL, NULL),
(11, 'Book reviews', 1, NULL, NULL),
(12, 'Editorial Criteria', 1, NULL, NULL),
(13, 'Editorial Process', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblrevision_matrix`
--

DROP TABLE IF EXISTS `tblrevision_matrix`;
CREATE TABLE IF NOT EXISTS `tblrevision_matrix` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mtx_man_id` int(11) NOT NULL,
  `mtx_file` varchar(255) NOT NULL,
  `mtx_usr_id` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblroles`
--

DROP TABLE IF EXISTS `tblroles`;
CREATE TABLE IF NOT EXISTS `tblroles` (
  `row_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(32) NOT NULL,
  `role_id` int(2) NOT NULL,
  `role_access` int(1) NOT NULL COMMENT '1-eJournal 2-eReview 3-Both',
  `role_status` int(11) NOT NULL DEFAULT 1 COMMENT '1-enabled 2-disabled',
  `created_at` varchar(16) DEFAULT NULL,
  `last_updated` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblroles`
--

INSERT INTO `tblroles` (`row_id`, `role_name`, `role_id`, `role_access`, `role_status`, `created_at`, `last_updated`) VALUES
(1, 'Author', 1, 2, 1, '', '1735388259'),
(2, 'Executive Director', 2, 2, 1, '', ''),
(3, 'Managing Editor', 3, 3, 1, '', ''),
(4, 'Editorial Admin', 4, 2, 1, '', ''),
(5, 'Technical Desk Editor', 5, 2, 1, '', ''),
(6, 'Editor-in-Chief', 6, 2, 1, '', ''),
(7, 'Associate 1 Editor', 7, 2, 1, '', ''),
(8, 'Associate 2 Editor', 8, 2, 1, NULL, NULL),
(9, 'Associate 3 Editor', 9, 2, 1, NULL, NULL),
(10, 'Associate 4 Editor', 10, 2, 1, NULL, NULL),
(11, 'Cluster 1 Editor', 11, 2, 1, NULL, NULL),
(12, 'Cluster 2 Editor', 12, 2, 1, NULL, NULL),
(13, 'Cluster 3 Editor', 13, 2, 1, NULL, NULL),
(14, 'Cluster 4 Editor', 14, 2, 1, NULL, NULL),
(15, 'Layout Artist', 15, 2, 1, NULL, NULL),
(16, 'Peer Reviewer', 16, 2, 1, NULL, NULL),
(17, 'Copy Editor', 17, 2, 1, NULL, NULL),
(18, 'Social Media Manager', 18, 2, 1, NULL, NULL),
(19, 'Admin', 19, 3, 1, NULL, NULL),
(20, 'Super Admin', 20, 3, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblsex`
--

DROP TABLE IF EXISTS `tblsex`;
CREATE TABLE IF NOT EXISTS `tblsex` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `sex` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblsex`
--

INSERT INTO `tblsex` (`id`, `sex`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `tblstatus_types`
--

DROP TABLE IF EXISTS `tblstatus_types`;
CREATE TABLE IF NOT EXISTS `tblstatus_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `status_desc` varchar(255) DEFAULT NULL,
  `status_id` int(2) DEFAULT NULL,
  `status_class` char(16) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0-disabled 1-enabled',
  `created_at` datetime DEFAULT NULL COMMENT 'Create Time',
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblstatus_types`
--

INSERT INTO `tblstatus_types` (`id`, `status_desc`, `status_id`, `status_class`, `status`, `created_at`, `last_updated`) VALUES
(1, 'On-review: TeDeEd', 1, 'warning', 1, NULL, '2024-12-29 22:33:52'),
(2, 'On-review: EIC', 2, 'primary', 1, NULL, NULL),
(3, 'On-review: AssocEd', 3, 'primary', 1, NULL, NULL),
(4, 'On-review: CluEd', 4, 'primary', 1, NULL, NULL),
(5, 'On-review: Peer', 5, 'primary', 1, NULL, NULL),
(6, 'Review Consolidation: TeDeEd', 6, 'primary', 1, NULL, NULL),
(7, 'Proofread: CopEd', 7, 'primary', 1, NULL, NULL),
(8, 'Final Review: EIC', 8, 'primary', 1, NULL, NULL),
(9, 'Proofread: Author', 9, 'dark', 1, NULL, NULL),
(10, 'Revision: Author', 10, 'dark', 1, NULL, NULL),
(11, 'Layout: LayArt', 11, 'dark', 1, NULL, NULL),
(12, 'Final Approval: EIC', 12, 'dark', 1, NULL, NULL),
(13, 'Publication: queue', 13, 'success', 1, NULL, NULL),
(14, 'Rejected', 14, 'danger', 1, NULL, NULL),
(15, 'Endorsement to Peer', 15, 'primary', 1, NULL, NULL),
(16, 'Published', 16, 'success', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblsuggested_peer`
--

DROP TABLE IF EXISTS `tblsuggested_peer`;
CREATE TABLE IF NOT EXISTS `tblsuggested_peer` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `peer_usr_id` varchar(255) DEFAULT NULL,
  `peer_title` varchar(16) NOT NULL,
  `peer_name` varchar(64) NOT NULL,
  `peer_specialization` varchar(255) NOT NULL,
  `peer_contact` varchar(64) DEFAULT NULL,
  `peer_email` varchar(64) NOT NULL,
  `peer_type` varchar(16) NOT NULL,
  `peer_man_id` int(11) DEFAULT NULL,
  `peer_clued_usr_id` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbltech_rev_criterias`
--

DROP TABLE IF EXISTS `tbltech_rev_criterias`;
CREATE TABLE IF NOT EXISTS `tbltech_rev_criterias` (
  `crt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `crt_code` varchar(16) DEFAULT NULL,
  `crt_desc` text DEFAULT NULL,
  `created_at` varchar(16) DEFAULT NULL,
  `last_updated` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`crt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbltech_rev_criterias`
--

INSERT INTO `tbltech_rev_criterias` (`crt_id`, `crt_code`, `crt_desc`, `created_at`, `last_updated`) VALUES
(1, 'Criteria 1', 'NUmber of words should be 5000-60002', NULL, '2024-12-30 14:21'),
(2, 'Criteria 2', 'Not published in any other journal', NULL, NULL),
(3, 'Criteria 3', 'Abstract should not be more than 150 woirds with keywords', NULL, NULL),
(4, 'Criteria 4', 'Tables, figures, in-text citations and references should follow APA 7th Edition format', NULL, NULL),
(5, 'Criteria 5', 'With complete key parts: Introduction, Methodology, Results and Discussion Conclusion and Recommendation, Ethics Statement, Declaraion of Conflict of Interest and Author Contributions', NULL, NULL),
(6, 'Criteria 6', 'Number of words should be 5000-6000', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbltech_rev_score`
--

DROP TABLE IF EXISTS `tbltech_rev_score`;
CREATE TABLE IF NOT EXISTS `tbltech_rev_score` (
  `row_id` int(11) NOT NULL AUTO_INCREMENT,
  `tr_man_id` int(5) DEFAULT NULL,
  `tr_processor_id` varchar(64) DEFAULT NULL,
  `tr_crt_1` int(1) DEFAULT NULL,
  `tr_crt_2` int(1) DEFAULT NULL,
  `tr_crt_3` int(1) DEFAULT NULL,
  `tr_crt_4` int(1) DEFAULT NULL,
  `tr_crt_5` int(1) DEFAULT NULL,
  `tr_crt_6` int(1) DEFAULT NULL,
  `tr_final` int(11) NOT NULL,
  `tr_remarks` text DEFAULT NULL,
  `tr_date_reviewed` datetime DEFAULT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1113 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbluser_access_tokens`
--

DROP TABLE IF EXISTS `tbluser_access_tokens`;
CREATE TABLE IF NOT EXISTS `tbluser_access_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `tkn_user_id` varchar(64) DEFAULT NULL,
  `tkn_value` varchar(255) DEFAULT NULL,
  `tkn_created_at` datetime DEFAULT NULL,
  `tkn_expired_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=433 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbluser_access_tokens`
--

INSERT INTO `tbluser_access_tokens` (`id`, `tkn_user_id`, `tkn_value`, `tkn_created_at`, `tkn_expired_at`) VALUES
(267, 'SAec942f88fe9d7cf05d40c30b5dac4fcg', '$2y$10$yzeaBVkmTCdkXp7E4rs.H.eSILkvJb4sb9.WQL4sjAz5.b3OoxquO', '2025-01-22 19:55:38', '2025-01-22 20:15:38'),
(273, 'Rd74e002038ad0afbca4dadd3004319b0', '$2y$10$uj11meZPUKGNCFDkhYys/OzXI8680Q2.aitCzeqV3iRoIo2RgMGUK', '2025-01-24 19:18:30', '2025-01-24 19:38:30'),
(395, 'SA07a6cc39be3517c3f952423c057cf816', '$2y$10$KPOW5WlAAUxAQdRWQjK6MubNSWnhxH4PVr45QugtsAqFqXxrZEi4y', '2025-02-08 20:31:36', '2025-02-08 20:51:36'),
(429, 'SAec942f88fe9d7cf05d40c30b5dac4fce', '$2y$10$mgpOY0jorm1Q2fiYmtaIVegqsELMbKvir7aqghZ95wnUIxBTV2UTi', '2025-02-11 22:22:01', '2025-02-11 22:42:01'),
(432, '7', '$2y$10$fUiyIjxpqjMj.JpEOjxgyeyWN3jJmJEooJjA2CeIkdN26N5T5vXRO', '2025-02-12 19:52:57', '2025-02-12 20:12:57');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
