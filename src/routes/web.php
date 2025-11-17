<?php
/**
 * Definición de rutas de la aplicación
 */

function handleRoute($uri) {
    // Eliminar query string
    $uri = strtok($uri, '?');
    
    $uri = rtrim($uri, '/');
    if ($uri === '') {
        $uri = '/';
    }
    
    // Rutas públicas
    if ($uri === '/' || $uri === '') {
        $controller = new HomeController();
        $controller->index();
        return;
    }
    
    // Autenticación
    if ($uri === '/registro') {
        $controller = new AuthController();
        $controller->mostrarRegistro();
        return;
    }
    
    if ($uri === '/registro/procesar') {
        $controller = new AuthController();
        $controller->registro();
        return;
    }
    
    if ($uri === '/login') {
        $controller = new AuthController();
        $controller->mostrarLogin();
        return;
    }
    
    if ($uri === '/login/procesar') {
        $controller = new AuthController();
        $controller->login();
        return;
    }
    
    if ($uri === '/logout') {
        $controller = new AuthController();
        $controller->logout();
        return;
    }
    
    // Platos (público)
    if ($uri === '/menu') {
        $controller = new PlatoController();
        $controller->listar();
        return;
    }
    
    if (preg_match('/^\/menu\/(\d+)$/', $uri, $matches)) {
        $controller = new PlatoController();
        $controller->detalle($matches[1]);
        return;
    }
    
    if ($uri === '/menu/buscar') {
        $controller = new PlatoController();
        $controller->buscar();
        return;
    }
    
    if ($uri === '/menu/menu-dia') {
        $controller = new PlatoController();
        $controller->menuDia();
        return;
    }
    
    // Reservas (usuario autenticado NO admin)
    if ($uri === '/reservas/nueva') {
        $controller = new ReservaController();
        $controller->mostrarFormulario();
        return;
    }
    
    if ($uri === '/reservas/crear') {
        $controller = new ReservaController();
        $controller->crear();
        return;
    }
    
    if ($uri === '/reservas/mis-reservas') {
        $controller = new ReservaController();
        $controller->misReservas();
        return;
    }
    
    if (preg_match('/^\/reservas\/cancelar\/(\d+)$/', $uri, $matches)) {
        $controller = new ReservaController();
        $controller->cancelar($matches[1]);
        return;
    }
    
    // Panel de administración
    if ($uri === '/admin' || $uri === '/admin/') {
        $controller = new AdminController();
        $controller->dashboard();
        return;
    }
    
    // Admin - Platos
    if ($uri === '/admin/platos') {
        $controller = new AdminController();
        $controller->listarPlatos();
        return;
    }
    
    if ($uri === '/admin/platos/crear') {
        $controller = new AdminController();
        $controller->mostrarCrearPlato();
        return;
    }
    
    if ($uri === '/admin/platos/guardar') {
        $controller = new AdminController();
        $controller->crearPlato();
        return;
    }
    
    if (preg_match('/^\/admin\/platos\/editar\/(\d+)$/', $uri, $matches)) {
        $controller = new AdminController();
        $controller->mostrarEditarPlato($matches[1]);
        return;
    }
    
    if (preg_match('/^\/admin\/platos\/actualizar\/(\d+)$/', $uri, $matches)) {
        $controller = new AdminController();
        $controller->actualizarPlato($matches[1]);
        return;
    }
    
    if (preg_match('/^\/admin\/platos\/eliminar\/(\d+)$/', $uri, $matches)) {
        $controller = new AdminController();
        $controller->eliminarPlato($matches[1]);
        return;
    }
    
    // Admin - Reservas
    if ($uri === '/admin/reservas') {
        $controller = new AdminController();
        $controller->listarReservas();
        return;
    }
    
    if (preg_match('/^\/admin\/reservas\/confirmar\/(\d+)$/', $uri, $matches)) {
        $controller = new AdminController();
        $controller->confirmarReserva($matches[1]);
        return;
    }
    
    if (preg_match('/^\/admin\/reservas\/editar\/(\d+)$/', $uri, $matches)) {
        $controller = new AdminController();
        $controller->mostrarEditarReserva($matches[1]);
        return;
    }
    
    if (preg_match('/^\/admin\/reservas\/actualizar\/(\d+)$/', $uri, $matches)) {
        $controller = new AdminController();
        $controller->actualizarReserva($matches[1]);
        return;
    }
    
    if (preg_match('/^\/admin\/reservas\/eliminar\/(\d+)$/', $uri, $matches)) {
        $controller = new AdminController();
        $controller->eliminarReserva($matches[1]);
        return;
    }
    
    // Admin - Usuarios
    if ($uri === '/admin/usuarios') {
        $controller = new AdminController();
        $controller->listarUsuarios();
        return;
    }
    
    if (preg_match('/^\/admin\/usuarios\/editar\/(\d+)$/', $uri, $matches)) {
        $controller = new AdminController();
        $controller->mostrarEditarUsuario($matches[1]);
        return;
    }
    
    if (preg_match('/^\/admin\/usuarios\/actualizar\/(\d+)$/', $uri, $matches)) {
        $controller = new AdminController();
        $controller->actualizarUsuario($matches[1]);
        return;
    }
    
    if (preg_match('/^\/admin\/usuarios\/cambiar-rol\/(\d+)$/', $uri, $matches)) {
        $controller = new AdminController();
        $controller->cambiarRolUsuario($matches[1]);
        return;
    }
    
    if (preg_match('/^\/admin\/usuarios\/eliminar\/(\d+)$/', $uri, $matches)) {
        $controller = new AdminController();
        $controller->eliminarUsuario($matches[1]);
        return;
    }
    
    if ($uri === '/admin/mesas') {
        $controller = new MesaController();
        $controller->gestionMesas();
        return;
    }
    
    if ($uri === '/admin/mesas/crear') {
        $controller = new MesaController();
        $controller->crear();
        return;
    }
    
    if ($uri === '/admin/mesas/actualizar-posicion') {
        $controller = new MesaController();
        $controller->actualizarPosicion();
        return;
    }
    
    if ($uri === '/admin/mesas/asignar-reserva') {
        $controller = new MesaController();
        $controller->asignarReserva();
        return;
    }
    
    if ($uri === '/admin/mesas/liberar') {
        $controller = new MesaController();
        $controller->liberar();
        return;
    }
    
    if ($uri === '/admin/mesas/actualizar-numero') {
        $controller = new MesaController();
        $controller->actualizarNumero();
        return;
    }
    
    if ($uri === '/admin/mesas/juntar') {
        $controller = new MesaController();
        $controller->juntarMesas();
        return;
    }
    
    if (preg_match('/^\/admin\/mesas\/eliminar\/(\d+)$/', $uri, $matches)) {
        $controller = new MesaController();
        $controller->eliminar($matches[1]);
        return;
    }
    
    if ($uri === '/admin/reservas/asignar-mesa') {
        $controller = new AdminController();
        $controller->asignarMesaAReserva();
        return;
    }
    
    if ($uri === '/api/mesas/disponibles') {
        $controller = new MesaController();
        $controller->obtenerDisponibles();
        return;
    }
    
    // Página 404
    http_response_code(404);
    require_once SRC_PATH . '/views/errors/404.php';
}
