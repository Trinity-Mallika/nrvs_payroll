-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2026 at 01:33 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nrvs_payroll`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_log`
--

CREATE TABLE `attendance_log` (
  `attendance_log_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `department_id` int(11) NOT NULL DEFAULT 0,
  `shift_id` int(11) NOT NULL DEFAULT 0,
  `machine_userid` int(11) NOT NULL DEFAULT 0,
  `month` int(11) NOT NULL DEFAULT 0,
  `year` year(4) DEFAULT NULL,
  `attendance_date` date DEFAULT NULL,
  `attendance_stamp` datetime DEFAULT NULL,
  `intime` time DEFAULT NULL,
  `entry_type` varchar(20) DEFAULT NULL,
  `outtime` time DEFAULT NULL,
  `entry_type_out` varchar(20) DEFAULT NULL,
  `working_hours` varchar(50) DEFAULT NULL,
  `in_status` varchar(2) DEFAULT NULL,
  `out_status` varchar(3) DEFAULT NULL,
  `machineid` varchar(50) DEFAULT NULL,
  `attheadid` int(11) NOT NULL,
  `attendance_status` varchar(100) DEFAULT NULL,
  `prev_attendance_status` varchar(60) DEFAULT NULL,
  `basic_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `in_remark` text DEFAULT NULL,
  `out_remark` text DEFAULT NULL,
  `previous_in_time` time DEFAULT NULL,
  `previous_out_time` time DEFAULT NULL,
  `late_in` time DEFAULT NULL,
  `early_out` time DEFAULT NULL,
  `overtime` time DEFAULT NULL,
  `fine_amt` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fine_action` varchar(100) DEFAULT NULL,
  `attendanceby` int(11) NOT NULL DEFAULT 0,
  `verifyiedby` int(11) NOT NULL DEFAULT 0,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `updateby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(250) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `createtime` time DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) DEFAULT 0,
  `sessionid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_log`
--
ALTER TABLE `attendance_log`
  ADD PRIMARY KEY (`attendance_log_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_log`
--
ALTER TABLE `attendance_log`
  MODIFY `attendance_log_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
