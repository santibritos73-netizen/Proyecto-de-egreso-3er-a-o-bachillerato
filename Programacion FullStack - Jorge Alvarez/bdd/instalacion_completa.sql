-- ============================================
-- INSTALACIÓN COMPLETA - Proyecto
-- Sistema de Gestión de Servicios
-- ============================================
-- Versión: 2.0 - Octubre 2025
-- Incluye: 8 tablas + datos de ejemplo
-- ============================================

-- Crear y seleccionar base de datos
CREATE DATABASE IF NOT EXISTS `proyecto` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `proyecto`;

-- ============================================
-- TABLA 1: usuarios
-- Sistema de autenticación
-- ============================================

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA 2: empresas
-- Proveedores de servicios
-- ============================================

CREATE TABLE IF NOT EXISTS `empresas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `empresas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA 3: clientes
-- Consumidores de servicios
-- ============================================

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA 4: categorias
-- Clasificación de servicios
-- ============================================

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `color` varchar(7) DEFAULT '#667eea',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA 5: servicios
-- Catálogo de servicios ofrecidos
-- ============================================

CREATE TABLE IF NOT EXISTS `servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `empresa_id` (`empresa_id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `servicios_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA 6: contrataciones
-- Gestión de contrataciones
-- ============================================

CREATE TABLE IF NOT EXISTS `contrataciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `servicio_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `estado` enum('solicitado','aceptado','en_progreso','completado','cancelado','rechazado') DEFAULT 'solicitado',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `precio_acordado` decimal(10,2) NOT NULL,
  `notas_cliente` text DEFAULT NULL,
  `notas_proveedor` text DEFAULT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `servicio_id` (`servicio_id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `empresa_id` (`empresa_id`),
  CONSTRAINT `contrataciones_ibfk_1` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contrataciones_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contrataciones_ibfk_3` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA 7: mensajes
-- Sistema de mensajería interna
-- ============================================

CREATE TABLE IF NOT EXISTS `mensajes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contratacion_id` int(11) NOT NULL,
  `remitente_tipo` enum('cliente','empresa') NOT NULL,
  `remitente_id` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `contratacion_id` (`contratacion_id`),
  CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`contratacion_id`) REFERENCES `contrataciones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA 8: resenas
-- Sistema de reseñas y calificaciones
-- ============================================

CREATE TABLE IF NOT EXISTS `resenas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contratacion_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `calificacion` int(1) NOT NULL CHECK (`calificacion` >= 1 AND `calificacion` <= 5),
  `titulo` varchar(200) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `respuesta_empresa` text DEFAULT NULL,
  `aprobada` tinyint(1) DEFAULT 1,
  `fecha_resena` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_respuesta` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_contratacion` (`contratacion_id`),
  KEY `servicio_id` (`servicio_id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `empresa_id` (`empresa_id`),
  CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`contratacion_id`) REFERENCES `contrataciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `resenas_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `resenas_ibfk_3` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `resenas_ibfk_4` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- DATOS DE EJEMPLO
-- ============================================

-- Categorías (8 categorías predefinidas)
INSERT INTO `categorias` (`nombre`, `descripcion`, `icono`, `color`) VALUES
('Tecnología', 'Desarrollo de software, aplicaciones y servicios IT', '💻', '#667eea'),
('Salud', 'Servicios médicos, bienestar y cuidado personal', '🏥', '#48bb78'),
('Educación', 'Cursos, capacitaciones y formación profesional', '📚', '#f6ad55'),
('Limpieza', 'Servicios de limpieza y mantenimiento', '🧹', '#4299e1'),
('Construcción', 'Servicios de construcción, remodelación y reparaciones', '🏗️', '#ed8936'),
('Transporte', 'Servicios de transporte y logística', '🚚', '#38b2ac'),
('Entretenimiento', 'Eventos, recreación y espectáculos', '🎉', '#9f7aea'),
('Consultoría', 'Asesoría profesional y consultoría empresarial', '📊', '#fc8181');

-- Empresas de ejemplo
INSERT INTO `empresas` (`nombre`, `direccion`, `telefono`, `email`, `descripcion`) VALUES
('TechSolutions Inc.', 'Av. Tecnológica 123', '555-1234', 'contacto@techsolutions.com', 'Desarrollo de software a medida'),
('Salud Total', 'Calle Bienestar 456', '555-5678', 'info@saludtotal.com', 'Centro médico integral'),
('EduPlus Academy', 'Av. Educación 789', '555-9012', 'cursos@eduplus.com', 'Capacitación y formación profesional');

-- Clientes de ejemplo
INSERT INTO `clientes` (`nombre`, `apellido`, `direccion`, `telefono`, `email`) VALUES
('Juan', 'Pérez', 'Calle Principal 100', '555-1111', 'juan.perez@email.com'),
('María', 'González', 'Av. Central 200', '555-2222', 'maria.gonzalez@email.com'),
('Carlos', 'Rodríguez', 'Calle Secundaria 300', '555-3333', 'carlos.rodriguez@email.com');

-- Servicios de ejemplo
INSERT INTO `servicios` (`titulo`, `categoria_id`, `descripcion`, `precio`, `empresa_id`) VALUES
('Desarrollo de Aplicación Móvil', 1, 'Creación de app nativa para iOS y Android', 5000.00, 1),
('Consulta Médica General', 2, 'Atención médica general y diagnóstico', 50.00, 2),
('Curso de Python Avanzado', 3, 'Capacitación intensiva en Python para profesionales', 299.00, 3),
('Mantenimiento de Sitio Web', 1, 'Actualización y mantenimiento mensual de sitio web', 150.00, 1);

-- Contrataciones de ejemplo
INSERT INTO `contrataciones` (`servicio_id`, `cliente_id`, `empresa_id`, `estado`, `fecha_inicio`, `fecha_fin`, `precio_acordado`, `notas_cliente`) VALUES
(1, 1, 1, 'en_progreso', '2025-10-15', '2025-12-15', 4800.00, 'App para gestión de inventario'),
(2, 2, 2, 'completado', '2025-10-01', '2025-10-01', 50.00, 'Control rutinario'),
(3, 3, 3, 'aceptado', '2025-11-01', '2025-11-30', 299.00, 'Necesito certificado al finalizar');

-- Mensajes de ejemplo
INSERT INTO `mensajes` (`contratacion_id`, `remitente_tipo`, `remitente_id`, `mensaje`, `leido`) VALUES
(1, 'cliente', 1, 'Hola, ¿cuándo podemos ver el primer prototipo?', 1),
(1, 'empresa', 1, 'Hola! Tendremos el prototipo listo para el viernes.', 1),
(2, 'cliente', 2, 'Gracias por la excelente atención!', 1),
(2, 'empresa', 2, 'Fue un placer atenderle. Saludos!', 1);

-- Reseñas de ejemplo
INSERT INTO `resenas` (`contratacion_id`, `servicio_id`, `cliente_id`, `empresa_id`, `calificacion`, `titulo`, `comentario`, `aprobada`) VALUES
(2, 2, 2, 2, 5, 'Excelente servicio', 'Muy profesional y atento. Totalmente recomendado.', 1);

-- ============================================
-- FINALIZACIÓN
-- ============================================

SELECT 'Base de datos Proyecto instalada correctamente!' AS 'ESTADO',
       'proyecto' AS 'BASE DE DATOS',
       '8 tablas creadas' AS 'TABLAS',
       'Datos de ejemplo incluidos' AS 'DATOS';
