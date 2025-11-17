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
    
    
    /**
     * Actualizar usuario
     */
    public function actualizar($id, $nombre, $email, $rol) {
        try {
            $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, rol = :rol WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':id' => $id,
                ':nombre' => $nombre,
                ':email' => $email,
                ':rol' => $rol
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Actualizar contraseña de usuario
     */
    public function actualizarContrasena($id, $contrasena) {
        try {
            $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET contrasena_hash = :contrasena_hash WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':id' => $id,
                ':contrasena_hash' => $contrasenaHash
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Actualizar solo el rol de usuario
     */
    public function actualizarRol($id, $rol) {
        try {
            $sql = "UPDATE usuarios SET rol = :rol WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':id' => $id,
                ':rol' => $rol
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar usuario
     */
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Verificar si email ya existe (excepto para el usuario actual)
     */
    public function emailExiste($email, $id = null) {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE email = :email";
        if ($id) {
            $sql .= " AND id != :id";
        }
        
        $stmt = $this->db->prepare($sql);
        $params = [':email' => $email];
        if ($id) {
            $params[':id'] = $id;
        }
        
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}
