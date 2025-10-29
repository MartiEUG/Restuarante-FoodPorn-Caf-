-- Base de datos para el sistema de restaurante
CREATE DATABASE IF NOT EXISTS restaurant_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurant_db;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    contrasena_hash VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'administrador') DEFAULT 'usuario',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_rol (rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nueva tabla de mesas con límite de 20 mesas y capacidad máxima de 5 personas
-- Added es_agrupada field to support merged tables
CREATE TABLE IF NOT EXISTS mesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(50) NOT NULL UNIQUE,
    capacidad INT NOT NULL,
    estado ENUM('disponible', 'ocupada', 'reservada') DEFAULT 'disponible',
    posicion_x INT DEFAULT 0,
    posicion_y INT DEFAULT 0,
    activa BOOLEAN DEFAULT TRUE,
    es_agrupada BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_estado (estado),
    INDEX idx_activa (activa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de platos
CREATE TABLE IF NOT EXISTS platos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    categoria VARCHAR(50),
    imagen VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE,
    es_menu_dia BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_categoria (categoria),
    INDEX idx_activo (activo),
    INDEX idx_menu_dia (es_menu_dia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de reservas actualizada con relación a mesas
CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    num_personas INT NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    comentarios TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_fecha (fecha),
    INDEX idx_usuario (id_usuario),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla intermedia para mesas asignadas a reservas (permite juntar mesas)
CREATE TABLE IF NOT EXISTS reserva_mesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_reserva INT NOT NULL,
    id_mesa INT NOT NULL,
    FOREIGN KEY (id_reserva) REFERENCES reservas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_mesa) REFERENCES mesas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reserva_mesa (id_reserva, id_mesa),
    INDEX idx_reserva (id_reserva),
    INDEX idx_mesa (id_mesa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para platos seleccionados en cada reserva
CREATE TABLE IF NOT EXISTS reserva_platos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_reserva INT NOT NULL,
    id_plato INT NOT NULL,
    cantidad INT DEFAULT 1,
    FOREIGN KEY (id_reserva) REFERENCES reservas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_plato) REFERENCES platos(id) ON DELETE CASCADE,
    INDEX idx_reserva (id_reserva),
    INDEX idx_plato (id_plato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador por defecto
-- Contraseña: admin123
INSERT INTO usuarios (nombre, email, contrasena_hash, rol) VALUES 
('Administrador', 'admin@restaurant.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrador');

-- Insertar usuario de prueba
-- Contraseña: user123
INSERT INTO usuarios (nombre, email, contrasena_hash, rol) VALUES 
('Usuario Prueba', 'usuario@restaurant.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario');

-- Insertar 20 mesas iniciales con diferentes capacidades
INSERT INTO mesas (numero, capacidad, posicion_x, posicion_y) VALUES 
(1, 2, 50, 50), (2, 2, 150, 50), (3, 4, 250, 50), (4, 4, 350, 50),
(5, 2, 50, 150), (6, 4, 150, 150), (7, 4, 250, 150), (8, 5, 350, 150),
(9, 2, 50, 250), (10, 4, 150, 250), (11, 4, 250, 250), (12, 5, 350, 250),
(13, 2, 50, 350), (14, 2, 150, 350), (15, 4, 250, 350), (16, 4, 350, 350),
(17, 3, 50, 450), (18, 3, 150, 450), (19, 5, 250, 450), (20, 5, 350, 450);

-- Insertar platos con algunos marcados como menú del día
INSERT INTO platos (nombre, descripcion, precio, categoria, activo, es_menu_dia) VALUES 
('Paella Valenciana', 'Arroz con mariscos, pollo y verduras frescas', 18.50, 'Principales', TRUE, TRUE),
('Gazpacho Andaluz', 'Sopa fría de tomate, pepino y pimiento', 6.50, 'Entrantes', TRUE, TRUE),
('Pulpo a la Gallega', 'Pulpo cocido con pimentón y aceite de oliva', 22.00, 'Principales', TRUE, FALSE),
('Crema Catalana', 'Postre tradicional con crema y azúcar caramelizado', 5.50, 'Postres', TRUE, TRUE),
('Jamón Ibérico', 'Jamón de bellota cortado a mano', 24.00, 'Entrantes', TRUE, FALSE),
('Tortilla Española', 'Tortilla de patatas casera', 8.00, 'Entrantes', TRUE, TRUE),
('Chuletón de Buey', 'Carne de buey a la parrilla (500g)', 35.00, 'Principales', TRUE, FALSE),
('Tarta de Santiago', 'Tarta de almendra tradicional gallega', 6.00, 'Postres', TRUE, TRUE),
('Ensalada Mixta', 'Lechuga, tomate, cebolla y aceitunas', 7.50, 'Entrantes', TRUE, TRUE),
('Solomillo al Whisky', 'Solomillo con salsa de whisky', 28.00, 'Principales', TRUE, FALSE);
