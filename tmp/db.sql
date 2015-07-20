SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `pl_categories` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `h` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pl_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(39) NOT NULL,
  `h` varchar(18) NOT NULL,
  `category_h` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pl_groupuser` (
  `id` int(11) NOT NULL,
  `group_h` text NOT NULL,
  `user_h` text NOT NULL,
  `status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pl_messages` (
  `id` int(11) NOT NULL,
  `from_h` varchar(99) CHARACTER SET utf8 NOT NULL,
  `to_h` varchar(99) CHARACTER SET utf8 NOT NULL,
  `subject` varchar(99) CHARACTER SET utf8 NOT NULL,
  `body` longtext CHARACTER SET utf8 NOT NULL,
  `h` varchar(99) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pl_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(99) CHARACTER SET utf8 NOT NULL,
  `body` longtext CHARACTER SET utf8 NOT NULL,
  `h` varchar(99) CHARACTER SET utf8 NOT NULL,
  `author` varchar(99) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pl_settings` (
  `id` int(11) NOT NULL,
  `property` varchar(99) CHARACTER SET utf8 NOT NULL,
  `value` varchar(99) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pl_units` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `h` varchar(99) NOT NULL,
  `group_h` varchar(99) NOT NULL,
  `status` varchar(25) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pl_users` (
  `id` int(11) NOT NULL,
  `username` char(29) CHARACTER SET utf8 NOT NULL,
  `name` char(29) CHARACTER SET utf8 NOT NULL,
  `surname` char(29) CHARACTER SET utf8 NOT NULL,
  `email` char(40) CHARACTER SET utf8 NOT NULL,
  `address` text CHARACTER SET utf8 NOT NULL,
  `phone` int(9) NOT NULL,
  `level` int(3) NOT NULL,
  `h` char(13) CHARACTER SET utf8 NOT NULL,
  `lang` varchar(5) CHARACTER SET utf8 NOT NULL,
  `photo` text CHARACTER SET utf8 NOT NULL,
  `birthday` date NOT NULL,
  `home` char(29) CHARACTER SET utf8 NOT NULL,
  `pass` char(99) CHARACTER SET utf8 NOT NULL,
  `privilege` int(2) NOT NULL,
  `creation_date` datetime NOT NULL,
  `last_time` datetime NOT NULL,
  `status` varchar(25) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pl_works` (
  `id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8 NOT NULL,
  `h` varchar(99) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `type` int(1) NOT NULL,
  `creation_date` datetime NOT NULL,
  `group_h` varchar(99) CHARACTER SET utf8 NOT NULL,
  `unit_h` varchar(99) CHARACTER SET utf8 NOT NULL,
  `status` varchar(25) CHARACTER SET utf8 NOT NULL,
  'attachment' text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_groups`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_groupuser`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_posts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_units`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_works`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_groups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_groupuser`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_messages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_units`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_works`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;