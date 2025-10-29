<?php 
$pageTitle = 'Página no encontrada - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <div class="error-page">
        <h1>404</h1>
        <h2>Página no encontrada</h2>
        <p>Lo sentimos, la página que buscas no existe.</p>
        <a href="<?php echo APP_URL; ?>/" class="btn btn-primary">Volver al Inicio</a>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
