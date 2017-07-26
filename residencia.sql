-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-12-2016 a las 12:39:13
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `residencia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contrato`
--

CREATE TABLE IF NOT EXISTS `contrato` (
`CodContrato` int(11) NOT NULL,
  `DNIResidente` varchar(20) NOT NULL,
  `CodHabitacion` int(11) NOT NULL,
  `FechaContrato` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `contrato`
--

INSERT INTO `contrato` (`CodContrato`, `DNIResidente`, `CodHabitacion`, `FechaContrato`) VALUES
(1, '70891675V', 102, '2016-12-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE IF NOT EXISTS `factura` (
`CodFactura` int(11) NOT NULL,
  `FechaExpedicion` date NOT NULL,
  `FechaPago` date DEFAULT NULL,
  `Importe` double NOT NULL,
  `CodContrato` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`CodFactura`, `FechaExpedicion`, `FechaPago`, `Importe`, `CodContrato`) VALUES
(1, '2016-12-19', NULL, 350, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitacion`
--

CREATE TABLE IF NOT EXISTS `habitacion` (
  `CodHabitacion` int(11) NOT NULL,
  `Descripcion` varchar(40) NOT NULL,
  `TipoHabitacion` int(11) NOT NULL,
  `TarifaMes` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `habitacion`
--

INSERT INTO `habitacion` (`CodHabitacion`, `Descripcion`, `TipoHabitacion`, `TarifaMes`) VALUES
(101, 'Habitacion individual', 1, 500),
(102, 'Habitacion doble', 2, 350),
(201, 'Habitacion individual', 1, 500),
(202, 'Habitacion individual', 1, 500);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_alergias`
--

CREATE TABLE IF NOT EXISTS `tiposAlergias` (
  `IdAlergia` int(11) NOT NULL,
  `Tipo` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `DNI` varchar(20) NOT NULL,
  `Nick` varchar(20) NOT NULL,
  `Contrasena` varchar(20) NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `Apellidos` varchar(40) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`DNI`, `Nick`, `Contrasena`, `Nombre`, `Apellidos`, `Email`, `Telefono`) VALUES
('', 'nick', '123456', '', 'V', 'mail', '22'),
('70891675V', 'Linksamaru', 'Xamimejora85', 'Marcos', 'Martin Davila', 'links234md5@gmail.com', '667322250'),
('G19116174', 'LuisH', '123456', 'Luis H', 'Vargas Alvarez', 'luishv94@gmail.com', '3314388930');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_alergia`
--

CREATE TABLE IF NOT EXISTS `usuarioAlergia` (
  `DNIUsuario` varchar(20) NOT NULL,
  `IdAlergia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contrato`
--
ALTER TABLE `contrato`
 ADD PRIMARY KEY (`CodContrato`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
 ADD PRIMARY KEY (`CodFactura`);

--
-- Indices de la tabla `habitacion`
--
ALTER TABLE `habitacion`
 ADD PRIMARY KEY (`CodHabitacion`);

--
-- Indices de la tabla `tipos_alergias`
--
ALTER TABLE `tiposAlergias`
 ADD PRIMARY KEY (`IdAlergia`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
 ADD PRIMARY KEY (`DNI`);

--
-- Indices de la tabla `usuario_alergia`
--
ALTER TABLE `usuarioAlergia`
 ADD PRIMARY KEY (`DNIUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contrato`
--
ALTER TABLE `contrato`
MODIFY `CodContrato` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
MODIFY `CodFactura` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
