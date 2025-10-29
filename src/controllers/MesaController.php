<?php
/**
 * Controlador de Mesas
 * Gestiona las operaciones relacionadas con mesas del restaurante
 */

class MesaController {
    private $mesaModel;
    
    public function __construct() {
        $this->mesaModel = new Mesa();
    }
    
    /**
     * Mostrar gestión de mesas (solo admin)
     * Añadida paginación y ordenamiento
     */
    public function gestionMesas() {
        AuthMiddleware::requireAdmin();
        
        $paginaActual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
        $mesasPorPagina = 5;
        $offset = ($paginaActual - 1) * $mesasPorPagina;
        
        $orden = $_GET['orden'] ?? 'numero';
        $direccion = $_GET['direccion'] ?? 'ASC';
        
        $mesas = $this->mesaModel->obtenerTodasPaginadas($mesasPorPagina, $offset, $orden, $direccion);
        $totalMesas = $this->mesaModel->contarActivas();
        $totalPaginas = ceil($totalMesas / $mesasPorPagina);
        
        $pageTitle = 'Gestión de Mesas';
        require_once SRC_PATH . '/views/admin/mesas/gestion.php';
        require_once SRC_PATH . '/views/layout/footer.php';
    }
    
    /**
     * Crear nueva mesa
     */
    public function crear() {
        AuthMiddleware::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/mesas');
        }
        
        $numero = sanitize($_POST['numero'] ?? '');
        $capacidad = (int)($_POST['capacidad'] ?? 0);
        $posicionX = (int)($_POST['posicion_x'] ?? 0);
        $posicionY = (int)($_POST['posicion_y'] ?? 0);
        
        // Validaciones
        if (empty($numero) || $capacidad <= 0) {
            setFlashMessage('error', 'Todos los campos son obligatorios');
            redirect('/admin/mesas');
        }
        
        
        $resultado = $this->mesaModel->crear($numero, $capacidad, $posicionX, $posicionY);
        
        if ($resultado) {
            setFlashMessage('success', 'Mesa creada exitosamente');
        } else {
            setFlashMessage('error', 'Error al crear la mesa. El número puede estar duplicado.');
        }
        
        redirect('/admin/mesas');
    }
    
    /**
     * Actualizar posición de mesa
     */
    public function actualizarPosicion() {
        AuthMiddleware::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            return;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $posicionX = (int)($_POST['posicion_x'] ?? 0);
        $posicionY = (int)($_POST['posicion_y'] ?? 0);
        
        $resultado = $this->mesaModel->actualizarPosicion($id, $posicionX, $posicionY);
        
        echo json_encode(['success' => $resultado]);
    }
    
    /**
     * Asignar reserva a mesa
     */
    public function asignarReserva() {
        AuthMiddleware::requireAdmin();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $reservaId = (int)($_POST['reserva_id'] ?? 0);
        $mesaId = (int)($_POST['mesa_id'] ?? 0);
        
        if ($reservaId <= 0 || $mesaId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }
        
        // Asignar la mesa a la reserva
        $resultado = $this->mesaModel->asignarAReserva($reservaId, [$mesaId]);
        
        if ($resultado) {
            // Actualizar estado de la mesa a reservada
            $this->mesaModel->actualizarEstado($mesaId, 'reservada');
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al asignar la reserva']);
        }
    }
    
    /**
     * Liberar mesa
     */
    public function liberar() {
        AuthMiddleware::requireAdmin();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            return;
        }
        
        $mesaId = (int)($_POST['mesa_id'] ?? 0);
        
        if ($mesaId <= 0) {
            echo json_encode(['success' => false]);
            return;
        }
        
        // Obtener la reserva asociada y eliminar la asignación
        $resultado = $this->mesaModel->liberarMesa($mesaId);
        
        if ($resultado) {
            // Actualizar estado de la mesa a disponible
            $this->mesaModel->actualizarEstado($mesaId, 'disponible');
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
    
    /**
     * Eliminar mesa
     */
    public function eliminar($id) {
        AuthMiddleware::requireAdmin();
        
        $this->mesaModel->liberarMesa($id);
        
        $resultado = $this->mesaModel->eliminar($id);
        
        if ($resultado) {
            setFlashMessage('success', 'Mesa eliminada exitosamente');
        } else {
            setFlashMessage('error', 'Error al eliminar la mesa');
        }
        
        redirect('/admin/mesas');
    }
    
    /**
     * Obtener mesas disponibles (AJAX)
     */
    public function obtenerDisponibles() {
        header('Content-Type: application/json');
        
        $fecha = $_GET['fecha'] ?? '';
        $hora = $_GET['hora'] ?? '';
        
        if (empty($fecha) || empty($hora)) {
            echo json_encode(['success' => false, 'mesas' => []]);
            return;
        }
        
        $mesas = $this->mesaModel->obtenerDisponibles($fecha, $hora);
        echo json_encode(['success' => true, 'mesas' => $mesas]);
    }
    
    /**
     * Actualizar nombre de mesa
     */
    public function actualizarNumero() {
        AuthMiddleware::requireAdmin();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $mesaId = (int)($_POST['mesa_id'] ?? 0);
        $numero = sanitize($_POST['numero'] ?? '');
        
        if ($mesaId <= 0 || empty($numero)) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }
        
        $resultado = $this->mesaModel->actualizarNumero($mesaId, $numero);
        
        if ($resultado) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el nombre']);
        }
    }
    
    /**
     * Juntar mesas seleccionadas
     */
    public function juntarMesas() {
        AuthMiddleware::requireAdmin();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'message' => 'Error al decodificar JSON: ' . json_last_error_msg()]);
            return;
        }
        
        $mesasIds = $data['mesas_ids'] ?? [];
        
        if (empty($mesasIds) || count($mesasIds) < 2) {
            echo json_encode(['success' => false, 'message' => 'Debes seleccionar al menos 2 mesas']);
            return;
        }
        
        $mesasIds = array_map('intval', $mesasIds);
        $mesasIds = array_filter($mesasIds, function($id) { return $id > 0; });
        $mesasIds = array_values($mesasIds); // Reindexar el array
        
        if (count($mesasIds) < 2) {
            echo json_encode(['success' => false, 'message' => 'IDs de mesas inválidos']);
            return;
        }
        
        try {
            $resultado = $this->mesaModel->juntarMesas($mesasIds);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'mesa_id' => $resultado]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error desconocido al juntar las mesas']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
