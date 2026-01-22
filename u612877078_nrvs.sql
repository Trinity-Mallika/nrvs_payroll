-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 20, 2026 at 09:53 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u612877078_nrvs`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_entry`
--

CREATE TABLE `attendance_entry` (
  `attendance_id` int(11) NOT NULL,
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
  `basic_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `in_remark` text DEFAULT NULL,
  `out_remark` text DEFAULT NULL,
  `previous_in_time` time DEFAULT NULL,
  `previous_out_time` time DEFAULT NULL,
  `overtime` time DEFAULT NULL,
  `fine_amt` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fine_action` varchar(100) DEFAULT NULL,
  `attendanceby` int(11) NOT NULL DEFAULT 0,
  `verifyiedby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(250) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `createtime` time DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) DEFAULT 0,
  `sessionid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_entry`
--

INSERT INTO `attendance_entry` (`attendance_id`, `emp_id`, `department_id`, `shift_id`, `machine_userid`, `month`, `year`, `attendance_date`, `attendance_stamp`, `intime`, `entry_type`, `outtime`, `entry_type_out`, `working_hours`, `in_status`, `out_status`, `machineid`, `attheadid`, `attendance_status`, `basic_salary`, `in_remark`, `out_remark`, `previous_in_time`, `previous_out_time`, `overtime`, `fine_amt`, `fine_action`, `attendanceby`, `verifyiedby`, `ipaddress`, `createdate`, `createtime`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(60, 1, 5, 7, 0, 12, '2025', '2025-12-01', '2025-12-01 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:46', NULL, 1, 10),
(61, 1, 5, 7, 0, 12, '2025', '2025-12-02', '2025-12-02 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:50', NULL, 1, 10),
(62, 1, 5, 7, 0, 12, '2025', '2025-12-03', '2025-12-03 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:56', NULL, 1, 10),
(63, 1, 5, 7, 0, 12, '2025', '2025-12-04', '2025-12-04 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:01', NULL, 1, 10),
(64, 1, 5, 7, 0, 12, '2025', '2025-12-08', '2025-12-08 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:04', NULL, 1, 10),
(65, 1, 5, 7, 0, 12, '2025', '2025-12-09', '2025-12-09 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:08', NULL, 1, 10),
(66, 1, 5, 7, 0, 12, '2025', '2025-12-10', '2025-12-10 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:12', NULL, 1, 10),
(67, 1, 5, 7, 0, 12, '2025', '2025-12-11', '2025-12-11 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:15', NULL, 1, 10),
(68, 1, 5, 7, 0, 12, '2025', '2025-12-12', '2025-12-12 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:19', NULL, 1, 10),
(69, 1, 5, 7, 0, 12, '2025', '2025-12-13', '2025-12-13 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:22', NULL, 1, 10),
(70, 1, 5, 7, 0, 12, '2025', '2025-12-14', '2025-12-14 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:34', NULL, 1, 10),
(71, 1, 5, 7, 0, 12, '2025', '2025-12-15', '2025-12-15 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:38', NULL, 1, 10),
(72, 1, 5, 7, 0, 12, '2025', '2025-12-16', '2025-12-16 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:43', NULL, 1, 10),
(73, 1, 5, 7, 0, 12, '2025', '2025-12-31', '2025-12-31 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:48', NULL, 1, 10),
(75, 1, 5, 7, 0, 12, '2025', '2025-12-28', '2025-12-28 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:15:55', NULL, 1, 10),
(79, 1, 5, 7, 0, 12, '2025', '2025-12-24', '2025-12-24 00:00:00', '07:00:00', 'manual', '13:00:00', NULL, '06:00:00', 'IN', 'OUT', NULL, 3, 'Half Day', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:16:11', NULL, 1, 10),
(80, 1, 5, 7, 0, 12, '2025', '2025-12-23', '2025-12-23 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:16:16', NULL, 1, 10),
(81, 1, 5, 7, 0, 12, '2025', '2025-12-20', '2025-12-20 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:16:20', NULL, 1, 10),
(82, 1, 5, 7, 0, 12, '2025', '2025-12-18', '2025-12-18 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:16:25', NULL, 1, 10),
(83, 1, 5, 7, 0, 12, '2025', '2025-12-05', '2025-12-05 00:00:00', '07:00:00', 'manual', '13:00:00', NULL, '06:00:00', 'IN', 'OUT', NULL, 3, 'Half Day', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:20:00', NULL, 1, 10),
(656, 1, 5, 6, 1, 1, '2026', '2026-01-04', '2026-01-04 00:00:00', '09:00:00', 'manual', '18:00:00', 'manual', '09:00:00', 'IN', 'OUT', 'CGCA201263139', 1, 'Present', 15000.00, '', NULL, NULL, NULL, '00:00:00', 0.00, NULL, 1, 1, NULL, '2026-01-16', '08:00:00', '2026-01-20', 1, 13),
(698, 1, 5, 7, 0, 12, '2025', '2025-12-06', '2025-12-06 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '17:23:33', NULL, 1, 10),
(699, 1, 5, 7, 0, 12, '2025', '2025-12-07', '2025-12-07 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '17:23:35', NULL, 1, 10),
(700, 1, 5, 7, 0, 12, '2025', '2025-12-17', '2025-12-17 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '17:23:39', NULL, 1, 10),
(701, 1, 5, 7, 0, 12, '2025', '2025-12-21', '2025-12-21 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '17:23:42', NULL, 1, 10),
(702, 1, 5, 7, 0, 12, '2025', '2025-12-22', '2025-12-22 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '17:23:44', NULL, 1, 10),
(734, 1, 5, 6, 0, 1, '2026', '2026-01-01', '2026-01-01 00:00:00', '09:00:00', 'manual', '18:00:00', 'manual', '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '18:43:09', '2026-01-20', 1, 13),
(735, 1, 5, 6, 0, 1, '2026', '2026-01-02', '2026-01-02 00:00:00', '09:00:00', 'manual', '18:00:00', 'manual', '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '18:43:28', '2026-01-20', 1, 13),
(737, 1, 5, 6, 0, 1, '2026', '2026-01-05', '2026-01-05 00:00:00', '09:00:00', 'manual', '18:00:00', 'manual', '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '18:44:02', '2026-01-20', 1, 13),
(738, 1, 5, 6, 0, 1, '2026', '2026-01-06', '2026-01-06 00:00:00', '09:00:00', 'manual', '18:00:00', 'manual', '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '18:44:07', '2026-01-20', 1, 13),
(739, 4, 5, 6, 0, 1, '2026', '2026-01-05', '2026-01-05 00:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '18:44:46', NULL, 1, 10),
(740, 5, 5, 6, 0, 1, '2026', '2026-01-08', '2026-01-08 00:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 135000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '18:45:09', NULL, 1, 10),
(741, 5, 5, 6, 0, 1, '2026', '2026-01-07', '2026-01-07 00:00:00', '13:30:00', 'manual', '18:00:00', NULL, '04:30:00', 'IN', 'OUT', NULL, 3, 'Half Day', 135000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-17', '10:52:43', NULL, 1, 10),
(742, 4, 5, 6, 0, 1, '2026', '2026-01-02', '2026-01-02 00:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-17', '10:53:55', NULL, 1, 10),
(743, 4, 5, 7, 0, 1, '2026', '2026-01-03', '2026-01-03 00:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '59.153.97.145', '2026-01-17', '18:42:56', NULL, 1, 10),
(744, 4, 5, 7, 0, 1, '2026', '2026-01-04', '2026-01-04 00:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '59.153.97.145', '2026-01-17', '18:42:59', NULL, 1, 10),
(745, 2, 5, 7, 0, 1, '2026', '2026-01-01', '2026-01-01 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(746, 2, 5, 7, 0, 1, '2026', '2026-01-02', '2026-01-02 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(747, 2, 5, 7, 0, 1, '2026', '2026-01-03', '2026-01-03 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(748, 2, 5, 7, 0, 1, '2026', '2026-01-04', '2026-01-04 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(749, 2, 5, 7, 0, 1, '2026', '2026-01-05', '2026-01-05 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(750, 2, 5, 7, 0, 1, '2026', '2026-01-06', '2026-01-06 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(751, 2, 5, 7, 0, 1, '2026', '2026-01-07', '2026-01-07 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(752, 2, 5, 7, 0, 1, '2026', '2026-01-08', '2026-01-08 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(753, 2, 5, 7, 0, 1, '2026', '2026-01-09', '2026-01-09 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(754, 2, 5, 7, 0, 1, '2026', '2026-01-10', '2026-01-10 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(755, 2, 5, 7, 0, 1, '2026', '2026-01-11', '2026-01-11 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(756, 2, 5, 7, 0, 1, '2026', '2026-01-12', '2026-01-12 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(757, 2, 5, 7, 0, 1, '2026', '2026-01-13', '2026-01-13 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(758, 2, 5, 7, 0, 1, '2026', '2026-01-14', '2026-01-14 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(759, 2, 5, 7, 0, 1, '2026', '2026-01-15', '2026-01-15 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(760, 2, 5, 7, 0, 1, '2026', '2026-01-16', '2026-01-16 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(761, 2, 5, 7, 0, 1, '2026', '2026-01-17', '2026-01-17 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(762, 5, 5, 6, 0, 12, '2025', '2025-12-01', '2025-12-01 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(763, 5, 5, 6, 0, 12, '2025', '2025-12-02', '2025-12-02 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(764, 5, 5, 6, 0, 12, '2025', '2025-12-03', '2025-12-03 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(765, 5, 5, 6, 0, 12, '2025', '2025-12-04', '2025-12-04 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(766, 5, 5, 6, 0, 12, '2025', '2025-12-05', '2025-12-05 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(767, 5, 5, 6, 0, 12, '2025', '2025-12-06', '2025-12-06 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(768, 5, 5, 6, 0, 12, '2025', '2025-12-07', '2025-12-07 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(769, 5, 5, 6, 0, 12, '2025', '2025-12-08', '2025-12-08 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(770, 5, 5, 6, 0, 12, '2025', '2025-12-09', '2025-12-09 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(771, 5, 5, 6, 0, 12, '2025', '2025-12-10', '2025-12-10 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(772, 5, 5, 6, 0, 12, '2025', '2025-12-11', '2025-12-11 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(773, 5, 5, 6, 0, 12, '2025', '2025-12-12', '2025-12-12 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(774, 5, 5, 6, 0, 12, '2025', '2025-12-13', '2025-12-13 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(775, 5, 5, 6, 0, 12, '2025', '2025-12-14', '2025-12-14 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(776, 5, 5, 6, 0, 12, '2025', '2025-12-15', '2025-12-15 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(777, 5, 5, 6, 0, 12, '2025', '2025-12-16', '2025-12-16 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(778, 5, 5, 6, 0, 12, '2025', '2025-12-17', '2025-12-17 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(779, 5, 5, 6, 0, 12, '2025', '2025-12-18', '2025-12-18 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(780, 5, 5, 6, 0, 12, '2025', '2025-12-19', '2025-12-19 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(781, 5, 5, 6, 0, 12, '2025', '2025-12-20', '2025-12-20 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(782, 5, 5, 6, 0, 12, '2025', '2025-12-21', '2025-12-21 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(783, 5, 5, 6, 0, 12, '2025', '2025-12-22', '2025-12-22 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(784, 5, 5, 6, 0, 12, '2025', '2025-12-23', '2025-12-23 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(785, 5, 5, 6, 0, 12, '2025', '2025-12-24', '2025-12-24 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(786, 5, 5, 6, 0, 12, '2025', '2025-12-25', '2025-12-25 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(787, 5, 5, 6, 0, 12, '2025', '2025-12-26', '2025-12-26 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(788, 5, 5, 6, 0, 12, '2025', '2025-12-27', '2025-12-27 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(789, 5, 5, 6, 0, 12, '2025', '2025-12-28', '2025-12-28 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(790, 5, 5, 6, 0, 12, '2025', '2025-12-29', '2025-12-29 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(791, 5, 5, 6, 0, 12, '2025', '2025-12-30', '2025-12-30 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(792, 5, 5, 6, 0, 12, '2025', '2025-12-31', '2025-12-31 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(793, 6, 5, 6, 0, 12, '2025', '2025-12-01', '2025-12-01 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(794, 6, 5, 6, 0, 12, '2025', '2025-12-02', '2025-12-02 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(795, 6, 5, 6, 0, 12, '2025', '2025-12-03', '2025-12-03 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(796, 6, 5, 6, 0, 12, '2025', '2025-12-04', '2025-12-04 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(797, 6, 5, 6, 0, 12, '2025', '2025-12-05', '2025-12-05 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(798, 6, 5, 6, 0, 12, '2025', '2025-12-06', '2025-12-06 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(799, 6, 5, 6, 0, 12, '2025', '2025-12-07', '2025-12-07 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(800, 6, 5, 6, 0, 12, '2025', '2025-12-08', '2025-12-08 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(801, 6, 5, 6, 0, 12, '2025', '2025-12-09', '2025-12-09 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(802, 6, 5, 6, 0, 12, '2025', '2025-12-10', '2025-12-10 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(803, 6, 5, 6, 0, 12, '2025', '2025-12-11', '2025-12-11 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(804, 6, 5, 6, 0, 12, '2025', '2025-12-12', '2025-12-12 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(805, 6, 5, 6, 0, 12, '2025', '2025-12-13', '2025-12-13 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(806, 6, 5, 6, 0, 12, '2025', '2025-12-14', '2025-12-14 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(807, 6, 5, 6, 0, 12, '2025', '2025-12-15', '2025-12-15 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(808, 6, 5, 6, 0, 12, '2025', '2025-12-16', '2025-12-16 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(809, 6, 5, 6, 0, 12, '2025', '2025-12-17', '2025-12-17 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(810, 6, 5, 6, 0, 12, '2025', '2025-12-18', '2025-12-18 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(811, 6, 5, 6, 0, 12, '2025', '2025-12-19', '2025-12-19 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(812, 6, 5, 6, 0, 12, '2025', '2025-12-20', '2025-12-20 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(813, 6, 5, 6, 0, 12, '2025', '2025-12-21', '2025-12-21 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(814, 6, 5, 6, 0, 12, '2025', '2025-12-22', '2025-12-22 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(815, 6, 5, 6, 0, 12, '2025', '2025-12-23', '2025-12-23 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(816, 6, 5, 6, 0, 12, '2025', '2025-12-24', '2025-12-24 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(817, 6, 5, 6, 0, 12, '2025', '2025-12-25', '2025-12-25 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(818, 6, 5, 6, 0, 12, '2025', '2025-12-26', '2025-12-26 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(819, 6, 5, 6, 0, 12, '2025', '2025-12-27', '2025-12-27 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(820, 6, 5, 6, 0, 12, '2025', '2025-12-28', '2025-12-28 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(821, 6, 5, 6, 0, 12, '2025', '2025-12-29', '2025-12-29 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(822, 6, 5, 6, 0, 12, '2025', '2025-12-30', '2025-12-30 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(823, 6, 5, 6, 0, 12, '2025', '2025-12-31', '2025-12-31 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(824, 2, 5, 7, 0, 12, '2025', '2025-12-01', '2025-12-01 00:00:00', '08:00:00', 'manual', '20:00:00', 'manual', '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '59.153.97.145', '2026-01-19', '15:30:53', '2026-01-19', 1, 13),
(825, 2, 5, 7, 0, 12, '2025', '2025-12-02', '2025-12-02 00:00:00', '08:00:00', 'manual', '20:00:00', 'manual', '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '59.153.97.145', '2026-01-19', '15:31:56', '2026-01-19', 1, 13),
(826, 2, 5, 7, 0, 12, '2025', '2025-12-03', '2025-12-03 00:00:00', '08:00:00', 'manual', '20:00:00', 'manual', '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '59.153.97.145', '2026-01-19', '15:32:03', '2026-01-19', 1, 13),
(827, 2, 5, 7, 0, 12, '2025', '2025-12-04', '2025-12-04 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(828, 2, 5, 7, 0, 12, '2025', '2025-12-05', '2025-12-05 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(829, 2, 5, 7, 0, 12, '2025', '2025-12-06', '2025-12-06 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(830, 2, 5, 7, 0, 12, '2025', '2025-12-07', '2025-12-07 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(831, 2, 5, 7, 0, 12, '2025', '2025-12-08', '2025-12-08 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(832, 2, 5, 7, 0, 12, '2025', '2025-12-09', '2025-12-09 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(833, 2, 5, 7, 0, 12, '2025', '2025-12-10', '2025-12-10 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(834, 2, 5, 7, 0, 12, '2025', '2025-12-11', '2025-12-11 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(835, 2, 5, 7, 0, 12, '2025', '2025-12-12', '2025-12-12 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(836, 2, 5, 7, 0, 12, '2025', '2025-12-13', '2025-12-13 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(837, 2, 5, 7, 0, 12, '2025', '2025-12-14', '2025-12-14 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(838, 2, 5, 7, 0, 12, '2025', '2025-12-15', '2025-12-15 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(839, 2, 5, 7, 0, 12, '2025', '2025-12-16', '2025-12-16 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(840, 2, 5, 7, 0, 12, '2025', '2025-12-17', '2025-12-17 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(841, 2, 5, 7, 0, 12, '2025', '2025-12-18', '2025-12-18 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(842, 2, 5, 7, 0, 12, '2025', '2025-12-19', '2025-12-19 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(843, 2, 5, 7, 0, 12, '2025', '2025-12-20', '2025-12-20 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(844, 2, 5, 7, 0, 12, '2025', '2025-12-21', '2025-12-21 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(845, 2, 5, 7, 0, 12, '2025', '2025-12-22', '2025-12-22 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(846, 2, 5, 7, 0, 12, '2025', '2025-12-23', '2025-12-23 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(847, 2, 5, 7, 0, 12, '2025', '2025-12-24', '2025-12-24 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(848, 2, 5, 7, 0, 12, '2025', '2025-12-25', '2025-12-25 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(849, 2, 5, 7, 0, 12, '2025', '2025-12-26', '2025-12-26 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(850, 2, 5, 7, 0, 12, '2025', '2025-12-27', '2025-12-27 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(851, 2, 5, 7, 0, 12, '2025', '2025-12-28', '2025-12-28 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(852, 2, 5, 7, 0, 12, '2025', '2025-12-29', '2025-12-29 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(853, 2, 5, 7, 0, 12, '2025', '2025-12-30', '2025-12-30 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(854, 2, 5, 7, 0, 12, '2025', '2025-12-31', '2025-12-31 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 200000.00, 'Mannual', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(855, 4, 5, 7, 0, 1, '2026', '2026-01-01', '2026-01-01 00:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 20003.00, 'dsfs', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '59.153.97.145', '2026-01-19', '18:34:30', NULL, 1, 13),
(856, 1, 5, 6, 0, 1, '2026', '2026-01-03', '2026-01-03 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(857, 1, 5, 6, 0, 1, '2026', '2026-01-07', '2026-01-07 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(858, 1, 5, 6, 0, 1, '2026', '2026-01-08', '2026-01-08 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(859, 1, 5, 6, 0, 1, '2026', '2026-01-09', '2026-01-09 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(860, 1, 5, 6, 0, 1, '2026', '2026-01-10', '2026-01-10 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(861, 1, 5, 6, 0, 1, '2026', '2026-01-11', '2026-01-11 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(862, 1, 5, 6, 0, 1, '2026', '2026-01-12', '2026-01-12 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(863, 1, 5, 6, 0, 1, '2026', '2026-01-13', '2026-01-13 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(864, 1, 5, 6, 0, 1, '2026', '2026-01-14', '2026-01-14 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(865, 1, 5, 6, 0, 1, '2026', '2026-01-15', '2026-01-15 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(866, 1, 5, 6, 0, 1, '2026', '2026-01-16', '2026-01-16 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(867, 1, 5, 6, 0, 1, '2026', '2026-01-17', '2026-01-17 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(868, 1, 5, 6, 0, 1, '2026', '2026-01-18', '2026-01-18 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(869, 1, 5, 6, 0, 1, '2026', '2026-01-19', '2026-01-19 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13),
(870, 1, 5, 6, 0, 1, '2026', '2026-01-20', '2026-01-20 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 13);

-- --------------------------------------------------------

--
-- Table structure for table `attendance_heading`
--

CREATE TABLE `attendance_heading` (
  `attheadid` int(11) NOT NULL,
  `att_type` varchar(100) NOT NULL,
  `att_color` varchar(50) NOT NULL,
  `unit_id` int(11) DEFAULT 0,
  `sessionid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_heading`
--

INSERT INTO `attendance_heading` (`attheadid`, `att_type`, `att_color`, `unit_id`, `sessionid`) VALUES
(1, 'Present', 'present', 0, 0),
(2, 'Absent', 'absent', 0, 0),
(3, 'Halfday', 'half-day', 0, 0),
(4, 'Paid Leave', 'paid-leave', 0, 0),
(5, 'Incomplete', 'incomplete', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bank_master`
--

CREATE TABLE `bank_master` (
  `bank_id` int(11) NOT NULL,
  `bank_name` varchar(200) DEFAULT NULL,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank_master`
--

INSERT INTO `bank_master` (`bank_id`, `bank_name`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 'SBI', 2, '192.168.1.10', '2025-12-31', NULL, 1, 10),
(2, 'Axis', 2, '192.168.1.10', '2025-12-31', '2025-12-31', 4, 10),
(3, 'CRGB', 1, '192.168.1.30', NULL, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `company_setting`
--

CREATE TABLE `company_setting` (
  `setting_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `co_id` varchar(10) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `landline` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `in_time` time NOT NULL,
  `out_time` time NOT NULL,
  `working_hour` varchar(5) NOT NULL,
  `company_img` text NOT NULL,
  `userid` int(11) NOT NULL,
  `ipaddress` varchar(50) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `unit_id` int(11) DEFAULT 0,
  `sessionid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `company_setting`
--

INSERT INTO `company_setting` (`setting_id`, `company_name`, `co_id`, `mobile`, `landline`, `address`, `email`, `in_time`, `out_time`, `working_hour`, `company_img`, `userid`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 'NRVS PAYROLL', 'CE', '9770131555', '895981911', 'Raipur', 'nipeshp@gmail.com', '10:30:00', '18:30:00', '08:00', '', 0, '192.168.1.21', '2025-12-04', '2025-12-04 07:08:36', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `c_off_setting`
--

CREATE TABLE `c_off_setting` (
  `c_off_setting_id` int(11) NOT NULL,
  `setting_type` varchar(50) NOT NULL,
  `week_off_Setting_r` varchar(11) NOT NULL,
  `d1` varchar(50) NOT NULL,
  `d2` varchar(50) NOT NULL,
  `d3` varchar(50) NOT NULL,
  `d4` varchar(50) NOT NULL,
  `d5` varchar(50) DEFAULT NULL,
  `w1` varchar(50) NOT NULL,
  `w2` varchar(50) NOT NULL,
  `w3` varchar(50) NOT NULL,
  `w4` varchar(50) NOT NULL,
  `w5` varchar(50) DEFAULT NULL,
  `unit_id` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(50) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `c_off_setting`
--

INSERT INTO `c_off_setting` (`c_off_setting_id`, `setting_type`, `week_off_Setting_r`, `d1`, `d2`, `d3`, `d4`, `d5`, `w1`, `w2`, `w3`, `w4`, `w5`, `unit_id`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `sessionid`) VALUES
(1, 'c_off', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '', '2025-12-19', '0000-00-00', 0),
(2, 'c_off', 'ESIC', '7', '13', '20', '26', '0', '0.5', '1', '1.5', '2', '0', 1, 1, '', '2025-12-22', '0000-00-00', 0),
(3, 'c_off', 'Non ESIC', '5', '10', '15', '20', '26', '0.5', '1', '1.5', '2', '2.5', 1, 0, '', '2025-12-22', '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `department_master`
--

CREATE TABLE `department_master` (
  `department_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `subdivision_id` int(11) NOT NULL DEFAULT 0,
  `department_name` varchar(200) NOT NULL,
  `c_off_check` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(200) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `unit_id` int(11) NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_master`
--

INSERT INTO `department_master` (`department_id`, `division_id`, `subdivision_id`, `department_name`, `c_off_check`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 0, 0, 'Accounts', 0, 2, '192.168.1.10', '2025-12-31', '0000-00-00', 4, 10),
(2, 0, 0, 'IT', 1, 2, '192.168.1.10', '2025-12-31', '0000-00-00', 4, 10),
(3, 0, 0, 'Non - IT', 0, 2, '192.168.1.10', '2025-12-31', '0000-00-00', 4, 10),
(5, 0, 2, 'Accounts', 1, 1, '192.168.1.37', '2025-12-31', '2026-01-15', 1, 10),
(6, 0, 2, 'IT', 1, 1, '192.168.1.37', '2025-12-31', '2026-01-15', 1, 10),
(7, 0, 1, 'Non - IT', 1, 1, '192.168.1.37', '2025-12-31', '2026-01-15', 1, 10),
(8, 0, 2, 'HR', 1, 1, '192.168.1.37', '0000-00-00', '2026-01-15', 1, 10),
(9, 0, 1, 'Security', 0, 1, '192.168.1.37', '2026-01-09', '2026-01-15', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `designation_master`
--

CREATE TABLE `designation_master` (
  `designation_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL DEFAULT 0,
  `designation` varchar(200) NOT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(200) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `unit_id` int(11) NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designation_master`
--

INSERT INTO `designation_master` (`designation_id`, `grade_id`, `designation`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 0, 'HR', 2, '192.168.1.10', '2025-12-31', '0000-00-00', 4, 10),
(2, 0, 'Developer', 2, '192.168.1.10', '2025-12-31', '0000-00-00', 4, 10),
(3, 0, 'Manager', 2, '192.168.1.10', '2025-12-31', '0000-00-00', 4, 10),
(5, 1, 'HR', 1, '192.168.1.37', '2025-12-31', '2026-01-15', 1, 10),
(6, 1, 'ACCOUNTANT', 1, '192.168.1.37', '2025-12-31', '2026-01-15', 1, 10),
(7, 1, 'Manager', 1, '192.168.1.37', '2025-12-31', '2026-01-15', 1, 10),
(8, 1, 'Developer', 1, '192.168.1.37', '2025-12-31', '2026-01-15', 1, 10),
(10, 5, 'Developer 2', 1, '192.168.1.37', '2026-01-16', '0000-00-00', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `division_master`
--

CREATE TABLE `division_master` (
  `division_id` int(11) NOT NULL,
  `division_name` varchar(200) DEFAULT NULL,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `division_master`
--

INSERT INTO `division_master` (`division_id`, `division_name`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 'Division 1', 1, '192.168.1.37', '2026-01-15', NULL, 1, 10),
(2, 'Division 2', 1, '192.168.1.37', '2026-01-15', NULL, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `document_master`
--

CREATE TABLE `document_master` (
  `doc_id` int(11) NOT NULL,
  `document_name` varchar(200) NOT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(200) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `unit_id` int(11) NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_master`
--

INSERT INTO `document_master` (`doc_id`, `document_name`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(2, 'Aadhar card', 2, '192.168.1.10', '2025-12-31', '2025-12-31', 4, 10),
(3, 'Pan card', 2, '192.168.1.10', '2025-12-31', '0000-00-00', 4, 10),
(4, '10th', 2, '192.168.1.10', '2025-12-31', '0000-00-00', 4, 10),
(6, 'ADHAR CARD', 1, '192.168.1.23', '2026-01-15', '2026-01-15', 1, 10),
(7, 'PAN CARD', 1, '192.168.1.23', '2026-01-15', '0000-00-00', 1, 10),
(8, 'DRIVING LICENCE', 1, '192.168.1.23', '2026-01-15', '0000-00-00', 1, 10),
(9, 'PHOTO GRAPH', 1, '192.168.1.23', '2026-01-15', '0000-00-00', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `emi_setting`
--

CREATE TABLE `emi_setting` (
  `emi_setting_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `emi_no` int(11) NOT NULL,
  `month` varchar(50) NOT NULL,
  `year` varchar(100) NOT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(50) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `unit_id` int(11) NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emi_setting`
--

INSERT INTO `emi_setting` (`emi_setting_id`, `emp_id`, `amount`, `emi_no`, `month`, `year`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 5, 500000.00, 10, '12', '2025', 1, '192.168.1.30', '2026-01-10', '0000-00-00', 1, 10),
(2, 2, 100000.00, 10, '8', '2025', 1, '192.168.1.30', '2026-01-10', '0000-00-00', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `emi_setting_details`
--

CREATE TABLE `emi_setting_details` (
  `emi_setting_details_id` int(11) NOT NULL,
  `emi_setting_id` int(11) NOT NULL DEFAULT 0,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `amount_detail` decimal(10,2) NOT NULL DEFAULT 0.00,
  `emi_no_detail` int(11) NOT NULL DEFAULT 0,
  `month_detail` varchar(100) DEFAULT NULL,
  `year_detail` int(11) DEFAULT 0,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(50) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emi_setting_details`
--

INSERT INTO `emi_setting_details` (`emi_setting_details_id`, `emi_setting_id`, `emp_id`, `amount_detail`, `emi_no_detail`, `month_detail`, `year_detail`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 1, 5, 50000.00, 1, '12', 2025, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(2, 1, 5, 50000.00, 2, '1', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(3, 1, 5, 50000.00, 3, '2', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(4, 1, 5, 50000.00, 4, '3', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(5, 1, 5, 50000.00, 5, '4', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(6, 1, 5, 50000.00, 6, '5', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(7, 1, 5, 50000.00, 7, '6', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(8, 1, 5, 50000.00, 8, '7', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(9, 1, 5, 50000.00, 9, '8', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(10, 1, 5, 50000.00, 10, '9', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(11, 2, 2, 10000.00, 1, '8', 2025, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(12, 2, 2, 10000.00, 2, '9', 2025, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(13, 2, 2, 10000.00, 3, '10', 2025, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(14, 2, 2, 10000.00, 4, '11', 2025, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(15, 2, 2, 10000.00, 5, '12', 2025, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(16, 2, 2, 10000.00, 6, '1', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(17, 2, 2, 10000.00, 7, '2', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(18, 2, 2, 10000.00, 8, '3', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(19, 2, 2, 10000.00, 9, '4', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0),
(20, 2, 2, 10000.00, 10, '5', 2026, 1, '192.168.1.30', '2026-01-10', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `employee_exit`
--

CREATE TABLE `employee_exit` (
  `exit_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `exit_type` varchar(50) DEFAULT NULL,
  `notice_period` varchar(100) DEFAULT NULL,
  `reason_for_leaving` text DEFAULT NULL,
  `last_working_date` date DEFAULT NULL,
  `resignation_date` date DEFAULT NULL,
  `is_approved` int(11) NOT NULL DEFAULT 0 COMMENT '0=pending, 1=approve , 2=reject',
  `reason_for_reject` text DEFAULT NULL,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_exit`
--

INSERT INTO `employee_exit` (`exit_id`, `emp_id`, `exit_type`, `notice_period`, `reason_for_leaving`, `last_working_date`, `resignation_date`, `is_approved`, `reason_for_reject`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(2, 2, 'Resignation', '30', 'Reason for LeavingReason for Leaving', '2026-02-15', '2026-01-31', 2, 'testingssss', 6, '192.168.1.37', '2026-01-17', NULL, 1, 10),
(4, 1, 'Termination', '12', 'gsfdgs', '2026-01-26', '2026-01-16', 0, '', 1, '192.168.1.37', '2026-01-17', NULL, 1, 10),
(5, 7, 'Resignation', '15', '', '2026-01-18', '2026-01-01', 1, '', 1, '59.153.97.145', '2026-01-19', NULL, 1, 13);

-- --------------------------------------------------------

--
-- Table structure for table `employee_master`
--

CREATE TABLE `employee_master` (
  `emp_id` int(11) NOT NULL,
  `emp_code` varchar(200) DEFAULT NULL,
  `biomatric_id` varchar(50) DEFAULT NULL,
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `father_name` varchar(200) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `age` int(11) NOT NULL DEFAULT 0,
  `blood_group` varchar(50) DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `anniversary_date` date DEFAULT NULL,
  `nationality` varchar(200) DEFAULT NULL,
  `religion` varchar(200) DEFAULT NULL,
  `caste` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `alt_mobile_no` varchar(15) DEFAULT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `emer_contact_name` varchar(200) DEFAULT NULL,
  `emer_contact_relation` varchar(200) DEFAULT NULL,
  `emer_contact_no` varchar(15) DEFAULT NULL,
  `aadhar_no` int(50) DEFAULT NULL,
  `pan_no` varchar(50) DEFAULT NULL,
  `driving_license` varchar(100) DEFAULT NULL,
  `passport_no` varchar(100) DEFAULT NULL,
  `identification_masks` varchar(200) DEFAULT NULL,
  `department_id` int(11) NOT NULL DEFAULT 0,
  `designation_id` int(11) NOT NULL DEFAULT 0,
  `grade_id` int(11) NOT NULL DEFAULT 0,
  `date_of_joining` date DEFAULT NULL,
  `job_location` text DEFAULT NULL,
  `shift_id` varchar(10) DEFAULT NULL,
  `employee_type` varchar(200) DEFAULT NULL,
  `employer_name` varchar(200) DEFAULT NULL,
  `employer_designation_id` int(11) DEFAULT 0,
  `service_from` date DEFAULT NULL,
  `service_to` date DEFAULT NULL,
  `reason` varchar(200) DEFAULT NULL,
  `job_responsibility` text DEFAULT NULL,
  `reporting_manager` varchar(200) DEFAULT NULL,
  `basic_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_pf` int(11) NOT NULL DEFAULT 0,
  `is_esic` int(11) NOT NULL DEFAULT 0,
  `gross_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bank_id` int(11) DEFAULT 0,
  `acc_holder_name` varchar(200) DEFAULT NULL,
  `account_no` varchar(200) DEFAULT NULL,
  `ifsc_code` varchar(100) DEFAULT NULL,
  `pf_uan` varchar(200) DEFAULT NULL,
  `uan_no` varchar(200) NOT NULL,
  `esic_no` varchar(100) DEFAULT NULL,
  `pf_joining_date` date DEFAULT NULL,
  `esic_joining_date` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `document_checked_ids` text DEFAULT NULL,
  `last_salary` double(10,2) NOT NULL DEFAULT 0.00,
  `profile_image` text DEFAULT NULL,
  `opening_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `opening_date` date DEFAULT NULL,
  `opening_status` int(11) NOT NULL DEFAULT 0,
  `form21_last_date` date DEFAULT NULL,
  `is_form21_last_date` int(11) NOT NULL DEFAULT 0,
  `resign_status` int(11) NOT NULL DEFAULT 0 COMMENT '0=pending,1=approve,2=reject',
  `last_working_date` date DEFAULT NULL,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_master`
--

INSERT INTO `employee_master` (`emp_id`, `emp_code`, `biomatric_id`, `first_name`, `last_name`, `father_name`, `gender`, `dob`, `age`, `blood_group`, `marital_status`, `anniversary_date`, `nationality`, `religion`, `caste`, `mobile_no`, `alt_mobile_no`, `email_id`, `present_address`, `permanent_address`, `emer_contact_name`, `emer_contact_relation`, `emer_contact_no`, `aadhar_no`, `pan_no`, `driving_license`, `passport_no`, `identification_masks`, `department_id`, `designation_id`, `grade_id`, `date_of_joining`, `job_location`, `shift_id`, `employee_type`, `employer_name`, `employer_designation_id`, `service_from`, `service_to`, `reason`, `job_responsibility`, `reporting_manager`, `basic_salary`, `is_pf`, `is_esic`, `gross_salary`, `net_salary`, `bank_id`, `acc_holder_name`, `account_no`, `ifsc_code`, `pf_uan`, `uan_no`, `esic_no`, `pf_joining_date`, `esic_joining_date`, `status`, `document_checked_ids`, `last_salary`, `profile_image`, `opening_balance`, `opening_date`, `opening_status`, `form21_last_date`, `is_form21_last_date`, `resign_status`, `last_working_date`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, '00001', '1', 'Roma', 'Chakradhari', 'mr chakradhari', 'Female', '2003-09-01', 22, 'O+', 'Unmarried', '2023-01-01', 'indian', 'Hindu', 'OBC', '7999559862', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559862', 2147483647, 'pan12345', 'driving1234', '12345678909', 'no', 5, 8, 1, '2023-01-01', 'raigarh', '09:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20000.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541230', 'CRGB000161', '123654789', '14785236985', '147852369', '2023-01-01', '2023-01-01', 0, NULL, 19000.00, NULL, 0.00, '2025-12-01', 0, '2023-01-01', 1, 0, '2026-01-26', 1, '192.168.1.37', NULL, NULL, 1, 10),
(2, '00012', '2', 'Bablu', 'pandit', 'mr pandit', 'Male', '2003-09-02', 23, 'O+', 'Unmarried', '0000-00-00', 'indian', 'Hindu', 'OBC', '7999559863', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559863', 2147483647, 'pan12346', 'driving1235', '12345678910', 'no', 5, 8, 1, '2023-01-02', 'raigarh', '12:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 200000.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541231', 'CRGB000162', '123654789', '14785236986', '147852369', '2023-01-01', '2023-01-01', 0, '', 19001.00, NULL, 0.00, '2025-12-02', 0, '0000-00-00', 0, 1, '2026-02-16', 1, '59.153.97.145', NULL, '2026-01-19', 1, 13),
(3, '00003', '3', 'Naveen', 'pandit', 'mr pandit', 'Male', '2003-09-03', 24, 'O+', 'Unmarried', '2023-01-17', 'indian', 'Hindu', 'OBC', '7999559864', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559864', 2147483647, 'pan12347', 'driving1236', '12345678911', 'no', 5, 8, 1, '2023-01-03', 'raigarh', '11:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20002.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541232', 'CRGB000163', '123654789', '14785236987', '147852369', '2023-01-01', '2023-01-01', 0, NULL, 19002.00, NULL, 0.00, '2025-12-03', 0, '2023-01-17', 1, 0, '0000-00-00', 1, '192.168.1.37', NULL, NULL, 1, 10),
(4, '00004', '4', 'tushali', 'mahanti', 'mr mahanti', 'Female', '1960-09-04', 25, 'O+', 'Unmarried', '2023-01-08', 'indian', 'Hindu', 'OBC', '7999559865', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559865', 2147483647, 'pan12348', 'driving1237', '12345678912', 'no', 5, 8, 1, '2023-01-04', 'raigarh', '12:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20003.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541233', 'CRGB000164', '123654789', '14785236988', '147852369', '2023-01-01', '2023-01-01', 0, NULL, 19003.00, NULL, 0.00, '2025-12-04', 0, '2023-01-08', 1, 0, '0000-00-00', 1, '192.168.1.37', NULL, NULL, 1, 10),
(5, '00008', '5', 'Mallika', 'Dutta', 'mr Dutta', 'Female', '2003-09-05', 26, 'O+', 'Unmarried', '0000-00-00', 'indian', 'Hindu', 'OBC', '7999559866', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559866', 2147483647, 'pan12349', 'driving1238', '12345678913', 'no', 5, 8, 1, '2023-01-05', 'raigarh', '09:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20004.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541234', 'CRGB000165', '123654789', '14785236989', '147852369', '2023-01-01', '2023-01-01', 0, '', 19004.00, NULL, 0.00, '2025-12-05', 0, '0000-00-00', 0, 0, '0000-00-00', 1, '59.153.97.145', NULL, '2026-01-17', 1, 10),
(6, '00010', '6', 'Kajal', 'Vishwakarma', 'mr Vishwakrma', 'Female', '2003-09-06', 27, 'O+', 'Unmarried', '0000-00-00', 'indian', 'Hindu', 'OBC', '7999559867', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559867', 2147483647, 'pan12350', 'driving1239', '12345678914', 'no', 5, 8, 1, '2023-01-06', 'raigarh', '09:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20005.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541235', 'CRGB000166', '123654789', '14785236990', '147852369', '2023-01-01', '2023-01-01', 0, '', 19005.00, NULL, 0.00, '2025-12-06', 0, '0000-00-00', 0, 0, '0000-00-00', 1, '59.153.97.145', NULL, '2026-01-17', 1, 10),
(7, '00007', '7', 'Radhika', 'Sahu', 'Mr sahu', 'Female', '2003-09-07', 28, 'O+', 'Unmarried', '2022-12-12', 'indian', 'Hindu', 'OBC', '7999559868', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559868', 2147483647, 'pan12351', 'driving1240', '12345678915', 'no', 5, 8, 1, '2023-01-07', 'raigarh', '15:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20006.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541236', 'CRGB000167', '123654789', '14785236991', '147852369', '2023-01-01', '2023-01-01', 0, NULL, 19006.00, NULL, 0.00, '2025-12-07', 0, '0000-00-00', 0, 1, '2026-01-18', 1, '192.168.1.37', NULL, NULL, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `emp_bank_details`
--

CREATE TABLE `emp_bank_details` (
  `emp_bank_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `bank_id` int(11) DEFAULT 0,
  `acc_holder_name` varchar(100) DEFAULT NULL,
  `account_no` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(30) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_bank_details`
--

INSERT INTO `emp_bank_details` (`emp_bank_id`, `emp_id`, `bank_id`, `acc_holder_name`, `account_no`, `ifsc_code`, `is_active`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(4, 0, 3, 'roma', '1478523698563', 'IFSC12345', 0, 1, '192.168.1.37', '2026-01-15', NULL, 1, 10),
(5, 0, 1, 'mallika', '1478523698563', 'sdfsdf', 1, 1, '192.168.1.37', '2026-01-15', NULL, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `emp_deduction`
--

CREATE TABLE `emp_deduction` (
  `deduction_id` int(11) NOT NULL,
  `emp_id` varchar(200) NOT NULL DEFAULT '0',
  `month` int(11) NOT NULL DEFAULT 0,
  `year` int(11) NOT NULL DEFAULT 0,
  `lpg_ded` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shoes_ded` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other` decimal(10,2) NOT NULL DEFAULT 0.00,
  `createdby` int(11) DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_deduction`
--

INSERT INTO `emp_deduction` (`deduction_id`, `emp_id`, `month`, `year`, `lpg_ded`, `shoes_ded`, `other`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(5, '1', 12, 2025, 1500.00, 700.00, 100.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
(6, '2', 12, 2025, 1500.00, 700.00, 100.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
(7, '3', 12, 2025, 1500.00, 700.00, 100.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
(8, '4', 12, 2025, 1500.00, 700.00, 100.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
(9, '5', 12, 2025, 1500.00, 700.00, 100.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
(10, '6', 12, 2025, 1500.00, 700.00, 100.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
(11, '7', 12, 2025, 1500.00, 700.00, 100.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `emp_document`
--

CREATE TABLE `emp_document` (
  `emp_doc_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `doc_id` int(11) DEFAULT 0,
  `doc_expiry_date` date DEFAULT NULL,
  `doc_remark` varchar(200) DEFAULT NULL,
  `doc_file` varchar(200) DEFAULT NULL,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_document`
--

INSERT INTO `emp_document` (`emp_doc_id`, `emp_id`, `doc_id`, `doc_expiry_date`, `doc_remark`, `doc_file`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(8, 1, 2, NULL, 'Remark', 'DOC1766208936362.jpg', 1, '192.168.1.32', '2025-12-20', NULL, 1, 10),
(9, 1, 3, NULL, 'Remark	Remark', 'DOC1766208951071.5.jpg', 1, '192.168.1.32', '2025-12-20', NULL, 1, 10),
(15, 10, 6, '2026-01-16', 'Remark', 'DOC1768557403079.7.jpg', 1, '192.168.1.37', '2026-01-16', NULL, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `emp_education`
--

CREATE TABLE `emp_education` (
  `education_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `examination` varchar(200) DEFAULT NULL,
  `university` varchar(100) DEFAULT NULL,
  `college` varchar(100) DEFAULT NULL,
  `percentage` decimal(10,2) DEFAULT NULL,
  `pass_year` varchar(70) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_education`
--

INSERT INTO `emp_education` (`education_id`, `emp_id`, `examination`, `university`, `college`, `percentage`, `pass_year`, `subject`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(8, 1, '12th', 'cgbsc', 'hs school sorid khurd', 83.00, '2020-21', 'abcd', 1, '192.168.1.32', '2025-12-20', NULL, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `emp_family_details`
--

CREATE TABLE `emp_family_details` (
  `family_detail_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `member_name` varchar(200) DEFAULT NULL,
  `relation` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `gender` varchar(70) DEFAULT NULL,
  `is_nominee` int(11) NOT NULL DEFAULT 0,
  `aadhar_card` text DEFAULT NULL,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emp_language`
--

CREATE TABLE `emp_language` (
  `emp_language_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `language_name` varchar(200) DEFAULT NULL,
  `is_speak` int(11) DEFAULT 0,
  `is_read` int(11) DEFAULT 0,
  `is_write` int(11) NOT NULL DEFAULT 0,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emp_monthly_leave`
--

CREATE TABLE `emp_monthly_leave` (
  `month_leave_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `department_id` int(11) NOT NULL DEFAULT 0,
  `basic_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_leave` decimal(10,2) NOT NULL DEFAULT 0.00,
  `remining_leave` decimal(10,2) NOT NULL DEFAULT 0.00,
  `month` varchar(20) DEFAULT NULL,
  `year` varchar(30) DEFAULT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(200) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `unit_id` int(11) NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emp_overtime`
--

CREATE TABLE `emp_overtime` (
  `overtime_id` int(11) NOT NULL,
  `emp_id` varchar(200) NOT NULL DEFAULT '0',
  `month` int(11) NOT NULL DEFAULT 0,
  `year` int(11) NOT NULL DEFAULT 0,
  `no_of_overtime` decimal(10,2) NOT NULL DEFAULT 0.00,
  `createdby` int(11) DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_overtime`
--

INSERT INTO `emp_overtime` (`overtime_id`, `emp_id`, `month`, `year`, `no_of_overtime`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, '1', 12, 2025, 2.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
(3, '3', 12, 2025, 6.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
(4, '4', 12, 2025, 8.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
(5, '5', 12, 2025, 25.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
(6, '6', 12, 2025, 12.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
(7, '7', 12, 2025, 14.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `emp_salary`
--

CREATE TABLE `emp_salary` (
  `salary_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `month` varchar(20) NOT NULL,
  `year` year(4) NOT NULL,
  `days` int(11) NOT NULL DEFAULT 0,
  `total_wh` varchar(10) DEFAULT NULL,
  `total_wh_minutes` int(11) DEFAULT NULL,
  `rate_per_day` double DEFAULT NULL,
  `gross_salary` double DEFAULT NULL,
  `cash` double DEFAULT NULL,
  `bank` double DEFAULT NULL,
  `basic_salary` double NOT NULL,
  `paydate` date NOT NULL,
  `ipaddress` varchar(100) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `unit_id` int(11) DEFAULT 0,
  `sessionid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade_master`
--

CREATE TABLE `grade_master` (
  `grade_id` int(11) NOT NULL,
  `grade_name` varchar(200) NOT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(200) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `unit_id` int(11) NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade_master`
--

INSERT INTO `grade_master` (`grade_id`, `grade_name`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 'Grade  A', 2, '192.168.1.10', '2025-12-31', '0000-00-00', 1, 10),
(2, 'Grade  B', 2, '192.168.1.10', '2025-12-31', '0000-00-00', 4, 10),
(3, 'Grade  C', 2, '192.168.1.10', '2025-12-31', '0000-00-00', 4, 10),
(4, 'Grade  D', 2, '192.168.1.10', '2025-12-31', '2025-12-31', 4, 10),
(5, 'Grade B', 1, '192.168.1.37', '2026-01-16', '0000-00-00', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `holiday_entry`
--

CREATE TABLE `holiday_entry` (
  `holiday_id` int(11) NOT NULL,
  `holiday_tittle` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `is_deleted` int(11) NOT NULL,
  `date` date NOT NULL,
  `createdate` date NOT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(250) NOT NULL,
  `lastupdated` date NOT NULL,
  `userid` int(11) NOT NULL,
  `unit_id` text DEFAULT NULL,
  `sessionid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `holiday_entry`
--

INSERT INTO `holiday_entry` (`holiday_id`, `holiday_tittle`, `is_deleted`, `date`, `createdate`, `createdby`, `ipaddress`, `lastupdated`, `userid`, `unit_id`, `sessionid`) VALUES
(1, 'Republic Day', 0, '2026-01-26', '2026-01-19', 1, '59.153.97.145', '0000-00-00', 0, '1,4', 13);

-- --------------------------------------------------------

--
-- Table structure for table `m_session`
--

CREATE TABLE `m_session` (
  `sessionid` int(11) NOT NULL,
  `fromdate` date DEFAULT NULL,
  `todate` date DEFAULT NULL,
  `session_name` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `ipaddress` varchar(25) DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `createdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `m_session`
--

INSERT INTO `m_session` (`sessionid`, `fromdate`, `todate`, `session_name`, `status`, `branch_id`, `userid`, `ipaddress`, `lastupdated`, `createdate`) VALUES
(10, '2025-04-01', '2026-03-31', '2025-2026', 0, 0, 20, '192.168.1.33', '2026-01-01', '2024-03-11'),
(13, '2026-04-01', '2027-03-31', '2026-2027', 1, 0, 0, '192.168.1.33', '2026-01-01', '2026-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `salary_slab`
--

CREATE TABLE `salary_slab` (
  `slab_id` int(11) NOT NULL,
  `from_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `to_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `heading` varchar(200) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `unit_id` int(11) NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary_slab`
--

INSERT INTO `salary_slab` (`slab_id`, `from_salary`, `to_salary`, `heading`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 0.00, 21500.00, 'BELOW 21000 SALARY BASIC+DA 100%', '0000-00-00', '0000-00-00', 0, 0),
(2, 21500.00, 30500.00, '21500 TO 30000 70% BASIC + DA', '0000-00-00', '0000-00-00', 0, 0),
(3, 30500.00, 42500.00, '30500 TO ABOVE 50% BASIC+DA', '0000-00-00', '0000-00-00', 0, 0),
(4, 42500.00, 0.00, 'ABOVE 42500 SALARY', '0000-00-00', '0000-00-00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `salary_slab_master`
--

CREATE TABLE `salary_slab_master` (
  `salary_slab_id` int(11) NOT NULL,
  `slab_id` int(11) NOT NULL DEFAULT 0,
  `basic_percent` decimal(10,2) NOT NULL DEFAULT 0.00,
  `hra_percent` decimal(10,2) NOT NULL DEFAULT 0.00,
  `medical_allow` decimal(10,2) NOT NULL DEFAULT 0.00,
  `conve_allow` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pf_per` decimal(10,2) NOT NULL DEFAULT 0.00,
  `esic_per` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pf_emp_per` decimal(10,2) NOT NULL DEFAULT 0.00,
  `esic_emp_per` decimal(10,2) NOT NULL DEFAULT 0.00,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(200) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `unit_id` int(11) NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary_slab_master`
--

INSERT INTO `salary_slab_master` (`salary_slab_id`, `slab_id`, `basic_percent`, `hra_percent`, `medical_allow`, `conve_allow`, `pf_per`, `esic_per`, `pf_emp_per`, `esic_emp_per`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(2, 1, 100.00, 0.00, 0.00, 0.00, 12.00, 0.75, 12.00, 3.25, 1, '192.168.1.27', '2025-12-27', '2025-12-27', 0, 0),
(3, 2, 70.00, 20.00, 1600.00, 1250.00, 12.00, 0.75, 12.00, 3.25, 1, '192.168.1.35', '2025-12-27', '2026-01-10', 0, 0),
(4, 3, 50.00, 50.00, 1600.00, 1250.00, 12.00, 0.75, 12.00, 3.25, 1, '192.168.1.27', '2025-12-27', '0000-00-00', 0, 0),
(5, 4, 50.00, 50.00, 1600.00, 1250.00, 12.00, 0.75, 12.00, 3.25, 1, '192.168.1.27', '2025-12-27', '0000-00-00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `salary_structure`
--

CREATE TABLE `salary_structure` (
  `salary_struc_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `department_id` int(11) NOT NULL DEFAULT 0,
  `month` int(11) DEFAULT 0,
  `year` year(4) DEFAULT 2000,
  `basic_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `increment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `revised_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `basic_pf_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pf_esic_basic` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pf_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `esic_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pf_paid_basic` decimal(10,2) NOT NULL DEFAULT 0.00,
  `esic_paid_basic` decimal(10,2) NOT NULL DEFAULT 0.00,
  `present_day` varchar(10) DEFAULT NULL,
  `advance_leave` varchar(10) DEFAULT NULL,
  `paid_holiday` varchar(10) DEFAULT NULL,
  `weekly_off` varchar(10) DEFAULT NULL,
  `leave_days` varchar(10) DEFAULT NULL,
  `c_off_leave` varchar(10) DEFAULT NULL,
  `total_c_off` varchar(10) DEFAULT NULL,
  `overtime_days` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_working_days` varchar(20) DEFAULT NULL,
  `basic_da` decimal(10,2) NOT NULL DEFAULT 0.00,
  `hra` decimal(10,2) NOT NULL DEFAULT 0.00,
  `medical` decimal(10,2) NOT NULL DEFAULT 0.00,
  `conveyance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `special_allow` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pf_emp` decimal(10,2) NOT NULL DEFAULT 0.00,
  `esic_emp` decimal(10,2) DEFAULT 0.00,
  `pf_employer` decimal(10,2) DEFAULT 0.00,
  `esic_employer` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_loan_ded` int(11) NOT NULL DEFAULT 0,
  `unit_id` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(25) NOT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` datetime NOT NULL,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `salary_structure`
--

INSERT INTO `salary_structure` (`salary_struc_id`, `emp_id`, `department_id`, `month`, `year`, `basic_salary`, `increment`, `revised_salary`, `basic_pf_rate`, `pf_esic_basic`, `pf_rate`, `esic_rate`, `pf_paid_basic`, `esic_paid_basic`, `present_day`, `advance_leave`, `paid_holiday`, `weekly_off`, `leave_days`, `c_off_leave`, `total_c_off`, `overtime_days`, `total_working_days`, `basic_da`, `hra`, `medical`, `conveyance`, `special_allow`, `total_salary`, `pf_emp`, `esic_emp`, `pf_employer`, `esic_employer`, `is_loan_ded`, `unit_id`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `sessionid`) VALUES
(10, 2, 5, 1, '2026', 200000.00, 0.00, 200000.00, 0.00, 0.00, 15000.00, 100000.00, 15000.00, 21000.00, '17', '0', '0', '2', '1', '0.00', '0.00', 0.00, '20', 64516.00, 32258.00, 1032.00, 806.00, 30420.00, 129032.00, 1800.00, 158.00, 1800.00, 683.00, 1, 1, 1, '59.153.97.145', '2026-01-19', '0000-00-00 00:00:00', 13),
(11, 3, 5, 1, '2026', 20002.00, 0.00, 20002.00, 0.00, 0.00, 15000.00, 20002.00, 4517.00, 4517.00, '0', '0', '0', '0', '0', '7', '7', 0.00, '7', 4517.00, 0.00, 0.00, 0.00, 0.00, 4517.00, 542.00, 34.00, 542.00, 147.00, 0, 1, 1, '59.153.97.145', '2026-01-19', '0000-00-00 00:00:00', 13),
(12, 4, 5, 1, '2026', 20003.00, 0.00, 20003.00, 0.00, 0.00, 15000.00, 20003.00, 2581.00, 2581.00, '4', '0', '0', '0', '0', '0.00', '0.00', 0.00, '4', 2581.00, 0.00, 0.00, 0.00, 0.00, 2581.00, 310.00, 19.00, 310.00, 84.00, 0, 1, 1, '59.153.97.145', '2026-01-19', '0000-00-00 00:00:00', 13),
(13, 5, 5, 1, '2026', 20004.00, 0.00, 20004.00, 0.00, 0.00, 15000.00, 20004.00, 968.00, 968.00, '1.5', '0', '0', '0', '0', '0.00', '0.00', 0.00, '1.5', 968.00, 0.00, 0.00, 0.00, 0.00, 968.00, 116.00, 7.00, 116.00, 31.00, 1, 1, 1, '59.153.97.145', '2026-01-19', '0000-00-00 00:00:00', 13),
(14, 6, 5, 1, '2026', 20005.00, 0.00, 20005.00, 0.00, 0.00, 15000.00, 20005.00, 6453.00, 6453.00, '0', '0', '0', '0', '0', '10', '10', 0.00, '10', 6453.00, 0.00, 0.00, 0.00, 0.00, 6453.00, 774.00, 48.00, 774.00, 210.00, 0, 1, 1, '59.153.97.145', '2026-01-19', '0000-00-00 00:00:00', 13),
(15, 7, 5, 1, '2026', 20006.00, 0.00, 20006.00, 0.00, 0.00, 15000.00, 20006.00, 7099.00, 7099.00, '0', '0', '0', '0', '0', '11', '11', 0.00, '11', 7099.00, 0.00, 0.00, 0.00, 0.00, 7099.00, 852.00, 53.00, 852.00, 231.00, 0, 1, 1, '59.153.97.145', '2026-01-19', '0000-00-00 00:00:00', 13);

-- --------------------------------------------------------

--
-- Table structure for table `shift_master`
--

CREATE TABLE `shift_master` (
  `shift_id` int(11) NOT NULL,
  `shift_name` varchar(100) NOT NULL,
  `machineid` varchar(100) NOT NULL,
  `in_time` time NOT NULL,
  `out_time` time NOT NULL,
  `working_hour` time NOT NULL,
  `weekly_holyday` varchar(50) NOT NULL,
  `grace_time_in` int(11) NOT NULL,
  `grace_time_out` int(11) NOT NULL,
  `is_cross_day` int(11) NOT NULL DEFAULT 0,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(25) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdated` datetime NOT NULL,
  `unit_id` int(11) DEFAULT 0,
  `sessionid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `shift_master`
--

INSERT INTO `shift_master` (`shift_id`, `shift_name`, `machineid`, `in_time`, `out_time`, `working_hour`, `weekly_holyday`, `grace_time_in`, `grace_time_out`, `is_cross_day`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(2, 'Day shift', '', '09:00:00', '18:00:00', '09:00:00', '', 10, 10, 0, 2, '192.168.1.10', '2025-12-31 16:41:46', '2025-12-31 16:41:57', 4, 10),
(5, 'General Shift', '', '20:42:00', '22:43:00', '02:01:00', '', 10, 1, 0, 2, '192.168.1.10', '2025-12-31 16:43:06', '0000-00-00 00:00:00', 4, 10),
(6, 'General Shift', '', '09:00:00', '18:00:00', '09:00:00', '', 15, 15, 0, 1, '192.168.1.33', '2025-12-31 17:06:42', '0000-00-00 00:00:00', 1, 10),
(7, 'Day Shift', '', '08:00:00', '20:00:00', '12:00:00', '', 15, 15, 0, 1, '59.153.97.145', '2025-12-31 17:13:05', '2026-01-13 15:37:04', 1, 10),
(8, 'Night Shift', '', '20:00:00', '08:00:00', '12:00:00', '', 15, 15, 1, 1, '59.153.97.145', '2025-12-31 17:13:47', '2026-01-13 15:37:25', 1, 10),
(9, 'shift A', '', '06:00:00', '14:00:00', '08:00:00', '', 15, 15, 0, 1, '59.153.97.145', '2025-12-31 17:15:30', '2026-01-13 15:35:09', 1, 10),
(10, 'shift B', '', '14:00:00', '22:00:00', '08:00:00', '', 15, 15, 0, 1, '59.153.97.145', '2025-12-31 17:16:55', '2026-01-13 15:35:30', 1, 10),
(11, 'Shift C', '', '22:00:00', '06:00:00', '08:00:00', '', 15, 15, 1, 1, '59.153.97.145', '2026-01-13 15:36:21', '0000-00-00 00:00:00', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `software_expired`
--

CREATE TABLE `software_expired` (
  `soft_exp_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `expired_date` date NOT NULL,
  `userid` int(11) NOT NULL,
  `unit_id` int(11) DEFAULT 0,
  `sessionid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `software_expired`
--

INSERT INTO `software_expired` (`soft_exp_id`, `start_date`, `expired_date`, `userid`, `unit_id`, `sessionid`) VALUES
(5, '2025-11-17', '2026-12-17', 17, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `subdivision_master`
--

CREATE TABLE `subdivision_master` (
  `subdivision_id` int(11) NOT NULL,
  `sub_division_name` varchar(200) DEFAULT NULL,
  `division_id` int(11) NOT NULL DEFAULT 0,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `ipaddress` varchar(200) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `lastupdated` date DEFAULT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `sessionid` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subdivision_master`
--

INSERT INTO `subdivision_master` (`subdivision_id`, `sub_division_name`, `division_id`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 'Sub Division 1', 1, 1, '192.168.1.37', '2026-01-15', '2026-01-15', 1, 10),
(2, 'Sub Division 2', 2, 1, '59.153.97.145', '2026-01-15', '2026-01-19', 1, 13);

-- --------------------------------------------------------

--
-- Table structure for table `unit_master`
--

CREATE TABLE `unit_master` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `email_id` varchar(50) NOT NULL,
  `gstin_no` varchar(25) NOT NULL,
  `pan_no` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `unithead` varchar(200) NOT NULL,
  `term_cond` text NOT NULL,
  `in_time` time DEFAULT NULL,
  `in_margin` varchar(100) DEFAULT NULL,
  `out_time` time DEFAULT NULL,
  `out_margin` varchar(100) DEFAULT NULL,
  `working_hours` varchar(50) DEFAULT NULL,
  `logo_image` text DEFAULT NULL,
  `is_deleted` int(11) NOT NULL,
  `setting_id` int(11) NOT NULL,
  `sessionid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(25) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `unit_master`
--

INSERT INTO `unit_master` (`unit_id`, `unit_name`, `mobile`, `email_id`, `gstin_no`, `pan_no`, `address`, `city`, `email`, `unithead`, `term_cond`, `in_time`, `in_margin`, `out_time`, `out_margin`, `working_hours`, `logo_image`, `is_deleted`, `setting_id`, `sessionid`, `createdby`, `ipaddress`, `createdate`, `lastupdated`) VALUES
(1, 'NRVS Steels Ltd.', '8959819111', 'trinitytesting@fmail.com', '', '', 'Gharghoda Raigarh', 'Raigarh', '', 'Raigarh', '', '10:30:00', '30', '18:30:00', '30', '8', 'DOC1768893249926.7.png', 0, 0, 13, 1, '59.153.97.145', '2025-12-04 13:34:08', '2026-01-20 12:44:09'),
(4, 'NR Inspat Pvt. Ltd.', '9898784545', 'radhika@gmail.com', '656546545665', '4654683445', 'Raipur', 'Raipur', '', 'Radhika', '', '11:00:00', '30', '19:00:00', '30', '8', 'DOC1768893238887.5.png', 0, 0, 13, 1, '59.153.97.145', '2025-12-18 13:22:26', '2026-01-20 12:43:58');

-- --------------------------------------------------------

--
-- Table structure for table `upload_attachments`
--

CREATE TABLE `upload_attachments` (
  `attachment_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL DEFAULT 0,
  `upload_attachment` text DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(200) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `unit_id` int(11) NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upload_attachments`
--

INSERT INTO `upload_attachments` (`attachment_id`, `doc_id`, `upload_attachment`, `remark`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 6, 'DOC1768821913296.5.jpg', 'Remark', 1, '59.153.97.145', '2026-01-19', '0000-00-00', 1, 13);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `usertype` varchar(10) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `status` varchar(11) NOT NULL,
  `email` text NOT NULL,
  `address` text NOT NULL,
  `unit_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `lastupdated` date NOT NULL,
  `ipaddress` varchar(30) NOT NULL,
  `createdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `username`, `password`, `usertype`, `fullname`, `mobile`, `status`, `email`, `address`, `unit_id`, `session_id`, `createdby`, `lastupdated`, `ipaddress`, `createdate`) VALUES
(1, 'admin', '@2025', 'admin', 'Admin', '8959819111', '1', '', '', 1, 1, 0, '0000-00-00', '', '2025-12-04 08:54:28'),
(2, 'tushali', '123', 'user', 'Tushali', '1212121212', '1', 'Testing@123', '', 4, 10, 1, '2026-01-12', '192.168.1.37', '2025-12-04 16:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `weekly_off_setting`
--

CREATE TABLE `weekly_off_setting` (
  `weekly_off_setting_id` int(11) NOT NULL,
  `setting_type` varchar(50) NOT NULL,
  `d1` varchar(50) NOT NULL,
  `d2` varchar(50) NOT NULL,
  `d3` varchar(50) NOT NULL,
  `d4` varchar(50) NOT NULL,
  `d5` int(11) NOT NULL,
  `w1` varchar(50) NOT NULL,
  `w2` varchar(50) NOT NULL,
  `w3` varchar(50) NOT NULL,
  `w4` varchar(50) NOT NULL,
  `w5` int(11) NOT NULL,
  `week_off_Setting_r` varchar(50) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `ipaddress` varchar(50) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weekly_off_setting`
--

INSERT INTO `weekly_off_setting` (`weekly_off_setting_id`, `setting_type`, `d1`, `d2`, `d3`, `d4`, `d5`, `w1`, `w2`, `w3`, `w4`, `w5`, `week_off_Setting_r`, `unit_id`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `sessionid`) VALUES
(1, 'week_off', '6', '13', '19', '23', 0, '1', '2', '3', '4', 0, '', 1, 0, '', '2025-12-20', '0000-00-00', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_entry`
--
ALTER TABLE `attendance_entry`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `attendance_heading`
--
ALTER TABLE `attendance_heading`
  ADD PRIMARY KEY (`attheadid`);

--
-- Indexes for table `bank_master`
--
ALTER TABLE `bank_master`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `company_setting`
--
ALTER TABLE `company_setting`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `c_off_setting`
--
ALTER TABLE `c_off_setting`
  ADD PRIMARY KEY (`c_off_setting_id`),
  ADD UNIQUE KEY `uniq_setting` (`setting_type`,`week_off_Setting_r`,`unit_id`);

--
-- Indexes for table `department_master`
--
ALTER TABLE `department_master`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `designation_master`
--
ALTER TABLE `designation_master`
  ADD PRIMARY KEY (`designation_id`);

--
-- Indexes for table `division_master`
--
ALTER TABLE `division_master`
  ADD PRIMARY KEY (`division_id`);

--
-- Indexes for table `document_master`
--
ALTER TABLE `document_master`
  ADD PRIMARY KEY (`doc_id`);

--
-- Indexes for table `emi_setting`
--
ALTER TABLE `emi_setting`
  ADD PRIMARY KEY (`emi_setting_id`);

--
-- Indexes for table `emi_setting_details`
--
ALTER TABLE `emi_setting_details`
  ADD PRIMARY KEY (`emi_setting_details_id`);

--
-- Indexes for table `employee_exit`
--
ALTER TABLE `employee_exit`
  ADD PRIMARY KEY (`exit_id`);

--
-- Indexes for table `employee_master`
--
ALTER TABLE `employee_master`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `emp_bank_details`
--
ALTER TABLE `emp_bank_details`
  ADD PRIMARY KEY (`emp_bank_id`);

--
-- Indexes for table `emp_deduction`
--
ALTER TABLE `emp_deduction`
  ADD PRIMARY KEY (`deduction_id`);

--
-- Indexes for table `emp_document`
--
ALTER TABLE `emp_document`
  ADD PRIMARY KEY (`emp_doc_id`);

--
-- Indexes for table `emp_education`
--
ALTER TABLE `emp_education`
  ADD PRIMARY KEY (`education_id`);

--
-- Indexes for table `emp_family_details`
--
ALTER TABLE `emp_family_details`
  ADD PRIMARY KEY (`family_detail_id`);

--
-- Indexes for table `emp_language`
--
ALTER TABLE `emp_language`
  ADD PRIMARY KEY (`emp_language_id`);

--
-- Indexes for table `emp_monthly_leave`
--
ALTER TABLE `emp_monthly_leave`
  ADD PRIMARY KEY (`month_leave_id`);

--
-- Indexes for table `emp_overtime`
--
ALTER TABLE `emp_overtime`
  ADD PRIMARY KEY (`overtime_id`);

--
-- Indexes for table `emp_salary`
--
ALTER TABLE `emp_salary`
  ADD PRIMARY KEY (`salary_id`);

--
-- Indexes for table `grade_master`
--
ALTER TABLE `grade_master`
  ADD PRIMARY KEY (`grade_id`);

--
-- Indexes for table `holiday_entry`
--
ALTER TABLE `holiday_entry`
  ADD PRIMARY KEY (`holiday_id`);

--
-- Indexes for table `m_session`
--
ALTER TABLE `m_session`
  ADD PRIMARY KEY (`sessionid`);

--
-- Indexes for table `salary_slab`
--
ALTER TABLE `salary_slab`
  ADD PRIMARY KEY (`slab_id`);

--
-- Indexes for table `salary_slab_master`
--
ALTER TABLE `salary_slab_master`
  ADD PRIMARY KEY (`salary_slab_id`);

--
-- Indexes for table `salary_structure`
--
ALTER TABLE `salary_structure`
  ADD PRIMARY KEY (`salary_struc_id`);

--
-- Indexes for table `shift_master`
--
ALTER TABLE `shift_master`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `software_expired`
--
ALTER TABLE `software_expired`
  ADD PRIMARY KEY (`soft_exp_id`);

--
-- Indexes for table `subdivision_master`
--
ALTER TABLE `subdivision_master`
  ADD PRIMARY KEY (`subdivision_id`);

--
-- Indexes for table `unit_master`
--
ALTER TABLE `unit_master`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `upload_attachments`
--
ALTER TABLE `upload_attachments`
  ADD PRIMARY KEY (`attachment_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `weekly_off_setting`
--
ALTER TABLE `weekly_off_setting`
  ADD PRIMARY KEY (`weekly_off_setting_id`),
  ADD UNIQUE KEY `uniq_setting` (`setting_type`,`week_off_Setting_r`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_entry`
--
ALTER TABLE `attendance_entry`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=871;

--
-- AUTO_INCREMENT for table `attendance_heading`
--
ALTER TABLE `attendance_heading`
  MODIFY `attheadid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bank_master`
--
ALTER TABLE `bank_master`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `company_setting`
--
ALTER TABLE `company_setting`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `c_off_setting`
--
ALTER TABLE `c_off_setting`
  MODIFY `c_off_setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `department_master`
--
ALTER TABLE `department_master`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `designation_master`
--
ALTER TABLE `designation_master`
  MODIFY `designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `division_master`
--
ALTER TABLE `division_master`
  MODIFY `division_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `document_master`
--
ALTER TABLE `document_master`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `emi_setting`
--
ALTER TABLE `emi_setting`
  MODIFY `emi_setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `emi_setting_details`
--
ALTER TABLE `emi_setting_details`
  MODIFY `emi_setting_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `employee_exit`
--
ALTER TABLE `employee_exit`
  MODIFY `exit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_master`
--
ALTER TABLE `employee_master`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `emp_bank_details`
--
ALTER TABLE `emp_bank_details`
  MODIFY `emp_bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `emp_deduction`
--
ALTER TABLE `emp_deduction`
  MODIFY `deduction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `emp_document`
--
ALTER TABLE `emp_document`
  MODIFY `emp_doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `emp_education`
--
ALTER TABLE `emp_education`
  MODIFY `education_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `emp_family_details`
--
ALTER TABLE `emp_family_details`
  MODIFY `family_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `emp_language`
--
ALTER TABLE `emp_language`
  MODIFY `emp_language_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emp_monthly_leave`
--
ALTER TABLE `emp_monthly_leave`
  MODIFY `month_leave_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emp_overtime`
--
ALTER TABLE `emp_overtime`
  MODIFY `overtime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `emp_salary`
--
ALTER TABLE `emp_salary`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grade_master`
--
ALTER TABLE `grade_master`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `holiday_entry`
--
ALTER TABLE `holiday_entry`
  MODIFY `holiday_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_session`
--
ALTER TABLE `m_session`
  MODIFY `sessionid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `salary_slab`
--
ALTER TABLE `salary_slab`
  MODIFY `slab_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `salary_slab_master`
--
ALTER TABLE `salary_slab_master`
  MODIFY `salary_slab_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salary_structure`
--
ALTER TABLE `salary_structure`
  MODIFY `salary_struc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `shift_master`
--
ALTER TABLE `shift_master`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `software_expired`
--
ALTER TABLE `software_expired`
  MODIFY `soft_exp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subdivision_master`
--
ALTER TABLE `subdivision_master`
  MODIFY `subdivision_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `unit_master`
--
ALTER TABLE `unit_master`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `upload_attachments`
--
ALTER TABLE `upload_attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `weekly_off_setting`
--
ALTER TABLE `weekly_off_setting`
  MODIFY `weekly_off_setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
