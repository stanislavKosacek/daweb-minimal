-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `comment` (
						   `id` int(11) NOT NULL AUTO_INCREMENT,
						   `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
						   `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
						   `user` int(11) NOT NULL,
						   `related_id` int(11) NOT NULL,
						   `added` datetime NOT NULL,
						   `edited` int(11) DEFAULT NULL,
						   PRIMARY KEY (`id`),
						   KEY `user` (`user`),
						   KEY `edited` (`edited`),
						   KEY `related_id` (`related_id`),
						   CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`),
						   CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`edited`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2021-02-13 00:41:39
