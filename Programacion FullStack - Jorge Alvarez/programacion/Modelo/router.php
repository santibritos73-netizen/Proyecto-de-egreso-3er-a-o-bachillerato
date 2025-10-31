<?php
/**
 * Sistema de Routing
 * Maneja las rutas y renderizado de vistas
 */

require_once 'config.php';

/**
 * Renderiza una vista con datos
 * 
 * @param string $vista_nombre Nombre del archivo de vista (sin .php)
 * @param array $datos Array de datos a pasar a la vista
 */
function renderizar_vista($vista_nombre, $datos = []) {
    // Extraer datos a variables
    extract($datos);
    
    // Construir ruta de la vista
    $ruta_vista = VISTA_PATH . '/' . $vista_nombre . '.php';
    
    // Verificar que la vista existe
    if (!file_exists($ruta_vista)) {
        die("Error: Vista '$vista_nombre' no encontrada en: $ruta_vista");
    }
    
    // Incluir la vista
    include $ruta_vista;
}

/**
 * Renderiza una vista con layout
 * 
 * @param string $vista_nombre Nombre del archivo de vista
 * @param array $datos Datos para la vista
 * @param string $layout Layout a usar (por defecto 'main')
 */
function renderizar_con_layout($vista_nombre, $datos = [], $layout = 'main') {
    // Extraer datos
    extract($datos);
    
    // Capturar el contenido de la vista
    ob_start();
    $ruta_vista = VISTA_PATH . '/' . $vista_nombre . '.php';
    
    if (file_exists($ruta_vista)) {
        include $ruta_vista;
    } else {
        die("Error: Vista '$vista_nombre' no encontrada");
    }
    
    $contenido_vista = ob_get_clean();
    
    // Incluir el layout con el contenido
    $ruta_layout = VISTA_PATH . '/layouts/' . $layout . '.php';
    
    if (file_exists($ruta_layout)) {
        include $ruta_layout;
    } else {
        // Si no hay layout, mostrar contenido directo
        echo $contenido_vista;
    }
}

/**
 * Redirecciona a una vista específica
 * 
 * @param string $ruta Ruta relativa
 */
function redirigir_a($ruta) {
    header("Location: " . $ruta);
    exit;
}

/**
 * Incluye un partial (fragmento de vista reutilizable)
 * 
 * @param string $partial_nombre Nombre del partial
 * @param array $datos Datos para el partial
 */
function incluir_partial($partial_nombre, $datos = []) {
    extract($datos);
    
    $ruta_partial = VISTA_PATH . '/partials/' . $partial_nombre . '.php';
    
    if (file_exists($ruta_partial)) {
        include $ruta_partial;
    } else {
        echo "<!-- Partial '$partial_nombre' no encontrado -->";
    }
}

/**
 * Obtiene el valor de un parámetro GET de forma segura
 * 
 * @param string $nombre Nombre del parámetro
 * @param mixed $default Valor por defecto
 * @return mixed
 */
function obtener_param_get($nombre, $default = null) {
    return isset($_GET[$nombre]) ? $_GET[$nombre] : $default;
}

/**
 * Obtiene el valor de un parámetro POST de forma segura
 * 
 * @param string $nombre Nombre del parámetro
 * @param mixed $default Valor por defecto
 * @return mixed
 */
function obtener_param_post($nombre, $default = null) {
    return isset($_POST[$nombre]) ? $_POST[$nombre] : $default;
}

/**
 * Verifica si es una petición POST
 * 
 * @return bool
 */
function es_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Verifica si es una petición GET
 * 
 * @return bool
 */
function es_get() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Escapa HTML de forma segura para output
 * 
 * @param string $texto Texto a escapar
 * @return string
 */
function e($texto) {
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Alias de e() para uso más corto en vistas
 */
function h($texto) {
    return e($texto);
}

?>
