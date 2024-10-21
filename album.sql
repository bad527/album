-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3306
-- 產生時間： 2024-10-21 14:52:58
-- 伺服器版本： 8.3.0
-- PHP 版本： 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `album`
--

-- --------------------------------------------------------

--
-- 資料表結構 `album`
--

DROP TABLE IF EXISTS `album`;
CREATE TABLE IF NOT EXISTS `album` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(240) NOT NULL,
  `owner` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- 傾印資料表的資料 `album`
--

INSERT INTO `album` (`id`, `name`, `owner`) VALUES
(1, '王大頭', 'dk321155');

-- --------------------------------------------------------

--
-- 資料表結構 `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE IF NOT EXISTS `photo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(66) NOT NULL,
  `filename` varchar(66) NOT NULL,
  `comment` varchar(540) NOT NULL,
  `album_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;

--
-- 傾印資料表的資料 `photo`
--

INSERT INTO `photo` (`id`, `name`, `filename`, `comment`, `album_id`) VALUES
(8, 'Dumplings2.png', '67165cf11f122.jpg', '', 1),
(7, 'Dumplings.png', '67165cf10ed38.jpg', '', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `account` varchar(36) NOT NULL,
  `password` varchar(36) NOT NULL,
  `name` varchar(36) NOT NULL,
  PRIMARY KEY (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`account`, `password`, `name`) VALUES
('dk321155', 'sprt675m', '王大明');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
