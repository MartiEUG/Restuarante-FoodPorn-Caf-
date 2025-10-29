<?php 
$pageTitle = 'Editar Reserva - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <div class="form-container">
        <h1>Editar Reserva</h1>
        
        <div class="info-box">
            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($reserva['nombre_usuario']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($reserva['email_usuario']); ?></p>
        </div>
        
        <form action="<?php echo APP_URL; ?>/admin/reservas/actualizar/<?php echo $reserva['id']; ?>" method="POST" class="admin-form">
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" value="<?php echo $reserva['fecha']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="hora">Hora</label>
                <input type="time" id="hora" name="hora" value="<?php echo $reserva['hora']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="num_personas">Número de Personas</label>
                <!-- Removed max limit to allow unlimited persons -->
                <input type="number" id="num_personas" name="num_personas" min="1" value="<?php echo $reserva['num_personas']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" required>
                    <option value="pendiente" <?php echo $reserva['estado'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="confirmada" <?php echo $reserva['estado'] === 'confirmada' ? 'selected' : ''; ?>>Confirmada</option>
                    <option value="cancelada" <?php echo $reserva['estado'] === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="comentarios">Comentarios</label>
                <textarea id="comentarios" name="comentarios" rows="4"><?php echo htmlspecialchars($reserva['comentarios']); ?></textarea>
            </div>
            
            <div class="form-actions">
                <a href="<?php echo APP_URL; ?>/admin/reservas" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Reserva</button>
            </div>
        </form>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
