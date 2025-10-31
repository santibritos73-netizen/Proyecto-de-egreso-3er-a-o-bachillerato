<?php
/**
 * Helper de Utilidades Generales
 * Funciones auxiliares para todo el sistema
 */

/**
 * Redirige a una página con mensaje
 * 
 * @param string $url URL de destino
 * @param string $mensaje Mensaje a mostrar
 * @param string $tipo Tipo de mensaje ('mensaje' o 'error')
 */
function redirigir_con_mensaje($url, $mensaje, $tipo = 'mensaje') {
    $separador = strpos($url, '?') !== false ? '&' : '?';
    header("Location: " . $url . $separador . $tipo . "=" . urlencode($mensaje));
    exit;
}

/**
 * Formatea un precio para mostrar
 * 
 * @param float $precio Precio a formatear
 * @param string $simbolo Símbolo de moneda
 * @return string Precio formateado
 */
function formatear_precio($precio, $simbolo = '$') {
    return $simbolo . number_format($precio, 2, '.', ',');
}

/**
 * Formatea una fecha para mostrar
 * 
 * @param string $fecha Fecha en formato Y-m-d o Y-m-d H:i:s
 * @param string $formato Formato de salida
 * @return string Fecha formateada
 */
function formatear_fecha($fecha, $formato = 'd/m/Y') {
    if (empty($fecha) || $fecha == '0000-00-00' || $fecha == '0000-00-00 00:00:00') {
        return 'N/A';
    }
    return date($formato, strtotime($fecha));
}

/**
 * Genera un extracto de texto
 * 
 * @param string $texto Texto completo
 * @param int $longitud Longitud máxima
 * @param string $sufijo Sufijo para textos cortados
 * @return string Texto resumido
 */
function generar_extracto($texto, $longitud = 100, $sufijo = '...') {
    if (mb_strlen($texto, 'UTF-8') <= $longitud) {
        return $texto;
    }
    return mb_substr($texto, 0, $longitud, 'UTF-8') . $sufijo;
}

/**
 * Verifica si una variable está vacía (null, '', 0, false, array vacío)
 * 
 * @param mixed $valor Valor a verificar
 * @return bool True si está vacío
 */
function esta_vacio($valor) {
    return empty($valor) && $valor !== '0' && $valor !== 0;
}

/**
 * Genera un color aleatorio en hex
 * 
 * @return string Color en formato #RRGGBB
 */
function generar_color_aleatorio() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

/**
 * Convierte un array a parámetros GET
 * 
 * @param array $parametros Array asociativo de parámetros
 * @return string String de parámetros (?param1=val1&param2=val2)
 */
function array_to_query_string($parametros) {
    if (empty($parametros)) {
        return '';
    }
    return '?' . http_build_query($parametros);
}

/**
 * Limpia un string de caracteres especiales para URL
 * 
 * @param string $texto Texto a limpiar
 * @return string Texto limpio para URL
 */
function limpiar_para_url($texto) {
    $texto = mb_strtolower($texto, 'UTF-8');
    $texto = str_replace(
        ['á', 'é', 'í', 'ó', 'ú', 'ñ', ' '],
        ['a', 'e', 'i', 'o', 'u', 'n', '-'],
        $texto
    );
    $texto = preg_replace('/[^a-z0-9\-]/', '', $texto);
    $texto = preg_replace('/-+/', '-', $texto);
    return trim($texto, '-');
}

/**
 * Obtiene el nombre del mes en español
 * 
 * @param int $numero_mes Número del mes (1-12)
 * @return string Nombre del mes
 */
function nombre_mes($numero_mes) {
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    return $meses[intval($numero_mes)] ?? 'Desconocido';
}

/**
 * Obtiene el nombre del día en español
 * 
 * @param int $numero_dia Número del día (0-6, 0=Domingo)
 * @return string Nombre del día
 */
function nombre_dia($numero_dia) {
    $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    return $dias[intval($numero_dia)] ?? 'Desconocido';
}

/**
 * Calcula el tiempo transcurrido desde una fecha
 * 
 * @param string $fecha Fecha en formato Y-m-d H:i:s
 * @return string Tiempo transcurrido en formato legible
 */
