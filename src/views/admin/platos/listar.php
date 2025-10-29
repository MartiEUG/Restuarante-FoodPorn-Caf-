<?php 
$pageTitle = 'Gestionar Platos - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <h1>Gestionar Platos</h1>
    
    <div class="actions-bar">
        <a href="<?php echo APP_URL; ?>/admin" class="btn btn-secondary">Volver al Panel</a>
        <a href="<?php echo APP_URL; ?>/admin/platos/crear" class="btn btn-primary">Crear Nuevo Plato</a>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($platos as $plato): ?>
                    <tr>
                        <td><?php echo $plato['id']; ?></td>
                        <td><?php echo htmlspecialchars($plato['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($plato['categoria']); ?></td>
                        <td><?php echo formatearPrecio($plato['precio']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $plato['activo'] ? 'confirmada' : 'cancelada'; ?>">
                                <?php echo $plato['activo'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/admin/platos/editar/<?php echo $plato['id']; ?>" class="btn btn-small">Editar</a>
                            <a href="<?php echo APP_URL; ?>/admin/platos/eliminar/<?php echo $plato['id']; ?>" 
                               class="btn btn-small btn-danger"
                               onclick="return confirm('¿Estás seguro de eliminar este plato?')">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
