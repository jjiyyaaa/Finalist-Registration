-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 8.4.3 - MySQL Community Server - GPL
-- OS Server:                    Win64
-- HeidiSQL Versi:               12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Membuang struktur basisdata untuk mmpu_db
CREATE DATABASE IF NOT EXISTS `mmpu_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `mmpu_db`;

-- membuang struktur untuk table mmpu_db.finalists
CREATE TABLE IF NOT EXISTS `finalists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `major` varchar(100) NOT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `whatsapp` varchar(50) NOT NULL,
  `motivation` text NOT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `height_mr` int DEFAULT NULL,
  `height_ms` int DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `wear_glasses` varchar(10) DEFAULT NULL,
  `prescription` varchar(100) DEFAULT NULL,
  `contact_lenses` varchar(10) DEFAULT NULL,
  `medical_history` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `finalists` (`fullname`, `nickname`, `email`, `major`, `batch`, `instagram`, `whatsapp`, `motivation`, `cv_path`, `height_mr`, `height_ms`, `gpa`, `wear_glasses`, `prescription`, `contact_lenses`, `medical_history`) VALUES
('Ari Putra', 'Ari', 'ari@example.com', 'Computer Science', '2024', '@ari.cs', '081234567890', 'I want to represent the university with passion and integrity.', '', 175, 170, 3.85, 'No', '-', 'No', 'No medical issues'),
('Nadia Sari', 'Nadia', 'nadia@example.com', 'Communication', '2023', '@nadiacomm', '081298765432', 'I am ready to be the best ambassador for my university.', '', 165, 160, 3.92, 'Yes', 'SPH -1.75', 'No', 'Minor allergies'),
('Rizky Ahmad', 'Rizky', 'rizky@example.com', 'Design', '2025', '@rizkydesign', '081212345678', 'My creative energy will bring a new color to the stage.', '', 178, 168, 3.70, 'No', '-', 'No', 'None');

-- Membuang data untuk tabel mmpu_db.finalists: ~0 rows (lebih kurang)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
