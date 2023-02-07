-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 23, 2023 at 10:59 PM
-- Server version: 5.6.51-cll-lve
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `open_table`
--

-- --------------------------------------------------------

--
-- Table structure for table `shopify_recommend`
--

CREATE TABLE `shopify_recommend` (
  `id` int(11) NOT NULL,
  `shopname` varchar(255) NOT NULL,
  `shopGuid` varchar(255) NOT NULL,
  `shop_domain` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shopify_recommend`
--

INSERT INTO `shopify_recommend` (`id`, `shopname`, `shopGuid`, `shop_domain`, `token`, `create_date`, `modified_date`) VALUES
(1, 'recommendit-dev.myshopify.com', 'ee537a37-d4d2-4b74-af0f-945ae9be364e', 'recommendit-dev.myshopify.com', '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', '2023-01-09 11:06:54', '2023-01-09 11:06:54'),
(3, 'darms-inc-development.myshopify.com', '5ce20f8b-b834-4d2f-bf45-52fcbea7bf27', 'darms-inc-development.myshopify.com', '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', '2023-01-18 07:23:44', '2023-01-18 07:23:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shopify_recommend`
--
ALTER TABLE `shopify_recommend`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shopify_recommend`
--
ALTER TABLE `shopify_recommend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
