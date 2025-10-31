<?php
/**
 * Controlador de Página de Contrataciones (MVC Puro)
 */

require_once '../Modelo/auth.php';
require_once '../Modelo/router.php';
require_once '../Modelo/helpers.php';
require_once '../Modelo/contratacion.php';

// Iniciar sesión
iniciar_sesion_segura();
requiere_autenticacion();

// Obtener todas las contrataciones
$todas_contrataciones = obtener_todas_contrataciones();
$estadisticas = obtener_estadisticas_contrataciones();

// Aplicar filtros
$filtro_estado = obtener_param_get('estado', '');
$filtro_buscar = obtener_param_get('buscar', '');

$contrataciones_filtradas = $todas_contrataciones;

if (!empty($filtro_estado)) {
    $contrataciones_filtradas = array_filter($contrataciones_filtradas, function($c) use ($filtro_estado) {
        return $c['estado'] == $filtro_estado;
    });
}

if (!empty($filtro_buscar)) {
    $contrataciones_filtradas = array_filter($contrataciones_filtradas, function($c) use ($filtro_buscar) {
        return stripos($c['titulo_servicio'], $filtro_buscar) !== false ||
               stripos($c['nombre_cliente'], $filtro_buscar) !== false ||
               stripos($c['nombre_empresa'], $filtro_buscar) !== false;
    });
}

// Obtener mensajes
$mensaje_exito = obtener_param_get('mensaje', '');
$mensaje_error = obtener_param_get('error', '');

// Datos del usuario
$datos_usuario = obtener_datos_usuario();

// Preparar datos para la vista
$datos_vista = [
    'contrataciones' => $contrataciones_filtradas,
    'estadisticas' => $estadisticas,
    'filtro_estado' => $filtro_estado,
    'filtro_buscar' => $filtro_buscar,
    'total_contrataciones' => count($contrataciones_filtradas),
    'mensaje_exito' => $mensaje_exito,
    'mensaje_error' => $mensaje_error,
    'usuario' => $datos_usuario
];

// Renderizar vista
renderizar_vista('contrataciones_view', $datos_vista);

?>
