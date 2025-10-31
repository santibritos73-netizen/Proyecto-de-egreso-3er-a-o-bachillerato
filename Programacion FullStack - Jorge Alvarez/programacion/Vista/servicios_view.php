<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Servicios - Proyecto</title>
    <?php include 'partials/estilos.php'; ?>
    <style>
        .stats-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); }
        .stat-number { font-size: 42px; font-weight: 700; margin-bottom: 8px; }
        .stat-label { font-size: 13px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .filtros-container { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; margin-bottom: 30px; color: white; }
        .filtros-container h3 { margin-bottom: 20px; color: white; font-size: 20px; }
        .filtros-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .filter-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px; }
        .filter-group input, .filter-group select { width: 100%; padding: 12px; border: none; border-radius: 6px; font-size: 14px; }
        .filtros-buttons { text-align: center; }
        .btn-filtrar { background-color: #28a745; padding: 12px 30px; margin-right: 10px; }
        .btn-limpiar { background-color: #6c757d; padding: 12px 30px; }
        
        .categoria-badge { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; }
        .categoria-icon { margin-right: 5px; font-size: 14px; }
        
        .toggle-form { margin-bottom: 25px; }
        .form-agregar { display: none; }
        .form-agregar.visible { display: block; }
        
        .calificacion { color: #ffc107; font-size: 16px; }
        .sin-calificacion { color: #ccc; }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'partials/navegacion.php'; ?>
        
        <h1>🔍 Gestión de Servicios con Búsqueda y Filtros</h1>
        
        <?php include 'partials/mensajes.php'; ?>
        
        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_servicios; ?></div>
                <div class="stat-label">Total Servicios</div>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="filtros-container">
            <h3>🔍 Buscar y Filtrar Servicios</h3>
            <form method="GET" action="../Controlador/servicios_page.php">
                <div class="filtros-grid">
                    <div class="filter-group">
                        <label for="busqueda">🔎 Buscar por nombre</label>
                        <input type="text" id="busqueda" name="busqueda" placeholder="Buscar servicio..." value="<?php echo e($busqueda); ?>">
                    </div>
                    <div class="filter-group">
                        <label for="categoria">📂 Categoría</label>
                        <select id="categoria" name="categoria">
                            <option value="">Todas las categorías</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($categoria_filtro == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo $cat['icono'] . ' ' . e($cat['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="empresa">🏢 Empresa</label>
                        <select id="empresa" name="empresa">
                            <option value="">Todas las empresas</option>
                            <?php foreach ($empresas as $emp): ?>
                                <option value="<?php echo $emp['id']; ?>" <?php echo ($empresa_filtro == $emp['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($emp['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="filtros-buttons">
                    <button type="submit" class="btn-filtrar">🔍 Buscar</button>
                    <button type="button" class="btn-limpiar" onclick="window.location.href='../Controlador/servicios_page.php'">🔄 Limpiar Filtros</button>
                </div>
            </form>
        </div>
        
        <!-- Botón agregar servicio (solo para empresas) -->
        <?php if ($es_empresa): ?>
            <button class="toggle-form btn-success" onclick="toggleForm()">➕ Agregar Nuevo Servicio</button>
            
            <div class="form-agregar" id="formAgregar">
                <h2>Nuevo Servicio</h2>
                <form method="POST" action="../Controlador/servicio_controller.php">
                    <input type="hidden" name="accion" value="crear">
                    <input type="hidden" name="empresa_id" value="<?php echo $usuario['empresa_id']; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="titulo">Título del Servicio *</label>
                            <input type="text" id="titulo" name="titulo" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="precio">Precio *</label>
                            <input type="number" step="0.01" id="precio" name="precio" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group full">
                            <label for="categoria_id">Categoría *</label>
                            <select id="categoria_id" name="categoria_id" required>
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>">
                                        <?php echo $cat['icono'] . ' ' . e($cat['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group full">
                        <label for="descripcion">Descripción *</label>
                        <textarea id="descripcion" name="descripcion" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-success">💾 Guardar Servicio</button>
                    <button type="button" class="btn-secondary" onclick="toggleForm()">✖ Cancelar</button>
                </form>
            </div>
        <?php endif; ?>
        
        <!-- Tabla de servicios -->
        <h2>📋 Lista de Servicios (<?php echo count($servicios); ?> resultados)</h2>
        
        <?php if (count($servicios) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Empresa</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicios as $serv): ?>
                        <tr>
                            <td><?php echo $serv['id']; ?></td>
                            <td><strong><?php echo e($serv['titulo']); ?></strong></td>
                            <td><?php echo e(substr($serv['descripcion'], 0, 60)) . '...'; ?></td>
                            <td><strong>$<?php echo number_format($serv['precio'], 2); ?></strong></td>
                            <td>
                                <?php if (isset($serv['categoria_nombre'])): ?>
                                    <span class="categoria-badge" style="background-color: <?php echo e($serv['categoria_color'] ?? '#007bff'); ?>">
                                        <span class="categoria-icon"><?php echo $serv['categoria_icono'] ?? '📋'; ?></span>
                                        <?php echo e($serv['categoria_nombre']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($serv['empresa_nombre'] ?? 'N/A'); ?></td>
                            <td><?php echo isset($serv['fecha_creacion']) ? formatear_fecha($serv['fecha_creacion'], 'd/m/Y') : 'N/A'; ?></td>
                            <td>
                                <?php if ($es_empresa && $usuario['empresa_id'] == $serv['empresa_id']): ?>
                                    <a href="#" onclick="editarServicio(<?php echo $serv['id']; ?>)" class="btn btn-sm btn-success">✏️ Editar</a>
                                    <a href="../Controlador/servicio_controller.php?accion=eliminar&id=<?php echo $serv['id']; ?>" 
                                       onclick="return confirm('¿Eliminar este servicio?')" 
                                       class="btn btn-sm btn-danger">🗑️ Eliminar</a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-resultados">
                <i>📭</i>
                <p>No se encontraron servicios con los filtros aplicados.</p>
                <button onclick="window.location.href='../Controlador/servicios_page.php'" class="btn-secondary mt-20">🔄 Ver todos los servicios</button>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function toggleForm() {
            const form = document.getElementById('formAgregar');
            form.classList.toggle('visible');
        }
        
        function editarServicio(id) {
            // Aquí iría la lógica para abrir el modal de edición
            alert('Funcionalidad de edición en desarrollo para servicio ID: ' + id);
        }
    </script>
</body>
</html>
