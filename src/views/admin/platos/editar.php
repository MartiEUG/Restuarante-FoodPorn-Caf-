<?php 
$pageTitle = 'Editar Plato - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <div class="form-container">
        <h1>Editar Plato</h1>
        
        <form action="<?php echo APP_URL; ?>/admin/platos/actualizar/<?php echo $plato['id']; ?>" method="POST" class="admin-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre del Plato</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($plato['nombre']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($plato['descripcion']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="precio">Precio (€)</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" value="<?php echo $plato['precio']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="categoria">Categoría</label>
                <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($plato['categoria']); ?>">
            </div>
            
            <!-- Enhanced image section with preview and delete option -->
            <div class="form-group">
                <label>Imagen del Plato</label>
                
                <!-- Current image preview section -->
                <?php if ($plato['imagen']): ?>
                    <div class="image-preview-section">
                        <div class="current-image">
                            <img src="<?php echo APP_URL . $plato['imagen']; ?>" alt="<?php echo htmlspecialchars($plato['nombre']); ?>" class="preview-img">
                            <p class="image-label">Imagen actual</p>
                        </div>
                        <div class="image-actions">
                            <!-- Fixed checkbox value to properly send 1 when checked -->
                            <label>
                                <input type="checkbox" name="eliminar_imagen" value="1" onchange="toggleEliminarImagen(this)">
                                Eliminar imagen actual
                            </label>
                            <p id="eliminar-confirmacion" style="display: none; color: #d32f2f; margin-top: 8px; font-weight: bold;">
                                ✓ La imagen será eliminada al guardar
                            </p>
                        </div>
                    </div>
                    <hr style="margin: 15px 0;">
                <?php else: ?>
                    <div class="no-image-notice">
                        <p>Este plato no tiene imagen</p>
                    </div>
                    <hr style="margin: 15px 0;">
                <?php endif; ?>
                
                <!-- New image upload -->
                <div class="form-group-inner">
                    <label for="imagen">Seleccionar nueva imagen</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*" class="form-input" onchange="previewNewImage(this)">
                    <small>Formatos permitidos: JPG, PNG, GIF, WebP. Máximo 2MB.</small>
                    
                    <!-- New image preview -->
                    <div id="new-image-preview" style="margin-top: 10px; display: none;">
                        <div class="current-image">
                            <img id="new-preview-img" src="/placeholder.svg" alt="Vista previa" class="preview-img">
                            <p class="image-label">Imagen nueva</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="activo" <?php echo $plato['activo'] ? 'checked' : ''; ?>>
                    Plato activo
                </label>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="es_menu_dia" <?php echo $plato['es_menu_dia'] ? 'checked' : ''; ?>>
                    Incluir en menú del día
                </label>
            </div>
            
            <div class="form-actions">
                <a href="<?php echo APP_URL; ?>/admin/platos" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Plato</button>
            </div>
        </form>
    </div>
</div>

<!-- Added JavaScript for image preview functionality -->
<script>
function previewNewImage(input) {
    const preview = document.getElementById('new-image-preview');
    const previewImg = document.getElementById('new-preview-img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

function toggleEliminarImagen(checkbox) {
    const confirmacion = document.getElementById('eliminar-confirmacion');
    if (checkbox.checked) {
        confirmacion.style.display = 'block';
    } else {
        confirmacion.style.display = 'none';
    }
}
</script>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
