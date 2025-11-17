<?php
/**
 * Controlador de Reservas
 * Gestiona las operaciones CRUD de reservas
 */

class ReservaController {
    private $reservaModel;
    private $platoModel;
    private $mesaModel;
    
    public function __construct() {
        $this->reservaModel = new Reserva();
        $this->platoModel = new Plato();
        $this->mesaModel = new Mesa();
    }
    
    /**
     * Mostrar formulario de nueva reserva
     */
    public function mostrarFormulario() {
        AuthMiddleware::requireUser();
        
        // Obtener platos activos para selección
        $platos = $this->platoModel->obtenerActivos(100, 0);
        
        require_once SRC_PATH . '/views/reservas/crear.php';
    }
    
    /**
     * Crear nueva reserva
     */
    public function crear() {
        AuthMiddleware::requireUser();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/reservas/nueva');
        }
        
        // Sanitizar datos
        $fecha = sanitize($_POST['fecha'] ?? '');
        $hora = sanitize($_POST['hora'] ?? '');
        $numPersonas = (int)($_POST['num_personas'] ?? 0);
        $comentarios = sanitize($_POST['comentarios'] ?? '');
        $platosSeleccionados = $_POST['platos'] ?? [];
        
        // Validaciones
        $errores = [];
        
        if (empty($fecha)) {
            $errores[] = 'La fecha es obligatoria';
        } elseif (strtotime($fecha) < strtotime(date('Y-m-d'))) {
            $errores[] = 'La fecha no puede ser anterior a hoy';
        }
        
        if (empty($hora)) {
            $errores[] = 'La hora es obligatoria';
        }
        
        if ($numPersonas < 1) {
            $errores[] = 'El número de personas debe ser al menos 1';
        }
        
        $mesasNecesarias = $this->mesaModel->calcularMesasNecesarias($numPersonas, $fecha, $hora);
        if (empty($mesasNecesarias)) {
            $errores[] = "No hay mesas disponibles con capacidad suficiente para $numPersonas personas en esa fecha y hora";
        } else {
            $validacion = $this->mesaModel->validarCapacidadMesas($mesasNecesarias, $numPersonas);
            if (!$validacion['valido']) {
                $errores[] = $validacion['mensaje'];
            }
        }
        
        if (!empty($errores)) {
            $_SESSION['errores_reserva'] = $errores;
            redirect('/reservas/nueva');
        }
        
        // Crear reserva
        $usuarioId = $_SESSION['usuario_id'];
        $reservaId = $this->reservaModel->crear($usuarioId, $fecha, $hora, $numPersonas, $comentarios);
        
        if ($reservaId) {
            $this->mesaModel->asignarAReserva($reservaId, $mesasNecesarias);
            
            if (!empty($platosSeleccionados)) {
                $this->reservaModel->agregarPlatos($reservaId, $platosSeleccionados);
            }
            
            setFlashMessage('success', 'Reserva creada exitosamente. Te contactaremos pronto para confirmarla');
            redirect('/reservas/mis-reservas');
        } else {
            setFlashMessage('error', 'Error al crear la reserva. Inténtalo de nuevo');
            redirect('/reservas/nueva');
        }
    }
    
    /**
     * Mostrar reservas del usuario actual
     */
    public function misReservas() {
        AuthMiddleware::requireUser();
        
        $usuarioId = $_SESSION['usuario_id'];
        $reservas = $this->reservaModel->obtenerPorUsuario($usuarioId);
        
        require_once SRC_PATH . '/views/reservas/mis-reservas.php';
    }
    
    /**
     * Cancelar reserva (solo el usuario propietario)
     */
    public function cancelar($id) {
        AuthMiddleware::requireUser();
        
        $reserva = $this->reservaModel->obtenerPorId($id);
        
        if (!$reserva || $reserva['id_usuario'] != $_SESSION['usuario_id']) {
            setFlashMessage('error', 'No tienes permiso para cancelar esta reserva');
            redirect('/reservas/mis-reservas');
        }
        
        if ($this->reservaModel->actualizarEstado($id, 'cancelada')) {
            setFlashMessage('success', 'Reserva cancelada exitosamente');
        } else {
            setFlashMessage('error', 'Error al cancelar la reserva');
        }
        
        redirect('/reservas/mis-reservas');
    }
}
