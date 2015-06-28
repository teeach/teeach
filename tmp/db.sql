-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2015 a las 13:01:18
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `teeach`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_categories`
--

CREATE TABLE IF NOT EXISTS `pl_categories` (
`id` int(11) NOT NULL,
  `name` text NOT NULL,
  `h` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_groups`
--

CREATE TABLE IF NOT EXISTS `pl_groups` (
`id` int(11) NOT NULL,
  `name` varchar(39) NOT NULL,
  `h` varchar(18) NOT NULL,
  `category_h` varchar(99) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_groupuser`
--

CREATE TABLE IF NOT EXISTS `pl_groupuser` (
`id` int(11) NOT NULL,
  `group_h` text NOT NULL,
  `user_h` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_messages`
--

CREATE TABLE IF NOT EXISTS `pl_messages` (
`id` int(11) NOT NULL,
  `from_h` int(11) NOT NULL,
  `to_h` int(11) NOT NULL,
  `subject` varchar(99) CHARACTER SET utf8 NOT NULL,
  `body` longtext CHARACTER SET utf8 NOT NULL,
  `h` varchar(99) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_posts`
--

CREATE TABLE IF NOT EXISTS `pl_posts` (
`id` int(11) NOT NULL,
  `title` varchar(99) CHARACTER SET utf8 NOT NULL,
  `body` longtext CHARACTER SET utf8 NOT NULL,
  `h` varchar(99) CHARACTER SET utf8 NOT NULL,
  `author` varchar(99) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_settings`
--

CREATE TABLE IF NOT EXISTS `pl_settings` (
`id` int(11) NOT NULL,
  `property` varchar(99) CHARACTER SET utf8 NOT NULL,
  `value` varchar(99) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_units`
--

CREATE TABLE IF NOT EXISTS `pl_units` (
`id` int(11) NOT NULL,
  `name` text NOT NULL,
  `h` varchar(99) NOT NULL,
  `group_h` varchar(99) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_users`
--

CREATE TABLE IF NOT EXISTS `pl_users` (
`id` int(11) NOT NULL,
  `username` char(29) CHARACTER SET utf8 NOT NULL,
  `name` char(29) CHARACTER SET utf8 NOT NULL,
  `surname` char(29) CHARACTER SET utf8 NOT NULL,
  `email` char(40) CHARACTER SET utf8 NOT NULL,
  `phone` int(9) NOT NULL,
  `level` int(3) NOT NULL,
  `h` char(13) CHARACTER SET utf8 NOT NULL,
  `photo` text CHARACTER SET utf8 NOT NULL,
  `birthday` date NOT NULL,
  `home` char(29) CHARACTER SET utf8 NOT NULL,
  `pass` char(99) CHARACTER SET utf8 NOT NULL,
  `privilege` int(2) NOT NULL,
  `creation_date` datetime NOT NULL,
  `last_time` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_works`
--

CREATE TABLE IF NOT EXISTS `pl_works` (
`id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8 NOT NULL,
  `h` varchar(99) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `type` int(1) NOT NULL,
  `creation_date` datetime NOT NULL,
  `group_h` varchar(99) CHARACTER SET utf8 NOT NULL,
  `unit` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pl_categories`
--
ALTER TABLE `pl_categories`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pl_groups`
--
ALTER TABLE `pl_groups`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pl_groupuser`
--
ALTER TABLE `pl_groupuser`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pl_messages`
--
ALTER TABLE `pl_messages`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pl_posts`
--
ALTER TABLE `pl_posts`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pl_settings`
--
ALTER TABLE `pl_settings`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pl_units`
--
ALTER TABLE `pl_units`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pl_users`
--
ALTER TABLE `pl_users`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pl_works`
--
ALTER TABLE `pl_works`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pl_categories`
--
ALTER TABLE `pl_categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pl_groups`
--
ALTER TABLE `pl_groups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `pl_groupuser`
--
ALTER TABLE `pl_groupuser`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `pl_messages`
--
ALTER TABLE `pl_messages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `pl_posts`
--
ALTER TABLE `pl_posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `pl_settings`
--
ALTER TABLE `pl_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `pl_units`
--
ALTER TABLE `pl_units`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `pl_users`
--
ALTER TABLE `pl_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `pl_works`
--
ALTER TABLE `pl_works`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
