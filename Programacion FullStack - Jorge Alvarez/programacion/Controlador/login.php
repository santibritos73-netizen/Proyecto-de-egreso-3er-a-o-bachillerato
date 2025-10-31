<?php 

require_once '../Modelo/usuario.php';
require_once '../Modelo/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Obtener usuario por email
    $usuario = obtener_usuario_por_email($email);

    if ($usuario) {
        // Verificar contraseña
        if (password_verify($password, $usuario['password'])) {
            // Obtener datos completos (cliente o empresa)
            $datos_completos = obtener_datos_completos_usuario($usuario['id']);
            
            if ($datos_completos) {
                // Usuario es cliente o empresa
                $tipo_usuario = $datos_completos['tipo_usuario'];
                
                // Determinar el nombre a mostrar
                if ($tipo_usuario === 'empresa') {
                    // Para empresas, usar el nombre de la empresa o el email si es placeholder
                    $nombre = ($datos_completos['nombre'] === 'Mi Empresa') ? $email : $datos_completos['nombre'];
                } else {
                    // Para clientes, verificar si son placeholders
                    $nombre_cliente = $datos_completos['nombre'];
                    $apellido_cliente = $datos_completos['apellido'];
                    
                    if ($nombre_cliente === 'Nombre' && $apellido_cliente === 'Apellido') {
                        // Si son placeholders, usar el email
                        $nombre = $email;
                    } elseif ($nombre_cliente === 'Cliente' && $apellido_cliente === 'Usuario') {
                        // Si son placeholders del script de migración, usar el email
                        $nombre = $email;
                    } else {
                        // Si tiene datos reales, usar nombre completo
                        $nombre = $nombre_cliente . ' ' . $apellido_cliente;
                    }
                }
                
                $entidad_id = $datos_completos['id'];
                
                // Establecer sesión con todos los datos
                establecer_sesion($usuario['id'], $email, $tipo_usuario, $nombre, $entidad_id);
                
                header("Location: index_page.php");
                exit;
            } else {
                // Usuario existe pero no tiene perfil de cliente ni empresa
                header("Location: login_page.php?error=" . urlencode("Usuario sin perfil asociado. Por favor contacte al administrador."));
                exit;
            }
        } else {
            // Contraseña incorrecta
            header("Location: login_page.php?error=" . urlencode("Contraseña incorrecta"));
            exit;
        }
    } else {
        // Usuario no encontrado
        header("Location: login_page.php?error=" . urlencode("Usuario no encontrado"));
        exit;
    }
}
?>
