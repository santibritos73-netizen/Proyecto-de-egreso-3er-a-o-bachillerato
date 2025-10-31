<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes - Proyecto</title>
    <?php include 'partials/estilos.php'; ?>
</head>
<body>
    <div class="container">
        <?php include 'partials/navegacion.php'; ?>
        
        <h1>👥 Gestión de Clientes</h1>
        
        <?php include 'partials/mensajes.php'; ?>
        
        <!-- Estadística -->
        <div class="stats-container" style="grid-template-columns: 1fr;">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_clientes; ?></div>
                <div class="stat-label">Total de Clientes</div>
            </div>
        </div>
        
        <!-- Formulario agregar cliente -->
        <button class="toggle-form btn-success" onclick="toggleForm()">➕ Agregar Nuevo Cliente</button>
        
        <div class="form-agregar" id="formAgregar">
            <h2>Nuevo Cliente</h2>
            <form method="POST" action="../Controlador/cliente_controller.php">
                <input type="hidden" name="accion" value="crear">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre *</label>
                        <input type="text" id="nombre" name="nombre" required 
                               placeholder="Ej: Juan">
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido">Apellido *</label>
                        <input type="text" id="apellido" name="apellido" required
                               placeholder="Ej: Pérez">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required
                               placeholder="Ej: juan.perez@email.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono *</label>
                        <input type="tel" id="telefono" name="telefono" required
                               placeholder="Ej: +52 123 456 7890">
                    </div>
                </div>
                
                <div class="form-group full">
                    <label for="direccion">Dirección *</label>
                    <textarea id="direccion" name="direccion" required
                              placeholder="Dirección completa del cliente"></textarea>
                </div>
                
                <button type="submit" class="btn-success">💾 Guardar Cliente</button>
                <button type="button" class="btn-secondary" onclick="toggleForm()">✖ Cancelar</button>
            </form>
        </div>
        
        <!-- Tabla de clientes -->
        <h2>📋 Lista de Clientes (<?php echo $total_clientes; ?>)</h2>
        
        <?php if (count($clientes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cli): ?>
                        <tr>
                            <td><?php echo $cli['id']; ?></td>
                            <td><strong><?php echo e($cli['nombre'] . ' ' . $cli['apellido']); ?></strong></td>
                            <td><?php echo e($cli['email']); ?></td>
                            <td><?php echo e($cli['telefono']); ?></td>
                            <td><?php echo e($cli['direccion']); ?></td>
                            <td><?php echo formatear_fecha($cli['fecha_registro'] ?? '', 'd/m/Y'); ?></td>
                            <td>
                                <a href="#" onclick="editarCliente(<?php echo $cli['id']; ?>)" 
                                   class="btn btn-sm btn-success">✏️ Editar</a>
                                <a href="../Controlador/cliente_controller.php?accion=eliminar&id=<?php echo $cli['id']; ?>" 
                                   onclick="return confirm('¿Está seguro de eliminar este cliente?')" 
                                   class="btn btn-sm btn-danger">🗑️ Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-resultados">
                <i>👥</i>
                <p>No hay clientes registrados.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Modal de edición -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>✏️ Editar Cliente</h2>
                <span class="close" onclick="cerrarModal()">&times;</span>
            </div>
            <form method="POST" action="../Controlador/cliente_controller.php" id="formEditar">
                <input type="hidden" name="accion" value="actualizar">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_nombre">Nombre *</label>
                        <input type="text" id="edit_nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_apellido">Apellido *</label>
                        <input type="text" id="edit_apellido" name="apellido" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_email">Email *</label>
                        <input type="email" id="edit_email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_telefono">Teléfono *</label>
                        <input type="tel" id="edit_telefono" name="telefono" required>
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
        
        function editarCliente(id) {
            const fila = event.target.closest('tr');
            const celdas = fila.cells;
            const nombreCompleto = celdas[1].textContent.trim().split(' ');
            
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombreCompleto[0] || '';
            document.getElementById('edit_apellido').value = nombreCompleto.slice(1).join(' ') || '';
            document.getElementById('edit_email').value = celdas[2].textContent.trim();
            document.getElementById('edit_telefono').value = celdas[3].textContent.trim();
            document.getElementById('edit_direccion').value = celdas[4].textContent.trim();
            
            document.getElementById('modalEditar').style.display = 'block';
        }
        
        function cerrarModal() {
            document.getElementById('modalEditar').style.display = 'none';
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('modalEditar');
            if (event.target == modal) {
                cerrarModal();
            }
        }
    </script>
</body>
</html>
