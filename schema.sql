CREATE DATABASE `yeticave` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE `yeticave`;

CREATE TABLE `user` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`date_registration` DATETIME,
	`email` CHAR(255),
	`name` CHAR(255),
	`password` CHAR(255),
	`avatar_img` CHAR(255),
	`contacts` TEXT,
	`created_lots` TEXT,
	`lots` TEXT
);

CREATE TABLE `wager` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`date` DATETIME,
	`price` INT,
	`author_id` INT,
	`lot_id` INT
);

CREATE TABLE `lot` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`date_create` DATETIME,
	`name` CHAR(255),
	`description` TEXT,
	`img` CHAR(255),
	`start_price` INT,
	`date_end` DATETIME,
	`bid_step` INT,
	`author_id` INT,
	`winner_id` INT,
	`category_id` INT
);

CREATE TABLE `category` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`name` CHAR(255),
	`code` CHAR(255)
);
