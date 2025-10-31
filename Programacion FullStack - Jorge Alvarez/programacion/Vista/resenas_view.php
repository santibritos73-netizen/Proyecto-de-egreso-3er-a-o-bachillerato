<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseñas y Calificaciones - Proyecto</title>
    <?php include 'partials/estilos.php'; ?>
    <style>
        .stats-overview { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 30px; }
        .rating-summary { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; }
        .rating-number { font-size: 4em; font-weight: bold; color: #ffc107; margin-bottom: 10px; }
        .rating-stars { font-size: 32px; color: #ffc107; margin-bottom: 10px; }
        .rating-count { color: #666; font-size: 14px; }
        .rating-distribution { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .distribution-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .distribution-label { width: 60px; font-size: 14px; color: #666; }
        .distribution-bar { flex: 1; height: 20px; background: #f0f0f0; border-radius: 10px; overflow: hidden; }
        .distribution-fill { height: 100%; background: linear-gradient(90deg, #ffc107, #ff9800); transition: width 0.3s; }
        .distribution-count { width: 50px; text-align: right; font-weight: 600; color: #666; }
        .filters-section { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .filter-row { display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; }
        .filter-group { flex: 1; min-width: 200px; }
        .filter-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
        .filter-group select { width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px; }
        .clear-filter { background: #6c757d !important; }
        .clear-filter:hover { background: #5a6268 !important; }
        .reviews-grid { display: grid; gap: 20px; }
        .review-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s; }
        .review-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .review-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px; }
        .review-client { font-weight: 600; color: #667eea; font-size: 16px; }
        .review-stars { font-size: 20px; color: #ffc107; }
        .review-service { color: #666; font-size: 14px; margin-bottom: 15px; }
        .review-comment { color: #333; line-height: 1.6; margin-bottom: 15px; }
        .review-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #f0f0f0; }
        .review-date { font-size: 13px; color: #999; }
        .review-actions { display: flex; gap: 10px; }
        @media (max-width: 768px) {
            .stats-overview { grid-template-columns: 1fr; }
            .filter-row { flex-direction: column; }
            .filter-group { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'partials/navegacion.php'; ?>
        
        <h1>⭐ Reseñas y Calificaciones</h1>
        
        <?php include 'partials/mensajes.php'; ?>
        
        <!-- Resumen de calificaciones -->
        <div class="stats-overview">
            <div class="rating-summary">
                <div class="rating-number"><?php echo $promedio_general; ?></div>
                <div class="rating-stars">
                    <?php 
                    $promedio_entero = round($promedio_general);
                    for ($i = 1; $i <= 5; $i++): 
                        echo $i <= $promedio_entero ? '★' : '☆';
                    endfor; 
                    ?>
                </div>
                <div class="rating-count"><?php echo $total_resenas; ?> reseña(s) totales</div>
            </div>
            
            <div class="rating-distribution">
                <h3 style="margin-bottom: 20px; color: #333;">📊 Distribución de Calificaciones</h3>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <div class="distribution-row">
                        <div class="distribution-label"><?php echo $i; ?> estrella<?php echo $i > 1 ? 's' : ''; ?></div>
                        <div class="distribution-bar">
                            <div class="distribution-fill" style="width: <?php echo $total_resenas > 0 ? ($distribucion[$i] / $total_resenas * 100) : 0; ?>%;"></div>
                        </div>
                        <div class="distribution-count"><?php echo $distribucion[$i]; ?></div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="filters-section">
            <h3>🔍 Filtrar Reseñas</h3>
            <form method="GET" action="">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="servicio">Servicio</label>
                        <select id="servicio" name="servicio">
                            <option value="">Todos los servicios</option>
                            <?php foreach ($servicios as $s): ?>
                                <option value="<?php echo $s['id']; ?>" 
                                    <?php echo $filtro_servicio == $s['id'] ? 'selected' : ''; ?>>
                                    <?php echo e($s['titulo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="empresa">Empresa</label>
                        <select id="empresa" name="empresa">
                            <option value="">Todas las empresas</option>
                            <?php foreach ($empresas as $emp): ?>
                                <option value="<?php echo $emp['id']; ?>" 
                                    <?php echo $filtro_empresa == $emp['id'] ? 'selected' : ''; ?>>
                                    <?php echo e($emp['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="calificacion">Calificación</label>
                        <select id="calificacion" name="calificacion">
                            <option value="">Todas</option>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?php echo $i; ?>" 
                                    <?php echo $filtro_calificacion == $i ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> estrella<?php echo $i > 1 ? 's' : ''; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <button type="submit" class="btn-success">🔍 Filtrar</button>
                        <a href="resenas_page.php" class="btn clear-filter">✖ Limpiar</a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Listado de reseñas -->
        <h2>📝 Reseñas (<?php echo $total_resenas; ?>)</h2>
        
        <?php if (count($resenas) > 0): ?>
            <div class="reviews-grid">
                <?php foreach ($resenas as $resena): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div>
                                <div class="review-client">👤 <?php echo e($resena['nombre_cliente']); ?></div>
                                <div class="review-service">
                                    <strong><?php echo e($resena['titulo_servicio']); ?></strong>
                                    <?php if (!empty($resena['nombre_empresa'])): ?>
                                        • <?php echo e($resena['nombre_empresa']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="review-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php echo $i <= $resena['calificacion'] ? '★' : '☆'; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="review-comment">
                            <?php echo nl2br(e($resena['comentario'])); ?>
                        </div>
                        
                        <div class="review-footer">
                            <div class="review-date">
                                📅 <?php 
                                $fecha = new DateTime($resena['fecha_resena']);
                                echo $fecha->format('d/m/Y');
                                ?>
                            </div>
                            <div class="review-actions">
                                <?php if ($resena['aprobada']): ?>
                                    <span class="status-badge status-completado">✓ Aprobada</span>
                                    <a href="../Controlador/resena_controller.php?accion=desaprobar&id=<?php echo $resena['id']; ?>" 
                                       class="btn btn-sm btn-secondary">🚫 Desaprobar</a>
                                <?php else: ?>
                                    <span class="status-badge status-solicitado">⏳ Pendiente</span>
                                    <a href="../Controlador/resena_controller.php?accion=aprobar&id=<?php echo $resena['id']; ?>" 
                                       class="btn btn-sm btn-success">✓ Aprobar</a>
                                <?php endif; ?>
                                <a href="../Controlador/resena_controller.php?accion=eliminar&id=<?php echo $resena['id']; ?>" 
                                   onclick="return confirm('¿Eliminar esta reseña?')"
                                   class="btn btn-sm btn-danger">🗑️ Eliminar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-resultados">
                <i>⭐</i>
                <p>No hay reseñas que coincidan con los filtros seleccionados.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
