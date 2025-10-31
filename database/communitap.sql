-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2025 at 01:51 PM
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
-- Database: `communitap`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-renzotoring@gmail.com|127.0.0.1', 'i:1;', 1761750113),
('laravel-cache-renzotoring@gmail.com|127.0.0.1:timer', 'i:1761750113;', 1761750113);

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
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackId` bigint(20) UNSIGNED NOT NULL,
  `FK1_userId` bigint(20) UNSIGNED NOT NULL,
  `FK2_taskId` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `feedback_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackId`, `FK1_userId`, `FK2_taskId`, `rating`, `comment`, `feedback_date`, `created_at`, `updated_at`) VALUES
(1, 5, 3, 3, 'good experience', '2025-09-06 15:30:13', '2025-09-06 07:30:13', '2025-09-06 07:30:13'),
(2, 5, 14, 5, 'fun task', '2025-10-04 11:47:55', '2025-10-04 03:47:55', '2025-10-04 03:47:55'),
(3, 5, 16, 4, 'AHAHAHAHA', '2025-10-28 13:28:12', '2025-10-28 05:28:12', '2025-10-28 05:28:12'),
(4, 6, 3, 4, 'good', '2025-10-28 13:30:02', '2025-10-28 05:30:02', '2025-10-28 05:30:02'),
(5, 11, 20, 5, 'Enjoyed the task!!!', '2025-10-29 14:46:46', '2025-10-29 06:46:46', '2025-10-29 06:46:46'),
(6, 5, 23, 4, 'fdsfsdfadsa', '2025-10-31 11:33:44', '2025-10-31 03:33:44', '2025-10-31 03:33:44');

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
(4, '0001_01_01_000000_create_users_table', 1),
(5, '0001_01_01_000001_create_cache_table', 1),
(6, '0001_01_01_000002_create_jobs_table', 1),
(7, '0001_01_01_000004_create_roles_table', 2),
(8, '0001_01_01_000005_create_user_roles_table', 2),
(9, '0001_01_01_000006_create_tasks_table', 2),
(10, '0001_01_01_000007_create_task_submission_table', 2),
(11, '0001_01_01_000008_create_task_assignment_table', 2),
(12, '0001_01_01_000009_create_feedback_table', 2),
(13, '0001_01_01_000010_create_reports_table', 2),
(14, '0001_01_01_000011_create_tap_nominations_table', 2),
(15, '0001_01_01_000012_create_rewards_table', 2),
(16, '0001_01_01_000013_create_notifications_table', 2),
(17, '0001_01_01_000014_create_reward_redemption_table', 2),
(18, '0001_01_01_000015_create_points_history_table', 2),
(19, '2025_09_06_141922_modify_tasks_table_make_user_id_nullable', 3),
(20, '2025_09_06_142246_create_task_assignments_table', 4),
(21, '2025_09_06_143740_add_photos_to_task_assignments', 5),
(22, '2025_09_06_143750_add_photos_to_task_assignments', 5),
(23, '2025_09_06_150236_add_time_location_to_tasks_table', 6),
(24, '2025_09_06_161722_add_rejection_count_to_task_assignments_table', 7),
(25, '2025_09_21_140000_create_tap_nominations_table_fix', 8),
(26, '2025_09_21_142411_add_chain_columns_to_tap_nominations_table', 9),
(27, '2025_09_06_152220_create_feedback_table', 10),
(28, '2025_09_24_000100_add_max_participants_to_tasks_table', 10),
(29, '2025_10_01_000001_create_personal_access_tokens_table', 11),
(30, '2025_10_01_000100_add_progress_to_task_assignments', 12),
(31, '2025_10_03_100305_create_user_incident_reports_table', 13),
(32, '2025_10_29_000000_add_coupon_code_to_reward_redemption_table', 14),
(33, '2025_10_29_000001_add_coupon_image_path_to_reward_redemption_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notificationId` bigint(20) UNSIGNED NOT NULL,
  `FK1_userId` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(30) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'unread',
  `created_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `points_history`
--

CREATE TABLE `points_history` (
  `historyId` bigint(20) UNSIGNED NOT NULL,
  `FK1_userId` bigint(20) UNSIGNED NOT NULL,
  `FK2_submissionId` bigint(20) UNSIGNED DEFAULT NULL,
  `points_amount` int(11) NOT NULL,
  `transaction_type` varchar(50) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `reportId` bigint(20) UNSIGNED NOT NULL,
  `FK1_userId` bigint(20) UNSIGNED NOT NULL,
  `report_type` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `report_date` datetime NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `moderation_date` datetime DEFAULT NULL,
  `action_taken` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `rewardId` bigint(20) UNSIGNED NOT NULL,
  `sponsor_name` varchar(50) NOT NULL,
  `reward_name` varchar(100) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `points_cost` int(11) NOT NULL,
  `QTY` int(11) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `created_date` datetime NOT NULL,
  `last_update_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`rewardId`, `sponsor_name`, `reward_name`, `image_path`, `description`, `points_cost`, `QTY`, `status`, `created_date`, `last_update_date`, `created_at`, `updated_at`) VALUES
