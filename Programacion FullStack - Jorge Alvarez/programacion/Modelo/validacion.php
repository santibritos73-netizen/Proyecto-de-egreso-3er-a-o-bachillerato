<?php
/**
 * Sistema de Validación y Moderación de Contenido
 * Previene contenido inapropiado, spam y validaciones generales
 */

// Lista de palabras prohibidas (contenido inapropiado)
$palabras_prohibidas = [
    // Palabras ofensivas generales
    'puto', 'puta', 'mierda', 'idiota', 'estupido', 'imbecil', 'pendejo',
    'cabron', 'chingada', 'verga', 'coño', 'joder', 'gilipollas',
    
    // Contenido sexual inapropiado
    'sexo', 'porno', 'xxx', 'desnudo', 'escort', 'prostituta', 'prostituto',
    
    // Drogas y actividades ilegales
    'droga', 'cocaina', 'marihuana', 'heroina', 'metanfetamina', 'crack',
    'arma', 'pistola', 'rifle', 'explosivo', 'bomba',
    
    // Estafas comunes
    'gratis', 'free money', 'gana dinero rapido', 'piramide', 'multinivel',
    'click aqui', 'oferta limitada', 'promocion urgente',
    
    // Spam
    'viagra', 'casino', 'poker', 'apuesta', 'loteria', 'premio',
];

// Patrones sospechosos (regex)
$patrones_sospechosos = [
    '/(\w)\1{4,}/i',                    // Caracteres repetidos (aaaa, bbbb)
    '/[A-Z]{10,}/',                     // Muchas mayúsculas seguidas
    '/\${2,}|€{2,}|£{2,}/',            // Símbolos de moneda repetidos
    '/!!!+/',                           // Múltiples signos de exclamación
    '/\?{3,}/',                         // Múltiples signos de interrogación
    '/https?:\/\/[^\s]{50,}/',         // URLs muy largas (potencial phishing)
    '/\b\d{4}[\s-]?\d{4}[\s-]?\d{4}[\s-]?\d{4}\b/', // Números de tarjeta
    '/\b\d{3}-\d{2}-\d{4}\b/',         // Números de seguro social
];

/**
 * Valida que el texto no contenga contenido inapropiado
 * 
 * @param string $texto Texto a validar
 * @return array ['valido' => bool, 'mensaje' => string, 'palabras_encontradas' => array]
 */
function validar_contenido_apropiado($texto) {
    global $palabras_prohibidas, $patrones_sospechosos;
    
    $texto_lower = mb_strtolower($texto, 'UTF-8');
    $palabras_encontradas = [];
    
    // Verificar palabras prohibidas
    foreach ($palabras_prohibidas as $palabra) {
        if (stripos($texto_lower, $palabra) !== false) {
            $palabras_encontradas[] = $palabra;
        }
    }
    
    if (!empty($palabras_encontradas)) {
        return [
            'valido' => false,
            'mensaje' => 'El contenido contiene palabras o frases inapropiadas',
            'palabras_encontradas' => $palabras_encontradas
        ];
    }
    
    // Verificar patrones sospechosos
    foreach ($patrones_sospechosos as $patron) {
        if (preg_match($patron, $texto)) {
            return [
                'valido' => false,
                'mensaje' => 'El contenido contiene patrones sospechosos o spam',
                'palabras_encontradas' => []
            ];
        }
    }
    
    return [
        'valido' => true,
        'mensaje' => 'Contenido válido',
        'palabras_encontradas' => []
    ];
}

/**
 * Valida un email
 * 
 * @param string $email Email a validar
 * @return array ['valido' => bool, 'mensaje' => string]
 */
