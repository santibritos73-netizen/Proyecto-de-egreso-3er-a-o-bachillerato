<?php

require_once '../Modelo/usuario.php';
require_once '../Modelo/cliente.php';
require_once '../Modelo/empresa.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $tipo_usuario = $_POST['tipo_usuario'] ?? '';
        $nombre = trim($_POST['nombre'] ?? '');

        // Validar que se haya seleccionado un tipo de usuario
        if (empty($tipo_usuario) || !in_array($tipo_usuario, ['cliente', 'empresa'])) {
            header("Location: registro_page.php?error=" . urlencode("Debe seleccionar un tipo de usuario"));
            exit;
        }
        
        // Validar que se haya proporcionado un nombre
        if (empty($nombre)) {
            header("Location: registro_page.php?error=" . urlencode("Debe proporcionar un nombre"));
            exit;
        }

        // Crear el usuario
        $resultado = registrar_usuario($email, $password);

        if ($resultado['exito']) 
            {
            // Obtener el ID del usuario recién creado
            $usuario = obtener_usuario_por_email($email);
            
            if ($usuario) {
                $usuario_id = $usuario['id'];
                
                // Crear el perfil según el tipo de usuario
                if ($tipo_usuario === 'cliente') {
                    // Dividir el nombre completo en nombre y apellido
                    $partes_nombre = explode(' ', $nombre, 2);
                    $primer_nombre = $partes_nombre[0];
                    $apellido = isset($partes_nombre[1]) ? $partes_nombre[1] : '';
                    
                    // Crear cliente con el nombre proporcionado
                    $resultado_perfil = crear_cliente(
                        $primer_nombre,
                        $apellido,
                        '',        // dirección vacía
                        '',        // teléfono vacío
                        $email
                    );
                    
                    if ($resultado_perfil['exito']) {
                        // Actualizar el usuario_id en la tabla clientes
                        $conexion = conectar_bd();
                        $sql = "UPDATE clientes SET usuario_id = ? WHERE id = ?";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bind_param("ii", $usuario_id, $resultado_perfil['id']);
                        $stmt->execute();
                        $stmt->close();
                        $conexion->close();
                    }
                    
                } elseif ($tipo_usuario === 'empresa') {
                    // Crear empresa con el nombre proporcionado
                    $resultado_perfil = crear_empresa(
                        $nombre,     // Nombre de la empresa
                        '',          // dirección vacía
                        '',          // teléfono vacío
                        $email
                    );
                    
                    if ($resultado_perfil['exito']) {
                        // Actualizar el usuario_id en la tabla empresas
                        $conexion = conectar_bd();
                        $sql = "UPDATE empresas SET usuario_id = ? WHERE id = ?";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bind_param("ii", $usuario_id, $resultado_perfil['id']);
                        $stmt->execute();
                        $stmt->close();
                        $conexion->close();
                    }
                }
            }
            
            header("Location: login_page.php?mensaje=" . urlencode("Usuario registrado exitosamente. Por favor inicia sesión"));
            exit;
            } 
                else 
                    {
                        header("Location: registro_page.php?error=" . urlencode($resultado['error']));
                        exit;
                    }
    }   
else 
    {
        header("Location: registro_page.php");
        exit;
    }
?>
