<?php
/**
 * Middleware para control de acceso y autenticación
 */

class AuthMiddleware {
    /**
     * Verificar que el usuario esté autenticado
     */
    public static function requireAuth() {
        if (!isAuthenticated()) {
            setFlashMessage('error', 'Debes iniciar sesión para acceder a esta página');
            redirect('/login');
        }
    }
    
    /**
     * Verificar que el usuario sea administrador
     */
    public static function requireAdmin() {
        self::requireAuth();
        
        if (!isAdmin()) {
            setFlashMessage('error', 'No tienes permisos para acceder a esta página');
            redirect('/');
        }
    }
    
    /**
     * Verificar que el usuario sea usuario normal (no admin)
     */
    public static function requireUser() {
        self::requireAuth();
        
        if (isAdmin()) {
            setFlashMessage('error', 'Los administradores no pueden acceder a esta sección');
            redirect('/admin');
        }
    }
    
    /**
     * Redirigir si ya está autenticado
     */
    public static function redirectIfAuthenticated() {
        if (isAuthenticated()) {
            if (isAdmin()) {
                redirect('/admin');
            } else {
                redirect('/');
            }
        }
    }
}
