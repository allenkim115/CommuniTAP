-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2025 at 05:13 PM
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
(1, 5, 3, 3, 'good experience', '2025-09-06 15:30:13', '2025-09-06 07:30:13', '2025-09-06 07:30:13');

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
(26, '2025_09_21_142411_add_chain_columns_to_tap_nominations_table', 9);

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
  `description` text NOT NULL,
  `points_cost` int(11) NOT NULL,
  `QTY` int(11) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `created_date` datetime NOT NULL,
  `last_update_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `approval_date` datetime DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('tRAXzWzOoD35mNcHhf2iOvg6sM2ySoeKEdHq17ke', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRFRoVWoxbVZtd1JuV3YwUEtZNHJiNzVCUVg2YXZUT1BqZ2xDMEtFNSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1758467576);

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
(3, 10, 5, 7, '2025-09-21 14:06:30', 'accepted', '2025-09-21 06:06:30', '2025-09-21 06:36:10');

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
  `published_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`taskId`, `FK1_userId`, `title`, `description`, `task_type`, `points_awarded`, `status`, `creation_date`, `approval_date`, `due_date`, `start_time`, `end_time`, `location`, `published_date`, `created_at`, `updated_at`) VALUES
(2, NULL, 'Test', 'Hello this is a test', 'one_time', 10, 'published', '2025-09-06 15:10:15', NULL, '2025-09-08 00:00:00', '13:00:00', '15:00:00', 'covered court', '2025-09-06 15:10:15', '2025-09-06 07:10:15', '2025-09-06 07:10:15'),
(3, NULL, 'Test Daily', 'Daily Task test create', 'daily', 20, 'published', '2025-09-06 15:11:14', NULL, '2025-09-09 00:00:00', '10:00:00', '12:30:00', 'barangay hall', '2025-09-06 15:11:14', '2025-09-06 07:11:14', '2025-09-06 07:11:14'),
(4, NULL, 'Test One TIme', 'hello this a test plss', 'one_time', 30, 'published', '2025-09-06 16:08:51', NULL, '2025-09-17 00:00:00', '12:09:00', '14:08:00', 'park', '2025-09-06 16:08:51', '2025-09-06 08:08:51', '2025-09-06 08:08:51'),
(5, NULL, 'another test', '231sdadasdsa', 'daily', 12, 'published', '2025-09-06 16:10:22', NULL, '2025-09-08 00:00:00', '16:10:00', '17:10:00', 'sda', '2025-09-06 16:10:22', '2025-09-06 08:10:22', '2025-09-06 08:10:22'),
(6, 5, 'need man power', 'building a dog house', 'user_uploaded', 10, 'published', '2025-09-06 16:14:08', '2025-09-06 16:14:37', '2025-09-08 00:00:00', '14:13:00', '15:13:00', 'balay', '2025-09-06 16:14:43', '2025-09-06 08:14:08', '2025-09-06 08:14:43'),
(7, 5, 'User TEst', 'test 123 123', 'user_uploaded', 20, 'published', '2025-09-10 13:10:39', '2025-09-10 13:12:09', '2025-09-12 00:00:00', '10:10:00', '12:10:00', 'House', '2025-09-10 13:12:26', '2025-09-10 05:10:39', '2025-09-10 05:12:26'),
(8, NULL, 'Tap and Pass Test', 'this is a test hehu', 'daily', 10, 'published', '2025-09-10 13:40:59', NULL, '2025-09-12 00:00:00', '22:40:00', '23:41:00', 'sports complex', '2025-09-10 13:40:59', '2025-09-10 05:40:59', '2025-09-10 05:40:59'),
(9, NULL, 'TapPass2', 'test 2', 'daily', 12, 'published', '2025-09-21 12:25:11', NULL, '2025-09-24 00:00:00', '11:24:00', '12:30:00', 'park', '2025-09-21 12:25:11', '2025-09-21 04:25:11', '2025-09-21 04:25:11'),
(10, NULL, 'tapPass3', 'test3', 'daily', 13, 'published', '2025-09-21 12:52:56', NULL, '2025-09-24 00:00:00', '10:54:00', '13:00:00', 'covered court', '2025-09-21 12:52:56', '2025-09-21 04:52:56', '2025-09-21 04:52:56');

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

