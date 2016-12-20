-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 20-12-2016 a las 04:44:58
-- Versión del servidor: 10.1.16-MariaDB
-- Versión de PHP: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `micrositio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catColonias`
--

CREATE TABLE `catColonias` (
  `idColonia` int(11) NOT NULL,
  `Colonia` varchar(250) CHARACTER SET utf8 NOT NULL,
  `CodigoPostal` varchar(250) CHARACTER SET utf8 NOT NULL,
  `Ciudad` varchar(250) CHARACTER SET utf8 NOT NULL,
  `Estado` varchar(250) CHARACTER SET utf8 NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catColonias`
--

INSERT INTO `catColonias` (`idColonia`, `Colonia`, `CodigoPostal`, `Ciudad`, `Estado`, `Status`) VALUES
(1, 'Ampl. Unidad Nacional', '89510', 'Madero', 'Tamaulipas', 0),
(2, 'Delfino Resendiz', '89511', 'Cd. Madero', 'Tamaulipas', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catConfiguraciones`
--

CREATE TABLE `catConfiguraciones` (
  `idConfiguracion` int(11) NOT NULL,
  `Optimo` double(8,2) NOT NULL DEFAULT '90.00',
  `Tolerable` double(8,2) NOT NULL DEFAULT '80.00',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catConfiguraciones`
--

INSERT INTO `catConfiguraciones` (`idConfiguracion`, `Optimo`, `Tolerable`, `Periodo`, `Status`) VALUES
(1, 90.00, 80.00, '2016', 0),
(2, 95.00, 80.00, '2015', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catEntidades`
--

CREATE TABLE `catEntidades` (
  `idEntidad` int(11) NOT NULL,
  `idTEntidad` int(11) NOT NULL,
  `Entidad` varchar(250) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catEntidades`
--

INSERT INTO `catEntidades` (`idEntidad`, `idTEntidad`, `Entidad`, `Status`) VALUES
(1, 1, 'Gerencia Divisional', 0),
(2, 2, 'Direccion Divisional', 0),
(3, 4, 'prueba', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catEstOpe`
--

CREATE TABLE `catEstOpe` (
  `idEstOpe` int(11) NOT NULL,
  `idObjEst` int(11) NOT NULL,
  `idObjOpe` int(11) NOT NULL,
  `Nomenclatura` varchar(250) COLLATE utf8_bin NOT NULL,
  `EstOpe` varchar(250) COLLATE utf8_bin NOT NULL,
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catEstOpe`
--

INSERT INTO `catEstOpe` (`idEstOpe`, `idObjEst`, `idObjOpe`, `Nomenclatura`, `EstOpe`, `Periodo`, `Status`) VALUES
(1, 1, 1, '1.1.1', 'AtenciÃ³n a vias de comunicaciÃ³n terrestres prioritarias', '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catIndicadores`
--

CREATE TABLE `catIndicadores` (
  `idIndicador` int(11) NOT NULL,
  `Nomenclatura` varchar(250) COLLATE utf8_bin NOT NULL,
  `Indicador` varchar(250) COLLATE utf8_bin NOT NULL,
  `Percentil` float(4,2) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catIndicadores`
--

INSERT INTO `catIndicadores` (`idIndicador`, `Nomenclatura`, `Indicador`, `Percentil`, `Status`) VALUES
(1, 'TIU', 'Tiempo de Interrupciï¿½n por Usuario', 0.10, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catNiveles`
--

CREATE TABLE `catNiveles` (
  `idNivel` int(11) NOT NULL,
  `Nivel` varchar(250) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catNiveles`
--

INSERT INTO `catNiveles` (`idNivel`, `Nivel`, `Status`) VALUES
(1, 'Administrador', 0),
(2, 'Operador', 0),
(3, 'Lector', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catObjEst`
--

CREATE TABLE `catObjEst` (
  `idObjEst` int(11) NOT NULL,
  `Nomenclatura` varchar(250) COLLATE utf8_bin NOT NULL,
  `ObjEst` varchar(250) COLLATE utf8_bin NOT NULL,
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catObjEst`
--

INSERT INTO `catObjEst` (`idObjEst`, `Nomenclatura`, `ObjEst`, `Periodo`, `Status`) VALUES
(1, '1', 'Conectividad terrestre de calidad', '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catObjOpe`
--

CREATE TABLE `catObjOpe` (
  `idObjOpe` int(11) NOT NULL,
  `idObjEst` int(11) NOT NULL,
  `Nomenclatura` varchar(250) COLLATE utf8_bin NOT NULL,
  `ObjOpe` varchar(250) COLLATE utf8_bin NOT NULL,
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catObjOpe`
--

INSERT INTO `catObjOpe` (`idObjOpe`, `idObjEst`, `Nomenclatura`, `ObjOpe`, `Periodo`, `Status`) VALUES
(1, 1, '1.1', 'Mejora de caminos', '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catProcesos`
--

CREATE TABLE `catProcesos` (
  `idProceso` int(11) NOT NULL,
  `Proceso` varchar(250) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catProcesos`
--

INSERT INTO `catProcesos` (`idProceso`, `Proceso`, `Status`) VALUES
(1, 'Responsabilidad Social', 0),
(2, 'Servicio al Cliente', 0),
(3, 'Subestaciones', 0),
(4, 'Alumbrado publico', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catPuestos`
--

CREATE TABLE `catPuestos` (
  `idPuesto` int(11) NOT NULL,
  `Puesto` varchar(250) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catPuestos`
--

INSERT INTO `catPuestos` (`idPuesto`, `Puesto`, `Status`) VALUES
(1, 'Supervisor', 0),
(2, 'Auxiliar administrativo A', 0),
(3, 'Auxiliar administrativo B', 0),
(4, 'Auxiliar administrativo C', 0),
(5, 'Secretaria', 0),
(6, 'Profesionista A', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catTEntidades`
--

CREATE TABLE `catTEntidades` (
  `idTEntidad` int(11) NOT NULL,
  `TEntidad` varchar(250) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catTEntidades`
--

INSERT INTO `catTEntidades` (`idTEntidad`, `TEntidad`, `Status`) VALUES
(1, 'Gerencia', 0),
(2, 'Direccion', 0),
(3, 'Departamento', 0),
(4, 'Area', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catUnidades`
--

CREATE TABLE `catUnidades` (
  `idUnidad` int(11) NOT NULL,
  `Nomenclatura` varchar(250) COLLATE utf8_bin NOT NULL,
  `Unidad` varchar(250) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catUnidades`
--

INSERT INTO `catUnidades` (`idUnidad`, `Nomenclatura`, `Unidad`, `Status`) VALUES
(1, 'Kg', 'Kilogramo', 0),
(2, 'm', 'Metro', 1),
(3, 'Km2', 'Kilometros cuadrados', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catUsuarios`
--

CREATE TABLE `catUsuarios` (
  `idUsuario` int(11) NOT NULL,
  `idNivel` int(11) NOT NULL,
  `Usuario` varchar(250) COLLATE utf8_bin NOT NULL,
  `Clave` varchar(250) COLLATE utf8_bin NOT NULL,
  `Correo` varchar(250) COLLATE utf8_bin NOT NULL,
  `Pregunta` varchar(250) COLLATE utf8_bin NOT NULL,
  `Respuesta` varchar(250) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catUsuarios`
--

INSERT INTO `catUsuarios` (`idUsuario`, `idNivel`, `Usuario`, `Clave`, `Correo`, `Pregunta`, `Respuesta`, `Status`) VALUES
(1, 1, 'root', '1OXW3t7Q', 'antonio.peyrano@live.com.mx', 'Su primera mascota', 'El motis', 0),
(2, 2, 'Usuario_1', 'yOLq0+HL3tGg', 'noname@nomail.com', 'Seleccione', '', 0),
(3, 2, 'Usuario_3', 'yOLq0+HL3tGi', 'noname@nomail.com', 'Su comida favorita', 'Pizza', 0),
(4, 3, 'Usuario_4', '49DY29XL0tPT4uE=', 'noname@nomail.com', 'Su pasatiempo favorito', 'Correr', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catVehiculos`
--

CREATE TABLE `catVehiculos` (
  `idVehiculo` int(11) NOT NULL,
  `idEntidad` int(11) NOT NULL,
  `NumEconomico` varchar(50) COLLATE utf8_bin NOT NULL,
  `NumPlaca` varchar(50) COLLATE utf8_bin NOT NULL,
  `Color` varchar(70) COLLATE utf8_bin NOT NULL,
  `Marca` varchar(70) COLLATE utf8_bin NOT NULL,
  `Modelo` varchar(70) COLLATE utf8_bin NOT NULL,
  `TMotor` varchar(70) COLLATE utf8_bin NOT NULL,
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `catVehiculos`
--

INSERT INTO `catVehiculos` (`idVehiculo`, `idEntidad`, `NumEconomico`, `NumPlaca`, `Color`, `Marca`, `Modelo`, `TMotor`, `Periodo`, `Status`) VALUES
(1, 1, 'PTF5689A', 'XAV-89-77', 'Blanco', 'Chevrolet', 'Chevy', 'Gasolina', '2006', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opActividades`
--

CREATE TABLE `opActividades` (
  `idActividad` int(11) NOT NULL,
  `idPrograma` int(11) NOT NULL,
  `idUnidad` int(11) NOT NULL,
  `Actividad` varchar(250) COLLATE utf8_bin NOT NULL,
  `Monto` double(18,2) NOT NULL DEFAULT '0.00',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opActividades`
--

INSERT INTO `opActividades` (`idActividad`, `idPrograma`, `idUnidad`, `Actividad`, `Monto`, `Periodo`, `Status`) VALUES
(1, 1, 3, 'PavimentaciÃ³n de calle alterna', 4000.00, '2016', 0),
(2, 1, 3, 'Encarpetado con concreto hidraulico', 2000.00, '2016', 0),
(3, 1, 3, 'Encarpetado asfaltico', 3000.00, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opCedulas`
--

CREATE TABLE `opCedulas` (
  `idCedula` int(11) NOT NULL,
  `idEntidad` int(11) NOT NULL,
  `Folio` varchar(50) COLLATE utf8_bin NOT NULL,
  `Fecha` varchar(50) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opCedulas`
--

INSERT INTO `opCedulas` (`idCedula`, `idEntidad`, `Folio`, `Fecha`, `Status`) VALUES
(1, 1, 'CEF-15122016-051720-1', '15/12/2016 05:17:20pm', 0),
(2, -2, 'CEF-15122016-054454-175', '15/12/2016 05:44:54pm', 0),
(3, 2, 'CEF-15122016-054536-221', '15/12/2016 05:45:36pm', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opClientes`
--

CREATE TABLE `opClientes` (
  `idCliente` int(11) NOT NULL,
  `idColonia` int(11) NOT NULL,
  `Paterno` varchar(250) COLLATE utf8_bin NOT NULL,
  `Materno` varchar(250) COLLATE utf8_bin NOT NULL,
  `Nombre` varchar(250) COLLATE utf8_bin NOT NULL,
  `Calle` varchar(250) COLLATE utf8_bin NOT NULL,
  `Nint` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `Next` varchar(250) COLLATE utf8_bin NOT NULL,
  `RFC` varchar(250) COLLATE utf8_bin NOT NULL,
  `CURP` varchar(250) COLLATE utf8_bin NOT NULL,
  `TelFijo` varchar(250) COLLATE utf8_bin NOT NULL,
  `TelCel` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opClientes`
--

INSERT INTO `opClientes` (`idCliente`, `idColonia`, `Paterno`, `Materno`, `Nombre`, `Calle`, `Nint`, `Next`, `RFC`, `CURP`, `TelFijo`, `TelCel`, `Status`) VALUES
(1, 1, 'Peyrano', 'Saldierna', 'Antonio', 'Melchor Ocampo Norte', '6', '121', 'PESA480824000', 'PESA480824HTSYNS09', '2110721', '8331095867', 0),
(2, 1, 'Peyrano', 'Luna', 'Jesus Antonio', 'Melchor Ocampo Norte', '6', '121', 'PELJ830819FP1', 'PELJ830819HTSYNS09', '2110721', '8338510726', 1),
(3, 1, 'Peyrano', 'Luna', 'Maria', 'Leona Vicario', 'D', '123', '2', '2', '2', '3', 0),
(4, 1, '', '', '', '', '', '', '', '', '', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEficAct`
--

CREATE TABLE `opEficAct` (
  `idEficAct` int(11) NOT NULL,
  `idActividad` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEficAct`
--

INSERT INTO `opEficAct` (`idEficAct`, `idActividad`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 100.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0),
(2, 2, 0.0000, 0.0000, 0.0000, 0.0000, 200.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0),
(3, 3, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEficEst`
--

CREATE TABLE `opEficEst` (
  `idEficEst` int(11) NOT NULL,
  `idEstOpe` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEficEst`
--

INSERT INTO `opEficEst` (`idEficEst`, `idEstOpe`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 100.0000, 0.0000, 0.0000, 0.0000, 83.3300, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEficGas`
--

CREATE TABLE `opEficGas` (
  `idEficGas` int(11) NOT NULL,
  `idEntidad` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEficGas`
--

INSERT INTO `opEficGas` (`idEficGas`, `idEntidad`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 50.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEficOE`
--

CREATE TABLE `opEficOE` (
  `idEficOE` int(11) NOT NULL,
  `idObjEst` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEficOE`
--

INSERT INTO `opEficOE` (`idEficOE`, `idObjEst`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 100.0000, 0.0000, 0.0000, 0.0000, 83.3300, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEficOO`
--

CREATE TABLE `opEficOO` (
  `idEficOO` int(11) NOT NULL,
  `idObjOpe` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEficOO`
--

INSERT INTO `opEficOO` (`idEficOO`, `idObjOpe`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 100.0000, 0.0000, 0.0000, 0.0000, 83.3300, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEficPro`
--

CREATE TABLE `opEficPro` (
  `idEficPro` int(11) NOT NULL,
  `idPrograma` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEficPro`
--

INSERT INTO `opEficPro` (`idEficPro`, `idPrograma`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 100.0000, 0.0000, 0.0000, 0.0000, 83.3333, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEjecAct`
--

CREATE TABLE `opEjecAct` (
  `idEjecAct` int(11) NOT NULL,
  `idActividad` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEjecAct`
--

INSERT INTO `opEjecAct` (`idEjecAct`, `idActividad`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 8.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0),
(2, 2, 0.0000, 0.0000, 0.0000, 0.0000, 2.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0),
(3, 3, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEjecEst`
--

CREATE TABLE `opEjecEst` (
  `idEjecEst` int(11) NOT NULL,
  `idEstOpe` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEjecEst`
--

INSERT INTO `opEjecEst` (`idEjecEst`, `idEstOpe`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 2.1680, 0.0000, 0.0000, 0.0000, 0.8333, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEjecGas`
--

CREATE TABLE `opEjecGas` (
  `idEjecGas` int(11) NOT NULL,
  `idEntidad` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEjecGas`
--

INSERT INTO `opEjecGas` (`idEjecGas`, `idEntidad`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 45.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEjecOE`
--

CREATE TABLE `opEjecOE` (
  `idEjecOE` int(11) NOT NULL,
  `idObjEst` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEjecOE`
--

INSERT INTO `opEjecOE` (`idEjecOE`, `idObjEst`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 2.1680, 0.0000, 0.0000, 0.0000, 0.8333, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEjecOO`
--

CREATE TABLE `opEjecOO` (
  `idEjecOO` int(11) NOT NULL,
  `idObjOpe` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEjecOO`
--

INSERT INTO `opEjecOO` (`idEjecOO`, `idObjOpe`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 2.1680, 0.0000, 0.0000, 0.0000, 0.8333, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEjecPro`
--

CREATE TABLE `opEjecPro` (
  `idEjecPro` int(11) NOT NULL,
  `idPrograma` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEjecPro`
--

INSERT INTO `opEjecPro` (`idEjecPro`, `idPrograma`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 2.1680, 0.0000, 0.0000, 0.0000, 0.8333, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEjecuciones`
--

CREATE TABLE `opEjecuciones` (
  `idEjecucion` int(11) NOT NULL,
  `idActividad` int(11) NOT NULL,
  `Cantidad` float(12,4) NOT NULL,
  `Mes` varchar(250) COLLATE utf8_bin NOT NULL,
  `Monto` double(18,2) NOT NULL,
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEjecuciones`
--

INSERT INTO `opEjecuciones` (`idEjecucion`, `idActividad`, `Cantidad`, `Mes`, `Monto`, `Periodo`, `Status`) VALUES
(1, 1, 8.0000, '1', 600.00, '2016', 0),
(2, 2, 2.0000, '5', 2000.00, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEmpleados`
--

CREATE TABLE `opEmpleados` (
  `idEmpleado` int(11) NOT NULL,
  `idColonia` int(11) NOT NULL,
  `idEntidad` int(11) NOT NULL,
  `idPuesto` int(11) NOT NULL,
  `Paterno` varchar(250) COLLATE utf8_bin NOT NULL,
  `Materno` varchar(250) COLLATE utf8_bin NOT NULL,
  `Nombre` varchar(250) COLLATE utf8_bin NOT NULL,
  `Calle` varchar(250) COLLATE utf8_bin NOT NULL,
  `Nint` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `Next` varchar(250) COLLATE utf8_bin NOT NULL,
  `RFC` varchar(250) COLLATE utf8_bin NOT NULL,
  `CURP` varchar(250) COLLATE utf8_bin NOT NULL,
  `TelFijo` varchar(250) COLLATE utf8_bin NOT NULL,
  `TelCel` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEmpleados`
--

INSERT INTO `opEmpleados` (`idEmpleado`, `idColonia`, `idEntidad`, `idPuesto`, `Paterno`, `Materno`, `Nombre`, `Calle`, `Nint`, `Next`, `RFC`, `CURP`, `TelFijo`, `TelCel`, `Status`) VALUES
(1, 1, 2, 3, 'Lorenzana', 'Quevedo', 'Andres', 'Sor Juana Ines Norte', '3E', '111', 'XXAAXX000', 'XXAA', '0000000', '8331567731', 0),
(2, 1, 1, 4, 'Pineda', 'Saucedo', 'Beatriz', 'Sor Juana Ines Norte', '3B', '111', 'XXAAXX000', 'XXAA', '0000000', '0000000', 0),
(3, 1, 2, 6, 'Medina', 'Quevedo', 'Rogelio', 'Sor Juana Ines Norte', '3B', '111', 'XXAAXX000', 'XXAA', '0000000', '0000000', 0),
(4, 1, 1, 1, 'Jimenez', 'Robledo', 'Astrid', 'Sor Juana Ines Norte', '3B', '111', 'XXAAXX000', 'XXAA', '0000000', '0000000', 0),
(5, 1, 1, 4, 'Pineda', 'Saucedo', 'Beatriz', 'Sor Juana Ines Norte', '3B', '111', 'XXAAXX000', 'XXAA', '0000000', '0000000', 0),
(6, 1, 2, 6, 'Rodriguez', 'Acevedo', 'Arturo', 'Pino Suarez Sur', 'A', '123', 'RAAR008999FA1', 'RAAR008999HTSYNS09', '3456789', '8332169077', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEscalas`
--

CREATE TABLE `opEscalas` (
  `idEscala` int(11) NOT NULL,
  `idCedula` int(11) NOT NULL,
  `Escala` varchar(50) COLLATE utf8_bin NOT NULL,
  `Ponderacion` float(4,2) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEscalas`
--

INSERT INTO `opEscalas` (`idEscala`, `idCedula`, `Escala`, `Ponderacion`, `Status`) VALUES
(1, 2, 'Excelente', 5.00, 0),
(2, 2, 'Bien', 4.00, 0),
(3, 2, 'Regular', 3.00, 0),
(4, 2, 'Pesimo', 1.00, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEvaluaciones`
--

CREATE TABLE `opEvaluaciones` (
  `idEvaluacion` int(11) NOT NULL,
  `idCedula` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `Folio` varchar(50) COLLATE utf8_bin NOT NULL,
  `Fecha` varchar(50) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEvaluaciones`
--

INSERT INTO `opEvaluaciones` (`idEvaluacion`, `idCedula`, `idEmpleado`, `Folio`, `Fecha`, `Status`) VALUES
(1, 1, 4, 'EVF-19122016-125040-53', '19/12/2016 12:50:40am', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opEvidencias`
--

CREATE TABLE `opEvidencias` (
  `idEvidencia` int(11) NOT NULL,
  `idEjecucion` int(11) NOT NULL,
  `RutaURL` varchar(250) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opEvidencias`
--

INSERT INTO `opEvidencias` (`idEvidencia`, `idEjecucion`, `RutaURL`, `Status`) VALUES
(1, 1, 'http://www.google.com.mx', 0),
(2, 1, 'http://www.facebook.com', 0),
(3, 1, 'ht', 0),
(4, 1, 'c:', 0),
(5, 1, 'c:', 0),
(6, 1, 'https://www.facebook.com', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opFactores`
--

CREATE TABLE `opFactores` (
  `idFactor` int(11) NOT NULL,
  `idCedula` int(11) NOT NULL,
  `Factor` varchar(250) COLLATE utf8_bin NOT NULL,
  `Tipo` varchar(50) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opFactores`
--

INSERT INTO `opFactores` (`idFactor`, `idCedula`, `Factor`, `Tipo`, `Status`) VALUES
(1, 2, 'El endeudamiento con proveedores', 'Interno', 0),
(2, 1, 'El tipo de cambio peso frente al dolar', 'Externo', 0),
(3, 2, 'Resistencia al cambio', 'Interno', 0),
(4, 2, 'Transparencia en rendicion de cuentas', 'Interno', 0),
(5, 2, 'Abastecimiento de papeleria', 'Interno', 0),
(6, 2, 'Servicios Generales', 'Interno', 0),
(7, 3, 'Disposicion de material corrosivo', 'Interno', 0),
(8, 2, 'Disposicion de material corrosivo', 'Interno', 0),
(9, 2, 'Personal capacitado', 'Interno', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opFichasProcesos`
--

CREATE TABLE `opFichasProcesos` (
  `idFicha` int(11) NOT NULL,
  `idProceso` int(11) NOT NULL,
  `Clave` varchar(250) COLLATE utf8_bin NOT NULL,
  `nEdicion` int(11) NOT NULL DEFAULT '0',
  `FechaCreacion` varchar(50) COLLATE utf8_bin NOT NULL,
  `FechaEdicion` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `Actividades` varchar(1500) COLLATE utf8_bin NOT NULL,
  `Responsable` varchar(1500) COLLATE utf8_bin NOT NULL,
  `MisionProceso` varchar(1500) COLLATE utf8_bin NOT NULL,
  `Entrada` varchar(1500) COLLATE utf8_bin NOT NULL,
  `Salida` varchar(1500) COLLATE utf8_bin NOT NULL,
  `relProcesos` varchar(1500) COLLATE utf8_bin NOT NULL,
  `necRecursos` varchar(1500) COLLATE utf8_bin NOT NULL,
  `regArchivos` varchar(1500) COLLATE utf8_bin NOT NULL,
  `docAplicables` varchar(1500) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opFichasProcesos`
--

INSERT INTO `opFichasProcesos` (`idFicha`, `idProceso`, `Clave`, `nEdicion`, `FechaCreacion`, `FechaEdicion`, `Actividades`, `Responsable`, `MisionProceso`, `Entrada`, `Salida`, `relProcesos`, `necRecursos`, `regArchivos`, `docAplicables`, `Status`) VALUES
(1, 1, 'FSMP-2016-1', 1, '19/10/2016 11:49:49pm', '25/11/2016 01:17:40pm', 'Formato de los registros. Conservacion y Archivo.<br>Identificacion de registros. Disposicion de registros.<br>Cumplimento de registros.<br>Almacenamiento y recuperacion.', 'El responsable de la gestion de registros, es el responsable de gestion de la calidad.<br>En cada procedimiento se definen los responsables de cumplimiento de registros.', 'Recoge el modo de identificacion, formato, cumplimiento, acceso, almacenamiento, conservacion y disposicion de los<br>registros del Sistema de Gestion de Calidad.', 'Determinacion de nuevos registros', 'Registros controlados y gestionado.', 'En cada procedimiento se definen los registros correspondientes a cada proceso.', 'Formato para el cumplimiento de registros.', 'Lista de registros en vigor.', 'Procedimiento de gestion de registros.', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opMovGas`
--

CREATE TABLE `opMovGas` (
  `idMovGas` int(11) NOT NULL,
  `idEjecGas` int(11) NOT NULL,
  `Cantidad` float(12,4) NOT NULL,
  `Tiempo` varchar(50) COLLATE utf8_bin NOT NULL,
  `Mes` varchar(250) COLLATE utf8_bin NOT NULL,
  `Monto` double(18,2) NOT NULL,
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opMovGas`
--

INSERT INTO `opMovGas` (`idMovGas`, `idEjecGas`, `Cantidad`, `Tiempo`, `Mes`, `Monto`, `Periodo`, `Status`) VALUES
(1, 1, 3.0000, '15/07/2016 12:42:33am', '1', 45.00, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opProgAct`
--

CREATE TABLE `opProgAct` (
  `idProgAct` int(11) NOT NULL,
  `idActividad` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opProgAct`
--

INSERT INTO `opProgAct` (`idProgAct`, `idActividad`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 8.0000, 0.0000, 5.0000, 0.0000, 0.0000, 0.0000, 0.0000, 30.0000, 0.0000, 0.0000, 80.0000, 0.0000, '2016', 0),
(2, 2, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 80.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0),
(3, 3, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 90.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opProgEst`
--

CREATE TABLE `opProgEst` (
  `idProgEst` int(11) NOT NULL,
  `idEstOpe` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opProgEst`
--

INSERT INTO `opProgEst` (`idProgEst`, `idEstOpe`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 2.1680, 0.0000, 1.3550, 0.0000, 0.0000, 33.3333, 0.0000, 8.1301, 0.0000, 0.0000, 21.6802, 33.3333, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opProgGas`
--

CREATE TABLE `opProgGas` (
  `idProgGas` int(11) NOT NULL,
  `idEntidad` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opProgGas`
--

INSERT INTO `opProgGas` (`idProgGas`, `idEntidad`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 90.0000, 0.0000, 80.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opProgOE`
--

CREATE TABLE `opProgOE` (
  `idProgOE` int(11) NOT NULL,
  `idObjEst` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opProgOE`
--

INSERT INTO `opProgOE` (`idProgOE`, `idObjEst`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 2.1680, 0.0000, 1.3550, 0.0000, 0.0000, 33.3333, 0.0000, 8.1301, 0.0000, 0.0000, 21.6802, 33.3333, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opProgOO`
--

CREATE TABLE `opProgOO` (
  `idProgOO` int(11) NOT NULL,
  `idObjOpe` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opProgOO`
--

INSERT INTO `opProgOO` (`idProgOO`, `idObjOpe`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 2.1680, 0.0000, 1.3550, 0.0000, 0.0000, 33.3333, 0.0000, 8.1301, 0.0000, 0.0000, 21.6802, 33.3333, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opProgPro`
--

CREATE TABLE `opProgPro` (
  `idProgPro` int(11) NOT NULL,
  `idPrograma` int(11) NOT NULL,
  `Enero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Febrero` float(18,4) NOT NULL DEFAULT '0.0000',
  `Marzo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Abril` float(18,4) NOT NULL DEFAULT '0.0000',
  `Mayo` float(18,4) NOT NULL DEFAULT '0.0000',
  `Junio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Julio` float(18,4) NOT NULL DEFAULT '0.0000',
  `Agosto` float(18,4) NOT NULL DEFAULT '0.0000',
  `Septiembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Octubre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Noviembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Diciembre` float(18,4) NOT NULL DEFAULT '0.0000',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opProgPro`
--

INSERT INTO `opProgPro` (`idProgPro`, `idPrograma`, `Enero`, `Febrero`, `Marzo`, `Abril`, `Mayo`, `Junio`, `Julio`, `Agosto`, `Septiembre`, `Octubre`, `Noviembre`, `Diciembre`, `Periodo`, `Status`) VALUES
(1, 1, 2.1680, 0.0000, 1.3550, 0.0000, 0.0000, 33.3333, 0.0000, 8.1301, 0.0000, 0.0000, 21.6802, 33.3333, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opProgramas`
--

CREATE TABLE `opProgramas` (
  `idPrograma` int(11) NOT NULL,
  `idEntidad` int(11) NOT NULL,
  `idObjEst` int(11) NOT NULL,
  `idObjOpe` int(11) NOT NULL,
  `idEstOpe` int(11) NOT NULL,
  `idResponsable` int(11) NOT NULL,
  `idSubalterno` int(11) NOT NULL,
  `Nomenclatura` varchar(250) COLLATE utf8_bin NOT NULL,
  `Programa` varchar(250) COLLATE utf8_bin NOT NULL,
  `Monto` double NOT NULL DEFAULT '0',
  `Periodo` varchar(4) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opProgramas`
--

INSERT INTO `opProgramas` (`idPrograma`, `idEntidad`, `idObjEst`, `idObjOpe`, `idEstOpe`, `idResponsable`, `idSubalterno`, `Nomenclatura`, `Programa`, `Monto`, `Periodo`, `Status`) VALUES
(1, 2, 1, 1, 1, 1, 3, '1.1.1.1', 'PavimentaciÃ³n de calles acceso a colonia Adriana Gonzalez de Hernandez', 50000, '2016', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opUsrTemp`
--

CREATE TABLE `opUsrTemp` (
  `idUsrtmp` int(11) NOT NULL,
  `idNivel` int(11) NOT NULL DEFAULT '3',
  `Usuario` varchar(250) COLLATE utf8_bin NOT NULL,
  `Clave` varchar(250) COLLATE utf8_bin NOT NULL,
  `Correo` varchar(250) COLLATE utf8_bin NOT NULL,
  `Pregunta` varchar(250) COLLATE utf8_bin NOT NULL,
  `Respuesta` varchar(250) COLLATE utf8_bin NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `opUsrTemp`
--

INSERT INTO `opUsrTemp` (`idUsrtmp`, `idNivel`, `Usuario`, `Clave`, `Correo`, `Pregunta`, `Respuesta`, `Status`) VALUES
(1, 2, 'Prototipo', '1NHo09vR3A==', 'antonio.peyrano@gmail.com', 'Su comida favorita', 'Pizza', 0),
(2, 2, 'Prototipo 5', '1NHo09vR3A==', 'antonio.peyrano@gmail.com', 'Su comida favorita', 'Tacos', 0),
(3, 3, 'Prototipo 6', 'w+Hq19HD', 'antonio.peyrano@live.com.mx', 'Su primera mascota', 'gandhi', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relEntPro`
--

CREATE TABLE `relEntPro` (
  `idRelEntPro` int(11) NOT NULL,
  `idProceso` int(11) NOT NULL,
  `idEntidad` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `relEntPro`
--

INSERT INTO `relEntPro` (`idRelEntPro`, `idProceso`, `idEntidad`, `Status`) VALUES
(1, 1, 1, 0),
(2, 1, 2, 0),
(3, 2, 2, 0),
(4, 3, 1, 0),
(5, 3, 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relEntPuesto`
--

CREATE TABLE `relEntPuesto` (
  `idRelEntPst` int(11) NOT NULL,
  `idPuesto` int(11) NOT NULL,
  `idEntidad` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `relEntPuesto`
--

INSERT INTO `relEntPuesto` (`idRelEntPst`, `idPuesto`, `idEntidad`, `Status`) VALUES
(1, 1, 1, 0),
(2, 1, 2, 0),
(3, 2, 1, 0),
(4, 3, 2, 0),
(5, 4, 1, 0),
(6, 5, 1, 0),
(7, 5, 2, 0),
(8, 6, 2, 0),
(9, 6, 1, 0),
(10, 2, 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relIndFicha`
--

CREATE TABLE `relIndFicha` (
  `idRelIndFicha` int(11) NOT NULL,
  `idIndicador` int(11) NOT NULL,
  `idFicha` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `relIndFicha`
--

INSERT INTO `relIndFicha` (`idRelIndFicha`, `idIndicador`, `idFicha`, `Status`) VALUES
(1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relIndPro`
--

CREATE TABLE `relIndPro` (
  `idRelIndPro` int(11) NOT NULL,
  `idIndicador` int(11) NOT NULL,
  `idProceso` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `relIndPro`
--

INSERT INTO `relIndPro` (`idRelIndPro`, `idIndicador`, `idProceso`, `Status`) VALUES
(1, 1, 1, 0),
(2, 1, 2, 0),
(3, 1, 3, 0),
(4, 1, 4, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relProgPro`
--

CREATE TABLE `relProgPro` (
  `idRelProgPro` int(11) NOT NULL,
  `idPrograma` int(11) NOT NULL,
  `idProceso` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `relProgPro`
--

INSERT INTO `relProgPro` (`idRelProgPro`, `idPrograma`, `idProceso`, `Status`) VALUES
(1, 1, 1, 0),
(2, 1, 3, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relUsrEmp`
--

CREATE TABLE `relUsrEmp` (
  `idRelUsrEmp` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `relUsrEmp`
--

INSERT INTO `relUsrEmp` (`idRelUsrEmp`, `idEmpleado`, `idUsuario`, `Status`) VALUES
(1, 1, 2, 0),
(2, 2, 2, 0),
(3, 3, 3, 0),
(4, 4, 1, 0),
(5, 5, 4, 0),
(6, 6, -1, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `catColonias`
--
ALTER TABLE `catColonias`
  ADD PRIMARY KEY (`idColonia`),
  ADD KEY `IdxColonia` (`idColonia`);

--
-- Indices de la tabla `catConfiguraciones`
--
ALTER TABLE `catConfiguraciones`
  ADD PRIMARY KEY (`idConfiguracion`),
  ADD KEY `idxConfiguracion` (`idConfiguracion`);

--
-- Indices de la tabla `catEntidades`
--
ALTER TABLE `catEntidades`
  ADD PRIMARY KEY (`idEntidad`),
  ADD KEY `idxEntidad` (`idEntidad`);

--
-- Indices de la tabla `catEstOpe`
--
ALTER TABLE `catEstOpe`
  ADD PRIMARY KEY (`idEstOpe`),
  ADD KEY `idxEstOpe` (`idEstOpe`);

--
-- Indices de la tabla `catIndicadores`
--
ALTER TABLE `catIndicadores`
  ADD PRIMARY KEY (`idIndicador`),
  ADD KEY `idxIndicador` (`idIndicador`);

--
-- Indices de la tabla `catNiveles`
--
ALTER TABLE `catNiveles`
  ADD PRIMARY KEY (`idNivel`),
  ADD KEY `idxNivel` (`idNivel`);

--
-- Indices de la tabla `catObjEst`
--
ALTER TABLE `catObjEst`
  ADD PRIMARY KEY (`idObjEst`),
  ADD KEY `idxObjEst` (`idObjEst`);

--
-- Indices de la tabla `catObjOpe`
--
ALTER TABLE `catObjOpe`
  ADD PRIMARY KEY (`idObjOpe`),
  ADD KEY `idxObjOpe` (`idObjOpe`);

--
-- Indices de la tabla `catProcesos`
--
ALTER TABLE `catProcesos`
  ADD PRIMARY KEY (`idProceso`),
  ADD KEY `idxProceso` (`idProceso`);

--
-- Indices de la tabla `catPuestos`
--
ALTER TABLE `catPuestos`
  ADD PRIMARY KEY (`idPuesto`),
  ADD KEY `idxPuesto` (`idPuesto`);

--
-- Indices de la tabla `catTEntidades`
--
ALTER TABLE `catTEntidades`
  ADD PRIMARY KEY (`idTEntidad`),
  ADD KEY `idxTEntidad` (`idTEntidad`);

--
-- Indices de la tabla `catUnidades`
--
ALTER TABLE `catUnidades`
  ADD PRIMARY KEY (`idUnidad`),
  ADD KEY `idxUnidades` (`idUnidad`);

--
-- Indices de la tabla `catUsuarios`
--
ALTER TABLE `catUsuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD KEY `idxUsuario` (`idUsuario`);

--
-- Indices de la tabla `catVehiculos`
--
ALTER TABLE `catVehiculos`
  ADD PRIMARY KEY (`idVehiculo`),
  ADD KEY `idxVehiculo` (`idVehiculo`);

--
-- Indices de la tabla `opActividades`
--
ALTER TABLE `opActividades`
  ADD PRIMARY KEY (`idActividad`),
  ADD KEY `idxActividad` (`idActividad`);

--
-- Indices de la tabla `opCedulas`
--
ALTER TABLE `opCedulas`
  ADD PRIMARY KEY (`idCedula`),
  ADD KEY `idxCedula` (`idCedula`);

--
-- Indices de la tabla `opClientes`
--
ALTER TABLE `opClientes`
  ADD PRIMARY KEY (`idCliente`),
  ADD KEY `idxCliente` (`idCliente`);

--
-- Indices de la tabla `opEficAct`
--
ALTER TABLE `opEficAct`
  ADD PRIMARY KEY (`idEficAct`),
  ADD KEY `idxEficAct` (`idEficAct`);

--
-- Indices de la tabla `opEficEst`
--
ALTER TABLE `opEficEst`
  ADD PRIMARY KEY (`idEficEst`),
  ADD KEY `idxEficEst` (`idEficEst`);

--
-- Indices de la tabla `opEficGas`
--
ALTER TABLE `opEficGas`
  ADD PRIMARY KEY (`idEficGas`),
  ADD KEY `idxEficGas` (`idEficGas`);

--
-- Indices de la tabla `opEficOE`
--
ALTER TABLE `opEficOE`
  ADD PRIMARY KEY (`idEficOE`),
  ADD KEY `idxEficOE` (`idEficOE`);

--
-- Indices de la tabla `opEficOO`
--
ALTER TABLE `opEficOO`
  ADD PRIMARY KEY (`idEficOO`),
  ADD KEY `idxEficOO` (`idEficOO`);

--
-- Indices de la tabla `opEficPro`
--
ALTER TABLE `opEficPro`
  ADD PRIMARY KEY (`idEficPro`),
  ADD KEY `idxEficPro` (`idEficPro`);

--
-- Indices de la tabla `opEjecAct`
--
ALTER TABLE `opEjecAct`
  ADD PRIMARY KEY (`idEjecAct`),
  ADD KEY `idxEjecAct` (`idEjecAct`);

--
-- Indices de la tabla `opEjecEst`
--
ALTER TABLE `opEjecEst`
  ADD PRIMARY KEY (`idEjecEst`),
  ADD KEY `idxEjecEst` (`idEjecEst`);

--
-- Indices de la tabla `opEjecGas`
--
ALTER TABLE `opEjecGas`
  ADD PRIMARY KEY (`idEjecGas`),
  ADD KEY `idxEjecGas` (`idEjecGas`);

--
-- Indices de la tabla `opEjecOE`
--
ALTER TABLE `opEjecOE`
  ADD PRIMARY KEY (`idEjecOE`),
  ADD KEY `idxEjecOE` (`idEjecOE`);

--
-- Indices de la tabla `opEjecOO`
--
ALTER TABLE `opEjecOO`
  ADD PRIMARY KEY (`idEjecOO`),
  ADD KEY `idxEjecOO` (`idEjecOO`);

--
-- Indices de la tabla `opEjecPro`
--
ALTER TABLE `opEjecPro`
  ADD PRIMARY KEY (`idEjecPro`),
  ADD KEY `idxEjecPro` (`idEjecPro`);

--
-- Indices de la tabla `opEjecuciones`
--
ALTER TABLE `opEjecuciones`
  ADD PRIMARY KEY (`idEjecucion`),
  ADD KEY `idxEjecucion` (`idEjecucion`);

--
-- Indices de la tabla `opEmpleados`
--
ALTER TABLE `opEmpleados`
  ADD PRIMARY KEY (`idEmpleado`),
  ADD KEY `idxEmpleado` (`idEmpleado`);

--
-- Indices de la tabla `opEscalas`
--
ALTER TABLE `opEscalas`
  ADD PRIMARY KEY (`idEscala`),
  ADD KEY `idxEscala` (`idEscala`);

--
-- Indices de la tabla `opEvaluaciones`
--
ALTER TABLE `opEvaluaciones`
  ADD PRIMARY KEY (`idEvaluacion`),
  ADD KEY `idxEvaluacion` (`idEvaluacion`);

--
-- Indices de la tabla `opEvidencias`
--
ALTER TABLE `opEvidencias`
  ADD PRIMARY KEY (`idEvidencia`),
  ADD KEY `idxEvidencia` (`idEvidencia`);

--
-- Indices de la tabla `opFactores`
--
ALTER TABLE `opFactores`
  ADD PRIMARY KEY (`idFactor`),
  ADD KEY `idxFactor` (`idFactor`);

--
-- Indices de la tabla `opFichasProcesos`
--
ALTER TABLE `opFichasProcesos`
  ADD PRIMARY KEY (`idFicha`),
  ADD KEY `idxFichasProcesos` (`idFicha`);

--
-- Indices de la tabla `opMovGas`
--
ALTER TABLE `opMovGas`
  ADD PRIMARY KEY (`idMovGas`),
  ADD KEY `idxMovGas` (`idMovGas`);

--
-- Indices de la tabla `opProgAct`
--
ALTER TABLE `opProgAct`
  ADD PRIMARY KEY (`idProgAct`),
  ADD KEY `idxProgAct` (`idProgAct`);

--
-- Indices de la tabla `opProgEst`
--
ALTER TABLE `opProgEst`
  ADD PRIMARY KEY (`idProgEst`),
  ADD KEY `idxProgEst` (`idProgEst`);

--
-- Indices de la tabla `opProgGas`
--
ALTER TABLE `opProgGas`
  ADD PRIMARY KEY (`idProgGas`),
  ADD KEY `idxProgGas` (`idProgGas`);

--
-- Indices de la tabla `opProgOE`
--
ALTER TABLE `opProgOE`
  ADD PRIMARY KEY (`idProgOE`),
  ADD KEY `idxProgOE` (`idProgOE`);

--
-- Indices de la tabla `opProgOO`
--
ALTER TABLE `opProgOO`
  ADD PRIMARY KEY (`idProgOO`),
  ADD KEY `idxProgOO` (`idProgOO`);

--
-- Indices de la tabla `opProgPro`
--
ALTER TABLE `opProgPro`
  ADD PRIMARY KEY (`idProgPro`),
  ADD KEY `idxProgPro` (`idProgPro`);

--
-- Indices de la tabla `opProgramas`
--
ALTER TABLE `opProgramas`
  ADD PRIMARY KEY (`idPrograma`),
  ADD KEY `idxPrograma` (`idPrograma`);

--
-- Indices de la tabla `opUsrTemp`
--
ALTER TABLE `opUsrTemp`
  ADD PRIMARY KEY (`idUsrtmp`),
  ADD KEY `idxUrstmp` (`idUsrtmp`);

--
-- Indices de la tabla `relEntPro`
--
ALTER TABLE `relEntPro`
  ADD PRIMARY KEY (`idRelEntPro`),
  ADD KEY `idxRelEntPro` (`idRelEntPro`);

--
-- Indices de la tabla `relEntPuesto`
--
ALTER TABLE `relEntPuesto`
  ADD PRIMARY KEY (`idRelEntPst`),
  ADD KEY `idxRelEntPst` (`idRelEntPst`);

--
-- Indices de la tabla `relIndFicha`
--
ALTER TABLE `relIndFicha`
  ADD PRIMARY KEY (`idRelIndFicha`),
  ADD KEY `idxRelIndFicha` (`idRelIndFicha`);

--
-- Indices de la tabla `relIndPro`
--
ALTER TABLE `relIndPro`
  ADD PRIMARY KEY (`idRelIndPro`),
  ADD KEY `idxRelIndPro` (`idRelIndPro`);

--
-- Indices de la tabla `relProgPro`
--
ALTER TABLE `relProgPro`
  ADD PRIMARY KEY (`idRelProgPro`),
  ADD KEY `idxRelProgPro` (`idRelProgPro`);

--
-- Indices de la tabla `relUsrEmp`
--
ALTER TABLE `relUsrEmp`
  ADD PRIMARY KEY (`idRelUsrEmp`),
  ADD KEY `idxRelUsrEmp` (`idRelUsrEmp`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `catColonias`
--
ALTER TABLE `catColonias`
  MODIFY `idColonia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `catConfiguraciones`
--
ALTER TABLE `catConfiguraciones`
  MODIFY `idConfiguracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `catEntidades`
--
ALTER TABLE `catEntidades`
  MODIFY `idEntidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `catEstOpe`
--
ALTER TABLE `catEstOpe`
  MODIFY `idEstOpe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `catIndicadores`
--
ALTER TABLE `catIndicadores`
  MODIFY `idIndicador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `catNiveles`
--
ALTER TABLE `catNiveles`
  MODIFY `idNivel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `catObjEst`
--
ALTER TABLE `catObjEst`
  MODIFY `idObjEst` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `catObjOpe`
--
ALTER TABLE `catObjOpe`
  MODIFY `idObjOpe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `catProcesos`
--
ALTER TABLE `catProcesos`
  MODIFY `idProceso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `catPuestos`
--
ALTER TABLE `catPuestos`
  MODIFY `idPuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `catTEntidades`
--
ALTER TABLE `catTEntidades`
  MODIFY `idTEntidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `catUnidades`
--
ALTER TABLE `catUnidades`
  MODIFY `idUnidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `catUsuarios`
--
ALTER TABLE `catUsuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `catVehiculos`
--
ALTER TABLE `catVehiculos`
  MODIFY `idVehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opActividades`
--
ALTER TABLE `opActividades`
  MODIFY `idActividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `opCedulas`
--
ALTER TABLE `opCedulas`
  MODIFY `idCedula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `opClientes`
--
ALTER TABLE `opClientes`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `opEficAct`
--
ALTER TABLE `opEficAct`
  MODIFY `idEficAct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `opEficEst`
--
ALTER TABLE `opEficEst`
  MODIFY `idEficEst` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opEficGas`
--
ALTER TABLE `opEficGas`
  MODIFY `idEficGas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opEficOE`
--
ALTER TABLE `opEficOE`
  MODIFY `idEficOE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opEficOO`
--
ALTER TABLE `opEficOO`
  MODIFY `idEficOO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opEficPro`
--
ALTER TABLE `opEficPro`
  MODIFY `idEficPro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opEjecAct`
--
ALTER TABLE `opEjecAct`
  MODIFY `idEjecAct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `opEjecEst`
--
ALTER TABLE `opEjecEst`
  MODIFY `idEjecEst` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opEjecGas`
--
ALTER TABLE `opEjecGas`
  MODIFY `idEjecGas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opEjecOE`
--
ALTER TABLE `opEjecOE`
  MODIFY `idEjecOE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opEjecOO`
--
ALTER TABLE `opEjecOO`
  MODIFY `idEjecOO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opEjecPro`
--
ALTER TABLE `opEjecPro`
  MODIFY `idEjecPro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opEjecuciones`
--
ALTER TABLE `opEjecuciones`
  MODIFY `idEjecucion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `opEmpleados`
--
ALTER TABLE `opEmpleados`
  MODIFY `idEmpleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `opEscalas`
--
ALTER TABLE `opEscalas`
  MODIFY `idEscala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `opEvaluaciones`
--
ALTER TABLE `opEvaluaciones`
  MODIFY `idEvaluacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opEvidencias`
--
ALTER TABLE `opEvidencias`
  MODIFY `idEvidencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `opFactores`
--
ALTER TABLE `opFactores`
  MODIFY `idFactor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `opFichasProcesos`
--
ALTER TABLE `opFichasProcesos`
  MODIFY `idFicha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opMovGas`
--
ALTER TABLE `opMovGas`
  MODIFY `idMovGas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opProgAct`
--
ALTER TABLE `opProgAct`
  MODIFY `idProgAct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `opProgEst`
--
ALTER TABLE `opProgEst`
  MODIFY `idProgEst` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opProgGas`
--
ALTER TABLE `opProgGas`
  MODIFY `idProgGas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opProgOE`
--
ALTER TABLE `opProgOE`
  MODIFY `idProgOE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opProgOO`
--
ALTER TABLE `opProgOO`
  MODIFY `idProgOO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opProgPro`
--
ALTER TABLE `opProgPro`
  MODIFY `idProgPro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opProgramas`
--
ALTER TABLE `opProgramas`
  MODIFY `idPrograma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `opUsrTemp`
--
ALTER TABLE `opUsrTemp`
  MODIFY `idUsrtmp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `relEntPro`
--
ALTER TABLE `relEntPro`
  MODIFY `idRelEntPro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `relEntPuesto`
--
ALTER TABLE `relEntPuesto`
  MODIFY `idRelEntPst` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `relIndFicha`
--
ALTER TABLE `relIndFicha`
  MODIFY `idRelIndFicha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `relIndPro`
--
ALTER TABLE `relIndPro`
  MODIFY `idRelIndPro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `relProgPro`
--
ALTER TABLE `relProgPro`
  MODIFY `idRelProgPro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `relUsrEmp`
--
ALTER TABLE `relUsrEmp`
  MODIFY `idRelUsrEmp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
