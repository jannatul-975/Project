-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 08, 2025 at 09:11 AM
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
-- Database: `guesthouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

DROP TABLE IF EXISTS `application`;
CREATE TABLE IF NOT EXISTS `application` (
  `applicationId` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `submission_date` date NOT NULL DEFAULT (curdate()),
  `checkInDate` date NOT NULL DEFAULT (curdate()),
  `checkOutDate` date NOT NULL DEFAULT (curdate()),
  `purpose` text NOT NULL,
  `guestInformation` text,
  `is_booked` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`applicationId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`applicationId`, `userId`, `status`, `submission_date`, `checkInDate`, `checkOutDate`, `purpose`, `guestInformation`, `is_booked`) VALUES
(1, 2, 'Approved', '2025-04-26', '2025-04-27', '2025-04-28', 'Myself', '', 1),
(2, 2, 'Approved', '2025-04-26', '2025-04-28', '2025-04-29', 'Guest', 'Rani, Rangpur', 0),
(3, 2, 'Rejected', '2025-05-04', '2025-05-01', '2025-05-06', 'Guest', '', NULL),
(4, 2, 'Pending', '2025-05-04', '2025-05-04', '2025-05-06', 'Myself', '', NULL),
(5, 9, 'Approved', '2025-05-04', '2025-05-04', '2025-05-06', 'Myself', '', NULL),
(6, 8, 'Pending', '2025-05-04', '2025-05-04', '2025-05-06', 'Guest', '', NULL),
(7, 9, 'Pending', '2025-05-04', '2025-05-05', '2025-05-07', 'Guest', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attendant`
--

DROP TABLE IF EXISTS `attendant`;
CREATE TABLE IF NOT EXISTS `attendant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'profile.jpg',
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendant`
--

INSERT INTO `attendant` (`id`, `name`, `email`, `phone`, `address`, `profile_pic`, `password`, `is_active`) VALUES
(1, 'Md. Tajdikur Rahman', 'tajdikur@gmail.com', '01823456790', 'Zero point, Khulna', 'profile.jpg', '$2y$10$4e0guYw2DD3mGmhh9rLh/ejFXgVkLGYXr6akSi0l5Ivx2JpEjRPSi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `bookingId` int NOT NULL AUTO_INCREMENT,
  `userId` int DEFAULT NULL,
  `guestId` int DEFAULT NULL,
  `applicationId` int DEFAULT NULL,
  `checkInDate` date NOT NULL DEFAULT (curdate()),
  `checkOutDate` date NOT NULL DEFAULT (curdate()),
  `totalAmount` decimal(10,2) DEFAULT NULL,
  `travelPurpose` text NOT NULL,
  `signature` int DEFAULT NULL,
  `paymentStatus` enum('Paid','Pending','Cancel') DEFAULT NULL,
  PRIMARY KEY (`bookingId`),
  KEY `userId` (`userId`),
  KEY `applicationId` (`applicationId`),
  KEY `fk_guest_id` (`guestId`),
  KEY `fk_signature_attendant` (`signature`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`bookingId`, `userId`, `guestId`, `applicationId`, `checkInDate`, `checkOutDate`, `totalAmount`, `travelPurpose`, `signature`, `paymentStatus`) VALUES
(20, NULL, NULL, 1, '2025-04-27', '2025-04-28', 440.00, 'Personal', NULL, 'Pending'),
(21, NULL, 1, 2, '2025-04-28', '2025-04-29', 440.00, 'Personal', NULL, 'Pending'),
(22, NULL, NULL, 5, '2025-05-05', '2025-05-06', 440.00, 'Personal', NULL, 'Pending'),
(23, 10, NULL, NULL, '2025-05-04', '2025-05-07', 1320.00, 'Personal', NULL, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `booking_room`
--

DROP TABLE IF EXISTS `booking_room`;
CREATE TABLE IF NOT EXISTS `booking_room` (
  `RoomID` int NOT NULL,
  `bookingId` int NOT NULL,
  KEY `RoomID` (`RoomID`),
  KEY `bookingId` (`bookingId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_room`
--

INSERT INTO `booking_room` (`RoomID`, `bookingId`) VALUES
(3, 20),
(5, 21),
(5, 22),
(1, 23);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `university_id` int NOT NULL,
  PRIMARY KEY (`department_id`),
  KEY `university_id` (`university_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `name`, `university_id`) VALUES
