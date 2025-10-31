<?php

require_once 'usuario.php';

// ==========================================
// FUNCIONES DE CONTRATACIÓN
// ==========================================

function crear_contratacion($servicio_id, $cliente_id, $empresa_id, $precio_acordado, $notas_cliente = '') {
    $conexion = conectar_bd();
    $sql = "INSERT INTO contrataciones (servicio_id, cliente_id, empresa_id, precio_acordado, notas_cliente, estado) 
            VALUES (?, ?, ?, ?, ?, 'solicitado')";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iiids", $servicio_id, $cliente_id, $empresa_id, $precio_acordado, $notas_cliente);
    $exito = $stmt->execute();
    $id = $conexion->insert_id;
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error, 'id' => $id];
}

function obtener_todas_contrataciones() {
    $conexion = conectar_bd();
    $sql = "SELECT c.*, 
            s.titulo as titulo_servicio,
            s.precio as precio_servicio,
            CONCAT(cl.nombre, ' ', cl.apellido) as nombre_cliente,
            cl.email as email_cliente,
            e.nombre as nombre_empresa,
            e.email as email_empresa,
            cat.nombre as categoria_nombre,
            c.fecha_solicitud as fecha_contratacion
            FROM contrataciones c
            INNER JOIN servicios s ON c.servicio_id = s.id
            INNER JOIN clientes cl ON c.cliente_id = cl.id
            INNER JOIN empresas e ON c.empresa_id = e.id
            LEFT JOIN categorias cat ON s.categoria_id = cat.id
            ORDER BY c.fecha_solicitud DESC";
    $resultado = $conexion->query($sql);
    $contrataciones = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $contrataciones[] = $row;
        }
    }
    $conexion->close();
    return $contrataciones;
}

function obtener_contratacion_por_id($id) {
    $conexion = conectar_bd();
    $sql = "SELECT c.*, 
            s.titulo as titulo_servicio,
            s.descripcion as servicio_descripcion,
            s.precio as precio_servicio,
            CONCAT(cl.nombre, ' ', cl.apellido) as nombre_cliente,
            cl.email as email_cliente,
            cl.telefono as telefono_cliente,
            e.nombre as nombre_empresa,
            e.email as email_empresa,
            e.telefono as telefono_empresa,
            cat.nombre as categoria_nombre,
            c.fecha_solicitud as fecha_contratacion,
            c.notas_cliente as comentario_cliente
            FROM contrataciones c
            INNER JOIN servicios s ON c.servicio_id = s.id
            INNER JOIN clientes cl ON c.cliente_id = cl.id
            INNER JOIN empresas e ON c.empresa_id = e.id
            LEFT JOIN categorias cat ON s.categoria_id = cat.id
            WHERE c.id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $contratacion = null;
    if ($resultado->num_rows == 1) {
        $contratacion = $resultado->fetch_assoc();
    }
    $stmt->close();
    $conexion->close();
    return $contratacion;
}

function actualizar_estado_contratacion($id, $nuevo_estado, $notas_proveedor = null, $fecha_inicio = null, $fecha_fin = null) {
    $conexion = conectar_bd();
    $sql = "UPDATE contrataciones SET estado = ?";
    $params = [$nuevo_estado];
    $types = 's';
    
    if ($notas_proveedor !== null) {
        $sql .= ", notas_proveedor = ?";
        $params[] = $notas_proveedor;
        $types .= 's';
    }
    
    if ($fecha_inicio !== null) {
        $sql .= ", fecha_inicio = ?";
        $params[] = $fecha_inicio;
        $types .= 's';
    }
    
    if ($fecha_fin !== null) {
        $sql .= ", fecha_fin = ?";
        $params[] = $fecha_fin;
        $types .= 's';
    }
    
    $sql .= " WHERE id = ?";
    $params[] = $id;
    $types .= 'i';
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

function obtener_contrataciones_por_cliente($cliente_id) {
    $conexion = conectar_bd();
    $sql = "SELECT c.*, 
            s.titulo as titulo_servicio,
            s.precio as precio_servicio,
            CONCAT(cl.nombre, ' ', cl.apellido) as nombre_cliente,
            cl.email as email_cliente,
            e.nombre as nombre_empresa,
            e.email as email_empresa,
            cat.nombre as categoria_nombre,
            c.fecha_solicitud as fecha_contratacion
            FROM contrataciones c
            INNER JOIN servicios s ON c.servicio_id = s.id
            INNER JOIN clientes cl ON c.cliente_id = cl.id
            INNER JOIN empresas e ON c.empresa_id = e.id
            LEFT JOIN categorias cat ON s.categoria_id = cat.id
            WHERE c.cliente_id = ? 
            ORDER BY c.fecha_solicitud DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $contrataciones = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $contrataciones[] = $row;
        }
    }
    $stmt->close();
    $conexion->close();
    return $contrataciones;
}

function obtener_contrataciones_por_empresa($empresa_id) {
    $conexion = conectar_bd();
    $sql = "SELECT c.*, 
            s.titulo as titulo_servicio,
            s.precio as precio_servicio,
            CONCAT(cl.nombre, ' ', cl.apellido) as nombre_cliente,
            cl.email as email_cliente,
            e.nombre as nombre_empresa,
            e.email as email_empresa,
            cat.nombre as categoria_nombre,
            c.fecha_solicitud as fecha_contratacion
            FROM contrataciones c
            INNER JOIN servicios s ON c.servicio_id = s.id
            INNER JOIN clientes cl ON c.cliente_id = cl.id
            INNER JOIN empresas e ON c.empresa_id = e.id
            LEFT JOIN categorias cat ON s.categoria_id = cat.id
            WHERE c.empresa_id = ? 
            ORDER BY c.fecha_solicitud DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $empresa_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $contrataciones = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $contrataciones[] = $row;
        }
    }
    $stmt->close();
    $conexion->close();
    return $contrataciones;
}

function obtener_estadisticas_contrataciones() {
    $conexion = conectar_bd();
    $stats = [
        'total' => 0,
        'solicitado' => 0,
        'aceptado' => 0,
        'en_progreso' => 0,
        'completado' => 0,
        'cancelado' => 0,
        'rechazado' => 0,
        'ingresos_totales' => 0,
        'ingresos_completados' => 0
    ];
    
    // Total y por estado
    $sql = "SELECT estado, COUNT(*) as total, SUM(precio_acordado) as ingresos 
            FROM contrataciones GROUP BY estado";
    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $stats[$row['estado']] = $row['total'];
            if ($row['estado'] == 'completado') {
                $stats['ingresos_completados'] = $row['ingresos'];
            }
        }
    }
    
    // Total general
    $sql = "SELECT COUNT(*) as total, SUM(precio_acordado) as ingresos FROM contrataciones";
    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $stats['total'] = $row['total'];
        $stats['ingresos_totales'] = $row['ingresos'] ?? 0;
    }
    
    $conexion->close();
    return $stats;
}

?>
