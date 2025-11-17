<?php
/**
 * Controlador de autenticación
 * Gestiona registro, login y logout
 */

class AuthController {
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Mostrar formulario de registro
     */
    public function mostrarRegistro() {
        AuthMiddleware::redirectIfAuthenticated();
        require_once SRC_PATH . '/views/auth/registro.php';
    }
    
    /**
     * Procesar registro de usuario
     */
    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/registro');
        }
        
        // Pedir Datos
        $nombre = sanitize($_POST['nombre'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $confirmarContrasena = $_POST['confirmar_contrasena'] ?? '';
        
        // Validaciones
        $errores = [];
        
        if (empty($nombre)) {
            $errores[] = 'El nombre es obligatorio';
        }
        
        if (empty($email) || !validarEmail($email)) {
            $errores[] = 'El email no es válido';
        }
        
        if (empty($contrasena) || strlen($contrasena) < 6) {
            $errores[] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        if ($contrasena !== $confirmarContrasena) {
            $errores[] = 'Las contraseñas no coinciden';
        }
        
        // Verificar si el email ya existe
        if ($this->usuarioModel->buscarPorEmail($email)) {
            $errores[] = 'Este email ya está registrado';
        }
        
        if (!empty($errores)) {
            $_SESSION['errores_registro'] = $errores;
            redirect('/registro');
        }
        
        // Crear usuario
        $usuarioId = $this->usuarioModel->crear($nombre, $email, $contrasena);
        
        if ($usuarioId) {
            setFlashMessage('success', 'Registro exitoso. Por favor, inicia sesión');
            redirect('/login');
        } else {
            setFlashMessage('error', 'Error al crear la cuenta. Inténtalo de nuevo');
            redirect('/registro');
        }
    }
    
    /**
     * Mostrar formulario de login
     */
    public function mostrarLogin() {
        AuthMiddleware::redirectIfAuthenticated();
        require_once SRC_PATH . '/views/auth/login.php';
    }
    
    /**
     * Procesar login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }
        
        $email = sanitize($_POST['email'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        
        if (empty($email) || empty($contrasena)) {
            setFlashMessage('error', 'Por favor, completa todos los campos');
            redirect('/login');
        }
        
        $usuario = $this->usuarioModel->verificarCredenciales($email, $contrasena);
        
        if ($usuario) {
            // Establecer sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            
            setFlashMessage('success', '¡Bienvenido, ' . $usuario['nombre'] . '!');
            
            // Redirigir según el rol
            if ($usuario['rol'] === 'administrador') {
                redirect('/admin');
            } else {
                redirect('/');
            }
        } else {
            setFlashMessage('error', 'Credenciales incorrectas');
            redirect('/login');
        }
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        session_destroy();
        redirect('/');
    }
}