(1, 'Don Mac', 'Caramel Macchiato', 'rewards/0R3e4TE3gw97d6GGMHREv1WTKRW6d0iu5mmAGyJt.jpg', 'sdadsa', 100, 20, 'active', '2025-10-06 12:00:53', '2025-10-06 12:31:32', '2025-10-06 04:00:53', '2025-10-06 04:31:32'),
(2, 'Kons Bahan', '200 GCASH', 'rewards/7gbM2bw1jdxHmsOzn0dHlSw9cyHxtObyaQcSTDSK.png', '200 pesos gcash', 300, 5, 'active', '2025-10-07 11:28:20', '2025-10-07 11:28:20', '2025-10-07 03:28:21', '2025-10-07 03:28:21'),
(3, 'Bahan', 'GCASH', 'rewards/LJsGmdOCqe277yrSGH89vc4iO5N8McAikuysEITC.png', 'freee cash', 20, 3, 'active', '2025-10-28 13:08:03', '2025-10-29 11:55:27', '2025-10-28 05:08:03', '2025-10-29 07:06:51'),
(5, 'Jhon\'s Sari-Sari Store', '1 sack of rice', 'rewards/cxw8H770iBiLHjMBT5qZ8Z9mZwAGin7O8hj6nFUh.jpg', '1 sack of rice', 5, 4, 'active', '2025-10-31 11:21:43', '2025-10-31 11:23:28', '2025-10-31 03:21:43', '2025-10-31 03:24:09');

-- --------------------------------------------------------

--
-- Table structure for table `reward_redemption`
--

