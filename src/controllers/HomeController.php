<?php

class HomeController {
    private $productModel;
    
    public function __construct() {
        $this->productModel = new Product();
    }
    
    public function index() {
        // Obtener productos para todos los visitantes
        $products = $this->productModel->getAll();
        
        // Variables para la vista (disponibles si el usuario está logueado)
        $userName = $_SESSION['user_name'] ?? null;
        $userRole = $_SESSION['user_role'] ?? null;
        
        require_once __DIR__ . '/../views/home.php';
    }
}
