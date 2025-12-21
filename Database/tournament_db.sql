-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2025 at 06:24 PM
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

--
-- Dumping data for table `fixtures`
--

INSERT INTO `fixtures` (`Match_id`, `Match_date`, `Match_time`, `Stadium`, `Match_status`, `Opponent`) VALUES
(1, '2025-09-15', '18:00:00', 'BRAC University Ground', 'Won', 'IUB FC'),
(2, '2025-09-22', '17:00:00', 'NSU Sports Complex', 'Lost', 'NSU FC'),
(3, '2025-09-29', '18:00:00', 'BRAC University Ground', 'Lost', 'DIU FC'),
(4, '2025-10-06', '15:00:00', 'Sylhet District Stadium', 'Lost', 'SUST FC'),
(5, '2025-10-13', '18:00:00', 'BRAC University Ground', 'Lost', 'BAU FC'),
(6, '2025-10-20', '16:00:00', 'UIU Sports Field', 'Lost', 'UIU FC'),
(7, '2025-10-27', '16:00:00', 'Jahangirnagar University Stadium', 'Lost', 'JUFC'),
(8, '2025-11-03', '15:00:00', 'Islamic University Stadium', 'Draw', 'IUB FC'),
(9, '2025-11-10', '18:00:00', 'Rajshahi Stadium', 'Won', 'RUFC'),
(10, '2025-11-17', '15:00:00', 'BRAC University Ground', 'Won', 'AUST FC'),
(11, '2025-11-24', '17:00:00', 'BRAC University Ground', 'Draw', 'JUFC'),
(12, '2025-12-01', '16:00:00', 'BRAC University Ground', 'Scheduled', 'DUFC'),
(13, '2025-12-08', '17:00:00', 'BAU Sports Complex', 'Scheduled', 'BAU FC'),
(14, '2025-12-15', '17:00:00', 'BRAC University Ground', 'Scheduled', 'BUET FC'),
(15, '2025-12-22', '15:00:00', 'BRAC University Ground', 'Scheduled', 'CUFC'),
(16, '2025-12-29', '15:00:00', 'BRAC University Ground', 'Scheduled', 'RUFC'),
(17, '2026-01-05', '16:00:00', 'BRAC University Ground', 'Scheduled', 'AIUB FC'),
(18, '2026-01-12', '16:00:00', 'BRAC University Ground', 'Scheduled', 'EWU FC'),
(19, '2026-01-19', '17:00:00', 'Dhaka University Stadium', 'Scheduled', 'DUFC'),
(20, '2026-01-26', '16:00:00', 'BUET Sports Complex', 'Scheduled', 'BUET FC'),
(21, '2026-02-02', '15:00:00', 'BRAC University Ground', 'Scheduled', 'IUB FC'),
(22, '2026-02-09', '17:00:00', 'BRAC University Ground', 'Scheduled', 'UIU FC'),
(23, '2026-02-16', '18:00:00', 'EWU Sports Ground', 'Scheduled', 'EWU FC'),
(24, '2026-02-23', '15:00:00', 'Chittagong MA Aziz Stadium', 'Scheduled', 'CUFC'),
(25, '2026-03-02', '16:00:00', 'Daffodil Sports Complex', 'Scheduled', 'DIU FC'),
(26, '2026-03-09', '18:00:00', 'BRAC University Ground', 'Scheduled', 'NSU FC'),
(27, '2026-03-16', '15:00:00', 'BRAC University Ground', 'Scheduled', 'SUST FC'),
(28, '2026-03-23', '18:00:00', 'IUB Sports Complex', 'Scheduled', 'IUB FC'),
(29, '2026-03-30', '16:00:00', 'AIUB Field', 'Scheduled', 'AIUB FC'),
(30, '2026-04-06', '15:00:00', 'AUST Ground', 'Scheduled', 'AUST FC');

-- --------------------------------------------------------

--
-- Table structure for table `generates`
--

