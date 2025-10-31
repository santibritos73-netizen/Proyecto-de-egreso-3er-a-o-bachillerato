<?php

require_once 'usuario.php';

function crear_servicio($titulo, $categoria_id, $descripcion, $precio, $empresa_id) {
    $conexion = conectar_bd();
    $sql = "INSERT INTO servicios (titulo, categoria_id, descripcion, precio, empresa_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sisdi", $titulo, $categoria_id, $descripcion, $precio, $empresa_id);
    $exito = $stmt->execute();
    $id = $conexion->insert_id;
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error, 'id' => $id];
}

function obtener_todos_servicios() {
    $conexion = conectar_bd();
    $sql = "SELECT s.*, 
            e.nombre as empresa_nombre, 
            cat.nombre as categoria_nombre,
            cat.icono as categoria_icono,
            cat.color as categoria_color
            FROM servicios s
            LEFT JOIN empresas e ON s.empresa_id = e.id
            LEFT JOIN categorias cat ON s.categoria_id = cat.id
            ORDER BY s.fecha_creacion DESC";
    $resultado = $conexion->query($sql);
    $servicios = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $servicios[] = $row;
        }
    }
    $conexion->close();
    return $servicios;
}

function obtener_servicio_por_id($id) {
    $conexion = conectar_bd();
    $sql = "SELECT s.*, 
            e.nombre as empresa_nombre, 
            cat.nombre as categoria_nombre,
            cat.icono as categoria_icono,
            cat.color as categoria_color
            FROM servicios s
            LEFT JOIN empresas e ON s.empresa_id = e.id
            LEFT JOIN categorias cat ON s.categoria_id = cat.id
            WHERE s.id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $servicio = null;
    if ($resultado->num_rows == 1) {
        $servicio = $resultado->fetch_assoc();
    }
    $stmt->close();
    $conexion->close();
    return $servicio;
}

function actualizar_servicio($id, $titulo, $categoria_id, $descripcion, $precio, $empresa_id) {
    $conexion = conectar_bd();
    $sql = "UPDATE servicios SET titulo = ?, categoria_id = ?, descripcion = ?, precio = ?, empresa_id = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sisdii", $titulo, $categoria_id, $descripcion, $precio, $empresa_id, $id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

function eliminar_servicio($id) {
    $conexion = conectar_bd();
    $sql = "DELETE FROM servicios WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

// Función para buscar y filtrar servicios
function buscar_filtrar_servicios($busqueda = '', $categoria_id = null, $estado = '', $empresa_id = null, $cliente_id = null) {
    $conexion = conectar_bd();
    
    $sql = "SELECT s.*, 
            e.nombre as empresa_nombre, 
            CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre,
            cat.nombre as categoria_nombre,
            cat.icono as categoria_icono,
            cat.color as categoria_color
            FROM servicios s
            LEFT JOIN empresas e ON s.empresa_id = e.id
            LEFT JOIN clientes c ON s.cliente_id = c.id
            LEFT JOIN categorias cat ON s.categoria_id = cat.id
            WHERE 1=1";
    
    $params = [];
    $types = '';
    
    // Filtro de búsqueda por nombre o descripción
    if (!empty($busqueda)) {
        $sql .= " AND (s.nombre LIKE ? OR s.descripcion LIKE ?)";
        $busqueda_param = "%{$busqueda}%";
        $params[] = $busqueda_param;
        $params[] = $busqueda_param;
        $types .= 'ss';
    }
    
    // Filtro por categoría
    if ($categoria_id !== null && $categoria_id !== '') {
        $sql .= " AND s.categoria_id = ?";
        $params[] = $categoria_id;
        $types .= 'i';
    }
    
    // Filtro por estado
    if (!empty($estado)) {
        $sql .= " AND s.estado = ?";
        $params[] = $estado;
        $types .= 's';
    }
    
    // Filtro por empresa
    if ($empresa_id !== null && $empresa_id !== '') {
        $sql .= " AND s.empresa_id = ?";
        $params[] = $empresa_id;
        $types .= 'i';
    }
    
    // Filtro por cliente
    if ($cliente_id !== null && $cliente_id !== '') {
        $sql .= " AND s.cliente_id = ?";
        $params[] = $cliente_id;
        $types .= 'i';
    }
    
    $sql .= " ORDER BY s.fecha_registro DESC";
    
    if (!empty($params)) {
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $resultado = $stmt->get_result();
    } else {
        $resultado = $conexion->query($sql);
    }
    
    $servicios = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $servicios[] = $row;
        }
    }
    
    if (isset($stmt)) {
        $stmt->close();
    }
    $conexion->close();
    
    return $servicios;
}

// Función para obtener estadísticas de servicios
function obtener_estadisticas_servicios() {
    $conexion = conectar_bd();
    
    $stats = [
        'total' => 0,
        'por_categoria' => [],
        'por_estado' => [],
        'ingreso_total' => 0
    ];
    
    // Total de servicios
    $sql = "SELECT COUNT(*) as total, SUM(precio) as ingreso_total FROM servicios";
    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $stats['total'] = $row['total'];
        $stats['ingreso_total'] = $row['ingreso_total'] ?? 0;
    }
    
    // Servicios por categoría
    $sql = "SELECT cat.nombre, cat.icono, cat.color, COUNT(s.id) as total 
            FROM categorias cat
            LEFT JOIN servicios s ON cat.id = s.categoria_id
            GROUP BY cat.id
            ORDER BY total DESC";
    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $stats['por_categoria'][] = $row;
        }
    }
    
    // Servicios por estado
    $sql = "SELECT estado, COUNT(*) as total FROM servicios GROUP BY estado";
    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $stats['por_estado'][$row['estado']] = $row['total'];
        }
    }
    
    $conexion->close();
    return $stats;
}

?>