function validar_email($email) {
    if (empty($email)) {
        return ['valido' => false, 'mensaje' => 'El email es requerido'];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['valido' => false, 'mensaje' => 'El formato del email no es válido'];
    }
    
    // Verificar dominios temporales/desechables comunes
    $dominios_prohibidos = ['tempmail.com', 'throwaway.email', '10minutemail.com', 'guerrillamail.com'];
    $dominio = substr(strrchr($email, "@"), 1);
    
    if (in_array($dominio, $dominios_prohibidos)) {
        return ['valido' => false, 'mensaje' => 'No se permiten correos temporales o desechables'];
    }
    
    return ['valido' => true, 'mensaje' => 'Email válido'];
}

/**
 * Valida un teléfono (formato flexible)
 * 
 * @param string $telefono Teléfono a validar
 * @return array ['valido' => bool, 'mensaje' => string]
 */
function validar_telefono($telefono) {
    if (empty($telefono)) {
        return ['valido' => false, 'mensaje' => 'El teléfono es requerido'];
    }
    
    // Remover espacios, guiones y paréntesis
    $telefono_limpio = preg_replace('/[\s\-\(\)]/', '', $telefono);
    
    // Verificar que solo contenga números y opcionalmente un + al inicio
    if (!preg_match('/^\+?\d{8,15}$/', $telefono_limpio)) {
        return ['valido' => false, 'mensaje' => 'El teléfono debe contener entre 8 y 15 dígitos'];
    }
    
    return ['valido' => true, 'mensaje' => 'Teléfono válido'];
}

/**
 * Valida un precio
 * 
 * @param mixed $precio Precio a validar
 * @param float $minimo Precio mínimo permitido
 * @param float $maximo Precio máximo permitido
 * @return array ['valido' => bool, 'mensaje' => string]
 */
function validar_precio($precio, $minimo = 0.01, $maximo = 1000000) {
    if (!is_numeric($precio)) {
        return ['valido' => false, 'mensaje' => 'El precio debe ser un número válido'];
    }
    
    $precio = floatval($precio);
    
    if ($precio < $minimo) {
        return ['valido' => false, 'mensaje' => "El precio mínimo es $" . number_format($minimo, 2)];
    }
    
    if ($precio > $maximo) {
        return ['valido' => false, 'mensaje' => "El precio máximo es $" . number_format($maximo, 2)];
    }
    
    return ['valido' => true, 'mensaje' => 'Precio válido'];
}

/**
 * Valida una URL
 * 
 * @param string $url URL a validar
 * @return array ['valido' => bool, 'mensaje' => string]
 */
function validar_url($url) {
    if (empty($url)) {
        return ['valido' => true, 'mensaje' => 'URL vacía (opcional)']; // URL es opcional
    }
    
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return ['valido' => false, 'mensaje' => 'El formato de la URL no es válido'];
    }
    
    // Verificar que use http o https
    if (!preg_match('/^https?:\/\//i', $url)) {
        return ['valido' => false, 'mensaje' => 'La URL debe comenzar con http:// o https://'];
    }
    
    return ['valido' => true, 'mensaje' => 'URL válida'];
}

/**
 * Valida longitud de texto
 * 
 * @param string $texto Texto a validar
 * @param int $min_length Longitud mínima
 * @param int $max_length Longitud máxima
 * @param string $nombre_campo Nombre del campo para mensajes
 * @return array ['valido' => bool, 'mensaje' => string]
 */
function validar_longitud($texto, $min_length, $max_length, $nombre_campo = 'El texto') {
    $longitud = mb_strlen($texto, 'UTF-8');
    
    if ($longitud < $min_length) {
        return ['valido' => false, 'mensaje' => "$nombre_campo debe tener al menos $min_length caracteres"];
    }
    
    if ($longitud > $max_length) {
        return ['valido' => false, 'mensaje' => "$nombre_campo no puede exceder $max_length caracteres"];
    }
    
    return ['valido' => true, 'mensaje' => 'Longitud válida'];
}

/**
 * Valida una calificación (1-5)
 * 
 * @param mixed $calificacion Calificación a validar
 * @return array ['valido' => bool, 'mensaje' => string]
 */
