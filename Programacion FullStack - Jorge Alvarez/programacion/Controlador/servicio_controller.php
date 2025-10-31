<?php
session_start();
require_once '../Modelo/servicio.php';
require_once '../Modelo/validacion.php';

// Crear servicio
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $titulo = $_POST['titulo'] ?? '';
    $categoria_id = $_POST['categoria_id'] ?? null;
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $empresa_id = $_POST['empresa_id'] ?? null;

    // Convertir valores vacíos a null para las foreign keys
    $categoria_id = $categoria_id === '' ? null : $categoria_id;
    $empresa_id = $empresa_id === '' ? null : $empresa_id;

    // VALIDACIÓN COMPLETA
    $validacion = validar_servicio_completo([
        'nombre' => $titulo,
        'descripcion' => $descripcion,
        'precio' => $precio
    ]);
    
    if (!$validacion['valido']) {
        $errores = implode('. ', $validacion['errores']);
        header("Location: servicios_page.php?error=" . urlencode($errores));
        exit;
    }

    $resultado = crear_servicio($titulo, $categoria_id, $descripcion, $precio, $empresa_id);
    
    if ($resultado['exito']) {
        header("Location: servicios_page.php?mensaje=Servicio creado exitosamente");
        exit;
    } else {
        header("Location: servicios_page.php?error=Error al crear servicio: " . $resultado['error']);
        exit;
    }
}

// Eliminar servicio
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $resultado = eliminar_servicio($id);
    
    if ($resultado['exito']) {
        header("Location: servicios_page.php?mensaje=Servicio eliminado exitosamente");
        exit;
    } else {
        header("Location: servicios_page.php?error=Error al eliminar servicio: " . $resultado['error']);
        exit;
    }
}

// Actualizar servicio
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $id = $_POST['id'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $categoria_id = $_POST['categoria_id'] ?? null;
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $empresa_id = $_POST['empresa_id'] ?? null;

    // Convertir valores vacíos a null para las foreign keys
    $categoria_id = $categoria_id === '' ? null : $categoria_id;
    $empresa_id = $empresa_id === '' ? null : $empresa_id;

    // VALIDACIÓN COMPLETA
    $validacion = validar_servicio_completo([
        'nombre' => $titulo,
        'descripcion' => $descripcion,
        'precio' => $precio
    ]);
    
    if (!$validacion['valido']) {
        $errores = implode('. ', $validacion['errores']);
        header("Location: servicios_page.php?error=" . urlencode($errores));
        exit;
    }

    $resultado = actualizar_servicio($id, $titulo, $categoria_id, $descripcion, $precio, $empresa_id);
    
    if ($resultado['exito']) {
        header("Location: servicios_page.php?mensaje=Servicio actualizado exitosamente");
        exit;
    } else {
        header("Location: servicios_page.php?error=Error al actualizar servicio: " . $resultado['error']);
        exit;
    }
}

?>
