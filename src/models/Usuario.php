<?php
/**
 * Modelo de Usuario
 * Gestiona las operaciones relacionadas con usuarios
 */

class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear un nuevo usuario
     */
    public function crear($nombre, $email, $contrasena, $rol = 'usuario') {
        try {
            $sql = "INSERT INTO usuarios (nombre, email, contrasena_hash, rol) VALUES (:nombre, :email, :contrasena_hash, :rol)";
            $stmt = $this->db->prepare($sql);
            
            $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
            
            $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':contrasena_hash' => $contrasenaHash,
                ':rol' => $rol
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Buscar usuario por email
     */
    public function buscarPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
    
    /**
     * Buscar usuario por ID
     */
    public function buscarPorId($id) {
        $sql = "SELECT id, nombre, email, rol, fecha_registro FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Verificar credenciales de login
     */
    public function verificarCredenciales($email, $contrasena) {
        $usuario = $this->buscarPorEmail($email);
        
        if ($usuario && password_verify($contrasena, $usuario['contrasena_hash'])) {
            return $usuario;
        }
        
        return false;
    }
    
    /**
     * Obtener todos los usuarios
     */
    public function obtenerTodos() {
        $sql = "SELECT id, nombre, email, rol, fecha_registro FROM usuarios ORDER BY fecha_registro DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Contar total de usuarios
     */
    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM usuarios";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
}
