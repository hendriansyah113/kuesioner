-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Nov 2024 pada 19.36
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kuesioner_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil`
--

CREATE TABLE `hasil` (
  `id` int(11) NOT NULL,
  `id_responden` int(11) NOT NULL,
  `id_soal` int(11) NOT NULL,
  `id_jawaban` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hasil`
--

INSERT INTO `hasil` (`id`, `id_responden`, `id_soal`, `id_jawaban`) VALUES
(17, 1, 1, 1),
(18, 1, 2, 3),
(19, 1, 3, 1),
(20, 1, 4, 1),
(21, 2, 1, 3),
(22, 2, 2, 3),
(23, 2, 3, 3),
(24, 2, 4, 3),
(25, 4, 1, 5),
(26, 4, 2, 5),
(27, 4, 3, 5),
(28, 4, 4, 5),
(29, 5, 1, 1),
(30, 5, 2, 1),
(31, 5, 3, 1),
(32, 5, 4, 1),
(33, 5, 5, 1),
(35, 6, 1, 4),
(36, 6, 2, 4),
(37, 6, 3, 4),
(38, 6, 4, 4),
(39, 6, 5, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jawaban`
--

CREATE TABLE `jawaban` (
  `id` int(11) NOT NULL,
  `jawaban` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jawaban`
--

INSERT INTO `jawaban` (`id`, `jawaban`) VALUES
(1, 'Sangat tidak setuju'),
(2, 'Tidak setuju'),
(3, 'Netral'),
(4, 'Setuju'),
(5, 'Sangat setuju');

-- --------------------------------------------------------

--
-- Struktur dari tabel `responden`
--

CREATE TABLE `responden` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `responden`
--

INSERT INTO `responden` (`id`, `nama`) VALUES
(1, 'Lisa'),
(2, 'hendri'),
(3, 'Yosefa Aditya Putra2'),
(4, 'Lisa'),
(5, 'Yosefa Aditya Putra2'),
(6, 'hendri');

-- --------------------------------------------------------

--
-- Struktur dari tabel `soal`
--

CREATE TABLE `soal` (
  `id` int(11) NOT NULL,
  `pertanyaan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `soal`
--

INSERT INTO `soal` (`id`, `pertanyaan`) VALUES
(1, 'Apakah bagw'),
(2, 'soal 2'),
(3, 'are you okay Lisa?'),
(4, 'Semangat Lisa!!!'),
(5, 'OTW KPU GAES');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_responden` (`id_responden`),
  ADD KEY `id_jawaban` (`id_jawaban`),
  ADD KEY `hasil_ibfk_2` (`id_soal`);

--
-- Indeks untuk tabel `jawaban`
--
ALTER TABLE `jawaban`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `responden`
--
ALTER TABLE `responden`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `jawaban`
--
ALTER TABLE `jawaban`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `responden`
--
ALTER TABLE `responden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hasil`
--
ALTER TABLE `hasil`
  ADD CONSTRAINT `hasil_ibfk_1` FOREIGN KEY (`id_responden`) REFERENCES `responden` (`id`),
  ADD CONSTRAINT `hasil_ibfk_2` FOREIGN KEY (`id_soal`) REFERENCES `soal` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_ibfk_3` FOREIGN KEY (`id_jawaban`) REFERENCES `jawaban` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
