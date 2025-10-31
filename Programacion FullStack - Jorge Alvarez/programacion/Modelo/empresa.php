<?php

require_once 'usuario.php';

function crear_empresa($nombre, $direccion, $telefono, $email) {
    $conexion = conectar_bd();
    $sql = "INSERT INTO empresas (nombre, direccion, telefono, email) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $direccion, $telefono, $email);
    $exito = $stmt->execute();
    $id = $conexion->insert_id;
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error, 'id' => $id];
}

function obtener_todas_empresas() {
    $conexion = conectar_bd();
    $sql = "SELECT * FROM empresas ORDER BY fecha_registro DESC";
    $resultado = $conexion->query($sql);
    $empresas = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $empresas[] = $row;
        }
    }
    $conexion->close();
    return $empresas;
}

function obtener_empresa_por_id($id) {
    $conexion = conectar_bd();
    $sql = "SELECT * FROM empresas WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $empresa = null;
    if ($resultado->num_rows == 1) {
        $empresa = $resultado->fetch_assoc();
    }
    $stmt->close();
    $conexion->close();
    return $empresa;
}

function actualizar_empresa($id, $nombre, $direccion, $telefono, $email) {
    $conexion = conectar_bd();
    $sql = "UPDATE empresas SET nombre = ?, direccion = ?, telefono = ?, email = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $direccion, $telefono, $email, $id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

function eliminar_empresa($id) {
    $conexion = conectar_bd();
    $sql = "DELETE FROM empresas WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

?>
