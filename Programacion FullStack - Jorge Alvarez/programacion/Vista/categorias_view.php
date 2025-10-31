<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías - Proyecto</title>
    <?php include 'partials/estilos.php'; ?>
    <style>
        .categorias-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .categoria-card { background: white; border-radius: 12px; padding: 25px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s; border: 3px solid; }
        .categoria-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .categoria-icono { font-size: 64px; margin-bottom: 15px; display: block; }
        .categoria-nombre { font-size: 20px; font-weight: 700; margin-bottom: 10px; color: #333; }
        .categoria-descripcion { font-size: 13px; color: #666; margin-bottom: 15px; min-height: 40px; }
        .categoria-servicios { font-size: 12px; color: #999; margin-bottom: 15px; }
        .categoria-acciones { display: flex; gap: 10px; justify-content: center; }
        .color-preview { width: 40px; height: 40px; border-radius: 50%; display: inline-block; border: 2px solid #ddd; margin: 0 auto 10px; }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'partials/navegacion.php'; ?>
        
        <h1>📂 Gestión de Categorías</h1>
        
        <?php include 'partials/mensajes.php'; ?>
        
        <!-- Estadística -->
        <div class="stats-container" style="grid-template-columns: 1fr;">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_categorias; ?></div>
                <div class="stat-label">Total de Categorías</div>
            </div>
        </div>
        
        <!-- Formulario agregar categoría -->
        <button class="toggle-form btn-success" onclick="toggleForm()">➕ Agregar Nueva Categoría</button>
        
        <div class="form-agregar" id="formAgregar">
            <h2>Nueva Categoría</h2>
            <form method="POST" action="../Controlador/categoria_controller.php">
                <input type="hidden" name="accion" value="crear">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre de la Categoría *</label>
                        <input type="text" id="nombre" name="nombre" required 
                               placeholder="Ej: Tecnología">
                    </div>
                    
                    <div class="form-group">
                        <label for="icono">Icono Emoji *</label>
                        <input type="text" id="icono" name="icono" required maxlength="4"
                               placeholder="Ej: 💻" value="📋">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="color">Color (HEX) *</label>
                        <input type="color" id="color" name="color" required value="#667eea">
                    </div>
                    
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="color-preview" id="colorPreview" style="background-color: #667eea;"></div>
                    </div>
                </div>
                
                <div class="form-group full">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="3"
                              placeholder="Descripción de la categoría (opcional)"></textarea>
                </div>
                
                <button type="submit" class="btn-success">💾 Guardar Categoría</button>
                <button type="button" class="btn-secondary" onclick="toggleForm()">✖ Cancelar</button>
            </form>
        </div>
        
        <!-- Grid de categorías -->
        <h2>📋 Categorías Disponibles (<?php echo $total_categorias; ?>)</h2>
        
        <?php if (count($categorias) > 0): ?>
            <div class="categorias-grid">
                <?php foreach ($categorias as $cat): ?>
                    <div class="categoria-card" style="border-color: <?php echo e($cat['color']); ?>;">
                        <span class="categoria-icono"><?php echo $cat['icono']; ?></span>
                        <div class="color-preview" style="background-color: <?php echo e($cat['color']); ?>;"></div>
                        <div class="categoria-nombre"><?php echo e($cat['nombre']); ?></div>
                        <div class="categoria-descripcion">
                            <?php echo !empty($cat['descripcion']) ? e($cat['descripcion']) : '<em>Sin descripción</em>'; ?>
                        </div>
                        <div class="categoria-servicios">
                            📊 <?php echo $cat['total_servicios'] ?? 0; ?> servicio(s)
                        </div>
                        <div class="categoria-acciones">
                            <a href="#" onclick="editarCategoria(<?php echo $cat['id']; ?>)" 
                               class="btn btn-sm btn-success">✏️ Editar</a>
                            <a href="../Controlador/categoria_controller.php?accion=eliminar&id=<?php echo $cat['id']; ?>" 
                               onclick="return confirm('¿Eliminar categoría?')" 
                               class="btn btn-sm btn-danger">🗑️ Eliminar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-resultados">
                <i>📂</i>
                <p>No hay categorías registradas.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Modal de edición -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>✏️ Editar Categoría</h2>
                <span class="close" onclick="cerrarModal()">&times;</span>
            </div>
            <form method="POST" action="../Controlador/categoria_controller.php">
                <input type="hidden" name="accion" value="actualizar">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_nombre">Nombre *</label>
                        <input type="text" id="edit_nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_icono">Icono *</label>
                        <input type="text" id="edit_icono" name="icono" required maxlength="4">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_color">Color *</label>
                        <input type="color" id="edit_color" name="color" required>
                    </div>
                    
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="color-preview" id="editColorPreview"></div>
                    </div>
                </div>
                
                <div class="form-group full">
                    <label for="edit_descripcion">Descripción</label>
                    <textarea id="edit_descripcion" name="descripcion" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn-success">💾 Guardar Cambios</button>
                <button type="button" class="btn-secondary" onclick="cerrarModal()">✖ Cancelar</button>
            </form>
        </div>
    </div>
    
    <script>
        // Preview del color en tiempo real
        document.getElementById('color').addEventListener('input', function(e) {
            document.getElementById('colorPreview').style.backgroundColor = e.target.value;
        });
        
        document.getElementById('edit_color').addEventListener('input', function(e) {
            document.getElementById('editColorPreview').style.backgroundColor = e.target.value;
        });
        
        function toggleForm() {
            const form = document.getElementById('formAgregar');
            form.classList.toggle('visible');
        }
        
        function editarCategoria(id) {
            const card = event.target.closest('.categoria-card');
            const nombre = card.querySelector('.categoria-nombre').textContent.trim();
            const icono = card.querySelector('.categoria-icono').textContent.trim();
            const color = card.style.borderColor || '#667eea';
            const descripcion = card.querySelector('.categoria-descripcion').textContent.trim();
            
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_icono').value = icono;
            document.getElementById('edit_color').value = color;
            document.getElementById('editColorPreview').style.backgroundColor = color;
            document.getElementById('edit_descripcion').value = (descripcion === 'Sin descripción') ? '' : descripcion;
            
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
