<?php
/**
 * Controlador de Página de Error (MVC Puro)
 */

require_once '../Modelo/router.php';
require_once '../Modelo/helpers.php';

// Obtener mensaje de error
$mensaje_error = obtener_param_get('error', 'Ha ocurrido un error inesperado');
$codigo_error = obtener_param_get('codigo', '500');

// Preparar datos para la vista
$datos_vista = [
    'mensaje_error' => $mensaje_error,
    'codigo_error' => $codigo_error
];

// Renderizar vista
renderizar_vista('error_view', $datos_vista);

?>
