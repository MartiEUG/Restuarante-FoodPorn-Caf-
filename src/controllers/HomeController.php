<?php
/**
 * Controlador de la página principal
 */

class HomeController {
    /**
     * Mostrar página de inicio
     */
    public function index() {
        $platoModel = new Plato();
        $platosDestacados = $platoModel->obtenerActivos(6, 0);
        
        require_once SRC_PATH . '/views/home/index.php';
    }
}
