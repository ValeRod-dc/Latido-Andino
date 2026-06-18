<?php

class HomeController {
    
    /**
     * Página principal - Landing Page pública con carrusel y modal
     */
    public function index() {
        // La landing ya contiene su propio CSS y JS, no necesita datos dinámicos
        require_once __DIR__ . '/../views/landing.php';
    }
    
    // Los métodos terminos, privacidad, contacto, etc. se pueden mantener o eliminar
    // Si no los usas, puedes borrarlos. Pero los dejamos por si acaso.
    
    public function terminos() {
        require_once __DIR__ . '/../views/public/terminos.php';
    }
    
    public function privacidad() {
        require_once __DIR__ . '/../views/public/privacidad.php';
    }
    
    public function contacto() {
        require_once __DIR__ . '/../views/public/contacto.php';
    }
    
    public function procesarContacto() {
        header('Content-Type: application/json');
        // Implementación básica
        echo json_encode(['success' => true, 'message' => 'Mensaje enviado']);
    }
}