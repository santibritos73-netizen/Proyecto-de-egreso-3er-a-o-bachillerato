<?php

require_once 'config.php';

function conectar_bd() {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    return $conexion;
}

function obtener_usuario_por_email($email) {
    $conexion = conectar_bd();
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = null;
    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
    }
    $stmt->close();
    $conexion->close();
    return $usuario;
}

function registrar_usuario($email, $password) {
    $conexion = conectar_bd();
    $pass_encriptada = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (email, password) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $email, $pass_encriptada);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

function obtener_datos_completos_usuario($usuario_id) {
    $conexion = conectar_bd();
    
    // Buscar si es empresa
    $sql = "SELECT e.*, 'empresa' as tipo_usuario FROM empresas e WHERE e.usuario_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows == 1) {
        $datos = $resultado->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $datos;
    }
    $stmt->close();
    
    // Buscar si es cliente
    $sql = "SELECT c.*, 'cliente' as tipo_usuario FROM clientes c WHERE c.usuario_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows == 1) {
        $datos = $resultado->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $datos;
    }
    $stmt->close();
    $conexion->close();
    
    return null;
}
?>
