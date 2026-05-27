-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 27, 2026 at 09:24 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `softhotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `roles`, `password`) VALUES
(1, 'leonboscolo200@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$3AWFYwu7cPnq7RgxBENsO.dhuCA8DRkw/ZKe6XRJPE.n3VRLNobMq'),
(4, 'lionel@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$U2Nn1Y.qxSyrAwmbRm6wyOLAJwWlQQMq2c0PBLQXIElXgzyGCBKoq'),
(5, 'leonking200@gmai.com', '[\"ROLE_USERS\"]', '$2y$13$M68SmCFJJJN8UltH8ZWtSeHdmFCcF2LjBQfQS2qDPlVByDwOXpDwG'),
(6, 'users@gmail.com', '[\"ROLE_USER\"]', '$2y$13$yVO1fKRYVA5rlgulbCMkXeV0wf.dswIcEjRUu8VAJrNCVZwGgmYXm'),
(7, 'Admin@admin.com', '[\"ROLE_USER\"]', '$2y$13$vQeeO3VsIT.3z4p8ufB7du3R4K1PM0S82r8Cpz4R51migPQks6qAO'),
(8, 'Admin1@admin.com', '[\"ROLE_USER\"]', '$2y$13$yYy3aJcwc42CV4XdXoKT6el6YQoMRQfDyt0KuB5TQhA9qrPTrDwcu');

-- --------------------------------------------------------

--
-- Table structure for table `chambre`
--

DROP TABLE IF EXISTS `chambre`;
CREATE TABLE IF NOT EXISTS `chambre` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_chambre` int NOT NULL,
  `type_chambre_id` int DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT '1',
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C509E4FF8614A971` (`type_chambre_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chambre`
--

INSERT INTO `chambre` (`id`, `numero_chambre`, `type_chambre_id`, `disponible`, `image`) VALUES
(1, 12, 2, 0, 'chambre_69ebbd7f49a809.04461484.jpg'),
(2, 10, 2, 1, 'chambre_69ef3114635fa9.52950637.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `mot_passe` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `nom`, `prenom`, `email`, `telephone`, `mot_passe`) VALUES
(1, 'Moyo', 'pola', 'polamoyo@gmail.com', '54785412', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20260406195951', '2026-04-06 20:00:24', 543),
('DoctrineMigrations\\Version20260406203649', '2026-04-06 20:36:52', 47),
('DoctrineMigrations\\Version20260408201213', '2026-04-08 20:12:25', 139),
('DoctrineMigrations\\Version20260424170207', '2026-04-24 17:02:15', 78),
('DoctrineMigrations\\Version20260424172916', '2026-04-24 17:33:48', 98),
('DoctrineMigrations\\Version20260424183235', '2026-04-24 18:43:11', 66),
('DoctrineMigrations\\Version20260427075308', '2026-04-27 08:06:31', 116),
('DoctrineMigrations\\Version20260427093955', '2026-04-27 09:43:03', 33);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750` (`queue_name`,`available_at`,`delivered_at`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `montant` int NOT NULL,
  `date_paiement` date NOT NULL,
  `reservation_id` int DEFAULT NULL,
  `reference_paypal` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B1DC7A1EB83297E7` (`reservation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `paiement`
--

INSERT INTO `paiement` (`id`, `montant`, `date_paiement`, `reservation_id`, `reference_paypal`) VALUES
(1, 100, '2026-04-27', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `date_reservation` date NOT NULL,
  `statut` varchar(50) NOT NULL,
  `client_id` int DEFAULT NULL,
  `chambre_id` int DEFAULT NULL,
  `montant_total` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_42C8495519EB6921` (`client_id`),
  KEY `IDX_42C849559B177F54` (`chambre_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `date_debut`, `date_fin`, `date_reservation`, `statut`, `client_id`, `chambre_id`, `montant_total`) VALUES
(1, '2026-12-30', '2026-12-31', '2026-04-27', 'Annulée', 1, 1, 100),
(3, '2026-05-04', '2026-05-07', '2026-05-04', 'Annulée', 1, 2, 300),
(4, '2026-05-09', '2026-05-11', '2026-05-08', 'Confirmée', 1, 1, 200);

-- --------------------------------------------------------

--
-- Table structure for table `type_chambre`
--

DROP TABLE IF EXISTS `type_chambre`;
CREATE TABLE IF NOT EXISTS `type_chambre` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_type` varchar(50) NOT NULL,
  `prix_par_nuit` int NOT NULL,
  `capacite_max` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `type_chambre`
--

INSERT INTO `type_chambre` (`id`, `nom_type`, `prix_par_nuit`, `capacite_max`) VALUES
(2, 'Suite', 100, 3),
(3, 'Économique', 40, 2),
(4, 'Prestige', 70, 3),
(5, 'Standard', 25, 3),
(6, 'double', 60, 4);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chambre`
--
ALTER TABLE `chambre`
  ADD CONSTRAINT `FK_C509E4FF8614A971` FOREIGN KEY (`type_chambre_id`) REFERENCES `type_chambre` (`id`);

--
-- Constraints for table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `FK_B1DC7A1EB83297E7` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `FK_42C8495519EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_42C849559B177F54` FOREIGN KEY (`chambre_id`) REFERENCES `chambre` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
