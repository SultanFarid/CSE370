-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2025 at 02:26 PM
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
-- Database: `tournament_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `fixtures`
--

CREATE TABLE `fixtures` (
  `Match_id` int(11) NOT NULL,
  `Match_date` date NOT NULL,
  `Match_time` time NOT NULL,
  `Stadium` varchar(100) DEFAULT NULL,
  `Match_status` varchar(50) DEFAULT NULL,
  `Opponent` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `generates`
--

CREATE TABLE `generates` (
  `Match_id` int(11) NOT NULL,
  `Team_Name` varchar(100) NOT NULL,
  `Score` varchar(50) DEFAULT NULL,
  `MVP` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `league_standings`
--

CREATE TABLE `league_standings` (
  `Team_Name` varchar(100) NOT NULL,
  `Points` int(11) DEFAULT 0,
  `Coach_Name` varchar(100) DEFAULT NULL,
  `Overall_Record` varchar(50) DEFAULT NULL,
  `Captain` varchar(100) DEFAULT NULL,
  `Most_Goal_Player` varchar(100) DEFAULT NULL,
  `Most_Assist_Player` varchar(100) DEFAULT NULL,
  `Best_Player` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fixtures`
--
ALTER TABLE `fixtures`
  ADD PRIMARY KEY (`Match_id`);

--
-- Indexes for table `generates`
--
ALTER TABLE `generates`
  ADD PRIMARY KEY (`Match_id`,`Team_Name`),
  ADD KEY `Team_Name` (`Team_Name`);

--
-- Indexes for table `league_standings`
--
ALTER TABLE `league_standings`
  ADD PRIMARY KEY (`Team_Name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fixtures`
--
ALTER TABLE `fixtures`
  MODIFY `Match_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `generates`
--
ALTER TABLE `generates`
  ADD CONSTRAINT `generates_ibfk_1` FOREIGN KEY (`Match_id`) REFERENCES `fixtures` (`Match_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `generates_ibfk_2` FOREIGN KEY (`Team_Name`) REFERENCES `league_standings` (`Team_Name`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
