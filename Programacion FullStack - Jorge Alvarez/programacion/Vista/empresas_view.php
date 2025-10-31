<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empresas - Proyecto</title>
    <?php include 'partials/estilos.php'; ?>
</head>
<body>
    <div class="container">
        <?php include 'partials/navegacion.php'; ?>
        
        <h1>🏢 Gestión de Empresas</h1>
        
        <?php include 'partials/mensajes.php'; ?>
        
        <!-- Estadística -->
        <div class="stats-container" style="grid-template-columns: 1fr;">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_empresas; ?></div>
                <div class="stat-label">Total de Empresas</div>
            </div>
        </div>
        
        <!-- Formulario agregar empresa -->
        <button class="toggle-form btn-success" onclick="toggleForm()">➕ Agregar Nueva Empresa</button>
        
        <div class="form-agregar" id="formAgregar">
            <h2>Nueva Empresa</h2>
            <form method="POST" action="../Controlador/empresa_controller.php">
                <input type="hidden" name="accion" value="crear">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre de la Empresa *</label>
                        <input type="text" id="nombre" name="nombre" required 
                               placeholder="Ej: Servicios ABC S.A.">
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono *</label>
                        <input type="tel" id="telefono" name="telefono" required
                               placeholder="Ej: +52 123 456 7890">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required
                               placeholder="Ej: contacto@empresa.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="sitio_web">Sitio Web</label>
                        <input type="url" id="sitio_web" name="sitio_web"
                               placeholder="Ej: https://www.empresa.com">
                    </div>
                </div>
                
                <div class="form-group full">
                    <label for="direccion">Dirección *</label>
                    <textarea id="direccion" name="direccion" required
                              placeholder="Dirección completa de la empresa"></textarea>
                </div>
                
                <button type="submit" class="btn-success">💾 Guardar Empresa</button>
                <button type="button" class="btn-secondary" onclick="toggleForm()">✖ Cancelar</button>
            </form>
        </div>
        
        <!-- Tabla de empresas -->
        <h2>📋 Lista de Empresas (<?php echo $total_empresas; ?>)</h2>
        
        <?php if (count($empresas) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Sitio Web</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empresas as $emp): ?>
                        <tr>
                            <td><?php echo $emp['id']; ?></td>
                            <td><strong><?php echo e($emp['nombre']); ?></strong></td>
                            <td><?php echo e($emp['direccion']); ?></td>
                            <td><?php echo e($emp['telefono']); ?></td>
                            <td><?php echo e($emp['email']); ?></td>
                            <td>
                                <?php if (!empty($emp['sitio_web'])): ?>
                                    <a href="<?php echo e($emp['sitio_web']); ?>" target="_blank" class="btn btn-sm">
                                        🔗 Visitar
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo formatear_fecha($emp['fecha_registro'] ?? '', 'd/m/Y'); ?></td>
                            <td>
                                <a href="#" onclick="editarEmpresa(<?php echo $emp['id']; ?>)" 
                                   class="btn btn-sm btn-success">✏️ Editar</a>
                                <a href="../Controlador/empresa_controller.php?accion=eliminar&id=<?php echo $emp['id']; ?>" 
                                   onclick="return confirm('¿Está seguro de eliminar esta empresa?')" 
                                   class="btn btn-sm btn-danger">🗑️ Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-resultados">
                <i>🏢</i>
                <p>No hay empresas registradas.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Modal de edición -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>✏️ Editar Empresa</h2>
                <span class="close" onclick="cerrarModal()">&times;</span>
            </div>
            <form method="POST" action="../Controlador/empresa_controller.php" id="formEditar">
                <input type="hidden" name="accion" value="actualizar">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_nombre">Nombre *</label>
                        <input type="text" id="edit_nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_telefono">Teléfono *</label>
                        <input type="tel" id="edit_telefono" name="telefono" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_email">Email *</label>
                        <input type="email" id="edit_email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_sitio_web">Sitio Web</label>
                        <input type="url" id="edit_sitio_web" name="sitio_web">
                    </div>
                </div>
                
                <div class="form-group full">
                    <label for="edit_direccion">Dirección *</label>
                    <textarea id="edit_direccion" name="direccion" required></textarea>
                </div>
                
                <button type="submit" class="btn-success">💾 Guardar Cambios</button>
                <button type="button" class="btn-secondary" onclick="cerrarModal()">✖ Cancelar</button>
            </form>
        </div>
    </div>
    
    <script>
        function toggleForm() {
            const form = document.getElementById('formAgregar');
            form.classList.toggle('visible');
        }
        
        function editarEmpresa(id) {
            // Obtener datos de la empresa desde la tabla
            const fila = event.target.closest('tr');
            const celdas = fila.cells;
            
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = celdas[1].textContent.trim();
            document.getElementById('edit_direccion').value = celdas[2].textContent.trim();
            document.getElementById('edit_telefono').value = celdas[3].textContent.trim();
            document.getElementById('edit_email').value = celdas[4].textContent.trim();
            
            // Sitio web (si existe)
            const sitioWebLink = celdas[5].querySelector('a');
            if (sitioWebLink) {
                document.getElementById('edit_sitio_web').value = sitioWebLink.href;
            } else {
                document.getElementById('edit_sitio_web').value = '';
            }
            
            document.getElementById('modalEditar').style.display = 'block';
        }
        
        function cerrarModal() {
            document.getElementById('modalEditar').style.display = 'none';
        }
        
        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('modalEditar');
            if (event.target == modal) {
                cerrarModal();
            }
        }
    </script>
</body>
</html>