CREATE TABLE `generates` (
  `Match_id` int(11) NOT NULL,
  `Team_Name` varchar(100) NOT NULL,
  `Score` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `generates`
--

INSERT INTO `generates` (`Match_id`, `Team_Name`, `Score`) VALUES
(1, 'BUFC', '3-0'),
(2, 'BUFC', '1-4'),
(3, 'BUFC', '0-4'),
(4, 'BUFC', '1-2'),
(5, 'BUFC', '1-2'),
(6, 'BUFC', '0-3'),
(7, 'BUFC', '2-3'),
(8, 'BUFC', '1-1'),
(9, 'BUFC', '2-1'),
(10, 'BUFC', '4-1'),
(11, 'BUFC', '3-3');

-- --------------------------------------------------------

--
-- Table structure for table `league_standings`
--

CREATE TABLE `league_standings` (
  `Team_Name` varchar(100) NOT NULL,
  `Points` int(11) DEFAULT 0,
  `Matches_Won` int(11) DEFAULT 0,
  `Matches_Drawn` int(11) DEFAULT 0,
  `Matches_Lost` int(11) DEFAULT 0,
  `Coach_Name` varchar(100) DEFAULT NULL,
  `Captain` varchar(100) DEFAULT NULL,
  `Most_Goal_Player` varchar(100) DEFAULT NULL,
  `Most_Assist_Player` varchar(100) DEFAULT NULL,
  `Best_Player` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `league_standings`
--

INSERT INTO `league_standings` (`Team_Name`, `Points`, `Matches_Won`, `Matches_Drawn`, `Matches_Lost`, `Coach_Name`, `Captain`, `Most_Goal_Player`, `Most_Assist_Player`, `Best_Player`) VALUES
('AIUB FC', 12, 3, 3, 5, 'Monirul Islam', 'Rubel Mia', 'Yasir Ali', 'Taskin Ahmed', 'Saif Hassan'),
('AUST FC', 18, 6, 0, 5, 'Akram Khan', 'Imrul Kayes', 'Mosaddek Hossain', 'Saifuddin Khan', 'Tushar Imran'),
('BAU FC', 16, 5, 1, 5, 'Manjural Islam', 'Arafat Sunny', 'Nazmul Hossain', 'Shafiul Islam', 'Farhad Reza'),
('BUET FC', 8, 2, 2, 7, 'Aminul Islam', 'Sakib Rahman', 'Ariful Islam', 'Najmul Islam', 'Shakil Ahmed'),
('BUFC', 11, 3, 2, 6, 'Nazmul Islam', 'Jamal Bhuyan', 'Rakib Hossain', 'Mohammad Ibrahim', 'Sumon Reza'),
('CUFC', 20, 6, 2, 3, 'Mahfuzul Haque', 'Taskin Mahmud', 'Anamul Haque', 'Shoriful Khan', 'Shamim Patwari'),
('DIU FC', 6, 1, 3, 7, 'Habibul Bashar', 'Shahriar Nafees', 'Sabbir Rahman', 'Rubel Hossain', 'Naeem Islam Jr'),
('DUFC', 15, 5, 0, 6, 'Khaled Mahmud', 'Fahim Ahmed', 'Imran Hossain', 'Mahmudul Joy', 'Rahim Uddin'),
('EWU FC', 28, 9, 1, 1, 'Masud Karim', 'Rakib Ahmed', 'Kamrul Hassan', 'Parvez Ahmed', 'Mahedi Hasan'),
('IIUC FC', 20, 6, 2, 3, 'Khaled Mashud', 'Nasir Hossain', 'Soumya Sarkar', 'Al-Amin Hossain', 'Raqibul Hasan'),
('IUB FC', 28, 9, 1, 1, 'Shahidul Rahman', 'Mushfiq Hasan', 'Shahriar Alam', 'Towhid Ridoy', 'Jaker Rahman'),
('JUFC', 26, 8, 2, 1, 'Rafiqul Alam', 'Tamim Hassan', 'Sohag Ahmed', 'Afif Hossain', 'Miraz Hossain'),
('NSU FC', 6, 1, 3, 7, 'Jahangir Kabir', 'Ashraful Karim', 'Taijul Rahman', 'Nurul Amin', 'Tanzid Tamim'),
('RUFC', 10, 2, 4, 5, 'Ashraful Amin', 'Sabbir Hossain', 'Shanto Mia', 'Ebadot Ali', 'Mim Mosaddek'),
('SUST FC', 9, 3, 0, 8, 'Rezaul Karim', 'Nafis Iqbal', 'Liton Ahmed', 'Hasan Mahmud', 'Yasir Rabbi'),
('UIU FC', 25, 8, 1, 2, 'Tariqul Islam', 'Soumya Khan', 'Naeem Islam', 'Tanzim Sakib', 'Tawhid Hridoy');

-- --------------------------------------------------------

--
-- Table structure for table `match_results`
--

CREATE TABLE `match_results` (
  `Match_id` int(11) NOT NULL,
  `MVP` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `match_results`
--

INSERT INTO `match_results` (`Match_id`, `MVP`) VALUES
(1, 'Rakib Hossain'),
(2, 'Junaid Khan'),
(3, 'Kamal Uddin'),
(4, 'Nasir Rahman'),
(5, 'Sabbir Ahmed'),
(6, 'Ahmed Hassan'),
(7, 'Arif Hossain'),
(8, 'Sumon Reza'),
(9, 'Mohammad Ibrahim'),
(10, 'Mohammad Ibrahim'),
(11, 'Shahin Alam');

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
-- Indexes for table `match_results`
--
ALTER TABLE `match_results`
  ADD PRIMARY KEY (`Match_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fixtures`
--
ALTER TABLE `fixtures`
  MODIFY `Match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `generates`
--
ALTER TABLE `generates`
  ADD CONSTRAINT `generates_ibfk_1` FOREIGN KEY (`Match_id`) REFERENCES `fixtures` (`Match_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `generates_ibfk_2` FOREIGN KEY (`Team_Name`) REFERENCES `league_standings` (`Team_Name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `match_results`
--
ALTER TABLE `match_results`
  ADD CONSTRAINT `match_results_ibfk_1` FOREIGN KEY (`Match_id`) REFERENCES `fixtures` (`Match_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
