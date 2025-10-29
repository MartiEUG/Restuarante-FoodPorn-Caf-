# Sistema de Gestión de Restaurante - PHP MVC

Aplicación completa para la gestión de un restaurante desarrollada con **PHP** siguiendo el patrón **MVC (Modelo-Vista-Controlador)**.
Permite administrar reservas, mesas, platos y usuarios desde un panel de control intuitivo, además de ofrecer una experiencia sencilla para los clientes.

---

## 🚀 Características Principales

### 👥 Para Clientes

* Registro e inicio de sesión de usuarios.
* Búsqueda y navegación de platos por categorías.
* Sistema de reservas con selección de fecha, hora y número de personas.
* Preselección de platos al reservar.
* Visualización, modificación o cancelación de reservas propias.

### 🔧 Para Administradores

* Panel de administración completo.
* Gestión de mesas del restaurante:

  * Crear, editar o eliminar mesas.
  * Combinar mesas para grupos grandes.
  * Asignar reservas mediante **drag & drop**.
  * Edición directa del nombre de mesas.
* Gestión de reservas:

  * Visualización global de todas las reservas.
  * Cambiar estado (pendiente, confirmada, cancelada).
  * Asignar mesas a reservas.
* Gestión de platos:

  * Crear, editar y eliminar platos.
  * Marcar platos como “Menú del día”.
  * Gestión por categorías (Entrantes, Principales, Postres).
* Gestión de usuarios y roles (cliente, administrador).

---

## 🧩 Tecnologías Utilizadas

* **Backend:** PHP 8.1+ con patrón MVC
* **Base de Datos:** MySQL 8.0
* **Frontend:** HTML5, CSS3, JavaScript vanilla
* **Servidor Web:** Apache 2.4
* **Contenedores:** Docker y Docker Compose
* **Gestión de BD:** phpMyAdmin

---

## ⚙️ Requisitos Previos

* Docker Desktop
* Docker Compose
* Puertos disponibles:

  * **8080:** Aplicación principal
  * **8081:** phpMyAdmin
  * **3306:** MySQL

---

## 🧭 Instalación y Configuración

### 1. Clonar o descargar el proyecto

```bash
git clone https://github.com/MartiEUG/Restuarante-FoodPorn-Caf-.git
cd Restuarante-FoodPorn-Caf-
```

### 2. (Opcional) Configurar variables de entorno

Puedes definir tus propias credenciales en un archivo `.env`:

```env
DB_NAME=restaurant_db
DB_USER=restaurant_user
DB_PASSWORD=restaurant_pass
DB_ROOT_PASSWORD=root_pass
```

### 3. Construir e iniciar los contenedores

```bash
docker-compose up -d --build
```

Este comando:

* Construye la imagen PHP con Apache.
* Inicia MySQL y phpMyAdmin.
* Ejecuta automáticamente el script SQL con datos iniciales.

### 4. Verificar los servicios

```bash
docker-compose ps
```

Debes ver los siguientes contenedores activos:

* `restaurant_php` (8080)
* `restaurant_mysql` (3306)
* `restaurant_phpmyadmin` (8081)

### 5. Acceder a la aplicación

* 🌐 **Aplicación:** [http://localhost:8080](http://localhost:8080)
* 🧭 **phpMyAdmin:** [http://localhost:8081](http://localhost:8081)

> Si ves un listado de directorios, espera unos segundos y recarga: los servicios pueden estar inicializándose.

---

## 🏗️ Estructura del Proyecto

```
restaurantphpmvc/
├── docker-compose.yml
├── Dockerfile
├── db.sql
├── public/
│   └── index.php
├── src/
│   ├── config/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   ├── middleware/
│   ├── routes/
│   └── utils/
└── logs/
```

---

## 🧠 Uso del Sistema

### 🪑 Gestión de Mesas

1. Accede al panel de administración.
2. Crea o edita mesas según capacidad.
3. Combina mesas para grupos grandes.
4. Asigna reservas arrastrando desde la lista de reservas.
5. Edita nombres directamente sobre la vista del plano.

### 📅 Reservas de Cliente

1. Regístrate o inicia sesión.
2. Accede a “Hacer Reserva”.
3. Selecciona fecha, hora y número de personas.
4. Preselecciona platos (opcional).
5. Confirma la reserva y consúltala en tu panel.

---

## 🐳 Comandos Docker Útiles

### Ver logs

```bash
docker-compose logs -f
```

### Detener los contenedores

```bash
docker-compose down
```

### Reiniciar un servicio

```bash
docker-compose restart php-apache
```

### Acceder a los contenedores

```bash
docker exec -it restaurant_php bash
docker exec -it restaurant_mysql mysql -u root -p
```

---

## 🧰 Solución de Problemas

### Puerto en uso

Modifica los puertos en `docker-compose.yml` o libera los actuales.

### Base de datos no disponible

* Verifica el contenedor: `docker-compose ps`
* Revisa logs: `docker-compose logs mysql`
* Espera 30-60 segundos tras iniciar.

### Permisos

```bash
chmod -R 755 public/ src/
chmod -R 777 logs/
```

### Reinicio completo

```bash
docker-compose down -v
docker-compose up -d --build
```

---

## 🔒 Seguridad y Arquitectura

* Contraseñas encriptadas (bcrypt).
* Prevención de SQL Injection mediante consultas preparadas.
* Middleware de autenticación y autorización.
* Arquitectura MVC estricta con separación de responsabilidades.

---

## 🌱 Mejoras Futuras

* Notificaciones por correo.
* Integración con pasarelas de pago.
* Calendario visual de reservas.
* Sistema de valoraciones y reseñas.
* API REST para apps móviles.
* Reportes y estadísticas.

---

## 📜 Licencia

Proyecto disponible bajo la **licencia MIT**.

---

## 👨‍💻 Autor

Proyecto educativo de gestión de restaurantes desarrollado en PHP MVC con Docker y MySQL.
