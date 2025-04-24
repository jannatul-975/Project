-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 14, 2025 at 07:47 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `guesthousedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
CREATE TABLE IF NOT EXISTS `administrator` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `designation` enum('VC','Treasurer','Pro VC') NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `profile_pic` varchar(255) DEFAULT 'profile.jpg',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone_no` (`phone_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`id`, `name`, `email`, `phone_no`, `designation`, `password`, `is_active`, `profile_pic`) VALUES
(1, 'Dr. Md. Rezaul Karim', 'vc@gmail.com', '+8801711928499', 'VC', '$2y$10$g7ZzvlFP2.kqifXaRvVluumhkQDvkrktb7RmEL8J4YAYtpNrMfN7W', 1, 'profile.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `attendant`
--

DROP TABLE IF EXISTS `attendant`;
CREATE TABLE IF NOT EXISTS `attendant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `designation` enum('Attendant') NOT NULL DEFAULT 'Attendant',
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `profile_pic` varchar(255) DEFAULT 'profile.jpg',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone_no` (`phone_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendant`
--

INSERT INTO `attendant` (`id`, `name`, `email`, `phone_no`, `designation`, `password`, `is_active`, `profile_pic`) VALUES
(1, 'Tajdikur Rahman', 'attendant@gmail.com', '+8801711928482', 'Attendant', '$2y$10$2RwSMC0ixdjkoatlY0doXOxJNtvVunF8NFRzYCZFWnRrIQYIS0gVW', 1, 'profile.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `BookingID` int NOT NULL AUTO_INCREMENT,
  `booked_by_role` enum('teacher','administrative','register','officestaff','guest') NOT NULL,
  `booked_by_id` int NOT NULL,
  `RoomNo` varchar(50) NOT NULL,
  `checkInDate` date NOT NULL,
  `checkOutDate` date NOT NULL,
  `status` enum('Pending','Confirmed','Checked-in','Checked-out','Cancelled') DEFAULT 'Pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `guest_number` int NOT NULL,
  PRIMARY KEY (`BookingID`),
  KEY `RoomNo` (`RoomNo`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`BookingID`, `booked_by_role`, `booked_by_id`, `RoomNo`, `checkInDate`, `checkOutDate`, `status`, `total_amount`, `last_update`, `guest_number`) VALUES
(5, 'teacher', 4, '2C', '2025-04-14', '2025-04-15', 'Confirmed', 440.00, '2025-04-14 08:39:34', 1),
(6, 'teacher', 3, '2D', '2025-04-14', '2025-04-16', 'Confirmed', 880.00, '2025-04-14 08:38:12', 1),
(9, 'teacher', 2, '2E', '2025-04-14', '2025-04-17', 'Confirmed', 2640.00, '2025-04-14 09:31:58', 3),
(10, 'teacher', 2, '2G', '2025-04-14', '2025-04-17', 'Confirmed', 2640.00, '2025-04-14 09:31:58', 3),
(13, 'register', 3, '2F', '2025-04-14', '2025-04-15', 'Confirmed', 440.00, '2025-04-14 10:11:58', 0),
(14, '', 1, '3A', '2025-04-14', '2025-04-15', 'Confirmed', 440.00, '2025-04-14 10:41:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `feedbackId` int NOT NULL AUTO_INCREMENT,
  `booked_by_role` enum('teacher','administrator','register','officestaff','guest') NOT NULL,
  `booked_by_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`feedbackId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

DROP TABLE IF EXISTS `guest`;
CREATE TABLE IF NOT EXISTS `guest` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `registration_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'profile.jpg',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone_no` (`phone_no`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`id`, `name`, `email`, `phone_no`, `address`, `registration_date`, `password`, `profile_pic`) VALUES
(1, 'Mohima Mostari', 'tuly@gmail.com', '+8801739990714', 'Barisal', '2025-04-05 11:42:26', '$2y$10$EGP565U8qyxoJTepsSe.yeFkzwcnqSPUtvXuLEtUGWlsLEE6Zh9Da', 'profile.jpg'),
(2, 'Mohima Mostari', 'munia@gmail.com', '+8801856896545', 'Satkhira', '2025-04-10 12:00:52', '$2y$10$3ghdGK.i8nM/G49XKhz.1OHbcRJQvV0WEUMD0C7xmqG/7cKce5aXy', 'profile.jpg'),
(3, 'Mohima Mostari', 'faisal@gmail.com', '+8801738492621', 'Thakurgaon', '2025-04-10 12:41:41', '$2y$10$IT9ZBdNGgB/9/VWFahuBD.l4zjDIkTuHC/4m9ddFSYatCRGfHYH6C', 'profile.jpg'),
(4, 'Mohima Mostari', 'mohima@gmail.com', '+8801738492629', 'Rangpur', '2025-04-14 05:18:11', '$2y$10$4O7BtYRzDrUGK2Xq1javJ.vBlXFMOuRLPdvSlH0Ap4Lj3rnD2pf3.', 'profile.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `officestaff`
--

DROP TABLE IF EXISTS `officestaff`;
CREATE TABLE IF NOT EXISTS `officestaff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `dept_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'profile.jpg',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone_no` (`phone_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `officestaff`
--

INSERT INTO `officestaff` (`id`, `name`, `email`, `phone_no`, `designation`, `dept_name`, `password`, `profile_pic`) VALUES
(1, 'Md. Anwar Hossain', 'anwar.cseku@gmail.com', '+8801718317343', '', 'CSE', '$2y$10$DAI5vRncA1XfJC0OY/hzkOHDF14B3Fu.Q/N4v7/3hACnajstwppEa', 'profile.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `PaymentID` int NOT NULL AUTO_INCREMENT,
  `BookingID` int NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `payment_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `payment_method` enum('Cash','Credit Card','Bank Transfer') NOT NULL,
  `transactionID` varchar(50) NOT NULL,
  `paymentStatus` enum('Paid','Pending') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`PaymentID`),
  UNIQUE KEY `transactionID` (`transactionID`),
  KEY `BookingID` (`BookingID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

DROP TABLE IF EXISTS `register`;
CREATE TABLE IF NOT EXISTS `register` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `designation` enum('Register') NOT NULL DEFAULT 'Register',
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `profile_pic` varchar(255) DEFAULT 'profile.jpg',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone_no` (`phone_no`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `name`, `email`, `phone_no`, `designation`, `password`, `is_active`, `profile_pic`) VALUES
(3, 'Dr. S. M. Mahbubur Rahman', 'resister@gmail.com', '+8801747734140', 'Register', '$2y$10$oWd/Qxc9UQTB9cQ7uxE43.zlPbMouRZtynIrALl3.Cs4EuTb0SA5S', 1, 'profile.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE IF NOT EXISTS `room` (
  `RoomID` int NOT NULL AUTO_INCREMENT,
  `RoomNo` varchar(50) NOT NULL,
  `room_type` enum('VIP','AC','Non AC &Double','Non AC & Single') NOT NULL,
  `pricePerNight` decimal(10,2) NOT NULL,
  `status` enum('Available','Booked','Under Maintenance') DEFAULT 'Available',
  PRIMARY KEY (`RoomID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`RoomID`, `RoomNo`, `room_type`, `pricePerNight`, `status`) VALUES
(1, '2A', 'VIP', 440.00, 'Booked'),
(2, '2B', 'VIP', 440.00, 'Available'),
(3, '2C', 'AC', 440.00, 'Booked'),
(4, '2D', 'AC', 440.00, 'Booked'),
(5, '2E', 'AC', 440.00, 'Booked'),
(6, '2F', 'VIP', 440.00, 'Booked'),
(7, '2G', 'AC', 440.00, 'Booked'),
(8, '3A', 'VIP', 440.00, 'Booked'),
(9, '3B', 'VIP', 440.00, 'Booked'),
(10, '3C', 'Non AC & Single', 220.00, 'Available'),
(11, '3D', 'AC', 440.00, 'Available'),
(12, '3E', 'AC', 440.00, 'Available'),
(13, '3F', 'AC', 440.00, 'Available'),
(14, '3G', 'AC', 440.00, 'Available'),
(15, '3H', 'AC', 440.00, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `designation` enum('Receptionist','Cleaner','Maintenance') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone_no` (`phone_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
CREATE TABLE IF NOT EXISTS `teacher` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `designation` enum('Lecturer','Assistant Professor','Associate Professor','Professor') DEFAULT NULL,
  `dept_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'profile.jpg',
  `RegisterID` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone_no` (`phone_no`),
  KEY `fk_teacher_register` (`RegisterID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `name`, `email`, `phone_no`, `designation`, `dept_name`, `password`, `profile_pic`, `RegisterID`) VALUES
(2, 'Mr. Aminul Islam', 'aminul@gmail.com', '+8801718317345', 'Assistant Professor', 'CSE', '$2y$10$yA3EzEJCnwihuDChZCj0GusNMssiLaVikDDFpdHXYuo8zyIyTG7vu', 'profile.jpg', 3),
(3, 'Md Anisur Rahman', 'anis@gmail.com', '+8801718317348', 'Assistant Professor', 'CSE', '$2y$10$h4fBof0fmI/4TYzVle5YA.awCefcf7UqbZAhCbNZ0tJk6pRi4oJVW', 'profile.jpg', 3),
(4, 'Md. Farhan Sadiq', 'farhan@gmail.com', '+8801718317350', 'Lecturer', 'CSE', '$2y$10$BPQ7gLo44ThVQyf/wUjZmuwt/.jp2zWMBjtXdkTYYekd8emAWcO16', 'profile.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_application`
--

DROP TABLE IF EXISTS `teacher_application`;
CREATE TABLE IF NOT EXISTS `teacher_application` (
  `ApplicationID` int NOT NULL AUTO_INCREMENT,
  `TeacherID` int NOT NULL,
  `guest_information` text,
  `RegisterID` int NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `submission_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `room_id` int DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `purpose` enum('Myself','Guest') NOT NULL DEFAULT 'Myself',
  PRIMARY KEY (`ApplicationID`),
  KEY `RegisterID` (`RegisterID`),
  KEY `fk_teacher_application_teacher` (`TeacherID`),
  KEY `fk_teacher_application_room` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `teacher_application`
--

INSERT INTO `teacher_application` (`ApplicationID`, `TeacherID`, `guest_information`, `RegisterID`, `status`, `submission_date`, `room_id`, `checkin_date`, `checkout_date`, `purpose`) VALUES
(26, 3, 'Munia satkhira', 3, 'Approved', '2025-04-07 11:26:28', 4, '2025-04-08', '2025-04-10', 'Guest'),
(32, 2, 'Rani Rangpur', 3, 'Approved', '2025-04-12 15:25:13', 5, '2025-04-12', '2025-04-13', 'Guest'),
(35, 4, '', 3, 'Approved', '2025-04-14 07:16:29', 3, '2025-04-14', '2025-04-15', 'Myself'),
(37, 3, 'My Father and Mother', 3, 'Approved', '2025-04-14 09:17:01', 2, '2025-04-14', '2025-04-16', 'Guest'),
(38, 2, '', 3, 'Approved', '2025-04-14 11:58:41', 15, '2025-04-18', '2025-04-20', 'Myself'),
(39, 2, '', 3, 'Rejected', '2025-04-14 11:59:20', 12, '2025-04-20', '2025-04-22', 'Guest');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_guest`
--

DROP TABLE IF EXISTS `teacher_guest`;
CREATE TABLE IF NOT EXISTS `teacher_guest` (
  `TeacherID` int NOT NULL,
  `GuestID` int NOT NULL,
  PRIMARY KEY (`TeacherID`,`GuestID`),
  KEY `GuestID` (`GuestID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verification_code`
--

DROP TABLE IF EXISTS `verification_code`;
CREATE TABLE IF NOT EXISTS `verification_code` (
  `id` int NOT NULL AUTO_INCREMENT,
  `application_id` int NOT NULL,
  `code` varchar(10) NOT NULL,
  `is_used` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `verification_code`
--

INSERT INTO `verification_code` (`id`, `application_id`, `code`, `is_used`, `created_at`) VALUES
(10, 32, 'B218C7', 1, '2025-04-13 03:23:17'),
(14, 26, '0BA72E', 1, '2025-04-13 03:32:50'),
(16, 35, '1F0C32', 1, '2025-04-14 01:17:11'),
(20, 37, '37EE0B', 1, '2025-04-14 03:17:21'),
(22, 38, 'C69A57', 0, '2025-04-14 06:39:29');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`BookingID`) REFERENCES `booking` (`BookingID`) ON DELETE CASCADE;

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `fk_teacher_register` FOREIGN KEY (`RegisterID`) REFERENCES `register` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_application`
--
ALTER TABLE `teacher_application`
  ADD CONSTRAINT `fk_teacher_application_room` FOREIGN KEY (`room_id`) REFERENCES `room` (`RoomID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_teacher_application_teacher` FOREIGN KEY (`TeacherID`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_application_ibfk_1` FOREIGN KEY (`TeacherID`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_application_ibfk_3` FOREIGN KEY (`RegisterID`) REFERENCES `register` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_guest`
--
ALTER TABLE `teacher_guest`
  ADD CONSTRAINT `teacher_guest_ibfk_1` FOREIGN KEY (`TeacherID`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_guest_ibfk_2` FOREIGN KEY (`GuestID`) REFERENCES `guest` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
