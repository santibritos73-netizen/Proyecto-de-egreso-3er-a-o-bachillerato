<?php
/**
 * Controlador de Página de Servicios (MVC Puro)
 * Maneja la lógica y renderiza la vista
 */

require_once '../Modelo/auth.php';
require_once '../Modelo/validacion.php';
require_once '../Modelo/helpers.php';
require_once '../Modelo/router.php';
require_once '../Modelo/servicio.php';
require_once '../Modelo/categoria.php';
require_once '../Modelo/empresa.php';
require_once '../Modelo/cliente.php';

// Iniciar sesión
iniciar_sesion_segura();
requiere_autenticacion();

// Obtener parámetros de filtros
$busqueda = obtener_param_get('busqueda', '');
$categoria_filtro = obtener_param_get('categoria', '');
$empresa_filtro = obtener_param_get('empresa', '');

// Obtener todos los datos necesarios
$servicios = obtener_todos_servicios();
$categorias = obtener_todas_categorias();
$empresas = obtener_todas_empresas();

// Aplicar filtros
$servicios_filtrados = $servicios;

if (!empty($busqueda)) {
    $servicios_filtrados = array_filter($servicios_filtrados, function($s) use ($busqueda) {
        return stripos($s['titulo'], $busqueda) !== false || 
               stripos($s['descripcion'], $busqueda) !== false;
    });
}

if (!empty($categoria_filtro)) {
    $servicios_filtrados = array_filter($servicios_filtrados, function($s) use ($categoria_filtro) {
        return $s['categoria_id'] == $categoria_filtro;
    });
}

if (!empty($empresa_filtro)) {
    $servicios_filtrados = array_filter($servicios_filtrados, function($s) use ($empresa_filtro) {
        return $s['empresa_id'] == $empresa_filtro;
    });
}

// Calcular estadísticas
$total_servicios = count($servicios_filtrados);

// Obtener mensajes de sesión
$mensaje_exito = obtener_param_get('mensaje', '');
$mensaje_error = obtener_param_get('error', '');

// Datos del usuario logueado
$datos_usuario = obtener_datos_usuario();

// Preparar datos para la vista
$datos_vista = [
    'servicios' => $servicios_filtrados,
    'categorias' => $categorias,
    'empresas' => $empresas,
    'busqueda' => $busqueda,
    'categoria_filtro' => $categoria_filtro,
    'empresa_filtro' => $empresa_filtro,
    'total_servicios' => $total_servicios,
    'mensaje_exito' => $mensaje_exito,
    'mensaje_error' => $mensaje_error,
    'usuario' => $datos_usuario,
    'es_empresa' => es_empresa(),
    'es_cliente' => es_cliente()
];

// Renderizar la vista
renderizar_vista('servicios_view', $datos_vista);

?>
