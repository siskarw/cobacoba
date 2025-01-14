-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2024 at 08:12 AM
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
-- Database: `dblaundry_latte`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_status_pembayaran` (IN `p_id_trans` CHAR(5), IN `p_status_pembayaran` VARCHAR(20))   BEGIN
    UPDATE transaksi
    SET pembayaran = p_status_pembayaran
    WHERE id_trans = p_id_trans;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `hitung_poin_customer` (`p_total_harga` DECIMAL(10,0)) RETURNS INT(11) DETERMINISTIC BEGIN
    RETURN FLOOR(p_total_harga / 1000);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `no_hp_cus` varchar(15) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `jenis_kelamin` char(1) DEFAULT NULL,
  `point_cust` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`no_hp_cus`, `nama`, `jenis_kelamin`, `point_cust`) VALUES
('089456732145', 'Siska', 'P', 20),
('089463123111', 'Vivi', 'P', 0),
('089463123456', 'Jaehyun', 'L', 0);

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_trans` char(5) NOT NULL,
  `id_menu` char(4) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `subtotal` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_trans`, `id_menu`, `kuantitas`, `subtotal`) VALUES
(1, 'T0001', 'M001', 2, 10000),
(2, 'T0002', 'M003', 1, 25000),
(3, 'T0003', 'M004', 1, 30000),
(4, 'T0004', 'M002', 5, 75000),
(5, 'T0005', 'M010', 2, 6000),
(6, 'T0006', 'M003', 2, 50000),
(7, 'T0007', 'M004', 2, 60000),
(9, 'T0001', 'M001', 2, 10000),
(18, 'T0008', 'M001', 3, 15000),
(19, 'T0008', 'M002', 2, 30000),
(23, 'T0010', 'M001', 2, 10000);

--
-- Triggers `detail_transaksi`
--
DELIMITER $$
CREATE TRIGGER `after_insert_detail_transaksi` AFTER INSERT ON `detail_transaksi` FOR EACH ROW BEGIN
    DECLARE total_poin INT;
    DECLARE total_harga_transaksi DECIMAL(10, 0);

    SELECT SUM(subtotal) INTO total_harga_transaksi
    FROM detail_transaksi
    WHERE id_trans = NEW.id_trans;

    SET total_poin = FLOOR(total_harga_transaksi / 25000) * 10;

    UPDATE customer
    SET point_cust = point_cust + total_poin
    WHERE no_hp_cus = (SELECT no_hp_cus FROM transaksi WHERE id_trans = NEW.id_trans);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_detail_transaksi` BEFORE INSERT ON `detail_transaksi` FOR EACH ROW BEGIN
    SET NEW.subtotal = NEW.kuantitas * (SELECT harga FROM menu WHERE id_menu = NEW.id_menu);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` char(3) NOT NULL,
  `nama_karyawan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nama_karyawan`) VALUES
('K01', 'Aida'),
('K02', 'Bella'),
('K03', 'Radit');

-- --------------------------------------------------------

--
-- Stand-in structure for view `laporan_transaksi`
-- (See below for the actual view)
--
CREATE TABLE `laporan_transaksi` (
`id_trans` char(5)
,`nama_pelanggan` varchar(50)
,`nama_karyawan` varchar(50)
,`trans_masuk` date
,`trans_ambil` date
,`pembayaran` varchar(20)
,`total_harga` decimal(10,0)
,`detail_items` mediumtext
);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` char(4) NOT NULL,
  `nama_menu` varchar(50) DEFAULT NULL,
  `jenis_menu` varchar(15) DEFAULT NULL,
  `waktu` varchar(15) DEFAULT NULL,
  `harga` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `jenis_menu`, `waktu`, `harga`) VALUES
('M001', 'Regular', 'Kiloan', '3 hari', 5000),
('M002', 'Ekspress (regular)', 'Kiloan', '1 hari', 15000),
('M003', 'Ekspress (premium)', 'Kiloan', '3 jam', 25000),
('M004', 'Sepatu', 'Satuan', '2 hari', 30000),
('M005', 'Tas', 'Satuan', '2 hari', 30000),
('M006', 'Bed Cover', 'Satuan', '2 hari', 20000),
('M007', 'Kebaya', 'Satuan', '2 hari', 15000),
('M008', 'Jas', 'Satuan', '2 hari', 15000),
('M009', 'Boneka', 'Kiloan', '3 hari', 20000),
('M010', 'Setrika (regular)', 'Kiloan', '3 hari', 3000),
('M011', 'Setrika (kilat)', 'Kiloan', '1 hari', 9000);