function validar_calificacion($calificacion) {
    if (!is_numeric($calificacion)) {
        return ['valido' => false, 'mensaje' => 'La calificación debe ser un número'];
    }
    
    $calificacion = intval($calificacion);
    
    if ($calificacion < 1 || $calificacion > 5) {
        return ['valido' => false, 'mensaje' => 'La calificación debe estar entre 1 y 5'];
    }
    
    return ['valido' => true, 'mensaje' => 'Calificación válida'];
}

/**
 * Sanitiza texto para prevenir XSS
 * 
 * @param string $texto Texto a sanitizar
 * @return string Texto sanitizado
 */
function sanitizar_texto($texto) {
    // Eliminar etiquetas HTML y PHP
    $texto = strip_tags($texto);
    
    // Convertir caracteres especiales a entidades HTML
    $texto = htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
    
    // Eliminar espacios en blanco extra
    $texto = trim($texto);
    
    return $texto;
}

/**
 * Detecta posible spam basado en múltiples criterios
 * 
 * @param string $texto Texto a analizar
 * @return array ['es_spam' => bool, 'puntuacion' => int, 'razones' => array]
 */
function detectar_spam($texto) {
    $puntuacion_spam = 0;
    $razones = [];
    
    // Muchas mayúsculas (más del 50%)
    $mayusculas = preg_match_all('/[A-Z]/', $texto, $matches);
    $total_letras = preg_match_all('/[a-zA-Z]/', $texto, $matches);
    if ($total_letras > 0 && ($mayusculas / $total_letras) > 0.5) {
        $puntuacion_spam += 2;
        $razones[] = 'Exceso de mayúsculas';
    }
    
    // Muchos signos de exclamación o interrogación
    if (preg_match_all('/[!?]/', $texto, $matches) > 5) {
        $puntuacion_spam += 1;
        $razones[] = 'Exceso de signos de puntuación';
    }
    
    // Enlaces múltiples
    if (preg_match_all('/https?:\/\//', $texto, $matches) > 2) {
        $puntuacion_spam += 3;
        $razones[] = 'Múltiples enlaces';
    }
    
    // Palabras muy cortas repetidas
    if (preg_match('/\b(\w{1,2})\b(?:\s+\1\b){3,}/', $texto)) {
        $puntuacion_spam += 2;
        $razones[] = 'Palabras repetidas sospechosamente';
    }
    
    // Números de teléfono o emails múltiples
    $telefonos = preg_match_all('/\d{3}[\s-]?\d{3}[\s-]?\d{4}/', $texto, $matches);
    $emails = preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $texto, $matches);
    if ($telefonos + $emails > 2) {
        $puntuacion_spam += 2;
        $razones[] = 'Múltiples contactos (posible spam)';
    }
    
    $es_spam = $puntuacion_spam >= 5;
    
    return [
        'es_spam' => $es_spam,
        'puntuacion' => $puntuacion_spam,
        'razones' => $razones
    ];
}

/**
 * Valida que no haya duplicados recientes
 * 
 * @param string $texto Texto a verificar
 * @param string $tabla Tabla a consultar
 * @param string $campo Campo a comparar
 * @param int $minutos_espera Minutos de espera entre envíos similares
 * @return array ['es_duplicado' => bool, 'mensaje' => string]
 */
function verificar_duplicado_reciente($texto, $tabla, $campo, $minutos_espera = 5) {
    require_once 'usuario.php';
    $conexion = conectar_bd();
    
    $tiempo_limite = date('Y-m-d H:i:s', strtotime("-$minutos_espera minutes"));
    
    $sql = "SELECT COUNT(*) as total FROM $tabla 
            WHERE $campo = ? AND fecha_registro > ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $texto, $tiempo_limite);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
    
    $stmt->close();
    $conexion->close();
    
    if ($fila['total'] > 0) {
        return [
            'es_duplicado' => true,
            'mensaje' => "Contenido duplicado detectado. Espera $minutos_espera minutos antes de enviar contenido similar"
        ];
    }
    
    return [
        'es_duplicado' => false,
        'mensaje' => 'No se detectaron duplicados'
    ];
}

