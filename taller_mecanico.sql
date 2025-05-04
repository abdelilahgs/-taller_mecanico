-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-05-2025 a las 22:19:33
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `taller_mecanico`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `ID` int(11) NOT NULL,
  `Usuario_ID` varchar(50) NOT NULL,
  `Vehiculo_ID` varchar(50) NOT NULL,
  `Fecha_Cita` datetime NOT NULL,
  `Estado` enum('Pendiente','Confirmada','Cancelada','Completada') DEFAULT 'Pendiente',
  `Comentarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`ID`, `Usuario_ID`, `Vehiculo_ID`, `Fecha_Cita`, `Estado`, `Comentarios`) VALUES
(20, 'jose', '', '2025-05-11 00:00:00', 'Pendiente', NULL),
(21, 'lopez', '', '2025-05-02 00:00:00', 'Pendiente', NULL),
(23, 'abde', '', '2025-05-03 00:00:00', 'Pendiente', NULL),
(24, 'abde', '', '2025-05-17 00:00:00', 'Pendiente', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `ID` varchar(50) NOT NULL,
  `Clave` varchar(100) NOT NULL,
  `Valor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturacion`
--

CREATE TABLE `facturacion` (
  `ID` int(11) NOT NULL,
  `Usuario_ID` varchar(50) NOT NULL,
  `Reparacion_ID` varchar(50) NOT NULL,
  `Fecha_Emision` timestamp NOT NULL DEFAULT current_timestamp(),
  `Total` decimal(10,2) NOT NULL,
  `Estado` enum('Pendiente','Pagado','Cancelado') DEFAULT 'Pendiente',
  `Metodo_Pago` enum('Efectivo','Tarjeta','Transferencia','Paypal') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturacion`
--

