-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-08-2023 a las 07:31:02
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `aplicacion_residencia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `id` int(11) NOT NULL,
  `numero_control` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_alumno` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_paterno` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_materno` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carrera_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`id`, `numero_control`, `nombre_alumno`, `apellido_paterno`, `apellido_materno`, `carrera_id`) VALUES
(1, '17520234', 'Roberto Gamaliel', 'Campos', 'de la Cruz', 1),
(20, '17520235', 'Vania', 'Marcial', 'García', 5),
(21, '17520233', 'Liseth', 'Sanchez', 'de la Cruz', 1),
(23, '17520287', 'Dalia Rosalia', 'Campos', 'de la Cruz', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `periodo_uno` double DEFAULT NULL,
  `periodo_dos` double DEFAULT NULL,
  `periodo_tres` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `calificaciones`
--

INSERT INTO `calificaciones` (`id`, `alumno_id`, `grupo_id`, `periodo_uno`, `periodo_dos`, `periodo_tres`) VALUES
(7, 21, 12, 98, 90, 85),
(8, 1, 12, 100, 85, 100),
(9, 20, 12, 99, 94, 60),
(10, 21, 13, 77, 100, 60),
(11, 1, 13, 50, 77, 87),
(12, 20, 13, 81, 85, 98),
(13, 21, 16, NULL, NULL, NULL),
(14, 1, 16, NULL, NULL, NULL),
(15, 20, 16, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id` int(11) NOT NULL,
  `clave_cargo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_cargo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`id`, `clave_cargo`, `nombre_cargo`) VALUES
(1, 'DOC', 'Docente'),
(2, 'JEA', 'Jefe Académico'),
(3, 'JAD', 'Jefe Académico-Docente'),
(4, 'DIR', 'Directivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `id` int(11) NOT NULL,
  `clave_carrera` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_carrera` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departamento_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`id`, `clave_carrera`, `nombre_carrera`, `departamento_id`) VALUES
(1, 'ISIC', 'Ingeniería en Sistemas Computacionales', 1),
(2, 'IINF', 'Ingeniería en Informática', 1),
(3, 'ICIV', 'Ingeniería Civil', 2),
(4, 'IGEM', 'Ingeniería en Gestión Empresarial', 3),
(5, 'COPU', 'Contador Público', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id` int(11) NOT NULL,
  `clave_departamento` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_departamento` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`id`, `clave_departamento`, `nombre_departamento`) VALUES
(1, 'DSIC', 'Sistemas y Computación'),
(2, 'DCIT', 'Ciencias de la Tierra'),
(3, 'DECA', 'Económico-Administrativo'),
(4, 'DECB', 'Ciencias Básicas'),
(5, 'SUBA', 'Subdirección Académica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id` int(11) NOT NULL,
  `rfc` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_empleado` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_paterno` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_materno` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genero` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firma_empleado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fotografia_empleado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_id` int(11) DEFAULT NULL,
  `departamento_id` int(11) NOT NULL,
  `cargo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id`, `rfc`, `nombre_empleado`, `apellido_paterno`, `apellido_materno`, `genero`, `firma_empleado`, `fotografia_empleado`, `mail_id`, `departamento_id`, `cargo_id`) VALUES
(1, 'YEA010199ABC', 'Yanet', 'Evangelista', 'Alcocer', 'F', 'firma-YEA010199ABC.png', '-YEA010199ABC.jpg-YEA010199ABC.png-YEA010199ABC.jpg', 6, 1, 3),
(2, 'AFC010199BCA', 'Alfredo de Jesús', 'Canto', 'Cetina', 'M', NULL, NULL, 7, 1, 1),
(3, 'MZH010199ABC', 'María', 'Zavala', 'Hurtado', 'F', NULL, NULL, 8, 1, 1),
(4, 'RFH010199ABC', 'Rogelio Fernando', 'Hernández', 'Miranda', 'M', NULL, NULL, 9, 1, 1),
(5, 'CPM010199ABC', 'Mauricio', 'Córdova', 'Portillo', 'M', NULL, NULL, 10, 1, 1),
(6, 'OPE010199ABC', 'Erika', 'Oropeza', 'Bruno', 'M', NULL, NULL, 11, 4, 1),
(7, 'SRJ010199ABC', 'José Daniel', 'Sánchez', 'Rodriguez', 'M', NULL, NULL, 13, 1, 1),
(8, 'RVT10199ABC', 'Tomás', 'Ríos', 'Velázquez', 'M', NULL, NULL, 12, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id` int(11) NOT NULL,
  `clave_grupo` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cupo` smallint(6) NOT NULL,
  `inscritos` smallint(6) NOT NULL,
  `aula` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `horario` varchar(85) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instrumentacion_didactica` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avance_programatico` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `docente_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `carrera_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id`, `clave_grupo`, `cupo`, `inscritos`, `aula`, `horario`, `instrumentacion_didactica`, `avance_programatico`, `docente_id`, `materia_id`, `carrera_id`) VALUES
(12, 'S1A', 40, 35, 'R1-1', 'Lu 7a8|Ma 7a8|Mi 7a8|Ju 7a8|Vi 7a8', 'reportes-63e1cf12022e7.pdf', NULL, 7, 1, 1),
(13, 'S1B', 40, 3, 'R1-1', 'Lu 8a9|Ma 8a9|Mi 8a9|Ju 8a9|Vi 8a9', 'FUNDAMENTOS-DE-PROGRAMACION-1-63e1ced4768e8.pdf', NULL, 7, 1, 1),
(14, 'S1C', 40, 35, 'R1-1', 'Lu 10a11|Ma 10a11|Mi 10a11|Ju 10a11|Vi 10a11', NULL, NULL, 7, 1, 1),
(16, 'S6B', 40, 90, 'R1-3', 'Lu 10a11|Ma 10a11|Mi 10a11|Ju 10a11|Vi 10a11', NULL, NULL, 2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_alumno`
--

CREATE TABLE `grupo_alumno` (
  `grupo_id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grupo_alumno`
--

INSERT INTO `grupo_alumno` (`grupo_id`, `alumno_id`) VALUES
(12, 1),
(12, 20),
(12, 21),
(13, 1),
(13, 20),
(13, 21),
(16, 1),
(16, 20),
(16, 21);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materia`
--

CREATE TABLE `materia` (
  `id` int(11) NOT NULL,
  `clave_materia` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_materia` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  `objetivo_materia` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caracterizacion_materia` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `intencion_didactica` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `horas_teoricas` smallint(6) NOT NULL,
  `horas_practicas` smallint(6) NOT NULL,
  `creditos` smallint(6) NOT NULL,
  `plan_academico` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semestre` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `materia`
--

INSERT INTO `materia` (`id`, `clave_materia`, `nombre_materia`, `objetivo_materia`, `caracterizacion_materia`, `intencion_didactica`, `horas_teoricas`, `horas_practicas`, `creditos`, `plan_academico`, `semestre`) VALUES
(1, 'SCD1011', 'Fundamentos de Programación', 'Analizar, diseñar y desarrollar soluciones de problemas reales utilizando algoritmos computacionales para implementarlos en un lenguaje de programación.', 'Está diseñada para el logro de competencias específicas dirigidas al aprendizaje de los dominios: manejo de consola y diseño de algoritmos. Comprenderá los conceptos básicos de la programación y escribirá expresiones aritméticas y lógicas en un lenguaje de programación, Así como el uso y funcionamiento de las estructuras secuenciales, selectivas,\r\narreglos unidimensionales y multidimensionales en el desarrollo de aplicaciones. Será capaz de aplicarlos al construir y desarrollar aplicaciones de software que requieran dichas estructuras.', NULL, 2, 3, 5, 'ISIC-2010-', 1),
(2, 'SCD1011A', 'Ingeniería de Software', 'Desarrolla soluciones de software, considerando la metodología y herramientas para la elaboración de un proyecto aplicativo en diferentes escenarios', 'La importancia de esta asignatura, es que permite al estudiante aplicar las fases de la metodología para el desarrollo de un sistema en un contexto multidisciplinario; aplicando el conocimiento científico, através de los métodos, técnicas y normas adecuados, para el desarrollo de software', 'La asignatura debe ser teórico – práctico, y capaz de desarrollar en el estudiante la habilidad para la aplicación de las diferentes técnicas en el desarrollo de software, considerando siempre los principios de la ingeniería de software, para lo cual se organiza el temario en cuatro temas.', 2, 3, 5, 'ISIC-2010-', 6),
(3, 'ACF-0902', 'Cálculo Integral', 'Contextualizar el concepto de Integral.Discernir cuál método puede ser más adecuado para resolver una integral dada resolverla usándolo. resolver problemas de cálculo de áreas, centroides, longitud de arco y volúmenes de sólidos de revolución. Reconocer el potencial del Cálculo integral en la ingeniería', 'Esta asignatura contribuye a desarrollar un pensamiento lógico, heurístico yalgorítmico al modelar fenómenos y resolver problemas en los que interviene la variación.Hay una diversidad de problemas en la ingeniería que son modelados y resueltos a través de una integral, por lo que resulta importante que el ingeniero domine el Cálculo integral.', 'Buscando la comprensión del significado de la integral se propone un tratamiento que comience por lo concreto y pase luego a lo abstracto, así se sugiere que la integral definida se estudie antes de la indefinida puesto que aquélla puede ser abordada a partir del acto concreto de medir áreas.', 2, 3, 5, 'Ninguno', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materia_carrera`
--

CREATE TABLE `materia_carrera` (
  `materia_id` int(11) NOT NULL,
  `carrera_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `materia_carrera`
--

INSERT INTO `materia_carrera` (`materia_id`, `carrera_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(3, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodo`
--

CREATE TABLE `periodo` (
  `id` int(11) NOT NULL,
  `clave_periodo` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_periodo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inicio` date NOT NULL,
  `fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `periodo`
--

INSERT INTO `periodo` (`id`, `clave_periodo`, `nombre_periodo`, `inicio`, `fin`) VALUES
(1, 'SEMACT', 'Enero - Junio 2023', '2023-02-06', '2023-06-15'),
(2, 'INSTDC', 'Cargar Instrumentación Did.', '2023-01-27', '2023-02-06'),
(3, 'REGPLA', 'Registrar Plan de Avance Prog.', '2023-01-27', '2023-02-06'),
(4, 'PARC1', 'Primer Seguimiento', '2023-02-06', '2023-03-10'),
(5, 'PARC2', 'Segundo Seguimiento', '2023-03-13', '2023-04-14'),
(6, 'PARC3', 'Tercer Seguimiento', '2023-05-01', '2023-06-15'),
(9, 'REPFIN', 'Entrega de Reporte Final', '2023-06-19', '2023-06-21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_final`
--

CREATE TABLE `reporte_final` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) DEFAULT NULL,
  `reporte_final` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periodo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reporte_final`
--

INSERT INTO `reporte_final` (`id`, `empleado_id`, `reporte_final`, `periodo`, `estado`) VALUES
(3, 7, 'ReporteFinalSRJ010199ABC-2023-1.pdf', '2023-1', 'Favorable'),
(4, 2, 'ReporteFinalAFC010199BCA-2023-1.pdf', '2023-1', 'Favorable');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_grupo`
--

CREATE TABLE `reporte_grupo` (
  `id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `ac_ordinario` smallint(6) DEFAULT NULL,
  `ac_regularizacion` smallint(6) DEFAULT NULL,
  `ac_extraordinario` smallint(6) DEFAULT NULL,
  `no_acreditado` smallint(6) DEFAULT NULL,
  `desertados` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reporte_grupo`
--

INSERT INTO `reporte_grupo` (`id`, `grupo_id`, `ac_ordinario`, `ac_regularizacion`, `ac_extraordinario`, `no_acreditado`, `desertados`) VALUES
(1, 12, 27, 3, 1, 4, 0),
(2, 13, 1, 1, 0, 1, 0),
(3, 14, 30, 0, 0, 5, 5),
(5, 16, 1, 1, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento`
--

CREATE TABLE `seguimiento` (
  `id` int(11) NOT NULL,
  `fecha_prog_inicio` date NOT NULL,
  `fecha_prog_fin` date NOT NULL,
  `fecha_real_inicio` date DEFAULT NULL,
  `fecha_real_fin` date DEFAULT NULL,
  `evaluacion_programada` date NOT NULL,
  `evaluacion_real` date DEFAULT NULL,
  `porcentaje_aprobacion` double DEFAULT NULL,
  `observaciones` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evidencia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grupo_id` int(11) NOT NULL,
  `parcial_id` int(11) DEFAULT NULL,
  `tema_id` int(11) DEFAULT NULL,
  `estado` varchar(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `seguimiento`
--

INSERT INTO `seguimiento` (`id`, `fecha_prog_inicio`, `fecha_prog_fin`, `fecha_real_inicio`, `fecha_real_fin`, `evaluacion_programada`, `evaluacion_real`, `porcentaje_aprobacion`, `observaciones`, `evidencia`, `grupo_id`, `parcial_id`, `tema_id`, `estado`) VALUES
(19, '2023-02-06', '2023-02-20', '2023-02-06', '2023-02-20', '2023-02-21', '2023-02-21', 100, '', '', 12, 4, 1, 'Aprobado'),
(20, '2023-02-22', '2023-03-09', '2023-02-22', '2023-03-09', '2023-03-10', '2023-03-10', 100, '', '', 12, 4, 2, 'Pendiente'),
(21, '2023-03-13', '2023-04-10', '2023-03-13', '2023-04-10', '2023-04-11', '2023-04-11', 100, '', '', 12, 5, 3, 'Pendiente'),
(22, '2023-04-12', '2023-05-15', NULL, NULL, '2023-05-16', NULL, 100, NULL, NULL, 12, 5, 4, NULL),
(23, '2023-05-17', '2023-06-05', NULL, NULL, '2023-06-06', NULL, 100, NULL, NULL, 12, 5, 5, NULL),
(24, '2023-02-06', '2023-02-20', '2023-02-06', '2023-02-20', '2023-02-21', '2023-02-21', 66.67, 'Examen', '', 13, 4, 1, NULL),
(25, '2023-02-22', '2023-03-05', '2023-02-22', '2023-03-05', '2023-03-10', '2023-03-13', 66.67, 'Desfase', '', 13, 4, 2, NULL),
(26, '2023-03-13', '2023-04-10', NULL, NULL, '2023-04-11', NULL, 100, NULL, NULL, 13, 5, 3, NULL),
(27, '2023-04-12', '2023-05-15', NULL, NULL, '2023-05-16', NULL, 66.67, NULL, NULL, 13, 6, 4, NULL),
(28, '2023-05-17', '2023-06-05', NULL, NULL, '2023-06-06', NULL, 66.67, NULL, NULL, 13, 6, 5, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tema`
--

CREATE TABLE `tema` (
  `id` int(11) NOT NULL,
  `numero_unidad` smallint(6) NOT NULL,
  `nombre_unidad` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtemas` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `materia_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tema`
--

INSERT INTO `tema` (`id`, `numero_unidad`, `nombre_unidad`, `subtemas`, `materia_id`) VALUES
(1, 1, 'Conceptos Básicos', '1.1 Clasificación del software.\r\n1.2 Algoritmo.\r\n1.3 Lenguaje de Programación.\r\n1.4 Programa.\r\n1.5 Programación.\r\n1.6 Paradigmas de programación.\r\n1.7 Editores de texto.\r\n1.8 Compiladores e intérpretes.\r\n1.9 Ejecutables.\r\n1.10 Consola de línea de comandos.', 1),
(2, 2, 'Algoritmos', '2.1 Análisis de problemas.\r\n2.2 Representación de algoritmos: gráfica y pseudocódigo.\r\n2.3 Diseño de algoritmos aplicados a problemas.\r\n2.4 Diseño algorítmico de funciones.', 1),
(3, 3, 'Control de Flujo', '3.1 Características del lenguaje de programación.\r\n3.2 Estructura básica de un programa.\r\n3.3 Traducción de un programa.\r\n3.4 Ejecución de un programa.\r\n3.5 Elementos del lenguaje.\r\n3.6 Errores en tiempo de ejecución.', 1),
(4, 4, 'Organización de datos', '4.1 Estructuras secuenciales.\r\n4.2 Estructuras selectivas: simple, doble y múltiple.\r\n4.3 Estructuras iterativas: repetir mientras, hasta, desde.\r\n4.4 Diseño e implementación de funciones.', 1),
(5, 5, 'Arreglos', '5.1 Unidimensionales:conceptos básicos, operaciones y aplicaciones.\r\n5.2 Multidimensionales: conceptos básicos, operaciones y aplicaciones.', 1),
(6, 1, 'Modelado de Negocios', '1.1 Evolución del Modelado de Negocios.\r\n1.2 Componentes del Modelado de Negocios.\r\n1.3 Orientaciones del Modelado de Negocio.\r\n1.4 BPMN en el Modelado del Negocio.', 2),
(7, 2, 'Metodologías de Desarrollo', '2.1 Metodologías clásicas.\r\n2.1.1 Cascada.\r\n2.1.2 Incremental.\r\n2.1.3 Evolutivo.\r\n2.1.4 Espiral.\r\n2.1.5 Prototipos.\r\n2.1.6 Desarrollo basado encomponentes.\r\n2.2 Otras Metodologías.\r\n2.2.1 Ganar-ganar.\r\n2.2.2 Proceso Unificado (UP).\r\n2.2.3 Ingeniería Web.\r\n2.2.4 Metodologías Ágiles.\r\n2.2.5 Metodologías emergentes.', 2),
(8, 3, 'Arquitecturas de software', '3.1 Descomposición modular.\r\n3.2 Patrones de Diseño.\r\n3.3 Arquitectura de dominio específico.\r\n3.4 Diseño de software de arquitectura multiprocesador.\r\n3.5 Diseño de software de arquitectura.\r\nCliente - Servidor\r\n3.6 Diseño de software de arquitectura distribuida.\r\n3.7 Diseño de software de arquitectura de tiempo real.', 2),
(9, 4, 'Seguridad en Ingeniería de Software', '4.1 Seguridad de software.\r\n4.2 Seguridad en el ciclo de desarrollo delsoftware\r\n4.3 Confiabilidad del software.\r\n4.4 Ingeniería de seguridad .', 2),
(14, 1, 'Fundamentos del Cálculo', '1.1 historia.\r\n1.2 Gottfried Leibniz.', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `email`, `roles`, `password`) VALUES
(1, 'administrador@chilpancingo.tecnm.mx', '[\"ROLE_ADMIN\"]', '$2y$13$3ExopfjQ6mD3rWhQpFZLFe4p4urio15YuwWWfJJmIgU7ZrEMJnsfe'),
(6, 'yanet.ea@chilpancingo.tecnm.mx', '[\"ROLE_USER\"]', '$2y$13$ey7qAxZpjGfaI3mlnP.62uZyRVrNe66b4.0EAVLFwQE71aNfKaNxu'),
(7, 'alfredo.cc@chilpancingo.tecnm.mx', '[\"ROLE_USER\"]', '$2y$13$svW5BX/LFhbR9yOtvgwbKOGMtfsj5aKY/ZxkRdfq2/RltsM5NJHRW'),
(8, 'maria.zh@chilpancingo.tecnm.mx', '[\"ROLE_USER\"]', '$2y$13$MrD2/p9dY4D.xANeQnNhu./91LMAzeuUHcApufb/Jfjbb7J0FOF1O'),
(9, 'rogelio.hm@chilpancingo.tecnm.mx', '[\"ROLE_USER\"]', '$2y$13$ZaUAdCDl/1ADiMZWUqvVSeRSjkuwqqHg8xhGXl2itz2raFKGen/Ba'),
(10, 'mauricio.cp@chilpancingo.tecnm', '[\"ROLE_USER\"]', '$2y$13$UYlFZhgJzfcyVuKS1wnSNe3IO5zyWE99sq5OQ.tYUBcWlwtdcfsIS'),
(11, 'erika.ob@chilpancingo.tecnm.mx', '[\"ROLE_USER\"]', '$2y$13$BibPh6sXHFQ2mBUXpwuFDOQf4Val0c6FQ1QhaY1ymdnigr5GAVUVa'),
(12, 'tomas.rv@chilpancingo.tecnm.mx', '[\"ROLE_USER\"]', '$2y$13$aVmVOg7UKY1TPY3XltKZXOf.tpa9WQVefzpn.7F73rQf82K/czDkG'),
(13, 'jose.sr@chilpancingo.tecnm.mx', '[\"ROLE_USER\"]', '$2y$13$UMBgGxPAl7IvoQBukW8d/.6oWwz9cEZbHUtTBFlR7usgXLZq.M4Oa');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1435D52D2B8F32EA` (`numero_control`),
  ADD KEY `IDX_1435D52DC671B40F` (`carrera_id`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_41F72CC8FC28E5EE` (`alumno_id`),
  ADD KEY `IDX_41F72CC89C833003` (`grupo_id`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_3BEE577191D7D1D7` (`clave_cargo`);

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_CF1ECD3033985A73` (`clave_carrera`),
  ADD KEY `IDX_CF1ECD305A91C08D` (`departamento_id`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_40E497EB2291A5EC` (`clave_departamento`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D9D9BF524F2899EF` (`rfc`),
  ADD UNIQUE KEY `UNIQ_D9D9BF52C8776F01` (`mail_id`),
  ADD KEY `IDX_D9D9BF525A91C08D` (`departamento_id`),
  ADD KEY `IDX_D9D9BF52813AC380` (`cargo_id`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8C0E9BD394E27525` (`docente_id`),
  ADD KEY `IDX_8C0E9BD3B54DBBCB` (`materia_id`),
  ADD KEY `IDX_8C0E9BD3C671B40F` (`carrera_id`);

--
-- Indices de la tabla `grupo_alumno`
--
ALTER TABLE `grupo_alumno`
  ADD PRIMARY KEY (`grupo_id`,`alumno_id`),
  ADD KEY `IDX_18337A149C833003` (`grupo_id`),
  ADD KEY `IDX_18337A14FC28E5EE` (`alumno_id`);

--
-- Indices de la tabla `materia`
--
ALTER TABLE `materia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_6DF052849176C5C7` (`clave_materia`);

--
-- Indices de la tabla `materia_carrera`
--
ALTER TABLE `materia_carrera`
  ADD PRIMARY KEY (`materia_id`,`carrera_id`),
  ADD KEY `IDX_AC5DF516B54DBBCB` (`materia_id`),
  ADD KEY `IDX_AC5DF516C671B40F` (`carrera_id`);

--
-- Indices de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indices de la tabla `periodo`
--
ALTER TABLE `periodo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reporte_final`
--
ALTER TABLE `reporte_final`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_430D9FD9952BE730` (`empleado_id`);

--
-- Indices de la tabla `reporte_grupo`
--
ALTER TABLE `reporte_grupo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_23D658CC9C833003` (`grupo_id`);

--
-- Indices de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1B2181D9C833003` (`grupo_id`),
  ADD KEY `IDX_1B2181D69A9EC6A` (`parcial_id`),
  ADD KEY `IDX_1B2181DA64A8A17` (`tema_id`);

--
-- Indices de la tabla `tema`
--
ALTER TABLE `tema`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_61E3A538B54DBBCB` (`materia_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_2265B05DE7927C74` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `carrera`
--
ALTER TABLE `carrera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `materia`
--
ALTER TABLE `materia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `periodo`
--
ALTER TABLE `periodo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `reporte_final`
--
ALTER TABLE `reporte_final`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `reporte_grupo`
--
ALTER TABLE `reporte_grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `tema`
--
ALTER TABLE `tema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `FK_1435D52DC671B40F` FOREIGN KEY (`carrera_id`) REFERENCES `carrera` (`id`);

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `FK_41F72CC89C833003` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`),
  ADD CONSTRAINT `FK_41F72CC8FC28E5EE` FOREIGN KEY (`alumno_id`) REFERENCES `alumno` (`id`);

--
-- Filtros para la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD CONSTRAINT `FK_CF1ECD305A91C08D` FOREIGN KEY (`departamento_id`) REFERENCES `departamento` (`id`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `FK_D9D9BF525A91C08D` FOREIGN KEY (`departamento_id`) REFERENCES `departamento` (`id`),
  ADD CONSTRAINT `FK_D9D9BF52813AC380` FOREIGN KEY (`cargo_id`) REFERENCES `cargo` (`id`),
  ADD CONSTRAINT `FK_D9D9BF52C8776F01` FOREIGN KEY (`mail_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `FK_8C0E9BD394E27525` FOREIGN KEY (`docente_id`) REFERENCES `empleado` (`id`),
  ADD CONSTRAINT `FK_8C0E9BD3B54DBBCB` FOREIGN KEY (`materia_id`) REFERENCES `materia` (`id`),
  ADD CONSTRAINT `FK_8C0E9BD3C671B40F` FOREIGN KEY (`carrera_id`) REFERENCES `carrera` (`id`);

--
-- Filtros para la tabla `grupo_alumno`
--
ALTER TABLE `grupo_alumno`
  ADD CONSTRAINT `FK_18337A149C833003` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_18337A14FC28E5EE` FOREIGN KEY (`alumno_id`) REFERENCES `alumno` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `materia_carrera`
--
ALTER TABLE `materia_carrera`
  ADD CONSTRAINT `FK_AC5DF516B54DBBCB` FOREIGN KEY (`materia_id`) REFERENCES `materia` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_AC5DF516C671B40F` FOREIGN KEY (`carrera_id`) REFERENCES `carrera` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reporte_final`
--
ALTER TABLE `reporte_final`
  ADD CONSTRAINT `FK_430D9FD9952BE730` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`);

--
-- Filtros para la tabla `reporte_grupo`
--
ALTER TABLE `reporte_grupo`
  ADD CONSTRAINT `FK_23D658CC9C833003` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`);

--
-- Filtros para la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD CONSTRAINT `FK_1B2181D69A9EC6A` FOREIGN KEY (`parcial_id`) REFERENCES `periodo` (`id`),
  ADD CONSTRAINT `FK_1B2181D9C833003` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`),
  ADD CONSTRAINT `FK_1B2181DA64A8A17` FOREIGN KEY (`tema_id`) REFERENCES `tema` (`id`);

--
-- Filtros para la tabla `tema`
--
ALTER TABLE `tema`
  ADD CONSTRAINT `FK_61E3A538B54DBBCB` FOREIGN KEY (`materia_id`) REFERENCES `materia` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
