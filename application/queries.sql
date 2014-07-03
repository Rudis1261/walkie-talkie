CREATE DATABASE kohana;
USE kohana;
GRANT USAGE ON kohana.* TO walkie@localhost IDENTIFIED BY 'talkie';
GRANT ALL PRIVILEGES ON kohana.* TO walkie@localhost;

CREATE TABLE `comments` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `parent` int(15) unsigned NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL
) COMMENT='' ENGINE='InnoDB';