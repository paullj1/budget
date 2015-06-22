-- MySQL dump 10.13  Distrib 5.6.24, for Linux (x86_64)
--
-- Current Database: `budget`
--

CREATE DATABASE IF NOT EXISTS `budget` DEFAULT CHARACTER SET utf8;
USE `budget`;

DROP TABLE IF EXISTS `budget`;
CREATE TABLE `budget`(
  id       int AUTO_INCREMENT,
  month    int NOT NULL,
  year     int NOT NULL,
  category int NOT NULL,
  amount   float NOT NULL,
  note     char(255) CHARACTER SET utf8 COLLATE utf8_bin, 
	CHECK(month > 0),
	CHECK(month < 13), 
	CHECK(year > 2012),
 	CHECK(amount > 0),
	PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Primary budget table';

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`(
  `id`			 int AUTO_INCREMENT,
  `category` char(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `goal`     float NOT NULL,
  `visible`  int NOT NULL,
	CHECK(goal > 0),
	CHECK(visible >= 0),
	CHECK(visible <= 1),
	PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Categories with associated monthly goals';

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`(
  `id`       int AUTO_INCREMENT,
  `email`		 char(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users';

