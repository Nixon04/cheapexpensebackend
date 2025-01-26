-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 26, 2025 at 01:21 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CheapXpense`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_collections`
--

CREATE TABLE `account_collections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_id` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `reasons` varchar(255) NOT NULL,
  `payment_type` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `contact_address` varchar(255) NOT NULL,
  `region_code` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'nixonsampson04@gmail.com', '$2y$12$AJLZh47uG4Am.3JZHZR.u..NCE7fPZhyFymPMO8JBllAHBRzzaJ2G', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ads_shows`
--

CREATE TABLE `ads_shows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ad_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_frame` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ads_shows`
--

INSERT INTO `ads_shows` (`id`, `ad_id`, `title`, `title_frame`, `image`, `message`, `created_at`, `updated_at`) VALUES
(1, '492382323', 'Buy Data Today', 'Data', 'image1.png', 'Earn 2% today for less than what you might have in no time and get exclusive discount', NULL, NULL),
(2, '492382323', 'Buy Airtime at cheapcost today', 'Airtime', 'image1.png', 'Earn 2% today for less than what you might have in no time and get exclusive discount', NULL, NULL),
(3, '492382323', 'Get Instant data today', 'Cable', 'image2.png', 'Earn 2% today for less than what you might have in no time and get exclusive discount', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `airtime_to_cashes`
--

CREATE TABLE `airtime_to_cashes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `bill_type` varchar(255) NOT NULL,
  `package_number` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `warrant` varchar(255) NOT NULL,
  `instructions` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `all_services`
--

CREATE TABLE `all_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `all_services`
--

INSERT INTO `all_services` (`id`, `service_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Airtime', 'true', NULL, NULL),
(2, 'Data', 'true', NULL, NULL),
(3, 'Cable', 'true', NULL, NULL),
(4, 'Utility', 'true', NULL, NULL),
(5, 'AirtimetoCash', 'true', NULL, NULL),
(6, 'Transfer', 'false', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `app_control_panel_versions`
--

CREATE TABLE `app_control_panel_versions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `statement_approved` varchar(255) NOT NULL,
  `last_updated` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_control_panel_versions`
--

INSERT INTO `app_control_panel_versions` (`id`, `name`, `version`, `status`, `statement_approved`, `last_updated`, `created_at`, `updated_at`) VALUES
(1, 'AirtimeToCashPercentage', 'v:1:1', 'true', '0.2', '', NULL, NULL),
(2, 'AppVersion', '2.0.0', 'true', 'Upgrade now to a better suitable version where we have improved on our products for the betterment of you and everyone within', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bank_lists`
--

CREATE TABLE `bank_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_id` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_lists`
--

INSERT INTO `bank_lists` (`id`, `bank_id`, `bank_name`, `bank_image`, `created_at`, `updated_at`) VALUES
(1, '120001', '9mobile 9Payment Service Bank', '9payment.png', NULL, NULL),
(2, '404', 'Abbey Mortgage Bank', 'abbeymortgage.png', NULL, NULL),
(3, '044', 'Access Bank', 'access.png', NULL, NULL),
(4, '063', 'Access Bank (Diamond)', 'access.png', NULL, NULL),
(5, '602', 'Accion Microfinance Bank', 'accion.png', NULL, NULL),
(6, '90077', 'AG Mortgage Bank', 'agmortgage.png', NULL, NULL),
(7, '120004', 'Airtel Smartcash PSB', 'airtelpayment.png', NULL, NULL),
(8, '050', 'Ecobank Nigeria', 'ecobank.png', NULL, NULL),
(9, '51318', 'Fairmoney Microfinance Bank', 'fairmoney.png', NULL, NULL),
(10, '011', 'First Bank of Nigeria', 'firstbank.png', NULL, NULL),
(11, '214', 'First City Monument Bank', 'fcmb.png', NULL, NULL),
(12, '00103', 'Globus Bank', 'globus.png', NULL, NULL),
(13, '50515', 'Moniepoint MFB', 'moniepoint.png', NULL, NULL),
(14, '120003', 'MTN Momo PSB', 'momo.png', NULL, NULL),
(15, '999992', 'OPay Digital Services Limited', 'opay.png', NULL, NULL),
(16, '076', 'Polaris Bank', 'polaris.png', NULL, NULL),
(17, '101', 'Providus Bank', 'providus.png', NULL, NULL),
(18, '51113', 'Safe Haven MFB', 'safehaven.png', NULL, NULL),
(19, '221', 'Stanbic IBTC Ban', 'stanbic.png', NULL, NULL),
(20, '232', 'Sterling Bank', 'sterling.png', NULL, NULL),
(21, '51316', 'Unilag Microfinance Bank', 'unilag.png', NULL, NULL),
(22, '032', 'Union Bank of Nigeria', 'union.png', NULL, NULL),
(24, '215', 'Unity Bank', 'unity.png', NULL, NULL),
(25, '035', 'Wema Bank', 'wema.png', NULL, NULL),
(26, '057', 'Zenith Bank', 'zenith.png', NULL, NULL),
(27, '50211', 'Kuda Bank', 'kuda.png', NULL, NULL),
(29, '301', 'Jaiz Bank', 'jaiz.png', NULL, NULL),
(30, '058', 'Guaranty Trust Bank', 'guaranty.png', NULL, NULL),
(32, '999991', 'PalmPay', 'palmpay.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sub_content` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `reference_log` varchar(255) NOT NULL,
  `updated_date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cable_packages`
--

CREATE TABLE `cable_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `packagename` varchar(255) NOT NULL,
  `variation_name` varchar(255) NOT NULL,
  `variation_code` varchar(255) NOT NULL,
  `variation_amount` varchar(255) NOT NULL,
  `fixed_price` varchar(255) NOT NULL,
  `calculated_price` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cable_packages`
--

INSERT INTO `cable_packages` (`id`, `packagename`, `variation_name`, `variation_code`, `variation_amount`, `fixed_price`, `calculated_price`, `created_at`, `updated_at`) VALUES
(1, 'startimes', 'Nova - 900 Naira - 1 Month', 'nova', '900.00', '900.00', '0', NULL, NULL),
(2, 'startimes', 'Basic - 1,700 Naira - 1 Month', 'basic', '1700', '1700', '0', NULL, NULL),
(3, 'startimes', 'Smart - 2,200 Naira - 1 Month', 'smart', '2200', '2200', '0', NULL, NULL),
(4, 'startimes', 'Classic - 2,500 Naira - 1 Month', 'classic', '2500', '2500', '0', NULL, NULL),
(5, 'startimes', 'Super - 4,200 Naira - 1 Month', 'super', '4200', '4200', '0', NULL, NULL),
(6, 'startimes', 'Nova - 300 Naira - 1 Week', 'nova-weekly', '300', '300', '0', NULL, NULL),
(7, 'startimes', 'Basic - 600 Naira - 1 Week', 'basic-weekly', '600', '600', '0', NULL, NULL),
(8, 'startimes', 'Smart - 700 Naira - 1 Week', 'smart-weekly', '700', '700', '0', NULL, NULL),
(9, 'startimes', 'Classic - 1200 Naira - 1 Week ', 'classic-weekly', '1200', '1200', '0', NULL, NULL),
(10, 'startimes', 'Super - 1,500 Naira - 1 Week', 'super-weekly', '1500', '1500', '0', NULL, NULL),
(11, 'startimes', 'Nova - 90 Naira - 1 Day', 'nova-daily', '90', '90', '0', NULL, NULL),
(12, 'startimes', 'Basic - 160 Naira - 1 Day', 'basic-daily', '160', '1600', '0', NULL, '2024-12-08 18:14:39'),
(13, 'startimes', 'Smart - 200 Naira - 1 Day', 'smart-daily', '200', '200', '0', NULL, NULL),
(14, 'startimes', 'Classic - 320 Naira - 1 Day ', 'classic-daily', '320', '320', '0', NULL, NULL),
(15, 'startimes', 'Super - 400 Naira - 1 Day', 'super-daily', '400', '400', '0', NULL, NULL),
(16, 'dstv', 'DStv Padi N1,850', 'dstv-padi', '1850', '1850', '0', NULL, NULL),
(17, 'dstv', 'DStv Yanga N2,565', 'dstv-yanga', '2565', '2565', '0', NULL, NULL),
(18, 'dstv', 'Dstv Confam N4,615', 'dstv-confam', '4615', '4615', '0', NULL, NULL),
(19, 'dstv', 'DStv Compact N7900', 'dstv79', '7900', '7900', '0', NULL, NULL),
(20, 'dstv', 'DStv Premium N18,400', 'dstv3', '18400', '18400', '0', NULL, NULL),
(21, 'dstv', 'DStv Asia N6,200', 'dstv6', '6200', '6200', '0', NULL, NULL),
(22, 'dstv', 'DStv Compact Plus N12,400', 'dstv7', '12400', '12400', '0', NULL, NULL),
(23, 'dstv', 'DStv Premium-French N25,550', 'dstv9', '25550', '25550', '0', NULL, NULL),
(24, 'dstv', 'DStv Premium-Asia N20,500', 'dstv10', '20500', '20500', '0', NULL, NULL),
(25, 'dstv', 'DStv Confam + ExtraView N7,115', 'confam-extra', '7115', '7115', '0', NULL, NULL),
(26, 'dstv', 'DStv Yanga + ExtraView N5,065', 'yanga-extra', '5065', '5065', '0', NULL, NULL),
(27, 'dstv', 'DStv Padi + ExtraView N4,350', 'padi-extra', '4350', '4350', '0', NULL, NULL),
(28, 'dstv', 'DStv Compact + Asia N14,100', 'com-asia', '14100', '14100', '0', NULL, NULL),
(29, 'dstv', 'DStv Compact + Extra View N10,400', 'dstv30', '10400', '10400', '0', NULL, NULL),
(30, 'dstv', 'DStv Compact + French Touch N10,200', 'com-frenchtouch', '10200', '10200', '0', NULL, NULL),
(31, 'dstv', 'DStv Premium - Extra View N20,900', 'dstv33', '20900', '20900', '0', NULL, NULL),
(32, 'dstv', 'DStv Compact Plus - Asia N18,600', 'dstv40', '18600', '18600', '0', NULL, NULL),
(33, 'dstv', 'DStv Compact + French Touch + ExtraView N12,700', 'com-frenchtouch-extra', '12700', '12700', '0', NULL, NULL),
(34, 'dstv', 'DStv Compact + Asia + ExtraView N16,600', 'com-asia-extra', '16600', '16600', '0', NULL, NULL),
(35, 'dstv', 'DStv Compact Plus + French Plus N20,500', 'dstv43', '20500', '20500', '0', NULL, NULL),
(36, 'dstv', 'DStv Compact Plus + French Touch N14,700', 'complus-frenchtouch', '14700', '14700', '0', NULL, NULL),
(37, 'dstv', 'DStv Compact Plus - Extra View N14,900', 'dstv45', '14900', '14900', '0', NULL, NULL),
(38, 'dstv', 'DStv Compact Plus + FrenchPlus + Extra View N23,000', 'complus-french-extraview', '23000', '23000', '0', NULL, NULL),
(39, 'dstv', 'DStv Compact + French Plus N16,000', 'dstv47', '16000', '16000', '0', NULL, NULL),
(40, 'dstv', 'DStv Compact Plus + Asia + ExtraView N21,100', 'dstv48', '21100', '21100', '0', NULL, NULL),
(41, 'dstv', 'DStv Premium + Asia + Extra View N23,000', 'dstv61', '23000', '23000', '0', NULL, NULL),
(42, 'dstv', 'DStv Premium + French + Extra View N28,000', 'dstv62', '28000', '28000', '0', NULL, NULL),
(43, 'dstv', 'DStv HDPVR Access Service N2,500', 'hdpvr-access-service', '2500', '2500', '0', NULL, NULL),
(44, 'dstv', 'DStv French Plus Add-on N8,100', 'frenchplus-addon', '8100', '8100', '0', NULL, NULL),
(45, 'dstv', 'DStv Asian Add-on N6,200', 'asia-addon', '6200', '6200', '0', NULL, NULL),
(46, 'dstv', 'DStv French Touch Add-on N2,300', 'frenchtouch-addon', '2300', '2300', '0', NULL, NULL),
(47, 'dstv', 'ExtraView Access N2,500', 'extraview-access', '2500', '2500', '0', NULL, NULL),
(48, 'dstv', 'DStv French 11 N3,260', 'french11', '3260', '3260', '0', NULL, NULL),
(49, 'showmax', 'Full - N2,900', 'full', '2900', '2900', '0', NULL, NULL),
(50, 'gotv', 'GOtv Lite N410', 'gotv-lite', '410', '410', '0', NULL, NULL),
(51, 'gotv', 'GOtv Max N3,600', 'gotv-max', '3600', '3600', '0', NULL, NULL),
(52, 'gotv', 'GOtv Jolli N2,460', 'gotv-jolli', '2460', '2460', '0', NULL, NULL),
(53, 'gotv', 'GOtv Jinja N1,640', 'gotv-jinja', '1640', '1640', '0', NULL, NULL),
(54, 'dstv', 'GOtv Lite (3 Months) N1,080', 'gotv-lite-3months', '1080', '1080', '0', NULL, NULL),
(55, 'dstv', 'dstv', 'gotv-lite-1year', '3180', '3180', '0', NULL, '2024-12-08 16:37:35'),
(56, 'showmax', 'Mobile Only - N1,450', 'mobile_only', '1450', '1450', '0', NULL, NULL),
(57, 'showmax', 'Sports Full - N6,300', 'sports_full', '6300', '6300', '0', NULL, NULL),
(58, 'showmax', 'showmax', 'sports_mobile_only', '32000', '50000', '0', NULL, '2024-12-08 16:42:35');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_ref_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chartflows`
--

CREATE TABLE `chartflows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_year` varchar(255) NOT NULL,
  `current_month` varchar(255) NOT NULL,
  `data_events` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clear_current_id_notifications`
--

CREATE TABLE `clear_current_id_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `notification_id` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clear_notifications`
--

CREATE TABLE `clear_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clear_notifications`
--

INSERT INTO `clear_notifications` (`id`, `username`, `status`, `created_at`, `updated_at`) VALUES
(1, 'nixon', 'true', '2024-07-19 10:43:48', '2024-07-19 10:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `data_body_packages`
--

CREATE TABLE `data_body_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `packagename` varchar(255) NOT NULL,
  `packagesubname` varchar(255) NOT NULL,
  `packagereference` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_body_packages`
--

INSERT INTO `data_body_packages` (`id`, `packagename`, `packagesubname`, `packagereference`, `created_at`, `updated_at`) VALUES
(1, 'SME', '11', 'MTN', NULL, NULL),
(2, 'COOPERATE', '12', 'MTN', NULL, NULL),
(3, 'GIFTING', '13', 'MTN', NULL, NULL),
(4, 'DATASHARE', '14', 'MTN', NULL, NULL),
(5, 'SME', '21', 'AIRTEL', NULL, NULL),
(6, 'COOPERATE', '22', 'AIRTEL', NULL, NULL),
(7, 'GIFTING', '23', 'AIRTEL', NULL, NULL),
(8, 'DATASHARE', '24', 'AIRTEL', NULL, NULL),
(9, 'SME', '31', 'GLO', NULL, NULL),
(10, 'COOPERATE', '32', 'GLO', NULL, NULL),
(11, 'GIFTING', '33', 'GLO', NULL, NULL),
(12, 'DATASHARE', '34', 'GLO', NULL, NULL),
(13, 'SME', '41', '9mobile', NULL, NULL),
(14, 'COOPERATE', '42', '9mobile', NULL, NULL),
(15, 'GIFTING', '43', '9mobile', NULL, NULL),
(16, 'DATASHARE', '44', '9mobile', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deactivate_accounts`
--

CREATE TABLE `deactivate_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fund_raising_members`
--

CREATE TABLE `fund_raising_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_of_partnership` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `nature_of_collaboration` varchar(255) NOT NULL,
  `organization_name` varchar(255) NOT NULL,
  `organization_user_position` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `reference_id` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `time_reply` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gofund_mes`
--

CREATE TABLE `gofund_mes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `area_of_fundraising` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `reason_of_fundraising` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `subject_means` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `relations` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gofund_registrations`
--

CREATE TABLE `gofund_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirm_token` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `reference_id_url` varchar(255) NOT NULL,
  `current_amount` varchar(255) NOT NULL,
  `amount_check` varchar(255) NOT NULL,
  `verified_status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `email_token` varchar(255) NOT NULL,
  `email_verified` varchar(255) NOT NULL,
  `account_visibility` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_06_12_234000_create_user_signups_table', 1),
(5, '2024_06_14_172026_create_personal_access_tokens_table', 1),
(6, '2024_06_16_171309_create_user_account_details_table', 1),
(7, '2024_06_20_202845_create_transactions_table', 1),
(8, '2024_06_24_142807_create_data_body_packages_table', 1),
(9, '2024_06_26_210124_create_purchase_data_items_table', 1),
(10, '2024_07_01_233234_create_cable_packages_table', 2),
(11, '2024_07_10_213009_create_app_control_panel_versions_table', 3),
(12, '2024_07_11_083252_create_bank_lists_table', 4),
(13, '2024_07_13_115709_create_users_bank_details_table', 5),
(14, '2024_07_19_084529_create_notification_lists_table', 6),
(15, '2024_07_19_111635_create_clear_notifications_table', 7),
(16, '2024_07_19_115429_create_clear_current_id_notifications_table', 8),
(17, '2024_07_19_134918_create_news_feed_models_table', 9),
(18, '2024_08_02_165430_create_virtual_accounts_table', 10),
(19, '2024_08_06_104154_create_airtime_to_cashes_table', 11),
(20, '2024_08_11_230038_create_notification_regs_table', 12),
(21, '2024_08_12_111749_create_ads_shows_table', 13),
(22, '2024_09_19_202907_create_referrals_table', 14),
(23, '2024_10_10_154146_create_all_services_table', 15),
(24, '2024_10_10_164852_create_deactivate_accounts_table', 16),
(25, '2024_04_07_144846_create_blogs_table', 17),
(26, '2024_04_07_230855_create_categories_table', 17),
(27, '2024_04_09_124732_create_chartflows_table', 17),
(28, '2024_04_10_113601_create_members_table', 17),
(29, '2024_04_10_233046_create_account_collections_table', 17),
(30, '2024_04_11_221631_create_gofund_mes_table', 17),
(31, '2024_04_11_224330_create_gofund_registrations_table', 17),
(32, '2024_04_11_225630_create_fund_raising_members_table', 17),
(33, '2024_04_18_112213_create_news_letters_table', 17),
(34, '2024_12_08_135440_create_admins_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `news_feed_models`
--

CREATE TABLE `news_feed_models` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `news_ref_id` varchar(255) NOT NULL,
  `news_title` varchar(255) NOT NULL,
  `news_image` varchar(255) NOT NULL,
  `news_message` longtext NOT NULL,
  `last_updated` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_feed_models`
--

INSERT INTO `news_feed_models` (`id`, `news_ref_id`, `news_title`, `news_image`, `news_message`, `last_updated`, `created_at`, `updated_at`) VALUES
(1, 'chxp323823232323re', 'Call of duty is the key', 'image1.png', 'The time is awesome and we all have to understand the beauty of life', 'may 20, 2024', NULL, NULL),
(2, 'chxp323823232323re', 'the time is here to stand by', 'image2.png', 'The time is awesome and we all have to understand the beauty of life', 'may 20, 2024', NULL, NULL),
(3, 'chxp323823232323re', 'we all have the ability to hear', 'image3.png', 'The time is awesome and we all have to understand the beauty of life', 'may 20, 2024', NULL, NULL),
(4, 'chxp323823232323re', 'Get all that you need for an awesome trait', 'image4.png', 'The time is awesome and we all have to understand the beauty of life', 'may 20, 2024', NULL, NULL),
(5, 'chxp323823232323re', 'Love supercede all endeavours', 'image5.png', 'http://192.168.206.149:9000/blogimages/image1.png and we have the best of time to believe that we can be just the best in our endeavours believing in the trust http://192.168.206.149:9000/blogimages/image2.png', 'may 20, 2024', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news_letters`
--

CREATE TABLE `news_letters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_lists`
--

CREATE TABLE `notification_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `notification_id` varchar(255) NOT NULL,
  `notification_title` varchar(255) NOT NULL,
  `notification_message` longtext NOT NULL,
  `last_updated` varchar(255) NOT NULL,
  `status_read` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_lists`
--

INSERT INTO `notification_lists` (`id`, `notification_id`, `notification_title`, `notification_message`, `last_updated`, `status_read`, `created_at`, `updated_at`) VALUES
(1, 'chxp494343', 'the goal is here', 'time is money', '10 may, 2024 10:32PM', 'true', '2024-06-11 23:05:47', NULL),
(2, 'chxp494343', 'having all it takes time a minute to realize', 'time is money', '10 may, 2024 10:32PM', 'true', '2024-08-11 23:05:50', NULL),
(3, 'chxp494343', 'if all was gold', 'time is money', '10 may, 2024 10:32PM', 'false', '2024-08-11 23:05:50', NULL),
(4, 'chxp494343', 'gov\'t don boost time caping', 'time is money', '10 may, 2024 10:32PM', 'true', '2024-08-11 23:05:50', NULL),
(5, 'chxp494343', 'overall performance by manchester and we have it all', 'time is money', '10 may, 2024 10:32PM', 'true', '2024-08-11 23:05:50', NULL),
(12, 'chxp494343', 'the goal is here', 'time is money', '10 may, 2024 10:32PM', 'true', '2024-06-11 23:05:47', NULL),
(13, 'chxp494343', 'having all it takes time a minute to realize', 'time is money', '10 may, 2024 10:32PM', 'true', '2024-08-11 23:05:50', NULL),
(14, 'chxp494343', 'if all was gold', 'time is money', '10 may, 2024 10:32PM', 'false', '2024-08-11 23:05:50', NULL),
(15, 'chxp494343', 'gov\'t don boost time caping', 'time is money', '10 may, 2024 10:32PM', 'true', '2024-08-11 23:05:50', NULL),
(16, 'chxp494343', 'overall performance by manchester and we have it all', 'time is money', '10 may, 2024 10:32PM', 'true', '2024-08-11 23:05:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification_regs`
--

CREATE TABLE `notification_regs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_regs`
--

INSERT INTO `notification_regs` (`id`, `username`, `flag`, `created_at`, `updated_at`) VALUES
(1, 'nixon', '1', '2024-04-22 14:28:11', '2024-04-22 14:28:11'),
(2, 'king', '1', '2024-08-12 15:16:40', '2024-08-12 14:16:40');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_data_items`
--

CREATE TABLE `purchase_data_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `networkId` varchar(255) NOT NULL,
  `networkName` varchar(255) NOT NULL,
  `networkCode` varchar(255) NOT NULL,
  `networkPrice` varchar(255) NOT NULL,
  `networkPlansList` varchar(255) NOT NULL,
  `networkPackageSpace` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_data_items`
--

INSERT INTO `purchase_data_items` (`id`, `networkId`, `networkName`, `networkCode`, `networkPrice`, `networkPlansList`, `networkPackageSpace`, `created_at`, `updated_at`) VALUES
(1, '11', 'SME', '216', '135', '500MB for 30Days', '500MB', NULL, NULL),
(2, '11', 'SME', '217', '265', '1GB for 30Days', '1.0GB', NULL, NULL),
(3, '11', 'SME', '218', '530', '2GB for 30Days', '2.0GB', NULL, NULL),
(4, '11', 'SME', '219', '795', '3.0GB for 30Days', '3.0GB', NULL, NULL),
(5, '11', 'SME', '220', '1325', '5.0GB for 30Days', '5.0GB', NULL, NULL),
(6, '11', 'SME', '221', '2650', '10GB for 30Days', '10.0GB', NULL, NULL),
(7, '11', 'SME', '222', '135', '500MB for 30Days', '500MB', NULL, NULL),
(8, '11', 'SME', '223', '265', '1.0GB for 30Days', '1.0GB', NULL, NULL),
(9, '11', 'SME', '224', '400', '1.5GB for 30Days', '1.5GB', NULL, NULL),
(10, '11', 'SME', '225', '530', '2GB for 30Days', '2.0GB', NULL, NULL),
(11, '11', 'SME', '226', '795', '3GB for 30Days', '3.0GB', NULL, NULL),
(12, '11', 'SME', '227', '1375', '5GB for 30Days', '5.0GB', NULL, NULL),
(13, '11', 'SME', '228', '2650', '10.0GB for 30Days', '10.0GB', NULL, NULL),
(14, '12', 'COOPERATE', '229', '35', '50MB for 30Days', '50MB', NULL, NULL),
(15, '12', 'COOPERATE', '230', '55', '150MB for 30Days', '150MB', NULL, NULL),
(16, '12', 'COOPERATE', '231', '70', '250MB for 30Days', '250MB', NULL, NULL),
(17, '12', 'COOPERATE', '232', '135', '500MB for 30Days', '500MB', NULL, NULL),
(18, '12', 'COOPERATE', '224', '538', '2.0GB for 30Days', '2.0GB', NULL, NULL),
(19, '12', 'COOPERATE', '235', '807', '3.0GB for 30Days', '3.0GB', NULL, NULL),
(20, '12', 'COOPERATE', '236', '1345', '5GB for 30Days', '5.0GB', NULL, NULL),
(21, '12', 'COOPERATE', '237', '2690', '10GB for 30Days', '10.0GB', NULL, NULL),
(22, '12', 'COOPERATE', '238', '4035', '15.0GB for 30Days', '15.0GB', NULL, NULL),
(23, '12', 'COOPERATE', '239', '5380', '20.0GB for 30Days', '20.0GB', NULL, NULL),
(24, '12', 'COOPERATE', '240', '10760', '40GB for 30Days', '40.0GB', NULL, NULL),
(25, '12', 'COOPERATE', '241', '20175', '75.0GB for 30Days', '75.0GB', NULL, NULL),
(26, '12', 'COOPERATE', '242', '26900', '100GB for 30Days', '100GB', NULL, NULL),
(27, '12', 'AWOOF', '244', '550', '3.5GB for 2Days', '3.5GB', NULL, NULL),
(28, '12', 'AWOOF', '245', '2150', '15GB for 7Days', '15.0GB', NULL, NULL),
(29, '13', 'GIFTING', '246', '48', '40MB for 1Day', '40MB', NULL, NULL),
(30, '13', 'GIFTING', '247', '97', '100MB for 1Day', '100MB', NULL, NULL),
(31, '13', 'GIFTING', '248', '289', '1.0GB for 1Day', '1.0GB', NULL, NULL),
(32, '13', 'GIFTING', '249', '583', '2.5GB for 2Days', '2.5GB', NULL, NULL),
(33, '13', 'GIFTING', '250', '485', '750MB for 7Days', '750MB', NULL, NULL),
(34, '13', 'GIFTING', '251', '485', '1GB for 7Days', '1.0GB', NULL, NULL),
(35, '13', 'GIFTING', '252', '1455', '5GB for 7Days', '5.0GB', NULL, NULL),
(36, '13', 'GIFTING', '253', '1940', '7.0GB for 7Days', '7.0GB', NULL, NULL),
(97, '13', 'GIFTING', '254', '970', '1.2GB for 30Days', '1.2GB', NULL, NULL),
(98, '13', 'GIFTING', '255', '1164', '1.5GB for 30Day', '1.5GB', NULL, NULL),
(99, '13', 'GIFTING', '256', '1552', '3.0GB for 30Days', '3.0GB', NULL, NULL),
(100, '13', 'GIFTING', '257', '2910', '8.0GB for 30Days', '8.0GB', NULL, NULL),
(101, '13', 'GIFTING', '258', '3395', '10.0GB for 30Days', '10.0GB', NULL, NULL),
(102, '13', 'GIFTING', '259', '3880', '12GB for 30Days', '12.0GB', NULL, NULL),
(103, '13', 'GIFTING', '260', '5335', '20GB for 30Days', '20.0GB', NULL, NULL),
(104, '13', 'GIFTING', '261', '6305', '25GB for 30Days', '25.0GB', NULL, NULL),
(105, '13', 'GIFTING', '262', '10670', '40.0GB for 30Days', '40.0GB', NULL, NULL),
(106, '13', 'GIFTING', '263', '15520', '75GB for 30Days', '75.0GB', NULL, NULL),
(107, '13', 'GIFTING', '264', '21340', '120GB for 30Days', '120.0GB', NULL, NULL),
(108, '13', 'GIFTING', '265', '29100', '200GB for 30Days', '200.0GB', NULL, NULL),
(109, '13', 'GIFTING', '266', '19400', '100.0GB for 60Days', '100.0GB', NULL, NULL),
(110, '13', 'GIFTING', '267', '29100', '160GB for 60Days', '160GB', NULL, NULL),
(111, '13', 'GIFTING', '268', '48500', '400GB for 90Days', '400GB', NULL, NULL),
(112, '13', 'GIFTING', '269', '100000', '1.0TB for 1 Year', '1.0TB', NULL, '2024-12-08 15:50:17'),
(113, '14', 'DATA SHARE', '356', '245', '1GB for 13Days', '1.0GB', NULL, NULL),
(114, '14', 'DATA SHARE', '357', '490', '2.0GB for 13Days', '2.0GB', NULL, NULL),
(115, '14', 'DATA SHARE', '358', '735', '3GB for 13Days', '3.0GB', NULL, NULL),
(116, '14', 'DATA SHARE', '359', '1225', '5GB for 13Days', '5.0GB', NULL, NULL),
(117, '14', 'DATA SHARE', '361', '125', '500MB for 30Days', '500MB', NULL, NULL),
(118, '22', 'COOPERATE GIFTING', '270', '35', '100.MB for 7Days', '100MB', NULL, NULL),
(119, '22', 'COOPERATE GIFTING', '271', '95', '300.MB for 7Days', '300MB', NULL, NULL),
(120, '22', 'COOPERATE GIFTING', '272', '140', '500MB for 30Days', '500MB', NULL, NULL),
(121, '22', 'COOPERATE GIFTING', '273', '275', '1.0GB for 30Days', '1.0GB', NULL, NULL),
(122, '22', 'COOPERATE GIFTING', '274', '550', '2.0GB for 30Days', '2.0GB', NULL, NULL),
(123, '22', 'COOPERATE GIFTING', '275', '1375', '5.0GB for 30Days', '5.0GB', NULL, NULL),
(124, '22', 'COOPERATE GIFTING', '276', '2750', '10.0GB for 30Days', '10.0GB', NULL, NULL),
(125, '22', 'COOPERATE GIFTING', '277', '4125', '15GB for 30Days', '15.0GB', NULL, NULL),
(126, '22', 'COOPERATE GIFTING', '278', '5500', '20GB for 30Days', '20.0GB', NULL, NULL),
(127, '23', 'GIFTING', '285', '490', '2.5GB for 2Days', '2.5GB', NULL, NULL),
(128, '23', 'GIFTING', '287', '1470', '6.0GB for 7Days', '6.0GB', NULL, NULL),
(129, '23', 'GIFTING', '288', '980', '1.2GB for 30Days', '1.2GB', NULL, NULL),
(130, '23', 'GIFTING', '289', '1176', '1.5GB for 30Days', '1.5GB', NULL, NULL),
(131, '23', 'GIFTING', '290', '1470', '3.0GB for 30Days', '3.0GB', NULL, NULL),
(132, '23', 'GIFTING', '291', '1960', '4.5GB for 30Days', '4.5GB', NULL, NULL),
(133, '23', 'GIFTING', '292', '2450', '6.0GB for 30Days', '6.0GB', NULL, NULL),
(134, '23', 'GIFTING', '293', '2940', '10.0GB for 30Days', '10.0GB', NULL, NULL),
(135, '23', 'GIFTING', '294', '3920', '15GB for 30Days', '15.0GB', NULL, NULL),
(136, '23', 'GIFTING', '295', '4900', '18GB for 30Days', '18.0GB', NULL, NULL),
(137, '23', 'GIFTING', '296', '7840', '30.0GB for 30Days', '30.0GB', NULL, NULL),
(138, '23', 'GIFTING', '297', '9800', '40.0GB for 30Days', '40.0GB', NULL, NULL),
(139, '23', 'GIFTING', '298', '14700', '75.0GB for 30Days', '75.0GB', NULL, NULL),
(140, '23', 'GIFTING', '299', '19600', '120.0GB for 30Days', '120.0GB', NULL, NULL),
(141, '23', 'GIFTING', '300', '29400', '240GB for 30Days', '240.0GB', NULL, NULL),
(142, '23', 'GIFTING', '301', '35280', '280.0GB for 30Days', '280.0GB', NULL, NULL),
(143, '23', 'GIFTING', '302', '49000', '400GB for 90Days', '400GB', NULL, NULL),
(144, '23', 'GIFTING', '303', '58800', '500GB for 120Days', '500GB', NULL, NULL),
(145, '23', 'GIFTING', '304', '98000', '1.0TB for 1 Year', '1.0TB', NULL, NULL),
(146, '32', 'COOPERATE GIFTING', '305', '70', '200MB for 14Days', '200MB', NULL, NULL),
(147, '32', 'COOPERATE GIFTING', '306', '140', '500MB for 30Days', '500MB', NULL, NULL),
(148, '32', 'COOPERATE GIFTING', '307', '265', '1.0GB for 30Days', '1.0GB', NULL, NULL),
(149, '32', 'COOPERATE GIFTING', '308', '530', '2.0GB for 30Days', '2.0GB', NULL, NULL),
(150, '32', 'COOPERATE GIFTING', '309', '795', '3.0GB for 30Days', '3.0GB', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `reg_user` varchar(255) NOT NULL,
  `reg_amount` text NOT NULL,
  `reg_transact_total` varchar(255) NOT NULL,
  `reg_link` varchar(255) NOT NULL,
  `earning_per_referral` varchar(255) NOT NULL,
  `reg_total` varchar(255) NOT NULL,
  `reg_date` varchar(255) NOT NULL,
  `reg_status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `referrals`
--

INSERT INTO `referrals` (`id`, `username`, `reg_user`, `reg_amount`, `reg_transact_total`, `reg_link`, `earning_per_referral`, `reg_total`, `reg_date`, `reg_status`, `created_at`, `updated_at`) VALUES
(1, 'nixon', 'stanley', '0', '1', '', '2', '1', '24 May, 2024', 'paid', NULL, '2024-10-10 14:02:34'),
(2, 'nixon', 'kingsley', '0', '1', '', '2', '1', '24 May, 2024', 'paid', NULL, '2024-10-10 14:02:34');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0qbDreaT3s2BEEXuDRlZ8cyW1gYxmHakbjvEnE4j', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWjljMldQdUdNTTlEN1J6ZjN6NzZINlhqSVJGY0R0TzlrM3NpaTl2eCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGVhcHgvZGFzaGJvYXJkL2hvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjY6InVzZXJpZCI7czoyNDoibml4b25zYW1wc29uMDRAZ21haWwuY29tIjt9', 1733752717),
('7XjgdtTDG5uz0Z87wPo59pmO3DFqhv6qBKcv4JAN', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTnpPOXdXY1J6V2gxTVprWWtIZ3J0czNqQnRpUEdsY0o0NkU1c3RuVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGVhcHgvZGFzaGJvYXJkL3JlZ3VzZXJzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1733772164),
('Jrw03Io7SslowOTtk38fp8CRhsjaY21ssxu7CvgU', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia3ZYbjZacnhtVkJDUjRHQ0U2dkxBaVBqY2FVRnJ4TWg4b0VxR1cwcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGVhcHgvZGFzaGJvYXJkL3JlZ3VzZXJzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1733798820),
('wIKQEh6OOE7X0Xe0iv0ULdA3Yp1zlqGN3H5KX8MQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNTBVNTB1b1Z1YlVJQWU4QzM0a3hpb3pBQk4zTnh3a0F0bWZxV0hEUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGVhcHgvZGFzaGJvYXJkL3JlZ3VzZXJzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1733784876),
('WVx13M7EgfRArh6oOcyb5kLJOT22mOT1njTTE5m3', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTnc4RXB3dlpKb2h1QURrSXJOTDFRTFM3UkdIbEoyMElQQnNoemZtayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoidXNlcmlkIjtzOjI0OiJuaXhvbnNhbXBzb24wNEBnbWFpbC5jb20iO30=', 1733772164),
('zRVgQZZoXF26Hs57UMYKZ0BxzNIp8aDvq8pdZYZ2', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWm9YdzRDZkljNlJhZFcya09xSGFtQjh3WFA5WVVXQ1pnVXdkZUlXOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGVhcHgvZGFzaGJvYXJkL3JlZ3VzZXJzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1733848325);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `type_of_purchase` varchar(255) NOT NULL,
  `sub_type_purchase` varchar(255) NOT NULL,
  `data_type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `ref_num_purchase` varchar(255) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `date_of_purchase` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `username`, `amount`, `type_of_purchase`, `sub_type_purchase`, `data_type`, `status`, `ref_num_purchase`, `reference`, `date_of_purchase`, `created_at`, `updated_at`) VALUES
(296, 'Nixon', '50', 'Airtime', 'etisalat', 'VTU', 'success', '09096213122', '20241004115157e58562f191a5', 'October 04, 2024 | 11:51 AM', '2024-10-04 09:52:04', '2024-10-04 09:52:04'),
(297, 'Nixon', '500', 'Utility', 'PortHarcourt Electricity', 'Token : 57880316552890870667', 'success', '09068225050', '202410041205156e77c6995c53', 'October 04, 2024 | 12:05 PM', '2024-10-04 10:05:24', '2024-10-04 10:05:24'),
(298, 'Nixon', '500', 'Utility', 'Kaduna Electricity', 'Token : 20229412358945840218', 'success', '09068225050', '202410041206245a9ca6d1948d', 'October 04, 2024 | 12:06 PM', '2024-10-04 10:06:32', '2024-10-04 10:06:32'),
(299, 'Nixon', '500', 'Utility', 'Jos Electricity', 'Token : 5597-5859-9066-3420-0216', 'success', '09068225050', '2024100412071490dc143deb27', 'October 04, 2024 | 12:07 PM', '2024-10-04 10:07:27', '2024-10-04 10:07:27'),
(300, 'Nixon', '500', 'Utility', 'Enugu Electricity', 'Token : 45105559352377689980', 'success', '09068225050', '20241004120806cde42186d609', 'October 04, 2024 | 12:08 PM', '2024-10-04 10:08:16', '2024-10-04 10:08:16'),
(301, 'Nixon', '500', 'Utility', 'Abuja Electricity', 'Token : 3359-3176-5478-0062-9598', 'success', '09068225050', '2024100412090741b348a535f6', 'October 04, 2024 | 12:09 PM', '2024-10-04 10:09:14', '2024-10-04 10:09:14'),
(302, 'Nixon', '500', 'Utility', 'Benin Electricity', 'Token : 36001644489787932779', 'success', '09068225050', '20241004120929b82a95c453a8', 'October 04, 2024 | 12:09 PM', '2024-10-04 10:09:37', '2024-10-04 10:09:37'),
(303, 'Nixon', '35', 'Data', 'AIRTEL', 'COOPERATE', 'success', '08086850429', '20241004202200534c2c7b7c55', 'October 04, 2024 | 08:22 PM', '2024-10-04 18:22:21', '2024-10-04 18:22:21'),
(304, 'Nixon', '35', 'Data', 'AIRTEL', 'COOPERATE', 'success', '08086850429', '202410042024587c60282cbfbc', 'October 04, 2024 | 08:24 PM', '2024-10-04 18:25:11', '2024-10-04 18:25:11'),
(305, 'Nixon', '500', 'Utility', 'Eko Electricity', 'Token : 11786621902768210244', 'success', '09068225050', '20241004202651dd5d470c0d50', 'October 04, 2024 | 08:26 PM', '2024-10-04 18:27:03', '2024-10-04 18:27:03'),
(306, 'Nixon', '100', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '20241004212753684bb8240b96', 'October 04, 2024 | 09:27 PM', '2024-10-04 19:28:01', '2024-10-04 19:28:01'),
(307, 'Nixon', '90', 'Cable', 'startimes', 'change', 'success', '09068225050', '20241004212935243f1c3d9942', 'October 04, 2024 | 09:29 PM', '2024-10-04 19:29:44', '2024-10-04 19:29:44'),
(308, 'Nixon', '100', 'VirtualWallet', 'Transfer', '', 'pending', '', '202410042210186fcecc38950b473b', 'October 04, 2024 | 10:10 PM', '2024-10-04 20:15:02', '2024-10-04 20:15:02'),
(309, 'Nixon', '100', 'VirtualWallet', 'Transfer', '', 'pending', '', '20241004221711a02db841fb1a480c', 'October 04, 2024 | 10:17 PM', '2024-10-04 20:17:19', '2024-10-04 20:17:19'),
(310, 'Nixon', '20', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '2024100811534822b142bd040e', 'October 08, 2024 | 11:53 AM', '2024-10-08 09:53:56', '2024-10-08 09:53:56'),
(311, 'Nixon', '20', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '2024100811534822b142bd040e', 'October 08, 2024 | 11:53 AM', '2024-10-08 09:53:57', '2024-10-08 09:53:57'),
(312, 'diepreye', '100', 'VirtualWallet', 'Transfer', '', 'pending', '', '20241008140552f7e53189608a4504', 'October 08, 2024 | 02:05 PM', '2024-10-08 12:06:02', '2024-10-08 12:06:02'),
(313, 'diepreye', '100', 'VirtualWallet', 'Transfer', '', 'pending', '', '2024100814091559e38f6ee5d7473d', 'October 08, 2024 | 02:09 PM', '2024-10-08 12:09:26', '2024-10-08 12:09:26'),
(314, 'diepreye', '1000', 'VirtualWallet', 'Transfer', 'time', 'pending', '', '20241008143220f1f0a73094ce426a', 'October 08, 2024 | 02:32 PM', '2024-10-08 12:32:29', '2024-10-08 12:32:29'),
(315, 'diepreye', '500', 'VirtualWallet', 'Transfer', 'time', 'pending', '', '202410081433529169d51ec23f4c49', 'October 08, 2024 | 02:33 PM', '2024-10-08 12:34:00', '2024-10-08 12:34:00'),
(316, 'diepreye', '500', 'VirtualWallet', 'Transfer', 'time', 'pending', '', '20241008143625ef1b90bddff241e5', 'October 08, 2024 | 02:36 PM', '2024-10-08 12:36:34', '2024-10-08 12:36:34'),
(317, 'diepreye', '100', 'VirtualWallet', 'Transfer', 'time', 'success', '', '20241008143925bb092b2579264e09', 'October 08, 2024 | 02:39 PM', '2024-10-08 12:39:33', '2024-10-08 12:39:33'),
(318, 'diepreye', '1000', 'VirtualWallet', 'Transfer', 'time', 'success', '', '20241008144042fbf885b3441b4ac7', 'October 08, 2024 | 02:40 PM', '2024-10-08 12:40:50', '2024-10-08 12:40:50'),
(319, 'diepreye', '1000', 'VirtualWallet', 'Transfer', 'time', 'success', '', '202410081445386a5dc82c25c84b14', 'October 08, 2024 | 02:45 PM', '2024-10-08 12:45:46', '2024-10-08 12:45:46'),
(320, 'Nixon', '35', 'Data', 'AIRTEL', 'COOPERATE', 'success', '08086850429', '20241009121936499ff39215f2', 'October 09, 2024 | 12:19 PM', '2024-10-09 10:19:44', '2024-10-09 10:19:44'),
(321, 'Nixon', '100', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '2024100912202695a5b1e5f34f', 'October 09, 2024 | 12:20 PM', '2024-10-09 10:20:32', '2024-10-09 10:20:32'),
(322, 'Nixon', '100', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '2024100912202695a5b1e5f34f', 'October 09, 2024 | 12:20 PM', '2024-10-09 10:20:33', '2024-10-09 10:20:33'),
(323, 'love', '35', 'Data', 'AIRTEL', 'COOPERATE', 'success', '08086850429', '20241009122246df1a8b51d39f', 'October 09, 2024 | 12:22 PM', '2024-10-09 10:23:41', '2024-10-09 10:23:41'),
(324, 'Nixon', '100', 'VirtualWallet', 'Transfer', '', 'success', '', '20241009122542415b44735d8447c4', 'October 09, 2024 | 12:25 PM', '2024-10-09 10:25:50', '2024-10-09 10:25:50'),
(325, 'love', '100', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '2024100913374301c63f41ac36', 'October 09, 2024 | 01:37 PM', '2024-10-09 11:37:52', '2024-10-09 11:37:52'),
(326, 'love', '100', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '20241009133956a4fbbbd51532', 'October 09, 2024 | 01:39 PM', '2024-10-09 11:40:03', '2024-10-09 11:40:03'),
(327, 'love', '100', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '20241009133956a4fbbbd51532', 'October 09, 2024 | 01:39 PM', '2024-10-09 11:40:47', '2024-10-09 11:40:47'),
(328, 'love', '100', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '20241009133956a4fbbbd51532', 'October 09, 2024 | 01:39 PM', '2024-10-09 11:41:16', '2024-10-09 11:41:16'),
(329, 'love', '100', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '20241009133956a4fbbbd51532', 'October 09, 2024 | 01:39 PM', '2024-10-09 11:44:15', '2024-10-09 11:44:15'),
(330, 'love', '100', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '20241009133956a4fbbbd51532', 'October 09, 2024 | 01:39 PM', '2024-10-09 11:44:39', '2024-10-09 11:44:39'),
(331, 'love', '100', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '202410091935027c831919b3e5', 'October 09, 2024 | 07:35 PM', '2024-10-09 17:35:09', '2024-10-09 17:35:09'),
(332, 'love', '50', 'Airtime', 'MTN', 'VTU', 'success', '09068225050', '20241009195221efd3dd506074', 'October 09, 2024 | 07:52 PM', '2024-10-09 17:52:27', '2024-10-09 17:52:27'),
(333, 'Nixon', '35', 'Data', 'AIRTEL', 'COOPERATE', 'success', '08086850429', '20241010104636d0e9c42d80d5', 'October 10, 2024 | 10:46 AM', '2024-10-10 08:46:50', '2024-10-10 08:46:50'),
(334, 'love', '160', 'Cable', 'startimes', 'change', 'success', '09068225050', '20241010113207a02ccc3071cf', 'October 10, 2024 | 11:32 AM', '2024-10-10 09:32:14', '2024-10-10 09:32:14'),
(335, 'Nixon', '1375', 'Data', 'AIRTEL', 'COOPERATE', 'success', '09078935667', '2024101019165092e2bdc69ad6', 'October 10, 2024 | 07:16 PM', '2024-10-10 17:17:00', '2024-10-10 17:17:00'),
(336, 'Nixon', '275', 'Data', 'AIRTEL', 'COOPERATE', 'success', '08086850429', '2024101020345503320faaaba4', 'October 10, 2024 | 08:34 PM', '2024-10-10 18:35:11', '2024-10-10 18:35:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_bank_details`
--

CREATE TABLE `users_bank_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `bank_name_id` varchar(255) NOT NULL,
  `user_account` varchar(255) NOT NULL,
  `bank_user_name` varchar(255) NOT NULL,
  `user_bank` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users_bank_details`
--

INSERT INTO `users_bank_details` (`id`, `username`, `bank_name_id`, `user_account`, `bank_user_name`, `user_bank`, `created_at`, `updated_at`) VALUES
(11, 'king', 'Access Bank', '1483693022', 'SAMPSON NENGIMOTE NIXON', '', '2024-07-13 13:23:13', '2024-07-13 13:23:13'),
(12, 'clinton', 'Access Bank', '148369302q', 'Couldn\'t verify account', '', '2024-07-13 13:23:25', '2024-07-13 13:23:25'),
(13, 'john', 'Access Bank', '148369302q', 'Couldn\'t verify account', '', '2024-07-13 13:24:43', '2024-07-13 13:24:43'),
(14, 'ebuka', 'Access Bank', '1483693011', 'Couldn\'t verify account', '', '2024-07-13 13:30:55', '2024-07-13 13:30:55'),
(15, 'fred', 'First City Monument Bank', '1342563018', 'TOBIN SPIFF NIXON BIOBARAGHA', '', '2024-07-13 18:15:48', '2024-07-13 18:15:48'),
(26, 'nixon\r\n', 'First City Monument Bank', '1342563018', 'TOBIN SPIFF NIXON BIOBARAGHA', '', '2024-07-13 18:15:48', '2024-07-13 18:15:48'),
(54, 'Nixon', 'OPay Digital Services Limited', '9068225050', 'SAMPSON NENGIMOTE NIXON', '', '2024-10-09 10:27:16', '2024-10-09 10:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `user_account_details`
--

CREATE TABLE `user_account_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_ref_id` varchar(255) NOT NULL,
  `user_amount` varchar(255) NOT NULL,
  `user_bonus` varchar(255) NOT NULL,
  `last_update` varchar(255) NOT NULL,
  `withdrawer_count` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_account_details`
--

INSERT INTO `user_account_details` (`id`, `username`, `user_ref_id`, `user_amount`, `user_bonus`, `last_update`, `withdrawer_count`, `created_at`, `updated_at`) VALUES
(1, 'nixon', '358111nixon', '2180', '0', '2024-06-29 17:09:06', '0', '2024-06-29 16:09:06', '2024-10-10 18:35:00'),
(2, 'king', '516109king', '0', '0.00', '2024-08-11 01:57:19', '0', '2024-08-11 00:57:19', '2024-08-12 07:56:44'),
(3, 'Nixo', '663095Nixo', '0.00', '0.00', '2024-09-25 23:33:31', '0', '2024-09-25 22:33:31', '2024-09-25 22:33:31'),
(4, 'diepreye', '772638diepreye', '5900', '0.00', '2024-10-04 19:44:27', '0', '2024-10-04 18:44:27', '2024-10-08 12:45:45'),
(5, 'Nengi', '187150Nengi', '0.00', '0.00', '2024-10-08 14:28:13', '0', '2024-10-08 13:28:13', '2024-10-08 13:28:13'),
(6, 'Finland', '565093Finland', '0.00', '0.00', '2024-10-08 14:30:54', '0', '2024-10-08 13:30:54', '2024-10-08 13:30:54'),
(7, 'Ebuka', '573077Ebuka', '0.00', '0.00', '2024-10-08 15:37:52', '0', '2024-10-08 14:37:52', '2024-10-08 14:37:52'),
(8, 'Love', '767025Love', '55', '0.00', '2024-10-08 15:40:37', '0', '2024-10-08 14:40:37', '2024-10-10 09:32:11'),
(9, 'Nengimote', '621585Nengimote', '0.00', '0.00', '2024-10-09 11:29:08', '0', '2024-10-09 10:29:08', '2024-10-09 10:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_signups`
--

CREATE TABLE `user_signups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profileimage` text NOT NULL,
  `resetcode` varchar(255) NOT NULL,
  `dob` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `users_id` varchar(255) NOT NULL,
  `referral_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_signups`
--

INSERT INTO `user_signups` (`id`, `fullname`, `username`, `email`, `contact`, `password`, `profileimage`, `resetcode`, `dob`, `date`, `users_id`, `referral_id`, `created_at`, `updated_at`) VALUES
(1, 'nixon sampson', 'nixon', 'nixonsampson04@gmail.com', '09068225050', '$2y$12$.gmDadP7BBhlBoRTmR/uGObGrpEyJXP/buncTxmLmDwDzDdC8QGQq', '1000264953.jpg', '2329', '2011-06-08', '2024-06-29 17:09:05', '5555', '88272323', '2024-06-29 16:09:05', '2024-10-10 18:09:19'),
(2, 'king fedrick', 'king', 'king@gmail.com', '09068225050', '$2y$12$Zr/jnh0fjQ3rH8wHoQRfuu/KBI7EnenBBzmnblxVM1lyrkUwZnLsK', '1000000033.jpg', '1583', '2000-07-28', '2024-08-11 01:57:19', '1234', '565130', '2024-08-11 00:57:19', '2024-08-12 07:39:00'),
(4, 'Nixon sampson', 'Nixo', 'nixonsampson@gmail.com', '09068225050', '$2y$12$R2H.vBcJaxITLHLxQml97OVPv9i799i5cdR4VK3LQ2mZ3F80tbyWy', '0', '', '2024-09-03', '2024-09-25 23:33:31', '1234', '148201', '2024-09-25 22:33:31', '2024-09-25 22:33:31'),
(5, 'dieprey nixo', 'diepreye', 'diepreye@gmail.com', '09068225050', '$2y$12$ptPqPFxnTt.MXf4/OmaigeVdARP41hVsTg/w4gpDUSp/J3OAxmala', '0', '', '1999-10-22', '2024-10-04 19:44:27', '7952', '516302', '2024-10-04 18:44:27', '2024-10-04 18:44:27'),
(6, 'Nixon sampson', 'Nengi', 'Nengimote@gmail.com', '09068225050', '$2y$12$Pu/XZYmsjmN3pMOgYf5I8eLetVSl.Po5vlAwHwc2aYC6Fh9QeXKTS', '0', '', '2008-10-08', '2024-10-08 14:28:13', '1234', '478757', '2024-10-08 13:28:13', '2024-10-08 13:28:13'),
(7, 'Nixon sampson', 'Finland', 'Nengimotes@gmail.com', '09068225050', '$2y$12$yw48eLPwtaRwuytnXocau.KnH8zMVtERtbYQwyQ7G49.UulBI7F/y', '0', '', '2011-10-08', '2024-10-08 14:30:54', '7777', '598370', '2024-10-08 13:30:54', '2024-10-08 13:30:54'),
(8, 'Colis ebuka', 'Ebuka', 'ebuka@gmail.com', '09068225050', '$2y$12$wn/IxfgyHoWA/2vMTpxTjOjnMYYNnXuTWz0mQawYq0bJATu2x/p2K', '0', '', '2024-10-08', '2024-10-08 15:37:52', '7777', '315563', '2024-10-08 14:37:52', '2024-10-08 14:37:52'),
(9, 'Love colins', 'Love', 'love@gmail.com', '09068225050', '$2y$12$BBzMh0VK1sHr47.GggkpoOMqXhxhnZWY63Kv4NiE90/8Tid7H3UTm', '0', '', '2011-10-08', '2024-10-08 15:40:37', '8888', '303367', '2024-10-08 14:40:37', '2024-10-08 14:40:37'),
(10, 'Nengi sam', 'Nengimote', 'Nengimote04@gmail.com', '09068225050', '$2y$12$FTfGgjJseehIurm6SlREJegZbr8TEx88Z0PwAM2B41UFgJnfF2UpS', '0', '', '2024-10-09', '2024-10-09 11:29:08', '7952', '672356', '2024-10-09 10:29:08', '2024-10-09 10:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `virtual_accounts`
--

CREATE TABLE `virtual_accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `current_bank` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `virtual_accounts`
--

INSERT INTO `virtual_accounts` (`id`, `username`, `account_name`, `account_number`, `current_bank`, `date`, `created_at`, `updated_at`) VALUES
(43, 'Nengi', '0', '0', 'WEMA', '10 08, 2024 : 04:26:52', '2024-10-08 14:26:52', '2024-10-08 14:26:52'),
(44, 'Ebuka', '0', '0', 'WEMA', '10 08, 2024 : 04:37:54', '2024-10-08 14:37:54', '2024-10-08 14:37:54'),
(45, 'Love', 'TEST-MANAGED-ACCOUNT', '1238213500', 'WEMA', '10 08, 2024 : 04:40:38', '2024-10-08 14:40:38', '2024-10-08 14:40:38'),
(46, 'Nengimote', 'TEST-MANAGED-ACCOUNT', '1238213854', 'WEMA', '10 09, 2024 : 12:29:08', '2024-10-09 10:29:08', '2024-10-09 10:29:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_collections`
--
ALTER TABLE `account_collections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ads_shows`
--
ALTER TABLE `ads_shows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `airtime_to_cashes`
--
ALTER TABLE `airtime_to_cashes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `all_services`
--
ALTER TABLE `all_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_control_panel_versions`
--
ALTER TABLE `app_control_panel_versions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_lists`
--
ALTER TABLE `bank_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cable_packages`
--
ALTER TABLE `cable_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chartflows`
--
ALTER TABLE `chartflows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clear_current_id_notifications`
--
ALTER TABLE `clear_current_id_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clear_notifications`
--
ALTER TABLE `clear_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_body_packages`
--
ALTER TABLE `data_body_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deactivate_accounts`
--
ALTER TABLE `deactivate_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fund_raising_members`
--
ALTER TABLE `fund_raising_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gofund_mes`
--
ALTER TABLE `gofund_mes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gofund_registrations`
--
ALTER TABLE `gofund_registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_feed_models`
--
ALTER TABLE `news_feed_models`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_letters`
--
ALTER TABLE `news_letters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_lists`
--
ALTER TABLE `notification_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_regs`
--
ALTER TABLE `notification_regs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `purchase_data_items`
--
ALTER TABLE `purchase_data_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `users_bank_details`
--
ALTER TABLE `users_bank_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_account_details`
--
ALTER TABLE `user_account_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_signups`
--
ALTER TABLE `user_signups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `virtual_accounts`
--
ALTER TABLE `virtual_accounts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_collections`
--
ALTER TABLE `account_collections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ads_shows`
--
ALTER TABLE `ads_shows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `airtime_to_cashes`
--
ALTER TABLE `airtime_to_cashes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `all_services`
--
ALTER TABLE `all_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `app_control_panel_versions`
--
ALTER TABLE `app_control_panel_versions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bank_lists`
--
ALTER TABLE `bank_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cable_packages`
--
ALTER TABLE `cable_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chartflows`
--
ALTER TABLE `chartflows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clear_current_id_notifications`
--
ALTER TABLE `clear_current_id_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clear_notifications`
--
ALTER TABLE `clear_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `data_body_packages`
--
ALTER TABLE `data_body_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `deactivate_accounts`
--
ALTER TABLE `deactivate_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fund_raising_members`
--
ALTER TABLE `fund_raising_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gofund_mes`
--
ALTER TABLE `gofund_mes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gofund_registrations`
--
ALTER TABLE `gofund_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `news_feed_models`
--
ALTER TABLE `news_feed_models`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `news_letters`
--
ALTER TABLE `news_letters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_lists`
--
ALTER TABLE `notification_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `notification_regs`
--
ALTER TABLE `notification_regs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_data_items`
--
ALTER TABLE `purchase_data_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=337;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_bank_details`
--
ALTER TABLE `users_bank_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `user_account_details`
--
ALTER TABLE `user_account_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_signups`
--
ALTER TABLE `user_signups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `virtual_accounts`
--
ALTER TABLE `virtual_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
