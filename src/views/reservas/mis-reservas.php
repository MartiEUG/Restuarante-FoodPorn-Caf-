<?php 
$pageTitle = 'Mis Reservas - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <h1>Mis Reservas</h1>
    
    <div class="actions-bar">
        <a href="<?php echo APP_URL; ?>/reservas/nueva" class="btn btn-primary">Nueva Reserva</a>
    </div>
    
    <?php if (empty($reservas)): ?>
        <p>No tienes reservas aún.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Personas</th>
                        <th>Estado</th>
                        <th>Comentarios</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?php echo formatearFecha($reserva['fecha']); ?></td>
                            <td><?php echo formatearHora($reserva['hora']); ?></td>
                            <td><?php echo $reserva['num_personas']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $reserva['estado']; ?>">
                                    <?php echo ucfirst($reserva['estado']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($reserva['comentarios']); ?></td>
                            <td>
                                <?php if ($reserva['estado'] !== 'cancelada'): ?>
                                    <a href="<?php echo APP_URL; ?>/reservas/cancelar/<?php echo $reserva['id']; ?>" 
                                       class="btn btn-small btn-danger"
                                       onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                        Cancelar
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
