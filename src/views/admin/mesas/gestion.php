<?php 
$pageTitle = 'Gestión de Mesas - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-primary);">Gestión de Mesas</h1>
    
    <div class="actions-bar">
        <a href="<?php echo APP_URL; ?>/admin" class="btn btn-secondary">← Volver al Panel</a>
        <span style="color: var(--text-secondary); font-weight: 600;">Total de mesas: <?php echo $totalMesas; ?></span>
    </div>
    
    <!-- Formulario de crear mesa -->
    <div class="mesas-container fade-in">
        <h3>➕ Crear Nueva Mesa</h3>
        <form action="<?php echo APP_URL; ?>/admin/mesas/crear" method="POST" style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
            <div class="form-group" style="margin-bottom: 0; flex: 0 0 150px;">
                <label for="numero">Número de Mesa</label>
                <input type="number" id="numero" name="numero" min="1" required>
            </div>
            <div class="form-group" style="margin-bottom: 0; flex: 0 0 180px;">
                <label for="capacidad">Capacidad (personas)</label>
                <input type="number" id="capacidad" name="capacidad" min="1" required>
            </div>
            <input type="hidden" name="posicion_x" value="0">
            <input type="hidden" name="posicion_y" value="0">
            <button type="submit" class="btn btn-primary">Crear Mesa</button>
        </form>
    </div>
    
    <!-- Vista de grid con drag & drop -->
    <div class="mesas-container fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3>🪑 Mesas del Restaurante</h3>
            <button id="juntarMesasBtn" class="btn btn-primary" style="display: none;" onclick="juntarMesasSeleccionadas()">
                Juntar Mesas Seleccionadas (<span id="contadorMesas">0</span>)
            </button>
        </div>
        
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6;">
            <strong>Nota:</strong> Haz clic en las mesas para seleccionarlas y juntarlas. Ahora puedes hacer múltiples reservas en la misma mesa en diferentes turnos (comida: 12:00-16:00, cena: 19:00-23:59)
        </p>
        
        <!-- Grid de mesas con estilo de tarjetas -->
        <div class="mesas-grid">
            <?php 
            $reservaModel = new Reserva();
            $todasReservas = $reservaModel->obtenerTodas();
            
            foreach ($mesas as $mesa): 
                $reservaAsignada = null;
                
                // Buscar si hay una reserva asignada a esta mesa
                foreach ($todasReservas as $r) {
                    $mesasDeReserva = $this->mesaModel->obtenerPorReserva($r['id']);
                    foreach ($mesasDeReserva as $mr) {
                        if ($mr['id'] == $mesa['id']) {
                            $reservaAsignada = $r;
                            break 2;
                        }
                    }
                }
            ?>
                <div class="mesa-card" data-mesa-id="<?php echo $mesa['id']; ?>" onclick="toggleMesaSeleccion(<?php echo $mesa['id']; ?>)">
                    <div class="mesa-card-header">
                        <h4>Mesa <?php echo htmlspecialchars($mesa['numero']); ?></h4>
                        <span class="badge badge-<?php echo $mesa['estado']; ?>">
                            <?php echo ucfirst($mesa['estado']); ?>
                        </span>
                    </div>
                    
                    <div class="mesa-card-body">
                        <div class="mesa-info">
                            <span class="mesa-capacidad">👥 <?php echo $mesa['capacidad']; ?> personas</span>
                        </div>
                        
                        <?php if ($reservaAsignada): ?>
                            <div class="mesa-reserva">
                                <strong>Reserva Actual:</strong><br>
                                <span><?php echo htmlspecialchars($reservaAsignada['nombre_usuario']); ?></span><br>
                                <span style="font-size: 0.85rem; color: var(--text-secondary);">
                                    <?php echo date('d/m/Y', strtotime($reservaAsignada['fecha'])); ?> - 
                                    <?php echo date('H:i', strtotime($reservaAsignada['hora'])); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mesa-card-actions" onclick="event.stopPropagation();">
                        <?php if ($reservaAsignada): ?>
                            <button class="btn btn-small btn-secondary" onclick="liberarMesa(<?php echo $mesa['id']; ?>)">
                                Liberar
                            </button>
                        <?php endif; ?>
                        <button class="btn btn-small btn-primary" onclick="editarMesa(<?php echo $mesa['id']; ?>, '<?php echo htmlspecialchars($mesa['numero']); ?>')">
                            Editar
                        </button>
                        <a href="<?php echo APP_URL; ?>/admin/mesas/eliminar/<?php echo $mesa['id']; ?>" 
                           class="btn btn-small btn-danger"
                           onclick="return confirm('¿Eliminar esta mesa?')">
                            Eliminar
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Paginación -->
        <?php if ($totalPaginas > 1): ?>
            <div class="pagination">
                <?php if ($paginaActual > 1): ?>
                    <a href="?pagina=<?php echo $paginaActual - 1; ?>&orden=<?php echo $orden; ?>&direccion=<?php echo $direccion; ?>" class="btn btn-secondary">
                        ← Anterior
                    </a>
                <?php endif; ?>
                
                <span style="margin: 0 1rem; color: var(--text-primary); font-weight: 600;">
                    Página <?php echo $paginaActual; ?> de <?php echo $totalPaginas; ?>
                </span>
                
                <?php if ($paginaActual < $totalPaginas): ?>
                    <a href="?pagina=<?php echo $paginaActual + 1; ?>&orden=<?php echo $orden; ?>&direccion=<?php echo $direccion; ?>" class="btn btn-secondary">
                        Siguiente →
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Sección de reservas pendientes -->
        <div style="margin-top: 3rem;">
            <h3 style="color: var(--primary-color); margin-bottom: 1.5rem;">📋 Reservas Pendientes de Asignar</h3>
            <?php
            $reservasSinMesa = [];
            $reservasYaMostradas = [];
            
            foreach ($todasReservas as $reserva) {
                if (in_array($reserva['id'], $reservasYaMostradas)) {
                    continue;
                }
                
                $mesasAsignadas = $this->mesaModel->obtenerPorReserva($reserva['id']);
                if (empty($mesasAsignadas) && $reserva['estado'] !== 'cancelada') {
                    $reservasSinMesa[] = $reserva;
                    $reservasYaMostradas[] = $reserva['id'];
                }
            }
            
            if (empty($reservasSinMesa)): ?>
                <p style="color: var(--text-secondary); font-style: italic;">No hay reservas pendientes de asignar</p>
            <?php else: ?>
                <div class="reservas-pendientes-grid">
                    <?php foreach ($reservasSinMesa as $reserva): ?>
                        <div class="reserva-pendiente-card">
                            <div class="reserva-pendiente-info">
                                <strong><?php echo htmlspecialchars($reserva['nombre_usuario']); ?></strong>
                                <span><?php echo date('d/m/Y', strtotime($reserva['fecha'])); ?> - <?php echo date('H:i', strtotime($reserva['hora'])); ?></span>
                                <span>👥 <?php echo $reserva['num_personas']; ?> personas</span>
                                <span class="badge badge-<?php echo $reserva['estado']; ?>">
                                    <?php echo ucfirst($reserva['estado']); ?>
                                </span>
                            </div>
                            <button class="btn btn-small btn-primary" onclick="asignarReservaManual(<?php echo $reserva['id']; ?>)">
                                Asignar Mesa
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
let mesasSeleccionadas = [];

