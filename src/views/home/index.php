<?php 
$pageTitle = 'Inicio - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<section class="hero">
    <div class="container">
        <h1>Bienvenido a <?php echo APP_NAME; ?></h1>
        <p class="hero-subtitle">Descubre la auténtica cocina mediterránea</p>
        <div class="hero-buttons">
            <a href="<?php echo APP_URL; ?>/menu" class="btn btn-primary">Ver Menú</a>
            <?php if (isAuthenticated()): ?>
                <a href="<?php echo APP_URL; ?>/reservas/nueva" class="btn btn-secondary">Hacer Reserva</a>
            <?php else: ?>
                <a href="<?php echo APP_URL; ?>/registro" class="btn btn-secondary">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Improved dishes display with better styling and proper encoding -->
<section class="featured-dishes">
    <div class="container">
        <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 3rem; color: var(--text-primary);">Platos Destacados</h2>
        
        <?php 
        // Group dishes by category
        $platosPorCategoria = [];
        foreach ($platosDestacados as $plato) {
            $categoria = $plato['categoria'];
            if (!isset($platosPorCategoria[$categoria])) {
                $platosPorCategoria[$categoria] = [];
            }
            $platosPorCategoria[$categoria][] = $plato;
        }
        
        // Display by category in correct order
        $ordenCategorias = ['Entrantes', 'Principales', 'Postres'];
        foreach ($ordenCategorias as $categoria):
            if (!isset($platosPorCategoria[$categoria])) continue;
        ?>
            <div class="categoria-section" style="margin-bottom: 3rem;">
                <h3 style="font-size: 1.75rem; color: var(--primary-color); margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 3px solid var(--primary-color);">
                    <?php echo htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8'); ?>
                </h3>
                <div class="dishes-grid">
                    <?php foreach ($platosPorCategoria[$categoria] as $plato): ?>
                        <div class="dish-card-modern">
                            <div class="dish-image-modern">
                                <span class="dish-category-badge"><?php echo htmlspecialchars($plato['categoria'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="dish-content">
                                <h4 class="dish-title"><?php echo htmlspecialchars($plato['nombre'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                <p class="dish-description"><?php echo htmlspecialchars($plato['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <div class="dish-footer-modern">
                                    <span class="price-modern"><?php echo formatearPrecio($plato['precio']); ?></span>
                                    <a href="<?php echo APP_URL; ?>/menu/<?php echo $plato['id']; ?>" class="btn btn-small btn-primary">Ver Detalle</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div style="text-align: center; margin-top: 3rem;">
            <a href="<?php echo APP_URL; ?>/menu" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem;">Ver Menú Completo</a>
        </div>
    </div>
</section>

<section class="about">
    <div class="container">
        <h2>Sobre Nosotros</h2>
        <p>En <?php echo APP_NAME; ?>, nos dedicamos a ofrecer la mejor experiencia gastronómica con ingredientes frescos y recetas tradicionales. Nuestro equipo de chefs expertos prepara cada plato con pasión y dedicación.</p>
    </div>
</section>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
