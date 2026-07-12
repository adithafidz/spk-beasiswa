-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2026 at 07:34 PM
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
-- Database: `db_sppk_beasiswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nim` varchar(15) NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nim`, `nama_mahasiswa`) VALUES
(5, '2023435002', 'Malik arsyad'),
(6, '2023435003', 'Ghifari adam'),
(7, '2023435004', 'Baskara putra'),
(8, '2023435005', 'Putri ayu'),
(9, '202343506', 'Adam');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kode_kriteria` varchar(10) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot` float NOT NULL,
  `jenis` enum('benefit','cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kode_kriteria`, `nama_kriteria`, `bobot`, `jenis`) VALUES
(1, 'C1', 'IPK', 0.4, 'benefit'),
(2, 'C2', 'Penghasilan Orang Tua', 0.3, 'cost'),
(3, 'C3', 'Jumlah Tanggungan', 0.2, 'benefit'),
(4, 'C4', 'Prestasi Non-Akademik', 0.1, 'benefit');

-- --------------------------------------------------------

--
-- Table structure for table `nilai_alternatif`
--

CREATE TABLE `nilai_alternatif` (
  `id_nilai` int(11) NOT NULL,
  `id_alternatif` int(11) DEFAULT NULL,
  `id_kriteria` int(11) DEFAULT NULL,
  `nilai` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai_alternatif`
--

INSERT INTO `nilai_alternatif` (`id_nilai`, `id_alternatif`, `id_kriteria`, `nilai`) VALUES
(17, 5, 1, 1),
(18, 5, 2, 1),
(19, 5, 3, 4),
(20, 5, 4, 2),
(21, 6, 1, 5),
(22, 6, 2, 5),
(23, 6, 3, 5),
(24, 6, 4, 5),
(25, 7, 1, 3),
(26, 7, 2, 3),
(27, 7, 3, 4),
(28, 7, 4, 2),
(29, 8, 1, 4),
(30, 8, 2, 2),
(31, 8, 3, 5),
(32, 8, 4, 3),
(33, 9, 1, 3),
(34, 9, 2, 3),
(35, 9, 3, 3),
(36, 9, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','mahasiswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Aditya Hafidz', 'admin'),
(3, '2023435001', '$2y$10$dvZC7pAphJIn2xqBm5wtQ.i.y5kGes4M1l5VbUEWTFYLbFIncBpai', 'Brian', 'mahasiswa'),
(4, 'admin2', '$2y$10$Q39j5fLcu0ay9Ugt3iv1UebMl96//u6OsGZi512xKYDav79.tliJK', 'John doe', 'admin'),
(5, '2023435002', '$2y$10$DQrNO/KKFZdKhBhv0ds25uFuz3ozkIOCS4Y0Epb0QTQYae8bCrBdS', 'Malik arsyad', 'mahasiswa'),
(6, '2023435003', '$2y$10$CXr53cG8rj6yENY51jpMgeVI6YVJ8uXdq1NpKHtS5Qgq5s.FRkPxa', 'Ghifari adam', 'mahasiswa'),
(7, '2023435004', '$2y$10$Vr1tDIhOziE2Pj8jSGEPeeHNGTEieTzgX5BpFWqjXeb1a6JcWSs.q', 'Baskara putra', 'mahasiswa'),
(8, '2023435005', '$2y$10$pcKOL9mfDyweuEN6uclY0ONBUQqRCLMu5euJatNc9e2/HomqI7nS6', 'Putri ayu', 'mahasiswa'),
(9, '202343506', '$2y$10$L9W8OHMZIO9RyQad9RxDCuzuJTYkxaDCouH98X2yiWEDv3aTIu1n6', 'Adam', 'mahasiswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `nilai_alternatif`
--
ALTER TABLE `nilai_alternatif`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `id_alternatif` (`id_alternatif`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nilai_alternatif`
--
ALTER TABLE `nilai_alternatif`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nilai_alternatif`
--
ALTER TABLE `nilai_alternatif`
  ADD CONSTRAINT `nilai_alternatif_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_alternatif_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
