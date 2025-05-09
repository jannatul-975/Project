

DROP TABLE IF EXISTS `university`;
CREATE TABLE IF NOT EXISTS `university` (
  `university_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`university_id`),
  UNIQUE KEY `university_name` (`name`(191))
) ENGINE=InnoDB;


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


DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `university_id` int NOT NULL,
  PRIMARY KEY (`department_id`),
  KEY `university_id` (`university_id`),
  CONSTRAINT `fk_university_id` FOREIGN KEY (`university_id`) REFERENCES `university` (`university_id`) ON DELETE CASCADE
) ENGINE=InnoDB;



INSERT INTO `department` (`department_id`, `name`, `university_id`) VALUES
(1, 'Department of Computer Science and Engineering', 1),
(2, 'Department of Electrical and Electronic Engineering', 1),
(3, 'Department of Business Administration', 1),
(4, 'Department of Law', 1),
(5, 'Department of Social Sciences', 1),
(6, 'Department of Computer Science and Engineering', 2),
(7, 'Department of Mathematics', 2),
(8, 'Department of Political Science', 2),
(9, 'Department of Business Studies', 2),
(10, 'Department of Economics', 2),
(11, 'Department of Computer Science', 3),
(12, 'Department of Mechanical Engineering', 3),
(13, 'Department of Electrical Engineering', 3),
(14, 'Department of Psychology', 3),
(15, 'Department of Business Administration', 3),
(16, 'Department of Computer Science', 4),
(17, 'Department of Electrical Engineering', 4),
(18, 'Department of Management Science and Engineering', 4),
(19, 'Department of Biology', 4),
(20, 'Department of Civil and Environmental Engineering', 4),
(21, 'Department of Computer Science', 5),
(22, 'Department of Philosophy', 5),
(23, 'Department of Law', 5),
(24, 'Department of Engineering Science', 5),
(25, 'Department of Medicine', 5),
(26, 'Department of Computer Science', 6),
(27, 'Department of Electrical Engineering and Computer Sciences', 6),
(28, 'Department of Bioengineering', 6),
(29, 'Department of Chemical Engineering', 6),
(30, 'Department of Physics', 6),
(31, 'Department of Computer Science and Engineering', 7),
(32, 'Department of Electrical Engineering', 7),
(33, 'Department of Civil Engineering', 7),
(34, 'Department of Mechanical Engineering', 7),
(35, 'Department of Chemical Engineering', 7),
(36, 'Department of Computer Science', 8),
(37, 'Department of Electrical and Computer Engineering', 8),
(38, 'Department of Chemical and Biomolecular Engineering', 8),
(39, 'Department of Economics', 8),
(40, 'Department of Business Administration', 8),
(41, 'Department of Computer Science', 9),
(42, 'Department of Engineering', 9),
(43, 'Department of Mathematics and Statistics', 9),
(44, 'Department of Biological Sciences', 9),
(45, 'Department of Arts and Humanities', 9),
(46, 'Department of Computer Science', 10),
(47, 'Department of Electrical Engineering', 10),
(48, 'Department of Civil Engineering', 10),
(49, 'Department of Environmental Studies', 10),
(50, 'Department of Physics', 10);

