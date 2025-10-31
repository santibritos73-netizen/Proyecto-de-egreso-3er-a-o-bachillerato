<?php
require_once '../Modelo/auth.php';
require_once '../Modelo/validacion.php';
require_once '../Modelo/helpers.php';
require_once '../Modelo/categoria.php';

iniciar_sesion_segura();
requiere_autenticacion();

// Crear categoría
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $icono = $_POST['icono'] ?? '📋';
    $color = $_POST['color'] ?? '#007bff';

    // Validar datos
    $datos_validacion = [
        'nombre' => $nombre,
        'descripcion' => $descripcion
    ];
    
    $validacion_nombre = validar_longitud($nombre, 2, 50, 'nombre de categoría');
    if (!$validacion_nombre['valido']) {
        redirigir_con_mensaje('categorias_page.php', $validacion_nombre['mensaje'], 'error');
    }
    
    $validacion_contenido = validar_contenido_apropiado($nombre . ' ' . $descripcion);
    if (!$validacion_contenido['valido']) {
        redirigir_con_mensaje('categorias_page.php', $validacion_contenido['mensaje'], 'error');
    }

    $resultado = crear_categoria($nombre, $descripcion, $icono, $color);
    
    if ($resultado['exito']) {
        registrar_actividad('Crear categoría', "Categoría: $nombre");
        redirigir_con_mensaje('categorias_page.php', 'Categoría creada exitosamente');
    } else {
        redirigir_con_mensaje('categorias_page.php', 'Error al crear categoría: ' . $resultado['error'], 'error');
    }
}

// Eliminar categoría
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $resultado = eliminar_categoria($id);
    
    if ($resultado['exito']) {
        registrar_actividad('Eliminar categoría', "ID: $id");
        redirigir_con_mensaje('categorias_page.php', 'Categoría eliminada exitosamente');
    } else {
        redirigir_con_mensaje('categorias_page.php', 'Error al eliminar categoría: ' . $resultado['error'], 'error');
    }
}

// Actualizar categoría
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $id = $_POST['id'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $icono = $_POST['icono'] ?? '📋';
    $color = $_POST['color'] ?? '#007bff';

    // Validar datos
    $validacion_nombre = validar_longitud($nombre, 2, 50, 'nombre de categoría');
    if (!$validacion_nombre['valido']) {
        redirigir_con_mensaje('categorias_page.php', $validacion_nombre['mensaje'], 'error');
    }
    
    $validacion_contenido = validar_contenido_apropiado($nombre . ' ' . $descripcion);
    if (!$validacion_contenido['valido']) {
        redirigir_con_mensaje('categorias_page.php', $validacion_contenido['mensaje'], 'error');
    }

    $resultado = actualizar_categoria($id, $nombre, $descripcion, $icono, $color);
    
    if ($resultado['exito']) {
        registrar_actividad('Actualizar categoría', "ID: $id - Categoría: $nombre");
        redirigir_con_mensaje('categorias_page.php', 'Categoría actualizada exitosamente');
    } else {
        redirigir_con_mensaje('categorias_page.php', 'Error al actualizar categoría: ' . $resultado['error'], 'error');
    }
}

?>