function toggleMesaSeleccion(mesaId) {
    const mesaCard = document.querySelector(`[data-mesa-id="${mesaId}"]`);
    const index = mesasSeleccionadas.indexOf(mesaId);
    
    if (index > -1) {
        mesasSeleccionadas.splice(index, 1);
        mesaCard.classList.remove('mesa-seleccionada');
    } else {
        mesasSeleccionadas.push(mesaId);
        mesaCard.classList.add('mesa-seleccionada');
    }
    
    actualizarBotonJuntar();
}

function actualizarBotonJuntar() {
    const btn = document.getElementById('juntarMesasBtn');
    const contador = document.getElementById('contadorMesas');
    
    if (mesasSeleccionadas.length >= 2) {
        btn.style.display = 'block';
        contador.textContent = mesasSeleccionadas.length;
    } else {
        btn.style.display = 'none';
    }
}

function juntarMesasSeleccionadas() {
    if (mesasSeleccionadas.length < 2) {
        alert('Debes seleccionar al menos 2 mesas para juntar');
        return;
    }
    
    if (!confirm(`¿Deseas juntar ${mesasSeleccionadas.length} mesas?`)) {
        return;
    }
    
    fetch('<?php echo APP_URL; ?>/admin/mesas/juntar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ mesas_ids: mesasSeleccionadas })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Mesas juntadas exitosamente');
            location.reload();
        } else {
            alert('Error al juntar las mesas: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al juntar las mesas');
    });
}

function liberarMesa(mesaId) {
    if (!confirm('¿Deseas liberar esta mesa?')) return;
    
    fetch('<?php echo APP_URL; ?>/admin/mesas/liberar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mesa_id=${mesaId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Mesa liberada exitosamente');
            location.reload();
        } else {
            alert('Error al liberar la mesa: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error al liberar mesa:', error);
        alert('Error de conexión al liberar la mesa');
    });
}

function editarMesa(mesaId, numeroActual) {
    const nuevoNumero = prompt('Ingresa el nuevo número de mesa:', numeroActual);
    
    if (nuevoNumero === null || nuevoNumero.trim() === '' || nuevoNumero === numeroActual) {
        return;
    }
    
    fetch('<?php echo APP_URL; ?>/admin/mesas/actualizar-numero', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mesa_id=${mesaId}&numero=${encodeURIComponent(nuevoNumero)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Número de mesa actualizado exitosamente');
            location.reload();
        } else {
            alert('Error al actualizar el número de la mesa');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el nombre');
    });
}

function asignarReservaManual(reservaId) {
    const mesaId = prompt('Ingresa el ID de la mesa a asignar:');
    
    if (!mesaId || isNaN(mesaId)) {
        alert('ID de mesa inválido');
        return;
    }
    
    fetch('<?php echo APP_URL; ?>/admin/mesas/asignar-reserva', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `reserva_id=${reservaId}&mesa_id=${mesaId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Reserva asignada exitosamente');
            location.reload();
        } else {
            alert('Error al asignar la reserva: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al asignar la reserva');
    });
}
</script>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
