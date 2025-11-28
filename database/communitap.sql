-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2025 at 04:46 PM
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
  `rating` decimal(3,1) NOT NULL,
  `comment` text NOT NULL,
  `feedback_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackId`, `FK1_userId`, `FK2_taskId`, `rating`, `comment`, `feedback_date`, `created_at`, `updated_at`) VALUES
(1, 5, 3, 3.0, 'good experience', '2025-09-06 15:30:13', '2025-09-06 07:30:13', '2025-09-06 07:30:13'),
(2, 5, 14, 5.0, 'fun task', '2025-10-04 11:47:55', '2025-10-04 03:47:55', '2025-10-04 03:47:55'),
(3, 5, 16, 4.0, 'AHAHAHAHA', '2025-10-28 13:28:12', '2025-10-28 05:28:12', '2025-10-28 05:28:12'),
(4, 6, 3, 4.0, 'good', '2025-10-28 13:30:02', '2025-10-28 05:30:02', '2025-10-28 05:30:02'),
(5, 11, 20, 5.0, 'Enjoyed the task!!!', '2025-10-29 14:46:46', '2025-10-29 06:46:46', '2025-10-29 06:46:46'),
(6, 5, 23, 4.0, 'fdsfsdfadsa', '2025-10-31 11:33:44', '2025-10-31 03:33:44', '2025-10-31 03:33:44');

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
(9, '0001_01_01_000006_create_tasks_table', 2),
(10, '0001_01_01_000007_create_task_submission_table', 2),
(12, '0001_01_01_000009_create_feedback_table', 2),
(14, '0001_01_01_000011_create_tap_nominations_table', 2),
(15, '0001_01_01_000012_create_rewards_table', 2),
(16, '0001_01_01_000013_create_notifications_table', 2),
(17, '0001_01_01_000014_create_reward_redemption_table', 2),
(18, '0001_01_01_000015_create_points_history_table', 2),
(19, '2025_09_06_141922_modify_tasks_table_make_user_id_nullable', 3),
(20, '2025_09_06_142246_create_task_assignments_table', 4),
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
(33, '2025_10_29_000001_add_coupon_image_path_to_reward_redemption_table', 15),
(36, '2025_11_10_000500_add_data_column_to_notifications_table', 17),
(37, '2025_10_06_000001_add_image_path_to_rewards_table', 18),
(38, '2025_11_15_121049_add_uncompleted_status_and_reminder_to_task_assignments', 18),
(39, '2025_11_22_232311_add_profile_picture_to_users_table', 19),
(40, '2025_11_23_012421_add_deactivated_at_to_tasks_table', 19),
(41, '2025_11_24_000001_update_feedback_rating_precision', 19),
(42, '2025_11_24_130000_add_coupon_code_to_reward_redemption_table', 19),
(43, '2025_11_28_232205_cleanup_unused_tables_and_columns', 20),
(44, '2025_11_28_232450_remove_roles_tables_and_convert_role_to_enum', 20);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notificationId` bigint(20) UNSIGNED NOT NULL,
  `FK1_userId` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(30) NOT NULL,
  `message` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `status` varchar(30) NOT NULL DEFAULT 'unread',
  `created_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notificationId`, `FK1_userId`, `type`, `message`, `data`, `status`, `created_date`, `created_at`, `updated_at`) VALUES
(1, 4, 'task_published', 'New task available: \"test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/31\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:09:08', '2025-11-10 03:09:08', '2025-11-10 03:33:02'),
(2, 5, 'task_published', 'New task available: \"test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/31\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:09:08', '2025-11-10 03:09:08', '2025-11-10 03:13:55'),
(3, 6, 'task_published', 'New task available: \"test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/31\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:09:08', '2025-11-10 03:09:08', '2025-11-10 03:30:45'),
(4, 7, 'task_published', 'New task available: \"test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/31\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:09:08', '2025-11-10 03:09:08', '2025-11-10 03:42:31'),
(5, 9, 'task_published', 'New task available: \"test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/31\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:09:08', '2025-11-10 03:09:08', '2025-11-25 06:42:41'),
(6, 10, 'task_published', 'New task available: \"test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/31\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 11:09:08', '2025-11-10 03:09:08', '2025-11-10 03:09:08'),
(7, 11, 'task_published', 'New task available: \"test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/31\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 11:09:08', '2025-11-10 03:09:08', '2025-11-10 03:09:08'),
(8, 13, 'task_published', 'New task available: \"test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/31\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 11:09:08', '2025-11-10 03:09:08', '2025-11-10 03:09:08'),
(9, 5, 'task_proposal_approved', 'Your task proposal \"notif admin test\" was approved.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/30\",\"description\":\"The task is ready to be reviewed for publishing.\"}', 'read', '2025-11-10 11:09:42', '2025-11-10 03:09:42', '2025-11-10 03:13:55'),
(10, 5, 'task_proposal_published', 'Your task \"notif admin test\" is live!', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/30\",\"description\":\"Participants can now join. Monitor submissions regularly.\"}', 'read', '2025-11-10 11:09:47', '2025-11-10 03:09:47', '2025-11-10 03:13:55'),
(11, 4, 'task_published', 'New task available: \"notif admin test\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/30\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:09:47', '2025-11-10 03:09:47', '2025-11-10 03:33:02'),
(12, 5, 'task_published', 'New task available: \"notif admin test\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/30\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:09:47', '2025-11-10 03:09:47', '2025-11-10 03:13:55'),
(13, 6, 'task_published', 'New task available: \"notif admin test\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/30\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:09:47', '2025-11-10 03:09:47', '2025-11-10 03:30:45'),
(14, 7, 'task_published', 'New task available: \"notif admin test\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/30\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:09:47', '2025-11-10 03:09:47', '2025-11-10 03:42:31'),
(15, 9, 'task_published', 'New task available: \"notif admin test\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/30\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:09:47', '2025-11-10 03:09:47', '2025-11-25 06:42:41'),
(16, 10, 'task_published', 'New task available: \"notif admin test\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/30\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 11:09:47', '2025-11-10 03:09:47', '2025-11-10 03:09:47'),
(17, 11, 'task_published', 'New task available: \"notif admin test\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/30\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 11:09:47', '2025-11-10 03:09:47', '2025-11-10 03:09:47'),
(18, 13, 'task_published', 'New task available: \"notif admin test\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/30\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 11:09:47', '2025-11-10 03:09:47', '2025-11-10 03:09:47'),
(19, 5, 'task_proposal_approved', 'Your task proposal \"another test notif\" was approved.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"The task is ready to be reviewed for publishing.\"}', 'read', '2025-11-10 11:15:08', '2025-11-10 03:15:08', '2025-11-10 03:30:15'),
(20, 5, 'task_proposal_published', 'Your task \"another test notif\" is live!', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Participants can now join. Monitor submissions regularly.\"}', 'read', '2025-11-10 11:15:22', '2025-11-10 03:15:22', '2025-11-10 03:30:15'),
(21, 4, 'task_published', 'New task available: \"another test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:15:22', '2025-11-10 03:15:22', '2025-11-10 03:33:02'),
(22, 6, 'task_published', 'New task available: \"another test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:15:22', '2025-11-10 03:15:22', '2025-11-10 03:30:45'),
(23, 7, 'task_published', 'New task available: \"another test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:15:22', '2025-11-10 03:15:22', '2025-11-10 03:42:31'),
(24, 9, 'task_published', 'New task available: \"another test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 11:15:22', '2025-11-10 03:15:22', '2025-11-25 06:42:41'),
(25, 10, 'task_published', 'New task available: \"another test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 11:15:22', '2025-11-10 03:15:22', '2025-11-10 03:15:22'),
(26, 11, 'task_published', 'New task available: \"another test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 11:15:22', '2025-11-10 03:15:22', '2025-11-10 03:15:22'),
(27, 13, 'task_published', 'New task available: \"another test notif\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 11:15:22', '2025-11-10 03:15:22', '2025-11-10 03:15:22'),
(28, 6, 'reward_redeemed', 'You redeemed \"notif reward\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/my-redemptions\",\"description\":\"Coupon code: PGT5UHZFNC. Present this to claim your reward.\"}', 'read', '2025-11-10 11:23:57', '2025-11-10 03:23:57', '2025-11-10 03:30:45'),
(29, 6, 'task_assigned', 'You\'re now assigned to \"another test notif\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Track progress and submit proof when you are done.\"}', 'read', '2025-11-10 11:29:47', '2025-11-10 03:29:47', '2025-11-10 03:30:45'),
(30, 5, 'task_participant_joined', 'John Doe joined your task \"another test notif\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Review submissions to award points when the task is done.\"}', 'read', '2025-11-10 11:29:47', '2025-11-10 03:29:47', '2025-11-10 03:30:15'),
(31, 5, 'task_progress_updated', 'John updated progress on \"another test notif\" to working.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Review the task progress and provide guidance if needed.\"}', 'read', '2025-11-10 11:33:38', '2025-11-10 03:33:38', '2025-11-10 03:34:57'),
(32, 5, 'task_progress_updated', 'John updated progress on \"another test notif\" to done.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Review the task progress and provide guidance if needed.\"}', 'read', '2025-11-10 11:34:05', '2025-11-10 03:34:05', '2025-11-10 03:34:57'),
(33, 5, 'task_submission_pending', 'John Doe submitted proof for \"another test notif\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/submissions\\/26\",\"description\":\"Review the submission and approve or reject it.\"}', 'read', '2025-11-10 11:34:17', '2025-11-10 03:34:17', '2025-11-10 03:34:57'),
(34, 6, 'task_submission_approved', 'Your submission for \"another test notif\" was approved!', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\",\"description\":\"Points have been added to your balance.\"}', 'read', '2025-11-10 11:35:06', '2025-11-10 03:35:06', '2025-11-10 03:35:37'),
(35, 4, 'task_proposal_submitted', 'New task proposal submitted: \"admin notif test\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/tasks?status=pending\",\"description\":\"Review and approve or reject the proposal.\"}', 'read', '2025-11-10 12:05:07', '2025-11-10 04:05:07', '2025-11-10 04:24:26'),
(36, 5, 'task_proposal_rejected', 'Your task proposal \"admin notif test\" was rejected.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/my-uploads\",\"description\":\"Review admin feedback and resubmit if needed.\"}', 'read', '2025-11-10 12:05:34', '2025-11-10 04:05:34', '2025-11-10 04:08:10'),
(37, 5, 'task_assigned', 'You\'re now assigned to \"test notif\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/31\",\"description\":\"Track progress and submit proof when you are done.\"}', 'read', '2025-11-10 12:06:03', '2025-11-10 04:06:03', '2025-11-10 04:08:10'),
(38, 4, 'task_submission_admin_review', 'Allen Rafaela submitted proof for \"test notif\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/task-submissions\",\"description\":\"Review the submission and award points if approved.\"}', 'read', '2025-11-10 12:06:32', '2025-11-10 04:06:32', '2025-11-10 04:24:26'),
(39, 5, 'reward_redeemed', 'You redeemed \"notif reward\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/my-redemptions\",\"description\":\"Coupon code: 6NLHLWKPH6. Present this to claim your reward.\"}', 'read', '2025-11-10 12:07:15', '2025-11-10 04:07:15', '2025-11-10 04:08:10'),
(40, 4, 'reward_claim_submitted', 'Allen Rafaela claimed the reward \"notif reward\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/rewards\",\"description\":\"Coupon code: 6NLHLWKPH6. Ensure fulfilment is tracked.\"}', 'read', '2025-11-10 12:07:15', '2025-11-10 04:07:15', '2025-11-10 04:24:26'),
(41, 4, 'incident_report_submitted', 'Allen Rafaela submitted an incident report.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/incident-reports\",\"description\":\"Review the new report and take appropriate action.\"}', 'read', '2025-11-10 12:08:04', '2025-11-10 04:08:04', '2025-11-10 04:24:26'),
(42, 4, 'task_proposal_submitted', 'New task proposal submitted: \"another admin test\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/tasks\\/35\",\"description\":\"Review and approve or reject the proposal.\"}', 'read', '2025-11-10 12:22:37', '2025-11-10 04:22:37', '2025-11-10 04:24:26'),
(43, 5, 'task_assigned', 'You\'re now assigned to \"Notif TEst task\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/29\",\"description\":\"Track progress and submit proof when you are done.\"}', 'read', '2025-11-10 12:23:05', '2025-11-10 04:23:05', '2025-11-16 06:00:14'),
(44, 4, 'task_submission_admin_review', 'Allen Rafaela submitted proof for \"Notif TEst task\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/task-submissions\\/28\",\"description\":\"Review the submission and award points if approved.\"}', 'read', '2025-11-10 12:23:26', '2025-11-10 04:23:26', '2025-11-10 04:24:26'),
(45, 4, 'incident_report_submitted', 'Allen Rafaela submitted an incident report.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/incident-reports\\/6\",\"description\":\"Review the new report and take appropriate action.\"}', 'read', '2025-11-10 12:23:55', '2025-11-10 04:23:55', '2025-11-10 04:24:26'),
(46, 5, 'incident_report_update', 'Your incident report #6 is now under_review.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/incident-reports\\/6\",\"description\":null}', 'read', '2025-11-10 12:24:21', '2025-11-10 04:24:21', '2025-11-16 06:00:14'),
(47, 5, 'task_published', 'New task available: \"testtest123123\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/36\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 12:25:23', '2025-11-10 04:25:23', '2025-11-16 06:00:14'),
(48, 6, 'task_published', 'New task available: \"testtest123123\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/36\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 12:25:23', '2025-11-10 04:25:23', '2025-11-10 04:25:23'),
(49, 7, 'task_published', 'New task available: \"testtest123123\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/36\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 12:25:23', '2025-11-10 04:25:23', '2025-11-16 06:51:36'),
(50, 9, 'task_published', 'New task available: \"testtest123123\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/36\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-10 12:25:23', '2025-11-10 04:25:23', '2025-11-25 06:42:41'),
(51, 10, 'task_published', 'New task available: \"testtest123123\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/36\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 12:25:23', '2025-11-10 04:25:23', '2025-11-10 04:25:23'),
(52, 11, 'task_published', 'New task available: \"testtest123123\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/36\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 12:25:23', '2025-11-10 04:25:23', '2025-11-10 04:25:23'),
(53, 13, 'task_published', 'New task available: \"testtest123123\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/36\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-10 12:25:23', '2025-11-10 04:25:23', '2025-11-10 04:25:23'),
(54, 6, 'tap_nomination_received', 'Allen nominated you for \"tap&pass notif test\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tap-nominations\",\"description\":\"Accept the nomination to join the task and earn extra points.\"}', 'unread', '2025-11-10 12:49:11', '2025-11-10 04:49:11', '2025-11-10 04:49:11'),
(55, 5, 'tap_nomination_accepted', 'John accepted your nomination for \"tap&pass notif test\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tap-nominations\",\"description\":\"Keep cheering them on!\"}', 'read', '2025-11-10 12:49:52', '2025-11-10 04:49:52', '2025-11-16 06:00:14'),
(56, 5, 'task_published', 'New task available: \"time test task\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/38\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-16 13:59:49', '2025-11-16 05:59:49', '2025-11-16 06:00:14'),
(57, 7, 'task_published', 'New task available: \"time test task\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/38\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-16 13:59:49', '2025-11-16 05:59:49', '2025-11-16 06:51:36'),
(58, 9, 'task_published', 'New task available: \"time test task\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/38\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-16 13:59:49', '2025-11-16 05:59:49', '2025-11-25 06:42:41'),
(59, 10, 'task_published', 'New task available: \"time test task\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/38\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-16 13:59:49', '2025-11-16 05:59:49', '2025-11-16 05:59:49'),
(60, 11, 'task_published', 'New task available: \"time test task\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/38\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-16 13:59:49', '2025-11-16 05:59:49', '2025-11-16 05:59:49'),
(61, 13, 'task_published', 'New task available: \"time test task\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/38\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-16 13:59:49', '2025-11-16 05:59:49', '2025-11-16 05:59:49'),
(62, 5, 'task_assigned', 'You\'re now assigned to \"time test task\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/38\",\"description\":\"Track progress and submit proof when you are done.\"}', 'read', '2025-11-16 14:00:18', '2025-11-16 06:00:19', '2025-11-16 06:00:37'),
(63, 4, 'task_proposal_submitted', 'New task proposal submitted: \"redirect test\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/tasks\\/39\",\"description\":\"Review and approve or reject the proposal.\"}', 'read', '2025-11-16 14:13:08', '2025-11-16 06:13:08', '2025-11-16 06:50:05'),
(64, 4, 'task_proposal_submitted', 'New task proposal submitted: \"REDIRECT\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/tasks\\/40\",\"description\":\"Review and approve or reject the proposal.\"}', 'read', '2025-11-16 14:30:36', '2025-11-16 06:30:36', '2025-11-16 06:50:05'),
(65, 5, 'task_proposal_approved', 'Your task proposal \"REDIRECT1\" was approved.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/40\",\"description\":\"The task is ready to be reviewed for publishing.\"}', 'read', '2025-11-16 14:47:34', '2025-11-16 06:47:34', '2025-11-16 06:51:00'),
(66, 5, 'task_proposal_published', 'Your task proposal \"redirect_test2\" was approved and is now live!', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Participants can now join. Monitor submissions regularly.\"}', 'read', '2025-11-16 14:49:53', '2025-11-16 06:49:53', '2025-11-16 06:51:00'),
(67, 7, 'new_task_available', 'New task available: \"redirect_test2\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Join now to earn points!\"}', 'read', '2025-11-16 14:49:53', '2025-11-16 06:49:53', '2025-11-16 06:51:36'),
(68, 9, 'new_task_available', 'New task available: \"redirect_test2\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Join now to earn points!\"}', 'read', '2025-11-16 14:49:53', '2025-11-16 06:49:53', '2025-11-25 06:42:41'),
(69, 10, 'new_task_available', 'New task available: \"redirect_test2\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Join now to earn points!\"}', 'unread', '2025-11-16 14:49:53', '2025-11-16 06:49:53', '2025-11-16 06:49:53'),
(70, 11, 'new_task_available', 'New task available: \"redirect_test2\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Join now to earn points!\"}', 'unread', '2025-11-16 14:49:53', '2025-11-16 06:49:53', '2025-11-16 06:49:53'),
(71, 13, 'new_task_available', 'New task available: \"redirect_test2\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Join now to earn points!\"}', 'unread', '2025-11-16 14:49:53', '2025-11-16 06:49:53', '2025-11-16 06:49:53'),
(72, 7, 'task_assigned', 'You\'re now assigned to \"redirect_test2\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Track progress and submit proof when you are done.\"}', 'read', '2025-11-16 14:52:32', '2025-11-16 06:52:32', '2025-11-16 06:52:44'),
(73, 5, 'task_participant_joined', 'Jane Doe joined your task \"redirect_test2\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Review submissions to award points when the task is done.\"}', 'read', '2025-11-16 14:52:32', '2025-11-16 06:52:32', '2025-11-16 06:52:58'),
(74, 5, 'task_progress_updated', 'Jane updated progress on \"redirect_test2\" to on the way.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Review the task progress and provide guidance if needed.\"}', 'read', '2025-11-16 14:53:18', '2025-11-16 06:53:18', '2025-11-16 06:59:03'),
(75, 5, 'task_progress_updated', 'Jane updated progress on \"redirect_test2\" to working.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Review the task progress and provide guidance if needed.\"}', 'read', '2025-11-16 14:54:09', '2025-11-16 06:54:09', '2025-11-16 06:59:03'),
(76, 5, 'task_progress_updated', 'Jane updated progress on \"redirect_test2\" to done.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Review the task progress and provide guidance if needed.\"}', 'read', '2025-11-16 14:54:38', '2025-11-16 06:54:38', '2025-11-16 06:59:03'),
(77, 5, 'task_submission_pending', 'Jane Doe submitted proof for \"redirect_test2\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/submissions\\/31\",\"description\":\"Review the submission and approve or reject it.\"}', 'read', '2025-11-16 14:55:04', '2025-11-16 06:55:04', '2025-11-16 06:59:03'),
(78, 7, 'task_submission_approved', 'Your submission for \"redirect_test2\" was approved!', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/39\",\"description\":\"Points have been added to your balance.\"}', 'read', '2025-11-16 14:58:55', '2025-11-16 06:58:55', '2025-11-16 07:11:43'),
(79, 7, 'reward_redeemed', 'You redeemed \"1 sack of rice\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/my-redemptions\",\"description\":\"Coupon code: DP6TIVXSBC. Present this to claim your reward.\"}', 'read', '2025-11-16 08:41:21', '2025-11-16 00:41:21', '2025-11-16 00:53:43'),
(80, 4, 'reward_claim_submitted', 'Jane Doe claimed the reward \"1 sack of rice\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/rewards\",\"description\":\"Coupon code: DP6TIVXSBC. Ensure fulfilment is tracked.\"}', 'read', '2025-11-16 08:41:21', '2025-11-16 00:41:21', '2025-11-16 00:54:32'),
(81, 4, 'task_submission_admin_review', 'Allen Rafaela submitted proof for \"time test task\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/task-submissions\\/30\",\"description\":\"Review the submission and award points if approved.\"}', 'read', '2025-11-16 08:54:19', '2025-11-16 00:54:19', '2025-11-16 00:54:32'),
(82, 5, 'task_submission_approved', 'Your submission for \"time test task\" was approved!', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/38\",\"description\":\"You have reached the points cap (500 points). No points were added.\"}', 'read', '2025-11-16 08:54:44', '2025-11-16 00:54:44', '2025-11-16 00:55:09'),
(83, 5, 'reward_redeemed', 'You redeemed \"1 sack of rice\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/my-redemptions\",\"description\":\"Coupon code: YEYK4SUY4H. Present this to claim your reward.\"}', 'read', '2025-11-16 08:55:51', '2025-11-16 00:55:51', '2025-11-16 00:56:38'),
(84, 4, 'reward_claim_submitted', 'Allen Rafaela claimed the reward \"1 sack of rice\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/rewards\",\"description\":\"Coupon code: YEYK4SUY4H. Ensure fulfilment is tracked.\"}', 'read', '2025-11-16 08:55:51', '2025-11-16 00:55:51', '2025-11-25 06:40:13'),
(85, 5, 'reward_redeemed', 'You redeemed \"200 GCASH\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/my-redemptions\",\"description\":\"Coupon code: FYBUOHWCCP. Present this to claim your reward.\"}', 'read', '2025-11-16 08:56:02', '2025-11-16 00:56:02', '2025-11-16 00:56:38'),
(86, 4, 'reward_claim_submitted', 'Allen Rafaela claimed the reward \"200 GCASH\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/rewards\",\"description\":\"Coupon code: FYBUOHWCCP. Ensure fulfilment is tracked.\"}', 'read', '2025-11-16 08:56:02', '2025-11-16 00:56:02', '2025-11-25 06:40:10'),
(87, 5, 'task_published', 'New task available: \"auto complete\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/41\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-16 17:15:48', '2025-11-16 09:15:48', '2025-11-16 09:23:49'),
(88, 6, 'task_published', 'New task available: \"auto complete\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/41\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-16 17:15:48', '2025-11-16 09:15:48', '2025-11-16 09:15:48'),
(89, 7, 'task_published', 'New task available: \"auto complete\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/41\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-16 17:15:48', '2025-11-16 09:15:48', '2025-11-16 09:15:48'),
(90, 9, 'task_published', 'New task available: \"auto complete\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/41\",\"description\":\"Join now while slots are open.\"}', 'read', '2025-11-16 17:15:48', '2025-11-16 09:15:48', '2025-11-25 06:42:41'),
(91, 10, 'task_published', 'New task available: \"auto complete\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/41\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-16 17:15:48', '2025-11-16 09:15:48', '2025-11-16 09:15:48'),
(92, 11, 'task_published', 'New task available: \"auto complete\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/41\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-16 17:15:48', '2025-11-16 09:15:48', '2025-11-16 09:15:48'),
(93, 13, 'task_published', 'New task available: \"auto complete\"', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/41\",\"description\":\"Join now while slots are open.\"}', 'unread', '2025-11-16 17:15:48', '2025-11-16 09:15:48', '2025-11-16 09:15:48'),
(94, 5, 'task_assigned', 'You\'re now assigned to \"auto complete\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/41\",\"description\":\"Track progress and submit proof when you are done.\"}', 'read', '2025-11-16 17:15:58', '2025-11-16 09:15:58', '2025-11-16 09:23:49'),
(95, 4, 'task_submission_admin_review', 'Allen Rafaela submitted proof for \"auto complete\".', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/task-submissions\\/32\",\"description\":\"Review the submission and award points if approved.\"}', 'read', '2025-11-16 17:16:20', '2025-11-16 09:16:20', '2025-11-16 09:22:46'),
(96, 5, 'task_submission_approved', 'Your submission for \"auto complete\" was approved!', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/41\",\"description\":\"Points have been added to your balance. Keep up the great work!\"}', 'read', '2025-11-16 17:16:47', '2025-11-16 09:16:47', '2025-11-16 09:23:49'),
(97, 5, 'task_submission_approved', 'Your submission for \"auto complete\" was approved!', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/41\",\"description\":\"Points have been added to your balance. Keep up the great work!\"}', 'read', '2025-11-16 17:16:58', '2025-11-16 09:16:58', '2025-11-16 09:23:49'),
(98, 5, 'task_submission_approved', 'Your submission for \"auto complete\" was approved!', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/41\",\"description\":\"Points have been added to your balance. Keep up the great work!\"}', 'read', '2025-11-16 17:18:58', '2025-11-16 09:18:58', '2025-11-16 09:23:49'),
(99, 4, 'incident_report_submitted', 'Damien Caumeran submitted an incident report.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/incident-reports\\/7\",\"description\":\"Review the new report and take appropriate action.\"}', 'read', '2025-11-25 14:43:38', '2025-11-25 06:43:38', '2025-11-25 06:43:54'),
(100, 9, 'incident_report_update', 'Your incident report #7 is now resolved.', '{\"url\":\"http:\\/\\/127.0.0.1:8000\\/incident-reports\\/7\",\"description\":\"Moderator notes: I already talked to Allen and I issued a warning to him\"}', 'read', '2025-11-25 14:44:57', '2025-11-25 06:44:57', '2025-11-25 06:45:40');

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
(2, 'Kons Bahan', '200 GCASH', 'rewards/7gbM2bw1jdxHmsOzn0dHlSw9cyHxtObyaQcSTDSK.png', '200 pesos gcash', 300, 4, 'active', '2025-10-07 11:28:20', '2025-10-07 11:28:20', '2025-10-07 03:28:21', '2025-11-16 00:56:02'),
(3, 'Bahan', 'GCASH', 'rewards/LJsGmdOCqe277yrSGH89vc4iO5N8McAikuysEITC.png', 'freee cash', 20, 3, 'active', '2025-10-28 13:08:03', '2025-10-29 11:55:27', '2025-10-28 05:08:03', '2025-10-29 07:06:51'),
(5, 'Jhon\'s Sari-Sari Store', '1 sack of rice', 'rewards/cxw8H770iBiLHjMBT5qZ8Z9mZwAGin7O8hj6nFUh.jpg', '1 sack of rice', 5, 1, 'active', '2025-10-31 11:21:43', '2025-10-31 11:23:28', '2025-10-31 03:21:43', '2025-11-16 00:55:51'),
(6, 'test reward', 'notif reward', 'rewards/yUzKqw77gecYHeX8Vl365Fg78uNY1SYbX8XQGoAK.png', 'sadsadsa', 1, 0, 'active', '2025-11-10 11:23:08', '2025-11-10 12:07:08', '2025-11-10 03:23:08', '2025-11-10 04:07:15');

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
(8, 5, 5, '2025-10-31 11:24:09', 'approved', NULL, NULL, '2025-10-31 11:24:09', 'Coupon: TSCZI6LNRG', '2025-10-31 03:24:09', '2025-10-31 03:24:09'),
(9, 6, 6, '2025-11-10 11:23:57', 'approved', NULL, NULL, '2025-11-10 11:23:57', 'Coupon: PGT5UHZFNC', '2025-11-10 03:23:57', '2025-11-10 03:23:57'),
(10, 6, 5, '2025-11-10 12:07:15', 'approved', NULL, NULL, '2025-11-10 12:07:15', 'Coupon: 6NLHLWKPH6', '2025-11-10 04:07:15', '2025-11-10 04:07:15'),
(11, 5, 7, '2025-11-16 08:39:36', 'pending', NULL, NULL, NULL, NULL, '2025-11-16 00:39:36', '2025-11-16 00:39:36'),
(12, 5, 7, '2025-11-16 08:41:21', 'approved', NULL, NULL, '2025-11-16 08:41:21', 'Coupon: DP6TIVXSBC', '2025-11-16 00:41:21', '2025-11-16 00:41:21'),
(13, 5, 5, '2025-11-16 08:55:51', 'approved', NULL, NULL, '2025-11-16 08:55:51', 'Coupon: YEYK4SUY4H', '2025-11-16 00:55:51', '2025-11-16 00:55:51'),
(14, 2, 5, '2025-11-16 08:56:02', 'approved', NULL, NULL, '2025-11-16 08:56:02', 'Coupon: FYBUOHWCCP', '2025-11-16 00:56:02', '2025-11-16 00:56:02');

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
('79mtJ8pr4fFOOd8RuKHYew3xJ0jAzwKPH1l5p90u', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV0kzd29QemFmOTFXU1JVZU1VMnY0ODZaOWQ3dUhxM1Q1d05OYndudiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTA1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vcmVwb3J0cy9yZXdhcmRzP2VuZF9kYXRlPTIwMjUtMTEtMjUmcGVyaW9kPWxhc3RfMzBfZGF5cyZzdGFydF9kYXRlPTIwMjUtMTAtMjciO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=', 1764059060),
('lV5VCVYzgamvseOd9x1APq1TALX7Ju7M6GOSPF6x', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYUhxcmdFMHljZ3VLaWJSZXlObnVXQjU3aXU2YkNaUkZhd3h5REJVdCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==', 1764056125);

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
(5, 23, 11, 5, '2025-10-29 14:38:39', 'accepted', '2025-10-29 06:38:39', '2025-10-29 06:39:02'),
(6, 37, 5, 6, '2025-11-10 12:49:11', 'accepted', '2025-11-10 04:49:11', '2025-11-10 04:49:52');

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
  `deactivated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`taskId`, `FK1_userId`, `title`, `description`, `task_type`, `points_awarded`, `status`, `creation_date`, `approval_date`, `due_date`, `start_time`, `end_time`, `location`, `max_participants`, `published_date`, `deactivated_at`, `created_at`, `updated_at`) VALUES
