<?php
/**
 * Controlador de Página de Registro (MVC Puro)
 */

require_once '../Modelo/auth.php';
require_once '../Modelo/router.php';
require_once '../Modelo/helpers.php';

// Si ya está autenticado, redirigir al index
if (hay_sesion_activa()) {
    header('Location: index_page.php');
    exit;
}

// Obtener mensajes
$mensaje_error = obtener_param_get('error', '');
$mensaje_exito = obtener_param_get('mensaje', '');

// Preparar datos para la vista
$datos_vista = [
    'mensaje_error' => $mensaje_error,
    'mensaje_exito' => $mensaje_exito
];

// Renderizar vista
renderizar_vista('registro_view', $datos_vista);

?>
