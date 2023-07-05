-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2022 at 05:26 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onlinebanking`
--
CREATE DATABASE IF NOT EXISTS `onlinebanking` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `onlinebanking`;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `UserID` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `day_create` date NOT NULL DEFAULT current_timestamp(),
  `activate_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`username`, `password`, `UserID`, `status`, `day_create`, `activate_token`) VALUES
('1549342659', '$2y$10$1T14tbnPrzgXCWukoE.VlO2jnCfbieltqOd0liUXPsmSdK74H90SC', 24, -1, '2022-05-24', 'a373cc220cf54a3b9966cfd18281a38b'),
('2439372637', '$2y$10$3StWnedzV/vBwn7Wh7Z5oec1oOzfNNsH9PCNJ9EEyhZ4oRICPbFwq', 19, 1, '2022-05-18', 'bc7de40e07ce235bce8ddd9031287bc4'),
('3651817244', '$2y$10$MHQM7MldpdvuTQ0iaDVGo.ahC9H/2B5ozNc5wx5kgOJQpUYfbVyg.', 21, 1, '2022-05-22', '761ff8cc65e0750f794e2ccb61ddc97d'),
('5973695080', '$2y$10$dqiygycQB22h0grmkWckV.7u2EConF2yf9b1fB3CWQV1.6i.XIeIK', 23, -2, '2022-05-24', 'f93bc07d494dc10df89b710d7930f151'),
('8920941248', '$2y$10$KU3sqo593l7U.oKxyBaxHOf7uIK9RSvLQdA.0vRMmS3xGyrU.sHYW', 27, -1, '2022-05-31', '070c4b7e66ecda688ab2b113a1a411c0'),
('9323223101', '$2y$10$nOSrg996vFyrbBE6Z.PQwOnY55hWajpvNA5nSMlhS42XL5PzEw8Ty', 25, -1, '2022-05-25', '9f5ef157b4cd2dbeac2d129df6b0c83d');

-- --------------------------------------------------------

--
-- Table structure for table `creditcard`
--

CREATE TABLE `creditcard` (
  `IDCard` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `CVV` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `endDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `creditcard`
--

INSERT INTO `creditcard` (`IDCard`, `CVV`, `endDate`) VALUES
('111111', '411', '2022-10-10'),
('222222', '443', '2022-11-11'),
('333333', '577', '2022-12-12');

-- --------------------------------------------------------

--
-- Table structure for table `historytransaction`
--

CREATE TABLE `historytransaction` (
  `ID` int(10) UNSIGNED NOT NULL,
  `UserId` int(11) NOT NULL,
  `Amount` int(11) NOT NULL,
  `DateTrans` date NOT NULL,
  `TypeTrans` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `Status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `historytransaction`
--

INSERT INTO `historytransaction` (`ID`, `UserId`, `Amount`, `DateTrans`, `TypeTrans`, `Status`) VALUES
(38, 19, 100000, '2022-05-31', 'Withdraw', 1),
(42, 19, 150000, '2022-05-31', 'Withdraw', 1),
(43, 19, 3000, '2022-05-31', 'Transfer', 1),
(53, 19, 100000, '2022-05-31', 'Withdraw', 1),
(54, 19, 100000, '2022-05-31', 'Withdraw', 1);

-- --------------------------------------------------------

--
-- Table structure for table `invalidaccess`
--

CREATE TABLE `invalidaccess` (
  `UserId` int(11) NOT NULL,
  `invalid` int(11) NOT NULL DEFAULT 0,
  `flag` int(11) NOT NULL DEFAULT 0,
  `retry` int(11) DEFAULT 0,
  `day_block` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `invalidaccess`
--

INSERT INTO `invalidaccess` (`UserId`, `invalid`, `flag`, `retry`, `day_block`) VALUES
(19, 0, 0, 1653846693, NULL),
(21, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reset_token`
--

CREATE TABLE `reset_token` (
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `otp` int(11) DEFAULT NULL,
  `expire_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `reset_token`
--

INSERT INTO `reset_token` (`email`, `otp`, `expire_on`) VALUES
('2417180075@caolanh1.edu.vn', 324131, 1653990033),
('gianguyen.2002.gn@gmail.com', 289799, 1653997089);

-- --------------------------------------------------------

--
-- Table structure for table `transferotp`
--

CREATE TABLE `transferotp` (
  `email` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expire_on` int(11) DEFAULT NULL,
  `otp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `transferotp`
--

INSERT INTO `transferotp` (`email`, `expire_on`, `otp`) VALUES
('gianguyen.2002.gn@gmail.com', 1653999829, 689129),
('2417180075@caolanh1.edu.vn', 1653999914, 944533);

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE `userinfo` (
  `UserID` int(10) UNSIGNED NOT NULL,
  `Phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `Email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `FullName` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `BirthDay` date NOT NULL,
  `UserAddress` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Front_IdentityCard` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Back_IndentityCard` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `userinfo`
--

INSERT INTO `userinfo` (`UserID`, `Phone`, `Email`, `FullName`, `BirthDay`, `UserAddress`, `Front_IdentityCard`, `Back_IndentityCard`) VALUES
(19, '1231312312', 'gianguyen.2002.gn@gmail.com', 'Nguyễn Gia Nguyễn', '0000-00-00', 'a211', '', ''),
(21, '10101010101', '2417180075@caolanh1.edu.vn', 'Lê Hồng Quân', '2022-05-09', 'B11', '52000851.png', 'image.png'),
(23, '12321312', 'abc@gmail.com', 'Nguyễn Thanh Phong', '2022-04-28', 's', '', ''),
(24, '123190009', 'slp03775@jiooq.com', 'Nguyen Nguyen', '0000-00-00', '123a', '', ''),
(25, '0737203', 'bcd@gmail.com', 'ABC', '2022-05-07', 'HCM', 'image.png', ''),
(27, '0908882525', 'nguyenThiMongThanh0105@gmail.com', 'Trần Thanh', '2022-05-19', 'Ho Chi Minh', '52000851.png', '');

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `UserID` int(11) NOT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `Phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `Balance` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`UserID`, `email`, `Phone`, `Balance`) VALUES
(19, 'gianguyen.2002.gn@gmail.com', '1231312312', 10294400),
(21, '2417180075@caolanh1.edu.vn', '10101010101', 603050);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD UNIQUE KEY `username` (`username`,`UserID`);

--
-- Indexes for table `creditcard`
--
ALTER TABLE `creditcard`
  ADD PRIMARY KEY (`CVV`),
  ADD UNIQUE KEY `IDCard` (`IDCard`);

--
-- Indexes for table `historytransaction`
--
ALTER TABLE `historytransaction`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `invalidaccess`
--
ALTER TABLE `invalidaccess`
  ADD UNIQUE KEY `UserId` (`UserId`);

--
-- Indexes for table `reset_token`
--
ALTER TABLE `reset_token`
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Phone` (`Phone`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD UNIQUE KEY `UserID` (`UserID`),
  ADD UNIQUE KEY `Phone` (`Phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `historytransaction`
--
ALTER TABLE `historytransaction`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `UserID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
