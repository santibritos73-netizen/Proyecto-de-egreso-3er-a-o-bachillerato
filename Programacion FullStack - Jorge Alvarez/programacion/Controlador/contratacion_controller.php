<?php
require_once '../Modelo/auth.php';
require_once '../Modelo/validacion.php';
require_once '../Modelo/helpers.php';
require_once '../Modelo/contratacion.php';

iniciar_sesion_segura();
requiere_autenticacion();

// Crear nueva contratación
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    requiere_ser_cliente();
    
    $servicio_id = $_POST['servicio_id'] ?? '';
    $cliente_id = $_POST['cliente_id'] ?? '';
    $empresa_id = $_POST['empresa_id'] ?? '';
    $precio_acordado = $_POST['precio_acordado'] ?? 0;
    $notas_cliente = $_POST['notas_cliente'] ?? '';

    // Validar que el cliente sea el usuario logueado
    if (!es_propietario_cliente($cliente_id)) {
        redirigir_con_mensaje('servicios_page.php', 'No puede crear contrataciones para otro cliente', 'error');
    }

    // Validar precio
    $validacion_precio = validar_precio($precio_acordado, PRECIO_MINIMO, PRECIO_MAXIMO);
    if (!$validacion_precio['valido']) {
        redirigir_con_mensaje('servicios_page.php', $validacion_precio['mensaje'], 'error');
    }

    // Validar notas si existen
    if (!empty($notas_cliente)) {
        $validacion_mensaje = validar_mensaje_completo($notas_cliente);
        if (!$validacion_mensaje['valido']) {
            $errores = implode('. ', $validacion_mensaje['errores']);
            redirigir_con_mensaje('servicios_page.php', $errores, 'error');
        }
    }

    $resultado = crear_contratacion($servicio_id, $cliente_id, $empresa_id, $precio_acordado, $notas_cliente);
    
    if ($resultado['exito']) {
        registrar_actividad('Crear contratación', "Servicio ID: $servicio_id");
        redirigir_con_mensaje('contrataciones_page.php?id=' . $resultado['id'], 'Contratación creada exitosamente');
    } else {
        redirigir_con_mensaje('contrataciones_page.php', 'Error al crear contratación: ' . $resultado['error'], 'error');
    }
}

// Actualizar estado de contratación
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'actualizar_estado') {
    $id = $_POST['id'] ?? '';
    $nuevo_estado = $_POST['estado'] ?? '';
    $notas_proveedor = $_POST['notas_proveedor'] ?? null;
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;

    // Validar estado
    if (!valor_en_array($nuevo_estado, ESTADOS_CONTRATACION)) {
        redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, 'Estado no válido', 'error');
    }

    // Convertir valores vacíos a null
    $notas_proveedor = empty($notas_proveedor) ? null : $notas_proveedor;
    $fecha_inicio = empty($fecha_inicio) ? null : $fecha_inicio;
    $fecha_fin = empty($fecha_fin) ? null : $fecha_fin;

    // Validar notas si existen
    if (!empty($notas_proveedor)) {
        $validacion_mensaje = validar_mensaje_completo($notas_proveedor);
        if (!$validacion_mensaje['valido']) {
            $errores = implode('. ', $validacion_mensaje['errores']);
            redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, $errores, 'error');
        }
    }

    $resultado = actualizar_estado_contratacion($id, $nuevo_estado, $notas_proveedor, $fecha_inicio, $fecha_fin);
    
    if ($resultado['exito']) {
        registrar_actividad('Actualizar estado contratación', "ID: $id - Estado: $nuevo_estado");
        redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, 'Estado actualizado exitosamente');
    } else {
        redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, 'Error al actualizar: ' . $resultado['error'], 'error');
    }
}

// Aceptar contratación (para empresa)
if (isset($_GET['accion']) && $_GET['accion'] == 'aceptar' && isset($_GET['id'])) {
    requiere_ser_empresa();
    $id = $_GET['id'];
    $resultado = actualizar_estado_contratacion($id, 'aceptado');
    
    if ($resultado['exito']) {
        registrar_actividad('Aceptar contratación', "ID: $id");
        redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, 'Contratación aceptada');
    } else {
        redirigir_con_mensaje('contrataciones_page.php', 'Error al aceptar contratación', 'error');
    }
}

