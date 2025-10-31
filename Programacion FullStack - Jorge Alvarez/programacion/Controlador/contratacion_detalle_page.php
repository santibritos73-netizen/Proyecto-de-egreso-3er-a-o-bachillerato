<?php
/**
 * Controlador de Página de Detalle de Contratación (MVC Puro)
 */

require_once '../Modelo/auth.php';
require_once '../Modelo/router.php';
require_once '../Modelo/helpers.php';
require_once '../Modelo/contratacion.php';
require_once '../Modelo/mensaje.php';
require_once '../Modelo/resena.php';

// Iniciar sesión
iniciar_sesion_segura();
requiere_autenticacion();

// Obtener ID de contratación
$id = obtener_param_get('id', '');

if (empty($id)) {
    redireccionar('../Controlador/contrataciones_page.php', 'error', 'ID de contratación no especificado');
}

// Obtener contratación
$todas = obtener_todas_contrataciones();
$contratacion = null;
foreach ($todas as $c) {
    if ($c['id'] == $id) {
        $contratacion = $c;
        break;
    }
}

if (!$contratacion) {
    redireccionar('../Controlador/contrataciones_page.php', 'error', 'Contratación no encontrada');
}

// Obtener mensajes
$mensajes = obtener_mensajes_por_contratacion($id);

// Obtener reseña si existe
$resenas = obtener_resenas_por_servicio($contratacion['servicio_id']);
$resena_contratacion = null;
foreach ($resenas as $r) {
    if ($r['contratacion_id'] == $id) {
        $resena_contratacion = $r;
        break;
    }
}

// Verificar si puede reseñar
$puede_resenar = verificar_puede_resenar($id, $contratacion['cliente_id']);

// Contar mensajes no leídos
$no_leidos_cliente = contar_mensajes_no_leidos($id, 'cliente');
$no_leidos_empresa = contar_mensajes_no_leidos($id, 'empresa');

// Obtener mensajes
$mensaje_exito = obtener_param_get('mensaje', '');
$mensaje_error = obtener_param_get('error', '');

// Datos del usuario
$datos_usuario = obtener_datos_usuario();

// Preparar datos para la vista
$datos_vista = [
    'contratacion' => $contratacion,
    'mensajes' => $mensajes,
    'resena_contratacion' => $resena_contratacion,
    'puede_resenar' => $puede_resenar,
    'no_leidos_cliente' => $no_leidos_cliente,
    'no_leidos_empresa' => $no_leidos_empresa,
    'mensaje_exito' => $mensaje_exito,
    'mensaje_error' => $mensaje_error,
    'usuario' => $datos_usuario
];

// Renderizar vista
renderizar_vista('contratacion_detalle_view', $datos_vista);

?>
