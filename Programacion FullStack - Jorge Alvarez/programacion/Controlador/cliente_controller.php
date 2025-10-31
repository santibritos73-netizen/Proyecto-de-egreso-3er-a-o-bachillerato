<?php
session_start();
require_once '../Modelo/cliente.php';
require_once '../Modelo/validacion.php';

// Crear cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';

    // VALIDACIÓN COMPLETA
    $validacion = validar_cliente_completo([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'email' => $email
    ]);
    
    if (!$validacion['valido']) {
        $errores = implode('. ', $validacion['errores']);
        header("Location: clientes_page.php?error=" . urlencode($errores));
        exit;
    }

    $resultado = crear_cliente($nombre, $apellido, $direccion, $telefono, $email);
    
    if ($resultado['exito']) {
        header("Location: clientes_page.php?mensaje=Cliente creado exitosamente");
        exit;
    } else {
        header("Location: clientes_page.php?error=Error al crear cliente: " . $resultado['error']);
        exit;
    }
}

// Eliminar cliente
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $resultado = eliminar_cliente($id);
    
    if ($resultado['exito']) {
        header("Location: clientes_page.php?mensaje=Cliente eliminado exitosamente");
        exit;
    } else {
        header("Location: clientes_page.php?error=Error al eliminar cliente: " . $resultado['error']);
        exit;
    }
}

// Actualizar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $id = $_POST['id'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';

    // VALIDACIÓN COMPLETA
    $validacion = validar_cliente_completo([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'email' => $email
    ]);
    
    if (!$validacion['valido']) {
        $errores = implode('. ', $validacion['errores']);
        header("Location: clientes_page.php?error=" . urlencode($errores));
        exit;
    }
    $email = $_POST['email'] ?? '';

    $resultado = actualizar_cliente($id, $nombre, $apellido, $direccion, $telefono, $email);
    
    if ($resultado['exito']) {
        header("Location: clientes_page.php?mensaje=Cliente actualizado exitosamente");
        exit;
    } else {
        header("Location: clientes_page.php?error=Error al actualizar cliente: " . $resultado['error']);
        exit;
    }
}

?>