CREATE TABLE `reward_redemption` (
  `redemptionId` bigint(20) UNSIGNED NOT NULL,
  `FK1_rewardId` bigint(20) UNSIGNED NOT NULL,
  `FK2_userId` bigint(20) UNSIGNED NOT NULL,
  `redemption_date` datetime NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `coupon_code` varchar(64) DEFAULT NULL,
  `coupon_image_path` varchar(255) DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reward_redemption`
--

INSERT INTO `reward_redemption` (`redemptionId`, `FK1_rewardId`, `FK2_userId`, `redemption_date`, `status`, `coupon_code`, `coupon_image_path`, `approval_date`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 3, 5, '2025-10-28 13:08:30', 'pending', NULL, NULL, NULL, NULL, '2025-10-28 05:08:30', '2025-10-28 05:08:30'),
(2, 3, 5, '2025-10-28 13:16:18', 'pending', NULL, NULL, NULL, NULL, '2025-10-28 05:16:18', '2025-10-28 05:16:18'),
(3, 3, 6, '2025-10-29 08:30:10', 'approved', '5BE33F2322', NULL, '2025-10-29 08:30:10', NULL, '2025-10-29 00:30:10', '2025-10-29 00:30:10'),
(4, 3, 6, '2025-10-29 09:59:40', 'approved', '4B6F4841DA', NULL, '2025-10-29 09:59:40', NULL, '2025-10-29 01:59:40', '2025-10-29 01:59:40'),
(5, 3, 6, '2025-10-29 10:00:32', 'approved', '6B9D173029', NULL, '2025-10-29 10:00:32', NULL, '2025-10-29 02:00:32', '2025-10-29 02:00:32'),
(6, 3, 6, '2025-10-29 15:02:07', 'pending', NULL, NULL, NULL, NULL, '2025-10-29 07:02:07', '2025-10-29 07:02:07'),
(7, 3, 5, '2025-10-29 15:06:51', 'approved', NULL, NULL, '2025-10-29 15:06:51', 'Coupon: JJOBAWZQ4M', '2025-10-29 07:06:51', '2025-10-29 07:06:51'),
(8, 5, 5, '2025-10-31 11:24:09', 'approved', NULL, NULL, '2025-10-31 11:24:09', 'Coupon: TSCZI6LNRG', '2025-10-31 03:24:09', '2025-10-31 03:24:09');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `roleId` bigint(20) UNSIGNED NOT NULL,
  `roleName` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('PHfUpUENaYDh2V1S5xUpGArt8sKAE7BzAZT35Sbu', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTmIwYjlZQkNtdVJmZTJnQTVqdnNLRTJGRFFkVUhOc28yNFVPV3hiQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQvcGRmIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1761910563),
('POQnYKisfyK8pkDKw6O06qm5O2WDXTgTjIXwLJu4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM1pVY3ljQUlzZ3E2NmVzYjlQYmMxSTFzNVo0ckdPRXJtTzNaZUZ1VyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1761912369),
('vRrQHDaF8vlXlyIY0xUj4YV1OMEIGbjzSeVtfkPi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoib3g5VFR1amZvZTJLeVVoekIxbGxlV01xMmNocnR2Q25VbXVSWmVEYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761908946);

-- --------------------------------------------------------

--
-- Table structure for table `tap_nominations`
--

CREATE TABLE `tap_nominations` (
  `nominationId` bigint(20) UNSIGNED NOT NULL,
  `FK1_taskId` bigint(20) UNSIGNED NOT NULL,
  `FK2_nominatorId` bigint(20) UNSIGNED NOT NULL,
  `FK3_nomineeId` bigint(20) UNSIGNED NOT NULL,
  `nomination_date` datetime NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tap_nominations`
--

INSERT INTO `tap_nominations` (`nominationId`, `FK1_taskId`, `FK2_nominatorId`, `FK3_nomineeId`, `nomination_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 9, 6, 5, '2025-09-21 12:51:34', 'accepted', '2025-09-21 04:51:34', '2025-09-21 06:36:10'),
(3, 10, 5, 7, '2025-09-21 14:06:30', 'accepted', '2025-09-21 06:06:30', '2025-09-21 06:36:10'),
(4, 23, 11, 5, '2025-10-29 14:35:39', 'declined', '2025-10-29 06:35:39', '2025-10-29 06:36:17'),
(5, 23, 11, 5, '2025-10-29 14:38:39', 'accepted', '2025-10-29 06:38:39', '2025-10-29 06:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `taskId` bigint(20) UNSIGNED NOT NULL,
  `FK1_userId` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `task_type` varchar(30) NOT NULL,
  `points_awarded` int(11) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `creation_date` datetime NOT NULL,
  `approval_date` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `max_participants` int(10) UNSIGNED DEFAULT NULL,
  `published_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`taskId`, `FK1_userId`, `title`, `description`, `task_type`, `points_awarded`, `status`, `creation_date`, `approval_date`, `due_date`, `start_time`, `end_time`, `location`, `max_participants`, `published_date`, `created_at`, `updated_at`) VALUES
(2, NULL, 'Test', 'Hello this is a test', 'one_time', 10, 'completed', '2025-09-06 15:10:15', NULL, '2025-09-08 00:00:00', '13:00:00', '15:00:00', 'covered court', NULL, '2025-09-06 15:10:15', '2025-09-06 07:10:15', '2025-09-24 04:52:54'),
(3, NULL, 'Test Daily', 'Daily Task test create', 'daily', 20, 'completed', '2025-09-06 15:11:14', NULL, '2025-09-09 00:00:00', '10:00:00', '12:30:00', 'barangay hall', NULL, '2025-09-06 15:11:14', '2025-09-06 07:11:14', '2025-09-24 04:52:54'),
(4, NULL, 'Test One TIme', 'hello this a test plss', 'one_time', 30, 'completed', '2025-09-06 16:08:51', NULL, '2025-09-17 00:00:00', '12:09:00', '14:08:00', 'park', NULL, '2025-09-06 16:08:51', '2025-09-06 08:08:51', '2025-09-24 04:52:54'),
(5, NULL, 'another test', '231sdadasdsa', 'daily', 12, 'completed', '2025-09-06 16:10:22', NULL, '2025-09-08 00:00:00', '16:10:00', '17:10:00', 'sda', NULL, '2025-09-06 16:10:22', '2025-09-06 08:10:22', '2025-09-24 04:52:54'),
(6, 5, 'need man power', 'building a dog house', 'user_uploaded', 10, 'completed', '2025-09-06 16:14:08', '2025-09-06 16:14:37', '2025-09-08 00:00:00', '14:13:00', '15:13:00', 'balay', NULL, '2025-09-06 16:14:43', '2025-09-06 08:14:08', '2025-09-24 04:52:54'),
(7, 5, 'User TEst', 'test 123 123', 'user_uploaded', 20, 'completed', '2025-09-10 13:10:39', '2025-09-10 13:12:09', '2025-09-12 00:00:00', '10:10:00', '12:10:00', 'House', NULL, '2025-09-10 13:12:26', '2025-09-10 05:10:39', '2025-09-24 04:52:54'),
(8, NULL, 'Tap and Pass Test', 'this is a test hehu', 'daily', 10, 'completed', '2025-09-10 13:40:59', NULL, '2025-09-12 00:00:00', '22:40:00', '23:41:00', 'sports complex', NULL, '2025-09-10 13:40:59', '2025-09-10 05:40:59', '2025-09-24 04:52:54'),
(9, NULL, 'TapPass2', 'test 2', 'daily', 12, 'completed', '2025-09-21 12:25:11', NULL, '2025-09-24 00:00:00', '11:24:00', '12:30:00', 'park', NULL, '2025-09-21 12:25:11', '2025-09-21 04:25:11', '2025-09-24 04:52:54'),
(10, NULL, 'tapPass3', 'test3', 'daily', 13, 'completed', '2025-09-21 12:52:56', NULL, '2025-09-24 00:00:00', '10:54:00', '13:00:00', 'covered court', NULL, '2025-09-21 12:52:56', '2025-09-21 04:52:56', '2025-09-24 04:52:54'),
(11, NULL, 'One Test', 'hello hi', 'one_time', 12, 'completed', '2025-09-24 12:55:17', NULL, '2025-09-26 00:00:00', '09:00:00', '12:00:00', 'Community  Center', NULL, '2025-09-24 12:55:25', '2025-09-24 04:55:17', '2025-10-01 05:07:13'),
(12, NULL, 'Limit Test', 'sdsad fdsfs1123', 'daily', 23, 'completed', '2025-09-24 13:05:39', NULL, '2025-09-27 00:00:00', '10:30:00', '12:00:00', 'Park', 2, '2025-09-24 13:05:39', '2025-09-24 05:05:39', '2025-10-01 05:07:13'),
(13, 5, 'Wood Working', 'need manpower in making a wood furniture', 'user_uploaded', 12, 'completed', '2025-10-01 13:06:46', '2025-10-01 13:07:27', '2025-10-04 00:00:00', '10:06:00', '12:06:00', 'PH', 2, '2025-10-01 13:07:31', '2025-10-01 05:06:46', '2025-10-04 02:41:28'),
(14, NULL, 'redirect test', 'test for redirecting task', 'daily', 12, 'completed', '2025-10-01 13:41:48', NULL, '2025-10-03 00:00:00', '09:41:00', '11:41:00', '123', 2, '2025-10-01 13:41:48', '2025-10-01 05:41:48', '2025-10-03 01:51:26'),
(15, 5, 'uploaded test', 'user task test', 'user_uploaded', 20, 'completed', '2025-10-01 14:32:38', NULL, '2025-10-04 00:00:00', '12:32:00', '14:32:00', 'covered court', NULL, NULL, '2025-10-01 06:32:38', '2025-10-04 02:41:28'),
(16, NULL, 'test3', 'gsakdasdas', 'daily', 20, 'completed', '2025-10-06 12:50:29', NULL, '2025-10-07 00:00:00', '10:50:00', '12:50:00', 'park', NULL, '2025-10-06 12:50:29', '2025-10-06 04:50:29', '2025-10-07 03:39:18'),
(17, 9, 'Test Task1', 'hello test', 'user_uploaded', 20, 'completed', '2025-10-28 12:26:03', '2025-10-28 12:59:11', '2025-10-29 00:00:00', '10:30:00', '12:30:00', 'park', 2, '2025-10-28 13:03:07', '2025-10-28 04:26:03', '2025-10-29 03:33:49'),
(18, NULL, 'daily task test123', '123 test 123', 'daily', 20, 'completed', '2025-10-28 12:40:36', NULL, '2025-10-30 00:00:00', '12:45:00', '14:45:00', 'ila bahan', NULL, '2025-10-28 12:40:36', '2025-10-28 04:40:36', '2025-10-31 01:36:01'),
(19, 10, 'hello there', 'dsadsadasdsas', 'user_uploaded', 10, 'completed', '2025-10-29 13:54:56', '2025-10-29 14:25:52', '2025-10-30 00:00:00', '10:00:00', '12:00:00', 'Ermita Proper', 2, NULL, '2025-10-29 05:54:56', '2025-10-31 01:36:01'),
(20, NULL, 'dailyyyy testttttt', 'hellllloooooo', 'daily', 15, 'inactive', '2025-10-29 13:59:50', NULL, '2025-10-31 00:00:00', '10:00:00', '12:00:00', 'Kastilaan', 2, '2025-10-29 13:59:50', '2025-10-29 05:59:50', '2025-10-29 06:26:20'),
(21, 11, 'sdsadasdasddsa', 'dsadasdsadsada', 'user_uploaded', 20, 'completed', '2025-10-29 14:19:12', '2025-10-29 14:21:32', '2025-10-30 00:00:00', '12:20:00', '13:20:00', 'Kastilaan', 2, NULL, '2025-10-29 06:19:12', '2025-10-31 01:36:01'),
(22, 11, 'lk;ldklfgd', 'asdsadsadsadsa', 'user_uploaded', 10, 'inactive', '2025-10-29 14:20:10', NULL, '2025-10-30 00:00:00', '13:20:00', '15:20:00', 'Panagdait', 1, NULL, '2025-10-29 06:20:10', '2025-10-29 06:24:16'),
(23, NULL, 'momomomo', 'dsadasdsadsad', 'daily', 20, 'completed', '2025-10-29 14:35:19', NULL, '2025-10-30 00:00:00', '14:34:00', '17:34:00', 'Panagdait', 10, '2025-10-29 14:35:19', '2025-10-29 06:35:19', '2025-10-31 01:36:01'),
(24, NULL, 'demo test', 'dsafsfsdfdfsd', 'daily', 20, 'published', '2025-10-31 09:41:37', NULL, '2025-11-01 00:00:00', '18:41:00', '20:41:00', 'Panagdait', NULL, '2025-10-31 09:41:37', '2025-10-31 01:41:37', '2025-10-31 01:41:37'),
(25, NULL, 'hello daily task', 'sfdsdssdgds', 'daily', 25, 'published', '2025-10-31 09:42:49', NULL, '2025-11-01 00:00:00', '09:42:00', '11:42:00', 'Sitio Bato', NULL, '2025-10-31 09:42:49', '2025-10-31 01:42:49', '2025-10-31 01:42:49'),
(26, NULL, 'another test daily', 'adskajdhsajkdasd', 'daily', 30, 'published', '2025-10-31 09:44:51', NULL, '2025-11-02 00:00:00', '10:00:00', '12:30:00', 'Ermita Proper', NULL, '2025-10-31 09:44:51', '2025-10-31 01:44:51', '2025-10-31 01:44:51'),
(27, NULL, 'One Time Task TEst 123123', 'dlasdlkn nvksdjiaosdsa', 'one_time', 50, 'inactive', '2025-10-31 09:46:21', NULL, '2025-11-02 00:00:00', '09:30:00', '13:45:00', 'Ermita Proper', NULL, '2025-10-31 09:46:21', '2025-10-31 01:46:21', '2025-10-31 03:13:26'),
(28, 5, 'hello proposal test456', 'dasdasdasdasd56546', 'user_uploaded', 25, 'approved', '2025-10-31 10:22:14', '2025-10-31 11:19:03', '2025-11-01 00:00:00', '11:30:00', '12:30:00', 'Ermita Proper', 1, NULL, '2025-10-31 02:22:14', '2025-10-31 03:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `task_assignment`
--

CREATE TABLE `task_assignment` (
  `assignmentId` bigint(20) UNSIGNED NOT NULL,
  `FK1_userId` bigint(20) UNSIGNED NOT NULL,
  `FK2_taskId` bigint(20) UNSIGNED NOT NULL,
  `assignment_date` datetime NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'assigned',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_assignments`
--

CREATE TABLE `task_assignments` (
  `assignmentId` bigint(20) UNSIGNED NOT NULL,
  `taskId` bigint(20) UNSIGNED NOT NULL,
  `userId` bigint(20) UNSIGNED NOT NULL,
  `status` enum('assigned','submitted','completed') NOT NULL DEFAULT 'assigned',
  `progress` enum('accepted','on_the_way','working','done','submitted_proof') NOT NULL DEFAULT 'accepted',
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `submitted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `completion_notes` text DEFAULT NULL,
  `rejection_count` int(11) NOT NULL DEFAULT 0,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_assignments`
--

INSERT INTO `task_assignments` (`assignmentId`, `taskId`, `userId`, `status`, `progress`, `assigned_at`, `submitted_at`, `completed_at`, `photos`, `completion_notes`, `rejection_count`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(3, 3, 6, 'completed', 'accepted', '2025-09-06 07:15:53', '2025-09-10 05:33:07', '2025-09-10 05:38:16', '[\"task-submissions\\/0pdquLS3iIGprTPetfDJIzL7OqKJiUr8Pya7YECC.png\"]', 'Approved by admin', 1, 'please provide a proof of completion', '2025-09-06 07:15:53', '2025-09-10 05:38:16'),
(4, 2, 6, 'completed', 'accepted', '2025-09-06 07:15:58', '2025-09-10 05:37:19', '2025-09-10 05:40:08', '[\"task-submissions\\/YwVq9FN1uDKWswQwgSCKGcfCNEcrY5VKVUszf67X.png\"]', 'Approved by admin', 0, NULL, '2025-09-06 07:15:58', '2025-09-10 05:40:08'),
(5, 3, 5, 'completed', 'accepted', '2025-09-06 07:18:26', '2025-09-06 07:19:02', '2025-09-06 08:01:38', '[\"task-submissions\\/IVTWKZDFbZklTSdo49UE54ITB1XtzPjzzMKESjU0.png\"]', 'Approved by admin', 0, NULL, '2025-09-06 07:18:26', '2025-09-06 08:01:38'),
(6, 2, 5, 'completed', 'accepted', '2025-09-06 07:40:05', '2025-09-06 07:40:05', '2025-09-06 07:56:33', '[\"task-submissions\\/IVTWKZDFbZklTSdo49UE54ITB1XtzPjzzMKESjU0.png\"]', 'Approved by admin', 0, NULL, '2025-09-06 07:40:05', '2025-09-06 07:56:33'),
(7, 5, 5, 'completed', 'accepted', '2025-09-06 08:15:16', '2025-09-10 05:14:31', '2025-09-10 05:15:05', '[\"task-submissions\\/4iaElVb6MZPb11erd9YpLkpulj63o9WoShjpddtb.png\"]', 'Approved by admin', 1, 'eww', '2025-09-06 08:15:16', '2025-09-10 05:15:05'),
(8, 6, 6, 'completed', 'accepted', '2025-09-10 05:42:38', '2025-09-10 05:45:19', '2025-09-10 05:45:37', '[\"task-submissions\\/RYDwXBB1l2elrvR9AzqfgtDO4abS3jBVEQYmbLtG.png\"]', 'Approved by admin', 1, 'sus', '2025-09-10 05:42:38', '2025-09-10 05:45:37'),
(9, 4, 6, 'completed', 'accepted', '2025-09-10 05:47:35', '2025-09-10 05:48:43', '2025-09-10 05:49:00', '[\"task-submissions\\/S5hP9Nsp6Vu2nESo3uMyu22OwD4HlJoNmQDHo82C.png\"]', 'Approved by admin', 1, 'submit more', '2025-09-10 05:47:35', '2025-09-10 05:49:00'),
(10, 8, 5, 'completed', 'accepted', '2025-09-21 04:20:27', '2025-09-21 04:53:26', '2025-09-21 04:57:45', '[]', 'Approved by admin', 0, NULL, '2025-09-21 04:20:27', '2025-09-21 04:57:45'),
(11, 8, 6, 'completed', 'accepted', '2025-09-21 04:21:03', '2025-09-21 04:21:30', '2025-09-21 04:22:40', '[\"task-submissions\\/aJJDmAd7nlGmEDJQD5gLQYL9M0FiCRG33my0uq46.png\"]', 'Approved by admin', 0, NULL, '2025-09-21 04:21:03', '2025-09-21 04:22:40'),
(12, 9, 5, 'completed', 'accepted', '2025-09-21 04:52:01', '2025-09-21 05:14:39', '2025-09-21 05:15:15', '[]', 'Approved by admin', 0, NULL, '2025-09-21 04:52:01', '2025-09-21 05:15:15'),
(13, 5, 7, 'completed', 'accepted', '2025-09-21 05:00:04', '2025-09-21 05:10:52', '2025-09-21 05:11:19', '[]', 'Approved by admin', 0, NULL, '2025-09-21 05:00:04', '2025-09-21 05:11:19'),
(14, 8, 7, 'completed', 'accepted', '2025-09-21 05:00:30', '2025-09-21 05:27:38', '2025-09-21 05:27:55', '[]', 'Approved by admin', 0, NULL, '2025-09-21 05:00:30', '2025-09-21 05:27:55'),
(15, 10, 7, 'completed', 'accepted', '2025-09-21 06:06:50', '2025-09-21 06:39:38', '2025-09-21 06:39:54', '[]', 'Approved by admin', 0, NULL, '2025-09-21 06:06:50', '2025-09-21 06:39:54'),
(16, 12, 5, 'completed', 'accepted', '2025-09-24 05:05:58', '2025-10-01 04:46:15', '2025-10-01 05:07:13', '[\"task-submissions\\/fKkk6kruPYxkgQfi3eZT4ejkaQlVplvO2VwNAvbD.jpg\",\"task-submissions\\/FkfVQ7ay5MAKOQkFgB5s2gaLMO2wYCN80oIghGd4.png\",\"task-submissions\\/uNEk7YDcJzYOfBzgNKMM2cxGD5FlXEGLi4mhbnvV.png\"]', NULL, 0, NULL, '2025-09-24 05:05:58', '2025-10-01 05:07:13'),
(17, 12, 6, 'completed', 'accepted', '2025-09-24 05:06:16', NULL, '2025-10-01 05:07:13', NULL, NULL, 0, NULL, '2025-09-24 05:06:16', '2025-10-01 05:07:13'),
(18, 14, 5, 'completed', 'accepted', '2025-10-01 05:46:30', '2025-10-01 06:02:53', '2025-10-03 01:51:26', '[\"task-submissions\\/qtCC54nHFpNfJ1zKm0xoBnj7p216bTTHEXc1YX9S.jpg\",\"task-submissions\\/Xkl58KLFUyBygw1ZnckO86AVzuA8VMIp0bPNceeV.jpg\",\"task-submissions\\/4mAOacIFWpfzvdSMzL9to44F8JI3NXIpilABlLaV.jpg\"]', NULL, 0, NULL, '2025-10-01 05:46:30', '2025-10-03 01:51:26'),
(19, 14, 7, 'completed', 'submitted_proof', '2025-10-01 06:48:42', '2025-10-01 06:55:49', '2025-10-03 01:51:26', '[\"task-submissions\\/dCvTpCbIa8Os0N8gRJeToM62CuriIemvK3D9P9av.jpg\",\"task-submissions\\/awNXhs5e31id7qvRsu1VOyafJOYv3NNCWAJdOEgj.jpg\",\"task-submissions\\/RKNfSLXKbA1TioMsCSpPhdbs0T2dCyqNngubU6iZ.jpg\"]', NULL, 0, NULL, '2025-10-01 06:48:42', '2025-10-03 01:51:26'),
(20, 13, 6, 'completed', 'submitted_proof', '2025-10-01 07:03:44', '2025-10-01 07:05:53', '2025-10-04 02:41:28', '[\"task-submissions\\/oNXIutT2bw9goMukCJR3oILjpGUwL9tykGhDylvb.jpg\",\"task-submissions\\/7mr7PrD9j79NdEcF4vUQCLudvWtX5UGUgRyjoIZu.jpg\",\"task-submissions\\/3E1WEqHSd5ZupNkVWkvUJr5hCPLfkv7woFximlBZ.jpg\"]', NULL, 0, NULL, '2025-10-01 07:03:44', '2025-10-04 02:41:28'),
(21, 16, 5, 'completed', 'submitted_proof', '2025-10-06 04:50:45', '2025-10-06 04:52:17', '2025-10-07 03:39:18', '[\"task-submissions\\/wKjEE5wjtTvQAhF6DTYJtqEp2nXs3gQbtVfTNHlx.jpg\",\"task-submissions\\/Y0iNJ03pHV9EFaH6sMjJq35wILHM5vGaAwm89FRH.jpg\"]', NULL, 0, NULL, '2025-10-06 04:50:45', '2025-10-07 03:39:18'),
(22, 18, 9, 'completed', 'submitted_proof', '2025-10-28 04:45:16', '2025-10-28 04:52:43', '2025-10-28 04:53:03', '[\"task-submissions\\/Q3DjnUiaX1seMyV8cLQIeOuqe0y4hbHeYyyj4Uo1.jpg\",\"task-submissions\\/Zmr2WxKBW13Zr6EE6YrhiCkZNnHzd9aW3p9meP32.jpg\"]', 'Approved by admin', 0, NULL, '2025-10-28 04:45:16', '2025-10-28 04:53:03'),
(23, 20, 11, 'completed', 'submitted_proof', '2025-10-29 06:17:34', '2025-10-29 06:32:42', '2025-10-29 06:33:04', '[\"task-submissions\\/lZXV9bXVsfNcjYufklm9LUt4LnJszsGXxJUEYfps.jpg\",\"task-submissions\\/DWU0XZ7g0ZN4C9y6xuWkyf3iIgEi6HMHpnfwmYI8.jpg\"]', 'Approved by admin', 0, NULL, '2025-10-29 06:17:34', '2025-10-29 06:33:04'),
(24, 23, 5, 'completed', 'accepted', '2025-10-29 06:39:02', NULL, '2025-10-31 01:36:01', NULL, NULL, 0, NULL, '2025-10-29 06:39:02', '2025-10-31 01:36:01'),
(25, 24, 5, 'assigned', 'accepted', '2025-10-31 01:43:33', NULL, NULL, NULL, NULL, 0, NULL, '2025-10-31 01:43:33', '2025-10-31 01:43:33');

-- --------------------------------------------------------

--
-- Table structure for table `task_submission`
--

CREATE TABLE `task_submission` (
  `submissionId` bigint(20) UNSIGNED NOT NULL,
  `FK1_taskId` bigint(20) UNSIGNED NOT NULL,
  `FK2_userId` bigint(20) UNSIGNED NOT NULL,
  `proof_details` text NOT NULL,
  `submission_date` datetime NOT NULL,
  `verification_status` varchar(30) NOT NULL DEFAULT 'pending',
  `verification_date` datetime DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` bigint(20) UNSIGNED NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `middleName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `role` varchar(30) NOT NULL DEFAULT 'user',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `date_registered` date NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `firstName`, `middleName`, `lastName`, `email`, `password`, `points`, `role`, `status`, `date_registered`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(4, 'admin', NULL, 'john', 'admin@email.com', '$2y$12$O3jb/2kBP6A/O6P5IGYRtu2/x6xfoQEpNhzlLl3/v7AnPSHAvXkJC', 0, 'admin', 'active', '2025-09-01', NULL, NULL, '2025-09-01 06:42:30', '2025-09-01 06:42:30'),
(5, 'Allen', NULL, 'Rafaela', 'allenkimrafaela@gmail.com', '$2y$12$FbyrySawbj4pbKGbsaBgiekXhxBxmhPk6ojKggq/yoDuPy49GOtM2', 6, 'user', 'active', '2025-09-01', NULL, NULL, '2025-09-01 06:43:19', '2025-10-31 03:33:44'),
(6, 'John', NULL, 'Doe', 'j.doe@email.com', '$2y$12$Fny4WoqDJmzam2YRY46fFuvpcMSOcl0GjnsgulecFw50gvzpvVIbG', 12, 'user', 'active', '2025-09-02', NULL, NULL, '2025-09-02 04:42:06', '2025-10-29 07:02:07'),
(7, 'Jane', NULL, 'Doe', 'jane.d@email.com', '$2y$12$br36ZSNuyogIihQHXo97Vu78VCzzDu/84BJqs.1gGIXr8XCvfJawO', 36, 'user', 'active', '2025-09-21', NULL, NULL, '2025-09-21 04:59:15', '2025-09-21 06:39:54'),
(8, 'Aldrian', NULL, 'Bahan', 'bahan@gmail.com', '$2y$12$jJWTu6ZFaah.LlawKHV1oeEWdcIuYM/VPgA9qOgRXM8ZZQ0oKzAAK', 0, 'user', 'suspended', '2025-10-07', NULL, NULL, '2025-10-07 01:52:58', '2025-10-31 03:10:06'),
(9, 'Damien', NULL, 'Caumeran', 'damskie123@email.com', '$2y$12$6Cqz0bquxvoP1u6EjvBNo.ArkVnnW/K1x0i3w/lkXlL4j9wS31QDO', 20, 'user', 'active', '2025-10-28', NULL, NULL, '2025-10-28 04:02:46', '2025-10-28 04:53:03'),
(10, 'Jhon Richmon', NULL, 'Solon', 'jhon@email.com', '$2y$12$I5UHhuVKGcZNBXh2OfEkRul4L29tN54QAL8tp1lpB3Qwl4e6hsBBS', 0, 'user', 'active', '2025-10-29', NULL, NULL, '2025-10-29 04:13:12', '2025-10-29 04:13:12'),
(11, 'jarom', NULL, 'bustillo', 'jarom@email.com', '$2y$12$tKdah8AJhpbCyovIfjzLQetox3e42VSjqTdb50u/andGIMW42c76O', 18, 'user', 'active', '2025-10-29', NULL, NULL, '2025-10-29 06:04:41', '2025-10-29 06:57:10'),
(13, 'Aldrian', NULL, 'Bahan', 'bahan@email.com', '$2y$12$YWoaF9FCRlhSUccdvnTOB.1wo4u9kL/z6Pq7gHOS3M70XWBNI8gP6', 0, 'user', 'active', '2025-10-31', NULL, NULL, '2025-10-31 03:06:02', '2025-10-31 03:06:02');

-- --------------------------------------------------------

--
-- Table structure for table `user_incident_reports`
--

CREATE TABLE `user_incident_reports` (
  `reportId` bigint(20) UNSIGNED NOT NULL,
  `FK1_reporterId` bigint(20) UNSIGNED NOT NULL,
  `FK2_reportedUserId` bigint(20) UNSIGNED NOT NULL,
  `FK3_taskId` bigint(20) UNSIGNED DEFAULT NULL,
  `incident_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `evidence` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `FK4_moderatorId` bigint(20) UNSIGNED DEFAULT NULL,
  `moderator_notes` text DEFAULT NULL,
  `action_taken` varchar(50) DEFAULT NULL,
  `moderation_date` datetime DEFAULT NULL,
  `report_date` datetime NOT NULL DEFAULT '2025-10-03 10:13:58',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_incident_reports`
--

INSERT INTO `user_incident_reports` (`reportId`, `FK1_reporterId`, `FK2_reportedUserId`, `FK3_taskId`, `incident_type`, `description`, `evidence`, `status`, `FK4_moderatorId`, `moderator_notes`, `action_taken`, `moderation_date`, `report_date`, `created_at`, `updated_at`) VALUES
(1, 7, 5, 12, 'abuse', 'asdasdasda', 'adasdsads', 'under_review', 4, NULL, 'warning', '2025-10-04 11:33:47', '2025-10-04 11:32:49', '2025-10-04 03:32:49', '2025-10-04 03:33:47'),
(2, 5, 9, 3, 'non_participation', 'dadsdadsadasd', 'Image: incident_evidence/2/2MPKqe93JrYRPQlByI9RYMEVzZnAeGn20GsjwBnL.jpg', 'dismissed', 4, 'not enough evidence', 'no_action', '2025-10-28 13:26:30', '2025-10-28 13:24:39', '2025-10-28 05:24:39', '2025-10-28 05:26:30'),
(3, 5, 11, 20, 'abuse', 'asdsadasdsadsadad', NULL, 'under_review', 4, 'sadasdsadsadsa', 'warning', '2025-10-29 14:44:46', '2025-10-29 14:43:10', '2025-10-29 06:43:10', '2025-10-29 06:44:46'),
(4, 5, 6, 3, 'non_participation', 'fdsfdsfdq32', 'Image: incident_evidence/4/zjwFwoDVfPx5BZeC6OsgLTGoUNw6j5QP6crz0AuU.jpg', 'under_review', 4, 'dsadasd', 'warning', '2025-10-31 11:31:38', '2025-10-31 11:27:34', '2025-10-31 03:27:34', '2025-10-31 03:31:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `FK1_userId` bigint(20) UNSIGNED NOT NULL,
  `FK2_roleId` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedbackId`),
  ADD KEY `feedback_fk1_userid_foreign` (`FK1_userId`),
  ADD KEY `feedback_fk2_taskid_foreign` (`FK2_taskId`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationId`),
  ADD KEY `notifications_fk1_userid_foreign` (`FK1_userId`);

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
-- Indexes for table `points_history`
--
ALTER TABLE `points_history`
  ADD PRIMARY KEY (`historyId`),
  ADD KEY `points_history_fk1_userid_foreign` (`FK1_userId`),
  ADD KEY `points_history_fk2_submissionid_foreign` (`FK2_submissionId`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`reportId`),
  ADD KEY `reports_fk1_userid_foreign` (`FK1_userId`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`rewardId`);

--
-- Indexes for table `reward_redemption`
--
ALTER TABLE `reward_redemption`
  ADD PRIMARY KEY (`redemptionId`),
  ADD KEY `reward_redemption_fk1_rewardid_foreign` (`FK1_rewardId`),
  ADD KEY `reward_redemption_fk2_userid_foreign` (`FK2_userId`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`roleId`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tap_nominations`
--
ALTER TABLE `tap_nominations`
  ADD PRIMARY KEY (`nominationId`),
  ADD KEY `tap_nominations_fk1_taskid_foreign` (`FK1_taskId`),
  ADD KEY `tap_nominations_fk2_nominatorid_foreign` (`FK2_nominatorId`),
  ADD KEY `tap_nominations_fk3_nomineeid_foreign` (`FK3_nomineeId`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`taskId`),
  ADD KEY `tasks_fk1_userid_foreign` (`FK1_userId`);

--
-- Indexes for table `task_assignment`
--
ALTER TABLE `task_assignment`
  ADD PRIMARY KEY (`assignmentId`),
  ADD KEY `task_assignment_fk1_userid_foreign` (`FK1_userId`),
  ADD KEY `task_assignment_fk2_taskid_foreign` (`FK2_taskId`);

--
-- Indexes for table `task_assignments`
--
ALTER TABLE `task_assignments`
  ADD PRIMARY KEY (`assignmentId`),
  ADD UNIQUE KEY `task_assignments_taskid_userid_unique` (`taskId`,`userId`),
  ADD KEY `task_assignments_userid_foreign` (`userId`);

--
-- Indexes for table `task_submission`
--
ALTER TABLE `task_submission`
  ADD PRIMARY KEY (`submissionId`),
  ADD KEY `task_submission_fk1_taskid_foreign` (`FK1_taskId`),
  ADD KEY `task_submission_fk2_userid_foreign` (`FK2_userId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_incident_reports`
--
ALTER TABLE `user_incident_reports`
  ADD PRIMARY KEY (`reportId`),
  ADD KEY `user_incident_reports_fk1_reporterid_foreign` (`FK1_reporterId`),
  ADD KEY `user_incident_reports_fk2_reporteduserid_foreign` (`FK2_reportedUserId`),
  ADD KEY `user_incident_reports_fk3_taskid_foreign` (`FK3_taskId`),
  ADD KEY `user_incident_reports_fk4_moderatorid_foreign` (`FK4_moderatorId`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`FK1_userId`,`FK2_roleId`),
  ADD KEY `user_roles_fk2_roleid_foreign` (`FK2_roleId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedbackId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `points_history`
--
ALTER TABLE `points_history`
  MODIFY `historyId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `reportId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `rewardId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reward_redemption`
--
ALTER TABLE `reward_redemption`
  MODIFY `redemptionId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `roleId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tap_nominations`
--
ALTER TABLE `tap_nominations`
  MODIFY `nominationId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `taskId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `task_assignment`
--
ALTER TABLE `task_assignment`
  MODIFY `assignmentId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_assignments`
--
ALTER TABLE `task_assignments`
  MODIFY `assignmentId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `task_submission`
--
ALTER TABLE `task_submission`
  MODIFY `submissionId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_incident_reports`
--
ALTER TABLE `user_incident_reports`
  MODIFY `reportId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_fk1_userid_foreign` FOREIGN KEY (`FK1_userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_fk2_taskid_foreign` FOREIGN KEY (`FK2_taskId`) REFERENCES `tasks` (`taskId`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_fk1_userid_foreign` FOREIGN KEY (`FK1_userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `points_history`
--
ALTER TABLE `points_history`
  ADD CONSTRAINT `points_history_fk1_userid_foreign` FOREIGN KEY (`FK1_userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `points_history_fk2_submissionid_foreign` FOREIGN KEY (`FK2_submissionId`) REFERENCES `task_submission` (`submissionId`) ON DELETE SET NULL;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_fk1_userid_foreign` FOREIGN KEY (`FK1_userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `reward_redemption`
--
ALTER TABLE `reward_redemption`
  ADD CONSTRAINT `reward_redemption_fk1_rewardid_foreign` FOREIGN KEY (`FK1_rewardId`) REFERENCES `rewards` (`rewardId`) ON DELETE CASCADE,
  ADD CONSTRAINT `reward_redemption_fk2_userid_foreign` FOREIGN KEY (`FK2_userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `tap_nominations`
--
ALTER TABLE `tap_nominations`
  ADD CONSTRAINT `tap_nominations_fk1_taskid_foreign` FOREIGN KEY (`FK1_taskId`) REFERENCES `tasks` (`taskId`) ON DELETE CASCADE,
  ADD CONSTRAINT `tap_nominations_fk2_nominatorid_foreign` FOREIGN KEY (`FK2_nominatorId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `tap_nominations_fk3_nomineeid_foreign` FOREIGN KEY (`FK3_nomineeId`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_fk1_userid_foreign` FOREIGN KEY (`FK1_userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `task_assignment`
--
ALTER TABLE `task_assignment`
  ADD CONSTRAINT `task_assignment_fk1_userid_foreign` FOREIGN KEY (`FK1_userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_assignment_fk2_taskid_foreign` FOREIGN KEY (`FK2_taskId`) REFERENCES `tasks` (`taskId`) ON DELETE CASCADE;

--
-- Constraints for table `task_assignments`
--
ALTER TABLE `task_assignments`
  ADD CONSTRAINT `task_assignments_taskid_foreign` FOREIGN KEY (`taskId`) REFERENCES `tasks` (`taskId`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_assignments_userid_foreign` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `task_submission`
--
ALTER TABLE `task_submission`
  ADD CONSTRAINT `task_submission_fk1_taskid_foreign` FOREIGN KEY (`FK1_taskId`) REFERENCES `tasks` (`taskId`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_submission_fk2_userid_foreign` FOREIGN KEY (`FK2_userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `user_incident_reports`
--
ALTER TABLE `user_incident_reports`
  ADD CONSTRAINT `user_incident_reports_fk1_reporterid_foreign` FOREIGN KEY (`FK1_reporterId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_incident_reports_fk2_reporteduserid_foreign` FOREIGN KEY (`FK2_reportedUserId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_incident_reports_fk3_taskid_foreign` FOREIGN KEY (`FK3_taskId`) REFERENCES `tasks` (`taskId`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_incident_reports_fk4_moderatorid_foreign` FOREIGN KEY (`FK4_moderatorId`) REFERENCES `users` (`userId`) ON DELETE SET NULL;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_fk1_userid_foreign` FOREIGN KEY (`FK1_userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_fk2_roleid_foreign` FOREIGN KEY (`FK2_roleId`) REFERENCES `roles` (`roleId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
