-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2021 at 11:33 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mlm`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(255) NOT NULL,
  `beneId` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `bankAccount` varchar(50) NOT NULL,
  `ifsc` varchar(20) NOT NULL,
  `address1` varchar(150) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `user` varchar(50) NOT NULL,
  `status` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `user` varchar(150) NOT NULL,
  `pass` varchar(1000) NOT NULL,
  `interest` double(3,2) DEFAULT 0.00,
  `default` double(9,2) DEFAULT NULL,
  `limit` double(9,2) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smpt_pass` varchar(100) NOT NULL,
  `smpt_port` int(10) NOT NULL,
  `smtp_host` varchar(150) NOT NULL,
  `protocol` varchar(11) NOT NULL,
  `website_name` varchar(180) NOT NULL,
  `payment` int(1) NOT NULL DEFAULT 0,
  `appswitch` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `phone`, `user`, `pass`, `interest`, `default`, `limit`, `smtp_user`, `smpt_pass`, `smpt_port`, `smtp_host`, `protocol`, `website_name`, `payment`, `appswitch`) VALUES
(1, 'yash jaiswal', 'yash121999@gmail.com', '8447441246', 'smartgogame123@', '19eaf3890faef938cf245c23aa3e0a65', 7.00, 100.00, 50000.00, 'programmingfinger@gmail.com', 'Newpassword1234567890@#$%^&*()', 465, 'ssl://smtp.googlemail.com', 'smtp', 'SmartGoGame.com', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `binary`
--

CREATE TABLE `binary` (
  `id` int(255) NOT NULL,
  `userid` varchar(200) NOT NULL,
  `amount` double(9,2) NOT NULL,
  `side` varchar(10) DEFAULT NULL,
  `carry` double(9,2) DEFAULT NULL,
  `right_id` varchar(200) DEFAULT NULL,
  `left_id` varchar(200) DEFAULT NULL,
  `created_at` varchar(150) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bv`
--

CREATE TABLE `bv` (
  `id` int(100) NOT NULL,
  `userid` varchar(150) NOT NULL,
  `RightBV` int(200) DEFAULT NULL,
  `created` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bv`
--

INSERT INTO `bv` (`id`, `userid`, `RightBV`, `created`) VALUES
(2550, 'GG938574', 0, '2020-10-21'),
(2551, 'GG788639', 0, '2021-01-18'),
(2552, 'GG623918', 0, '2021-02-14');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` int(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `credential`
--

CREATE TABLE `credential` (
  `id` int(10) NOT NULL,
  `type` varchar(150) DEFAULT NULL,
  `appid` varchar(200) NOT NULL,
  `secretkey` varchar(250) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `mode` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `credential`
--

INSERT INTO `credential` (`id`, `type`, `appid`, `secretkey`, `currency`, `mode`) VALUES
(1, 'cashfree', '64657e8d46dcbf73878a457fd75646', 'fbedf08a00e0c9860dad408161af3e4868f23955', 'INR', 'PROD'),
(3, 'payout', 'CF64657DPUEP58D48IAM22', 'ab11f6180635c3c290fe7bfe73836f34474c0d4a', '', 'PROD'),
(4, 'payout_free', 'CF20580V82MJ30TOV2YYMI', '61a43d07f3ae8f22efb27dba93727a7be47da2a4', '', 'TEST');

-- --------------------------------------------------------

--
-- Table structure for table `daily_p`
--

CREATE TABLE `daily_p` (
  `id` int(255) NOT NULL,
  `userid` varchar(100) NOT NULL,
  `day` varchar(100) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `rank` varchar(200) DEFAULT NULL,
  `team` int(12) DEFAULT NULL,
  `dm` int(10) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `daily_p`
--

INSERT INTO `daily_p` (`id`, `userid`, `day`, `amount`, `rank`, `team`, `dm`, `timestamp`) VALUES
(5, 'GG938574', '2020-10-21', 50.00, 'Single Joining', 1, 1, '2020-10-21 16:20:04'),
(6, 'GG788639', '2020-12-27', 0.00, NULL, NULL, NULL, '2020-12-27 12:28:21'),
(7, 'GG788639', '2020-12-28', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(8, 'GG788639', '2020-12-29', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(9, 'GG788639', '2020-12-30', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(10, 'GG788639', '2020-12-31', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(11, 'GG788639', '2021-01-01', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(12, 'GG788639', '2021-01-02', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(13, 'GG788639', '2021-01-03', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(14, 'GG788639', '2021-01-04', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(15, 'GG788639', '2021-01-05', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(16, 'GG788639', '2021-01-06', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(17, 'GG788639', '2021-01-07', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(18, 'GG788639', '2021-01-08', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(19, 'GG788639', '2021-01-09', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(20, 'GG788639', '2021-01-10', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(21, 'GG788639', '2021-01-11', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(22, 'GG788639', '2021-01-12', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(23, 'GG788639', '2021-01-13', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(24, 'GG788639', '2021-01-14', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(25, 'GG788639', '2021-01-15', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(26, 'GG788639', '2021-01-16', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(27, 'GG788639', '2021-01-17', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(28, 'GG788639', '2021-01-18', 10.00, 'Single Joining', 1, 1, '2021-01-18 11:33:19'),
(29, 'GG623918', '2021-01-18', 0.00, NULL, NULL, NULL, '2021-01-18 11:33:59'),
(30, 'GG623918', '2021-01-19', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(31, 'GG623918', '2021-01-20', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(32, 'GG623918', '2021-01-21', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(33, 'GG623918', '2021-01-22', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(34, 'GG623918', '2021-01-23', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(35, 'GG623918', '2021-01-24', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(36, 'GG623918', '2021-01-25', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(37, 'GG623918', '2021-01-26', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(38, 'GG623918', '2021-01-27', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(39, 'GG623918', '2021-01-28', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(40, 'GG623918', '2021-01-29', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(41, 'GG623918', '2021-01-30', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(42, 'GG623918', '2021-01-31', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(43, 'GG623918', '2021-02-01', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(44, 'GG623918', '2021-02-02', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(45, 'GG623918', '2021-02-03', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(46, 'GG623918', '2021-02-04', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(47, 'GG623918', '2021-02-05', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(48, 'GG623918', '2021-02-06', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(49, 'GG623918', '2021-02-07', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(50, 'GG623918', '2021-02-08', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(51, 'GG623918', '2021-02-09', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(52, 'GG623918', '2021-02-10', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(53, 'GG623918', '2021-02-11', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(54, 'GG623918', '2021-02-12', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(55, 'GG623918', '2021-02-13', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13'),
(56, 'GG623918', '2021-02-14', 10.00, 'Single Joining', 1, 1, '2021-02-14 07:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `directincome`
--

CREATE TABLE `directincome` (
  `id` int(12) NOT NULL,
  `user_id` varchar(200) NOT NULL,
  `income` double(8,2) DEFAULT NULL,
  `direct` int(12) NOT NULL DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `directincome`
--

INSERT INTO `directincome` (`id`, `user_id`, `income`, `direct`, `date`) VALUES
(2548, 'GG938574', 0.00, 0, '2020-10-21 16:20:04'),
(2549, 'GG788639', 0.00, 0, '2020-12-27 12:29:18'),
(2550, 'GG623918', 0.00, 0, '2021-01-18 11:34:29');

-- --------------------------------------------------------

--
-- Table structure for table `forget`
--

CREATE TABLE `forget` (
  `memberId` varchar(120) NOT NULL,
  `key` varchar(255) NOT NULL,
  `status` varchar(30) DEFAULT NULL,
  `created_at` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `id` int(100) NOT NULL,
  `tourid` varchar(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` double(9,2) NOT NULL,
  `start` varchar(150) NOT NULL,
  `end` varchar(150) NOT NULL,
  `img` varchar(200) NOT NULL,
  `url` varchar(150) NOT NULL,
  `del` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`, `tourid`, `name`, `price`, `start`, `end`, `img`, `url`, `del`) VALUES
(1218, '1597382327', 'Bubble Shoot', 100.00, '2020-08-14 10:48:15', '2020-08-14 11:48:15', '75b053ffbb571c9a26c159674b30a513.jpg', '', 0),
(1217, '1597382357', 'Jumper Frog', 100.00, '2020-08-14 10:48:49', '2020-08-14 11:49:59', '9b0966654ce00c72659be8c99a10c3af.jpg', '', 0),
(1220, '1613245230', 'Racing', 50.00, '2021-02-14 01:10:18', '2021-02-24 05:14:15', 'd9c93e96255d7596c38d45af17f8d5bf.png', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gamelogin`
--

CREATE TABLE `gamelogin` (
  `id` int(255) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `pass` varchar(200) DEFAULT NULL,
  `phone` varchar(11) NOT NULL,
  `pic` varchar(100) DEFAULT NULL,
  `wallet` double(9,2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gamelogin`
--

INSERT INTO `gamelogin` (`id`, `name`, `email`, `user`, `pass`, `phone`, `pic`, `wallet`, `status`) VALUES
(5046, 'Aakash', 'avdigietech@gmail.com', 'GG938574', '191d2404720882e9bba06a86728ff5b9', '9898989898', NULL, 0.00, 1),
(5047, 'Yash Gupta', 'yash121999@gmail.com', 'GG623918', '5f4dcc3b5aa765d61d8327deb882cf99', '8447441246', NULL, 0.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `kyccomplete`
--

CREATE TABLE `kyccomplete` (
  `id` int(255) NOT NULL,
  `userid` varchar(150) NOT NULL,
  `panno` varchar(12) NOT NULL,
  `panname` varchar(30) NOT NULL,
  `dob` varchar(150) NOT NULL,
  `panimg` varchar(255) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `created_at` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `dob` varchar(100) DEFAULT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `scracth` varchar(100) DEFAULT NULL,
  `pin` int(10) DEFAULT NULL,
  `side` varchar(100) NOT NULL,
  `sponser` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal` varchar(10) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `pass` varchar(255) NOT NULL,
  `time` varchar(12) NOT NULL,
  `agree-term` int(1) NOT NULL DEFAULT 0 COMMENT 'agree=0',
  `member_id` varchar(100) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT 'active=1 and inactive=0',
  `nominee_name` varchar(100) DEFAULT NULL,
  `nominee_reltion` varchar(100) DEFAULT NULL,
  `del` int(1) NOT NULL DEFAULT 0,
  `modify_date` varchar(100) NOT NULL,
  `activation` varchar(100) NOT NULL DEFAULT '00-00-000',
  `package_name` varchar(200) DEFAULT NULL,
  `package_amt` double(9,2) DEFAULT NULL,
  `daily_payout` int(1) NOT NULL DEFAULT 0,
  `direct_sp` varchar(200) DEFAULT NULL,
  `tableId` int(12) DEFAULT NULL,
  `timePeriod` varchar(255) DEFAULT NULL,
  `kyc` int(1) NOT NULL DEFAULT 0,
  `upgrade` int(1) NOT NULL DEFAULT 0,
  `appswitch` int(1) NOT NULL DEFAULT 0,
  `clear` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `name`, `father_name`, `dob`, `gender`, `scracth`, `pin`, `side`, `sponser`, `email`, `phone`, `state`, `city`, `postal`, `address`, `district`, `pass`, `time`, `agree-term`, `member_id`, `status`, `nominee_name`, `nominee_reltion`, `del`, `modify_date`, `activation`, `package_name`, `package_amt`, `daily_payout`, `direct_sp`, `tableId`, `timePeriod`, `kyc`, `upgrade`, `appswitch`, `clear`) VALUES
(1, 'Aakash', '', '2003-07-10', 'Male', '86424170', 78582, 'Left', 'GG058396', 'avdigietech@gmail.com', '9898989898', '', '', '', '', '', '191d2404720882e9bba06a86728ff5b9', '02-Jun-2020 ', 0, 'GG938574', 0, NULL, NULL, 0, '06 Jun, 2020 05:16 PM', '2020-10-21', 'joining', 10000.00, 0, 'GG058396', 1, '2021-08-17 09:53:38', 0, 1, 1, 0),
(10729, 'Abhay', NULL, NULL, NULL, '6535195999', 79253352, 'Right', 'GG938574', 'as271070@gmail.com', '8750908036', NULL, NULL, NULL, NULL, NULL, 'dc647eb65e6711e155375218212b3964', '27-Dec-2020', 0, 'GG788639', 0, NULL, NULL, 0, '', '2020-12-27', 'joining', 1000.00, 0, 'GG938574', 1, '2021-10-23 05:58:21', 0, 0, 0, 0),
(10730, 'Yash Gupta', NULL, NULL, NULL, '8410088337', 58620127, 'Right', 'GG788639', 'yash121999@gmail.com', '8447441246', NULL, NULL, NULL, NULL, NULL, '5f4dcc3b5aa765d61d8327deb882cf99', '18-Jan-2021', 0, 'GG623918', 0, NULL, NULL, 0, '', '2021-01-18', 'joining', 1000.00, 0, 'GG788639', 1, '2021-11-14 05:03:59', 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `makebinary`
--

CREATE TABLE `makebinary` (
  `id` int(255) NOT NULL,
  `parent` varchar(150) NOT NULL,
  `user_id` varchar(150) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `time` char(150) DEFAULT NULL,
  `activation` varchar(150) DEFAULT NULL,
  `p_name` varchar(150) DEFAULT NULL,
  `p_amt` double(12,2) DEFAULT NULL,
  `side` varchar(100) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `direct` varchar(200) DEFAULT NULL,
  `sponser` varchar(150) DEFAULT NULL,
  `teamside` varchar(10) NOT NULL,
  `created_at` varchar(150) DEFAULT NULL,
  `used` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(100) NOT NULL,
  `user` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `msg` varchar(200) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `st` int(1) NOT NULL DEFAULT 1,
  `refrence` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `user`, `type`, `msg`, `created_at`, `st`, `refrence`) VALUES
(2094, 'GG623918', 'Game Racing', 'you have play Racing game of 50.00 Rs. tournament id: #1613245230', '2021-02-14 01:14:18', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `id` int(12) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `price` double(9,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isActive` int(1) NOT NULL DEFAULT 0,
  `forAdmin` int(1) NOT NULL DEFAULT 0,
  `check` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`id`, `name`, `price`, `created_at`, `isActive`, `forAdmin`, `check`) VALUES
(3, 'joining', 1000.00, '2020-08-11 08:45:18', 0, 0, 3),
(4, 'joining', 2000.00, '2020-08-11 08:45:26', 0, 0, 4),
(5, 'joining', 3000.00, '2020-08-11 08:45:28', 0, 0, 5),
(6, 'joining', 4000.00, '2020-08-11 08:45:34', 0, 0, 6),
(7, 'joining', 5000.00, '2020-08-11 08:45:38', 0, 0, 7),
(8, 'joining', 6000.00, '2020-08-11 08:45:42', 0, 0, 8),
(9, 'joining', 7000.00, '2020-08-11 08:45:45', 0, 0, 9),
(10, 'joining', 8000.00, '2020-08-11 08:45:48', 0, 0, 10),
(25, 'joining', 9000.00, '2020-08-11 08:46:07', 0, 0, 11),
(42, 'joining', 10000.00, '2020-08-11 08:46:01', 0, 0, 12),
(49, 'joining', 1000000.00, '2020-08-11 08:46:45', 2, 0, 11),
(50, 'joining', 2500.00, '2020-08-11 08:47:09', 0, 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `reuest_scratch`
--

CREATE TABLE `reuest_scratch` (
  `id` int(255) NOT NULL,
  `txn` varchar(150) NOT NULL,
  `s_id` varchar(200) NOT NULL,
  `s_name` varchar(255) NOT NULL,
  `s_price` double(9,2) NOT NULL,
  `s_total_price` double(9,2) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'NOT DONE',
  `date` varchar(200) NOT NULL,
  `del` int(1) NOT NULL DEFAULT 0,
  `number` int(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reward`
--

CREATE TABLE `reward` (
  `id` int(10) NOT NULL,
  `name` varchar(150) NOT NULL,
  `point` int(80) NOT NULL,
  `opt` int(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reward`
--

INSERT INTO `reward` (`id`, `name`, `point`, `opt`) VALUES
(1, 'Thailand', 1000, 1),
(2, 'Dubai', 1500, 1),
(3, 'Bike', 2000, 1),
(4, 'Kwid Car', 5000, 1),
(5, 'Breeza', 15000, 1),
(6, 'Harrier', 25000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `scoreboard`
--

CREATE TABLE `scoreboard` (
  `id` int(200) NOT NULL,
  `gameRealId` varchar(150) NOT NULL,
  `game_id` varchar(300) NOT NULL,
  `user` varchar(200) NOT NULL,
  `score` int(200) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `scratch`
--

CREATE TABLE `scratch` (
  `pin` int(255) NOT NULL,
  `scratch` varchar(100) NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `usedORnot` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `del` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `single_leg`
--

CREATE TABLE `single_leg` (
  `id` int(12) NOT NULL,
  `rank` varchar(200) NOT NULL,
  `team` int(12) NOT NULL,
  `direct` int(12) NOT NULL,
  `amount` double(8,1) NOT NULL,
  `days` int(12) NOT NULL,
  `isActive` int(1) NOT NULL DEFAULT 0,
  `type` varchar(200) NOT NULL,
  `bvmake` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `single_leg`
--

INSERT INTO `single_leg` (`id`, `rank`, `team`, `direct`, `amount`, `days`, `isActive`, `type`, `bvmake`) VALUES
(1, 'Single Joining', 0, 0, 1.0, 300, 0, 'single', 0),
(2, 'Upgrade', 0, 0, 0.5, 300, 0, 'single', 0),
(15, 'Referral', 0, 2, 10.0, 0, 0, 'referral', 0),
(16, 'binary', 2, 0, 10.0, 0, 0, 'binary', 0),
(17, 'BV', 0, 0, 1000.0, 0, 0, 'BV', 1000),
(18, 'capping', 0, 0, 2.5, 0, 0, 'capping', 0);

-- --------------------------------------------------------

--
-- Table structure for table `site_setting`
--

CREATE TABLE `site_setting` (
  `id` int(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `favicon` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `meta_author` varchar(255) NOT NULL,
  `meta_desc` varchar(255) NOT NULL,
  `loader` varchar(255) NOT NULL,
  `default_user` varchar(500) NOT NULL,
  `default_cover` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_setting`
--

INSERT INTO `site_setting` (`id`, `title`, `favicon`, `logo`, `meta_keyword`, `meta_author`, `meta_desc`, `loader`, `default_user`, `default_cover`) VALUES
(1, 'jaiswal industeries | Play Game and Earn Real Money', 'e0addfde64fc4a4fbf5c848bcaabc283.png', 'logo.png', 'online games, gaming world, best games,play game to earn, earn money online, mlm,markiting,multi level markiting, daily winning gameing', 'Avdigietch', 'Smart Go Game - Play Simple Games & Win Cash Prizes & Real Money', '225770fcfce1596ab83aad9908e069b4.gif', 'e5695c12ea762d3cd434636d7a773cf3.png', 'bg-pattern.png');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(255) NOT NULL,
  `txn` bigint(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `t_amt` double(9,2) NOT NULL,
  `interest` double(9,2) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'pending',
  `method` varchar(100) DEFAULT NULL,
  `order` varchar(200) NOT NULL,
  `timestamp` varchar(200) NOT NULL,
  `gate` varchar(200) DEFAULT NULL,
  `dr_cr` varchar(10) DEFAULT NULL,
  `refrence` varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `txn`, `user`, `t_amt`, `interest`, `status`, `method`, `order`, `timestamp`, `gate`, `dr_cr`, `refrence`) VALUES
(1, 0, 'GG938574', 10000.00, 0.00, 'SUCCESS', 'Admin', 'Join Megacontest', '2020-10-21 09:53:38 PM', 'site', 'Dr', 'Admin'),
(2, 1613245458623918, 'GG623918', 50.00, 0.00, 'Done', 'GAME_PLAY', 'Game Racing', '2021-02-14 01:14:18 PM', 'app', 'Dr', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userbinary`
--

CREATE TABLE `userbinary` (
  `id` int(255) NOT NULL,
  `userid` varchar(200) NOT NULL,
  `amount` double(9,2) NOT NULL,
  `created_at` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id` int(20) NOT NULL,
  `user` varchar(200) NOT NULL,
  `amount` double(9,2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`id`, `user`, `amount`, `date`) VALUES
(10458, 'GG938574', 50.00, '2020-10-21 16:20:05'),
(10459, 'GG788639', 220.00, '2021-01-18 11:33:20'),
(10460, 'GG623918', 270.00, '2021-02-14 07:37:15');

-- --------------------------------------------------------

--
-- Table structure for table `winners`
--

CREATE TABLE `winners` (
  `id` int(100) NOT NULL,
  `user` varchar(200) NOT NULL,
  `tournament` varchar(200) DEFAULT NULL,
  `amount` double(9,2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `winners`
--

INSERT INTO `winners` (`id`, `user`, `tournament`, `amount`, `date`) VALUES
(11, 'GG167982', '1596768193', 500.00, '2020-08-07 14:46:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `binary`
--
ALTER TABLE `binary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bv`
--
ALTER TABLE `bv`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credential`
--
ALTER TABLE `credential`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_p`
--
ALTER TABLE `daily_p`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `directincome`
--
ALTER TABLE `directincome`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gamelogin`
--
ALTER TABLE `gamelogin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`);

--
-- Indexes for table `kyccomplete`
--
ALTER TABLE `kyccomplete`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `sponser` (`sponser`),
  ADD KEY `side` (`side`),
  ADD KEY `member_id_2` (`member_id`),
  ADD KEY `status` (`status`),
  ADD KEY `tableId` (`tableId`),
  ADD KEY `timePeriod` (`timePeriod`(191)),
  ADD KEY `direct_sp` (`direct_sp`(191)),
  ADD KEY `member_id_3` (`member_id`),
  ADD KEY `clear` (`clear`);

--
-- Indexes for table `makebinary`
--
ALTER TABLE `makebinary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`parent`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reuest_scratch`
--
ALTER TABLE `reuest_scratch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reward`
--
ALTER TABLE `reward`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scoreboard`
--
ALTER TABLE `scoreboard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scratch`
--
ALTER TABLE `scratch`
  ADD PRIMARY KEY (`pin`);

--
-- Indexes for table `single_leg`
--
ALTER TABLE `single_leg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_setting`
--
ALTER TABLE `site_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userbinary`
--
ALTER TABLE `userbinary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `winners`
--
ALTER TABLE `winners`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1134;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `binary`
--
ALTER TABLE `binary`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=435;

--
-- AUTO_INCREMENT for table `bv`
--
ALTER TABLE `bv`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2553;

--
-- AUTO_INCREMENT for table `credential`
--
ALTER TABLE `credential`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `daily_p`
--
ALTER TABLE `daily_p`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `directincome`
--
ALTER TABLE `directincome`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2551;

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1221;

--
-- AUTO_INCREMENT for table `gamelogin`
--
ALTER TABLE `gamelogin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5048;

--
-- AUTO_INCREMENT for table `kyccomplete`
--
ALTER TABLE `kyccomplete`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1583;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10731;

--
-- AUTO_INCREMENT for table `makebinary`
--
ALTER TABLE `makebinary`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=486094;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2095;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `reuest_scratch`
--
ALTER TABLE `reuest_scratch`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `reward`
--
ALTER TABLE `reward`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `scoreboard`
--
ALTER TABLE `scoreboard`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `scratch`
--
ALTER TABLE `scratch`
  MODIFY `pin` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=273;

--
-- AUTO_INCREMENT for table `single_leg`
--
ALTER TABLE `single_leg`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `site_setting`
--
ALTER TABLE `site_setting`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `userbinary`
--
ALTER TABLE `userbinary`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=435;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10461;

--
-- AUTO_INCREMENT for table `winners`
--
ALTER TABLE `winners`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
