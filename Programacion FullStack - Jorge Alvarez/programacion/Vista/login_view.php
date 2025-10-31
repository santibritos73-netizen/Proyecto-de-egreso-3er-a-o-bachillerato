<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Proyecto</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .login-container {
            background: white;
            padding: 50px 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 450px;
            width: 100%;
            animation: slideIn 0.5s ease;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .logo {
            font-size: 64px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        h1 {
            color: #333;
            font-size: 2em;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: inherit;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-group input::placeholder {
            color: #aaa;
        }
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
        }
        .divider span {
            background: white;
            padding: 0 15px;
            color: #999;
            position: relative;
            font-size: 14px;
        }
        .register-link {
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        .register-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #999;
            font-size: 12px;
        }
        @media (max-width: 480px) {
            .login-container {
                padding: 40px 25px;
            }
            h1 {
                font-size: 1.6em;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">🎨</div>
            <h1>Proyecto</h1>
            <p class="subtitle">Inicia sesión para continuar</p>
        </div>
        
        <?php if (!empty($mensaje_error)): ?>
            <div class="alert alert-error">
                ✗ <?php echo e($mensaje_error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($mensaje_exito)): ?>
            <div class="alert alert-success">
                ✓ <?php echo e($mensaje_exito); ?>
            </div>
        <?php endif; ?>
        
        <form action="../Controlador/login.php" method="POST">
            <div class="form-group">
                <label for="email">📧 Correo Electrónico</label>
                <input type="email" id="email" name="email" 
                       placeholder="ejemplo@correo.com" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">🔒 Contraseña</label>
                <input type="password" id="password" name="password" 
                       placeholder="Ingresa tu contraseña" required>
            </div>
            
            <button type="submit" class="btn-login">
                🚀 Iniciar Sesión
            </button>
        </form>
        
        <div class="divider">
            <span>o</span>
        </div>
        
        <div class="register-link">
            ¿No tienes cuenta? <a href="registro_page.php">Crear cuenta nueva</a>
        </div>
        
        <div class="footer">
            © 2025 Proyecto - Sistema de Gestión de Servicios
        </div>
    </div>
</body>
</html>
