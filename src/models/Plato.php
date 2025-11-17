<?php
/**
 * Modelo de Plato
 * Gestiona las operaciones relacionadas con platos del menú
 */

class Plato {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear un nuevo plato
     */
    public function crear($nombre, $descripcion, $precio, $categoria, $imagen = null, $esMenuDia = false) {
        try {
            $sql = "INSERT INTO platos (nombre, descripcion, precio, categoria, imagen, activo, es_menu_dia) 
                    VALUES (:nombre, :descripcion, :precio, :categoria, :imagen, TRUE, :es_menu_dia)";
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':categoria' => $categoria,
                ':imagen' => $imagen,
                ':es_menu_dia' => $esMenuDia
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener plato por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM platos WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Obtener todos los platos activos con paginación
     * Fixed category ordering: Entrantes, Principales, Postres
     */
    public function obtenerActivos($limite = 10, $offset = 0, $categoria = null) {
        // Define el orden correcto de categorías
        $ordenCategorias = "CASE 
            WHEN categoria = 'Entrantes' THEN 1 
            WHEN categoria = 'Principales' THEN 2 
            WHEN categoria = 'Postres' THEN 3 
            ELSE 4 
        END";
        
        if ($categoria) {
            $sql = "SELECT * FROM platos WHERE activo = TRUE AND categoria = :categoria 
                    ORDER BY nombre LIMIT :limite OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':categoria', $categoria, PDO::PARAM_STR);
        } else {
            $sql = "SELECT * FROM platos WHERE activo = TRUE ORDER BY $ordenCategorias, nombre LIMIT :limite OFFSET :offset";
            $stmt = $this->db->prepare($sql);
        }
        
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener todos los platos (para admin)
     */
    public function obtenerTodos() {
        $sql = "SELECT * FROM platos ORDER BY categoria, nombre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar platos por palabra clave
     */
    public function buscar($keyword) {
        try {
            $sql = "SELECT * FROM platos 
                    WHERE activo = TRUE 
                    AND (nombre LIKE :keyword OR descripcion LIKE :keyword OR categoria LIKE :keyword) 
                    ORDER BY nombre";
            $stmt = $this->db->prepare($sql);
            $searchTerm = '%' . $keyword . '%';
            $stmt->execute([':keyword' => $searchTerm]);
            $result = $stmt->fetchAll();
            
            // Return empty array if no results instead of false
            return $result ? $result : [];
        } catch (PDOException $e) {
            error_log("Error en búsqueda de platos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener categorías únicas
     * Return categories in correct order
     */
    public function obtenerCategorias() {
        $sql = "SELECT DISTINCT categoria FROM platos WHERE activo = TRUE AND categoria IS NOT NULL 
                ORDER BY CASE 
                    WHEN categoria = 'Entrantes' THEN 1 
                    WHEN categoria = 'Principales' THEN 2 
                    WHEN categoria = 'Postres' THEN 3 
                    ELSE 4 
                END";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Obtener platos del menú del día
     */
    public function obtenerMenuDia() {
        $sql = "SELECT * FROM platos WHERE activo = TRUE AND es_menu_dia = TRUE ORDER BY categoria, nombre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Actualizar plato
     */
    public function actualizar($id, $nombre, $descripcion, $precio, $categoria, $activo, $esMenuDia = false, $imagen = null) {
        if ($imagen) {
            $sql = "UPDATE platos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, 
                    categoria = :categoria, activo = :activo, es_menu_dia = :es_menu_dia, imagen = :imagen WHERE id = :id";
            $params = [
                ':id' => $id,
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':categoria' => $categoria,
                ':activo' => $activo,
                ':es_menu_dia' => $esMenuDia,
                ':imagen' => $imagen
            ];
        } else {
            $sql = "UPDATE platos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, 
                    categoria = :categoria, activo = :activo, es_menu_dia = :es_menu_dia WHERE id = :id";
            $params = [
                ':id' => $id,
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':categoria' => $categoria,
                ':activo' => $activo,
                ':es_menu_dia' => $esMenuDia
            ];
        }
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Eliminar plato
     */
    public function eliminar($id) {
        $sql = "DELETE FROM platos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Contar total de platos activos
     */
    public function contarActivos($categoria = null) {
        if ($categoria) {
            $sql = "SELECT COUNT(*) as total FROM platos WHERE activo = TRUE AND categoria = :categoria";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':categoria' => $categoria]);
        } else {
            $sql = "SELECT COUNT(*) as total FROM platos WHERE activo = TRUE";
            $stmt = $this->db->query($sql);
        }
        $result = $stmt->fetch();
        return $result['total'];
    }
}