(1, 'Computer Science and Engineering', 1),
(2, 'Electronic and Communication Engineering', 1),
(3, 'Business Administration', 1),
(4, 'Law', 1),
(5, 'Social Sciences', 1),
(6, 'Computer Science and Engineering', 2),
(7, 'Mathematics', 2),
(8, 'Political Science', 2),
(9, 'Business Studies', 2),
(10, 'Economics', 2),
(11, 'Computer Science', 3),
(12, 'Mechanical Engineering', 3),
(13, 'Electrical Engineering', 3),
(14, 'Psychology', 3),
(15, 'Business Administration', 3),
(16, 'Computer Science', 4),
(17, 'Electrical Engineering', 4),
(18, 'Management Science and Engineering', 4),
(19, 'Biology', 4),
(20, 'Civil and Environmental Engineering', 4),
(21, 'Computer Science', 5),
(22, 'Philosophy', 5),
(23, 'Law', 5),
(24, 'Engineering Science', 5),
(25, 'Medicine', 5),
(26, 'Computer Science', 6),
(27, 'Electrical Engineering and Computer Sciences', 6),
(28, 'Bioengineering', 6),
(29, 'Chemical Engineering', 6),
(30, 'Physics', 6),
(31, 'Computer Science and Engineering', 7),
(32, 'Electrical Engineering', 7),
(33, 'Civil Engineering', 7),
(34, 'Mechanical Engineering', 7),
(35, 'Chemical Engineering', 7),
(36, 'Computer Science', 8),
(37, 'Electrical and Computer Engineering', 8),
(38, 'Chemical and Biomolecular Engineering', 8),
(39, 'Economics', 8),
(40, 'Business Administration', 8),
(41, 'Computer Science', 9),
(42, 'Engineering', 9),
(43, 'Mathematics and Statistics', 9),
(44, 'Biological Sciences', 9),
(45, 'Arts and Humanities', 9),
(46, 'Computer Science', 10),
(47, 'Electrical Engineering', 10),
(48, 'Civil Engineering', 10),
(49, 'Environmental Studies', 10),
(50, 'Physics', 10);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `feedbackId` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text NOT NULL,
  `date` date NOT NULL DEFAULT (curdate()),
  PRIMARY KEY (`feedbackId`),
  KEY `userId` (`userId`)
) ;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackId`, `userId`, `rating`, `comment`, `date`) VALUES
(1, 2, 3, 'It was a nice experience.', '2025-05-07'),
(2, 2, 3, 'It was a nice experience.', '2025-05-07'),
(3, 2, 4, 'It was a nice experience.', '2025-05-07'),
(4, 2, 2, '', '2025-05-07'),
(5, 2, 2, '', '2025-05-07');

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

DROP TABLE IF EXISTS `guest`;
CREATE TABLE IF NOT EXISTS `guest` (
  `guestId` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text,
  `dept` int DEFAULT NULL,
  `guestType` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`guestId`),
  UNIQUE KEY `phone` (`phone`),
  KEY `fk_department_id` (`dept`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`guestId`, `name`, `phone`, `address`, `dept`, `guestType`) VALUES
(1, 'Toma Rani', '01972839476', 'Rangpur', NULL, 'Non Academic');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE IF NOT EXISTS `room` (
  `RoomID` int NOT NULL AUTO_INCREMENT,
  `RoomName` varchar(255) NOT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `pricePerNight` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`RoomID`),
  UNIQUE KEY `RoomName` (`RoomName`),
  UNIQUE KEY `RoomName_2` (`RoomName`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`RoomID`, `RoomName`, `room_type`, `pricePerNight`, `status`) VALUES
(1, '2A', 'VIP', 440.00, 'Available'),
(2, '2B', 'VIP', 440.00, 'Available'),
(3, '2C', 'AC', 440.00, 'Available'),
(4, '2D', 'AC', 440.00, 'Available'),
(5, '2E', 'AC', 440.00, 'Available'),
(6, '2F', 'VIP', 440.00, 'Available'),
(7, '2G', 'AC', 440.00, 'Available'),
(9, '3A', 'AC', 440.00, 'Available'),
(10, '3B', 'VIP', 440.00, 'Available'),
(11, '3C', 'Non AC single', 220.00, 'Available'),
(12, '3D', 'AC', 440.00, 'Available'),
(13, '3E', 'AC', 440.00, 'Available'),
(14, '3F', 'AC', 440.00, 'Available'),
(15, '3G', 'AC', 440.00, 'Available'),
(16, '3H', 'AC', 440.00, 'Available'),
(17, '4A', 'VIP', 440.00, 'Available'),
(18, '4B', 'VIP', 440.00, 'Available'),
(19, '4C', 'Non AC single', 220.00, 'Available'),
(20, '4D', 'Non AC double', 440.00, 'Available'),
(21, '4E', 'Non AC single', 220.00, 'Available'),
(23, '4G', 'Non AC single', 220.00, 'Available'),
(24, '4H', 'Non AC single', 220.00, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `StaffID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`StaffID`),
  UNIQUE KEY `phone_no` (`phone_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university`
--

DROP TABLE IF EXISTS `university`;
CREATE TABLE IF NOT EXISTS `university` (
  `university_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`university_id`),
  UNIQUE KEY `university_name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `university`
--

INSERT INTO `university` (`university_id`, `name`) VALUES
(1, 'Khulna University (Khulna, Bangladesh)'),
(2, 'Dhaka University (Dhaka, Bangladesh)'),
(3, 'Harvard University (Cambridge, USA)'),
(4, 'Stanford University (Stanford, USA)'),
(5, 'University of Oxford (Oxford, UK)'),
(6, 'University of California, Berkeley (California, USA)'),
(7, 'Indian Institute of Technology (IIT) Bombay (Mumbai, India)'),
(8, 'National University of Singapore (Singapore)'),
(9, 'University of Melbourne (Melbourne, Australia)'),
(10, 'University of Tokyo (Tokyo, Japan)');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `dept_name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `role` enum('Teacher','Officestaff','Register','Vice Chancellor','Pro-Vice Chancellor','Treasurer') NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'profile.jpg',
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `phone`, `dept_name`, `designation`, `role`, `profile_pic`, `password`) VALUES
(2, 'Mr. Aminul Islam', 'aminul@gmail.com', '01823456789', 'Computer Science and Engineering', 'Assistant Professor', 'Teacher', 'profile.jpg', '$2y$10$tZILAznbM3pdDEXkqY5rn.fV0hd/BQI0uYuV84EU4c3s5qUN5UC.S'),
(8, 'Nishat Sultana Toma', 'toma@gmail.com', '01823456000', 'Electronic and Communication Engineering', 'Associate Professor', 'Teacher', 'profile.jpg', '$2y$10$UEHZJs04MQwEkSmr4ObTHO3S2Z6wRWOXzCwz4GadojvExc19DYXzu'),
(9, 'Mr. Anisul Islam', 'anis@gmail.com', '01823456700', 'Computer Science and Engineering', 'Professor', 'Teacher', 'profile.jpg', '$2y$10$mRMAtf0bCdltTRetlJ4fj.Q2G5Vuj6lpzv1gYCzNkSxxpG4FfQymy'),
(10, 'S M Mahbubur Rahman', 'rahman@gmail.com', '01823456791', 'Social Sciences', 'Professor', 'Register', 'profile.jpg', '$2y$10$oxH2PHEnx8K6cINRt2W8wO63yqslJp2.oO.xkWvR3NOSVooET8/YO');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `application_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`applicationId`) REFERENCES `application` (`applicationId`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_guest_id` FOREIGN KEY (`guestId`) REFERENCES `guest` (`guestId`),
  ADD CONSTRAINT `fk_signature_attendant` FOREIGN KEY (`signature`) REFERENCES `attendant` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `booking_room`
--
ALTER TABLE `booking_room`
  ADD CONSTRAINT `booking_room_ibfk_1` FOREIGN KEY (`RoomID`) REFERENCES `room` (`RoomID`),
  ADD CONSTRAINT `booking_room_ibfk_2` FOREIGN KEY (`bookingId`) REFERENCES `booking` (`bookingId`);

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `fk_university_id` FOREIGN KEY (`university_id`) REFERENCES `university` (`university_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guest`
--
ALTER TABLE `guest`
  ADD CONSTRAINT `fk_department_id` FOREIGN KEY (`dept`) REFERENCES `department` (`department_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
