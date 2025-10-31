<?php
/**
 * Controlador de Página de Categorías (MVC Puro)
 */

require_once '../Modelo/auth.php';
require_once '../Modelo/router.php';
require_once '../Modelo/helpers.php';
require_once '../Modelo/categoria.php';

// Iniciar sesión
iniciar_sesion_segura();
requiere_autenticacion();

// Obtener todas las categorías
$categorias = obtener_todas_categorias();

// Obtener mensajes
$mensaje_exito = obtener_param_get('mensaje', '');
$mensaje_error = obtener_param_get('error', '');

// Datos del usuario
$datos_usuario = obtener_datos_usuario();

// Preparar datos para la vista
$datos_vista = [
    'categorias' => $categorias,
    'total_categorias' => count($categorias),
    'mensaje_exito' => $mensaje_exito,
    'mensaje_error' => $mensaje_error,
    'usuario' => $datos_usuario
];

// Renderizar vista
renderizar_vista('categorias_view', $datos_vista);

?>
