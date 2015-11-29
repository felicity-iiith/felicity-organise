-- phpMyAdmin SQL Dump
-- version 4.5.2-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 29, 2015 at 10:50 PM
-- Server version: 5.5.46-0ubuntu0.14.04.2-log
-- PHP Version: 5.5.9-1ubuntu4.14

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
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `slug` varchar(255) COLLATE utf8_bin NOT NULL,
  `parent` int(11) NOT NULL,
  `type` enum('directory','file') COLLATE utf8_bin NOT NULL,
  `default_role` enum('none','reader','author','admin') COLLATE utf8_bin NOT NULL DEFAULT 'reader',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueName` (`slug`,`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `name`, `slug`, `parent`, `type`, `default_role`) VALUES
(0, 'Felicity ʼ16', '', -1, 'directory', 'reader');

-- --------------------------------------------------------

--
-- Table structure for table `file_data`
--

CREATE TABLE IF NOT EXISTS `file_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `action` enum('create','edit','delete','recover') COLLATE utf8_bin NOT NULL,
  `data` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(255) COLLATE utf8_bin NOT NULL,
  `comment` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `file_permissions`
--

CREATE TABLE IF NOT EXISTS `file_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `user` varchar(255) COLLATE utf8_bin NOT NULL,
  `role` enum('none','reader','author','admin') COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueUser` (`file_id`,`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `trash_files`
--

CREATE TABLE IF NOT EXISTS `trash_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `slug` varchar(255) COLLATE utf8_bin NOT NULL,
  `parent` int(11) NOT NULL,
  `type` enum('directory','file') COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_id` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
