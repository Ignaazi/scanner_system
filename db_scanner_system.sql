-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 12 Jan 2026 pada 18.24
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_scanner_system`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `bom_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `action_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `bom_id`, `username`, `action_text`, `created_at`) VALUES
(1, 6, '24096065', 'Melakukan sinkronisasi (Update Master) sebanyak 76 data item.', '2026-01-12 14:57:18'),
(2, 9, '24096065', 'Isi tabel dikosongkan (semua item dihapus dari daftar mounter).', '2026-01-12 15:00:20'),
(3, 9, '24096065', 'Data Master diperbarui: Mengunci 0 item sebagai data referensi utama.', '2026-01-12 15:00:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bom_check`
--

CREATE TABLE `bom_check` (
  `id` int(11) NOT NULL,
  `bom_id` int(11) DEFAULT NULL,
  `sap_code` varchar(50) DEFAULT NULL,
  `scan_by` varchar(50) DEFAULT NULL,
  `status_proses` enum('pending','done') DEFAULT 'pending',
  `scan_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bom_check`
--

INSERT INTO `bom_check` (`id`, `bom_id`, `sap_code`, `scan_by`, `status_proses`, `scan_at`) VALUES
(1, 1, '1123-45678', '24096065', 'done', '2026-01-06 19:22:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bom_items`
--

CREATE TABLE `bom_items` (
  `id` int(11) NOT NULL,
  `bom_id` int(11) DEFAULT NULL,
  `sap_code` varchar(50) DEFAULT NULL,
  `feeder_name` varchar(100) DEFAULT NULL,
  `feeder_size` varchar(20) DEFAULT NULL,
  `feeder_type` varchar(20) DEFAULT NULL,
  `pitch` int(11) DEFAULT NULL,
  `status_verifikasi` enum('pending','verified') DEFAULT 'pending',
  `module` varchar(50) DEFAULT NULL,
  `avl_name` varchar(100) DEFAULT NULL,
  `avl` varchar(100) DEFAULT NULL,
  `feeder_id` varchar(50) DEFAULT NULL,
  `part_shape` varchar(100) DEFAULT NULL,
  `package_name` varchar(100) DEFAULT NULL,
  `ref_list` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bom_items`
--

INSERT INTO `bom_items` (`id`, `bom_id`, `sap_code`, `feeder_name`, `feeder_size`, `feeder_type`, `pitch`, `status_verifikasi`, `module`, `avl_name`, `avl`, `feeder_id`, `part_shape`, `package_name`, `ref_list`) VALUES
(6, 4, '1347-01320', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 4, '1347-01843', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 4, '1347-01899', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 4, '1347-02121', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 4, '1347-02123', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 4, '1347-02114', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 4, '1347-00996', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 4, '1347-02125', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 4, '1347-02124', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 4, '1347-01909', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 4, '1347-00132', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 4, '1347-01907', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 4, '1347-02127', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 4, '1347-01390', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 4, '1347-02129', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 4, '1347-00943', NULL, '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 4, '1347-00157', NULL, '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 5, '1153-28190', NULL, '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 5, '1153-10571', NULL, '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 5, '1153-15698', NULL, '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 5, '1153-15622', NULL, '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 5, '1153-15621', NULL, '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 5, '1153-08749', NULL, '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 5, '1153-27871', NULL, '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 5, '1153-27922', NULL, '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 5, '1153-28131', NULL, '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 5, '1153-14501', NULL, '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 5, '1153-09757', NULL, '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 5, '1153-15162', NULL, '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 5, '1153-27854', NULL, '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1009, 8, '1610-00119', 'KT-0800F-180', 'Emboss', 'E0804', 8, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1010, 8, '1610-00122', 'KT-1600F-380', 'Emboss', 'E1612', 16, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1017, 6, '1610-00105', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1018, 6, '1610-00007', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1019, 6, '1610-00036', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1020, 6, '1610-00082', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1021, 6, '1610-00083', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1022, 6, '1610-00086', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1023, 6, '1610-00078', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1024, 6, '1610-00154', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1025, 6, '1610-00015', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1026, 6, '1610-00077', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1027, 6, '1610-00150', 'KT-0800F-180', '8mm', 'Emboss', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1028, 6, '1610-00080', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1029, 6, '1610-00074', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1030, 6, '1610-00166', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1031, 6, '1610-00076', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1032, 6, '1610-00018', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1033, 6, '1610-00132', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1034, 6, '1610-00087', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1035, 6, '1610-00091', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1036, 6, '1610-00092', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1037, 6, '1610-00098', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1038, 6, '1610-00099', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1039, 6, '1610-00173', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1040, 6, '1610-00161', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1041, 6, '1610-00048', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1042, 6, '1610-00136', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1043, 6, '1610-00134', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1044, 6, '1610-00071', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1045, 6, '1610-00107', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1046, 6, '1610-00101', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1047, 6, '1610-00143', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1048, 6, '1610-00037', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1049, 6, '1610-00024', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1050, 6, '1610-00045', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1051, 6, '1610-00006', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1052, 6, '1610-00075', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1053, 6, '1610-00005', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1054, 6, '1610-00142', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1055, 6, '1610-00140', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1056, 6, '1610-00088', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1057, 6, '1610-00112', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1058, 6, '1610-00081', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1059, 6, '1610-00145', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1060, 6, '1610-00106', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1061, 6, '1610-00108', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1062, 6, '1610-00131', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1063, 6, '1610-00135', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1064, 6, '1610-00039', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1065, 6, '1610-00029', 'KT-0800F-180', '8mm', 'Paper', 2, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1066, 6, '1610-00094', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1067, 6, '1610-00172', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1068, 6, '1610-00113', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1069, 6, '1610-00114', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1070, 6, '1610-00116', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1071, 6, '1610-00138', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1072, 6, '1610-00139', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1073, 6, '1610-00169', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1074, 6, '1610-00167', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1075, 6, '1610-00160', 'KT-1200F-180', '12mm', 'Emboss', 8, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1076, 6, '1610-00158', 'KT-1200F-180', '12mm', 'Emboss', 8, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1077, 6, '1610-00157', 'KT-1200F-180', '12mm', 'Emboss', 8, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1078, 6, '1610-00152', 'KT-1200F-180', '12mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1079, 6, '1610-00146', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1080, 6, '1610-00151', 'KT-1200F-180', '12mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1081, 6, '1610-00110', 'KT-1200F-180', '12mm', 'Emboss', 8, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1082, 6, '1610-00104', 'KT-1600F-380', '16mm', 'Emboss', 12, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1083, 6, '1610-00165', 'KT-0800F-180', '8mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1084, 6, '1610-00073', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1085, 6, '1610-00171', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1086, 6, '1610-00095', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1087, 6, '1610-00100', 'KT-0800F-180', '8mm', 'Paper', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1088, 6, '1610-00111', 'KT-1200F-180', '12mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1089, 6, '1610-00115', 'KT-1200F-180', '12mm', 'Emboss', 4, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1090, 6, '1610-00164', 'KT-1200F-180', '12mm', 'Emboss', 8, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1091, 6, '1610-00155', 'KT-1200F-180', '12mm', 'Emboss', 8, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1092, 6, '1610-00109', 'KT-1200F-180', '12mm', 'Emboss', 8, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `bom_list`
--

CREATE TABLE `bom_list` (
  `id` int(11) NOT NULL,
  `nama_line` varchar(50) DEFAULT NULL,
  `customer` varchar(100) DEFAULT NULL,
  `model_name` varchar(100) DEFAULT NULL,
  `tipe_mesin` enum('FUJI','CM','NPM') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bom_list`
--

INSERT INTO `bom_list` (`id`, `nama_line`, `customer`, `model_name`, `tipe_mesin`) VALUES
(2, 'LINE 1', 'KAWAI', 'KEP098', NULL),
(4, 'LINE 10', 'TOKAIRIKA', '75P829', NULL),
(5, 'LINE 16', 'KOITO', '5P45 LDM', NULL),
(6, 'LINE 16', 'GREENWAY', 'ZZ098MAINBOARD1ST', NULL),
(8, 'LINE 16', 'GREENWAY', 'ZZ098LIGHT 1ST', 'FUJI'),
(9, 'LINE 16', 'GREENWAY', 'ZZ098LIGHT 2ND', 'FUJI');

-- --------------------------------------------------------

--
-- Struktur dari tabel `component_registration`
--

CREATE TABLE `component_registration` (
  `id_reg` int(11) NOT NULL,
  `id_model` int(11) DEFAULT NULL,
  `no_machine` int(11) DEFAULT NULL,
  `feeder_slot` varchar(50) DEFAULT NULL,
  `part_number` varchar(100) DEFAULT NULL,
  `size_feeder` int(11) DEFAULT NULL,
  `material_type` int(11) DEFAULT NULL,
  `pitch` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id_customer` int(11) NOT NULL,
  `nama_customer` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id_customer`, `nama_customer`) VALUES
(3, 'LG'),
(2, 'SAMSUNG'),
(1, 'SIIX');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lines`
--

CREATE TABLE `lines` (
  `id_line` int(11) NOT NULL,
  `nama_line` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lines`
--

INSERT INTO `lines` (`id_line`, `nama_line`) VALUES
(1, 'Line 01'),
(2, 'Line 02'),
(3, 'Line 03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_line`
--

CREATE TABLE `master_line` (
  `id` int(11) NOT NULL,
  `nama_line` varchar(100) DEFAULT NULL,
  `customer` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `master_line`
--

INSERT INTO `master_line` (`id`, `nama_line`, `customer`, `model`) VALUES
(2, 'LINE 1', 'GREENWAY', 'ZZ098 MIAN BOARD'),
(3, 'LINE 16', 'GREENWAY', 'ZZ098LIGHT1ST');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_model`
--

CREATE TABLE `master_model` (
  `id` int(11) NOT NULL,
  `bom_id` int(11) DEFAULT NULL,
  `sap_code` varchar(100) DEFAULT NULL,
  `feeder_name` varchar(100) DEFAULT NULL,
  `feeder_type` varchar(20) DEFAULT NULL,
  `feeder_size` varchar(50) DEFAULT NULL,
  `pitch` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `master_model`
--

INSERT INTO `master_model` (`id`, `bom_id`, `sap_code`, `feeder_name`, `feeder_type`, `feeder_size`, `pitch`, `created_at`) VALUES
(384, 6, '1610-00105', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(385, 6, '1610-00007', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(386, 6, '1610-00036', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(387, 6, '1610-00082', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(388, 6, '1610-00083', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(389, 6, '1610-00086', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(390, 6, '1610-00078', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(391, 6, '1610-00154', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(392, 6, '1610-00015', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(393, 6, '1610-00077', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(394, 6, '1610-00150', 'KT-0800F-180', 'Emboss', '8mm', '2', '2026-01-12 14:57:18'),
(395, 6, '1610-00080', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(396, 6, '1610-00074', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(397, 6, '1610-00166', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(398, 6, '1610-00076', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(399, 6, '1610-00018', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(400, 6, '1610-00132', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(401, 6, '1610-00087', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(402, 6, '1610-00091', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(403, 6, '1610-00092', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(404, 6, '1610-00098', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(405, 6, '1610-00099', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(406, 6, '1610-00173', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(407, 6, '1610-00161', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(408, 6, '1610-00048', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(409, 6, '1610-00136', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(410, 6, '1610-00134', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(411, 6, '1610-00071', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(412, 6, '1610-00107', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(413, 6, '1610-00101', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(414, 6, '1610-00143', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(415, 6, '1610-00037', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(416, 6, '1610-00024', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(417, 6, '1610-00045', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(418, 6, '1610-00006', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(419, 6, '1610-00075', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(420, 6, '1610-00005', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(421, 6, '1610-00142', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(422, 6, '1610-00140', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(423, 6, '1610-00088', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(424, 6, '1610-00112', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(425, 6, '1610-00081', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(426, 6, '1610-00145', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(427, 6, '1610-00106', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(428, 6, '1610-00108', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(429, 6, '1610-00131', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(430, 6, '1610-00135', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(431, 6, '1610-00039', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(432, 6, '1610-00029', 'KT-0800F-180', 'Paper', '8mm', '2', '2026-01-12 14:57:18'),
(433, 6, '1610-00094', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(434, 6, '1610-00172', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(435, 6, '1610-00113', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(436, 6, '1610-00114', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(437, 6, '1610-00116', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(438, 6, '1610-00138', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(439, 6, '1610-00139', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(440, 6, '1610-00169', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(441, 6, '1610-00167', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(442, 6, '1610-00160', 'KT-1200F-180', 'Emboss', '12mm', '8', '2026-01-12 14:57:18'),
(443, 6, '1610-00158', 'KT-1200F-180', 'Emboss', '12mm', '8', '2026-01-12 14:57:18'),
(444, 6, '1610-00157', 'KT-1200F-180', 'Emboss', '12mm', '8', '2026-01-12 14:57:18'),
(445, 6, '1610-00152', 'KT-1200F-180', 'Emboss', '12mm', '4', '2026-01-12 14:57:18'),
(446, 6, '1610-00146', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(447, 6, '1610-00151', 'KT-1200F-180', 'Emboss', '12mm', '4', '2026-01-12 14:57:18'),
(448, 6, '1610-00110', 'KT-1200F-180', 'Emboss', '12mm', '8', '2026-01-12 14:57:18'),
(449, 6, '1610-00104', 'KT-1600F-380', 'Emboss', '16mm', '12', '2026-01-12 14:57:18'),
(450, 6, '1610-00165', 'KT-0800F-180', 'Emboss', '8mm', '4', '2026-01-12 14:57:18'),
(451, 6, '1610-00073', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(452, 6, '1610-00171', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(453, 6, '1610-00095', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(454, 6, '1610-00100', 'KT-0800F-180', 'Paper', '8mm', '4', '2026-01-12 14:57:18'),
(455, 6, '1610-00111', 'KT-1200F-180', 'Emboss', '12mm', '4', '2026-01-12 14:57:18'),
(456, 6, '1610-00115', 'KT-1200F-180', 'Emboss', '12mm', '4', '2026-01-12 14:57:18'),
(457, 6, '1610-00164', 'KT-1200F-180', 'Emboss', '12mm', '8', '2026-01-12 14:57:18'),
(458, 6, '1610-00155', 'KT-1200F-180', 'Emboss', '12mm', '8', '2026-01-12 14:57:18'),
(459, 6, '1610-00109', 'KT-1200F-180', 'Emboss', '12mm', '8', '2026-01-12 14:57:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `models`
--

CREATE TABLE `models` (
  `id_model` int(11) NOT NULL,
  `id_line` int(11) DEFAULT NULL,
  `id_customer` int(11) DEFAULT NULL,
  `nama_model` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `scanner_history`
--

CREATE TABLE `scanner_history` (
  `id` int(11) NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `nama_karyawan` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `model_name` varchar(100) DEFAULT NULL,
  `sap_code` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'COMPLETED',
  `cll` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `pitch` varchar(50) DEFAULT NULL,
  `cycle` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `scanner_history`
--

INSERT INTO `scanner_history` (`id`, `tanggal`, `nama_karyawan`, `username`, `role`, `model_name`, `sap_code`, `status`, `cll`, `size`, `type`, `pitch`, `cycle`) VALUES
(1, '2026-01-05 16:17:44', 'Muhammad ignazi', '24096065', 'admin', 'ZZ098 MAIN', '1147-00236', 'COMPLETED', '1147-00236', '8', 'PAPER', '2', 1),
(2, '2026-01-06 11:58:05', 'Muhammad ignazi', '24096065', 'admin', 'KEP098', '1123-45678', 'COMPLETED', '1123-45678', '8', 'PAPER', '2', 1),
(3, '2026-01-06 11:58:29', 'Muhammad ignazi', '24096065', 'admin', 'KEP098', '1123-45678', 'COMPLETED', '1123-45678', '8', 'EMBOSS', '2', 2),
(4, '2026-01-06 12:00:58', 'Muhammad ignazi', '24096065', 'admin', 'ZZ098 MAIN', '1123-45678', 'COMPLETED', '1123-45678', '8', 'PAPER', '2', 1),
(5, '2026-01-07 02:37:28', 'Muhammad ignazi', '24096065', NULL, 'ZZ098 MAIN', '1123-45678', 'COMPLETED', '1123-45678', '8', 'PAPER', '2', 1),
(6, '2026-01-07 02:37:48', 'Muhammad ignazi', '24096065', NULL, 'ZZ098 MAIN', '1123-45678', 'COMPLETED', '1123-45678', '8', 'PAPER', '2', 2),
(7, '2026-01-07 02:38:04', 'Muhammad ignazi', '24096065', NULL, 'ZZ098 MAIN', '1123-45678', 'COMPLETED', '1123-45678', '8', 'PAPER', '2', 3),
(8, '2026-01-07 02:49:34', 'Muhammad ignazi', '24096065', NULL, '5P45 LDM', '1123-00000', 'COMPLETED', '1123-00000', '8', 'PAPER', '2', 1),
(9, '2026-01-07 02:49:52', 'Muhammad ignazi', '24096065', NULL, '5P45 LDM', '1123-00000', 'COMPLETED', '1123-00000', '8', 'PAPER', '2', 2),
(10, '2026-01-07 02:50:07', 'Muhammad ignazi', '24096065', NULL, '5P45 LDM', '1123-00000', 'COMPLETED', '1123-00000', '8', 'PAPER', '2', 3),
(12, '2026-01-07 14:55:10', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00150', 'COMPLETED', '1610-00150', '8', 'EMBOSS', '2', 1),
(14, '2026-01-07 14:57:35', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00150', 'COMPLETED', '1610-00150', '8', 'EMBOSS', '2', 1),
(15, '2026-01-07 14:57:55', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00150', 'COMPLETED', '1610-00150', '8', 'EMBOSS', '2', 2),
(16, '2026-01-07 14:58:13', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00150', 'COMPLETED', '1610-00150', '8', 'EMBOSS', '2', 3),
(17, '2026-01-07 15:07:55', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00150', 'COMPLETED', '1610-00150', '8', 'EMBOSS', '2', 1),
(18, '2026-01-07 15:08:19', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00150', 'COMPLETED', '1610-00150', '8', 'EMBOSS', '2', 2),
(19, '2026-01-08 09:50:05', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00105', 'COMPLETED', '1610-00105', '8', 'EMBOSS', '2', 1),
(20, '2026-01-09 09:05:39', 'Muhammad ignazi', '24096065', NULL, 'ZZ098LIGHT 1ST', '1610-00119', 'COMPLETED', '1610-00119', '8', 'EMBOSS', '4', 1),
(21, '2026-01-09 09:07:46', 'Muhammad ignazi', '24096065', NULL, 'ZZ098LIGHT 1ST', '1610-00122', 'COMPLETED', '1610-00122', '16', 'EMBOSS', '12', 1),
(22, '2026-01-09 09:08:48', 'Muhammad ignazi', '24096065', NULL, 'ZZ098LIGHT 1ST', '1610-00119', 'COMPLETED', '1610-00119', '8', 'EMBOSS', '4', 2),
(23, '2026-01-09 09:09:32', 'Muhammad ignazi', '24096065', NULL, 'ZZ098LIGHT 1ST', '1610-00122', 'COMPLETED', '1610-00122', '16', 'EMBOSS', '12', 2),
(24, '2026-01-09 09:10:23', 'Muhammad ignazi', '24096065', NULL, 'ZZ098LIGHT 1ST', '1610-00119', 'COMPLETED', '1610-00119', '8', 'EMBOSS', '4', 3),
(25, '2026-01-09 09:11:35', 'Muhammad ignazi', '24096065', NULL, 'ZZ098LIGHT 1ST', '1610-00122', 'COMPLETED', '1610-00122', '16', 'EMBOSS', '12', 3),
(26, '2026-01-09 13:40:33', 'Bang Fajar', '21095228', NULL, 'ZZ098LIGHT 2ND', '1610-00171', 'COMPLETED', '1610-00171', '8', 'PAPER', '4', 1),
(27, '2026-01-09 13:41:05', 'Bang Fajar', '21095228', NULL, 'ZZ098LIGHT 2ND', '1610-00121', 'COMPLETED', '1610-00121', '8', 'PAPER', '2', 1),
(28, '2026-01-09 13:41:47', 'Bang Fajar', '21095228', NULL, 'ZZ098LIGHT 2ND', '1610-00120', 'COMPLETED', '1610-00120', '24', 'EMBOSS', '12', 1),
(29, '2026-01-09 13:43:08', 'Bang Fajar', '21095228', NULL, 'ZZ098LIGHT 2ND', '1610-00171', 'COMPLETED', '1610-00171', '8', 'PAPER', '4', 2),
(30, '2026-01-09 13:43:38', 'Bang Fajar', '21095228', NULL, 'ZZ098LIGHT 2ND', '1610-00121', 'COMPLETED', '1610-00121', '8', 'PAPER', '4', 2),
(31, '2026-01-09 13:45:54', 'Bang Fajar', '21095228', NULL, 'ZZ098LIGHT 2ND', '1610-00120', 'COMPLETED', '1610-00120', '24', 'EMBOSS', '12', 2),
(32, '2026-01-09 13:49:06', 'Bang Fajar', '21095228', NULL, 'ZZ098LIGHT 2ND', '1610-00171', 'COMPLETED', '1610-00171', '8', 'PAPER', '4', 3),
(33, '2026-01-09 13:49:55', 'Bang Fajar', '21095228', NULL, 'ZZ098LIGHT 2ND', '1610-00121', 'COMPLETED', '1610-00121', '8', 'PAPER', '4', 3),
(34, '2026-01-09 13:50:31', 'Bang Fajar', '21095228', NULL, 'ZZ098LIGHT 2ND', '1610-00120', 'COMPLETED', '1610-00120', '24', 'EMBOSS', '12', 3),
(35, '2026-01-12 08:54:28', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00007', 'COMPLETED', '1610-00007', '8', 'PAPER', '2', 1),
(36, '2026-01-12 08:55:04', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00036', 'COMPLETED', '1610-00036', '8', 'PAPER', '2', 1),
(37, '2026-01-12 08:55:29', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00082', 'COMPLETED', '1610-00082', '8', 'PAPER', '2', 1),
(38, '2026-01-12 08:55:53', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00083', 'COMPLETED', '1610-00083', '8', 'PAPER', '2', 1),
(39, '2026-01-12 08:56:17', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00086', 'COMPLETED', '1610-00086', '8', 'PAPER', '2', 1),
(40, '2026-01-12 08:56:44', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00078', 'COMPLETED', '1610-00078', '8', 'PAPER', '4', 1),
(41, '2026-01-12 08:57:16', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00015', 'COMPLETED', '1610-00015', '8', 'PAPER', '4', 1),
(42, '2026-01-12 08:57:40', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00077', 'COMPLETED', '1610-00077', '8', 'PAPER', '2', 1),
(43, '2026-01-12 08:58:30', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00150', 'COMPLETED', '1610-00150', '8', 'EMBOSS', '2', 1),
(44, '2026-01-12 08:59:07', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00080', 'COMPLETED', '1610-00080', '8', 'PAPER', '2', 1),
(45, '2026-01-12 08:59:57', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00166', 'COMPLETED', '1610-00166', '8', 'EMBOSS', '4', 1),
(46, '2026-01-12 09:00:54', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00076', 'COMPLETED', '1610-00076', '8', 'PAPER', '4', 1),
(47, '2026-01-12 09:01:17', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00018', 'COMPLETED', '1610-00018', '8', 'PAPER', '2', 1),
(48, '2026-01-12 09:01:57', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00132', 'COMPLETED', '1610-00132', '8', 'EMBOSS', '4', 1),
(49, '2026-01-12 09:02:23', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00087', 'COMPLETED', '1610-00087', '8', 'PAPER', '2', 1),
(50, '2026-01-12 09:03:24', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00091', 'COMPLETED', '1610-00091', '8', 'PAPER', '2', 1),
(51, '2026-01-12 09:03:46', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00092', 'COMPLETED', '1610-00092', '8', 'PAPER', '4', 1),
(52, '2026-01-12 09:04:18', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00098', 'COMPLETED', '1610-00098', '8', 'PAPER', '4', 1),
(53, '2026-01-12 09:04:35', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00099', 'COMPLETED', '1610-00099', '8', 'PAPER', '2', 1),
(54, '2026-01-12 09:05:26', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00173', 'COMPLETED', '1610-00173', '8', 'PAPER', '2', 1),
(55, '2026-01-12 09:05:49', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00161', 'COMPLETED', '1610-00161', '8', 'EMBOSS', '4', 1),
(56, '2026-01-12 09:06:09', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00048', 'COMPLETED', '1610-00048', '8', 'EMBOSS', '4', 1),
(57, '2026-01-12 09:06:33', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00136', 'COMPLETED', '1610-00136', '8', 'PAPER', '2', 1),
(58, '2026-01-12 09:07:01', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00134', 'COMPLETED', '1610-00134', '8', 'PAPER', '2', 1),
(59, '2026-01-12 09:07:23', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00071', 'COMPLETED', '1610-00071', '8', 'PAPER', '2', 1),
(60, '2026-01-12 09:07:43', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00107', 'COMPLETED', '1610-00107', '8', 'EMBOSS', '4', 1),
(61, '2026-01-12 09:14:23', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00101', 'COMPLETED', '1610-00101', '8', 'PAPER', '4', 1),
(62, '2026-01-12 09:14:50', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00143', 'COMPLETED', '1610-00143', '8', 'EMBOSS', '4', 1),
(63, '2026-01-12 09:16:37', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00037', 'COMPLETED', '1610-00037', '8', 'PAPER', '2', 1),
(64, '2026-01-12 09:16:53', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00024', 'COMPLETED', '1610-00024', '8', 'PAPER', '2', 1),
(65, '2026-01-12 09:17:13', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00045', 'COMPLETED', '1610-00045', '8', 'EMBOSS', '4', 1),
(66, '2026-01-12 09:17:33', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00006', 'COMPLETED', '1610-00006', '8', 'PAPER', '2', 1),
(67, '2026-01-12 09:19:15', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00075', 'COMPLETED', '1610-00075', '8', 'PAPER', '4', 1),
(68, '2026-01-12 09:25:35', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00005', 'COMPLETED', '1610-00005', '8', 'PAPER', '4', 1),
(69, '2026-01-12 09:25:57', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00142', 'COMPLETED', '1610-00142', '8', 'PAPER', '2', 1),
(70, '2026-01-12 09:26:26', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00140', 'COMPLETED', '1610-00140', '8', 'PAPER', '4', 1),
(71, '2026-01-12 09:26:53', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00088', 'COMPLETED', '1610-00088', '8', 'PAPER', '2', 1),
(72, '2026-01-12 09:27:22', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00112', 'COMPLETED', '1610-00112', '8', 'EMBOSS', '4', 1),
(73, '2026-01-12 09:27:49', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00081', 'COMPLETED', '1610-00081', '8', 'PAPER', '2', 1),
(74, '2026-01-12 09:28:13', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00145', 'COMPLETED', '1610-00145', '8', 'EMBOSS', '4', 1),
(75, '2026-01-12 09:28:46', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00106', 'COMPLETED', '1610-00106', '8', 'EMBOSS', '4', 1),
(76, '2026-01-12 09:29:10', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00108', 'COMPLETED', '1610-00108', '8', 'EMBOSS', '4', 1),
(77, '2026-01-12 09:29:39', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00131', 'COMPLETED', '1610-00131', '8', 'PAPER', '4', 1),
(78, '2026-01-12 09:30:02', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00135', 'COMPLETED', '1610-00135', '8', 'PAPER', '2', 1),
(79, '2026-01-12 09:30:26', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00039', 'COMPLETED', '1610-00039', '8', 'PAPER', '2', 1),
(80, '2026-01-12 09:30:48', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00029', 'COMPLETED', '1610-00029', '8', 'PAPER', '2', 1),
(81, '2026-01-12 09:31:16', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00094', 'COMPLETED', '1610-00094', '8', 'PAPER', '4', 1),
(82, '2026-01-12 09:31:39', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00172', 'COMPLETED', '1610-00172', '8', 'PAPER', '4', 1),
(83, '2026-01-12 09:32:12', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00105', 'COMPLETED', '1610-00105', '8', 'EMBOSS', '4', 1),
(84, '2026-01-12 09:32:49', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00154', 'COMPLETED', '1610-00154', '8', 'EMBOSS', '4', 1),
(85, '2026-01-12 09:34:39', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00074', 'COMPLETED', '1610-00074', '8', 'PAPER', '2', 1),
(86, '2026-01-12 09:35:54', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00113', 'COMPLETED', '1610-00113', '8', 'EMBOSS', '4', 1),
(87, '2026-01-12 09:36:42', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00114', 'COMPLETED', '1610-00114', '8', 'EMBOSS', '4', 1),
(88, '2026-01-12 09:37:08', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00116', 'COMPLETED', '1610-00116', '8', 'EMBOSS', '4', 1),
(89, '2026-01-12 09:37:38', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00138', 'COMPLETED', '1610-00138', '8', 'PAPER', '4', 1),
(90, '2026-01-12 09:38:06', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00139', 'COMPLETED', '1610-00139', '8', 'PAPER', '4', 1),
(91, '2026-01-12 09:38:35', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00169', 'COMPLETED', '1610-00169', '8', 'EMBOSS', '4', 1),
(92, '2026-01-12 09:39:15', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00167', 'COMPLETED', '1610-00167', '8', 'EMBOSS', '4', 1),
(93, '2026-01-12 09:39:57', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00160', 'COMPLETED', '1610-00160', '12', 'EMBOSS', '8', 1),
(94, '2026-01-12 09:40:43', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00158', 'COMPLETED', '1610-00158', '12', 'EMBOSS', '8', 1),
(95, '2026-01-12 09:41:41', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00157', 'COMPLETED', '1610-00157', '12', 'EMBOSS', '8', 1),
(96, '2026-01-12 09:42:08', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00152', 'COMPLETED', '1610-00152', '12', 'EMBOSS', '8', 1),
(97, '2026-01-12 09:43:48', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00146', 'COMPLETED', '1610-00146', '8', 'EMBOSS', '4', 1),
(98, '2026-01-12 09:44:28', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00151', 'COMPLETED', '1610-00151', '12', 'EMBOSS', '8', 1),
(99, '2026-01-12 09:45:29', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00151', 'COMPLETED', '1610-00151', '12', 'EMBOSS', '4', 2),
(100, '2026-01-12 09:46:00', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00110', 'COMPLETED', '1610-00110', '12', 'EMBOSS', '8', 1),
(101, '2026-01-12 10:54:05', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00007', 'COMPLETED', '1610-00007', '8', 'P', '2', 1),
(102, '2026-01-12 13:28:47', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00105', 'COMPLETED', '1610-00105', '8', 'E', '4', 1),
(103, '2026-01-12 13:29:41', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00007', 'COMPLETED', '1610-00007', '8', 'P', '2', 1),
(104, '2026-01-12 13:31:00', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00036', 'COMPLETED', '1610-00036', '8', 'P', '2', 1),
(105, '2026-01-12 13:31:42', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00082', 'COMPLETED', '1610-00082', '8', 'P', '2', 1),
(106, '2026-01-12 13:32:16', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00083', 'COMPLETED', '1610-00083', '8', 'P', '2', 1),
(107, '2026-01-12 13:32:56', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00086', 'COMPLETED', '1610-00086', '8', 'P', '2', 1),
(108, '2026-01-12 13:33:30', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00078', 'COMPLETED', '1610-00078', '8', 'P', '4', 1),
(109, '2026-01-12 13:33:56', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00154', 'COMPLETED', '1610-00154', '8', 'E', '4', 1),
(110, '2026-01-12 13:34:35', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00015', 'COMPLETED', '1610-00015', '8', 'P', '4', 1),
(111, '2026-01-12 13:35:16', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00077', 'COMPLETED', '1610-00077', '8', 'P', '2', 1),
(112, '2026-01-12 13:35:58', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00150', 'COMPLETED', '1610-00150', '8', 'E', '2', 1),
(113, '2026-01-12 13:36:22', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00080', 'COMPLETED', '1610-00080', '8', 'P', '2', 1),
(114, '2026-01-12 13:36:50', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00074', 'COMPLETED', '1610-00074', '8', 'P', '2', 1),
(115, '2026-01-12 13:37:26', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00166', 'COMPLETED', '1610-00166', '8', 'E', '4', 1),
(116, '2026-01-12 13:37:57', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00076', 'COMPLETED', '1610-00076', '8', 'P', '4', 1),
(117, '2026-01-12 13:39:14', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00018', 'COMPLETED', '1610-00018', '8', 'P', '2', 1),
(118, '2026-01-12 13:39:58', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00132', 'COMPLETED', '1610-00132', '8', 'E', '4', 1),
(119, '2026-01-12 13:43:32', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00087', 'COMPLETED', '1610-00087', '8', 'P', '2', 1),
(120, '2026-01-12 13:44:07', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00091', 'COMPLETED', '1610-00091', '8', 'P', '2', 1),
(121, '2026-01-12 13:44:28', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00092', 'COMPLETED', '1610-00092', '8', 'P', '4', 1),
(122, '2026-01-12 13:44:47', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00098', 'COMPLETED', '1610-00098', '8', 'P', '4', 1),
(123, '2026-01-12 13:45:00', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00099', 'COMPLETED', '1610-00099', '8', 'P', '2', 1),
(124, '2026-01-12 13:45:29', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00173', 'COMPLETED', '1610-00173', '8', 'P', '2', 1),
(125, '2026-01-12 13:46:43', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00161', 'COMPLETED', '1610-00161', '8', 'E', '4', 1),
(126, '2026-01-12 13:46:57', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00048', 'COMPLETED', '1610-00048', '8', 'E', '4', 1),
(127, '2026-01-12 13:47:11', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00136', 'COMPLETED', '1610-00136', '8', 'P', '2', 1),
(128, '2026-01-12 13:47:31', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00134', 'COMPLETED', '1610-00134', '8', 'P', '2', 1),
(129, '2026-01-12 13:47:44', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00071', 'COMPLETED', '1610-00071', '8', 'P', '2', 1),
(130, '2026-01-12 13:47:56', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00107', 'COMPLETED', '1610-00107', '8', 'P', '4', 1),
(131, '2026-01-12 13:48:11', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00101', 'COMPLETED', '1610-00101', '8', 'P', '4', 1),
(132, '2026-01-12 13:48:49', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00107', 'COMPLETED', '1610-00107', '8', 'E', '4', 2),
(133, '2026-01-12 13:53:46', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00143', 'COMPLETED', '1610-00143', '8', 'E', '4', 1),
(134, '2026-01-12 13:54:45', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00037', 'COMPLETED', '1610-00037', '8', 'P', '2', 1),
(135, '2026-01-12 13:55:35', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00024', 'COMPLETED', '1610-00024', '8', 'P', '2', 1),
(136, '2026-01-12 13:56:13', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00045', 'COMPLETED', '1610-00045', '8', 'E', '4', 1),
(137, '2026-01-12 13:56:39', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00006', 'COMPLETED', '1610-00006', '8', 'P', '2', 1),
(138, '2026-01-12 13:57:12', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00075', 'COMPLETED', '1610-00075', '8', 'P', '2', 1),
(139, '2026-01-12 13:57:51', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00005', 'COMPLETED', '1610-00005', '8', 'P', '4', 1),
(140, '2026-01-12 13:58:34', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00142', 'COMPLETED', '1610-00142', '8', 'P', '2', 1),
(141, '2026-01-12 13:58:57', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00140', 'COMPLETED', '1610-00140', '8', 'P', '4', 1),
(142, '2026-01-12 13:59:28', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00088', 'COMPLETED', '1610-00088', '8', 'P', '2', 1),
(143, '2026-01-12 13:59:55', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00112', 'COMPLETED', '1610-00112', '8', 'E', '4', 1),
(144, '2026-01-12 14:00:18', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00081', 'COMPLETED', '1610-00081', '8', 'P', '2', 1),
(145, '2026-01-12 14:00:40', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00145', 'COMPLETED', '1610-00145', '8', 'E', '4', 1),
(146, '2026-01-12 14:01:05', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00106', 'COMPLETED', '1610-00106', '8', 'E', '4', 1),
(147, '2026-01-12 14:01:39', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00108', 'COMPLETED', '1610-00108', '8', 'E', '4', 1),
(148, '2026-01-12 14:02:01', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00131', 'COMPLETED', '1610-00131', '8', 'P', '4', 1),
(149, '2026-01-12 14:02:18', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00135', 'COMPLETED', '1610-00135', '8', 'P', '2', 1),
(150, '2026-01-12 14:03:34', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00039', 'COMPLETED', '1610-00039', '8', 'P', '2', 1),
(151, '2026-01-12 14:04:31', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00029', 'COMPLETED', '1610-00029', '8', 'P', '2', 1),
(152, '2026-01-12 14:04:55', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00094', 'COMPLETED', '1610-00094', '8', 'P', '4', 1),
(153, '2026-01-12 14:05:13', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00172', 'COMPLETED', '1610-00172', '8', 'P', '4', 1),
(154, '2026-01-12 14:06:35', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00113', 'COMPLETED', '1610-00113', '8', 'E', '4', 1),
(155, '2026-01-12 14:06:53', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00114', 'COMPLETED', '1610-00114', '8', 'E', '4', 1),
(156, '2026-01-12 14:07:21', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00116', 'COMPLETED', '1610-00116', '8', 'E', '4', 1),
(157, '2026-01-12 14:07:46', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00138', 'COMPLETED', '1610-00138', '8', 'P', '4', 1),
(158, '2026-01-12 14:08:07', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00139', 'COMPLETED', '1610-00139', '8', 'P', '4', 1),
(159, '2026-01-12 14:08:27', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00169', 'COMPLETED', '1610-00169', '8', 'E', '4', 1),
(160, '2026-01-12 14:08:57', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00167', 'COMPLETED', '1610-00167', '8', 'E', '4', 1),
(161, '2026-01-12 14:09:46', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00160', 'COMPLETED', '1610-00160', '12', 'E', '8', 1),
(162, '2026-01-12 14:10:26', 'Muhammad ignazi', '24096065', NULL, 'ZZ098MAINBOARD1ST', '1610-00158', 'COMPLETED', '1610-00158', '12', 'E', '8', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama_karyawan` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `foto` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `nama_karyawan`, `password`, `role`, `foto`) VALUES
(1, '23116000', 'Raedho', '23116000', 'admin', 'upd_1767670034.jpeg'),
(2, '21095228', 'Bang Fajar', '1', 'admin', 'fajar.png'),
(4, '24096065', 'Muhammad ignazi', '24096065', 'admin', 'ignazi.png'),
(5, '24076062', 'Aceng Fikri', '24076062', 'admin', 'default.png'),
(6, '24016019', 'Adhitya', '24016019', 'admin', 'default.png'),
(7, '23116001', 'Arey Turner', 're', 'admin', 'default.png'),
(8, '24076058', 'Difa ', '24076058', 'admin', 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `bom_check`
--
ALTER TABLE `bom_check`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `bom_items`
--
ALTER TABLE `bom_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bom_id` (`bom_id`);

--
-- Indeks untuk tabel `bom_list`
--
ALTER TABLE `bom_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `model_name` (`model_name`);

--
-- Indeks untuk tabel `component_registration`
--
ALTER TABLE `component_registration`
  ADD PRIMARY KEY (`id_reg`),
  ADD KEY `id_model` (`id_model`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id_customer`),
  ADD UNIQUE KEY `nama_customer` (`nama_customer`);

--
-- Indeks untuk tabel `lines`
--
ALTER TABLE `lines`
  ADD PRIMARY KEY (`id_line`),
  ADD UNIQUE KEY `nama_line` (`nama_line`);

--
-- Indeks untuk tabel `master_line`
--
ALTER TABLE `master_line`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `master_model`
--
ALTER TABLE `master_model`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bom_id` (`bom_id`);

--
-- Indeks untuk tabel `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id_model`),
  ADD KEY `id_line` (`id_line`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indeks untuk tabel `scanner_history`
--
ALTER TABLE `scanner_history`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `bom_check`
--
ALTER TABLE `bom_check`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `bom_items`
--
ALTER TABLE `bom_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1093;

--
-- AUTO_INCREMENT untuk tabel `bom_list`
--
ALTER TABLE `bom_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `component_registration`
--
ALTER TABLE `component_registration`
  MODIFY `id_reg` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `lines`
--
ALTER TABLE `lines`
  MODIFY `id_line` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `master_line`
--
ALTER TABLE `master_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `master_model`
--
ALTER TABLE `master_model`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=460;

--
-- AUTO_INCREMENT untuk tabel `models`
--
ALTER TABLE `models`
  MODIFY `id_model` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `scanner_history`
--
ALTER TABLE `scanner_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bom_items`
--
ALTER TABLE `bom_items`
  ADD CONSTRAINT `bom_items_ibfk_1` FOREIGN KEY (`bom_id`) REFERENCES `bom_list` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `component_registration`
--
ALTER TABLE `component_registration`
  ADD CONSTRAINT `component_registration_ibfk_1` FOREIGN KEY (`id_model`) REFERENCES `models` (`id_model`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `master_model`
--
ALTER TABLE `master_model`
  ADD CONSTRAINT `master_model_ibfk_1` FOREIGN KEY (`bom_id`) REFERENCES `bom_list` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `models_ibfk_1` FOREIGN KEY (`id_line`) REFERENCES `lines` (`id_line`) ON DELETE CASCADE,
  ADD CONSTRAINT `models_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
