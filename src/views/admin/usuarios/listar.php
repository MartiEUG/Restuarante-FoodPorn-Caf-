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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $usuario['rol'] === 'administrador' ? 'confirmada' : 'pendiente'; ?>">
                                <?php echo ucfirst($usuario['rol']); ?>
                            </span>
                        </td>
                        <td><?php echo formatearFecha($usuario['fecha_registro']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
