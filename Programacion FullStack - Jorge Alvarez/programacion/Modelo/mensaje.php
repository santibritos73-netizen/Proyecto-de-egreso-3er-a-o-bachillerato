<?php

require_once 'usuario.php';

// ==========================================
// FUNCIONES DE MENSAJERÍA
// ==========================================

function enviar_mensaje($contratacion_id, $remitente_tipo, $remitente_id, $mensaje) {
    $conexion = conectar_bd();
    $sql = "INSERT INTO mensajes (contratacion_id, remitente_tipo, remitente_id, mensaje) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("isis", $contratacion_id, $remitente_tipo, $remitente_id, $mensaje);
    $exito = $stmt->execute();
    $id = $conexion->insert_id;
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error, 'id' => $id];
}

function obtener_mensajes_por_contratacion($contratacion_id) {
    $conexion = conectar_bd();
    $sql = "SELECT m.*,
            m.remitente_tipo as tipo_remitente,
            m.mensaje as contenido,
            CASE 
                WHEN m.remitente_tipo = 'cliente' THEN CONCAT(c.nombre, ' ', c.apellido)
                WHEN m.remitente_tipo = 'empresa' THEN e.nombre
            END as remitente_nombre
            FROM mensajes m
            LEFT JOIN clientes c ON m.remitente_tipo = 'cliente' AND m.remitente_id = c.id
            LEFT JOIN empresas e ON m.remitente_tipo = 'empresa' AND m.remitente_id = e.id
            WHERE m.contratacion_id = ?
            ORDER BY m.fecha_envio ASC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $contratacion_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $mensajes = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $mensajes[] = $row;
        }
    }
    $stmt->close();
    $conexion->close();
    return $mensajes;
}

function marcar_mensajes_como_leidos($contratacion_id, $tipo_receptor) {
    $conexion = conectar_bd();
    // Marcar como leídos los mensajes donde el receptor NO es el remitente
    $tipo_remitente = ($tipo_receptor == 'cliente') ? 'empresa' : 'cliente';
    $sql = "UPDATE mensajes SET leido = 1 
            WHERE contratacion_id = ? AND remitente_tipo = ? AND leido = 0";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("is", $contratacion_id, $tipo_remitente);
    $exito = $stmt->execute();
    $stmt->close();
    $conexion->close();
    return $exito;
}

function contar_mensajes_no_leidos($contratacion_id, $tipo_receptor) {
    $conexion = conectar_bd();
    $tipo_remitente = ($tipo_receptor == 'cliente') ? 'empresa' : 'cliente';
    $sql = "SELECT COUNT(*) as total FROM mensajes 
            WHERE contratacion_id = ? AND remitente_tipo = ? AND leido = 0";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("is", $contratacion_id, $tipo_remitente);
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

function obtener_ultimos_mensajes_cliente($cliente_id, $limite = 10) {
    $conexion = conectar_bd();
    $sql = "SELECT m.*, c.id as contratacion_id, c.servicio_id,
            s.nombre as servicio_nombre, e.nombre as empresa_nombre
            FROM mensajes m
            INNER JOIN contrataciones c ON m.contratacion_id = c.id
            INNER JOIN servicios s ON c.servicio_id = s.id
            INNER JOIN empresas e ON c.empresa_id = e.id
            WHERE c.cliente_id = ?
            ORDER BY m.fecha_envio DESC
            LIMIT ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $cliente_id, $limite);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $mensajes = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $mensajes[] = $row;
        }
    }
    $stmt->close();
    $conexion->close();
    return $mensajes;
}

function obtener_ultimos_mensajes_empresa($empresa_id, $limite = 10) {
    $conexion = conectar_bd();
    $sql = "SELECT m.*, c.id as contratacion_id, c.servicio_id,
            s.nombre as servicio_nombre, 
            CONCAT(cl.nombre, ' ', cl.apellido) as cliente_nombre
            FROM mensajes m
            INNER JOIN contrataciones c ON m.contratacion_id = c.id
            INNER JOIN servicios s ON c.servicio_id = s.id
            INNER JOIN clientes cl ON c.cliente_id = cl.id
            WHERE c.empresa_id = ?
            ORDER BY m.fecha_envio DESC
            LIMIT ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $empresa_id, $limite);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $mensajes = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $mensajes[] = $row;
        }
    }
    $stmt->close();
    $conexion->close();
    return $mensajes;
}

?>
