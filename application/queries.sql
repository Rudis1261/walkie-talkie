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

TRUNCATE `comments`;
INSERT INTO `comments` (`id`, `parent`, `first_name`, `email`, `comment`, `timestamp`, `active`) VALUES
(1, 0,  'Ygritte',  'Ygritte@got.com',  'You know nothing, Jon Snow',   '1404588236',   1),
(2, 1,  'Jon Snow', 'jon@got.com',  'I know enough',    '1404588276',   1),
(3, 0,  'Eddard Stark', 'eddard@got.com',   'The man who passes the sentence should swing the sword',   '1404588416',   1),
(4, 0,  'TyrionL',  'Tyrion@lanister.com',  'A mind needs books as a sword needs a whetstone, if it is to keep its edge.',  '1404588566',   1),
(5, 3,  'TyrionL',  'Tyrion@lanister.com',  'Hahaha, allot of good that did you Ned.',  '1404588619',   1),
(6, 5,  'Cersei',   'cersei@lannister.com', 'Good one. Brother, we may still see eye to eye someday.',  '1404588694',   1),
(7, 0,  'TyrionL',  'Tyrion@lannister.com', 'Never forget what you are, for surely the world will not. Make it your strength. Then it can never be your weakness. Armour yourself in it, and it will never be used to hurt you.',   '1404588750',   1),
(8, 5,  'Eddard Stark', 'eddard@got.com',   'Winter is coming.',    '1404588885',   1);

-- 2014-07-05 20:02:23

-- Create the user convo to be able to access the DB
USE convo;
GRANT USAGE ON convo.* TO convo@localhost IDENTIFIED BY 'convo';
GRANT ALL PRIVILEGES ON convo.* TO convo@localhost;