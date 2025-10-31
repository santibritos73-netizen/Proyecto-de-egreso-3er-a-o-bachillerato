<?php

require_once 'usuario.php';

function crear_cliente($nombre, $apellido, $direccion, $telefono, $email) {
    $conexion = conectar_bd();
    $sql = "INSERT INTO clientes (nombre, apellido, direccion, telefono, email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $apellido, $direccion, $telefono, $email);
    $exito = $stmt->execute();
    $id = $conexion->insert_id;
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error, 'id' => $id];
}

function obtener_todos_clientes() {
    $conexion = conectar_bd();
    $sql = "SELECT * FROM clientes ORDER BY fecha_registro DESC";
    $resultado = $conexion->query($sql);
    $clientes = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $clientes[] = $row;
        }
    }
    $conexion->close();
    return $clientes;
}

function obtener_cliente_por_id($id) {
    $conexion = conectar_bd();
    $sql = "SELECT * FROM clientes WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $cliente = null;
    if ($resultado->num_rows == 1) {
        $cliente = $resultado->fetch_assoc();
    }
    $stmt->close();
    $conexion->close();
    return $cliente;
}

function actualizar_cliente($id, $nombre, $apellido, $direccion, $telefono, $email) {
    $conexion = conectar_bd();
    $sql = "UPDATE clientes SET nombre = ?, apellido = ?, direccion = ?, telefono = ?, email = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $apellido, $direccion, $telefono, $email, $id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

function eliminar_cliente($id) {
    $conexion = conectar_bd();
    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

?>
