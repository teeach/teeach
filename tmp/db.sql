SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_groups`
--

CREATE TABLE IF NOT EXISTS `pl_groups` (
`id` int(11) NOT NULL,
  `name` varchar(39) CHARACTER SET latin1 NOT NULL,
  `h` varchar(18) CHARACTER SET latin1 NOT NULL,
  `level` varchar(28) CHARACTER SET latin1 NOT NULL,
  `monday` varchar(99) CHARACTER SET latin1 NOT NULL,
  `tuesday` varchar(99) CHARACTER SET latin1 NOT NULL,
  `wednesday` varchar(99) CHARACTER SET latin1 NOT NULL,
  `thursday` varchar(99) CHARACTER SET latin1 NOT NULL,
  `friday` varchar(99) CHARACTER SET latin1 NOT NULL,
  `saturday` varchar(99) CHARACTER SET latin1 NOT NULL,
  `sunday` varchar(99) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_groupuser`
--

CREATE TABLE IF NOT EXISTS `pl_groupuser` (
`id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_messages`
--

CREATE TABLE IF NOT EXISTS `pl_messages` (
`id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `subject` varchar(99) NOT NULL,
  `body` longtext NOT NULL,
  `h` varchar(99) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_posts`
--

CREATE TABLE IF NOT EXISTS `pl_posts` (
`id` int(11) NOT NULL,
  `title` varchar(99) NOT NULL,
  `body` longtext NOT NULL,
  `h` varchar(99) NOT NULL,
  `author` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_settings`
--

CREATE TABLE IF NOT EXISTS `pl_settings` (
`id` int(11) NOT NULL,
  `property` varchar(99) NOT NULL,
  `value` varchar(99) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_subjects`
--

CREATE TABLE IF NOT EXISTS `pl_subjects` (
`id` int(11) NOT NULL,
  `name` varchar(59) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pl_users`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

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
-- Indices de la tabla `pl_subjects`
--
ALTER TABLE `pl_subjects`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pl_users`
--
ALTER TABLE `pl_users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pl_groups`
--
ALTER TABLE `pl_groups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `pl_groupuser`
--
ALTER TABLE `pl_groupuser`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pl_messages`
--
ALTER TABLE `pl_messages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `pl_posts`
--
ALTER TABLE `pl_posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pl_settings`
--
ALTER TABLE `pl_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `pl_subjects`
--
ALTER TABLE `pl_subjects`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pl_users`
--
ALTER TABLE `pl_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