(2, NULL, 'Test', 'Hello this is a test', 'one_time', 10, 'completed', '2025-09-06 15:10:15', NULL, '2025-09-08 00:00:00', '13:00:00', '15:00:00', 'covered court', NULL, '2025-09-06 15:10:15', NULL, '2025-09-06 07:10:15', '2025-09-24 04:52:54'),
(3, NULL, 'Test Daily', 'Daily Task test create', 'daily', 20, 'completed', '2025-09-06 15:11:14', NULL, '2025-09-09 00:00:00', '10:00:00', '12:30:00', 'barangay hall', NULL, '2025-09-06 15:11:14', NULL, '2025-09-06 07:11:14', '2025-09-24 04:52:54'),
(4, NULL, 'Test One TIme', 'hello this a test plss', 'one_time', 30, 'completed', '2025-09-06 16:08:51', NULL, '2025-09-17 00:00:00', '12:09:00', '14:08:00', 'park', NULL, '2025-09-06 16:08:51', NULL, '2025-09-06 08:08:51', '2025-09-24 04:52:54'),
(5, NULL, 'another test', '231sdadasdsa', 'daily', 12, 'completed', '2025-09-06 16:10:22', NULL, '2025-09-08 00:00:00', '16:10:00', '17:10:00', 'sda', NULL, '2025-09-06 16:10:22', NULL, '2025-09-06 08:10:22', '2025-09-24 04:52:54'),
(6, 5, 'need man power', 'building a dog house', 'user_uploaded', 10, 'completed', '2025-09-06 16:14:08', '2025-09-06 16:14:37', '2025-09-08 00:00:00', '14:13:00', '15:13:00', 'balay', NULL, '2025-09-06 16:14:43', NULL, '2025-09-06 08:14:08', '2025-09-24 04:52:54'),
(7, 5, 'User TEst', 'test 123 123', 'user_uploaded', 20, 'uncompleted', '2025-09-10 13:10:39', '2025-09-10 13:12:09', '2025-09-12 00:00:00', '10:10:00', '12:10:00', 'House', NULL, '2025-09-10 13:12:26', NULL, '2025-09-10 05:10:39', '2025-11-25 06:39:14'),
(8, NULL, 'Tap and Pass Test', 'this is a test hehu', 'daily', 10, 'completed', '2025-09-10 13:40:59', NULL, '2025-09-12 00:00:00', '22:40:00', '23:41:00', 'sports complex', NULL, '2025-09-10 13:40:59', NULL, '2025-09-10 05:40:59', '2025-09-24 04:52:54'),
(9, NULL, 'TapPass2', 'test 2', 'daily', 12, 'completed', '2025-09-21 12:25:11', NULL, '2025-09-24 00:00:00', '11:24:00', '12:30:00', 'park', NULL, '2025-09-21 12:25:11', NULL, '2025-09-21 04:25:11', '2025-09-24 04:52:54'),
(10, NULL, 'tapPass3', 'test3', 'daily', 13, 'completed', '2025-09-21 12:52:56', NULL, '2025-09-24 00:00:00', '10:54:00', '13:00:00', 'covered court', NULL, '2025-09-21 12:52:56', NULL, '2025-09-21 04:52:56', '2025-09-24 04:52:54'),
(11, NULL, 'One Test', 'hello hi', 'one_time', 12, 'uncompleted', '2025-09-24 12:55:17', NULL, '2025-09-26 00:00:00', '09:00:00', '12:00:00', 'Community  Center', NULL, '2025-09-24 12:55:25', NULL, '2025-09-24 04:55:17', '2025-11-25 06:39:14'),
(12, NULL, 'Limit Test', 'sdsad fdsfs1123', 'daily', 23, 'completed', '2025-09-24 13:05:39', NULL, '2025-09-27 00:00:00', '10:30:00', '12:00:00', 'Park', 2, '2025-09-24 13:05:39', NULL, '2025-09-24 05:05:39', '2025-10-01 05:07:13'),
(13, 5, 'Wood Working', 'need manpower in making a wood furniture', 'user_uploaded', 12, 'completed', '2025-10-01 13:06:46', '2025-10-01 13:07:27', '2025-10-04 00:00:00', '10:06:00', '12:06:00', 'PH', 2, '2025-10-01 13:07:31', NULL, '2025-10-01 05:06:46', '2025-10-04 02:41:28'),
(14, NULL, 'redirect test', 'test for redirecting task', 'daily', 12, 'completed', '2025-10-01 13:41:48', NULL, '2025-10-03 00:00:00', '09:41:00', '11:41:00', '123', 2, '2025-10-01 13:41:48', NULL, '2025-10-01 05:41:48', '2025-10-03 01:51:26'),
(15, 5, 'uploaded test', 'user task test', 'user_uploaded', 20, 'uncompleted', '2025-10-01 14:32:38', NULL, '2025-10-04 00:00:00', '12:32:00', '14:32:00', 'covered court', NULL, NULL, NULL, '2025-10-01 06:32:38', '2025-11-25 06:39:14'),
(16, NULL, 'test3', 'gsakdasdas', 'daily', 20, 'completed', '2025-10-06 12:50:29', NULL, '2025-10-07 00:00:00', '10:50:00', '12:50:00', 'park', NULL, '2025-10-06 12:50:29', NULL, '2025-10-06 04:50:29', '2025-10-07 03:39:18'),
(17, 9, 'Test Task1', 'hello test', 'user_uploaded', 20, 'uncompleted', '2025-10-28 12:26:03', '2025-10-28 12:59:11', '2025-10-29 00:00:00', '10:30:00', '12:30:00', 'park', 2, '2025-10-28 13:03:07', NULL, '2025-10-28 04:26:03', '2025-11-25 06:39:14'),
(18, NULL, 'daily task test123', '123 test 123', 'daily', 20, 'completed', '2025-10-28 12:40:36', NULL, '2025-10-30 00:00:00', '12:45:00', '14:45:00', 'ila bahan', NULL, '2025-10-28 12:40:36', NULL, '2025-10-28 04:40:36', '2025-10-31 01:36:01'),
(19, 10, 'hello there', 'dsadsadasdsas', 'user_uploaded', 10, 'uncompleted', '2025-10-29 13:54:56', '2025-10-29 14:25:52', '2025-10-30 00:00:00', '10:00:00', '12:00:00', 'Ermita Proper', 2, NULL, NULL, '2025-10-29 05:54:56', '2025-11-25 06:39:14'),
(20, NULL, 'dailyyyy testttttt', 'hellllloooooo', 'daily', 15, 'inactive', '2025-10-29 13:59:50', NULL, '2025-10-31 00:00:00', '10:00:00', '12:00:00', 'Kastilaan', 2, '2025-10-29 13:59:50', NULL, '2025-10-29 05:59:50', '2025-10-29 06:26:20'),
(21, 11, 'sdsadasdasddsa', 'dsadasdsadsada', 'user_uploaded', 20, 'uncompleted', '2025-10-29 14:19:12', '2025-10-29 14:21:32', '2025-10-30 00:00:00', '12:20:00', '13:20:00', 'Kastilaan', 2, NULL, NULL, '2025-10-29 06:19:12', '2025-11-25 06:39:14'),
(22, 11, 'lk;ldklfgd', 'asdsadsadsadsa', 'user_uploaded', 10, 'inactive', '2025-10-29 14:20:10', NULL, '2025-10-30 00:00:00', '13:20:00', '15:20:00', 'Panagdait', 1, NULL, NULL, '2025-10-29 06:20:10', '2025-10-29 06:24:16'),
(23, NULL, 'momomomo', 'dsadasdsadsad', 'daily', 20, 'completed', '2025-10-29 14:35:19', NULL, '2025-10-30 00:00:00', '14:34:00', '17:34:00', 'Panagdait', 10, '2025-10-29 14:35:19', NULL, '2025-10-29 06:35:19', '2025-10-31 01:36:01'),
(24, NULL, 'demo test', 'dsafsfsdfdfsd', 'daily', 20, 'completed', '2025-10-31 09:41:37', NULL, '2025-11-01 00:00:00', '18:41:00', '20:41:00', 'Panagdait', NULL, '2025-10-31 09:41:37', NULL, '2025-10-31 01:41:37', '2025-11-09 02:33:48'),
(25, NULL, 'hello daily task', 'sfdsdssdgds', 'daily', 25, 'uncompleted', '2025-10-31 09:42:49', NULL, '2025-11-01 00:00:00', '09:42:00', '11:42:00', 'Sitio Bato', NULL, '2025-10-31 09:42:49', NULL, '2025-10-31 01:42:49', '2025-11-25 06:39:14'),
(26, NULL, 'another test daily', 'adskajdhsajkdasd', 'daily', 30, 'uncompleted', '2025-10-31 09:44:51', NULL, '2025-11-02 00:00:00', '10:00:00', '12:30:00', 'Ermita Proper', NULL, '2025-10-31 09:44:51', NULL, '2025-10-31 01:44:51', '2025-11-25 06:39:14'),
(27, NULL, 'One Time Task TEst 123123', 'dlasdlkn nvksdjiaosdsa', 'one_time', 50, 'inactive', '2025-10-31 09:46:21', NULL, '2025-11-02 00:00:00', '09:30:00', '13:45:00', 'Ermita Proper', NULL, '2025-10-31 09:46:21', NULL, '2025-10-31 01:46:21', '2025-10-31 03:13:26'),
(28, 5, 'hello proposal test456', 'dasdasdasdasd56546', 'user_uploaded', 25, 'uncompleted', '2025-10-31 10:22:14', '2025-10-31 11:19:03', '2025-11-01 00:00:00', '11:30:00', '12:30:00', 'Ermita Proper', 1, NULL, NULL, '2025-10-31 02:22:14', '2025-11-25 06:39:14'),
(29, NULL, 'Notif TEst task', '123 hello', 'daily', 10, 'completed', '2025-11-10 10:52:22', NULL, '2025-11-11 00:00:00', '10:30:00', '12:00:00', 'Ermita Proper', 2, '2025-11-10 11:08:06', NULL, '2025-11-10 02:52:22', '2025-11-13 00:11:00'),
(30, 5, 'notif admin test', 'dsadsads', 'user_uploaded', 10, 'uncompleted', '2025-11-10 10:59:29', '2025-11-10 11:09:42', '2025-11-11 00:00:00', '07:00:00', '09:00:00', 'Sitio Bato', 3, '2025-11-10 11:09:47', NULL, '2025-11-10 02:59:29', '2025-11-25 06:39:14'),
(31, NULL, 'test notif', 'sdasdsadsadassd', 'one_time', 10, 'completed', '2025-11-10 11:05:01', NULL, '2025-11-12 00:00:00', '09:00:00', '12:00:00', 'Kastilaan', 10, '2025-11-10 11:09:08', NULL, '2025-11-10 03:05:01', '2025-11-13 00:11:00'),
(32, 5, 'another test notif', 'hello heloo', 'user_uploaded', 10, 'completed', '2025-11-10 11:14:48', '2025-11-10 11:15:08', '2025-11-11 00:00:00', '10:14:00', '11:14:00', 'Ermita Proper', 1, '2025-11-10 11:15:22', NULL, '2025-11-10 03:14:48', '2025-11-13 00:11:00'),
(33, 5, 'admin test', 'sdsadsadsadsa', 'user_uploaded', 10, 'inactive', '2025-11-10 11:48:03', NULL, '2025-11-12 00:00:00', '09:47:00', '10:47:00', 'Eyeseekers', 1, NULL, NULL, '2025-11-10 03:48:03', '2025-11-10 04:04:43'),
(34, 5, 'admin notif test', 'sdsadsad', 'user_uploaded', 20, 'uncompleted', '2025-11-10 12:05:07', NULL, '2025-11-12 00:00:00', '11:05:00', '12:05:00', 'Ermita Proper', 1, NULL, NULL, '2025-11-10 04:05:07', '2025-11-25 06:39:14'),
(35, 5, 'another admin test', 'dasdsadas', 'user_uploaded', 10, 'uncompleted', '2025-11-10 12:22:37', NULL, '2025-11-12 00:00:00', '12:22:00', '13:22:00', 'Ermita Proper', 1, NULL, NULL, '2025-11-10 04:22:37', '2025-11-25 06:39:14'),
(36, NULL, 'testtest123123', 'dsadsadsadsad', 'one_time', 9, 'uncompleted', '2025-11-10 12:25:23', NULL, '2025-11-11 00:00:00', '13:25:00', '14:25:00', 'Pig Vendor', NULL, '2025-11-10 12:25:23', NULL, '2025-11-10 04:25:23', '2025-11-25 06:39:14'),
(37, NULL, 'tap&pass notif test', '123123412421sdadsadsadasd', 'daily', 10, 'completed', '2025-11-10 12:45:52', NULL, '2025-11-11 00:00:00', '08:45:00', '09:45:00', 'Kastilaan', 1, '2025-11-10 12:45:52', NULL, '2025-11-10 04:45:52', '2025-11-13 00:11:00'),
(38, NULL, 'time test task', 'sadas hhheheq123', 'daily', 10, 'completed', '2025-11-16 13:59:49', NULL, '2025-11-16 00:00:00', '15:30:00', '16:00:00', 'YHC', NULL, '2025-11-16 13:59:49', NULL, '2025-11-16 05:59:49', '2025-11-16 09:08:38'),
(39, 5, 'redirect_test2', 'sldjasldkascmas123', 'user_uploaded', 15, 'completed', '2025-11-16 14:13:08', '2025-11-16 14:49:53', '2025-11-16 00:00:00', '16:12:00', '17:12:00', 'Kawit', 1, '2025-11-16 14:49:53', NULL, '2025-11-16 06:13:08', '2025-11-16 09:12:27'),
(40, 5, 'REDIRECT1', 'sdasdasdas', 'user_uploaded', 20, 'uncompleted', '2025-11-16 14:30:36', '2025-11-16 14:47:34', '2025-11-16 00:00:00', '15:30:00', '16:30:00', 'Eyeseekers', 2, '2025-11-16 14:50:39', NULL, '2025-11-16 06:30:36', '2025-11-25 06:39:14'),
(41, NULL, 'auto complete', 'das dsadkaopwie123', 'daily', 28, 'completed', '2025-11-16 17:15:48', NULL, '2025-11-16 00:00:00', '18:30:00', '20:00:00', 'Eyeseekers', 1, '2025-11-16 17:15:48', NULL, '2025-11-16 09:15:48', '2025-11-16 09:16:47');