-- --------------------------------------------------------

--
-- Stand-in structure for view `pendapatan_harian`
-- (See below for the actual view)
--
CREATE TABLE `pendapatan_harian` (
`tanggal` date
,`total_lunas` decimal(32,0)
,`total_belum_lunas` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_trans` char(5) NOT NULL,
  `no_hp_cus` varchar(15) NOT NULL,
  `id_karyawan` char(3) NOT NULL,
  `trans_masuk` date NOT NULL,
  `trans_ambil` date NOT NULL,
  `pembayaran` varchar(20) NOT NULL,
  `total_harga` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_trans`, `no_hp_cus`, `id_karyawan`, `trans_masuk`, `trans_ambil`, `pembayaran`, `total_harga`) VALUES
('T0001', '089456732145', 'K01', '2024-10-01', '2024-10-04', 'Lunas', 20000),
('T0002', '089463123111', 'K02', '2024-10-08', '2024-10-08', 'Lunas', 25000),
('T0003', '089463123456', 'K03', '2024-06-06', '2024-06-08', 'Lunas', 30000),
('T0004', '089463123111', 'K02', '2024-12-15', '2024-12-18', 'Lunas', 75000),
('T0005', '089463123111', 'K02', '2024-12-20', '2024-12-22', 'Lunas', 6000),
('T0006', '089456732145', 'K01', '2024-12-20', '2024-12-22', 'Belum Lunas', 50000),
('T0007', '089463123456', 'K01', '2024-12-10', '2024-12-12', 'Belum Lunas', 60000),
('T0008', '089463123111', 'K01', '2024-12-23', '2024-12-25', 'Lunas', 45000),
('T0010', '089456732145', 'K01', '2024-12-25', '2024-12-30', 'Belum Lunas', 20000);

--
-- Triggers `transaksi`
--
DELIMITER $$
CREATE TRIGGER `after_insert_transaksi` AFTER INSERT ON `transaksi` FOR EACH ROW BEGIN
    DECLARE total_poin INT;

    SET total_poin = FLOOR(NEW.total_harga / 25000) * 10;

    UPDATE customer
    SET point_cust = point_cust + total_poin
    WHERE no_hp_cus = NEW.no_hp_cus;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `laporan_transaksi`
--
DROP TABLE IF EXISTS `laporan_transaksi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `laporan_transaksi`  AS SELECT `t`.`id_trans` AS `id_trans`, `c`.`nama` AS `nama_pelanggan`, `k`.`nama_karyawan` AS `nama_karyawan`, `t`.`trans_masuk` AS `trans_masuk`, `t`.`trans_ambil` AS `trans_ambil`, `t`.`pembayaran` AS `pembayaran`, `t`.`total_harga` AS `total_harga`, group_concat(concat(`dt`.`id_menu`,' (',`dt`.`kuantitas`,'x',`m`.`harga`,')') separator '; ') AS `detail_items` FROM ((((`transaksi` `t` join `customer` `c` on(`t`.`no_hp_cus` = `c`.`no_hp_cus`)) join `karyawan` `k` on(`t`.`id_karyawan` = `k`.`id_karyawan`)) left join `detail_transaksi` `dt` on(`t`.`id_trans` = `dt`.`id_trans`)) left join `menu` `m` on(`dt`.`id_menu` = `m`.`id_menu`)) GROUP BY `t`.`id_trans` ;

-- --------------------------------------------------------

--
-- Structure for view `pendapatan_harian`
--
DROP TABLE IF EXISTS `pendapatan_harian`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pendapatan_harian`  AS SELECT `t`.`trans_masuk` AS `tanggal`, sum(case when `t`.`pembayaran` = 'Lunas' then `t`.`total_harga` else 0 end) AS `total_lunas`, sum(case when `t`.`pembayaran` = 'Belum Lunas' then `t`.`total_harga` else 0 end) AS `total_belum_lunas` FROM `transaksi` AS `t` GROUP BY `t`.`trans_masuk` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`no_hp_cus`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_trans` (`id_trans`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_trans`),
  ADD KEY `no_hp_cus` (`no_hp_cus`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_trans`) REFERENCES `transaksi` (`id_trans`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`no_hp_cus`) REFERENCES `customer` (`no_hp_cus`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
