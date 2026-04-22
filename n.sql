-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2026 at 02:34 AM
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
-- Database: `n`
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
-- Table structure for table `journals`
--

CREATE TABLE `journals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `nama_atasan` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `tanggal_pekerjaan` date DEFAULT NULL,
  `uraian_pekerjaan` text NOT NULL,
  `dokumen_pekerjaan` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `mood` varchar(255) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `no` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `received_by_admin` tinyint(1) NOT NULL DEFAULT 0,
  `admin_checks` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `received_at` timestamp NULL DEFAULT NULL,
  `penilai_kasubang` varchar(255) DEFAULT NULL,
  `penilai_tu` varchar(255) DEFAULT NULL,
  `penilai_katimker` varchar(255) DEFAULT NULL,
  `jenis_katimker` enum('program','evaluasi','pemanfaatan') DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journals`
--

INSERT INTO `journals` (`id`, `user_id`, `title`, `nama_atasan`, `content`, `tanggal_pekerjaan`, `uraian_pekerjaan`, `dokumen_pekerjaan`, `category`, `mood`, `tags`, `is_private`, `created_at`, `updated_at`, `no`, `tanggal`, `received_by_admin`, `admin_checks`, `received_at`, `penilai_kasubang`, `penilai_tu`, `penilai_katimker`, `jenis_katimker`, `admin_id`) VALUES
(6, 6, NULL, NULL, NULL, NULL, 'cqvbcqcvq', 'uploads/journal-documents/journal_1769998915.jpg', NULL, NULL, NULL, 0, '2026-02-01 19:21:55', '2026-02-01 19:25:43', NULL, '2026-02-02', 1, 0, '2026-02-01 19:25:43', NULL, NULL, NULL, NULL, NULL),
(7, 6, NULL, NULL, NULL, NULL, 'aetdeevedg', 'uploads/journal-documents/journal_1770086673.png', NULL, NULL, NULL, 0, '2026-02-02 19:44:33', '2026-02-02 19:45:56', NULL, '2026-02-03', 1, 0, '2026-02-02 19:45:56', NULL, NULL, NULL, NULL, NULL),
(99, 6, NULL, NULL, NULL, NULL, 'uddeuycvuyedv', 'uploads/journal-documents/journal_1770360265.png', NULL, NULL, NULL, 0, '2026-02-05 23:44:25', '2026-02-05 23:44:25', NULL, '2026-02-06', 0, 0, NULL, 'iheifhiuefhiuefeiuh', 'widwiudbwi', 'bdwdbiwdb', 'evaluasi', NULL),
(100, 6, NULL, NULL, NULL, NULL, 'uddeuycvuyedv', 'uploads/journal-documents/journal_1770360266.png', NULL, NULL, NULL, 0, '2026-02-05 23:44:26', '2026-02-05 23:44:26', NULL, '2026-02-06', 0, 0, NULL, 'iheifhiuefhiuefeiuh', 'widwiudbwi', 'bdwdbiwdb', 'evaluasi', NULL),
(194, 6, 'Juki', NULL, NULL, NULL, 'Ngobck', 'uploads/journal-documents/journal_1770862217.jpg', NULL, NULL, NULL, 0, '2026-02-11 19:10:17', '2026-02-23 03:47:10', '5', NULL, 0, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(198, 6, 'jhbjjhvbhjhhbj', 'jhvuhvuhvh', NULL, NULL, 'gfdxgfhgvjk', 'journal-documents/journal_1771823929.jpeg', NULL, NULL, NULL, 0, '2026-02-23 05:18:49', '2026-02-23 05:26:18', 'JRN-20260223-001', '2026-02-23', 0, 2, NULL, NULL, NULL, NULL, NULL, 5),
(202, 36, 'kelas king', 'pak mul', NULL, NULL, 'kvnvnisnvidnvcvmcsv', 'journal-documents/journal_1772078402.png', NULL, NULL, NULL, 0, '2026-02-26 04:00:02', '2026-02-26 04:00:02', 'JRN-20260226-001', '2026-02-26', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(203, 36, 'kelas king', 'jhvuhvuhvh', NULL, NULL, 'dssdafdga', 'https://static.wikitide.net/bluearchivewiki/thumb/7/7d/Lobby_Banner_20240110_01.png/450px-Lobby_Banner_20240110_01.png', NULL, NULL, NULL, 0, '2026-02-26 04:07:37', '2026-02-26 04:07:37', 'JRN-20260226-002', '2026-02-26', 0, 0, NULL, NULL, NULL, NULL, NULL, 5),
(204, 36, 'ba', NULL, NULL, NULL, 'dadasdadadwfgbfs', 'https://static.wikitide.net/bluearchivewiki/thumb/e/ed/Lobby_Banner_20260225_01.png/450px-Lobby_Banner_20260225_01.png', NULL, NULL, NULL, 0, '2026-02-27 01:36:53', '2026-03-03 01:43:31', 'JRN-20260227-001', '2026-02-27', 1, 1, '2026-03-03 01:43:31', NULL, NULL, NULL, NULL, NULL),
(205, 36, 'kelas king', NULL, NULL, NULL, 'knjbvfxfcgvhbj', 'journal-documents/journal_1772159152.png', NULL, NULL, NULL, 0, '2026-02-27 02:25:52', '2026-03-03 01:44:06', 'JRN-20260227-002', '2026-02-27', 1, 1, '2026-03-03 01:44:06', NULL, NULL, NULL, NULL, NULL),
(206, 36, 'dakkkkk', NULL, NULL, NULL, 'jhjvghjbkjdnlasd', 'journal-documents/journal_1772159288.png', NULL, NULL, NULL, 0, '2026-02-27 02:28:08', '2026-03-03 01:44:06', 'JRN-20260227-003', '2026-02-27', 1, 1, '2026-03-03 01:44:06', NULL, NULL, NULL, NULL, NULL),
(207, 36, 'jurnalku', NULL, NULL, NULL, 'admiinnnnnnn', 'journal-documents/journal_1772160153.png', NULL, NULL, NULL, 0, '2026-02-27 02:42:33', '2026-03-03 01:44:06', 'JRN-20260227-004', '2026-02-27', 1, 1, '2026-03-03 01:44:06', NULL, NULL, NULL, NULL, NULL),
(208, 36, 'njhghcxdgxhcvhbj', NULL, NULL, NULL, 'jbhvgcxddxfcgvhj', 'journal-documents/journal_1772327732.png', NULL, NULL, NULL, 0, '2026-03-01 01:15:33', '2026-03-03 01:44:52', 'JRN-20260301-001', '2026-03-01', 1, 3, '2026-03-03 01:44:52', NULL, NULL, NULL, NULL, NULL),
(209, 6, 'Puasa', NULL, NULL, NULL, 'Hshbshdd', 'journal-documents/journal_1772497188.jpg', NULL, NULL, NULL, 0, '2026-03-03 00:19:49', '2026-03-03 00:46:50', 'JRN-20260303-001', '2026-03-03', 1, 1, '2026-03-03 00:46:50', NULL, NULL, NULL, NULL, NULL),
(210, 6, 'Puasa', NULL, NULL, NULL, 'Hshbshdd', 'journal-documents/journal_1772497250.jpg', NULL, NULL, NULL, 0, '2026-03-03 00:20:50', '2026-03-03 00:47:00', 'JRN-20260303-002', '2026-03-03', 0, 1, '2026-03-03 00:46:50', NULL, NULL, NULL, NULL, NULL),
(211, 36, 'abangku', NULL, NULL, NULL, 'putang', 'journal-documents/journal_1773032026.jpeg', NULL, NULL, NULL, 0, '2026-03-09 04:53:46', '2026-03-10 01:25:03', 'JRN-20260309-001', '2026-03-09', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(212, 6, 'testing 1', NULL, NULL, NULL, 'saya mengerjakan', 'journal-documents/journal_1774418763.jfif', NULL, NULL, NULL, 0, '2026-03-25 06:06:04', '2026-03-25 06:06:04', 'JRN-20260325-001', '2026-03-25', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(213, 6, 'Lebaran', NULL, NULL, NULL, 'Selamat', 'journal-documents/journal_1774419105.jpg', NULL, NULL, NULL, 0, '2026-03-25 06:11:45', '2026-03-25 06:13:40', 'JRN-20260325-002', '2026-03-25', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(214, 36, 'mvmfjvjfbf', NULL, NULL, NULL, 'njjbvfjb', 'journal-documents/journal_1774485504.jpeg', NULL, NULL, NULL, 0, '2026-03-26 00:38:25', '2026-03-26 00:38:25', 'JRN-20260326-001', '2026-03-26', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(215, 6, 'testing upload 1', NULL, NULL, NULL, 'prikitiw', 'journal-documents/journal_1774748633.jfif', NULL, NULL, NULL, 0, '2026-03-29 01:43:54', '2026-04-06 05:52:51', 'JRN-20260329-001', '2026-03-29', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(216, 6, 'jurnal testing lagi', NULL, NULL, NULL, 'hhhidhshdiah', 'journal-documents/journal_1775455467.png', NULL, NULL, NULL, 0, '2026-04-06 06:04:27', '2026-04-06 06:04:27', 'JRN-20260406-001', '2026-04-06', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `journal_admin`
--

CREATE TABLE `journal_admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'waiting',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_admin`
--

INSERT INTO `journal_admin` (`id`, `journal_id`, `admin_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 204, 5, 'approved', '2026-02-27 01:36:53', '2026-03-03 01:43:31'),
(2, 205, 29, 'approved', '2026-02-27 02:25:52', '2026-03-03 01:44:06'),
(3, 206, 29, 'approved', '2026-02-27 02:28:08', '2026-03-03 01:44:06'),
(4, 207, 29, 'approved', '2026-02-27 02:42:33', '2026-03-03 01:44:06'),
(5, 208, 29, 'approved', '2026-03-01 01:15:33', '2026-03-03 01:44:07'),
(6, 208, 5, 'approved', '2026-03-01 01:15:33', '2026-03-03 01:43:31'),
(8, 209, 5, 'approved', '2026-03-03 00:19:49', '2026-03-03 00:46:50'),
(9, 210, 5, 'revised', '2026-03-03 00:20:50', '2026-03-03 00:47:00'),
(11, 211, 5, 'approved', '2026-03-09 04:53:46', '2026-03-10 01:25:48'),
(12, 212, 29, 'waiting', '2026-03-25 06:06:04', '2026-03-25 06:06:04'),
(13, 212, 5, 'waiting', '2026-03-25 06:06:04', '2026-03-25 06:06:04'),
(14, 213, 29, 'waiting', '2026-03-25 06:11:45', '2026-03-25 06:11:45'),
(15, 213, 5, 'approved', '2026-03-25 06:11:45', '2026-03-25 06:13:40'),
(16, 214, 29, 'waiting', '2026-03-26 00:38:25', '2026-03-26 00:38:25'),
(17, 214, 5, 'waiting', '2026-03-26 00:38:25', '2026-03-26 00:38:25'),
(19, 215, 29, 'waiting', '2026-03-29 01:43:54', '2026-03-29 01:43:54'),
(20, 215, 5, 'approved', '2026-03-29 01:43:54', '2026-04-06 05:52:51'),
(22, 216, 29, 'waiting', '2026-04-06 06:04:27', '2026-04-06 06:04:27'),
(23, 216, 5, 'waiting', '2026-04-06 06:04:27', '2026-04-06 06:04:27');

-- --------------------------------------------------------

--
-- Table structure for table `journal_admin_approvals`
--

CREATE TABLE `journal_admin_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_admin_approvals`
--

INSERT INTO `journal_admin_approvals` (`id`, `journal_id`, `admin_id`, `created_at`, `updated_at`) VALUES
(10, 194, 5, '2026-02-23 03:46:45', '2026-02-23 03:46:45'),
(11, 194, 29, '2026-02-23 03:47:10', '2026-02-23 03:47:10'),
(13, 198, 5, '2026-02-23 05:26:18', '2026-02-23 05:26:18');

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
(4, '2026_01_29_052636_create_journals_table', 1),
(5, '2026_01_29_053541_add_work_fields_to_journals_table', 1),
(6, '2026_01_29_060031_create_users_table', 1),
(7, '2026_01_29_070000_add_profile_photo_to_users_table', 1),
(8, '2026_01_30_013536_add_task_completed_to_users_table', 2),
(12, '2026_01_30_015718_add_received_fields_to_journals_table', 3),
(13, '2026_01_30_022807_add_journal_fields_to_journals_table', 3),
(14, '2026_01_30_024111_make_title_content_nullable_in_journals_table', 4),
(15, '2026_01_30_024313_make_tanggal_pekerjaan_nullable_in_journals_table', 5),
(16, '2026_02_03_074901_add_division_to_users_table', 6),
(17, '2026_02_04_014906_update_role_column_in_users_table', 7),
(18, '2026_02_04_021655_create_notifications_table', 8),
(19, '2026_02_09_030000_add_profile_details_to_users_table', 9),
(20, '2026_02_10_033700_add_admin_checks_to_journals_table', 10),
(21, '2026_02_10_093500_add_admin_checks_to_journals_table', 11),
(22, '2026_02_10_080000_create_journal_admin_approvals_table', 12),
(23, '2026_02_19_020119_add_admin_id_and_nama_atasan_to_journals_table', 13),
(24, '2026_02_19_020729_update_dokumen_pekerjaan_column_length', 14),
(25, '2026_02_25_074710_add_google_id_and_avatar_to_users_table', 15),
(26, '2026_02_26_083441_add_provider_to_users_table', 16),
(27, '2026_02_26_111028_create_journal_admin_table', 17),
(28, '2026_02_27_090603_add_status_to_journal_admin_table', 18),
(29, '2026_03_10_110000_create_push_subscriptions_table', 19),
(30, '2026_03_10_110100_create_notification_preferences_table', 19),
(31, '2026_03_10_110200_add_theme_and_default_page_to_users_table', 19),
(32, '2026_03_31_095440_add_nip_to_users_table', 20);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `journal_id` bigint(20) UNSIGNED DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `message`, `journal_id`, `read`, `created_at`, `updated_at`) VALUES
(17, 6, 'progress', 'Jurnal \'Juki\' telah diperiksa oleh admin. Status: 1/3', 194, 1, '2026-02-23 03:46:45', '2026-03-05 02:26:21'),
(18, 6, 'progress', 'Jurnal \'Juki\' telah diperiksa oleh admin. Status: 2/3', 194, 1, '2026-02-23 03:47:10', '2026-03-05 02:26:21'),
(19, 6, 'progress', 'Jurnal \'jhbjjhvbhjhhbj\' telah diperiksa oleh admin. Status: 1/3', 198, 1, '2026-02-23 05:23:20', '2026-03-05 02:26:21'),
(20, 6, 'progress', 'Jurnal \'jhbjjhvbhjhhbj\' telah diperiksa oleh admin. Status: 2/3', 198, 1, '2026-02-23 05:26:18', '2026-03-05 02:26:21'),
(26, 6, 'revised', 'Jurnal \'kgyjftdytfuguhoija\' perlu direvisi. Silakan buat jurnal baru dengan perbaikan yang diperlukan.', NULL, 1, '2026-02-25 01:45:58', '2026-03-05 02:26:21'),
(27, 29, 'progress', 'Pegawai Altaf mengirimkan jurnal baru: \'jurnalku\'', 207, 0, '2026-02-27 02:42:33', '2026-02-27 02:42:33'),
(28, 36, 'revised', 'Jurnal \'jurnalku\' perlu direvisi sesuai arahan dari Admin Four.', 207, 1, '2026-02-27 02:46:32', '2026-03-25 03:49:12'),
(29, 29, 'progress', 'Pegawai Altaf mengirimkan jurnal baru: \'njhghcxdgxhcvhbj\'', 208, 0, '2026-03-01 01:15:33', '2026-03-01 01:15:33'),
(30, 5, 'progress', 'Pegawai Altaf mengirimkan jurnal baru: \'njhghcxdgxhcvhbj\'', 208, 1, '2026-03-01 01:15:33', '2026-03-06 02:25:45'),
(32, 36, 'received', 'Jurnal \'njhghcxdgxhcvhbj\' telah disetujui oleh aee. Progress: 1/3', 208, 1, '2026-03-02 01:48:59', '2026-03-25 03:49:12'),
(33, 36, 'revised', 'Jurnal \'njhghcxdgxhcvhbj\' perlu direvisi sesuai arahan dari aee.', 208, 1, '2026-03-02 01:49:06', '2026-03-25 03:49:12'),
(34, 36, 'received', 'Jurnal \'ba\' telah disetujui oleh aee. Progress: 1/1', 204, 1, '2026-03-02 01:56:06', '2026-03-25 03:49:12'),
(35, 36, 'rejected', 'Persetujuan jurnal \'ba\' dibatalkan oleh aee.', 204, 1, '2026-03-02 02:14:28', '2026-03-25 03:49:12'),
(36, 36, 'rejected', 'Persetujuan jurnal \'ba\' dibatalkan oleh aee.', 204, 1, '2026-03-02 02:14:47', '2026-03-25 03:49:12'),
(37, 5, 'progress', 'Pegawai altaf mengirimkan jurnal baru: \'Puasa\'', 209, 1, '2026-03-03 00:19:49', '2026-03-06 02:25:45'),
(38, 5, 'progress', 'Pegawai altaf mengirimkan jurnal baru: \'Puasa\'', 210, 1, '2026-03-03 00:20:50', '2026-03-06 02:25:45'),
(39, 6, 'received', 'Jurnal \'Puasa\' telah disetujui oleh aee. Progress: 1/1', 209, 1, '2026-03-03 00:46:50', '2026-03-05 02:26:21'),
(40, 6, 'received', 'Jurnal \'Puasa\' telah disetujui oleh aee. Progress: 1/1', 210, 1, '2026-03-03 00:46:50', '2026-03-05 02:26:21'),
(41, 6, 'revised', 'Jurnal \'Puasa\' perlu direvisi sesuai arahan dari aee.', 210, 1, '2026-03-03 00:47:00', '2026-03-05 02:26:21'),
(42, 36, 'received', 'Jurnal \'ba\' telah disetujui oleh aee. Progress: 1/1', 204, 1, '2026-03-03 01:43:31', '2026-03-25 03:49:12'),
(43, 36, 'received', 'Jurnal \'njhghcxdgxhcvhbj\' telah disetujui oleh aee. Progress: 1/3', 208, 1, '2026-03-03 01:43:31', '2026-03-25 03:49:12'),
(44, 36, 'received', 'Jurnal \'kelas king\' telah disetujui oleh Admin Four. Progress: 1/1', 205, 1, '2026-03-03 01:44:06', '2026-03-25 03:49:12'),
(45, 36, 'received', 'Jurnal \'dakkkkk\' telah disetujui oleh Admin Four. Progress: 1/1', 206, 1, '2026-03-03 01:44:06', '2026-03-25 03:49:12'),
(46, 36, 'received', 'Jurnal \'jurnalku\' telah disetujui oleh Admin Four. Progress: 1/1', 207, 1, '2026-03-03 01:44:06', '2026-03-25 03:49:12'),
(47, 36, 'received', 'Jurnal \'njhghcxdgxhcvhbj\' telah disetujui oleh Admin Four. Progress: 2/3', 208, 1, '2026-03-03 01:44:07', '2026-03-25 03:49:12'),
(48, 36, 'received', 'Jurnal \'njhghcxdgxhcvhbj\' telah disetujui oleh pak mul. Progress: 3/3', 208, 1, '2026-03-03 01:44:52', '2026-03-25 03:49:12'),
(50, 5, 'progress', 'Pegawai Altaf mengirimkan jurnal baru: \'abangku\'', 211, 1, '2026-03-09 04:53:46', '2026-03-10 01:21:58'),
(51, 36, 'received', 'Jurnal \'abangku\' telah disetujui oleh aee. Progress: 1/2', 211, 1, '2026-03-10 01:25:48', '2026-03-25 03:49:12'),
(52, 29, 'new_journal_batch', '1 pegawai telah mengirim jurnal', 214, 0, '2026-03-25 06:06:04', '2026-03-26 00:38:25'),
(53, 5, 'new_journal_batch', '1 pegawai telah mengirim jurnal', 213, 1, '2026-03-25 06:06:04', '2026-03-25 06:13:28'),
(54, 6, 'received', 'Jurnal \'Lebaran\' telah disetujui oleh aee. Progress: 1/2', 213, 0, '2026-03-25 06:13:40', '2026-03-25 06:13:40'),
(55, 5, 'new_journal_batch', '1 pegawai telah mengirim jurnal', 214, 1, '2026-03-26 00:38:25', '2026-03-26 01:12:38'),
(57, 29, 'new_journal_batch', 'Pegawai altaf mengirimkan jurnal baru: \'testing upload 1\'', 215, 0, '2026-03-29 01:43:54', '2026-03-29 01:43:54'),
(58, 5, 'new_journal_batch', 'Pegawai altaf mengirimkan jurnal baru: \'testing upload 1\'', 215, 1, '2026-03-29 01:43:54', '2026-03-31 00:59:14'),
(60, 6, 'received', 'Jurnal \'testing upload 1\' telah disetujui oleh BRMP ADMIN. Progress: 1/2', 215, 0, '2026-04-06 05:52:51', '2026-04-06 05:52:51'),
(61, 29, 'new_journal_batch', 'Pegawai altaf mengirimkan jurnal baru: \'jurnal testing lagi\'', 216, 0, '2026-04-06 06:04:27', '2026-04-06 06:04:27'),
(62, 5, 'new_journal_batch', 'Pegawai altaf mengirimkan jurnal baru: \'jurnal testing lagi\'', 216, 0, '2026-04-06 06:04:27', '2026-04-06 06:04:27');

-- --------------------------------------------------------

--
-- Table structure for table `notification_preferences`
--

CREATE TABLE `notification_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 1,
  `revised` tinyint(1) NOT NULL DEFAULT 1,
  `rejected` tinyint(1) NOT NULL DEFAULT 1,
  `feedback` tinyint(1) NOT NULL DEFAULT 1,
  `new_journal` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_preferences`
--

INSERT INTO `notification_preferences` (`id`, `user_id`, `approved`, `revised`, `rejected`, `feedback`, `new_journal`, `created_at`, `updated_at`) VALUES
(1, 36, 1, 1, 1, 1, 1, '2026-03-10 02:23:25', '2026-03-10 02:23:25'),
(2, 5, 1, 1, 1, 1, 1, '2026-03-10 02:56:45', '2026-03-10 02:56:45'),
(3, 6, 1, 1, 1, 1, 1, '2026-04-06 06:03:07', '2026-04-06 06:03:07');

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
-- Table structure for table `push_subscriptions`
--

CREATE TABLE `push_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `endpoint` varchar(255) NOT NULL,
  `public_key` varchar(255) DEFAULT NULL,
  `auth_token` varchar(255) DEFAULT NULL,
  `content_encoding` varchar(255) DEFAULT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `provider` varchar(255) NOT NULL DEFAULT 'local',
  `theme` varchar(10) NOT NULL DEFAULT 'light',
  `default_page` varchar(20) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `division` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `profile_photo` varchar(255) DEFAULT NULL,
  `task_completed` tinyint(1) NOT NULL DEFAULT 0,
  `task_completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `nip`, `provider`, `theme`, `default_page`, `google_id`, `avatar`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `division`, `phone`, `address`, `birth_date`, `gender`, `is_admin`, `profile_photo`, `task_completed`, `task_completed_at`) VALUES
(5, 'BRMP ADMIN', 'dekeng1234@gmail.com', '12345678910', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$SmhB.XB12yarxLJJdeV1w..6M1EkOHsMrIXOcueBsiPNWhlXfOrQe', 'dFtgxAV4lPn6UwbuELgtX1Q3T0piLMbaMimlawCCVyOXFrjkYBJ2pNuS8vZ5', '2026-02-01 18:38:25', '2026-04-06 06:12:00', 'admin', 'Penata Layanan Operasional', '087801684033', 'Jl. Salak No. 22, Kelurahan Babakan, Kecamatan Bogor Tengah, Kota Bogor, Jawa Barat 16128', NULL, NULL, 1, 'https://th.bing.com/th/id/OIP.Da4fyTJn7iq6D3JU84lpLwHaHa?w=155&h=180&c=7&r=0&o=7&pid=1.7&rm=3', 0, NULL),
(6, 'altaf', 'altaf@gmail.com', NULL, 'local', 'dark', NULL, NULL, NULL, NULL, '$2y$12$lKTESr2VPegAyf.gKU6WUe9Dsdi4tCmFjh79VdvE1VQTHEMRG.ZSm', 'DVcH26zvD9N7PHNRy2Y8mHb8kVJXYrxfZuccAjRyrRRZU4KKzGc5E7JvwglN', '2026-02-01 19:19:19', '2026-04-06 06:16:31', 'user', NULL, NULL, NULL, NULL, NULL, 0, 'profile-photos/profile_6_1771824106.jfif', 0, NULL),
(29, 'Admin Four', 'admin4@example.com', NULL, 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$BSZiKxfb5QFtRJXsjlfJ1Oqh906Fy8jEcD6lXkyrcDtLVn2gwQDRO', NULL, '2026-02-09 21:20:17', '2026-03-26 00:37:25', 'admin', NULL, NULL, NULL, NULL, NULL, 1, 'profile-photos/profile_admin_29_1771823838.jfif', 0, NULL),
(36, 'Altaf', 'altafrifqibareeq@gmail.com', NULL, 'google', 'dark', NULL, '101279229197113722675', 'https://lh3.googleusercontent.com/a/ACg8ocLJnWm6qlXcDCSlYRX6go-qgLkfRsx954X0_fmv-2S0boVSDQco=s96-c', NULL, NULL, 'AlyyumdnRgSr8t1wxQq1dtVXjIxC5Ursx82nvV8DZEiLkjORJSerLC3LnEVl', '2026-02-26 01:49:20', '2026-03-26 01:32:04', 'pegawai', 'IT', 'cihuy', 'semplak', '2026-02-26', 'male', 0, 'profile-photos/profile_36_1772502588.jpg', 0, NULL),
(39, 'Eva Yuliana, S.Kom.', 'evayuliana@brmpph.com', '198801102025212059', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$45evuJJJWa7qxKXU2si4HudgWWUhIsOfv3ip.uGEC0bnGRXD0abVi', NULL, '2026-03-31 04:40:23', '2026-03-31 04:40:23', 'pegawai', 'Penata Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(40, 'Tigia Eloka Kailaku, S.Si., M.M.', 'tigiaelokakailaku@brmpph.com', '198809032025212053', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$mODhwhj0lJ6gaoLtN4rfVOv9ghQsQTMgUQCvL1/5cq1cUuOaKT9wq', NULL, '2026-03-31 04:40:24', '2026-03-31 04:40:24', 'pegawai', 'Penata Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(41, 'Tri Yogi Adi Wigati, S.Psi.', 'triyogiadiwigati@brmpph.com', '199504162025212076', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$o72WL9WCu/SChhrX/MxcQuWm4EsFXqjMqjPeplaE3ghvH105qvIHS', NULL, '2026-03-31 04:40:24', '2026-03-31 04:40:24', 'pegawai', 'Penata Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(42, 'Rini Rospiani Iswari', 'rinirospianiiswari@brmpph.com', '198003052025212039', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$Rp9pS09bXJdEqk1lSsTwTOzX.FYCeVdQYQjzwPX4ijaDoAdvTkJv.', NULL, '2026-03-31 04:40:24', '2026-03-31 04:40:24', 'pegawai', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(43, 'Ahmad Ridwan', 'ahmadridwan@brmpph.com', '198012132025211039', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$aVWIaqY2Q.pw1x2e7POIPuUylV2Bp/fFRQsZ92aPK.N8CeEmD6rpe', NULL, '2026-03-31 04:40:24', '2026-03-31 04:40:24', 'pegawai', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(44, 'Ahmad Sofyan', 'ahmadsofyan@brmpph.com', '197702072025211040', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$0Lq2P9YuvuBBSiDyXqCumOXU8qj5YXA0KbNe1z2lVETAYZ82xUxuS', NULL, '2026-03-31 04:40:25', '2026-03-31 04:40:25', 'pegawai', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(45, 'Marjuki', 'marjuki@brmpph.com', '198805082025211019', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$9Gks.lRTePNgwu9FMlKRt.dFDMgDQTyhz0KRZyBFfrPAibyokOsR.', NULL, '2026-03-31 04:40:25', '2026-03-31 04:40:25', 'pegawai', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(46, 'Toni Hendrik', 'tonihendrik@brmpph.com', '197308012025211037', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$7BQ6rws55Nfk7oNARyOFWO8RSZfMnw.Gcl11AFosFcxVx0.4xNmhm', NULL, '2026-03-31 04:40:26', '2026-03-31 04:40:26', 'pegawai', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(47, 'Sumardi', 'sumardi@brmpph.com', '198808032025211097', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$XjLGVVaj2XrMeE5uA6MS5ur21a0lJjwT6Y/NoiRGhKoKjlrR/KVWS', NULL, '2026-03-31 04:40:26', '2026-03-31 04:40:26', 'pegawai', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(48, 'Hermawan', 'hermawan@brmpph.com', '198605272025211069', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$MatN7pucQDL3KUHsbwnM4Oz811Nsc6gtVcnd6qZcJXTn5Av06fNcu', NULL, '2026-03-31 04:40:26', '2026-03-31 04:40:26', 'pegawai', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(49, 'Widiyatno', 'widiyatno@brmpph.com', '198708222025211051', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$JVmR7pZ2dk1UpdZxdSplc.SgyVQY3FlLVZOEdnPvH0DDbU/fzKsJK', NULL, '2026-03-31 04:40:26', '2026-03-31 04:40:26', 'pegawai', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(50, 'Dedem Danuatmadja', 'dedemdanuatmadja@brmpph.com', '198209132025211057', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$qP7056EyIOh.EpJBDhQ9yuUjoDhi8M/E9nz.G605PfY5aZSIBPrHu', NULL, '2026-03-31 04:40:27', '2026-03-31 04:40:27', 'pegawai', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(51, 'Hoerudin', 'hoerudin@brmpph.com', '197907152025211096', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$FrL0BveFPVC9..dkPADCHuN5gnGqdA43UJlolkKyvZRb4oGZ9sh6.', NULL, '2026-03-31 04:40:27', '2026-03-31 04:40:27', 'pegawai', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(52, 'Dindin Saepudin', 'dindinsaepudin@brmpph.com', '198604192025211075', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$zuI02/1vF3FSL528wZZsjO5kFYAv3kdOzyDjRhuwmYkmHYr.kPaKi', NULL, '2026-03-31 04:40:27', '2026-03-31 04:40:27', 'pegawai', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(53, 'Sahim', 'sahim@brmpph.com', '197804082025211082', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$luht4LrIrXlAcL.3ILd99eC6TnDCTPoSTDEju3z5Res3Oe.dgXpPy', NULL, '2026-03-31 04:40:28', '2026-03-31 04:40:28', 'pegawai', 'Pengelola Umum Operasional', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
(54, 'Tigia Eloka Kailaku, S.Si., M.M.', 'tigiaadminplo@brmpph.com', '198809032025212053', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$ZXrac/ZzPO6YwYeidPk1lOHtdap8ntRZofkgdgLFgtZHZuBK/vY/e', NULL, '2026-03-31 04:48:51', '2026-03-31 04:48:51', 'admin', 'Penata Layanan Operasional', NULL, NULL, NULL, NULL, 1, NULL, 0, NULL),
(55, 'Rini Rospiani Iswari', 'riniadminolo@brmpph.com', '198003052025212039', 'local', 'light', NULL, NULL, NULL, NULL, '$2y$12$DciZvbp1/ZJISqfOke4qUONYxYSIm0L1NnkRBPX.lg0ggElZs2XLu', NULL, '2026-03-31 04:48:51', '2026-03-31 04:48:51', 'admin', 'Operator Layanan Operasional', NULL, NULL, NULL, NULL, 1, NULL, 0, NULL);

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
-- Indexes for table `journals`
--
ALTER TABLE `journals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journals_user_id_foreign` (`user_id`),
  ADD KEY `journals_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `journal_admin`
--
ALTER TABLE `journal_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_admin_journal_id_foreign` (`journal_id`),
  ADD KEY `journal_admin_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `journal_admin_approvals`
--
ALTER TABLE `journal_admin_approvals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journal_admin_approvals_journal_id_admin_id_unique` (`journal_id`,`admin_id`),
  ADD KEY `journal_admin_approvals_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_journal_id_foreign` (`journal_id`),
  ADD KEY `notifications_user_id_read_index` (`user_id`,`read`);

--
-- Indexes for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_preferences_user_id_unique` (`user_id`),
  ADD KEY `notification_preferences_user_id_index` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `push_subscriptions_endpoint_unique` (`endpoint`),
  ADD KEY `push_subscriptions_user_id_index` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journals`
--
ALTER TABLE `journals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT for table `journal_admin`
--
ALTER TABLE `journal_admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `journal_admin_approvals`
--
ALTER TABLE `journal_admin_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `journals`
--
ALTER TABLE `journals`
  ADD CONSTRAINT `journals_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `journals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_admin`
--
ALTER TABLE `journal_admin`
  ADD CONSTRAINT `journal_admin_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `journal_admin_journal_id_foreign` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_admin_approvals`
--
ALTER TABLE `journal_admin_approvals`
  ADD CONSTRAINT `journal_admin_approvals_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `journal_admin_approvals_journal_id_foreign` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_journal_id_foreign` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD CONSTRAINT `notification_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  ADD CONSTRAINT `push_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
