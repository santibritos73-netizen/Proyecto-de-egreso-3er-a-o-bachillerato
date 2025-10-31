<?php
/**
 * Controlador de Página de Reseñas (MVC Puro)
 */

require_once '../Modelo/auth.php';
require_once '../Modelo/router.php';
require_once '../Modelo/helpers.php';
require_once '../Modelo/resena.php';
require_once '../Modelo/servicio.php';
require_once '../Modelo/empresa.php';

// Iniciar sesión
iniciar_sesion_segura();
requiere_autenticacion();

// Obtener filtros
$filtro_servicio = obtener_param_get('servicio', '');
$filtro_empresa = obtener_param_get('empresa', '');
$filtro_calificacion = obtener_param_get('calificacion', '');

// Obtener todas las reseñas según filtros
$todas_resenas = [];
if (!empty($filtro_servicio)) {
    $todas_resenas = obtener_resenas_por_servicio($filtro_servicio);
} elseif (!empty($filtro_empresa)) {
    $todas_resenas = obtener_resenas_por_empresa($filtro_empresa);
} else {
    // Obtener todos los servicios y sus reseñas
    $servicios_temp = obtener_todos_servicios();
    foreach ($servicios_temp as $servicio) {
        $resenas_servicio = obtener_resenas_por_servicio($servicio['id']);
        $todas_resenas = array_merge($todas_resenas, $resenas_servicio);
    }
}

// Filtrar por calificación
if (!empty($filtro_calificacion)) {
    $todas_resenas = array_filter($todas_resenas, function($r) use ($filtro_calificacion) {
        return $r['calificacion'] == $filtro_calificacion;
    });
}

// Ordenar por fecha (más recientes primero)
usort($todas_resenas, function($a, $b) {
    return strtotime($b['fecha_resena']) - strtotime($a['fecha_resena']);
});

// Obtener listas para filtros
$servicios = obtener_todos_servicios();
$empresas = obtener_todas_empresas();

// Calcular estadísticas
$total_resenas = count($todas_resenas);
$suma_calificaciones = array_sum(array_column($todas_resenas, 'calificacion'));
$promedio_general = $total_resenas > 0 ? round($suma_calificaciones / $total_resenas, 1) : 0;

// Distribución de calificaciones
$distribucion = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
foreach ($todas_resenas as $resena) {
    $distribucion[$resena['calificacion']]++;
}

// Obtener mensajes
$mensaje_exito = obtener_param_get('mensaje', '');
$mensaje_error = obtener_param_get('error', '');

// Datos del usuario
$datos_usuario = obtener_datos_usuario();

// Preparar datos para la vista
$datos_vista = [
    'resenas' => $todas_resenas,
    'servicios' => $servicios,
    'empresas' => $empresas,
    'filtro_servicio' => $filtro_servicio,
    'filtro_empresa' => $filtro_empresa,
    'filtro_calificacion' => $filtro_calificacion,
    'total_resenas' => $total_resenas,
    'promedio_general' => $promedio_general,
    'distribucion' => $distribucion,
    'mensaje_exito' => $mensaje_exito,
    'mensaje_error' => $mensaje_error,
    'usuario' => $datos_usuario
];

// Renderizar vista
renderizar_vista('resenas_view', $datos_vista);

?>