/**
 * Validación completa de un servicio
 * 
 * @param array $datos Datos del servicio
 * @return array ['valido' => bool, 'errores' => array]
 */
function validar_servicio_completo($datos) {
    $errores = [];
    
    // Validar nombre
    $val_longitud = validar_longitud($datos['nombre'], 3, 100, 'El nombre del servicio');
    if (!$val_longitud['valido']) {
        $errores[] = $val_longitud['mensaje'];
    }
    
    $val_contenido = validar_contenido_apropiado($datos['nombre']);
    if (!$val_contenido['valido']) {
        $errores[] = 'Nombre: ' . $val_contenido['mensaje'];
    }
    
    // Validar descripción
    if (!empty($datos['descripcion'])) {
        $val_desc_longitud = validar_longitud($datos['descripcion'], 10, 1000, 'La descripción');
        if (!$val_desc_longitud['valido']) {
            $errores[] = $val_desc_longitud['mensaje'];
        }
        
        $val_desc_contenido = validar_contenido_apropiado($datos['descripcion']);
        if (!$val_desc_contenido['valido']) {
            $errores[] = 'Descripción: ' . $val_desc_contenido['mensaje'];
        }
        
        // Detectar spam
        $spam = detectar_spam($datos['descripcion']);
        if ($spam['es_spam']) {
            $errores[] = 'Descripción sospechosa de spam: ' . implode(', ', $spam['razones']);
        }
    }
    
    // Validar precio
    $val_precio = validar_precio($datos['precio'], 0.01, 1000000);
    if (!$val_precio['valido']) {
        $errores[] = $val_precio['mensaje'];
    }
    
    return [
        'valido' => empty($errores),
        'errores' => $errores
    ];
}

/**
 * Validación completa de una empresa
 * 
 * @param array $datos Datos de la empresa
 * @return array ['valido' => bool, 'errores' => array]
 */
function validar_empresa_completa($datos) {
    $errores = [];
    
    // Validar nombre
    $val_nombre = validar_longitud($datos['nombre'], 2, 100, 'El nombre de la empresa');
    if (!$val_nombre['valido']) {
        $errores[] = $val_nombre['mensaje'];
    }
    
    $val_contenido = validar_contenido_apropiado($datos['nombre']);
    if (!$val_contenido['valido']) {
        $errores[] = 'Nombre: ' . $val_contenido['mensaje'];
    }
    
    // Validar email
    $val_email = validar_email($datos['email']);
    if (!$val_email['valido']) {
        $errores[] = $val_email['mensaje'];
    }
    
    // Validar teléfono
    $val_telefono = validar_telefono($datos['telefono']);
    if (!$val_telefono['valido']) {
        $errores[] = $val_telefono['mensaje'];
    }
    
    // Validar sitio web (opcional)
    if (!empty($datos['sitio_web'])) {
        $val_url = validar_url($datos['sitio_web']);
        if (!$val_url['valido']) {
            $errores[] = $val_url['mensaje'];
        }
    }
    
    // Validar dirección
    if (!empty($datos['direccion'])) {
        $val_direccion = validar_longitud($datos['direccion'], 5, 200, 'La dirección');
        if (!$val_direccion['valido']) {
            $errores[] = $val_direccion['mensaje'];
        }
    }
    
    return [
        'valido' => empty($errores),
        'errores' => $errores
    ];
}

/**
 * Validación completa de un cliente
 * 
 * @param array $datos Datos del cliente
 * @return array ['valido' => bool, 'errores' => array]
 */
