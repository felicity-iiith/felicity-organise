-- phpMyAdmin SQL Dump
-- version 4.6.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 20, 2015 at 07:14 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1-log
-- PHP Version: 5.5.9-1ubuntu4.13

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
  `default_role` enum('none','reader','author','admin') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueName` (`slug`,`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `name`, `slug`, `parent`, `type`, `created_by`) VALUES
(0, 'Felicity ''16', '', -1, 'directory', '');
-- --------------------------------------------------------

--
-- Table structure for table `file_data`
--

CREATE TABLE IF NOT EXISTS `file_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `action` enum('create','edit','delete','recover') NOT NULL,
  `data` blob NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `file_permissions`
--

CREATE TABLE IF NOT EXISTS `file_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `role` enum('none','reader','author','admin') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueUser` (`file_id`,`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trash_files`
--

CREATE TABLE IF NOT EXISTS `trash_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `type` enum('directory','file') NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_id` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