INSERT INTO `facturacion` (`ID`, `Usuario_ID`, `Reparacion_ID`, `Fecha_Emision`, `Total`, `Estado`, `Metodo_Pago`) VALUES
(11, 'abde', 'bujias y frenos', '2025-04-30 22:00:00', 40.00, 'Pendiente', 'Transferencia'),
(12, 'jose', 'bujiasbujiasbujiasbujiasbujiasbujiasbujiasbujiasbu', '2025-04-30 22:00:00', 44.00, '', 'Efectivo'),
(13, 'usuario', 'alog', '2025-04-30 22:00:00', 323.00, 'Pendiente', 'Efectivo'),
(14, '1', '', '2025-05-01 15:42:59', 150.50, 'Pendiente', 'Tarjeta'),
(15, '1', '', '2025-05-01 15:42:59', 200.75, '', 'Efectivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparaciones`
--

CREATE TABLE `reparaciones` (
  `ID` varchar(50) NOT NULL,
  `Vehiculo_ID` varchar(255) NOT NULL,
  `Mecanico_ID` varchar(50) DEFAULT NULL,
  `Descripcion` text NOT NULL,
  `Costo` decimal(10,2) NOT NULL,
  `Estado` enum('Pendiente','En Proceso','Completado','Cancelado') DEFAULT 'Pendiente',
  `Fecha_Ingreso` timestamp NOT NULL DEFAULT current_timestamp(),
  `Fecha_Salida` timestamp NULL DEFAULT NULL,
  `Piezas_Utilizadas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reparaciones`
--

INSERT INTO `reparaciones` (`ID`, `Vehiculo_ID`, `Mecanico_ID`, `Descripcion`, `Costo`, `Estado`, `Fecha_Ingreso`, `Fecha_Salida`, `Piezas_Utilizadas`) VALUES
('8a96bcbb-1078-11f0-a130-d8bbc1821698', '0', '3', '2323', 232.00, 'Pendiente', '2025-04-02 22:00:00', NULL, NULL),
('d3eab8d1-1079-11f0-a130-d8bbc1821698', 'ale', NULL, 'fafafasf', 25.00, 'Pendiente', '2025-04-02 22:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('cliente','mecanico','admin') NOT NULL DEFAULT 'cliente',
  `status` enum('pendiente','activo','bloqueado','denegado') NOT NULL DEFAULT 'pendiente',
  `token_verificacion` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','aprobado','bloqueado','denegado') NOT NULL DEFAULT 'pendiente',
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `email`, `password`, `role`, `status`, `token_verificacion`, `fecha_registro`, `estado`, `nombre`) VALUES
(1, 'admin', 'admin@gmial.com', '$2y$10$VTF4uc5mcZQmuYPf1ABFR.jkBhZO3JRpV91uA/IlXjBrlz8aB0cF.', 'admin', 'activo', NULL, '2025-04-30 22:34:18', 'aprobado', ''),
(2, 'usuario', 'usuario1@usuario1.com', '$2y$10$m.PN06yIKKWPuUcnJE/qb.U.b8VM1b4V9EbIEfnOZfproS8zt2TRm', 'cliente', 'activo', NULL, '2025-04-30 22:47:33', 'aprobado', ''),
(3, 'usuario2', 'usuario2@usuario2.com', '$2y$10$SEt1AqL/3CcpwuD8G4mN/OQaX6v9kKj6xdGalQ.lnfrTthMR4yZKu', 'cliente', 'pendiente', NULL, '2025-05-01 12:28:29', 'pendiente', ''),
(4, 'jose', '', '', 'cliente', 'pendiente', NULL, '2025-05-01 13:01:52', 'pendiente', ''),
(8, 'lopez', '', '', 'cliente', 'pendiente', NULL, '2025-05-01 13:24:44', 'pendiente', ''),
(9, 'pepe', '', '', 'cliente', 'pendiente', NULL, '2025-05-01 13:25:07', 'pendiente', ''),
(10, 'lolo', '', '', 'cliente', 'pendiente', NULL, '2025-05-01 13:29:30', 'pendiente', ''),
(11, 'sdaff', '', '', 'cliente', 'pendiente', NULL, '2025-05-01 13:30:16', 'pendiente', ''),
(12, 'dsds', '', '', 'cliente', 'pendiente', NULL, '2025-05-01 13:32:04', 'pendiente', ''),
(13, 'dsdsds', '', '', 'cliente', 'pendiente', NULL, '2025-05-01 13:32:49', 'denegado', ''),
(14, 'toni', '', '', 'cliente', 'pendiente', NULL, '2025-05-01 13:54:51', 'denegado', ''),
(15, 'dfafadf', '', '', 'cliente', 'pendiente', NULL, '2025-05-01 13:59:33', 'pendiente', ''),
(16, 'josejose', '', '', 'cliente', 'pendiente', NULL, '2025-05-01 14:09:11', 'pendiente', ''),
(17, 'abde', 'abde@abde.com', '$2y$10$BJeUNNXIjD4MVQvxfV/SRu2YJYYvdDIahN6uE4MccbJXFsUaKRLKq', 'cliente', 'activo', NULL, '2025-05-01 16:02:00', 'aprobado', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `ID` varchar(50) NOT NULL,
  `Usuario_ID` varchar(50) NOT NULL,
  `Marca` varchar(50) NOT NULL,
  `Modelo` varchar(50) NOT NULL,
  `Año` int(11) NOT NULL,
  `Matricula` varchar(20) NOT NULL,
  `Estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `Fecha_Registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`ID`, `Usuario_ID`, `Marca`, `Modelo`, `Año`, `Matricula`, `Estado`, `Fecha_Registro`) VALUES
('', '', 'afas', 'fadsf', 23, 'sfaf', 'Activo', '2025-04-30 22:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Usuario_ID` (`Usuario_ID`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Clave` (`Clave`);

--
-- Indices de la tabla `facturacion`
--
ALTER TABLE `facturacion`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Usuario_ID` (`Usuario_ID`),
  ADD KEY `Reparacion_ID` (`Reparacion_ID`);

--
-- Indices de la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Vehiculo_ID` (`Vehiculo_ID`),
  ADD KEY `Mecanico_ID` (`Mecanico_ID`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Matricula` (`Matricula`),
  ADD KEY `Usuario_ID` (`Usuario_ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `facturacion`
--
ALTER TABLE `facturacion`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
