<?php
session_start();
require_once '../Modelo/empresa.php';
require_once '../Modelo/validacion.php';

// Crear empresa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';

    // VALIDACIÓN COMPLETA
    $validacion = validar_empresa_completa([
        'nombre' => $nombre,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'email' => $email,
        'sitio_web' => '' // No está en el modelo actual
    ]);
    
    if (!$validacion['valido']) {
        $errores = implode('. ', $validacion['errores']);
        header("Location: empresas_page.php?error=" . urlencode($errores));
        exit;
    }

    $resultado = crear_empresa($nombre, $direccion, $telefono, $email);
    
    if ($resultado['exito']) {
        header("Location: empresas_page.php?mensaje=Empresa creada exitosamente");
        exit;
    } else {
        header("Location: empresas_page.php?error=Error al crear empresa: " . $resultado['error']);
        exit;
    }
}

// Eliminar empresa
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $resultado = eliminar_empresa($id);
    
    if ($resultado['exito']) {
        header("Location: empresas_page.php?mensaje=Empresa eliminada exitosamente");
        exit;
    } else {
        header("Location: empresas_page.php?error=Error al eliminar empresa: " . $resultado['error']);
        exit;
    }
}

// Actualizar empresa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $id = $_POST['id'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';

    // VALIDACIÓN COMPLETA
    $validacion = validar_empresa_completa([
        'nombre' => $nombre,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'email' => $email,
        'sitio_web' => ''
    ]);
    
    if (!$validacion['valido']) {
        $errores = implode('. ', $validacion['errores']);
        header("Location: empresas_page.php?error=" . urlencode($errores));
        exit;
    }

    $resultado = actualizar_empresa($id, $nombre, $direccion, $telefono, $email);
    
    if ($resultado['exito']) {
        header("Location: empresas_page.php?mensaje=Empresa actualizada exitosamente");
        exit;
    } else {
        header("Location: empresas_page.php?error=Error al actualizar empresa: " . $resultado['error']);
        exit;
    }
}

?>
