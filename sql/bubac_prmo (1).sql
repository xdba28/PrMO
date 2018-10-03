-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2018 at 11:18 AM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bubac_prmo`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_requests`
--

CREATE TABLE IF NOT EXISTS `account_requests` (
  `ID` int(11) NOT NULL,
  `fname` char(50) NOT NULL,
  `midle_name` char(50) NOT NULL,
  `last_name` char(50) NOT NULL,
  `ext_name` char(20) NOT NULL DEFAULT 'none',
  `employee_id` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `designation` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `userpassword` varchar(100) NOT NULL,
  `submitted` datetime NOT NULL,
  `remarks` varchar(500) NOT NULL DEFAULT 'none',
  `status` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_requests`
--

INSERT INTO `account_requests` (`ID`, `fname`, `midle_name`, `last_name`, `ext_name`, `employee_id`, `email`, `contact`, `designation`, `username`, `userpassword`, `submitted`, `remarks`, `status`) VALUES
(1, 'Paula Jane', 'Balatucan', 'Socito', 'none', '2015-9999', 'paulajane.socito@bicol-u.edu.ph', '', 1, 'pjane101', 'pjane101@', '0000-00-00 00:00:00', 'something', 'reviewed'),
(2, 'Adrienne', 'Amparo', 'Castro', 'none', '2015-09821', 'adry.castro2030@gmail.com', '', 1, 'adrycastro', 'yenyen20', '2018-08-17 08:30:51', 'none', 'pending'),
(4, 'Emer Jay', 'Base', 'Rebueno', 'none', '2015-07544', 'emerjay.rebueno@bicol-u.edu.ph', '0950 460 1684', 1, 'emerjay', 'password', '2018-08-17 10:29:51', 'none', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `commitee`
--

CREATE TABLE IF NOT EXISTS `commitee` (
  `ID` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `type` enum('INF','GDS','GEN') NOT NULL,
  `unit_office` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `edr_account`
--

CREATE TABLE IF NOT EXISTS `edr_account` (
  `account_id` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `userpassword` varchar(100) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `group` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `edr_account`
--

INSERT INTO `edr_account` (`account_id`, `username`, `userpassword`, `salt`, `group`) VALUES
('2015-11583', 'Denver', '34b9014977a99b4663dc280b5bbcd04989a2118828eaab89bd61ed420c04f361', 'JoHÆ\nÃ6>øƒæéÙCH}á~ëkÆ•z­r^‘aì', 1),
('2015-15096', 'nico', '2734a952ed131588d53549ffc94269ad285c5dda2ed5e11cfc1ba44ccfe1d5da', 'å’æ%Àe\Z	NÒº£$¬ë`Âp/í+ÑiC\rêP', 2);

-- --------------------------------------------------------

--
-- Table structure for table `enduser`
--

CREATE TABLE IF NOT EXISTS `enduser` (
  `edr_id` varchar(20) NOT NULL,
  `edr_fname` char(50) NOT NULL,
  `edr_mname` char(50) NOT NULL,
  `edr_lname` char(50) NOT NULL,
  `edr_ext_name` char(20) DEFAULT 'XXXXX',
  `edr_email` varchar(50) DEFAULT NULL,
  `edr_designated_office` int(10) unsigned NOT NULL,
  `edr_job_tittle` varchar(50) NOT NULL,
  `edr_profile_photo` blob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enduser`
--

INSERT INTO `enduser` (`edr_id`, `edr_fname`, `edr_mname`, `edr_lname`, `edr_ext_name`, `edr_email`, `edr_designated_office`, `edr_job_tittle`, `edr_profile_photo`) VALUES
('2000-12345', 'Christian', 'Y', 'Sy', 'XXXXX', 'christian.sy@bicol-u.edu.ph', 1, 'associate dean', NULL),
('2015-11583', 'Denver', 'B', 'Arancillo', 'XXXXX', 'denver.arancillo@bicol-u.edu.ph', 1, 'none', NULL),
('2015-15096', 'Nico', 'Villaraza', 'Ativo', 'XXXXX', 'nico.ativo@bicol-u.edu.ph', 1, 'none', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `group_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `permission` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`group_id`, `name`, `permission`) VALUES
(1, 'standard_user', ''),
(2, 'administrator', '{"admin": 1}'),
(3, 'super_admin', ''),
(4, 'director', ''),
(5, 'aid', ''),
(6, 'staff', '');

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE IF NOT EXISTS `personnel` (
  `prnl_id` varchar(20) NOT NULL,
  `prnl_fname` char(50) NOT NULL,
  `prnl_mname` char(50) NOT NULL,
  `prnl_lname` char(50) NOT NULL,
  `prnl_ext_name` char(20) DEFAULT 'XXXXX',
  `prnl_email` varchar(50) DEFAULT NULL,
  `prnl_designated_office` int(10) unsigned NOT NULL,
  `prnl_job_tittle` varchar(50) NOT NULL,
  `prnl_assined_phase` varchar(50) NOT NULL,
  `prnl_profile_photo` blob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`prnl_id`, `prnl_fname`, `prnl_mname`, `prnl_lname`, `prnl_ext_name`, `prnl_email`, `prnl_designated_office`, `prnl_job_tittle`, `prnl_assined_phase`, `prnl_profile_photo`) VALUES
('163-141', 'super_admin', 'super_admin', 'super_admin', 'XXXXX', 'super_admin@gmail.com', 21, 'super_admin', 'super_admin', NULL),
('2014-001-171', 'Jane', 'Arcinue', 'Millamina', 'XXXXX', 'janemillamina18@gmail.com', 21, 'JO', 'sample', NULL),
('2015-42911', 'Ma. Julieta', 'Madla', 'Borres', 'XXXXX', 'sample.email@gmail.com', 21, 'director', 'sample', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prnl_account`
--

CREATE TABLE IF NOT EXISTS `prnl_account` (
  `account_id` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `userpassword` varchar(100) NOT NULL,
  `salt` varchar(50) NOT NULL,
  `group` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prnl_account`
--

INSERT INTO `prnl_account` (`account_id`, `username`, `userpassword`, `salt`, `group`) VALUES
('163-141', 'super_admin', 'a8f12483d5880c55412d6ed16de6bb3bfbbff4df36c52eb6fdfca5d9858dba31', 'äÑ–Ù¯ÑU5ïÛø>½ùËª-M,ƒ99 ½CŽ÷‹fx', 3),
('2014-001-171', 'jane', '808810706a32cf03e36a0e0a7062654c0556e08f4b19a4f2a7418fb92d659097', 'í#bçoê©9ó¶1Þr±''Æº!ÿÀš®Ós', 5);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE IF NOT EXISTS `units` (
  `ID` int(10) unsigned NOT NULL,
  `office_name` varchar(255) NOT NULL,
  `acronym` varchar(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`ID`, `office_name`, `acronym`) VALUES
(1, 'Bicol University College of Science', 'BUCS'),
(2, 'Bicol University College of Nursing', 'BUCN'),
(3, 'Bicol University College of Art and Letters', 'BUCAL'),
(4, 'Bicol University Graduate School', 'BUGS'),
(5, 'Bicol University College of Education', 'BUCE'),
(6, 'Bicol University College of Medicine', 'BUCM'),
(7, 'Bicol University College of Business Education and Management', 'BUCBEM'),
(8, 'Bicol University College of Industrial Technology', 'BUCIT'),
(9, 'Bicol University Institute of Architecture', 'BUIA'),
(10, 'Bicol University Gubat Campus', 'BUGC'),
(11, 'Bicol University Polangui Campus', 'BUPC'),
(12, 'Bicol University Tabaco Campus', 'BUTC'),
(13, 'Bicol University Language Center', 'BULC'),
(14, 'University Registrar''s Office', 'URO'),
(15, 'University Office of Admission', ''),
(16, 'University Library', ''),
(17, 'University Office of the Alumni Coordinator', ''),
(18, 'University Health Services', ''),
(19, 'University Office of the NSTP Coordinator', ''),
(20, 'Office of the University Sports Development', ''),
(21, 'Procurement Management Office', 'PrMO');

-- --------------------------------------------------------

--
-- Table structure for table `users_session`
--

CREATE TABLE IF NOT EXISTS `users_session` (
  `id` int(11) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  `hash` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_session`
--

INSERT INTO `users_session` (`id`, `user_id`, `hash`) VALUES
(2, '2015-15096', '0a149d972f75f58f5409a9ffa162807c1dc36efb078fa8d388e4d4e6a20cda14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_requests`
--
ALTER TABLE `account_requests`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `commitee`
--
ALTER TABLE `commitee`
  ADD PRIMARY KEY (`ID`), ADD KEY `unit_office` (`unit_office`);

--
-- Indexes for table `edr_account`
--
ALTER TABLE `edr_account`
  ADD PRIMARY KEY (`account_id`), ADD UNIQUE KEY `username` (`username`), ADD KEY `group` (`group`);

--
-- Indexes for table `enduser`
--
ALTER TABLE `enduser`
  ADD PRIMARY KEY (`edr_id`), ADD UNIQUE KEY `edr_email` (`edr_email`), ADD KEY `edr_id` (`edr_id`), ADD KEY `edr_designated_office` (`edr_designated_office`), ADD KEY `edr_designated_office_2` (`edr_designated_office`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`group_id`), ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`prnl_id`), ADD UNIQUE KEY `prnl_email` (`prnl_email`), ADD KEY `prnl_id` (`prnl_id`), ADD KEY `prnl_designated_office` (`prnl_designated_office`);

--
-- Indexes for table `prnl_account`
--
ALTER TABLE `prnl_account`
  ADD PRIMARY KEY (`account_id`), ADD UNIQUE KEY `username` (`username`), ADD KEY `group` (`group`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users_session`
--
ALTER TABLE `users_session`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_requests`
--
ALTER TABLE `account_requests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `commitee`
--
ALTER TABLE `commitee`
  MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `users_session`
--
ALTER TABLE `users_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `commitee`
--
ALTER TABLE `commitee`
ADD CONSTRAINT `commitee_ibfk_1` FOREIGN KEY (`unit_office`) REFERENCES `units` (`ID`) ON UPDATE CASCADE;

--
-- Constraints for table `edr_account`
--
ALTER TABLE `edr_account`
ADD CONSTRAINT `edr_account_ibfk_1` FOREIGN KEY (`group`) REFERENCES `group` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk1` FOREIGN KEY (`account_id`) REFERENCES `enduser` (`edr_id`);

--
-- Constraints for table `enduser`
--
ALTER TABLE `enduser`
ADD CONSTRAINT `enduser_ibfk_1` FOREIGN KEY (`edr_designated_office`) REFERENCES `units` (`ID`) ON UPDATE CASCADE;

--
-- Constraints for table `personnel`
--
ALTER TABLE `personnel`
ADD CONSTRAINT `personnel_ibfk_1` FOREIGN KEY (`prnl_designated_office`) REFERENCES `units` (`ID`) ON UPDATE CASCADE;

--
-- Constraints for table `prnl_account`
--
ALTER TABLE `prnl_account`
ADD CONSTRAINT `prnl_account_fk` FOREIGN KEY (`account_id`) REFERENCES `personnel` (`prnl_id`),
ADD CONSTRAINT `prnl_account_ibfk_1` FOREIGN KEY (`group`) REFERENCES `group` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
