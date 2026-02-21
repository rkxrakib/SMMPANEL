-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 30, 2025 at 09:29 PM
-- Server version: 10.11.14-MariaDB-cll-lve
-- PHP Version: 8.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clubtru5_dipjouti22`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_type` enum('3','2') NOT NULL DEFAULT '2',
  `admin_name` varchar(255) DEFAULT NULL,
  `admin_email` text DEFAULT NULL,
  `username` varchar(225) DEFAULT NULL,
  `password` text NOT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `register_date` datetime NOT NULL,
  `login_date` datetime DEFAULT NULL,
  `login_ip` varchar(225) DEFAULT NULL,
  `client_type` enum('1','2') NOT NULL DEFAULT '2' COMMENT '2 -> ON, 1 -> OFF',
  `access` varchar(999) NOT NULL,
  `mode` varchar(225) NOT NULL,
  `two_factor` enum('0','1') NOT NULL DEFAULT '0',
  `two_factor_secret_key` varchar(100) DEFAULT NULL,
  `ip_type` int(11) NOT NULL,
  `ip` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_type`, `admin_name`, `admin_email`, `username`, `password`, `telephone`, `register_date`, `login_date`, `login_ip`, `client_type`, `access`, `mode`, `two_factor`, `two_factor_secret_key`, `ip_type`, `ip`) VALUES
(1, '3', 'Admingyan', 'Admingyan@admin.com', 'admin', 'admin', '', '2021-09-08 10:19:05', '2025-11-30 21:27:36', '106.222.227.119', '2', '{\"admin_access\":1,\"users\":1,\"services\":1,\"update-prices\":1,\"bulk\":1,\"synced-logs\":1,\"orders\":1,\"subscriptions\":1,\"dripfeed\":1,\"tasks\":1,\"payments\":1,\"tickets\":1,\"additionals\":1,\"referral\":1,\"broadcast\":1,\"logs\":1,\"reports\":1,\"videop\":1,\"coupon\":1,\"child-panels\":1,\"updates\":1,\"appearance\":1,\"themes\":1,\"new_year\":1,\"pages\":1,\"news\":1,\"meta\":1,\"blog\":1,\"menu\":1,\"inte\":1,\"language\":1,\"files\":1,\"settings\":1,\"general_settings\":1,\"providers\":1,\"payments_settings\":1,\"bank_accounts\":1,\"modules\":1,\"subject\":1,\"payments_bonus\":1,\"currency-manager\":1,\"alert_settings\":1,\"site_count\":1,\"manager\":1,\"super_admin\":1}', 'sun', '0', '', 1, '10.60.615.2022');

-- --------------------------------------------------------

--
-- Table structure for table `admin_constants`
--

CREATE TABLE `admin_constants` (
  `id` int(11) NOT NULL,
  `brand_logo` varchar(255) DEFAULT NULL,
  `paidRent` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `admin_constants`
--

INSERT INTO `admin_constants` (`id`, `brand_logo`, `paidRent`) VALUES
(1, '{\"logo_url\":\"/img/panel/b1eec33c726a60554bc78518d5f9b32c.png\",\"width\":\"190\",\"height\":\"36\"}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `image_file` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int(11) NOT NULL,
  `bank_name` varchar(225) NOT NULL,
  `bank_sube` varchar(225) NOT NULL,
  `bank_hesap` varchar(225) NOT NULL,
  `bank_iban` text NOT NULL,
  `bank_alici` varchar(225) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `published_at` datetime NOT NULL,
  `image_file` varchar(200) DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `blog_get` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulkedit`
--

CREATE TABLE `bulkedit` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` text NOT NULL,
  `category_name_lang` longtext DEFAULT NULL CHECK (json_valid(`category_name_lang`)),
  `category_line` double NOT NULL,
  `category_type` enum('1','2') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '2',
  `category_secret` enum('1','2') NOT NULL DEFAULT '2',
  `category_icon` text NOT NULL,
  `is_refill` enum('1','2') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '1',
  `category_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_name_lang`, `category_line`, `category_type`, `category_secret`, `category_icon`, `is_refill`, `category_deleted`) VALUES
(1, 'IG Views - Cheapest |【 𝐋𝐚𝐭𝐞𝐬𝐭 𝐔𝐩𝐝𝐚𝐭𝐞𝐝 】', '{\"en\":\"IG Views - Cheapest |\\u3010 \\ud835\\udc0b\\ud835\\udc1a\\ud835\\udc2d\\ud835\\udc1e\\ud835\\udc2c\\ud835\\udc2d \\ud835\\udc14\\ud835\\udc29\\ud835\\udc1d\\ud835\\udc1a\\ud835\\udc2d\\ud835\\udc1e\\ud835\\udc1d \\u3011\"}', 1, '2', '2', '', '1', '0'),
(2, 'New Services - Own Provider', '{\"en\":\"New Services - Own Provider\"}', 2, '2', '2', '', '1', '0'),
(3, 'IG Followers |【 Cheapest Updated - 𝐁𝐞𝐬𝐭 𝐖𝐨𝐫𝐤𝐢𝐧𝐠 】ᴺᴱᵂ - 10/12/2024', '{\"en\":\"IG Followers |\\u3010 Cheapest Updated - \\ud835\\udc01\\ud835\\udc1e\\ud835\\udc2c\\ud835\\udc2d \\ud835\\udc16\\ud835\\udc28\\ud835\\udc2b\\ud835\\udc24\\ud835\\udc22\\ud835\\udc27\\ud835\\udc20 \\u3011\\u1d3a\\u1d31\\u1d42 - 10\\/12\\/2024\"}', 3, '2', '2', '', '1', '0'),
(4, 'IG Followers »【 Best Working | 𝗔𝗹𝘄𝗮𝘆𝘀 𝗣𝗲𝗿𝗳𝗼𝗿𝗺𝗶𝗻𝗴 】- 𝗟𝗮𝘁𝗲𝘀𝘁 𝗨𝗽𝗱𝗮𝘁𝗲𝗱 🔥', '{\"en\":\"IG Followers \\u00bb\\u3010 Best Working | \\ud835\\uddd4\\ud835\\uddf9\\ud835\\ude04\\ud835\\uddee\\ud835\\ude06\\ud835\\ude00 \\ud835\\udde3\\ud835\\uddf2\\ud835\\uddff\\ud835\\uddf3\\ud835\\uddfc\\ud835\\uddff\\ud835\\uddfa\\ud835\\uddf6\\ud835\\uddfb\\ud835\\uddf4 \\u3011- \\ud835\\udddf\\ud835\\uddee\\ud835\\ude01\\ud835\\uddf2\\ud835\\ude00\\ud835\\ude01 \\ud835\\udde8\\ud835\\uddfd\\ud835\\uddf1\\ud835\\uddee\\ud835\\ude01\\ud835\\uddf2\\ud835\\uddf1 \\ud83d\\udd25\"}', 4, '2', '2', '', '1', '0'),
(5, 'IG Followers |【 No Refill 】- 𝙐𝙥𝙙𝙖𝙩𝙚𝙙 𝙎𝙚𝙧𝙫𝙚𝙧', '{\"en\":\"IG Followers |\\u3010 No Refill \\u3011- \\ud835\\ude50\\ud835\\ude65\\ud835\\ude59\\ud835\\ude56\\ud835\\ude69\\ud835\\ude5a\\ud835\\ude59 \\ud835\\ude4e\\ud835\\ude5a\\ud835\\ude67\\ud835\\ude6b\\ud835\\ude5a\\ud835\\ude67\"}', 5, '2', '2', '', '1', '0'),
(6, 'YouTube Views |【 Indian -  States 】', '{\"en\":\"YouTube Views |\\u3010 Indian -  States \\u3011\"}', 6, '2', '2', '', '1', '0'),
(7, 'YouTube Revenue |【 Safe - Real 】', '{\"en\":\"YouTube Revenue |\\u3010 Safe - Real \\u3011\"}', 7, '2', '2', '', '1', '0'),
(8, 'YouTube Comments |【 No Drop 】ᴺᴱᵂ > 𝐔𝐩𝐝𝐚𝐭𝐞𝐝', '{\"en\":\"YouTube Comments |\\u3010 No Drop \\u3011\\u1d3a\\u1d31\\u1d42 > \\ud835\\udc14\\ud835\\udc29\\ud835\\udc1d\\ud835\\udc1a\\ud835\\udc2d\\ud835\\udc1e\\ud835\\udc1d\"}', 8, '2', '2', '', '1', '0'),
(9, 'Facebook Live - Stream |【 Updated 】', '{\"en\":\"Facebook Live - Stream |\\u3010 Updated \\u3011\"}', 9, '2', '2', '', '1', '0'),
(10, 'Facebook » Monetization Views |【 For Live Video\'s 】', '{\"en\":\"Facebook \\u00bb Monetization Views |\\u3010 For Live Video\'s \\u3011\"}', 10, '2', '2', '', '1', '0'),
(11, 'Twitter Followers |【 Unstable - Cheapest 】', '{\"en\":\"Twitter Followers |\\u3010 Unstable - Cheapest \\u3011\"}', 11, '2', '2', '', '1', '0'),
(12, '❌ 𝗧𝗜𝗞𝗧𝗢𝗞 𝗦𝗘𝗥𝗩𝗜𝗖𝗘𝗦 ❌', '{\"en\":\"\\u274c \\ud835\\udde7\\ud835\\udddc\\ud835\\uddde\\ud835\\udde7\\ud835\\udde2\\ud835\\uddde \\ud835\\udde6\\ud835\\uddd8\\ud835\\udde5\\ud835\\udde9\\ud835\\udddc\\ud835\\uddd6\\ud835\\uddd8\\ud835\\udde6 \\u274c\"}', 12, '2', '2', '', '1', '0'),
(13, 'TikTok Views |【 Live-Stream - 𝗩𝗜𝗣 - 𝗦𝘁𝗮𝗯𝗹𝗲 】', '{\"en\":\"TikTok Views |\\u3010 Live-Stream - \\ud835\\udde9\\ud835\\udddc\\ud835\\udde3 - \\ud835\\udde6\\ud835\\ude01\\ud835\\uddee\\ud835\\uddef\\ud835\\uddf9\\ud835\\uddf2 \\u3011\"}', 13, '2', '2', '', '1', '0'),
(14, 'Telegram Members |《 Refill 》', '{\"en\":\"Telegram Members |\\u300a Refill \\u300b\"}', 14, '2', '2', '', '1', '0'),
(15, 'Telegram Members |《 No - Drop 》', '{\"en\":\"Telegram Members |\\u300a No - Drop \\u300b\"}', 15, '2', '2', '', '1', '0'),
(16, 'Telegram Fastest |《 Services 》', '{\"en\":\"Telegram Fastest |\\u300a Services \\u300b\"}', 16, '2', '2', '', '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `childpanels`
--

CREATE TABLE `childpanels` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `domain` varchar(191) NOT NULL,
  `child_panel_currency` varchar(191) NOT NULL,
  `child_panel_username` varchar(191) NOT NULL,
  `child_panel_password` varchar(191) NOT NULL,
  `charged_amount` float NOT NULL,
  `child_panel_status` enum('Pending','Active','Frozen','Suspended') NOT NULL DEFAULT 'Pending',
  `renewal_date` date NOT NULL,
  `created_on` datetime NOT NULL,
  `child_panel_uqid` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `name` varchar(225) DEFAULT NULL,
  `email` varchar(225) NOT NULL,
  `username` varchar(225) DEFAULT NULL,
  `admin_type` enum('1','2') NOT NULL DEFAULT '2',
  `password` text NOT NULL,
  `telephone` varchar(225) DEFAULT NULL,
  `balance` decimal(21,4) NOT NULL DEFAULT 0.0000,
  `spent` decimal(21,4) NOT NULL DEFAULT 0.0000,
  `balance_type` enum('1','2') NOT NULL DEFAULT '2',
  `debit_limit` double DEFAULT NULL,
  `register_date` datetime NOT NULL,
  `login_date` datetime DEFAULT NULL,
  `login_ip` varchar(225) DEFAULT NULL,
  `apikey` text NOT NULL,
  `tel_type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '2 -> ON, 1 -> OFF',
  `email_type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '2 -> ON, 1 -> OFF',
  `client_type` enum('1','2') NOT NULL DEFAULT '2' COMMENT '2 -> ON, 1 -> OFF',
  `access` text DEFAULT NULL,
  `lang` varchar(255) NOT NULL DEFAULT 'tr',
  `timezone` double NOT NULL DEFAULT 0,
  `currency_type` varchar(10) DEFAULT NULL,
  `ref_code` text NOT NULL,
  `ref_by` text DEFAULT NULL,
  `change_email` enum('1','2') NOT NULL DEFAULT '2',
  `resend_max` int(11) NOT NULL DEFAULT 3,
  `currency` varchar(225) NOT NULL DEFAULT '1',
  `passwordreset_token` varchar(225) NOT NULL,
  `discount_percentage` int(11) NOT NULL,
  `broadcast_id` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `name`, `email`, `username`, `admin_type`, `password`, `telephone`, `balance`, `spent`, `balance_type`, `debit_limit`, `register_date`, `login_date`, `login_ip`, `apikey`, `tel_type`, `email_type`, `client_type`, `access`, `lang`, `timezone`, `currency_type`, `ref_code`, `ref_by`, `change_email`, `resend_max`, `currency`, `passwordreset_token`, `discount_percentage`, `broadcast_id`) VALUES
(7, '', 'admin@gmail.com', 'Admin11', '2', '4f8b8a69d97289826680fa7ee476e6f0', '', 0.0000, 0.0000, '2', NULL, '2025-01-08 14:22:07', '2025-01-08 16:44:10', '152.58.131.135', 'fd047186671597ed8bf003add6a759aa', '1', '2', '2', NULL, 'en', 0, 'INR', 'd494bc', NULL, '2', 3, '1', '', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `clients_category`
--

CREATE TABLE `clients_category` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients_price`
--

CREATE TABLE `clients_price` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_price` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients_service`
--

CREATE TABLE `clients_service` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_report`
--

CREATE TABLE `client_report` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `report_ip` varchar(225) NOT NULL,
  `report_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `client_report`
--

