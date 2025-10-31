<div class="nav">
    <div class="nav-links">
        <a href="../Controlador/index_page.php">🏠 Inicio</a>
        <a href="../Controlador/empresas_page.php">🏢 Empresas</a>
        <a href="../Controlador/clientes_page.php">👥 Clientes</a>
        <a href="../Controlador/servicios_page.php">📋 Servicios</a>
        <a href="../Controlador/categorias_page.php">📂 Categorías</a>
        <a href="../Controlador/contrataciones_page.php">💼 Contrataciones</a>
        <a href="../Controlador/resenas_page.php">⭐ Reseñas</a>
    </div>
    <div class="nav-user">
        <?php if (isset($usuario) && $usuario): ?>
            👤 <?php echo e($usuario['nombre']); ?> 
            (<?php echo $usuario['tipo'] == 'empresa' ? '🏢 Empresa' : '👤 Cliente'; ?>)
            | <a href="../Controlador/logout.php" style="color: #ffeb3b;">Salir</a>
        <?php endif; ?>
    </div>
</div>
