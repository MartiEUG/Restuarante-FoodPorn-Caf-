<?php 
$pageTitle = 'Buscar Platos - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <h1>Resultados de búsqueda: "<?php echo htmlspecialchars($keyword); ?>"</h1>
    
    <div class="search-bar">
        <form action="<?php echo APP_URL; ?>/menu/buscar" method="GET">
            <input type="text" name="q" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Buscar platos..." required>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
    </div>
    
    <div class="dishes-grid">
        <?php if (empty($platos)): ?>
            <p>No se encontraron platos que coincidan con tu búsqueda.</p>
        <?php else: ?>
            <p>Se encontraron <?php echo count($platos); ?> platos</p>
            <?php foreach ($platos as $plato): ?>
                <div class="dish-card">
                    <div class="dish-image">
                        <span class="dish-category"><?php echo htmlspecialchars($plato['categoria']); ?></span>
                    </div>
                    <div class="dish-info">
                        <h3><?php echo htmlspecialchars($plato['nombre']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($plato['descripcion'], 0, 100)); ?>...</p>
                        <div class="dish-footer">
                            <span class="price"><?php echo formatearPrecio($plato['precio']); ?></span>
                            <a href="<?php echo APP_URL; ?>/menu/<?php echo $plato['id']; ?>" class="btn btn-small">Ver Detalle</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="text-center">
        <a href="<?php echo APP_URL; ?>/menu" class="btn btn-secondary">Ver Menú Completo</a>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
