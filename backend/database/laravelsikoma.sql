-- phpMyadmin_lapangan SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin_lapangan.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 25, 2025 at 04:01 PM
-- Server version: 9.1.0
-- PHP Version: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravelsikoma`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nohp` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pesan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `negara` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `perusahaan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `departement` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edukasi`
--

DROP TABLE IF EXISTS `edukasi`;
CREATE TABLE IF NOT EXISTS `edukasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` enum('Program','Satwa','Executive','') COLLATE utf8mb4_general_ci NOT NULL,
  `keygaleri` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `edukasi`
--

INSERT INTO `edukasi` (`id`, `judul`, `slug`, `deskripsi`, `foto`, `kategori`, `keygaleri`, `created_at`, `updated_at`) VALUES
(7, 'Pengelolaan Hutan', 'pengelolaan-hutan', '<p>The Power of Eco-Friendly Products at Greenify</p>', '1758815578.jpg', 'Program', '1cm0BZaG', '2025-09-25 22:52:58', '2025-09-25 22:52:58'),
(8, 'Pemanfaatan Hasil Hutan  Bukan Kayu (HHBK)', 'pemanfaatan-hasil-hutan-bukan-kayu-hhbk', '<p>How Greenify Helps You Make a Difference</p>', '1758815598.jpg', 'Program', 'MyGiqEoD', '2025-09-25 22:53:18', '2025-09-25 22:53:18'),
(9, 'Rehabilitasi  dan Perlindungan Hutan', 'rehabilitasi-dan-perlindungan-hutan', '<p>Reducing Your Carbon Footprint One Purchase at a Time</p>', '1758815627.jpg', 'Program', 'OjEmkgpg', '2025-09-25 22:53:47', '2025-09-25 22:53:47'),
(10, 'KT Laut Lestari', 'kt-laut-lestari', '<p>Siak</p>', '1758815673.png', 'Satwa', 'mt8rOFV8', '2025-09-25 22:54:33', '2025-09-25 22:54:33'),
(11, 'KT Mangrove', 'kt-mangrove', '<p>Perawang</p>', '1758815706.png', 'Satwa', 'WNt9B55u', '2025-09-25 22:55:06', '2025-09-25 22:55:06'),
(12, 'Monyet', 'monyet', '<p>Danau Pulau Besar</p>', '1758815734.png', 'Satwa', 'MaPreGx5', '2025-09-25 22:55:34', '2025-09-25 22:55:34'),
(13, 'Berharap Sejahtera Di Alam  yang Kaya', 'berharap-sejahtera-di-alam-yang-kaya', '<p>-</p>', '1758815768.png', 'Executive', '0IZeRZ6N', '2025-09-25 22:56:08', '2025-09-25 22:56:08'),
(14, 'Mengajak Masyarakat Untuk  Menjaga Hutan', 'mengajak-masyarakat-untuk-menjaga-hutan', '<p>-</p>', '1758815787.jpg', 'Executive', 'VHvMY1Op', '2025-09-25 22:56:27', '2025-09-25 22:56:27'),
(15, 'Hutan yang Harus di Jaga', 'hutan-yang-harus-di-jaga', '<p>-</p>', '1758815803.jpg', 'Executive', 'CSz1684d', '2025-09-25 22:56:43', '2025-09-25 22:56:43');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

