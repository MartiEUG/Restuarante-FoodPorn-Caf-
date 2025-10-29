<?php 
$pageTitle = 'Panel de Administración - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-primary);">Panel de Administración</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Reservas</h3>
            <p class="stat-number"><?php echo $totalReservas; ?></p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
            <h3>Reservas Pendientes</h3>
            <p class="stat-number"><?php echo $reservasPendientes; ?></p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
            <h3>Reservas Confirmadas</h3>
            <p class="stat-number"><?php echo $reservasConfirmadas; ?></p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);">
            <h3>Platos Activos</h3>
            <p class="stat-number"><?php echo $totalPlatos; ?></p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);">
            <h3>Usuarios Registrados</h3>
            <p class="stat-number"><?php echo $totalUsuarios; ?></p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);">
            <h3>Mesas Activas</h3>
            <p class="stat-number"><?php echo $totalMesas; ?> / 20</p>
        </div>
    </div>
    
    <div class="admin-menu">
        <h2>Gestión del Restaurante</h2>
        <div class="admin-links">
            <a href="<?php echo APP_URL; ?>/admin/reservas" class="admin-link-card">
                <h3>📅 Gestionar Reservas</h3>
                <p>Ver, editar y confirmar reservas de clientes</p>
            </a>
            <a href="<?php echo APP_URL; ?>/admin/mesas" class="admin-link-card">
                <h3>🪑 Gestionar Mesas</h3>
                <p>Administrar mesas, asignar reservas y juntar mesas</p>
            </a>
            <a href="<?php echo APP_URL; ?>/admin/platos" class="admin-link-card">
                <h3>🍽️ Gestionar Platos</h3>
                <p>Administrar el menú del restaurante</p>
            </a>
            <a href="<?php echo APP_URL; ?>/admin/usuarios" class="admin-link-card">
                <h3>👥 Ver Usuarios</h3>
                <p>Lista de usuarios registrados en el sistema</p>
            </a>
        </div>
    </div>
</div>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
