<?php
/**
 * Helper de Autenticación y Sesiones
 * Funciones para manejo de sesiones y autenticación
 */

require_once 'config.php';

/**
 * Inicia una sesión segura
 */
function iniciar_sesion_segura() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        
        // Configuración de seguridad de sesión
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', 0); // Cambiar a 1 si usas HTTPS
        ini_set('session.use_strict_mode', 1);
        
        session_start();
        
        // Verificar timeout de sesión
        if (isset($_SESSION['ultima_actividad'])) {
            if (time() - $_SESSION['ultima_actividad'] > SESSION_TIMEOUT) {
                cerrar_sesion();
                return false;
            }
        }
        
        $_SESSION['ultima_actividad'] = time();
        
        // Regenerar ID de sesión periódicamente
        if (!isset($_SESSION['creada'])) {
            $_SESSION['creada'] = time();
        } else if (time() - $_SESSION['creada'] > 1800) { // cada 30 minutos
            session_regenerate_id(true);
            $_SESSION['creada'] = time();
        }
    }
    
    return true;
}

/**
 * Verifica si hay una sesión activa
 * 
 * @return bool True si hay sesión activa
 */
function hay_sesion_activa() {
    iniciar_sesion_segura();
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

/**
 * Verifica si el usuario es una empresa
 * 
 * @return bool True si es empresa
 */
function es_empresa() {
    return hay_sesion_activa() && isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'empresa';
}

/**
 * Verifica si el usuario es un cliente
 * 
 * @return bool True si es cliente
 */
function es_cliente() {
    return hay_sesion_activa() && isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'cliente';
}

/**
 * Obtiene el ID del usuario logueado
 * 
 * @return int|null ID del usuario o null si no hay sesión
 */
function obtener_usuario_id() {
    return hay_sesion_activa() ? $_SESSION['usuario_id'] : null;
}

/**
 * Obtiene el tipo de usuario logueado
 * 
 * @return string|null Tipo de usuario ('empresa' o 'cliente') o null
 */
function obtener_tipo_usuario() {
    return hay_sesion_activa() ? $_SESSION['tipo_usuario'] : null;
}

/**
 * Obtiene el email del usuario logueado
 * 
 * @return string|null Email del usuario o null
 */
function obtener_usuario_email() {
    return hay_sesion_activa() && isset($_SESSION['email']) ? $_SESSION['email'] : null;
}

/**
 * Obtiene el nombre del usuario logueado
 * 
 * @return string|null Nombre del usuario o null
 */
function obtener_usuario_nombre() {
    return hay_sesion_activa() && isset($_SESSION['nombre']) ? $_SESSION['nombre'] : null;
}

/**
 * Obtiene todos los datos del usuario logueado
 * 
 * @return array|null Array con datos del usuario o null
 */
function obtener_datos_usuario() {
    if (!hay_sesion_activa()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['usuario_id'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'tipo' => $_SESSION['tipo_usuario'] ?? null,
        'nombre' => $_SESSION['nombre'] ?? null,
        'empresa_id' => $_SESSION['empresa_id'] ?? null,
        'cliente_id' => $_SESSION['cliente_id'] ?? null
    ];
}

/**
 * Establece la sesión del usuario
 * 
 * @param int $usuario_id ID del usuario
 * @param string $email Email del usuario
 * @param string $tipo_usuario Tipo de usuario ('empresa' o 'cliente')
 * @param string $nombre Nombre del usuario
 * @param int|null $entidad_id ID de la empresa o cliente
 */
function establecer_sesion($usuario_id, $email, $tipo_usuario, $nombre, $entidad_id = null) {
    iniciar_sesion_segura();
    
    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['email'] = $email;
    $_SESSION['tipo_usuario'] = $tipo_usuario;
    $_SESSION['nombre'] = $nombre;
    
    if ($tipo_usuario === 'empresa') {
        $_SESSION['empresa_id'] = $entidad_id;
    } elseif ($tipo_usuario === 'cliente') {
        $_SESSION['cliente_id'] = $entidad_id;
    }
    
    $_SESSION['creada'] = time();
    $_SESSION['ultima_actividad'] = time();
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
}

/**
 * Cierra la sesión del usuario
 */
function cerrar_sesion() {
    // Iniciar la sesión si no está iniciada para poder destruirla
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_start();
    }
    
    // Limpiar todas las variables de sesión
    $_SESSION = [];
    
    // Eliminar la cookie de sesión si existe
    if (isset($_COOKIE[session_name()])) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), 
            '', 
            time() - 42000,
            $params["path"], 
            $params["domain"], 
            $params["secure"], 
            $params["httponly"]
        );
    }
    
    // Destruir la sesión
    session_destroy();
}

/**
 * Requiere autenticación - redirige al login si no hay sesión
 * 
 * @param string $redireccion_login URL del login
 */
function requiere_autenticacion($redireccion_login = 'login_page.php') {
    if (!hay_sesion_activa()) {
        header("Location: $redireccion_login?error=" . urlencode('Debe iniciar sesión'));
        exit;
    }
}

/**
 * Requiere que el usuario sea empresa
 * 
 * @param string $redireccion_error URL de error
 */
