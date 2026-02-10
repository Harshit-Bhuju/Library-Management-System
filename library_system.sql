-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2026 at 02:45 PM
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
-- Database: `library_system`
--
CREATE DATABASE IF NOT EXISTS `library_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `library_system`;

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `action_description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `action_type`, `action_description`, `ip_address`, `created_at`) VALUES
(1, 7, 'login', 'User logged in successfully', '::1', '2026-02-02 15:16:52'),
(2, 7, 'login', 'User logged in successfully', '::1', '2026-02-02 15:17:48'),
(3, 8, 'login', 'User logged in successfully', '::1', '2026-02-02 15:28:41'),
(4, 8, 'password_change', 'Changed password', '::1', '2026-02-02 15:29:42'),
(5, 8, 'profile_update', 'Updated profile information', '::1', '2026-02-02 15:29:54'),
(6, 8, 'profile_update', 'Updated profile information', '::1', '2026-02-02 15:29:57'),
(7, 9, 'login', 'User logged in successfully', '::1', '2026-02-02 16:02:19'),
(8, 8, 'login', 'User logged in successfully', '::1', '2026-02-02 16:10:43'),
(9, 7, 'login', 'User logged in successfully', '::1', '2026-02-02 16:11:16'),
(10, 8, 'login', 'User logged in successfully', '::1', '2026-02-02 16:29:55'),
(11, 8, 'login', 'User logged in successfully', '::1', '2026-02-02 16:29:57'),
(12, 8, 'book_request', 'Requested book ID: 228', '::1', '2026-02-02 16:53:06'),
(13, 7, 'book_reject', 'Rejected issue ID: 1', '::1', '2026-02-02 16:57:01'),
(14, 9, 'login', 'User logged in successfully', '::1', '2026-02-02 16:59:16'),
(15, 9, 'book_request', 'Requested book ID: 110', '::1', '2026-02-02 16:59:51'),
(16, 9, 'book_request', 'Requested book ID: 66', '::1', '2026-02-02 16:59:59'),
(17, 7, 'book_issue', 'Issued \'Advanced Biology\' to user', '::1', '2026-02-02 17:06:23'),
(18, 7, 'book_approve', 'Approved issue ID: 2', '::1', '2026-02-02 17:06:52'),
(19, 7, 'book_return', 'Returned \'The Comprehensive Facts (2019 Edition)\' from user', '::1', '2026-02-02 17:07:10'),
(20, 7, 'book_add', 'Added book: english', '::1', '2026-02-02 17:20:12'),
(21, 7, 'book_update', 'Updated book: english', '::1', '2026-02-02 17:20:18'),
(22, 7, 'book_delete', 'Deleted book ID: 401', '::1', '2026-02-02 17:20:23'),
(23, 7, 'book_delete', 'Deleted book ID: 256', '::1', '2026-02-02 17:24:44'),
(24, 7, 'book_delete', 'Deleted book ID: 257', '::1', '2026-02-02 17:24:46'),
(25, 7, 'book_delete', 'Deleted book ID: 258', '::1', '2026-02-02 17:26:33'),
(26, 7, 'book_toggle', 'deactivated book: Living Growth Vol 3', '::1', '2026-02-02 17:28:33'),
(27, 7, 'book_toggle', 'activated book: Living Growth Vol 3', '::1', '2026-02-02 17:28:36'),
(28, 7, 'book_toggle', 'deactivated book: Living Growth Vol 3', '::1', '2026-02-02 17:30:03'),
(29, 7, 'book_toggle', 'activated book: Living Growth Vol 3', '::1', '2026-02-02 17:30:08'),
(30, 7, 'login', 'User logged in successfully', '::1', '2026-02-03 14:05:53'),
(31, 8, 'login', 'User logged in successfully', '::1', '2026-02-03 14:09:13'),
(32, 7, 'book_reject', 'Rejected (Cancelled) issue ID: 3', '::1', '2026-02-03 14:09:49'),
(33, 9, 'login', 'User logged in successfully', '::1', '2026-02-03 14:21:55'),
(34, 7, 'book_add', 'Added book: hello', '::1', '2026-02-03 14:21:59'),
(35, 7, 'book_toggle', 'deactivated book: hello', '::1', '2026-02-03 14:22:11'),
(36, 7, 'book_delete', 'Deleted book ID: 402', '::1', '2026-02-03 14:22:15'),
(37, 8, 'login', 'User logged in successfully', '::1', '2026-02-03 14:44:17'),
(38, 7, 'book_update', 'Updated book: Living Growth Vol 3', '::1', '2026-02-03 14:57:54'),
(39, 8, 'login', 'User logged in successfully', '::1', '2026-02-03 15:03:48'),
(40, 8, 'book_review', 'Reviewed book ID: 259 with 5 stars', '::1', '2026-02-03 15:05:31'),
(41, 7, 'review_delete', 'Deleted review ID: 1', '::1', '2026-02-03 15:07:49'),
(43, 7, 'login', 'User logged in successfully', '::1', '2026-02-03 15:13:45'),
(44, 7, 'book_toggle', 'deactivated book: Living Growth Vol 3', '::1', '2026-02-03 15:14:03'),
(45, 7, 'book_toggle', 'activated book: Living Growth Vol 3', '::1', '2026-02-03 15:14:06'),
(46, 7, 'book_issue', 'Issued \'Basic Chemistry\' to user', '::1', '2026-02-03 15:14:37'),
(47, 7, 'book_issue', 'Issued \'Advanced Biology\' to user', '::1', '2026-02-03 15:15:45'),
(48, 7, 'book_issue', 'Issued \'Advanced Motion\' to user', '::1', '2026-02-03 15:15:52'),
(49, 7, 'book_return', 'Returned \'Basic Chemistry\' from user', '::1', '2026-02-03 15:16:41'),
(50, 8, 'book_review', 'Reviewed book ID: 259 with 5 stars', '::1', '2026-02-03 15:17:27'),
(51, 8, 'login', 'User logged in successfully', '::1', '2026-02-03 15:21:16'),
(52, 11, 'login', 'User logged in successfully', '::1', '2026-02-03 15:26:03'),
(53, 8, 'login', 'User logged in successfully', '::1', '2026-02-03 15:28:42'),
(54, 8, 'review_delete', 'Deleted review for book ID: 259', '::1', '2026-02-03 15:29:58'),
(55, 8, 'book_request', 'Requested book ID: 267', '::1', '2026-02-03 15:30:24'),
(56, 7, 'login', 'User logged in successfully', '::1', '2026-02-03 15:31:52'),
(57, 7, 'login', 'User logged in successfully', '::1', '2026-02-03 15:32:14'),
(58, 7, 'book_approve', 'Approved issue ID: 8', '::1', '2026-02-03 15:32:26'),
(59, 7, 'book_issue', 'Issued \'Advanced Biology\' to Royan Baidar', '::1', '2026-02-03 15:33:29'),
(60, 7, 'book_toggle', 'deactivated book: Living Growth Vol 3', '::1', '2026-02-03 15:34:18'),
(61, 7, 'book_toggle', 'activated book: Living Growth Vol 3', '::1', '2026-02-03 15:34:28'),
(62, 7, 'book_return', 'Returned \'Advanced Biology\' from user', '::1', '2026-02-03 15:35:34'),
(63, 7, 'book_issue', 'Issued \'Advanced Biology\' to user', '::1', '2026-02-03 15:35:42'),
(64, 8, 'login', 'User logged in successfully', '::1', '2026-02-03 15:36:29'),
(65, 12, 'login', 'User logged in successfully', '::1', '2026-02-03 15:37:13'),
(66, 7, 'book_toggle', 'deactivated book: Living Growth Vol 3', '::1', '2026-02-03 15:42:16'),
(67, 8, 'login', 'User logged in successfully', '::1', '2026-02-03 15:42:54'),
(68, 7, 'login', 'User logged in successfully', '::1', '2026-02-03 15:43:57'),
(69, 7, 'book_toggle', 'activated book: Living Growth Vol 3', '::1', '2026-02-03 15:44:24'),
(70, 7, 'book_toggle', 'deactivated book: Living Growth Vol 3', '::1', '2026-02-03 15:48:25'),
(71, 8, 'login', 'User logged in successfully', '::1', '2026-02-03 15:48:40'),
(72, 8, 'book_review', 'Reviewed book ID: 260 with 5 stars', '::1', '2026-02-03 15:49:21'),
(73, 7, 'login', 'User logged in successfully', '::1', '2026-02-03 15:51:39'),
(74, 13, 'login', 'User logged in successfully', '::1', '2026-02-03 15:54:47'),
(75, 13, 'book_request', 'Requested book ID: 298', '::1', '2026-02-03 15:55:40'),
(76, 7, 'book_issue', 'Issued \'Advanced Chemistry\' to Ram', '::1', '2026-02-03 15:56:37'),
(77, 13, 'book_request', 'Requested book ID: 133', '::1', '2026-02-03 15:56:37'),
(78, 13, 'book_request_cancel', 'Cancelled request ID: 11', '::1', '2026-02-03 15:59:49'),
(79, 13, 'book_request_cancel', 'Cancelled request ID: 13', '::1', '2026-02-03 15:59:52'),
(80, 7, 'login', 'User logged in successfully', '::1', '2026-02-04 06:56:19'),
(81, 8, 'login', 'User logged in successfully', '::1', '2026-02-04 06:57:23'),
(82, 8, 'book_review', 'Reviewed book ID: 260 with 5 stars', '::1', '2026-02-04 06:57:47'),
(83, 8, 'book_review', 'Reviewed book ID: 260 with 2 stars', '::1', '2026-02-04 06:57:54'),
(84, 8, 'login', 'User logged in successfully', '::1', '2026-02-04 07:09:20'),
(85, 7, 'login', 'User logged in successfully', '::1', '2026-02-04 07:10:11'),
(86, 7, 'book_toggle', 'deactivated book: Handbook of Adventures', '::1', '2026-02-04 07:10:50'),
(87, 7, 'book_toggle', 'activated book: Handbook of Adventures', '::1', '2026-02-04 07:10:56'),
(88, 9, 'login', 'User logged in successfully', '::1', '2026-02-04 07:30:42'),
(89, 9, 'book_request', 'Requested book ID: 295', '::1', '2026-02-04 07:38:09'),
(90, 7, 'login', 'User logged in successfully', '::1', '2026-02-04 07:39:15'),
(91, 7, 'book_approve', 'Approved issue ID: 14', '::1', '2026-02-04 07:39:32'),
(92, 9, 'book_request', 'Requested book ID: 260', '::1', '2026-02-04 07:43:44'),
(93, 7, 'book_approve', 'Approved issue ID: 15', '::1', '2026-02-04 07:43:54'),
(94, 7, 'book_return', 'Returned \'Handbook of Adventures\' from user', '::1', '2026-02-04 07:44:41'),
(95, 9, 'book_request', 'Requested book ID: 260', '::1', '2026-02-04 07:46:20'),
(96, 9, 'book_request', 'Requested book ID: 261', '::1', '2026-02-04 07:46:24'),
(97, 7, 'book_approve', 'Approved issue ID: 17', '::1', '2026-02-04 07:46:58'),
(98, 7, 'book_approve', 'Approved issue ID: 16', '::1', '2026-02-04 07:47:03'),
(99, 7, 'book_update', 'Updated book: Handbook of Adventures', '::1', '2026-02-04 07:49:48'),
(100, 7, 'book_toggle', 'deactivated book: Handbook of Adventures', '::1', '2026-02-04 07:49:58'),
(101, 7, 'book_toggle', 'activated book: Handbook of Adventures', '::1', '2026-02-04 07:50:14'),
(102, 7, 'book_issue', 'Issued \'Advanced Chemistry\' to Suman Neupane', '::1', '2026-02-04 07:51:48'),
(103, 8, 'login', 'User logged in successfully', '::1', '2026-02-07 14:55:17'),
(104, 8, 'login', 'User logged in successfully', '::1', '2026-02-07 15:06:02'),
(105, 7, 'login', 'User logged in via Remember Me', '::1', '2026-02-07 15:06:35'),
(106, 7, 'login', 'User logged in successfully', '::1', '2026-02-07 15:06:48'),
(107, 7, 'book_return', 'Returned \'Handbook of Adventures\' from user', '::1', '2026-02-07 15:07:19'),
(108, 7, 'book_return', 'Returned \'Advanced Biology\' from Royan Baidar', '::1', '2026-02-07 15:07:21'),
(109, 7, 'book_return', 'Returned \'Advanced Motion\' from user', '::1', '2026-02-07 15:07:23'),
(110, 7, 'book_return', 'Returned \'Introduction to Knowledge (2015 Edition)\' from user', '::1', '2026-02-07 15:07:25'),
(111, 7, 'book_return', 'Returned \'Advanced Biology\' from user', '::1', '2026-02-07 15:07:27'),
(112, 7, 'book_return', 'Returned \'Advanced Chemistry\' from Ram', '::1', '2026-02-07 15:07:31'),
(113, 7, 'book_return', 'Returned \'Guide to Facts\' from user', '::1', '2026-02-07 15:07:33'),
(114, 7, 'book_return', 'Returned \'Essentials of Facts (2011 Edition)\' from user', '::1', '2026-02-07 15:07:35'),
(115, 7, 'book_return', 'Returned \'Advanced Chemistry\' from Suman Neupane', '::1', '2026-02-07 15:07:38'),
(116, 7, 'book_return', 'Returned \'The Art of Happiness Vol 5\' from user (Fine: NPR 11.5)', '::1', '2026-02-07 15:37:37'),
(117, 7, 'book_return', 'Returned \'World of Adventures\' from user (Fine: NPR 11.5)', '::1', '2026-02-07 15:44:16'),
(118, 7, 'book_return', 'Returned \'World of Prose\' from user (Fine: NPR 9)', '::1', '2026-02-07 15:46:15'),
(119, 7, 'debug_return', 'Issue: 23, Fine: 9, Paid: YES', '::1', '2026-02-07 15:56:02'),
(120, 7, 'book_return', 'Returned \'Cosmic Genetics\' from user (Fine: NPR 9)', '::1', '2026-02-07 15:56:02'),
(121, 7, 'debug_return', 'Issue: 24, Fine: 6.5, Paid: YES', '::1', '2026-02-07 15:56:05'),
(122, 7, 'book_return', 'Returned \'Principles of Genetics\' from user (Fine: NPR 6.5)', '::1', '2026-02-07 15:56:05'),
(123, 8, 'login', 'User logged in via Remember Me', '::1', '2026-02-07 15:56:24'),
(124, 7, 'book_return', 'Returned \'Understanding Facts\' from Suman Neupane (Fine: NPR 1)', '::1', '2026-02-07 16:13:10'),
(125, 8, 'login', 'User logged in successfully', '::1', '2026-02-07 16:29:56'),
(126, 7, 'login', 'User logged in successfully', '::1', '2026-02-07 16:32:31'),
(127, 7, 'login', 'User logged in successfully', '::1', '2026-02-07 16:33:04'),
(128, 8, 'login', 'User logged in successfully', '::1', '2026-02-07 16:36:04'),
(129, 8, 'login', 'User logged in successfully', '::1', '2026-02-07 16:38:44'),
(130, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 04:48:23'),
(131, 8, 'book_review', 'Reviewed book ID: 261 with 4 stars', '::1', '2026-02-08 04:53:43'),
(132, 8, 'book_request', 'Requested book ID: 261', '::1', '2026-02-08 04:53:49'),
(133, 7, 'login', 'User logged in successfully', '::1', '2026-02-08 05:06:52'),
(134, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 05:16:01'),
(135, 7, 'login', 'User logged in successfully', '::1', '2026-02-08 05:16:52'),
(136, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 05:18:24'),
(137, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 05:40:55'),
(138, 8, 'book_request', 'Requested book ID: 234', '::1', '2026-02-08 05:41:21'),
(139, 7, 'book_approve', 'Approved issue ID: 38', '::1', '2026-02-08 05:42:05'),
(140, 7, 'book_return', 'Returned \'Cosmic Gravity Vol 4\' from user', '::1', '2026-02-08 05:43:32'),
(141, 14, 'login', 'User logged in successfully', '::1', '2026-02-08 06:38:43'),
(142, 7, 'login', 'User logged in successfully', '::1', '2026-02-08 06:39:22'),
(143, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 06:45:54'),
(144, 7, 'login', 'User logged in successfully', '::1', '2026-02-08 06:49:48'),
(145, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 06:52:23'),
(146, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 07:05:12'),
(147, 8, 'book_request', 'Requested book ID: 262', '::1', '2026-02-08 07:08:56'),
(148, 7, 'book_reject', 'Rejected (Cancelled) issue ID: 37', '::1', '2026-02-08 07:09:11'),
(149, 7, 'book_reject', 'Rejected (Cancelled) issue ID: 39', '::1', '2026-02-08 07:09:22'),
(150, 8, 'book_request', 'Requested book ID: 123', '::1', '2026-02-08 07:13:38'),
(151, 7, 'book_approve', 'Approved issue ID: 40', '::1', '2026-02-08 07:13:51'),
(152, 7, 'book_return', 'Returned \'Understanding Gravity Vol 3\' from user', '::1', '2026-02-08 07:14:50'),
(153, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 07:21:57'),
(154, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 07:25:11'),
(155, 8, 'login', 'User logged in via Remember Me', '::1', '2026-02-08 07:36:45'),
(156, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 07:37:11'),
(157, 7, 'login', 'User logged in successfully', '::1', '2026-02-08 07:40:39'),
(158, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 07:49:03'),
(159, 8, 'login', 'User logged in successfully', '::1', '2026-02-08 07:52:01'),
(160, 8, 'book_request', 'Requested book ID: 260', '::1', '2026-02-08 07:52:48'),
(161, 7, 'login', 'User logged in successfully', '::1', '2026-02-08 07:52:52'),
(162, 7, 'book_approve', 'Approved issue ID: 41', '::1', '2026-02-08 07:53:13'),
(163, 7, 'book_return', 'Returned \'World of Facts\' from Suman Neupane (Fine: NPR 2.5)', '::1', '2026-02-08 07:55:38'),
(164, 7, 'book_return', 'Returned \'The Art of Happiness Vol 5\' from user (Fine: NPR 2.5)', '::1', '2026-02-08 07:56:32'),
(165, 8, 'book_request', 'Requested book ID: 370', '::1', '2026-02-08 07:56:49'),
(166, 7, 'book_approve', 'Approved issue ID: 42', '::1', '2026-02-08 07:57:02'),
(167, 7, 'login', 'User logged in successfully', '::1', '2026-02-08 07:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `author` varchar(100) NOT NULL,
  `publisher` varchar(100) DEFAULT NULL,
  `publication_year` year(4) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `total_copies` int(11) NOT NULL DEFAULT 1,
  `available_copies` int(11) NOT NULL DEFAULT 1,
  `shelf_location` varchar(20) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `isbn`, `title`, `author`, `publisher`, `publication_year`, `category_id`, `description`, `total_copies`, `available_copies`, `shelf_location`, `cover_image`, `created_at`, `updated_at`, `is_active`) VALUES
(1, '9783500060049', 'Applied Design', 'Robert Wilson', 'Oxford Press', '2010', 6, 'A distinctive Mathematics book titled \'Applied Design\'. Authored by Robert Wilson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 4, 4, 'MATH-727', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:31', '2026-02-03 14:22:51', 1),
(2, '9780825745634', 'Guide to Knowledge Vol 5', 'James Brown', 'Penguin', '2011', 2, 'A distinctive Non-Fiction book titled \'Guide to Knowledge Vol 5\'. Authored by James Brown, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 7, 7, 'NF-888', 'uploads/covers/bad.png', '2026-02-02 15:59:31', '2026-02-03 14:22:55', 1),
(3, '9786530918841', 'Understanding Adventures Vol 5', 'Alice Davis', 'OReilly', '2017', 10, 'A distinctive Children book titled \'Understanding Adventures Vol 5\'. Authored by Alice Davis, this book delves into the core concepts of children, offering valuable insights for readers.', 8, 8, 'KIDS-228', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:31', '2026-02-03 14:22:59', 1),
(4, '9785367919821', 'Whispering Mountain', 'Jane Davis', 'Scholastic', '2017', 1, 'A distinctive Fiction book titled \'Whispering Mountain\'. Authored by Jane Davis, this book delves into the core concepts of fiction, offering valuable insights for readers.', 13, 13, 'FIC-663', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:31', '2026-02-03 14:23:02', 1),
(5, '9789547444183', 'The Comprehensive Facts', 'Bob Wilson', 'Oxford Press', '2015', 8, 'A distinctive Reference book titled \'The Comprehensive Facts\'. Authored by Bob Wilson, this book delves into the core concepts of reference, offering valuable insights for readers.', 6, 6, 'REF-390', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(6, '9782365539682', 'Essentials of Facts', 'David Johnson', 'Scholastic', '2023', 8, 'A distinctive Reference book titled \'Essentials of Facts\'. Authored by David Johnson, this book delves into the core concepts of reference, offering valuable insights for readers.', 12, 12, 'REF-976', 'uploads/covers/cherry.png', '2026-02-02 15:59:31', '2026-02-03 14:23:08', 1),
(7, '9789547355887', 'Century of Rome', 'Robert Williams', 'Oxford Press', '2011', 5, 'A distinctive History book titled \'Century of Rome\'. Authored by Robert Williams, this book delves into the core concepts of history, offering valuable insights for readers.', 7, 7, 'HIST-833', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(8, '9787097363397', 'Logic and Variables', 'James Wilson', 'HarperCollins', '2017', 6, 'A distinctive Mathematics book titled \'Logic and Variables\'. Authored by James Wilson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 13, 13, 'MATH-522', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(9, '9786730548790', 'The Comprehensive Facts (2022 Edition)', 'David Miller', 'Penguin', '2015', 8, 'A distinctive Reference book titled \'The Comprehensive Facts (2022 Edition)\'. Authored by David Miller, this book delves into the core concepts of reference, offering valuable insights for readers.', 7, 7, 'REF-685', 'uploads/covers/formula.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(10, '9780994164276', 'Mastering Adventures', 'John Johnson', 'Springer', '2014', 10, 'A distinctive Children book titled \'Mastering Adventures\'. Authored by John Johnson, this book delves into the core concepts of children, offering valuable insights for readers.', 4, 4, 'KIDS-708', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(11, '9788118440178', 'Calculus Analysis', 'Alice Jones', 'Wiley', '2020', 6, 'A distinctive Mathematics book titled \'Calculus Analysis\'. Authored by Alice Jones, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 10, 10, 'MATH-224', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(12, '9787623179241', 'The Rise of Kings', 'Michael Miller', 'Cambridge', '2016', 5, 'A distinctive History book titled \'The Rise of Kings\'. Authored by Michael Miller, this book delves into the core concepts of history, offering valuable insights for readers.', 12, 12, 'HIST-158', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(13, '9781790728940', 'Evolution of Genetics Vol 4', 'John Jones', 'Penguin', '2025', 3, 'A distinctive Science book titled \'Evolution of Genetics Vol 4\'. Authored by John Jones, this book delves into the core concepts of science, offering valuable insights for readers.', 10, 10, 'SCI-603', 'uploads/covers/memory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(14, '9780997719571', 'World of Knowledge Vol 5', 'James Miller', 'Cambridge', '2010', 2, 'A distinctive Non-Fiction book titled \'World of Knowledge Vol 5\'. Authored by James Miller, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 6, 6, 'NF-489', 'uploads/covers/own_business.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(15, '9783163825655', 'Numbers of Dimensions', 'David Jones', 'Wiley', '2012', 6, 'A distinctive Mathematics book titled \'Numbers of Dimensions\'. Authored by David Jones, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 12, 12, 'MATH-807', 'uploads/covers/paradox.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(16, '9782965702935', 'Battle for Revolution', 'Bob Davis', 'Springer', '2020', 5, 'A distinctive History book titled \'Battle for Revolution\'. Authored by Bob Davis, this book delves into the core concepts of history, offering valuable insights for readers.', 7, 7, 'HIST-745', 'uploads/covers/really_good.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(17, '9789628227824', 'Mastering Facts', 'Mary Jones', 'OReilly', '2013', 8, 'A distinctive Reference book titled \'Mastering Facts\'. Authored by Mary Jones, this book delves into the core concepts of reference, offering valuable insights for readers.', 4, 4, 'REF-183', 'uploads/covers/success.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(18, '9786950082987', 'Thinking Confidence Vol 4', 'Alice Wilson', 'Springer', '2014', 9, 'A distinctive Self-Help book titled \'Thinking Confidence Vol 4\'. Authored by Alice Wilson, this book delves into the core concepts of self-help, offering valuable insights for readers.', 9, 9, 'SH-909', 'uploads/covers/time_machine.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(19, '9780904109910', 'Modern Universe', 'Emily Johnson', 'HarperCollins', '2024', 3, 'A distinctive Science book titled \'Modern Universe\'. Authored by Emily Johnson, this book delves into the core concepts of science, offering valuable insights for readers.', 5, 5, 'SCI-386', 'uploads/covers/two_spans.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(20, '9780892347636', 'Guide to Knowledge', 'Bob Moore', 'Cambridge', '2021', 2, 'A distinctive Non-Fiction book titled \'Guide to Knowledge\'. Authored by Bob Moore, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 13, 13, 'NF-949', 'uploads/covers/understory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(21, '9782001063846', 'Essentials of Facts Vol 3', 'Jane Miller', 'Wiley', '2017', 8, 'A distinctive Reference book titled \'Essentials of Facts Vol 3\'. Authored by Jane Miller, this book delves into the core concepts of reference, offering valuable insights for readers.', 6, 6, 'REF-810', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(22, '9787419523749', 'Basic Cells', 'Michael Williams', 'Oxford Press', '2018', 3, 'A distinctive Science book titled \'Basic Cells\'. Authored by Michael Williams, this book delves into the core concepts of science, offering valuable insights for readers.', 6, 6, 'SCI-508', 'uploads/covers/bad.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(23, '9783237952571', 'Guide to Facts', 'Robert Moore', 'Penguin', '2012', 8, 'A distinctive Reference book titled \'Guide to Facts\'. Authored by Robert Moore, this book delves into the core concepts of reference, offering valuable insights for readers.', 6, 6, 'REF-832', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(24, '9784019683369', 'The Comprehensive Knowledge Vol 3', 'Sarah Smith', 'Cambridge', '2023', 2, 'A distinctive Non-Fiction book titled \'The Comprehensive Knowledge Vol 3\'. Authored by Sarah Smith, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 15, 15, 'NF-673', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(25, '9782981344558', 'Mastering Adventures (2023 Edition)', 'Emily Moore', 'Scholastic', '2025', 10, 'A distinctive Children book titled \'Mastering Adventures (2023 Edition)\'. Authored by Emily Moore, this book delves into the core concepts of children, offering valuable insights for readers.', 7, 7, 'KIDS-930', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(26, '9783054469076', 'Introduction to Prose', 'Alice Jones', 'Wiley', '2020', 7, 'A distinctive Literature book titled \'Introduction to Prose\'. Authored by Alice Jones, this book delves into the core concepts of literature, offering valuable insights for readers.', 6, 6, 'LIT-355', 'uploads/covers/cherry.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(27, '9784371230545', 'The Art of Growth', 'John Taylor', 'Wiley', '2013', 9, 'A distinctive Self-Help book titled \'The Art of Growth\'. Authored by John Taylor, this book delves into the core concepts of self-help, offering valuable insights for readers.', 13, 13, 'SH-775', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(28, '9788280759070', 'Data Intelligence', 'Emily Smith', 'Cambridge', '2020', 4, 'A distinctive Technology book titled \'Data Intelligence\'. Authored by Emily Smith, this book delves into the core concepts of technology, offering valuable insights for readers.', 7, 7, 'TECH-728', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(29, '9787244158740', 'Software Systems', 'Robert Moore', 'Springer', '2011', 4, 'A distinctive Technology book titled \'Software Systems\'. Authored by Robert Moore, this book delves into the core concepts of technology, offering valuable insights for readers.', 4, 4, 'TECH-209', 'uploads/covers/formula.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(30, '9786465593949', 'The Hidden Forest', 'John Miller', 'Cambridge', '2022', 1, 'A distinctive Fiction book titled \'The Hidden Forest\'. Authored by John Miller, this book delves into the core concepts of fiction, offering valuable insights for readers.', 4, 4, 'FIC-360', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(31, '9783776978146', 'Programming Computing', 'David Miller', 'Oxford Press', '2019', 4, 'A distinctive Technology book titled \'Programming Computing\'. Authored by David Miller, this book delves into the core concepts of technology, offering valuable insights for readers.', 11, 11, 'TECH-407', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(32, '9780356228240', 'Ancient Peace', 'John Taylor', 'OReilly', '2021', 5, 'A distinctive History book titled \'Ancient Peace\'. Authored by John Taylor, this book delves into the core concepts of history, offering valuable insights for readers.', 12, 12, 'HIST-507', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(33, '9787671006241', 'Discrete Functions', 'David Jones', 'Cambridge', '2024', 6, 'A distinctive Mathematics book titled \'Discrete Functions\'. Authored by David Jones, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 8, 8, 'MATH-228', 'uploads/covers/memory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(34, '9787573522377', 'Essentials of Knowledge', 'David Williams', 'Wiley', '2022', 2, 'A distinctive Non-Fiction book titled \'Essentials of Knowledge\'. Authored by David Williams, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 13, 13, 'NF-767', 'uploads/covers/own_business.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(35, '9787078684742', 'World of Knowledge', 'Michael Moore', 'OReilly', '2020', 2, 'A distinctive Non-Fiction book titled \'World of Knowledge\'. Authored by Michael Moore, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 11, 11, 'NF-746', 'uploads/covers/paradox.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(36, '9785345629975', 'Theory of Probability', 'David Johnson', 'HarperCollins', '2020', 6, 'A distinctive Mathematics book titled \'Theory of Probability\'. Authored by David Johnson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 11, 11, 'MATH-310', 'uploads/covers/really_good.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(37, '9789915238224', 'World of Prose Vol 1', 'Jane Wilson', 'Springer', '2015', 7, 'A distinctive Literature book titled \'World of Prose Vol 1\'. Authored by Jane Wilson, this book delves into the core concepts of literature, offering valuable insights for readers.', 5, 5, 'LIT-323', 'uploads/covers/success.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(38, '9784944541212', 'Software Architecture', 'Michael Smith', 'Scholastic', '2020', 4, 'A distinctive Technology book titled \'Software Architecture\'. Authored by Michael Smith, this book delves into the core concepts of technology, offering valuable insights for readers.', 4, 4, 'TECH-674', 'uploads/covers/time_machine.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(39, '9789320042646', 'Ancient Democracy', 'Alice Smith', 'Pearson', '2015', 5, 'A distinctive History book titled \'Ancient Democracy\'. Authored by Alice Smith, this book delves into the core concepts of history, offering valuable insights for readers.', 5, 5, 'HIST-493', 'uploads/covers/two_spans.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(40, '9789269445918', 'Medieval Asia', 'Alice Taylor', 'Cambridge', '2016', 5, 'A distinctive History book titled \'Medieval Asia\'. Authored by Alice Taylor, this book delves into the core concepts of history, offering valuable insights for readers.', 4, 4, 'HIST-612', 'uploads/covers/understory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(41, '9786329606987', 'Handbook of Adventures', 'Emily Wilson', 'Scholastic', '2023', 10, 'A distinctive Children book titled \'Handbook of Adventures\'. Authored by Emily Wilson, this book delves into the core concepts of children, offering valuable insights for readers.', 4, 4, 'KIDS-502', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(42, '9785163117251', 'Software Algorithms', 'Mary Johnson', 'Scholastic', '2013', 4, 'A distinctive Technology book titled \'Software Algorithms\'. Authored by Mary Johnson, this book delves into the core concepts of technology, offering valuable insights for readers.', 9, 9, 'TECH-292', 'uploads/covers/bad.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(43, '9785765214171', 'Molecular Matter Vol 5', 'Michael Smith', 'Pearson', '2024', 3, 'A distinctive Science book titled \'Molecular Matter Vol 5\'. Authored by Michael Smith, this book delves into the core concepts of science, offering valuable insights for readers.', 12, 12, 'SCI-531', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(44, '9789501210133', 'Mastering Knowledge Vol 5', 'Sarah Davis', 'Oxford Press', '2012', 2, 'A distinctive Non-Fiction book titled \'Mastering Knowledge Vol 5\'. Authored by Sarah Davis, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 11, 11, 'NF-785', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(45, '9782196303090', 'Mastering Knowledge', 'Mary Jones', 'Cambridge', '2022', 2, 'A distinctive Non-Fiction book titled \'Mastering Knowledge\'. Authored by Mary Jones, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 8, 8, 'NF-844', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(46, '9781443517443', 'Understanding Facts', 'David Smith', 'Cambridge', '2022', 8, 'A distinctive Reference book titled \'Understanding Facts\'. Authored by David Smith, this book delves into the core concepts of reference, offering valuable insights for readers.', 3, 3, 'REF-632', 'uploads/covers/cherry.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(47, '9788497636468', 'The Rise of Kings (2020 Edition)', 'John Moore', 'Cambridge', '2019', 5, 'A distinctive History book titled \'The Rise of Kings (2020 Edition)\'. Authored by John Moore, this book delves into the core concepts of history, offering valuable insights for readers.', 13, 13, 'HIST-952', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(48, '9781133201415', 'Robotics Architecture Vol 5', 'Mary Jones', 'HarperCollins', '2025', 4, 'A distinctive Technology book titled \'Robotics Architecture Vol 5\'. Authored by Mary Jones, this book delves into the core concepts of technology, offering valuable insights for readers.', 10, 10, 'TECH-299', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(49, '9787674276356', 'Network Engineering Vol 1', 'James Wilson', 'Oxford Press', '2012', 4, 'A distinctive Technology book titled \'Network Engineering Vol 1\'. Authored by James Wilson, this book delves into the core concepts of technology, offering valuable insights for readers.', 10, 10, 'TECH-397', 'uploads/covers/formula.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(50, '9788861479818', 'The Comprehensive Adventures Vol 5', 'David Moore', 'Scholastic', '2012', 10, 'A distinctive Children book titled \'The Comprehensive Adventures Vol 5\'. Authored by David Moore, this book delves into the core concepts of children, offering valuable insights for readers.', 6, 6, 'KIDS-737', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(51, '9782890467831', 'Calculus Equations', 'James Davis', 'Wiley', '2019', 6, 'A distinctive Mathematics book titled \'Calculus Equations\'. Authored by James Davis, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 15, 15, 'MATH-557', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(52, '9788732805786', 'Modern Chemistry', 'Michael Moore', 'OReilly', '2024', 3, 'A distinctive Science book titled \'Modern Chemistry\'. Authored by Michael Moore, this book delves into the core concepts of science, offering valuable insights for readers.', 10, 10, 'SCI-960', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(53, '9783146884386', 'Principles of Physics Vol 3', 'Alice Jones', 'Wiley', '2021', 3, 'A distinctive Science book titled \'Principles of Physics Vol 3\'. Authored by Alice Jones, this book delves into the core concepts of science, offering valuable insights for readers.', 14, 14, 'SCI-595', 'uploads/covers/memory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(54, '9788162748167', 'The Comprehensive Adventures', 'James Taylor', 'OReilly', '2024', 10, 'A distinctive Children book titled \'The Comprehensive Adventures\'. Authored by James Taylor, this book delves into the core concepts of children, offering valuable insights for readers.', 3, 3, 'KIDS-897', 'uploads/covers/own_business.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(55, '9783197808568', 'Introduction to Adventures Vol 4', 'David Wilson', 'Wiley', '2014', 10, 'A distinctive Children book titled \'Introduction to Adventures Vol 4\'. Authored by David Wilson, this book delves into the core concepts of children, offering valuable insights for readers.', 12, 12, 'KIDS-318', 'uploads/covers/paradox.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(56, '9789120654964', 'Handbook of Facts', 'James Smith', 'Springer', '2018', 8, 'A distinctive Reference book titled \'Handbook of Facts\'. Authored by James Smith, this book delves into the core concepts of reference, offering valuable insights for readers.', 12, 12, 'REF-620', 'uploads/covers/really_good.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(57, '9780171563514', 'Mastering Confidence Vol 5', 'James Wilson', 'HarperCollins', '2014', 9, 'A distinctive Self-Help book titled \'Mastering Confidence Vol 5\'. Authored by James Wilson, this book delves into the core concepts of self-help, offering valuable insights for readers.', 12, 12, 'SH-978', 'uploads/covers/success.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(58, '9787467793916', 'Principles of Genetics', 'Michael Miller', 'Cambridge', '2024', 3, 'A distinctive Science book titled \'Principles of Genetics\'. Authored by Michael Miller, this book delves into the core concepts of science, offering valuable insights for readers.', 5, 5, 'SCI-337', 'uploads/covers/time_machine.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(59, '9785883500211', 'Handbook of Facts Vol 4', 'Sarah Brown', 'HarperCollins', '2021', 8, 'A distinctive Reference book titled \'Handbook of Facts Vol 4\'. Authored by Sarah Brown, this book delves into the core concepts of reference, offering valuable insights for readers.', 14, 14, 'REF-534', 'uploads/covers/two_spans.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(60, '9781934572922', 'Mastering Focus', 'David Smith', 'HarperCollins', '2014', 9, 'A distinctive Self-Help book titled \'Mastering Focus\'. Authored by David Smith, this book delves into the core concepts of self-help, offering valuable insights for readers.', 5, 5, 'SH-875', 'uploads/covers/understory.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(61, '9788162545755', 'World of Facts Vol 1', 'Alice Miller', 'Wiley', '2011', 8, 'A distinctive Reference book titled \'World of Facts Vol 1\'. Authored by Alice Miller, this book delves into the core concepts of reference, offering valuable insights for readers.', 9, 9, 'REF-912', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(62, '9781288665265', 'Whispering Time Vol 5', 'Michael Johnson', 'Cambridge', '2024', 1, 'A distinctive Fiction book titled \'Whispering Time Vol 5\'. Authored by Michael Johnson, this book delves into the core concepts of fiction, offering valuable insights for readers.', 7, 7, 'FIC-290', 'uploads/covers/bad.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(63, '9787058072290', 'Introduction to Knowledge', 'Jane Johnson', 'Scholastic', '2014', 2, 'A distinctive Non-Fiction book titled \'Introduction to Knowledge\'. Authored by Jane Johnson, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 14, 14, 'NF-370', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(64, '9783670244079', 'Statistics Proof Vol 3', 'James Wilson', 'Pearson', '2016', 6, 'A distinctive Mathematics book titled \'Statistics Proof Vol 3\'. Authored by James Wilson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 7, 7, 'MATH-813', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(65, '9788129592818', 'Handbook of Knowledge', 'Alice Miller', 'Wiley', '2012', 2, 'A distinctive Non-Fiction book titled \'Handbook of Knowledge\'. Authored by Alice Miller, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 15, 15, 'NF-808', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(66, '9785724554592', 'World of Knowledge (2018 Edition)', 'James Johnson', 'Cambridge', '2014', 2, 'A distinctive Non-Fiction book titled \'World of Knowledge (2018 Edition)\'. Authored by James Johnson, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 15, 15, 'NF-222', 'uploads/covers/cherry.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(67, '9788084572785', 'Basic Chemistry', 'Robert Moore', 'Cambridge', '2025', 3, 'A distinctive Science book titled \'Basic Chemistry\'. Authored by Robert Moore, this book delves into the core concepts of science, offering valuable insights for readers.', 15, 15, 'SCI-551', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:31', '2026-02-03 15:16:41', 1),
(68, '9788991223960', 'Network Design', 'James Williams', 'Scholastic', '2019', 4, 'A distinctive Technology book titled \'Network Design\'. Authored by James Williams, this book delves into the core concepts of technology, offering valuable insights for readers.', 10, 10, 'TECH-827', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(69, '9784614412321', 'Quantum Physics', 'Jane Davis', 'Wiley', '2015', 3, 'A distinctive Science book titled \'Quantum Physics\'. Authored by Jane Davis, this book delves into the core concepts of science, offering valuable insights for readers.', 9, 9, 'SCI-217', 'uploads/covers/formula.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(70, '9780543128326', 'Essentials of Adventures', 'Michael Wilson', 'Scholastic', '2022', 10, 'A distinctive Children book titled \'Essentials of Adventures\'. Authored by Michael Wilson, this book delves into the core concepts of children, offering valuable insights for readers.', 13, 13, 'KIDS-353', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(71, '9780148671379', 'Introduction to Adventures', 'Jane Williams', 'Springer', '2021', 10, 'A distinctive Children book titled \'Introduction to Adventures\'. Authored by Jane Williams, this book delves into the core concepts of children, offering valuable insights for readers.', 15, 15, 'KIDS-294', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(72, '9788166034641', 'Robotics Design', 'Mary Wilson', 'HarperCollins', '2016', 4, 'A distinctive Technology book titled \'Robotics Design\'. Authored by Mary Wilson, this book delves into the core concepts of technology, offering valuable insights for readers.', 6, 6, 'TECH-729', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(73, '9787899768497', 'Understanding Adventures', 'David Moore', 'HarperCollins', '2025', 10, 'A distinctive Children book titled \'Understanding Adventures\'. Authored by David Moore, this book delves into the core concepts of children, offering valuable insights for readers.', 4, 4, 'KIDS-314', 'uploads/covers/memory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(74, '9783273456330', 'The Comprehensive Facts (2016 Edition)', 'Mary Davis', 'Scholastic', '2019', 8, 'A distinctive Reference book titled \'The Comprehensive Facts (2016 Edition)\'. Authored by Mary Davis, this book delves into the core concepts of reference, offering valuable insights for readers.', 13, 13, 'REF-912', 'uploads/covers/own_business.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(75, '9785799217970', 'Mastering Facts (2019 Edition)', 'Michael Smith', 'Wiley', '2018', 8, 'A distinctive Reference book titled \'Mastering Facts (2019 Edition)\'. Authored by Michael Smith, this book delves into the core concepts of reference, offering valuable insights for readers.', 15, 15, 'REF-976', 'uploads/covers/paradox.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(76, '9787246968882', 'Shadows of Time', 'Robert Moore', 'Springer', '2015', 1, 'A distinctive Fiction book titled \'Shadows of Time\'. Authored by Robert Moore, this book delves into the core concepts of fiction, offering valuable insights for readers.', 4, 4, 'FIC-464', 'uploads/covers/really_good.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(77, '9783758099999', 'Handbook of Prose Vol 4', 'Alice Davis', 'Cambridge', '2012', 7, 'A distinctive Literature book titled \'Handbook of Prose Vol 4\'. Authored by Alice Davis, this book delves into the core concepts of literature, offering valuable insights for readers.', 6, 6, 'LIT-350', 'uploads/covers/success.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(78, '9782781036923', 'Handbook of Knowledge (2001 Edition)', 'David Jones', 'Oxford Press', '2019', 2, 'A distinctive Non-Fiction book titled \'Handbook of Knowledge (2001 Edition)\'. Authored by David Jones, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 10, 10, 'NF-276', 'uploads/covers/time_machine.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(79, '9783915777948', 'Understanding Adventures (2017 Edition)', 'Mary Davis', 'Pearson', '2017', 10, 'A distinctive Children book titled \'Understanding Adventures (2017 Edition)\'. Authored by Mary Davis, this book delves into the core concepts of children, offering valuable insights for readers.', 4, 4, 'KIDS-206', 'uploads/covers/two_spans.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(80, '9788375060558', 'Molecular Chemistry', 'Sarah Taylor', 'Wiley', '2017', 3, 'A distinctive Science book titled \'Molecular Chemistry\'. Authored by Sarah Taylor, this book delves into the core concepts of science, offering valuable insights for readers.', 9, 9, 'SCI-541', 'uploads/covers/understory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(81, '9788726468264', 'Mastering Prose', 'Emily Brown', 'Wiley', '2014', 7, 'A distinctive Literature book titled \'Mastering Prose\'. Authored by Emily Brown, this book delves into the core concepts of literature, offering valuable insights for readers.', 8, 8, 'LIT-990', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(82, '9783850910011', 'Essentials of Adventures (2021 Edition)', 'Bob Moore', 'Oxford Press', '2016', 10, 'A distinctive Children book titled \'Essentials of Adventures (2021 Edition)\'. Authored by Bob Moore, this book delves into the core concepts of children, offering valuable insights for readers.', 8, 8, 'KIDS-700', 'uploads/covers/bad.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(83, '9784601412784', 'Introduction to Knowledge (2003 Edition)', 'Michael Davis', 'Scholastic', '2010', 2, 'A distinctive Non-Fiction book titled \'Introduction to Knowledge (2003 Edition)\'. Authored by Michael Davis, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 6, 6, 'NF-422', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(84, '9784133816399', 'Geometry Proof Vol 1', 'Robert Williams', 'Wiley', '2025', 6, 'A distinctive Mathematics book titled \'Geometry Proof Vol 1\'. Authored by Robert Williams, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 3, 3, 'MATH-208', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(85, '9782968602195', 'Mastering Knowledge (2003 Edition)', 'Alice Davis', 'OReilly', '2022', 2, 'A distinctive Non-Fiction book titled \'Mastering Knowledge (2003 Edition)\'. Authored by Alice Davis, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 6, 6, 'NF-145', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(86, '9788174228991', 'Handbook of Facts (2002 Edition)', 'Robert Miller', 'Oxford Press', '2010', 8, 'A distinctive Reference book titled \'Handbook of Facts (2002 Edition)\'. Authored by Robert Miller, this book delves into the core concepts of reference, offering valuable insights for readers.', 9, 9, 'REF-169', 'uploads/covers/cherry.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(87, '9786884905616', 'Quantum Matter', 'Michael Brown', 'OReilly', '2022', 3, 'A distinctive Science book titled \'Quantum Matter\'. Authored by Michael Brown, this book delves into the core concepts of science, offering valuable insights for readers.', 11, 11, 'SCI-364', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(88, '9786354127927', 'World of Knowledge Vol 5 (2000 Edition)', 'Robert Williams', 'HarperCollins', '2020', 2, 'A distinctive Non-Fiction book titled \'World of Knowledge Vol 5 (2000 Edition)\'. Authored by Robert Williams, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 14, 14, 'NF-610', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(89, '9787678188435', 'Evolution of Physics', 'James Wilson', 'Pearson', '2011', 3, 'A distinctive Science book titled \'Evolution of Physics\'. Authored by James Wilson, this book delves into the core concepts of science, offering valuable insights for readers.', 9, 9, 'SCI-372', 'uploads/covers/formula.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(90, '9781211134494', 'The Last Journey Vol 3', 'Michael Brown', 'Penguin', '2019', 1, 'A distinctive Fiction book titled \'The Last Journey Vol 3\'. Authored by Michael Brown, this book delves into the core concepts of fiction, offering valuable insights for readers.', 9, 9, 'FIC-170', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(91, '9788302002039', 'Logic and Variables (2022 Edition)', 'Sarah Johnson', 'Cambridge', '2013', 6, 'A distinctive Mathematics book titled \'Logic and Variables (2022 Edition)\'. Authored by Sarah Johnson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 7, 7, 'MATH-481', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(92, '9787905742085', 'Handbook of Knowledge Vol 2', 'Mary Williams', 'Penguin', '2020', 2, 'A distinctive Non-Fiction book titled \'Handbook of Knowledge Vol 2\'. Authored by Mary Williams, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 4, 4, 'NF-316', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(93, '9783333629865', 'Quantum Physics (2018 Edition)', 'John Smith', 'OReilly', '2023', 3, 'A distinctive Science book titled \'Quantum Physics (2018 Edition)\'. Authored by John Smith, this book delves into the core concepts of science, offering valuable insights for readers.', 7, 7, 'SCI-666', 'uploads/covers/memory.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(94, '9789331164772', 'Data Design Vol 1', 'Emily Brown', 'Springer', '2019', 4, 'A distinctive Technology book titled \'Data Design Vol 1\'. Authored by Emily Brown, this book delves into the core concepts of technology, offering valuable insights for readers.', 6, 6, 'TECH-504', 'uploads/covers/own_business.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(95, '9786651352196', 'Future Coding Vol 4', 'Sarah Wilson', 'Cambridge', '2011', 4, 'A distinctive Technology book titled \'Future Coding Vol 4\'. Authored by Sarah Wilson, this book delves into the core concepts of technology, offering valuable insights for readers.', 5, 5, 'TECH-778', 'uploads/covers/paradox.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(96, '9783633087648', 'The Comprehensive Prose', 'John Johnson', 'Oxford Press', '2025', 7, 'A distinctive Literature book titled \'The Comprehensive Prose\'. Authored by John Johnson, this book delves into the core concepts of literature, offering valuable insights for readers.', 12, 12, 'LIT-806', 'uploads/covers/really_good.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(97, '9788049757827', 'Molecular Chemistry (2001 Edition)', 'Michael Wilson', 'Wiley', '2014', 3, 'A distinctive Science book titled \'Molecular Chemistry (2001 Edition)\'. Authored by Michael Wilson, this book delves into the core concepts of science, offering valuable insights for readers.', 13, 13, 'SCI-199', 'uploads/covers/success.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(98, '9781924441101', 'Cosmic Gravity', 'Michael Williams', 'Scholastic', '2022', 3, 'A distinctive Science book titled \'Cosmic Gravity\'. Authored by Michael Williams, this book delves into the core concepts of science, offering valuable insights for readers.', 15, 15, 'SCI-761', 'uploads/covers/time_machine.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(99, '9789039222409', 'Programming Algorithms Vol 2', 'Michael Williams', 'Wiley', '2011', 4, 'A distinctive Technology book titled \'Programming Algorithms Vol 2\'. Authored by Michael Williams, this book delves into the core concepts of technology, offering valuable insights for readers.', 12, 12, 'TECH-937', 'uploads/covers/two_spans.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(100, '9788815947217', 'Statistics Proof Vol 4', 'Emily Smith', 'Springer', '2017', 6, 'A distinctive Mathematics book titled \'Statistics Proof Vol 4\'. Authored by Emily Smith, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 15, 15, 'MATH-833', 'uploads/covers/understory.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(101, '9780999862008', 'Mastering Knowledge (2015 Edition)', 'Mary Jones', 'Oxford Press', '2024', 2, 'A distinctive Non-Fiction book titled \'Mastering Knowledge (2015 Edition)\'. Authored by Mary Jones, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 9, 9, 'NF-628', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(102, '9787328726902', 'Civilizations of Greece', 'Emily Brown', 'Oxford Press', '2015', 5, 'A distinctive History book titled \'Civilizations of Greece\'. Authored by Emily Brown, this book delves into the core concepts of history, offering valuable insights for readers.', 4, 4, 'HIST-146', 'uploads/covers/bad.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(103, '9789186488831', 'Eternal Soul Vol 5', 'Sarah Davis', 'Cambridge', '2018', 1, 'A distinctive Fiction book titled \'Eternal Soul Vol 5\'. Authored by Sarah Davis, this book delves into the core concepts of fiction, offering valuable insights for readers.', 13, 13, 'FIC-769', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(104, '9789588580931', 'Handbook of Prose', 'David Johnson', 'Pearson', '2019', 7, 'A distinctive Literature book titled \'Handbook of Prose\'. Authored by David Johnson, this book delves into the core concepts of literature, offering valuable insights for readers.', 9, 9, 'LIT-368', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(105, '9781215805561', 'The Theory of Matter', 'Alice Johnson', 'Springer', '2025', 3, 'A distinctive Science book titled \'The Theory of Matter\'. Authored by Alice Johnson, this book delves into the core concepts of science, offering valuable insights for readers.', 13, 13, 'SCI-460', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(106, '9789560579843', 'The Comprehensive Adventures (2000 Edition)', 'Mary Wilson', 'Scholastic', '2019', 10, 'A distinctive Children book titled \'The Comprehensive Adventures (2000 Edition)\'. Authored by Mary Wilson, this book delves into the core concepts of children, offering valuable insights for readers.', 9, 9, 'KIDS-238', 'uploads/covers/cherry.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(107, '9786416902159', 'The Silent Soul', 'Bob Johnson', 'Cambridge', '2013', 1, 'A distinctive Fiction book titled \'The Silent Soul\'. Authored by Bob Johnson, this book delves into the core concepts of fiction, offering valuable insights for readers.', 15, 15, 'FIC-628', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(108, '9786718972845', 'The Rise of War', 'Alice Brown', 'HarperCollins', '2014', 5, 'A distinctive History book titled \'The Rise of War\'. Authored by Alice Brown, this book delves into the core concepts of history, offering valuable insights for readers.', 9, 9, 'HIST-320', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(109, '9784269608477', 'Introduction to Adventures (2016 Edition)', 'Jane Miller', 'Oxford Press', '2025', 10, 'A distinctive Children book titled \'Introduction to Adventures (2016 Edition)\'. Authored by Jane Miller, this book delves into the core concepts of children, offering valuable insights for readers.', 3, 3, 'KIDS-992', 'uploads/covers/formula.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(110, '9789003563732', 'The Comprehensive Facts (2019 Edition)', 'Bob Johnson', 'HarperCollins', '2012', 8, 'A distinctive Reference book titled \'The Comprehensive Facts (2019 Edition)\'. Authored by Bob Johnson, this book delves into the core concepts of reference, offering valuable insights for readers.', 15, 15, 'REF-629', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:31', '2026-02-02 17:07:10', 1),
(111, '9788594910107', 'Essentials of Adventures Vol 1', 'Sarah Wilson', 'Cambridge', '2014', 10, 'A distinctive Children book titled \'Essentials of Adventures Vol 1\'. Authored by Sarah Wilson, this book delves into the core concepts of children, offering valuable insights for readers.', 6, 6, 'KIDS-794', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(112, '9789635693867', 'Understanding Universe', 'James Moore', 'Cambridge', '2025', 3, 'A distinctive Science book titled \'Understanding Universe\'. Authored by James Moore, this book delves into the core concepts of science, offering valuable insights for readers.', 12, 12, 'SCI-938', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(113, '9785436615247', 'Mastering Prose (2005 Edition)', 'Bob Jones', 'Springer', '2010', 7, 'A distinctive Literature book titled \'Mastering Prose (2005 Edition)\'. Authored by Bob Jones, this book delves into the core concepts of literature, offering valuable insights for readers.', 15, 15, 'LIT-739', 'uploads/covers/memory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(114, '9784031681202', 'Introduction to Knowledge Vol 4', 'Sarah Brown', 'Oxford Press', '2012', 2, 'A distinctive Non-Fiction book titled \'Introduction to Knowledge Vol 4\'. Authored by Sarah Brown, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 14, 14, 'NF-217', 'uploads/covers/own_business.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(115, '9787266481273', 'Basic Matter', 'Jane Johnson', 'Pearson', '2015', 3, 'A distinctive Science book titled \'Basic Matter\'. Authored by Jane Johnson, this book delves into the core concepts of science, offering valuable insights for readers.', 4, 4, 'SCI-352', 'uploads/covers/paradox.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(116, '9783765325043', 'Guide to Adventures Vol 5', 'Alice Wilson', 'OReilly', '2024', 10, 'A distinctive Children book titled \'Guide to Adventures Vol 5\'. Authored by Alice Wilson, this book delves into the core concepts of children, offering valuable insights for readers.', 4, 4, 'KIDS-818', 'uploads/covers/really_good.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(117, '9783232438604', 'Dreams of Sea', 'Jane Wilson', 'Cambridge', '2015', 1, 'A distinctive Fiction book titled \'Dreams of Sea\'. Authored by Jane Wilson, this book delves into the core concepts of fiction, offering valuable insights for readers.', 3, 3, 'FIC-469', 'uploads/covers/success.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(118, '9780144071653', 'Linear Functions', 'Jane Taylor', 'Wiley', '2020', 6, 'A distinctive Mathematics book titled \'Linear Functions\'. Authored by Jane Taylor, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 6, 6, 'MATH-499', 'uploads/covers/time_machine.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(119, '9789281770224', 'The Last Forest Vol 1', 'John Taylor', 'HarperCollins', '2015', 1, 'A distinctive Fiction book titled \'The Last Forest Vol 1\'. Authored by John Taylor, this book delves into the core concepts of fiction, offering valuable insights for readers.', 12, 12, 'FIC-619', 'uploads/covers/two_spans.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(120, '9786176268286', 'The Rise of Peace', 'Michael Taylor', 'Wiley', '2010', 5, 'A distinctive History book titled \'The Rise of Peace\'. Authored by Michael Taylor, this book delves into the core concepts of history, offering valuable insights for readers.', 13, 13, 'HIST-565', 'uploads/covers/understory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(121, '9787357199455', 'Evolution of Universe', 'Michael Davis', 'HarperCollins', '2024', 3, 'A distinctive Science book titled \'Evolution of Universe\'. Authored by Michael Davis, this book delves into the core concepts of science, offering valuable insights for readers.', 9, 9, 'SCI-526', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(122, '9783771161421', 'Handbook of Facts (2004 Edition)', 'Sarah Jones', 'Oxford Press', '2021', 8, 'A distinctive Reference book titled \'Handbook of Facts (2004 Edition)\'. Authored by Sarah Jones, this book delves into the core concepts of reference, offering valuable insights for readers.', 13, 13, 'REF-456', 'uploads/covers/bad.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(123, '9781976576402', 'Understanding Gravity Vol 3', 'James Smith', 'OReilly', '2012', 3, 'A distinctive Science book titled \'Understanding Gravity Vol 3\'. Authored by James Smith, this book delves into the core concepts of science, offering valuable insights for readers.', 9, 9, 'SCI-392', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:31', '2026-02-08 07:14:50', 1),
(124, '9789448239125', 'Cyber Security', 'Bob Wilson', 'HarperCollins', '2017', 4, 'A distinctive Technology book titled \'Cyber Security\'. Authored by Bob Wilson, this book delves into the core concepts of technology, offering valuable insights for readers.', 4, 4, 'TECH-234', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(125, '9785635355592', 'Guide to Leadership Vol 4', 'Robert Davis', 'Pearson', '2018', 9, 'A distinctive Self-Help book titled \'Guide to Leadership Vol 4\'. Authored by Robert Davis, this book delves into the core concepts of self-help, offering valuable insights for readers.', 8, 8, 'SH-921', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(126, '9786793968027', 'Handbook of Knowledge (2022 Edition)', 'Mary Moore', 'Wiley', '2023', 2, 'A distinctive Non-Fiction book titled \'Handbook of Knowledge (2022 Edition)\'. Authored by Mary Moore, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 8, 8, 'NF-319', 'uploads/covers/cherry.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(127, '9789237676348', 'Discrete Equations Vol 3', 'Emily Jones', 'Oxford Press', '2011', 6, 'A distinctive Mathematics book titled \'Discrete Equations Vol 3\'. Authored by Emily Jones, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 6, 6, 'MATH-500', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(128, '9782508072576', 'Algebra Functions Vol 3', 'Michael Miller', 'Scholastic', '2011', 6, 'A distinctive Mathematics book titled \'Algebra Functions Vol 3\'. Authored by Michael Miller, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 5, 5, 'MATH-574', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(129, '9782264678425', 'Guide to Facts (2013 Edition)', 'Michael Jones', 'Cambridge', '2016', 8, 'A distinctive Reference book titled \'Guide to Facts (2013 Edition)\'. Authored by Michael Jones, this book delves into the core concepts of reference, offering valuable insights for readers.', 11, 11, 'REF-693', 'uploads/covers/formula.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(130, '9783879451660', 'World of Knowledge Vol 5 (2002 Edition)', 'Jane Taylor', 'Scholastic', '2011', 2, 'A distinctive Non-Fiction book titled \'World of Knowledge Vol 5 (2002 Edition)\'. Authored by Jane Taylor, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 8, 8, 'NF-622', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(131, '9789011319961', 'Applied Probability', 'David Wilson', 'Penguin', '2018', 6, 'A distinctive Mathematics book titled \'Applied Probability\'. Authored by David Wilson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 3, 3, 'MATH-520', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1);
INSERT INTO `books` (`book_id`, `isbn`, `title`, `author`, `publisher`, `publication_year`, `category_id`, `description`, `total_copies`, `available_copies`, `shelf_location`, `cover_image`, `created_at`, `updated_at`, `is_active`) VALUES
(132, '9780227471317', 'Logic and Variables Vol 5', 'Bob Johnson', 'HarperCollins', '2013', 6, 'A distinctive Mathematics book titled \'Logic and Variables Vol 5\'. Authored by Bob Johnson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 10, 10, 'MATH-715', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(133, '9788168390383', 'Geometry Equations', 'Sarah Williams', 'HarperCollins', '2021', 6, 'A distinctive Mathematics book titled \'Geometry Equations\'. Authored by Sarah Williams, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 4, 4, 'MATH-164', 'uploads/covers/memory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(134, '9787115956309', 'Eternal Promise', 'Jane Brown', 'Cambridge', '2021', 1, 'A distinctive Fiction book titled \'Eternal Promise\'. Authored by Jane Brown, this book delves into the core concepts of fiction, offering valuable insights for readers.', 6, 6, 'FIC-232', 'uploads/covers/own_business.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(135, '9785691045409', 'Civilizations of Democracy Vol 4', 'Emily Miller', 'Scholastic', '2018', 5, 'A distinctive History book titled \'Civilizations of Democracy Vol 4\'. Authored by Emily Miller, this book delves into the core concepts of history, offering valuable insights for readers.', 9, 9, 'HIST-525', 'uploads/covers/paradox.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(136, '9784803113087', 'World of Adventures', 'Bob Davis', 'Penguin', '2023', 10, 'A distinctive Children book titled \'World of Adventures\'. Authored by Bob Davis, this book delves into the core concepts of children, offering valuable insights for readers.', 5, 5, 'KIDS-961', 'uploads/covers/really_good.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(137, '9782036920761', 'Discrete Proof', 'Michael Davis', 'Springer', '2021', 6, 'A distinctive Mathematics book titled \'Discrete Proof\'. Authored by Michael Davis, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 8, 8, 'MATH-186', 'uploads/covers/success.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(138, '9789317015802', 'The Last City Vol 3', 'Mary Brown', 'Wiley', '2012', 1, 'A distinctive Fiction book titled \'The Last City Vol 3\'. Authored by Mary Brown, this book delves into the core concepts of fiction, offering valuable insights for readers.', 4, 4, 'FIC-769', 'uploads/covers/time_machine.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(139, '9782139843447', 'Software Coding', 'Mary Taylor', 'Penguin', '2024', 4, 'A distinctive Technology book titled \'Software Coding\'. Authored by Mary Taylor, this book delves into the core concepts of technology, offering valuable insights for readers.', 7, 7, 'TECH-433', 'uploads/covers/two_spans.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(140, '9784284791301', 'Mastering Facts (2012 Edition)', 'Michael Brown', 'Springer', '2025', 8, 'A distinctive Reference book titled \'Mastering Facts (2012 Edition)\'. Authored by Michael Brown, this book delves into the core concepts of reference, offering valuable insights for readers.', 7, 7, 'REF-261', 'uploads/covers/understory.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(141, '9782228987276', 'Evolution of Biology', 'David Wilson', 'Pearson', '2024', 3, 'A distinctive Science book titled \'Evolution of Biology\'. Authored by David Wilson, this book delves into the core concepts of science, offering valuable insights for readers.', 7, 7, 'SCI-384', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(142, '9789070513989', 'Software Algorithms Vol 3', 'Jane Davis', 'Penguin', '2010', 4, 'A distinctive Technology book titled \'Software Algorithms Vol 3\'. Authored by Jane Davis, this book delves into the core concepts of technology, offering valuable insights for readers.', 3, 3, 'TECH-450', 'uploads/covers/bad.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(143, '9785509713957', 'Handbook of Adventures (2015 Edition)', 'Jane Miller', 'Springer', '2021', 10, 'A distinctive Children book titled \'Handbook of Adventures (2015 Edition)\'. Authored by Jane Miller, this book delves into the core concepts of children, offering valuable insights for readers.', 8, 8, 'KIDS-626', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(144, '9786831635243', 'Artificial Computing', 'Alice Johnson', 'Wiley', '2020', 4, 'A distinctive Technology book titled \'Artificial Computing\'. Authored by Alice Johnson, this book delves into the core concepts of technology, offering valuable insights for readers.', 10, 10, 'TECH-575', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(145, '9788105705464', 'World of Facts Vol 3', 'Mary Smith', 'Cambridge', '2012', 8, 'A distinctive Reference book titled \'World of Facts Vol 3\'. Authored by Mary Smith, this book delves into the core concepts of reference, offering valuable insights for readers.', 15, 15, 'REF-557', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(146, '9780726435414', 'Geometry Proof Vol 1 (2016 Edition)', 'David Miller', 'Cambridge', '2014', 6, 'A distinctive Mathematics book titled \'Geometry Proof Vol 1 (2016 Edition)\'. Authored by David Miller, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 8, 8, 'MATH-982', 'uploads/covers/cherry.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(147, '9782488910305', 'The Art of Happiness', 'Emily Johnson', 'HarperCollins', '2023', 9, 'A distinctive Self-Help book titled \'The Art of Happiness\'. Authored by Emily Johnson, this book delves into the core concepts of self-help, offering valuable insights for readers.', 9, 9, 'SH-342', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(148, '9787081020572', 'Golden Soul', 'Alice Jones', 'HarperCollins', '2016', 1, 'A distinctive Fiction book titled \'Golden Soul\'. Authored by Alice Jones, this book delves into the core concepts of fiction, offering valuable insights for readers.', 13, 13, 'FIC-873', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(149, '9786165489555', 'Robotics Design Vol 5', 'Michael Jones', 'Wiley', '2025', 4, 'A distinctive Technology book titled \'Robotics Design Vol 5\'. Authored by Michael Jones, this book delves into the core concepts of technology, offering valuable insights for readers.', 8, 8, 'TECH-886', 'uploads/covers/formula.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(150, '9789341116086', 'World of Prose Vol 4', 'John Jones', 'Penguin', '2011', 7, 'A distinctive Literature book titled \'World of Prose Vol 4\'. Authored by John Jones, this book delves into the core concepts of literature, offering valuable insights for readers.', 6, 6, 'LIT-513', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(151, '9789324813184', 'Empire of Greece', 'David Taylor', 'Scholastic', '2016', 5, 'A distinctive History book titled \'Empire of Greece\'. Authored by David Taylor, this book delves into the core concepts of history, offering valuable insights for readers.', 15, 15, 'HIST-521', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(152, '9781818387054', 'The Comprehensive Adventures (2001 Edition)', 'Emily Moore', 'Wiley', '2018', 10, 'A distinctive Children book titled \'The Comprehensive Adventures (2001 Edition)\'. Authored by Emily Moore, this book delves into the core concepts of children, offering valuable insights for readers.', 11, 11, 'KIDS-328', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(153, '9787159612147', 'Introduction to Knowledge Vol 5', 'Michael Davis', 'Oxford Press', '2010', 2, 'A distinctive Non-Fiction book titled \'Introduction to Knowledge Vol 5\'. Authored by Michael Davis, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 13, 13, 'NF-494', 'uploads/covers/memory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(154, '9780616796420', 'Discrete Systems', 'Jane Brown', 'Wiley', '2019', 6, 'A distinctive Mathematics book titled \'Discrete Systems\'. Authored by Jane Brown, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 10, 10, 'MATH-153', 'uploads/covers/own_business.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(155, '9785221610064', 'Shadows of Soul Vol 3', 'John Williams', 'Cambridge', '2019', 1, 'A distinctive Fiction book titled \'Shadows of Soul Vol 3\'. Authored by John Williams, this book delves into the core concepts of fiction, offering valuable insights for readers.', 10, 10, 'FIC-848', 'uploads/covers/paradox.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(156, '9782599920741', 'The Silent Soul Vol 5', 'Robert Johnson', 'Pearson', '2019', 1, 'A distinctive Fiction book titled \'The Silent Soul Vol 5\'. Authored by Robert Johnson, this book delves into the core concepts of fiction, offering valuable insights for readers.', 14, 14, 'FIC-366', 'uploads/covers/really_good.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(157, '9783978455078', 'Algebra Dimensions Vol 5', 'Emily Wilson', 'Oxford Press', '2023', 6, 'A distinctive Mathematics book titled \'Algebra Dimensions Vol 5\'. Authored by Emily Wilson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 4, 4, 'MATH-653', 'uploads/covers/success.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(158, '9781597122955', 'Geometry Analysis', 'Robert Jones', 'Oxford Press', '2021', 6, 'A distinctive Mathematics book titled \'Geometry Analysis\'. Authored by Robert Jones, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 8, 8, 'MATH-580', 'uploads/covers/time_machine.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(159, '9789595093159', 'Mastering Change', 'Michael Miller', 'Penguin', '2024', 9, 'A distinctive Self-Help book titled \'Mastering Change\'. Authored by Michael Miller, this book delves into the core concepts of self-help, offering valuable insights for readers.', 14, 14, 'SH-333', 'uploads/covers/two_spans.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(160, '9786607306302', 'Geometry Proof', 'James Johnson', 'Wiley', '2021', 6, 'A distinctive Mathematics book titled \'Geometry Proof\'. Authored by James Johnson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 5, 5, 'MATH-131', 'uploads/covers/understory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(161, '9781622481203', 'Guide to Facts (2009 Edition)', 'John Taylor', 'Scholastic', '2016', 8, 'A distinctive Reference book titled \'Guide to Facts (2009 Edition)\'. Authored by John Taylor, this book delves into the core concepts of reference, offering valuable insights for readers.', 5, 5, 'REF-274', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(162, '9784198186971', 'Mastering Leadership', 'Jane Johnson', 'HarperCollins', '2011', 9, 'A distinctive Self-Help book titled \'Mastering Leadership\'. Authored by Jane Johnson, this book delves into the core concepts of self-help, offering valuable insights for readers.', 9, 9, 'SH-179', 'uploads/covers/bad.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(163, '9781501553466', 'Statistics Analysis', 'David Taylor', 'HarperCollins', '2019', 6, 'A distinctive Mathematics book titled \'Statistics Analysis\'. Authored by David Taylor, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 11, 11, 'MATH-724', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(164, '9783632496135', 'Mastering Knowledge (2005 Edition)', 'David Davis', 'Springer', '2023', 2, 'A distinctive Non-Fiction book titled \'Mastering Knowledge (2005 Edition)\'. Authored by David Davis, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 15, 15, 'NF-352', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(165, '9780309016872', 'Shadows of City', 'Bob Brown', 'Scholastic', '2024', 1, 'A distinctive Fiction book titled \'Shadows of City\'. Authored by Bob Brown, this book delves into the core concepts of fiction, offering valuable insights for readers.', 9, 9, 'FIC-388', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(166, '9781746497473', 'Mindset for Wealth', 'Alice Brown', 'Wiley', '2024', 9, 'A distinctive Self-Help book titled \'Mindset for Wealth\'. Authored by Alice Brown, this book delves into the core concepts of self-help, offering valuable insights for readers.', 8, 8, 'SH-987', 'uploads/covers/cherry.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(167, '9785695610394', 'Algebra Equations', 'Alice Johnson', 'Wiley', '2017', 6, 'A distinctive Mathematics book titled \'Algebra Equations\'. Authored by Alice Johnson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 14, 14, 'MATH-620', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(168, '9783069414870', 'Robotics Computing', 'Jane Williams', 'Springer', '2019', 4, 'A distinctive Technology book titled \'Robotics Computing\'. Authored by Jane Williams, this book delves into the core concepts of technology, offering valuable insights for readers.', 13, 13, 'TECH-691', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(169, '9785410953808', 'Essentials of Facts Vol 3 (2005 Edition)', 'David Jones', 'HarperCollins', '2024', 8, 'A distinctive Reference book titled \'Essentials of Facts Vol 3 (2005 Edition)\'. Authored by David Jones, this book delves into the core concepts of reference, offering valuable insights for readers.', 5, 5, 'REF-446', 'uploads/covers/formula.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(170, '9783925227529', 'Essentials of Adventures (2002 Edition)', 'Emily Wilson', 'Scholastic', '2011', 10, 'A distinctive Children book titled \'Essentials of Adventures (2002 Edition)\'. Authored by Emily Wilson, this book delves into the core concepts of children, offering valuable insights for readers.', 8, 8, 'KIDS-912', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(171, '9784433380065', 'The Art of Freedom', 'James Johnson', 'Scholastic', '2015', 9, 'A distinctive Self-Help book titled \'The Art of Freedom\'. Authored by James Johnson, this book delves into the core concepts of self-help, offering valuable insights for readers.', 12, 12, 'SH-542', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(172, '9783160400490', 'Introduction to Facts', 'Bob Smith', 'Scholastic', '2015', 8, 'A distinctive Reference book titled \'Introduction to Facts\'. Authored by Bob Smith, this book delves into the core concepts of reference, offering valuable insights for readers.', 4, 4, 'REF-631', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(173, '9784780311590', 'History of War', 'David Miller', 'Springer', '2013', 5, 'A distinctive History book titled \'History of War\'. Authored by David Miller, this book delves into the core concepts of history, offering valuable insights for readers.', 8, 8, 'HIST-432', 'uploads/covers/memory.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(174, '9782950329326', 'Introduction to Knowledge Vol 3', 'Alice Miller', 'Cambridge', '2022', 2, 'A distinctive Non-Fiction book titled \'Introduction to Knowledge Vol 3\'. Authored by Alice Miller, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 15, 15, 'NF-793', 'uploads/covers/own_business.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(175, '9785735668077', 'Shadows of Mountain', 'Emily Johnson', 'HarperCollins', '2022', 1, 'A distinctive Fiction book titled \'Shadows of Mountain\'. Authored by Emily Johnson, this book delves into the core concepts of fiction, offering valuable insights for readers.', 6, 6, 'FIC-838', 'uploads/covers/paradox.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(176, '9786477004670', 'World of Prose', 'Alice Williams', 'HarperCollins', '2021', 7, 'A distinctive Literature book titled \'World of Prose\'. Authored by Alice Williams, this book delves into the core concepts of literature, offering valuable insights for readers.', 14, 14, 'LIT-980', 'uploads/covers/really_good.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(177, '9782576691356', 'Century of Revolution', 'James Smith', 'Scholastic', '2013', 5, 'A distinctive History book titled \'Century of Revolution\'. Authored by James Smith, this book delves into the core concepts of history, offering valuable insights for readers.', 11, 11, 'HIST-722', 'uploads/covers/success.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(178, '9789243014022', 'Mastering Prose (2011 Edition)', 'Michael Smith', 'HarperCollins', '2011', 7, 'A distinctive Literature book titled \'Mastering Prose (2011 Edition)\'. Authored by Michael Smith, this book delves into the core concepts of literature, offering valuable insights for readers.', 9, 9, 'LIT-889', 'uploads/covers/time_machine.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(179, '9788386743105', 'World of Knowledge (2025 Edition)', 'John Miller', 'Pearson', '2010', 2, 'A distinctive Non-Fiction book titled \'World of Knowledge (2025 Edition)\'. Authored by John Miller, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 15, 15, 'NF-590', 'uploads/covers/two_spans.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(180, '9787639529504', 'Wars of Democracy', 'Mary Jones', 'OReilly', '2011', 5, 'A distinctive History book titled \'Wars of Democracy\'. Authored by Mary Jones, this book delves into the core concepts of history, offering valuable insights for readers.', 8, 8, 'HIST-518', 'uploads/covers/understory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(181, '9782105194910', 'Guide to Adventures', 'Robert Brown', 'HarperCollins', '2020', 10, 'A distinctive Children book titled \'Guide to Adventures\'. Authored by Robert Brown, this book delves into the core concepts of children, offering valuable insights for readers.', 5, 5, 'KIDS-784', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(182, '9780191830529', 'Introduction to Adventures (2014 Edition)', 'Emily Wilson', 'OReilly', '2019', 10, 'A distinctive Children book titled \'Introduction to Adventures (2014 Edition)\'. Authored by Emily Wilson, this book delves into the core concepts of children, offering valuable insights for readers.', 9, 9, 'KIDS-585', 'uploads/covers/bad.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(183, '9781826732969', 'Cyber Computing', 'Robert Jones', 'Pearson', '2021', 4, 'A distinctive Technology book titled \'Cyber Computing\'. Authored by Robert Jones, this book delves into the core concepts of technology, offering valuable insights for readers.', 15, 15, 'TECH-263', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(184, '9788536542813', 'Battle for Europe', 'Jane Wilson', 'HarperCollins', '2017', 5, 'A distinctive History book titled \'Battle for Europe\'. Authored by Jane Wilson, this book delves into the core concepts of history, offering valuable insights for readers.', 15, 15, 'HIST-101', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(185, '9782594707115', 'Essentials of Facts (2021 Edition)', 'John Moore', 'OReilly', '2016', 8, 'A distinctive Reference book titled \'Essentials of Facts (2021 Edition)\'. Authored by John Moore, this book delves into the core concepts of reference, offering valuable insights for readers.', 6, 6, 'REF-926', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(186, '9782384322060', 'Theory of Design', 'Robert Brown', 'OReilly', '2012', 6, 'A distinctive Mathematics book titled \'Theory of Design\'. Authored by Robert Brown, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 15, 15, 'MATH-313', 'uploads/covers/cherry.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(187, '9780320590624', 'Statistics Functions', 'Jane Davis', 'Scholastic', '2016', 6, 'A distinctive Mathematics book titled \'Statistics Functions\'. Authored by Jane Davis, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 3, 3, 'MATH-875', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(188, '9782134372255', 'The Theory of Motion', 'David Moore', 'Penguin', '2017', 3, 'A distinctive Science book titled \'The Theory of Motion\'. Authored by David Moore, this book delves into the core concepts of science, offering valuable insights for readers.', 11, 11, 'SCI-250', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(189, '9789009062700', 'Golden City Vol 2', 'Jane Jones', 'Cambridge', '2025', 1, 'A distinctive Fiction book titled \'Golden City Vol 2\'. Authored by Jane Jones, this book delves into the core concepts of fiction, offering valuable insights for readers.', 13, 13, 'FIC-626', 'uploads/covers/formula.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(190, '9784269599530', 'Guide to Adventures (2000 Edition)', 'Robert Smith', 'Oxford Press', '2019', 10, 'A distinctive Children book titled \'Guide to Adventures (2000 Edition)\'. Authored by Robert Smith, this book delves into the core concepts of children, offering valuable insights for readers.', 7, 7, 'KIDS-982', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(191, '9783396899214', 'Power of Life', 'Alice Smith', 'Penguin', '2018', 9, 'A distinctive Self-Help book titled \'Power of Life\'. Authored by Alice Smith, this book delves into the core concepts of self-help, offering valuable insights for readers.', 10, 10, 'SH-130', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(192, '9784895128215', 'World of Adventures (2020 Edition)', 'Bob Brown', 'HarperCollins', '2021', 10, 'A distinctive Children book titled \'World of Adventures (2020 Edition)\'. Authored by Bob Brown, this book delves into the core concepts of children, offering valuable insights for readers.', 14, 14, 'KIDS-805', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(193, '9786643217673', 'Ancient Queens Vol 5', 'Mary Williams', 'HarperCollins', '2023', 5, 'A distinctive History book titled \'Ancient Queens Vol 5\'. Authored by Mary Williams, this book delves into the core concepts of history, offering valuable insights for readers.', 15, 15, 'HIST-293', 'uploads/covers/memory.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(194, '9787386364809', 'Molecular Cells Vol 2', 'Michael Johnson', 'OReilly', '2012', 3, 'A distinctive Science book titled \'Molecular Cells Vol 2\'. Authored by Michael Johnson, this book delves into the core concepts of science, offering valuable insights for readers.', 14, 14, 'SCI-523', 'uploads/covers/own_business.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(195, '9788076923807', 'The Comprehensive Knowledge', 'Alice Miller', 'OReilly', '2016', 2, 'A distinctive Non-Fiction book titled \'The Comprehensive Knowledge\'. Authored by Alice Miller, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 10, 10, 'NF-974', 'uploads/covers/paradox.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(196, '9785920305168', 'Software Intelligence Vol 2', 'Jane Miller', 'HarperCollins', '2022', 4, 'A distinctive Technology book titled \'Software Intelligence Vol 2\'. Authored by Jane Miller, this book delves into the core concepts of technology, offering valuable insights for readers.', 6, 6, 'TECH-863', 'uploads/covers/really_good.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(197, '9781771245525', 'The Comprehensive Prose (2025 Edition)', 'Bob Jones', 'Wiley', '2025', 7, 'A distinctive Literature book titled \'The Comprehensive Prose (2025 Edition)\'. Authored by Bob Jones, this book delves into the core concepts of literature, offering valuable insights for readers.', 10, 10, 'LIT-457', 'uploads/covers/success.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(198, '9786942744664', 'The Comprehensive Facts (2000 Edition)', 'Robert Taylor', 'Penguin', '2024', 8, 'A distinctive Reference book titled \'The Comprehensive Facts (2000 Edition)\'. Authored by Robert Taylor, this book delves into the core concepts of reference, offering valuable insights for readers.', 7, 7, 'REF-696', 'uploads/covers/time_machine.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(199, '9780184120712', 'Theory of Systems Vol 1', 'David Wilson', 'Oxford Press', '2015', 6, 'A distinctive Mathematics book titled \'Theory of Systems Vol 1\'. Authored by David Wilson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 8, 8, 'MATH-903', 'uploads/covers/two_spans.png', '2026-02-02 15:59:31', '2026-02-03 14:24:39', 1),
(200, '9780864048249', 'Understanding Adventures (2000 Edition)', 'Emily Johnson', 'Pearson', '2018', 10, 'A distinctive Children book titled \'Understanding Adventures (2000 Edition)\'. Authored by Emily Johnson, this book delves into the core concepts of children, offering valuable insights for readers.', 10, 10, 'KIDS-620', 'uploads/covers/understory.png', '2026-02-02 15:59:31', '2026-02-02 15:59:31', 1),
(201, '9783652450050', 'Cyber Architecture', 'John Taylor', 'Wiley', '2014', 4, 'A distinctive Technology book titled \'Cyber Architecture\'. Authored by John Taylor, this book delves into the core concepts of technology, offering valuable insights for readers.', 11, 11, 'TECH-932', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(202, '9784343160837', 'Mastering Adventures', 'Mary Miller', 'Cambridge', '2025', 10, 'A distinctive Children book titled \'Mastering Adventures\'. Authored by Mary Miller, this book delves into the core concepts of children, offering valuable insights for readers.', 10, 10, 'KIDS-106', 'uploads/covers/bad.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(203, '9785551954557', 'Applied Structures Vol 2', 'Alice Moore', 'Springer', '2022', 6, 'A distinctive Mathematics book titled \'Applied Structures Vol 2\'. Authored by Alice Moore, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 9, 9, 'MATH-556', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(204, '9783837621283', 'Introduction to Facts', 'Michael Jones', 'Cambridge', '2015', 8, 'A distinctive Reference book titled \'Introduction to Facts\'. Authored by Michael Jones, this book delves into the core concepts of reference, offering valuable insights for readers.', 8, 8, 'REF-865', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(205, '9789447101087', 'World of Facts Vol 1', 'Bob Brown', 'Pearson', '2010', 8, 'A distinctive Reference book titled \'World of Facts Vol 1\'. Authored by Bob Brown, this book delves into the core concepts of reference, offering valuable insights for readers.', 4, 4, 'REF-513', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(206, '9785507135232', 'The Comprehensive Facts Vol 5', 'Alice Miller', 'Penguin', '2013', 8, 'A distinctive Reference book titled \'The Comprehensive Facts Vol 5\'. Authored by Alice Miller, this book delves into the core concepts of reference, offering valuable insights for readers.', 6, 6, 'REF-526', 'uploads/covers/cherry.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(207, '9785729227000', 'Mastering Focus Vol 2', 'Mary Johnson', 'Pearson', '2025', 9, 'A distinctive Self-Help book titled \'Mastering Focus Vol 2\'. Authored by Mary Johnson, this book delves into the core concepts of self-help, offering valuable insights for readers.', 6, 6, 'SH-901', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(208, '9789298560563', 'Statistics Probability', 'Robert Williams', 'Oxford Press', '2017', 6, 'A distinctive Mathematics book titled \'Statistics Probability\'. Authored by Robert Williams, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 12, 12, 'MATH-383', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(209, '9783088965936', 'Principles of Biology', 'James Smith', 'Springer', '2015', 3, 'A distinctive Science book titled \'Principles of Biology\'. Authored by James Smith, this book delves into the core concepts of science, offering valuable insights for readers.', 3, 3, 'SCI-867', 'uploads/covers/formula.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(210, '9789111373857', 'History of Asia', 'Mary Smith', 'Springer', '2011', 5, 'A distinctive History book titled \'History of Asia\'. Authored by Mary Smith, this book delves into the core concepts of history, offering valuable insights for readers.', 9, 9, 'HIST-623', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(211, '9782702154891', 'Living Growth', 'Robert Brown', 'Scholastic', '2011', 9, 'A distinctive Self-Help book titled \'Living Growth\'. Authored by Robert Brown, this book delves into the core concepts of self-help, offering valuable insights for readers.', 7, 7, 'SH-464', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(212, '9781379162014', 'Introduction to Knowledge Vol 1', 'Alice Johnson', 'Cambridge', '2020', 2, 'A distinctive Non-Fiction book titled \'Introduction to Knowledge Vol 1\'. Authored by Alice Johnson, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 15, 15, 'NF-942', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(213, '9787721893560', 'Power of Growth', 'John Williams', 'OReilly', '2010', 9, 'A distinctive Self-Help book titled \'Power of Growth\'. Authored by John Williams, this book delves into the core concepts of self-help, offering valuable insights for readers.', 13, 13, 'SH-317', 'uploads/covers/memory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(214, '9783401839825', 'The Silent Journey', 'Emily Johnson', 'OReilly', '2019', 1, 'A distinctive Fiction book titled \'The Silent Journey\'. Authored by Emily Johnson, this book delves into the core concepts of fiction, offering valuable insights for readers.', 7, 7, 'FIC-287', 'uploads/covers/own_business.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(215, '9782726165157', 'The Comprehensive Adventures Vol 2', 'James Taylor', 'OReilly', '2023', 10, 'A distinctive Children book titled \'The Comprehensive Adventures Vol 2\'. Authored by James Taylor, this book delves into the core concepts of children, offering valuable insights for readers.', 10, 10, 'KIDS-672', 'uploads/covers/paradox.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(216, '9783167053870', 'Future Security', 'Robert Smith', 'OReilly', '2012', 4, 'A distinctive Technology book titled \'Future Security\'. Authored by Robert Smith, this book delves into the core concepts of technology, offering valuable insights for readers.', 5, 5, 'TECH-689', 'uploads/covers/really_good.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(217, '9789792806321', 'History of War Vol 2', 'Jane Smith', 'Pearson', '2010', 5, 'A distinctive History book titled \'History of War Vol 2\'. Authored by Jane Smith, this book delves into the core concepts of history, offering valuable insights for readers.', 5, 5, 'HIST-758', 'uploads/covers/success.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(218, '9780401506314', 'Century of Peace', 'Michael Moore', 'Wiley', '2022', 5, 'A distinctive History book titled \'Century of Peace\'. Authored by Michael Moore, this book delves into the core concepts of history, offering valuable insights for readers.', 5, 5, 'HIST-648', 'uploads/covers/time_machine.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(219, '9786376756251', 'Molecular Motion', 'Sarah Wilson', 'Springer', '2012', 3, 'A distinctive Science book titled \'Molecular Motion\'. Authored by Sarah Wilson, this book delves into the core concepts of science, offering valuable insights for readers.', 12, 12, 'SCI-292', 'uploads/covers/two_spans.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(220, '9781040299778', 'Future Intelligence', 'Sarah Williams', 'Wiley', '2017', 4, 'A distinctive Technology book titled \'Future Intelligence\'. Authored by Sarah Williams, this book delves into the core concepts of technology, offering valuable insights for readers.', 6, 6, 'TECH-541', 'uploads/covers/understory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(221, '9789006016045', 'Ancient Rome', 'Robert Johnson', 'HarperCollins', '2016', 5, 'A distinctive History book titled \'Ancient Rome\'. Authored by Robert Johnson, this book delves into the core concepts of history, offering valuable insights for readers.', 7, 7, 'HIST-747', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(222, '9781555733567', 'Path to Happiness Vol 4', 'John Davis', 'HarperCollins', '2013', 9, 'A distinctive Self-Help book titled \'Path to Happiness Vol 4\'. Authored by John Davis, this book delves into the core concepts of self-help, offering valuable insights for readers.', 7, 7, 'SH-621', 'uploads/covers/bad.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(223, '9788992908097', 'Guide to Prose', 'Jane Williams', 'Oxford Press', '2012', 7, 'A distinctive Literature book titled \'Guide to Prose\'. Authored by Jane Williams, this book delves into the core concepts of literature, offering valuable insights for readers.', 15, 15, 'LIT-432', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(224, '9780280749889', 'Introduction to Knowledge Vol 5', 'Sarah Davis', 'Wiley', '2019', 2, 'A distinctive Non-Fiction book titled \'Introduction to Knowledge Vol 5\'. Authored by Sarah Davis, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 3, 3, 'NF-827', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(225, '9785731315003', 'Advanced Motion Vol 3', 'James Jones', 'Penguin', '2021', 3, 'A distinctive Science book titled \'Advanced Motion Vol 3\'. Authored by James Jones, this book delves into the core concepts of science, offering valuable insights for readers.', 15, 15, 'SCI-583', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(226, '9782625928440', 'Mastering Adventures Vol 1', 'David Wilson', 'HarperCollins', '2025', 10, 'A distinctive Children book titled \'Mastering Adventures Vol 1\'. Authored by David Wilson, this book delves into the core concepts of children, offering valuable insights for readers.', 11, 11, 'KIDS-864', 'uploads/covers/cherry.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(227, '9782498561004', 'Introduction to Knowledge', 'David Williams', 'Wiley', '2021', 2, 'A distinctive Non-Fiction book titled \'Introduction to Knowledge\'. Authored by David Williams, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 3, 3, 'NF-925', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(228, '9784231977042', 'Numbers of Proof', 'Bob Taylor', 'OReilly', '2013', 6, 'A distinctive Mathematics book titled \'Numbers of Proof\'. Authored by Bob Taylor, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 9, 9, 'MATH-397', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(229, '9786469877017', 'Introduction to Facts Vol 2', 'James Johnson', 'Pearson', '2016', 8, 'A distinctive Reference book titled \'Introduction to Facts Vol 2\'. Authored by James Johnson, this book delves into the core concepts of reference, offering valuable insights for readers.', 9, 9, 'REF-206', 'uploads/covers/formula.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(230, '9780332956036', 'Mastering Change', 'Sarah Smith', 'Cambridge', '2023', 9, 'A distinctive Self-Help book titled \'Mastering Change\'. Authored by Sarah Smith, this book delves into the core concepts of self-help, offering valuable insights for readers.', 8, 8, 'SH-130', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(231, '9780837959654', 'Battle for Europe Vol 1', 'David Johnson', 'Oxford Press', '2022', 5, 'A distinctive History book titled \'Battle for Europe Vol 1\'. Authored by David Johnson, this book delves into the core concepts of history, offering valuable insights for readers.', 3, 3, 'HIST-196', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(232, '9784119751385', 'Handbook of Prose', 'Robert Miller', 'Penguin', '2014', 7, 'A distinctive Literature book titled \'Handbook of Prose\'. Authored by Robert Miller, this book delves into the core concepts of literature, offering valuable insights for readers.', 4, 4, 'LIT-198', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(233, '9784871818266', 'The Hidden Time', 'Alice Johnson', 'Wiley', '2019', 1, 'A distinctive Fiction book titled \'The Hidden Time\'. Authored by Alice Johnson, this book delves into the core concepts of fiction, offering valuable insights for readers.', 3, 3, 'FIC-423', 'uploads/covers/memory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(234, '9784705560866', 'Cosmic Gravity Vol 4', 'Alice Smith', 'Oxford Press', '2021', 3, 'A distinctive Science book titled \'Cosmic Gravity Vol 4\'. Authored by Alice Smith, this book delves into the core concepts of science, offering valuable insights for readers.', 15, 15, 'SCI-905', 'uploads/covers/own_business.png', '2026-02-02 15:59:36', '2026-02-08 05:43:32', 1),
(235, '9783464154238', 'Dreams of Forest', 'Sarah Davis', 'Pearson', '2013', 1, 'A distinctive Fiction book titled \'Dreams of Forest\'. Authored by Sarah Davis, this book delves into the core concepts of fiction, offering valuable insights for readers.', 6, 6, 'FIC-740', 'uploads/covers/paradox.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(236, '9780538119986', 'Digital Systems', 'Jane Miller', 'HarperCollins', '2016', 4, 'A distinctive Technology book titled \'Digital Systems\'. Authored by Jane Miller, this book delves into the core concepts of technology, offering valuable insights for readers.', 3, 3, 'TECH-994', 'uploads/covers/really_good.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(237, '9784503076262', 'Guide to Prose Vol 2', 'Michael Miller', 'OReilly', '2025', 7, 'A distinctive Literature book titled \'Guide to Prose Vol 2\'. Authored by Michael Miller, this book delves into the core concepts of literature, offering valuable insights for readers.', 9, 9, 'LIT-465', 'uploads/covers/success.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(238, '9781819412819', 'Essentials of Facts', 'John Johnson', 'Scholastic', '2017', 8, 'A distinctive Reference book titled \'Essentials of Facts\'. Authored by John Johnson, this book delves into the core concepts of reference, offering valuable insights for readers.', 7, 7, 'REF-138', 'uploads/covers/time_machine.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(239, '9783425769291', 'Cyber Intelligence', 'James Miller', 'Cambridge', '2022', 4, 'A distinctive Technology book titled \'Cyber Intelligence\'. Authored by James Miller, this book delves into the core concepts of technology, offering valuable insights for readers.', 5, 5, 'TECH-859', 'uploads/covers/two_spans.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(240, '9787394322545', 'Mastering Knowledge', 'Robert Smith', 'Pearson', '2017', 2, 'A distinctive Non-Fiction book titled \'Mastering Knowledge\'. Authored by Robert Smith, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 14, 14, 'NF-284', 'uploads/covers/understory.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(241, '9788048833808', 'Guide to Growth Vol 2', 'Bob Smith', 'Oxford Press', '2011', 9, 'A distinctive Self-Help book titled \'Guide to Growth Vol 2\'. Authored by Bob Smith, this book delves into the core concepts of self-help, offering valuable insights for readers.', 9, 9, 'SH-815', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(242, '9787759461754', 'Understanding Knowledge', 'John Williams', 'Cambridge', '2023', 2, 'A distinctive Non-Fiction book titled \'Understanding Knowledge\'. Authored by John Williams, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 6, 6, 'NF-781', 'uploads/covers/bad.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(243, '9788196967204', 'Handbook of Knowledge', 'John Davis', 'OReilly', '2013', 2, 'A distinctive Non-Fiction book titled \'Handbook of Knowledge\'. Authored by John Davis, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 8, 8, 'NF-576', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(244, '9788063509532', 'Theory of Functions Vol 1', 'Sarah Wilson', 'OReilly', '2013', 6, 'A distinctive Mathematics book titled \'Theory of Functions Vol 1\'. Authored by Sarah Wilson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 4, 4, 'MATH-252', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(245, '9780995576023', 'Guide to Change', 'Emily Brown', 'Penguin', '2023', 9, 'A distinctive Self-Help book titled \'Guide to Change\'. Authored by Emily Brown, this book delves into the core concepts of self-help, offering valuable insights for readers.', 12, 12, 'SH-446', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(246, '9788234177642', 'Essentials of Adventures Vol 1', 'Sarah Jones', 'OReilly', '2011', 10, 'A distinctive Children book titled \'Essentials of Adventures Vol 1\'. Authored by Sarah Jones, this book delves into the core concepts of children, offering valuable insights for readers.', 3, 3, 'KIDS-479', 'uploads/covers/cherry.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(247, '9781696944157', 'Whispering Promise Vol 3', 'James Miller', 'Pearson', '2011', 1, 'A distinctive Fiction book titled \'Whispering Promise Vol 3\'. Authored by James Miller, this book delves into the core concepts of fiction, offering valuable insights for readers.', 7, 7, 'FIC-136', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(248, '9789021527129', 'Broken Journey', 'Emily Johnson', 'Cambridge', '2024', 1, 'A distinctive Fiction book titled \'Broken Journey\'. Authored by Emily Johnson, this book delves into the core concepts of fiction, offering valuable insights for readers.', 11, 11, 'FIC-410', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(249, '9789579172569', 'Medieval Greece', 'Michael Smith', 'Scholastic', '2020', 5, 'A distinctive History book titled \'Medieval Greece\'. Authored by Michael Smith, this book delves into the core concepts of history, offering valuable insights for readers.', 12, 12, 'HIST-492', 'uploads/covers/formula.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(250, '9784046055726', 'The Art of Happiness Vol 5', 'Jane Davis', 'OReilly', '2016', 9, 'A distinctive Self-Help book titled \'The Art of Happiness Vol 5\'. Authored by Jane Davis, this book delves into the core concepts of self-help, offering valuable insights for readers.', 12, 12, 'SH-798', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:36', '2026-02-08 07:56:32', 1),
(251, '9781979814783', 'World of Prose', 'Emily Jones', 'Springer', '2019', 7, 'A distinctive Literature book titled \'World of Prose\'. Authored by Emily Jones, this book delves into the core concepts of literature, offering valuable insights for readers.', 6, 6, 'LIT-413', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:36', '2026-02-07 16:16:59', 1),
(252, '9786590979714', 'Modern Universe Vol 4', 'Emily Wilson', 'OReilly', '2025', 3, 'A distinctive Science book titled \'Modern Universe Vol 4\'. Authored by Emily Wilson, this book delves into the core concepts of science, offering valuable insights for readers.', 12, 11, 'SCI-381', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:36', '2026-02-07 16:11:51', 1),
(253, '9789059585375', 'World of Adventures', 'Alice Taylor', 'Springer', '2016', 10, 'A distinctive Children book titled \'World of Adventures\'. Authored by Alice Taylor, this book delves into the core concepts of children, offering valuable insights for readers.', 6, 5, 'KIDS-726', 'uploads/covers/memory.png', '2026-02-02 15:59:36', '2026-02-07 16:11:51', 1),
(254, '9780446804676', 'Cosmic Genetics', 'Jane Moore', 'Penguin', '2010', 3, 'A distinctive Science book titled \'Cosmic Genetics\'. Authored by Jane Moore, this book delves into the core concepts of science, offering valuable insights for readers.', 10, 9, 'SCI-869', 'uploads/covers/own_business.png', '2026-02-02 15:59:36', '2026-02-07 16:11:51', 1),
(255, '9786824523806', 'Principles of Genetics', 'Alice Miller', 'HarperCollins', '2022', 3, 'A distinctive Science book titled \'Principles of Genetics\'. Authored by Alice Miller, this book delves into the core concepts of science, offering valuable insights for readers.', 13, 12, 'SCI-319', 'uploads/covers/paradox.png', '2026-02-02 15:59:36', '2026-02-07 16:11:51', 1),
(259, '9783284417839', 'Living Growth Vol 3', 'Sarah Johnson', 'HarperCollins', '2013', 9, 'A distinctive Self-Help book titled &#039;Living Growth Vol 3&#039;. Authored by Sarah Johnson, this book delves into the core concepts of self-help, offering valuable insights for readers.', 6, 6, 'SH-763', 'uploads/covers/two_spans.png', '2026-02-02 15:59:36', '2026-02-03 15:48:25', 0),
(260, '9784359943728', 'Handbook of Adventures', 'Jane Smith', 'Penguin', '2017', 10, 'A distinctive Children book titled &#039;Handbook of Adventures&#039;. Authored by Jane Smith, this book delves into the core concepts of children, offering valuable insights for readers.', 20, 18, 'KIDS-173', 'uploads/covers/understory.png', '2026-02-02 15:59:36', '2026-02-08 07:53:13', 1),
(261, '9785402875735', 'Essentials of Facts (2011 Edition)', 'David Wilson', 'HarperCollins', '2014', 8, 'A distinctive Reference book titled \'Essentials of Facts (2011 Edition)\'. Authored by David Wilson, this book delves into the core concepts of reference, offering valuable insights for readers.', 4, 3, 'REF-401', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:36', '2026-02-07 16:11:51', 1),
(262, '9787771159414', 'Advanced Motion', 'Sarah Brown', 'Penguin', '2022', 3, 'A distinctive Science book titled \'Advanced Motion\'. Authored by Sarah Brown, this book delves into the core concepts of science, offering valuable insights for readers.', 14, 13, 'SCI-590', 'uploads/covers/bad.png', '2026-02-02 15:59:36', '2026-02-07 16:11:51', 1),
(263, '9785629060595', 'World of Facts', 'John Davis', 'OReilly', '2010', 8, 'A distinctive Reference book titled \'World of Facts\'. Authored by John Davis, this book delves into the core concepts of reference, offering valuable insights for readers.', 12, 12, 'REF-198', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:36', '2026-02-08 07:55:38', 1),
(264, '9784167151243', 'Discrete Functions Vol 1', 'Robert Johnson', 'Oxford Press', '2018', 6, 'A distinctive Mathematics book titled \'Discrete Functions Vol 1\'. Authored by Robert Johnson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 14, 13, 'MATH-210', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:36', '2026-02-07 16:11:51', 1),
(265, '9788622062498', 'Understanding Facts', 'James Williams', 'Pearson', '2012', 8, 'A distinctive Reference book titled \'Understanding Facts\'. Authored by James Williams, this book delves into the core concepts of reference, offering valuable insights for readers.', 14, 14, 'REF-201', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:36', '2026-02-07 16:13:10', 1),
(266, '9789910661391', 'Eternal Sea', 'James Davis', 'Scholastic', '2018', 1, 'A distinctive Fiction book titled \'Eternal Sea\'. Authored by James Davis, this book delves into the core concepts of fiction, offering valuable insights for readers.', 5, 5, 'FIC-887', 'uploads/covers/cherry.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1);
INSERT INTO `books` (`book_id`, `isbn`, `title`, `author`, `publisher`, `publication_year`, `category_id`, `description`, `total_copies`, `available_copies`, `shelf_location`, `cover_image`, `created_at`, `updated_at`, `is_active`) VALUES
(267, '9785162597567', 'Introduction to Knowledge (2015 Edition)', 'Emily Miller', 'OReilly', '2019', 2, 'A distinctive Non-Fiction book titled \'Introduction to Knowledge (2015 Edition)\'. Authored by Emily Miller, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 8, 8, 'NF-984', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:36', '2026-02-07 15:07:25', 1),
(268, '9787301728443', 'The Comprehensive Knowledge Vol 1', 'Robert Davis', 'Cambridge', '2021', 2, 'A distinctive Non-Fiction book titled \'The Comprehensive Knowledge Vol 1\'. Authored by Robert Davis, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 12, 12, 'NF-603', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(269, '9784569229070', 'Numbers of Proof (2016 Edition)', 'Robert Davis', 'Cambridge', '2025', 6, 'A distinctive Mathematics book titled \'Numbers of Proof (2016 Edition)\'. Authored by Robert Davis, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 13, 13, 'MATH-427', 'uploads/covers/formula.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(270, '9785960429650', 'Essentials of Facts (2005 Edition)', 'John Jones', 'Penguin', '2012', 8, 'A distinctive Reference book titled \'Essentials of Facts (2005 Edition)\'. Authored by John Jones, this book delves into the core concepts of reference, offering valuable insights for readers.', 10, 10, 'REF-394', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(271, '9781810443397', 'Cyber Web', 'James Williams', 'Oxford Press', '2014', 4, 'A distinctive Technology book titled \'Cyber Web\'. Authored by James Williams, this book delves into the core concepts of technology, offering valuable insights for readers.', 8, 8, 'TECH-762', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(272, '9780187860948', 'Living Discipline', 'Jane Taylor', 'Pearson', '2018', 9, 'A distinctive Self-Help book titled \'Living Discipline\'. Authored by Jane Taylor, this book delves into the core concepts of self-help, offering valuable insights for readers.', 5, 5, 'SH-690', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(273, '9785464979316', 'Modern Gravity', 'Alice Miller', 'Cambridge', '2013', 3, 'A distinctive Science book titled \'Modern Gravity\'. Authored by Alice Miller, this book delves into the core concepts of science, offering valuable insights for readers.', 5, 5, 'SCI-833', 'uploads/covers/memory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(274, '9788728779252', 'Guide to Adventures Vol 2', 'Emily Brown', 'Springer', '2018', 10, 'A distinctive Children book titled \'Guide to Adventures Vol 2\'. Authored by Emily Brown, this book delves into the core concepts of children, offering valuable insights for readers.', 10, 10, 'KIDS-533', 'uploads/covers/own_business.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(275, '9787589523778', 'Mastering Wealth', 'Alice Miller', 'Penguin', '2011', 9, 'A distinctive Self-Help book titled \'Mastering Wealth\'. Authored by Alice Miller, this book delves into the core concepts of self-help, offering valuable insights for readers.', 11, 11, 'SH-977', 'uploads/covers/paradox.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(276, '9788221946415', 'Mastering Adventures (2006 Edition)', 'James Taylor', 'OReilly', '2014', 10, 'A distinctive Children book titled \'Mastering Adventures (2006 Edition)\'. Authored by James Taylor, this book delves into the core concepts of children, offering valuable insights for readers.', 6, 6, 'KIDS-894', 'uploads/covers/really_good.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(277, '9788643138392', 'Advanced Chemistry', 'Alice Taylor', 'Pearson', '2015', 3, 'A distinctive Science book titled \'Advanced Chemistry\'. Authored by Alice Taylor, this book delves into the core concepts of science, offering valuable insights for readers.', 3, 3, 'SCI-973', 'uploads/covers/success.png', '2026-02-02 15:59:36', '2026-02-07 15:07:38', 1),
(278, '9783976421282', 'Understanding Knowledge (2009 Edition)', 'Sarah Brown', 'Oxford Press', '2015', 2, 'A distinctive Non-Fiction book titled \'Understanding Knowledge (2009 Edition)\'. Authored by Sarah Brown, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 3, 3, 'NF-904', 'uploads/covers/time_machine.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(279, '9782738517152', 'Mastering Prose', 'Alice Taylor', 'Springer', '2019', 7, 'A distinctive Literature book titled \'Mastering Prose\'. Authored by Alice Taylor, this book delves into the core concepts of literature, offering valuable insights for readers.', 6, 6, 'LIT-676', 'uploads/covers/two_spans.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(280, '9786706591017', 'Introduction to Knowledge (2013 Edition)', 'Michael Brown', 'OReilly', '2012', 2, 'A distinctive Non-Fiction book titled \'Introduction to Knowledge (2013 Edition)\'. Authored by Michael Brown, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 5, 5, 'NF-923', 'uploads/covers/understory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(281, '9789117969688', 'Understanding Chemistry', 'Sarah Smith', 'HarperCollins', '2023', 3, 'A distinctive Science book titled \'Understanding Chemistry\'. Authored by Sarah Smith, this book delves into the core concepts of science, offering valuable insights for readers.', 11, 11, 'SCI-868', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(282, '9788876044629', 'Programming Intelligence', 'James Miller', 'Oxford Press', '2022', 4, 'A distinctive Technology book titled \'Programming Intelligence\'. Authored by James Miller, this book delves into the core concepts of technology, offering valuable insights for readers.', 13, 13, 'TECH-490', 'uploads/covers/bad.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(283, '9787511064787', 'Applied Systems Vol 2', 'Michael Williams', 'OReilly', '2025', 6, 'A distinctive Mathematics book titled \'Applied Systems Vol 2\'. Authored by Michael Williams, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 5, 5, 'MATH-767', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(284, '9789775012366', 'Power of Wealth', 'Alice Taylor', 'Penguin', '2019', 9, 'A distinctive Self-Help book titled \'Power of Wealth\'. Authored by Alice Taylor, this book delves into the core concepts of self-help, offering valuable insights for readers.', 13, 13, 'SH-821', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(285, '9786425371110', 'The Theory of Chemistry Vol 5', 'Emily Wilson', 'Wiley', '2024', 3, 'A distinctive Science book titled \'The Theory of Chemistry Vol 5\'. Authored by Emily Wilson, this book delves into the core concepts of science, offering valuable insights for readers.', 5, 5, 'SCI-586', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(286, '9788334816815', 'Geometry Probability', 'Emily Brown', 'OReilly', '2014', 6, 'A distinctive Mathematics book titled \'Geometry Probability\'. Authored by Emily Brown, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 4, 4, 'MATH-514', 'uploads/covers/cherry.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(287, '9780857870694', 'The Last Mountain', 'John Smith', 'HarperCollins', '2015', 1, 'A distinctive Fiction book titled \'The Last Mountain\'. Authored by John Smith, this book delves into the core concepts of fiction, offering valuable insights for readers.', 10, 10, 'FIC-712', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(288, '9786582536164', 'Essentials of Prose Vol 5', 'Mary Taylor', 'HarperCollins', '2016', 7, 'A distinctive Literature book titled \'Essentials of Prose Vol 5\'. Authored by Mary Taylor, this book delves into the core concepts of literature, offering valuable insights for readers.', 9, 9, 'LIT-277', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(289, '9789921644322', 'Geometry Functions Vol 3', 'Bob Jones', 'Cambridge', '2018', 6, 'A distinctive Mathematics book titled \'Geometry Functions Vol 3\'. Authored by Bob Jones, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 14, 14, 'MATH-382', 'uploads/covers/formula.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(290, '9781279742743', 'Shadows of City Vol 1', 'Jane Williams', 'Wiley', '2015', 1, 'A distinctive Fiction book titled \'Shadows of City Vol 1\'. Authored by Jane Williams, this book delves into the core concepts of fiction, offering valuable insights for readers.', 7, 7, 'FIC-682', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(291, '9786061628504', 'Handbook of Adventures (2015 Edition)', 'Robert Brown', 'Penguin', '2025', 10, 'A distinctive Children book titled \'Handbook of Adventures (2015 Edition)\'. Authored by Robert Brown, this book delves into the core concepts of children, offering valuable insights for readers.', 5, 5, 'KIDS-962', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(292, '9787928139835', 'The Comprehensive Knowledge', 'David Moore', 'Springer', '2012', 2, 'A distinctive Non-Fiction book titled \'The Comprehensive Knowledge\'. Authored by David Moore, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 11, 11, 'NF-146', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(293, '9787199440022', 'Essentials of Knowledge', 'David Davis', 'Springer', '2012', 2, 'A distinctive Non-Fiction book titled \'Essentials of Knowledge\'. Authored by David Davis, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 8, 8, 'NF-159', 'uploads/covers/memory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(294, '9780438269645', 'Digital Security Vol 5', 'John Moore', 'Scholastic', '2023', 4, 'A distinctive Technology book titled \'Digital Security Vol 5\'. Authored by John Moore, this book delves into the core concepts of technology, offering valuable insights for readers.', 5, 5, 'TECH-550', 'uploads/covers/own_business.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(295, '9786332840357', 'Guide to Facts', 'James Miller', 'Scholastic', '2016', 8, 'A distinctive Reference book titled \'Guide to Facts\'. Authored by James Miller, this book delves into the core concepts of reference, offering valuable insights for readers.', 15, 15, 'REF-372', 'uploads/covers/paradox.png', '2026-02-02 15:59:36', '2026-02-07 15:07:33', 1),
(296, '9784232528886', 'The Art of Focus Vol 4', 'Robert Taylor', 'HarperCollins', '2020', 9, 'A distinctive Self-Help book titled \'The Art of Focus Vol 4\'. Authored by Robert Taylor, this book delves into the core concepts of self-help, offering valuable insights for readers.', 14, 14, 'SH-135', 'uploads/covers/really_good.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(297, '9789187558365', 'Robotics Design Vol 4', 'Sarah Brown', 'Springer', '2016', 4, 'A distinctive Technology book titled \'Robotics Design Vol 4\'. Authored by Sarah Brown, this book delves into the core concepts of technology, offering valuable insights for readers.', 14, 14, 'TECH-989', 'uploads/covers/success.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(298, '9782998043046', 'The Hidden Time (2012 Edition)', 'John Davis', 'Springer', '2021', 1, 'A distinctive Fiction book titled \'The Hidden Time (2012 Edition)\'. Authored by John Davis, this book delves into the core concepts of fiction, offering valuable insights for readers.', 12, 12, 'FIC-468', 'uploads/covers/time_machine.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(299, '9781069462062', 'Introduction to Adventures', 'Sarah Jones', 'Oxford Press', '2022', 10, 'A distinctive Children book titled \'Introduction to Adventures\'. Authored by Sarah Jones, this book delves into the core concepts of children, offering valuable insights for readers.', 10, 10, 'KIDS-240', 'uploads/covers/two_spans.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(300, '9781262582928', 'Geometry Proof', 'Jane Moore', 'Pearson', '2019', 6, 'A distinctive Mathematics book titled \'Geometry Proof\'. Authored by Jane Moore, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 6, 6, 'MATH-413', 'uploads/covers/understory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(301, '9784399151233', 'Robotics Design Vol 2', 'Sarah Williams', 'Penguin', '2013', 4, 'A distinctive Technology book titled \'Robotics Design Vol 2\'. Authored by Sarah Williams, this book delves into the core concepts of technology, offering valuable insights for readers.', 6, 6, 'TECH-405', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(302, '9781217596832', 'Robotics Engineering', 'Jane Johnson', 'Springer', '2018', 4, 'A distinctive Technology book titled \'Robotics Engineering\'. Authored by Jane Johnson, this book delves into the core concepts of technology, offering valuable insights for readers.', 9, 9, 'TECH-454', 'uploads/covers/bad.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(303, '9786039953531', 'Whispering Soul', 'Jane Williams', 'Scholastic', '2016', 1, 'A distinctive Fiction book titled \'Whispering Soul\'. Authored by Jane Williams, this book delves into the core concepts of fiction, offering valuable insights for readers.', 10, 10, 'FIC-610', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(304, '9780388055846', 'The Last Sea', 'Mary Davis', 'Pearson', '2017', 1, 'A distinctive Fiction book titled \'The Last Sea\'. Authored by Mary Davis, this book delves into the core concepts of fiction, offering valuable insights for readers.', 4, 4, 'FIC-284', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(305, '9781839870091', 'Essentials of Knowledge (2022 Edition)', 'Alice Brown', 'OReilly', '2016', 2, 'A distinctive Non-Fiction book titled \'Essentials of Knowledge (2022 Edition)\'. Authored by Alice Brown, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 4, 4, 'NF-344', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(306, '9781939759236', 'Path to Life', 'John Williams', 'Wiley', '2019', 9, 'A distinctive Self-Help book titled \'Path to Life\'. Authored by John Williams, this book delves into the core concepts of self-help, offering valuable insights for readers.', 10, 10, 'SH-508', 'uploads/covers/cherry.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(307, '9789819480707', 'Discrete Dimensions', 'Alice Smith', 'Pearson', '2013', 6, 'A distinctive Mathematics book titled \'Discrete Dimensions\'. Authored by Alice Smith, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 10, 10, 'MATH-420', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(308, '9783984113128', 'World of Knowledge Vol 3', 'Bob Moore', 'Cambridge', '2015', 2, 'A distinctive Non-Fiction book titled \'World of Knowledge Vol 3\'. Authored by Bob Moore, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 4, 4, 'NF-287', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(309, '9783009698265', 'Robotics Web', 'Alice Jones', 'Scholastic', '2024', 4, 'A distinctive Technology book titled \'Robotics Web\'. Authored by Alice Jones, this book delves into the core concepts of technology, offering valuable insights for readers.', 14, 14, 'TECH-742', 'uploads/covers/formula.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(310, '9782783895938', 'Advanced Motion (2003 Edition)', 'Emily Moore', 'Scholastic', '2020', 3, 'A distinctive Science book titled \'Advanced Motion (2003 Edition)\'. Authored by Emily Moore, this book delves into the core concepts of science, offering valuable insights for readers.', 3, 3, 'SCI-465', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(311, '9789865969691', 'Essentials of Prose', 'Emily Johnson', 'Pearson', '2025', 7, 'A distinctive Literature book titled \'Essentials of Prose\'. Authored by Emily Johnson, this book delves into the core concepts of literature, offering valuable insights for readers.', 11, 11, 'LIT-649', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(312, '9782932369317', 'The Hidden Sky', 'Sarah Taylor', 'Cambridge', '2014', 1, 'A distinctive Fiction book titled \'The Hidden Sky\'. Authored by Sarah Taylor, this book delves into the core concepts of fiction, offering valuable insights for readers.', 6, 6, 'FIC-188', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(313, '9783270318610', 'Quantum Biology', 'Sarah Jones', 'Cambridge', '2015', 3, 'A distinctive Science book titled \'Quantum Biology\'. Authored by Sarah Jones, this book delves into the core concepts of science, offering valuable insights for readers.', 3, 3, 'SCI-531', 'uploads/covers/memory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(314, '9783545960621', 'Essentials of Knowledge Vol 5', 'David Brown', 'Pearson', '2019', 2, 'A distinctive Non-Fiction book titled \'Essentials of Knowledge Vol 5\'. Authored by David Brown, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 7, 7, 'NF-109', 'uploads/covers/own_business.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(315, '9784761644455', 'Introduction to Adventures (2005 Edition)', 'Michael Jones', 'HarperCollins', '2019', 10, 'A distinctive Children book titled \'Introduction to Adventures (2005 Edition)\'. Authored by Michael Jones, this book delves into the core concepts of children, offering valuable insights for readers.', 6, 6, 'KIDS-486', 'uploads/covers/paradox.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(316, '9780092426484', 'History of Queens', 'John Moore', 'HarperCollins', '2023', 5, 'A distinctive History book titled \'History of Queens\'. Authored by John Moore, this book delves into the core concepts of history, offering valuable insights for readers.', 9, 9, 'HIST-458', 'uploads/covers/really_good.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(317, '9780591752216', 'Mindset for Freedom', 'Emily Brown', 'Wiley', '2016', 9, 'A distinctive Self-Help book titled \'Mindset for Freedom\'. Authored by Emily Brown, this book delves into the core concepts of self-help, offering valuable insights for readers.', 14, 14, 'SH-678', 'uploads/covers/success.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(318, '9789833553481', 'Understanding Genetics', 'Robert Miller', 'Oxford Press', '2025', 3, 'A distinctive Science book titled \'Understanding Genetics\'. Authored by Robert Miller, this book delves into the core concepts of science, offering valuable insights for readers.', 6, 6, 'SCI-398', 'uploads/covers/time_machine.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(319, '9786200485774', 'World of Facts (2017 Edition)', 'Bob Brown', 'Pearson', '2025', 8, 'A distinctive Reference book titled \'World of Facts (2017 Edition)\'. Authored by Bob Brown, this book delves into the core concepts of reference, offering valuable insights for readers.', 15, 15, 'REF-118', 'uploads/covers/two_spans.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(320, '9789771310809', 'Whispering Time', 'Alice Wilson', 'Springer', '2021', 1, 'A distinctive Fiction book titled \'Whispering Time\'. Authored by Alice Wilson, this book delves into the core concepts of fiction, offering valuable insights for readers.', 10, 10, 'FIC-792', 'uploads/covers/understory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(321, '9784977698689', 'Essentials of Prose (2016 Edition)', 'Michael Johnson', 'Scholastic', '2022', 7, 'A distinctive Literature book titled \'Essentials of Prose (2016 Edition)\'. Authored by Michael Johnson, this book delves into the core concepts of literature, offering valuable insights for readers.', 11, 11, 'LIT-957', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(322, '9780589911461', 'Algebra Design', 'Mary Miller', 'Oxford Press', '2011', 6, 'A distinctive Mathematics book titled \'Algebra Design\'. Authored by Mary Miller, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 4, 4, 'MATH-498', 'uploads/covers/bad.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(323, '9782378256401', 'Understanding Knowledge (2010 Edition)', 'Bob Smith', 'OReilly', '2015', 2, 'A distinctive Non-Fiction book titled \'Understanding Knowledge (2010 Edition)\'. Authored by Bob Smith, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 9, 9, 'NF-422', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(324, '9780391520051', 'Quantum Motion Vol 2', 'Michael Brown', 'HarperCollins', '2011', 3, 'A distinctive Science book titled \'Quantum Motion Vol 2\'. Authored by Michael Brown, this book delves into the core concepts of science, offering valuable insights for readers.', 5, 5, 'SCI-992', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(325, '9781991489962', 'Data Security', 'Emily Wilson', 'Scholastic', '2011', 4, 'A distinctive Technology book titled \'Data Security\'. Authored by Emily Wilson, this book delves into the core concepts of technology, offering valuable insights for readers.', 5, 5, 'TECH-151', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(326, '9784084830117', 'Handbook of Knowledge (2023 Edition)', 'Bob Taylor', 'Oxford Press', '2015', 2, 'A distinctive Non-Fiction book titled \'Handbook of Knowledge (2023 Edition)\'. Authored by Bob Taylor, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 12, 12, 'NF-656', 'uploads/covers/cherry.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(327, '9782225298949', 'Essentials of Prose (2010 Edition)', 'David Miller', 'Wiley', '2017', 7, 'A distinctive Literature book titled \'Essentials of Prose (2010 Edition)\'. Authored by David Miller, this book delves into the core concepts of literature, offering valuable insights for readers.', 11, 11, 'LIT-506', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(328, '9784887019427', 'World of Facts (2019 Edition)', 'Robert Moore', 'OReilly', '2020', 8, 'A distinctive Reference book titled \'World of Facts (2019 Edition)\'. Authored by Robert Moore, this book delves into the core concepts of reference, offering valuable insights for readers.', 7, 7, 'REF-778', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(329, '9789715501577', 'Guide to Adventures', 'Emily Miller', 'Pearson', '2012', 10, 'A distinctive Children book titled \'Guide to Adventures\'. Authored by Emily Miller, this book delves into the core concepts of children, offering valuable insights for readers.', 10, 10, 'KIDS-942', 'uploads/covers/formula.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(330, '9784558575318', 'The Comprehensive Adventures', 'David Davis', 'HarperCollins', '2016', 10, 'A distinctive Children book titled \'The Comprehensive Adventures\'. Authored by David Davis, this book delves into the core concepts of children, offering valuable insights for readers.', 15, 15, 'KIDS-734', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(331, '9781780219854', 'Essentials of Knowledge Vol 1', 'Alice Williams', 'Wiley', '2016', 2, 'A distinctive Non-Fiction book titled \'Essentials of Knowledge Vol 1\'. Authored by Alice Williams, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 3, 3, 'NF-417', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(332, '9789891118834', 'Path to Life (2001 Edition)', 'David Williams', 'Scholastic', '2022', 9, 'A distinctive Self-Help book titled \'Path to Life (2001 Edition)\'. Authored by David Williams, this book delves into the core concepts of self-help, offering valuable insights for readers.', 13, 13, 'SH-635', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(333, '9782420654374', 'World of Knowledge Vol 3 (2024 Edition)', 'John Moore', 'Scholastic', '2015', 2, 'A distinctive Non-Fiction book titled \'World of Knowledge Vol 3 (2024 Edition)\'. Authored by John Moore, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 13, 13, 'NF-925', 'uploads/covers/memory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(334, '9788267691100', 'Introduction to Adventures (2015 Edition)', 'James Wilson', 'Pearson', '2025', 10, 'A distinctive Children book titled \'Introduction to Adventures (2015 Edition)\'. Authored by James Wilson, this book delves into the core concepts of children, offering valuable insights for readers.', 6, 6, 'KIDS-114', 'uploads/covers/own_business.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(335, '9783393578416', 'Handbook of Adventures (2000 Edition)', 'John Smith', 'Wiley', '2015', 10, 'A distinctive Children book titled \'Handbook of Adventures (2000 Edition)\'. Authored by John Smith, this book delves into the core concepts of children, offering valuable insights for readers.', 15, 15, 'KIDS-221', 'uploads/covers/paradox.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(336, '9783969274143', 'Advanced Biology', 'Robert Davis', 'Scholastic', '2012', 3, 'A distinctive Science book titled \'Advanced Biology\'. Authored by Robert Davis, this book delves into the core concepts of science, offering valuable insights for readers.', 3, 3, 'SCI-682', 'uploads/covers/really_good.png', '2026-02-02 15:59:36', '2026-02-07 15:07:27', 1),
(337, '9787726251127', 'The Comprehensive Facts', 'Robert Davis', 'OReilly', '2022', 8, 'A distinctive Reference book titled \'The Comprehensive Facts\'. Authored by Robert Davis, this book delves into the core concepts of reference, offering valuable insights for readers.', 4, 4, 'REF-365', 'uploads/covers/success.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(338, '9784589283167', 'Civilizations of War Vol 2', 'David Johnson', 'Pearson', '2012', 5, 'A distinctive History book titled \'Civilizations of War Vol 2\'. Authored by David Johnson, this book delves into the core concepts of history, offering valuable insights for readers.', 11, 11, 'HIST-363', 'uploads/covers/time_machine.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(339, '9780640525080', 'Algebra Design (2018 Edition)', 'Robert Williams', 'Springer', '2012', 6, 'A distinctive Mathematics book titled \'Algebra Design (2018 Edition)\'. Authored by Robert Williams, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 13, 13, 'MATH-953', 'uploads/covers/two_spans.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(340, '9780600848358', 'Guide to Adventures Vol 5', 'Jane Johnson', 'Oxford Press', '2019', 10, 'A distinctive Children book titled \'Guide to Adventures Vol 5\'. Authored by Jane Johnson, this book delves into the core concepts of children, offering valuable insights for readers.', 6, 6, 'KIDS-387', 'uploads/covers/understory.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(341, '9789286554572', 'Understanding Facts Vol 3', 'John Brown', 'HarperCollins', '2023', 8, 'A distinctive Reference book titled \'Understanding Facts Vol 3\'. Authored by John Brown, this book delves into the core concepts of reference, offering valuable insights for readers.', 15, 15, 'REF-247', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(342, '9782080260091', 'Robotics Web (2025 Edition)', 'Emily Wilson', 'OReilly', '2012', 4, 'A distinctive Technology book titled \'Robotics Web (2025 Edition)\'. Authored by Emily Wilson, this book delves into the core concepts of technology, offering valuable insights for readers.', 9, 9, 'TECH-666', 'uploads/covers/bad.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(343, '9784333931114', 'Understanding Prose', 'Emily Wilson', 'HarperCollins', '2020', 7, 'A distinctive Literature book titled \'Understanding Prose\'. Authored by Emily Wilson, this book delves into the core concepts of literature, offering valuable insights for readers.', 9, 9, 'LIT-305', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(344, '9788418526196', 'The Last Sky', 'Mary Taylor', 'Wiley', '2021', 1, 'A distinctive Fiction book titled \'The Last Sky\'. Authored by Mary Taylor, this book delves into the core concepts of fiction, offering valuable insights for readers.', 13, 13, 'FIC-208', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(345, '9783440857453', 'Century of Greece Vol 1', 'Michael Miller', 'Scholastic', '2015', 5, 'A distinctive History book titled \'Century of Greece Vol 1\'. Authored by Michael Miller, this book delves into the core concepts of history, offering valuable insights for readers.', 14, 14, 'HIST-371', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(346, '9784487743096', 'Software Coding Vol 1', 'Alice Davis', 'HarperCollins', '2011', 4, 'A distinctive Technology book titled \'Software Coding Vol 1\'. Authored by Alice Davis, this book delves into the core concepts of technology, offering valuable insights for readers.', 15, 15, 'TECH-917', 'uploads/covers/cherry.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(347, '9781442650647', 'World of Adventures Vol 1', 'James Davis', 'Oxford Press', '2021', 10, 'A distinctive Children book titled \'World of Adventures Vol 1\'. Authored by James Davis, this book delves into the core concepts of children, offering valuable insights for readers.', 12, 12, 'KIDS-408', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(348, '9786690619514', 'Introduction to Knowledge (2009 Edition)', 'Sarah Smith', 'Scholastic', '2013', 2, 'A distinctive Non-Fiction book titled \'Introduction to Knowledge (2009 Edition)\'. Authored by Sarah Smith, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 6, 6, 'NF-408', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(349, '9782980523568', 'The Comprehensive Adventures (2020 Edition)', 'David Miller', 'HarperCollins', '2014', 10, 'A distinctive Children book titled \'The Comprehensive Adventures (2020 Edition)\'. Authored by David Miller, this book delves into the core concepts of children, offering valuable insights for readers.', 11, 11, 'KIDS-208', 'uploads/covers/formula.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(350, '9782481777328', 'Cloud Algorithms', 'David Miller', 'OReilly', '2012', 4, 'A distinctive Technology book titled \'Cloud Algorithms\'. Authored by David Miller, this book delves into the core concepts of technology, offering valuable insights for readers.', 12, 12, 'TECH-325', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(351, '9786016745212', 'Robotics Architecture', 'Mary Williams', 'Cambridge', '2024', 4, 'A distinctive Technology book titled \'Robotics Architecture\'. Authored by Mary Williams, this book delves into the core concepts of technology, offering valuable insights for readers.', 12, 12, 'TECH-931', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(352, '9781761115258', 'Data Engineering', 'Sarah Johnson', 'Cambridge', '2017', 4, 'A distinctive Technology book titled \'Data Engineering\'. Authored by Sarah Johnson, this book delves into the core concepts of technology, offering valuable insights for readers.', 7, 7, 'TECH-992', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(353, '9785148674674', 'Habits of Freedom', 'Alice Taylor', 'OReilly', '2025', 9, 'A distinctive Self-Help book titled \'Habits of Freedom\'. Authored by Alice Taylor, this book delves into the core concepts of self-help, offering valuable insights for readers.', 7, 7, 'SH-229', 'uploads/covers/memory.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(354, '9788856314130', 'Principles of Physics', 'Emily Jones', 'HarperCollins', '2017', 3, 'A distinctive Science book titled \'Principles of Physics\'. Authored by Emily Jones, this book delves into the core concepts of science, offering valuable insights for readers.', 11, 11, 'SCI-624', 'uploads/covers/own_business.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(355, '9789730935843', 'Essentials of Prose (2022 Edition)', 'Jane Jones', 'OReilly', '2020', 7, 'A distinctive Literature book titled \'Essentials of Prose (2022 Edition)\'. Authored by Jane Jones, this book delves into the core concepts of literature, offering valuable insights for readers.', 15, 15, 'LIT-320', 'uploads/covers/paradox.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(356, '9789273279896', 'Cyber Design', 'Sarah Davis', 'Wiley', '2014', 4, 'A distinctive Technology book titled \'Cyber Design\'. Authored by Sarah Davis, this book delves into the core concepts of technology, offering valuable insights for readers.', 15, 15, 'TECH-211', 'uploads/covers/really_good.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(357, '9785010004447', 'The Silent City', 'John Brown', 'OReilly', '2024', 1, 'A distinctive Fiction book titled \'The Silent City\'. Authored by John Brown, this book delves into the core concepts of fiction, offering valuable insights for readers.', 3, 3, 'FIC-381', 'uploads/covers/success.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(358, '9781677262906', 'Essentials of Adventures', 'Sarah Smith', 'Springer', '2024', 10, 'A distinctive Children book titled \'Essentials of Adventures\'. Authored by Sarah Smith, this book delves into the core concepts of children, offering valuable insights for readers.', 3, 3, 'KIDS-992', 'uploads/covers/time_machine.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(359, '9787313693021', 'Understanding Adventures', 'Jane Smith', 'HarperCollins', '2019', 10, 'A distinctive Children book titled \'Understanding Adventures\'. Authored by Jane Smith, this book delves into the core concepts of children, offering valuable insights for readers.', 11, 11, 'KIDS-437', 'uploads/covers/two_spans.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(360, '9784120606546', 'Century of Queens', 'Emily Miller', 'Springer', '2014', 5, 'A distinctive History book titled \'Century of Queens\'. Authored by Emily Miller, this book delves into the core concepts of history, offering valuable insights for readers.', 3, 3, 'HIST-189', 'uploads/covers/understory.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(361, '9788413812738', 'Software Algorithms', 'James Smith', 'Pearson', '2025', 4, 'A distinctive Technology book titled \'Software Algorithms\'. Authored by James Smith, this book delves into the core concepts of technology, offering valuable insights for readers.', 8, 8, 'TECH-846', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(362, '9786702160187', 'World of Adventures Vol 5', 'Robert Miller', 'Springer', '2014', 10, 'A distinctive Children book titled \'World of Adventures Vol 5\'. Authored by Robert Miller, this book delves into the core concepts of children, offering valuable insights for readers.', 11, 11, 'KIDS-397', 'uploads/covers/bad.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(363, '9783607953324', 'Mindset for Confidence Vol 2', 'John Wilson', 'Springer', '2012', 9, 'A distinctive Self-Help book titled \'Mindset for Confidence Vol 2\'. Authored by John Wilson, this book delves into the core concepts of self-help, offering valuable insights for readers.', 3, 3, 'SH-601', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(364, '9784946510699', 'Cloud Systems Vol 1', 'David Johnson', 'HarperCollins', '2017', 4, 'A distinctive Technology book titled \'Cloud Systems Vol 1\'. Authored by David Johnson, this book delves into the core concepts of technology, offering valuable insights for readers.', 11, 11, 'TECH-132', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(365, '9783075664819', 'The Last Mountain Vol 1', 'David Jones', 'Oxford Press', '2020', 1, 'A distinctive Fiction book titled \'The Last Mountain Vol 1\'. Authored by David Jones, this book delves into the core concepts of fiction, offering valuable insights for readers.', 13, 13, 'FIC-496', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(366, '9782713467501', 'Mindset for Confidence', 'David Taylor', 'HarperCollins', '2012', 9, 'A distinctive Self-Help book titled \'Mindset for Confidence\'. Authored by David Taylor, this book delves into the core concepts of self-help, offering valuable insights for readers.', 15, 15, 'SH-149', 'uploads/covers/cherry.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(367, '9785758606125', 'Understanding Adventures Vol 2', 'John Davis', 'Oxford Press', '2021', 10, 'A distinctive Children book titled \'Understanding Adventures Vol 2\'. Authored by John Davis, this book delves into the core concepts of children, offering valuable insights for readers.', 6, 6, 'KIDS-336', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(368, '9785012855611', 'Thinking Leadership', 'James Brown', 'Pearson', '2017', 9, 'A distinctive Self-Help book titled \'Thinking Leadership\'. Authored by James Brown, this book delves into the core concepts of self-help, offering valuable insights for readers.', 15, 15, 'SH-696', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(369, '9783433814791', 'Guide to Prose Vol 4', 'Jane Johnson', 'Springer', '2017', 7, 'A distinctive Literature book titled \'Guide to Prose Vol 4\'. Authored by Jane Johnson, this book delves into the core concepts of literature, offering valuable insights for readers.', 3, 3, 'LIT-754', 'uploads/covers/formula.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(370, '9780701232332', 'Introduction to Prose Vol 2', 'Emily Brown', 'HarperCollins', '2012', 7, 'A distinctive Literature book titled \'Introduction to Prose Vol 2\'. Authored by Emily Brown, this book delves into the core concepts of literature, offering valuable insights for readers.', 13, 12, 'LIT-730', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:36', '2026-02-08 07:57:02', 1),
(371, '9784452400995', 'Golden Time', 'Bob Taylor', 'Oxford Press', '2011', 1, 'A distinctive Fiction book titled \'Golden Time\'. Authored by Bob Taylor, this book delves into the core concepts of fiction, offering valuable insights for readers.', 3, 3, 'FIC-794', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(372, '9782425909809', 'The Theory of Cells', 'James Smith', 'Scholastic', '2023', 3, 'A distinctive Science book titled \'The Theory of Cells\'. Authored by James Smith, this book delves into the core concepts of science, offering valuable insights for readers.', 14, 14, 'SCI-516', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(373, '9783893454154', 'Handbook of Knowledge Vol 5', 'John Moore', 'Scholastic', '2010', 2, 'A distinctive Non-Fiction book titled \'Handbook of Knowledge Vol 5\'. Authored by John Moore, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 14, 14, 'NF-224', 'uploads/covers/memory.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(374, '9786643076718', 'Guide to Facts Vol 1', 'James Smith', 'Scholastic', '2013', 8, 'A distinctive Reference book titled \'Guide to Facts Vol 1\'. Authored by James Smith, this book delves into the core concepts of reference, offering valuable insights for readers.', 5, 5, 'REF-896', 'uploads/covers/own_business.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(375, '9789956281973', 'Molecular Chemistry', 'James Taylor', 'Oxford Press', '2024', 3, 'A distinctive Science book titled \'Molecular Chemistry\'. Authored by James Taylor, this book delves into the core concepts of science, offering valuable insights for readers.', 12, 12, 'SCI-691', 'uploads/covers/paradox.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(376, '9789164468405', 'Logic and Proof', 'Mary Johnson', 'Penguin', '2017', 6, 'A distinctive Mathematics book titled \'Logic and Proof\'. Authored by Mary Johnson, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 15, 15, 'MATH-792', 'uploads/covers/really_good.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(377, '9782152527807', 'The Fall of Greece', 'Robert Smith', 'HarperCollins', '2017', 5, 'A distinctive History book titled \'The Fall of Greece\'. Authored by Robert Smith, this book delves into the core concepts of history, offering valuable insights for readers.', 11, 11, 'HIST-921', 'uploads/covers/success.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(378, '9784889001511', 'Cosmic Universe', 'Sarah Taylor', 'Wiley', '2010', 3, 'A distinctive Science book titled \'Cosmic Universe\'. Authored by Sarah Taylor, this book delves into the core concepts of science, offering valuable insights for readers.', 3, 3, 'SCI-819', 'uploads/covers/time_machine.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(379, '9784307324821', 'Introduction to Prose Vol 3', 'Bob Davis', 'Springer', '2014', 7, 'A distinctive Literature book titled \'Introduction to Prose Vol 3\'. Authored by Bob Davis, this book delves into the core concepts of literature, offering valuable insights for readers.', 13, 13, 'LIT-733', 'uploads/covers/two_spans.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(380, '9784568202518', 'Discrete Structures', 'Robert Jones', 'Springer', '2021', 6, 'A distinctive Mathematics book titled \'Discrete Structures\'. Authored by Robert Jones, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 4, 4, 'MATH-791', 'uploads/covers/understory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(381, '9787248630286', 'Handbook of Facts', 'Bob Williams', 'HarperCollins', '2016', 8, 'A distinctive Reference book titled \'Handbook of Facts\'. Authored by Bob Williams, this book delves into the core concepts of reference, offering valuable insights for readers.', 6, 6, 'REF-661', 'uploads/covers/a_million_to_one.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(382, '9783512921955', 'Essentials of Knowledge (2001 Edition)', 'Sarah Davis', 'Cambridge', '2014', 2, 'A distinctive Non-Fiction book titled \'Essentials of Knowledge (2001 Edition)\'. Authored by Sarah Davis, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 10, 10, 'NF-531', 'uploads/covers/bad.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(383, '9785166976490', 'The Fall of Europe Vol 1', 'Alice Moore', 'Penguin', '2021', 5, 'A distinctive History book titled \'The Fall of Europe Vol 1\'. Authored by Alice Moore, this book delves into the core concepts of history, offering valuable insights for readers.', 10, 10, 'HIST-903', 'uploads/covers/beyond_the_ocean__door.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(384, '9788071338196', 'Whispering Sky', 'Robert Smith', 'Penguin', '2014', 1, 'A distinctive Fiction book titled \'Whispering Sky\'. Authored by Robert Smith, this book delves into the core concepts of fiction, offering valuable insights for readers.', 13, 13, 'FIC-879', 'uploads/covers/bigger&better.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(385, '9783569292702', 'Introduction to Adventures Vol 4', 'James Miller', 'Penguin', '2019', 10, 'A distinctive Children book titled \'Introduction to Adventures Vol 4\'. Authored by James Miller, this book delves into the core concepts of children, offering valuable insights for readers.', 4, 4, 'KIDS-862', 'uploads/covers/birds_of_a_feather.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(386, '9783547516534', 'The Theory of Physics Vol 3', 'David Jones', 'Oxford Press', '2020', 3, 'A distinctive Science book titled \'The Theory of Physics Vol 3\'. Authored by David Jones, this book delves into the core concepts of science, offering valuable insights for readers.', 15, 15, 'SCI-294', 'uploads/covers/cherry.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(387, '9782796260890', 'Cosmic Energy Vol 5', 'David Davis', 'Penguin', '2025', 3, 'A distinctive Science book titled \'Cosmic Energy Vol 5\'. Authored by David Davis, this book delves into the core concepts of science, offering valuable insights for readers.', 8, 8, 'SCI-933', 'uploads/covers/debbie_berne.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(388, '9788925706092', 'Lost in City', 'Emily Johnson', 'Oxford Press', '2022', 1, 'A distinctive Fiction book titled \'Lost in City\'. Authored by Emily Johnson, this book delves into the core concepts of fiction, offering valuable insights for readers.', 14, 14, 'FIC-175', 'uploads/covers/enchanted_to_meet_you.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(389, '9787367911144', 'Evolution of Genetics', 'Emily Jones', 'Pearson', '2017', 3, 'A distinctive Science book titled \'Evolution of Genetics\'. Authored by Emily Jones, this book delves into the core concepts of science, offering valuable insights for readers.', 7, 7, 'SCI-569', 'uploads/covers/formula.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(390, '9788726109276', 'The Comprehensive Facts (2011 Edition)', 'Jane Williams', 'HarperCollins', '2013', 8, 'A distinctive Reference book titled \'The Comprehensive Facts (2011 Edition)\'. Authored by Jane Williams, this book delves into the core concepts of reference, offering valuable insights for readers.', 13, 13, 'REF-995', 'uploads/covers/green_witchcraft.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(391, '9789396483655', 'Modern Biology', 'Emily Moore', 'Springer', '2023', 3, 'A distinctive Science book titled \'Modern Biology\'. Authored by Emily Moore, this book delves into the core concepts of science, offering valuable insights for readers.', 14, 14, 'SCI-674', 'uploads/covers/harry_potter.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(392, '9782386441988', 'Essentials of Adventures (2018 Edition)', 'John Brown', 'Wiley', '2019', 10, 'A distinctive Children book titled \'Essentials of Adventures (2018 Edition)\'. Authored by John Brown, this book delves into the core concepts of children, offering valuable insights for readers.', 7, 7, 'KIDS-122', 'uploads/covers/man_in_the_woods.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(393, '9780926459169', 'Understanding Knowledge (2024 Edition)', 'David Miller', 'Scholastic', '2021', 2, 'A distinctive Non-Fiction book titled \'Understanding Knowledge (2024 Edition)\'. Authored by David Miller, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 10, 10, 'NF-644', 'uploads/covers/memory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(394, '9782363033994', 'Medieval War', 'Alice Miller', 'HarperCollins', '2018', 5, 'A distinctive History book titled \'Medieval War\'. Authored by Alice Miller, this book delves into the core concepts of history, offering valuable insights for readers.', 6, 6, 'HIST-668', 'uploads/covers/own_business.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(395, '9780883466904', 'Discrete Variables', 'Mary Smith', 'Scholastic', '2021', 6, 'A distinctive Mathematics book titled \'Discrete Variables\'. Authored by Mary Smith, this book delves into the core concepts of mathematics, offering valuable insights for readers.', 7, 7, 'MATH-541', 'uploads/covers/paradox.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(396, '9780476197827', 'Handbook of Knowledge Vol 4', 'James Moore', 'Cambridge', '2016', 2, 'A distinctive Non-Fiction book titled \'Handbook of Knowledge Vol 4\'. Authored by James Moore, this book delves into the core concepts of non-fiction, offering valuable insights for readers.', 14, 14, 'NF-790', 'uploads/covers/really_good.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(397, '9786353717696', 'Evolution of Physics', 'Bob Davis', 'Wiley', '2016', 3, 'A distinctive Science book titled \'Evolution of Physics\'. Authored by Bob Davis, this book delves into the core concepts of science, offering valuable insights for readers.', 8, 8, 'SCI-263', 'uploads/covers/success.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1);
INSERT INTO `books` (`book_id`, `isbn`, `title`, `author`, `publisher`, `publication_year`, `category_id`, `description`, `total_copies`, `available_copies`, `shelf_location`, `cover_image`, `created_at`, `updated_at`, `is_active`) VALUES
(398, '9787306666810', 'The Theory of Physics', 'John Brown', 'Oxford Press', '2013', 3, 'A distinctive Science book titled \'The Theory of Physics\'. Authored by John Brown, this book delves into the core concepts of science, offering valuable insights for readers.', 5, 5, 'SCI-220', 'uploads/covers/time_machine.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1),
(399, '9788365305588', 'Mindset for Focus', 'Bob Moore', 'Cambridge', '2016', 9, 'A distinctive Self-Help book titled \'Mindset for Focus\'. Authored by Bob Moore, this book delves into the core concepts of self-help, offering valuable insights for readers.', 15, 15, 'SH-991', 'uploads/covers/two_spans.png', '2026-02-02 15:59:36', '2026-02-02 15:59:36', 1),
(400, '9789493740320', 'Golden Soul', 'James Taylor', 'Wiley', '2020', 1, 'A distinctive Fiction book titled \'Golden Soul\'. Authored by James Taylor, this book delves into the core concepts of fiction, offering valuable insights for readers.', 9, 9, 'FIC-790', 'uploads/covers/understory.png', '2026-02-02 15:59:36', '2026-02-03 14:24:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`) VALUES
(1, 'Fiction', 'Novels, short stories, and literary fiction'),
(2, 'Non-Fiction', 'Biographies, essays, and factual books'),
(3, 'Science', 'Physics, Chemistry, Biology, and scientific literature'),
(4, 'Technology', 'Computer Science, Engineering, and tech books'),
(5, 'History', 'Historical accounts and world history'),
(6, 'Mathematics', 'Algebra, Calculus, Statistics, and more'),
(7, 'Literature', 'Poetry, Drama, and classic literature'),
(8, 'Reference', 'Dictionaries, Encyclopedias, and reference books'),
(9, 'Self-Help', 'Personal development and motivational books'),
(10, 'Children', 'Books for young readers');

-- --------------------------------------------------------

--
-- Table structure for table `issued_books`
--

DROP TABLE IF EXISTS `issued_books`;
CREATE TABLE `issued_books` (
  `issue_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `issued_by` int(11) DEFAULT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `fine_amount` decimal(10,2) DEFAULT 0.00,
  `fine_paid` tinyint(1) DEFAULT 0,
  `status` enum('issued','returned','overdue','requested','cancelled') DEFAULT 'requested'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issued_books`
--

INSERT INTO `issued_books` (`issue_id`, `book_id`, `user_id`, `issued_by`, `issue_date`, `due_date`, `return_date`, `fine_amount`, `fine_paid`, `status`) VALUES
(5, 67, 8, 7, '2026-02-03', '2026-02-19', '2026-02-03', 0.00, 0, 'returned'),
(6, 336, 8, 7, '2026-02-03', '2026-02-19', '2026-02-03', 0.00, 0, 'returned'),
(7, 262, 8, 7, '2026-02-03', '2026-02-19', '2026-02-07', 0.00, 0, 'returned'),
(8, 267, 8, 7, '2026-02-03', '2026-02-19', '2026-02-07', 0.00, 0, 'returned'),
(9, 336, 11, 7, '2026-02-03', '2026-02-19', '2026-02-07', 0.00, 0, 'returned'),
(10, 336, 8, 7, '2026-02-03', '2026-02-19', '2026-02-07', 0.00, 0, 'returned'),
(11, 298, 13, NULL, '2026-02-03', '2026-02-18', NULL, 0.00, 0, 'cancelled'),
(12, 277, 13, 7, '2026-02-03', '2026-02-19', '2026-02-07', 0.00, 0, 'returned'),
(13, 133, 13, NULL, '2026-02-03', '2026-02-18', NULL, 0.00, 0, 'cancelled'),
(14, 295, 9, 7, '2026-02-04', '2026-02-20', '2026-02-07', 0.00, 0, 'returned'),
(15, 260, 9, 7, '2026-02-04', '2026-02-20', '2026-02-04', 0.00, 0, 'returned'),
(16, 260, 9, 7, '2026-02-04', '2026-02-20', '2026-02-07', 0.00, 0, 'returned'),
(17, 261, 9, 7, '2026-02-04', '2026-02-20', '2026-02-07', 0.00, 0, 'returned'),
(18, 277, 12, 7, '2026-02-04', '2026-02-20', '2026-02-07', 0.00, 0, 'returned'),
(19, 250, 8, 7, '2026-01-01', '2026-01-15', '2026-02-07', 11.50, 0, 'returned'),
(20, 251, 8, 7, '2026-01-05', '2026-01-20', '2026-02-07', 9.00, 0, 'returned'),
(21, 252, 8, 7, '2026-01-10', '2026-01-25', '2026-02-07', 50.00, 1, 'returned'),
(22, 253, 9, 7, '2026-01-01', '2026-01-15', '2026-02-07', 11.50, 0, 'returned'),
(23, 254, 9, 7, '2026-01-05', '2026-01-20', '2026-02-07', 9.00, 1, 'returned'),
(24, 255, 9, 7, '2026-01-10', '2026-01-25', '2026-02-07', 6.50, 1, 'returned'),
(25, 250, 8, 7, '2026-02-01', '2026-02-03', '2026-02-08', 2.50, 1, 'returned'),
(26, 251, 8, 7, '2026-02-01', '2026-02-04', '2026-02-07', 100.00, 1, 'returned'),
(27, 252, 8, 7, '2026-02-01', '2026-02-05', NULL, 1.50, 0, 'overdue'),
(28, 253, 9, 7, '2026-02-01', '2026-02-03', NULL, 2.50, 0, 'overdue'),
(29, 254, 9, 7, '2026-02-01', '2026-02-04', NULL, 2.00, 0, 'overdue'),
(30, 255, 9, 7, '2026-02-01', '2026-02-05', NULL, 1.50, 0, 'overdue'),
(31, 260, 11, 7, '2026-02-01', '2026-02-03', NULL, 2.50, 0, 'overdue'),
(32, 261, 11, 7, '2026-02-01', '2026-02-04', NULL, 2.00, 0, 'overdue'),
(33, 262, 11, 7, '2026-02-01', '2026-02-05', NULL, 1.50, 0, 'overdue'),
(34, 263, 12, 7, '2026-02-01', '2026-02-03', '2026-02-08', 2.50, 1, 'returned'),
(35, 264, 12, 7, '2026-02-01', '2026-02-04', NULL, 2.00, 0, 'overdue'),
(36, 265, 12, 7, '2026-02-01', '2026-02-05', '2026-02-07', 1.00, 1, 'returned'),
(37, 261, 8, NULL, '2026-02-08', '2026-02-23', NULL, 0.00, 0, 'cancelled'),
(38, 234, 8, 7, '2026-02-08', '2026-02-24', '2026-02-08', 0.00, 1, 'returned'),
(39, 262, 8, NULL, '2026-02-08', '2026-02-23', NULL, 0.00, 0, 'cancelled'),
(40, 123, 8, 7, '2026-02-08', '2026-02-24', '2026-02-08', 0.00, 1, 'returned'),
(41, 260, 8, 7, '2026-02-08', '2026-02-24', NULL, 0.00, 0, 'issued'),
(42, 370, 8, 7, '2026-02-08', '2026-02-24', NULL, 0.00, 0, 'issued');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `book_id`, `user_id`, `rating`, `review_text`, `is_featured`, `created_at`) VALUES
(3, 260, 8, 2, 'nice book', 0, '2026-02-04 06:57:54'),
(4, 261, 8, 4, 'this is the best book i ever read.', 0, '2026-02-08 04:53:43');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`, `description`, `updated_at`) VALUES
('allow_overdue_borrow', '0', 'Allow borrowing when user has overdue books (0=No, 1=Yes)', '2026-02-02 14:55:43'),
('borrow_period_days', '14', 'Default number of days for book borrowing', '2026-02-02 14:55:43'),
('fine_per_day', '0.50', 'Fine amount per day for overdue books', '2026-02-02 14:55:43'),
('library_name', 'LMS - Library Management System', 'Library display name', '2026-02-02 14:55:43'),
('max_books_student', '3', 'Maximum books a student can borrow', '2026-02-02 14:55:43');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `issue_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_code` varchar(100) DEFAULT NULL,
  `transaction_uuid` varchar(100) NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `payment_method` enum('esewa','cash') DEFAULT 'cash',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `user_id`, `issue_id`, `amount`, `transaction_code`, `transaction_uuid`, `status`, `payment_method`, `created_at`) VALUES
(3, 8, 21, 50.00, '000E5D4', 'FINE-6987573cf33ea-21-1770477372', 'completed', 'esewa', '2026-02-07 15:16:12'),
(4, 9, 23, 9.00, 'CASH-369BD9E8', 'CASH-69876092175dc-23-1770479762', 'completed', 'cash', '2026-02-07 15:56:02'),
(5, 9, 24, 6.50, 'CASH-761F8C94', 'CASH-698760959b5bf-24-1770479765', 'completed', 'cash', '2026-02-07 15:56:05'),
(7, 12, 36, 1.00, 'CASH-A0395734', 'CASH-69876496b6e99-36-1770480790', 'completed', 'cash', '2026-02-07 16:13:10'),
(8, 8, 26, 100.00, '000E5DE', 'FINE-698765636df00-26-1770480995', 'completed', 'esewa', '2026-02-07 16:16:35'),
(9, 8, 25, 2.00, NULL, 'FINE-6988163f6cefb-25-1770526271', 'pending', 'cash', '2026-02-08 04:51:11'),
(10, 8, 25, 2.00, NULL, 'FINE-6988174b67084-25-1770526539', 'pending', 'cash', '2026-02-08 04:55:39'),
(11, 8, 25, 2.50, NULL, 'FINE-69881cb28325d-25-1770527922', 'pending', 'cash', '2026-02-08 05:18:42'),
(12, 8, 25, 2.50, NULL, 'FINE-6988313e9c2cd-25-1770533182', 'pending', 'cash', '2026-02-08 06:46:22'),
(13, 8, 27, 1.50, NULL, 'FINE-69883517568d3-27-1770534167', 'pending', 'cash', '2026-02-08 07:02:47'),
(14, 8, 25, 2.50, NULL, 'FINE-6988366fe36fb-25-1770534511', 'pending', 'cash', '2026-02-08 07:08:31'),
(15, 8, 27, 1.50, NULL, 'FINE-69883d30611aa-27-1770536240', 'pending', 'cash', '2026-02-08 07:37:20'),
(16, 8, 25, 2.50, NULL, 'FINE-698841096babd-25-1770537225', 'pending', 'cash', '2026-02-08 07:53:45'),
(17, 12, 34, 2.50, 'CASH-00A92EC1', 'CASH-6988417a4456b-34-1770537338', 'completed', 'cash', '2026-02-08 07:55:38'),
(18, 8, 25, 2.50, 'CASH-CB8676E4', 'CASH-698841b0afca2-25-1770537392', 'completed', 'cash', '2026-02-08 07:56:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `phone`, `password`, `department`, `class`, `role`, `is_active`, `created_at`, `last_login`, `remember_token`) VALUES
(7, 'admin', 'admin@library.com', '', '$2y$10$OnLTO/qsow.Ky7a9as/67ODW9GyRLEZdf.VFs9vqOq9aRB/Wj1VCO', NULL, NULL, 'admin', 1, '2026-02-02 15:16:39', '2026-02-08 07:59:00', '$2y$10$eo4zh9Ty3YwJjK72himtOOwXTBme4uNd6/mt1KQgnIzna4uzdEtcO'),
(8, 'user', 'user@library.com', '9800000000', '$2y$10$30qdLptnVoE9/vytC/djEe0qPfajAz9RONA5wnHCYEjSKs64Mia82', 'Physical Science', 12, 'student', 1, '2026-02-02 15:28:33', '2026-02-08 07:52:01', '$2y$10$Lr6fIdNxqnEaVxMlcXaV1OsboDvwKDJFBuLnXGxc2yce5xNU2tBzW'),
(9, 'user', 'user@gmail.com', '9800000000', '$2y$10$BgDqYU6ocxQGImI/J9es0udd0SN.GPPcVxuY9sUCzouIjBy4hBk9y', 'Physical Science', 12, 'student', 1, '2026-02-02 16:02:11', '2026-02-04 07:30:42', NULL),
(11, 'Royan Baidar', 'bccandd78@gmail.com', '', '$2y$10$Ajc69OL2Bra/NgJJb24E2eq8eY2ycjIeOdOvCyHETz59INkDyjtx2', 'Science', 12, 'student', 1, '2026-02-03 15:25:44', '2026-02-03 15:26:03', NULL),
(12, 'Suman Neupane', 'suman01@gmail.com', '9841393762', '$2y$10$.wINpGaHtpXPc810eVYnq.11fnRBTAjW.FCb8Xru8pxkJe7ESvpQm', 'Science', 12, 'student', 1, '2026-02-03 15:36:06', '2026-02-03 15:37:13', '$2y$10$Rq7PRPvPq5ujIa3qCsQhFu1NdySPrDmoyTAP1tKRjIokmLK.sc17q'),
(13, 'Ram', 'rambahadur@gmail.com', '', '$2y$10$PhXza3E7jGoWDvmqOUedBemEVG7LO0fYhELV.HEkyIoE3Do15WJ2C', 'Management', 12, 'student', 1, '2026-02-03 15:54:34', '2026-02-03 15:54:47', NULL),
(14, 'Rohit Satyal', 'rohitsatyalv333@gmail.com', '', '$2y$10$B5AMeIZvvSo4O5/pvyWrGeG.a/frGzUaxUsByXQuNPzOmLR1LTq4i', 'Science', 12, 'student', 1, '2026-02-08 06:38:33', '2026-02-08 06:38:43', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_user_activity` (`user_id`),
  ADD KEY `idx_action_type` (`action_type`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_isbn` (`isbn`),
  ADD KEY `idx_title` (`title`),
  ADD KEY `idx_author` (`author`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD PRIMARY KEY (`issue_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_book` (`book_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `unique_user_book_review` (`user_id`,`book_id`),
  ADD KEY `idx_book` (`book_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `issue_id` (`issue_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=403;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `issued_books`
--
ALTER TABLE `issued_books`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD CONSTRAINT `issued_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `issued_books_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`issue_id`) REFERENCES `issued_books` (`issue_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
