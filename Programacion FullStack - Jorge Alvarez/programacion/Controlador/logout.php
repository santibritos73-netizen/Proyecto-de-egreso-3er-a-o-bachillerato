<?php
/**
 * Controlador de Cierre de Sesión
 */

require_once '../Modelo/auth.php';

// Cerrar la sesión usando la función centralizada
cerrar_sesion();

// Redirigir al login con mensaje
header("Location: login_page.php?mensaje=" . urlencode("Sesión cerrada exitosamente"));
exit;
?>
