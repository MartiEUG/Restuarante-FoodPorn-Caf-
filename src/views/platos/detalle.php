<?php 
$pageTitle = htmlspecialchars($plato['nombre']) . ' - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <div class="dish-detail">
        <div class="dish-detail-image">
            <span class="dish-category"><?php echo htmlspecialchars($plato['categoria']); ?></span>
        </div>
        <div class="dish-detail-info">
            <h1><?php echo htmlspecialchars($plato['nombre']); ?></h1>
            <p class="price-large"><?php echo formatearPrecio($plato['precio']); ?></p>
            <p class="description"><?php echo nl2br(htmlspecialchars($plato['descripcion'])); ?></p>
            
            <div class="dish-actions">
                <a href="<?php echo APP_URL; ?>/menu" class="btn btn-secondary">Volver al Menú</a>
                <?php if (isAuthenticated()): ?>
                    <a href="<?php echo APP_URL; ?>/reservas/nueva" class="btn btn-primary">Hacer Reserva</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
