<?php
/**
 * Punto de entrada de la aplicación
 */

// Definir constantes de rutas
define('ROOT_PATH', dirname(__DIR__));
define('SRC_PATH', ROOT_PATH . '/src');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Cargar configuración
require_once SRC_PATH . '/config/config.php';
require_once SRC_PATH . '/config/database.php';
require_once SRC_PATH . '/services/EmailService.php';


// Cargar helpers
require_once SRC_PATH . '/utils/helpers.php';

// Cargar modelos
require_once SRC_PATH . '/models/Usuario.php';
require_once SRC_PATH . '/models/Plato.php';
require_once SRC_PATH . '/models/Reserva.php';
require_once SRC_PATH . '/models/Mesa.php';

// Cargar controladores
require_once SRC_PATH . '/controllers/HomeController.php';
require_once SRC_PATH . '/controllers/AuthController.php';
require_once SRC_PATH . '/controllers/PlatoController.php';
require_once SRC_PATH . '/controllers/ReservaController.php';
require_once SRC_PATH . '/controllers/AdminController.php';
require_once SRC_PATH . '/controllers/MesaController.php';

// Cargar middleware
require_once SRC_PATH . '/middleware/AuthMiddleware.php';

// Cargar rutas
require_once SRC_PATH . '/routes/web.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener la URI solicitada
$uri = $_SERVER['REQUEST_URI'];

// Manejar la ruta
handleRoute($uri);
