<?php 
$pageTitle = 'Editar Usuario - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <h1>Editar Usuario</h1>
    
    <div class="actions-bar">
        <a href="<?php echo APP_URL; ?>/admin/usuarios" class="btn btn-secondary">Volver a Usuarios</a>
    </div>
    
    <!-- Added user edit form -->
    <div style="max-width: 600px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);">
        <form method="POST" action="<?php echo APP_URL; ?>/admin/usuarios/actualizar/<?php echo $usuario['id']; ?>">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="usuario" <?php echo $usuario['rol'] === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                    <option value="administrador" <?php echo $usuario['rol'] === 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            
            <!-- Added password change section -->
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e0e0e0;">
                <h3 style="margin-bottom: 1rem; font-size: 1rem; color: #333;">Cambiar Contraseña (Opcional)</h3>
                
                <div class="form-group">
                    <label for="contrasena">Nueva Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Dejar vacío para no cambiar">
                </div>
                
                <div class="form-group">
                    <label for="contrasena_confirmar">Confirmar Contraseña:</label>
                    <input type="password" id="contrasena_confirmar" name="contrasena_confirmar" placeholder="Dejar vacío para no cambiar">
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="<?php echo APP_URL; ?>/admin/usuarios" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
