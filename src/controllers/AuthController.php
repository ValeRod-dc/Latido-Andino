<?php

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectByRole($_SESSION['user_role']);
            exit;
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    public function processLogin() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Email y contraseña son requeridos']);
            return;
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if (!$user || !$this->userModel->verifyPassword($password, $user->password)) {
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
            return;
        }
        
        // Crear sesión
        $_SESSION['user_id'] = (string)$user->_id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = $user->role;
        
        // Cookie persistente
        $cookie_value = base64_encode(json_encode([
            'user_id' => (string)$user->_id,
            'user_role' => $user->role
        ]));
        setcookie('latido_session', $cookie_value, time() + (86400 * 7), '/');
        
        // Determinar redirección según rol
        $redirect = $this->getRedirectByRole($user->role);
        
        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'rol' => $user->role,
            'redirect' => $redirect
        ]);
    }
    
    private function getRedirectByRole($role) {
        $redirects = [
            'viajero' => '/viajero/dashboard',
            'aduanas' => '/aduanas/dashboard',
            'sag' => '/sag/dashboard',
            'pdi' => '/pdi/dashboard',
            'admin' => '/admin/dashboard'
        ];
        return $redirects[$role] ?? '/home';
    }
    
    private function redirectByRole($role) {
        $redirect = $this->getRedirectByRole($role);
        header("Location: $redirect");
        exit;
    }
    
    public function logout() {
        session_destroy();
        setcookie('latido_session', '', time() - 3600, '/');
        header('Location: /login');
        exit;
    }
}