-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `convo`;
CREATE DATABASE `convo` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `convo`;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(15) unsigned NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `timestamp` varchar(65) NOT NULL,
  `active` int(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2014-07-05 09:46:20

-- Create the user convo to be able to access the DB
USE convo;
GRANT USAGE ON convo.* TO convo@localhost IDENTIFIED BY 'convo';
GRANT ALL PRIVILEGES ON convo.* TO convo@localhost;