-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 01, 2025 at 09:44 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `strzelnica`
--

-- --------------------------------------------------------

--
-- Table structure for table `ammo`
--

CREATE TABLE `ammo` (
  `ID_ammo` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `amount` int(11) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='Lista typów amunicji';

--
-- Dumping data for table `ammo`
--

INSERT INTO `ammo` (`ID_ammo`, `name`, `amount`, `price`) VALUES
(1, '9x19 mm', 500, 2),
(2, '.45 ACP', 300, 3.6),
(3, '.9x32Rmm', 100, 3.6),
(4, '.223 Rem', 200, 6),
(5, '5.56 NATO', 180, 4),
(6, '7.62x39 mm', 160, 6.5);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `ID_client` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` text NOT NULL,
  `surname` text NOT NULL,
  `ID_membership` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='Klienci wraz z członkostwem';

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`ID_client`, `user_id`, `name`, `surname`, `ID_membership`) VALUES
(1, NULL, 'Krzysztof', 'Mazur', 23),
(2, NULL, 'Andrzej', 'Wójcik', 31),
(3, NULL, 'Mateusz', 'Nowak', 29),
(4, NULL, 'Agnieszka', 'Witkowska', 17),
(5, NULL, 'Magdalena', 'Witkowska', 44),
(6, NULL, 'Paweł', 'Sikora', 18),
(7, NULL, 'Tomasz', 'Piotrowski', 2),
(8, NULL, 'Adam', 'Krawczyk', 12),
(9, NULL, 'Piotr', 'Nowak', 7),
(10, NULL, 'Paweł', 'Krawczyk', 27),
(11, NULL, 'Agnieszka', 'Witkowska', 5),
(12, NULL, 'Barbara', 'Wiśniewska', 45),
(13, NULL, 'Magdalena', 'Dąbrowska', 24),
(14, NULL, 'Maria', 'Woźniak', 25),
(15, NULL, 'Marcin', 'Krawczyk', 41),
(16, NULL, 'Michał', 'Mazur', 40),
(17, NULL, 'Adam', 'Dudek', 3),
(18, NULL, 'Andrzej', 'Kowalczyk', 4),
(19, NULL, 'Andrzej', 'Krawczyk', 38),
(20, NULL, 'Paweł', 'Dudek', 36),
(21, NULL, 'Katarzyna', 'Szymańska', 9),
(22, NULL, 'Maria', 'Kamińska', 16),
(23, NULL, 'Aleksandra', 'Majewska', 22),
(24, NULL, 'Maria', 'Witkowska', 35),
(25, NULL, 'Krzysztof', 'Wójcik', 46),
(26, NULL, 'Aleksandra', 'Majewska', 21),
(27, NULL, 'Mateusz', 'Grabowski', 50),
(28, NULL, 'Paweł', 'Mazur', 1),
(29, NULL, 'Piotr', 'Grabowski', 42),
(30, NULL, 'Barbara', 'Wiśniewska', 10),
(31, NULL, 'Michał', 'Nowak', 19),
(32, NULL, 'Katarzyna', 'Wiśniewska', 47),
(33, NULL, 'Paweł', 'Piotrowski', 39),
(34, NULL, 'Ewa', 'Szymańska', 13),
(35, NULL, 'Marcin', 'Mazur', 33),
(36, NULL, 'Anna', 'Zając', 8),
(37, NULL, 'Adam', 'Nowak', 30),
(38, NULL, 'Michał', 'Piotrowski', 11),
(39, NULL, 'Andrzej', 'Piotrowski', 43),
(40, NULL, 'Mateusz', 'Dudek', 14),
(41, NULL, 'Joanna', 'Szymańska', 26),
(42, NULL, 'Joanna', 'Majewska', 48),
(43, NULL, 'Krzysztof', 'Piotrowski', 20),
(44, NULL, 'Katarzyna', 'Zając', 34),
(45, NULL, 'Mateusz', 'Dudek', 6),
(46, NULL, 'Piotr', 'Kowalczyk', 32),
(47, NULL, 'Agnieszka', 'Kamińska', 15),
(48, NULL, 'Andrzej', 'Grabowski', 49),
(49, NULL, 'Maria', 'Kamińska', 37),
(50, NULL, 'Joanna', 'Witkowska', 28),
(151, 10, 'Karol', 'Narel', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `guns`
--

CREATE TABLE `guns` (
  `ID_guns` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `ID_ammo` int(11) NOT NULL,
  `availability` tinyint(1) NOT NULL,
  `in_use` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='Lista dostępnych broni palnych';

--
-- Dumping data for table `guns`
--

INSERT INTO `guns` (`ID_guns`, `name`, `ID_ammo`, `availability`, `in_use`) VALUES
(1, 'Colt 1911', 2, 0, 0),
(2, 'Beretta 92 FS', 1, 1, 1),
(4, 'Glock 17 Gen.4', 1, 0, 0),
(5, 'HK z tłumikiem', 1, 1, 1),
(6, 'Vis 100', 1, 1, 0),
(7, '.357 Magnum', 3, 1, 0),
(8, 'AR-15', 4, 1, 0),
(9, 'kbk wz. 96D „Beryl”', 5, 1, 1),
(10, 'IWI Galil SAR', 6, 1, 1),
(11, 'MSBS „Grot”', 5, 1, 0),
(12, 'AKMS „Kałasznikow”', 6, 1, 0),
(13, 'M4', 5, 1, 0),
(14, 'PM-84P „Glauberyt”', 1, 1, 1),
(15, 'IWI UZI', 1, 1, 0),
(16, 'MP 40', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `ID_membership` int(11) NOT NULL,
  `type` text NOT NULL,
  `activation_date` date NOT NULL,
  `expiration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='Członkostwo';

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`ID_membership`, `type`, `activation_date`, `expiration_date`) VALUES
(1, 'Premium', '2025-05-21', '2025-06-21'),
(2, 'Standard', '2025-05-22', '2025-06-22'),
(3, 'VIP', '2025-05-23', '2025-06-23'),
(4, 'Standard', '2025-05-24', '2025-06-24'),
(5, 'Premium', '2025-05-25', '2025-06-25'),
(6, 'VIP', '2025-05-26', '2025-06-26'),
(7, 'Standard', '2025-05-27', '2025-06-27'),
(8, 'Premium', '2025-05-28', '2025-06-28'),
(9, 'VIP', '2025-05-29', '2025-06-29'),
(10, 'Standard', '2025-05-30', '2025-06-30'),
(11, 'Premium', '2025-05-21', '2025-06-21'),
(12, 'VIP', '2025-05-22', '2025-06-22'),
(13, 'Standard', '2025-05-23', '2025-06-23'),
(14, 'Premium', '2025-05-24', '2025-06-24'),
(15, 'VIP', '2025-05-25', '2025-06-25'),
(16, 'Standard', '2025-05-26', '2025-06-26'),
(17, 'Premium', '2025-05-27', '2025-06-27'),
(18, 'VIP', '2025-05-28', '2025-06-28'),
(19, 'Standard', '2025-05-29', '2025-06-29'),
(20, 'Premium', '2025-05-30', '2025-06-30'),
(21, 'Standard', '2025-05-21', '2025-06-21'),
(22, 'Standard', '2025-05-22', '2025-06-22'),
(23, 'Standard', '2025-05-23', '2025-06-23'),
(24, 'Standard', '2025-05-24', '2025-06-24'),
(25, 'Standard', '2025-05-25', '2025-06-25'),
(26, 'Standard', '2025-05-26', '2025-06-26'),
(27, 'Standard', '2025-05-27', '2025-06-27'),
(28, 'Standard', '2025-05-28', '2025-06-28'),
(29, 'Standard', '2025-05-29', '2025-06-29'),
(30, 'Standard', '2025-05-30', '2025-06-30'),
(31, 'Standard', '2025-05-21', '2025-06-21'),
(32, 'Standard', '2025-05-22', '2025-06-22'),
(33, 'Standard', '2025-05-23', '2025-06-23'),
(34, 'Standard', '2025-05-24', '2025-06-24'),
(35, 'Standard', '2025-05-25', '2025-06-25'),
(36, 'Standard', '2025-05-26', '2025-06-26'),
(37, 'Standard', '2025-05-27', '2025-06-27'),
(38, 'Standard', '2025-05-28', '2025-06-28'),
(39, 'Premium', '2025-05-21', '2025-06-21'),
(40, 'Premium', '2025-05-22', '2025-06-22'),
(41, 'Premium', '2025-05-23', '2025-06-23'),
(42, 'Premium', '2025-05-24', '2025-06-24'),
(43, 'Premium', '2025-05-25', '2025-06-25'),
(44, 'Premium', '2025-05-26', '2025-06-26'),
(45, 'Premium', '2025-05-27', '2025-06-27'),
(46, 'Premium', '2025-05-28', '2025-06-28'),
(47, 'Premium', '2025-05-29', '2025-06-29'),
(48, 'VIP', '2025-05-21', '2025-06-21'),
(49, 'VIP', '2025-05-24', '2025-06-24'),
(50, 'VIP', '2025-05-27', '2025-06-27'),
(999, 'No Membership', '1970-01-01', '1970-01-01'),
(1000, 'Standard', '2025-06-01', '2025-07-01');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `ID_reservations` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `ID_client` int(11) NOT NULL,
  `participants` int(11) NOT NULL,
  `instructor` tinyint(1) NOT NULL,
  `ID_station` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='Rezerwacja (participants = grupa)';

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`ID_reservations`, `date`, `time`, `ID_client`, `participants`, `instructor`, `ID_station`) VALUES
(1, '2025-06-03', '09:00:00', 42, 4, 1, 1),
(2, '2025-06-05', '10:30:00', 32, 6, 0, 2),
(3, '2025-06-06', '11:15:00', 45, 2, 1, 5),
(4, '2025-06-07', '12:00:00', 21, 1, 0, 3),
(5, '2025-06-10', '13:45:00', 3, 6, 1, 5),
(6, '2025-06-11', '14:30:00', 32, 8, 0, 1),
(7, '2025-06-13', '15:00:00', 11, 3, 1, 7),
(8, '2025-06-16', '16:00:00', 15, 4, 0, 4),
(9, '2025-06-18', '17:00:00', 16, 6, 1, 7),
(10, '2025-06-20', '17:30:00', 43, 8, 0, 1),
(11, '2025-06-14', '08:00:00', 1, 4, 1, 1),
(12, '2025-06-14', '09:00:00', 2, 6, 0, 2),
(13, '2025-06-14', '10:00:00', 3, 8, 1, 3),
(14, '2025-06-14', '11:00:00', 4, 4, 0, 4),
(15, '2025-06-14', '12:00:00', 5, 6, 1, 5),
(16, '2025-06-14', '13:00:00', 6, 8, 0, 6),
(17, '2025-06-14', '14:00:00', 7, 2, 1, 7),
(18, '2025-06-14', '15:00:00', 8, 1, 0, 8),
(19, '2025-06-15', '08:30:00', 9, 4, 0, 1),
(20, '2025-06-15', '09:30:00', 10, 6, 1, 2),
(21, '2025-06-15', '10:30:00', 11, 8, 0, 3),
(22, '2025-06-15', '11:30:00', 12, 4, 1, 4),
(23, '2025-06-15', '12:30:00', 13, 6, 0, 5),
(24, '2025-06-15', '13:30:00', 14, 8, 1, 6),
(25, '2025-06-15', '14:30:00', 15, 2, 0, 7),
(26, '2025-06-15', '15:30:00', 16, 1, 1, 8),
(27, '2025-06-16', '08:15:00', 17, 4, 1, 1),
(28, '2025-06-16', '09:15:00', 18, 6, 0, 2),
(29, '2025-06-16', '10:15:00', 19, 8, 1, 3),
(30, '2025-06-16', '11:15:00', 20, 4, 0, 4),
(31, '2025-06-16', '12:15:00', 1, 6, 1, 5),
(32, '2025-06-16', '13:15:00', 2, 8, 0, 6),
(33, '2025-06-16', '14:15:00', 3, 2, 1, 7),
(34, '2025-06-16', '15:15:00', 4, 1, 0, 8),
(35, '2025-06-17', '08:45:00', 5, 4, 1, 1),
(36, '2025-06-17', '09:45:00', 6, 6, 0, 2),
(37, '2025-06-17', '10:45:00', 7, 8, 1, 3),
(38, '2025-06-17', '11:45:00', 8, 4, 0, 4),
(39, '2025-06-17', '12:45:00', 9, 6, 1, 5),
(40, '2025-06-17', '13:45:00', 10, 8, 0, 6),
(41, '2025-06-21', '08:00:00', 30, 4, 1, 1),
(42, '2025-06-21', '09:00:00', 31, 6, 0, 2),
(43, '2025-06-21', '10:00:00', 32, 8, 1, 3),
(44, '2025-06-21', '11:00:00', 33, 4, 0, 4),
(45, '2025-06-21', '12:00:00', 34, 6, 1, 5),
(46, '2025-06-21', '13:00:00', 35, 8, 0, 6),
(47, '2025-06-21', '14:00:00', 36, 2, 1, 7),
(48, '2025-06-21', '15:00:00', 37, 1, 0, 8),
(49, '2025-06-22', '08:30:00', 38, 4, 0, 1),
(50, '2025-06-22', '09:30:00', 39, 6, 1, 2),
(51, '2025-06-22', '10:30:00', 40, 8, 0, 3),
(52, '2025-06-22', '11:30:00', 41, 4, 1, 4),
(53, '2025-06-22', '12:30:00', 42, 6, 0, 5),
(54, '2025-06-22', '13:30:00', 43, 8, 1, 6),
(55, '2025-06-22', '14:30:00', 44, 2, 0, 7),
(56, '2025-06-22', '15:30:00', 45, 1, 1, 8),
(57, '2025-06-23', '08:15:00', 46, 4, 1, 1),
(58, '2025-06-23', '09:15:00', 47, 6, 0, 2),
(59, '2025-06-23', '10:15:00', 48, 8, 1, 3),
(60, '2025-06-23', '11:15:00', 49, 4, 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE `stations` (
  `ID_station` int(11) NOT NULL,
  `slots` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='Stanowiska wraz z ilością miejsc';

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`ID_station`, `slots`) VALUES
(1, 4),
(2, 6),
(3, 8),
(4, 4),
(5, 6),
(6, 8),
(7, 2),
(8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `ID_transaction` int(11) NOT NULL,
  `ID_ammo` int(11) NOT NULL,
  `ID_client` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='Transakcje wykupu amunicji';

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`ID_transaction`, `ID_ammo`, `ID_client`, `count`) VALUES
(1, 3, 14, 20),
(2, 1, 7, 50),
(3, 6, 35, 40),
(4, 2, 2, 30),
(5, 4, 22, 60),
(6, 5, 50, 10),
(7, 1, 19, 40),
(8, 3, 44, 20),
(9, 2, 11, 30),
(10, 6, 9, 50),
(11, 4, 28, 10),
(12, 5, 7, 60),
(13, 1, 30, 30),
(14, 3, 3, 40),
(15, 2, 48, 20),
(16, 6, 16, 10),
(17, 5, 39, 60),
(18, 4, 25, 50),
(19, 1, 6, 40),
(20, 3, 12, 30),
(21, 2, 20, 20),
(22, 6, 33, 10),
(23, 5, 18, 60),
(24, 4, 27, 40),
(25, 1, 4, 30),
(26, 3, 41, 50),
(27, 2, 15, 20),
(28, 6, 8, 10),
(29, 5, 49, 60),
(30, 4, 36, 40),
(31, 2, 21, 30),
(32, 5, 13, 50),
(33, 1, 42, 20),
(34, 3, 7, 40),
(35, 4, 18, 60),
(36, 6, 29, 10),
(37, 2, 45, 20),
(38, 5, 10, 40),
(39, 1, 31, 30),
(40, 3, 23, 60),
(41, 4, 16, 10),
(42, 6, 39, 50),
(43, 2, 26, 40),
(44, 5, 12, 30),
(45, 1, 8, 60),
(46, 3, 34, 20),
(47, 4, 47, 50),
(48, 6, 9, 10),
(49, 2, 28, 40),
(50, 5, 19, 30),
(51, 1, 44, 50),
(52, 3, 14, 20),
(53, 4, 25, 10),
(54, 6, 36, 60),
(55, 2, 11, 30),
(56, 5, 30, 40),
(57, 1, 15, 10),
(58, 3, 50, 60),
(59, 4, 17, 20),
(60, 6, 22, 40),
(61, 2, 38, 30),
(62, 5, 5, 50),
(63, 1, 27, 10),
(64, 3, 33, 20),
(65, 4, 41, 60),
(66, 6, 24, 40),
(67, 2, 7, 30),
(68, 5, 46, 10),
(69, 1, 13, 60),
(70, 3, 35, 50),
(71, 1, 10, 20),
(72, 2, 23, 30),
(73, 3, 35, 40),
(74, 4, 5, 50),
(75, 5, 12, 60),
(76, 6, 18, 10),
(77, 1, 44, 20),
(78, 2, 6, 30),
(79, 3, 27, 40),
(80, 4, 31, 50),
(81, 5, 9, 60),
(82, 6, 14, 10),
(83, 1, 22, 20),
(84, 2, 37, 30),
(85, 3, 48, 40),
(86, 4, 2, 50),
(87, 5, 45, 60),
(88, 6, 11, 10),
(89, 1, 7, 20),
(90, 2, 29, 30),
(91, 3, 40, 40),
(92, 4, 16, 50),
(93, 5, 38, 60),
(94, 6, 3, 10),
(95, 1, 50, 20),
(96, 2, 24, 30),
(97, 3, 17, 40),
(98, 4, 28, 50),
(99, 5, 41, 60),
(100, 6, 1, 10),
(101, 1, 33, 20),
(102, 2, 15, 30),
(103, 3, 4, 40),
(104, 4, 36, 50),
(105, 5, 20, 60),
(106, 6, 8, 10),
(107, 1, 43, 20),
(108, 2, 13, 30),
(109, 3, 19, 40),
(110, 4, 39, 50),
(111, 5, 25, 60),
(112, 6, 34, 10),
(113, 1, 21, 20),
(114, 2, 30, 30),
(115, 3, 47, 40),
(116, 4, 26, 50),
(117, 5, 42, 60),
(118, 6, 49, 10),
(119, 1, 32, 20),
(120, 2, 46, 30),
(121, 3, 44, 40),
(122, 4, 35, 50),
(123, 3, 11, 30),
(124, 1, 22, 50),
(125, 6, 3, 10),
(126, 5, 45, 40),
(127, 2, 14, 60),
(128, 4, 27, 20),
(129, 1, 39, 30),
(130, 3, 6, 50),
(131, 2, 17, 40),
(132, 5, 8, 10),
(133, 6, 21, 60),
(134, 4, 49, 20),
(135, 1, 12, 30),
(136, 3, 24, 40),
(137, 5, 36, 50),
(138, 2, 41, 10),
(139, 6, 7, 60),
(140, 4, 30, 20),
(141, 1, 44, 50),
(142, 3, 2, 10),
(143, 5, 38, 40),
(144, 6, 26, 60),
(145, 2, 15, 30),
(146, 4, 19, 50),
(147, 1, 48, 20),
(148, 3, 9, 40),
(149, 5, 33, 10),
(150, 6, 35, 60),
(151, 2, 4, 30),
(152, 4, 16, 50),
(153, 1, 23, 20),
(154, 3, 37, 40),
(155, 5, 13, 60),
(156, 6, 1, 10),
(157, 2, 28, 30),
(158, 4, 42, 50),
(159, 1, 10, 40),
(160, 3, 25, 20),
(161, 5, 47, 30),
(162, 6, 34, 60),
(163, 2, 5, 50),
(164, 4, 40, 10),
(165, 1, 31, 20),
(166, 3, 43, 30),
(167, 5, 18, 40),
(168, 6, 29, 60),
(169, 2, 8, 10),
(170, 4, 46, 50),
(171, 1, 20, 30),
(172, 3, 39, 40),
(173, 5, 7, 60),
(174, 6, 22, 20),
(175, 2, 32, 50),
(176, 4, 14, 10),
(177, 1, 41, 30),
(178, 3, 6, 40),
(179, 5, 27, 60),
(180, 6, 11, 20),
(181, 2, 44, 50),
(182, 4, 19, 10),
(183, 1, 35, 30),
(184, 3, 12, 40),
(185, 5, 30, 60),
(186, 6, 16, 20),
(187, 2, 47, 50),
(188, 4, 9, 10),
(189, 1, 28, 30),
(190, 3, 24, 40),
(191, 5, 38, 60),
(192, 6, 7, 20),
(193, 2, 15, 50),
(194, 4, 33, 10),
(195, 1, 41, 30);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='Tabela użytkowników systemu';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `created_at`, `updated_at`, `is_active`) VALUES
(10, 'Karol', 'Narel', 'karolnarel@gmail.com', '$2y$10$Co9I/yZKEFsTfk8UAcFJAeUezO0r4.iLpiS3yeMdNbXJt9qsSV2Am', '2025-06-01 19:41:28', '2025-06-01 19:41:43', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ammo`
--
ALTER TABLE `ammo`
  ADD PRIMARY KEY (`ID_ammo`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`ID_client`),
  ADD UNIQUE KEY `ID_membership` (`ID_membership`) USING BTREE,
  ADD UNIQUE KEY `unique_user_client` (`user_id`),
  ADD KEY `idx_clients_user_id` (`user_id`),
  ADD KEY `idx_clients_membership` (`ID_membership`);

--
-- Indexes for table `guns`
--
ALTER TABLE `guns`
  ADD PRIMARY KEY (`ID_guns`),
  ADD KEY `ID_ammo` (`ID_ammo`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`ID_membership`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`ID_reservations`),
  ADD KEY `ID_client` (`ID_client`),
  ADD KEY `ID_station` (`ID_station`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`ID_station`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`ID_transaction`),
  ADD KEY `ID_ammo` (`ID_ammo`),
  ADD KEY `ID_client` (`ID_client`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ammo`
--
ALTER TABLE `ammo`
  MODIFY `ID_ammo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `ID_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `guns`
--
ALTER TABLE `guns`
  MODIFY `ID_guns` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `ID_membership` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `ID_reservations` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `stations`
--
ALTER TABLE `stations`
  MODIFY `ID_station` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `ID_transaction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`ID_membership`) REFERENCES `membership` (`ID_membership`),
  ADD CONSTRAINT `clients_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guns`
--
ALTER TABLE `guns`
  ADD CONSTRAINT `guns_ibfk_1` FOREIGN KEY (`ID_ammo`) REFERENCES `ammo` (`ID_ammo`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`ID_station`) REFERENCES `stations` (`ID_station`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`ID_ammo`) REFERENCES `ammo` (`ID_ammo`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`ID_client`) REFERENCES `clients` (`ID_client`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
