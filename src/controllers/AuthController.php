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
        // La vista login ya no se usa directamente; ahora el login está en el modal de landing.php
        // Pero si alguien accede a /login, redirigimos a la home (landing)
        header('Location: /');
        exit;
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
        $_SESSION['user_rut'] = $user->rut ?? '';
        
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
            'viajero'       => '/portal/viajero',
            'transportista' => '/portal/viajero',
            'aduanas'       => '/portal/funcionario',
            'sag'           => '/portal/funcionario',
            'pdi'           => '/portal/funcionario',
            'admin'         => '/portal/admin'
        ];
        return $redirects[$role] ?? '/portal/viajero';
    }
    
    private function redirectByRole($role) {
        $redirect = $this->getRedirectByRole($role);
        header("Location: $redirect");
        exit;
    }
    
    public function logout() {
        session_destroy();
        setcookie('latido_session', '', time() - 3600, '/');
        header('Location: /');
        exit;
    }
    
    public function register() {
        // Ya no se usa vista de registro separada; todo desde modal
        header('Location: /');
        exit;
    }
    
    public function processRegister() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rut = trim($_POST['rut'] ?? '');
        $nacionalidad = trim($_POST['nacionalidad'] ?? 'Chilena');
        $role = $_POST['role'] ?? 'viajero'; // viajero, funcionario o admin
        
        if (empty($name) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Nombre, email y contraseña son requeridos']);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email inválido']);
            return;
        }
        
        if (strlen($password) < 6) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
            return;
        }
        
        // Verificar si el email ya existe
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser) {
            echo json_encode(['success' => false, 'message' => 'Este email ya está registrado']);
            return;
        }
        
        // Crear usuario
        $userId = $this->userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'rut' => $rut,
            'nacionalidad' => $nacionalidad
        ]);
        
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Error al crear el usuario']);
            return;
        }
        
        // Iniciar sesión automáticamente
        $_SESSION['user_id'] = (string)$userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $role;
        $_SESSION['user_rut'] = $rut;
        
        $cookie_value = base64_encode(json_encode([
            'user_id' => (string)$userId,
            'user_role' => $role
        ]));
        setcookie('latido_session', $cookie_value, time() + (86400 * 7), '/');
        
        $redirect = $this->getRedirectByRole($role);
        
        echo json_encode([
            'success' => true,
            'message' => 'Registro exitoso',
            'redirect' => $redirect
        ]);
    }
}