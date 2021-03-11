-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `code_share`;
CREATE TABLE `code_share` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
							  `language` varchar(255) DEFAULT NULL,
							  `text_code` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
							  `note` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
							  `added` datetime NOT NULL,
							  `user` int(11) DEFAULT NULL,
							  PRIMARY KEY (`id`),
							  KEY `user` (`user`),
							  KEY `language` (`language`),
							  CONSTRAINT `code_share_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `email`;
CREATE TABLE `email` (
						 `id` int(11) NOT NULL AUTO_INCREMENT,
						 `from` varchar(255) NOT NULL,
						 `to` varchar(255) NOT NULL,
						 `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
						 `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
						 `locale` varchar(255) NOT NULL,
						 `error` tinyint(4) NOT NULL,
						 `error_message` varchar(255) DEFAULT NULL,
						 `added` datetime NOT NULL,
						 `sent` datetime DEFAULT NULL,
						 `again` datetime DEFAULT NULL,
						 `email_type` varchar(255) NOT NULL,
						 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
							`id` int(11) NOT NULL AUTO_INCREMENT,
							`name` varchar(255) NOT NULL,
							`code` varchar(255) NOT NULL,
							`default` tinyint(4) NOT NULL,
							PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `language` (`id`, `name`, `code`, `default`) VALUES
(1,	'czech',	'cs',	1);

DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
						`added` datetime NOT NULL,
						`published` datetime DEFAULT NULL,
						`target` int(11) DEFAULT NULL,
						PRIMARY KEY (`id`),
						KEY `target` (`target`),
						CONSTRAINT `page_ibfk_1` FOREIGN KEY (`target`) REFERENCES `target` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `page_block`;
CREATE TABLE `page_block` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
							  `related_id` int(11) DEFAULT NULL,
							  `priority` int(11) NOT NULL,
							  `page` int(11) NOT NULL,
							  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
							  PRIMARY KEY (`id`),
							  KEY `page` (`page`),
							  CONSTRAINT `page_block_ibfk_1` FOREIGN KEY (`page`) REFERENCES `page` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `redirect`;
CREATE TABLE `redirect` (
							`id` int(11) NOT NULL AUTO_INCREMENT,
							`from` varchar(255) NOT NULL,
							`to` varchar(255) NOT NULL,
							PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`name` varchar(255) NOT NULL,
						`name_cs` varchar(255) NOT NULL,
						PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `role` (`id`, `name`, `name_cs`) VALUES
(1,	'admin',	'Administrátor');

DROP TABLE IF EXISTS `target`;
CREATE TABLE `target` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `presenter` varchar(255) NOT NULL,
						  `action` varchar(255) DEFAULT NULL,
						  `slug` varchar(255) DEFAULT NULL,
						  `param_name` varchar(255) DEFAULT NULL,
						  `param_value` varchar(255) DEFAULT NULL,
						  `locale` varchar(255) DEFAULT NULL,
						  `title` varchar(255) DEFAULT NULL,
						  `description` varchar(255) DEFAULT NULL,
						  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
						`surname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
						`date_birth` datetime DEFAULT NULL,
						`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
						`password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
						`username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
						`phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
						`note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
						`image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
						`last_login` datetime DEFAULT NULL,
						`last_request` datetime DEFAULT NULL,
						`added` datetime NOT NULL,
						`edited` datetime DEFAULT NULL,
						`deleted` datetime DEFAULT NULL,
						`forgotten_password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
						`default_locale` int(11) DEFAULT NULL,
						PRIMARY KEY (`id`),
						KEY `default_locale` (`default_locale`),
						CONSTRAINT `user_ibfk_1` FOREIGN KEY (`default_locale`) REFERENCES `language` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `user_x_role`;
CREATE TABLE `user_x_role` (
							   `user_id` int(11) NOT NULL,
							   `role_id` int(11) NOT NULL,
							   PRIMARY KEY (`user_id`,`role_id`),
							   KEY `role_id` (`role_id`),
							   CONSTRAINT `user_x_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
							   CONSTRAINT `user_x_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `web_image`;
CREATE TABLE `web_image` (
							 `id` int(11) NOT NULL AUTO_INCREMENT,
							 `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
							 `alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
							 `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
							 `render_width` int(11) DEFAULT NULL,
							 `width` int(11) DEFAULT NULL,
							 `height` int(11) DEFAULT NULL,
							 `content_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
							 `size` int(11) DEFAULT NULL,
							 `added` datetime NOT NULL,
							 `edited` datetime DEFAULT NULL,
							 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2021-02-12 14:17:14
