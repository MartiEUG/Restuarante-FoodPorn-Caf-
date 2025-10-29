<?php 
$pageTitle = 'Gestionar Reservas - ' . APP_NAME;
include SRC_PATH . '/views/layout/header.php'; 
?>

<div class="container">
    <h1>Gestionar Reservas</h1>
    
    <div class="actions-bar">
        <a href="<?php echo APP_URL; ?>/admin" class="btn btn-secondary">Volver al Panel</a>
    </div>
    
    <!-- Añadido layout de dos columnas para drag and drop -->
    <div class="reservas-mesas-view">
        <!-- Columna de reservas -->
        <div class="reservas-column">
            <h2>Reservas Pendientes de Asignar</h2>
            <div class="reservas-list">
                <?php foreach ($reservas as $reserva): 
                    $mesasAsignadas = $mesaModel->obtenerPorReserva($reserva['id']);
                    if (empty($mesasAsignadas) && $reserva['estado'] !== 'cancelada'):
                ?>
                    <div class="reserva-card" 
                         draggable="true" 
                         data-reserva-id="<?php echo $reserva['id']; ?>"
                         data-num-personas="<?php echo $reserva['num_personas']; ?>">
                        <div class="reserva-header">
                            <strong><?php echo htmlspecialchars($reserva['nombre_usuario']); ?></strong>
                            <span class="badge badge-<?php echo $reserva['estado']; ?>">
                                <?php echo ucfirst($reserva['estado']); ?>
                            </span>
                        </div>
                        <div class="reserva-details">
                            <p><strong>Fecha:</strong> <?php echo formatearFecha($reserva['fecha']); ?></p>
                            <p><strong>Hora:</strong> <?php echo formatearHora($reserva['hora']); ?></p>
                            <p><strong>Personas:</strong> <?php echo $reserva['num_personas']; ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($reserva['email_usuario']); ?></p>
                        </div>
                        <div class="reserva-actions">
                            <?php if ($reserva['estado'] === 'pendiente'): ?>
                                <a href="<?php echo APP_URL; ?>/admin/reservas/confirmar/<?php echo $reserva['id']; ?>" 
                                   class="btn btn-small btn-success"
                                   onclick="return confirm('¿Confirmar esta reserva?')">
                                    Confirmar
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo APP_URL; ?>/admin/reservas/editar/<?php echo $reserva['id']; ?>" class="btn btn-small">Editar</a>
                            <a href="<?php echo APP_URL; ?>/admin/reservas/eliminar/<?php echo $reserva['id']; ?>" 
                               class="btn btn-small btn-danger"
                               onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">
                                Eliminar
                            </a>
                        </div>
                    </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
        </div>
        
        <!-- Columna de mesas -->
        <div class="mesas-column">
            <h2>Mesas Disponibles</h2>
            <div class="mesas-grid">
                <?php foreach ($mesas as $mesa): ?>
                    <div class="mesa-item <?php echo $mesa['estado']; ?>" 
                         data-mesa-id="<?php echo $mesa['id']; ?>"
                         data-capacidad="<?php echo $mesa['capacidad']; ?>">
                        <div class="mesa-numero">Mesa <?php echo $mesa['numero']; ?></div>
                        <div class="mesa-capacidad"><?php echo $mesa['capacidad']; ?> personas</div>
                        <div class="mesa-estado">
                            <span class="badge badge-<?php echo $mesa['estado']; ?>">
                                <?php echo ucfirst($mesa['estado']); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Tabla de todas las reservas -->
    <div style="margin-top: 3rem;">
        <h2>Todas las Reservas</h2>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Personas</th>
                        <th>Mesa Asignada</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): 
                        $mesasAsignadas = $mesaModel->obtenerPorReserva($reserva['id']);
                        $mesasTexto = !empty($mesasAsignadas) ? implode(', ', array_map(function($m) { return 'Mesa ' . $m['numero']; }, $mesasAsignadas)) : 'Sin asignar';
                    ?>
                        <tr>
                            <td><?php echo $reserva['id']; ?></td>
                            <td><?php echo htmlspecialchars($reserva['nombre_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['email_usuario']); ?></td>
                            <td><?php echo formatearFecha($reserva['fecha']); ?></td>
                            <td><?php echo formatearHora($reserva['hora']); ?></td>
                            <td><?php echo $reserva['num_personas']; ?></td>
                            <td>
                                <?php if (empty($mesasAsignadas)): ?>
                                    <span style="color: var(--text-secondary); font-style: italic;">Sin asignar</span>
                                <?php else: ?>
                                    <strong style="color: var(--success-color);"><?php echo $mesasTexto; ?></strong>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $reserva['estado']; ?>">
                                    <?php echo ucfirst($reserva['estado']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($reserva['estado'] === 'pendiente'): ?>
                                    <a href="<?php echo APP_URL; ?>/admin/reservas/confirmar/<?php echo $reserva['id']; ?>" 
                                       class="btn btn-small btn-success"
                                       onclick="return confirm('¿Confirmar esta reserva?')">
                                        Confirmar
                                    </a>
                                <?php endif; ?>
                                
                                <a href="<?php echo APP_URL; ?>/admin/reservas/editar/<?php echo $reserva['id']; ?>" class="btn btn-small">Editar</a>
                                <a href="<?php echo APP_URL; ?>/admin/reservas/eliminar/<?php echo $reserva['id']; ?>" 
                                   class="btn btn-small btn-danger"
                                   onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let draggedReserva = null;

// Eventos para las reservas (draggable)
document.querySelectorAll('.reserva-card').forEach(card => {
    card.addEventListener('dragstart', function(e) {
        draggedReserva = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    });
    
    card.addEventListener('dragend', function() {
        this.classList.remove('dragging');
        draggedReserva = null;
    });
});

// Eventos para las mesas (drop zones)
document.querySelectorAll('.mesa-item').forEach(mesa => {
    mesa.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        
        if (draggedReserva && this.classList.contains('disponible')) {
            this.classList.add('drag-over');
        }
    });
    
    mesa.addEventListener('dragleave', function() {
        this.classList.remove('drag-over');
    });
    
    mesa.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        
        if (!draggedReserva) return;
        
        const reservaId = draggedReserva.dataset.reservaId;
        const mesaId = this.dataset.mesaId;
        const numPersonas = parseInt(draggedReserva.dataset.numPersonas);
        const capacidad = parseInt(this.dataset.capacidad);
        
        if (!this.classList.contains('disponible')) {
            alert('Esta mesa no está disponible');
            return;
        }
        
        if (numPersonas > capacidad) {
            if (!confirm(`La mesa tiene capacidad para ${capacidad} personas pero la reserva es para ${numPersonas}. ¿Deseas asignarla de todas formas?`)) {
                return;
            }
        }
        
        // Asignar la mesa a la reserva
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
                alert('Mesa asignada exitosamente');
                location.reload();
            } else {
                alert('Error al asignar la mesa: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('[v0] Error:', error);
            alert('Error al asignar la mesa');
        });
    });
});
</script>

<?php include SRC_PATH . '/views/layout/footer.php'; ?>