function tiempo_transcurrido($fecha) {
    $ahora = time();
    $tiempo = strtotime($fecha);
    $diferencia = $ahora - $tiempo;
    
    if ($diferencia < 60) {
        return 'Hace menos de un minuto';
    } elseif ($diferencia < 3600) {
        $minutos = floor($diferencia / 60);
        return "Hace " . $minutos . ($minutos == 1 ? ' minuto' : ' minutos');
    } elseif ($diferencia < 86400) {
        $horas = floor($diferencia / 3600);
        return "Hace " . $horas . ($horas == 1 ? ' hora' : ' horas');
    } elseif ($diferencia < 604800) {
        $dias = floor($diferencia / 86400);
        return "Hace " . $dias . ($dias == 1 ? ' día' : ' días');
    } elseif ($diferencia < 2592000) {
        $semanas = floor($diferencia / 604800);
        return "Hace " . $semanas . ($semanas == 1 ? ' semana' : ' semanas');
    } elseif ($diferencia < 31536000) {
        $meses = floor($diferencia / 2592000);
        return "Hace " . $meses . ($meses == 1 ? ' mes' : ' meses');
    } else {
        $anos = floor($diferencia / 31536000);
        return "Hace " . $anos . ($anos == 1 ? ' año' : ' años');
    }
}

/**
 * Genera estrellas visuales basadas en calificación
 * 
 * @param float $calificacion Calificación (0-5)
 * @param bool $html Si retorna HTML o texto plano
 * @return string Estrellas visuales
 */
function generar_estrellas($calificacion, $html = false) {
    $calificacion_redondeada = round($calificacion);
    $estrellas = '';
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $calificacion_redondeada) {
            $estrellas .= $html ? '<span class="estrella-activa">⭐</span>' : '⭐';
        } else {
            $estrellas .= $html ? '<span class="estrella-inactiva">☆</span>' : '☆';
        }
    }
    
    return $estrellas;
}

/**
 * Obtiene el color del badge según el estado
 * 
 * @param string $estado Estado del servicio o contratación
 * @return string Clase CSS del color
 */
function obtener_color_estado($estado) {
    $colores = [
        'pendiente' => 'estado-pendiente',
        'en_proceso' => 'estado-en_proceso',
        'completado' => 'estado-completado',
        'cancelado' => 'estado-cancelado',
        'solicitado' => 'status-solicitado',
        'aceptado' => 'status-aceptado',
        'en_progreso' => 'status-en_progreso',
        'rechazado' => 'status-rechazado'
    ];
    
    return $colores[$estado] ?? 'estado-default';
}

/**
 * Verifica si un valor existe en un array
 * 
 * @param mixed $valor Valor a buscar
 * @param array $array Array donde buscar
 * @return bool True si existe
 */
function valor_en_array($valor, $array) {
    return in_array($valor, $array, true);
}

/**
 * Log de errores o eventos (para debugging)
 * 
 * @param string $mensaje Mensaje a loggear
 * @param string $tipo Tipo de log (info, warning, error)
 */
function log_evento($mensaje, $tipo = 'info') {
    $archivo_log = dirname(__DIR__, 2) . '/logs/sistema.log';
    $directorio_log = dirname($archivo_log);
    
    if (!file_exists($directorio_log)) {
        mkdir($directorio_log, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $linea_log = "[$timestamp] [$tipo] $mensaje" . PHP_EOL;
    
    file_put_contents($archivo_log, $linea_log, FILE_APPEND);
}

/**
 * Genera un token CSRF para formularios
 * 
 * @return string Token generado
 */
function generar_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifica un token CSRF
 * 
 * @param string $token Token a verificar
 * @return bool True si es válido
 */
function verificar_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Pagina un array de resultados
 * 
 * @param array $datos Array de datos
 * @param int $pagina_actual Página actual
 * @param int $por_pagina Elementos por página
 * @return array Array con datos paginados y metadatos
 */
function paginar($datos, $pagina_actual = 1, $por_pagina = 10) {
    $total = count($datos);
    $total_paginas = ceil($total / $por_pagina);
    $pagina_actual = max(1, min($pagina_actual, $total_paginas));
    $offset = ($pagina_actual - 1) * $por_pagina;
    
    $datos_pagina = array_slice($datos, $offset, $por_pagina);
    
    return [
        'datos' => $datos_pagina,
        'pagina_actual' => $pagina_actual,
        'por_pagina' => $por_pagina,
        'total' => $total,
        'total_paginas' => $total_paginas,
        'tiene_anterior' => $pagina_actual > 1,
        'tiene_siguiente' => $pagina_actual < $total_paginas
    ];
}

?>
