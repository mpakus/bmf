-- phpMyAdmin SQL Dump
-- version 3.4.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 31, 2012 at 01:26 AM
-- Server version: 5.1.62
-- PHP Version: 5.3.5-1ubuntu7.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bmf`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL,
  `post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `added_at` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`,`user_id`,`deleted`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mails`
--

CREATE TABLE IF NOT EXISTS `mails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `added_at` datetime NOT NULL,
  `email` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mails`
--

INSERT INTO `mails` (`id`, `added_at`, `email`) VALUES
(1, '2012-05-08 17:58:49', 'mrak69@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `post_id` int(10) NOT NULL,
  `ord` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `post_id`, `ord`, `name`) VALUES
(4, 1, 2, 'text'),
(5, 1, 3, 'code'),
(6, 1, 4, 'code'),
(7, 2, 1, 'text'),
(8, 3, 1, 'text'),
(9, 3, 2, 'code');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `added_at` datetime NOT NULL,
  `user_id` int(10) NOT NULL,
  `type` int(2) NOT NULL,
  `category_id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `rating` int(4) NOT NULL,
  `views` int(4) NOT NULL,
  `preview` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `added_at`, `user_id`, `type`, `category_id`, `title`, `tags`, `rating`, `views`, `preview`, `link`, `deleted`) VALUES
(1, '2012-05-19 01:16:01', 4, 1, 0, 'Первый пост в супер блоге', 'пост, блог, сообщение', 0, 0, '', '', 0),
(2, '2012-05-28 16:12:58', 4, 1, 0, 'feefeerwe', 'rwerwerwer', 0, 0, '', '', 0),
(3, '2012-05-30 13:40:54', 4, 1, 0, 'Супер топик', 'топик, хуй', 0, 0, '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tagged_objects`
--

CREATE TABLE IF NOT EXISTS `tagged_objects` (
  `tag_id` int(10) NOT NULL,
  `object_id` int(10) NOT NULL,
  `object_type` int(2) NOT NULL,
  KEY `object_id` (`object_id`,`object_type`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tagged_objects`
--

INSERT INTO `tagged_objects` (`tag_id`, `object_id`, `object_type`) VALUES
(1, 847, 1),
(2, 847, 1),
(3, 1, 1),
(4, 1, 1),
(5, 1, 1),
(6, 2, 1),
(7, 3, 1),
(8, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag`) VALUES
(6, 'rwerwerwer'),
(4, 'блог'),
(3, 'пост'),
(5, 'сообщение'),
(1, 'тема'),
(7, 'топик'),
(2, 'уно'),
(8, 'хуй');

-- --------------------------------------------------------

--
-- Table structure for table `texts`
--

CREATE TABLE IF NOT EXISTS `texts` (
  `module_id` int(10) NOT NULL,
  `short` text NOT NULL,
  `full` text NOT NULL,
  `original` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `texts`
--

INSERT INTO `texts` (`module_id`, `short`, `full`, `original`) VALUES
(8, '', 'аывавыа ыавыа', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `registered_at` datetime NOT NULL,
  `logined_at` datetime NOT NULL,
  `login` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `avatar` varchar(128) NOT NULL,
  `rating` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(16) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `registered_at`, `logined_at`, `login`, `email`, `password`, `salt`, `avatar`, `rating`, `banned`, `role`) VALUES
(1, '2012-04-17 21:07:00', '2012-05-09 21:44:55', 'MpaK', 'mpak_work@inbox.ru', '864ec5bef5768c4252ecd320ec4228478c2fd849', 'f6e09db4f6e0', '858de61cecafe0d532003deedf143781.jpg', 0, 0, 'user'),
(2, '2012-04-17 21:39:21', '0000-00-00 00:00:00', 'MpaKus', 'mrak69@gmail.com', '6e736b8f4a8fc3e6c60261c822aaa106412fe420', 'a4d940f6279b', '', 0, 0, 'user'),
(4, '2012-04-17 21:51:59', '2012-05-30 23:40:01', 'Admin', 'mrak69@gmail.com', '04b23fa8f205a446303e020252b7391323738ea7', '5a3b07273666', '72a13bcacac59c2fdfe6c650e2d1c1fb.gif', 0, 0, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `added_at` datetime NOT NULL,
  `post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `weight` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