function requiere_ser_empresa($redireccion_error = 'error_page.php') {
    requiere_autenticacion();
    
    if (!es_empresa()) {
        header("Location: $redireccion_error?error=" . urlencode('Acceso denegado: solo para empresas'));
        exit;
    }
}

/**
 * Requiere que el usuario sea cliente
 * 
 * @param string $redireccion_error URL de error
 */
function requiere_ser_cliente($redireccion_error = 'error_page.php') {
    requiere_autenticacion();
    
    if (!es_cliente()) {
        header("Location: $redireccion_error?error=" . urlencode('Acceso denegado: solo para clientes'));
        exit;
    }
}

/**
 * Verifica si el usuario es propietario de un recurso
 * 
 * @param int $empresa_id ID de la empresa
 * @return bool True si el usuario es el propietario
 */
function es_propietario_empresa($empresa_id) {
    return es_empresa() && isset($_SESSION['empresa_id']) && $_SESSION['empresa_id'] == $empresa_id;
}

/**
 * Verifica si el usuario es propietario cliente
 * 
 * @param int $cliente_id ID del cliente
 * @return bool True si el usuario es el propietario
 */
function es_propietario_cliente($cliente_id) {
    return es_cliente() && isset($_SESSION['cliente_id']) && $_SESSION['cliente_id'] == $cliente_id;
}

/**
 * Verifica permisos para editar un servicio
 * 
 * @param int $empresa_id ID de la empresa dueña del servicio
 * @return bool True si tiene permisos
 */
function puede_editar_servicio($empresa_id) {
    return es_propietario_empresa($empresa_id);
}

/**
 * Verifica permisos para responder reseña
 * 
 * @param int $empresa_id ID de la empresa
 * @return bool True si puede responder
 */
function puede_responder_resena($empresa_id) {
    return es_propietario_empresa($empresa_id);
}

/**
 * Verifica permisos para hacer reseña
 * 
 * @param int $cliente_id ID del cliente
 * @return bool True si puede hacer reseña
 */
function puede_hacer_resena($cliente_id) {
    return es_propietario_cliente($cliente_id);
}

/**
 * Verifica si hay sesión y redirige según tipo de usuario
 * 
 * @param string $url_empresa URL para empresas
 * @param string $url_cliente URL para clientes
 */
function redirigir_segun_tipo($url_empresa = '../Vista/empresas.php', $url_cliente = '../Vista/servicios.php') {
    if (!hay_sesion_activa()) {
        return;
    }
    
    if (es_empresa()) {
        header("Location: $url_empresa");
        exit;
    } elseif (es_cliente()) {
        header("Location: $url_cliente");
        exit;
    }
}

/**
 * Previene acceso si ya hay sesión activa
 * 
 * @param string $redireccion URL de redirección
 */
function prevenir_si_autenticado($redireccion = '../Vista/index.html') {
    if (hay_sesion_activa()) {
        redirigir_segun_tipo();
    }
}

/**
 * Registra actividad del usuario (opcional - para auditoría)
 * 
 * @param string $accion Acción realizada
 * @param string $detalle Detalle de la acción
 */
function registrar_actividad($accion, $detalle = '') {
    if (!hay_sesion_activa()) {
        return;
    }
    
    $usuario_id = obtener_usuario_id();
    $tipo = obtener_tipo_usuario();
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    $mensaje = "Usuario #$usuario_id ($tipo) - $accion";
    if (!empty($detalle)) {
        $mensaje .= " - $detalle";
    }
    $mensaje .= " [IP: $ip]";
    
    log_evento($mensaje, 'actividad');
}

/**
 * Verifica si la sesión proviene de la misma IP y User Agent
 * Medida de seguridad adicional
 * 
 * @return bool True si coinciden
 */
function verificar_integridad_sesion() {
    if (!hay_sesion_activa()) {
        return false;
    }
    
    $ip_actual = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $ua_actual = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $ip_sesion = $_SESSION['ip'] ?? null;
    $ua_sesion = $_SESSION['user_agent'] ?? null;
    
    // En producción, podrías hacer esto más estricto
    // Por ahora solo advertimos
    if ($ip_sesion !== $ip_actual || $ua_sesion !== $ua_actual) {
        log_evento("Posible sesión comprometida - Usuario #" . obtener_usuario_id(), 'warning');
        // Opcionalmente: cerrar_sesion();
        // return false;
    }
    
    return true;
}

/**
 * Actualiza la última actividad de la sesión
 */
function actualizar_actividad_sesion() {
    if (hay_sesion_activa()) {
        $_SESSION['ultima_actividad'] = time();
    }
}

/**
 * Obtiene el tiempo restante de sesión en minutos
 * 
 * @return int Minutos restantes
 */
function tiempo_restante_sesion() {
    if (!hay_sesion_activa() || !isset($_SESSION['ultima_actividad'])) {
        return 0;
    }
    
    $tiempo_transcurrido = time() - $_SESSION['ultima_actividad'];
    $tiempo_restante = SESSION_TIMEOUT - $tiempo_transcurrido;
    
    return max(0, floor($tiempo_restante / 60));
}

?>
