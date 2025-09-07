-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2025 at 02:34 PM
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
-- Database: `buildeasedata`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `addressID` int(11) NOT NULL,
  `street` varchar(20) NOT NULL,
  `city` varchar(20) NOT NULL,
  `state` varchar(20) DEFAULT NULL,
  `postalCode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`addressID`, `street`, `city`, `state`, `postalCode`) VALUES
(7, 'a', 's', 'd', 'fd'),
(8, 'a', 's', 'd', 'fd'),
(9, 'strret10', 'batroun', '788ll', '400'),
(10, '5t', 'g', '5t', '5t'),
(11, 'a', 's', 'd', 'fd'),
(12, 'a', 's', 'd', 'fd'),
(13, 'kai', 'bekaa', 'd3889', '000'),
(14, 'a', 's', 'd', 'fd'),
(15, 'Menjez', 'kobayat', 'er436', 'xxx'),
(16, 'a', 's', 'd', 'fd'),
(17, '', '', '', ''),
(18, 'a', 's', 'd', 'fd'),
(19, 'zouke', 'kobayate', '5thdiag', '222222264'),
(20, 'kobayat', 'katlabeh', 'kobayat', '2222'),
(21, 'kobayat', 'katlabeh', 'kobayat', '2222'),
(22, 'a', 's', 'd', 'fd'),
(23, 'a', 's', 'd', 'fd'),
(24, 'a', 's', 'd', 'fd'),
(25, 'Andaket', 'Akkar', 'North', '111'),
(26, 'andaket', 'akkar', 'north', '111'),
(27, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(28, 'andaket', 'akkar', 'north', '111'),
(29, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(32, '78 avenue', 'Sidon', 'Lebanon', '9012'),
(33, '32 Avenue', 'Tyre', 'Lebanon', '3456'),
(34, '67  Road', 'Byblos', 'Lebanon', '7890'),
(35, '23 Street', 'Baalbek', 'Lebanon', '2345'),
(36, '89 Street', 'Zahle', 'Lebanon', '6789'),
(37, '14Street', 'Jounieh', 'Lebanon', '0123'),
(38, '55 Road', 'Nabatieh', 'Lebanon', '4567'),
(39, '22 Avenue', 'Batroun', 'Lebanon', '8901'),
(40, '12 Street', 'Beirut', 'Lebanon', '1234'),
(41, '45  Road', 'Tripoli', 'Lebanon', '5678'),
(42, '78 avenue', 'Sidon', 'Lebanon', '9012'),
(43, '32 Avenue', 'Tyre', 'Lebanon', '3456'),
(44, '67  Road', 'Byblos', 'Lebanon', '7890'),
(45, '23 Street', 'Baalbek', 'Lebanon', '2345'),
(46, '89 Street', 'Zahle', 'Lebanon', '6789'),
(47, '14Street', 'Jounieh', 'Lebanon', '0123'),
(48, '55 Road', 'Nabatieh', 'Lebanon', '4567'),
(49, '22 Avenue', 'Batroun', 'Lebanon', '8901'),
(50, '12 Street', 'Beirut', 'Lebanon', '1234'),
(51, '45  Road', 'Tripoli', 'Lebanon', '5678'),
(52, '78 avenue', 'Sidon', 'Lebanon', '9012'),
(53, '32 Avenue', 'Tyre', 'Lebanon', '3456'),
(54, '67  Road', 'Byblos', 'Lebanon', '7890'),
(55, '23 Street', 'Baalbek', 'Lebanon', '2345'),
(56, '89 Street', 'Zahle', 'Lebanon', '6789'),
(57, '14Street', 'Jounieh', 'Lebanon', '0123'),
(58, '55 Road', 'Nabatieh', 'Lebanon', '4567'),
(70, 'andaket', 'akkar', 'north', '111'),
(71, 'beirut', 'hadath', 'beirut', '5420'),
(72, 'andaket', 'akkar', 'north', '111'),
(73, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(74, 'andaket', 'akkar', 'north', '111'),
(75, 'halba', 'akkar', 'north', '759h'),
(76, 'katlabeh', 'kobayat', 'akkar', '79'),
(77, 'zouk', 'kobayat', 'Akkar', '7t'),
(78, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(79, 'andaket', 'akkar', 'north', '111'),
(80, '2b', 'Rmeich', 'South', '777'),
(81, 'Andaket', 'Akkar', 'North', '111'),
(82, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(83, 'a', 's', 'd', 'fd'),
(84, 'andaket', 'akkar', 'north', '111'),
(85, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(86, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(87, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(88, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(89, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(90, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(91, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(92, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(93, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(94, 'andaket', 'akkar', 'north', '111'),
(95, 'katlabeh', 'kobayat', 'akkar', '29eu'),
(96, 'andaket', 'akkar', 'north', '111'),
(97, 'andaket', 'akkar', 'north', '111'),
(98, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(99, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(100, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(101, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(102, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(103, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(104, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(105, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(106, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(107, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(108, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(109, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(110, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(111, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(112, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(113, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(114, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(115, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(116, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(117, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(118, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(119, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(120, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(121, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(122, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(123, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(124, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(125, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(126, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(127, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(128, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(129, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(130, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(131, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(132, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(133, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(134, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(135, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(136, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(137, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(138, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(139, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(140, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(141, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(142, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(143, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(144, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(145, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(146, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(147, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(148, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(149, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(150, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(151, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(152, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(153, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(154, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(155, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(156, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(157, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(158, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(159, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(160, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(161, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(162, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(163, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(164, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(165, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(166, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(167, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(168, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(169, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(170, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(171, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(172, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(173, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(174, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(175, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(176, '123 Main St', 'Beirut', 'Lebanon', '1001'),
(177, '456 Oak Ave', 'Tripoli', 'North Lebanon', '2002'),
(178, '789 Cedar Rd', 'Sidon', 'South Lebanon', '3003'),
(179, '101 Pine Ln', 'Byblos', 'Mount Lebanon', '4004'),
(180, '202 Maple Dr', 'Zahle', 'Bekaa', '5005'),
(181, '303 Elm St', 'Tyre', 'South Lebanon', '6006'),
(182, 'andaket', 'akkar', 'north', '111'),
(183, 'katlabeh', 'kobayat', 'akkar', '29eu');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `fullName` varchar(40) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fullName`, `email`, `password`) VALUES
(12, 'pascal tohme', 'pascaltohme@admin.buildEase', '$2y$10$U2yKCPe95WJVAW4h0HcYs.Kpe7fu0z8OJZD11OdDpl0HlnVHdL70q'),
(13, 'clarita antoun', 'claritaantoun123@admin.buildEase', '$2y$10$U2yKCPe95WJVAW4h0HcYs.Kpe7fu0z8OJZD11OdDpl0HlnVHdL70q'),
(14, 'John Doe', 'johndoe1@admin.buildEase', '$2y$10$Fy6BevdTtn2de.eIYLLKZO9DIgNQUs4DanTqr.L9W2MHrIX//aIS6'),
(15, 'Jane Smith', 'janesmith2@admin.buildEase', '$2y$10$Fy6BevdTtn2de.eIYLLKZO9DIgNQUs4DanTqr.L9W2MHrIX//aIS6'),
(16, 'Mike Johnson', 'mikejohnson3@admin.buildEase', '$2y$10$Fy6BevdTtn2de.eIYLLKZO9DIgNQUs4DanTqr.L9W2MHrIX//aIS6'),
(17, 'Emily Davis', 'emilydavis4@admin.buildEase', '$2y$10$Fy6BevdTtn2de.eIYLLKZO9DIgNQUs4DanTqr.L9W2MHrIX//aIS6'),
(18, 'Chris Brown', 'chrisbrown5@admin.buildEase', '$2y$10$Fy6BevdTtn2de.eIYLLKZO9DIgNQUs4DanTqr.L9W2MHrIX//aIS6'),
(19, 'Sophie Turner', 'sophieturner6@admin.buildEase', '$2y$10$Fy6BevdTtn2de.eIYLLKZO9DIgNQUs4DanTqr.L9W2MHrIX//aIS6'),
(20, 'Daniel Green', 'danielgreen7@admin.buildEase', '$2y$10$Fy6BevdTtn2de.eIYLLKZO9DIgNQUs4DanTqr.L9W2MHrIX//aIS6'),
(21, 'Nina Patel', 'ninapatel8@admin.buildEase', '$2y$10$Fy6BevdTtn2de.eIYLLKZO9DIgNQUs4DanTqr.L9W2MHrIX//aIS6');

-- --------------------------------------------------------

--
-- Table structure for table `change_requests`
--

CREATE TABLE `change_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_role` enum('professional','homeowner','contractor') NOT NULL,
  `request_type` enum('email','password') NOT NULL,
  `new_value` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `change_requests`
--

INSERT INTO `change_requests` (`id`, `user_id`, `user_role`, `request_type`, `new_value`, `status`, `created_at`) VALUES
(25, 11, 'homeowner', 'email', 'MariaAbdo1772@homeowner.buildEase', 'approved', '2025-04-16 07:00:31'),
(26, 11, 'homeowner', 'password', '$2y$10$4LH3MFs9t72nw4tztjgpxOpakuZXjz7y2ODqn8YcecKf0u7CjacR2', 'approved', '2025-04-16 07:00:53'),
(27, 11, 'homeowner', 'email', 'MariaAbdo12@homeowner.buildEase', 'approved', '2025-04-16 07:36:07'),
(28, 11, 'homeowner', 'email', 'MariaAbdo12@homeowner.buildEase', 'rejected', '2025-04-16 08:17:21'),
(29, 11, 'homeowner', 'password', '$2y$10$K0spJlmgYVRQMbuybU8nN./GEmyk3Ch7.JSjbQL2/a9hSDH9awUky', 'approved', '2025-04-16 08:17:31'),
(30, 39, 'professional', 'email', 'ChristinaKaram300@professional.buildEase', 'rejected', '2025-04-17 05:53:22'),
(31, 11, 'homeowner', 'password', '$2y$10$OB6G2mZ3FV3n/tZmErqIn.kATuEtUgb5eaPCi384BmzUsQ7w8oC0i', 'approved', '2025-04-17 08:39:55'),
(32, 11, 'homeowner', 'email', 'MariaAbdo1200@homeowner.buildEase', 'approved', '2025-04-18 06:54:38'),
(33, 8, 'contractor', 'email', 'ClaraTannous3499@contractor.buildEase', 'approved', '2025-04-18 06:57:12'),
(34, 7, 'contractor', 'email', 'EtienKaram3341@contractor.buildEase', 'pending', '2025-04-25 18:10:24'),
(35, 7, 'contractor', 'email', 'EtienKaram30000@contractor.buildEase', 'pending', '2025-04-30 18:22:27'),
(36, 37, 'professional', 'password', '$2y$10$ES26N6JEkce9poO63KoVtOZcvVr4hagAdOC4Mj/AFUBx9ZIsysFsC', 'pending', '2025-05-09 11:01:23'),
(37, 37, 'professional', 'email', 'ElisaZmeter26666666661@professional.buildEase', 'pending', '2025-05-09 11:03:00'),
(38, 37, 'professional', 'email', 'ElisaZmeter2467800001@professional.buildEase', 'pending', '2025-05-09 11:06:13'),
(39, 37, 'professional', 'email', 'ElisaZmeter29948494831@professional.buildEase', 'pending', '2025-05-09 11:10:50'),
(40, 37, 'professional', 'email', 'ElisaZmeter29948494831@professional.buildEase', 'approved', '2025-05-09 11:11:09');

