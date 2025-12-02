-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2025 at 02:16 PM
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
-- Database: `bufc`
--

-- --------------------------------------------------------

--
-- Table structure for table `coach`
--

CREATE TABLE `coach` (
  `Coach_ID` int(11) NOT NULL,
  `Coach_Type` varchar(50) DEFAULT NULL,
  `Coach_Experience` varchar(50) DEFAULT NULL,
  `Coach_Availability` varchar(50) DEFAULT NULL,
  `Coach_Previous_Club` varchar(100) DEFAULT NULL,
  `Coach_Speciality` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coach`
--

INSERT INTO `coach` (`Coach_ID`, `Coach_Type`, `Coach_Experience`, `Coach_Availability`, `Coach_Previous_Club`, `Coach_Speciality`) VALUES
(1, 'Head Coach', '15 Years', 'Active', 'Sheikh Jamal DC', 'Tactical Management'),
(2, 'Assistant Coach', '8 Years', 'Active', 'Arambagh KS', 'Player Development'),
(3, 'Goalkeeping Coach', '12 Years', 'Active', 'Muktijoddha SKC', 'Goalkeeping'),
(4, 'Fitness Coach', '10 Years', 'Active', 'Brothers Union', 'Strength & Conditioning'),
(5, 'Defensive Coach', '9 Years', 'Active', 'Rahmatganj MFS', 'Defensive Structure'),
(6, 'Attacking Coach', '14 Years', 'Active', 'Saif Sporting', 'Attacking Drills'),
(7, 'Technical Director', '20 Years', 'Active', 'BFF Academy', 'Youth Development'),
(8, 'Physio', '7 Years', 'Active', 'Medical College', 'Injury Rehabilitation'),
(9, 'Scout', '5 Years', 'Active', 'Local Leagues', 'Talent Spotting'),
(10, 'Analyst', '4 Years', 'Active', 'University Team', 'Video Analysis');

-- --------------------------------------------------------

--
-- Table structure for table `medical_record`
--

CREATE TABLE `medical_record` (
  `Prescription_ID` int(11) NOT NULL,
  `Player_ID` int(11) NOT NULL,
  `Doctor_in_charge` varchar(100) DEFAULT NULL,
  `Recovery_status` varchar(50) DEFAULT NULL,
  `Hospital` varchar(100) DEFAULT NULL,
  `Injury_Type` varchar(100) DEFAULT NULL,
  `Injured_from` date DEFAULT NULL,
  `Injured_to` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE `player` (
  `Player_ID` int(11) NOT NULL,
  `Position` varchar(50) DEFAULT NULL,
  `Preferred_foot` varchar(20) DEFAULT NULL,
  `Height` float DEFAULT NULL,
  `Weight` float DEFAULT NULL,
  `Current_Injury_Status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`Player_ID`, `Position`, `Preferred_foot`, `Height`, `Weight`, `Current_Injury_Status`) VALUES
(11, 'Goalkeeper', 'Right', 183, 78.5, 'Fit'),
(12, 'Defender', 'Right', 178, 74, 'Fit'),
(13, 'Defender', 'Right', 184, 79, 'Fit'),
(14, 'Goalkeeper', 'Right', 185.5, 80, 'Injured'),
(15, 'Striker', 'Right', 175, 70, 'Fit'),
(16, 'Defender', 'Right', 176, 72, 'Recovering'),
(17, 'Midfielder', 'Right', 170, 65, 'Fit'),
(18, 'Midfielder', 'Right', 169, 66, 'Fit'),
(19, 'Midfielder', 'Right', 175, 71, 'Fit'),
(20, 'Defender', 'Left', 175, 70, 'Fit'),
(21, 'Defender', 'Left', 174, 69, 'Doubtful'),
(22, 'Defender', 'Right', 180, 76, 'Fit'),
(23, 'Striker', 'Right', 180, 76, 'Fit'),
(24, 'Striker', 'Left', 178, 74, 'Doubtful'),
(25, 'Striker', 'Right', 179, 75, 'Fit'),
(26, 'Striker', 'Right', 176, 71, 'Fit'),
(27, 'Striker', 'Right', 174, 69, 'Injured'),
(28, 'Midfielder', 'Right', 171, 68, 'Recovering'),
(29, 'Midfielder', 'Right', 172, 69, 'Fit'),
(30, 'Midfielder', 'Right', 174, 70, 'Injured'),
(31, 'Defender', 'Right', 179, 75, 'Injured'),
(32, 'Defender', 'Left', 173, 68, 'Fit'),
(33, 'Defender', 'Right', 181, 77, 'Fit'),
(34, 'Defender', 'Left', 172, 67, 'Fit'),
(35, 'Striker', 'Right', 168, 64, 'Fit'),
(36, 'Midfielder', 'Right', 176, 72, 'Fit'),
(37, 'Midfielder', 'Right', 177, 73, 'Fit'),
(38, 'Goalkeeper', 'Right', 182, 77, 'Fit'),
(39, 'Midfielder', 'Right', 173, 67, 'Fit'),
(40, 'Midfielder', 'Right', 170, 65, 'Fit'),
(41, 'Striker', 'Right', 175, 68, 'Fit'),
(42, 'Midfielder', 'Left', 170, 65, 'Fit'),
(43, 'Defender', 'Right', 180, 75, 'Fit'),
(44, 'Winger', 'Both', 168, 62, 'Fit'),
(45, 'Goalkeeper', 'Right', 182, 76, 'Fit');