DROP TABLE IF EXISTS `galeri`;
CREATE TABLE IF NOT EXISTS `galeri` (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keygaleri` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id`, `judul`, `keterangan`, `gambar`, `keygaleri`, `created_at`, `updated_at`) VALUES
(3, 'SIKOMA', 'Sistem Informasi Konservasi', '248fb997-4e04-40a2-8953-b75d2192ae59.jpg', 'banner', '2025-08-20 19:28:26', '2025-09-25 22:49:22'),
(51, 'lily', 'lily', '68cabf11ab52a.jpg', 'ySgHOCMI', '2025-09-17 21:00:49', '2025-09-17 21:00:49'),
(52, 'lily', 'lily', '68cabf11ad180.jpg', 'ySgHOCMI', '2025-09-17 21:00:49', '2025-09-17 21:00:49'),
(53, 'lily', 'lily', '68cabf11ae9eb.jpg', 'ySgHOCMI', '2025-09-17 21:00:49', '2025-09-17 21:00:49'),
(54, 'lily', 'lily', '68cabf11b059f.jpg', 'ySgHOCMI', '2025-09-17 21:00:49', '2025-09-17 21:00:49'),
(55, 'Lala', 'Lala', '68cabf8497ae8.jpg', '84v1GVuS', '2025-09-17 21:02:44', '2025-09-17 21:02:44'),
(56, 'Lala', 'Lala', '68cabf849930f.jpg', '84v1GVuS', '2025-09-17 21:02:44', '2025-09-17 21:02:44'),
(57, 'Lala', 'Lala', '68cabf849bb0e.jpg', '84v1GVuS', '2025-09-17 21:02:44', '2025-09-17 21:02:44'),
(58, 'Lala', 'Lala', '68cabf849d16d.jpg', '84v1GVuS', '2025-09-17 21:02:44', '2025-09-17 21:02:44'),
(59, 'Ucok', 'Ucok', '68cabfa9b0155.jpg', 'nY92xQkb', '2025-09-17 21:03:21', '2025-09-17 21:03:21'),
(60, 'Ucok', 'Ucok', '68cabfa9b1adb.jpg', 'nY92xQkb', '2025-09-17 21:03:21', '2025-09-17 21:03:21'),
(61, 'Ucok', 'Ucok', '68cabfa9b33c2.jpg', 'nY92xQkb', '2025-09-17 21:03:21', '2025-09-17 21:03:21'),
(62, 'Ucok', 'Ucok', '68cabfa9b5419.jpg', 'nY92xQkb', '2025-09-17 21:03:21', '2025-09-17 21:03:21');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kawasankonservasi`
--

DROP TABLE IF EXISTS `kawasankonservasi`;
CREATE TABLE IF NOT EXISTS `kawasankonservasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `luasKawasan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jenisKawasan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `kondisi` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status` text COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kawasankonservasi`
--

INSERT INTO `kawasankonservasi` (`id`, `deskripsi`, `luasKawasan`, `jenisKawasan`, `alamat`, `kondisi`, `status`, `gambar`, `created_at`, `updated_at`) VALUES
(1, '<p>Kawasan konservasi ini merupakan area vital yang dilindungi untuk menjaga keanekaragaman hayati dan ekosistem unik. Berbagai upaya dilakukan untuk pelestarian flora dan fauna endemik, serta pengelolaan sumber daya alam yang berkelanjutan. Area ini juga berfungsi sebagai laboratorium alam untuk penelitian dan edukasi lingkungan.</p>', '12.500 Ha', 'Taman Nasional', 'Jl. Pengayoman No.1,\r\nTengkerang Utara,\r\nKec. Bukit Raya,\r\nKota Pekanbaru, Riau 28126', 'Sehat kali', 'Kawasan ini menunjukkan indikator kesehatan ekosistem yang baik dan minim ancaman.', 'peta.png', '2025-09-25 15:07:22', '2025-09-25 16:35:04');

-- --------------------------------------------------------

--
-- Table structure for table `laporankonservasi`
--

DROP TABLE IF EXISTS `laporankonservasi`;
CREATE TABLE IF NOT EXISTS `laporankonservasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pengirim` bigint UNSIGNED DEFAULT NULL,
  `judulLaporan` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `jenisKegiatan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggalMulai` date DEFAULT NULL,
  `tanggalSelesai` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `daerahLokasi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kabupaten` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `lokasiKegiatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latitude` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `longitude` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `suratTugas` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `luasArea` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `fotoSebelum` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `fotoSetelah` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0' COMMENT '0 = pending, 1 = disetujui, 2 = ditolak',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengirim` (`pengirim`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporankonservasi`
--

INSERT INTO `laporankonservasi` (`id`, `pengirim`, `judulLaporan`, `jenisKegiatan`, `tanggalMulai`, `tanggalSelesai`, `keterangan`, `daerahLokasi`, `kabupaten`, `kecamatan`, `lokasiKegiatan`, `latitude`, `longitude`, `suratTugas`, `luasArea`, `fotoSebelum`, `fotoSetelah`, `status`, `created_at`, `updated_at`) VALUES
(3, 1, 'Ngetes aja', 'Ngetes', '2025-09-24', '2025-09-27', 'tes tes aja', 'Palembang', 'Lampung', 'babalan', NULL, '3.6241408', '98.7037696', '1758815964_surat_BaExI.jpg', '20', '1758815964_sebelum_ETx42.jpg', '1758815964_setelah_z4sum.jpg', 1, '2025-09-25 22:59:24', '2025-09-25 23:00:09');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peraturan`
--

DROP TABLE IF EXISTS `peraturan`;
CREATE TABLE IF NOT EXISTS `peraturan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun` int NOT NULL,
  `nomor` int NOT NULL,
  `file` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peraturan`
--

INSERT INTO `peraturan` (`id`, `nama`, `deskripsi`, `tahun`, `nomor`, `file`, `created_at`, `updated_at`) VALUES
(1, 'Peraturan Pemerintah No. 7 Tahun 1999', 'Tentang Pengawetan Jenis Tumbuhan dan Satwa', 1999, 7, 'peraturan1.pdf', '2025-09-24 18:13:23', '2025-09-24 18:13:23');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin_lapangan', 'admin_lapangan', 'admin_lapangan@gmail.com', NULL, '$2y$12$3Lpgu5n3Tm.8AUdzgLGDkuk39.hCifmdvei7K0k6bSH91iSFb4Uey', 'admin_lapangan', NULL, '2025-06-19 12:37:34', '2025-08-27 18:45:36'),
(2, 'Super admin_lapangan', 'admin_pusat', 'admin_pusat@gmail.com', NULL, '$2y$12$3Lpgu5n3Tm.8AUdzgLGDkuk39.hCifmdvei7K0k6bSH91iSFb4Uey', 'admin_pusat', NULL, '2025-06-19 12:37:34', '2025-08-22 13:42:44');

-- --------------------------------------------------------

--
-- Table structure for table `website`
--

DROP TABLE IF EXISTS `website`;
CREATE TABLE IF NOT EXISTS `website` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keyword` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telepon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `facebook` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `instagram` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `wa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gmaps` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jambuka` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `visi` text COLLATE utf8mb4_general_ci NOT NULL,
  `misi` text COLLATE utf8mb4_general_ci NOT NULL,
  `struktur` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `website`
--

INSERT INTO `website` (`id`, `nama`, `deskripsi`, `keyword`, `alamat`, `telepon`, `email`, `facebook`, `instagram`, `wa`, `gmaps`, `jambuka`, `visi`, `misi`, `struktur`, `icon`, `logo`) VALUES
(1, 'SIKOMA', 'Sistem Informasi Konservasi', 'sikoma', 'Ruko Duta Garden Square, Kec. Benda, Tangerang', '0821-2864-4561', 'garudateknik@gmail.com', 'https://facebook.com', 'https://instagram.com', '6289613325456', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666466973582!2d106.82457787409564!3d-6.175387060511402!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764b12d%3A0x3d2ad6e1e0e9bcc8!2sMonumen%20Nasional!5e0!3m2!1sid!2sid!4v1755852124328!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '<p><strong>Monday - Friday:</strong></p><p>09.00 AM - 09.00 PM</p><p><strong>Saturday - Sunday:</strong></p><p>09.00 AM - 12.00 PM</p>', '<p>&ldquo;Menjadi pengelola Kawasan Hutan Rawa Gambut yang Unggul, produktif, ekonomis, lestari, rendah emisi serta berbasis kemitraan masyarakat&rdquo;.</p>', '<p>a. Tata kelola Hutan Rawa Gambut yang profesional<br />b. Pelestarian ekosistem hutan dan keanekaragaman hayati<br />c. Pemantapan, pengamanan, dan perlindungan kawasan hutan<br />d. Melaksanakan Best Management Practice yang lebih ekonomis dan rendah emisi bagi seluruh pemegang izin pemanfaatan hutan<br />e. Fasilitasi partisipasi masyarakat dalam pengelolaan hutan berbasis kearifan lokal<br />f. Optimalisasi pemanfaatan potensi sumberdaya hutan secara multipihak dan berbasiskan kemandirian</p>', 'struktur.png', 'icon.png', 'logo.png');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporankonservasi`
--
ALTER TABLE `laporankonservasi`
  ADD CONSTRAINT `laporankonservasi_ibfk_1` FOREIGN KEY (`pengirim`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
