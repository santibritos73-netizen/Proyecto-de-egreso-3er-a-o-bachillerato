<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Proyecto</title>
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
        .container {
            background-color: white;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 900px;
            width: 100%;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5em;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .subtitle {
            color: #666;
            font-size: 1.1em;
            font-weight: normal;
        }
        .user-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 500;
        }
        .user-info strong {
            font-weight: 700;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
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
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 35px 25px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .card:hover::before {
            opacity: 1;
        }
        .card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        .card a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            display: block;
            position: relative;
            z-index: 1;
        }
        .card-icon {
            font-size: 56px;
            margin-bottom: 15px;
            display: block;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-top: 10px;
        }
        .logout-section {
            text-align: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }
        .btn-logout {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 14px 40px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }
        .btn-logout:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #999;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }
            h1 {
                font-size: 2em;
            }
            .grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎨 Proyecto</h1>
            <p class="subtitle">Sistema de Gestión de Servicios</p>
        </div>
        
        <?php if (!empty($usuario)): ?>
            <div class="user-info">
                👤 Bienvenido/a, <strong><?php echo e($usuario['email']); ?></strong>
                <?php if ($es_empresa): ?>
                    <span style="margin-left: 10px;">| 🏢 Panel de Empresa</span>
                <?php elseif ($es_cliente): ?>
                    <span style="margin-left: 10px;">| 👥 Panel de Cliente</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($mensaje_exito)): ?>
            <div class="alert alert-success">
                ✓ <?php echo e($mensaje_exito); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($mensaje_error)): ?>
            <div class="alert alert-error">
                ✗ <?php echo e($mensaje_error); ?>
            </div>
        <?php endif; ?>
        
        <div class="grid">
            <div class="card">
                <span class="card-icon">🏢</span>
                <a href="empresas_page.php">
                    <div class="card-title">Empresas</div>
                </a>
            </div>
            
            <div class="card">
                <span class="card-icon">👥</span>
                <a href="clientes_page.php">
                    <div class="card-title">Clientes</div>
                </a>
            </div>
            
            <div class="card">
                <span class="card-icon">⚙️</span>
                <a href="servicios_page.php">
                    <div class="card-title">Servicios</div>
                </a>
            </div>
            
            <div class="card">
                <span class="card-icon">📂</span>
                <a href="categorias_page.php">
                    <div class="card-title">Categorías</div>
                </a>
            </div>
            
            <div class="card">
                <span class="card-icon">📋</span>
                <a href="contrataciones_page.php">
                    <div class="card-title">Contrataciones</div>
                </a>
            </div>
            
            <div class="card">
                <span class="card-icon">⭐</span>
                <a href="resenas_page.php">
                    <div class="card-title">Reseñas</div>
                </a>
            </div>
        </div>

        <div class="logout-section">
            <form action="../Controlador/logout.php" method="POST">
                <button type="submit" class="btn-logout">🚪 Cerrar Sesión</button>
            </form>
        </div>
        
        <div class="footer">
            © 2025 Proyecto - Sistema de Gestión de Servicios
        </div>
    </div>
</body>
</html>