function validar_cliente_completo($datos) {
    $errores = [];
    
    // Validar nombre
    $val_nombre = validar_longitud($datos['nombre'], 2, 50, 'El nombre');
    if (!$val_nombre['valido']) {
        $errores[] = $val_nombre['mensaje'];
    }
    
    // Validar apellido
    $val_apellido = validar_longitud($datos['apellido'], 2, 50, 'El apellido');
    if (!$val_apellido['valido']) {
        $errores[] = $val_apellido['mensaje'];
    }
    
    // Validar contenido apropiado en nombre y apellido
    $val_contenido_nombre = validar_contenido_apropiado($datos['nombre'] . ' ' . $datos['apellido']);
    if (!$val_contenido_nombre['valido']) {
        $errores[] = 'Nombre/Apellido: ' . $val_contenido_nombre['mensaje'];
    }
    
    // Validar email
    $val_email = validar_email($datos['email']);
    if (!$val_email['valido']) {
        $errores[] = $val_email['mensaje'];
    }
    
    // Validar teléfono
    $val_telefono = validar_telefono($datos['telefono']);
    if (!$val_telefono['valido']) {
        $errores[] = $val_telefono['mensaje'];
    }
    
    return [
        'valido' => empty($errores),
        'errores' => $errores
    ];
}

/**
 * Validación completa de una reseña
 * 
 * @param array $datos Datos de la reseña
 * @return array ['valido' => bool, 'errores' => array]
 */
function validar_resena_completa($datos) {
    $errores = [];
    
    // Validar calificación
    $val_calificacion = validar_calificacion($datos['calificacion']);
    if (!$val_calificacion['valido']) {
        $errores[] = $val_calificacion['mensaje'];
    }
    
    // Validar título
    $val_titulo = validar_longitud($datos['titulo'], 3, 100, 'El título');
    if (!$val_titulo['valido']) {
        $errores[] = $val_titulo['mensaje'];
    }
    
    $val_contenido_titulo = validar_contenido_apropiado($datos['titulo']);
    if (!$val_contenido_titulo['valido']) {
        $errores[] = 'Título: ' . $val_contenido_titulo['mensaje'];
    }
    
    // Validar comentario
    $val_comentario = validar_longitud($datos['comentario'], 10, 1000, 'El comentario');
    if (!$val_comentario['valido']) {
        $errores[] = $val_comentario['mensaje'];
    }
    
    $val_contenido_comentario = validar_contenido_apropiado($datos['comentario']);
    if (!$val_contenido_comentario['valido']) {
        $errores[] = 'Comentario: ' . $val_contenido_comentario['mensaje'];
    }
    
    // Detectar spam en comentario
    $spam = detectar_spam($datos['comentario']);
    if ($spam['es_spam']) {
        $errores[] = 'Comentario sospechoso de spam: ' . implode(', ', $spam['razones']);
    }
    
    return [
        'valido' => empty($errores),
        'errores' => $errores
    ];
}

/**
 * Validación completa de un mensaje
 * 
 * @param string $mensaje Texto del mensaje
 * @return array ['valido' => bool, 'errores' => array]
 */
function validar_mensaje_completo($mensaje) {
    $errores = [];
    
    // Validar longitud
    $val_longitud = validar_longitud($mensaje, 1, 500, 'El mensaje');
    if (!$val_longitud['valido']) {
        $errores[] = $val_longitud['mensaje'];
    }
    
    // Validar contenido
    $val_contenido = validar_contenido_apropiado($mensaje);
    if (!$val_contenido['valido']) {
        $errores[] = $val_contenido['mensaje'];
    }
    
    // Detectar spam
    $spam = detectar_spam($mensaje);
    if ($spam['es_spam']) {
        $errores[] = 'Mensaje sospechoso de spam: ' . implode(', ', $spam['razones']);
    }
    
    return [
        'valido' => empty($errores),
        'errores' => $errores
    ];
}

?>
