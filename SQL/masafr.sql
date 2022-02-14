-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2021 at 11:16 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `masafr`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `type`, `password`, `email`, `phone`, `photo`, `gender`, `created_at`, `updated_at`) VALUES
(1, 'eslam admin', 1, '$2y$10$Px3NOtr4GC3/gnC4K2.N7Oq0TIjjHBC4ONfK0jH3XVn36VIlzj6KS', 'es@gmail.com', '0123456789', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications_or_email`
--

CREATE TABLE `admin_notifications_or_email` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0 COMMENT '0 user 1 masafr',
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_by` int(11) NOT NULL DEFAULT 0 COMMENT '0 => notification 1 => window 2 => email 3 => sms',
  `type_of_template` int(11) DEFAULT 0 COMMENT 'the kind of template in notifiaction',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_notifications_or_email`
--

INSERT INTO `admin_notifications_or_email` (`id`, `type`, `subject`, `title`, `send_by`, `type_of_template`, `created_at`, `updated_at`) VALUES
(1, 0, 'welcome user', 'title1', 1, 0, '2021-10-03 19:51:59', NULL),
(2, 1, 'welcome masafr', 'title2', 1, 0, '2021-10-15 19:52:02', NULL),
(3, 0, 'welcome user2', 'title4', 1, 0, '2021-10-28 19:51:59', NULL),
(4, 1, 'welcome masafr2', 'title5', 1, 0, '2021-10-20 19:52:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `advertisings`
--

CREATE TABLE `advertisings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_after_announcement` int(11) NOT NULL DEFAULT 1,
  `all_days` tinyint(1) NOT NULL DEFAULT 0,
  `appear_time` int(11) NOT NULL DEFAULT 5,
  `daily_repeat` int(11) NOT NULL DEFAULT 2,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `animation_type` int(11) NOT NULL,
  `person_target` int(11) NOT NULL COMMENT '0 is all users 1 is all masafrs 2 is all visitors 3 is specifics',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advertisings`
--

INSERT INTO `advertisings` (`id`, `subject`, `link`, `site_after_announcement`, `all_days`, `appear_time`, `daily_repeat`, `active`, `image`, `animation_type`, `person_target`, `created_at`, `updated_at`) VALUES
(1, 'اهلا وسهلا بكم في التطبيق  اعلان1', 'www.google.com', 1, 1, 5, 2, 1, NULL, 1, 1, '2021-11-02 19:29:23', NULL),
(2, 'اهلا وسهلا بكم في التطبيق  اعلان2', 'www.google.com', 1, 0, 7, 2, 1, NULL, 1, 3, '2021-11-22 19:29:23', NULL),
(3, 'اهلا وسهلا بكم في التطبيق  اعلان3', 'www.google.com', 1, 1, 5, 2, 1, NULL, 1, 0, '2021-11-02 19:29:23', NULL),
(4, 'اهلا وسهلا بكم في التطبيق  اعلان4', 'www.google.com', 1, 0, 7, 2, 1, NULL, 1, 2, '2021-11-22 19:29:23', NULL),
(5, 'اهلا وسهلا بكم في التطبيق  اعلان5', 'www.google.com', 1, 1, 5, 2, 1, NULL, 1, 1, '2021-11-02 19:29:23', NULL),
(6, 'اهلا وسهلا بكم في التطبيق  اعلان6', 'www.google.com', 1, 0, 7, 2, 1, NULL, 1, 3, '2021-11-22 19:29:23', NULL),
(7, 'اهلا وسهلا بكم في التطبيق  اعلان7', 'www.google.com', 1, 1, 5, 2, 1, NULL, 1, 0, '2021-11-02 19:29:23', NULL),
(8, 'اهلا وسهلا بكم في التطبيق  اعلان8', 'www.google.com', 1, 0, 7, 2, 1, NULL, 1, 2, '2021-11-22 19:29:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `advertising_days`
--

CREATE TABLE `advertising_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `advertising_id` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advertising_days`
--

INSERT INTO `advertising_days` (`id`, `advertising_id`, `day`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2021-11-02 19:31:02', NULL),
(2, 2, 2, '2021-11-02 19:31:02', NULL),
(3, 2, 3, '2021-11-02 19:31:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `advertising_places`
--

CREATE TABLE `advertising_places` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `advertising_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `place` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advertising_places`
--

INSERT INTO `advertising_places` (`id`, `advertising_id`, `type`, `place`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 1, '2021-11-05 19:31:51', NULL),
(2, 1, 0, 7, '2021-11-05 19:31:51', NULL),
(3, 1, 1, 2, '2021-11-05 19:31:51', NULL),
(4, 1, 1, 5, '2021-11-05 19:31:51', NULL),
(5, 2, 0, 6, '2021-11-23 19:36:53', NULL),
(6, 2, 0, 9, '2021-11-23 19:36:53', NULL),
(7, 2, 1, 4, '2021-11-23 19:36:53', NULL),
(8, 2, 1, 8, '2021-11-23 19:36:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `advertising_users`
--

CREATE TABLE `advertising_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `advertising_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 is user 1 is masafr',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advertising_users`
--

INSERT INTO `advertising_users` (`id`, `advertising_id`, `type`, `phone`, `created_at`, `updated_at`) VALUES
(1, 1, 0, '0123456789', '2021-11-02 19:38:12', NULL),
(2, 1, 0, '0147852369', '2021-11-02 19:38:12', NULL),
(3, 1, 1, '0123456789', '2021-11-02 19:38:12', NULL),
(4, 1, 1, '0147852369', '2021-11-02 19:38:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `apllication_settings`
--

CREATE TABLE `apllication_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `apllication_settings`
--

INSERT INTO `apllication_settings` (`id`, `subject`, `value`, `created_at`, `updated_at`) VALUES
(1, 'عمولة علي العميل دفع الكتروني', 10, '2021-11-01 19:39:33', NULL),
(2, 'عمولة علي المسافر من الاتفاق', 20, '2021-11-01 19:39:33', NULL),
(3, 'نسبة قيمة التأمين', 15, '2021-11-01 19:39:33', NULL),
(4, 'اقصي شحن رصيد', 1000, '2021-11-01 19:39:33', NULL),
(5, 'اقصي مديونية علي المسافر', 50, '2021-11-01 19:39:33', NULL),
(6, 'اقصي عدد رسائل تنبية', 15, '2021-11-01 19:39:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categorie_request_subsections`
--

CREATE TABLE `categorie_request_subsections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `section_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorie_request_subsections`
--

INSERT INTO `categorie_request_subsections` (`id`, `categorie_id`, `section_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'شحنة  خفيفة', '2021-11-02 19:42:37', NULL),
(2, 1, 'شحنة  كبيرة', '2021-11-02 19:42:37', NULL),
(3, 1, 'توصيل نبات', '2021-11-02 19:48:39', NULL),
(4, 1, 'توصيل طعام', '2021-11-02 19:48:39', NULL),
(5, 1, 'توصيل أجهزة', '2021-11-02 19:49:33', NULL),
(6, 1, 'توصيل حيوان', '2021-11-02 19:50:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categorie_trip_subsections`
--

CREATE TABLE `categorie_trip_subsections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `section_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorie_trip_subsections`
--

INSERT INTO `categorie_trip_subsections` (`id`, `categorie_id`, `section_name`, `created_at`, `updated_at`) VALUES
(1, 4, 'شحنات  خفيفة', '2021-11-03 19:50:41', NULL),
(2, 4, 'شحنات  منوعة ', '2021-11-03 19:50:41', NULL),
(3, 4, 'نبات وحيوانات', '2021-11-03 19:51:18', NULL),
(4, 4, 'أطعمة  وهدايا', '2021-11-03 19:51:18', NULL),
(5, 4, 'خدمات مدن', '2021-11-03 19:51:53', NULL),
(6, 4, 'ركاب', '2021-11-03 19:51:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 from user to masafr  1 from masafr to user',
  `masafr_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wait` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 there is no wait comments and 1 is there are and admin get the comments that need to be accepted by geting comments with wait is 1',
  `wait_subject` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'the updated comment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `type`, `masafr_id`, `user_id`, `subject`, `wait`, `wait_subject`, `created_at`, `updated_at`) VALUES
(1, 0, 1, 1, 'تعليق من عميل 1', 0, NULL, '2021-11-02 19:53:59', NULL),
(2, 1, 1, 1, 'تعليق من مسافر 1', 0, NULL, '2021-11-02 19:53:59', NULL),
(3, 1, 1, 1, 'تعليق من مسافر 2', 1, 'تعليق معلق', '2021-11-03 19:55:07', NULL),
(4, 0, 1, 1, 'تعليق من عميل 2', 1, 'تعليق معلق من العميل 2', '2021-11-03 19:55:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complains`
--

CREATE TABLE `complains` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `masafr_id` int(11) NOT NULL,
  `related_trip` int(11) DEFAULT NULL,
  `related_request_service` int(11) DEFAULT NULL,
  `related_chat` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 close 1 open',
  `solved` tinyint(1) NOT NULL DEFAULT 0,
  `user_negative` tinyint(1) NOT NULL DEFAULT 0,
  `masafr_negative` tinyint(1) NOT NULL DEFAULT 0,
  `complainant` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 from user 1 from masafr',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complains`
--

INSERT INTO `complains` (`id`, `user_id`, `masafr_id`, `related_trip`, `related_request_service`, `related_chat`, `status`, `solved`, `user_negative`, `masafr_negative`, `complainant`, `reason`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 1, NULL, '2021-11-08 20:18:34', NULL),
(2, 1, 2, 1, 1, 2, 0, 0, 0, 0, 0, NULL, '2021-11-08 20:18:34', NULL),
(3, 1, 2, 2, 2, 1, 1, 0, 0, 0, 0, NULL, '2021-11-08 20:18:34', NULL),
(4, 1, 2, 1, 1, 2, 1, 1, 1, 1, 1, NULL, '2021-11-08 20:18:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complain_lists`
--

CREATE TABLE `complain_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0 user 1 is masafr 2 is admin',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attach` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complain_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complain_lists`
--

INSERT INTO `complain_lists` (`id`, `type`, `subject`, `attach`, `complain_id`, `created_at`, `updated_at`) VALUES
(1, '0', 'does not give my need', '', 1, '2021-11-01 20:20:58', NULL),
(2, '1', 'does not give my money', NULL, 1, '2021-11-01 20:21:03', NULL),
(3, '0', 'does not give my need2', '', 2, '2021-11-04 20:21:07', NULL),
(4, '1', 'does not give my money2', '', 2, '2021-11-04 20:21:11', NULL),
(5, '0', 'does not give my need3', '', 3, '2021-11-15 20:21:16', NULL),
(6, '1', 'does not give my money3', NULL, 3, '2021-11-15 20:21:20', NULL),
(7, '0', 'does not give my need4', '', 4, '2021-11-29 20:21:23', NULL),
(8, '1', 'does not give my money4', '', 4, '2021-11-29 20:21:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `copons`
--

CREATE TABLE `copons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gift_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `copon_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `copon_start_date` date DEFAULT NULL,
  `copon_end_date` date DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `value` int(11) NOT NULL DEFAULT 0,
  `copon_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 is is discount copon 1 is gift copon',
  `used` int(11) NOT NULL DEFAULT 0,
  `copon_full_amount_err` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `not_exsist_copon_err` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_used_copon_before_err` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `copon_success` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attach` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `copons`
--

INSERT INTO `copons` (`id`, `gift_picture`, `copon_name`, `copon_start_date`, `copon_end_date`, `amount`, `value`, `copon_type`, `used`, `copon_full_amount_err`, `not_exsist_copon_err`, `has_used_copon_before_err`, `copon_success`, `link`, `attach`, `created_at`, `updated_at`) VALUES
(1, NULL, 'ESLAM', '2021-10-11', '2021-11-28', 100, 20, 0, 2, 'اكتمل العدد', 'لايوجد', 'لقد استخدمته من قبل', 'مبروك', NULL, NULL, '2021-11-22 20:22:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `copon_users`
--

CREATE TABLE `copon_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `copon_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `copon_users`
--

INSERT INTO `copon_users` (`id`, `user_id`, `copon_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2021-11-30 20:22:43', NULL),
(2, 2, 1, '2021-11-30 20:22:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers_service`
--

CREATE TABLE `customers_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers_service`
--

INSERT INTO `customers_service` (`id`, `name`, `email`, `title`, `body`, `attachment`, `created_at`, `updated_at`) VALUES
(1, 'user1', 'tawseelwithmasafr@gmail.com', 'خدمة', 'اختبار خدمة العملاء 1', NULL, '2021-11-15 20:24:53', NULL),
(2, 'user2', 'tawseelwithmasafr@gmail.com', '2خدمة', 'اختبار خدمة العملاء 2', NULL, '2021-11-25 20:24:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fatoorahs`
--

CREATE TABLE `fatoorahs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fatoorah_list_id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int(11) NOT NULL DEFAULT 0,
  `is_fee_insurance` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 is fatoorah subject 1 is fee insurance subject',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fatoorahs`
--

INSERT INTO `fatoorahs` (`id`, `fatoorah_list_id`, `subject`, `value`, `is_fee_insurance`, `created_at`, `updated_at`) VALUES
(1, 1, 'sub1', 100, 0, '2021-11-02 20:25:14', NULL),
(2, 1, 'sub2', 200, 0, '2021-11-02 20:25:17', NULL),
(3, 2, 'sub3', 300, 0, '2021-11-16 20:25:21', NULL),
(4, 2, 'sub4', 400, 0, '2021-11-16 20:25:24', NULL),
(5, 1, 'التامين', 12, 1, '2021-11-02 20:25:14', NULL),
(6, 2, 'التامين', 15, 1, '2021-11-16 20:25:24', NULL),
(7, 3, 'sub1', 100, 0, '2021-11-02 20:25:14', NULL),
(8, 3, 'sub2', 200, 0, '2021-11-02 20:25:17', NULL),
(9, 3, 'التامين', 12, 1, '2021-11-02 20:25:14', NULL),
(10, 4, 'sub1', 100, 0, '2021-11-02 20:25:14', NULL),
(11, 4, 'sub2', 200, 0, '2021-11-02 20:25:17', NULL),
(12, 4, 'التامين', 12, 1, '2021-11-02 20:25:14', NULL),
(13, 5, 'sub1', 100, 0, '2021-11-02 20:25:14', NULL),
(14, 5, 'sub2', 200, 0, '2021-11-02 20:25:17', NULL),
(15, 5, 'التامين', 12, 1, '2021-11-02 20:25:14', NULL),
(16, 6, 'sub1', 100, 0, '2021-11-02 20:25:14', NULL),
(17, 6, 'sub2', 200, 0, '2021-11-02 20:25:17', NULL),
(18, 6, 'التامين', 12, 1, '2021-11-02 20:25:14', NULL),
(19, 7, 'sub1', 100, 0, '2021-11-02 20:25:14', NULL),
(20, 7, 'sub2', 200, 0, '2021-11-02 20:25:17', NULL),
(21, 7, 'التامين', 12, 1, '2021-11-02 20:25:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fatoorah_lists`
--

CREATE TABLE `fatoorah_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_trip_id` int(11) NOT NULL,
  `accepted` enum('-1','0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 did not take action 1 accept the fatoorah -1 refuesed the fatoorah',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fatoorah_lists`
--

INSERT INTO `fatoorah_lists` (`id`, `request_trip_id`, `accepted`, `created_at`, `updated_at`) VALUES
(1, 1, '-1', '2021-10-01 22:17:05', NULL),
(2, 2, '-1', '2021-10-02 22:17:09', NULL),
(3, 1, '-1', '2021-10-03 22:17:12', NULL),
(4, 1, '1', '2021-10-04 22:17:16', NULL),
(5, 2, '1', '2021-10-05 22:17:19', NULL),
(6, 2, '0', '2021-11-22 20:27:26', NULL),
(7, 1, '0', '2021-11-22 20:27:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `free_services`
--

CREATE TABLE `free_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `masafr_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `free_services`
--

INSERT INTO `free_services` (`id`, `masafr_id`, `type`, `photo`, `description`, `active`, `created_at`, `updated_at`) VALUES
(1, 1, 'توصيل علاج ', NULL, 'توصيل علاج ', 1, '2021-11-14 20:35:25', NULL),
(2, 1, 'توصيل مرضي', NULL, 'توصيل مرضي', 1, '2021-11-25 20:35:25', NULL),
(3, 2, 'توصيل علاج2', NULL, 'توصيل علاج ', 1, '2021-11-14 20:35:25', NULL),
(4, 2, '2توصيل مرضي', NULL, 'توصيل مرضي', 1, '2021-11-25 20:35:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `free_service_places`
--

CREATE TABLE `free_service_places` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `free_service_id` int(11) NOT NULL,
  `place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `free_service_places`
--

INSERT INTO `free_service_places` (`id`, `free_service_id`, `place`, `created_at`, `updated_at`) VALUES
(1, 1, 'طنطا', '2021-11-02 20:37:37', NULL),
(2, 1, 'كفر الزيات', '2021-11-02 20:37:40', NULL),
(3, 2, 'شبين', '2021-11-04 20:38:42', NULL),
(4, 2, 'قويسنا', '2021-11-05 20:38:47', NULL),
(5, 2, 'بنها', '2021-11-06 20:38:49', NULL),
(6, 3, 'طنطا', '2021-11-02 20:37:37', NULL),
(7, 3, 'كفر الزيات', '2021-11-02 20:37:40', NULL),
(8, 4, 'شبين', '2021-11-07 20:38:52', NULL),
(9, 2, 'قويسنا', '2021-11-08 20:38:55', NULL),
(10, 4, 'بنها', '2021-11-09 20:38:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gift_copon_users`
--

CREATE TABLE `gift_copon_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `copon_id` int(11) NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gift_copon_users`
--

INSERT INTO `gift_copon_users` (`id`, `copon_id`, `phone`, `created_at`, `updated_at`) VALUES
(1, 1, '0123456789', '2021-11-02 20:39:24', NULL),
(2, 1, '0147852369', '2021-11-23 20:39:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `masafr`
--

CREATE TABLE `masafr` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` double NOT NULL DEFAULT 0,
  `id_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => female  1 => male',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active_try` int(11) NOT NULL DEFAULT 0 COMMENT 'how many he try to send a verfication code to mobile phone',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => no  1 => yes',
  `last_try_verify` datetime DEFAULT NULL,
  `last_send_verify_code` datetime DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `national_id_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car_model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car_image_east` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car_image_west` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car_image_north` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driving_license_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_notifications` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => no  1 => yes',
  `email_notifications` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => no  1 => yes',
  `balance` int(11) NOT NULL DEFAULT 0,
  `trust` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => no  1 => yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `masafr`
--

INSERT INTO `masafr` (`id`, `name`, `photo`, `rate`, `id_photo`, `gender`, `email`, `country_code`, `phone`, `verification_code`, `active_try`, `email_verified_at`, `is_verified`, `last_try_verify`, `last_send_verify_code`, `password`, `national_id_number`, `nationality`, `car_name`, `car_model`, `car_number`, `car_image_east`, `car_image_west`, `car_image_north`, `driving_license_photo`, `remember_token`, `sms_notifications`, `email_notifications`, `balance`, `trust`, `created_at`, `updated_at`) VALUES
(1, 'test  masafr2', NULL, 0, NULL, 0, 'es3@gmail.com', '966', '01234567893', '176248', 0, NULL, 1, NULL, '2021-10-27 13:41:51', '$2y$10$MsVVD.dS5AQRTcIDrWBFkuBMPVJhuWGs3EK17ZT8WkzfFomPiHI6G', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL),
(4, 'test  masafr3', NULL, 0, NULL, 0, 'eslamelbanna02@gmail.com', '20', '0123456789', '417504', 0, NULL, 1, NULL, '2021-10-30 12:30:58', '$2y$10$fufeiINrEbrevhB9ZhjXf.1q0bwpKygAOypiQZqdL3fMxQaznjAlu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, '2021-10-30 11:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `masafr_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `masafr_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2021-10-04 13:52:12', NULL),
(2, 2, 2, '2021-10-14 13:52:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message_objects`
--

CREATE TABLE `message_objects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` int(11) NOT NULL,
  `sender_type` int(11) NOT NULL COMMENT '0 from user to masafr, 1 from masafr to user, and else is th chat notifications',
  `subject` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attach` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_seen` tinyint(1) NOT NULL DEFAULT 0,
  `masafr_seen` tinyint(1) NOT NULL DEFAULT 0,
  `private_msg` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 not private 1 private to user 2 private to masafr',
  `code` int(11) NOT NULL COMMENT 'the code of thr private msg',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_objects`
--

INSERT INTO `message_objects` (`id`, `message_id`, `sender_type`, `subject`, `attach`, `user_seen`, `masafr_seen`, `private_msg`, `code`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 'hello masafr am user', NULL, 1, 0, '0', 0, '2021-10-03 15:00:52', NULL),
(2, 1, 1, 'hello user am masafr', 'asd.pdf', 0, 1, '0', 0, '2021-10-19 15:00:56', NULL),
(3, 2, 0, 'hello masafr am user2', NULL, 1, 0, '0', 0, '2021-10-03 15:00:52', NULL),
(4, 2, 1, 'hello user am masafr2', 'asd.pdf', 0, 1, '0', 0, '2021-10-19 15:00:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(504, '2014_10_12_000000_create_users_table', 1),
(505, '2014_10_12_100000_create_password_resets_table', 1),
(506, '2019_08_19_000000_create_failed_jobs_table', 1),
(507, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(508, '2021_09_16_204659_create_masafr_table', 1),
(509, '2021_09_16_211708_create_transactions_table', 1),
(510, '2021_09_17_141119_create_comments_table', 1),
(511, '2021_09_17_142745_create_customers_service_table', 1),
(512, '2021_09_17_144031_create_complains_table', 1),
(513, '2021_09_17_145446_create_admin_notifications_or_email_table', 1),
(514, '2021_09_17_150301_create_trips_table', 1),
(515, '2021_09_17_153048_create_trip_ways_table', 1),
(516, '2021_09_17_153616_create_trip_days_table', 1),
(517, '2021_09_17_155346_create_notifications_table', 1),
(518, '2021_09_17_163922_create_request_services_table', 1),
(519, '2021_09_17_191209_create_messages_table', 1),
(520, '2021_09_18_213603_create_complain_lists_table', 1),
(521, '2021_09_20_151805_create_free_services_table', 1),
(522, '2021_09_20_152822_create_free_service_places_table', 1),
(523, '2021_09_27_162125_create_admins_table', 1),
(524, '2021_09_27_182756_create_notification_or_mail_people_table', 1),
(525, '2021_09_27_222603_create_request_categories_table', 1),
(526, '2021_09_27_224854_create_categorie_request_subsections_table', 1),
(527, '2021_09_28_193420_create_copons_table', 1),
(528, '2021_09_28_210226_create_gift_copon_users_table', 1),
(529, '2021_09_28_214005_create_advertisings_table', 1),
(530, '2021_09_28_214708_create_advertising_days_table', 1),
(531, '2021_09_28_214820_create_advertising_users_table', 1),
(532, '2021_09_30_185121_create_message_objects_table', 1),
(533, '2021_10_02_134943_create_request_trips_table', 1),
(534, '2021_10_03_120050_create_request_send_tos_table', 1),
(535, '2021_10_03_131245_create_fatoorahs_table', 1),
(536, '2021_10_04_183748_create_rollback_request_money_table', 1),
(537, '2021_10_04_191556_create_advertising_places_table', 1),
(538, '2021_10_04_213330_create_rollback_requests_table', 1),
(539, '2021_10_05_115609_create_trip_categories_table', 1),
(540, '2021_10_05_120921_create_categorie_trip_subsections_table', 1),
(541, '2021_10_05_125538_create_apllication_settings_table', 1),
(542, '2021_10_07_094129_create_fatoorah_lists_table', 1),
(543, '2021_10_07_153046_create_copon_users_table', 1),
(544, '2021_10_08_193845_create_pronunciation_statements_table', 1),
(545, '2021_10_09_143358_create_update_qeueus_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `person_id` int(11) NOT NULL,
  `type` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'notification 0 => to user, 1 => to masafr, 2 => to admin',
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_code` int(11) DEFAULT NULL COMMENT 'to know where he must move to like if value is 3 when he press on it he open his personal tap screen,,, and so on',
  `related_trip` int(11) DEFAULT NULL,
  `related_request_service` int(11) DEFAULT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `person_id`, `type`, `subject`, `target_code`, `related_trip`, `related_request_service`, `seen`, `created_at`, `updated_at`) VALUES
(1, 1, '0', 'اهلا عميل1', NULL, 1, 1, 0, '2021-11-01 20:59:02', NULL),
(2, 1, '1', 'اهلا مسافر1', NULL, 1, 1, 0, '2021-11-02 20:59:05', NULL),
(3, 1, '0', 'اهلا عميل2', NULL, 1, 2, 1, '2021-11-03 20:59:08', NULL),
(4, 1, '1', 'اهلا مسافر2', NULL, 1, 2, 1, '2021-11-04 20:59:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification_or_mail_people`
--

CREATE TABLE `notification_or_mail_people` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `notfication_or_mail_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `showed` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 not yet 1 showed it',
  `seen_time` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_or_mail_people`
--

INSERT INTO `notification_or_mail_people` (`id`, `notfication_or_mail_id`, `person_id`, `showed`, `seen_time`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, NULL, '2021-10-03 19:50:21', NULL),
(2, 1, 2, 1, NULL, '2021-10-21 19:50:24', NULL),
(3, 2, 1, 1, NULL, '2021-10-03 19:50:21', NULL),
(4, 2, 2, 0, NULL, '2021-10-21 19:50:24', NULL),
(5, 3, 1, 0, NULL, '2021-11-03 21:01:15', NULL),
(6, 4, 2, 1, NULL, '2021-11-19 21:01:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pronunciation_statements`
--

CREATE TABLE `pronunciation_statements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 from user 1 from masafr',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chat_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pronunciation_statements`
--

INSERT INTO `pronunciation_statements` (`id`, `sender_type`, `subject`, `chat_id`, `created_at`, `updated_at`) VALUES
(1, 0, 'قال لفظ سئ 1', 1, '2021-11-02 21:03:13', NULL),
(2, 1, 'سبني 1', 2, '2021-11-03 21:03:16', NULL),
(3, 0, 'قال لفظ سئ 2', 1, '2021-11-04 21:03:19', NULL),
(4, 1, 'سبني 2', 2, '2021-11-05 21:03:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `request_categories`
--

CREATE TABLE `request_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `categorie_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `only_saudi` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'this categorey for only sauid people',
  `payment_method` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'the payment method 0 => by hand 1 => by payment online',
  `have_insurance` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'have insurance or not',
  `have_photo` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'this categories apply user to upload photos or not',
  `two_places` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 one place 1 have two places',
  `two_codes` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 is one code and 1 is 2 codes',
  `active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 not active 1 is active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_categories`
--

INSERT INTO `request_categories` (`id`, `categorie_name`, `photo`, `title`, `only_saudi`, `payment_method`, `have_insurance`, `have_photo`, `two_places`, `two_codes`, `active`, `created_at`, `updated_at`) VALUES
(1, 'توصيل', NULL, 'توصيل  بين المدن', 1, 0, 1, 1, 1, 1, 1, '2021-11-08 21:03:28', NULL),
(2, 'شراء', NULL, 'شراء  طلباتك وشحنها  أو توصيلها', 1, 0, 1, 1, 1, 0, 1, '2021-11-08 21:03:28', NULL),
(3, 'توصيل ركاب', NULL, 'توصيل   عائلة أو أفراد بين المدن', 1, 0, 1, 1, 1, 0, 1, '2021-11-08 21:03:28', NULL),
(4, 'خدمات عامة', NULL, 'أي خدمة  - تعقيب - بحث - فحص - الخ', 1, 0, 1, 1, 1, 0, 1, '2021-11-08 21:03:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `request_send_tos`
--

CREATE TABLE `request_send_tos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 public 1 specifics masafrs',
  `masar_id` int(11) NOT NULL,
  `requestService_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_services`
--

CREATE TABLE `request_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `type_of_trips` int(11) NOT NULL COMMENT '1 => delivery service, 2 => buy, 3 => Passenger delivery, 4 => public services',
  `type_of_services` int(11) NOT NULL COMMENT '1 => light shipments, 2 => Miscellaneous shipments, 3 => plants, 4 => animals, 5 => Foods, 6 => devices',
  `from_place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_day` date DEFAULT NULL,
  `delivery_to` tinyint(1) DEFAULT NULL COMMENT '0 to home 1 on the road',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `only_women` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 any one, 1 only womens',
  `have_insurance` tinyint(1) DEFAULT NULL COMMENT '0 does not have insurance, 1 Have insurance',
  `insurance_value` int(11) DEFAULT NULL,
  `website_service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_passengers` int(11) DEFAULT NULL,
  `type_of_car` enum('1','2') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1 => big car transport, 2 => family car',
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 not active , 1 active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_services`
--

INSERT INTO `request_services` (`id`, `user_id`, `type_of_trips`, `type_of_services`, `from_place`, `from_longitude`, `from_latitude`, `to_place`, `to_longitude`, `to_latitude`, `max_day`, `delivery_to`, `photo`, `description`, `only_women`, `have_insurance`, `insurance_value`, `website_service`, `number_of_passengers`, `type_of_car`, `active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'tanta', NULL, NULL, 'alex', NULL, NULL, '2020-10-30', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2021-11-02 21:22:43', NULL),
(2, 4, 2, 1, 'shipin', NULL, NULL, 'mansors', NULL, NULL, '2021-10-30', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2021-11-03 21:22:40', NULL),
(3, 1, 2, 5, 'tanta', '12.255', '15.3245', 'alex', '40.2155', '30.21456', '2021-09-19', 1, NULL, 'nonee', 1, 0, NULL, 'amazon.com', NULL, NULL, 1, '2021-11-01 21:19:55', '2021-11-01 21:19:55'),
(4, 1, 1, 6, 'mansora', '12.255', '15.3245', 'kawesna', '40.2155', '30.21456', '2021-09-19', 1, NULL, 'nonee', 1, NULL, NULL, NULL, NULL, NULL, 1, '2021-11-01 21:20:35', '2021-11-01 21:20:35'),
(5, 1, 3, 2, 'tala', '12.255', '15.3245', 'damnhour', '40.2155', '30.21456', NULL, NULL, NULL, 'nonee', 1, NULL, NULL, NULL, 14, '1', 1, '2021-11-01 21:20:51', '2021-11-01 21:20:51'),
(6, 1, 4, 3, 'santa', '12.255', '15.3245', NULL, NULL, NULL, '2021-09-19', NULL, NULL, 'nonee', 1, NULL, NULL, NULL, NULL, NULL, 1, '2021-11-01 21:21:06', '2021-11-01 21:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `request_trips`
--

CREATE TABLE `request_trips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `receipt_code` int(11) NOT NULL DEFAULT 0,
  `delivery_code` int(11) NOT NULL DEFAULT 0,
  `offer_status` enum('-1','0','1','2','3','4','5') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `payment_method` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 is online 1 is cash hand',
  `user_mark` int(11) NOT NULL DEFAULT 0,
  `user_feedback` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `masafr_mark` int(11) NOT NULL DEFAULT 0,
  `masafr_feedback` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decision_maker` int(11) NOT NULL DEFAULT 0,
  `reasons` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_status` int(11) DEFAULT 0,
  `discounts` int(11) DEFAULT 0,
  `website_service` int(11) NOT NULL DEFAULT 0,
  `insurance_hold` int(11) NOT NULL DEFAULT 0,
  `cancel_type` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 not canceled , 1 from user 2 from masafr',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_trips`
--

INSERT INTO `request_trips` (`id`, `chat_id`, `request_id`, `trip_id`, `receipt_code`, `delivery_code`, `offer_status`, `payment_method`, `user_mark`, `user_feedback`, `masafr_mark`, `masafr_feedback`, `decision_maker`, `reasons`, `current_status`, `discounts`, `website_service`, `insurance_hold`, `cancel_type`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, 0, '5', 0, 0, 'gg', 0, 'kl', 0, NULL, 0, 0, 0, 0, '0', NULL, NULL),
(2, 1, 2, 3, 0, 0, '-1', 0, 0, 'hh', 0, 'nn', 0, NULL, 0, 0, 0, 0, '0', NULL, NULL),
(3, 1, 2, 7, 147, 236, '-1', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL),
(4, 1, 2, 7, 147, 236, '1', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL),
(5, 1, 2, 7, 147, 236, '2', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL),
(6, 1, 2, 7, 147, 236, '3', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL),
(7, 1, 2, 7, 147, 236, '4', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL),
(8, 1, 2, 7, 147, 236, '5', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL),
(9, 2, 6, 4, 147, 236, '-1', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL),
(10, 2, 6, 4, 147, 236, '1', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL),
(11, 2, 6, 4, 147, 236, '2', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL),
(12, 2, 6, 4, 147, 236, '3', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL),
(13, 2, 6, 4, 147, 236, '4', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL),
(14, 2, 6, 4, 147, 236, '5', 0, 5, 'good', 3, 'bad', 0, NULL, 0, 10, 15, 60, '0', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rollback_requests`
--

CREATE TABLE `rollback_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `masafr_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type send,,, 0 user 1 masafr',
  `msg` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response` tinyint(1) NOT NULL DEFAULT 0,
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `escalation` tinyint(1) NOT NULL DEFAULT 0,
  `decision` int(11) NOT NULL DEFAULT 0,
  `complain_id` int(11) NOT NULL,
  `decision_maker` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rollback_request_money`
--

CREATE TABLE `rollback_request_money` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_time` date DEFAULT NULL,
  `account_number` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `decision_maker` int(11) NOT NULL,
  `trans_msg` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 user transaction 1 is masafr transaction',
  `user_id` int(11) NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `type`, `user_id`, `subject`, `created_at`, `updated_at`) VALUES
(1, 0, 1, 'geet money from x to y 1', '2021-11-04 22:12:22', NULL),
(2, 0, 2, 'geet money from x to y 2', '2021-11-04 22:12:22', NULL),
(3, 1, 1, 'geet money from x to y 1', '2021-11-04 22:12:22', NULL),
(4, 1, 2, 'geet money from x to y 2', '2021-11-04 22:12:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `masafr_id` int(11) NOT NULL,
  `type_of_trips` int(11) NOT NULL COMMENT 'the main categorie',
  `type_of_services` int(11) NOT NULL COMMENT 'subsection to the categorie',
  `only_women` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 any one, 1 only womens',
  `from_place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 not active , 1 active',
  `on_progress` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 no one(user) take it , 1 some one take it and it is in progress',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `masafr_id`, `type_of_trips`, `type_of_services`, `only_women`, `from_place`, `from_longitude`, `from_latitude`, `to_place`, `to_longitude`, `to_latitude`, `start_date`, `end_date`, `description`, `active`, `on_progress`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, 'tanta', NULL, NULL, 'alex', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL),
(3, 1, 1, 1, 0, 'cairo', NULL, NULL, 'kom hamada', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL),
(4, 1, 1, 7, 1, 'tanta', '12.3651', '30.25315', 'cairo', '50.635154', '45.353', '2021-09-18', '2021-09-30', 'test disciption', 1, 0, NULL, NULL),
(5, 1, 2, 5, 0, 'mansora', '12.3651', '30.25315', 'kawesna', '50.635154', '45.353', NULL, NULL, 'test disciption', 1, 0, NULL, NULL),
(6, 1, 3, 4, 1, 'tala', '12.3651', '30.25315', 'alex', '50.635154', '45.353', NULL, NULL, 'test disciption', 1, 0, NULL, NULL),
(7, 1, 4, 7, 0, 'shibin', '12.3651', '30.25315', NULL, NULL, NULL, NULL, NULL, 'test disciption', 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trip_categories`
--

CREATE TABLE `trip_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `categorie_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `only_saudi` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'this categorey for only sauid people',
  `active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 not active 1 is active',
  `special_dlivery` tinyint(1) NOT NULL DEFAULT 0,
  `two_place` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 one place 1 two places',
  `weekly` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 no 1 yes',
  `counter` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 no 1 yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trip_categories`
--

INSERT INTO `trip_categories` (`id`, `categorie_name`, `photo`, `title`, `only_saudi`, `active`, `special_dlivery`, `two_place`, `weekly`, `counter`, `created_at`, `updated_at`) VALUES
(1, 'سفر بوقت محدد', NULL, 'سفر بوفت محدد  -( أعلان ينتهي )', 0, 1, 1, 1, 1, 0, '2021-11-24 21:47:23', NULL),
(2, 'رحلة أسبوعية ', NULL, 'سفر أسبوعي  ( أعلان ثابت  )', 0, 1, 1, 1, 1, 0, '2021-11-24 21:47:23', NULL),
(3, 'رحلة عند وجود طلب', NULL, 'سفر عند وجود طلب  (أعلان ثابت ) ', 1, 1, 1, 1, 1, 0, '2021-11-24 21:47:23', NULL),
(4, 'خدمات  المدن', NULL, 'تقديم خدمات عامة -  ( أعلان ثابت  )', 0, 1, 1, 1, 1, 0, '2021-11-24 21:47:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trip_days`
--

CREATE TABLE `trip_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trip_id` int(11) NOT NULL,
  `trip_day` int(11) NOT NULL COMMENT '1 => Saturday, 2 => Sunday, 3 => Monday, 4 => Tuesday, 5 => Wednesday, 6 => Thursday, 7 => Friday',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trip_days`
--

INSERT INTO `trip_days` (`id`, `trip_id`, `trip_day`, `created_at`, `updated_at`) VALUES
(1, 5, 1, '2021-11-01 21:25:05', '2021-11-01 21:25:05'),
(2, 5, 3, '2021-11-01 21:25:05', '2021-11-01 21:25:05'),
(3, 5, 5, '2021-11-01 21:25:05', '2021-11-01 21:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `trip_ways`
--

CREATE TABLE `trip_ways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trip_id` int(11) NOT NULL,
  `place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trip_ways`
--

INSERT INTO `trip_ways` (`id`, `trip_id`, `place`, `longitude`, `latitude`, `time`, `created_at`, `updated_at`) VALUES
(1, 4, 'kom hammada', NULL, NULL, '2021-09-18', '2021-11-01 21:24:49', '2021-11-01 21:24:49'),
(2, 4, 'damanhour', NULL, NULL, '2021-09-19', '2021-11-01 21:24:49', '2021-11-01 21:24:49'),
(3, 5, 'kom hammada', NULL, NULL, '2021-09-18', '2021-11-01 21:25:05', '2021-11-01 21:25:05'),
(4, 5, 'damanhour', NULL, NULL, '2021-09-19', '2021-11-01 21:25:05', '2021-11-01 21:25:05'),
(5, 6, 'kom hammada', NULL, NULL, NULL, '2021-11-01 21:25:54', '2021-11-01 21:25:54'),
(6, 6, 'damanhour', NULL, NULL, NULL, '2021-11-01 21:25:54', '2021-11-01 21:25:54');

-- --------------------------------------------------------

--
-- Table structure for table `update_qeueus`
--

CREATE TABLE `update_qeueus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `person_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 user 1 masafr',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `national_id_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_response` enum('-1','0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 does not response yet 1 accepted -1 refuesd',
  `gender` tinyint(1) DEFAULT NULL COMMENT '0 women 1 man',
  `update_type` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 data 1 tawsek 2 both',
  `decision_maker` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `update_qeueus`
--

INSERT INTO `update_qeueus` (`id`, `person_id`, `type`, `name`, `phone`, `email`, `password`, `id_photo`, `nationality`, `national_id_number`, `reason`, `admin_response`, `gender`, `update_type`, `decision_maker`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 'eslam22', '014785', 'es@gmail.com', '$2y$10$lnxOgdp.KhfMH3eukK6L9.nwH0TbxW8Hg9eMU4IoIboFIpvShDwqW', NULL, 'سعودي', '1234567895', NULL, '0', 1, '0', NULL, '2021-10-27 12:43:40', '2021-10-27 12:43:40'),
(2, 1, 0, 'eslam22', '03698', 'es@gmail.com', '$2y$10$r2ZmqssSDHeDa6yCC8ruxe9ZyJGGXpzqVOzzCG8CKEenP13G/pVYi', NULL, 'مصري', '123456789', NULL, '-1', 1, '0', NULL, '2021-10-27 12:44:47', '2021-10-27 12:44:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` double NOT NULL DEFAULT 0,
  `id_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `national_id_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => female  1 => male',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active_try` int(11) NOT NULL DEFAULT 0 COMMENT 'how many he try to send a verfication code to mobile phone',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => no  1 => yes',
  `last_try_verify` datetime DEFAULT NULL,
  `last_send_verify_code` datetime DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_notifications` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => no  1 => yes',
  `balance` int(11) NOT NULL DEFAULT 0,
  `trust` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => no  1 => yes',
  `decision_maker` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `photo`, `rate`, `id_photo`, `nationality`, `national_id_number`, `gender`, `email`, `country_code`, `phone`, `verification_code`, `active_try`, `email_verified_at`, `is_verified`, `last_try_verify`, `last_send_verify_code`, `password`, `remember_token`, `email_notifications`, `balance`, `trust`, `decision_maker`, `created_at`, `updated_at`) VALUES
(1, 'eslam2', NULL, 0, NULL, NULL, '147', 1, 'es1@gmail.com2', '966', '012345678935667', '735999', 0, NULL, 1, NULL, '2021-10-27 13:43:07', '$2y$10$u.k74vf4qYwZFqhe7VMx0uH87v4zTwX9ZcnrqpmgRxR79NFihFrCu', NULL, 0, 0, 0, 0, NULL, NULL),
(3, 'eslam2', NULL, 0, NULL, NULL, NULL, 1, 'solombana2000@gmail.com', '20', '01210732005', '625512', 0, NULL, 0, NULL, '2021-10-29 21:31:50', '$2y$10$uTP.7F/1b32hLQ971CDOdu7XKEz8IuBoWw4Ae1774ZsOjZoPjPKgW', NULL, 0, 0, 0, 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_notifications_or_email`
--
ALTER TABLE `admin_notifications_or_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertisings`
--
ALTER TABLE `advertisings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertising_days`
--
ALTER TABLE `advertising_days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertising_places`
--
ALTER TABLE `advertising_places`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertising_users`
--
ALTER TABLE `advertising_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `apllication_settings`
--
ALTER TABLE `apllication_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorie_request_subsections`
--
ALTER TABLE `categorie_request_subsections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorie_trip_subsections`
--
ALTER TABLE `categorie_trip_subsections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complains`
--
ALTER TABLE `complains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complain_lists`
--
ALTER TABLE `complain_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `copons`
--
ALTER TABLE `copons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `copon_users`
--
ALTER TABLE `copon_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers_service`
--
ALTER TABLE `customers_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fatoorahs`
--
ALTER TABLE `fatoorahs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fatoorah_lists`
--
ALTER TABLE `fatoorah_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `free_services`
--
ALTER TABLE `free_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `free_service_places`
--
ALTER TABLE `free_service_places`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_copon_users`
--
ALTER TABLE `gift_copon_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `masafr`
--
ALTER TABLE `masafr`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `masafr_email_unique` (`email`),
  ADD UNIQUE KEY `masafr_phone_unique` (`phone`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_objects`
--
ALTER TABLE `message_objects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_or_mail_people`
--
ALTER TABLE `notification_or_mail_people`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pronunciation_statements`
--
ALTER TABLE `pronunciation_statements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_categories`
--
ALTER TABLE `request_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_send_tos`
--
ALTER TABLE `request_send_tos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_services`
--
ALTER TABLE `request_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_trips`
--
ALTER TABLE `request_trips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rollback_requests`
--
ALTER TABLE `rollback_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rollback_request_money`
--
ALTER TABLE `rollback_request_money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_categories`
--
ALTER TABLE `trip_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_days`
--
ALTER TABLE `trip_days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_ways`
--
ALTER TABLE `trip_ways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `update_qeueus`
--
ALTER TABLE `update_qeueus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_notifications_or_email`
--
ALTER TABLE `admin_notifications_or_email`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `advertisings`
--
ALTER TABLE `advertisings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `advertising_days`
--
ALTER TABLE `advertising_days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `advertising_places`
--
ALTER TABLE `advertising_places`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `advertising_users`
--
ALTER TABLE `advertising_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `apllication_settings`
--
ALTER TABLE `apllication_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categorie_request_subsections`
--
ALTER TABLE `categorie_request_subsections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categorie_trip_subsections`
--
ALTER TABLE `categorie_trip_subsections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `complains`
--
ALTER TABLE `complains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `complain_lists`
--
ALTER TABLE `complain_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `copons`
--
ALTER TABLE `copons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `copon_users`
--
ALTER TABLE `copon_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers_service`
--
ALTER TABLE `customers_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fatoorahs`
--
ALTER TABLE `fatoorahs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `fatoorah_lists`
--
ALTER TABLE `fatoorah_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `free_services`
--
ALTER TABLE `free_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `free_service_places`
--
ALTER TABLE `free_service_places`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `gift_copon_users`
--
ALTER TABLE `gift_copon_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `masafr`
--
ALTER TABLE `masafr`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `message_objects`
--
ALTER TABLE `message_objects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=546;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notification_or_mail_people`
--
ALTER TABLE `notification_or_mail_people`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pronunciation_statements`
--
ALTER TABLE `pronunciation_statements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `request_categories`
--
ALTER TABLE `request_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `request_send_tos`
--
ALTER TABLE `request_send_tos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_services`
--
ALTER TABLE `request_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `request_trips`
--
ALTER TABLE `request_trips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `rollback_requests`
--
ALTER TABLE `rollback_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rollback_request_money`
--
ALTER TABLE `rollback_request_money`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `trip_categories`
--
ALTER TABLE `trip_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trip_days`
--
ALTER TABLE `trip_days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trip_ways`
--
ALTER TABLE `trip_ways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `update_qeueus`
--
ALTER TABLE `update_qeueus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
