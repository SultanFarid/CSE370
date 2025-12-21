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
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `Doctor_Name` varchar(100) NOT NULL,
  `Hospital` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`Doctor_Name`, `Hospital`) VALUES
('Dr. Abu Saleh', 'Club Clinic'),
('Dr. Ananda', 'United Hospital'),
('Dr. Nashir Uddin', 'Apollo Dhaka'),
('Dr. Robert Smith', 'Evercare Hospital'),
('Dr. Sarah Khan', 'United Hospital');

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

--
-- Dumping data for table `medical_record`
--

INSERT INTO `medical_record` (`Prescription_ID`, `Player_ID`, `Doctor_in_charge`, `Recovery_status`, `Hospital`, `Injury_Type`, `Injured_from`, `Injured_to`) VALUES
(1, 11, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Groin Pull', '2023-11-05', '2023-11-25'),
(2, 11, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Rib Contusion', '2024-02-14', '2024-03-01'),
(3, 11, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Wrist Sprain', '2023-05-10', '2023-05-25'),
(4, 12, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Muscle Fatigue', '2023-08-01', '2023-08-05'),
(5, 13, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Ankle Sprain', '2022-03-10', '2022-04-05'),
(6, 13, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Concussion', '2023-09-15', '2023-09-25'),
(7, 13, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Thigh Strain', '2024-01-10', '2024-01-20'),
(8, 14, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Dislocated Finger', '2022-08-01', '2022-09-01'),
(9, 14, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Lower Back Pain', '2023-05-10', '2023-05-20'),
(10, 14, 'Dr. Robert Smith', 'Critical', 'Evercare Hospital', 'ACL Tear', '2024-11-01', '2025-05-01'),
(11, 15, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Ankle Sprain', '2023-11-10', '2023-12-01'),
(12, 15, 'Dr. Abu Saleh', 'Healed', 'Club Clinic', 'Shin Bruise', '2022-06-15', '2022-06-25'),
(13, 16, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Calf Cramps', '2023-02-10', '2023-02-15'),
(14, 16, 'Dr. Nashir Uddin', 'Rehabilitating', 'Apollo Dhaka', 'Hamstring Strain', '2024-10-15', '2024-12-20'),
(15, 17, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Hamstring Tightness', '2022-12-01', '2022-12-15'),
(16, 17, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Hamstring Strain', '2023-04-10', '2023-05-01'),
(17, 17, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Shin Splints', '2023-09-01', '2023-09-15'),
(18, 18, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Lower Back Pain', '2024-03-10', '2024-03-20'),
(19, 19, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Thumb Fracture', '2023-06-01', '2023-07-01'),
(20, 20, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Quad Strain', '2024-02-15', '2024-03-01'),
(21, 20, 'Dr. Abu Saleh', 'Healed', 'Club Clinic', 'Dehydration', '2022-05-20', '2022-05-25'),
(22, 21, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Knee Scrape', '2023-01-10', '2023-01-20'),
(23, 21, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Ankle Knock', '2024-12-01', '2024-12-07'),
(24, 22, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Cut on Eyebrow', '2023-12-20', '2024-01-05'),
(25, 23, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Viral Fever', '2024-05-01', '2024-05-10'),
(26, 24, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Ankle Twist', '2022-11-10', '2022-11-25'),
(27, 24, 'Dr. Nashir Uddin', 'Physiotherapy', 'Apollo Dhaka', 'Groin Strain', '2024-11-28', '2024-12-10'),
(28, 25, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Hamstring Tightness', '2023-10-05', '2023-10-12'),
(29, 26, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Toe Injury', '2024-04-15', '2024-04-30'),
(30, 26, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Mild Concussion', '2022-09-01', '2022-09-10'),
(31, 27, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Knee Pain', '2023-01-05', '2023-01-15'),
(32, 27, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Calf Strain', '2024-11-25', '2024-12-15'),
(33, 28, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Flu', '2023-06-10', '2023-06-17'),
(34, 28, 'Dr. Sarah Khan', 'Light Training', 'United Hospital', 'Concussion', '2024-11-10', '2024-12-05'),
(35, 29, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Rib Contusion', '2023-07-20', '2023-08-10'),
(36, 30, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Ankle Sprain', '2022-12-01', '2023-01-01'),
(37, 30, 'Dr. Robert Smith', 'Post-Surgery', 'Evercare Hospital', 'Metatarsal Fracture', '2024-09-01', '2025-01-01'),
(38, 31, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Wrist Fracture', '2021-05-15', '2021-07-01'),
(39, 31, 'Dr. Nashir Uddin', 'Treatment', 'Apollo Dhaka', 'Shoulder Dislocation', '2024-11-20', '2025-01-15'),
(40, 32, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Achilles Tendonitis', '2023-02-01', '2023-03-01'),
(41, 33, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Hip Flexor Strain', '2024-01-10', '2024-01-25'),
(42, 34, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Dehydration', '2023-06-15', '2023-06-18'),
(43, 35, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Sprained Finger', '2023-12-01', '2023-12-10'),
(44, 36, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Knee Ligament Strain', '2023-04-01', '2023-05-01'),
(45, 37, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Concussion', '2023-09-10', '2023-09-25'),
(46, 38, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Elbow Brusie', '2024-02-20', '2024-02-28'),
(47, 39, 'Dr. Robert Smith', 'Healed', 'Evercare Hospital', 'Groin Pull', '2023-11-05', '2023-11-20'),
(48, 40, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Ankle Twist', '2024-01-05', '2024-01-20'),
(49, 41, 'Dr. Abu Saleh', 'Healed', 'Club Clinic', 'Meniscus Tear', '2022-01-01', '2022-06-01'),
(50, 42, 'Dr. Abu Saleh', 'Healed', 'Club Clinic', 'Shin Splints', '2023-01-01', '2023-02-01'),
(51, 42, 'Dr. Abu Saleh', 'Healed', 'Club Clinic', 'Ankle Twist', '2023-08-15', '2023-08-25'),
(52, 43, 'Dr. Nashir Uddin', 'Healed', 'Apollo Dhaka', 'Arm Fracture', '2023-03-10', '2023-05-10'),
(53, 45, 'Dr. Sarah Khan', 'Healed', 'United Hospital', 'Nose Fracture', '2023-11-01', '2023-11-20'),
(54, 27, 'Dr. Ananda', 'Healed', 'United Hospital', 'ACL Tear', '2025-12-05', '2025-12-13');

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
(21, 'Defender', 'Left', 174, 69, 'Fit'),
(22, 'Defender', 'Right', 180, 76, 'Fit'),
(23, 'Striker', 'Right', 180, 76, 'Fit'),
(24, 'Striker', 'Left', 178, 74, 'Doubtful'),
(25, 'Striker', 'Right', 179, 75, 'Fit'),
(26, 'Striker', 'Right', 176, 71, 'Fit'),
(27, 'Striker', 'Left', 174, 70, 'Fit'),
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
(44, 'Striker', 'Both', 168, 62, 'Fit'),
(45, 'Goalkeeper', 'Right', 182, 76, 'Fit'),
(48, 'Midfielder', 'Right', 181, 69, 'Recovering'),
(49, 'Defender', 'Left', 185, 71, 'Recovering');

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
  `Goals_Scored` int(11) DEFAULT 0,
  `Status` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plays_in`
--

INSERT INTO `plays_in` (`Regular_Player_ID`, `Match_id`, `Rating`, `Goals_Scored`, `Status`) VALUES
(11, 1, 6.7, 0, 'Substituted'),
(11, 3, 6.7, 0, 'Substituted'),
(11, 4, 6.1, 0, 'Started'),
(11, 7, 6.2, 0, 'Started'),
(11, 8, 6.7, 0, 'Started'),
(11, 11, 7.0, 0, 'Started'),
(12, 1, 7.5, 0, 'Started'),
(12, 2, 6.5, 0, 'Substituted'),
(12, 3, 6.2, 0, 'Started'),
(12, 7, 6.2, 0, 'Substituted'),
(12, 8, 6.8, 0, 'Started'),
(12, 10, 6.3, 0, 'Substituted'),
(13, 1, 8.5, 0, 'Started'),
(13, 2, 6.6, 0, 'Started'),
(13, 3, 6.4, 0, 'Started'),
(13, 4, 6.4, 0, 'Started'),
(13, 5, 6.9, 0, 'Substituted'),
(13, 6, 6.1, 0, 'Substituted'),
(13, 7, 6.3, 0, 'Started'),
(13, 9, 6.9, 0, 'Substituted'),
(13, 10, 6.7, 0, 'Substituted'),
(14, 1, 8.1, 0, 'Started'),
(14, 2, 7.0, 0, 'Started'),
(14, 5, 6.8, 0, 'Started'),
(14, 7, 6.8, 0, 'Substituted'),
(14, 8, 6.5, 0, 'Substituted'),
(14, 9, 6.1, 0, 'Substituted'),
(14, 10, 7.8, 0, 'Started'),
(15, 1, 6.8, 0, 'Substituted'),
(15, 2, 6.5, 0, 'Started'),
(15, 3, 6.2, 0, 'Started'),
(15, 4, 6.2, 0, 'Started'),
(15, 5, 6.5, 0, 'Started'),
(15, 6, 6.4, 0, 'Started'),
(15, 8, 6.6, 0, 'Started'),
(15, 11, 7.8, 1, 'Started'),
(16, 2, 6.5, 0, 'Started'),
(16, 4, 6.1, 0, 'Substituted'),
(16, 5, 6.5, 0, 'Started'),
(16, 6, 6.7, 0, 'Substituted'),
(16, 9, 6.7, 0, 'Substituted'),
(16, 11, 6.9, 0, 'Started'),
(17, 1, 7.3, 0, 'Started'),
(17, 3, 6.4, 0, 'Substituted'),
(17, 4, 6.1, 0, 'Started'),
(17, 5, 6.4, 0, 'Substituted'),
(17, 7, 7.0, 0, 'Started'),
(18, 2, 6.1, 0, 'Started'),
(18, 3, 6.3, 0, 'Substituted'),
(18, 5, 6.4, 0, 'Substituted'),
(18, 6, 6.4, 0, 'Substituted'),
(18, 7, 6.7, 0, 'Started'),
(18, 8, 6.9, 0, 'Substituted'),
(18, 9, 7.5, 0, 'Started'),
(18, 10, 8.2, 0, 'Started'),
(18, 11, 6.5, 0, 'Started'),
(19, 1, 7.9, 1, 'Substituted'),
(19, 2, 6.3, 0, 'Substituted'),
(19, 5, 6.5, 0, 'Substituted'),
(19, 6, 6.2, 0, 'Started'),
(19, 8, 6.3, 0, 'Substituted'),
(19, 9, 6.4, 0, 'Substituted'),
(19, 10, 7.6, 0, 'Started'),
(20, 1, 7.3, 0, 'Started'),
(20, 4, 6.1, 0, 'Started'),
(20, 5, 6.2, 0, 'Started'),
(20, 6, 6.1, 0, 'Started'),
(20, 8, 6.3, 0, 'Substituted'),
(20, 9, 7.5, 0, 'Started'),
(20, 11, 6.7, 0, 'Started'),
(21, 1, 8.0, 0, 'Started'),
(21, 2, 6.5, 0, 'Started'),
(21, 4, 6.5, 0, 'Started'),
(21, 6, 6.7, 0, 'Started'),
(21, 7, 6.4, 0, 'Substituted'),
(21, 10, 7.9, 0, 'Started'),
(21, 11, 6.5, 0, 'Substituted'),
(22, 1, 6.9, 0, 'Substituted'),
(22, 2, 6.7, 0, 'Started'),
(22, 3, 6.1, 0, 'Substituted'),
(22, 6, 7.0, 0, 'Substituted'),
(22, 8, 6.2, 0, 'Substituted'),
(22, 10, 6.6, 0, 'Substituted'),
(22, 11, 6.6, 0, 'Started'),
(23, 1, 9.0, 1, 'Started'),
(23, 2, 7.6, 1, 'Started'),
(23, 3, 6.7, 0, 'Started'),
(23, 4, 6.6, 0, 'Substituted'),
(23, 5, 6.3, 0, 'Started'),
(23, 6, 6.5, 0, 'Started'),
(23, 7, 6.2, 0, 'Substituted'),
(23, 9, 8.1, 0, 'Started'),
(23, 11, 6.7, 0, 'Substituted'),
(24, 1, 7.4, 0, 'Started'),
(24, 4, 7.4, 1, 'Started'),
(24, 5, 6.5, 0, 'Substituted'),
(24, 6, 6.4, 0, 'Substituted'),
(24, 7, 6.0, 0, 'Substituted'),
(24, 10, 7.2, 0, 'Started'),
(25, 1, 7.0, 0, 'Substituted'),
(25, 3, 6.7, 0, 'Substituted'),
(25, 5, 6.7, 0, 'Started'),
(25, 6, 6.5, 0, 'Started'),
(25, 7, 6.8, 0, 'Substituted'),
(25, 8, 7.6, 1, 'Started'),
(25, 9, 8.2, 0, 'Started'),
(25, 10, 8.1, 0, 'Started'),
(25, 11, 6.2, 0, 'Substituted'),
(26, 2, 6.3, 0, 'Substituted'),
(26, 3, 6.0, 0, 'Started'),
(26, 6, 6.1, 0, 'Substituted'),
(26, 7, 6.4, 0, 'Started'),
(26, 10, 8.8, 1, 'Started'),
(26, 11, 6.6, 0, 'Started'),
(27, 1, 7.7, 0, 'Started'),
(27, 2, 6.5, 0, 'Started'),
(27, 5, 6.1, 0, 'Substituted'),
(27, 7, 7.0, 0, 'Started'),
(27, 8, 6.9, 0, 'Substituted'),
(27, 9, 6.6, 0, 'Substituted'),
(27, 11, 7.4, 1, 'Started'),
(28, 2, 6.6, 0, 'Substituted'),
(28, 3, 6.1, 0, 'Started'),
(28, 4, 6.1, 0, 'Substituted'),
(28, 5, 7.3, 1, 'Started'),
(28, 6, 6.1, 0, 'Started'),
(28, 7, 6.5, 0, 'Substituted'),
(28, 8, 6.7, 0, 'Substituted'),
(28, 9, 6.1, 0, 'Substituted'),
(28, 10, 7.6, 1, 'Substituted'),
(29, 2, 6.5, 0, 'Started'),
(29, 3, 6.4, 0, 'Substituted'),
(29, 4, 6.1, 0, 'Started'),
(29, 5, 6.5, 0, 'Started'),
(29, 6, 6.0, 0, 'Substituted'),
(29, 7, 6.2, 0, 'Substituted'),
(29, 8, 6.0, 0, 'Substituted'),
(29, 9, 6.7, 0, 'Substituted'),
(29, 11, 7.8, 1, 'Started'),
(30, 1, 8.4, 0, 'Started'),
(30, 2, 6.0, 0, 'Started'),
(30, 3, 6.0, 0, 'Substituted'),
(30, 4, 6.5, 0, 'Substituted'),
(30, 8, 7.0, 0, 'Started'),
(30, 9, 9.0, 1, 'Started'),
(30, 10, 7.6, 2, 'Substituted'),
(30, 11, 6.7, 0, 'Started'),
(31, 4, 6.4, 0, 'Substituted'),
(31, 5, 6.3, 0, 'Started'),
(31, 6, 6.2, 0, 'Substituted'),
(31, 7, 6.9, 0, 'Started'),
(31, 8, 7.2, 0, 'Started'),
(31, 9, 7.6, 0, 'Started'),
(31, 10, 8.1, 0, 'Started'),
(31, 11, 7.1, 0, 'Started'),
(32, 1, 6.7, 0, 'Substituted'),
(32, 3, 6.7, 0, 'Started'),
(32, 4, 6.6, 0, 'Started'),
(32, 5, 6.5, 0, 'Substituted'),
(32, 6, 6.1, 0, 'Started'),
(32, 8, 6.6, 0, 'Started'),
(32, 9, 7.5, 0, 'Started'),
(32, 10, 8.2, 0, 'Started'),
(32, 11, 6.6, 0, 'Substituted'),
(33, 1, 6.5, 0, 'Substituted'),
(33, 3, 6.9, 0, 'Substituted'),
(33, 4, 6.9, 0, 'Substituted'),
(33, 5, 6.0, 0, 'Started'),
(33, 6, 6.1, 0, 'Started'),
(33, 7, 6.5, 0, 'Started'),
(33, 8, 6.7, 0, 'Started'),
(33, 10, 7.3, 0, 'Started'),
(34, 1, 6.6, 0, 'Substituted'),
(34, 2, 6.3, 0, 'Substituted'),
(34, 3, 6.5, 0, 'Started'),
(34, 7, 6.4, 0, 'Started'),
(34, 9, 8.4, 0, 'Started'),
(34, 10, 6.6, 0, 'Substituted'),
(34, 11, 6.3, 0, 'Substituted'),
(35, 1, 6.1, 0, 'Substituted'),
(35, 4, 6.3, 0, 'Started'),
(35, 7, 7.1, 2, 'Started'),
(35, 8, 6.8, 0, 'Started'),
(35, 9, 7.1, 0, 'Started'),
(35, 10, 6.3, 0, 'Substituted'),
(36, 3, 6.7, 0, 'Started'),
(36, 6, 6.7, 0, 'Substituted'),
(36, 8, 6.5, 0, 'Started'),
(36, 9, 6.5, 0, 'Substituted'),
(36, 10, 6.1, 0, 'Substituted'),
(36, 11, 6.5, 0, 'Substituted'),
(37, 2, 6.8, 0, 'Substituted'),
(37, 3, 6.9, 0, 'Started'),
(37, 4, 7.0, 0, 'Substituted'),
(37, 5, 6.8, 0, 'Substituted'),
(37, 9, 8.9, 1, 'Started'),
(37, 10, 7.2, 0, 'Started'),
(37, 11, 6.6, 0, 'Substituted'),
(38, 2, 6.7, 0, 'Substituted'),
(38, 3, 7.0, 0, 'Started'),
(38, 4, 6.4, 0, 'Substituted'),
(38, 5, 6.9, 0, 'Substituted'),
(38, 6, 6.1, 0, 'Started'),
(38, 7, 6.4, 0, 'Substituted'),
(38, 9, 8.5, 0, 'Started'),
(38, 10, 6.2, 0, 'Substituted'),
(38, 11, 6.0, 0, 'Substituted'),
(39, 1, 8.2, 1, 'Started'),
(39, 2, 6.5, 0, 'Substituted'),
(39, 3, 6.4, 0, 'Substituted'),
(39, 4, 7.0, 0, 'Substituted'),
(39, 8, 6.6, 0, 'Substituted'),
(40, 2, 6.1, 0, 'Substituted'),
(40, 4, 6.4, 0, 'Started'),
(40, 5, 7.0, 0, 'Started'),
(40, 6, 6.3, 0, 'Started'),
(40, 7, 6.1, 0, 'Started'),
(40, 8, 7.4, 0, 'Started'),
(40, 9, 6.4, 0, 'Substituted'),
(40, 11, 6.7, 0, 'Substituted');

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
(11, 1, 0, 16),
(12, 2, 1, 13),
(13, 3, 3, 15),
(14, 22, 0, 10),
(15, 10, 8, 15),
(16, 4, 0, 13),
(17, 7, 5, 15),
(18, 8, 3, 15),
(19, 11, 2, 14),
(20, 5, 1, 11),
(21, 12, 0, 9),
(22, 13, 0, 9),
(23, 9, 6, 13),
(24, 25, 4, 10),
(25, 26, 3, 10),
(26, 27, 2, 9),
(27, 28, 1, 7),
(28, 6, 1, 10),
(29, 18, 0, 10),
(30, 19, 0, 8),
(31, 14, 0, 5),
(32, 15, 0, 7),
(33, 16, 1, 8),
(34, 17, 0, 5),
(35, 29, 0, 5),
(36, 20, 2, 12),
(37, 21, 2, 8),
(38, 30, 0, 3),
(39, 23, 0, 6),
(40, 24, 0, 4);

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
(44, 'School Team', 'Sylhet School', 'Very fast winger, can play on both sides.', 'Trialing'),
(45, 'U-19 Divisional', 'Barisal Divisional Team', 'Good reflexes and shot stopping ability.', 'Trialing'),
(48, '5 years', 'NSU FC Academy', 'I am a good striker with aggressive mentality.', 'Trialing'),
(49, '5 Years', 'IUB FC Academy', 'I am a no nonsense defender,', 'Rejected');

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
  `Coach_remarks` text DEFAULT NULL,
  `participation_status` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_participation`
--

INSERT INTO `training_participation` (`Session_id`, `Player_ID`, `Coach_ID`, `Technical_score`, `Physical_score`, `Tactical_score`, `Coach_remarks`, `participation_status`) VALUES
(1, 11, 1, 7.5, 8.9, 8.7, 'Strong work ethic', 'Attended'),
(1, 13, 1, 7.0, 9.3, 6.4, 'Excellent performance', 'Attended'),
(1, 16, 1, 6.8, 6.1, 6.6, 'Limited training - recovering from injury', 'Attended'),
(1, 17, 1, 8.5, 6.5, 7.6, 'Excellent performance', 'Attended'),
(1, 20, 1, 7.1, 7.3, 7.9, 'Needs to work on positioning', 'Attended'),
(1, 21, 1, 7.7, 7.9, 6.6, 'Strong work ethic', 'Attended'),
(1, 22, 1, 7.5, 8.6, 6.4, 'Good improvement shown', 'Attended'),
(1, 23, 1, 8.0, 7.7, 8.0, 'Needs to work on positioning', 'Attended'),
(1, 25, 1, 8.0, 9.4, 7.6, 'Great technical ability', 'Attended'),
(1, 26, 1, 8.6, 6.8, 6.7, 'Solid performance overall', 'Attended'),
(1, 29, 1, 8.2, 8.3, 7.6, 'Good improvement shown', 'Attended'),
(1, 32, 1, 7.5, 8.1, 6.6, 'Good improvement shown', 'Attended'),
(1, 35, 1, 6.6, 7.4, 6.8, 'Excellent performance', 'Attended'),
(1, 36, 1, 9.2, 8.0, 6.7, 'Shows good potential', 'Attended'),
(1, 37, 1, 6.5, 8.7, 8.9, 'Needs to work on positioning', 'Attended'),
(1, 38, 1, 6.9, 7.7, 7.2, 'Shows good potential', 'Attended'),
(1, 39, 1, 8.3, 6.9, 6.8, 'Great technical ability', 'Attended'),
(1, 42, 1, 6.1, 6.3, 7.7, 'Adapting well to training', 'Attended'),
(1, 44, 1, 6.5, 6.6, 6.0, 'Promising young talent', 'Attended'),
(1, 45, 1, 6.6, 8.1, 7.8, 'Promising young talent', 'Attended'),
(2, 12, 4, 9.1, 7.3, 6.9, 'Solid performance overall', 'Attended'),
(2, 13, 4, 7.3, 6.8, 9.0, 'Solid performance overall', 'Attended'),
(2, 15, 4, 6.8, 9.2, 6.7, 'Good improvement shown', 'Attended'),
(2, 16, 4, 6.9, 5.1, 6.0, 'Limited training - recovering from injury', 'Attended'),
(2, 18, 4, 9.0, 8.1, 6.1, 'Good improvement shown', 'Attended'),
(2, 19, 4, 7.8, 8.5, 7.3, 'Strong work ethic', 'Attended'),
(2, 20, 4, 7.3, 7.8, 6.7, 'Shows good potential', 'Attended'),
(2, 25, 4, 9.1, 9.0, 6.3, 'Solid performance overall', 'Attended'),
(2, 27, 4, 9.1, 9.1, 6.7, 'Great technical ability', 'Attended'),
(2, 28, 4, 6.8, 6.6, 6.7, 'Limited training - recovering from injury', 'Attended'),
(2, 33, 4, 9.1, 9.1, 7.4, 'Excellent performance', 'Attended'),
(2, 34, 4, 9.4, 9.2, 6.0, 'Strong work ethic', 'Attended'),
(2, 37, 4, NULL, NULL, NULL, NULL, 'Absent'),
(2, 38, 4, 8.7, 7.5, 6.4, 'Great technical ability', 'Attended'),
(2, 39, 4, 7.3, 9.3, 7.2, 'Strong work ethic', 'Attended'),
(2, 40, 4, NULL, NULL, NULL, NULL, 'Absent'),
(3, 11, 6, 8.3, 8.0, 8.7, 'Solid performance overall', 'Attended'),
(3, 12, 6, 9.0, 9.4, 8.2, 'Needs to work on positioning', 'Attended'),
(3, 15, 6, 8.8, 8.2, 6.3, 'Strong work ethic', 'Attended'),
(3, 17, 6, 7.4, 6.8, 7.6, 'Strong work ethic', 'Attended'),
(3, 18, 6, 7.8, 6.5, 6.7, 'Great technical ability', 'Attended'),
(3, 19, 6, 7.9, 8.7, 7.3, 'Good improvement shown', 'Attended'),
(3, 21, 6, 7.6, 8.6, 7.2, 'Needs to work on positioning', 'Attended'),
(3, 22, 6, 6.7, 9.5, 8.9, 'Needs to work on positioning', 'Attended'),
(3, 23, 6, NULL, NULL, NULL, NULL, 'Absent'),
(3, 24, 6, 5.2, 4.6, 6.6, 'Light training due to doubtful fitness', 'Attended'),
(3, 28, 6, 5.8, 6.8, 6.8, 'Limited training - recovering from injury', 'Attended'),
(3, 33, 6, 9.5, 6.5, 7.2, 'Shows good potential', 'Attended'),
(3, 35, 6, 7.5, 7.5, 6.1, 'Great technical ability', 'Attended'),
(3, 36, 6, 7.9, 7.4, 8.7, 'Shows good potential', 'Attended'),
(3, 38, 6, 9.5, 8.7, 7.0, 'Excellent performance', 'Attended'),
(3, 45, 6, 7.1, 7.1, 5.4, 'Needs more experience', 'Attended'),
(4, 11, 2, NULL, NULL, NULL, NULL, 'Absent'),
(4, 13, 2, 9.2, 7.8, 6.3, 'Shows good potential', 'Attended'),
(4, 17, 2, 8.0, 8.1, 7.1, 'Excellent performance', 'Attended'),
(4, 19, 2, 6.6, 9.1, 8.1, 'Strong work ethic', 'Attended'),
(4, 22, 2, 8.1, 8.7, 8.6, 'Good improvement shown', 'Attended'),
(4, 23, 2, 8.1, 6.5, 8.4, 'Good improvement shown', 'Attended'),
(4, 25, 2, 8.1, 6.7, 7.6, 'Strong work ethic', 'Attended'),
(4, 26, 2, 9.1, 8.2, 8.5, 'Great technical ability', 'Attended'),
(4, 27, 2, 6.8, 8.5, 6.3, 'Excellent performance', 'Attended'),
(4, 29, 2, 7.8, 8.7, 6.4, 'Solid performance overall', 'Attended'),
(4, 32, 2, 6.6, 8.9, 7.1, 'Solid performance overall', 'Attended'),
(4, 33, 2, 7.2, 7.6, 7.2, 'Excellent performance', 'Attended'),
(4, 34, 2, 8.8, 8.2, 7.1, 'Strong work ethic', 'Attended'),
(4, 35, 2, 7.3, 7.8, 7.5, 'Needs to work on positioning', 'Attended'),
(4, 37, 2, 8.9, 9.3, 6.5, 'Good improvement shown', 'Attended'),
(4, 39, 2, 7.8, 7.5, 7.9, 'Excellent performance', 'Attended'),
(4, 40, 2, 6.9, 7.7, 7.9, 'Strong work ethic', 'Attended'),
(4, 42, 2, 5.9, 6.7, 5.8, 'Needs more experience', 'Attended'),
(4, 44, 2, 7.9, 6.8, 7.7, 'Needs tactical improvement', 'Attended'),
(4, 48, 2, 6.2, 6.4, 5.8, 'Limited training - recovering from injury', 'Attended'),
(5, 12, 1, 6.9, 7.5, 6.8, 'Great technical ability', 'Attended'),
(5, 15, 1, 7.2, 8.2, 8.0, 'Solid performance overall', 'Attended'),
(5, 18, 1, 7.5, 8.5, 6.1, 'Great technical ability', 'Attended'),
(5, 20, 1, 9.5, 8.7, 7.6, 'Shows good potential', 'Attended'),
(5, 21, 1, 8.1, 6.9, 7.3, 'Good improvement shown', 'Attended'),
(5, 26, 1, 8.7, 9.3, 6.5, 'Good improvement shown', 'Attended'),
(5, 27, 1, 8.3, 9.2, 7.6, 'Strong work ethic', 'Attended'),
(5, 29, 1, NULL, NULL, NULL, NULL, 'Absent'),
(5, 32, 1, 6.7, 8.6, 8.8, 'Solid performance overall', 'Attended'),
(5, 34, 1, 6.7, 7.1, 9.0, 'Shows good potential', 'Attended'),
(5, 36, 1, 9.4, 9.0, 8.0, 'Shows good potential', 'Attended'),
(5, 40, 1, 9.0, 9.0, 7.6, 'Shows good potential', 'Attended'),
(5, 42, 1, 7.7, 7.6, 6.9, 'Needs tactical improvement', 'Attended'),
(5, 44, 1, 7.3, 7.8, 5.2, 'Adapting well to training', 'Attended'),
(5, 45, 1, 6.3, 6.9, 5.6, 'Shows potential for development', 'Attended'),
(6, 11, 1, 8.2, 7.2, 7.9, 'Consistent effort', 'Attended'),
(6, 12, 1, 7.0, 8.6, 9.2, 'Excellent technical ability', 'Attended'),
(6, 13, 1, 7.6, 8.6, 8.9, 'Shows improvement', 'Attended'),
(6, 15, 1, 8.3, 7.6, 7.3, 'Needs improvement in passing', 'Attended'),
(6, 17, 1, 7.7, 7.3, 7.0, 'Needs focus on positioning', 'Attended'),
(6, 19, 1, 7.7, 8.1, 7.5, 'Great decision making', 'Attended'),
(6, 20, 1, 7.1, 8.4, 9.0, 'Shows improvement', 'Attended'),
(6, 21, 1, 6.8, 8.4, 7.9, 'Good performance overall', 'Attended'),
(6, 22, 1, 7.7, 8.0, 8.3, 'Great decision making', 'Attended'),
(6, 23, 1, 7.2, 8.6, 6.1, 'Solid work ethic', 'Attended'),
(6, 24, 1, 6.2, 6.9, 5.5, 'Consistent effort', 'Attended'),
(6, 25, 1, 7.8, 8.6, 6.0, 'Consistent effort', 'Attended'),
(6, 27, 1, 8.7, 8.1, 6.5, 'Excellent technical ability', 'Attended'),
(6, 29, 1, 7.7, 8.3, 8.8, 'Great decision making', 'Attended'),
(6, 32, 1, 6.1, 7.2, 8.8, 'Shows good potential', 'Attended'),
(6, 33, 1, 7.5, 7.1, 9.3, 'Shows good potential', 'Attended'),
(6, 34, 1, 8.2, 7.0, 9.4, 'Solid work ethic', 'Attended'),
(6, 35, 1, 7.9, 8.5, 6.8, 'Shows improvement', 'Attended'),
(6, 36, 1, 7.5, 6.6, 7.8, 'Good performance overall', 'Attended'),
(6, 38, 1, 7.1, 7.6, 8.0, 'Shows improvement', 'Attended'),
(6, 39, 1, 8.8, 6.8, 7.5, 'Solid work ethic', 'Attended'),
(6, 40, 1, 9.2, 7.9, 7.9, 'Consistent effort', 'Attended'),
(6, 44, 1, 8.6, 8.4, 8.0, 'Good performance overall', 'Attended'),
(6, 45, 1, 7.8, 6.8, 7.4, 'Shows good potential', 'Attended'),
(6, 48, 1, 6.5, 5.1, 7.7, 'Strong performance today', 'Attended'),
(7, 12, 4, 8.1, 8.5, 9.3, 'Needs to be more consistent', 'Attended'),
(7, 15, 4, 8.8, 8.5, 7.3, 'Fast and agile', 'Attended'),
(7, 17, 4, 7.8, 7.7, 7.6, 'Lacked concentration late on', 'Attended'),
(7, 18, 4, 8.4, 8.1, 7.9, 'Good performance overall', 'Attended'),
(7, 20, 4, 6.5, 8.3, 8.3, 'Shows improvement', 'Attended'),
(7, 21, 4, 8.2, 8.5, 8.0, 'Excellent technical ability', 'Attended'),
(7, 22, 4, 6.8, 8.1, 8.2, 'Great ball control', 'Attended'),
(7, 25, 4, 9.0, 8.9, 7.2, 'Good performance overall', 'Attended'),
(7, 26, 4, 7.2, 8.8, 6.7, 'Lacked pace in key moments', 'Attended'),
(7, 28, 4, 6.7, 5.0, 7.5, 'Solid defensive display', 'Attended'),
(7, 33, 4, 7.3, 8.1, 7.8, 'Impact player off the bench', 'Attended'),
(7, 34, 4, 7.5, 8.0, 9.4, 'Strong performance today', 'Attended'),
(7, 37, 4, 9.0, 8.3, 7.9, 'Tackling was timed perfectly', 'Attended'),
(7, 39, 4, 7.7, 7.5, 7.3, 'Good performance overall', 'Attended'),
(7, 42, 4, 9.1, 8.1, 8.8, 'Remained calm under pressure', 'Attended'),
(8, 11, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 13, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 16, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 18, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 19, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 20, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 23, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 25, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 26, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 27, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 28, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 29, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 32, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 34, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 35, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 36, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 37, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 38, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 40, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 42, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 44, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(8, 45, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(9, 11, 2, 7.1, 7.7, 6.7, 'High energy levels', 'Attended'),
(9, 12, 2, 6.5, 7.8, 9.2, 'Impact player off the bench', 'Attended'),
(9, 13, 2, 7.0, 7.8, 8.1, 'Clinical finishing today', 'Attended'),
(9, 15, 2, 7.5, 8.9, 7.9, 'Needs to improve stamina', 'Attended'),
(9, 17, 2, 8.0, 7.7, 8.2, 'Training efforts paying off', 'Attended'),
(9, 18, 2, 8.3, 8.2, 7.5, 'Precise passing range', 'Attended'),
(9, 19, 2, 8.2, 8.5, 8.3, 'Needs to be more consistent', 'Attended'),
(9, 21, 2, 8.2, 8.6, 8.3, 'Needs focus on positioning', 'Attended'),
(9, 22, 2, 6.3, 7.7, 9.3, 'Needs to improve stamina', 'Attended'),
(9, 23, 2, 8.4, 9.5, 7.0, 'Needs to track back more', 'Attended'),
(9, 26, 2, 8.1, 8.8, 7.5, 'Needs to improve stamina', 'Attended'),
(9, 27, 2, 7.3, 8.4, 7.5, 'Tackling was timed perfectly', 'Attended'),
(9, 29, 2, 8.2, 6.6, 7.1, 'Outworked the opposition', 'Attended'),
(9, 32, 2, 7.2, 7.8, 9.0, 'Tactically disciplined', 'Attended'),
(9, 33, 2, 6.7, 7.0, 9.2, 'Lacked concentration late on', 'Attended'),
(9, 35, 2, 7.0, 8.2, 7.1, 'Great ball control', 'Attended'),
(9, 36, 2, 9.0, 7.3, 8.3, 'Man of the match performance', 'Attended'),
(9, 37, 2, 8.1, 7.8, 8.7, 'Quiet game today', 'Attended'),
(9, 38, 2, 9.4, 7.0, 7.3, 'Quiet game today', 'Attended'),
(9, 39, 2, 8.3, 6.9, 9.0, 'Tactically disciplined', 'Attended'),
(9, 40, 2, 8.0, 7.8, 7.9, 'Needs focus on positioning', 'Attended'),
(9, 42, 2, 8.3, 7.9, 8.2, 'Needs better communication', 'Attended'),
(9, 44, 2, 7.5, 8.4, 7.4, 'Needs focus on positioning', 'Attended'),
(9, 45, 2, 8.1, 7.8, 7.1, 'Good performance overall', 'Attended'),
(10, 14, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(10, 16, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(10, 30, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(10, 31, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(10, 48, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 11, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 12, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 15, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 16, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 19, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 20, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 22, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 23, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 27, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 29, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 34, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 35, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 36, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 39, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 40, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 42, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 44, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 45, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(11, 48, 1, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 12, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 13, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 15, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 17, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 18, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 19, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 20, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 21, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 22, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 25, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 26, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 32, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 33, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 34, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 35, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 37, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 38, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 39, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 40, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 42, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(12, 45, 4, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 11, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 13, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 15, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 17, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 18, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 19, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 20, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 21, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 23, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 24, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 25, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 26, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 27, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 29, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 32, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 33, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 36, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 37, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 38, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 39, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 40, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 42, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(13, 44, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 11, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 12, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 13, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 16, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 17, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 18, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 21, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 22, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 23, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 25, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 26, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 27, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 28, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 29, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 32, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 33, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 34, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 35, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 36, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 37, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 38, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 44, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(14, 45, 2, NULL, NULL, NULL, NULL, 'Scheduled'),
(15, 14, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(15, 30, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(15, 31, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(16, 15, 6, 8.5, 9.0, 6.0, 'Shows improvement', 'Attended'),
(16, 23, 6, 8.1, 9.3, 6.7, 'Needs focus on positioning', 'Attended'),
(16, 24, 6, 6.7, 6.8, 6.6, 'Needs focus on positioning', 'Attended'),
(16, 25, 6, 8.1, 7.8, 6.9, 'Needs improvement in passing', 'Attended'),
(16, 26, 6, 8.2, 7.6, 6.5, 'Needs improvement in passing', 'Attended'),
(16, 27, 6, 7.2, 8.4, 7.7, 'Shows improvement', 'Attended'),
(16, 29, 6, 8.0, 7.2, 7.4, 'Strong performance today', 'Attended'),
(16, 35, 6, 7.5, 8.3, 7.5, 'Needs focus on positioning', 'Attended'),
(16, 37, 6, 8.6, 8.0, 8.6, 'Good performance overall', 'Attended'),
(16, 39, 6, 8.2, 8.1, 8.7, 'Great decision making', 'Attended'),
(17, 11, 1, 8.1, 7.8, 8.4, 'Excellent positioning', 'Attended'),
(17, 12, 1, 7.4, 8.2, 8.9, 'Strong defensive awareness', 'Attended'),
(17, 13, 1, 8.7, 7.6, 9.2, 'Reads the game well', 'Attended'),
(17, 15, 1, 7.8, 8.5, 7.2, 'Good movement off the ball', 'Attended'),
(17, 17, 1, 8.9, 7.4, 8.1, 'Creative playmaker', 'Attended'),
(17, 18, 1, 8.5, 7.9, 8.7, 'Excellent spatial awareness', 'Attended'),
(17, 19, 1, 7.6, 8.3, 7.5, 'Good improvement shown', 'Attended'),
(17, 20, 1, 7.2, 8.1, 8.5, 'Tactically disciplined', 'Attended'),
(17, 21, 1, 8.4, 7.7, 8.8, 'Shows good potential', 'Attended'),
(17, 23, 1, 8.2, 8.9, 7.3, 'Clinical finishing today', 'Attended'),
(17, 25, 1, 7.9, 8.7, 6.9, 'Fast and agile', 'Attended'),
(17, 27, 1, 8.6, 8.4, 7.8, 'Key contributor to the win', 'Attended'),
(17, 29, 1, 7.5, 7.8, 8.2, 'Consistent effort', 'Attended'),
(17, 32, 1, 8.1, 7.5, 8.6, 'Strong work ethic', 'Attended'),
(17, 36, 1, 9.1, 8.2, 8.4, 'Man of the match performance', 'Attended'),
(17, 37, 1, 7.7, 8.6, 7.9, 'Solid performance overall', 'Attended'),
(17, 39, 1, 8.4, 7.3, 8.1, 'Great technical ability', 'Attended'),
(17, 42, 1, 7.1, 6.9, 7.4, 'Adapting well to training', 'Attended'),
(17, 44, 1, 7.8, 8.5, 7.2, 'Promising young talent', 'Attended'),
(18, 12, 4, 7.2, 9.1, 6.8, 'Outworked the opposition', 'Attended'),
(18, 13, 4, 7.5, 8.8, 7.1, 'High energy levels', 'Attended'),
(18, 14, 4, 7.8, 6.2, 6.5, 'Limited training - injured player', 'Attended'),
(18, 15, 4, 7.4, 9.3, 6.4, 'Physicality was a key asset', 'Attended'),
(18, 16, 4, 6.9, 6.8, 6.2, 'Light training - recovering from injury', 'Attended'),
(18, 18, 4, 8.1, 8.7, 6.9, 'Strong performance today', 'Attended'),
(18, 19, 4, 7.6, 9.2, 7.3, 'Fast and agile', 'Attended'),
(18, 20, 4, 7.8, 8.5, 7.1, 'Good improvement shown', 'Attended'),
(18, 22, 4, 8.2, 9.4, 7.5, 'Dominant in the air', 'Attended'),
(18, 23, 4, NULL, NULL, NULL, NULL, 'Absent'),
(18, 25, 4, 8.5, 9.1, 6.7, 'High energy levels', 'Attended'),
(18, 26, 4, 7.9, 8.9, 6.3, 'Needs to improve stamina', 'Attended'),
(18, 27, 4, 8.3, 8.7, 6.8, 'Solid work ethic', 'Attended'),
(18, 28, 4, 6.7, 6.4, 6.5, 'Limited training - recovering from injury', 'Attended'),
(18, 32, 4, 7.4, 8.6, 7.2, 'Strong performance today', 'Attended'),
(18, 33, 4, 8.1, 9.3, 7.4, 'Physicality was a key asset', 'Attended'),
(18, 34, 4, 7.7, 8.8, 6.9, 'Good improvement shown', 'Attended'),
(18, 38, 4, 8.4, 7.6, 6.7, 'Consistent effort', 'Attended'),
(18, 40, 4, 7.9, 8.3, 7.1, 'Shows good potential', 'Attended'),
(18, 42, 4, 7.2, 7.5, 6.8, 'Needs tactical improvement', 'Attended'),
(19, 11, 6, 7.9, 7.5, 7.8, 'Good distribution', 'Attended'),
(19, 15, 6, 8.7, 8.2, 6.9, 'Clinical finishing today', 'Attended'),
(19, 17, 6, 9.1, 7.4, 7.6, 'Creative playmaker', 'Attended'),
(19, 18, 6, 8.4, 7.8, 7.2, 'Precise passing range', 'Attended'),
(19, 19, 6, 8.6, 8.5, 7.4, 'Fast and agile', 'Attended'),
(19, 23, 6, 9.3, 8.7, 7.1, 'Clinical finishing today', 'Attended'),
(19, 24, 6, 6.8, 6.2, 6.7, 'Light training due to doubtful fitness', 'Attended'),
(19, 25, 6, 8.9, 8.8, 6.5, 'Fast and agile', 'Attended'),
(19, 26, 6, 8.5, 7.9, 6.8, 'Great ball control', 'Attended'),
(19, 27, 6, 8.2, 8.4, 7.3, 'Clinical finishing today', 'Attended'),
(19, 29, 6, 7.9, 8.1, 7.5, 'Solid performance overall', 'Attended'),
(19, 35, 6, 7.6, 8.3, 6.4, 'Shows improvement', 'Attended'),
(19, 36, 6, 9.2, 8.5, 8.1, 'Creative playmaker', 'Attended'),
(19, 37, 6, 8.3, 7.7, 7.8, 'Great technical ability', 'Attended'),
(19, 39, 6, 8.7, 7.6, 7.9, 'Precise passing range', 'Attended'),
(19, 40, 6, 8.1, 7.4, 7.2, 'Good improvement shown', 'Attended'),
(19, 41, 6, 7.8, 8.6, 6.6, 'Shows good potential', 'Attended'),
(19, 44, 6, 8.4, 8.9, 7.1, 'Fast and agile', 'Attended'),
(20, 11, 2, 8.9, 7.8, 7.5, 'Great ball control', 'Attended'),
(20, 12, 2, 7.6, 8.2, 8.4, 'Tackling was timed perfectly', 'Attended'),
(20, 13, 2, 8.2, 8.5, 8.7, 'Solid defensive display', 'Attended'),
(20, 15, 2, 8.5, 8.1, 7.3, 'First touch needs work', 'Attended'),
(20, 17, 2, 9.4, 7.6, 7.9, 'Great ball control', 'Attended'),
(20, 18, 2, 9.1, 7.8, 8.2, 'Precise passing range', 'Attended'),
(20, 20, 2, 7.8, 8.3, 8.5, 'Solid defensive display', 'Attended'),
(20, 21, 2, 8.4, 7.9, 8.1, 'Tackling was timed perfectly', 'Attended'),
(20, 22, 2, 7.9, 8.6, 8.3, 'Dominant in the air', 'Attended'),
(20, 25, 2, 8.7, 8.9, 7.1, 'Great ball control', 'Attended'),
(20, 26, 2, 8.3, 8.1, 6.9, 'First touch needs work', 'Attended'),
(20, 29, 2, 7.7, 8.4, 7.6, 'Shows improvement', 'Attended'),
(20, 32, 2, 7.5, 7.8, 8.2, 'Solid defensive display', 'Attended'),
(20, 33, 2, 8.1, 8.7, 8.6, 'Dominant in the air', 'Attended'),
(20, 36, 2, 9.3, 8.2, 8.4, 'Creative playmaker', 'Attended'),
(20, 37, 2, 8.6, 7.9, 7.8, 'Precise passing range', 'Attended'),
(20, 39, 2, 8.8, 7.5, 8.1, 'Great technical ability', 'Attended'),
(20, 40, 2, 8.4, 7.7, 7.4, 'Good improvement shown', 'Attended'),
(20, 42, 2, 7.9, 7.3, 7.2, 'Adapting well to training', 'Attended'),
(20, 44, 2, 8.2, 8.5, 7.5, 'Promising young talent', 'Attended'),
(20, 45, 2, 7.6, 7.1, 6.8, 'Shows potential for development', 'Attended'),
(21, 11, 5, 8.7, 7.9, 8.2, 'Remained calm under pressure', 'Attended'),
(21, 12, 5, 8.9, 8.4, 9.1, 'Solid defensive display', 'Attended'),
(21, 13, 5, 9.2, 8.7, 9.4, 'Reads the game well', 'Attended'),
(21, 16, 5, 7.1, 6.5, 7.8, 'Light training - recovering from injury', 'Attended'),
(21, 20, 5, 8.5, 8.1, 9.2, 'Tactically disciplined', 'Attended'),
(21, 21, 5, 8.7, 8.3, 8.9, 'Tackling was timed perfectly', 'Attended'),
(21, 22, 5, 8.4, 8.8, 9.1, 'Dominant in the air', 'Attended'),
(21, 29, 5, 7.8, 8.5, 8.3, 'Needs to track back more', 'Attended'),
(21, 31, 5, NULL, NULL, NULL, NULL, 'Absent'),
(21, 32, 5, 8.2, 7.9, 8.7, 'Solid defensive display', 'Attended'),
(21, 33, 5, 8.6, 8.9, 9.3, 'Excellent spatial awareness', 'Attended'),
(21, 34, 5, 7.9, 8.2, 8.5, 'Tactically disciplined', 'Attended'),
(21, 36, 5, 8.3, 8.1, 8.6, 'Reads the game well', 'Attended'),
(21, 37, 5, 7.7, 8.4, 8.2, 'Needs to track back more', 'Attended'),
(21, 39, 5, 8.1, 7.6, 8.4, 'Good improvement shown', 'Attended'),
(21, 40, 5, 7.8, 7.9, 8.1, 'Tactically disciplined', 'Attended'),
(21, 43, 5, 8.5, 8.7, 8.9, 'Solid defensive display', 'Attended'),
(21, 49, 5, 6.9, 6.2, 7.3, 'Light training - recovering from injury', 'Attended'),
(22, 11, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 13, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 15, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 17, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 18, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 19, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 20, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 22, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 25, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 26, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 29, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 32, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 34, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 35, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 38, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(22, 39, 6, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 12, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 13, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 15, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 21, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 23, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 24, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 26, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 27, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 28, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 32, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 33, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 34, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 35, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(23, 37, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 11, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 12, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 13, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 15, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 19, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 21, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 23, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 24, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 25, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 26, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 29, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 33, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 35, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 37, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 38, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(24, 39, 7, NULL, NULL, NULL, NULL, 'Scheduled'),
(25, 16, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(25, 28, 8, NULL, NULL, NULL, NULL, 'Scheduled'),
(26, 12, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(26, 13, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(26, 20, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(26, 21, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(26, 22, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(26, 32, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(26, 33, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(26, 34, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(26, 37, 5, NULL, NULL, NULL, NULL, 'Scheduled'),
(26, 39, 5, NULL, NULL, NULL, NULL, 'Scheduled');

-- --------------------------------------------------------

--
-- Table structure for table `training_sessions`
--

CREATE TABLE `training_sessions` (
  `Session_id` int(11) NOT NULL,
  `Session_time` varchar(30) DEFAULT NULL,
  `Session_date` date NOT NULL,
  `Session_Type` varchar(50) DEFAULT NULL,
  `Session_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_sessions`
--

INSERT INTO `training_sessions` (`Session_id`, `Session_time`, `Session_date`, `Session_Type`, `Session_status`) VALUES
(1, 'Morning', '2025-12-02', 'Team Tactical Training', 'Completed'),
(2, 'Morning', '2025-12-03', 'Fitness & Conditioning', 'Completed'),
(3, 'Afternoon', '2025-12-04', 'Attacking Drills', 'Completed'),
(4, 'Morning', '2025-12-05', 'Technical Skills', 'Completed'),
(5, 'Evening', '2025-12-06', 'Match Preparation', 'Completed'),
(6, 'Morning', '2025-12-09', 'Team Tactical Training', 'Completed'),
(7, 'Morning', '2025-12-10', 'Fitness & Conditioning', 'Completed'),
(8, 'Afternoon', '2025-12-11', 'Defensive Structure', 'Scheduled'),
(9, 'Morning', '2025-12-12', 'Technical Skills', 'Completed'),
(10, 'Evening', '2025-12-13', 'Recovery Session', 'Scheduled'),
(11, 'Morning', '2025-12-16', 'Team Tactical Training', 'Scheduled'),
(12, 'Morning', '2025-12-17', 'Fitness & Conditioning', 'Scheduled'),
(13, 'Afternoon', '2025-12-18', 'Attacking Drills', 'Scheduled'),
(14, 'Morning', '2025-12-19', 'Technical Skills', 'Scheduled'),
(15, 'Evening', '2025-12-20', 'Recovery Session', 'Scheduled'),
(16, 'Morning', '2025-12-08', 'Team Tactical Training', 'Completed'),
(17, 'Morning', '2025-11-25', 'Team Tactical Training', 'Completed'),
(18, 'Afternoon', '2025-11-26', 'Fitness Conditioning', 'Completed'),
(19, 'Morning', '2025-11-27', 'Attacking Drills', 'Completed'),
(20, 'Evening', '2025-11-28', 'Technical Skills', 'Completed'),
(21, 'Morning', '2025-11-29', 'Defensive Structure', 'Completed'),
(22, 'Morning', '2025-12-23', 'Match Preparation', 'Scheduled'),
(23, 'Afternoon', '2025-12-24', 'Recovery Session', 'Scheduled'),
(24, 'Morning', '2025-12-26', 'Team Tactical Training', 'Scheduled'),
(25, 'Morning', '2025-12-27', 'Fitness Conditioning', 'Scheduled'),
(26, 'Afternoon', '2025-12-30', 'Defensive Structure', 'Scheduled');

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
(1, 'Nazmul Islam', 45, '1928374669', 'nazmul@bufc.com', 'Dhaka', '01711000069', '1980-01-01', 100000.00, '2024-01-01', '2026-01-01', 'nazmul123', 'coach'),
(2, 'Tamkin Mahmud Tan', 32, '9182736455', 'tamkin@bufc.com', 'Chittagong', '01711000002', '1992-05-15', 60000.00, '2024-06-01', '2026-06-01', 'tamkin123', 'coach'),
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
(27, 'Jewel Rana', 27, '3647586970', 'jewel@bufc.com', 'Dhaka', '01911000017', '1996-12-25', 43000.00, '2024-01-01', '2025-12-31', 'jewel123', 'regular_player'),
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
(45, 'Rafiqul Islam', 19, '5566778899', 'rafiq@gmail.com', 'Barisal', '01811000045', '2005-07-30', NULL, NULL, NULL, 'rafiqul123', 'scouted_player'),
(48, 'Arshad Zaman Araf', 21, '6468527659', 'arshad.zaman.araf@example.com', 'Dhaka', '+8801823459372', '2003-12-19', NULL, NULL, NULL, '123456', 'scouted_player'),
(49, 'Amirun Nahin', 21, '1235896457', 'amirunnahin04@gmail.com', 'Dhaka', '+8801823459372', '2003-12-19', NULL, NULL, NULL, '123456', 'scouted_player');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coach`
--
ALTER TABLE `coach`
  ADD PRIMARY KEY (`Coach_ID`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`Doctor_Name`);

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
  MODIFY `Prescription_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `training_sessions`
--
ALTER TABLE `training_sessions`
  MODIFY `Session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

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
