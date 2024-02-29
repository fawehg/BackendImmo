-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for pfe
CREATE DATABASE IF NOT EXISTS `pfe` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pfe`;

-- Dumping structure for table pfe.availabilities
CREATE TABLE IF NOT EXISTS `availabilities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `day` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pfe.availabilities: ~0 rows (approximately)

-- Dumping structure for table pfe.clients
CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pfe.clients: ~0 rows (approximately)
REPLACE INTO `clients` (`id`, `nom`, `prenom`, `ville`, `adresse`, `email`, `password`, `created_at`, `updated_at`) VALUES
	(1, 'AAAAAAA', 'BBBBBB', 'AAAA', 'BBBB', 'AAA@bbb.com', '$2y$12$39jamegKME1FTpgE1vxB5eU/xJNZnvPG5GSz7b2LxIW1jQXHCsria', '2024-02-23 11:14:00', '2024-02-23 11:14:00');

-- Dumping structure for table pfe.demandes
CREATE TABLE IF NOT EXISTS `demandes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `panneType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pfe.demandes: ~2 rows (approximately)
REPLACE INTO `demandes` (`id`, `service`, `panneType`, `city`, `date`, `time`, `description`, `image`, `created_at`, `updated_at`) VALUES
	(1, 'Maçon', 'Mur endommagé', 'Jendouba', '2024-02-03', '17:00:00', 'hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh', NULL, '2024-02-26 13:59:22', '2024-02-26 13:59:22'),
	(2, 'Maçon', 'Mur endommagé', 'Jendouba', '2024-02-03', '17:00:00', 'hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh', NULL, '2024-02-26 13:59:23', '2024-02-26 13:59:23');

-- Dumping structure for table pfe.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pfe.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table pfe.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pfe.migrations: ~8 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2024_02_14_213948_create_ouvriers_table', 1),
	(6, '2024_02_15_122553_create__client_table', 2),
	(7, '2024_02_19_121105_create_ouvriers_table', 3),
	(8, '2024_02_19_121631_create_clients_table', 4),
	(9, '2024_02_22_113046_create_users_table', 5),
	(10, '2024_02_22_132101_create_ouvriers_table', 6),
	(11, '2024_02_22_134904_create_ouvriers_table', 7),
	(12, '2024_02_22_142701_create_ouvriers_table', 8),
	(13, '2024_02_22_142708_create_specialties_table', 8),
	(14, '2024_02_22_142714_create_availabilities_table', 8),
	(15, '2024_02_22_145954_create_users_table', 9),
	(16, '2024_02_23_110715_create_clients_table', 10),
	(17, '2024_02_23_114705_create_clients_table', 11),
	(18, '2024_02_23_120518_create_clients_table', 12),
	(19, '2024_02_26_141614_create_demandes_table', 13),
	(20, '2024_02_26_145232_create_demandes_table', 14);

-- Dumping structure for table pfe.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pfe.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table pfe.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pfe.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table pfe.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profession` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialties` json DEFAULT NULL,
  `joursDisponibilite` json DEFAULT NULL,
  `heureDebut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `heureFin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pfe.users: ~6 rows (approximately)
REPLACE INTO `users` (`id`, `nom`, `prenom`, `email`, `ville`, `adresse`, `password`, `profession`, `specialties`, `joursDisponibilite`, `heureDebut`, `heureFin`, `created_at`, `updated_at`) VALUES
	(1, 'Moetaz', 'ZYTA', 'MOEZYTA@MAIL.COM', 'TUNIS', '16ABAB10', '$2y$12$4G4sXn9duA7ATnh17emkEuvxYjf6P3l2/W0zaucmqrUHyBCDDiJIC', 'Maçon', '["Mur endommagé"]', '["Lundi", "Mardi"]', '16:08', '16:09', '2024-02-22 14:07:49', '2024-02-22 14:07:49'),
	(2, 'baalouch', 'omar', 'baalouchomar123@gmail.com', 'MouroujCity', 'balasPharmacie', '$2y$12$jWpHul8ln7LtqwKx7RLgSee3YHBvXc9G3r9A0.L7GIOOwxO9bWLG.', 'Charpentier', '["Toiture endommagée"]', '["Lundi", "Mardi", "Mercredi"]', '22:14', '22:19', '2024-02-22 14:14:23', '2024-02-22 14:14:23'),
	(3, 'ZYTA', 'MOETA', 'MOAZY11@MAIL.COM', 'TUNIS', '12AA12', '$2y$12$gNg27QT9wn47fW7crr/ldOq6a4/0lhGls4VpIYdZPrDoocKTEE6I2', 'Menuisier', '["Fenêtre cassée", "Porte qui coince", "Escalier endommagé"]', '["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"]', '20:23', '22:23', '2024-02-22 14:23:35', '2024-02-22 14:23:35'),
	(4, 'traaa', 'khalil', 'TRABELSI@MAIL.COM', 'tadhamen', '11AA11', '$2y$12$tyiPKeFV6HQZJh7A7mRBFedRXHM9Ven7VtecZCcfUnUOgwJx3myZW', 'Plombier', '["Fuite d\'eau", "Canalisations bouchées", "Problème de chauffe-eau"]', '["Dimanche", "Samedi", "Vendredi", "Jeudi", "Mercredi", "Mardi", "Lundi"]', '21:25', '22:25', '2024-02-22 14:25:51', '2024-02-22 14:25:51'),
	(5, 'zitounAAAAA', 'Moetaz', 'moetazzita@gmail.comAA', 'tunis', 'DDD', '$2y$12$7qNq5pAChLpPNgYsNkkLp.KYvSsGLmWNwo23Z2x/rfV8a3gehSGEW', 'Charpentier', '["Toiture endommagée"]', '["Lundi", "Mardi", "Samedi"]', '00:56', '18:57', '2024-02-23 08:53:55', '2024-02-23 08:53:55'),
	(6, 'ZITOUN', 'MOETAZ', 'MOETAZZITA19@GMAIL.COM', 'TUNIS', 'CIT2 AVICENNE', '$2y$12$mrx6E2Nk4EOaee/dqve3FuLuagXJdEdeVKFBs2UYf.ASuuOv25OAC', 'Menuisier', '["Porte qui coince", "Escalier endommagé"]', '["Lundi", "Mardi", "Mercredi", "Jeudi", "Samedi"]', '03:15', '05:15', '2024-02-23 09:15:08', '2024-02-23 09:15:08'),
	(7, 'Zytaaa', 'Moetaza', 'moetazzita0@gmail.coma', 'Tunisa', '16 rue Aglabides cite avicennea', '$2y$12$dVqA7MTkGBBPq9HFvrBGCePmULt5KgHzkmcn5Mi3T9lifJRxIDx62', 'Maçon', '["Mur endommagé"]', '["Mardi", "Lundi"]', '17:26', '20:32', '2024-02-23 11:26:14', '2024-02-23 11:26:14');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
pfepfe