<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Contrataciones - Proyecto</title>
    <?php include 'partials/estilos.php'; ?>
    <style>
        /* Estadísticas mejoradas */
        .stats-container { 
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .stat-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
        }
        
        .stat-number { 
            font-size: 42px; 
            font-weight: 800; 
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .stat-label { 
            font-size: 14px; 
            font-weight: 600; 
            opacity: 0.95;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Colores específicos para cada estado */
        .stat-card.total { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card.solicitado { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-card.aceptado { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stat-card.en-progreso { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .stat-card.completado { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .stat-card.cancelado { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
        .stat-card.rechazado { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
        .stat-card.revenue { background: linear-gradient(135deg, #52c234 0%, #061700 100%); }
        
        /* Filtros */
        .filters-section { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .filter-row { display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; }
        .filter-group { flex: 1; min-width: 200px; }
        .filter-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
        .filter-group input, .filter-group select { width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px; }
        .clear-filter { background: #6c757d !important; }
        .clear-filter:hover { background: #5a6268 !important; }
        
        /* Badges de estado mejorados */
        .status-badge { 
            padding: 8px 16px; 
            border-radius: 25px; 
            font-size: 13px; 
            font-weight: 700; 
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        .status-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }
        
        .status-solicitado { 
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); 
            color: white;
        }
        .status-aceptado { 
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); 
            color: white;
        }
        .status-en_progreso { 
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); 
            color: #0d3d2f;
        }
        .status-completado { 
            background: linear-gradient(135deg, #52c234 0%, #38b000 100%); 
            color: white;
        }
        .status-cancelado { 
            background: linear-gradient(135deg, #ff6b6b 0%, #c92a2a 100%); 
            color: white;
        }
        .status-rechazado { 
            background: linear-gradient(135deg, #868e96 0%, #495057 100%); 
            color: white;
        }
        
        .action-buttons { display: flex; gap: 5px; flex-wrap: wrap; }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'partials/navegacion.php'; ?>
        
        <h1>📋 Gestión de Contrataciones</h1>
        
        <?php include 'partials/mensajes.php'; ?>
        
        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="stat-card total">
                <div class="stat-icon" style="font-size: 24px; margin-bottom: 10px;">📊</div>
                <div class="stat-number"><?php echo $estadisticas['total']; ?></div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-card solicitado">
                <div class="stat-icon" style="font-size: 24px; margin-bottom: 10px;">📝</div>
                <div class="stat-number"><?php echo $estadisticas['solicitado']; ?></div>
                <div class="stat-label">Solicitadas</div>
            </div>
            <div class="stat-card aceptado">
                <div class="stat-icon" style="font-size: 24px; margin-bottom: 10px;">✅</div>
                <div class="stat-number"><?php echo $estadisticas['aceptado']; ?></div>
                <div class="stat-label">Aceptadas</div>
            </div>
            <div class="stat-card en-progreso">
                <div class="stat-icon" style="font-size: 24px; margin-bottom: 10px;">⚙️</div>
                <div class="stat-number"><?php echo $estadisticas['en_progreso']; ?></div>
                <div class="stat-label">En Progreso</div>
            </div>
            <div class="stat-card completado">
                <div class="stat-icon" style="font-size: 24px; margin-bottom: 10px;">🎉</div>
                <div class="stat-number"><?php echo $estadisticas['completado']; ?></div>
                <div class="stat-label">Completadas</div>
            </div>
            <div class="stat-card cancelado">
                <div class="stat-icon" style="font-size: 24px; margin-bottom: 10px;">❌</div>
                <div class="stat-number"><?php echo $estadisticas['cancelado']; ?></div>
                <div class="stat-label">Canceladas</div>
            </div>
            <div class="stat-card rechazado">
                <div class="stat-icon" style="font-size: 24px; margin-bottom: 10px;">🚫</div>
                <div class="stat-number"><?php echo $estadisticas['rechazado']; ?></div>
                <div class="stat-label">Rechazadas</div>
            </div>
            <div class="stat-card revenue">
                <div class="stat-icon" style="font-size: 24px; margin-bottom: 10px;">💰</div>
                <div class="stat-number">$<?php echo number_format($estadisticas['ingresos_totales'], 2); ?></div>
                <div class="stat-label">Ingresos Totales</div>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="filters-section">
            <h3>🔍 Filtros de Búsqueda</h3>
            <form method="GET" action="">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado">
                            <option value="">Todos los estados</option>
                            <option value="solicitado" <?php echo $filtro_estado == 'solicitado' ? 'selected' : ''; ?>>Solicitado</option>
                            <option value="aceptado" <?php echo $filtro_estado == 'aceptado' ? 'selected' : ''; ?>>Aceptado</option>
                            <option value="en_progreso" <?php echo $filtro_estado == 'en_progreso' ? 'selected' : ''; ?>>En Progreso</option>
                            <option value="completado" <?php echo $filtro_estado == 'completado' ? 'selected' : ''; ?>>Completado</option>
                            <option value="cancelado" <?php echo $filtro_estado == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                            <option value="rechazado" <?php echo $filtro_estado == 'rechazado' ? 'selected' : ''; ?>>Rechazado</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="buscar">Buscar</label>
                        <input type="text" id="buscar" name="buscar" 
                               value="<?php echo e($filtro_buscar); ?>"
                               placeholder="Servicio, cliente o empresa...">
                    </div>
                    
                    <div class="filter-group">
                        <button type="submit" class="btn-success">🔍 Buscar</button>
                        <a href="contrataciones_page.php" class="btn clear-filter">✖ Limpiar</a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Tabla de contrataciones -->
        <h2>📊 Listado de Contrataciones (<?php echo $total_contrataciones; ?>)</h2>
        
        <?php if (count($contrataciones) > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Empresa</th>
                            <th>Servicio</th>
                            <th>Precio</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contrataciones as $c): ?>
                            <tr>
                                <td>#<?php echo $c['id']; ?></td>
                                <td>
                                    <strong><?php echo e($c['nombre_cliente']); ?></strong><br>
                                    <small style="color: #888;"><?php echo e($c['email_cliente']); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo e($c['nombre_empresa']); ?></strong>
                                </td>
                                <td>
                                    <?php echo e($c['titulo_servicio']); ?><br>
                                    <small style="color: #888;"><?php echo e($c['categoria_nombre']); ?></small>
                                </td>
                                <td><strong>$<?php echo number_format($c['precio_servicio'], 2); ?></strong></td>
                                <td>
                                    <?php 
                                    $fecha = new DateTime($c['fecha_contratacion']);
                                    echo $fecha->format('d/m/Y');
                                    ?>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $c['estado']; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $c['estado'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="contratacion_detalle_page.php?id=<?php echo $c['id']; ?>" 
                                           class="btn btn-primary">👁️ Ver</a>
                                        
                                        <?php if ($c['estado'] == 'solicitado'): ?>
                                            <a href="../Controlador/contratacion_controller.php?accion=cambiar_estado&id=<?php echo $c['id']; ?>&estado=aceptado" 
                                               class="btn btn-success">✓ Aceptar</a>
                                            <a href="../Controlador/contratacion_controller.php?accion=cambiar_estado&id=<?php echo $c['id']; ?>&estado=rechazado" 
                                               onclick="return confirm('¿Rechazar contratación?')"
                                               class="btn btn-danger">✗ Rechazar</a>
                                        <?php elseif ($c['estado'] == 'aceptado'): ?>
                                            <a href="../Controlador/contratacion_controller.php?accion=cambiar_estado&id=<?php echo $c['id']; ?>&estado=en_progreso" 
                                               class="btn btn-success">▶ Iniciar</a>
                                        <?php elseif ($c['estado'] == 'en_progreso'): ?>
                                            <a href="../Controlador/contratacion_controller.php?accion=cambiar_estado&id=<?php echo $c['id']; ?>&estado=completado" 
                                               class="btn btn-success">✓ Completar</a>
                                        <?php endif; ?>
                                        
                                        <?php if (in_array($c['estado'], ['solicitado', 'aceptado', 'en_progreso'])): ?>
                                            <a href="../Controlador/contratacion_controller.php?accion=cambiar_estado&id=<?php echo $c['id']; ?>&estado=cancelado" 
                                               onclick="return confirm('¿Cancelar contratación?')"
                                               class="btn btn-danger">🚫 Cancelar</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-resultados">
                <i>📋</i>
                <p>No hay contrataciones que coincidan con los filtros.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