// Cambiar estado genérico (desde enlaces GET)
if (isset($_GET['accion']) && $_GET['accion'] == 'cambiar_estado' && isset($_GET['id']) && isset($_GET['estado'])) {
    $id = $_GET['id'];
    $nuevo_estado = $_GET['estado'];
    
    // Validar estado
    $estados_validos = ['solicitado', 'aceptado', 'en_progreso', 'completado', 'cancelado', 'rechazado'];
    if (!in_array($nuevo_estado, $estados_validos)) {
        redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, 'Estado no válido', 'error');
    }
    
    // Determinar fecha según el estado
    $fecha_inicio = null;
    $fecha_fin = null;
    
    if ($nuevo_estado == 'en_progreso') {
        $fecha_inicio = date('Y-m-d');
    } elseif ($nuevo_estado == 'completado') {
        $fecha_fin = date('Y-m-d');
    }
    
    $resultado = actualizar_estado_contratacion($id, $nuevo_estado, null, $fecha_inicio, $fecha_fin);
    
    if ($resultado['exito']) {
        $mensaje = '';
        switch ($nuevo_estado) {
            case 'aceptado': $mensaje = 'Contratación aceptada'; break;
            case 'rechazado': $mensaje = 'Contratación rechazada'; break;
            case 'en_progreso': $mensaje = 'Servicio iniciado'; break;
            case 'completado': $mensaje = 'Servicio completado'; break;
            case 'cancelado': $mensaje = 'Contratación cancelada'; break;
            default: $mensaje = 'Estado actualizado';
        }
        registrar_actividad('Cambiar estado contratación', "ID: $id - Estado: $nuevo_estado");
        redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, $mensaje);
    } else {
        redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, 'Error al actualizar: ' . $resultado['error'], 'error');
    }
}

// Rechazar contratación (para empresa)
if (isset($_GET['accion']) && $_GET['accion'] == 'rechazar' && isset($_GET['id'])) {
    requiere_ser_empresa();
    $id = $_GET['id'];
    $resultado = actualizar_estado_contratacion($id, 'rechazado');
    
    if ($resultado['exito']) {
        registrar_actividad('Rechazar contratación', "ID: $id");
        redirigir_con_mensaje('contrataciones_page.php', 'Contratación rechazada');
    } else {
        redirigir_con_mensaje('contrataciones_page.php', 'Error al rechazar contratación', 'error');
    }
}

// Iniciar progreso
if (isset($_GET['accion']) && $_GET['accion'] == 'iniciar' && isset($_GET['id'])) {
    requiere_ser_empresa();
    $id = $_GET['id'];
    $fecha_inicio = date('Y-m-d');
    $resultado = actualizar_estado_contratacion($id, 'en_progreso', null, $fecha_inicio);
    
    if ($resultado['exito']) {
        registrar_actividad('Iniciar contratación', "ID: $id");
        redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, 'Servicio iniciado');
    } else {
        redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, 'Error al iniciar', 'error');
    }
}

// Completar contratación
if (isset($_GET['accion']) && $_GET['accion'] == 'completar' && isset($_GET['id'])) {
    requiere_ser_empresa();
    $id = $_GET['id'];
    $fecha_fin = date('Y-m-d');
    $resultado = actualizar_estado_contratacion($id, 'completado', null, null, $fecha_fin);
    
    if ($resultado['exito']) {
        registrar_actividad('Completar contratación', "ID: $id");
        redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, 'Servicio completado');
    } else {
        redirigir_con_mensaje('contratacion_detalle_page.php?id=' . $id, 'Error al completar', 'error');
    }
}

// Cancelar contratación
if (isset($_GET['accion']) && $_GET['accion'] == 'cancelar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $resultado = actualizar_estado_contratacion($id, 'cancelado');
    
    if ($resultado['exito']) {
        registrar_actividad('Cancelar contratación', "ID: $id");
        redirigir_con_mensaje('contrataciones_page.php', 'Contratación cancelada');
    } else {
        redirigir_con_mensaje('contrataciones_page.php', 'Error al cancelar', 'error');
    }
}

?>
