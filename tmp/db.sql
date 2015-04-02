SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE TABLE IF NOT EXISTS `pl_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property` varchar(49) NOT NULL,
  `value` varchar(49) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;
CREATE TABLE IF NOT EXISTS `pl_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(39) NOT NULL,
  `h` varchar(18) NOT NULL,
  `level` int(3) NOT NULL,
  `monday` varchar(99) NOT NULL,
  `tuesday` varchar(99) NOT NULL,
  `wednesday` varchar(99) NOT NULL,
  `thursday` varchar(99) NOT NULL,
  `friday` varchar(99) NOT NULL,
  `saturday` varchar(99) NOT NULL,
  `sunday` varchar(99) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
CREATE TABLE IF NOT EXISTS `pl_hours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(29) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;
CREATE TABLE IF NOT EXISTS `pl_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(29) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `pl_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(59) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;
CREATE TABLE IF NOT EXISTS `pl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `group` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;