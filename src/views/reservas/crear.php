<?php 
$pageTitle = 'Nueva Reserva - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <div class="form-container-modern">
        <h1 style="text-align: center; font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-primary);">Hacer una Reserva</h1>
        
        <?php if (isset($_SESSION['errores_reserva'])): ?>
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    <?php foreach ($_SESSION['errores_reserva'] as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errores_reserva']); ?>
        <?php endif; ?>
        
        <form action="<?php echo APP_URL; ?>/reservas/crear" method="POST" class="reservation-form-modern">
            <div class="form-section">
                <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; font-size: 1.5rem;">📅 Información de la Reserva</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="hora">Hora</label>
                        <select id="hora" name="hora" required>
                            <option value="">Selecciona una hora</option>
                            <optgroup label="Almuerzo">
                                <option value="13:00:00">13:00</option>
                                <option value="13:30:00">13:30</option>
                                <option value="14:00:00">14:00</option>
                                <option value="14:30:00">14:30</option>
                                <option value="15:00:00">15:00</option>
                            </optgroup>
                            <optgroup label="Cena">
                                <option value="20:00:00">20:00</option>
                                <option value="20:30:00">20:30</option>
                                <option value="21:00:00">21:00</option>
                                <option value="21:30:00">21:30</option>
                                <option value="22:00:00">22:00</option>
                                <option value="22:30:00">22:30</option>
                            </optgroup>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="num_personas">Número de Personas</label>
                        <!-- Removed max limit to allow unlimited persons -->
                        <input type="number" id="num_personas" name="num_personas" min="1" required>
                    </div>
                </div>
            </div>
            
            <!-- Improved platos selector with better styling and correct category order -->
            <?php if (!empty($platos)): ?>
                <div class="form-section platos-selector-modern">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.5rem;">🍽️ Selecciona tus Platos (Opcional)</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 2rem; line-height: 1.6;">
                        Puedes preseleccionar los platos que deseas para tu reserva. Esto nos ayudará a preparar mejor tu experiencia.
                    </p>
                    
                    <?php 
                    // Group by category
                    $categorias = [];
                    foreach ($platos as $plato) {
                        $categorias[$plato['categoria']][] = $plato;
                    }
                    
                    // Display in correct order
                    $ordenCategorias = ['Entrantes', 'Principales', 'Postres'];
                    foreach ($ordenCategorias as $categoria):
                        if (!isset($categorias[$categoria])) continue;
                    ?>
                        <div class="categoria-platos">
                            <h4 style="color: var(--primary-color); font-size: 1.25rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--primary-color);">
                                <?php echo htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8'); ?>
                            </h4>
                            <div class="platos-grid">
                                <?php foreach ($categorias[$categoria] as $plato): ?>
                                    <div class="plato-item-modern">
                                        <div class="plato-info-modern">
                                            <h5 style="color: var(--text-primary); font-size: 1.1rem; margin-bottom: 0.5rem; font-weight: 600;">
                                                <?php echo htmlspecialchars($plato['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                            </h5>
                                            <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.5; margin-bottom: 0.75rem;">
                                                <?php echo htmlspecialchars($plato['descripcion'], ENT_QUOTES, 'UTF-8'); ?>
                                            </p>
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <span class="plato-precio-modern"><?php echo number_format($plato['precio'], 2); ?>€</span>
                                                <div class="cantidad-selector-modern">
                                                    <label for="plato_<?php echo $plato['id']; ?>" style="margin-right: 0.5rem; font-weight: 600;">Cantidad:</label>
                                                    <input type="number" 
                                                           id="plato_<?php echo $plato['id']; ?>" 
                                                           name="platos[<?php echo $plato['id']; ?>]" 
                                                           min="0" 
                                                           max="10" 
                                                           value="0"
                                                           style="width: 70px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-section">
                <div class="form-group">
                    <label for="comentarios">Comentarios (opcional)</label>
                    <textarea id="comentarios" name="comentarios" rows="4" placeholder="Alergias, preferencias de mesa, ocasión especial, etc."></textarea>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block" style="padding: 1rem; font-size: 1.1rem; margin-top: 1rem;">
                Confirmar Reserva
            </button>
        </form>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
