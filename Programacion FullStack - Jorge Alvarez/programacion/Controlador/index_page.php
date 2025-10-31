<?php
/**
 * Controlador de Página de Inicio (MVC Puro)
 */

require_once '../Modelo/auth.php';
require_once '../Modelo/router.php';
require_once '../Modelo/helpers.php';

// Requiere que el usuario esté autenticado
requiere_autenticacion();

// Obtener mensajes
$mensaje_exito = obtener_param_get('mensaje', '');
$mensaje_error = obtener_param_get('error', '');

// Datos del usuario
$datos_usuario = obtener_datos_usuario();

// Preparar datos para la vista
$datos_vista = [
    'mensaje_exito' => $mensaje_exito,
    'mensaje_error' => $mensaje_error,
    'usuario' => $datos_usuario,
    'es_empresa' => isset($_SESSION['empresa_id']),
    'es_cliente' => isset($_SESSION['cliente_id'])
];

// Renderizar vista
renderizar_vista('index_view', $datos_vista);

?>
