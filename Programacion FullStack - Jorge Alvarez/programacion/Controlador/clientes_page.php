<?php
/**
 * Controlador de Página de Clientes (MVC Puro)
 */

require_once '../Modelo/auth.php';
require_once '../Modelo/router.php';
require_once '../Modelo/helpers.php';
require_once '../Modelo/cliente.php';

// Iniciar sesión
iniciar_sesion_segura();
requiere_autenticacion();

// Obtener todos los clientes
$clientes = obtener_todos_clientes();

// Obtener mensajes
$mensaje_exito = obtener_param_get('mensaje', '');
$mensaje_error = obtener_param_get('error', '');

// Datos del usuario
$datos_usuario = obtener_datos_usuario();

// Preparar datos para la vista
$datos_vista = [
    'clientes' => $clientes,
    'total_clientes' => count($clientes),
    'mensaje_exito' => $mensaje_exito,
    'mensaje_error' => $mensaje_error,
    'usuario' => $datos_usuario,
    'es_cliente' => es_cliente()
];

// Renderizar vista
renderizar_vista('clientes_view', $datos_vista);

?>
