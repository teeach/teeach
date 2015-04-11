SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `pl_config` (
`id` int(11) NOT NULL,
  `property` varchar(49) NOT NULL,
  `value` varchar(49) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pl_groups` (
`id` int(11) NOT NULL,
  `name` varchar(39) NOT NULL,
  `h` varchar(18) NOT NULL,
  `level` varchar(28) NOT NULL,
  `monday` varchar(99) NOT NULL,
  `tuesday` varchar(99) NOT NULL,
  `wednesday` varchar(99) NOT NULL,
  `thursday` varchar(99) NOT NULL,
  `friday` varchar(99) NOT NULL,
  `saturday` varchar(99) NOT NULL,
  `sunday` varchar(99) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pl_groupuser` (
`id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pl_hours` (
`id` int(11) NOT NULL,
  `name` varchar(29) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pl_levels` (
`id` int(11) NOT NULL,
  `name` varchar(29) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pl_posts` (
`id` int(11) NOT NULL,
  `title` varchar(99) NOT NULL,
  `body` longtext NOT NULL,
  `h` varchar(99) NOT NULL,
  `author` varchar(99) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pl_subjects` (
`id` int(11) NOT NULL,
  `name` varchar(59) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pl_users` (
`id` int(11) NOT NULL,
  `username` char(29) NOT NULL,
  `name` char(29) NOT NULL,
  `subname1` char(29) NOT NULL,
  `subname2` char(29) NOT NULL,
  `email` char(40) NOT NULL,
  `phone` int(9) NOT NULL,
  `level` int(3) NOT NULL,
  `h` char(13) NOT NULL,
  `photo` varchar(99) NOT NULL,
  `birthday` date NOT NULL,
  `home` char(29) NOT NULL,
  `pass` char(99) NOT NULL,
  `privilege` int(2) NOT NULL,
  `group` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;


ALTER TABLE `pl_config`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_groups`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_groupuser`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_hours`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_levels`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_posts`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_subjects`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_users`
 ADD PRIMARY KEY (`id`);


ALTER TABLE `pl_config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `pl_groups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `pl_groupuser`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `pl_hours`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pl_levels`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pl_posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
ALTER TABLE `pl_subjects`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
ALTER TABLE `pl_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
