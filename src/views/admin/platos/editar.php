<?php 
$pageTitle = 'Editar Plato - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <div class="form-container">
        <h1>Editar Plato</h1>
        
        <form action="<?php echo APP_URL; ?>/admin/platos/actualizar/<?php echo $plato['id']; ?>" method="POST" class="admin-form">
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
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="activo" <?php echo $plato['activo'] ? 'checked' : ''; ?>>
                    Plato activo
                </label>
            </div>
            
            <div class="form-actions">
                <a href="<?php echo APP_URL; ?>/admin/platos" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Plato</button>
            </div>
        </form>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