-- --------------------------------------------------------

--
-- Table structure for table `player_skills`
--

CREATE TABLE `player_skills` (
  `Player_ID` int(11) NOT NULL,
  `Skill_Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player_skills`
--

INSERT INTO `player_skills` (`Player_ID`, `Skill_Name`) VALUES
(11, 'Goalkeeping'),
(11, 'Penalty Saving'),
(12, 'Marking'),
(12, 'Tackling'),
(13, 'Heading'),
(13, 'Interceptions'),
(13, 'Strength'),
(14, 'Goalkeeping'),
(14, 'Reflexes'),
(15, 'Finishing'),
(15, 'Heading'),
(15, 'Speed'),
(16, 'Aggression'),
(16, 'Tackling'),
(17, 'Dribbling'),
(17, 'Speed'),
(18, 'Passing'),
(18, 'Vision'),
(19, 'Speed'),
(19, 'Work Rate'),
(20, 'Crossing'),
(20, 'Speed'),
(21, 'Tackling'),
(22, 'Heading'),
(22, 'Marking'),
(23, 'Finishing'),
(23, 'Positioning'),
(24, 'Dribbling'),
(24, 'Finishing'),
(25, 'Speed'),
(26, 'Finishing'),
(26, 'Free Kicks'),
(27, 'Speed'),
(28, 'Long Shots'),
(28, 'Passing'),
(29, 'Stamina'),
(29, 'Tackling'),
(30, 'Passing'),
(31, 'Tackling'),
(32, 'Crossing'),
(33, 'Heading'),
(34, 'Speed'),
(35, 'Finishing'),
(36, 'Playmaking'),
(36, 'Vision'),
(37, 'Tackling'),
(38, 'Goalkeeping'),
(39, 'Passing'),
(40, 'Dribbling'),
(41, 'Finishing'),
(41, 'Speed'),
(42, 'Passing'),
(42, 'Vision'),
(44, 'Dribbling'),
(44, 'Speed');

-- --------------------------------------------------------

--
-- Table structure for table `plays_in`
--

CREATE TABLE `plays_in` (
  `Regular_Player_ID` int(11) NOT NULL,
  `Match_id` int(11) NOT NULL,
  `Rating` decimal(3,1) DEFAULT NULL,
  `Goals_Scored` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regular_player`
--

CREATE TABLE `regular_player` (
  `Regular_Player_ID` int(11) NOT NULL,
  `Jersey_No` int(11) DEFAULT NULL,
  `Goals_Scored` int(11) DEFAULT 0,
  `Matches_Played` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regular_player`
--

INSERT INTO `regular_player` (`Regular_Player_ID`, `Jersey_No`, `Goals_Scored`, `Matches_Played`) VALUES
(11, 1, 0, 15),
(12, 2, 1, 12),
(13, 3, 3, 14),
(14, 22, 0, 10),
(15, 10, 8, 15),
(16, 4, 0, 13),
(17, 7, 5, 15),
(18, 8, 3, 14),
(19, 11, 2, 13),
(20, 5, 1, 11),
(21, 12, 0, 8),
(22, 13, 0, 9),
(23, 9, 6, 12),
(24, 25, 4, 10),
(25, 26, 3, 9),
(26, 27, 2, 8),
(27, 28, 1, 6),
(28, 6, 1, 10),
(29, 18, 0, 9),
(30, 19, 0, 8),
(31, 14, 0, 5),
(32, 15, 0, 6),
(33, 16, 1, 7),
(34, 17, 0, 4),
(35, 29, 0, 4),
(36, 20, 2, 11),
(37, 21, 1, 7),
(38, 30, 0, 2),
(39, 23, 0, 5),
(40, 24, 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `scouted_player`
--

CREATE TABLE `scouted_player` (
  `Scouted_Player_ID` int(11) NOT NULL,
  `Scouted_Player_Experience` varchar(50) DEFAULT NULL,
  `Scouted_Player_Previous_Club` varchar(100) DEFAULT NULL,
  `Bio` text DEFAULT NULL,
  `Application_Status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scouted_player`
--

INSERT INTO `scouted_player` (`Scouted_Player_ID`, `Scouted_Player_Experience`, `Scouted_Player_Previous_Club`, `Bio`, `Application_Status`) VALUES
(41, 'U-17 District Team', 'Comilla Academy', 'I am a fast striker with good finishing skills.', 'Pending'),
(42, 'BKSP Student', 'BKSP', 'Creative midfielder with good vision.', 'Trialing'),
(43, 'Local League', 'Rampura KC', 'Strong defender, good in the air.', 'Pending'),
(44, 'School Team', 'Sylhet School', 'Very fast winger, can play on both sides.', 'Pending'),
(45, 'U-19 Divisional', 'Barisal Divisional Team', 'Good reflexes and shot stopping ability.', 'Trialing');

-- --------------------------------------------------------

--
-- Table structure for table `training_participation`
--

CREATE TABLE `training_participation` (
  `Session_id` int(11) NOT NULL,
  `Player_ID` int(11) NOT NULL,
  `Coach_ID` int(11) NOT NULL,
  `Technical_score` decimal(4,1) DEFAULT NULL,
  `Physical_score` decimal(4,1) DEFAULT NULL,
  `Tactical_score` decimal(4,1) DEFAULT NULL,
  `Coach_remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_sessions`
--

CREATE TABLE `training_sessions` (
  `Session_id` int(11) NOT NULL,
  `Session_time` time NOT NULL,
  `Session_date` date NOT NULL,
  `Session_Type` varchar(50) DEFAULT NULL,
  `Session_status` varchar(50) DEFAULT NULL,
  `Location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Age` int(11) DEFAULT NULL,
  `NID` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Phone_No` varchar(20) DEFAULT NULL,
  `Date_of_Birth` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `contract_start_date` date DEFAULT NULL,
  `contract_end_date` date DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `Name`, `Age`, `NID`, `Email`, `Address`, `Phone_No`, `Date_of_Birth`, `salary`, `contract_start_date`, `contract_end_date`, `Password`, `Role`) VALUES
(1, 'Nazmul Islam', 45, '1928374655', 'nazmul@bufc.com', 'Dhaka, Bangladesh', '01711000001', '1980-01-01', 100000.00, '2024-01-01', '2026-01-01', 'nazmul123', 'coach'),
(2, 'Tamkin Mahmud Tan', 32, '9182736455', 'tamkin@bufc.com', 'Chittagong, Bangladesh', '01711000002', '1992-05-15', 60000.00, '2024-06-01', '2026-06-01', 'tamkin123', 'coach'),
(3, 'Maruful Haque', 50, '8273645192', 'maruful@bufc.com', 'Dhaka', '01711000003', '1973-01-18', 80000.00, '2023-01-01', '2025-12-31', 'maruful123', 'coach'),
(4, 'Saiful Bari Titu', 53, '7364519283', 'saiful@bufc.com', 'Sylhet', '01711000004', '1970-06-16', 80000.00, '2023-01-01', '2025-12-31', 'saiful123', 'coach'),
(5, 'Zulfiker Mahmud', 48, '6451928374', 'zulfiker@bufc.com', 'Khulna', '01711000005', '1975-01-26', 75000.00, '2023-01-01', '2025-12-31', 'zulfiker123', 'coach'),
(6, 'Kamal Babu', 55, '5192837465', 'kamal@bufc.com', 'Barisal', '01711000006', '1968-06-10', 90000.00, '2023-01-01', '2025-12-31', 'kamal123', 'coach'),
(7, 'Shafiqul Islam Manik', 52, '4928374651', 'shafiqul@bufc.com', 'Dhaka', '01711000007', '1971-01-25', 78000.00, '2023-01-01', '2025-12-31', 'shafiqul123', 'coach'),
(8, 'Rezaul Karim', 49, '3847561928', 'rezaul@bufc.com', 'Rajshahi', '01711000008', '1974-06-23', 85000.00, '2023-01-01', '2025-12-31', 'rezaul123', 'coach'),
(9, 'Mahbubur Rahman', 47, '2938475610', 'mahbubur@bufc.com', 'Comilla', '01711000009', '1976-04-28', 70000.00, '2023-01-01', '2025-12-31', 'mahbubur123', 'coach'),
(10, 'Jahangir Hossain', 46, '1029384756', 'jahangir@bufc.com', 'Mymensingh', '01711000010', '1977-07-31', 72000.00, '2023-01-01', '2025-12-31', 'jahangir123', 'coach'),
(11, 'Jamal Bhuyan', 30, '5647382910', 'jamal@bufc.com', 'Dhaka', '01911000001', '1990-04-10', 50000.00, '2024-01-01', '2025-12-31', 'jamal123', 'regular_player'),
(12, 'Tariq Kazi', 24, '6574839201', 'tariq@bufc.com', 'Dhaka', '01911000002', '2000-10-06', 45000.00, '2024-01-01', '2025-12-31', 'tariq123', 'regular_player'),
(13, 'Topu Barman', 29, '7483920156', 'topu@bufc.com', 'Dhaka', '01911000003', '1994-12-20', 48000.00, '2024-01-01', '2025-12-31', 'topu123', 'regular_player'),
(14, 'Anisur Rahman Zico', 26, '8392015647', 'zico@bufc.com', 'Coxs Bazar', '01911000004', '1997-08-10', 47000.00, '2024-01-01', '2025-12-31', 'anisur123', 'regular_player'),
(15, 'Rakib Hossain', 25, '9201564738', 'rakib@bufc.com', 'Barisal', '01911000005', '1998-11-20', 42000.00, '2024-01-01', '2025-12-31', 'rakib123', 'regular_player'),
(16, 'Bishwanath Ghosh', 24, '1564738290', 'bishwa@bufc.com', 'Sylhet', '01911000006', '1999-05-30', 40000.00, '2024-01-01', '2025-12-31', 'bishwanath123', 'regular_player'),
(17, 'Mohammad Ibrahim', 27, '2657483910', 'ibrahim@bufc.com', 'Dhaka', '01911000007', '1996-02-15', 43000.00, '2024-01-01', '2025-12-31', 'mohammad123', 'regular_player'),
(18, 'Sohel Rana', 28, '3748592016', 'sohel@bufc.com', 'Dhaka', '01911000008', '1995-03-22', 44000.00, '2024-01-01', '2025-12-31', 'sohel123', 'regular_player'),
(19, 'Saad Uddin', 25, '4859201637', 'saad@bufc.com', 'Sylhet', '01911000009', '1998-09-02', 41000.00, '2024-01-01', '2025-12-31', 'saad123', 'regular_player'),
(20, 'Yeasin Arafat', 21, '5960312748', 'yeasin@bufc.com', 'Chittagong', '01911000010', '2003-01-05', 38000.00, '2024-01-01', '2025-12-31', 'yeasin123', 'regular_player'),
(21, 'Rimon Hossain', 22, '6071423859', 'rimon@bufc.com', 'Rajshahi', '01911000011', '2002-07-01', 37000.00, '2024-01-01', '2025-12-31', 'rimon123', 'regular_player'),
(22, 'Tutul Hossain Badsha', 23, '7182534960', 'tutul@bufc.com', 'Dhaka', '01911000012', '2000-05-26', 39000.00, '2024-01-01', '2025-12-31', 'tutul123', 'regular_player'),
(23, 'Sumon Reza', 28, '8293645071', 'sumon@bufc.com', 'Khulna', '01911000013', '1995-06-15', 40000.00, '2024-01-01', '2025-12-31', 'sumon123', 'regular_player'),
(24, 'Matin Miah', 25, '9304756182', 'matin@bufc.com', 'Sylhet', '01911000014', '1998-12-20', 42000.00, '2024-01-01', '2025-12-31', 'matin123', 'regular_player'),
(25, 'Mahbubur Rahman Sufil', 24, '1425364758', 'sufil@bufc.com', 'Dhaka', '01911000015', '1999-09-10', 41000.00, '2024-01-01', '2025-12-31', 'mahbubur123', 'regular_player'),
(26, 'Nabib Newaj Jibon', 31, '2536475869', 'jibon@bufc.com', 'Dhaka', '01911000016', '1992-11-15', 46000.00, '2024-01-01', '2025-12-31', 'nabib123', 'regular_player'),
(27, 'Jewel Rana', 27, '3647586970', 'jewel@bufc.com', 'Mymensingh', '01911000017', '1996-12-25', 43000.00, '2024-01-01', '2025-12-31', 'jewel123', 'regular_player'),
(28, 'Emon Mahmud Babu', 29, '4758697081', 'emon@bufc.com', 'Dhaka', '01911000018', '1994-06-03', 44000.00, '2024-01-01', '2025-12-31', 'emon123', 'regular_player'),
(29, 'Masuk Miah Zoni', 25, '5869708192', 'masuk@bufc.com', 'Sylhet', '01911000019', '1998-01-16', 42000.00, '2024-01-01', '2025-12-31', 'masuk123', 'regular_player'),
(30, 'Atiqur Rahman Fahad', 26, '6970819203', 'atiqur@bufc.com', 'Chittagong', '01911000020', '1997-09-15', 41000.00, '2024-01-01', '2025-12-31', 'atiqur123', 'regular_player'),
(31, 'Rayhan Hasan', 28, '7081920314', 'rayhan@bufc.com', 'Dhaka', '01911000021', '1995-08-20', 40000.00, '2024-01-01', '2025-12-31', 'rayhan123', 'regular_player'),
(32, 'Rahmat Mia', 23, '8192031425', 'rahmat@bufc.com', 'Dhaka', '01911000022', '2000-12-08', 39000.00, '2024-01-01', '2025-12-31', 'rahmat123', 'regular_player'),
(33, 'Riyadul Hasan Rafi', 24, '9203142536', 'riyadul@bufc.com', 'Comilla', '01911000023', '1999-10-01', 40000.00, '2024-01-01', '2025-12-31', 'riyadul123', 'regular_player'),
(34, 'Isa Faysal', 24, '1031425364', 'isa@bufc.com', 'Dhaka', '01911000024', '1999-07-20', 39000.00, '2024-01-01', '2025-12-31', 'isa123', 'regular_player'),
(35, 'Mehedi Hasan', 21, '1242536475', 'mehedi@bufc.com', 'Khulna', '01911000025', '2002-02-15', 36000.00, '2024-01-01', '2025-12-31', 'mehedi123', 'regular_player'),
(36, 'Hemanta Vincent Biswas', 27, '1353647586', 'hemanta@bufc.com', 'Dhaka', '01911000026', '1996-12-13', 43000.00, '2024-01-01', '2025-12-31', 'hemanta123', 'regular_player'),
(37, 'Manik Hossain Molla', 24, '1464758697', 'manik@bufc.com', 'Rajshahi', '01911000027', '1999-03-11', 40000.00, '2024-01-01', '2025-12-31', 'manik123', 'regular_player'),
(38, 'Papon Singh', 23, '1575869708', 'papon@bufc.com', 'Mymensingh', '01911000028', '2000-12-31', 38000.00, '2024-01-01', '2025-12-31', 'papon123', 'regular_player'),
(39, 'Mitul Hasan', 22, '1686970819', 'mitul@bufc.com', 'Dhaka', '01911000029', '2001-05-15', 37000.00, '2024-01-01', '2025-12-31', 'mitul123', 'regular_player'),
(40, 'Asif Hossain', 21, '1797081920', 'asif@bufc.com', 'Chittagong', '01911000030', '2002-09-10', 36000.00, '2024-01-01', '2025-12-31', 'asif123', 'regular_player'),
(41, 'Rahim Uddin', 19, '1122334455', 'rahim@gmail.com', 'Comilla', '01811000041', '2005-01-10', NULL, NULL, NULL, 'rahim123', 'scouted_player'),
(42, 'Karim Mia', 18, '2233445566', 'karim@gmail.com', 'Noakhali', '01811000042', '2006-05-20', NULL, NULL, NULL, 'karim123', 'scouted_player'),
(43, 'Sajid Hasan', 20, '3344556677', 'sajid@gmail.com', 'Dhaka', '01811000043', '2004-11-15', NULL, NULL, NULL, 'sajid123', 'scouted_player'),
(44, 'Tanvir Ahmed', 17, '4455667788', 'tanvir@gmail.com', 'Sylhet', '01811000044', '2007-02-28', NULL, NULL, NULL, 'tanvir123', 'scouted_player'),
(45, 'Rafiqul Islam', 19, '5566778899', 'rafiq@gmail.com', 'Barisal', '01811000045', '2005-07-30', NULL, NULL, NULL, 'rafiqul123', 'scouted_player');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coach`
--
ALTER TABLE `coach`
  ADD PRIMARY KEY (`Coach_ID`);

--
-- Indexes for table `medical_record`
--
ALTER TABLE `medical_record`
  ADD PRIMARY KEY (`Prescription_ID`,`Player_ID`),
  ADD KEY `Player_ID` (`Player_ID`);

--
-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`Player_ID`);

--
-- Indexes for table `player_skills`
--
ALTER TABLE `player_skills`
  ADD PRIMARY KEY (`Player_ID`,`Skill_Name`);

--
-- Indexes for table `plays_in`
--
ALTER TABLE `plays_in`
  ADD PRIMARY KEY (`Regular_Player_ID`,`Match_id`),
  ADD KEY `Match_id` (`Match_id`);

--
-- Indexes for table `regular_player`
--
ALTER TABLE `regular_player`
  ADD PRIMARY KEY (`Regular_Player_ID`);

--
-- Indexes for table `scouted_player`
--
ALTER TABLE `scouted_player`
  ADD PRIMARY KEY (`Scouted_Player_ID`);

--
-- Indexes for table `training_participation`
--
ALTER TABLE `training_participation`
  ADD PRIMARY KEY (`Session_id`,`Player_ID`,`Coach_ID`),
  ADD KEY `Player_ID` (`Player_ID`),
  ADD KEY `Coach_ID` (`Coach_ID`);

--
-- Indexes for table `training_sessions`
--
ALTER TABLE `training_sessions`
  ADD PRIMARY KEY (`Session_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `NID` (`NID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `medical_record`
--
ALTER TABLE `medical_record`
  MODIFY `Prescription_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_sessions`
--
ALTER TABLE `training_sessions`
  MODIFY `Session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coach`
--
ALTER TABLE `coach`
  ADD CONSTRAINT `coach_ibfk_1` FOREIGN KEY (`Coach_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medical_record`
--
ALTER TABLE `medical_record`
  ADD CONSTRAINT `medical_record_ibfk_1` FOREIGN KEY (`Player_ID`) REFERENCES `player` (`Player_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`Player_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `player_skills`
--
ALTER TABLE `player_skills`
  ADD CONSTRAINT `player_skills_ibfk_1` FOREIGN KEY (`Player_ID`) REFERENCES `player` (`Player_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `plays_in`
--
ALTER TABLE `plays_in`
  ADD CONSTRAINT `plays_in_ibfk_1` FOREIGN KEY (`Regular_Player_ID`) REFERENCES `regular_player` (`Regular_Player_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `plays_in_ibfk_2` FOREIGN KEY (`Match_id`) REFERENCES `tournament_db`.`fixtures` (`Match_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `regular_player`
--
ALTER TABLE `regular_player`
  ADD CONSTRAINT `regular_player_ibfk_1` FOREIGN KEY (`Regular_Player_ID`) REFERENCES `player` (`Player_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `scouted_player`
--
ALTER TABLE `scouted_player`
  ADD CONSTRAINT `scouted_player_ibfk_1` FOREIGN KEY (`Scouted_Player_ID`) REFERENCES `player` (`Player_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `training_participation`
--
ALTER TABLE `training_participation`
  ADD CONSTRAINT `training_participation_ibfk_1` FOREIGN KEY (`Session_id`) REFERENCES `training_sessions` (`Session_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `training_participation_ibfk_2` FOREIGN KEY (`Player_ID`) REFERENCES `player` (`Player_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `training_participation_ibfk_3` FOREIGN KEY (`Coach_ID`) REFERENCES `coach` (`Coach_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
