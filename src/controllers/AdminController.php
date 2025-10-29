<?php
/**
 * Controlador de Administración
 * Gestiona el panel de administración y operaciones CRUD
 */

class AdminController {
    private $platoModel;
    private $reservaModel;
    private $usuarioModel;
    private $mesaModel;
    
    public function __construct() {
        $this->platoModel = new Plato();
        $this->reservaModel = new Reserva();
        $this->usuarioModel = new Usuario();
        $this->mesaModel = new Mesa();
    }
    
    /**
     * Panel principal de administración
     */
    public function dashboard() {
        AuthMiddleware::requireAdmin();
        
        // Obtener estadísticas
        $totalReservas = $this->reservaModel->contarTotal();
        $reservasPendientes = $this->reservaModel->contarPorEstado('pendiente');
        $reservasConfirmadas = $this->reservaModel->contarPorEstado('confirmada');
        $totalPlatos = $this->platoModel->contarActivos();
        $totalUsuarios = $this->usuarioModel->contarTotal();
        $totalMesas = $this->mesaModel->contarActivas();
        
        require_once SRC_PATH . '/views/admin/dashboard.php';
    }
    
    // ========== GESTIÓN DE PLATOS ==========
    
    /**
     * Listar todos los platos (admin)
     */
    public function listarPlatos() {
        AuthMiddleware::requireAdmin();
        $platos = $this->platoModel->obtenerTodos();
        require_once SRC_PATH . '/views/admin/platos/listar.php';
    }
    
    /**
     * Mostrar formulario de crear plato
     */
    public function mostrarCrearPlato() {
        AuthMiddleware::requireAdmin();
        require_once SRC_PATH . '/views/admin/platos/crear.php';
    }
    
    /**
     * Crear nuevo plato
     */
    public function crearPlato() {
        AuthMiddleware::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/platos');
        }
        
        $nombre = sanitize($_POST['nombre'] ?? '');
        $descripcion = sanitize($_POST['descripcion'] ?? '');
        $precio = (float)($_POST['precio'] ?? 0);
        $categoria = sanitize($_POST['categoria'] ?? '');
        $esMenuDia = isset($_POST['es_menu_dia']) ? 1 : 0;
        
        if (empty($nombre) || $precio <= 0) {
            setFlashMessage('error', 'Nombre y precio son obligatorios');
            redirect('/admin/platos/crear');
        }
        
        if ($this->platoModel->crear($nombre, $descripcion, $precio, $categoria, null, $esMenuDia)) {
            setFlashMessage('success', 'Plato creado exitosamente');
        } else {
            setFlashMessage('error', 'Error al crear el plato');
        }
        
