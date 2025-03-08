-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-03-2025 a las 17:13:32
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ticket_system`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `file` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `progress` varchar(20) DEFAULT '0%',
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `specialist_id` int(11) DEFAULT NULL,
  `departamento` varchar(255) DEFAULT NULL,
  `tipo_solicitud` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`id`, `type`, `department`, `description`, `file`, `status`, `progress`, `assigned_to`, `created_at`, `specialist_id`, `departamento`, `tipo_solicitud`) VALUES
(1, 'support', 'accounting', 'fggfdgfd', '', 'resolved', '0%', 3, '2025-03-06 01:17:11', NULL, NULL, NULL),
(2, 'support', 'accounting', 'fggfdgfd', '', 'En proceso', '0%', NULL, '2025-03-06 01:25:09', 3, NULL, NULL),
(3, 'support', 'accounting', 'sadsafdssdggfhfdhfdngfngfb', '', 'En proceso', '0%', NULL, '2025-03-06 01:28:08', 3, NULL, NULL),
(4, 'support', 'accounting', 'sadsafdssdggfhfdhfdngfngfb', '', 'En proceso', '0%', NULL, '2025-03-06 10:08:47', 5, NULL, NULL),
(5, 'installation', 'hr', 'INSTALAR IMPRESORA', '', 'resolved', '0%', 3, '2025-03-06 10:51:47', 3, NULL, NULL),
(6, 'soporte', 'informatica', 'solicitud a internet', NULL, 'En proceso', '0%', NULL, '2025-03-06 12:00:08', 3, NULL, NULL),
(7, 'soporte', 'informatica', 'fwrrrgff', NULL, 'pending', '0%', NULL, '2025-03-06 12:01:05', NULL, NULL, NULL),
(8, 'soporte', 'informatica', 'dfddsadgadsg', NULL, 'pending', '0%', NULL, '2025-03-06 12:03:43', NULL, NULL, NULL),
(9, 'soporte', 'informatica', 'solicitud a internet', NULL, 'pending', '0%', NULL, '2025-03-06 12:37:54', NULL, NULL, NULL),
(10, 'soporte', 'informatica', 'ddjikdjñdkslf', NULL, 'pending', '0%', NULL, '2025-03-06 16:45:02', NULL, NULL, NULL),
(11, 'soporte', 'informatica', 'swdedcsdsacsadesdsdewd', NULL, 'pending', '0%', NULL, '2025-03-06 16:59:50', NULL, NULL, NULL),
(12, 'soporte', 'informatica', 'problema de red', NULL, 'En proceso', '0%', NULL, '2025-03-06 18:52:42', 3, NULL, NULL),
(13, 'cvdfr', 'dfdf', 'gfbdgb', NULL, 'pending', '0%', NULL, '2025-03-06 18:52:59', NULL, NULL, NULL),
(14, 'soporte', '1', 'frfrfrg', NULL, 'pending', '0%', NULL, '2025-03-06 19:08:47', NULL, NULL, NULL),
(15, 'soporte', 'frewrgerera', 'vergtru8i8', NULL, 'pending', '0%', NULL, '2025-03-06 19:17:03', NULL, NULL, NULL),
(16, 'gterggg', 'tgtg', 'cfvad', NULL, 'pending', '0%', NULL, '2025-03-06 19:24:41', NULL, NULL, NULL),
(17, 'soporte tecnico', 'sistemas', 'fkejfjeñfj', NULL, 'pending', '0%', NULL, '2025-03-07 02:12:55', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin1', '1234', 'admin'),
(2, 'admin2', 'password2', 'admin'),
(3, 'specialist1', '1234', 'specialist'),
(4, 'specialist2', 'password2', 'specialist'),
(5, 'specialist3', 'password3', 'specialist'),
(6, 'specialist4', 'password4', 'specialist'),
(7, 'specialist5', 'password5', 'specialist');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
