-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 10.4.1.136:3306
-- Generation Time: Feb 06, 2020 at 07:43 AM
-- Server version: 10.2.24-MariaDB
-- PHP Version: 7.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lirs`
--

-- --------------------------------------------------------

--
-- Table structure for table `HOUSE`
--

CREATE TABLE `HOUSE` (
  `H_ID` varchar(3) NOT NULL,
  `H_NAME` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `HOUSE`
--

INSERT INTO `HOUSE` (`H_ID`, `H_NAME`) VALUES
('LI', 'Ledang Inn'),
('VF', 'Villa Firdaus'),
('VL', 'Villa Lekiu');

-- --------------------------------------------------------

--
-- Table structure for table `PROGRAMME`
--

CREATE TABLE `PROGRAMME` (
  `ID_PROG` varchar(5) NOT NULL,
  `PROG` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PROGRAMME`
--

INSERT INTO `PROGRAMME` (`ID_PROG`, `PROG`) VALUES
('AC110', 'Diploma in Accountancy'),
('AC220', 'Bachelor of Accountancy (Hons)'),
('BM111', 'Diploma in Business Studies'),
('BM114', 'Diploma in Investment Analysis '),
('BM117', 'Diploma Business Studies (Transportation)'),
('BM119', 'Diploma in Banking Studies'),
('BM240', 'Bachelor of Business Administration (Hons) Marketing'),
('BM242', 'Bachelor of Business Administration (Hons) Finance'),
('BM249', 'Bachelor of Business Administration (Hons) Islamic Banking'),
('BM251', 'Bachelor of Business Administration (Hons) Investment Management'),
('CS110', 'Diploma in Computer Science'),
('CS113', 'Diploma in Quantitative Science'),
('CS143', 'Diploma in Mathematical Science'),
('EC110', 'Diploma in Civil Engineering'),
('EE110', 'Diploma in Electrical Engineering (Control and Instrumentation)'),
('EE111', 'Diploma in Electrical Engineering (Electronic)'),
('EE112', 'Diploma in Electrical Engineering (Power)'),
('EH110', 'Diploma in Chemical Engineering'),
('EM110', 'Diploma in Mechanical Engineering'),
('IM110', 'Diploma in Information Management'),
('IM246', 'Bachelor of Information Science (Hons) Records Management'),
('PD002', 'Pre-Commerce');

-- --------------------------------------------------------

--
-- Table structure for table `RESERVE`
--

CREATE TABLE `RESERVE` (
  `RV_ID` int(10) NOT NULL,
  `RV_STUD` int(10) NOT NULL,
  `RV_DATE` datetime NOT NULL DEFAULT current_timestamp(),
  `RV_IN` date NOT NULL,
  `RV_OUT` date NOT NULL,
  `RV_ROOM` varchar(5) DEFAULT NULL,
  `RV_STAT` varchar(2) NOT NULL DEFAULT 'PG'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ROOM`
--

CREATE TABLE `ROOM` (
  `R_ID` varchar(5) NOT NULL,
  `R_TYPE` varchar(2) NOT NULL,
  `R_HOUSE` varchar(3) NOT NULL,
  `R_STAT` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ROOM`
--

INSERT INTO `ROOM` (`R_ID`, `R_TYPE`, `R_HOUSE`, `R_STAT`) VALUES
('LI001', 'QB', 'LI', 0),
('LI002', 'TS', 'LI', 0),
('LI003', 'TS', 'LI', 1),
('LI004', 'KB', 'LI', 1),
('LI105', 'TS', 'LI', 1),
('LI106', 'QB', 'LI', 1),
('LI107', 'QB', 'LI', 1),
('LI108', 'KB', 'LI', 0),
('VF001', 'KB', 'VF', 1),
('VF002', 'TS', 'VF', 0),
('VF003', 'TS', 'VF', 1),
('VF104', 'TS', 'VF', 0),
('VF105', 'KB', 'VF', 1),
('VF106', 'QB', 'VF', 1),
('VF107', 'KB', 'VF', 0),
('VL001', 'KB', 'VL', 1),
('VL002', 'QB', 'VL', 1),
('VL003', 'TS', 'VL', 0),
('VL104', 'TS', 'VL', 1),
('VL105', 'TS', 'VL', 0),
('VL106', 'QB', 'VL', 0),
('VL107', 'KB', 'VL', 1);

-- --------------------------------------------------------

--
-- Table structure for table `STUDENT`
--

CREATE TABLE `STUDENT` (
  `S_ID` int(10) NOT NULL,
  `S_NAME` varchar(100) NOT NULL,
  `S_IC` varchar(12) NOT NULL,
  `S_PROG` varchar(5) NOT NULL,
  `S_PART` varchar(2) NOT NULL,
  `S_PHONE` varchar(20) NOT NULL,
  `S_MAIL` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `UPK`
--

CREATE TABLE `UPK` (
  `U_ID` int(10) NOT NULL,
  `U_NAME` varchar(100) NOT NULL,
  `U_PWD` varchar(50) NOT NULL,
  `U_PHONE` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `UPK`
--

INSERT INTO `UPK` (`U_ID`, `U_NAME`, `U_PWD`, `U_PHONE`) VALUES
(12345, 'Abd Rahim Bin Abd Majid', 'upknr', '+607-935 2412'),
(54321, 'Nurul Fakhira Binti Juraimi', 'upknr', '+6019-797 8468');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `HOUSE`
--
ALTER TABLE `HOUSE`
  ADD PRIMARY KEY (`H_ID`);

--
-- Indexes for table `PROGRAMME`
--
ALTER TABLE `PROGRAMME`
  ADD PRIMARY KEY (`ID_PROG`);

--
-- Indexes for table `RESERVE`
--
ALTER TABLE `RESERVE`
  ADD PRIMARY KEY (`RV_ID`),
  ADD KEY `RV_STUD` (`RV_STUD`),
  ADD KEY `RV_ROOM` (`RV_ROOM`);

--
-- Indexes for table `ROOM`
--
ALTER TABLE `ROOM`
  ADD PRIMARY KEY (`R_ID`),
  ADD KEY `R_HOUSE` (`R_HOUSE`);

--
-- Indexes for table `STUDENT`
--
ALTER TABLE `STUDENT`
  ADD PRIMARY KEY (`S_ID`),
  ADD KEY `S_PROG` (`S_PROG`);

--
-- Indexes for table `UPK`
--
ALTER TABLE `UPK`
  ADD PRIMARY KEY (`U_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `RESERVE`
--
ALTER TABLE `RESERVE`
  MODIFY `RV_ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `RESERVE`
--
ALTER TABLE `RESERVE`
  ADD CONSTRAINT `RESERVE_IBFK_1` FOREIGN KEY (`RV_STUD`) REFERENCES `STUDENT` (`S_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `RESERVE_IBFK_2` FOREIGN KEY (`RV_ROOM`) REFERENCES `ROOM` (`R_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `ROOM`
--
ALTER TABLE `ROOM`
  ADD CONSTRAINT `ROOM_IBFK_1` FOREIGN KEY (`R_HOUSE`) REFERENCES `HOUSE` (`H_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `STUDENT`
--
ALTER TABLE `STUDENT`
  ADD CONSTRAINT `STUDENT_IBFK_1` FOREIGN KEY (`S_PROG`) REFERENCES `PROGRAMME` (`ID_PROG`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
