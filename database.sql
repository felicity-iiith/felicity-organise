-- phpMyAdmin SQL Dump
-- version 4.6.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 23, 2015 at 02:51 AM
-- Server version: 5.5.44-0ubuntu0.14.04.1-log
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `feli_organise`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `type` enum('directory','file') NOT NULL,
  `data` blob NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueName` (`slug`,`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `name`, `slug`, `parent`, `type`, `data`, `timestamp`, `created_by`) VALUES
(0, 'Felicity ''16', '', -1, 'directory', '', '2015-09-22 21:18:01', '');
