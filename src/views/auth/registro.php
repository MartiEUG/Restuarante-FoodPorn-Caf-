<?php 
$pageTitle = 'Registro - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <div class="auth-container">
        <h1>Crear Cuenta</h1>
        
        <?php if (isset($_SESSION['errores_registro'])): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($_SESSION['errores_registro'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errores_registro']); ?>
        <?php endif; ?>
        
        <form action="<?php echo APP_URL; ?>/registro/procesar" method="POST" class="auth-form">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required minlength="6">
                <small>Mínimo 6 caracteres</small>
            </div>
            
            <div class="form-group">
                <label for="confirmar_contrasena">Confirmar Contraseña</label>
                <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
        </form>
        
        <p class="auth-link">¿Ya tienes cuenta? <a href="<?php echo APP_URL; ?>/login">Inicia sesión aquí</a></p>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
