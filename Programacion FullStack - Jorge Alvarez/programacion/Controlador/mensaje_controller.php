<?php
require_once '../Modelo/mensaje.php';
require_once '../Modelo/validacion.php';
require_once '../Modelo/auth.php';
require_once '../Modelo/usuario.php';

// Iniciar sesión de forma segura
iniciar_sesion_segura();

// Enviar mensaje
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'enviar') {
    // Verificar autenticación
    if (!hay_sesion_activa()) {
        header("Location: ../Controlador/login_page.php");
        exit;
    }
    
    $contratacion_id = $_POST['contratacion_id'] ?? '';
    $mensaje = $_POST['contenido'] ?? '';  // Cambiado de 'mensaje' a 'contenido'

    // Obtener datos del usuario logueado
    $datos_usuario = obtener_datos_completos_usuario($_SESSION['usuario_id']);
    
    // Determinar tipo y ID del remitente
    $remitente_tipo = '';
    $remitente_id = '';
    
    if ($datos_usuario && $datos_usuario['tipo_usuario'] == 'cliente') {
        $remitente_tipo = 'cliente';
        $remitente_id = $datos_usuario['id'];
    } elseif ($datos_usuario && $datos_usuario['tipo_usuario'] == 'empresa') {
        $remitente_tipo = 'empresa';
        $remitente_id = $datos_usuario['id'];
    } else {
    header("Location: contratacion_detalle_page.php?id=".$contratacion_id ."&error=". urlencode("No se pudo determinar el tipo de usuario"));
        exit;
    }

    // VALIDACIÓN COMPLETA
    $validacion = validar_mensaje_completo($mensaje);
    
    if (!$validacion['valido']) {
        $errores = implode('. ', $validacion['errores']);
        header("Location: contratacion_detalle_page.php?id=" . $contratacion_id . "&error=" . urlencode($errores));
        exit;
    }

    $resultado = enviar_mensaje($contratacion_id, $remitente_tipo, $remitente_id, $mensaje);
    
    if ($resultado['exito']) {
        header("Location: contratacion_detalle_page.php?id=" . $contratacion_id . "&mensaje=Mensaje enviado#mensajes");
        exit;
    } else {
        header("Location: contratacion_detalle_page.php?id=" . $contratacion_id . "&error=Error al enviar mensaje: " . $resultado['error']);
        exit;
    }
}

// Marcar mensajes como leídos (AJAX)
if (isset($_GET['accion']) && $_GET['accion'] == 'marcar_leidos' && isset($_GET['contratacion_id']) && isset($_GET['tipo'])) {
    $contratacion_id = $_GET['contratacion_id'];
    $tipo_receptor = $_GET['tipo'];
    
    $exito = marcar_mensajes_como_leidos($contratacion_id, $tipo_receptor);
    
    header('Content-Type: application/json');
    echo json_encode(['exito' => $exito]);
    exit;
}

?>
