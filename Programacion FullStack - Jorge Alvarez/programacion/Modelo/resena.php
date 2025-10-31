<?php

require_once 'usuario.php';

// ==========================================
// FUNCIONES DE RESEÑAS Y CALIFICACIONES
// ==========================================

function crear_resena($contratacion_id, $servicio_id, $cliente_id, $empresa_id, $calificacion, $titulo, $comentario) {
    $conexion = conectar_bd();
    $sql = "INSERT INTO resenas (contratacion_id, servicio_id, cliente_id, empresa_id, calificacion, titulo, comentario) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iiiiiss", $contratacion_id, $servicio_id, $cliente_id, $empresa_id, $calificacion, $titulo, $comentario);
    $exito = $stmt->execute();
    $id = $conexion->insert_id;
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error, 'id' => $id];
}

function obtener_resenas_por_servicio($servicio_id) {
    $conexion = conectar_bd();
    $sql = "SELECT r.*, 
            s.titulo as titulo_servicio,
            CONCAT(c.nombre, ' ', c.apellido) as nombre_cliente,
            e.nombre as nombre_empresa
            FROM resenas r
            INNER JOIN servicios s ON r.servicio_id = s.id
            INNER JOIN clientes c ON r.cliente_id = c.id
            INNER JOIN empresas e ON r.empresa_id = e.id
            WHERE r.servicio_id = ?
            ORDER BY r.fecha_resena DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $servicio_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $resenas = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $resenas[] = $row;
        }
    }
    $stmt->close();
    $conexion->close();
    return $resenas;
}

function obtener_resenas_por_empresa($empresa_id) {
    $conexion = conectar_bd();
    $sql = "SELECT r.*, 
            s.titulo as titulo_servicio,
            CONCAT(c.nombre, ' ', c.apellido) as nombre_cliente,
            e.nombre as nombre_empresa
            FROM resenas r
            INNER JOIN servicios s ON r.servicio_id = s.id
            INNER JOIN clientes c ON r.cliente_id = c.id
            INNER JOIN empresas e ON r.empresa_id = e.id
            WHERE r.empresa_id = ?
            ORDER BY r.fecha_resena DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $empresa_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $resenas = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $resenas[] = $row;
        }
    }
    $stmt->close();
    $conexion->close();
    return $resenas;
}

function obtener_todas_resenas() {
    $conexion = conectar_bd();
    $sql = "SELECT r.*, 
            s.titulo as titulo_servicio,
            CONCAT(c.nombre, ' ', c.apellido) as nombre_cliente,
            e.nombre as nombre_empresa
            FROM resenas r
            INNER JOIN servicios s ON r.servicio_id = s.id
            INNER JOIN clientes c ON r.cliente_id = c.id
            INNER JOIN empresas e ON r.empresa_id = e.id
            ORDER BY r.fecha_resena DESC";
    $resultado = $conexion->query($sql);
    $resenas = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $resenas[] = $row;
        }
    }
    $conexion->close();
    return $resenas;
}

function responder_resena($resena_id, $respuesta_empresa) {
    $conexion = conectar_bd();
    $sql = "UPDATE resenas SET respuesta_empresa = ?, fecha_respuesta = NOW() WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $respuesta_empresa, $resena_id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

function verificar_puede_resenar($contratacion_id, $cliente_id) {
    $conexion = conectar_bd();
    
    // Verificar que la contratación esté completada
    $sql = "SELECT estado FROM contrataciones WHERE id = ? AND cliente_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $contratacion_id, $cliente_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows == 0) {
        $stmt->close();
        $conexion->close();
        return ['puede' => false, 'mensaje' => 'Contratación no encontrada'];
    }
    
    $row = $resultado->fetch_assoc();
    if ($row['estado'] != 'completado') {
        $stmt->close();
        $conexion->close();
        return ['puede' => false, 'mensaje' => 'Solo puedes reseñar servicios completados'];
    }
    $stmt->close();
    
    // Verificar que no haya reseña previa
    $sql = "SELECT id FROM resenas WHERE contratacion_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $contratacion_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $stmt->close();
        $conexion->close();
        return ['puede' => false, 'mensaje' => 'Ya has reseñado este servicio'];
    }
    
    $stmt->close();
    $conexion->close();
    return ['puede' => true, 'mensaje' => 'Puede reseñar'];
}

function obtener_calificacion_promedio_servicio($servicio_id) {
    $conexion = conectar_bd();
    $sql = "SELECT AVG(calificacion) as promedio, COUNT(*) as total FROM resenas WHERE servicio_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $servicio_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $datos = ['promedio' => 0, 'total' => 0];
    if ($resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
        $datos['promedio'] = round($datos['promedio'], 1);
    }
    $stmt->close();
    $conexion->close();
    return $datos;
}

function obtener_calificacion_promedio_empresa($empresa_id) {
    $conexion = conectar_bd();
    $sql = "SELECT AVG(calificacion) as promedio, COUNT(*) as total FROM resenas WHERE empresa_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $empresa_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $datos = ['promedio' => 0, 'total' => 0];
    if ($resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
        $datos['promedio'] = round($datos['promedio'], 1);
    }
    $stmt->close();
    $conexion->close();
    return $datos;
}

function obtener_distribucion_calificaciones_servicio($servicio_id) {
    $conexion = conectar_bd();
    $sql = "SELECT calificacion, COUNT(*) as total 
            FROM resenas 
            WHERE servicio_id = ? 
            GROUP BY calificacion 
            ORDER BY calificacion DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $servicio_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $distribucion = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $distribucion[$row['calificacion']] = $row['total'];
        }
    }
    $stmt->close();
    $conexion->close();
    return $distribucion;
}

function aprobar_resena($resena_id) {
    $conexion = conectar_bd();
    $sql = "UPDATE resenas SET aprobada = 1 WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $resena_id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

function desaprobar_resena($resena_id) {
    $conexion = conectar_bd();
    $sql = "UPDATE resenas SET aprobada = 0 WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $resena_id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

function eliminar_resena($resena_id) {
    $conexion = conectar_bd();
    $sql = "DELETE FROM resenas WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $resena_id);
    $exito = $stmt->execute();
    $error = $conexion->error;
    $stmt->close();
    $conexion->close();
    return ['exito' => $exito, 'error' => $error];
}

?>

