<?php 
$pageTitle = 'Menú Completo - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container" style="padding: 40px 20px;">
    <div class="menu-header" style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 2.5rem; color: #2c3e50; margin-bottom: 10px;">Nuestro Menú</h1>
        <p style="color: #7f8c8d; font-size: 1.1rem;">Descubre nuestra selección de platos</p>
    </div>
    
    <!-- Buscador -->
    <div class="search-bar" style="max-width: 600px; margin: 0 auto 30px;">
        <form action="<?php echo APP_URL; ?>/menu/buscar" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="q" placeholder="Buscar platos..." required 
                   style="flex: 1; padding: 12px 20px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px; white-space: nowrap;">Buscar</button>
        </form>
    </div>
    
    <!-- Filtro por categoría -->
    <div class="category-filter" style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; margin-bottom: 40px;">
        <a href="<?php echo APP_URL; ?>/menu" 
           class="btn <?php echo !$categoria ? 'btn-primary' : 'btn-secondary'; ?>"
           style="padding: 10px 20px; border-radius: 25px;">
            Todos
        </a>
        <?php foreach ($categorias as $cat): ?>
            <a href="<?php echo APP_URL; ?>/menu?categoria=<?php echo urlencode($cat); ?>" 
               class="btn <?php echo $categoria === $cat ? 'btn-primary' : 'btn-secondary'; ?>"
               style="padding: 10px 20px; border-radius: 25px;">
                <?php echo htmlspecialchars($cat); ?>
            </a>
        <?php endforeach; ?>
    </div>
    
    <!-- Lista de platos -->
    <div class="dishes-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; margin-bottom: 40px;">
        <?php if (empty($platos)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                <p style="font-size: 1.2rem; color: #7f8c8d;">No se encontraron platos.</p>
            </div>
        <?php else: ?>
            <?php foreach ($platos as $plato): ?>
                <div class="dish-card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;">
                    <div class="dish-image" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative; display: flex; align-items: center; justify-content: center;">
                        <span class="dish-category" style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.95); padding: 6px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; color: #667eea;">
                            <?php echo htmlspecialchars($plato['categoria']); ?>
                        </span>
                        <span style="font-size: 3rem; color: rgba(255,255,255,0.3);">🍽️</span>
                    </div>
                    <div class="dish-info" style="padding: 20px;">
                        <h3 style="font-size: 1.3rem; color: #2c3e50; margin-bottom: 10px; font-weight: 600;">
                            <?php echo htmlspecialchars($plato['nombre']); ?>
                        </h3>
                        <p style="color: #7f8c8d; line-height: 1.6; margin-bottom: 20px; min-height: 60px;">
                            <?php echo htmlspecialchars(substr($plato['descripcion'], 0, 100)); ?>...
                        </p>
                        <div class="dish-footer" style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="price" style="font-size: 1.5rem; font-weight: 700; color: #e67e22;">
                                <?php echo formatearPrecio($plato['precio']); ?>
                            </span>
                            <a href="<?php echo APP_URL; ?>/menu/<?php echo $plato['id']; ?>" 
                               class="btn btn-primary" 
                               style="padding: 8px 20px; font-size: 0.9rem;">
                                Ver Detalle
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Paginación -->
    <?php if ($totalPaginas > 1): ?>
        <div class="pagination" style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="<?php echo APP_URL; ?>/menu?pagina=<?php echo $i; ?><?php echo $categoria ? '&categoria=' . urlencode($categoria) : ''; ?>" 
                   class="btn <?php echo $i === $paginaActual ? 'btn-primary' : 'btn-secondary'; ?>"
                   style="min-width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.dish-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

@media (max-width: 768px) {
    .dishes-grid {
        grid-template-columns: 1fr !important;
    }
    
    .menu-header h1 {
        font-size: 2rem !important;
    }
}
</style>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
