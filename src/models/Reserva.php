<?php
/**
 * Modelo de Reserva
 * Gestiona las operaciones relacionadas con reservas
 */

class Reserva {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear una nueva reserva
     */
    public function crear($idUsuario, $fecha, $hora, $numPersonas, $comentarios = '') {
        try {
            $sql = "INSERT INTO reservas (id_usuario, fecha, hora, num_personas, comentarios, estado) 
                    VALUES (:id_usuario, :fecha, :hora, :num_personas, :comentarios, 'pendiente')";
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                ':id_usuario' => $idUsuario,
                ':fecha' => $fecha,
                ':hora' => $hora,
                ':num_personas' => $numPersonas,
                ':comentarios' => $comentarios
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener reserva por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT r.*, u.nombre as nombre_usuario, u.email as email_usuario 
                FROM reservas r 
                INNER JOIN usuarios u ON r.id_usuario = u.id 
                WHERE r.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Obtener reservas de un usuario
     */
    public function obtenerPorUsuario($idUsuario) {
        $sql = "SELECT * FROM reservas WHERE id_usuario = :id_usuario ORDER BY fecha DESC, hora DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_usuario' => $idUsuario]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener todas las reservas (para admin)
     */
    public function obtenerTodas() {
        $sql = "SELECT r.*, u.nombre as nombre_usuario, u.email as email_usuario 
                FROM reservas r 
                INNER JOIN usuarios u ON r.id_usuario = u.id 
                ORDER BY r.fecha DESC, r.hora DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Actualizar estado de reserva
     */
    public function actualizarEstado($id, $estado) {
        $sql = "UPDATE reservas SET estado = :estado WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':estado' => $estado
        ]);
    }
    
    /**
     * Actualizar reserva completa
     */
    public function actualizar($id, $fecha, $hora, $numPersonas, $comentarios, $estado) {
        $sql = "UPDATE reservas SET fecha = :fecha, hora = :hora, num_personas = :num_personas, 
                comentarios = :comentarios, estado = :estado WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':fecha' => $fecha,
            ':hora' => $hora,
            ':num_personas' => $numPersonas,
            ':comentarios' => $comentarios,
            ':estado' => $estado
        ]);
    }
    
    /**
     * Eliminar reserva
     */
    public function eliminar($id) {
        $sql = "DELETE FROM reservas WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Agregar platos a una reserva
     */
    public function agregarPlatos($idReserva, $platos) {
        try {
            // Primero eliminar platos previos
            $sql = "DELETE FROM reserva_platos WHERE id_reserva = :id_reserva";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_reserva' => $idReserva]);
            
            // Insertar nuevos platos
            $sql = "INSERT INTO reserva_platos (id_reserva, id_plato, cantidad) 
                    VALUES (:id_reserva, :id_plato, :cantidad)";
            $stmt = $this->db->prepare($sql);
            
            foreach ($platos as $idPlato => $cantidad) {
                if ($cantidad > 0) {
                    $stmt->execute([
                        ':id_reserva' => $idReserva,
                        ':id_plato' => $idPlato,
                        ':cantidad' => $cantidad
                    ]);
                }
            }
            
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener platos de una reserva
     */
    public function obtenerPlatos($idReserva) {
        $sql = "SELECT p.*, rp.cantidad 
                FROM platos p
                INNER JOIN reserva_platos rp ON p.id = rp.id_plato
                WHERE rp.id_reserva = :id_reserva
                ORDER BY p.categoria, p.nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_reserva' => $idReserva]);
        return $stmt->fetchAll();
    }
    
    /**
     * Contar total de reservas
     */
    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM reservas";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Contar reservas por estado
     */
    public function contarPorEstado($estado) {
        $sql = "SELECT COUNT(*) as total FROM reservas WHERE estado = :estado";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':estado' => $estado]);
        $result = $stmt->fetch();
        return $result['total'];
    }
}
