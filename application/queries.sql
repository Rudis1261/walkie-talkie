CREATE DATABASE kohana;
USE kohana;
GRANT USAGE ON kohana.* TO convo@localhost IDENTIFIED BY 'convo';
GRANT ALL PRIVILEGES ON kohana.* TO convo@localhost;

CREATE TABLE `comments` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `parent` int(15) unsigned NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL
) COMMENT='' ENGINE='InnoDB';