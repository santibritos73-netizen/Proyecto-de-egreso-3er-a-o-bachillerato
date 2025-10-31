<?php
/**
 * Configuración Global del Sistema
 * Constantes y configuraciones centralizadas
 */

// Configuración de Base de Datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'proyecto');

// Configuración de la Aplicación
define('APP_NAME', 'Proyecto');
define('APP_VERSION', '4.0');
define('APP_URL', 'http://localhost/Proyecto');

// Rutas del Sistema
define('BASE_PATH', dirname(__DIR__));
define('MODELO_PATH', BASE_PATH . '/Modelo');
define('VISTA_PATH', BASE_PATH . '/Vista');
define('CONTROLADOR_PATH', BASE_PATH . '/Controlador');

// Configuración de Sesión
define('SESSION_TIMEOUT', 3600); // 1 hora en segundos
define('SESSION_NAME', 'Proyecto_Session');

// Configuración de Moderación
define('PERMITIR_CONTENIDO_HTML', false);
define('LONGITUD_MINIMA_NOMBRE', 2);
define('LONGITUD_MAXIMA_NOMBRE', 100);
define('LONGITUD_MINIMA_DESCRIPCION', 10);
define('LONGITUD_MAXIMA_DESCRIPCION', 1000);
define('LONGITUD_MINIMA_MENSAJE', 1);
define('LONGITUD_MAXIMA_MENSAJE', 500);
define('PRECIO_MINIMO', 0.01);
define('PRECIO_MAXIMO', 1000000);

// Configuración de Tiempo de Duplicados
define('MINUTOS_ANTI_SPAM', 5); // Minutos de espera entre contenidos similares

// Estados Permitidos
define('ESTADOS_SERVICIO', ['pendiente', 'en_proceso', 'completado', 'cancelado']);
define('ESTADOS_CONTRATACION', ['solicitado', 'aceptado', 'en_progreso', 'completado', 'cancelado', 'rechazado']);

// Configuración de Zona Horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de Errores (desarrollo/producción)
define('ENVIRONMENT', 'development'); // 'development' o 'production'

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Configuración de Caracteres
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');

?>
