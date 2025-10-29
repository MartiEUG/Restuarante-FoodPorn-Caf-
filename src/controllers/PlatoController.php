<?php
/**
 * Controlador de Platos
 * Gestiona la visualización pública de platos
 */

class PlatoController {
    private $platoModel;
    
    public function __construct() {
        $this->platoModel = new Plato();
    }
    
    /**
     * Listar todos los platos con paginación
     */
    public function listar() {
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $platosPorPagina = 9;
        $offset = ($paginaActual - 1) * $platosPorPagina;
        
        $categoria = isset($_GET['categoria']) ? sanitize($_GET['categoria']) : null;
        
        $platos = $this->platoModel->obtenerActivos($platosPorPagina, $offset, $categoria);
        $totalPlatos = $this->platoModel->contarActivos($categoria);
        $totalPaginas = ceil($totalPlatos / $platosPorPagina);
        
        $categorias = $this->platoModel->obtenerCategorias();
        
        require_once SRC_PATH . '/views/platos/listar.php';
    }
    
    /**
     * Mostrar detalle de un plato
     */
    public function detalle($id) {
        $plato = $this->platoModel->obtenerPorId($id);
        
        if (!$plato || !$plato['activo']) {
            setFlashMessage('error', 'Plato no encontrado');
            redirect('/menu');
        }
        
        require_once SRC_PATH . '/views/platos/detalle.php';
    }
    
    /**
     * Buscar platos
     */
    public function buscar() {
        $keyword = isset($_GET['q']) ? sanitize($_GET['q']) : '';
        
        if (empty($keyword)) {
            redirect('/menu');
        }
        
        $platos = $this->platoModel->buscar($keyword);
        
        require_once SRC_PATH . '/views/platos/buscar.php';
    }
}
