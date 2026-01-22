-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 10:52 AM
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
(30, 6, 5, 9, 0, 12, '2025', '2025-12-01', '2025-12-01 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:12:33', NULL, 1, 10),
(31, 6, 5, 9, 0, 12, '2025', '2025-12-02', '2025-12-02 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:12:37', NULL, 1, 10),
(32, 6, 5, 9, 0, 12, '2025', '2025-12-03', '2025-12-03 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:12:41', NULL, 1, 10),
(33, 6, 5, 9, 0, 12, '2025', '2025-12-04', '2025-12-04 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:12:44', NULL, 1, 10),
(34, 6, 5, 9, 0, 12, '2025', '2025-12-05', '2025-12-05 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:12:47', NULL, 1, 10),
(35, 6, 5, 9, 0, 12, '2025', '2025-12-06', '2025-12-06 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:12:51', NULL, 1, 10),
(36, 6, 5, 9, 0, 12, '2025', '2025-12-07', '2025-12-07 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:12:54', NULL, 1, 10),
(37, 6, 5, 9, 0, 12, '2025', '2025-12-08', '2025-12-08 00:00:00', '10:00:00', 'manual', '14:00:00', NULL, '04:00:00', 'IN', 'OUT', NULL, 3, 'Half Day', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:00', NULL, 1, 10),
(38, 6, 5, 9, 0, 12, '2025', '2025-12-09', '2025-12-09 00:00:00', '10:00:00', 'manual', '14:00:00', NULL, '04:00:00', 'IN', 'OUT', NULL, 3, 'Half Day', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:05', NULL, 1, 10),
(39, 6, 5, 9, 0, 12, '2025', '2025-12-10', '2025-12-10 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:08', NULL, 1, 10),
(40, 6, 5, 9, 0, 12, '2025', '2025-12-11', '2025-12-11 00:00:00', '14:00:00', 'manual', '18:00:00', NULL, '04:00:00', 'IN', 'OUT', NULL, 3, 'Half Day', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:13', NULL, 1, 10),
(41, 6, 5, 9, 0, 12, '2025', '2025-12-12', '2025-12-12 00:00:00', NULL, 'manual', NULL, NULL, NULL, 'IN', 'OUT', NULL, 0, 'Leave', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:18', NULL, 1, 10),
(42, 6, 5, 9, 0, 12, '2025', '2025-12-13', '2025-12-13 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:21', NULL, 1, 10),
(43, 6, 5, 9, 0, 12, '2025', '2025-12-14', '2025-12-14 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:24', NULL, 1, 10),
(44, 6, 5, 9, 0, 12, '2025', '2025-12-15', '2025-12-15 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:31', NULL, 1, 10),
(45, 6, 5, 9, 0, 12, '2025', '2025-12-16', '2025-12-16 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:34', NULL, 1, 10),
(46, 6, 5, 9, 0, 12, '2025', '2025-12-17', '2025-12-17 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:38', NULL, 1, 10),
(47, 6, 5, 9, 0, 12, '2025', '2025-12-18', '2025-12-18 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:43', NULL, 1, 10),
(48, 6, 5, 9, 0, 12, '2025', '2025-12-19', '2025-12-19 00:00:00', '14:00:00', 'manual', '18:00:00', NULL, '04:00:00', 'IN', 'OUT', NULL, 3, 'Half Day', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:47', NULL, 1, 10),
(49, 6, 5, 9, 0, 12, '2025', '2025-12-20', '2025-12-20 00:00:00', '10:00:00', 'manual', '14:00:00', NULL, '04:00:00', 'IN', 'OUT', NULL, 3, 'Half Day', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:52', NULL, 1, 10),
(50, 6, 5, 9, 0, 12, '2025', '2025-12-21', '2025-12-21 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:13:55', NULL, 1, 10),
(51, 6, 5, 9, 0, 12, '2025', '2025-12-22', '2025-12-22 00:00:00', NULL, 'manual', NULL, NULL, NULL, 'IN', 'OUT', NULL, 0, 'Leave', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:00', NULL, 1, 10),
(52, 6, 5, 9, 0, 12, '2025', '2025-12-23', '2025-12-23 00:00:00', NULL, 'manual', NULL, NULL, NULL, 'IN', 'OUT', NULL, 0, 'Leave', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:07', NULL, 1, 10),
(53, 6, 5, 9, 0, 12, '2025', '2025-12-24', '2025-12-24 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:10', NULL, 1, 10),
(54, 6, 5, 9, 0, 12, '2025', '2025-12-25', '2025-12-25 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:14', NULL, 1, 10),
(55, 6, 5, 9, 0, 12, '2025', '2025-12-26', '2025-12-26 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:18', NULL, 1, 10),
(56, 6, 5, 9, 0, 12, '2025', '2025-12-27', '2025-12-27 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:21', NULL, 1, 10),
(57, 6, 5, 9, 0, 12, '2025', '2025-12-28', '2025-12-28 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:24', NULL, 1, 10),
(58, 6, 5, 10, 0, 12, '2025', '2025-12-31', '2025-12-31 00:00:00', '11:00:00', 'manual', '19:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:31', NULL, 1, 10),
(59, 6, 5, 9, 0, 12, '2025', '2025-12-29', '2025-12-29 00:00:00', '10:00:00', 'manual', '18:00:00', NULL, '08:00:00', 'IN', 'OUT', NULL, 1, 'Present', 140000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:14:34', NULL, 1, 10),
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
(84, 5, 5, 6, 0, 12, '2025', '2025-12-01', '2025-12-01 00:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 135000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:20:28', NULL, 1, 10),
(85, 5, 5, 6, 0, 12, '2025', '2025-12-02', '2025-12-02 00:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 135000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:20:31', NULL, 1, 10),
(86, 5, 5, 6, 0, 12, '2025', '2025-12-03', '2025-12-03 00:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 135000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:20:34', NULL, 1, 10),
(87, 5, 5, 6, 0, 12, '2025', '2025-12-04', '2025-12-04 00:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 135000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:20:39', NULL, 1, 10),
(88, 5, 5, 6, 0, 12, '2025', '2025-12-05', '2025-12-05 00:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 135000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:20:41', NULL, 1, 10),
(89, 5, 5, 6, 0, 12, '2025', '2025-12-07', '2025-12-07 00:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 135000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:20:51', NULL, 1, 10),
(90, 5, 5, 6, 0, 12, '2025', '2025-12-08', '2025-12-08 00:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', 'IN', 'OUT', NULL, 1, 'Present', 135000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.30', '2026-01-10', '12:20:54', NULL, 1, 10),
(698, 1, 5, 7, 0, 12, '2025', '2025-12-06', '2025-12-06 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '17:23:33', NULL, 1, 10),
(699, 1, 5, 7, 0, 12, '2025', '2025-12-07', '2025-12-07 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '17:23:35', NULL, 1, 10),
(700, 1, 5, 7, 0, 12, '2025', '2025-12-17', '2025-12-17 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '17:23:39', NULL, 1, 10),
(701, 1, 5, 7, 0, 12, '2025', '2025-12-21', '2025-12-21 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '17:23:42', NULL, 1, 10),
(702, 1, 5, 7, 0, 12, '2025', '2025-12-22', '2025-12-22 00:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', 'IN', 'OUT', NULL, 1, 'Present', 15000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, '192.168.1.37', '2026-01-16', '17:23:44', NULL, 1, 10),
(703, 2, 5, 7, 0, 12, '2025', '2025-12-01', '2025-12-01 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(704, 2, 5, 7, 0, 12, '2025', '2025-12-02', '2025-12-02 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(705, 2, 5, 7, 0, 12, '2025', '2025-12-03', '2025-12-03 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(706, 2, 5, 7, 0, 12, '2025', '2025-12-04', '2025-12-04 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(707, 2, 5, 7, 0, 12, '2025', '2025-12-05', '2025-12-05 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(708, 2, 5, 7, 0, 12, '2025', '2025-12-06', '2025-12-06 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(709, 2, 5, 7, 0, 12, '2025', '2025-12-07', '2025-12-07 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(710, 2, 5, 7, 0, 12, '2025', '2025-12-08', '2025-12-08 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(711, 2, 5, 7, 0, 12, '2025', '2025-12-09', '2025-12-09 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(712, 2, 5, 7, 0, 12, '2025', '2025-12-10', '2025-12-10 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(713, 2, 5, 7, 0, 12, '2025', '2025-12-11', '2025-12-11 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(714, 2, 5, 7, 0, 12, '2025', '2025-12-12', '2025-12-12 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(715, 2, 5, 7, 0, 12, '2025', '2025-12-13', '2025-12-13 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(716, 2, 5, 7, 0, 12, '2025', '2025-12-14', '2025-12-14 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(717, 2, 5, 7, 0, 12, '2025', '2025-12-15', '2025-12-15 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(718, 2, 5, 7, 0, 12, '2025', '2025-12-16', '2025-12-16 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(719, 2, 5, 7, 0, 12, '2025', '2025-12-17', '2025-12-17 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(720, 2, 5, 7, 0, 12, '2025', '2025-12-18', '2025-12-18 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(721, 2, 5, 7, 0, 12, '2025', '2025-12-19', '2025-12-19 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(722, 2, 5, 7, 0, 12, '2025', '2025-12-20', '2025-12-20 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(723, 2, 5, 7, 0, 12, '2025', '2025-12-21', '2025-12-21 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(724, 2, 5, 7, 0, 12, '2025', '2025-12-22', '2025-12-22 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(725, 2, 5, 7, 0, 12, '2025', '2025-12-23', '2025-12-23 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(726, 2, 5, 7, 0, 12, '2025', '2025-12-24', '2025-12-24 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(727, 2, 5, 7, 0, 12, '2025', '2025-12-25', '2025-12-25 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(728, 2, 5, 7, 0, 12, '2025', '2025-12-26', '2025-12-26 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(729, 2, 5, 7, 0, 12, '2025', '2025-12-27', '2025-12-27 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(730, 2, 5, 7, 0, 12, '2025', '2025-12-28', '2025-12-28 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(731, 2, 5, 7, 0, 12, '2025', '2025-12-29', '2025-12-29 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(732, 2, 5, 7, 0, 12, '2025', '2025-12-30', '2025-12-30 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(733, 2, 5, 7, 0, 12, '2025', '2025-12-31', '2025-12-31 07:00:00', '07:00:00', 'manual', '19:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 30000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(892, 1, 5, 6, 0, 1, '2026', '2026-01-01', '2026-01-01 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(893, 1, 5, 6, 0, 1, '2026', '2026-01-02', '2026-01-02 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(895, 1, 5, 6, 0, 1, '2026', '2026-01-04', '2026-01-04 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(896, 1, 5, 6, 0, 1, '2026', '2026-01-05', '2026-01-05 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(897, 1, 5, 6, 0, 1, '2026', '2026-01-06', '2026-01-06 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(898, 1, 5, 6, 0, 1, '2026', '2026-01-07', '2026-01-07 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(899, 1, 5, 6, 0, 1, '2026', '2026-01-08', '2026-01-08 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(900, 1, 5, 6, 0, 1, '2026', '2026-01-09', '2026-01-09 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(901, 1, 5, 6, 0, 1, '2026', '2026-01-10', '2026-01-10 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(902, 1, 5, 6, 0, 1, '2026', '2026-01-11', '2026-01-11 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(903, 1, 5, 6, 0, 1, '2026', '2026-01-12', '2026-01-12 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(904, 1, 5, 6, 0, 1, '2026', '2026-01-13', '2026-01-13 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(905, 1, 5, 6, 0, 1, '2026', '2026-01-14', '2026-01-14 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(906, 1, 5, 6, 0, 1, '2026', '2026-01-15', '2026-01-15 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(907, 1, 5, 6, 0, 1, '2026', '2026-01-16', '2026-01-16 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(908, 1, 5, 6, 0, 1, '2026', '2026-01-17', '2026-01-17 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(909, 1, 5, 6, 0, 1, '2026', '2026-01-18', '2026-01-18 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(910, 1, 5, 6, 0, 1, '2026', '2026-01-19', '2026-01-19 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(911, 1, 5, 6, 0, 1, '2026', '2026-01-20', '2026-01-20 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20000.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(912, 2, 5, 7, 0, 1, '2026', '2026-01-01', '2026-01-01 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(913, 2, 5, 7, 0, 1, '2026', '2026-01-02', '2026-01-02 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(914, 2, 5, 7, 0, 1, '2026', '2026-01-03', '2026-01-03 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(915, 2, 5, 7, 0, 1, '2026', '2026-01-04', '2026-01-04 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(916, 2, 5, 7, 0, 1, '2026', '2026-01-05', '2026-01-05 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(917, 2, 5, 7, 0, 1, '2026', '2026-01-06', '2026-01-06 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(918, 2, 5, 7, 0, 1, '2026', '2026-01-07', '2026-01-07 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(919, 2, 5, 7, 0, 1, '2026', '2026-01-08', '2026-01-08 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(920, 2, 5, 7, 0, 1, '2026', '2026-01-09', '2026-01-09 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(921, 2, 5, 7, 0, 1, '2026', '2026-01-10', '2026-01-10 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(922, 2, 5, 7, 0, 1, '2026', '2026-01-11', '2026-01-11 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(923, 2, 5, 7, 0, 1, '2026', '2026-01-12', '2026-01-12 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(924, 2, 5, 7, 0, 1, '2026', '2026-01-13', '2026-01-13 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(925, 2, 5, 7, 0, 1, '2026', '2026-01-14', '2026-01-14 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(926, 2, 5, 7, 0, 1, '2026', '2026-01-15', '2026-01-15 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(927, 2, 5, 7, 0, 1, '2026', '2026-01-16', '2026-01-16 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(928, 2, 5, 7, 0, 1, '2026', '2026-01-17', '2026-01-17 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(929, 2, 5, 7, 0, 1, '2026', '2026-01-18', '2026-01-18 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(930, 2, 5, 7, 0, 1, '2026', '2026-01-19', '2026-01-19 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(931, 2, 5, 7, 0, 1, '2026', '2026-01-20', '2026-01-20 08:00:00', '08:00:00', 'manual', '14:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20001.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(932, 3, 5, 6, 0, 1, '2026', '2026-01-01', '2026-01-01 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(933, 3, 5, 6, 0, 1, '2026', '2026-01-02', '2026-01-02 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(934, 3, 5, 6, 0, 1, '2026', '2026-01-03', '2026-01-03 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(935, 3, 5, 6, 0, 1, '2026', '2026-01-04', '2026-01-04 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(936, 3, 5, 6, 0, 1, '2026', '2026-01-05', '2026-01-05 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(937, 3, 5, 6, 0, 1, '2026', '2026-01-06', '2026-01-06 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(938, 3, 5, 6, 0, 1, '2026', '2026-01-07', '2026-01-07 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(939, 3, 5, 6, 0, 1, '2026', '2026-01-08', '2026-01-08 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(940, 3, 5, 6, 0, 1, '2026', '2026-01-09', '2026-01-09 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(941, 3, 5, 6, 0, 1, '2026', '2026-01-10', '2026-01-10 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(942, 3, 5, 6, 0, 1, '2026', '2026-01-11', '2026-01-11 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(943, 3, 5, 6, 0, 1, '2026', '2026-01-12', '2026-01-12 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(944, 3, 5, 6, 0, 1, '2026', '2026-01-13', '2026-01-13 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(945, 3, 5, 6, 0, 1, '2026', '2026-01-14', '2026-01-14 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(946, 3, 5, 6, 0, 1, '2026', '2026-01-15', '2026-01-15 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(947, 3, 5, 6, 0, 1, '2026', '2026-01-16', '2026-01-16 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(948, 3, 5, 6, 0, 1, '2026', '2026-01-17', '2026-01-17 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(949, 3, 5, 6, 0, 1, '2026', '2026-01-18', '2026-01-18 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(950, 3, 5, 6, 0, 1, '2026', '2026-01-19', '2026-01-19 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(951, 3, 5, 6, 0, 1, '2026', '2026-01-20', '2026-01-20 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20002.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(952, 4, 5, 8, 0, 1, '2026', '2026-01-01', '2026-01-01 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(953, 4, 5, 8, 0, 1, '2026', '2026-01-02', '2026-01-02 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(954, 4, 5, 8, 0, 1, '2026', '2026-01-03', '2026-01-03 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(955, 4, 5, 8, 0, 1, '2026', '2026-01-04', '2026-01-04 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(956, 4, 5, 8, 0, 1, '2026', '2026-01-05', '2026-01-05 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(957, 4, 5, 8, 0, 1, '2026', '2026-01-06', '2026-01-06 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(958, 4, 5, 8, 0, 1, '2026', '2026-01-07', '2026-01-07 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(959, 4, 5, 8, 0, 1, '2026', '2026-01-08', '2026-01-08 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(960, 4, 5, 8, 0, 1, '2026', '2026-01-09', '2026-01-09 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(961, 4, 5, 8, 0, 1, '2026', '2026-01-10', '2026-01-10 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(962, 4, 5, 8, 0, 1, '2026', '2026-01-11', '2026-01-11 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(963, 4, 5, 8, 0, 1, '2026', '2026-01-12', '2026-01-12 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(964, 4, 5, 8, 0, 1, '2026', '2026-01-13', '2026-01-13 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(965, 4, 5, 8, 0, 1, '2026', '2026-01-14', '2026-01-14 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(966, 4, 5, 8, 0, 1, '2026', '2026-01-15', '2026-01-15 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(967, 4, 5, 8, 0, 1, '2026', '2026-01-16', '2026-01-16 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(968, 4, 5, 8, 0, 1, '2026', '2026-01-17', '2026-01-17 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(969, 4, 5, 8, 0, 1, '2026', '2026-01-18', '2026-01-18 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(970, 4, 5, 8, 0, 1, '2026', '2026-01-19', '2026-01-19 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(971, 4, 5, 8, 0, 1, '2026', '2026-01-20', '2026-01-20 20:00:00', '02:00:00', 'manual', '08:00:00', NULL, '06:00:00', NULL, NULL, NULL, 3, 'Half Day', 20003.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(972, 5, 5, 6, 0, 1, '2026', '2026-01-01', '2026-01-01 00:00:00', '09:00:00', 'manual', '18:00:00', 'manual', '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, 'hfghgfgf', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, '2026-01-20', 1, 10),
(973, 5, 5, 6, 0, 1, '2026', '2026-01-02', '2026-01-02 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(974, 5, 5, 6, 0, 1, '2026', '2026-01-03', '2026-01-03 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(975, 5, 5, 6, 0, 1, '2026', '2026-01-04', '2026-01-04 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(977, 5, 5, 6, 0, 1, '2026', '2026-01-06', '2026-01-06 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(978, 5, 5, 6, 0, 1, '2026', '2026-01-07', '2026-01-07 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(979, 5, 5, 6, 0, 1, '2026', '2026-01-08', '2026-01-08 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(980, 5, 5, 6, 0, 1, '2026', '2026-01-09', '2026-01-09 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(981, 5, 5, 6, 0, 1, '2026', '2026-01-10', '2026-01-10 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(982, 5, 5, 6, 0, 1, '2026', '2026-01-11', '2026-01-11 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(983, 5, 5, 6, 0, 1, '2026', '2026-01-12', '2026-01-12 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(984, 5, 5, 6, 0, 1, '2026', '2026-01-13', '2026-01-13 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(985, 5, 5, 6, 0, 1, '2026', '2026-01-14', '2026-01-14 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(986, 5, 5, 6, 0, 1, '2026', '2026-01-15', '2026-01-15 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(987, 5, 5, 6, 0, 1, '2026', '2026-01-16', '2026-01-16 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(988, 5, 5, 6, 0, 1, '2026', '2026-01-17', '2026-01-17 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(989, 5, 5, 6, 0, 1, '2026', '2026-01-18', '2026-01-18 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(990, 5, 5, 6, 0, 1, '2026', '2026-01-19', '2026-01-19 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(991, 5, 5, 6, 0, 1, '2026', '2026-01-20', '2026-01-20 09:00:00', '09:00:00', 'manual', '18:00:00', NULL, '09:00:00', NULL, NULL, NULL, 1, 'Present', 20004.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(992, 6, 5, 9, 0, 1, '2026', '2026-01-01', '2026-01-01 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(993, 6, 5, 9, 0, 1, '2026', '2026-01-02', '2026-01-02 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(994, 6, 5, 9, 0, 1, '2026', '2026-01-03', '2026-01-03 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(995, 6, 5, 9, 0, 1, '2026', '2026-01-04', '2026-01-04 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(996, 6, 5, 9, 0, 1, '2026', '2026-01-05', '2026-01-05 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(997, 6, 5, 9, 0, 1, '2026', '2026-01-06', '2026-01-06 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(999, 6, 5, 9, 0, 1, '2026', '2026-01-08', '2026-01-08 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1000, 6, 5, 9, 0, 1, '2026', '2026-01-09', '2026-01-09 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1001, 6, 5, 9, 0, 1, '2026', '2026-01-10', '2026-01-10 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1002, 6, 5, 9, 0, 1, '2026', '2026-01-11', '2026-01-11 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1003, 6, 5, 9, 0, 1, '2026', '2026-01-12', '2026-01-12 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10);
INSERT INTO `attendance_entry` (`attendance_id`, `emp_id`, `department_id`, `shift_id`, `machine_userid`, `month`, `year`, `attendance_date`, `attendance_stamp`, `intime`, `entry_type`, `outtime`, `entry_type_out`, `working_hours`, `in_status`, `out_status`, `machineid`, `attheadid`, `attendance_status`, `basic_salary`, `in_remark`, `out_remark`, `previous_in_time`, `previous_out_time`, `overtime`, `fine_amt`, `fine_action`, `attendanceby`, `verifyiedby`, `ipaddress`, `createdate`, `createtime`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1004, 6, 5, 9, 0, 1, '2026', '2026-01-13', '2026-01-13 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1005, 6, 5, 9, 0, 1, '2026', '2026-01-14', '2026-01-14 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1006, 6, 5, 9, 0, 1, '2026', '2026-01-15', '2026-01-15 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1007, 6, 5, 9, 0, 1, '2026', '2026-01-16', '2026-01-16 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1008, 6, 5, 9, 0, 1, '2026', '2026-01-17', '2026-01-17 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1009, 6, 5, 9, 0, 1, '2026', '2026-01-18', '2026-01-18 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1010, 6, 5, 9, 0, 1, '2026', '2026-01-19', '2026-01-19 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1011, 6, 5, 9, 0, 1, '2026', '2026-01-20', '2026-01-20 06:00:00', '06:00:00', 'manual', '14:00:00', NULL, '08:00:00', NULL, NULL, NULL, 1, 'Present', 20005.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1012, 7, 5, 7, 0, 1, '2026', '2026-01-01', '2026-01-01 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1013, 7, 5, 7, 0, 1, '2026', '2026-01-02', '2026-01-02 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1014, 7, 5, 7, 0, 1, '2026', '2026-01-03', '2026-01-03 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1015, 7, 5, 7, 0, 1, '2026', '2026-01-04', '2026-01-04 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1016, 7, 5, 7, 0, 1, '2026', '2026-01-05', '2026-01-05 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1017, 7, 5, 7, 0, 1, '2026', '2026-01-06', '2026-01-06 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1018, 7, 5, 7, 0, 1, '2026', '2026-01-07', '2026-01-07 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1019, 7, 5, 7, 0, 1, '2026', '2026-01-08', '2026-01-08 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1020, 7, 5, 7, 0, 1, '2026', '2026-01-09', '2026-01-09 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1022, 7, 5, 7, 0, 1, '2026', '2026-01-11', '2026-01-11 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1023, 7, 5, 7, 0, 1, '2026', '2026-01-12', '2026-01-12 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1024, 7, 5, 7, 0, 1, '2026', '2026-01-13', '2026-01-13 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1025, 7, 5, 7, 0, 1, '2026', '2026-01-14', '2026-01-14 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1026, 7, 5, 7, 0, 1, '2026', '2026-01-15', '2026-01-15 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1027, 7, 5, 7, 0, 1, '2026', '2026-01-16', '2026-01-16 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1028, 7, 5, 7, 0, 1, '2026', '2026-01-17', '2026-01-17 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1029, 7, 5, 7, 0, 1, '2026', '2026-01-18', '2026-01-18 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1030, 7, 5, 7, 0, 1, '2026', '2026-01-19', '2026-01-19 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10),
(1031, 7, 5, 7, 0, 1, '2026', '2026-01-20', '2026-01-20 08:00:00', '08:00:00', 'manual', '20:00:00', NULL, '12:00:00', NULL, NULL, NULL, 1, 'Present', 20006.00, '', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, 10);

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
(1, 1, 1, 'ADMIN', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(2, 1, 2, 'OHC', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(3, 1, 2, 'SAFETY', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(4, 1, 3, 'COMMON', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(5, 1, 3, 'CLEANER', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(6, 1, 1, 'HUMAN RESOURCE', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(7, 1, 1, 'INFORMATION TECHNOLOGY', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(8, 1, 1, 'PURCHASE', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(9, 1, 4, 'SECURITY', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(10, 1, 5, 'STORE', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(11, 2, 6, 'FERRO PRODUCTION', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(12, 2, 6, 'FERRO LAB (QUALITY)', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(13, 3, 7, 'SMS CCM', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(14, 3, 7, 'SMS LAB (QUALITY)', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(15, 3, 7, 'SMS OPERATION & MAINTANANCE', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(16, 3, 7, 'SMS MAINTENANCE - ELECTRICAL', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(17, 3, 7, 'SMS MAINTENANCE - MECHANICAL', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(18, 3, 7, 'SMS PRODUCTION', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(19, 4, 8, 'POWER PLANT E & I 60MW', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(20, 4, 9, 'POWER PLANT E & I 8MW', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(21, 4, 8, 'POWER PLANT MECHANICAL 60MW', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(22, 4, 8, 'POWER PLANT OPERATION 60MW', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(23, 4, 9, 'POWER PLANT MECHANICAL 8MW', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(24, 4, 9, 'POWER PLANT OPERATION  8MW', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(25, 5, 10, 'SECTION MILL', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(26, 5, 11, 'SECTION MILL', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(27, 5, 11, 'ROLLING MILL DISPATCH', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(28, 5, 11, 'ROLLING MILL OPERATION', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(29, 5, 11, 'ROLLING MILL PRODUCTION', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(30, 5, 11, 'ROLLING MILL WORKSHOP', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(31, 6, 12, 'AUTOMOBILE', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(32, 6, 13, 'DISPATCH', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(33, 6, 14, 'CIVIL', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(34, 6, 15, 'DRI ELECTRICAL', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(35, 6, 15, 'DRI MECHANICAL', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(36, 6, 15, 'DRI MECH RMHS', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(37, 6, 15, 'DRI PROCESS', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(38, 6, 15, 'DRI RMHS', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(39, 6, 16, 'SID ELECTRICAL', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(40, 6, 16, 'SID LAB (QUALITY)', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(41, 6, 16, 'SID MECHANICAL', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(42, 6, 16, 'SID PROCESS', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(43, 6, 16, 'SID RMHS', 0, 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `designation_master`
--

CREATE TABLE `designation_master` (
  `designation_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL DEFAULT 0,
  `department_id` int(11) NOT NULL DEFAULT 0,
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

INSERT INTO `designation_master` (`designation_id`, `grade_id`, `department_id`, `designation`, `createdby`, `ipaddress`, `createdate`, `lastupdated`, `unit_id`, `sessionid`) VALUES
(1, 1, 1, 'FACTORY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(2, 1, 1, 'PLANT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(3, 1, 1, 'AUDITOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(4, 1, 1, 'RECEPTIONIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(5, 1, 1, 'OFFICE BOY', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(6, 1, 2, 'DOCTOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(7, 1, 2, 'COMPOUNDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(8, 1, 2, 'AMBULANCE DRIVER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(9, 1, 3, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(10, 1, 3, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(11, 1, 3, 'OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(12, 1, 3, 'ASSISTANT OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(13, 1, 3, 'SENIOR SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(14, 1, 3, 'SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(15, 1, 3, 'TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(16, 1, 4, 'SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(17, 1, 5, 'SWEEPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(18, 1, 4, 'COOK', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(19, 1, 4, 'GARDENER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(20, 1, 4, 'PLUMBER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(21, 1, 4, 'PAINTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(22, 1, 6, 'GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(23, 1, 6, 'DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(24, 1, 6, 'ASSISTANT GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(25, 1, 6, 'SENIOR OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(26, 1, 6, 'OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(27, 1, 6, 'EXECUTIVE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(28, 1, 6, 'JUNIOR EXECUTIVE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(29, 1, 6, 'ASSISTANT HR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(30, 1, 7, 'MIS EXECUTIVE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(31, 1, 8, 'VICE PRESIDENT', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(32, 1, 8, 'GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(33, 1, 8, 'DEPUTY GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(34, 1, 8, 'ASSISTANT GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(35, 1, 8, 'SENIOR MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(36, 1, 8, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(37, 1, 8, 'DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(38, 1, 8, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(39, 1, 8, 'OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(40, 1, 8, 'ASSISTANT OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(41, 1, 8, 'EXECUTIVE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(42, 1, 8, 'JUNIOR EXECUTIVE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(43, 1, 8, 'ASSISTANT', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(44, 1, 9, 'CHIEF SECURITY OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(45, 1, 9, 'SECURITY OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(46, 1, 9, 'ASSISTANT SECURITY OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(47, 1, 9, 'SECURITY GUARD', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(48, 1, 10, 'VICE PRESIDENT', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(49, 1, 10, 'GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(50, 1, 10, 'DEPUTY GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(51, 1, 10, 'ASSISTANT GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(52, 1, 10, 'SENIOR MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(53, 1, 10, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(54, 1, 10, 'DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(55, 1, 10, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(56, 1, 10, 'OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(57, 1, 10, 'ASSISTANT OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(58, 1, 10, 'SENIOR STORE KEEPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(59, 1, 10, 'STORE KEEPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(60, 1, 10, 'EXECUTIVE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(61, 1, 10, 'JUNIOR EXECUTIVE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(62, 1, 10, 'ASSISTANT', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(63, 1, 10, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(64, 1, 11, 'VICE PRESIDENT', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(65, 1, 11, 'GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(66, 1, 11, 'DEPUTY GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(67, 1, 11, 'ASSISTANT GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(68, 1, 11, 'PRODUCTION MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(69, 1, 11, 'MECHANICAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(70, 1, 11, 'ELECTRICAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(71, 1, 11, 'DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(72, 1, 11, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(73, 1, 11, 'SENIOR SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(74, 1, 11, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(75, 1, 11, 'SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(76, 1, 12, 'SENIOR CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(77, 1, 11, 'LOADING SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(78, 1, 12, 'LAB SAMPLER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(79, 1, 12, 'LAB INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(80, 1, 12, 'CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(81, 1, 11, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(82, 1, 13, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(83, 1, 13, 'SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(84, 1, 14, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(85, 1, 14, 'SENIOR CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(86, 1, 14, 'CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(87, 1, 14, 'ASSISTANT CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(88, 1, 14, 'SENIOR TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(89, 1, 14, 'TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(90, 1, 14, 'ASSISTANT TECHNICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(91, 1, 15, 'GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(92, 1, 16, 'DEPUTY GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(93, 1, 16, 'ASSISTANT GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(94, 1, 16, 'SENIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(95, 1, 16, 'ELECTRICAL FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(96, 1, 16, 'SENIOR SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(97, 1, 16, 'SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(98, 1, 16, 'GET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(99, 1, 16, 'DET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(100, 1, 16, 'SENIOR ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(101, 1, 16, 'ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(102, 1, 16, 'ASSISTANT ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(103, 1, 16, 'JUNIOR ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(104, 1, 16, 'SENIOR TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(105, 1, 16, 'TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(106, 1, 16, 'ASSISTANT TECHNICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(107, 1, 17, 'MECHANICAL FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(108, 1, 17, 'DEPUTY GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(109, 1, 17, 'ASSISTANT GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(110, 1, 17, 'SENIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(111, 1, 17, 'ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(112, 1, 17, 'SENIOR FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(113, 1, 17, 'SENIOR SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(114, 1, 17, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(115, 1, 17, 'SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(116, 1, 17, 'GET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(117, 1, 17, 'DET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(118, 1, 17, 'SENIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(119, 1, 17, 'FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(120, 1, 17, 'ASSISTANT FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(121, 1, 17, 'SENIOR WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(122, 1, 17, 'WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(123, 1, 17, 'JUNIOR WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(124, 1, 17, 'TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(125, 1, 18, 'GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(126, 1, 18, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(127, 1, 18, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(128, 1, 18, 'SENIOR SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(129, 1, 18, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(130, 1, 18, 'ASSISTANT SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(131, 1, 18, 'SENIOR SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(132, 1, 18, 'SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(133, 1, 19, 'GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(134, 1, 19, 'DEPUTY GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(135, 1, 19, 'ASSISTANT GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(136, 1, 19, 'SENIOR MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(137, 1, 19, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(138, 1, 19, 'DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(139, 1, 19, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(140, 1, 19, 'SENIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(141, 1, 19, 'ELECTRICAL ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(142, 1, 19, 'JUNIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(143, 1, 19, 'SENIOR FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(144, 1, 19, 'ELECTRICAL FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(145, 1, 19, 'SENIOR SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(146, 1, 19, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(147, 1, 19, 'SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(148, 1, 19, 'GET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(149, 1, 19, 'DET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(150, 1, 19, 'SENIOR ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(151, 1, 19, 'ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(152, 1, 19, 'ASSISTANT ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(153, 1, 19, 'JUNIOR ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(154, 1, 19, 'SENIOR TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(155, 1, 19, 'TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(156, 1, 19, 'ASSISTANT TECHNICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(157, 1, 19, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(158, 1, 19, 'TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(159, 1, 19, 'INSTRUMENT ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(160, 1, 19, 'INSTRUMENT  JUNIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(161, 1, 19, 'INSTRUMENT FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(162, 1, 20, 'INSTRUMENT TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(163, 1, 20, 'INSTRUMENT  ASSISTANT ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(164, 1, 20, 'SENIOR INSTUMENT ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(165, 1, 20, 'SENIOR TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(166, 1, 20, 'TECHNICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(167, 1, 20, 'TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(168, 1, 21, 'DEPUTY GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(169, 1, 21, 'ASSISTANT GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(170, 1, 21, 'AHS SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(171, 1, 21, 'SENIOR MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(172, 1, 21, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(173, 1, 21, 'DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(174, 1, 21, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(175, 1, 21, 'SENIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(176, 1, 21, 'ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(177, 1, 21, 'ASSISTANT ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(178, 1, 21, 'GET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(179, 1, 21, 'DET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(180, 1, 21, 'ASSISTANT FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(181, 1, 21, 'CHP OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(182, 1, 21, 'FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(183, 1, 21, 'FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(184, 1, 21, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(185, 1, 21, 'IBR WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(186, 1, 21, 'JUNIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(187, 1, 21, 'SENIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(188, 1, 21, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(189, 1, 21, 'SILO OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(190, 1, 21, 'SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(191, 1, 21, 'TURBINE FIELD TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(192, 1, 21, 'WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(193, 1, 22, '1st CLASS ASSISTANT BOILER ATTENDANT', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(194, 1, 22, '1ST CLASS ASSISTANT BOILER OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(195, 1, 22, '2nd CLASS BOILER ATTENDANT', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(196, 1, 22, 'AFBC DCS ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(197, 1, 22, 'AFBC DCS SENIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(198, 1, 22, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(199, 1, 22, 'BOILER DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(200, 1, 22, 'BOILER FIELD OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(201, 1, 22, 'CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(202, 1, 22, 'CHP OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(203, 1, 22, 'DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(204, 1, 22, 'DM PLANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(205, 1, 22, 'DM PLANT OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(206, 1, 22, 'FIREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(207, 1, 22, 'FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(208, 1, 22, 'SENIOR CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(209, 1, 22, 'SENIOR SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(210, 1, 22, 'SHIFT ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(211, 1, 22, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(212, 1, 22, 'SILO OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(213, 1, 22, 'TURBINE DCS ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(214, 1, 22, 'TURBINE FIELD ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(215, 1, 22, 'TURBINE FIELD ENGINEER TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(216, 1, 22, 'TURBINE FIELD JOUNIER ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(217, 1, 22, 'TURBINE FIELD OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(218, 1, 22, 'TURBINE FIELD TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(219, 1, 22, 'WHRB DCS ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(220, 1, 22, 'WHRB DCS ENGINEER TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(221, 1, 22, 'WHRB DCS SENIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(222, 1, 23, 'ASSISTANT FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(223, 1, 23, 'FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(224, 1, 23, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(225, 1, 23, 'IBR WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(226, 1, 23, 'KILLAN ASSISTANT FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(227, 1, 23, 'SENIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(228, 1, 23, 'SENIOR WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(229, 1, 23, 'SILO OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(230, 1, 23, 'TURBINE FIELD OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(231, 1, 23, 'WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(232, 1, 24, '2ND CLASS BOILER OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(233, 1, 24, 'CHP OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(234, 1, 24, '2nd CLASS BOILER ATTENDANT', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(235, 1, 24, 'AFBC DCS ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(236, 1, 24, 'BOILER DCS ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(237, 1, 24, 'DM PLANT CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(238, 1, 24, 'DM PLANT CHEMIST CUM OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(239, 1, 24, 'DM PLANT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(240, 1, 24, 'DM PLANT TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(241, 1, 24, 'ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(242, 1, 24, 'FIELD OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(243, 1, 24, 'FIREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(244, 1, 24, 'FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(245, 1, 24, 'JUNIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(246, 1, 24, 'OPERATION MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(247, 1, 24, 'SENIOR CHP OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(248, 1, 24, 'SENIOR ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(249, 1, 24, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(250, 1, 24, 'TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(251, 1, 24, 'TURBINE DCS OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(252, 1, 24, 'TURBINE DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(253, 1, 24, 'TURBINE FIELD OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(254, 1, 25, 'ASSISTANT FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(255, 1, 25, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(256, 1, 25, 'ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(257, 1, 25, 'FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(258, 1, 25, 'FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(259, 1, 25, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(260, 1, 25, 'JUNIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(261, 1, 25, 'MECHANICAL INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(262, 1, 25, 'ROUGHING MILL FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(263, 1, 25, 'SENIOR CNC OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(264, 1, 25, 'SENIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(265, 1, 25, 'SENIOR TURNER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(266, 1, 25, 'SHEPER MAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(267, 1, 25, 'TURNER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(268, 1, 25, 'WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(269, 1, 25, 'WORKSHOP INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(270, 1, 26, 'JUNIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(271, 1, 26, 'SHIPPER MAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(272, 1, 27, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(273, 1, 27, 'SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(274, 1, 28, 'FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(275, 1, 28, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(276, 1, 28, 'JUNIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(277, 1, 28, 'MECH INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(278, 1, 28, 'PUMP HOUSE OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(279, 1, 28, 'SENIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(280, 1, 28, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(281, 1, 28, 'TURNER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(282, 1, 28, 'WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(283, 1, 29, 'GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(284, 1, 29, 'FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(285, 1, 29, 'BOX FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(286, 1, 29, 'FUFFITING OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(287, 1, 29, 'MILL FITTTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(288, 1, 29, 'MILL INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(289, 1, 29, 'PUMP HOUSE OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(290, 1, 29, 'RUFFING OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(291, 1, 29, 'SENIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(292, 1, 29, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(293, 1, 29, 'WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(294, 1, 30, 'CNC OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(295, 1, 30, 'LETH MACHINE OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(296, 1, 30, 'MILL INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(297, 1, 30, 'SENIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(298, 1, 30, 'TURNER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(299, 1, 30, 'WORKSHOP INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(300, 1, 31, 'SENIOR SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(301, 1, 31, 'SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(302, 1, 31, 'EXECAVATOR OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(303, 1, 31, 'HYDRA DRIVER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(304, 1, 31, 'HYDRA OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(305, 1, 31, 'HYVA DRIVER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(306, 1, 31, 'JCB OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(307, 1, 31, 'SCEVER MACHINE OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(308, 1, 31, 'TRACTOR DRIVER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(309, 1, 31, 'DRIVER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(310, 1, 32, 'ASSISTANT COMMERCIAL OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(311, 1, 32, 'ASSISTANT ACCOUNTANT', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(312, 1, 32, 'ASSISTANT DISPATCH', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(313, 1, 32, 'ASSISTANT LOGISTIC OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(314, 1, 32, 'COMPUTER OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(315, 1, 32, 'DEPUTY GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(316, 1, 32, 'EXECUTIVE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(317, 1, 32, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(318, 1, 32, 'LOGISTIC OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(319, 1, 32, 'SENIOR LOGISTIC OFFICER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(320, 1, 32, 'SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(321, 1, 32, 'TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(322, 1, 32, 'TRAINEE DISPATCH', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(323, 1, 32, 'TRAINEE WEIGHMENT OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(324, 1, 32, 'WEIGHMENT OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(325, 1, 33, 'DET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(326, 1, 33, 'ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(327, 1, 33, 'GET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(328, 1, 33, 'JUNIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(329, 1, 33, 'SENIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(330, 1, 33, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(331, 1, 33, 'DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(332, 1, 33, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(333, 1, 33, 'SENIOR SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(334, 1, 33, 'SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(335, 1, 33, 'SURVEY', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(336, 1, 34, 'GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(337, 1, 34, 'DEPUTY GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(338, 1, 34, 'ASSISTANT GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(339, 1, 34, 'SENIOR MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(340, 1, 34, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(341, 1, 34, 'DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(342, 1, 34, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(343, 1, 34, 'SENIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(344, 1, 34, 'ELECTRICAL ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(345, 1, 34, 'JUNIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(346, 1, 34, 'SENIOR FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(347, 1, 34, 'ELECTRICAL FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(348, 1, 34, 'SENIOR SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(349, 1, 34, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(350, 1, 34, 'SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(351, 1, 34, 'GET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(352, 1, 34, 'DET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(353, 1, 34, 'SENIOR ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(354, 1, 34, 'ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(355, 1, 34, 'ASSISTANT ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(356, 1, 34, 'JUNIOR ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(357, 1, 34, 'SENIOR TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(358, 1, 34, 'TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(359, 1, 34, 'ASSISTANT TECHNICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(360, 1, 34, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(361, 1, 34, 'TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(362, 1, 35, 'ASSISTANT ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(363, 1, 36, 'ASSISTANT FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(364, 1, 35, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(365, 1, 36, 'BELT JOINTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(366, 1, 35, 'BELT JOINTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(367, 1, 36, 'BELT OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(368, 1, 36, 'CIRCUIT OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(369, 1, 35, 'CIRCUIT OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(370, 1, 35, 'DEPUTY GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(371, 1, 35, 'DET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(372, 1, 35, 'ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(373, 1, 36, 'FIELD OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(374, 1, 35, 'FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(375, 1, 36, 'FITTER CUM CIRCUIT OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(376, 1, 36, 'FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(377, 1, 35, 'FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(378, 1, 35, 'GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(379, 1, 35, 'GET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(380, 1, 35, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(381, 1, 35, 'JUNIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(382, 1, 35, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(383, 1, 36, 'PLC OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(384, 1, 35, 'RIGGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(385, 1, 36, 'RMHS (PLC + FIELD)', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(386, 1, 36, 'RMHS OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(387, 1, 35, 'RMP OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(388, 1, 35, 'RMP WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(389, 1, 35, 'SENIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(390, 1, 35, 'SENIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(391, 1, 35, 'SENIOR FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(392, 1, 35, 'SENIOR WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(393, 1, 35, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(394, 1, 35, 'TRAINEE RMHS OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(395, 1, 35, 'WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(396, 1, 37, 'ASSISTANT ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(397, 1, 37, 'ASSISTANT SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(398, 1, 37, 'CRO', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(399, 1, 37, 'DEPUTY MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(400, 1, 37, 'DET', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(401, 1, 37, 'FIELD OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(402, 1, 37, 'GENERAL MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(403, 1, 37, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(404, 1, 37, 'QRT OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(405, 1, 37, 'SENIOR ENGINEER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(406, 1, 37, 'SENIOR KILLAN OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(407, 1, 37, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(408, 1, 37, 'TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(409, 1, 38, 'JR SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(410, 1, 38, 'SENIOR SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(411, 1, 38, 'SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(412, 1, 39, 'AC ELECTRIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(413, 1, 39, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(414, 1, 39, 'ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(415, 1, 39, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(416, 1, 39, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(417, 1, 39, 'MOTOR WINDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(418, 1, 39, 'MOTTOR HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(419, 1, 39, 'SENIOR AC TECHNICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(420, 1, 39, 'SENIOR ELECTRICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(421, 1, 39, 'SENIOR MOTTOR WINDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(422, 1, 39, 'SENIOR SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(423, 1, 39, 'SENIOR TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(424, 1, 39, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(425, 1, 39, 'TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(426, 1, 39, 'TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(427, 1, 39, 'WINDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(428, 1, 40, 'ASSISTANT CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(429, 1, 40, 'ASSISTANT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(430, 1, 40, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(431, 1, 40, 'CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(432, 1, 40, 'JUNIOR CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(433, 1, 40, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(434, 1, 40, 'SENIOR CHEMIST', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(435, 1, 40, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(436, 1, 40, 'TECHANICIAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(437, 1, 40, 'TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(438, 1, 41, 'ASSISTANT FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(439, 1, 41, 'ASSISTANT MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(440, 1, 41, 'CIRCUIT OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(441, 1, 41, 'FEBRICATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(442, 1, 41, 'FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(443, 1, 41, 'FOREMAN', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(444, 1, 41, 'HELPER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(445, 1, 41, 'KILLAN OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(446, 1, 41, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(447, 1, 41, 'POLLUTION INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(448, 1, 41, 'SENIOR FITTER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(449, 1, 41, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(450, 1, 41, 'WELDER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(451, 1, 42, 'MANAGER', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(452, 1, 42, 'QRT OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(453, 1, 42, 'SENIOR OPERATOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(454, 1, 42, 'SENIOR TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(455, 1, 42, 'SENIOR TRAINEE CRO', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(456, 1, 42, 'SHIFT INCHARGE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(457, 1, 42, 'TRAINEE', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(458, 1, 43, 'JUNIOR SUPERVISOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10),
(459, 1, 43, 'SUPERVISIOR', 1, '192.168.1.37', '2026-01-20', '0000-00-00', 1, 10);

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
(1, 'ADMIN', 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(2, 'FD', 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(3, 'SMS', 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(4, 'PD', 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(5, 'RM', 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(6, 'SID', 1, '192.168.1.37', '2026-01-20', NULL, 1, 10);

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
(4, 1, 'Termination', '12', 'gsfdgs', '2026-01-26', '2026-01-16', 0, '', 1, '192.168.1.37', '2026-01-17', NULL, 1, 10);

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
(1, '00011', '1', 'Roma', 'Chakradhari', 'mr chakradhari', 'Female', '2003-09-01', 22, 'O+', 'Unmarried', '0000-00-00', 'indian', 'Hindu', 'OBC', '7999559862', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559862', 2147483647, 'pan12345', 'driving1234', '12345678909', 'no', 5, 17, 1, '2023-01-01', 'raigarh', '09:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20000.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541230', 'CRGB000161', '123654789', '14785236985', '147852369', '2023-01-01', '2023-01-01', 0, '', 19000.00, NULL, 0.00, '2025-12-01', 0, '0000-00-00', 0, 0, NULL, 1, '192.168.1.37', NULL, '2026-01-20', 1, 10),
(2, '00013', '2', 'Bablu', 'pandit', 'mr pandit', 'Male', '2003-09-02', 23, 'O+', 'Unmarried', '0000-00-00', 'indian', 'Hindu', 'OBC', '7999559863', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559863', 2147483647, 'pan12346', 'driving1235', '12345678910', 'no', 1, 2, 1, '2023-01-02', 'raigarh', '12:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20001.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541231', 'CRGB000162', '123654789', '14785236986', '147852369', '2023-01-01', '2023-01-01', 0, '', 19001.00, NULL, 6.00, '2025-12-02', 0, '0000-00-00', 0, 0, NULL, 1, '192.168.1.37', NULL, '2026-01-20', 1, 10),
(3, '00008', '3', 'Naveen', 'pandit', 'mr pandit', 'Male', '2003-09-03', 24, 'O+', 'Unmarried', '0000-00-00', 'indian', 'Hindu', 'OBC', '7999559864', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559864', 2147483647, 'pan12347', 'driving1236', '12345678911', 'no', 1, 2, 1, '2023-01-03', 'raigarh', '09:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20002.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541232', 'CRGB000163', '123654789', '14785236987', '147852369', '2023-01-01', '2023-01-01', 0, '', 19002.00, NULL, 7.00, '2025-12-03', 0, '0000-00-00', 0, 0, NULL, 1, '192.168.1.37', NULL, '2026-01-20', 1, 10),
(4, '00010', '4', 'tushali', 'mahanti', 'mr mahanti', 'Female', '2003-09-04', 25, 'O+', 'Unmarried', '0000-00-00', 'indian', 'Hindu', 'OBC', '7999559865', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559865', 2147483647, 'pan12348', 'driving1237', '12345678912', 'no', 35, 362, 1, '2023-01-04', 'raigarh', '12:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20003.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541233', 'CRGB000164', '123654789', '14785236988', '147852369', '2023-01-01', '2023-01-01', 0, '', 19003.00, NULL, 8.00, '2025-12-04', 0, '0000-00-00', 0, 0, NULL, 1, '192.168.1.37', NULL, '2026-01-20', 1, 10),
(5, '00005', '5', 'Mallika', 'Dutta', 'mr Dutta', 'Female', '2003-09-05', 26, 'O+', 'Unmarried', '0000-00-00', 'indian', 'Hindu', 'OBC', '7999559866', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559866', 2147483647, 'pan12349', 'driving1238', '12345678913', 'no', 5, 17, 1, '2023-01-05', 'raigarh', '09:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20004.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541234', 'CRGB000165', '123654789', '14785236989', '147852369', '2023-01-01', '2023-01-01', 0, NULL, 19004.00, NULL, 9.00, '2025-12-05', 0, '0000-00-00', 0, 0, NULL, 2, '192.168.1.37', NULL, NULL, 1, 10),
(6, '00006', '6', 'Kajal', 'Vishwakarma', 'mr Vishwakrma', 'Female', '2003-09-06', 27, 'O+', 'Unmarried', '0000-00-00', 'indian', 'Hindu', 'OBC', '7999559867', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559867', 2147483647, 'pan12350', 'driving1239', '12345678914', 'no', 5, 17, 1, '2023-01-06', 'raigarh', '08:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20005.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541235', 'CRGB000166', '123654789', '14785236990', '147852369', '2023-01-01', '2023-01-01', 0, NULL, 19005.00, NULL, 10.00, '2025-12-06', 0, '0000-00-00', 0, 0, NULL, 2, '192.168.1.37', NULL, NULL, 1, 10),
(7, '00009', '7', 'Radhika', 'Sahu', 'Mr sahu', 'Female', '2003-09-07', 28, 'O+', 'Unmarried', '0000-00-00', 'indian', 'Hindu', 'OBC', '7999559868', '7999559862', 'roma@gmail.com', 'raipur', 'fingeshwari', 'leena', 'sister', '7999559868', 2147483647, 'pan12351', 'driving1240', '12345678915', 'no', 1, 3, 1, '2023-01-07', 'raigarh', '12:00:00', 'Permanent', 'roma', 8, '2000-01-01', '2022-01-01', 'low Salary', 'NA', 'Bablu sir', 20006.00, 1, 1, 0.00, 0.00, 3, 'roma chakradhari', '7896541236', 'CRGB000167', '123654789', '14785236991', '147852369', '2023-01-01', '2023-01-01', 0, '', 19006.00, NULL, 11.00, '2025-12-07', 0, '0000-00-00', 0, 0, NULL, 1, '192.168.1.37', NULL, '2026-01-20', 1, 10);

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
(2, '2', 12, 2025, 4.00, 1, '192.168.1.30', '2026-01-10', NULL, 1, 10),
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
  `holiday_tittle` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
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
(10, '2025-04-01', '2026-03-31', '2025-2026', 1, 0, 20, '192.168.1.33', '2026-01-01', '2024-03-11'),
(13, '2026-04-01', '2027-03-31', '2026-2027', 0, 0, 0, '192.168.1.33', '2026-01-01', '2026-01-01');

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
(20, 2, 5, 1, '2026', 200000.00, 0.00, 200000.00, 0.00, 0.00, 15000.00, 100000.00, 15000.00, 21000.00, '15.5', '0', '0', '2', '1', '0', '0.00', 0.00, '18.5', 59677.00, 29839.00, 955.00, 746.00, 28138.00, 119355.00, 1800.00, 158.00, 1800.00, 683.00, 1, 1, 2, '192.168.1.37', '2026-01-19', '0000-00-00 00:00:00', 10),
(21, 6, 5, 1, '2026', 20005.00, 0.00, 20005.00, 0.00, 0.00, 15000.00, 20005.00, 6453.00, 6453.00, '0', '0', '0', '0', '0', '10', '10', 0.00, '10', 6453.00, 0.00, 0.00, 0.00, 0.00, 6453.00, 774.00, 48.00, 774.00, 210.00, 1, 1, 2, '192.168.1.37', '2026-01-19', '0000-00-00 00:00:00', 10),
(22, 1, 5, 1, '2026', 20000.00, 0.00, 20000.00, 0.00, 0.00, 15000.00, 20000.00, 15000.00, 18065.00, '19', '0', '0', '3', '1', '5', '5', 0.00, '28', 18065.00, 0.00, 0.00, 0.00, 0.00, 18065.00, 1800.00, 135.00, 1800.00, 587.00, 1, 1, 1, '192.168.1.37', '2026-01-20', '0000-00-00 00:00:00', 10);

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
(1, 'OFFICE ADMIN', 1, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(2, 'HEALTH & SAFETY', 1, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(3, 'HOUSE KEEPING', 1, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(4, 'SECURITY', 1, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(5, 'STORE', 1, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(6, 'FERRO', 2, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(7, 'SMS FURNACE', 3, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(8, 'CPP 60MW', 4, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(9, 'CPP 8MW', 4, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(10, 'SECTION MILL', 5, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(11, 'TMT MILL', 5, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(12, 'SID P & M', 6, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(13, 'DISPATCH', 6, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(14, 'CIVIL', 6, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(15, 'DRI', 6, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10),
(16, 'SID 100TPD', 6, 1, '192.168.1.37', '2026-01-20', NULL, 1, 10);

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
(1, 'NRVS Steels Ltd.', '8959819111', 'trinitytesting@fmail.com', '', '', 'Gharghoda Raigarh', 'Raigarh', '', 'Raigarh', '', '10:30:00', '30', '18:30:00', '30', '8', 'DOC1768892093486.6.png', 0, 0, 10, 1, '192.168.1.37', '2025-12-04 13:34:08', '2026-01-20 12:24:53'),
(4, 'NR Inspat Pvt. Ltd.', '9898784545', 'radhika@gmail.com', '656546545665', '465468dfsd', 'Raipur', 'Raipur', '', 'Radhika', '', '11:00:00', '30', '19:00:00', '30', '8', 'DOC1768892080698.1.png', 0, 0, 10, 1, '192.168.1.37', '2025-12-18 13:22:26', '2026-01-20 12:24:40'),
(5, 'UNIT 3', '9632587412', 'test@dfs', 'GSTIN12345', 'pan123456', 'raipur', 'raipur', '', 'Raigarh', '', '10:30:00', '30', '18:30:00', '30', '8', 'DOC1768892070311.3.png', 0, 0, 10, 1, '192.168.1.37', '2025-12-18 14:17:12', '2026-01-20 12:24:30');

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
(1, 'admin', '123', 'admin', 'Admin', '8959819111', '1', '', '', 1, 1, 0, '0000-00-00', '', '2025-12-04 08:54:28'),
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
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1032;

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
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `designation_master`
--
ALTER TABLE `designation_master`
  MODIFY `designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=460;

--
-- AUTO_INCREMENT for table `division_master`
--
ALTER TABLE `division_master`
  MODIFY `division_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `exit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `emp_doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `month_leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `holiday_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `salary_struc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
  MODIFY `subdivision_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `unit_master`
--
ALTER TABLE `unit_master`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `upload_attachments`
--
ALTER TABLE `upload_attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `weekly_off_setting`
--
ALTER TABLE `weekly_off_setting`
  MODIFY `weekly_off_setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