INSERT INTO `client_report` (`id`, `client_id`, `action`, `report_ip`, `report_date`) VALUES
(1, 1, '\r\n    User registered.', '2405:201:a408:f983:d1f:4e8:74c7:faa2', '2024-12-30 18:32:43'),
(2, 1, 'API Key changed', '2405:201:a408:f983:d1f:4e8:74c7:faa2', '2024-12-30 18:33:11'),
(3, 2, '\r\n    User registered.', '49.37.38.5', '2025-01-05 22:01:40'),
(4, 3, '\r\n    User registered.', '152.58.184.69', '2025-01-05 22:02:20'),
(5, 4, '\r\n    User registered.', '45.207.200.82', '2025-01-07 01:54:57'),
(6, 3, 'Member logged in.', '152.58.157.53', '2025-01-08 10:13:28'),
(7, 5, '\r\n    User registered.', '152.58.157.53', '2025-01-08 10:19:00'),
(8, 6, '\r\n    User registered.', '14.224.160.246', '2025-01-08 12:17:17'),
(9, 7, '\r\n    User registered.', '152.58.131.135', '2025-01-08 14:22:07'),
(10, 7, 'Member logged in.', '152.58.131.135', '2025-01-08 16:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(100) NOT NULL,
  `currency_name` varchar(50) NOT NULL,
  `currency_code` varchar(10) NOT NULL,
  `currency_symbol` varchar(10) DEFAULT NULL,
  `symbol_position` varchar(10) DEFAULT 'left',
  `currency_rate` double NOT NULL,
  `currency_inverse_rate` double NOT NULL,
  `is_enable` tinyint(1) NOT NULL DEFAULT 0,
  `currency_hash` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currency_name`, `currency_code`, `currency_symbol`, `symbol_position`, `currency_rate`, `currency_inverse_rate`, `is_enable`, `currency_hash`) VALUES
(1, 'Indian Rupee', 'INR', '₹', 'left', 1, 1, 1, 'a998d486cc7ea9917fd02f6caee5673933182f30'),
(2, 'U.S. Dollar', 'USD', '$', 'left', 0.01169749722451, 85.488372496014, 1, '48f0d8f1437778d67f345e30b5f398ffc771afe4'),
(3, 'Euro', 'EUR', '€', 'left', 0.011218304216123, 89.140032284276, 1, 'a76c6d2e7aca644af29faf0679c214532cf677ca'),
(4, 'Turkish Lira', 'TRY', '₺', 'left', 0.41327112268438, 2.4197190297366, 1, '2a9bae862a5967e5eb8da8ec020bb8190ae7be7d'),
(5, 'Russian Rouble', 'RUB', '₽', 'left', 1.2813734258508, 0.78041262587914, 1, '2d52239dada54aa63def537c6cec59473ffe8bb1'),
(6, 'Brazilian Real', 'BRL', 'R$', 'left', 0.072489194638069, 13.7951594716, 1, '10822ee29e673a4cc8277a3b35c0663d302baef3'),
(7, 'South Korean Won', 'KRW', '₩', 'left', 17.206234353528, 0.05811846912308, 1, '7643600700c3ed25d53084f278bdd974957cac3a'),
(8, 'Saudi Riyal', 'SAR', '﷼', 'left', 0.043911687512606, 22.772980421509, 1, '6be758284f2e67e43d04ea7c0c7c545ca2eba295'),
(9, 'Chinese Yuan', 'CNY', '¥', 'left', 0.085382393670038, 11.712016459324, 1, 'f00f040909620f8bb66b49d10adb616912433b65'),
(10, 'Vietnamese Dong', 'VND', '₫', 'left', 297.75106699017, 0.0033585102149542, 1, '472b25c7a2107c6d1e6249d18333a32f7a1e12f7'),
(11, 'Kuwaiti Dinar', 'KWD', 'د.ك', 'left', 0.0036050935384786, 277.38531312062, 1, 'de958b2a0c41151aad51e9c24dd41b875afbcc34'),
(12, 'Egyptian Pound', 'EGP', '£', 'left', 0.59487453601402, 1.6810267366637, 1, '469fd732faab87f94b562d8e6831fc79f38acff1'),
(13, 'Pakistani Rupee', 'PKR', '₨', 'left', 3.2581515519504, 0.30692249395255, 1, '7a8a8da37166774b69e0fca73a4d16e91c2dba53'),
(14, 'Nigerian Naira', 'NGN', '₦', 'left', 18.005138596833, 0.055539700215131, 1, '7ace161ee8dc795a400b1de5d760c382168796ae');

-- --------------------------------------------------------

--
-- Table structure for table `custom_settings`
--

CREATE TABLE `custom_settings` (
  `id` int(11) NOT NULL,
  `snow_data` text NOT NULL,
  `snow_data_array` text NOT NULL,
  `snow_status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - inactive , 2 - active',
  `start_count_parser` text NOT NULL,
  `orders_count_increase` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_settings`
--

INSERT INTO `custom_settings` (`id`, `snow_data`, `snow_data_array`, `snow_status`, `start_count_parser`, `orders_count_increase`) VALUES
(1, '\"snow\":{\"init\":false,\"options\":{\"particles\":{\"move\":{\"speed\":,\"bounce\":false,\"enable\":true,\"random\":false,\"attract\":{\"enable\":false,\"rotateX\":600,\"rotateY\":1200},\"out_mode\":\"out\",\"straight\":false,\"direction\":\"bottom\"},\"size\":{\"anim\":{\"sync\":false,\"speed\":40,\"enable\":false,\"size_min\":0.1},\"value\":10,\"random\":true},\"color\":{\"value\":\"#fff\"},\"number\":{\"value\":,\"density\":{\"enable\":true,\"value_area\":650}},\"opacity\":{\"anim\":{\"sync\":false,\"speed\":1,\"enable\":true,\"opacity_min\":0.9},\"value\":0.9,\"random\":true},\"line_linked\":{\"color\":\"#ffffff\",\"width\":1,\"enable\":false,\"opacity\":0.8,\"distance\":500}},\"interactivity\":{\"modes\":{\"bubble\":{\"size\":4,\"speed\":3,\"opacity\":1,\"distance\":400,\"duration\":0.3},\"repulse\":{\"speed\":3,\"distance\":200,\"duration\":0.4}},\"events\":{\"resize\":true,\"onclick\":{\"mode\":\"repulse\",\"enable\":true},\"onhover\":{\"mode\":\"bubble\",\"enable\":false}},\"detect_on\":\"window\"},\"retina_detect\":true}},\"toys\":{\"init\":false,\"options\":{\"count\":100,\"speed\":1,\"images\":[],\"maxSize\":30,\"launches\":\"1\"}},\"garland\":{\"init\":false,\"options\":{\"type\":\"\",\"style\":\"\"}},\"fireworks\":{\"init\":false,\"options\":{\"delay\":{\"max\":30,\"min\":30},\"friction\":,\"launches\":1,}}', '{\"snow_fall\":\"true\",\"snowflakes\":\"20\",\"snow_speed\":\"3\",\"garlands\":\"true\",\"gar_shape\":\"apple\",\"gar_style\":\"style1\",\"fire_works\":\"true\",\"fire_size\":\"0.95\",\"fire_speed\":\"slow\",\"toy_size\":\"80\",\"toy_quantity\":\"100\",\"toy_speed\":\"6\",\"toy_launch\":\"infinite\"}', '1', '{\"none\":\"Catch from supplier\",\"instagram_follower\":\"Instagram followers\",\"instagram_photo\":\"Instagram likes\",\"instagram_comments\":\"Instagram comments\",\"youtube_views\":\"Youtube views\",\"youtube_likes\":\"Youtube likes\",\"youtube_comments\":\"Youtube comments\",\"youtube_subscribers\":\"Youtube subscribers\"}', '0:0');

-- --------------------------------------------------------

--
-- Table structure for table `decoration`
--

CREATE TABLE `decoration` (
  `id` int(11) NOT NULL,
  `snow_effect` int(11) NOT NULL,
  `snow_colour` text NOT NULL,
  `diwali_lights` int(11) NOT NULL,
  `video_link` text NOT NULL,
  `christmas_deco` varchar(5000) NOT NULL,
  `action_link` text NOT NULL,
  `pop_noti` int(11) NOT NULL,
  `pop_title` text NOT NULL,
  `pop_desc` text NOT NULL,
  `action_text` varchar(10) NOT NULL,
  `action_button` int(11) NOT NULL,
  `snow_fall` varchar(500) DEFAULT NULL,
  `garlands` text DEFAULT NULL,
  `fire_works` text DEFAULT NULL,
  `toys` text DEFAULT NULL,
  `snowflakes` int(11) NOT NULL,
  `snow_speed` int(11) NOT NULL,
  `gar_shape` text NOT NULL,
  `gar_style` text NOT NULL,
  `fire_size` varchar(100) NOT NULL,
  `fire_speed` text NOT NULL,
  `toy_size` int(11) NOT NULL,
  `toy_quantity` int(11) NOT NULL,
  `toy_speed` int(11) NOT NULL,
  `toy_launch` varchar(100) NOT NULL,
  `toy_a` varchar(50) NOT NULL,
  `toy_b` varchar(50) NOT NULL,
  `toy_c` varchar(50) NOT NULL,
  `toy_d` varchar(50) NOT NULL,
  `toy_e` varchar(50) NOT NULL,
  `toy_f` varchar(50) NOT NULL,
  `toy_g` varchar(50) NOT NULL,
  `toy_h` varchar(50) NOT NULL,
  `toy_i` varchar(50) NOT NULL,
  `toy_j` varchar(50) NOT NULL,
  `toy_k` varchar(50) NOT NULL,
  `psw_license` text NOT NULL,
  `toy_l` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `decoration`
--

INSERT INTO `decoration` (`id`, `snow_effect`, `snow_colour`, `diwali_lights`, `video_link`, `christmas_deco`, `action_link`, `pop_noti`, `pop_title`, `pop_desc`, `action_text`, `action_button`, `snow_fall`, `garlands`, `fire_works`, `toys`, `snowflakes`, `snow_speed`, `gar_shape`, `gar_style`, `fire_size`, `fire_speed`, `toy_size`, `toy_quantity`, `toy_speed`, `toy_launch`, `toy_a`, `toy_b`, `toy_c`, `toy_d`, `toy_e`, `toy_f`, `toy_g`, `toy_h`, `toy_i`, `toy_j`, `toy_k`, `psw_license`, `toy_l`) VALUES
(1, 0, '#004a00', 0, '', '\n<style>.particle-snow{position:fixed;top:0;left:0;width:100%;height:100%;z-index:1;pointer-events:none}.particle-snow canvas{position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none}.christmas-garland{text-align:center;white-space:nowrap;overflow:hidden;position:absolute;z-index:1;padding:0;pointer-events:none;width:100%;height:85px}.christmas-garland .christmas-garland__item{position:relative;width:28px;height:28px;border-radius:50%;display:inline-block;margin-left:20px}.christmas-garland .christmas-garland__item .shape{-webkit-animation-fill-mode:both;animation-fill-mode:both;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;-webkit-animation-name:flash-1;animation-name:flash-1;-webkit-animation-duration:2s;animation-duration:2s}.christmas-garland .christmas-garland__item .apple{width:22px;height:22px;border-radius:50%;margin-left:auto;margin-right:auto;margin-top:8px}.christmas-garland .christmas-garland__item .pear{width:12px;height:28px;border-radius:50%;margin-left:auto;margin-right:auto;margin-top:6px}.christmas-garland .christmas-garland__item:nth-child(2n+1) .shape{-webkit-animation-name:flash-2;animation-name:flash-2;-webkit-animation-duration:.4s;animation-duration:.4s}.christmas-garland .christmas-garland__item:nth-child(4n+2) .shape{-webkit-animation-name:flash-3;animation-name:flash-3;-webkit-animation-duration:1.1s;animation-duration:1.1s}.christmas-garland .christmas-garland__item:nth-child(odd) .shape{-webkit-animation-duration:1.8s;animation-duration:1.8s}.christmas-garland .christmas-garland__item:nth-child(3n+1) .shape{-webkit-animation-duration:1.4s;animation-duration:1.4s}.christmas-garland .christmas-garland__item:before{content:\"\";position:absolute;background:#222;width:10px;height:10px;border-radius:3px;top:-1px;left:9px}.christmas-garland .christmas-garland__item:after{content:\"\";top:-9px;left:14px;position:absolute;width:52px;height:18px;border-bottom:solid #222 2px;border-radius:50%}.christmas-garland .christmas-garland__item:last-child:after{content:none}.christmas-garland .christmas-garland__item:first-child{margin-left:-40px}</style>\n<!-- developed by Raj Patel-->\n      \n<!-- developed by Raj Patel-->  \n    <script type=\"text/javascript\" src=\"https://cdn.mypanel.link/libs/jquery/1.12.4/jquery.min.js\">\n          </script>\n    \n<!-- developed by Raj Patel-->\n        \n    <script type=\"text/javascript\" src=\"https://cdn.mypanel.link/global/flpbonhmkq9tsp29.js\">\n          </script>\n    \n        \n<!-- developed by Raj Patel-->\n    <script type=\"text/javascript\" src=\"https://cdn.mypanel.link/global/a4kdpfesx15uh7ae.js\">\n          </script>\n    \n<!-- developed by Raj Patel-->\n        \n    <script type=\"text/javascript\" src=\"https://cdn.mypanel.link/global/596z6ya3isgxcipy.js\">\n          </script>\n    \n        \n    <script type=\"text/javascript\" src=\"https://cdn.mypanel.link/global/39j8e9yrxs283d1x.js\">\n          </script>\n    \n        \n    <script type=\"text/javascript\" src=\"https://cdn.mypanel.link/global/33srijdbqcgk6lsz.js\">\n          </script>\n    \n<!-- developed by Raj Patel-->\n<!-- developed by Raj Patel-->\n        \n    <script type=\"text/javascript\" src=\"https://cdn.mypanel.link/52pp7z/wxbh27w4jdzpslxn.js\">\n          </script>\n    \n<!-- developed by Raj Patel-->\n<!-- developed by Raj Patel-->\n        \n    <script type=\"text/javascript\" src=\"https://cdn.mypanel.link/52pp7z/angedasgma230hxr.js\">\n          </script>\n    \n        \n<!-- developed by Raj Patel-->\n<!-- developed by Raj Patel-->\n    <script type=\"text/javascript\" >\n       window.modules.layouts = {\"theme_id\":1,\"auth\":0,\"live\":true};     </script>\n    \n        \n    <script type=\"text/javascript\" >\n       window.modules.signin = [];     </script>\n    \n<!-- developed by Raj Patel-->\n<!-- developed by Raj Patel-->\n<!-- developed by Raj Patel-->\n        \n    <script type=\"text/javascript\" >\n       document.addEventListener(\'DOMContentLoaded\', function() { \nvar newYearEvent = new window.NewYearEvent({\"snow\":{\"init\":true,\"options\":{\"particles\":{\"move\":{\"speed\":3,\"bounce\":false,\"enable\":true,\"random\":false,\"attract\":{\"enable\":false,\"rotateX\":600,\"rotateY\":1200},\"out_mode\":\"out\",\"straight\":false,\"direction\":\"bottom\"},\"size\":{\"anim\":{\"sync\":false,\"speed\":40,\"enable\":false,\"size_min\":0.1},\"value\":5,\"random\":true},\"color\":{\"value\":\"#fff\"},\"number\":{\"value\":100,\"density\":{\"enable\":true,\"value_area\":650}},\"opacity\":{\"anim\":{\"sync\":false,\"speed\":1,\"enable\":true,\"opacity_min\":0.9},\"value\":0.9,\"random\":true},\"line_linked\":{\"color\":\"#ffffff\",\"width\":1,\"enable\":false,\"opacity\":0.8,\"distance\":500}},\"interactivity\":{\"modes\":{\"bubble\":{\"size\":4,\"speed\":3,\"opacity\":1,\"distance\":400,\"duration\":0.3},\"repulse\":{\"speed\":3,\"distance\":200,\"duration\":0.4}},\"events\":{\"resize\":true,\"onclick\":{\"mode\":\"repulse\",\"enable\":true},\"onhover\":{\"mode\":\"bubble\",\"enable\":false}},\"detect_on\":\"window\"},\"retina_detect\":true}},\"toys\"', '', 0, '', '', '', 0, NULL, NULL, NULL, NULL, 50, 10, 'apple', 'style2', '0.95', 'slow', 80, 100, 6, 'infinite', '', '', '', '', '', '1', '', '', '', '1', '1', 'GARUDSMM.IN', '1');

-- --------------------------------------------------------

--
-- Table structure for table `earn`
--

CREATE TABLE `earn` (
  `earn_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `link` text NOT NULL,
  `earn_note` text NOT NULL,
  `status` enum('Pending','Under Review','Funds Granted','Rejected','Not Eligible') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `General_options`
--

CREATE TABLE `General_options` (
  `id` int(11) NOT NULL,
  `coupon_status` enum('1','2') NOT NULL DEFAULT '1',
  `updates_show` enum('1','2') NOT NULL DEFAULT '1',
  `panel_status` enum('Pending','Active','Frozen','Suspended') NOT NULL,
  `panel_orders` int(11) NOT NULL,
  `panel_thismonthorders` int(11) NOT NULL,
  `massorder` enum('1','2') NOT NULL DEFAULT '2',
  `balance_format` enum('0.0','0.00','0.000','0.0000') NOT NULL DEFAULT '0.0',
  `currency_format` enum('0','2','3','4') NOT NULL DEFAULT '3',
  `ticket_system` enum('1','2') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `General_options`
--

INSERT INTO `General_options` (`id`, `coupon_status`, `updates_show`, `panel_status`, `panel_orders`, `panel_thismonthorders`, `massorder`, `balance_format`, `currency_format`, `ticket_system`) VALUES
(1, '', '', 'Active', 1024, 20, '', '', '', '2');

-- --------------------------------------------------------

--
-- Table structure for table `integrations`
--

CREATE TABLE `integrations` (
  `id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `description` varchar(225) NOT NULL,
  `icon_url` varchar(225) NOT NULL,
  `code` text NOT NULL,
  `visibility` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `integrations`
--

INSERT INTO `integrations` (`id`, `name`, `description`, `icon_url`, `code`, `visibility`, `status`) VALUES
(1, 'Beamer', 'Announce updates and get feedback with in-app notification center, widgets and changelog', '/img/integrations/Beamer.svg', '', 1, 1),
(2, 'Getsitecontrol', 'It helps you prevent website visitors from leaving your website without taking any action.', '/img/integrations/Getsitecontrol.svg', '<html>\r\n<body style=\'background-color:black\'>\r\n<canvas id=\'myCanvas\' width=\'800\' height=\'800\'></canvas>\r\n<script>\r\nconst max_fireworks = 5,\r\n  max_sparks = 50;\r\nlet canvas = document.getElementById(\'myCanvas\');\r\nlet context = canvas.getContext(\'2d\');\r\nlet fireworks = [];\r\n \r\nfor (let i = 0; i < max_fireworks; i++) {\r\n  let firework = {\r\n    sparks: []\r\n  };\r\n  for (let n = 0; n < max_sparks; n++) {\r\n    let spark = {\r\n      vx: Math.random() * 5 + .5,\r\n      vy: Math.random() * 5 + .5,\r\n      weight: Math.random() * .3 + .03,\r\n      red: Math.floor(Math.random() * 2),\r\n      green: Math.floor(Math.random() * 2),\r\n      blue: Math.floor(Math.random() * 2)\r\n    };\r\n    if (Math.random() > .5) spark.vx = -spark.vx;\r\n    if (Math.random() > .5) spark.vy = -spark.vy;\r\n    firework.sparks.push(spark);\r\n  }\r\n  fireworks.push(firework);\r\n  resetFirework(firework);\r\n}\r\nwindow.requestAnimationFrame(explode);\r\n \r\nfunction resetFirework(firework) {\r\n  firework.x = Math.floor(Math.random() * canvas.width);\r\n  firework.y = canvas.height;\r\n  firework.age = 0;\r\n  firework.phase = \'fly\';\r\n}\r\n \r\nfunction explode() {\r\n  context.clearRect(0, 0, canvas.width, canvas.height);\r\n  fireworks.forEach((firework,index) => {\r\n    if (firework.phase == \'explode\') {\r\n        firework.sparks.forEach((spark) => {\r\n        for (let i = 0; i < 10; i++) {\r\n          let trailAge = firework.age + i;\r\n          let x = firework.x + spark.vx * trailAge;\r\n          let y = firework.y + spark.vy * trailAge + spark.weight * trailAge * spark.weight * trailAge;\r\n          let fade = i * 20 - firework.age * 2;\r\n          let r = Math.floor(spark.red * fade);\r\n          let g = Math.floor(spark.green * fade);\r\n          let b = Math.floor(spark.blue * fade);\r\n          context.beginPath();\r\n          context.fillStyle = \'rgba(\' + r + \',\' + g + \',\' + b + \',1)\';\r\n          context.rect(x, y, 4, 4);\r\n          context.fill();\r\n        }\r\n      });\r\n      firework.age++;\r\n      if (firework.age > 100 && Math.random() < .05) {\r\n        resetFirework(firework);\r\n      }\r\n    } else {\r\n      firework.y = firework.y - 10;\r\n      for (let spark = 0; spark < 15; spark++) {\r\n        context.beginPath();\r\n        context.fillStyle = \'rgba(\' + index * 50 + \',\' + spark * 17 + \',0,1)\';\r\n        context.rect(firework.x + Math.random() * spark - spark / 2, firework.y + spark * 4, 4, 4);\r\n        context.fill();\r\n      }\r\n      if (Math.random() < .001 || firework.y < 200) firework.phase = \'explode\';\r\n    }\r\n  });\r\n  window.requestAnimationFrame(explode);\r\n}\r\n</script>\r\n</body>\r\n</html>', 1, 1),
(3, 'Google Analytics', 'Statistics and basic analytical tools for search engine optimization (SEO) and marketing purposes', '/img/integrations/Google%20Analytics.svg', '', 1, 1),
(4, 'Google Tag manager', 'Manage all your website tags without editing the code using simple tag management solutions', '/img/integrations/Google%20Tag%20manager.svg', '', 1, 1),
(5, 'JivoChat', 'All-in-one business messenger to talk to customers: live chat, phone, email and social', '/img/integrations/JivoChat.svg', '', 1, 1),
(6, 'Onesignal', 'Leader in customer engagement, empowers mobile push, web push, email, in-app messages', '/img/integrations/Onesignal.svg', '', 1, 1),
(7, 'Push alert', 'Increase reach, revenue, retarget users with Push Notifications on desktop and mobile', '/img/integrations/Push%20alert.svg', '', 1, 1),
(8, 'Smartsupp', 'Live chat, email inbox and Facebook Messenger in one customer messaging platform', '/img/integrations/Smartsupp.svg', '', 1, 1),
(9, 'Tawk.to', 'Track and chat with visitors on your website, mobile app or a free customizable page', '/img/integrations/Tawk.to.svg', '', 1, 1),
(10, 'Tidio', 'Communicator for businesses that keep live chat, chatbots, Messenger and email in one place', '/img/integrations/Tidio.svg', '', 1, 1),
(11, 'Zendesk Chat', 'Helps respond quickly to customer questions, reduce wait times and increase sales', '/img/integrations/Zendesk%20Chat.svg', '', 1, 1),
(12, 'Getbutton.io', 'Chat with website visitors through popular messaging apps. Whatsapp, messenger etc. contact button.', '/img/integrations/Getbutton.svg', '', 1, 1),
(13, 'Google reCAPTCHA v2', 'It uses an advanced risk analysis engine and adaptive challenges to prevent malware from engaging in abusive activities on your website.', '/img/integrations/reCAPTCHA.svg', '', 1, 1),
(14, 'Whatsapp', 'Whatsapp is for Personal Support of your Users', '/img/integrations/whatsapp.svg', '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `kuponlar`
--

CREATE TABLE `kuponlar` (
  `id` int(11) NOT NULL,
  `kuponadi` varchar(255) NOT NULL,
  `adet` int(11) NOT NULL,
  `tutar` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kuponlar`
--

INSERT INTO `kuponlar` (`id`, `kuponadi`, `adet`, `tutar`) VALUES
(2, 'GARUDSMM', 1, 500);

-- --------------------------------------------------------

--
-- Table structure for table `kupon_kullananlar`
--

CREATE TABLE `kupon_kullananlar` (
  `id` int(11) NOT NULL,
  `uye_id` int(11) NOT NULL,
  `kuponadi` varchar(255) NOT NULL,
  `tutar` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `language_name` varchar(225) NOT NULL,
  `language_code` varchar(225) NOT NULL,
  `language_type` enum('2','1') NOT NULL DEFAULT '2',
  `default_language` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `language_name`, `language_code`, `language_type`, `default_language`) VALUES
(1, 'English', 'en', '2', '1'),
(2, 'Arabic', 'ar', '2', '0');

-- --------------------------------------------------------

--
-- Table structure for table `Mailforms`
--

CREATE TABLE `Mailforms` (
  `id` int(11) NOT NULL,
  `subject` varchar(225) NOT NULL,
  `message` varchar(225) NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `header` varchar(225) NOT NULL,
  `footer` varchar(225) NOT NULL,
  `type` enum('Admins','Users') NOT NULL DEFAULT 'Users'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `menu_name_lang` longtext DEFAULT NULL CHECK (json_valid(`menu_name_lang`)),
  `menu_line` double NOT NULL,
  `type` enum('1','2') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '2',
  `slug` varchar(225) NOT NULL DEFAULT '2',
  `icon` varchar(225) DEFAULT NULL,
  `menu_status` enum('1','2') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '1',
  `visible` enum('Internal','External') NOT NULL DEFAULT 'Internal',
  `active` varchar(225) NOT NULL,
  `tiptext` varchar(225) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `menu_name_lang`, `menu_line`, `type`, `slug`, `icon`, `menu_status`, `visible`, `active`, `tiptext`) VALUES
(1, 'New Order', '{\"en\": \"New Order\"}', 1, '2', '/', 'fa fa-shopping-bag', '1', 'Internal', 'neworder', ''),
(2, 'Mass Order', '{\"en\": \"Mass Order\"}', 2, '', '/massorder', 'fas fa-cart-plus', '1', 'Internal', 'massorder', 'Shown only if Mass Order system enabled for use'),
(3, 'Orders ', '{\"en\": \"Orders \"}', 3, '2', '/orders', 'fas fa-server', '1', 'Internal', 'orders', ''),
(6, 'Services', '{\"en\": \"Services\"}', 5, '2', '/services', 'fas fa-file-alt', '1', 'Internal', 'services', ''),
(7, 'Add Funds', '{\"en\": \"Add Funds\"}', 6, '2', '/addfunds', 'fab fa-cc-amazon-pay', '1', 'Internal', 'addfunds', ''),
(8, 'Api', '{\"en\": \"Api\"}', 9, '2', '/api', 'fal fa-plug', '1', 'Internal', 'api', ''),
(9, 'Tickets ', '{\"en\": \"Tickets \"}', 8, '2', '/tickets', 'fas fa-headset', '1', 'Internal', 'tickets', ''),
(10, 'Child Panels', '{\"en\": \"Child Panels\"}', 10, '', '/child-panels', 'fas fa-child', '1', 'Internal', 'child-panels', 'Shown only if child panels selling enabled'),
(11, 'Refer & Earn', '{\"en\": \"Refer & Earn\"}', 11, '', '/refer', 'fas fa-bezier-curve', '1', 'Internal', 'refer', 'Shown only if affiliate system enabled for use'),
(13, 'Terms', '{\"en\": \"Terms\"}', 12, '2', '/terms', 'fas fa-exclamation-triangle', '1', 'Internal', 'terms', ''),
(14, 'Signup ', '{\"en\": \"Signup\"}', 2, '2', '/signup', 'fas fa-arrow-right', '1', 'External', 'signup', 'Shown only if Signup system enabled for use'),
(15, 'Api', '{\"en\": \"Api\"}', 4, '2', '/api', 'fal fa-plug', '1', 'External', 'api', ''),
(17, 'Daily Updates', '{\"en\": \"Daily Updates\"}', 13, '', '/updates', 'fas fa-bell', '1', 'Internal', '', 'Shown only if Updates System enabled'),
(18, 'Terms', '{\"en\": \"Terms\"}', 3, '2', '/terms', 'fas fa-exclamation-triangle', '1', 'External', 'terms', ''),
(32, 'blogs', '{\"en\": \"blogs\"}', 16, '2', '/blog', 'fab fa-500px', '1', 'Internal', 'blog', ''),
(24, 'Services', '{\"en\": \"Services\"}', 14, '2', '/services', 'fas fa-file-alt', '1', 'External', 'services', ''),
(28, 'Transfer Funds ', '{\"en\": \"Transfer Funds \"}', 14, '2', '/transferfunds', 'fas fa-grip-vertical', '1', 'Internal', 'Transfer Funds ', ''),
(31, 'blogs', '{\"en\": \"blogs\"}', 15, '2', '/blog', '', '1', 'External', 'blog', '');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `news_icon` varchar(225) NOT NULL,
  `news_title` varchar(225) NOT NULL,
  `news_title_lang` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`news_title_lang`)),
  `news_content` varchar(225) NOT NULL,
  `news_content_lang` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`news_content_lang`)),
  `news_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications_popup`
--

