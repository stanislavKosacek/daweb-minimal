CREATE TABLE `lesson` (
						  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
						  `type` varchar(255) NOT NULL,
						  `page` int(11) NOT NULL,
						  `added` datetime NOT NULL,
						  `date_start` datetime NULL,
						  `date_end` datetime NULL,
						  FOREIGN KEY (`page`) REFERENCES `page` (`id`)
) COLLATE 'utf8mb4_unicode_ci';

CREATE TABLE `lesson_file` (
							   `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
							   `filename` varchar(255) NOT NULL,
							   `name` varchar(255) NULL,
							   `description` varchar(255) NULL,
							   `added` datetime NOT NULL,
							   `lesson` int(11) NOT NULL,
							   FOREIGN KEY (`lesson`) REFERENCES `lesson` (`id`)
) COLLATE 'utf8mb4_unicode_ci';


CREATE TABLE `lesson_team_role` (
									`id` int NOT NULL,
									`type` varchar(255) NOT NULL,
									`user` int(11) NOT NULL,
									`lesson` int(11) NOT NULL,
									FOREIGN KEY (`user`) REFERENCES `user` (`id`),
									FOREIGN KEY (`lesson`) REFERENCES `lesson` (`id`)
);

CREATE TABLE `homework_assignment` (
									   `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
									   `page` int(11) NOT NULL,
									   `git_folder` varchar(255) NULL,
									   `lesson` int(11) NOT NULL,
									   `added` datetime NOT NULL,
									   FOREIGN KEY (`page`) REFERENCES `page` (`id`),
									   FOREIGN KEY (`lesson`) REFERENCES `lesson` (`id`)
) COLLATE 'utf8mb4_unicode_ci';


CREATE TABLE `homework_solution` (
									 `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
									 `note` varchar(255) NULL,
									 `user` int(11) NOT NULL,
									 `homework_assignment` int(11) NOT NULL,
									 `state` varchar(255) NOT NULL,
									 `added` datetime NOT NULL,
									 `edited` datetime NULL,
									 FOREIGN KEY (`user`) REFERENCES `user` (`id`),
									 FOREIGN KEY (`homework_assignment`) REFERENCES `homework_assignment` (`id`)
);
