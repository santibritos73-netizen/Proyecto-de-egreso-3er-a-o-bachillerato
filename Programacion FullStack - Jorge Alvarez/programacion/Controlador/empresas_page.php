<?php
/**
 * Controlador de Página de Empresas (MVC Puro)
 */

require_once '../Modelo/auth.php';
require_once '../Modelo/router.php';
require_once '../Modelo/helpers.php';
require_once '../Modelo/empresa.php';

// Iniciar sesión
iniciar_sesion_segura();
requiere_autenticacion();

// Obtener todas las empresas
$empresas = obtener_todas_empresas();

// Obtener mensajes
$mensaje_exito = obtener_param_get('mensaje', '');
$mensaje_error = obtener_param_get('error', '');

// Datos del usuario
$datos_usuario = obtener_datos_usuario();

// Preparar datos para la vista
$datos_vista = [
    'empresas' => $empresas,
    'total_empresas' => count($empresas),
    'mensaje_exito' => $mensaje_exito,
    'mensaje_error' => $mensaje_error,
    'usuario' => $datos_usuario,
    'es_empresa' => es_empresa()
];

// Renderizar vista
renderizar_vista('empresas_view', $datos_vista);

?>
