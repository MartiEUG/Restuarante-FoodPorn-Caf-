<?php 
$pageTitle = htmlspecialchars($plato['nombre']) . ' - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <div class="dish-detail">
        <div class="dish-detail-image">
            <!-- Added actual image display with fallback -->
            <?php if ($plato['imagen']): ?>
                <img src="<?php echo htmlspecialchars($plato['imagen'], ENT_QUOTES, 'UTF-8'); ?>" 
                     alt="<?php echo htmlspecialchars($plato['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                     style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%); font-size: 5rem; opacity: 0.2;">🍽️</div>
            <?php endif; ?>
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
