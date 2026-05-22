-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026 at 09:56 AM
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
-- Table structure for table `emp_leave_allotment`
--

CREATE TABLE `emp_leave_allotment` (
  `leave_allot_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `eoff` decimal(10,2) NOT NULL DEFAULT 0.00,
  `coff` decimal(10,2) NOT NULL DEFAULT 0.00,
  `opening_leave` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_leave` decimal(10,2) NOT NULL DEFAULT 0.00,
  `createdby` int(11) NOT NULL,
  `updatedby` int(11) DEFAULT NULL,
  `ipaddress` varchar(200) NOT NULL,
  `createdate` date NOT NULL,
  `lastupdated` date NOT NULL,
  `unit_id` int(11) NOT NULL,
  `sessionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `emp_leave_allotment`
--
ALTER TABLE `emp_leave_allotment`
  ADD PRIMARY KEY (`leave_allot_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emp_leave_allotment`
--
ALTER TABLE `emp_leave_allotment`
  MODIFY `leave_allot_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
