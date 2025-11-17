<?php 
$pageTitle = 'Usuarios Registrados - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <h1>Usuarios Registrados</h1>
    
    <div class="actions-bar">
        <a href="<?php echo APP_URL; ?>/admin" class="btn btn-secondary">Volver al Panel</a>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <!-- Added quick role toggle functionality -->
                            <form method="POST" action="<?php echo APP_URL; ?>/admin/usuarios/cambiar-rol/<?php echo $usuario['id']; ?>" style="display: inline;">
                                <select name="rol" onchange="if(confirm('¿Cambiar el rol de este usuario?')) this.form.submit();" 
                                        class="role-select" <?php echo $usuario['id'] == $_SESSION['usuario_id'] ? 'disabled' : ''; ?>>
                                    <option value="usuario" <?php echo $usuario['rol'] === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                                    <option value="administrador" <?php echo $usuario['rol'] === 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                                </select>
                            </form>
                        </td>
                        <td><?php echo formatearFecha($usuario['fecha_registro']); ?></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="<?php echo APP_URL; ?>/admin/usuarios/editar/<?php echo $usuario['id']; ?>" class="btn btn-primary btn-small">Editar</a>
                                <?php if ($usuario['id'] != $_SESSION['usuario_id']): ?>
                                    <a href="<?php echo APP_URL; ?>/admin/usuarios/eliminar/<?php echo $usuario['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.role-select {
    padding: 0.25rem 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #fff;
    cursor: pointer;
    font-size: 0.9rem;
}

.role-select:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.role-select:hover:not(:disabled) {
    border-color: #0066cc;
}
</style>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