INSERT INTO `task_assignments` (`assignmentId`, `taskId`, `userId`, `status`, `assigned_at`, `submitted_at`, `completed_at`, `photos`, `completion_notes`, `rejection_count`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(3, 3, 6, 'completed', '2025-09-06 07:15:53', '2025-09-10 05:33:07', '2025-09-10 05:38:16', '[\"task-submissions\\/0pdquLS3iIGprTPetfDJIzL7OqKJiUr8Pya7YECC.png\"]', 'Approved by admin', 1, 'please provide a proof of completion', '2025-09-06 07:15:53', '2025-09-10 05:38:16'),
(4, 2, 6, 'completed', '2025-09-06 07:15:58', '2025-09-10 05:37:19', '2025-09-10 05:40:08', '[\"task-submissions\\/YwVq9FN1uDKWswQwgSCKGcfCNEcrY5VKVUszf67X.png\"]', 'Approved by admin', 0, NULL, '2025-09-06 07:15:58', '2025-09-10 05:40:08'),
(5, 3, 5, 'completed', '2025-09-06 07:18:26', '2025-09-06 07:19:02', '2025-09-06 08:01:38', '[\"task-submissions\\/IVTWKZDFbZklTSdo49UE54ITB1XtzPjzzMKESjU0.png\"]', 'Approved by admin', 0, NULL, '2025-09-06 07:18:26', '2025-09-06 08:01:38'),
(6, 2, 5, 'completed', '2025-09-06 07:40:05', '2025-09-06 07:40:05', '2025-09-06 07:56:33', '[\"task-submissions\\/IVTWKZDFbZklTSdo49UE54ITB1XtzPjzzMKESjU0.png\"]', 'Approved by admin', 0, NULL, '2025-09-06 07:40:05', '2025-09-06 07:56:33'),
(7, 5, 5, 'completed', '2025-09-06 08:15:16', '2025-09-10 05:14:31', '2025-09-10 05:15:05', '[\"task-submissions\\/4iaElVb6MZPb11erd9YpLkpulj63o9WoShjpddtb.png\"]', 'Approved by admin', 1, 'eww', '2025-09-06 08:15:16', '2025-09-10 05:15:05'),
(8, 6, 6, 'completed', '2025-09-10 05:42:38', '2025-09-10 05:45:19', '2025-09-10 05:45:37', '[\"task-submissions\\/RYDwXBB1l2elrvR9AzqfgtDO4abS3jBVEQYmbLtG.png\"]', 'Approved by admin', 1, 'sus', '2025-09-10 05:42:38', '2025-09-10 05:45:37'),
(9, 4, 6, 'completed', '2025-09-10 05:47:35', '2025-09-10 05:48:43', '2025-09-10 05:49:00', '[\"task-submissions\\/S5hP9Nsp6Vu2nESo3uMyu22OwD4HlJoNmQDHo82C.png\"]', 'Approved by admin', 1, 'submit more', '2025-09-10 05:47:35', '2025-09-10 05:49:00'),
(10, 8, 5, 'completed', '2025-09-21 04:20:27', '2025-09-21 04:53:26', '2025-09-21 04:57:45', '[]', 'Approved by admin', 0, NULL, '2025-09-21 04:20:27', '2025-09-21 04:57:45'),
(11, 8, 6, 'completed', '2025-09-21 04:21:03', '2025-09-21 04:21:30', '2025-09-21 04:22:40', '[\"task-submissions\\/aJJDmAd7nlGmEDJQD5gLQYL9M0FiCRG33my0uq46.png\"]', 'Approved by admin', 0, NULL, '2025-09-21 04:21:03', '2025-09-21 04:22:40'),
(12, 9, 5, 'completed', '2025-09-21 04:52:01', '2025-09-21 05:14:39', '2025-09-21 05:15:15', '[]', 'Approved by admin', 0, NULL, '2025-09-21 04:52:01', '2025-09-21 05:15:15'),
(13, 5, 7, 'completed', '2025-09-21 05:00:04', '2025-09-21 05:10:52', '2025-09-21 05:11:19', '[]', 'Approved by admin', 0, NULL, '2025-09-21 05:00:04', '2025-09-21 05:11:19'),
(14, 8, 7, 'completed', '2025-09-21 05:00:30', '2025-09-21 05:27:38', '2025-09-21 05:27:55', '[]', 'Approved by admin', 0, NULL, '2025-09-21 05:00:30', '2025-09-21 05:27:55'),
(15, 10, 7, 'completed', '2025-09-21 06:06:50', '2025-09-21 06:39:38', '2025-09-21 06:39:54', '[]', 'Approved by admin', 0, NULL, '2025-09-21 06:06:50', '2025-09-21 06:39:54');

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
(5, 'Allen Kim', NULL, 'Rafaela', 'allenkimrafaela@gmail.com', '$2y$12$FbyrySawbj4pbKGbsaBgiekXhxBxmhPk6ojKggq/yoDuPy49GOtM2', 67, 'user', 'active', '2025-09-01', NULL, NULL, '2025-09-01 06:43:19', '2025-09-21 06:06:30'),
(6, 'John', NULL, 'Doe', 'j.doe@email.com', '$2y$12$Fny4WoqDJmzam2YRY46fFuvpcMSOcl0GjnsgulecFw50gvzpvVIbG', 91, 'user', 'active', '2025-09-02', NULL, NULL, '2025-09-02 04:42:06', '2025-09-21 04:51:34'),
(7, 'Jane', NULL, 'Doe', 'jane.d@email.com', '$2y$12$br36ZSNuyogIihQHXo97Vu78VCzzDu/84BJqs.1gGIXr8XCvfJawO', 36, 'user', 'active', '2025-09-21', NULL, NULL, '2025-09-21 04:59:15', '2025-09-21 06:39:54');

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
  MODIFY `feedbackId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `rewardId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_redemption`
--
ALTER TABLE `reward_redemption`
  MODIFY `redemptionId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `roleId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tap_nominations`
--
ALTER TABLE `tap_nominations`
  MODIFY `nominationId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `taskId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `task_assignment`
--
ALTER TABLE `task_assignment`
  MODIFY `assignmentId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_assignments`
--
ALTER TABLE `task_assignments`
  MODIFY `assignmentId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `task_submission`
--
ALTER TABLE `task_submission`
  MODIFY `submissionId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_fk1_userid_foreign` FOREIGN KEY (`FK1_userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_fk2_roleid_foreign` FOREIGN KEY (`FK2_roleId`) REFERENCES `roles` (`roleId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
