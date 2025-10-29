<?php 
$pageTitle = 'Iniciar Sesión - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <div class="auth-container">
        <h1>Iniciar Sesión</h1>
        
        <form action="<?php echo APP_URL; ?>/login/procesar" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
        </form>
        
        <p class="auth-link">¿No tienes cuenta? <a href="<?php echo APP_URL; ?>/registro">Regístrate aquí</a></p>
        
        <div class="demo-credentials">
            <h4>Credenciales de prueba:</h4>
            <p><strong>Usuario:</strong> usuario@restaurant.com / user123</p>
            <p><strong>Admin:</strong> admin@restaurant.com / admin123</p>
        </div>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