        redirect('/admin/platos');
    }
    
    /**
     * Mostrar formulario de editar plato
     */
    public function mostrarEditarPlato($id) {
        AuthMiddleware::requireAdmin();
        $plato = $this->platoModel->obtenerPorId($id);
        
        if (!$plato) {
            setFlashMessage('error', 'Plato no encontrado');
            redirect('/admin/platos');
        }
        
        require_once SRC_PATH . '/views/admin/platos/editar.php';
    }
    
    /**
     * Actualizar plato
     */
    public function actualizarPlato($id) {
        AuthMiddleware::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/platos');
        }
        
        $nombre = sanitize($_POST['nombre'] ?? '');
        $descripcion = sanitize($_POST['descripcion'] ?? '');
        $precio = (float)($_POST['precio'] ?? 0);
        $categoria = sanitize($_POST['categoria'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;
        $esMenuDia = isset($_POST['es_menu_dia']) ? 1 : 0;
        
        if (empty($nombre) || $precio <= 0) {
            setFlashMessage('error', 'Nombre y precio son obligatorios');
            redirect('/admin/platos/editar/' . $id);
        }
        
        if ($this->platoModel->actualizar($id, $nombre, $descripcion, $precio, $categoria, $activo, $esMenuDia)) {
            setFlashMessage('success', 'Plato actualizado exitosamente');
        } else {
            setFlashMessage('error', 'Error al actualizar el plato');
        }
        
        redirect('/admin/platos');
    }
    
    /**
     * Eliminar plato
     */
    public function eliminarPlato($id) {
        AuthMiddleware::requireAdmin();
        
        if ($this->platoModel->eliminar($id)) {
            setFlashMessage('success', 'Plato eliminado exitosamente');
        } else {
            setFlashMessage('error', 'Error al eliminar el plato');
        }
        
        redirect('/admin/platos');
    }
    
    // ========== GESTIÓN DE RESERVAS ==========
    
    /**
     * Listar todas las reservas (admin)
     */
    public function listarReservas() {
        AuthMiddleware::requireAdmin();
        $reservas = $this->reservaModel->obtenerTodas();
        $mesas = $this->mesaModel->obtenerTodas();
        $mesaModel = $this->mesaModel;
        require_once SRC_PATH . '/views/admin/reservas/listar.php';
    }
    
    /**
     * Mostrar formulario de editar reserva
     */
    public function mostrarEditarReserva($id) {
        AuthMiddleware::requireAdmin();
        $reserva = $this->reservaModel->obtenerPorId($id);
        
        if (!$reserva) {
            setFlashMessage('error', 'Reserva no encontrada');
            redirect('/admin/reservas');
        }
        
        $platosReserva = $this->reservaModel->obtenerPlatos($id);
        $mesasReserva = $this->mesaModel->obtenerPorReserva($id);
        
        require_once SRC_PATH . '/views/admin/reservas/editar.php';
    }
    
    /**
     * Actualizar reserva
     */
    public function actualizarReserva($id) {
        AuthMiddleware::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/reservas');
        }
        
        $fecha = sanitize($_POST['fecha'] ?? '');
        $hora = sanitize($_POST['hora'] ?? '');
        $numPersonas = (int)($_POST['num_personas'] ?? 0);
        $comentarios = sanitize($_POST['comentarios'] ?? '');
        $estado = sanitize($_POST['estado'] ?? 'pendiente');
        
        if ($this->reservaModel->actualizar($id, $fecha, $hora, $numPersonas, $comentarios, $estado)) {
            setFlashMessage('success', 'Reserva actualizada exitosamente');
        } else {
            setFlashMessage('error', 'Error al actualizar la reserva');
        }
        
        redirect('/admin/reservas');
    }
    
    /**
     * Confirmar reserva
     */
    public function confirmarReserva($id) {
        AuthMiddleware::requireAdmin();
        
        if ($this->reservaModel->actualizarEstado($id, 'confirmada')) {
            setFlashMessage('success', 'Reserva confirmada exitosamente');
        } else {
            setFlashMessage('error', 'Error al confirmar la reserva');
        }
        
        redirect('/admin/reservas');
    }
    
    /**
     * Eliminar reserva
     */
    public function eliminarReserva($id) {
        AuthMiddleware::requireAdmin();
        
        if ($this->reservaModel->eliminar($id)) {
            setFlashMessage('success', 'Reserva eliminada exitosamente');
        } else {
            setFlashMessage('error', 'Error al eliminar la reserva');
        }
        
        redirect('/admin/reservas');
    }
    
    /**
     * Asignar mesa a reserva desde la página de reservas
     */
    public function asignarMesaAReserva() {
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
            // Actualizar estado de la reserva a confirmada
            $this->reservaModel->actualizarEstado($reservaId, 'confirmada');
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al asignar la mesa']);
        }
    }
    
    // ========== GESTIÓN DE USUARIOS ==========
    
    /**
     * Listar todos los usuarios
     */
    public function listarUsuarios() {
        AuthMiddleware::requireAdmin();
        $usuarios = $this->usuarioModel->obtenerTodos();
        require_once SRC_PATH . '/views/admin/usuarios/listar.php';
    }
}
