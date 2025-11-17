<?php 
$pageTitle = htmlspecialchars($plato['nombre']) . ' - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<!-- Diseño completamente rediseñado con imagen grande y layout profesional -->
<div class="dish-detail-container" style="min-height: calc(100vh - 200px);">
    <!-- Hero Image Section -->
    <div class="dish-hero" style="position: relative; height: 500px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <?php if ($plato['imagen']): ?>
            <img src="<?php echo htmlspecialchars($plato['imagen'], ENT_QUOTES, 'UTF-8'); ?>" 
                 alt="<?php echo htmlspecialchars($plato['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                 style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.6) 100%);"></div>
        <?php else: ?>
            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 8rem; opacity: 0.3; color: white;">🍽️</span>
            </div>
        <?php endif; ?>
        
        <!-- Category Badge -->
        <span style="position: absolute; top: 30px; right: 30px; background: rgba(255,255,255,0.95); padding: 10px 24px; border-radius: 30px; font-size: 0.95rem; font-weight: 600; color: #667eea; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10;">
            <?php echo htmlspecialchars($plato['categoria']); ?>
        </span>
        
        <!-- Back Button -->
        <a href="<?php echo APP_URL; ?>/menu" 
           style="position: absolute; top: 30px; left: 30px; background: rgba(255,255,255,0.95); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #2c3e50; font-size: 1.2rem; font-weight: bold; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: all 0.3s; z-index: 10;"
           onmouseover="this.style.background='white'; this.style.transform='scale(1.1)';"
           onmouseout="this.style.background='rgba(255,255,255,0.95)'; this.style.transform='scale(1)';">
            ←
        </a>
        
        <!-- Title Overlay -->
        <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 40px; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);">
            <div class="container">
                <h1 style="color: white; font-size: 3rem; font-weight: 700; margin-bottom: 10px; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                    <?php echo htmlspecialchars($plato['nombre']); ?>
                </h1>
                <p style="color: rgba(255,255,255,0.9); font-size: 1.3rem; font-weight: 600; margin: 0;">
                    <?php echo formatearPrecio($plato['precio']); ?>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Content Section -->
    <div class="container" style="padding: 60px 20px;">
        <div style="max-width: 800px; margin: 0 auto;">
            <!-- Description Card -->
            <div style="background: white; padding: 40px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 30px;">
                <h2 style="color: #2c3e50; font-size: 1.8rem; margin-bottom: 20px; font-weight: 600; border-bottom: 3px solid #e67e22; padding-bottom: 15px; display: inline-block;">
                    Descripción
                </h2>
                <p style="color: #555; line-height: 1.8; font-size: 1.1rem; margin-top: 20px;">
                    <?php echo nl2br(htmlspecialchars($plato['descripcion'])); ?>
                </p>
            </div>
            
            <!-- Action Buttons -->
            <div style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center;">
                <a href="<?php echo APP_URL; ?>/menu" 
                   class="btn btn-secondary" 
                   style="padding: 15px 40px; font-size: 1.1rem; border-radius: 30px; min-width: 200px; text-align: center; transition: all 0.3s;">
                    Volver al Menú
                </a>
                <?php if (isAuthenticated()): ?>
                    <a href="<?php echo APP_URL; ?>/reservas/nueva" 
                       class="btn btn-primary" 
                       style="padding: 15px 40px; font-size: 1.1rem; border-radius: 30px; min-width: 200px; text-align: center; transition: all 0.3s; box-shadow: 0 4px 15px rgba(230, 126, 34, 0.3);">
                        Hacer Reserva
                    </a>
                <?php else: ?>
                    <a href="<?php echo APP_URL; ?>/login" 
                       class="btn btn-primary" 
                       style="padding: 15px 40px; font-size: 1.1rem; border-radius: 30px; min-width: 200px; text-align: center; transition: all 0.3s; box-shadow: 0 4px 15px rgba(230, 126, 34, 0.3);">
                        Iniciar Sesión para Reservar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .dish-hero {
        height: 350px !important;
    }
    
    .dish-hero h1 {
        font-size: 2rem !important;
    }
    
    .dish-hero p {
        font-size: 1.1rem !important;
    }
    
    .dish-hero > a:first-of-type,
    .dish-hero > span:first-of-type {
        top: 15px !important;
        left: 15px !important;
        right: 15px !important;
    }
    
    .container > div > div {
        padding: 25px !important;
    }
}

/* Button hover effects */
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
}
</style>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
