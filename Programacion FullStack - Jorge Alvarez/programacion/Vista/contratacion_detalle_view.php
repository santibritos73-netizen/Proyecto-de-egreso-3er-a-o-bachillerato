<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Contratación #<?php echo $contratacion['id']; ?> - Proyecto</title>
    <?php include 'partials/estilos.php'; ?>
    <style>
        .detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px; }
        .detail-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .detail-section h2 { color: #667eea; margin-bottom: 20px; font-size: 1.5em; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
        .detail-row { display: grid; grid-template-columns: 150px 1fr; gap: 15px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0; }
        .detail-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .detail-label { font-weight: 600; color: #555; }
        .detail-value { color: #333; }
        .status-badge { padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; display: inline-block; }
        .status-solicitado { background: #fff3cd; color: #856404; }
        .status-aceptado { background: #d1ecf1; color: #0c5460; }
        .status-en_progreso { background: #cce5ff; color: #004085; }
        .status-completado { background: #d4edda; color: #155724; }
        .status-cancelado { background: #f8d7da; color: #721c24; }
        .status-rechazado { background: #e2e3e5; color: #383d41; }
        .action-buttons { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px; padding-top: 20px; border-top: 2px solid #f0f0f0; }
        .messages-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .messages-container { max-height: 400px; overflow-y: auto; margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; }
        .message { background: white; padding: 15px; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .message:last-child { margin-bottom: 0; }
        .message-header { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px; }
        .message-sender { font-weight: 600; color: #667eea; }
        .message-date { color: #888; }
        .message-body { color: #333; line-height: 1.5; }
        .no-messages { text-align: center; padding: 30px; color: #999; font-style: italic; }
        .review-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .stars { font-size: 24px; color: #ffc107; margin: 10px 0; }
        .review-form textarea { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-family: inherit; resize: vertical; min-height: 100px; }
        .star-rating { font-size: 32px; cursor: pointer; user-select: none; }
        .star-rating span { color: #ddd; transition: color 0.2s; }
        .star-rating span.active { color: #ffc107; }
        .star-rating span:hover, .star-rating span:hover ~ span { color: #ffc107; }
        @media (max-width: 768px) {
            .detail-grid { grid-template-columns: 1fr; }
            .detail-row { grid-template-columns: 1fr; gap: 5px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'partials/navegacion.php'; ?>
        
        <h1>📄 Detalle de Contratación #<?php echo $contratacion['id']; ?></h1>
        
        <?php include 'partials/mensajes.php'; ?>
        
        <!-- Grid principal -->
        <div class="detail-grid">
            <!-- Información de la contratación -->
            <div class="detail-section">
                <h2>📋 Información de la Contratación</h2>
                
                <div class="detail-row">
                    <div class="detail-label">Estado:</div>
                    <div class="detail-value">
                        <span class="status-badge status-<?php echo $contratacion['estado']; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $contratacion['estado'])); ?>
                        </span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Servicio:</div>
                    <div class="detail-value">
                        <strong><?php echo e($contratacion['titulo_servicio']); ?></strong><br>
                        <small style="color: #888;"><?php echo e($contratacion['categoria_nombre']); ?></small>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Precio:</div>
                    <div class="detail-value"><strong style="color: #28a745; font-size: 1.3em;">$<?php echo number_format($contratacion['precio_servicio'], 2); ?></strong></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Fecha Contratación:</div>
                    <div class="detail-value">
                        <?php 
                        $fecha = new DateTime($contratacion['fecha_contratacion']);
                        echo $fecha->format('d/m/Y H:i');
                        ?>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Cliente:</div>
                    <div class="detail-value">
                        <strong><?php echo e($contratacion['nombre_cliente']); ?></strong><br>
                        <small><?php echo e($contratacion['email_cliente']); ?></small>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Empresa:</div>
                    <div class="detail-value">
                        <strong><?php echo e($contratacion['nombre_empresa']); ?></strong>
                    </div>
                </div>
                
                <?php if (!empty($contratacion['comentario_cliente'])): ?>
                    <div class="detail-row">
                        <div class="detail-label">Comentario del Cliente:</div>
                        <div class="detail-value">
                            <em><?php echo e($contratacion['comentario_cliente']); ?></em>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Acciones de estado -->
                <div class="action-buttons">
                    <?php if ($contratacion['estado'] == 'solicitado'): ?>
                        <a href="../Controlador/contratacion_controller.php?accion=cambiar_estado&id=<?php echo $contratacion['id']; ?>&estado=aceptado" 
                           class="btn btn-success">✓ Aceptar Contratación</a>
                        <a href="../Controlador/contratacion_controller.php?accion=cambiar_estado&id=<?php echo $contratacion['id']; ?>&estado=rechazado" 
                           onclick="return confirm('¿Rechazar esta contratación?')"
                           class="btn btn-danger">✗ Rechazar Contratación</a>
                    <?php elseif ($contratacion['estado'] == 'aceptado'): ?>
                        <a href="../Controlador/contratacion_controller.php?accion=cambiar_estado&id=<?php echo $contratacion['id']; ?>&estado=en_progreso" 
                           class="btn btn-success">▶ Iniciar Trabajo</a>
                    <?php elseif ($contratacion['estado'] == 'en_progreso'): ?>
                        <a href="../Controlador/contratacion_controller.php?accion=cambiar_estado&id=<?php echo $contratacion['id']; ?>&estado=completado" 
                           class="btn btn-success">✓ Marcar como Completado</a>
                    <?php endif; ?>
                    
                    <?php if (in_array($contratacion['estado'], ['solicitado', 'aceptado', 'en_progreso'])): ?>
                        <a href="../Controlador/contratacion_controller.php?accion=cambiar_estado&id=<?php echo $contratacion['id']; ?>&estado=cancelado" 
                           onclick="return confirm('¿Cancelar esta contratación?')"
                           class="btn btn-danger">🚫 Cancelar Contratación</a>
                    <?php endif; ?>
                    
                    <a href="contrataciones_page.php" class="btn btn-secondary">← Volver al Listado</a>
                </div>
            </div>
            
            <!-- Resumen lateral -->
            <div>
                <div class="detail-section">
                    <h2>📊 Resumen</h2>
                    <div class="detail-row">
                        <div class="detail-label">Mensajes:</div>
                        <div class="detail-value"><strong><?php echo count($mensajes); ?></strong></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Duración:</div>
                        <div class="detail-value">
                            <?php 
                            $fecha_inicio = new DateTime($contratacion['fecha_contratacion']);
                            $ahora = new DateTime();
                            $diff = $fecha_inicio->diff($ahora);
                            echo $diff->days . ' día(s)';
                            ?>
                        </div>
                    </div>
                    <?php if ($resena_contratacion): ?>
                        <div class="detail-row">
                            <div class="detail-label">Calificación:</div>
                            <div class="detail-value">
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php echo $i <= $resena_contratacion['calificacion'] ? '★' : '☆'; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Sección de mensajes -->
        <div class="messages-section">
            <h2>💬 Mensajes (<?php echo count($mensajes); ?>)</h2>
            
            <?php if (count($mensajes) > 0): ?>
                <div class="messages-container">
                    <?php foreach ($mensajes as $msg): ?>
                        <div class="message">
                            <div class="message-header">
                                <span class="message-sender">
                                    <?php echo $msg['tipo_remitente'] == 'cliente' ? '👤 ' : '🏢 '; ?>
                                    <?php echo ucfirst($msg['tipo_remitente']); ?>
                                    <?php if (!$msg['leido']): ?>
                                        <span style="background: #dc3545; color: white; padding: 2px 6px; border-radius: 10px; font-size: 10px; margin-left: 5px;">NUEVO</span>
                                    <?php endif; ?>
                                </span>
                                <span class="message-date">
                                    <?php 
                                    $fecha_msg = new DateTime($msg['fecha_envio']);
                                    echo $fecha_msg->format('d/m/Y H:i');
                                    ?>
                                </span>
                            </div>
                            <div class="message-body"><?php echo nl2br(e($msg['contenido'])); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-messages">💬 No hay mensajes todavía</div>
            <?php endif; ?>
            
            <!-- Formulario enviar mensaje -->
            <form method="POST" action="../Controlador/mensaje_controller.php">
                <input type="hidden" name="accion" value="enviar">
                <input type="hidden" name="contratacion_id" value="<?php echo $contratacion['id']; ?>">
                
                <div class="form-group">
                    <label for="mensaje">Enviar Mensaje:</label>
                    <textarea id="mensaje" name="contenido" required 
                              placeholder="Escribe tu mensaje aquí..."></textarea>
                </div>
                
                <button type="submit" class="btn-success">📤 Enviar Mensaje</button>
            </form>
        </div>
        
        <!-- Sección de reseña -->
        <?php if ($puede_resenar && !$resena_contratacion): ?>
            <div class="review-section">
                <h2>⭐ Dejar Reseña</h2>
                <p style="color: #666; margin-bottom: 20px;">Esta contratación está completada. ¡Comparte tu experiencia!</p>
                
                <form method="POST" action="../Controlador/resena_controller.php" class="review-form">
                    <input type="hidden" name="accion" value="crear">
                    <input type="hidden" name="contratacion_id" value="<?php echo $contratacion['id']; ?>">
                    <input type="hidden" name="servicio_id" value="<?php echo $contratacion['servicio_id']; ?>">
                    <input type="hidden" name="cliente_id" value="<?php echo $contratacion['cliente_id']; ?>">
                    <input type="hidden" name="calificacion" id="calificacion_input" value="5">
                    
                    <div class="form-group">
                        <label>Calificación:</label>
                        <div class="star-rating" id="star-rating">
                            <span data-rating="5" class="active">★</span>
                            <span data-rating="4" class="active">★</span>
                            <span data-rating="3" class="active">★</span>
                            <span data-rating="2" class="active">★</span>
                            <span data-rating="1" class="active">★</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="comentario">Comentario:</label>
                        <textarea id="comentario" name="comentario" required
                                  placeholder="Describe tu experiencia con este servicio..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-success">⭐ Publicar Reseña</button>
                </form>
            </div>
        <?php elseif ($resena_contratacion): ?>
            <div class="review-section">
                <h2>⭐ Reseña Publicada</h2>
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php echo $i <= $resena_contratacion['calificacion'] ? '★' : '☆'; ?>
                    <?php endfor; ?>
                    <span style="color: #666; font-size: 16px; margin-left: 10px;">
                        (<?php echo $resena_contratacion['calificacion']; ?>/5)
                    </span>
                </div>
                <p style="color: #333; margin-top: 15px; line-height: 1.6;">
                    <?php echo nl2br(e($resena_contratacion['comentario'])); ?>
                </p>
                <p style="color: #888; font-size: 13px; margin-top: 10px;">
                    Publicada el <?php 
                    $fecha_resena = new DateTime($resena_contratacion['fecha_resena']);
                    echo $fecha_resena->format('d/m/Y');
                    ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Sistema de calificación por estrellas
        const starRating = document.getElementById('star-rating');
        if (starRating) {
            const stars = starRating.querySelectorAll('span');
            const input = document.getElementById('calificacion_input');
            
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.dataset.rating;
                    input.value = rating;
                    
                    stars.forEach(s => {
                        if (s.dataset.rating >= rating) {
                            s.classList.add('active');
                        } else {
                            s.classList.remove('active');
                        }
                    });
                });
            });
        }
    </script>
</body>
</html>
