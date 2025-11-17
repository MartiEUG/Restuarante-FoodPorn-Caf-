<?php
/**
 * Modelo de Mesa
 * Gestiona las operaciones relacionadas con mesas del restaurante
 */

class Mesa {
    private $db;
    
    const TURNO_COMIDA_INICIO = '12:00:00';
    const TURNO_COMIDA_FIN = '16:00:00';
    const TURNO_CENA_INICIO = '19:00:00';
    const TURNO_CENA_FIN = '23:59:59';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear una nueva mesa
     */
    public function crear($numero, $capacidad, $posicionX = 0, $posicionY = 0) {
        try {
            $sql = "INSERT INTO mesas (numero, capacidad, posicion_x, posicion_y) 
                    VALUES (:numero, :capacidad, :posicion_x, :posicion_y)";
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                ':numero' => $numero,
                ':capacidad' => $capacidad,
                ':posicion_x' => $posicionX,
                ':posicion_y' => $posicionY
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener todas las mesas activas
     */
    public function obtenerTodas() {
        $sql = "SELECT * FROM mesas WHERE activa = TRUE ORDER BY numero ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener mesa por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM mesas WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Obtener mesas disponibles para una fecha y hora
     */
    public function obtenerDisponibles($fecha, $hora) {
        $turno = $this->determinarTurno($hora);
        
        if ($turno === 'comida') {
            $turnoInicio = self::TURNO_COMIDA_INICIO;
            $turnoFin = self::TURNO_COMIDA_FIN;
        } else {
            $turnoInicio = self::TURNO_CENA_INICIO;
            $turnoFin = self::TURNO_CENA_FIN;
        }
        
        // Solo excluir mesas que tienen reservas en el mismo turno del mismo día
        $sql = "SELECT m.* FROM mesas m 
                WHERE m.activa = TRUE 
                AND m.id NOT IN (
                    SELECT rm.id_mesa FROM reserva_mesas rm
                    INNER JOIN reservas r ON rm.id_reserva = r.id
                    WHERE r.fecha = :fecha 
                    AND r.hora >= :turno_inicio
                    AND r.hora < :turno_fin
                    AND r.estado != 'cancelada'
                )
                ORDER BY m.numero ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':fecha' => $fecha, 
            ':turno_inicio' => $turnoInicio,
            ':turno_fin' => $turnoFin
        ]);
        return $stmt->fetchAll();
    }
    
    /**
     * Determinar el turno basado en la hora
     */
    private function determinarTurno($hora) {
        $horaTime = strtotime($hora);
        $comidaInicio = strtotime(self::TURNO_COMIDA_INICIO);
        $comidaFin = strtotime(self::TURNO_COMIDA_FIN);
        
        if ($horaTime >= $comidaInicio && $horaTime <= $comidaFin) {
            return 'comida';
        }
        return 'cena';
    }
    
    /**
     * Actualizar posición de mesa
     */
    public function actualizarPosicion($id, $posicionX, $posicionY) {
        $sql = "UPDATE mesas SET posicion_x = :posicion_x, posicion_y = :posicion_y WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':posicion_x' => $posicionX,
            ':posicion_y' => $posicionY
        ]);
    }
    
    /**
     * Actualizar estado de mesa
     */
    public function actualizarEstado($id, $estado) {
        $sql = "UPDATE mesas SET estado = :estado WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':estado' => $estado]);
    }
    
    /**
     * Actualizar nombre/número de mesa
     */
    public function actualizarNumero($id, $numero) {
        try {
            $sql = "UPDATE mesas SET numero = :numero WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id, ':numero' => $numero]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Juntar mesas (crear grupo de mesas)
     */
    public function juntarMesas($idsMesas) {
        try {
            $this->db->beginTransaction();
            
            if (count($idsMesas) < 2) {
                throw new Exception('Se necesitan al menos 2 mesas para juntar');
            }
            
            $placeholders = implode(',', array_fill(0, count($idsMesas), '?'));
            $sql = "SELECT id, numero, capacidad, activa, es_agrupada FROM mesas WHERE id IN ($placeholders)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($idsMesas);
            $mesasData = $stmt->fetchAll();
            
            if (count($mesasData) !== count($idsMesas)) {
                throw new Exception('Algunas mesas no están disponibles o no existen');
            }
            
            // Verificar que todas las mesas estén activas
            foreach ($mesasData as $mesa) {
                if (!$mesa['activa']) {
                    throw new Exception('La mesa ' . $mesa['numero'] . ' no está activa');
                }
                if ($mesa['es_agrupada']) {
                    throw new Exception('La mesa ' . $mesa['numero'] . ' ya está agrupada con otras mesas');
                }
            }
            
            $capacidadTotal = 0;
            foreach ($mesasData as $mesa) {
                $capacidadTotal += (int)$mesa['capacidad'];
            }
            
            if ($capacidadTotal <= 0) {
                throw new Exception('Capacidad total inválida');
            }
            
            $numerosMesas = array_column($mesasData, 'numero');
            sort($numerosMesas); // Ordenar los números
            $nombreGrupo = implode('+', $numerosMesas);
            
            $primeraMesa = $mesasData[0];
            
            $sql = "UPDATE mesas SET capacidad = ?, numero = ?, es_agrupada = TRUE WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$capacidadTotal, $nombreGrupo, $primeraMesa['id']]);
            
            // Desactivar las otras mesas del grupo
            $idsADesactivar = array_slice($idsMesas, 1);
            if (!empty($idsADesactivar)) {
                $placeholders = implode(',', array_fill(0, count($idsADesactivar), '?'));
                $sql = "UPDATE mesas SET activa = FALSE WHERE id IN ($placeholders)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute($idsADesactivar);
            }
            
            $this->db->commit();
            return $primeraMesa['id'];
            
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
    
    /**
     * Eliminar mesa (desactivar)
     */
    public function eliminar($id) {
        $sql = "UPDATE mesas SET activa = FALSE WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Contar mesas activas
     */
    public function contarActivas() {
        $sql = "SELECT COUNT(*) as total FROM mesas WHERE activa = TRUE";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Asignar mesas a una reserva
     */
    public function asignarAReserva($idReserva, $idsMesas) {
        try {
            // Primero eliminar asignaciones previas
            $sql = "DELETE FROM reserva_mesas WHERE id_reserva = :id_reserva";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_reserva' => $idReserva]);
            
            // Insertar nuevas asignaciones
            $sql = "INSERT INTO reserva_mesas (id_reserva, id_mesa) VALUES (:id_reserva, :id_mesa)";
            $stmt = $this->db->prepare($sql);
            
            foreach ($idsMesas as $idMesa) {
                $stmt->execute([
                    ':id_reserva' => $idReserva,
                    ':id_mesa' => $idMesa
                ]);
            }
            
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Liberar mesa de una reserva
     */
    public function liberarMesa($idMesa) {
        try {
            $sql = "DELETE FROM reserva_mesas WHERE id_mesa = :id_mesa";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id_mesa' => $idMesa]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener mesas asignadas a una reserva
     */
    public function obtenerPorReserva($idReserva) {
        $sql = "SELECT m.* FROM mesas m
                INNER JOIN reserva_mesas rm ON m.id = rm.id_mesa
                WHERE rm.id_reserva = :id_reserva
                ORDER BY m.numero ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_reserva' => $idReserva]);
        return $stmt->fetchAll();
    }
    
    /**
     * Calcular mesas necesarias para un número de personas
     */
    public function calcularMesasNecesarias($numPersonas, $fecha, $hora) {
        $mesasDisponibles = $this->obtenerDisponibles($fecha, $hora);
        $mesasSeleccionadas = [];
        $capacidadTotal = 0;
        
        // Ordenar mesas por capacidad descendente
        usort($mesasDisponibles, function($a, $b) {
            return $b['capacidad'] - $a['capacidad'];
        });
        
        // Seleccionar mesas hasta cubrir el número de personas
        foreach ($mesasDisponibles as $mesa) {
            if ($capacidadTotal >= $numPersonas) {
                break;
            }
            $mesasSeleccionadas[] = $mesa['id'];
            $capacidadTotal += $mesa['capacidad'];
        }
        
        return $capacidadTotal >= $numPersonas ? $mesasSeleccionadas : [];
    }
    
    /**
     * Obtener todas las mesas con paginación
     */
    public function obtenerTodasPaginadas($limite = 10, $offset = 0, $orden = 'numero', $direccion = 'ASC') {
        $ordenesPermitidos = ['numero', 'capacidad', 'estado', 'id'];
        $direccionesPermitidas = ['ASC', 'DESC'];
        
        if (!in_array($orden, $ordenesPermitidos)) {
            $orden = 'numero';
        }
        
        if (!in_array(strtoupper($direccion), $direccionesPermitidas)) {
            $direccion = 'ASC';
        }
        
        $sql = "SELECT * FROM mesas WHERE activa = TRUE ORDER BY $orden $direccion LIMIT :limite OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Validar que las mesas seleccionadas tengan capacidad suficiente
     */
    public function validarCapacidadMesas($idsMesas, $numPersonas) {
        if (empty($idsMesas)) {
            return ['valido' => false, 'mensaje' => 'No se han seleccionado mesas'];
        }
        
        $placeholders = implode(',', array_fill(0, count($idsMesas), '?'));
        $sql = "SELECT SUM(capacidad) as capacidad_total FROM mesas WHERE id IN ($placeholders) AND activa = TRUE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($idsMesas);
        $result = $stmt->fetch();
        
        $capacidadTotal = (int)$result['capacidad_total'];
        
        if ($capacidadTotal < $numPersonas) {
            return [
                'valido' => false, 
                'mensaje' => "La capacidad total de las mesas seleccionadas ($capacidadTotal personas) es insuficiente para $numPersonas personas"
            ];
        }
        
        return ['valido' => true, 'capacidad_total' => $capacidadTotal];
    }
}
