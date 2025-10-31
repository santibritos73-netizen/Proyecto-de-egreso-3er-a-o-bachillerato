<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Proyecto</title>
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
        .error-container {
            background: white;
            padding: 60px 50px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 550px;
            width: 100%;
            text-align: center;
            animation: shake 0.5s ease;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        .error-icon {
            font-size: 120px;
            margin-bottom: 30px;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }
        .error-code {
            font-size: 72px;
            font-weight: 700;
            background: linear-gradient(135deg, #dc3545, #c82333);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            font-size: 2em;
            margin-bottom: 20px;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #f5c6cb;
            margin-bottom: 30px;
            font-weight: 500;
            line-height: 1.6;
        }
        .error-details {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
            font-size: 15px;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-block;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }
        .help-text {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
            color: #999;
            font-size: 13px;
        }
        .help-text a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .help-text a:hover {
            text-decoration: underline;
        }
        @media (max-width: 480px) {
            .error-container {
                padding: 40px 30px;
            }
            .error-code {
                font-size: 56px;
            }
            h1 {
                font-size: 1.5em;
            }
            .action-buttons {
                flex-direction: column;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        
        <div class="error-code"><?php echo e($codigo_error); ?></div>
        
        <h1>¡Oops! Algo salió mal</h1>
        
        <div class="error-message">
            <strong>Error:</strong><br>
            <?php echo e($mensaje_error); ?>
        </div>
        
        <div class="error-details">
            Lo sentimos, ha ocurrido un error mientras procesábamos tu solicitud.
            Por favor, intenta nuevamente o vuelve a la página anterior.
        </div>
        
        <div class="action-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary">
                ← Volver Atrás
            </a>
            <a href="login_page.php" class="btn btn-primary">
                🏠 Ir al Inicio de Sesión
            </a>
        </div>
        
        <div class="help-text">
            Si el problema persiste, <a href="mailto:soporte@proyecto.com">contacta al soporte técnico</a>
        </div>
    </div>
</body>
</html>