-- --------------------------------------------------------

--
-- Table structure for table `contract`
--

CREATE TABLE `contract` (
  `contractID` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `salary` double NOT NULL,
  `status` varchar(20) NOT NULL,
  `details` varchar(800) NOT NULL,
  `contractorID` int(11) NOT NULL,
  `signature` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contract`
--

INSERT INTO `contract` (`contractID`, `startDate`, `endDate`, `salary`, `status`, `details`, `contractorID`, `signature`) VALUES
(2, '2025-04-01', '2025-04-16', 100, 'active', '..........', 7, 'signatures/contract_2.png'),
(3, '2025-04-02', '2025-05-08', 700, 'deactivated', '.............', 6, 'signatures/contract_3.png'),
(4, '2025-04-01', '2025-05-10', 66666, 'active', '.....', 9, 'signatures/contract_4.png'),
(5, '2025-03-31', '2025-05-10', 1000, 'active', '.......', 13, ''),
(6, '2025-05-16', '2025-08-15', 500, 'active', '....', 7, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contractor`
--

CREATE TABLE `contractor` (
  `id` int(11) NOT NULL,
  `fullName` varchar(40) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `cvID` int(11) DEFAULT NULL,
  `addressID` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contractor`
--

INSERT INTO `contractor` (`id`, `fullName`, `email`, `password`, `phoneNumber`, `cvID`, `addressID`, `status`) VALUES
(6, 'rana abdo', 'ranaabdo99@contractor.buildEase', '$2y$10$5t1InLKkuzHBYzF/g1vtGOyYGGnG0ABUdX.UnCJIkQ7sFAJd6axoO', '00/111/111', 13, 13, 'accepted'),
(7, 'Etien Karam', 'EtienKaram31@contractor.buildEase', '$2y$10$VqyE.J1FpEXK0qjt7joHwuYlgouaU3gg0darPn3ByUK9s.hjdGCd6', '00/111/111', 19, 22, 'accepted'),
(8, 'Clara Tannous', 'ClaraTannous3499@contractor.buildEase', '$2y$10$Bt3/DX2Aq2lH4D005V1GDufdAwbhSmayGCapBWRfeWWK7lBLAFaay', '81/234 567', 36, 75, 'accepted'),
(9, 'Antoinette Moussa', 'AntoinetteMoussa908@contractor.buildEase', '$2y$10$yafZLkY9lqL/fwm7gU1u6.Eq/7ugNrk5KOa6cS.NcemG.DMM4cI/O', '76/893 467', 37, 76, 'accepted'),
(10, 'Michel Hanna', 'MichelHanna67@contractor.buildEase', '$2y$10$fKnEQ33s8LGQEcjSuNr0Qen6BfmPmwbU4RDHGVUzoFMIOtnFfxz32', '03/987 567', 38, 77, 'accepted'),
(11, 'Youssef Abboud', 'YoussefAbboud9968@contractor.buildEase', '$2y$10$4mpJ4ptlDm0HividpEoO8OxY/HQgYggjbrFdm0jD4Jk0s3STc8Xvm', '01/234 577', 39, 78, 'accepted'),
(12, 'Katrina Salloum', 'KatrinaSalloum000@contractor.buildEase', '$2y$10$37eb0OWTDwnzbo.n2uVG4.Kayy7JjvN4ojbNjcYhrfYlUCuVHmaQy', '00/111/111', 40, 79, 'accepted'),
(13, 'Daniella Abboud', 'DaniellaAbboud3400@contractor.buildEase', '$2y$10$Q6dXeQVssDABRPPBO30lTuSclqtfI/2qEzbpOfOCIknQhcuR5WFmC', '06/675 999', 41, 80, 'rejected'),
(14, 'Heaven Daher', 'HeavenDaher3400@contractor.buildEase', '$2y$10$jX4nXA39rbAUuB5fIyt39.tcAWXjJ9RdWpuLxY6OkkAlFOV8guJ96', '76/893 467', 42, 81, 'accepted'),
(16, 'Samaher Hanna', 'SamaherHanna31@contractor.buildEase', '$2y$10$gU7RtRBaiUGi6EyN6/LJSO6wv.k91tJ5ZwxlA4bSUObUSMJfJCz6.', '06/675 999', 44, 84, 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `cont_pro_feedback`
--

CREATE TABLE `cont_pro_feedback` (
  `feedbackID` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(800) DEFAULT NULL,
  `date` date DEFAULT current_timestamp(),
  `contractorID` int(11) DEFAULT NULL,
  `professionalID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cont_pro_feedback`
--

INSERT INTO `cont_pro_feedback` (`feedbackID`, `rating`, `comment`, `date`, `contractorID`, `professionalID`) VALUES
(1, 2, 'not good', NULL, 7, 36),
(4, 5, 'excellent', NULL, 13, 37),
(5, 5, 'excellent', NULL, 12, 37),
(6, 4, 'very good', NULL, 8, 37),
(7, 3, 'good', NULL, 6, 38),
(8, 4, 'very good', NULL, 7, 38),
(9, 2, 'bad', NULL, 11, 38),
(12, 1, 'bad', '2025-05-06', 7, 45),
(13, 5, 'excellent', '2025-05-06', 7, 43),
(15, 5, 'amazing', '2025-05-15', 7, 37);

-- --------------------------------------------------------

--
-- Table structure for table `creates`
--

CREATE TABLE `creates` (
  `projectID` int(11) NOT NULL,
  `homeOwnerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `creates`
--

INSERT INTO `creates` (`projectID`, `homeOwnerID`) VALUES
(3, 11),
(4, 11),
(5, 11),
(6, 11),
(7, 11),
(8, 11),
(9, 11),
(10, 11),
(11, 11),
(12, 11),
(13, 11),
(14, 11),
(15, 11);

-- --------------------------------------------------------

--
-- Table structure for table `curriculum_vitae`
--

CREATE TABLE `curriculum_vitae` (
  `cvID` int(11) NOT NULL,
  `educations` varchar(800) DEFAULT NULL,
  `experiences` varchar(800) DEFAULT NULL,
  `skills` varchar(800) DEFAULT NULL,
  `languages` varchar(200) DEFAULT NULL,
  `certifications` varchar(800) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculum_vitae`
--

INSERT INTO `curriculum_vitae` (`cvID`, `educations`, `experiences`, `skills`, `languages`, `certifications`) VALUES
(13, 'cis ', '5 years', 'team Work', 'Arabic,Spanish', 'BMIS'),
(14, '...', '.....', '.......', 'English,Arabic,French', '.....'),
(15, '........', '..........', '..........', 'English,Arabic,French,Spanish,German,Chinese', 'ggggggg'),
(16, 'none', 'none', '', 'none', 'none'),
(17, 'Bachelor of Science in Civil Engineering\r\nUniversity of Engineering & Technology\r\nGraduated: 2017\r\n\r\n', 'Civil Engineer\r\nXYZ Builders Ltd. | Jan 2020 – Present\r\nCity, Country\r\n\r\nDesigned and supervised the structural layout of 20+ single-family homes and duplexes.\r\n\r\nCoordinated with land surveyors, architects, and MEP engineers for efficient site execution.\r\n\r\nPerformed load calculations and selected appropriate building materials for each project.\r\n\r\nReduced construction delays by 15% through better coordination with suppliers and contractors.', 'Soil  Foundation Analysis.\r\n\r\nSite Layout & Planning.\r\n\r\nBuilding Codes & Safety Standards.\r\n\r\nTeam Coordination & Reporting.\r\n\r\n', 'English,Arabic', 'AutoCAD Certification.\r\nOSHA Safety Certification.\r\nproject Management (Optional but great).\r\n\r\n'),
(18, 'Bachelor of Science in Civil Engineering\r\nUniversity of Engineering & Technology\r\nGraduated: 2017\r\n\r\n', 'Junior Civil Engineer\r\nBuildWell Constructions | Jul 2017 – Dec 2019\r\nCity, Country\r\n\r\nAssisted in creating structural plans using AutoCAD and Revit.\r\n\r\nPerformed site inspections and submitted quality assurance reports.\r\n\r\nCalculated BOQs and prepared daily progress reports for project managers.\r\n\r\n', 'Structural Design & Analysis.\r\nConstruction Supervision.\r\nAutoCAD / Revit / Civil 3D.\r\nQuantity & Material Estimation.', 'French,Spanish', 'Bachelor of Science in Civil Engineering\r\nUniversity of Engineering & Technology\r\nGraduated: 2017\r\n\r\n'),
(19, '......', '.........', '............', 'English', '...........'),
(20, 'High School Diploma / Vocational Training\r\nConstruction Skills Institute (or similar)\r\nGraduated: 2016', '\r\nHeavy Equipment Operator (Trainee > Operator)\r\nBuildFast Earthworks | Jan 2017 – Mar 2020\r\nCity, Country\r\n\r\nStarted as an apprentice, promoted after 1 year due to performance.\r\n\r\nGained experience in soil grading, material loading, and safe machine operation.\r\n\r\nFollowed detailed instructions from foremen and engineers to meet daily goals.', 'Earthmoving & Backfilling.\r\nEquipment Maintenance & Safety Checks.\r\nBlueprint & Marking Reading.\r\nTeam Coordination.\r\nHealth & Safety Compliance (OSHA or equivalent).', 'Italian,Chinese,Japanese', 'Excavator Operator License (Valid / Issuer Name).'),
(21, 'BSc Geomatics', '5 years topographic surveys', 'GPS operation, CAD mapping', 'English, Spanish', 'Licensed Surveyor'),
(22, 'MEng Civil Engineering', 'Bridge construction projects', 'Structural analysis, AutoCAD', 'English', 'PE License'),
(23, 'Diploma in Heavy Machinery', '10 excavation projects', 'Excavator operation, Soil analysis', 'English', 'OSHA Certified'),
(24, 'Construction Management', 'High-rise foundations', 'Concrete mixing, Rebar installation', 'English, Arabic', 'Safety Supervisor'),
(25, 'Masonry Apprenticeship', 'Historical restoration projects', 'Bricklaying, Stone carving', 'English', 'Heritage Craftsman'),
(26, 'Plastering Vocational', 'Commercial plastering projects', 'Skimming, Drywall finishing', 'English', 'Plasterers Guild'),
(27, 'Electrical Engineering Degree', 'Industrial installations', 'Circuit design, PLC programming', 'English, German', 'IEEE Member'),
(28, 'Electrician Certification', 'Residential wiring projects', 'Wiring, Troubleshooting', 'English', 'NECA Certified'),
(29, 'Plumbing Trade School', 'Commercial plumbing systems', 'Pipe fitting, Boiler installation', 'English', 'Master Plumber'),
(30, 'Tile Installation Course', 'Luxury bathroom installations', 'Pattern layout, Grouting', 'English, Italian', 'CTEF Certified'),
(31, 'interior design', '10 years', 'team work\r\nhospitality', 'English,Arabic,French,Spanish', 'Bachelor in interior design'),
(32, 'none', '15 years', 'Hard work\r\nTeam work', 'Arabic', 'none'),
(33, '....', '2 years', 'Team Work', 'Italian', 'Bachelor in Civil Engineering'),
(34, '....', '5 years', 'good quality services\r\nand at time', 'English,Arabic,French', 'Bachelor in Electrical Engineer \r\nMaster in Power Engineering'),
(35, '...', '10 years', 'Hard work with good quality', 'English,Arabic,French', 'Licensed plumbing Engineer'),
(36, 'Diploma in Construction Management – XYZ Technical College', 'Senior Contractor, Site Manager in Antoun\'s company in Australia', 'Project Management\r\n\r\nBudget Estimation & Cost Control', 'English,Arabic,French,Spanish,German', 'Diploma in Construction Management – XYZ Technical College'),
(37, 'Certified Building Contractor – Local Authority License #123456', 'Managed 25+ residential projects (single-family homes and duplexes) from site excavation to finishing.', 'Material Procurement\r\nQuality Control\r\nScheduling Tools (e.g., MS Project, Buildertrend) \r\n\r\n', 'English,Arabic,French,Spanish', 'Certified Building Contractor – Local Authority License #123456'),
(38, 'Certified Building Contractor – Local Authority License #123456\r\nOSHA Safety Certification.', 'Coordinated 10+ subcontractors across plumbing, electrical, tiling, and carpentry services.\r\nReduced average project delivery time by 12% through optimized scheduling and materials planning.\r\n\r\n', 'Project Management.\r\nBudget Estimation & Cost Control.', 'Arabic,French,Spanish,German', 'Certified Building Contractor – Local Authority License #123456\r\nOSHA Safety Certification.'),
(39, 'Certified Building Contractor – Local Authority License #123456.\r\nOSHA Safety Certification.\r\nFirst Aid Training.', 'Coordinated 10+ subcontractors across plumbing, electrical, tiling, and carpentry services.', 'Reading & Interpreting Blueprints.\r\nSite Safety & Compliance.\r\nMaterial Procurement.\r\nQuality Control.\r\nScheduling Tools.', 'English,Arabic,French', 'Certified Building Contractor – Local Authority License #123456.\r\nOSHA Safety Certification.\r\nFirst Aid Training.'),
(40, 'Diploma in Construction Management (or related field)\r\nTechnical Institute of Construction.\r\nGraduated: 2015.', 'General Contractor\r\nModern Homes Construction Ltd. | Jan 2019 – Present\r\nCity, Country\r\n\r\nManaged 15+ house construction projects ranging from 2-bedroom homes to duplexes.\r\n\r\nHired and supervised professionals (engineers, plumbers, electricians, etc.) for each phase.\r\n\r\nMonitored project timelines and costs to ensure delivery within agreed limits.\r\n\r\nRegularly updated homeowners with progress reports and resolved on-site issues.\r\n\r\nEnsured compliance with local building laws and safety regulations.', 'End-to-End Project Management\r\n\r\nBudgeting & Cost Control\r\n\r\nContractor & Subcontractor Coordination\r\n\r\nConstruction Schedulinga', 'Arabic,French,German,Chinese', 'Contractor License / Registration (if applicable).'),
(41, 'Bachelor\'s Degree in Civil Engineering / Construction Technology\r\nBALAMAND\r\nGraduated in 2020\r\n', 'Site Manager / Assistant Contractor\r\nEcoBuild Projects | Jun 2015 – Dec 2018\r\nBeirut, Lebanon\r\n\r\nOversaw daily operations on residential construction sites.\r\n\r\nCoordinated excavation, masonry, plumbing, and carpentry teams.\r\n\r\nWorked closely with engineers to implement building plans effectively.\r\n\r\nOrdered and tracked material usage to reduce waste and save costs.\r\n\r\n', 'Contractor & Subcontractor Coordination.\r\nConstruction Scheduling.\r\nKnowledge of Building Codes & Permits.\r\nTeam Supervision & Labor Management.\r\nMaterial Procurement & Logistics.', 'English,Arabic,French', 'Health & Safety Training Certification (OSHA, HSE, etc.).\r\nProject Management Certification (optional).'),
(42, 'Diploma in Construction Management (or related field)\r\nTechnical Institute of Construction\r\nGraduated: 2015', 'Site Manager / Assistant Contractor\r\nEcoBuild Projects | Jun 2015 – Dec 2018\r\nKobayat . Lebanon\r\n\r\nOversaw daily operations on residential construction sites.\r\n\r\nCoordinated excavation, masonry, plumbing, and carpentry teams.\r\n\r\nWorked closely with engineers to implement building plans effectively.\r\n\r\nOrdered and tracked material usage to reduce waste and save costs.\r\n\r\n', 'Knowledge of Building Codes & Permits.\r\n\r\nTeam Supervision & Labor Management.\r\n\r\nMaterial Procurement & Logistics.\r\nClient Communication & Reporting.\r\nHealth & Safety Compliance.', 'English,Arabic,Spanish', 'Contractor License / Registration (if applicable).\r\nHealth & Safety Training Certification (OSHA, HSE, etc.).'),
(43, 'Diploma in Construction Management (or related field)\r\nTechnical Institute of Construction\r\nGraduated: 2015', 'Site Manager / Assistant Contractor\r\nEcoBuild Projects | Jun 2015 – Dec 2018\r\nKobayat.Lebanon\r\n\r\nOversaw daily operations on residential construction sites.\r\n\r\nCoordinated excavation, masonry, plumbing, and carpentry teams.\r\n\r\nWorked closely with engineers to implement building plans effectively.\r\n\r\nOrdered and tracked material usage to reduce waste and save costs.', 'End-to-End Project Management.\r\nBudgeting & Cost Control.\r\nKnowledge of Building Codes & Permits.\r\nTeam Supervision & Labor Management.\r\nMaterial Procurement & Logistics.', 'English,Arabic,Spanish', 'Health & Safety Training Certification (OSHA, HSE, etc.).\r\nProject Management Certification (optional).'),
(44, 'Civil &mechanical engineer ', '7 years in ABC company for construction', 'team work', 'English,Arabic,French', 'Civil &mechanical engineer AT Balamand'),
(46, 'B.Sc in Civil Engineering', '7 years in civil projects', 'Structural Design, SAP2000', 'English', 'PE Licensed'),
(47, 'Diploma in Excavation', '10 years heavy machinery', 'Excavation, Bulldozer operation', 'English', 'OSHA Certified'),
(48, 'B.Sc in Structural Engineering', '6 years in foundation design', 'AutoCAD, Revit', 'English, German', 'Certified Structural Engineer'),
(49, 'Diploma in Masonry', '15 years masonry works', 'Bricklaying, Concrete finishing', 'English, Spanish', 'Certified Mason'),
(50, 'Certificate in Plastering', '8 years plastering walls', 'Finishing techniques', 'English', 'Certified Plasterer'),
(51, 'B.Sc in Electrical Engineering', '5 years electrical designs', 'Circuit Design, AutoCAD', 'English', 'Licensed Electric Engineer'),
(52, 'Certificate in Electrical Installations', '4 years in wiring', 'House Wiring', 'English', 'Certified Electrician'),
(53, 'Diploma in Plumbing', '6 years plumbing works', 'Pipe fittings', 'English', 'Certified Plumber'),
(54, 'Certificate in Plumbing Installations', '3 years pipe installations', 'Water Systems', 'English', 'Plumber Certificate'),
(55, 'Diploma in Carpentry', '12 years carpentry works', 'Framework building', 'English', 'Certified Carpenter'),
(56, 'Certificate in Tiling', '4 years tiling works', 'Tile installation', 'English', 'Certified Tiler'),
(57, 'B.A in Interior Design', '6 years home designs', 'Sketchup, 3DS Max', 'English, French', 'Interior Design Certificate'),
(58, 'Certificate in Painting', '7 years painting houses', 'Painting, Finishing', 'English', 'Certified Painter'),
(59, 'civil engineering', 'ppppp', 'ppppp', 'English,Arabic,French,Spanish,German,Italian,Chinese,Japanese', 'liu'),
(60, 'BS in Geomatics', '7 years in land surveying', 'GPS, CAD Mapping, Topography', 'English, Arabic', 'Licensed Surveyor'),
(61, 'MS in Civil Engineering', '10 years in boundary mapping', 'GIS, Total Station, Levelling', 'English, French', 'Certified GIS Specialist'),
(62, 'Diploma in Land Surveying', '6 years field experience', 'GNSS, UAV Mapping, AutoCAD', 'Arabic, English', 'OSHA Certified'),
(63, 'BS in Surveying Technology', '8 years in infrastructure projects', '3D Scanning, Drone Surveys', 'English, Spanish', 'Professional Land Surveyor License'),
(64, 'Diploma in Geospatial Science', '5 years in urban planning', 'Trimble Equipment, Cogo, Field Data Collection', 'Arabic, French', 'Certified Mapping Technician'),
(65, 'Certificate in Surveying', '4 years in residential projects', 'Boundary Surveys, ALTA Standards', 'English, Italian', 'Field Survey Technician'),
(66, 'BS in Civil Engineering', '7 years in infrastructure projects', 'Structural Analysis, AutoCAD, Project Management', 'English, Arabic', 'Licensed Civil Engineer'),
(67, 'MS in Structural Engineering', '10 years in bridge and building design', 'SAP2000, ETABS, Revit', 'English, French', 'PE License Holder'),
(68, 'Diploma in Civil Engineering', '6 years in residential construction', 'AutoCAD, Quantity Surveying, BOQ Preparation', 'Arabic, English', 'OSHA Certified'),
(69, 'BS in Civil Engineering', '8 years in road and infrastructure projects', 'Highway Design, Drainage Systems, Site Supervision', 'English, Spanish', 'Professional Land Surveyor License'),
(70, 'MEng in Structural Engineering', '5 years in high-rise buildings', 'ETABS, SAP2000, STAAD.Pro', 'Arabic, French', 'Chartered Engineer'),
(71, 'Certificate in Foundation Engineering', '4 years in foundation and retaining wall design', 'Soil Mechanics, Retaining Walls, Deep Foundations', 'English, Italian', 'Foundation Engineering Certification'),
(72, 'Diploma in Heavy Machinery Operation', '10 years operating excavators on construction sites', 'Excavator operation, Soil analysis, Site preparation', 'English, Arabic', 'OSHA Certified'),
(73, 'Certificate in Earthmoving Operations', '8 years in excavation and land development', 'Bulldozer & Excavator Operation, Topographic Survey Assistance', 'Arabic, English', 'Excavator Operator License'),
(74, 'Heavy Equipment Training Certificate', '12 years of experience in large-scale earthworks', 'Grading, Trenching, Utility Installation Prep', 'English, Spanish', 'Equipment Safety Certification'),
(75, 'Vocational Certificate in Construction Equipment', '9 years working on infrastructure and road projects', 'Loader Operation, Material Handling, Site Clearance', 'English, French', 'Construction Equipment Safety Certificate'),
(76, 'Heavy Machinery Operator Certificate', '7 years in demolition and rock breaking', 'Rock Breaking, Demolition, Concrete Crushing', 'Arabic, English', 'Demolition Safety Certification'),
(77, 'Diploma in Civil Engineering Technology', '6 years in utility installation and trenching', 'Trenching, Pipe Bedding, Backfilling', 'English, Italian', 'Utility Construction Certification'),
(78, 'Diploma in Civil Engineering', '8 years in foundation and column construction', 'Reinforced Concrete, Footing Design, Steel Fixing', 'English, Arabic', 'OSHA Certified'),
(79, 'Certificate in Reinforced Concrete', '6 years in column and beam casting', 'Steel Reinforcement, Formwork, Concrete Pouring', 'Arabic, English', 'Concrete Technology Certification'),
(80, 'BS in Structural Engineering', '10 years designing and building high-rise foundations', 'Column Design, Load Calculations, Rebar Installation', 'Arabic, French', 'PE License Holder'),
(81, 'Heavy Equipment Training Certificate', '12 years working on large-scale concrete projects', 'Formwork Setup, Concrete Mixing, Curing Techniques', 'English, Spanish', 'Concrete Field Testing Certification'),
(82, 'Diploma in Building Technology', '9 years in residential and industrial slab and column work', 'Slab Casting, Beam Formwork, Steel Detailing', 'Arabic, English', 'Building Technology Certification'),
(83, 'Certificate in Foundation Engineering', '7 years in footing and pile construction', 'Pile Driving, Spread Footing, Mat Foundations', 'English, Italian', 'Foundation Engineering Certification'),
(84, 'Certificate in Masonry & Bricklaying', '15 years of experience in residential and commercial masonry', 'Bricklaying, Concrete Block Installation, Stone Cutting', 'English, Arabic', 'Certified Mason - National Builders Association'),
(85, 'Diploma in Construction Technology', '12 years working on villa and apartment buildings', 'Block Laying, Mortar Mixing, Wall Reinforcement Techniques', 'Arabic, English', 'OSHA Certified Mason'),
(86, 'Vocational Certificate in Masonry', '10 years handling stone and concrete block work', 'Stone Masonry, Retaining Walls, Arch Construction', 'English, Spanish', 'Masonry Certification – Local Authority'),
(87, 'Heavy Equipment Training Certificate', '9 years in large-scale block laying', 'Concrete Block Work, Rebar Installation, Wall Finishing', 'English, French', 'Masonry Apprentice License'),
(88, 'Apprenticeship in Historical Masonry', '8 years restoring heritage buildings', 'Historical Stone Repair, Lime Mortar Application', 'Arabic, English', 'Heritage Masonry Guild Member'),
(89, 'Masonry Apprenticeship', '11 years building retaining walls and foundations', 'Retaining Wall Masonry, Block Laying, Joint Finishing', 'English, Italian', 'State Certified Mason'),
(90, 'Certificate in Plastering & Skimming', '10 years experience in interior and exterior plastering', 'Skimming, Drywall Finishing, Render Application', 'English, Arabic', 'Certified Plasterer - National Builders Association'),
(91, 'Diploma in Construction Technology', '8 years working on residential and commercial buildings', 'Plaster Mixing, Wall Preparation, Trowel Techniques', 'Arabic, English', 'OSHA Certified Plasterer'),
(92, 'Vocational Certificate in Plastering', '12 years of experience in high-end finishes', 'Decorative Plastering, Cornice Installation, Textured Walls', 'Arabic, French', 'Guild Qualified Plasterer'),
(93, 'Heavy Equipment Training Certificate', '9 years in large-scale drywall projects', 'Drywall Installation, Joint Taping, Sanding & Finishing', 'English, Spanish', 'Drywall Finishing Certification'),
(94, 'Apprenticeship in Traditional Plastering', '8 years restoring heritage buildings', 'Lime Plaster Application, Historic Wall Repairs', 'Arabic, English', 'Heritage Craftsman - Plastering'),
(95, 'Masonry Apprenticeship', '7 years applying cement and gypsum plasters', 'Cement Rendering, Gypsum Boarding, Stucco Work', 'English, Italian', 'State Certified Plasterer'),
(96, 'B.Sc in Electrical Engineering', '8 years designing electrical systems for residential and commercial buildings', 'Circuit Design, AutoCAD, PLC Programming', 'English, Arabic', 'IEEE Member'),
(97, 'Certificate in Electrical Installations', '6 years installing electrical wiring in new constructions', 'House Wiring, Panel Installation, Troubleshooting', 'Arabic, English', 'NECA Certified'),
(98, 'Diploma in Electrical Technology', '7 years working on industrial and commercial projects', 'Industrial Wiring, Control Panels, Testing & Commissioning', 'English, Spanish', 'Licensed Electric Engineer'),
(99, 'B.Sc in Electrical Engineering', '10 years managing large-scale electrical infrastructure', 'Power Distribution, Lighting Systems, Safety Protocols', 'English, French', 'Licensed Electrical Engineer'),
(100, 'Electrical Engineering Degree', '9 years troubleshooting and repairing complex systems', 'Fault Finding, Cable Testing, Electrical Maintenance', 'Arabic, English', 'OSHA Certified'),
(101, 'Certificate in Electrical Maintenance', '5 years in maintenance and upgrades for residential units', 'Panel Upgrades, Light Fixtures, Outlet Installation', 'English, Italian', 'Certified Electrician'),
(102, 'B.Sc in Electrical Engineering', '8 years designing and installing electrical systems for residential and commercial buildings', 'Circuit Design, AutoCAD, PLC Programming, Wiring Installation', 'English, Arabic', 'IEEE Member'),
(103, 'Certificate in Electrical Installations', '6 years installing electrical wiring in new constructions', 'House Wiring, Panel Installation, Troubleshooting', 'Arabic, English', 'NECA Certified'),
(104, 'Diploma in Electrical Technology', '7 years working on industrial and commercial wiring projects', 'Industrial Wiring, Control Panels, Testing & Commissioning', 'English, Spanish', 'Licensed Electric Engineer'),
(105, 'B.Sc in Electrical Engineering', '10 years managing large-scale electrical infrastructure', 'Power Distribution, Lighting Systems, Safety Protocols', 'English, French', 'Licensed Electrical Engineer'),
(106, 'Electrical Engineering Degree', '9 years troubleshooting and repairing complex systems', 'Fault Finding, Cable Testing, Electrical Maintenance', 'Arabic, English', 'OSHA Certified'),
(107, 'Certificate in Electrical Maintenance', '5 years in maintenance and upgrades for residential units', 'Panel Upgrades, Light Fixtures, Outlet Installation', 'English, Italian', 'Certified Electrician'),
(108, 'Diploma in Plumbing Technology', '8 years experience in residential and commercial plumbing', 'Pipe Installation, Water Systems, Leak Detection', 'English, Arabic', 'Master Plumber License'),
(109, 'Certificate in Residential Plumbing', '6 years installing and repairing home plumbing systems', 'Water Heater Install, Drain Cleaning, Fixture Replacement', 'Arabic, English', 'Licensed Residential Plumber'),
(110, 'Apprenticeship in Commercial Plumbing', '7 years working on large-scale plumbing projects', 'Commercial Piping, Backflow Testing, Pipefitting', 'English, Spanish', 'State Certified Plumber'),
(111, 'Vocational Certificate in Plumbing', '10 years fixing leaks and replacing fixtures', 'Drain Repair, Toilet & Faucet Replacement, Leak Detection', 'English, French', 'Certified Plumber - Local Authority'),
(112, 'Plumbing Trade School', '8 years working in high-rise buildings', 'High-rise Plumbing, Pressure System Installation', 'Arabic, English', 'OSHA Certified Plumber'),
(113, 'Certificate in Pipefitting', '5 years specializing in bathroom and kitchen installations', 'Toilet, Sink & Bathtub Installation, Pipe Soldering', 'English, Italian', 'Certified Installer - Plumbing Association'),
(114, 'Diploma in Plumbing Technology', '8 years experience in residential and commercial plumbing', 'Pipe Installation, Water Systems, Leak Detection', 'English, Arabic', 'Master Plumber License'),
(115, 'Certificate in Residential Plumbing', '6 years installing and repairing home plumbing systems', 'Water Heater Install, Drain Cleaning, Fixture Replacement', 'Arabic, English', 'Licensed Residential Plumber'),
(116, 'Apprenticeship in Commercial Plumbing', '7 years working on large-scale plumbing projects', 'Commercial Piping, Backflow Testing, Pipefitting', 'English, Spanish', 'State Certified Plumber'),
(117, 'Vocational Certificate in Plumbing', '10 years fixing leaks and replacing fixtures', 'Drain Repair, Toilet & Faucet Replacement, Leak Detection', 'English, French', 'Certified Plumber - Local Authority'),
(118, 'Plumbing Trade School', '8 years working in high-rise buildings', 'High-rise Plumbing, Pressure System Installation', 'Arabic, English', 'OSHA Certified Plumber'),
(119, 'Certificate in Pipefitting', '5 years specializing in bathroom and kitchen installations', 'Toilet, Sink & Bathtub Installation, Pipe Soldering', 'English, Italian', 'Certified Installer - Plumbing Association'),
(120, 'Diploma in Carpentry & Joinery', '8 years experience in residential and commercial carpentry', 'Wood Framing, Cabinet Installation, Wood Finishing', 'English, Arabic', 'Certified Carpenter - Local Authority'),
(121, 'Certificate in Cabinet Making', '6 years building and installing custom furniture', 'Furniture Assembly, Wood Cutting, Sanding & Finishing', 'Arabic, English', 'Certified Cabinet Maker'),
(122, 'B.Sc in Construction Management', '7 years working on large-scale wood projects', 'Wood Framing, Roof Truss Installation, Paneling', 'English, Spanish', 'OSHA Certified Carpenter'),
(123, 'Apprenticeship in Carpentry', '10 years of experience in residential construction', 'Floor Installation, Trim Work, Door Hanging', 'English, French', 'National Association of the Remodeling Industry'),
(124, 'Vocational Certificate in Carpentry', '9 years working on high-end home interiors', 'Custom Shelves, Staircases, Built-in Furniture', 'Arabic, English', 'Certified Finish Carpenter'),
(125, 'Certificate in Industrial Carpentry', '5 years working on commercial and industrial sites', 'Formwork, Scaffold Building, Concrete Support Frames', 'English, Italian', 'Construction Safety Certification'),
(126, 'Certificate in Tile Installation', '5 years installing tiles in bathrooms and kitchens', 'Tile Layout, Grouting, Cutting & Fitting Tiles', 'English, Arabic', 'CTEF Certified Tile Installer'),
(127, 'Vocational Certificate in Tiling', '6 years specializing in mosaic and luxury tiling', 'Pattern Layout, Tile Cutting, Waterproofing', 'Arabic, English', 'Certified Luxury Tile Installer'),
(128, 'Apprenticeship in Ceramic Tiling', '7 years working on residential and commercial projects', 'Ceramic & Porcelain Tiling, Wall & Floor Tiling', 'English, Spanish', 'Tile Council of North America Certification'),
(129, 'Diploma in Interior Tiling', '10 years experience in high-end home renovations', 'Tile Setting, Grouting, Sealing, Pattern Layout', 'English, French', 'Certified Tile Setter - Local Authority'),
(130, 'Certificate in Porcelain Tile Installation', '8 years working with luxury porcelain and ceramic', 'Large Format Tile Installation, Wet Area Tiling', 'English, Italian', 'Porcelain Tile Installation Certification'),
(131, 'Tile Installation Course', '5 years in natural stone and marble tiling', 'Marble Installation, Natural Stone Cutting, Polishing', 'English, Italian', 'Natural Stone Tile Certification'),
(132, 'B.A in Interior Design', '8 years experience in residential and commercial interior design', 'Space Planning, 3D Modeling, Material Selection', 'English, Arabic', 'Certified Interior Designer - CID'),
(133, 'Certificate in Interior Decorating', '6 years specializing in home interiors and renovations', 'Color Coordination, Furniture Layout, Mood Boards', 'Arabic, English', 'Certified Interior Decorator'),
(134, 'M.Des in Interior Architecture', '7 years working on luxury apartment and villa interiors', 'AutoCAD, SketchUp, Lighting Design', 'English, Spanish', 'NCIDQ Certified'),
(135, 'Diploma in Space Design', '10 years designing modern and minimalist spaces', 'Feng Shui Principles, Color Theory, CAD Drafting', 'English, French', 'Certified by IIDA'),
(136, 'Bachelor in Interior Design', '8 years in high-end residential projects', 'Custom Furniture Design, Lighting Plans, Textile Selection', 'Arabic, English', 'LEED Accredited Professional'),
(137, 'Certificate in Kitchen & Bath Design', '5 years specializing in kitchen and bathroom remodeling', 'Kitchen Layout, Tile Selection, Storage Optimization', 'English, Italian', 'Certified Kitchen & Bath Designer'),
(138, 'Certificate in Painting & Finishing', '8 years experience in residential and commercial painting', 'Interior & Exterior Painting, Wall Texturing, Spray Painting', 'English, Arabic', 'Certified Master Painter'),
(139, 'Vocational Certificate in Interior Painting', '6 years specializing in home painting and finishing', 'Wall Preparation, Roller & Brush Techniques, Color Matching', 'Arabic, English', 'Certified Residential Painter'),
(140, 'Apprenticeship in Decorative Painting', '7 years working on luxury homes and hotels', 'Faux Finishes, Stenciling, Accent Walls', 'English, Spanish', 'Decorative Painting Certification'),
(141, 'Diploma in Industrial Painting', '10 years experience in large-scale commercial projects', 'Spray Painting, Surface Priming, Sandblasting', 'English, French', 'Industrial Painting Certification'),
(142, 'Certificate in Fine Arts - Painting', '8 years restoring historical buildings and murals', 'Mural Painting, Fresco Restoration, Detail Work', 'Arabic, English', 'Heritage Painting Certification'),
(143, 'Certificate in Residential Painting', '5 years specializing in fast-track house painting', 'Quick Prep & Paint Jobs, Eco-Friendly Coatings', 'English, Italian', 'Green Building Painting Certification'),
(144, 'humidity isolation bs', '7 years of experience in ABC company', 'team work', 'English,Arabic,Spanish', 'humidity isolation bs'),
(145, '.', '..', '.', 'Arabic', '..');

-- --------------------------------------------------------

--
-- Table structure for table `homeowner`
--

CREATE TABLE `homeowner` (
  `id` int(11) NOT NULL,
  `fullName` varchar(40) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `addressID` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homeowner`
--

INSERT INTO `homeowner` (`id`, `fullName`, `email`, `password`, `phoneNumber`, `addressID`, `status`) VALUES
(3, 'Jihad antoun          ', 'Jihadantoun1@homeowner.buildEase', '$2y$10$5XYfThS0KqoiYKYOOqgZDOVdOUIPGx8SlOVMBGso0EqQlevocs1PO', '00/111/111', 9, 'accepted'),
(9, 'jack ff  ', 'jackff1@homeowner.buildEase', '$2y$10$HGWTB8ejlltU4Mf6.EJ7CuJQGpyn5z2BSlGTV9vvPf4s0lcbGxtRq', '00/111/111', 11, 'accepted'),
(10, 'Elie Jarrouge  ', 'eliejarrouge1@homeowner.buildEase', '$2y$10$0d/K2gpWnS1Ro2ydhymr7e11EvFLpcCLtOINHBdrAZ6Kh2/O/qJgy', '00/111/111', 14, 'accepted'),
(11, 'maria abdo       ', 'MariaAbdo1200@homeowner.buildEase', '$2y$10$OB6G2mZ3FV3n/tZmErqIn.kATuEtUgb5eaPCi384BmzUsQ7w8oC0i', '00/111/111', 15, 'accepted'),
(13, 'Yara Mansour', 'yaramansour1@homeowner.buildEase', '$2y$10$kJAajEUHZreqcKbRpwWk9u3qI.OjqYtapEEESdmmbwOl80qsrg32G', '00/111/111', 24, 'accepted'),
(14, 'David Hsein ', 'DavidHsein999@homeowner.buildEase', '$2y$10$avxmpHyBv/heA/jnpaKYqel2DYhkhD1tKVbCeTTNkJ5HmEY.UCfhi', '00/111/111', 25, 'accepted'),
(15, 'Jackline Moussa', 'JacklineMoussa18@homeowner.buildEase', '$2y$10$QHSBLIgDXPoW7LG3J14hAuld31asOD.N/r5VrNxLuGmc3thwRhUHm', '00/111/643', 26, 'accepted'),
(16, 'Maria Moussa', 'MariaMoussa5463@homeowner.buildEase', '$2y$10$LVhGXUehV6EgMTchnZo96OdNyRJXXJbGFX85Iq1kRs7XN28Xnit0K', '00/111/111', 27, 'accepted'),
(17, 'Hanna Yaacoub', 'HannaYaacoub777@homeowner.buildEase', '$2y$10$8Dn.6DOvspRXsH9kPWipMOC3xedxzyBC.hurmoBhqdzkyJ0F3XXF2', '00/111/111', 28, 'accepted'),
(18, 'Rola Hsein', 'RolaHsein09@homeowner.buildEase', '$2y$10$aQmaXtGuonZYaAwPlF/4O.kdCM1SWTKL1dYrl5QtvRomng1ak0jcK', '00/111/111', 29, 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `ho_cont_feedback`
--

CREATE TABLE `ho_cont_feedback` (
  `feedbackID` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(800) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `homeOwnerID` int(11) NOT NULL,
  `contractorID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ho_cont_feedback`
--

INSERT INTO `ho_cont_feedback` (`feedbackID`, `rating`, `comment`, `date`, `homeOwnerID`, `contractorID`) VALUES
(1, 1, 'sooo sooo badd he was the reason why my project took double of the period expected!!', '2025-04-24', 11, 7),
(2, 1, 'bad', '2025-04-24', 11, 9),
(6, 5, 'exceeeellllleenntt!!', '2025-05-06', 11, 16),
(7, 2, 'not bad', '2025-05-06', 11, 7);

-- --------------------------------------------------------

--
-- Table structure for table `ho_pro_feedback`
--

CREATE TABLE `ho_pro_feedback` (
  `feedbackID` int(11) NOT NULL,
  `rating` double NOT NULL,
  `comment` varchar(800) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `homeOwnerID` int(11) NOT NULL,
  `professionalID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ho_pro_feedback`
--

INSERT INTO `ho_pro_feedback` (`feedbackID`, `rating`, `comment`, `date`, `homeOwnerID`, `professionalID`) VALUES
(3, 5, 'excellent', '2025-04-01', 10, 36),
(4, 4, 'very good', '2025-04-01', 13, 39),
(5, 1, 'very bad', '2025-04-01', 11, 41),
(6, 2, 'very bad', '2025-04-18', 9, 42),
(7, 3, 'good', '2025-04-18', 16, 43),
(8, 5, 'excellent', '2025-04-18', 14, 37),
(9, 5, 'excellent', '2025-04-18', 16, 39),
(10, 1, 'not good at all', '2025-04-18', 16, 37),
(11, 3, 'good', '2025-04-18', 15, 44),
(12, 2, 'bad', '2025-04-18', 11, 42),
(13, 4, 'good', '2025-04-23', 11, 36),
(14, 5, 'she is so good and expert i really recommend to work with her!!!', '2025-04-24', 11, 40),
(15, 1, 'badddddddddddddddd', '2025-05-06', 11, 45);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoiceID` int(11) NOT NULL,
  `invoiceAmount` double NOT NULL,
  `description` varchar(800) NOT NULL,
  `date` date NOT NULL,
  `stepNumber` int(11) NOT NULL,
  `homeOwnerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_library`
--

CREATE TABLE `material_library` (
  `id` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `unit_measure` varchar(20) DEFAULT NULL,
  `description` varchar(800) DEFAULT NULL,
  `contractorID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material_library`
--

INSERT INTO `material_library` (`id`, `title`, `category`, `price`, `supplier`, `unit_measure`, `description`, `contractorID`) VALUES
(1, 'paint', 'Painter', 15, 'Benjamin Moore', '3 Litter', 'difrent colors', 7),
(2, 'GPS Survey Receiver', 'Land Surveying', 2600, 'Trimble', 'each', 'GNSS receiver for accurate positioning', 7),
(3, 'Survey Tripod', 'Land Surveying', 150, 'CST/Berger', 'each', 'Stable base for surveying instruments', 7),
(4, 'AutoCAD Software', 'Civil Engineering', 1600, 'Autodesk', 'license', 'Design and drafting software', 7),
(5, 'Structural Steel Beam', 'Civil Engineering', 120, 'SteelCo', 'meter', 'Support beam for structural integrity', 7),
(6, 'Concrete Mix Design', 'Civil Engineering', 250, 'Cemex', 'cubic meter', 'Customized concrete mix for foundations', 7),
(7, 'Excavator Rental', 'Excavation', 500, 'CAT Rentals', 'day', 'Heavy machinery for digging and earthmoving', 7),
(8, 'Rock Breaker Attachment', 'Excavation', 1200, 'Bobcat', 'each', 'Attachment for breaking hard surfaces', 7),
(9, 'Dump Truck Rental', 'Excavation', 400, 'Volvo', 'day', 'Transporting excavated materials', 7),
(10, 'Ready-Mix Concrete', 'Foundation & Column Construction', 95, 'Lafarge', 'cubic meter', 'Pre-mixed concrete for foundations', 7),
(11, 'Rebar Steel Rod', 'Foundation & Column Construction', 1.2, 'ArcelorMittal', 'meter', 'Reinforcement for concrete structures', 7),
(12, 'Formwork Panels', 'Foundation & Column Construction', 25, 'Doka', 'square meter', 'Temporary molds for concrete shaping', 7),
(13, 'Concrete Block', 'Masonry', 1, 'Holcim', 'each', 'Standard concrete masonry unit', 7),
(14, 'Clay Brick', 'Masonry', 0.8, 'Acme Brick', 'each', 'Traditional red clay brick', 7),
(15, 'Mortar Mix', 'Masonry', 7.5, 'Quikrete', 'bag (50kg)', 'Pre-blended mortar for bricklaying', 7),
(16, 'Gypsum Plaster', 'Plastering', 10, 'USG', 'bag (25kg)', 'Quick-setting plaster for interiors', 7),
(17, 'Lime Plaster', 'Plastering', 12, 'LimeCo', 'bag (25kg)', 'Breathable plaster for historic buildings', 7),
(18, 'Cement Plaster', 'Plastering', 8, 'Cemex', 'bag (25kg)', 'Durable plaster for exterior surfaces', 7),
(19, 'Electrical CAD Software', 'Electrical Engineering', 1200, 'AutoCAD', 'license', 'Software for electrical schematics', 7),
(20, 'Circuit Breaker Panel', 'Electrical Engineering', 250, 'Siemens', 'each', 'Main distribution panel for circuits', 7),
(21, 'Electrical Conduit', 'Electrical Engineering', 2, 'Allied Tube', 'meter', 'Protective tubing for electrical wiring', 7),
(22, 'Copper Wire 2.5mm', 'Electrician', 0.75, 'Southwire', 'meter', 'Standard wire for residential circuits', 7),
(23, 'LED Light Fixture', 'Electrician', 20, 'Philips', 'each', 'Energy-efficient lighting solution', 7),
(24, 'Wall Switch', 'Electrician', 5, 'Leviton', 'each', 'Standard on/off switch for lighting', 7),
(25, 'Plumbing CAD Software', 'Plumbing', 1000, 'AutoCAD', 'license', 'Software for designing plumbing layouts', 7),
(26, 'Water Pressure Regulator', 'Plumbing', 85, 'Watts', 'each', 'Controls water pressure in systems', 7),
(27, 'Pipe Insulation', 'Plumbing', 1.5, 'Frost King', 'meter', 'Prevents heat loss and pipe freezing', 7),
(28, 'PVC Pipe 1 inch', 'Plumber', 0.9, 'Charlotte Pipe', 'meter', 'Common pipe for water supply lines', 7),
(29, 'Pipe Wrench', 'Plumber', 25, 'Ridgid', 'each', 'Tool for gripping and turning pipes', 7),
(30, 'Faucet Fixture', 'Plumber', 45, 'Moen', 'each', 'Standard kitchen sink faucet', 7),
(31, 'Plywood Sheet', 'Carpentry', 20, 'Georgia-Pacific', 'sheet', 'Versatile wood panel for construction', 7),
(32, '2x4 Lumber', 'Carpentry', 3.5, 'Weyerhaeuser', 'meter', 'Standard framing lumber', 7),
(33, 'Wood Screws', 'Carpentry', 0.1, 'Fastenal', 'each', 'Fasteners for wood projects', 7),
(34, 'Ceramic Tile 30x30', 'Tiling', 1.2, 'RAK Ceramics', 'tile', 'Glossy finish, ideal for walls', 7),
(35, 'Porcelain Tile 60x60', 'Tiling', 3.5, 'Kajaria', 'tile', 'Matte finish, high durability', 7),
(36, 'Tile Adhesive', 'Tiling', 15, 'Mapei', 'bag (25kg)', 'High-bond adhesive for tile installations', 7),
(37, 'Total Station', 'Land Surveying', 3500, 'Leica', 'each', 'High-precision electronic/optical instrument', 7),
(38, 'Tiles', 'Tiling', 5, 'Benjamin Moore', '50', 'Ceramic/Porcelain', 7),
(39, 'armid', 'ARMID', 5, 'Benjamin Moore', '3 per metter', 'red', 7),
(40, 'mass loaded vinyl', 'sound insulation', 3000, 'audimute', 'square meteres', 'add mass to block sound transmition', 7),
(41, 'ceramic', 'Carpentry', 4, 'Benjamin Moore', 'per metter', 'mmmm', 7),
(42, 'cement', 'Foundation &amp; Column Construction', 5, 'Benjamin Moore', 'square meteres', 'type a', 7);

-- --------------------------------------------------------

--
-- Table structure for table `professional`
--

CREATE TABLE `professional` (
  `id` int(11) NOT NULL,
  `fullName` varchar(40) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `addressID` int(11) DEFAULT NULL,
  `cvID` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professional`
--

INSERT INTO `professional` (`id`, `fullName`, `email`, `password`, `age`, `phoneNumber`, `addressID`, `cvID`, `status`) VALUES
(36, 'Clarita Antoun', 'claritaantoun88@professional.buildEase', '$2y$10$fh7c/E2zuIuxk7aauISJqeM3953c64j4MlZxr7C9EEypZ6VZoTvTy', 54, '00/144/111', 19, 16, 'rejected'),
(37, 'Elisa Zmeter', 'ElisaZmeter29948494831@professional.buildEase', '$2y$10$6DMZSOr7oVkUfoV2lWylvOFlnuwkKo/zylzWQ2NEWsIZeimMn.s3i', 23, '00/222 888', 20, 17, 'accepted'),
(38, 'grg antn', 'grgantn21@professional.buildEase', '$2y$10$k6x7fgWDbMkxbl6fynFXUOkXUWpgcvfP9NpqcDihXQIKao8vtFWxm', 23, '00/222 888', 21, 18, 'accepted'),
(39, 'Habib Karam', 'HabibKaram3@professional.buildEase', '$2y$10$ZKowjdDLDMgzWw7Op0GcJ.YZRbwIXBRgBhDFWmsEwXWknZ1WUTR.S', 33, '00/111/111', 23, 20, 'accepted'),
(40, 'Samar Macherky', 'SamarMacherky@professional.buildEase', 'Strong123@@', 22, '00/111/111', 33, 26, 'accepted'),
(41, 'Manal Moussa', 'ManalMoussa2@professional.buildEase', '$2y$10$qT6xJIpqOPScr0HNKqgMuOlvHmPpjx/vL4Ek7IVmojSIG2N/FWZNq', 45, '00/111/111', 70, 31, 'accepted'),
(42, 'Karl Azar', 'KarlAzar333@professional.buildEase', '$2y$10$t.qHVQKvx6/oSiJDT0oaBunzhva4qB11EI68t2Ai7SSJO.wekqY8a', 34, '00/111/111', 71, 32, 'accepted'),
(43, 'Malek Jomaa', 'MalekJomaa456@professional.buildEase', '$2y$10$ryyPA296EwgFypfCAI2i/.LQoOH1moFXrE1eEGHiRq.jI9KSPw.qC', 33, '76/893 467', 72, 33, 'accepted'),
(44, 'Sasha Jomaa', 'SashaJomaa562@professional.buildEase', '$2y$10$YH7ElgIiQ.VRY1Q2Vns4h.Qz3yiDJVP6VKuIgttlohW1SBo7P17Me', 34, '76/893 499', 73, 34, 'accepted'),
(45, 'Carla Antoun', 'CarlaAntoun0976@professional.buildEase', '$2y$10$w5ZXLflL7Q83o3VS3Pgupetx/Nk4s88R17bwygT/SlZ.rosVp5RVa', 60, '03/756 877', 74, 35, 'accepted'),
(68, 'Ahida Mourad', 'Ahidamourad1@professional.buildEase', '$2y$10$pibhpm4xiFFdpsDwfBXJku5KfLMZTDKPSShe4T5fHqxrI44mYngDS', 25, '79/911222', 34, 38, 'accepted'),
(153, 'Rita Abboud', 'RitaAbboudr21@professional.buildEase', '$2y$10$tgSVBN.oJ3jPD5raDZvmUekb5Xukpr9WhDomgSebON73agiVeTizq', 55, '76/893 467', 182, 144, 'accepted'),
(154, 'Rita zeitouny', 'RitaZeitouny21@professional.buildEase', '$2y$10$.jnCQmMLItlSPIcyYEyEy.dS.fbtNSIY0AJPbC96XPcgxxYee2u5e', 45, '06/675 999', 183, 145, 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `professional_details`
--

CREATE TABLE `professional_details` (
  `detailID` int(11) NOT NULL,
  `areaOfWork` varchar(400) NOT NULL,
  `startDate` date DEFAULT NULL,
  `availibilityStatus` varchar(100) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `priceDetails` varchar(1000) DEFAULT NULL,
  `professionalID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professional_details`
--

INSERT INTO `professional_details` (`detailID`, `areaOfWork`, `startDate`, `availibilityStatus`, `price`, `priceDetails`, `professionalID`) VALUES
(1, 'Land Surveying', '0000-00-00', 'Not Available', 0, NULL, 36),
(2, 'Civil Engineering', '0000-00-00', 'Not Available', 50, '50', 37),
(3, 'Civil Engineering', '0000-00-00', '', 0, NULL, 38),
(4, 'Excavation', '0000-00-00', 'Available', 0, NULL, 39),
(5, 'plastering', NULL, 'Not Available', 0, '', 40),
(6, 'Land Surveying', NULL, 'Not Available', NULL, NULL, 41),
(7, 'Tiling', NULL, 'Available', NULL, NULL, 42),
(8, 'Civil Engineering', NULL, 'Not Available', NULL, NULL, 43),
(9, 'Electrical Engineering', NULL, 'Not Available', NULL, NULL, 44),
(10, 'Land Surveying', NULL, 'Available', NULL, NULL, 45),
(36, 'Civil Engineering', '0000-00-00', 'Available', 100, '100$ per month', 68),
(121, 'humidity isolation', NULL, 'Available', 50, '50$ per month', 153),
(122, 'humidity isolation', NULL, 'Available', 200, '200$per month', 154);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `projectID` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `budget` double NOT NULL,
  `exactCost` double NOT NULL,
  `estimatedDuration` varchar(40) NOT NULL,
  `exactDuration` varchar(40) NOT NULL,
  `startDate` date NOT NULL,
  `imageGenerated` varchar(300) DEFAULT NULL,
  `contractorID` int(11) DEFAULT NULL,
  `addressID` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending' COMMENT 'Project status: pending, active, completed, rejected',
  `contractorPrice` decimal(10,2) DEFAULT NULL,
  `websiteCommission` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`projectID`, `name`, `budget`, `exactCost`, `estimatedDuration`, `exactDuration`, `startDate`, `imageGenerated`, `contractorID`, `addressID`, `status`, `contractorPrice`, `websiteCommission`) VALUES
(1, 'House Construction', 500000, 0, '90', '0', '2025-05-01', NULL, NULL, NULL, 'active', NULL, NULL),
(3, 'house construction', 400000000, 0, '', '', '2025-05-31', NULL, 16, 92, 'pending', NULL, NULL),
(4, 'House construction', 9000, 0, '', '', '2025-06-06', 'generated_images/ai_project_4_1748523129.png', 7, 93, 'active', 2000.00, NULL),
(5, 'House construction 1', 3000, 4000, '10 months', '8 weeks 2 days', '2025-12-27', NULL, 7, 94, 'active', 1000.00, 10.00),
(6, 'House construction 2', 34566632, 0, '', '', '2025-06-06', NULL, 7, 95, 'active', NULL, NULL),
(7, 'house construction', 4995792, 0, '', '', '2025-05-29', NULL, 7, 96, 'pending', NULL, NULL),
(8, 'House construction', 736399363, 0, '', '', '2025-05-23', NULL, 16, 97, 'rejected', NULL, NULL),
(9, 'House Construction', 500000, 0, '90', '0', '2025-05-01', NULL, NULL, NULL, 'ongoing', NULL, NULL),
(10, 'house construction3', 400000000, 0, '', '', '2025-05-31', NULL, 16, 92, 'pending', NULL, NULL),
(11, 'House construction', 9000, 0, '', '', '2025-06-06', NULL, 7, 93, 'active', NULL, NULL),
(12, 'House construction 1', 234566000, 0, '', '', '2025-12-27', 'generated_images/ai_project_5_1747311391.png', 7, 94, 'active', NULL, NULL),
(13, 'House construction 2', 34566632, 0, '', '', '2025-06-06', NULL, 7, 95, 'active', NULL, NULL),
(14, 'house construction', 4995792, 0, '', '', '2025-05-29', NULL, 7, 96, 'completed', NULL, NULL),
(15, 'House construction', 736399363, 0, '', '', '2025-05-23', NULL, 7, 97, 'rejected', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_picture`
--

CREATE TABLE `project_picture` (
  `projectPictureID` int(11) NOT NULL,
  `paths` varchar(200) NOT NULL,
  `details` varchar(800) NOT NULL,
  `projectID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_picture`
--

INSERT INTO `project_picture` (`projectPictureID`, `paths`, `details`, `projectID`) VALUES
(1, 'project_pictures/6828db9712ea3_logo.jpg', 'about the project', 12),
(2, 'project_pictures/6833649544ed7_ai_project_5_1747311391.png', 'about the project', 5);

-- --------------------------------------------------------

--
-- Table structure for table `step`
--

CREATE TABLE `step` (
  `stepNumber` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `details` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `step`
--

INSERT INTO `step` (`stepNumber`, `name`, `details`) VALUES
(1, 'Land Surveying', 'Boundary mapping and site analysis'),
(2, 'Civil Engineering', 'Home plan design based on survey'),
(3, 'Excavation', 'Earth/rock removal for site preparation'),
(4, 'Foundation & Column Construction', 'Laying foundation and erecting columns'),
(5, 'Masonry', 'Laying concrete blocks or stones'),
(6, 'Plastering', 'Applying plaster to walls/ceilings'),
(7, 'Electrical Engineering', 'Design of electrical system'),
(8, 'Electrician', 'Wiring and installations'),
(9, 'Plumbing', 'Design of plumbing system'),
(10, 'Plumber', 'Installation and repair of pipes/fixtures'),
(11, 'Carpentry', 'Building and installing wooden frameworks'),
(12, 'Tiling', 'Installing tiles on surfaces'),
(13, 'Interior Design', 'Color schemes and finishes'),
(14, 'Painter', 'Applying paint and finishes');

-- --------------------------------------------------------

--
-- Table structure for table `step_picture`
--

CREATE TABLE `step_picture` (
  `stepPictureID` int(11) NOT NULL,
  `path` varchar(100) NOT NULL,
  `details` varchar(800) NOT NULL,
  `stepNumber` int(11) NOT NULL,
  `projectID` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `step_picture`
--

INSERT INTO `step_picture` (`stepPictureID`, `path`, `details`, `stepNumber`, `projectID`, `is_active`) VALUES
(1, 'uploads/steps/682904c831411_LIU.png', 'Step update uploaded on 2025-05-17', 2, 4, 1),
(2, 'uploads/step_pictures/68291396656a2_dash.PNG', 'test', 2, 4, 0),
(3, 'uploads/steps/68291bd46adfc_LIU.png', 'Step update uploaded on 2025-05-18', 2, 4, 1),
(4, 'uploads/steps/682c36978c3ef_Clarita-sequence-1.drawio.png', 'Step update uploaded on 2025-05-20', 2, 5, 0),
(5, 'uploads/step_pictures/682c387da820f_LIU.png', 'liu', 1, 12, 1),
(6, 'uploads/steps/682cfa78afa82_logo.jpg', 'Step update uploaded on 2025-05-20', 2, 5, 1),
(7, 'uploads/step_pictures/6832d54fa0e94_LIU.png', 'liu', 1, 5, 1),
(8, 'uploads/step_pictures/68332739f251a_LIU.png', 'liu', 2, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `work_in`
--

CREATE TABLE `work_in` (
  `professionalID` int(11) NOT NULL,
  `projectID` int(11) NOT NULL,
  `stepNumber` int(11) NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `stepStatus` varchar(200) NOT NULL,
  `worked_details` varchar(1000) DEFAULT NULL,
  `exactPrice` varchar(200) DEFAULT NULL,
  `paymentStatus` varchar(200) NOT NULL,
  `stepName` varchar(100) DEFAULT NULL,
  `stepDetails` text DEFAULT NULL,
  `stepType` enum('standard','custom') NOT NULL DEFAULT 'standard',
  `is_active` tinyint(1) DEFAULT 1,
  `deactivated_at` timestamp NULL DEFAULT NULL,
  `cost` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_in`
--

INSERT INTO `work_in` (`professionalID`, `projectID`, `stepNumber`, `startDate`, `endDate`, `stepStatus`, `worked_details`, `exactPrice`, `paymentStatus`, `stepName`, `stepDetails`, `stepType`, `is_active`, `deactivated_at`, `cost`) VALUES
(1, 4, -3, NULL, NULL, 'pending', NULL, NULL, 'unpaid', 'humidity isolation', 'humidity isolation', 'custom', 0, '2025-05-17 18:03:51', NULL),
(1, 4, -2, '2025-02-02', '2025-03-03', 'pending', NULL, '78', 'unpaid', 'ARMID', 'ARMID', 'custom', 0, '2025-05-17 11:53:57', NULL),
(1, 4, -1, '2025-03-03', '0207-04-05', 'pending', NULL, '45', 'unpaid', 'sound insulation', 'to isolate the sound', 'custom', 0, '2025-05-20 15:22:20', NULL),
(1, 5, -1, '2024-03-03', '1025-11-01', 'pending', NULL, '56', 'unpaid', 'sound insulation2', 'to isolate the sound2', 'custom', 0, '2025-05-26 06:46:06', NULL),
(36, 4, 1, '2025-05-15', '2025-05-17', 'in_progress', '3', '150', '', NULL, NULL, 'standard', 1, NULL, 45874),
(37, 4, -5, NULL, NULL, 'pending', NULL, NULL, 'unpaid', 'custom', 'custom', 'custom', 1, NULL, NULL),
(40, 4, 6, '2025-05-20', NULL, 'in_progress', NULL, NULL, '', NULL, NULL, 'standard', 1, NULL, 540),
(41, 12, 1, '2025-05-15', NULL, 'in_progress', NULL, '', '', NULL, NULL, 'standard', 1, NULL, NULL),
(42, 5, 12, '2025-06-05', '2025-06-07', 'pending', NULL, '1700', 'unpaid', NULL, NULL, 'standard', 1, NULL, NULL),
(43, 4, -7, NULL, NULL, 'pending', NULL, NULL, 'unpaid', 'customm', 'customm', 'custom', 1, NULL, NULL),
(44, 5, 7, '2025-05-20', '2025-05-22', 'in_progress', '3', '1600', 'unpaid', NULL, NULL, 'standard', 1, NULL, 4800),
(44, 11, -1, '2025-05-17', NULL, 'in_progress', NULL, NULL, 'unpaid', 'custom', 'custom', 'custom', 1, NULL, NULL),
(45, 4, -4, NULL, NULL, 'pending', NULL, NULL, 'unpaid', 'humidity isolation', 'humidity isolation', 'custom', 1, NULL, NULL),
(45, 5, 1, '2025-05-01', '2025-05-25', 'completed', NULL, '1000', 'paid', NULL, NULL, 'standard', 1, NULL, 11475),
(52, 4, -6, NULL, NULL, 'pending', NULL, NULL, 'unpaid', 'tese', 'test', 'custom', 1, NULL, NULL),
(56, 12, 2, '2025-05-15', NULL, 'in_progress', NULL, NULL, '', NULL, NULL, 'standard', 1, NULL, NULL),
(57, 11, 9, '2025-05-16', NULL, 'in_progress', NULL, '', '', NULL, NULL, 'standard', 1, NULL, NULL),
(60, 4, 12, NULL, NULL, 'pending', NULL, NULL, '', NULL, NULL, 'standard', 1, NULL, NULL),
(64, 12, -1, NULL, NULL, 'pending', NULL, NULL, 'unpaid', 'sound insulation', 'to isolate the sound', 'custom', 1, NULL, NULL),
(68, 4, 2, '2025-05-15', '2026-05-05', 'completed', NULL, NULL, '', NULL, NULL, 'standard', 1, NULL, 9100),
(68, 5, 2, '2025-05-31', '2025-06-30', 'in_progress', '2 ', '250', 'paid', NULL, NULL, 'standard', 1, NULL, 1160),
(84, 5, 3, '2025-07-01', '2025-07-31', 'in_progress', NULL, '1800', 'paid', NULL, NULL, 'standard', 1, NULL, NULL),
(88, 5, 4, '2025-07-01', '2025-08-30', 'in_progress', NULL, '2500', 'paid', NULL, NULL, 'standard', 1, NULL, NULL),
(93, 5, 5, '2025-08-01', '2025-08-30', 'pending', NULL, '2200', 'paid', NULL, NULL, 'standard', 1, NULL, NULL),
(99, 5, 6, '2025-05-18', '2025-05-19', 'pending', NULL, '1300', 'paid', NULL, NULL, 'standard', 1, NULL, NULL),
(112, 5, 8, '2025-05-23', '2025-05-25', 'pending', NULL, '1400', 'unpaid', NULL, NULL, 'standard', 1, NULL, NULL),
(121, 5, 9, '2025-05-26', '2025-05-28', 'pending', NULL, '1500', 'unpaid', NULL, NULL, 'standard', 1, NULL, NULL),
(125, 5, 10, '2025-05-29', '2025-05-30', 'pending', NULL, '1200', 'unpaid', NULL, NULL, 'standard', 1, NULL, NULL),
(130, 5, 11, '2025-06-01', '2025-06-04', 'pending', NULL, '2000', 'unpaid', NULL, NULL, 'standard', 1, NULL, NULL),
(142, 5, 13, '2025-06-08', '2025-06-10', 'pending', NULL, '1900', 'unpaid', NULL, NULL, 'standard', 1, NULL, NULL),
(151, 5, 14, '2025-06-11', '2025-06-12', 'pending', NULL, '1100', 'unpaid', NULL, NULL, 'standard', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `work_materials`
--

CREATE TABLE `work_materials` (
  `id` int(11) NOT NULL,
  `professionalID` int(11) NOT NULL,
  `projectID` int(11) NOT NULL,
  `stepNumber` int(11) NOT NULL,
  `materialID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `assigned_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `deactivated_at` timestamp NULL DEFAULT NULL,
  `unused_quantity` int(11) DEFAULT 0,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_materials`
--

INSERT INTO `work_materials` (`id`, `professionalID`, `projectID`, `stepNumber`, `materialID`, `quantity`, `assigned_date`, `is_active`, `deactivated_at`, `unused_quantity`, `feedback`) VALUES
(3, 36, 4, 1, 2, 19, '2025-05-17', 1, NULL, 2, NULL),
(4, 68, 5, 2, 5, 5, '2025-05-16', 1, NULL, 2, 'good'),
(5, 56, 12, 2, 2, 3, '2025-05-19', 1, NULL, 1, NULL),
(6, 57, 11, 9, 25, 8, '2025-05-17', 0, '2025-05-16 20:20:46', 5, NULL),
(8, 57, 11, 9, 27, 5, '2025-05-16', 1, NULL, 0, NULL),
(9, 57, 11, 9, 26, 9, '2025-05-16', 0, '2025-05-16 20:30:07', 0, NULL),
(10, 68, 4, 2, 4, 5, '2025-05-20', 1, NULL, 0, NULL),
(11, 68, 4, 2, 6, 3, '2025-05-17', 1, NULL, 1, 'good'),
(12, 36, 4, 1, 3, 9, '2025-05-20', 1, NULL, 1, NULL),
(31, 68, 4, 2, 5, 5, '2025-05-20', 1, NULL, 0, NULL),
(33, 36, 4, 1, 18, 3, '2025-05-20', 1, NULL, 0, NULL),
(34, 40, 4, 6, 16, 3, '2025-05-20', 1, NULL, 0, NULL),
(35, 40, 4, 6, 1, 4, '2025-05-20', 1, NULL, 0, NULL),
(36, 40, 4, 6, 3, 3, '2025-05-20', 1, NULL, 0, NULL),
(37, 52, 4, -6, 10, 3, '2025-05-20', 1, NULL, 0, NULL),
(38, 45, 5, 1, 6, 5, '2025-05-25', 1, NULL, 0, NULL),
(39, 45, 5, 1, 2, 4, '2025-05-25', 1, NULL, 0, NULL),
(40, 68, 5, 2, 3, 2, '2025-05-25', 1, NULL, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`addressID`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `change_requests`
--
ALTER TABLE `change_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contract`
--
ALTER TABLE `contract`
  ADD PRIMARY KEY (`contractID`),
  ADD KEY `contractorID` (`contractorID`);

--
-- Indexes for table `contractor`
--
ALTER TABLE `contractor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addressID` (`addressID`),
  ADD KEY `cvID` (`cvID`);

--
-- Indexes for table `cont_pro_feedback`
--
ALTER TABLE `cont_pro_feedback`
  ADD PRIMARY KEY (`feedbackID`),
  ADD KEY `contractorID` (`contractorID`),
  ADD KEY `professionalID` (`professionalID`);

--
-- Indexes for table `creates`
--
ALTER TABLE `creates`
  ADD KEY `homeOwnerID` (`homeOwnerID`),
  ADD KEY `projectID` (`projectID`);

--
-- Indexes for table `curriculum_vitae`
--
ALTER TABLE `curriculum_vitae`
  ADD PRIMARY KEY (`cvID`);

--
-- Indexes for table `homeowner`
--
ALTER TABLE `homeowner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addressID` (`addressID`);

--
-- Indexes for table `ho_cont_feedback`
--
ALTER TABLE `ho_cont_feedback`
  ADD PRIMARY KEY (`feedbackID`),
  ADD KEY `contractorID` (`contractorID`),
  ADD KEY `homeOwnerID` (`homeOwnerID`);

--
-- Indexes for table `ho_pro_feedback`
--
ALTER TABLE `ho_pro_feedback`
  ADD PRIMARY KEY (`feedbackID`),
  ADD KEY `homeOwnerID` (`homeOwnerID`),
  ADD KEY `professionalID` (`professionalID`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoiceID`),
  ADD KEY `homeOwnerID` (`homeOwnerID`),
  ADD KEY `stepNumber` (`stepNumber`);

--
-- Indexes for table `material_library`
--
ALTER TABLE `material_library`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractorID` (`contractorID`);

--
-- Indexes for table `professional`
--
ALTER TABLE `professional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `professional_ibfk_1` (`addressID`),
  ADD KEY `cvID` (`cvID`);

--
-- Indexes for table `professional_details`
--
ALTER TABLE `professional_details`
  ADD PRIMARY KEY (`detailID`),
  ADD KEY `professionalID` (`professionalID`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`projectID`),
  ADD KEY `project_ibfk_1` (`contractorID`),
  ADD KEY `addressID` (`addressID`);

--
-- Indexes for table `project_picture`
--
ALTER TABLE `project_picture`
  ADD PRIMARY KEY (`projectPictureID`),
  ADD KEY `projectID` (`projectID`);

--
-- Indexes for table `step`
--
ALTER TABLE `step`
  ADD PRIMARY KEY (`stepNumber`);

--
-- Indexes for table `step_picture`
--
ALTER TABLE `step_picture`
  ADD PRIMARY KEY (`stepPictureID`),
  ADD KEY `stepNumber` (`stepNumber`),
  ADD KEY `fk_step_picture_project` (`projectID`);

--
-- Indexes for table `work_in`
--
ALTER TABLE `work_in`
  ADD PRIMARY KEY (`professionalID`,`projectID`,`stepNumber`),
  ADD KEY `stepNumber` (`stepNumber`),
  ADD KEY `projectID` (`projectID`),
  ADD KEY `professionalID` (`professionalID`);

--
-- Indexes for table `work_materials`
--
ALTER TABLE `work_materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment` (`professionalID`,`projectID`,`stepNumber`,`materialID`),
  ADD KEY `materialID` (`materialID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `change_requests`
--
ALTER TABLE `change_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `contract`
--
ALTER TABLE `contract`
  MODIFY `contractID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contractor`
--
ALTER TABLE `contractor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cont_pro_feedback`
--
ALTER TABLE `cont_pro_feedback`
  MODIFY `feedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `curriculum_vitae`
--
ALTER TABLE `curriculum_vitae`
  MODIFY `cvID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `homeowner`
--
ALTER TABLE `homeowner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `ho_cont_feedback`
--
ALTER TABLE `ho_cont_feedback`
  MODIFY `feedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ho_pro_feedback`
--
ALTER TABLE `ho_pro_feedback`
  MODIFY `feedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoiceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_library`
--
ALTER TABLE `material_library`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `professional`
--
ALTER TABLE `professional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `professional_details`
--
ALTER TABLE `professional_details`
  MODIFY `detailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `projectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `project_picture`
--
ALTER TABLE `project_picture`
  MODIFY `projectPictureID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `step`
--
ALTER TABLE `step`
  MODIFY `stepNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `step_picture`
--
ALTER TABLE `step_picture`
  MODIFY `stepPictureID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `work_materials`
--
ALTER TABLE `work_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `contract_ibfk_1` FOREIGN KEY (`contractorID`) REFERENCES `contractor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contractor`
--
ALTER TABLE `contractor`
  ADD CONSTRAINT `contractor_ibfk_1` FOREIGN KEY (`addressID`) REFERENCES `address` (`addressID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contractor_ibfk_3` FOREIGN KEY (`cvID`) REFERENCES `curriculum_vitae` (`cvID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cont_pro_feedback`
--
ALTER TABLE `cont_pro_feedback`
  ADD CONSTRAINT `cont_pro_feedback_ibfk_1` FOREIGN KEY (`contractorID`) REFERENCES `contractor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cont_pro_feedback_ibfk_2` FOREIGN KEY (`professionalID`) REFERENCES `professional` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `creates`
--
ALTER TABLE `creates`
  ADD CONSTRAINT `creates_ibfk_1` FOREIGN KEY (`homeOwnerID`) REFERENCES `homeowner` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `creates_ibfk_2` FOREIGN KEY (`projectID`) REFERENCES `project` (`projectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `homeowner`
--
ALTER TABLE `homeowner`
  ADD CONSTRAINT `homeowner_ibfk_1` FOREIGN KEY (`addressID`) REFERENCES `address` (`addressID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ho_cont_feedback`
--
ALTER TABLE `ho_cont_feedback`
  ADD CONSTRAINT `ho_cont_feedback_ibfk_1` FOREIGN KEY (`contractorID`) REFERENCES `contractor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ho_cont_feedback_ibfk_2` FOREIGN KEY (`homeOwnerID`) REFERENCES `homeowner` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ho_pro_feedback`
--
ALTER TABLE `ho_pro_feedback`
  ADD CONSTRAINT `ho_pro_feedback_ibfk_1` FOREIGN KEY (`homeOwnerID`) REFERENCES `homeowner` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ho_pro_feedback_ibfk_2` FOREIGN KEY (`professionalID`) REFERENCES `professional` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`homeOwnerID`) REFERENCES `homeowner` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`stepNumber`) REFERENCES `step` (`stepNumber`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `professional`
--
ALTER TABLE `professional`
  ADD CONSTRAINT `professional_ibfk_1` FOREIGN KEY (`addressID`) REFERENCES `address` (`addressID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `professional_ibfk_2` FOREIGN KEY (`cvID`) REFERENCES `curriculum_vitae` (`cvID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `professional_details`
--
ALTER TABLE `professional_details`
  ADD CONSTRAINT `professional_details_ibfk_1` FOREIGN KEY (`professionalID`) REFERENCES `professional` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`contractorID`) REFERENCES `contractor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_ibfk_2` FOREIGN KEY (`addressID`) REFERENCES `address` (`addressID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_picture`
--
ALTER TABLE `project_picture`
  ADD CONSTRAINT `project_picture_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `project` (`projectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `step_picture`
--
ALTER TABLE `step_picture`
  ADD CONSTRAINT `fk_step_picture_project` FOREIGN KEY (`projectID`) REFERENCES `project` (`projectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `step_picture_ibfk_1` FOREIGN KEY (`stepNumber`) REFERENCES `step` (`stepNumber`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `work_materials`
--
ALTER TABLE `work_materials`
  ADD CONSTRAINT `fk_work_materials_material` FOREIGN KEY (`materialID`) REFERENCES `material_library` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_work_materials_work` FOREIGN KEY (`professionalID`,`projectID`,`stepNumber`) REFERENCES `work_in` (`professionalID`, `projectID`, `stepNumber`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
