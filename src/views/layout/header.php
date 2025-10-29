<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="<?php echo APP_URL; ?>/" class="logo"><?php echo APP_NAME; ?></a>
            <ul class="nav-menu">
                <li><a href="<?php echo APP_URL; ?>/">Inicio</a></li>
                <li><a href="<?php echo APP_URL; ?>/menu">Menú</a></li>
                
                <?php if (isAuthenticated()): ?>
                    <li><a href="<?php echo APP_URL; ?>/reservas/nueva">Nueva Reserva</a></li>
                    <li><a href="<?php echo APP_URL; ?>/reservas/mis-reservas">Mis Reservas</a></li>
                    
                    <?php if (isAdmin()): ?>
                        <li><a href="<?php echo APP_URL; ?>/admin">Panel Admin</a></li>
                    <?php endif; ?>
                    
                    <li class="user-menu">
                        <span>Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
                        <a href="<?php echo APP_URL; ?>/logout" class="btn-logout">Cerrar Sesión</a>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo APP_URL; ?>/login">Iniciar Sesión</a></li>
                    <li><a href="<?php echo APP_URL; ?>/registro" class="btn-primary">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    
    <?php 
    // Mostrar mensajes flash
    $flashMessage = getFlashMessage();
    if ($flashMessage): 
    ?>
        <div class="alert alert-<?php echo $flashMessage['type']; ?>">
            <div class="container">
                <?php echo htmlspecialchars($flashMessage['message']); ?>
            </div>
        </div>
    <?php endif; ?>
    
    <main class="main-content">
