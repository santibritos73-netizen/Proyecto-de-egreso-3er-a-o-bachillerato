<?php
require_once '../Modelo/resena.php';
require_once '../Modelo/validacion.php';
require_once '../Modelo/auth.php';
require_once '../Modelo/contratacion.php';

// Iniciar sesión de forma segura
iniciar_sesion_segura();

// Crear reseña
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $contratacion_id = $_POST['contratacion_id'] ?? '';
    $servicio_id = $_POST['servicio_id'] ?? '';
    $cliente_id = $_POST['cliente_id'] ?? '';
    $calificacion = $_POST['calificacion'] ?? 0;
    $titulo = $_POST['titulo'] ?? null;  // Título es opcional
    $comentario = $_POST['comentario'] ?? '';

    // Obtener la contratación para sacar el empresa_id
    $contratacion = obtener_contratacion_por_id($contratacion_id);
    if (!$contratacion) {
        header("Location: contratacion_detalle_page.php?id=" . $contratacion_id . "&error=" . urlencode("Contratación no encontrada"));
        exit;
    }
    $empresa_id = $contratacion['empresa_id'];

    // VALIDACIÓN - Solo validar calificación y comentario (título es opcional)
    $errores = [];
    
    if ($calificacion < 1 || $calificacion > 5) {
        $errores[] = "La calificación debe ser entre 1 y 5";
    }
    
    if (strlen(trim($comentario)) < 10) {
        $errores[] = "El comentario debe tener al menos 10 caracteres";
    }
    
    if (count($errores) > 0) {
        $errores_texto = implode('. ', $errores);
        header("Location: contratacion_detalle_page.php?id=" . $contratacion_id . "&error=" . urlencode($errores_texto));
        exit;
    }

    // Verificar si puede reseñar
    $verificacion = verificar_puede_resenar($contratacion_id, $cliente_id);
    if (!$verificacion['puede']) {
        header("Location: contratacion_detalle_page.php?id=" . $contratacion_id . "&error=" . $verificacion['mensaje']);
        exit;
    }

    $resultado = crear_resena($contratacion_id, $servicio_id, $cliente_id, $empresa_id, $calificacion, $titulo, $comentario);
    
    if ($resultado['exito']) {
        header("Location: contratacion_detalle_page.php?id=" . $contratacion_id . "&mensaje=Reseña publicada exitosamente");
        exit;
    } else {
        header("Location: contratacion_detalle_page.php?id=" . $contratacion_id . "&error=Error al crear reseña: " . $resultado['error']);
        exit;
    }
}

// Responder reseña (empresa)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'responder') {
    $resena_id = $_POST['resena_id'] ?? '';
    $respuesta_empresa = $_POST['respuesta_empresa'] ?? '';
    $contratacion_id = $_POST['contratacion_id'] ?? '';

    // VALIDACIÓN DEL MENSAJE
    $validacion = validar_mensaje_completo($respuesta_empresa);
    
    if (!$validacion['valido']) {
        $errores = implode('. ', $validacion['errores']);
        header("Location: contratacion_detalle_page.php?id=" . $contratacion_id . "&error=" . urlencode($errores));
        exit;
    }

    $resultado = responder_resena($resena_id, $respuesta_empresa);
    
    if ($resultado['exito']) {
        header("Location: resenas_page.php?mensaje=Respuesta publicada exitosamente");
        exit;
    } else {
        header("Location: resenas_page.php?error=Error al publicar respuesta: " . $resultado['error']);
        exit;
    }
}

// Aprobar reseña
if (isset($_GET['accion']) && $_GET['accion'] == 'aprobar' && isset($_GET['id'])) {
    $resena_id = $_GET['id'];
    
    $resultado = aprobar_resena($resena_id);
    
    if ($resultado['exito']) {
        header("Location: resenas_page.php?mensaje=" . urlencode("Reseña aprobada exitosamente"));
        exit;
    } else {
        header("Location: resenas_page.php?error=" . urlencode("Error al aprobar reseña: " . $resultado['error']));
        exit;
    }
}

// Desaprobar reseña
if (isset($_GET['accion']) && $_GET['accion'] == 'desaprobar' && isset($_GET['id'])) {
    $resena_id = $_GET['id'];
    
    $resultado = desaprobar_resena($resena_id);
    
    if ($resultado['exito']) {
        header("Location: resenas_page.php?mensaje=" . urlencode("Reseña desaprobada"));
        exit;
    } else {
        header("Location: resenas_page.php?error=" . urlencode("Error al desaprobar reseña: " . $resultado['error']));
        exit;
    }
}

// Eliminar reseña
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $resena_id = $_GET['id'];
    
    $resultado = eliminar_resena($resena_id);
    
    if ($resultado['exito']) {
        header("Location: resenas_page.php?mensaje=" . urlencode("Reseña eliminada exitosamente"));
        exit;
    } else {
        header("Location: resenas_page.php?error=" . urlencode("Error al eliminar reseña: " . $resultado['error']));
        exit;
    }
}

?>