-- --------------------------------------------------------

--
-- Table structure for table `task_assignments`
--

CREATE TABLE `task_assignments` (
  `assignmentId` bigint(20) UNSIGNED NOT NULL,
  `taskId` bigint(20) UNSIGNED NOT NULL,
  `userId` bigint(20) UNSIGNED NOT NULL,
  `status` enum('assigned','submitted','completed','uncompleted') NOT NULL DEFAULT 'assigned',
  `progress` enum('accepted','on_the_way','working','done','submitted_proof') NOT NULL DEFAULT 'accepted',
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `submitted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `reminder_sent_at` timestamp NULL DEFAULT NULL,
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

INSERT INTO `task_assignments` (`assignmentId`, `taskId`, `userId`, `status`, `progress`, `assigned_at`, `submitted_at`, `completed_at`, `reminder_sent_at`, `photos`, `completion_notes`, `rejection_count`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(3, 3, 6, 'completed', 'accepted', '2025-09-06 07:15:53', '2025-09-10 05:33:07', '2025-09-10 05:38:16', NULL, '[\"task-submissions\\/0pdquLS3iIGprTPetfDJIzL7OqKJiUr8Pya7YECC.png\"]', 'Approved by admin', 1, 'please provide a proof of completion', '2025-09-06 07:15:53', '2025-09-10 05:38:16'),
(4, 2, 6, 'completed', 'accepted', '2025-09-06 07:15:58', '2025-09-10 05:37:19', '2025-09-10 05:40:08', NULL, '[\"task-submissions\\/YwVq9FN1uDKWswQwgSCKGcfCNEcrY5VKVUszf67X.png\"]', 'Approved by admin', 0, NULL, '2025-09-06 07:15:58', '2025-09-10 05:40:08'),
(5, 3, 5, 'completed', 'accepted', '2025-09-06 07:18:26', '2025-09-06 07:19:02', '2025-09-06 08:01:38', NULL, '[\"task-submissions\\/IVTWKZDFbZklTSdo49UE54ITB1XtzPjzzMKESjU0.png\"]', 'Approved by admin', 0, NULL, '2025-09-06 07:18:26', '2025-09-06 08:01:38'),
(6, 2, 5, 'completed', 'accepted', '2025-09-06 07:40:05', '2025-09-06 07:40:05', '2025-09-06 07:56:33', NULL, '[\"task-submissions\\/IVTWKZDFbZklTSdo49UE54ITB1XtzPjzzMKESjU0.png\"]', 'Approved by admin', 0, NULL, '2025-09-06 07:40:05', '2025-09-06 07:56:33'),
(7, 5, 5, 'completed', 'accepted', '2025-09-06 08:15:16', '2025-09-10 05:14:31', '2025-09-10 05:15:05', NULL, '[\"task-submissions\\/4iaElVb6MZPb11erd9YpLkpulj63o9WoShjpddtb.png\"]', 'Approved by admin', 1, 'eww', '2025-09-06 08:15:16', '2025-09-10 05:15:05'),
(8, 6, 6, 'completed', 'accepted', '2025-09-10 05:42:38', '2025-09-10 05:45:19', '2025-09-10 05:45:37', NULL, '[\"task-submissions\\/RYDwXBB1l2elrvR9AzqfgtDO4abS3jBVEQYmbLtG.png\"]', 'Approved by admin', 1, 'sus', '2025-09-10 05:42:38', '2025-09-10 05:45:37'),
(9, 4, 6, 'completed', 'accepted', '2025-09-10 05:47:35', '2025-09-10 05:48:43', '2025-09-10 05:49:00', NULL, '[\"task-submissions\\/S5hP9Nsp6Vu2nESo3uMyu22OwD4HlJoNmQDHo82C.png\"]', 'Approved by admin', 1, 'submit more', '2025-09-10 05:47:35', '2025-09-10 05:49:00'),
(10, 8, 5, 'completed', 'accepted', '2025-09-21 04:20:27', '2025-09-21 04:53:26', '2025-09-21 04:57:45', NULL, '[]', 'Approved by admin', 0, NULL, '2025-09-21 04:20:27', '2025-09-21 04:57:45'),
(11, 8, 6, 'completed', 'accepted', '2025-09-21 04:21:03', '2025-09-21 04:21:30', '2025-09-21 04:22:40', NULL, '[\"task-submissions\\/aJJDmAd7nlGmEDJQD5gLQYL9M0FiCRG33my0uq46.png\"]', 'Approved by admin', 0, NULL, '2025-09-21 04:21:03', '2025-09-21 04:22:40'),
(12, 9, 5, 'completed', 'accepted', '2025-09-21 04:52:01', '2025-09-21 05:14:39', '2025-09-21 05:15:15', NULL, '[]', 'Approved by admin', 0, NULL, '2025-09-21 04:52:01', '2025-09-21 05:15:15'),
(13, 5, 7, 'completed', 'accepted', '2025-09-21 05:00:04', '2025-09-21 05:10:52', '2025-09-21 05:11:19', NULL, '[]', 'Approved by admin', 0, NULL, '2025-09-21 05:00:04', '2025-09-21 05:11:19'),
(14, 8, 7, 'completed', 'accepted', '2025-09-21 05:00:30', '2025-09-21 05:27:38', '2025-09-21 05:27:55', NULL, '[]', 'Approved by admin', 0, NULL, '2025-09-21 05:00:30', '2025-09-21 05:27:55'),
(15, 10, 7, 'completed', 'accepted', '2025-09-21 06:06:50', '2025-09-21 06:39:38', '2025-09-21 06:39:54', NULL, '[]', 'Approved by admin', 0, NULL, '2025-09-21 06:06:50', '2025-09-21 06:39:54'),
(16, 12, 5, 'completed', 'accepted', '2025-09-24 05:05:58', '2025-10-01 04:46:15', '2025-10-01 05:07:13', NULL, '[\"task-submissions\\/fKkk6kruPYxkgQfi3eZT4ejkaQlVplvO2VwNAvbD.jpg\",\"task-submissions\\/FkfVQ7ay5MAKOQkFgB5s2gaLMO2wYCN80oIghGd4.png\",\"task-submissions\\/uNEk7YDcJzYOfBzgNKMM2cxGD5FlXEGLi4mhbnvV.png\"]', NULL, 0, NULL, '2025-09-24 05:05:58', '2025-10-01 05:07:13'),
(17, 12, 6, 'completed', 'accepted', '2025-09-24 05:06:16', NULL, '2025-10-01 05:07:13', NULL, NULL, NULL, 0, NULL, '2025-09-24 05:06:16', '2025-10-01 05:07:13'),
(18, 14, 5, 'completed', 'accepted', '2025-10-01 05:46:30', '2025-10-01 06:02:53', '2025-10-03 01:51:26', NULL, '[\"task-submissions\\/qtCC54nHFpNfJ1zKm0xoBnj7p216bTTHEXc1YX9S.jpg\",\"task-submissions\\/Xkl58KLFUyBygw1ZnckO86AVzuA8VMIp0bPNceeV.jpg\",\"task-submissions\\/4mAOacIFWpfzvdSMzL9to44F8JI3NXIpilABlLaV.jpg\"]', NULL, 0, NULL, '2025-10-01 05:46:30', '2025-10-03 01:51:26'),
(19, 14, 7, 'completed', 'submitted_proof', '2025-10-01 06:48:42', '2025-10-01 06:55:49', '2025-10-03 01:51:26', NULL, '[\"task-submissions\\/dCvTpCbIa8Os0N8gRJeToM62CuriIemvK3D9P9av.jpg\",\"task-submissions\\/awNXhs5e31id7qvRsu1VOyafJOYv3NNCWAJdOEgj.jpg\",\"task-submissions\\/RKNfSLXKbA1TioMsCSpPhdbs0T2dCyqNngubU6iZ.jpg\"]', NULL, 0, NULL, '2025-10-01 06:48:42', '2025-10-03 01:51:26'),
(20, 13, 6, 'completed', 'submitted_proof', '2025-10-01 07:03:44', '2025-10-01 07:05:53', '2025-10-04 02:41:28', NULL, '[\"task-submissions\\/oNXIutT2bw9goMukCJR3oILjpGUwL9tykGhDylvb.jpg\",\"task-submissions\\/7mr7PrD9j79NdEcF4vUQCLudvWtX5UGUgRyjoIZu.jpg\",\"task-submissions\\/3E1WEqHSd5ZupNkVWkvUJr5hCPLfkv7woFximlBZ.jpg\"]', NULL, 0, NULL, '2025-10-01 07:03:44', '2025-10-04 02:41:28'),
(21, 16, 5, 'completed', 'submitted_proof', '2025-10-06 04:50:45', '2025-10-06 04:52:17', '2025-10-07 03:39:18', NULL, '[\"task-submissions\\/wKjEE5wjtTvQAhF6DTYJtqEp2nXs3gQbtVfTNHlx.jpg\",\"task-submissions\\/Y0iNJ03pHV9EFaH6sMjJq35wILHM5vGaAwm89FRH.jpg\"]', NULL, 0, NULL, '2025-10-06 04:50:45', '2025-10-07 03:39:18'),
(22, 18, 9, 'completed', 'submitted_proof', '2025-10-28 04:45:16', '2025-10-28 04:52:43', '2025-10-28 04:53:03', NULL, '[\"task-submissions\\/Q3DjnUiaX1seMyV8cLQIeOuqe0y4hbHeYyyj4Uo1.jpg\",\"task-submissions\\/Zmr2WxKBW13Zr6EE6YrhiCkZNnHzd9aW3p9meP32.jpg\"]', 'Approved by admin', 0, NULL, '2025-10-28 04:45:16', '2025-10-28 04:53:03'),
(23, 20, 11, 'completed', 'submitted_proof', '2025-10-29 06:17:34', '2025-10-29 06:32:42', '2025-10-29 06:33:04', NULL, '[\"task-submissions\\/lZXV9bXVsfNcjYufklm9LUt4LnJszsGXxJUEYfps.jpg\",\"task-submissions\\/DWU0XZ7g0ZN4C9y6xuWkyf3iIgEi6HMHpnfwmYI8.jpg\"]', 'Approved by admin', 0, NULL, '2025-10-29 06:17:34', '2025-10-29 06:33:04'),
(24, 23, 5, 'completed', 'accepted', '2025-10-29 06:39:02', NULL, '2025-10-31 01:36:01', NULL, NULL, NULL, 0, NULL, '2025-10-29 06:39:02', '2025-10-31 01:36:01'),
(25, 24, 5, 'completed', 'accepted', '2025-10-31 01:43:33', NULL, '2025-11-09 02:33:48', NULL, NULL, NULL, 0, NULL, '2025-10-31 01:43:33', '2025-11-09 02:33:48'),
(26, 32, 6, 'completed', 'submitted_proof', '2025-11-10 03:29:47', '2025-11-10 03:34:17', '2025-11-10 03:35:06', NULL, '[\"task-submissions\\/vvCJOlBlrbPJcJBo2k4qqAmT4AhRmxEYKD0lYZxf.jpg\",\"task-submissions\\/4jWhIPQ8NSXdb9dpFOJcDtAh4BKWkuQYRQ90jwAY.jpg\"]', 'Approved by creator', 0, NULL, '2025-11-10 03:29:47', '2025-11-10 03:35:06'),
(27, 31, 5, 'completed', 'submitted_proof', '2025-11-10 04:06:03', '2025-11-10 04:06:32', '2025-11-13 00:11:00', NULL, '[\"task-submissions\\/1yY79HgP17yV8FsayrmSx22moWl76ly9yJrtzAON.png\",\"task-submissions\\/qcBNEJPndIKkeWppduzyJCyHd9CqNPSBxgXjHSnM.png\"]', NULL, 0, NULL, '2025-11-10 04:06:03', '2025-11-13 00:11:00'),
(28, 29, 5, 'completed', 'submitted_proof', '2025-11-10 04:23:05', '2025-11-10 04:23:26', '2025-11-10 04:44:59', NULL, '[\"task-submissions\\/0yMBcUHJ7LCMomv7ztfKKRKWgqNTs8zEKVBqwmpi.jpg\",\"task-submissions\\/cns2xxVoKvlihL9NQNNXkwapB9payVOxg4ABaBXf.png\"]', 'Approved by admin', 0, NULL, '2025-11-10 04:23:05', '2025-11-10 04:44:59'),
(29, 37, 6, 'completed', 'accepted', '2025-11-10 04:49:52', NULL, '2025-11-13 00:11:00', NULL, NULL, NULL, 0, NULL, '2025-11-10 04:49:52', '2025-11-13 00:11:00'),
(30, 38, 5, 'completed', 'submitted_proof', '2025-11-16 06:00:18', '2025-11-16 00:54:19', '2025-11-16 00:54:44', NULL, '[\"task-submissions\\/1j8RDU096w6M4jWJ4a7eHY3RdKfLSxMMQu4bX7hB.jpg\",\"task-submissions\\/w3dOHEcW9WxYV2ktspTuS8RsEFH5vEmCyKm896Tu.png\"]', 'Approved by admin', 0, NULL, '2025-11-16 06:00:18', '2025-11-16 00:54:44'),
(31, 39, 7, 'completed', 'submitted_proof', '2025-11-16 06:52:32', '2025-11-16 06:55:04', '2025-11-16 06:58:55', NULL, '[\"task-submissions\\/TZmYxTWoqfU52HUZzkx5PpGexIizTG8rwNjg749h.jpg\",\"task-submissions\\/G4LaUu7rNsr9SieqzKY7F4KWDgCVWBHHdRlOd5Ew.jpg\"]', 'Approved by creator', 0, NULL, '2025-11-16 06:52:32', '2025-11-16 06:58:55'),
(32, 41, 5, 'completed', 'submitted_proof', '2025-11-16 09:15:58', '2025-11-16 09:16:20', '2025-11-16 09:18:58', NULL, '[\"task-submissions\\/7H17Jer4aFb4bPIiMuFZTC14NbTrUHX1mXfBl3Pf.png\",\"task-submissions\\/IbjAY4d2DVW473cfRwoxBLQdw8ERzejcRc8Zv5Cj.jpg\"]', 'Approved by admin', 0, NULL, '2025-11-16 09:15:58', '2025-11-16 09:18:58');

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
  `profile_picture` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
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

INSERT INTO `users` (`userId`, `firstName`, `middleName`, `lastName`, `email`, `profile_picture`, `password`, `points`, `role`, `status`, `date_registered`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(4, 'admin', NULL, 'john', 'admin@email.com', NULL, '$2y$12$O3jb/2kBP6A/O6P5IGYRtu2/x6xfoQEpNhzlLl3/v7AnPSHAvXkJC', 0, 'admin', 'active', '2025-09-01', NULL, NULL, '2025-09-01 06:42:30', '2025-09-01 06:42:30'),
(5, 'Allen', NULL, 'Rafaela', 'allenkimrafaela@gmail.com', NULL, '$2y$12$FbyrySawbj4pbKGbsaBgiekXhxBxmhPk6ojKggq/yoDuPy49GOtM2', 279, 'user', 'active', '2025-09-01', NULL, NULL, '2025-09-01 06:43:19', '2025-11-16 09:18:58'),
(6, 'John', NULL, 'Doe', 'j.doe@email.com', NULL, '$2y$12$Fny4WoqDJmzam2YRY46fFuvpcMSOcl0GjnsgulecFw50gvzpvVIbG', 13, 'user', 'active', '2025-09-02', NULL, NULL, '2025-09-02 04:42:06', '2025-11-16 09:11:17'),
(7, 'Jane', NULL, 'Doe', 'jane.d@email.com', NULL, '$2y$12$br36ZSNuyogIihQHXo97Vu78VCzzDu/84BJqs.1gGIXr8XCvfJawO', 41, 'user', 'active', '2025-09-21', NULL, NULL, '2025-09-21 04:59:15', '2025-11-16 00:41:21'),
(8, 'Aldrian', NULL, 'Bahan', 'bahan@gmail.com', NULL, '$2y$12$jJWTu6ZFaah.LlawKHV1oeEWdcIuYM/VPgA9qOgRXM8ZZQ0oKzAAK', 0, 'user', 'suspended', '2025-10-07', NULL, NULL, '2025-10-07 01:52:58', '2025-10-31 03:10:06'),
(9, 'Damien', NULL, 'Caumeran', 'damskie123@email.com', NULL, '$2y$12$6Cqz0bquxvoP1u6EjvBNo.ArkVnnW/K1x0i3w/lkXlL4j9wS31QDO', 20, 'user', 'active', '2025-10-28', NULL, NULL, '2025-10-28 04:02:46', '2025-10-28 04:53:03'),
(10, 'Jhon Richmon', NULL, 'Solon', 'jhon@email.com', NULL, '$2y$12$I5UHhuVKGcZNBXh2OfEkRul4L29tN54QAL8tp1lpB3Qwl4e6hsBBS', 0, 'user', 'active', '2025-10-29', NULL, NULL, '2025-10-29 04:13:12', '2025-10-29 04:13:12'),
(11, 'jarom', NULL, 'bustillo', 'jarom@email.com', NULL, '$2y$12$tKdah8AJhpbCyovIfjzLQetox3e42VSjqTdb50u/andGIMW42c76O', 18, 'user', 'active', '2025-10-29', NULL, NULL, '2025-10-29 06:04:41', '2025-10-29 06:57:10'),
(13, 'Aldrian', NULL, 'Bahan', 'bahan@email.com', NULL, '$2y$12$YWoaF9FCRlhSUccdvnTOB.1wo4u9kL/z6Pq7gHOS3M70XWBNI8gP6', 0, 'user', 'active', '2025-10-31', NULL, NULL, '2025-10-31 03:06:02', '2025-10-31 03:06:02');

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
(1, 7, 5, 12, 'abuse', 'asdasdasda', 'adasdsads', 'dismissed', 4, NULL, 'dismissed', '2025-11-13 09:53:20', '2025-10-04 11:32:49', '2025-10-04 03:32:49', '2025-11-13 01:53:20'),
(2, 5, 9, 3, 'non_participation', 'dadsdadsadasd', 'Image: incident_evidence/2/2MPKqe93JrYRPQlByI9RYMEVzZnAeGn20GsjwBnL.jpg', 'dismissed', 4, 'not enough evidence', 'no_action', '2025-10-28 13:26:30', '2025-10-28 13:24:39', '2025-10-28 05:24:39', '2025-10-28 05:26:30'),
(3, 5, 11, 20, 'abuse', 'asdsadasdsadsadad', NULL, 'dismissed', 4, 'sadasdsadsadsa', 'no_action', '2025-11-13 09:53:09', '2025-10-29 14:43:10', '2025-10-29 06:43:10', '2025-11-13 01:53:09'),
(4, 5, 6, 3, 'non_participation', 'fdsfdsfdq32', 'Image: incident_evidence/4/zjwFwoDVfPx5BZeC6OsgLTGoUNw6j5QP6crz0AuU.jpg', 'resolved', 4, 'dsadasd', 'warning', '2025-11-13 09:52:59', '2025-10-31 11:27:34', '2025-10-31 03:27:34', '2025-11-13 01:52:59'),
(5, 5, 6, 3, 'non_participation', 'fdasasdsadsada', NULL, 'resolved', 4, 'dsadasdadsa', 'suspension', '2025-11-13 09:52:21', '2025-11-10 12:08:04', '2025-11-10 04:08:04', '2025-11-13 01:52:21'),
(6, 5, 6, 2, 'inappropriate_content', 'dsadasdsadsad', NULL, 'dismissed', 4, NULL, 'warning', '2025-11-13 09:52:47', '2025-11-10 12:23:55', '2025-11-10 04:23:55', '2025-11-13 01:52:47'),
(7, 9, 5, 3, 'harassment', 'harrassed me while doing the task.', NULL, 'resolved', 4, 'I already talked to Allen and I issued a warning to him', 'warning', '2025-11-25 14:44:57', '2025-11-25 14:43:38', '2025-11-25 06:43:38', '2025-11-25 06:44:57');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

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
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `rewardId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reward_redemption`
--
ALTER TABLE `reward_redemption`
  MODIFY `redemptionId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tap_nominations`
--
ALTER TABLE `tap_nominations`
  MODIFY `nominationId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `taskId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `task_assignments`
--
ALTER TABLE `task_assignments`
  MODIFY `assignmentId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
  MODIFY `reportId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
