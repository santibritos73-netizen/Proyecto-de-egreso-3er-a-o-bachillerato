<?php

require_once 'usuario.php';

function crear_categoria($nombre, $descripcion, $icono, $color) {
    $conexion = conectar_bd();
    $sql = "INSERT INTO categorias (nombre, descripcion, icono, color) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $descripcion, $icono, $color);
    $exito = $stmt->execute();
    $id = $conexion->insert_id;
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error, 'id' => $id];
}

function obtener_todas_categorias() {
    $conexion = conectar_bd();
    $sql = "SELECT c.*, COUNT(s.id) as total_servicios 
            FROM categorias c 
            LEFT JOIN servicios s ON c.id = s.categoria_id 
            GROUP BY c.id 
            ORDER BY c.nombre ASC";
    $resultado = $conexion->query($sql);
    $categorias = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $categorias[] = $row;
        }
    }
    $conexion->close();
    return $categorias;
}

function obtener_categoria_por_id($id) {
    $conexion = conectar_bd();
    $sql = "SELECT * FROM categorias WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $categoria = null;
    if ($resultado->num_rows == 1) {
        $categoria = $resultado->fetch_assoc();
    }
    $stmt->close();
    $conexion->close();
    return $categoria;
}

function actualizar_categoria($id, $nombre, $descripcion, $icono, $color) {
    $conexion = conectar_bd();
    $sql = "UPDATE categorias SET nombre = ?, descripcion = ?, icono = ?, color = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $descripcion, $icono, $color, $id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

function eliminar_categoria($id) {
    $conexion = conectar_bd();
    
    // Verificar cuántos servicios tienen esta categoría
    $sql_count = "SELECT COUNT(*) as total FROM servicios WHERE categoria_id = ?";
    $stmt_count = $conexion->prepare($sql_count);
    $stmt_count->bind_param("i", $id);
    $stmt_count->execute();
    $resultado_count = $stmt_count->get_result();
    $row = $resultado_count->fetch_assoc();
    $total_servicios = $row['total'];
    $stmt_count->close();
    
    // Proceder con la eliminación
    // Nota: La base de datos está configurada con ON DELETE SET NULL,
    // por lo que los servicios NO se eliminarán, solo se les quitará la categoría
    $sql = "DELETE FROM categorias WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    
    // Devolver resultado con información sobre servicios afectados
    return [
        'exito' => $exito, 
        'error' => $error,
        'servicios_afectados' => $total_servicios
    ];
}

function contar_servicios_por_categoria($categoria_id) {
    $conexion = conectar_bd();
    $sql = "SELECT COUNT(*) as total FROM servicios WHERE categoria_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $total = 0;
    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $total = $row['total'];
    }
    $stmt->close();
    $conexion->close();
    return $total;
}

?>