CREATE TABLE `notifications_popup` (
  `id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `type` text DEFAULT NULL,
  `action_link` text DEFAULT NULL,
  `isAllUser` enum('1','0') NOT NULL DEFAULT '0',
  `expiry_date` date NOT NULL,
  `status` enum('1','2','0') NOT NULL DEFAULT '1',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `action_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `api_orderid` int(11) NOT NULL DEFAULT 0,
  `order_error` text NOT NULL,
  `order_detail` text DEFAULT NULL,
  `order_api` int(11) NOT NULL DEFAULT 0,
  `api_serviceid` int(11) NOT NULL DEFAULT 0,
  `api_charge` double NOT NULL DEFAULT 0,
  `api_currencycharge` double DEFAULT 1,
  `order_profit` double NOT NULL,
  `order_quantity` double NOT NULL,
  `order_extras` text NOT NULL,
  `order_charge` double NOT NULL,
  `dripfeed` enum('1','2','3') DEFAULT '1' COMMENT '2 -> ON, 1 -> OFF',
  `dripfeed_id` double NOT NULL DEFAULT 0,
  `subscriptions_id` double NOT NULL DEFAULT 0,
  `subscriptions_type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '2 -> ON, 1 -> OFF',
  `dripfeed_totalcharges` double DEFAULT NULL,
  `dripfeed_runs` double DEFAULT NULL,
  `dripfeed_delivery` double NOT NULL DEFAULT 0,
  `dripfeed_interval` double DEFAULT NULL,
  `dripfeed_totalquantity` double DEFAULT NULL,
  `dripfeed_status` enum('active','completed','canceled') NOT NULL DEFAULT 'active',
  `order_url` text NOT NULL,
  `order_start` double NOT NULL DEFAULT 0,
  `order_finish` double NOT NULL DEFAULT 0,
  `order_remains` double NOT NULL DEFAULT 0,
  `order_create` datetime NOT NULL,
  `order_status` enum('pending','inprogress','completed','partial','processing','canceled') NOT NULL DEFAULT 'pending',
  `subscriptions_status` enum('active','paused','completed','canceled','expired','limit') NOT NULL DEFAULT 'active',
  `subscriptions_username` text DEFAULT NULL,
  `subscriptions_posts` double DEFAULT NULL,
  `subscriptions_delivery` double NOT NULL DEFAULT 0,
  `subscriptions_delay` double DEFAULT NULL,
  `subscriptions_min` double DEFAULT NULL,
  `subscriptions_max` double DEFAULT NULL,
  `subscriptions_expiry` date DEFAULT NULL,
  `last_check` datetime NOT NULL,
  `order_where` enum('site','api') NOT NULL DEFAULT 'site',
  `refill_status` enum('Pending','Refilling','Completed','Rejected','Error') NOT NULL DEFAULT 'Pending',
  `is_refill` enum('1','2') NOT NULL DEFAULT '1',
  `refill` varchar(225) NOT NULL DEFAULT '1',
  `cancelbutton` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 -> ON, 2 -> OFF',
  `show_refill` enum('true','false') NOT NULL DEFAULT 'true',
  `api_refillid` double NOT NULL DEFAULT 0,
  `avg_done` enum('0','1') NOT NULL DEFAULT '1',
  `order_increase` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL,
  `page_name` varchar(225) NOT NULL,
  `page_get` varchar(225) NOT NULL,
  `page_content` text NOT NULL,
  `page_status` enum('1','2') NOT NULL DEFAULT '1',
  `active` enum('1','2') NOT NULL DEFAULT '1',
  `seo_title` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `seo_keywords` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `seo_description` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `last_modified` datetime NOT NULL,
  `del` varchar(255) NOT NULL DEFAULT '1',
  `page_content2` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`page_id`, `page_name`, `page_get`, `page_content`, `page_status`, `active`, `seo_title`, `seo_keywords`, `seo_description`, `last_modified`, `del`, `page_content2`) VALUES
(2, 'Add funds', 'addfunds', '', '1', '1', '', '', '', '2023-07-27 09:55:56', '2', ''),
(787, 'Login', 'auth', '', '1', '1', '', '', '', '2023-08-12 09:18:17', '2', ''),
(9, 'New Order', 'neworder', '', '1', '1', '', '', '', '2023-08-02 11:58:29', '2', ''),
(14, 'Terms', 'terms', '', '1', '1', '', '', '', '2022-02-07 08:41:16', '2', ''),
(789, 'Mass Order', 'massorder', '', '1', '1', '', '', '', '2022-02-07 08:43:06', '2', ''),
(790, 'Orders', 'orders', '', '1', '1', '', '', '', '2022-02-07 08:53:20', '2', ''),
(791, 'Services', 'services', '', '1', '1', '', '', '', '2022-01-26 07:22:09', '2', ''),
(792, 'Tickets', 'tickets', '', '1', '1', '', '', '', '2022-01-26 07:22:09', '2', ''),
(793, 'API', 'api', '', '1', '1', '', '', '', '2022-01-24 07:21:07', '2', ''),
(794, 'Signup', 'signup', '', '1', '1', '', '', '', '2022-01-24 07:21:07', '2', ''),
(795, 'Blog', 'blog', '', '1', '1', '', '', '', '2022-01-24 07:21:07', '2', ''),
(909, 'success', 'success', '', '1', '1', '', '', '', '0000-00-00 00:00:00', '1', '');

-- --------------------------------------------------------

--
-- Table structure for table `panel_categories`
--

CREATE TABLE `panel_categories` (
  `id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1 -> ENABLE, 0 -> DISABLE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `panel_info`
--

CREATE TABLE `panel_info` (
  `panel_id` int(11) NOT NULL,
  `panel_domain` text NOT NULL,
  `panel_plan` text NOT NULL,
  `panel_status` enum('Pending','Active','Frozen','Suspended') NOT NULL,
  `panel_orders` int(11) NOT NULL,
  `panel_thismonthorders` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `api_key` varchar(225) NOT NULL,
  `renewal_date` datetime NOT NULL,
  `panel_type` enum('Child','Main') NOT NULL DEFAULT 'Main'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `panel_info`
--

INSERT INTO `panel_info` (`panel_id`, `panel_domain`, `panel_plan`, `panel_status`, `panel_orders`, `panel_thismonthorders`, `date_created`, `api_key`, `renewal_date`, `panel_type`) VALUES
(1, 'garudsmm.in', 'A', 'Active', 0, 0, '2022-01-24 10:58:08', 'b1fbedd6f1266a8990bf648919068680', '2025-02-23 10:58:08', 'Main');

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethods`
--

CREATE TABLE `paymentmethods` (
  `methodId` int(11) NOT NULL,
  `methodName` varchar(300) DEFAULT NULL,
  `methodLogo` varchar(200) DEFAULT NULL,
  `methodVisibleName` varchar(300) DEFAULT NULL,
  `methodCallback` varchar(100) DEFAULT NULL,
  `methodMin` int(11) NOT NULL DEFAULT 1,
  `methodMax` int(11) NOT NULL DEFAULT 1,
  `methodFee` float NOT NULL DEFAULT 0,
  `methodBonusPercentage` float NOT NULL DEFAULT 0,
  `methodBonusStartAmount` int(11) NOT NULL DEFAULT 0,
  `methodCurrency` varchar(3) DEFAULT NULL,
  `methodStatus` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 -> off, 1 -> on',
  `methodExtras` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `methodPosition` int(11) DEFAULT NULL,
  `methodInstructions` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paymentmethods`
--

INSERT INTO `paymentmethods` (`methodId`, `methodName`, `methodLogo`, `methodVisibleName`, `methodCallback`, `methodMin`, `methodMax`, `methodFee`, `methodBonusPercentage`, `methodBonusStartAmount`, `methodCurrency`, `methodStatus`, `methodExtras`, `methodPosition`, `methodInstructions`) VALUES
(1, 'PayTM Checkout', 'https://i.postimg.cc/WzMm5g97/download.jpg', 'PayTM Checkout', 'payTMCheckout', 1, 100000, 0, 0, 0, 'INR', '0', '{\"merchantId\":\"\",\"merchantKey\":\"\"}', 3, ''),
(2, 'PayTM Merchant', 'https://i.postimg.cc/TPLxrggH/paytm-napdxw.png', 'PayTM / Phone PayQr', 'payTMMerchant', 1, 10000, 0, 0, 0, 'INR', '1', '{\"merchantId\":\"\"}', 2, ''),
(3, 'Perfect Money', 'https://i.postimg.cc/dVnkQ4kC/perfectmoney-wgszxi.png', 'Perfect Money', 'perfectMoney', 10, 1000, 3, 0, 0, 'USD', '0', '{\"accountNumber\":\"\",\"alternatePassPhrase\":\"\"}', 2, ''),
(4, 'Coinbase Commerce', 'https://i.postimg.cc/pXbns9Mm/coinbase-bcpppn.png', 'Coinbase Commerce', 'coinbaseCommerce', 1, 1000, 0, 0, 0, 'USD', '0', '{\"APIKey\":\"\"}', 4, NULL),
(5, 'Kashier', 'https://i.postimg.cc/6pS05WGn/download-4.png', 'Kashier', 'kashier', 1, 1000, 0, 0, 0, 'USD', '0', '{\"MID\":\"\",\"APIKey\":\"\",\"mode\":\"live\"}', 5, NULL),
(6, 'Razorpay', 'https://i.postimg.cc/W4T4Zrrv/download-5.png', 'Razorpay', 'razorPay', 1, 10000, 0, 0, 0, 'INR', '0', '{\"APIPublicKey\":\"\",\"APISecretKey\":\"\",\"gatewayThemeColour\":\"\"}', 6, NULL),
(7, 'PhonePe (Automatic)', 'https://i.postimg.cc/mD97r7GG/download-1.png', 'PhonePe (Automatic)', 'phonepe', 1, 10000, 0, 0, 0, 'INR', '1', '{\"email\":\"sarjeelgamerzone08@gmail.com\",\"password\":\"aobs rgkj vwip iehf\"}', 7, ''),
(8, 'Easypaisa (Automatic)', 'https://i.postimg.cc/SNGQhR31/download-6.png', 'Easypaisa (Automatic)', 'easypaisa', 1, 50000, 0, 0, 0, 'PKR', '0', '{\"email\":\"easypaisaautosmm@gmail.com\",\"password\":\"ismh zmev rllk tgdo\",\"senderEmail\":\"\",\"emailSubject\":\"easypaisa\"}', 8, ''),
(9, 'Jazzcash (Automatic)', 'https://i.postimg.cc/ZqQKMQ2T/download-7.png', 'Jazzcash (Automatic)', 'jazzcash', 1, 50000, 0, 0, 0, 'PKR', '0', '{\"email\":\"\",\"password\":\"\",\"senderEmail\":\"\",\"emailSubject\":\"jazzcash\"}', 9, NULL),
(10, 'Instamojo', 'https://i.postimg.cc/7hmRKFt9/images.png', 'Instamojo', 'instamojo', 1, 1000, 0, 0, 0, 'INR', '0', '{\"APIKey\":\"\",\"authToken\":\"\"}', 10, NULL),
(11, 'Cashmaal', 'https://i.postimg.cc/26q7Z3PC/download-1.jpg', 'Cashmaal', 'cashmaal', 1, 50000, 0, 0, 0, 'PKR', '0', '{\"webId\":\"\"}', 11, NULL),
(12, 'Alipay', 'https://i.postimg.cc/xd8KwcjL/download-8.png', 'Alipay', 'alipay', 1, 10000, 0, 0, 0, 'USD', '0', '{\"partnerId\":\"\",\"privateKey\":\"\"}', 12, NULL),
(13, 'PayU', 'https://i.postimg.cc/bJ21vWtJ/download-9.png', 'PayU', 'payU', 1, 10000, 0, 0, 0, 'INR', '0', '{\"merchantKey\":\"\",\"merchantSalt\":\"\"}', 13, NULL),
(14, 'Upiapi', 'https://i.postimg.cc/xCWhkwpF/download-11.png', 'UPI Api', 'upiapi', 1, 10000, 0, 0, 0, 'INR', '0', '{\"productionAPIToken\":\"\",\"productionAPISecretKey\":\"\"}', 3, ''),
(15, 'Opay Express Checkout', 'https://i.postimg.cc/NjfTX5HZ/download-3.png', 'Opay Express Checkout', 'opay', 1, 10000, 0, 0, 0, 'USD', '0', '{\"merchantId\":\"\",\"publicKey\":\"\",\"secretKey\":\"\"}', 3, ''),
(16, 'Flutterwave', 'https://i.postimg.cc/k5LtLspd/download-10.png', 'Flutterwave', 'flutterwave', 1, 1000, 0, 0, 0, 'USD', '0', '{\"secretKey\":\"\"}', 3, ''),
(17, 'Stripe', 'https://i.postimg.cc/j25KX4qp/download-2.png', 'Stripe', 'stripe', 1, 1000, 0, 0, 0, 'USD', '0', '{\"publishableKey\":\"\",\"secretKey\":\"\"}', 3, ''),
(18, 'Payeer', 'https://i.postimg.cc/q7LtRQNn/payeer-ygcgcj.png', 'Payeer', 'payeer', 1, 1000, 0, 0, 0, 'USD', '0', '{\"shopId\":\"\",\"secretKey\":\"\"}', 3, '');
INSERT INTO `paymentmethods` (`methodId`, `methodName`, `methodLogo`, `methodVisibleName`, `methodCallback`, `methodMin`, `methodMax`, `methodFee`, `methodBonusPercentage`, `methodBonusStartAmount`, `methodCurrency`, `methodStatus`, `methodExtras`, `methodPosition`, `methodInstructions`) VALUES
(22, 'bharatpe', 'https://bp-wp-website-prod.s3.ap-south-1.amazonaws.com/wp-content/uploads/2023/09/bharatpe_logo.png', 'BharatPe', 'bharatpe', 1, 1000, 0, 0, 0, 'INR', '1', '{\"merchantId\":\"46881225\",\"token\":\"0c88295ec070441085b45bac866e8a16\"}', 1, '&lt;p&gt;&lt;img src=&quot;data:image/jpeg;base64,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&quot;&gt;&lt;/p&gt;');
INSERT INTO `paymentmethods` (`methodId`, `methodName`, `methodLogo`, `methodVisibleName`, `methodCallback`, `methodMin`, `methodMax`, `methodFee`, `methodBonusPercentage`, `methodBonusStartAmount`, `methodCurrency`, `methodStatus`, `methodExtras`, `methodPosition`, `methodInstructions`) VALUES
(100, 'Manual One', 'https://i.postimg.cc/15LhnN23/images-1.png', 'Manual One', NULL, 1, 1, 0, 0, 0, NULL, '0', NULL, 3, NULL),
(101, 'Manual Two', 'https://i.postimg.cc/15LhnN23/images-1.png', 'Manual Two', NULL, 1, 1, 0, 0, 0, NULL, '0', NULL, 3, NULL),
(102, 'Manual Three', 'https://i.postimg.cc/15LhnN23/images-1.png', 'Manual Three', NULL, 1, 1, 0, 0, 0, NULL, '0', NULL, 4, NULL),
(103, 'Manual Four', 'https://i.postimg.cc/15LhnN23/images-1.png', 'Manual Four', NULL, 1, 1, 0, 0, 0, NULL, '0', NULL, 3, '&lt;p&gt;&lt;strong style=&quot;color: rgb(230, 0, 0);&quot;&gt;Pay Via our what\'s alpp&lt;/strong&gt;&lt;/p&gt;'),
(104, 'Manual Five', 'https://i.postimg.cc/15LhnN23/images-1.png', 'Manual Five', NULL, 1, 1, 0, 0, 0, NULL, '0', NULL, 18, ''),
(105, 'Manual Six', 'https://i.postimg.cc/15LhnN23/images-1.png', 'Manual Six', NULL, 1, 1, 0, 0, 0, NULL, '0', NULL, 19, NULL),
(106, 'Manual Seven', 'https://i.postimg.cc/15LhnN23/images-1.png', 'Manual Seven', NULL, 1, 1, 0, 0, 0, NULL, '0', NULL, 20, NULL),
(107, 'Manual Eight', 'https://i.postimg.cc/15LhnN23/images-1.png', 'Manual Eight', NULL, 1, 1, 0, 0, 0, NULL, '0', NULL, 21, NULL),
(108, 'Manual Nine', 'https://i.postimg.cc/15LhnN23/images-1.png', 'Manual Nine', NULL, 1, 1, 0, 0, 0, NULL, '0', NULL, 22, NULL),
(109, 'Manual Ten', 'https://i.postimg.cc/15LhnN23/images-1.png', 'Manual Ten', NULL, 1, 1, 0, 0, 0, NULL, '0', NULL, 23, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `client_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_amount` decimal(15,4) NOT NULL,
  `payment_privatecode` double DEFAULT NULL,
  `payment_method` int(11) NOT NULL,
  `payment_status` enum('1','2','3') NOT NULL DEFAULT '1',
  `payment_delivery` enum('1','2') NOT NULL DEFAULT '1',
  `payment_note` varchar(255) NOT NULL DEFAULT 'No',
  `payment_mode` enum('Manual','Automatic') NOT NULL DEFAULT 'Automatic',
  `payment_create_date` datetime NOT NULL,
  `payment_update_date` datetime NOT NULL,
  `payment_ip` varchar(225) NOT NULL,
  `payment_extra` text NOT NULL,
  `payment_bank` int(11) NOT NULL,
  `t_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `client_id`, `client_balance`, `payment_amount`, `payment_privatecode`, `payment_method`, `payment_status`, `payment_delivery`, `payment_note`, `payment_mode`, `payment_create_date`, `payment_update_date`, `payment_ip`, `payment_extra`, `payment_bank`, `t_id`) VALUES
(1, 1, 0.00, 1.0000, NULL, 22, '3', '2', 'No', 'Automatic', '2024-12-30 19:48:51', '0000-00-00 00:00:00', '2405:201:a408:f983:d1f:4e8:74c7:faa2', '138537842390', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `referral`
--

CREATE TABLE `referral` (
  `referral_id` int(11) NOT NULL,
  `referral_client_id` int(11) NOT NULL,
  `referral_clicks` double NOT NULL DEFAULT 0,
  `referral_sign_up` double NOT NULL DEFAULT 0,
  `referral_totalFunds_byReffered` double NOT NULL DEFAULT 0,
  `referral_earned_commision` double DEFAULT 0,
  `referral_requested_commision` varchar(225) DEFAULT '0',
  `referral_total_commision` double DEFAULT 0,
  `referral_status` enum('1','2') NOT NULL DEFAULT '1',
  `referral_code` text NOT NULL,
  `referral_rejected_commision` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `referral`
--

INSERT INTO `referral` (`referral_id`, `referral_client_id`, `referral_clicks`, `referral_sign_up`, `referral_totalFunds_byReffered`, `referral_earned_commision`, `referral_requested_commision`, `referral_total_commision`, `referral_status`, `referral_code`, `referral_rejected_commision`) VALUES
(1, 1, 0, 0, 0, 0, '0', 0, '1', '4f549a', 0),
(2, 2, 0, 0, 0, 0, '0', 0, '1', 'e04d9c', 0),
(3, 3, 0, 0, 0, 0, '0', 0, '1', 'e64a10', 0),
(4, 4, 0, 0, 0, 0, '0', 0, '1', '6f22b1', 0),
(5, 5, 0, 0, 0, 0, '0', 0, '1', '0a5f74', 0),
(6, 6, 0, 0, 0, 0, '0', 0, '1', 'febd53', 0),
(7, 7, 0, 0, 0, 0, '0', 0, '1', 'd494bc', 0);

-- --------------------------------------------------------

--
-- Table structure for table `referral_payouts`
--

CREATE TABLE `referral_payouts` (
  `r_p_id` int(11) NOT NULL,
  `r_p_code` text NOT NULL,
  `r_p_status` enum('1','2','3','4','0') NOT NULL DEFAULT '0',
  `r_p_amount_requested` double NOT NULL,
  `r_p_requested_at` datetime NOT NULL,
  `r_p_updated_at` datetime NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `serviceapi_alert`
--

CREATE TABLE `serviceapi_alert` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `serviceapi_alert` text NOT NULL,
  `servicealert_extra` text NOT NULL,
  `servicealert_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_api` int(11) NOT NULL DEFAULT 0,
  `api_service` int(11) NOT NULL DEFAULT 0,
  `api_servicetype` enum('1','2') NOT NULL DEFAULT '2',
  `api_detail` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `service_line` double NOT NULL,
  `service_type` enum('1','2') NOT NULL DEFAULT '2',
  `service_package` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17') NOT NULL,
  `service_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `service_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `service_price` varchar(225) NOT NULL,
  `service_min` double NOT NULL,
  `service_max` double NOT NULL,
  `service_dripfeed` enum('1','2') NOT NULL DEFAULT '1',
  `service_autotime` double NOT NULL DEFAULT 0,
  `service_autopost` double NOT NULL DEFAULT 0,
  `service_speed` enum('1','2','3','4') NOT NULL,
  `want_username` enum('1','2') NOT NULL DEFAULT '1',
  `service_secret` enum('1','2') NOT NULL DEFAULT '2',
  `price_type` enum('normal','percent','amount') NOT NULL DEFAULT 'normal',
  `price_cal` text DEFAULT NULL,
  `instagram_second` enum('1','2') NOT NULL DEFAULT '2',
  `start_count` enum('none','instagram_follower','instagram_photo','') NOT NULL,
  `instagram_private` enum('1','2') NOT NULL,
  `name_lang` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`name_lang`)),
  `description_lang` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`description_lang`)),
  `time_lang` varchar(225) NOT NULL DEFAULT 'Not enough data',
  `time` varchar(225) NOT NULL DEFAULT 'Not enough data',
  `cancelbutton` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1 -> ON, 2 -> OFF',
  `show_refill` enum('true','false') NOT NULL DEFAULT 'false',
  `service_profit` varchar(225) NOT NULL,
  `refill_days` varchar(225) NOT NULL DEFAULT '30',
  `refill_hours` varchar(225) NOT NULL DEFAULT '24',
  `avg_days` int(11) NOT NULL,
  `avg_hours` int(11) NOT NULL,
  `avg_minutes` int(11) NOT NULL,
  `avg_many` int(11) NOT NULL,
  `price_profit` int(11) NOT NULL,
  `service_overflow` int(11) NOT NULL DEFAULT 0,
  `service_sync` enum('0','1') NOT NULL DEFAULT '1',
  `service_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_api`, `api_service`, `api_servicetype`, `api_detail`, `category_id`, `service_line`, `service_type`, `service_package`, `service_name`, `service_description`, `service_price`, `service_min`, `service_max`, `service_dripfeed`, `service_autotime`, `service_autopost`, `service_speed`, `want_username`, `service_secret`, `price_type`, `price_cal`, `instagram_second`, `start_count`, `instagram_private`, `name_lang`, `description_lang`, `time_lang`, `time`, `cancelbutton`, `show_refill`, `service_profit`, `refill_days`, `refill_hours`, `avg_days`, `avg_hours`, `avg_minutes`, `avg_many`, `price_profit`, `service_overflow`, `service_sync`, `service_deleted`) VALUES
(1, 1, 16026, '2', '{\"min\":\"100\",\"max\":\"2000000\",\"rate\":\"0.17\",\"currency\":\"INR\"}', 1, 1, '2', '1', '➤ IG » Views + Tv + Reels【 Test Server - Own Production ] - Pvt🚫', '', '0.187', 100, 2000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Views + Tv + Reels\\u3010 Test Server - Own Production ] - Pvt\\ud83d\\udeab\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(2, 1, 14076, '2', '{\"min\":\"100\",\"max\":\"100000000\",\"rate\":\"0.19\",\"currency\":\"INR\"}', 1, 2, '2', '1', '➤ IG Views + Tv + Reels【 𝐋𝐨𝐰𝐞𝐬𝐭 𝐏𝐫𝐢𝐜𝐞 𝐄𝐯𝐞𝐫 - 𝗨𝗽𝗱𝗮𝘁𝗲𝗱 ]🔥', '', '0.209', 100, 100000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG Views + Tv + Reels\\u3010 \\ud835\\udc0b\\ud835\\udc28\\ud835\\udc30\\ud835\\udc1e\\ud835\\udc2c\\ud835\\udc2d \\ud835\\udc0f\\ud835\\udc2b\\ud835\\udc22\\ud835\\udc1c\\ud835\\udc1e \\ud835\\udc04\\ud835\\udc2f\\ud835\\udc1e\\ud835\\udc2b - \\ud835\\udde8\\ud835\\uddfd\\ud835\\uddf1\\ud835\\uddee\\ud835\\ude01\\ud835\\uddf2\\ud835\\uddf1 ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(3, 1, 15528, '2', '{\"min\":\"100\",\"max\":\"2000000\",\"rate\":\"0.26\",\"currency\":\"INR\"}', 1, 3, '2', '1', '➤ IG Views + Tv + Reels【 Instant - 𝐂𝐡𝐞𝐚𝐩𝐞𝐫 𝐚𝐥𝐥 𝐓𝐢𝐦𝐞 ]🔥', '', '0.286', 100, 2000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG Views + Tv + Reels\\u3010 Instant - \\ud835\\udc02\\ud835\\udc21\\ud835\\udc1e\\ud835\\udc1a\\ud835\\udc29\\ud835\\udc1e\\ud835\\udc2b \\ud835\\udc1a\\ud835\\udc25\\ud835\\udc25 \\ud835\\udc13\\ud835\\udc22\\ud835\\udc26\\ud835\\udc1e ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(4, 1, 13279, '2', '{\"min\":\"100\",\"max\":\"100000000\",\"rate\":\"0.38\",\"currency\":\"INR\"}', 1, 4, '2', '1', '➤ IG » Views + Tv + Reels【 Instant - All Links - 𝐂𝐡𝐞𝐚𝐩𝐞𝐬𝐭 ]', '', '0.418', 100, 100000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Views + Tv + Reels\\u3010 Instant - All Links - \\ud835\\udc02\\ud835\\udc21\\ud835\\udc1e\\ud835\\udc1a\\ud835\\udc29\\ud835\\udc1e\\ud835\\udc2c\\ud835\\udc2d ]\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(5, 1, 12544, '2', '{\"min\":\"100\",\"max\":\"99999999\",\"rate\":\"0.45\",\"currency\":\"INR\"}', 1, 5, '2', '1', '➤ IG » Views + Tv + Reels【 Instant + New Service + 𝐂𝐡𝐞𝐚𝐩𝐞𝐬𝐭 - 4 ]🔥', '', '0.495', 100, 99999999, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Views + Tv + Reels\\u3010 Instant + New Service + \\ud835\\udc02\\ud835\\udc21\\ud835\\udc1e\\ud835\\udc1a\\ud835\\udc29\\ud835\\udc1e\\ud835\\udc2c\\ud835\\udc2d - 4 ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(6, 1, 16699, '2', '{\"min\":\"100\",\"max\":\"50000000\",\"rate\":\"0.58\",\"currency\":\"INR\"}', 1, 6, '2', '1', '➤ IG » Views + Tv + Reels【 Instant + New Service + 𝐂𝐡𝐞𝐚𝐩𝐞𝐬𝐭 - 5 ]🔥', '', '0.638', 100, 50000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Views + Tv + Reels\\u3010 Instant + New Service + \\ud835\\udc02\\ud835\\udc21\\ud835\\udc1e\\ud835\\udc1a\\ud835\\udc29\\ud835\\udc1e\\ud835\\udc2c\\ud835\\udc2d - 5 ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(7, 1, 16846, '2', '{\"min\":\"100\",\"max\":\"20000000\",\"rate\":\"0.71\",\"currency\":\"INR\"}', 1, 7, '2', '1', '➤ IG » Views + Tv + Reels【 Instant - Good Speed ]🔥', '', '0.781', 100, 20000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Views + Tv + Reels\\u3010 Instant - Good Speed ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(8, 1, 17590, '2', '{\"min\":\"100\",\"max\":\"100000000\",\"rate\":\"1.03\",\"currency\":\"INR\"}', 1, 8, '2', '1', '➤ IG » Views + Tv + Reels【 Instant - Stable - Support Available ] 🔥', '', '1.133', 100, 100000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Views + Tv + Reels\\u3010 Instant - Stable - Support Available ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(9, 1, 14243, '2', '{\"min\":\"100\",\"max\":\"100000000\",\"rate\":\"1.13\",\"currency\":\"INR\"}', 1, 9, '2', '1', '➤ IG Views + Tv + Reels【 𝐏𝐮𝐛𝐥𝐢𝐜 𝐃𝐞𝐦𝐚𝐧𝐝 - No Cancellection ]🔥	🚀', '', '1.243', 100, 100000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG Views + Tv + Reels\\u3010 \\ud835\\udc0f\\ud835\\udc2e\\ud835\\udc1b\\ud835\\udc25\\ud835\\udc22\\ud835\\udc1c \\ud835\\udc03\\ud835\\udc1e\\ud835\\udc26\\ud835\\udc1a\\ud835\\udc27\\ud835\\udc1d - No Cancellection ]\\ud83d\\udd25\\t\\ud83d\\ude80\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(10, 1, 16121, '2', '{\"min\":\"100\",\"max\":\"100000000\",\"rate\":\"4.10\",\"currency\":\"INR\"}', 1, 10, '2', '1', '➤ IG » Views + Tv + Reels【 Instant - No Support & Cancellation ]🔥', '', '4.51', 100, 100000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Views + Tv + Reels\\u3010 Instant - No Support & Cancellation ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(11, 1, 16032, '2', '{\"min\":\"100\",\"max\":\"100000000\",\"rate\":\"8.21\",\"currency\":\"INR\"}', 1, 11, '2', '1', '➤ IG Views + Tv + Reels, Natural Speed【 Instant - 𝐎𝐰𝐧 𝐁𝐚𝐬𝐞 ] 🔥', '', '9.031', 100, 100000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG Views + Tv + Reels, Natural Speed\\u3010 Instant - \\ud835\\udc0e\\ud835\\udc30\\ud835\\udc27 \\ud835\\udc01\\ud835\\udc1a\\ud835\\udc2c\\ud835\\udc1e ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(12, 1, 16696, '2', '{\"min\":\"100\",\"max\":\"100000000\",\"rate\":\"10.26\",\"currency\":\"INR\"}', 1, 12, '2', '1', '➤ IG » Views + Tv + Reels【 Instant - Cheapest ] - Own Provider🔥', '', '11.286', 100, 100000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Views + Tv + Reels\\u3010 Instant - Cheapest ] - Own Provider\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(13, 1, 16631, '2', '{\"min\":\"100\",\"max\":\"100000000\",\"rate\":\"19.49\",\"currency\":\"INR\"}', 1, 13, '2', '1', '➤ IG » Views + Tv + Reels【 High Speed 𝐒𝐞𝐫𝐯𝐞𝐫 - 𝗘𝗺𝗲𝗿𝗴𝗲𝗻𝗰𝘆 ]🔥', '', '21.439', 100, 100000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Views + Tv + Reels\\u3010 High Speed \\ud835\\udc12\\ud835\\udc1e\\ud835\\udc2b\\ud835\\udc2f\\ud835\\udc1e\\ud835\\udc2b - \\ud835\\uddd8\\ud835\\uddfa\\ud835\\uddf2\\ud835\\uddff\\ud835\\uddf4\\ud835\\uddf2\\ud835\\uddfb\\ud835\\uddf0\\ud835\\ude06 ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(14, 1, 15364, '2', '{\"min\":\"100\",\"max\":\"50000000\",\"rate\":\"22.00\",\"currency\":\"INR\"}', 1, 14, '2', '1', '➤ IG » Views + Tv + Reels【 𝐒𝐮𝐩𝐞𝐫𝐅𝐚𝐬𝐭𝐞𝐫 + Emergency ]🔥', '', '24.2', 100, 50000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Views + Tv + Reels\\u3010 \\ud835\\udc12\\ud835\\udc2e\\ud835\\udc29\\ud835\\udc1e\\ud835\\udc2b\\ud835\\udc05\\ud835\\udc1a\\ud835\\udc2c\\ud835\\udc2d\\ud835\\udc1e\\ud835\\udc2b + Emergency ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(15, 1, 16454, '2', '{\"min\":\"100\",\"max\":\"100000000\",\"rate\":\"25.42\",\"currency\":\"INR\"}', 1, 15, '2', '1', '➤ IG » Views + Tv + Reels【 High Speed 𝐒𝐞𝐫𝐯𝐞𝐫 - 𝗘𝗺𝗲𝗿𝗴𝗲𝗻𝗰𝘆 ] - 01🔥', '', '27.962', 100, 100000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Views + Tv + Reels\\u3010 High Speed \\ud835\\udc12\\ud835\\udc1e\\ud835\\udc2b\\ud835\\udc2f\\ud835\\udc1e\\ud835\\udc2b - \\ud835\\uddd8\\ud835\\uddfa\\ud835\\uddf2\\ud835\\uddff\\ud835\\uddf4\\ud835\\uddf2\\ud835\\uddfb\\ud835\\uddf0\\ud835\\ude06 ] - 01\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(16, 1, 17625, '2', '{\"min\":\"500\",\"max\":\"1000000\",\"rate\":\"91.06\",\"currency\":\"INR\"}', 2, 16, '2', '1', '▶ YouTube » Views - No Drop【 External/Direct Source - 2K - 5K Per Day - 0 - 1 Hours - Lifetime Guaranteed ] - 𝐎𝐯𝐞𝐫𝐝𝐞𝐥𝐢𝐯𝐞𝐫𝐲🔥', '', '100.166', 500, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 YouTube \\u00bb Views - No Drop\\u3010 External\\/Direct Source - 2K - 5K Per Day - 0 - 1 Hours - Lifetime Guaranteed ] - \\ud835\\udc0e\\ud835\\udc2f\\ud835\\udc1e\\ud835\\udc2b\\ud835\\udc1d\\ud835\\udc1e\\ud835\\udc25\\ud835\\udc22\\ud835\\udc2f\\ud835\\udc1e\\ud835\\udc2b\\ud835\\udc32\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(17, 1, 17626, '2', '{\"min\":\"10000\",\"max\":\"1000000\",\"rate\":\"80.79\",\"currency\":\"INR\"}', 2, 17, '2', '1', '▶ YouTube » Views - No Drop【 External/Direct Source - 150K - 250K Per Day - 0 - 1 Hours - Lifetime Guaranteed ] - 𝐎𝐯𝐞𝐫𝐝𝐞𝐥𝐢𝐯𝐞𝐫𝐲🔥', '', '88.869', 10000, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 YouTube \\u00bb Views - No Drop\\u3010 External\\/Direct Source - 150K - 250K Per Day - 0 - 1 Hours - Lifetime Guaranteed ] - \\ud835\\udc0e\\ud835\\udc2f\\ud835\\udc1e\\ud835\\udc2b\\ud835\\udc1d\\ud835\\udc1e\\ud835\\udc25\\ud835\\udc22\\ud835\\udc2f\\ud835\\udc1e\\ud835\\udc2b\\ud835\\udc32\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(18, 1, 15600, '2', '{\"min\":\"1\",\"max\":\"1\",\"rate\":\"999999.00\",\"currency\":\"INR\"}', 2, 18, '2', '2', '-------------X-------------------------X--------------------', '', '1099998.9', 1, 1, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"-------------X-------------------------X--------------------\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(19, 1, 15895, '2', '{\"min\":\"50\",\"max\":\"3000\",\"rate\":\"180.95\",\"currency\":\"INR\"}', 2, 19, '2', '1', '➤ Instagram » Followers【 𝗜𝗻𝗱𝗶𝗮𝗻 𝗡𝗮𝗺𝗲𝘀 🇮🇳 + 𝗢𝗿𝗴𝗮𝗻𝗶𝗰 𝗔𝗰𝗰𝗼𝘂𝗻𝘁𝘀 - 2K - 5K Per Day - No Drop - Lifetime Refill ]🔥', '', '199.045', 50, 3000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Instagram \\u00bb Followers\\u3010 \\ud835\\udddc\\ud835\\uddfb\\ud835\\uddf1\\ud835\\uddf6\\ud835\\uddee\\ud835\\uddfb \\ud835\\udde1\\ud835\\uddee\\ud835\\uddfa\\ud835\\uddf2\\ud835\\ude00 \\ud83c\\uddee\\ud83c\\uddf3 + \\ud835\\udde2\\ud835\\uddff\\ud835\\uddf4\\ud835\\uddee\\ud835\\uddfb\\ud835\\uddf6\\ud835\\uddf0 \\ud835\\uddd4\\ud835\\uddf0\\ud835\\uddf0\\ud835\\uddfc\\ud835\\ude02\\ud835\\uddfb\\ud835\\ude01\\ud835\\ude00 - 2K - 5K Per Day - No Drop - Lifetime Refill ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(20, 1, 15380, '2', '{\"min\":\"10\",\"max\":\"1000000\",\"rate\":\"224.40\",\"currency\":\"INR\"}', 2, 20, '2', '1', '➤ IG ➠ Followers【 100% 🇮🇳 𝐈𝐧𝐝𝐢𝐚𝐧 𝐑𝐞𝐚𝐥 - 20K - 50K Per Day - Absolute Indian 🇮🇳 - Instant with 366 Days Refill ]🔥', '', '246.84', 10, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u27a0 Followers\\u3010 100% \\ud83c\\uddee\\ud83c\\uddf3 \\ud835\\udc08\\ud835\\udc27\\ud835\\udc1d\\ud835\\udc22\\ud835\\udc1a\\ud835\\udc27 \\ud835\\udc11\\ud835\\udc1e\\ud835\\udc1a\\ud835\\udc25 - 20K - 50K Per Day - Absolute Indian \\ud83c\\uddee\\ud83c\\uddf3 - Instant with 366 Days Refill ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(21, 1, 14084, '2', '{\"min\":\"100\",\"max\":\"200000\",\"rate\":\"121.63\",\"currency\":\"INR\"}', 2, 21, '2', '1', '➤ Instagram Followers -> 𝐂𝐡𝐞𝐚𝐩𝐞𝐬𝐭【 100% Old Quality - 50K Per Day - 3% Drop - 0 - 2 Hours - Refill 365 Days ] - 𝐅𝐥𝐚𝐠 𝐨𝐧 𝐚𝐮𝐭𝐨 𝐜𝐚𝐧𝐜𝐞𝐥𝐥𝐚𝐭𝐢𝐨𝐧 🚫🔥', '', '133.793', 100, 200000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Instagram Followers -> \\ud835\\udc02\\ud835\\udc21\\ud835\\udc1e\\ud835\\udc1a\\ud835\\udc29\\ud835\\udc1e\\ud835\\udc2c\\ud835\\udc2d\\u3010 100% Old Quality - 50K Per Day - 3% Drop - 0 - 2 Hours - Refill 365 Days ] - \\ud835\\udc05\\ud835\\udc25\\ud835\\udc1a\\ud835\\udc20 \\ud835\\udc28\\ud835\\udc27 \\ud835\\udc1a\\ud835\\udc2e\\ud835\\udc2d\\ud835\\udc28 \\ud835\\udc1c\\ud835\\udc1a\\ud835\\udc27\\ud835\\udc1c\\ud835\\udc1e\\ud835\\udc25\\ud835\\udc25\\ud835\\udc1a\\ud835\\udc2d\\ud835\\udc22\\ud835\\udc28\\ud835\\udc27 \\ud83d\\udeab\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(22, 1, 15532, '2', '{\"min\":\"100\",\"max\":\"200000\",\"rate\":\"127.87\",\"currency\":\"INR\"}', 2, 22, '2', '1', '➤ Instagram Followers, 𝗧𝗼𝗽 & 25+ Posts 𝗢𝗹𝗱 𝗔𝗰𝗰𝗼𝘂𝗻𝘁\'𝘀【 50K - 100K Per Day - 2% Drop - Refill Button 𝟑𝟔𝟓 Day ] - 𝐅𝐥𝐚𝐠 𝐨𝐧 𝐚𝐮𝐭𝐨 𝐜𝐚𝐧𝐜𝐞𝐥𝐥𝐚𝐭𝐢𝐨𝐧 🚫🔥', '', '140.657', 100, 200000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Instagram Followers, \\ud835\\udde7\\ud835\\uddfc\\ud835\\uddfd & 25+ Posts \\ud835\\udde2\\ud835\\uddf9\\ud835\\uddf1 \\ud835\\uddd4\\ud835\\uddf0\\ud835\\uddf0\\ud835\\uddfc\\ud835\\ude02\\ud835\\uddfb\\ud835\\ude01\'\\ud835\\ude00\\u3010 50K - 100K Per Day - 2% Drop - Refill Button \\ud835\\udfd1\\ud835\\udfd4\\ud835\\udfd3 Day ] - \\ud835\\udc05\\ud835\\udc25\\ud835\\udc1a\\ud835\\udc20 \\ud835\\udc28\\ud835\\udc27 \\ud835\\udc1a\\ud835\\udc2e\\ud835\\udc2d\\ud835\\udc28 \\ud835\\udc1c\\ud835\\udc1a\\ud835\\udc27\\ud835\\udc1c\\ud835\\udc1e\\ud835\\udc25\\ud835\\udc25\\ud835\\udc1a\\ud835\\udc2d\\ud835\\udc22\\ud835\\udc28\\ud835\\udc27 \\ud83d\\udeab\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(23, 1, 12764, '2', '{\"min\":\"100\",\"max\":\"700000\",\"rate\":\"127.38\",\"currency\":\"INR\"}', 2, 23, '2', '1', '➤ Instagram » Followers【 𝗛𝗤 𝗥𝗘𝗔𝗟 + 𝗠𝗨𝗟𝗧𝗜𝗣𝗟𝗘 𝗣𝗢𝗦𝗧𝗦 - 100K+ Per Day - Instant - No Drop - Lifetime Refill - 𝐏𝐫𝐞𝐦𝐢𝐮𝐦 𝐒𝐞𝐫𝐯𝐞𝐫 ] - 𝐅𝐥𝐚𝐠 𝐨𝐧 𝐚𝐮𝐭𝐨 𝐜𝐚𝐧𝐜𝐞𝐥𝐥𝐚𝐭𝐢𝐨𝐧 🚫🔥', '', '140.118', 100, 700000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Instagram \\u00bb Followers\\u3010 \\ud835\\udddb\\ud835\\udde4 \\ud835\\udde5\\ud835\\uddd8\\ud835\\uddd4\\ud835\\udddf + \\ud835\\udde0\\ud835\\udde8\\ud835\\udddf\\ud835\\udde7\\ud835\\udddc\\ud835\\udde3\\ud835\\udddf\\ud835\\uddd8 \\ud835\\udde3\\ud835\\udde2\\ud835\\udde6\\ud835\\udde7\\ud835\\udde6 - 100K+ Per Day - Instant - No Drop - Lifetime Refill - \\ud835\\udc0f\\ud835\\udc2b\\ud835\\udc1e\\ud835\\udc26\\ud835\\udc22\\ud835\\udc2e\\ud835\\udc26 \\ud835\\udc12\\ud835\\udc1e\\ud835\\udc2b\\ud835\\udc2f\\ud835\\udc1e\\ud835\\udc2b ] - \\ud835\\udc05\\ud835\\udc25\\ud835\\udc1a\\ud835\\udc20 \\ud835\\udc28\\ud835\\udc27 \\ud835\\udc1a\\ud835\\udc2e\\ud835\\udc2d\\ud835\\udc28 \\ud835\\udc1c\\ud835\\udc1a\\ud835\\udc27\\ud835\\udc1c\\ud835\\udc1e\\ud835\\udc25\\ud835\\udc25\\ud835\\udc1a\\ud835\\udc2d\\ud835\\udc22\\ud835\\udc28\\ud835\\udc27 \\ud83d\\udeab\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(24, 1, 15950, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"129.60\",\"currency\":\"INR\"}', 2, 24, '2', '1', '➤ Instagram Followers【 Old Quality - 100K Per Day - Non Drop - Instant - Refill 365 Days ] - 𝐅𝐥𝐚𝐠 𝐨𝐧 𝐚𝐮𝐭𝐨 𝐜𝐚𝐧𝐜𝐞𝐥𝐥𝐚𝐭𝐢𝐨𝐧 🚫🔥', '', '142.56', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Instagram Followers\\u3010 Old Quality - 100K Per Day - Non Drop - Instant - Refill 365 Days ] - \\ud835\\udc05\\ud835\\udc25\\ud835\\udc1a\\ud835\\udc20 \\ud835\\udc28\\ud835\\udc27 \\ud835\\udc1a\\ud835\\udc2e\\ud835\\udc2d\\ud835\\udc28 \\ud835\\udc1c\\ud835\\udc1a\\ud835\\udc27\\ud835\\udc1c\\ud835\\udc1e\\ud835\\udc25\\ud835\\udc25\\ud835\\udc1a\\ud835\\udc2d\\ud835\\udc22\\ud835\\udc28\\ud835\\udc27 \\ud83d\\udeab\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(25, 1, 15475, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"139.67\",\"currency\":\"INR\"}', 3, 25, '2', '1', '➤ IG ≽ Followers【 Old Accounts - 20K - 50K+ Day - No Drop - Instant - 366 Days Refill ] - 𝗢𝘄𝗻 𝗣𝗿𝗼𝗱𝘂𝗰𝘁𝗶𝗼𝗻🔥', '', '153.637', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u227d Followers\\u3010 Old Accounts - 20K - 50K+ Day - No Drop - Instant - 366 Days Refill ] - \\ud835\\udde2\\ud835\\ude04\\ud835\\uddfb \\ud835\\udde3\\ud835\\uddff\\ud835\\uddfc\\ud835\\uddf1\\ud835\\ude02\\ud835\\uddf0\\ud835\\ude01\\ud835\\uddf6\\ud835\\uddfc\\ud835\\uddfb\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(26, 1, 16212, '2', '{\"min\":\"100\",\"max\":\"500000\",\"rate\":\"141.24\",\"currency\":\"INR\"}', 3, 26, '2', '1', '➤ IG Followers »【 𝗢𝗹𝗱 𝗔𝗰𝗰𝗼𝘂𝗻𝘁 - 20K - 50K+ Per Day - Stable - Instant - 366 Days Refill ] - 𝗠𝘂𝘀𝘁 𝗙𝗹𝗮𝗴 𝗢𝗳𝗳 🔥', '', '155.364', 100, 500000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG Followers \\u00bb\\u3010 \\ud835\\udde2\\ud835\\uddf9\\ud835\\uddf1 \\ud835\\uddd4\\ud835\\uddf0\\ud835\\uddf0\\ud835\\uddfc\\ud835\\ude02\\ud835\\uddfb\\ud835\\ude01 - 20K - 50K+ Per Day - Stable - Instant - 366 Days Refill ] - \\ud835\\udde0\\ud835\\ude02\\ud835\\ude00\\ud835\\ude01 \\ud835\\uddd9\\ud835\\uddf9\\ud835\\uddee\\ud835\\uddf4 \\ud835\\udde2\\ud835\\uddf3\\ud835\\uddf3 \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(27, 1, 15949, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"146.05\",\"currency\":\"INR\"}', 3, 27, '2', '1', '➤ IG Followers【 Old Quality - 50K Per Day - Non Drop - Instant - Refill 365 Days ] - 𝗠𝘂𝘀𝘁 𝗙𝗹𝗮𝗴 𝗢𝗳𝗳 🚫🔥', '', '160.655', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG Followers\\u3010 Old Quality - 50K Per Day - Non Drop - Instant - Refill 365 Days ] - \\ud835\\udde0\\ud835\\ude02\\ud835\\ude00\\ud835\\ude01 \\ud835\\uddd9\\ud835\\uddf9\\ud835\\uddee\\ud835\\uddf4 \\ud835\\udde2\\ud835\\uddf3\\ud835\\uddf3 \\ud83d\\udeab\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(28, 1, 15530, '2', '{\"min\":\"100\",\"max\":\"500000\",\"rate\":\"148.85\",\"currency\":\"INR\"}', 3, 28, '2', '1', '➤ Instagram » Followers【 Old Accounts - 20K - 100K Per Day - Instant - 𝗟𝗶𝗳𝗲𝘁𝗶𝗺𝗲 𝗚𝘂𝗮𝗿𝗮𝗻𝘁𝗲𝗲 ] - 𝐌𝐮𝐬𝐭 𝐅𝐥𝐚𝐠 𝐎𝐟𝐟 🔥', '', '163.735', 100, 500000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Instagram \\u00bb Followers\\u3010 Old Accounts - 20K - 100K Per Day - Instant - \\ud835\\udddf\\ud835\\uddf6\\ud835\\uddf3\\ud835\\uddf2\\ud835\\ude01\\ud835\\uddf6\\ud835\\uddfa\\ud835\\uddf2 \\ud835\\uddda\\ud835\\ude02\\ud835\\uddee\\ud835\\uddff\\ud835\\uddee\\ud835\\uddfb\\ud835\\ude01\\ud835\\uddf2\\ud835\\uddf2 ] - \\ud835\\udc0c\\ud835\\udc2e\\ud835\\udc2c\\ud835\\udc2d \\ud835\\udc05\\ud835\\udc25\\ud835\\udc1a\\ud835\\udc20 \\ud835\\udc0e\\ud835\\udc1f\\ud835\\udc1f \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(29, 1, 14459, '2', '{\"min\":\"10\",\"max\":\"1000000\",\"rate\":\"149.98\",\"currency\":\"INR\"}', 3, 29, '2', '1', '➤ Instagram » Followers【 𝗛𝗤 𝗥𝗘𝗔𝗟 + 𝗠𝗨𝗟𝗧𝗜𝗣𝗟𝗘 𝗣𝗢𝗦𝗧𝗦 - 100K+ Per Day - Instant - No Refill ] - 𝐌𝐮𝐬𝐭 𝐅𝐥𝐚𝐠 𝐎𝐟𝐟 🔥', '', '164.978', 10, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Instagram \\u00bb Followers\\u3010 \\ud835\\udddb\\ud835\\udde4 \\ud835\\udde5\\ud835\\uddd8\\ud835\\uddd4\\ud835\\udddf + \\ud835\\udde0\\ud835\\udde8\\ud835\\udddf\\ud835\\udde7\\ud835\\udddc\\ud835\\udde3\\ud835\\udddf\\ud835\\uddd8 \\ud835\\udde3\\ud835\\udde2\\ud835\\udde6\\ud835\\udde7\\ud835\\udde6 - 100K+ Per Day - Instant - No Refill ] - \\ud835\\udc0c\\ud835\\udc2e\\ud835\\udc2c\\ud835\\udc2d \\ud835\\udc05\\ud835\\udc25\\ud835\\udc1a\\ud835\\udc20 \\ud835\\udc0e\\ud835\\udc1f\\ud835\\udc1f \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(30, 1, 14261, '2', '{\"min\":\"50\",\"max\":\"100000\",\"rate\":\"150.06\",\"currency\":\"INR\"}', 3, 30, '2', '1', '➤ IG » Followers【 𝐀𝐥𝐥 𝟭𝟱+ 𝗣𝗼𝘀𝘁 - 𝐎𝐥𝐝 𝐀𝐜𝐜𝐨𝐮𝐧𝐭𝐬 - 20K -  50K Per Day - Lifetime Refill - Instant ] - 𝗠𝘂𝘀𝘁 𝗙𝗹𝗮𝗴 𝗢𝗳𝗳🔥🚫', '', '165.066', 50, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Followers\\u3010 \\ud835\\udc00\\ud835\\udc25\\ud835\\udc25 \\ud835\\udfed\\ud835\\udff1+ \\ud835\\udde3\\ud835\\uddfc\\ud835\\ude00\\ud835\\ude01 - \\ud835\\udc0e\\ud835\\udc25\\ud835\\udc1d \\ud835\\udc00\\ud835\\udc1c\\ud835\\udc1c\\ud835\\udc28\\ud835\\udc2e\\ud835\\udc27\\ud835\\udc2d\\ud835\\udc2c - 20K -  50K Per Day - Lifetime Refill - Instant ] - \\ud835\\udde0\\ud835\\ude02\\ud835\\ude00\\ud835\\ude01 \\ud835\\uddd9\\ud835\\uddf9\\ud835\\uddee\\ud835\\uddf4 \\ud835\\udde2\\ud835\\uddf3\\ud835\\uddf3\\ud83d\\udd25\\ud83d\\udeab\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(31, 1, 17418, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"154.81\",\"currency\":\"INR\"}', 3, 31, '2', '1', '➤ IG ≽ Followers【 𝐑𝐞𝐚𝐥 𝐀𝐜𝐜𝐨𝐮𝐧𝐭 - 50K - 100K Day - Instant - 𝗟𝗶𝗳𝗲𝘁𝗶𝗺𝗲 𝗚𝘂𝗮𝗿𝗮𝗻𝘁𝗲𝗲𝗱 ] - 𝙇𝙖𝙩𝙚𝙨𝙩 𝙐𝙥𝙙𝙖𝙩𝙚𝙙🔥', '', '170.291', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u227d Followers\\u3010 \\ud835\\udc11\\ud835\\udc1e\\ud835\\udc1a\\ud835\\udc25 \\ud835\\udc00\\ud835\\udc1c\\ud835\\udc1c\\ud835\\udc28\\ud835\\udc2e\\ud835\\udc27\\ud835\\udc2d - 50K - 100K Day - Instant - \\ud835\\udddf\\ud835\\uddf6\\ud835\\uddf3\\ud835\\uddf2\\ud835\\ude01\\ud835\\uddf6\\ud835\\uddfa\\ud835\\uddf2 \\ud835\\uddda\\ud835\\ude02\\ud835\\uddee\\ud835\\uddff\\ud835\\uddee\\ud835\\uddfb\\ud835\\ude01\\ud835\\uddf2\\ud835\\uddf2\\ud835\\uddf1 ] - \\ud835\\ude47\\ud835\\ude56\\ud835\\ude69\\ud835\\ude5a\\ud835\\ude68\\ud835\\ude69 \\ud835\\ude50\\ud835\\ude65\\ud835\\ude59\\ud835\\ude56\\ud835\\ude69\\ud835\\ude5a\\ud835\\ude59\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(32, 1, 16866, '2', '{\"min\":\"10\",\"max\":\"100000\",\"rate\":\"139.59\",\"currency\":\"INR\"}', 3, 32, '2', '1', '➤ Instagram Followers【 𝗢𝗹𝗱 - 𝗗𝗮𝘁𝗲𝗱 𝗔𝗰𝗰𝗼𝘂𝗻𝘁 -  10K - 50K+ Per Day - Instant - Lifetime Refill ] - Flag Off🔥', '', '153.549', 10, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Instagram Followers\\u3010 \\ud835\\udde2\\ud835\\uddf9\\ud835\\uddf1 - \\ud835\\uddd7\\ud835\\uddee\\ud835\\ude01\\ud835\\uddf2\\ud835\\uddf1 \\ud835\\uddd4\\ud835\\uddf0\\ud835\\uddf0\\ud835\\uddfc\\ud835\\ude02\\ud835\\uddfb\\ud835\\ude01 -  10K - 50K+ Per Day - Instant - Lifetime Refill ] - Flag Off\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(33, 1, 15145, '2', '{\"min\":\"10\",\"max\":\"1000000\",\"rate\":\"133.63\",\"currency\":\"INR\"}', 4, 33, '2', '1', '➤ IG Followers »【 Old Accounts - 20K - 60K+ Per Day - Instant - No Refill - \"𝐅𝐥𝐚𝐠 𝐑𝐞𝐯𝐢𝐞𝐰 𝐎𝐟𝐟\" ] - 𝙇𝙖𝙩𝙚𝙨𝙩 𝙐𝙥𝙙𝙖𝙩𝙚𝙙🔥', '', '146.993', 10, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG Followers \\u00bb\\u3010 Old Accounts - 20K - 60K+ Per Day - Instant - No Refill - \\\"\\ud835\\udc05\\ud835\\udc25\\ud835\\udc1a\\ud835\\udc20 \\ud835\\udc11\\ud835\\udc1e\\ud835\\udc2f\\ud835\\udc22\\ud835\\udc1e\\ud835\\udc30 \\ud835\\udc0e\\ud835\\udc1f\\ud835\\udc1f\\\" ] - \\ud835\\ude47\\ud835\\ude56\\ud835\\ude69\\ud835\\ude5a\\ud835\\ude68\\ud835\\ude69 \\ud835\\ude50\\ud835\\ude65\\ud835\\ude59\\ud835\\ude56\\ud835\\ude69\\ud835\\ude5a\\ud835\\ude59\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(34, 1, 14052, '2', '{\"min\":\"10\",\"max\":\"1000000\",\"rate\":\"136.06\",\"currency\":\"INR\"}', 4, 34, '2', '1', '➤ Instagram Followers »【 Mixed Accounts - 10K - 50K+ Per Day - Instant - No Refill ] - 𝙁𝙖𝙨𝙩𝙚𝙨𝙩 𝙎𝙥𝙚𝙚𝙙🔥', '', '149.666', 10, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Instagram Followers \\u00bb\\u3010 Mixed Accounts - 10K - 50K+ Per Day - Instant - No Refill ] - \\ud835\\ude41\\ud835\\ude56\\ud835\\ude68\\ud835\\ude69\\ud835\\ude5a\\ud835\\ude68\\ud835\\ude69 \\ud835\\ude4e\\ud835\\ude65\\ud835\\ude5a\\ud835\\ude5a\\ud835\\ude59\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(35, 1, 15533, '2', '{\"min\":\"50\",\"max\":\"650000\",\"rate\":\"165.00\",\"currency\":\"INR\"}', 4, 35, '2', '1', '➤ IG » Followers【 𝟭𝟱+ 𝗣𝗼𝘀𝘁 𝐎𝐥𝐝 𝗔𝗰𝗰𝗼𝘂𝗻𝘁\'s - 𝟭𝟬𝟬𝗞+ 𝗣𝗲𝗿 𝗗𝗮𝘆 - 366 Days Auto - Refill Button ] - 𝙄𝙢𝙥𝙤𝙧𝙩𝙚𝙙 𝙁𝙧𝙤𝙢 𝙈𝙚𝙭𝙞𝙘𝙤 🇲🇽🔥', '', '181.5', 50, 650000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Followers\\u3010 \\ud835\\udfed\\ud835\\udff1+ \\ud835\\udde3\\ud835\\uddfc\\ud835\\ude00\\ud835\\ude01 \\ud835\\udc0e\\ud835\\udc25\\ud835\\udc1d \\ud835\\uddd4\\ud835\\uddf0\\ud835\\uddf0\\ud835\\uddfc\\ud835\\ude02\\ud835\\uddfb\\ud835\\ude01\'s - \\ud835\\udfed\\ud835\\udfec\\ud835\\udfec\\ud835\\uddde+ \\ud835\\udde3\\ud835\\uddf2\\ud835\\uddff \\ud835\\uddd7\\ud835\\uddee\\ud835\\ude06 - 366 Days Auto - Refill Button ] - \\ud835\\ude44\\ud835\\ude62\\ud835\\ude65\\ud835\\ude64\\ud835\\ude67\\ud835\\ude69\\ud835\\ude5a\\ud835\\ude59 \\ud835\\ude41\\ud835\\ude67\\ud835\\ude64\\ud835\\ude62 \\ud835\\ude48\\ud835\\ude5a\\ud835\\ude6d\\ud835\\ude5e\\ud835\\ude58\\ud835\\ude64 \\ud83c\\uddf2\\ud83c\\uddfd\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(36, 1, 16867, '2', '{\"min\":\"10\",\"max\":\"3000000\",\"rate\":\"175.00\",\"currency\":\"INR\"}', 4, 36, '2', '1', '➤ IG ≽ Followers【 10K - 50K+ Per Day - Old Accounts - No Drop - 𝐃𝐚𝐢𝐥𝐲/𝐋𝐢𝐟𝐞𝐭𝐢𝐦𝐞 𝐀𝐮𝐭𝐨 𝐑𝐞𝐟𝐢𝐥𝐥 - Instant ] - 𝗨𝗽𝗱𝗮𝘁𝗲𝗱 𝗦𝗲𝗿𝘃𝗲𝗿🔥', '', '192.5', 10, 3000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u227d Followers\\u3010 10K - 50K+ Per Day - Old Accounts - No Drop - \\ud835\\udc03\\ud835\\udc1a\\ud835\\udc22\\ud835\\udc25\\ud835\\udc32\\/\\ud835\\udc0b\\ud835\\udc22\\ud835\\udc1f\\ud835\\udc1e\\ud835\\udc2d\\ud835\\udc22\\ud835\\udc26\\ud835\\udc1e \\ud835\\udc00\\ud835\\udc2e\\ud835\\udc2d\\ud835\\udc28 \\ud835\\udc11\\ud835\\udc1e\\ud835\\udc1f\\ud835\\udc22\\ud835\\udc25\\ud835\\udc25 - Instant ] - \\ud835\\udde8\\ud835\\uddfd\\ud835\\uddf1\\ud835\\uddee\\ud835\\ude01\\ud835\\uddf2\\ud835\\uddf1 \\ud835\\udde6\\ud835\\uddf2\\ud835\\uddff\\ud835\\ude03\\ud835\\uddf2\\ud835\\uddff\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(37, 1, 15119, '2', '{\"min\":\"10\",\"max\":\"100000\",\"rate\":\"185.80\",\"currency\":\"INR\"}', 4, 37, '2', '1', '➤ IG ≽ Followers【 Old Quality - 50K - 100K Day - 𝗕𝗶𝗴 𝗕𝗮𝘀𝗲 𝗦𝗲𝗿𝘃𝗶𝗰𝗲 - Instant - 365 Days Refill ] - Updated🔥', '', '204.38', 10, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u227d Followers\\u3010 Old Quality - 50K - 100K Day - \\ud835\\uddd5\\ud835\\uddf6\\ud835\\uddf4 \\ud835\\uddd5\\ud835\\uddee\\ud835\\ude00\\ud835\\uddf2 \\ud835\\udde6\\ud835\\uddf2\\ud835\\uddff\\ud835\\ude03\\ud835\\uddf6\\ud835\\uddf0\\ud835\\uddf2 - Instant - 365 Days Refill ] - Updated\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(38, 1, 16700, '2', '{\"min\":\"100\",\"max\":\"500000\",\"rate\":\"206.80\",\"currency\":\"INR\"}', 4, 38, '2', '1', '➤ IG » Followers - High Quality Accounts -【 20K - 100K Per Day - 𝗡𝗼 𝗗𝗿𝗼𝗽 - 𝗟𝗶𝗳𝗲𝘁𝗶𝗺𝗲 𝗥𝗲𝗳𝗶𝗹𝗹 𝗕𝘂𝘁𝘁𝗼𝗻 - Cancel Button ] 🚫🔥', '', '227.48', 100, 500000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Followers - High Quality Accounts -\\u3010 20K - 100K Per Day - \\ud835\\udde1\\ud835\\uddfc \\ud835\\uddd7\\ud835\\uddff\\ud835\\uddfc\\ud835\\uddfd - \\ud835\\udddf\\ud835\\uddf6\\ud835\\uddf3\\ud835\\uddf2\\ud835\\ude01\\ud835\\uddf6\\ud835\\uddfa\\ud835\\uddf2 \\ud835\\udde5\\ud835\\uddf2\\ud835\\uddf3\\ud835\\uddf6\\ud835\\uddf9\\ud835\\uddf9 \\ud835\\uddd5\\ud835\\ude02\\ud835\\ude01\\ud835\\ude01\\ud835\\uddfc\\ud835\\uddfb - Cancel Button ] \\ud83d\\udeab\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(39, 1, 16409, '2', '{\"min\":\"10\",\"max\":\"5000000\",\"rate\":\"286.88\",\"currency\":\"INR\"}', 4, 39, '2', '1', '➤ IG » Followers -【 𝟭𝟱+ 𝗣𝗼𝘀𝘁 𝐎𝐥𝐝 𝗔𝗰𝗰𝗼𝘂𝗻𝘁\'s - 𝟭𝟬𝟬𝗞+ 𝗣𝗲𝗿 𝗗𝗮𝘆 - 30 Days Refill Button ] - 𝙒𝙤𝙧𝙠𝙞𝙣𝙜 𝙐𝙣𝙡𝙞𝙢𝙞𝙩𝙚𝙙 𝙍𝙚𝙛𝙞𝙡𝙡🔥', '', '315.568', 10, 5000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Followers -\\u3010 \\ud835\\udfed\\ud835\\udff1+ \\ud835\\udde3\\ud835\\uddfc\\ud835\\ude00\\ud835\\ude01 \\ud835\\udc0e\\ud835\\udc25\\ud835\\udc1d \\ud835\\uddd4\\ud835\\uddf0\\ud835\\uddf0\\ud835\\uddfc\\ud835\\ude02\\ud835\\uddfb\\ud835\\ude01\'s - \\ud835\\udfed\\ud835\\udfec\\ud835\\udfec\\ud835\\uddde+ \\ud835\\udde3\\ud835\\uddf2\\ud835\\uddff \\ud835\\uddd7\\ud835\\uddee\\ud835\\ude06 - 30 Days Refill Button ] - \\ud835\\ude52\\ud835\\ude64\\ud835\\ude67\\ud835\\ude60\\ud835\\ude5e\\ud835\\ude63\\ud835\\ude5c \\ud835\\ude50\\ud835\\ude63\\ud835\\ude61\\ud835\\ude5e\\ud835\\ude62\\ud835\\ude5e\\ud835\\ude69\\ud835\\ude5a\\ud835\\ude59 \\ud835\\ude4d\\ud835\\ude5a\\ud835\\ude5b\\ud835\\ude5e\\ud835\\ude61\\ud835\\ude61\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(40, 1, 13524, '2', '{\"min\":\"10\",\"max\":\"5000000\",\"rate\":\"316.67\",\"currency\":\"INR\"}', 4, 40, '2', '1', '➤ IG » Followers -【 𝟭𝟱+ 𝗣𝗼𝘀𝘁 𝐎𝐥𝐝 𝗔𝗰𝗰𝗼𝘂𝗻𝘁\'s - 𝟭𝟬𝟬𝗞+ 𝗣𝗲𝗿 𝗗𝗮𝘆 - 60 Days Refill Button ] - 𝙒𝙤𝙧𝙠𝙞𝙣𝙜 𝙐𝙣𝙡𝙞𝙢𝙞𝙩𝙚𝙙 𝙍𝙚𝙛𝙞𝙡𝙡🔥', '', '348.337', 10, 5000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Followers -\\u3010 \\ud835\\udfed\\ud835\\udff1+ \\ud835\\udde3\\ud835\\uddfc\\ud835\\ude00\\ud835\\ude01 \\ud835\\udc0e\\ud835\\udc25\\ud835\\udc1d \\ud835\\uddd4\\ud835\\uddf0\\ud835\\uddf0\\ud835\\uddfc\\ud835\\ude02\\ud835\\uddfb\\ud835\\ude01\'s - \\ud835\\udfed\\ud835\\udfec\\ud835\\udfec\\ud835\\uddde+ \\ud835\\udde3\\ud835\\uddf2\\ud835\\uddff \\ud835\\uddd7\\ud835\\uddee\\ud835\\ude06 - 60 Days Refill Button ] - \\ud835\\ude52\\ud835\\ude64\\ud835\\ude67\\ud835\\ude60\\ud835\\ude5e\\ud835\\ude63\\ud835\\ude5c \\ud835\\ude50\\ud835\\ude63\\ud835\\ude61\\ud835\\ude5e\\ud835\\ude62\\ud835\\ude5e\\ud835\\ude69\\ud835\\ude5a\\ud835\\ude59 \\ud835\\ude4d\\ud835\\ude5a\\ud835\\ude5b\\ud835\\ude5e\\ud835\\ude61\\ud835\\ude61\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(41, 1, 16485, '2', '{\"min\":\"10\",\"max\":\"5000000\",\"rate\":\"349.43\",\"currency\":\"INR\"}', 4, 41, '2', '1', '➤ IG » Followers -【 𝟭𝟱+ 𝗣𝗼𝘀𝘁 𝐎𝐥𝐝 𝗔𝗰𝗰𝗼𝘂𝗻𝘁\'s - 𝟭𝟬𝟬𝗞+ 𝗣𝗲𝗿 𝗗𝗮𝘆 - 90 Days Refill Button ] - 𝙒𝙤𝙧𝙠𝙞𝙣𝙜 𝙐𝙣𝙡𝙞𝙢𝙞𝙩𝙚𝙙 𝙍𝙚𝙛𝙞𝙡𝙡🔥', '', '384.373', 10, 5000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Followers -\\u3010 \\ud835\\udfed\\ud835\\udff1+ \\ud835\\udde3\\ud835\\uddfc\\ud835\\ude00\\ud835\\ude01 \\ud835\\udc0e\\ud835\\udc25\\ud835\\udc1d \\ud835\\uddd4\\ud835\\uddf0\\ud835\\uddf0\\ud835\\uddfc\\ud835\\ude02\\ud835\\uddfb\\ud835\\ude01\'s - \\ud835\\udfed\\ud835\\udfec\\ud835\\udfec\\ud835\\uddde+ \\ud835\\udde3\\ud835\\uddf2\\ud835\\uddff \\ud835\\uddd7\\ud835\\uddee\\ud835\\ude06 - 90 Days Refill Button ] - \\ud835\\ude52\\ud835\\ude64\\ud835\\ude67\\ud835\\ude60\\ud835\\ude5e\\ud835\\ude63\\ud835\\ude5c \\ud835\\ude50\\ud835\\ude63\\ud835\\ude61\\ud835\\ude5e\\ud835\\ude62\\ud835\\ude5e\\ud835\\ude69\\ud835\\ude5a\\ud835\\ude59 \\ud835\\ude4d\\ud835\\ude5a\\ud835\\ude5b\\ud835\\ude5e\\ud835\\ude61\\ud835\\ude61\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(42, 1, 16255, '2', '{\"min\":\"10\",\"max\":\"1000000000\",\"rate\":\"179.49\",\"currency\":\"INR\"}', 4, 42, '2', '1', '➤ IG » Followers【 𝐎𝐥𝐝 𝐀𝐜𝐜𝐨𝐮𝐧𝐭\'s - 50K - 100K Day - 𝐍𝐨 𝐃𝐫𝐨𝐩 - 150 Days Refill Button ] - Cancel Button 🚫🔥', '', '197.439', 10, 1000000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u00bb Followers\\u3010 \\ud835\\udc0e\\ud835\\udc25\\ud835\\udc1d \\ud835\\udc00\\ud835\\udc1c\\ud835\\udc1c\\ud835\\udc28\\ud835\\udc2e\\ud835\\udc27\\ud835\\udc2d\'s - 50K - 100K Day - \\ud835\\udc0d\\ud835\\udc28 \\ud835\\udc03\\ud835\\udc2b\\ud835\\udc28\\ud835\\udc29 - 150 Days Refill Button ] - Cancel Button \\ud83d\\udeab\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(43, 1, 14143, '2', '{\"min\":\"100\",\"max\":\"1000000\",\"rate\":\"103.58\",\"currency\":\"INR\"}', 5, 43, '2', '1', '➤ IG Followers【 Less Drop - 5K - 10K+ Per Day - Instant - No Refill ]🩸', '', '113.938', 100, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG Followers\\u3010 Less Drop - 5K - 10K+ Per Day - Instant - No Refill ]\\ud83e\\ude78\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(44, 1, 15561, '2', '{\"min\":\"100\",\"max\":\"1000000\",\"rate\":\"114.43\",\"currency\":\"INR\"}', 5, 44, '2', '1', '➤ IG ≽ Followers【 𝐌𝐢𝐱𝐞𝐝 𝐈𝐧𝐝𝐢𝐚𝐧 - 10K - 20K Day - Less Drop - 0 - 1 Hours - No Refill ]🔥', '', '125.873', 100, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u227d Followers\\u3010 \\ud835\\udc0c\\ud835\\udc22\\ud835\\udc31\\ud835\\udc1e\\ud835\\udc1d \\ud835\\udc08\\ud835\\udc27\\ud835\\udc1d\\ud835\\udc22\\ud835\\udc1a\\ud835\\udc27 - 10K - 20K Day - Less Drop - 0 - 1 Hours - No Refill ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(45, 1, 15370, '2', '{\"min\":\"100\",\"max\":\"1000000\",\"rate\":\"117.39\",\"currency\":\"INR\"}', 5, 45, '2', '1', '➤ IG ≽ Followers【 𝐁𝐨𝐭 𝐀𝐜𝐜𝐨𝐮𝐧𝐭𝐬 - 10K - 30K Day - Less Drop - 0 - 1 Hours - No Refill ]🔥', '', '129.129', 100, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u227d Followers\\u3010 \\ud835\\udc01\\ud835\\udc28\\ud835\\udc2d \\ud835\\udc00\\ud835\\udc1c\\ud835\\udc1c\\ud835\\udc28\\ud835\\udc2e\\ud835\\udc27\\ud835\\udc2d\\ud835\\udc2c - 10K - 30K Day - Less Drop - 0 - 1 Hours - No Refill ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(46, 1, 16868, '2', '{\"min\":\"100\",\"max\":\"1000000\",\"rate\":\"119.36\",\"currency\":\"INR\"}', 5, 46, '2', '1', '➤ IG ≽ Followers【 Mix Quality - 20K - 50K Day - 𝗦𝗺𝗼𝗼𝘁𝗵 - Low Drop - Instant - No Refill ]🔥', '', '131.296', 100, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u227d Followers\\u3010 Mix Quality - 20K - 50K Day - \\ud835\\udde6\\ud835\\uddfa\\ud835\\uddfc\\ud835\\uddfc\\ud835\\ude01\\ud835\\uddf5 - Low Drop - Instant - No Refill ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(47, 1, 15559, '2', '{\"min\":\"100\",\"max\":\"1000000\",\"rate\":\"136.12\",\"currency\":\"INR\"}', 5, 47, '2', '1', '➤ IG ≽ Followers【 𝐌𝐢𝐱𝐞𝐝 𝐐𝐮𝐚𝐥𝐢𝐭𝐲 - 20K - 100K Day - 𝗦𝗺𝗼𝗼𝘁𝗵 - Low Drop - Instant - No Refill ]🔥', '', '149.732', 100, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 IG \\u227d Followers\\u3010 \\ud835\\udc0c\\ud835\\udc22\\ud835\\udc31\\ud835\\udc1e\\ud835\\udc1d \\ud835\\udc10\\ud835\\udc2e\\ud835\\udc1a\\ud835\\udc25\\ud835\\udc22\\ud835\\udc2d\\ud835\\udc32 - 20K - 100K Day - \\ud835\\udde6\\ud835\\uddfa\\ud835\\uddfc\\ud835\\uddfc\\ud835\\ude01\\ud835\\uddf5 - Low Drop - Instant - No Refill ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(48, 1, 15782, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 48, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ 𝐀𝐥𝐥 𝐈𝐧𝐝𝐢𝐚 Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ \\ud835\\udc00\\ud835\\udc25\\ud835\\udc25 \\ud835\\udc08\\ud835\\udc27\\ud835\\udc1d\\ud835\\udc22\\ud835\\udc1a Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(49, 1, 15783, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 49, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~  Assam Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~  Assam Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(50, 1, 15784, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 50, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Haryana / Punjab Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Haryana \\/ Punjab Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(51, 1, 15247, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 51, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Delhi Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Delhi Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(52, 1, 15785, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 52, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Uttar Pradesh Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Uttar Pradesh Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(53, 1, 15786, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 53, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Andhra Pradesh Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Andhra Pradesh Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(54, 1, 15787, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 54, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Arunachal Pradesh Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Arunachal Pradesh Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(55, 1, 15788, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 55, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Bihar Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Bihar Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(56, 1, 15789, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 56, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Chandigarh Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Chandigarh Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(57, 1, 15790, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 57, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Chhattisgarh Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Chhattisgarh Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(58, 1, 15791, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 58, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Goa + Maharashtra Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Goa + Maharashtra Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(59, 1, 15792, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 59, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Gujrat Real Target 🎯 Views | 🪄 100% Real Audience | 💨 1K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Gujrat Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 1K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(60, 1, 15793, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 60, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Himachal Pradesh Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Himachal Pradesh Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(61, 1, 15794, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 61, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Jharkhand Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Jharkhand Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(62, 1, 15795, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 62, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Karnataka Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Karnataka Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(63, 1, 15796, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 63, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Kerala Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Kerala Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0');
INSERT INTO `services` (`service_id`, `service_api`, `api_service`, `api_servicetype`, `api_detail`, `category_id`, `service_line`, `service_type`, `service_package`, `service_name`, `service_description`, `service_price`, `service_min`, `service_max`, `service_dripfeed`, `service_autotime`, `service_autopost`, `service_speed`, `want_username`, `service_secret`, `price_type`, `price_cal`, `instagram_second`, `start_count`, `instagram_private`, `name_lang`, `description_lang`, `time_lang`, `time`, `cancelbutton`, `show_refill`, `service_profit`, `refill_days`, `refill_hours`, `avg_days`, `avg_hours`, `avg_minutes`, `avg_many`, `price_profit`, `service_overflow`, `service_sync`, `service_deleted`) VALUES
(64, 1, 15797, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 64, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Madhya Pradesh Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Madhya Pradesh Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(65, 1, 15798, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 65, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Manipur Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Manipur Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(66, 1, 15799, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 66, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Meghalaya Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Meghalaya Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(67, 1, 15800, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 67, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Mizoram Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Mizoram Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(68, 1, 15801, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 68, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Nagaland Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Nagaland Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(69, 1, 15802, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 69, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Odisha Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Odisha Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(70, 1, 15803, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 70, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Sikkim Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Sikkim Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(71, 1, 15804, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 71, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Tamil Nadu Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Tamil Nadu Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(72, 1, 15805, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 72, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Telangana Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Telangana Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(73, 1, 15806, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 73, '2', '1', '▶  𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Tripura Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6  \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Tripura Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(74, 1, 15807, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 74, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ Uttarakhand Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ Uttarakhand Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(75, 1, 15808, '2', '{\"min\":\"100\",\"max\":\"300000\",\"rate\":\"254.87\",\"currency\":\"INR\"}', 6, 75, '2', '1', '▶ 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ~ West Bengal Real Target 🎯 Views | 🪄 100% Real Audience | 💨 10K Per Day | 📊 High Monetization', '', '280.357', 100, 300000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u25b6 \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 ~ West Bengal Real Target \\ud83c\\udfaf Views | \\ud83e\\ude84 100% Real Audience | \\ud83d\\udca8 10K Per Day | \\ud83d\\udcca High Monetization\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(76, 1, 16309, '2', '{\"min\":\"15000\",\"max\":\"3000000\",\"rate\":\"134.66\",\"currency\":\"INR\"}', 7, 76, '2', '1', '⭕📽 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ↪ 𝙑𝙄𝙀𝙒 | 📊 Monetization | 💲 0.30-1$ CPM | ⏩ Speed +1K | ✅ 30Days Refill', '', '148.126', 15000, 3000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2b55\\ud83d\\udcfd \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 \\u21aa \\ud835\\ude51\\ud835\\ude44\\ud835\\ude40\\ud835\\ude52 | \\ud83d\\udcca Monetization | \\ud83d\\udcb2 0.30-1$ CPM | \\u23e9 Speed +1K | \\u2705 30Days Refill\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(77, 1, 16310, '2', '{\"min\":\"3000\",\"max\":\"500000000\",\"rate\":\"329.96\",\"currency\":\"INR\"}', 7, 77, '2', '1', '⭕📽 𝙔𝙊𝙐𝙏𝙐𝘽𝙀 ↪ 𝙑𝙄𝙀𝙒 | 📊 Monetization | 💲 2-4$ CPM | ⏩ Speed +1K | ✅ No Refill', '', '362.956', 3000, 500000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2b55\\ud83d\\udcfd \\ud835\\ude54\\ud835\\ude4a\\ud835\\ude50\\ud835\\ude4f\\ud835\\ude50\\ud835\\ude3d\\ud835\\ude40 \\u21aa \\ud835\\ude51\\ud835\\ude44\\ud835\\ude40\\ud835\\ude52 | \\ud83d\\udcca Monetization | \\ud83d\\udcb2 2-4$ CPM | \\u23e9 Speed +1K | \\u2705 No Refill\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(78, 1, 17496, '2', '{\"min\":\"10\",\"max\":\"20000\",\"rate\":\"93.15\",\"currency\":\"INR\"}', 9, 78, '2', '1', '➤  FB Live Stream Views - [ 15 Minutes ] - [ Working ]🔥', '', '102.465', 10, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4  FB Live Stream Views - [ 15 Minutes ] - [ Working ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(79, 1, 17497, '2', '{\"min\":\"10\",\"max\":\"20000\",\"rate\":\"186.29\",\"currency\":\"INR\"}', 9, 79, '2', '1', '➤ FB Live Stream Views - [ 30 Minutes ] - [ Working ]🔥', '', '204.919', 10, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 FB Live Stream Views - [ 30 Minutes ] - [ Working ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(80, 1, 17498, '2', '{\"min\":\"10\",\"max\":\"20000\",\"rate\":\"372.57\",\"currency\":\"INR\"}', 9, 80, '2', '1', '➤ FB Live Stream Views - [ 60 Minutes ] - [ Working ]🔥', '', '409.827', 10, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 FB Live Stream Views - [ 60 Minutes ] - [ Working ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(81, 1, 17499, '2', '{\"min\":\"10\",\"max\":\"20000\",\"rate\":\"558.85\",\"currency\":\"INR\"}', 9, 81, '2', '1', '➤ FB Live Stream Views - [ 90 Minutes ] - [ Working ]🔥', '', '614.735', 10, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 FB Live Stream Views - [ 90 Minutes ] - [ Working ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(82, 1, 17500, '2', '{\"min\":\"10\",\"max\":\"20000\",\"rate\":\"745.13\",\"currency\":\"INR\"}', 9, 82, '2', '1', '➤ FB Live Stream Views - [ 120 Minutes ] - [ Working ]🔥', '', '819.643', 10, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 FB Live Stream Views - [ 120 Minutes ] - [ Working ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(83, 1, 17501, '2', '{\"min\":\"10\",\"max\":\"20000\",\"rate\":\"1117.70\",\"currency\":\"INR\"}', 9, 83, '2', '1', '➤ FB Live Stream Views - [ 180 Minutes ] - [ Working ]🔥', '', '1229.47', 10, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 FB Live Stream Views - [ 180 Minutes ] - [ Working ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(84, 1, 17502, '2', '{\"min\":\"10\",\"max\":\"20000\",\"rate\":\"1490.26\",\"currency\":\"INR\"}', 9, 84, '2', '1', '➤ FB Live Stream Views - [ 240 Minutes ] - [ Working ]🔥', '', '1639.286', 10, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 FB Live Stream Views - [ 240 Minutes ] - [ Working ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(85, 1, 17503, '2', '{\"min\":\"10\",\"max\":\"20000\",\"rate\":\"2235.39\",\"currency\":\"INR\"}', 9, 85, '2', '1', '➤ FB Live Stream Views - [ 360 Minutes ] - [ Working ]🔥', '', '2458.929', 10, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 FB Live Stream Views - [ 360 Minutes ] - [ Working ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(86, 1, 17504, '2', '{\"min\":\"10\",\"max\":\"20000\",\"rate\":\"5278.99\",\"currency\":\"INR\"}', 9, 86, '2', '1', '➤ FB Live Stream Views - [ 12 Hours ] - [ Working ]🔥', '', '5806.889', 10, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 FB Live Stream Views - [ 12 Hours ] - [ Working ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(87, 1, 17505, '2', '{\"min\":\"10\",\"max\":\"20000\",\"rate\":\"10557.98\",\"currency\":\"INR\"}', 9, 87, '2', '1', '➤ FB Live Stream Views - [ 24 Hours ] - [ Working ]🔥', '', '11613.778', 10, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 FB Live Stream Views - [ 24 Hours ] - [ Working ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(88, 1, 16726, '2', '{\"min\":\"100\",\"max\":\"500000\",\"rate\":\"42.88\",\"currency\":\"INR\"}', 11, 88, '2', '1', '➤ Twitter Followers【 10K - 50K+ Per Day - Instant - No Refill ] - Cheapest🔥', '', '47.168', 100, 500000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers\\u3010 10K - 50K+ Per Day - Instant - No Refill ] - Cheapest\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(89, 1, 15917, '2', '{\"min\":\"10\",\"max\":\"100000\",\"rate\":\"45.15\",\"currency\":\"INR\"}', 11, 89, '2', '1', '❂ Twitter Followers, Mix Quality【 5K -10K+ Per Day - 0 - 2 Hrs ] 🔥', '', '49.665', 10, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2742 Twitter Followers, Mix Quality\\u3010 5K -10K+ Per Day - 0 - 2 Hrs ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(90, 1, 13041, '2', '{\"min\":\"100\",\"max\":\"1000000\",\"rate\":\"70.28\",\"currency\":\"INR\"}', 11, 90, '2', '1', '➤ Twitter Followers - Real Quality -【 10K - 50K Per Day - Instant - 30 Days Refill Button ] - #2🔥', '', '77.308', 100, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers - Real Quality -\\u3010 10K - 50K Per Day - Instant - 30 Days Refill Button ] - #2\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(91, 1, 16720, '2', '{\"min\":\"10\",\"max\":\"100000\",\"rate\":\"54.55\",\"currency\":\"INR\"}', 11, 91, '2', '1', '➤ Twitter Followers【 3 Days Refill + 10K - 100K Per Day【 [0-24Hrs] 🔥', '', '60.005', 10, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers\\u3010 3 Days Refill + 10K - 100K Per Day\\u3010 [0-24Hrs] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(92, 1, 16834, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"380.30\",\"currency\":\"INR\"}', 11, 92, '2', '1', '❂ Twitter Followers, Nice Quality【 30K - 50K+ Per Day - [ 0 - 12 Hrs ] 🔥', '', '418.33', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2742 Twitter Followers, Nice Quality\\u3010 30K - 50K+ Per Day - [ 0 - 12 Hrs ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(93, 1, 16719, '2', '{\"min\":\"100\",\"max\":\"50000\",\"rate\":\"402.24\",\"currency\":\"INR\"}', 11, 93, '2', '1', '❂ Twitter Followers, Low Quality【 10K -20K+ Per Day - Super Instant ] 🔥', '', '442.464', 100, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2742 Twitter Followers, Low Quality\\u3010 10K -20K+ Per Day - Super Instant ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(94, 1, 16833, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"501.29\",\"currency\":\"INR\"}', 11, 94, '2', '1', '❂ Twitter Followers【 1K - 5K+ Per Day - [ Instant - Cancel Enabled - Refill 15 Days ] 🔥', '', '551.419', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2742 Twitter Followers\\u3010 1K - 5K+ Per Day - [ Instant - Cancel Enabled - Refill 15 Days ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(95, 1, 16729, '2', '{\"min\":\"10\",\"max\":\"1000000\",\"rate\":\"118.63\",\"currency\":\"INR\"}', 11, 95, '2', '1', '➤ Twitter Real Followers【 30 Days Refill - 20K - 50K+ Per Day - Instant ]🔥', '', '130.493', 10, 1000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Real Followers\\u3010 30 Days Refill - 20K - 50K+ Per Day - Instant ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(96, 1, 13059, '2', '{\"min\":\"100\",\"max\":\"500000\",\"rate\":\"448.48\",\"currency\":\"INR\"}', 11, 96, '2', '1', '❂ Twitter Followers -【 10K - 20K Per Day - 15 Days Refill - Cancel Enabled ] - HQ Accounts🔥', '', '493.328', 100, 500000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2742 Twitter Followers -\\u3010 10K - 20K Per Day - 15 Days Refill - Cancel Enabled ] - HQ Accounts\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(97, 1, 13043, '2', '{\"min\":\"10\",\"max\":\"10000000\",\"rate\":\"356.66\",\"currency\":\"INR\"}', 11, 97, '2', '1', '➤ Twitter » Followers【 Good Quality - 1K - 2K+ Per Day - Instant - No Refill ] - Cheapest🔥', '', '392.326', 10, 10000000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter \\u00bb Followers\\u3010 Good Quality - 1K - 2K+ Per Day - Instant - No Refill ] - Cheapest\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(98, 1, 16832, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"521.01\",\"currency\":\"INR\"}', 11, 98, '2', '1', '❂ Twitter Followers, 𝗡𝗙𝗧【 50K - 200K+ Per Day - [ Instant - 30 Days Refill ] 🔥', '', '573.111', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2742 Twitter Followers, \\ud835\\udde1\\ud835\\uddd9\\ud835\\udde7\\u3010 50K - 200K+ Per Day - [ Instant - 30 Days Refill ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(99, 1, 16721, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"502.37\",\"currency\":\"INR\"}', 11, 99, '2', '1', '❂ Twitter Followers【 5K - 10K+ Per Day - [ Instant - Refill 30 Days ] 🔥', '', '552.607', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2742 Twitter Followers\\u3010 5K - 10K+ Per Day - [ Instant - Refill 30 Days ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(100, 1, 13077, '2', '{\"min\":\"100\",\"max\":\"500000\",\"rate\":\"508.19\",\"currency\":\"INR\"}', 11, 100, '2', '1', '❂ Twitter Followers -【 10K - 20K Per Day - 30 Days Refill - Cancel Enabled ] - HQ Accounts🔥', '', '559.009', 100, 500000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2742 Twitter Followers -\\u3010 10K - 20K Per Day - 30 Days Refill - Cancel Enabled ] - HQ Accounts\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(101, 1, 13055, '2', '{\"min\":\"50\",\"max\":\"10000\",\"rate\":\"3102.00\",\"currency\":\"INR\"}', 11, 101, '2', '1', '➤ Twitter Followers - Good Quality -【 50K - 100K Per Day - Instant - Lifetime Refill ]🔥', '', '3412.2', 50, 10000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers - Good Quality -\\u3010 50K - 100K Per Day - Instant - Lifetime Refill ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(102, 1, 16722, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"525.74\",\"currency\":\"INR\"}', 11, 102, '2', '1', '❂ Twitter Followers, Good Quality【 1K - 5K+ Per Day - [ 0 - 1 Hrs - Refill 30 Days ] 🔥', '', '578.314', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2742 Twitter Followers, Good Quality\\u3010 1K - 5K+ Per Day - [ 0 - 1 Hrs - Refill 30 Days ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(103, 1, 14370, '2', '{\"min\":\"100\",\"max\":\"50000\",\"rate\":\"568.54\",\"currency\":\"INR\"}', 11, 103, '2', '1', '➤ Twitter Followers【 Good Quality - 2K - 10K+ Per Day - Instant - 30 Days Refill ]🔥', '', '625.394', 100, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers\\u3010 Good Quality - 2K - 10K+ Per Day - Instant - 30 Days Refill ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(104, 1, 14551, '2', '{\"min\":\"100\",\"max\":\"50000\",\"rate\":\"594.38\",\"currency\":\"INR\"}', 11, 104, '2', '1', '➤ Twitter Followers【 50K - 100K+ Per Day - 30 Days Refill with Button - Low Drop ]🔥', '', '653.818', 100, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers\\u3010 50K - 100K+ Per Day - 30 Days Refill with Button - Low Drop ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(105, 1, 16717, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"438.80\",\"currency\":\"INR\"}', 11, 105, '2', '1', '❂ Twitter Followers, Nice Quality【 10K - 100K+ Per Day - [ Instant ] 🔥', '', '482.68', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u2742 Twitter Followers, Nice Quality\\u3010 10K - 100K+ Per Day - [ Instant ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(106, 1, 14550, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"573.71\",\"currency\":\"INR\"}', 11, 106, '2', '1', '➤ Twitter Followers, Old Accounts【 No Refill + 10K - 50K Per Day【 [0-1Hrs] 🔥', '', '631.081', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers, Old Accounts\\u3010 No Refill + 10K - 50K Per Day\\u3010 [0-1Hrs] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(107, 1, 16725, '2', '{\"min\":\"50\",\"max\":\"50000\",\"rate\":\"549.26\",\"currency\":\"INR\"}', 11, 107, '2', '1', '➤ Twitter Followers, Thialand【 Real Quality【 5K - 10K+ Per Day + 30Days Refill 🔥', '', '604.186', 50, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers, Thialand\\u3010 Real Quality\\u3010 5K - 10K+ Per Day + 30Days Refill \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(108, 1, 16731, '2', '{\"min\":\"100\",\"max\":\"10000\",\"rate\":\"549.26\",\"currency\":\"INR\"}', 11, 108, '2', '1', '➤ Twitter Followers, Indian【 Real Quality【 2K - 10K+ Per Day + 30Days Refill 🔥', '', '604.186', 100, 10000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers, Indian\\u3010 Real Quality\\u3010 2K - 10K+ Per Day + 30Days Refill \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(109, 1, 16723, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"549.26\",\"currency\":\"INR\"}', 11, 109, '2', '1', '➤ Twitter Turkish Followers [Real] [30 Days Refill] [5-10K/D] [0-1/H]', '', '604.186', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Turkish Followers [Real] [30 Days Refill] [5-10K\\/D] [0-1\\/H]\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(110, 1, 14524, '2', '{\"min\":\"100\",\"max\":\"50000\",\"rate\":\"529.92\",\"currency\":\"INR\"}', 11, 110, '2', '1', '➤ Twitter Followers【 High Quality + [0-1/Hrs]【 500 - 2K Per Day + 30Days Refill 🔥', '', '582.912', 100, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers\\u3010 High Quality + [0-1\\/Hrs]\\u3010 500 - 2K Per Day + 30Days Refill \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(111, 1, 15492, '2', '{\"min\":\"100\",\"max\":\"50000\",\"rate\":\"529.92\",\"currency\":\"INR\"}', 11, 111, '2', '1', '🔘 Twitter Follower |🔥 Real |💧 Low Drop |♻ 30 Days Refill Button |⚡ 500-1000/D |⏳ 0-1/H', '', '582.912', 100, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\ud83d\\udd18 Twitter Follower |\\ud83d\\udd25 Real |\\ud83d\\udca7 Low Drop |\\u267b 30 Days Refill Button |\\u26a1 500-1000\\/D |\\u23f3 0-1\\/H\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(112, 1, 16728, '2', '{\"min\":\"50\",\"max\":\"10000\",\"rate\":\"423.23\",\"currency\":\"INR\"}', 11, 112, '2', '1', '➤ Twitter Followers, USA【 Real Quality【 5K - 10K+ Per Day + 30Days Refill 🔥', '', '465.553', 50, 10000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers, USA\\u3010 Real Quality\\u3010 5K - 10K+ Per Day + 30Days Refill \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(113, 1, 15391, '2', '{\"min\":\"50\",\"max\":\"30000\",\"rate\":\"421.35\",\"currency\":\"INR\"}', 11, 113, '2', '1', '➤ Twitter Followers【 Real Quality【 2K - 10K+ Per Day + Works For All 🔥', '', '463.485', 50, 30000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Twitter Followers\\u3010 Real Quality\\u3010 2K - 10K+ Per Day + Works For All \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(114, 1, 16727, '2', '{\"min\":\"10\",\"max\":\"100000\",\"rate\":\"1118.43\",\"currency\":\"INR\"}', 11, 114, '2', '1', 'Twitter Followers [Real] [30 Days Refill] [50-100K/D] [0-1/H]', '', '1230.273', 10, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"Twitter Followers [Real] [30 Days Refill] [50-100K\\/D] [0-1\\/H]\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(115, 1, 16086, '2', '{\"min\":\"1\",\"max\":\"1\",\"rate\":\"1000000.00\",\"currency\":\"INR\"}', 12, 115, '2', '2', '❌ 𝗧𝗜𝗞𝗧𝗢𝗞 𝗦𝗘𝗥𝗩𝗜𝗖𝗘𝗦 ❌', '', '1100000', 1, 1, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u274c \\ud835\\udde7\\ud835\\udddc\\ud835\\uddde\\ud835\\udde7\\ud835\\udde2\\ud835\\uddde \\ud835\\udde6\\ud835\\uddd8\\ud835\\udde5\\ud835\\udde9\\ud835\\udddc\\ud835\\uddd6\\ud835\\uddd8\\ud835\\udde6 \\u274c\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(116, 1, 14802, '2', '{\"min\":\"50\",\"max\":\"50000\",\"rate\":\"261.50\",\"currency\":\"INR\"}', 13, 116, '2', '1', '➤ Tiktok » Live Steram Views [ Valid For All Streams ] [ 15 Minutes ] [ Stable - Instant ]🔥', '', '287.65', 50, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Tiktok \\u00bb Live Steram Views [ Valid For All Streams ] [ 15 Minutes ] [ Stable - Instant ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(117, 1, 14803, '2', '{\"min\":\"50\",\"max\":\"50000\",\"rate\":\"523.00\",\"currency\":\"INR\"}', 13, 117, '2', '1', '➤ Tiktok » Live Steram Views [ Valid For All Streams ] [ 30 Minutes ] [ Stable - Instant ]🔥', '', '575.3', 50, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Tiktok \\u00bb Live Steram Views [ Valid For All Streams ] [ 30 Minutes ] [ Stable - Instant ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(118, 1, 14804, '2', '{\"min\":\"50\",\"max\":\"50000\",\"rate\":\"1050.66\",\"currency\":\"INR\"}', 13, 118, '2', '1', '➤ Tiktok » Live Steram Views [ Valid For All Streams ] [ 60 Minutes ] [ Stable - Instant ]🔥', '', '1155.726', 50, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Tiktok \\u00bb Live Steram Views [ Valid For All Streams ] [ 60 Minutes ] [ Stable - Instant ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(119, 1, 15425, '2', '{\"min\":\"100\",\"max\":\"15000\",\"rate\":\"30.21\",\"currency\":\"INR\"}', 14, 119, '2', '1', '📌 Telegram Members【 3 Days Auto Refill【 30K/Day + Instant 🔥', '', '33.231', 100, 15000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\ud83d\\udccc Telegram Members\\u3010 3 Days Auto Refill\\u3010 30K\\/Day + Instant \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(120, 1, 15426, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"36.06\",\"currency\":\"INR\"}', 14, 120, '2', '1', '📌 Telegram Members【 7 Days Auto Refill【 30K/Day + Instant 🔥', '', '39.666', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\ud83d\\udccc Telegram Members\\u3010 7 Days Auto Refill\\u3010 30K\\/Day + Instant \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(121, 1, 15423, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"44.83\",\"currency\":\"INR\"}', 14, 121, '2', '1', '📌 Telegram Members【 14 Days Auto Refill【 30K/Day + Instant 🔥', '', '49.313', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\ud83d\\udccc Telegram Members\\u3010 14 Days Auto Refill\\u3010 30K\\/Day + Instant \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(122, 1, 15436, '2', '{\"min\":\"100\",\"max\":\"50000\",\"rate\":\"65.25\",\"currency\":\"INR\"}', 14, 122, '2', '1', '➤ Telegram Members【 20K - 50K Day, 𝗛𝗶𝗴𝗵 𝗦𝗽𝗲𝗲𝗱【 𝗔𝗹𝗹 𝗖𝗵𝗮𝗻𝗻𝗲𝗹𝘀 𝗔𝗰𝗰𝗲𝗽𝘁𝗲𝗱 - Refill 30 Days ]🔥', '', '71.775', 100, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Members\\u3010 20K - 50K Day, \\ud835\\udddb\\ud835\\uddf6\\ud835\\uddf4\\ud835\\uddf5 \\ud835\\udde6\\ud835\\uddfd\\ud835\\uddf2\\ud835\\uddf2\\ud835\\uddf1\\u3010 \\ud835\\uddd4\\ud835\\uddf9\\ud835\\uddf9 \\ud835\\uddd6\\ud835\\uddf5\\ud835\\uddee\\ud835\\uddfb\\ud835\\uddfb\\ud835\\uddf2\\ud835\\uddf9\\ud835\\ude00 \\ud835\\uddd4\\ud835\\uddf0\\ud835\\uddf0\\ud835\\uddf2\\ud835\\uddfd\\ud835\\ude01\\ud835\\uddf2\\ud835\\uddf1 - Refill 30 Days ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(123, 1, 15428, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"68.04\",\"currency\":\"INR\"}', 14, 123, '2', '1', '➤ Telegram Members【 30 Days - 𝗡𝗼 𝗗𝗿𝗼𝗽 - 𝗔𝘂𝘁𝗼 𝗥𝗲𝗳𝗶𝗹𝗹【 30K/Day + Instant ] 🔥', '', '74.844', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Members\\u3010 30 Days - \\ud835\\udde1\\ud835\\uddfc \\ud835\\uddd7\\ud835\\uddff\\ud835\\uddfc\\ud835\\uddfd - \\ud835\\uddd4\\ud835\\ude02\\ud835\\ude01\\ud835\\uddfc \\ud835\\udde5\\ud835\\uddf2\\ud835\\uddf3\\ud835\\uddf6\\ud835\\uddf9\\ud835\\uddf9\\u3010 30K\\/Day + Instant ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(124, 1, 15490, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"69.00\",\"currency\":\"INR\"}', 14, 124, '2', '1', '🔘 Telegram Real Member |🔥 Real |💧 No Drop |⚡ 20K - 50K/Day |⏳ 0-1/H', '', '75.9', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\ud83d\\udd18 Telegram Real Member |\\ud83d\\udd25 Real |\\ud83d\\udca7 No Drop |\\u26a1 20K - 50K\\/Day |\\u23f3 0-1\\/H\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'true', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(125, 1, 13568, '2', '{\"min\":\"10\",\"max\":\"100000\",\"rate\":\"79.23\",\"currency\":\"INR\"}', 14, 125, '2', '1', '➤ Telegram Group Members【 500 - 1K Per Day + 0 - 1 Hours ] 🔥', '', '87.153', 10, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Group Members\\u3010 500 - 1K Per Day + 0 - 1 Hours ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(126, 1, 15429, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"87.70\",\"currency\":\"INR\"}', 14, 126, '2', '1', '📌 Telegram No Drop Members【 English Names【 30 Days Auto Refill【 10K/Day + Instant 🔥', '', '96.47', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\ud83d\\udccc Telegram No Drop Members\\u3010 English Names\\u3010 30 Days Auto Refill\\u3010 10K\\/Day + Instant \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(127, 1, 15427, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"95.50\",\"currency\":\"INR\"}', 14, 127, '2', '1', '📌 Telegram Members【 60 Days - No Drop - 30K/Day + Instant ]🔥', '', '105.05', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\ud83d\\udccc Telegram Members\\u3010 60 Days - No Drop - 30K\\/Day + Instant ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(128, 1, 15430, '2', '{\"min\":\"100\",\"max\":\"500000\",\"rate\":\"102.32\",\"currency\":\"INR\"}', 14, 128, '2', '1', '📌 Telegram Members【 Refill for 100% Value【 20K/Day + Instant 🔥', '', '112.552', 100, 500000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\ud83d\\udccc Telegram Members\\u3010 Refill for 100% Value\\u3010 20K\\/Day + Instant \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(129, 1, 13569, '2', '{\"min\":\"20\",\"max\":\"100000\",\"rate\":\"107.19\",\"currency\":\"INR\"}', 14, 129, '2', '1', '➤ Telegram Group Members【 500 - 1K Per Day + 0 - 1 Hours - Refill 30 Days ] 🔥', '', '117.909', 20, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Group Members\\u3010 500 - 1K Per Day + 0 - 1 Hours - Refill 30 Days ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(130, 1, 15434, '2', '{\"min\":\"100\",\"max\":\"30000\",\"rate\":\"58.47\",\"currency\":\"INR\"}', 15, 130, '2', '1', '➤ Telegram Mix Members + High Quality + Low Drop【 5K - 10K Per Day【 Instant 🔥', '', '64.317', 100, 30000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Mix Members + High Quality + Low Drop\\u3010 5K - 10K Per Day\\u3010 Instant \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(131, 1, 16419, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"69.99\",\"currency\":\"INR\"}', 15, 131, '2', '1', '➤ Telegram, 𝐖𝐨𝐫𝐤 𝐟𝐨𝐫 𝐈𝐥𝐥𝐞𝐠𝐚𝐥 𝐂𝐡𝐚𝐧𝐧𝐞𝐥𝐬! + HQ Members]【 50K+/Day【 Instant 🔥', '', '76.989', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram, \\ud835\\udc16\\ud835\\udc28\\ud835\\udc2b\\ud835\\udc24 \\ud835\\udc1f\\ud835\\udc28\\ud835\\udc2b \\ud835\\udc08\\ud835\\udc25\\ud835\\udc25\\ud835\\udc1e\\ud835\\udc20\\ud835\\udc1a\\ud835\\udc25 \\ud835\\udc02\\ud835\\udc21\\ud835\\udc1a\\ud835\\udc27\\ud835\\udc27\\ud835\\udc1e\\ud835\\udc25\\ud835\\udc2c! + HQ Members]\\u3010 50K+\\/Day\\u3010 Instant \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(132, 1, 16416, '2', '{\"min\":\"100\",\"max\":\"100000\",\"rate\":\"90.10\",\"currency\":\"INR\"}', 15, 132, '2', '1', '➤ Telegram Members【 30K - 50K Day - 90 Dyas Guaranteed -  No Drop - Instant ] 🔥', '', '99.11', 100, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Members\\u3010 30K - 50K Day - 90 Dyas Guaranteed -  No Drop - Instant ] \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(133, 1, 15567, '2', '{\"min\":\"500\",\"max\":\"100000\",\"rate\":\"88.91\",\"currency\":\"INR\"}', 15, 133, '2', '1', '➤ Telegram » 𝗡𝗼 - 𝗗𝗿𝗼𝗽 Members【 English Members - 50K - 100K+ Per Day - Instant ]🔥🚀', '', '97.801', 500, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram \\u00bb \\ud835\\udde1\\ud835\\uddfc - \\ud835\\uddd7\\ud835\\uddff\\ud835\\uddfc\\ud835\\uddfd Members\\u3010 English Members - 50K - 100K+ Per Day - Instant ]\\ud83d\\udd25\\ud83d\\ude80\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(134, 1, 16413, '2', '{\"min\":\"100\",\"max\":\"50000\",\"rate\":\"89.99\",\"currency\":\"INR\"}', 15, 134, '2', '1', '➤ Telegram Eng Member\'s【 No Drop + 10K - 20K Per Day【 Instant + 90 Days Refill🔥', '', '98.989', 100, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Eng Member\'s\\u3010 No Drop + 10K - 20K Per Day\\u3010 Instant + 90 Days Refill\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(135, 1, 15578, '2', '{\"min\":\"100\",\"max\":\"40000\",\"rate\":\"91.51\",\"currency\":\"INR\"}', 15, 135, '2', '1', '➤ Telegram 𝟓% 𝐃𝐫𝐨𝐩! + HQ Members]【 10K+/Day【 Instant 🔥', '', '100.661', 100, 40000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram \\ud835\\udfd3% \\ud835\\udc03\\ud835\\udc2b\\ud835\\udc28\\ud835\\udc29! + HQ Members]\\u3010 10K+\\/Day\\u3010 Instant \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(136, 1, 16421, '2', '{\"min\":\"100\",\"max\":\"50000\",\"rate\":\"92.57\",\"currency\":\"INR\"}', 15, 136, '2', '1', '➤ Telegram Real, Followers, Big Channel【 5% Drop + Cancel Enabled【 1K - 5K/Day【 0-1/Hrs 💧', '', '101.827', 100, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Real, Followers, Big Channel\\u3010 5% Drop + Cancel Enabled\\u3010 1K - 5K\\/Day\\u3010 0-1\\/Hrs \\ud83d\\udca7\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(137, 1, 15431, '2', '{\"min\":\"50\",\"max\":\"25000\",\"rate\":\"95.58\",\"currency\":\"INR\"}', 15, 137, '2', '1', '➤ Telegram Real, Followers, English Names【 No Drop + 10K - 20K Per Day【 0-1/Hrs 🔥', '', '105.138', 50, 25000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Real, Followers, English Names\\u3010 No Drop + 10K - 20K Per Day\\u3010 0-1\\/Hrs \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(138, 1, 16394, '2', '{\"min\":\"100\",\"max\":\"30000\",\"rate\":\"99.99\",\"currency\":\"INR\"}', 15, 138, '2', '1', '➤ Telegram 𝐍𝐨 𝐃𝐫𝐨𝐩! + HQ [English Members]【 10K+ Day - Instant - New Base ]🔥', '', '109.989', 100, 30000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram \\ud835\\udc0d\\ud835\\udc28 \\ud835\\udc03\\ud835\\udc2b\\ud835\\udc28\\ud835\\udc29! + HQ [English Members]\\u3010 10K+ Day - Instant - New Base ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(139, 1, 16883, '2', '{\"min\":\"100\",\"max\":\"50000\",\"rate\":\"107.19\",\"currency\":\"INR\"}', 15, 139, '2', '1', '➤ Telegram Members, 𝗥𝘂𝘀𝘀𝗶𝗮𝗻 𝗠𝗲𝗺𝗯𝗲𝗿【 50K Per Day - 𝗭𝗲𝗿𝗼𝗗𝗿𝗼𝗽 - Instant - 90 Days Refill ]💧', '', '117.909', 100, 50000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Members, \\ud835\\udde5\\ud835\\ude02\\ud835\\ude00\\ud835\\ude00\\ud835\\uddf6\\ud835\\uddee\\ud835\\uddfb \\ud835\\udde0\\ud835\\uddf2\\ud835\\uddfa\\ud835\\uddef\\ud835\\uddf2\\ud835\\uddff\\u3010 50K Per Day - \\ud835\\udded\\ud835\\uddf2\\ud835\\uddff\\ud835\\uddfc\\ud835\\uddd7\\ud835\\uddff\\ud835\\uddfc\\ud835\\uddfd - Instant - 90 Days Refill ]\\ud83d\\udca7\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(140, 1, 16417, '2', '{\"min\":\"100\",\"max\":\"20000\",\"rate\":\"116.93\",\"currency\":\"INR\"}', 15, 140, '2', '1', '➤ TG No Drop Members【 Real Indian + Russian【 15K - 30K/Day【 Instant 🔥', '', '128.623', 100, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 TG No Drop Members\\u3010 Real Indian + Russian\\u3010 15K - 30K\\/Day\\u3010 Instant \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(141, 1, 16473, '2', '{\"min\":\"100\",\"max\":\"30000\",\"rate\":\"123.03\",\"currency\":\"INR\"}', 15, 141, '2', '1', '➤ TG No Drop Members [𝐑𝐚𝐧𝐤𝐞𝐝]【 English, High Quality【 High Speed, +20K/Day【 Instant💧', '', '135.333', 100, 30000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 TG No Drop Members [\\ud835\\udc11\\ud835\\udc1a\\ud835\\udc27\\ud835\\udc24\\ud835\\udc1e\\ud835\\udc1d]\\u3010 English, High Quality\\u3010 High Speed, +20K\\/Day\\u3010 Instant\\ud83d\\udca7\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(142, 1, 16420, '2', '{\"min\":\"500\",\"max\":\"100000\",\"rate\":\"129.54\",\"currency\":\"INR\"}', 15, 142, '2', '1', '➤ Telegram 𝐍𝐨 𝐃𝐫𝐨𝐩! + HQ Members]【 100K+ Day - Instant - 3 Months Guarmateed🔥]', '', '142.494', 500, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram \\ud835\\udc0d\\ud835\\udc28 \\ud835\\udc03\\ud835\\udc2b\\ud835\\udc28\\ud835\\udc29! + HQ Members]\\u3010 100K+ Day - Instant - 3 Months Guarmateed\\ud83d\\udd25]\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(143, 1, 16227, '2', '{\"min\":\"100\",\"max\":\"20000\",\"rate\":\"131.98\",\"currency\":\"INR\"}', 15, 143, '2', '1', '➤ Telegram Real Members【 𝐍𝐨 𝐃𝐫𝐨𝐩! + HQ Members【 15K - 30K Per Day【 Instant 🔥', '', '145.178', 100, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Real Members\\u3010 \\ud835\\udc0d\\ud835\\udc28 \\ud835\\udc03\\ud835\\udc2b\\ud835\\udc28\\ud835\\udc29! + HQ Members\\u3010 15K - 30K Per Day\\u3010 Instant \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(144, 1, 16882, '2', '{\"min\":\"500\",\"max\":\"100000\",\"rate\":\"132.35\",\"currency\":\"INR\"}', 15, 144, '2', '1', '➤ Telegram Members, 𝗘𝗻𝗴𝗹𝗶𝘀𝗵 𝗠𝗲𝗺𝗯𝗲𝗿【 50K Per Day - 𝗡𝗼 𝗗𝗿𝗼𝗽 - Instant - 90 Days Refill ]💧', '', '145.585', 500, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Members, \\ud835\\uddd8\\ud835\\uddfb\\ud835\\uddf4\\ud835\\uddf9\\ud835\\uddf6\\ud835\\ude00\\ud835\\uddf5 \\ud835\\udde0\\ud835\\uddf2\\ud835\\uddfa\\ud835\\uddef\\ud835\\uddf2\\ud835\\uddff\\u3010 50K Per Day - \\ud835\\udde1\\ud835\\uddfc \\ud835\\uddd7\\ud835\\uddff\\ud835\\uddfc\\ud835\\uddfd - Instant - 90 Days Refill ]\\ud83d\\udca7\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(145, 1, 15424, '2', '{\"min\":\"500\",\"max\":\"100000\",\"rate\":\"138.37\",\"currency\":\"INR\"}', 15, 145, '2', '1', '➤ Telegram Real, Followers, Smooth【 No Drop【 Cancel Enabled【 10-20K/D【 0-1/H', '', '152.207', 500, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Real, Followers, Smooth\\u3010 No Drop\\u3010 Cancel Enabled\\u3010 10-20K\\/D\\u3010 0-1\\/H\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(146, 1, 16414, '2', '{\"min\":\"500\",\"max\":\"100000\",\"rate\":\"146.16\",\"currency\":\"INR\"}', 15, 146, '2', '1', '➤ Telegram Real, Followers, No Drop【 30K - 50K/Day【 0-1/Hrs + Working Fast 💧', '', '160.776', 500, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Real, Followers, No Drop\\u3010 30K - 50K\\/Day\\u3010 0-1\\/Hrs + Working Fast \\ud83d\\udca7\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(147, 1, 16418, '2', '{\"min\":\"500\",\"max\":\"100000\",\"rate\":\"146.16\",\"currency\":\"INR\"}', 15, 147, '2', '1', '➤ Telegram » Channel Member, Real【 100% Non Drop - 30 Day Refill - 100k+ Per Day + Instant ] 🔥🚀', '', '160.776', 500, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram \\u00bb Channel Member, Real\\u3010 100% Non Drop - 30 Day Refill - 100k+ Per Day + Instant ] \\ud83d\\udd25\\ud83d\\ude80\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(148, 1, 14608, '2', '{\"min\":\"500\",\"max\":\"40000\",\"rate\":\"151.04\",\"currency\":\"INR\"}', 15, 148, '2', '1', '➤ TG No Drop Members【 English Names【 20-50K/Day【 0-1/Hrs', '', '166.144', 500, 40000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 TG No Drop Members\\u3010 English Names\\u3010 20-50K\\/Day\\u3010 0-1\\/Hrs\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(149, 1, 15422, '2', '{\"min\":\"500\",\"max\":\"250000\",\"rate\":\"174.97\",\"currency\":\"INR\"}', 15, 149, '2', '1', '➤ TG No Drop Members【 Work on Private Links【 10K - 20K/Day【 Instant 🔥', '', '192.467', 500, 250000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 TG No Drop Members\\u3010 Work on Private Links\\u3010 10K - 20K\\/Day\\u3010 Instant \\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0');
INSERT INTO `services` (`service_id`, `service_api`, `api_service`, `api_servicetype`, `api_detail`, `category_id`, `service_line`, `service_type`, `service_package`, `service_name`, `service_description`, `service_price`, `service_min`, `service_max`, `service_dripfeed`, `service_autotime`, `service_autopost`, `service_speed`, `want_username`, `service_secret`, `price_type`, `price_cal`, `instagram_second`, `start_count`, `instagram_private`, `name_lang`, `description_lang`, `time_lang`, `time`, `cancelbutton`, `show_refill`, `service_profit`, `refill_days`, `refill_hours`, `avg_days`, `avg_hours`, `avg_minutes`, `avg_many`, `price_profit`, `service_overflow`, `service_sync`, `service_deleted`) VALUES
(150, 1, 15444, '2', '{\"min\":\"10\",\"max\":\"250000\",\"rate\":\"179.97\",\"currency\":\"INR\"}', 15, 150, '2', '1', '➤ Telegram Real, Followers, World Wide【 No Drop【 Cancel Enabled【 30-50K/D【 0-1/H', '', '197.967', 10, 250000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Real, Followers, World Wide\\u3010 No Drop\\u3010 Cancel Enabled\\u3010 30-50K\\/D\\u3010 0-1\\/H\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(151, 1, 15455, '2', '{\"min\":\"500\",\"max\":\"100000\",\"rate\":\"33.13\",\"currency\":\"INR\"}', 16, 151, '2', '1', '➤ Telegram Public Channel Member【 Worldwide🌍【 Instant + Low Drop 🚫', '', '36.443', 500, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Public Channel Member\\u3010 Worldwide\\ud83c\\udf0d\\u3010 Instant + Low Drop \\ud83d\\udeab\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(152, 1, 15453, '2', '{\"min\":\"100\",\"max\":\"20000\",\"rate\":\"29.24\",\"currency\":\"INR\"}', 16, 152, '2', '1', '➤ Telegram Public Channel Member【 Worldwide🌍【 Instant + No Refill 🚫', '', '32.164', 100, 20000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Public Channel Member\\u3010 Worldwide\\ud83c\\udf0d\\u3010 Instant + No Refill \\ud83d\\udeab\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(153, 1, 15452, '2', '{\"min\":\"500\",\"max\":\"100000\",\"rate\":\"52.62\",\"currency\":\"INR\"}', 16, 153, '2', '1', '➤ Telegram Public Channel Member【 Worldwide🌍【 Instant + Low Drop 🚫', '', '57.882', 500, 100000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Public Channel Member\\u3010 Worldwide\\ud83c\\udf0d\\u3010 Instant + Low Drop \\ud83d\\udeab\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(154, 1, 15454, '2', '{\"min\":\"100\",\"max\":\"30000\",\"rate\":\"58.47\",\"currency\":\"INR\"}', 16, 154, '2', '1', '➤ Telegram Public Channel Member【 Worldwide🌍【 Instant + Low Drop 🚫', '', '64.317', 100, 30000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Public Channel Member\\u3010 Worldwide\\ud83c\\udf0d\\u3010 Instant + Low Drop \\ud83d\\udeab\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0'),
(155, 1, 15442, '2', '{\"min\":\"100\",\"max\":\"40000\",\"rate\":\"121.80\",\"currency\":\"INR\"}', 16, 155, '2', '1', '➤ Telegram Real Members【 𝐍𝐨 𝐃𝐫𝐨𝐩! - 20K - 50K Per Day - Instant - Lifetime Guaranteed ]🔥', '', '133.98', 100, 40000, '1', 0, 0, '1', '1', '2', 'normal', NULL, '2', 'none', '1', '{\"en\":\"\\u27a4 Telegram Real Members\\u3010 \\ud835\\udc0d\\ud835\\udc28 \\ud835\\udc03\\ud835\\udc2b\\ud835\\udc28\\ud835\\udc29! - 20K - 50K Per Day - Instant - Lifetime Guaranteed ]\\ud83d\\udd25\"}', '{\"en\":\"\"}', 'Not enough data', 'Not enough data', '2', 'false', '', '30', '24', 0, 0, 0, 0, 10, 0, '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `service_api`
--

CREATE TABLE `service_api` (
  `id` int(11) NOT NULL,
  `api_name` varchar(225) NOT NULL,
  `api_url` text NOT NULL,
  `api_key` varchar(225) NOT NULL,
  `api_type` int(11) NOT NULL,
  `api_limit` double NOT NULL DEFAULT 0,
  `currency` varchar(200) DEFAULT NULL,
  `api_alert` enum('1','2') NOT NULL DEFAULT '2' COMMENT '2 -> Gönder, 1 -> Gönderildi',
  `status` enum('1','2') NOT NULL DEFAULT '2',
  `api_sync` enum('0','1') NOT NULL DEFAULT '1',
  `api_login_credentials` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `service_api`
--

INSERT INTO `service_api` (`id`, `api_name`, `api_url`, `api_key`, `api_type`, `api_limit`, `currency`, `api_alert`, `status`, `api_sync`, `api_login_credentials`) VALUES
(1, 'theroyalsmm.com', 'https://theroyalsmm.com/api/v2', '94ebcf5d9b4ad450ec3b4e7b734fe185', 1, 0, 'INR', '1', '1', '1', '{\"username\":\"\",\"password\":\"\"}');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_seo` text NOT NULL,
  `site_title` text DEFAULT NULL,
  `site_description` text DEFAULT NULL,
  `site_keywords` text DEFAULT NULL,
  `site_logo` text DEFAULT NULL,
  `site_name` text DEFAULT NULL,
  `site_currency` varchar(2555) NOT NULL DEFAULT 'try',
  `site_base_currency` varchar(20) DEFAULT NULL,
  `site_currency_converter` tinyint(1) NOT NULL DEFAULT 0,
  `site_update_rates_automatically` int(11) NOT NULL DEFAULT 0,
  `last_updated_currency_rates` datetime DEFAULT NULL,
  `favicon` text DEFAULT NULL,
  `site_language` varchar(225) NOT NULL DEFAULT 'tr',
  `site_theme` text NOT NULL,
  `site_theme_alt` text DEFAULT NULL,
  `recaptcha` enum('1','2') NOT NULL DEFAULT '1',
  `recaptcha_key` text DEFAULT NULL,
  `recaptcha_secret` text DEFAULT NULL,
  `custom_header` text DEFAULT NULL,
  `custom_footer` text DEFAULT NULL,
  `ticket_system` enum('1','2') NOT NULL DEFAULT '2',
  `register_page` enum('1','2') NOT NULL DEFAULT '2',
  `service_speed` enum('1','2') NOT NULL,
  `service_list` enum('1','2') NOT NULL,
  `dolar_charge` double NOT NULL,
  `euro_charge` double NOT NULL,
  `smtp_user` text NOT NULL,
  `smtp_pass` text NOT NULL,
  `smtp_server` text NOT NULL,
  `smtp_port` varchar(225) NOT NULL,
  `smtp_protocol` enum('0','ssl','tls') NOT NULL,
  `alert_type` enum('1','2','3') NOT NULL,
  `alert_apimail` enum('1','2') NOT NULL,
  `alert_newmanuelservice` enum('1','2') NOT NULL,
  `alert_newticket` enum('1','2') NOT NULL,
  `alert_apibalance` enum('1','2') NOT NULL,
  `alert_serviceapialert` enum('1','2') NOT NULL,
  `sms_provider` varchar(225) NOT NULL,
  `sms_title` varchar(225) NOT NULL,
  `sms_user` varchar(225) NOT NULL,
  `sms_pass` varchar(225) NOT NULL,
  `sms_validate` enum('0','1') NOT NULL DEFAULT '0' COMMENT '1 -> OK, 0 -> NO',
  `admin_mail` varchar(225) NOT NULL,
  `admin_telephone` varchar(225) NOT NULL,
  `resetpass_page` enum('1','2') NOT NULL,
  `resetpass_sms` enum('1','2') NOT NULL,
  `resetpass_email` enum('1','2') NOT NULL,
  `site_maintenance` enum('1','2') NOT NULL DEFAULT '2',
  `servis_siralama` varchar(255) NOT NULL,
  `bronz_statu` int(11) NOT NULL,
  `silver_statu` int(11) NOT NULL,
  `gold_statu` int(11) NOT NULL,
  `bayi_statu` int(11) NOT NULL,
  `child_panel_nameservers` varchar(255) NOT NULL DEFAULT '{"ns1":"ns1.scriptlux.com","ns2":"ns2.scriptlux.com"}',
  `childpanel_price` double DEFAULT NULL,
  `snow_effect` enum('1','2') NOT NULL DEFAULT '2',
  `snow_colour` text NOT NULL,
  `promotion` enum('1','2') DEFAULT '2',
  `referral_commision` double NOT NULL,
  `referral_payout` double NOT NULL,
  `referral_status` enum('1','2') NOT NULL DEFAULT '1',
  `childpanel_selling` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 -> OFF , 2 -> ON',
  `tickets_per_user` double NOT NULL DEFAULT 5,
  `name_fileds` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 -> ON, 2 -> NO',
  `skype_feilds` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 -> ON, 2 -> NO',
  `otp_login` enum('1','2','0') NOT NULL DEFAULT '0',
  `auto_deactivate_payment` enum('1','2') NOT NULL DEFAULT '1',
  `service_avg_time` enum('1','0') NOT NULL DEFAULT '0',
  `alert_orderfail` enum('1','2') NOT NULL DEFAULT '2',
  `alert_welcomemail` enum('1','2') NOT NULL DEFAULT '2',
  `freebalance` enum('1','2') NOT NULL DEFAULT '1',
  `freeamount` double DEFAULT 0,
  `alert_newmessage` enum('1','2') NOT NULL DEFAULT '1',
  `email_confirmation` enum('1','2') NOT NULL DEFAULT '2',
  `resend_max` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '1',
  `fundstransfer_fees` varchar(10) NOT NULL,
  `permissions` text DEFAULT NULL,
  `fake_order_service_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `fake_order_min` int(11) DEFAULT NULL,
  `fake_order_max` int(11) DEFAULT NULL,
  `panel_orders` int(11) DEFAULT NULL,
  `panel_orders_pattern` varchar(255) NOT NULL DEFAULT '{"panel_orders_prefix":"","panel_orders_suffix":""}',
  `downloaded_category_icons` tinyint(1) NOT NULL DEFAULT 0,
  `summary_card_background_color` varchar(100) DEFAULT 'theme_colour',
  `google_login` varchar(100) NOT NULL DEFAULT '{"purchased":"1","status":"1"}',
  `services_average_time` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_seo`, `site_title`, `site_description`, `site_keywords`, `site_logo`, `site_name`, `site_currency`, `site_base_currency`, `site_currency_converter`, `site_update_rates_automatically`, `last_updated_currency_rates`, `favicon`, `site_language`, `site_theme`, `site_theme_alt`, `recaptcha`, `recaptcha_key`, `recaptcha_secret`, `custom_header`, `custom_footer`, `ticket_system`, `register_page`, `service_speed`, `service_list`, `dolar_charge`, `euro_charge`, `smtp_user`, `smtp_pass`, `smtp_server`, `smtp_port`, `smtp_protocol`, `alert_type`, `alert_apimail`, `alert_newmanuelservice`, `alert_newticket`, `alert_apibalance`, `alert_serviceapialert`, `sms_provider`, `sms_title`, `sms_user`, `sms_pass`, `sms_validate`, `admin_mail`, `admin_telephone`, `resetpass_page`, `resetpass_sms`, `resetpass_email`, `site_maintenance`, `servis_siralama`, `bronz_statu`, `silver_statu`, `gold_statu`, `bayi_statu`, `child_panel_nameservers`, `childpanel_price`, `snow_effect`, `snow_colour`, `promotion`, `referral_commision`, `referral_payout`, `referral_status`, `childpanel_selling`, `tickets_per_user`, `name_fileds`, `skype_feilds`, `otp_login`, `auto_deactivate_payment`, `service_avg_time`, `alert_orderfail`, `alert_welcomemail`, `freebalance`, `freeamount`, `alert_newmessage`, `email_confirmation`, `resend_max`, `status`, `fundstransfer_fees`, `permissions`, `fake_order_service_enabled`, `fake_order_min`, `fake_order_max`, `panel_orders`, `panel_orders_pattern`, `downloaded_category_icons`, `summary_card_background_color`, `google_login`, `services_average_time`) VALUES
(1, 'Garudsmm.in', 'World cheapest smm panel', 'Best and Cheap SMM Panel for Instagram, Tiktok, Facebook, Youtube and Twitter. Buy IG Reels views and Followers, Buy Instagram Likes, Views and Comments, Buy TikTok Likes and Followers, Buy Facebook Likes and Comments, Buy Youtube Likes, Views and Subscribers.', 'smm panel,smmpanel,SMM Panel India,SMM Panel Paytm,SMM Panel Cheap India,SMM Reseller Panel,SMM Reseller Panel India,Cheap SMM Panel,cheapest SMM panel,cheap SMM panel india,Cheapest SMM Reseller Panel,Cheapest SMM Panel Paytm,Cheapest SMM Panel Paytm,indian smm panel,IndianSMM Reseller Panel,Best SMM panel,Best SMM Panel India,Top SMM Panel', '', '@Web_Script_Master', '', 'INR', 0, 0, NULL, '', 'en', 'xblur', 'green', '1', '', '', '', '', '1', '2', '1', '2', 0, 0, 'support@garudsmm.in', 'Seaclip$2024', 'smtp.gmail.com', '587', 'tls', '2', '2', '2', '2', '2', '2', 'bizimsms', '', '', '', '1', 'support@garudsmm.in', '', '2', '1', '2', '2', 'asc', 0, 0, 0, 0, '{\"ns1\":\"ns1.supergrow.online\",\"ns2\":\"ns2.supergrow.online\"}', NULL, '', '', NULL, 0, 0, '', '', 9999999999, '2', '2', '0', '1', '1', '2', '2', '', NULL, '2', '2', 2, '0', '3', '{\"admin access\":{\"admin_access\":{\"name\":\"Admin Access\",\"value\":\"admin_access\"}},\"pages\":{\"users\":{\"name\":\"Users\",\"value\":\"users\"},\"services\":{\"name\":\"Services\",\"value\":\"services\"},\"update-prices\":{\"name\":\"Update Prices\",\"value\":\"update-prices\"},\"bulk\":{\"name\":\"Bulk Services Editor\",\"value\":\"bulk\"},\"bulkc\":{\"name\":\"Bulk Category Editor\",\"value\":\"services\"},\"synced-logs\":{\"name\":\"Seller Sync Logs\",\"value\":\"synced-logs\"},\"orders\":{\"name\":\"Orders\",\"value\":\"orders\"},\"subscriptions\":{\"name\":\"Subscriptions\",\"value\":\"subscriptions\"},\"dripfeed\":{\"name\":\"Dripfeed\",\"value\":\"dripfeed\"},\"tasks\":{\"name\":\"Order Refill and Cancel Tasks\",\"value\":\"tasks\"},\"payments\":{\"name\":\"Payments\",\"value\":\"payments\"},\"tickets\":{\"name\":\"Tickets\",\"value\":\"tickets\"}},\"additionals\":{\"additionals\":{\"name\":\"Additionals\",\"value\":\"additionals\"},\"referral\":{\"name\":\"Affiliates\",\"value\":\"referral\"},\"broadcast\":{\"name\":\"Broadcasts\",\"value\":\"broadcast\"},\"logs\":{\"name\":\"Panel Logs\",\"value\":\"logs\"},\"reports\":{\"name\":\"Reports\",\"value\":\"reports\"},\"videop\":{\"name\":\"Promotion\",\"value\":\"videop\"},\"coupon\":{\"name\":\"Coupons\",\"value\":\"coupon\"},\"child-panels\":{\"name\":\"Child Panels\",\"value\":\"child-panels\"},\"updates\":{\"name\":\"Updates\",\"value\":\"updates\"}},\"appearance\":{\"appearance\":{\"name\":\"Appearance\",\"value\":\"appearance\"},\"themes\":{\"name\":\"Themes\",\"value\":\"themes\"},\"new_year\":{\"name\":\"New Year\",\"value\":\"new_year\"},\"pages\":{\"name\":\"Pages\",\"value\":\"pages\"},\"news\":{\"name\":\"Announcements\",\"value\":\"news\"},\"meta\":{\"name\":\"Meta (SEO) Settings\",\"value\":\"meta\"},\"blog\":{\"name\":\"Blogs\",\"value\":\"blog\"},\"menu\":{\"name\":\"Menu\",\"value\":\"menu\"},\"inte\":{\"name\":\"Integrations\",\"value\":\"inte\"},\"language\":{\"name\":\"Languages\",\"value\":\"language\"},\"files\":{\"name\":\"Uploaded Images\",\"value\":\"files\"}},\"settings\":{\"settings\":{\"name\":\"Settings\",\"value\":\"settings\"},\"general_settings\":{\"name\":\"General Settings\",\"value\":\"general_settings\"},\"providers\":{\"name\":\"Sellers\",\"value\":\"providers\"},\"payments_settings\":{\"name\":\"Payment Methods\",\"value\":\"payments_settings\"},\"bank_accounts\":{\"name\":\"Bank Accounts\",\"value\":\"bank_accounts\"},\"modules\":{\"name\":\"Modules\",\"value\":\"modules\"},\"subject\":{\"name\":\"Support Settings\",\"value\":\"subject\"},\"payments_bonus\":{\"name\":\"Payment Bonuses\",\"value\":\"payments_bonus\"},\"currency-manager\":{\"name\":\"Site Currency Manager\",\"value\":\"currency-manager\"},\"alert_settings\":{\"name\":\"Notification Settings\",\"value\":\"alert_settings\"},\"site_count\":{\"name\":\"Fake Orders\",\"value\":\"site_count\"},\"manager\":{\"name\":\"Manager\",\"value\":\"manager\"}}}', 0, 1, 3, 0, '{\"panel_orders_prefix\":\"\",\"panel_orders_suffix\":\"\"}', 0, 'theme_colour', '{\"purchased\":\"1\",\"status\":\"1\"}', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sync_logs`
--

CREATE TABLE `sync_logs` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `action` varchar(225) NOT NULL,
  `date` datetime NOT NULL,
  `description` varchar(225) NOT NULL,
  `api_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `task_api` int(11) DEFAULT NULL,
  `task_type` varchar(225) DEFAULT NULL,
  `task_status` varchar(225) DEFAULT 'pending',
  `task_response` text DEFAULT NULL,
  `task_created_at` datetime DEFAULT NULL,
  `task_updated_at` datetime DEFAULT NULL,
  `task_by` text DEFAULT NULL,
  `check_refill_status` int(11) DEFAULT NULL,
  `refill_orderid` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `theme_name` text NOT NULL,
  `theme_dirname` text NOT NULL,
  `theme_extras` text NOT NULL,
  `last_modified` datetime NOT NULL,
  `newpage` text NOT NULL,
  `colour` enum('1','2') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `theme_name`, `theme_dirname`, `theme_extras`, `last_modified`, `newpage`, `colour`) VALUES
(1, 'Simplify', 'Simplify', '', '2022-11-16 09:28:01', '{% include \'header.twig\' %}\r\n	<br><br><br>\r\n	\r\n	<div class=\"container-fluid container-fluid-spacious\">\r\n		<div class=\"row\">\r\n			<div class=\"col-md-12\">\r\n			{% if contentText %}\r\n{{ contentText }}\r\n{% endif %}\r\n				{% if contentText2 %}\r\n{{ contentText2 }}\r\n{% endif %}\r\n				\r\n			</div>\r\n		</div>\r\n	</div>\r\n   \r\n      \r\n        \r\n   ', '2'),
(4, 'Eternity', 'Eternity', '', '0000-00-00 00:00:00', '', '2'),
(2, 'Clementine', 'pitchy', '{\"stylesheets\":[\"public/pitchy/bootstrap.css\",\"public/pitchy/black.css\",\"https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css\",\"public/datepicker/css/bootstrap-datepicker3.min.css\"],\"scripts\":[\"https://code.jquery.com/jquery-3.3.1.min.js\",\"public/pitchy/js/script.js\",\"public/pitchy/js/snowfall.min.js\",\"public/pitchy/js/bundle.js\",\"public/pitchy/js/main.js\",\"public/ajax.js\",\"public/datepicker/js/bootstrap-datepicker.min.js\"]}', '2022-11-21 09:29:47', '', '2'),
(5, 'X Blur', 'xblur', '', '2023-11-07 09:29:47', '', '1'),
(9, 'TechSMM', 'TechSMM', '', '2024-07-03 06:45:09', '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `subject` varchar(225) NOT NULL,
  `time` datetime NOT NULL,
  `lastupdate_time` datetime NOT NULL,
  `client_new` enum('1','2') NOT NULL DEFAULT '2',
  `status` enum('pending','answered','closed') NOT NULL DEFAULT 'pending',
  `support_new` enum('1','2') NOT NULL DEFAULT '1',
  `canmessage` enum('1','2') NOT NULL DEFAULT '2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_reply`
--

CREATE TABLE `ticket_reply` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `support` enum('1','2') NOT NULL DEFAULT '1',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `readed` enum('1','2') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_subjects`
--

CREATE TABLE `ticket_subjects` (
  `subject_id` int(11) NOT NULL,
  `subject` varchar(225) NOT NULL,
  `content` text DEFAULT NULL,
  `auto_reply` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `ticket_subjects`
--

INSERT INTO `ticket_subjects` (`subject_id`, `subject`, `content`, `auto_reply`) VALUES
(1, 'Order', '', '0'),
(2, 'Payment', '', '0'),
(4, 'Complaint & Suggestion', '', '0'),
(6, 'Others', 'You will be answered within minutes', '1');

-- --------------------------------------------------------

--
-- Table structure for table `units_per_page`
--

CREATE TABLE `units_per_page` (
  `id` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  `page` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `units_per_page`
--

INSERT INTO `units_per_page` (`id`, `unit`, `page`) VALUES
(1, 50, 'clients'),
(2, 50, 'orders'),
(3, 50, 'payments'),
(4, 50, 'refill'),
(5, 50, 'bulk'),
(6, 8, 'services');

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE `updates` (
  `u_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `action` varchar(225) NOT NULL,
  `date` datetime NOT NULL,
  `description` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT 'Not enough data'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `admin_constants`
--
ALTER TABLE `admin_constants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulkedit`
--
ALTER TABLE `bulkedit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `childpanels`
--
ALTER TABLE `childpanels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `clients_category`
--
ALTER TABLE `clients_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients_price`
--
ALTER TABLE `clients_price`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients_service`
--
ALTER TABLE `clients_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_report`
--
ALTER TABLE `client_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `decoration`
--
ALTER TABLE `decoration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `earn`
--
ALTER TABLE `earn`
  ADD PRIMARY KEY (`earn_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `General_options`
--
ALTER TABLE `General_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `integrations`
--
ALTER TABLE `integrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kuponlar`
--
ALTER TABLE `kuponlar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kupon_kullananlar`
--
ALTER TABLE `kupon_kullananlar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Mailforms`
--
ALTER TABLE `Mailforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications_popup`
--
ALTER TABLE `notifications_popup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `panel_categories`
--
ALTER TABLE `panel_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `panel_info`
--
ALTER TABLE `panel_info`
  ADD PRIMARY KEY (`panel_id`);

--
-- Indexes for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD PRIMARY KEY (`methodId`),
  ADD KEY `methodId` (`methodId`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `referral`
--
ALTER TABLE `referral`
  ADD PRIMARY KEY (`referral_id`);

--
-- Indexes for table `referral_payouts`
--
ALTER TABLE `referral_payouts`
  ADD PRIMARY KEY (`r_p_id`);

--
-- Indexes for table `serviceapi_alert`
--
ALTER TABLE `serviceapi_alert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `service_api`
--
ALTER TABLE `service_api`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sync_logs`
--
ALTER TABLE `sync_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `ticket_reply`
--
ALTER TABLE `ticket_reply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_subjects`
--
ALTER TABLE `ticket_subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `units_per_page`
--
ALTER TABLE `units_per_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulkedit`
--
ALTER TABLE `bulkedit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `childpanels`
--
ALTER TABLE `childpanels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `clients_category`
--
ALTER TABLE `clients_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients_price`
--
ALTER TABLE `clients_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `clients_service`
--
ALTER TABLE `clients_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_report`
--
ALTER TABLE `client_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `earn`
--
ALTER TABLE `earn`
  MODIFY `earn_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `General_options`
--
ALTER TABLE `General_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `integrations`
--
ALTER TABLE `integrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `kuponlar`
--
ALTER TABLE `kuponlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kupon_kullananlar`
--
ALTER TABLE `kupon_kullananlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Mailforms`
--
ALTER TABLE `Mailforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications_popup`
--
ALTER TABLE `notifications_popup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=913;

--
-- AUTO_INCREMENT for table `panel_categories`
--
ALTER TABLE `panel_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `panel_info`
--
ALTER TABLE `panel_info`
  MODIFY `panel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  MODIFY `methodId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `referral`
--
ALTER TABLE `referral`
  MODIFY `referral_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `referral_payouts`
--
ALTER TABLE `referral_payouts`
  MODIFY `r_p_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `serviceapi_alert`
--
ALTER TABLE `serviceapi_alert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53367;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `service_api`
--
ALTER TABLE `service_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sync_logs`
--
ALTER TABLE `sync_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_reply`
--
ALTER TABLE `ticket_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_subjects`
--
ALTER TABLE `ticket_subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `units_per_page`
--
ALTER TABLE `units_per_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
