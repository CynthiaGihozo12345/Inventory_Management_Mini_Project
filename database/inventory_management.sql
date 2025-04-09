-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2025 at 01:46 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(2, 'admin', '123');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `PRODUCT_ID` int(11) NOT NULL,
  `PRODUCT_NAME` varchar(20) NOT NULL,
  `DATE_RECORDED` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`PRODUCT_ID`, `PRODUCT_NAME`, `DATE_RECORDED`) VALUES
(10, 'computer', '2025-03-11 00:42:33');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `STOCK_ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`STOCK_ID`, `PRODUCT_ID`, `QUANTITY`) VALUES
(1, 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stockin`
--

CREATE TABLE `stockin` (
  `STOCKIN_ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL,
  `PRICE` int(11) DEFAULT NULL,
  `DATE` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stockin`
--

INSERT INTO `stockin` (`STOCKIN_ID`, `PRODUCT_ID`, `QUANTITY`, `PRICE`, `DATE`) VALUES
(1, 10, 5, 200000, '2025-03-11 00:42:57'),
(2, 10, 7, 9000000, '2025-03-11 00:43:10');

-- --------------------------------------------------------

--
-- Table structure for table `stockout`
--

CREATE TABLE `stockout` (
  `STOCKOUT_ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL,
  `PRICE` int(11) DEFAULT NULL,
  `DATE` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stockout`
--

INSERT INTO `stockout` (`STOCKOUT_ID`, `PRODUCT_ID`, `QUANTITY`, `PRICE`, `DATE`) VALUES
(1, 10, 9, 232, '2025-03-11 00:44:42'),
(2, 10, 1, 233, '2025-03-11 00:45:06');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `USER_ID` int(11) NOT NULL,
  `USER_NAME` varchar(20) NOT NULL,
  `USER_EMAIL` varchar(30) NOT NULL,
  `PASSWORD` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`PRODUCT_ID`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`STOCK_ID`),
  ADD KEY `PRODUCT_ID` (`PRODUCT_ID`);

--
-- Indexes for table `stockin`
--
ALTER TABLE `stockin`
  ADD PRIMARY KEY (`STOCKIN_ID`),
  ADD KEY `PRODUCT_ID` (`PRODUCT_ID`);

--
-- Indexes for table `stockout`
--
ALTER TABLE `stockout`
  ADD PRIMARY KEY (`STOCKOUT_ID`),
  ADD KEY `PRODUCT_ID` (`PRODUCT_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USER_ID`),
  ADD UNIQUE KEY `USER_EMAIL` (`USER_EMAIL`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `PRODUCT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `STOCK_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stockin`
--
ALTER TABLE `stockin`
  MODIFY `STOCKIN_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stockout`
--
ALTER TABLE `stockout`
  MODIFY `STOCKOUT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `products` (`PRODUCT_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stockin`
--
ALTER TABLE `stockin`
  ADD CONSTRAINT `stockin_ibfk_1` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `products` (`PRODUCT_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stockout`
--
ALTER TABLE `stockout`
  ADD CONSTRAINT `stockout_ibfk_1` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `products` (`PRODUCT_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
