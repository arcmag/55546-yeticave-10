SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `yeticave` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `yeticave`;

CREATE TABLE `category` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` char(255) DEFAULT NULL,
  `code` char(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `lot` (
  `id` int(11) UNSIGNED NOT NULL,
  `date_create` datetime DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `description` text,
  `img` char(255) DEFAULT NULL,
  `start_price` int(11) DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `bid_step` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `winner_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `date_registration` datetime DEFAULT NULL,
  `email` char(255) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `password` char(255) DEFAULT NULL,
  `avatar_img` char(255) DEFAULT NULL,
  `contacts` text,
  `created_lots` text,
  `lots` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wager` (
  `id` int(11) UNSIGNED NOT NULL,
  `date` datetime DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `lot_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `lot`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `lot` ADD FULLTEXT KEY `lot_if_search` (`name`,`description`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wager`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

ALTER TABLE `lot`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

ALTER TABLE `wager`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
COMMIT;
