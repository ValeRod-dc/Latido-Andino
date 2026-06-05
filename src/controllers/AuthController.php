<?php

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        // Si ya está logueado, redirigir a home
        if (isset($_SESSION['user_id'])) {
            header('Location: /home');
            exit;
        }
        
        require_once __DIR__ . '/../views/login.php';
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
        
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
            return;
        }
        
        if (!$this->userModel->verifyPassword($password, $user->password)) {
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
            return;
        }
        
        // Crear sesión
        $_SESSION['user_id'] = (string)$user->_id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = $user->role;
        
        // Cookie para persistencia (7 días)
        $cookie_value = base64_encode(json_encode([
            'user_id' => (string)$user->_id,
            'user_role' => $user->role
        ]));
        
        setcookie('coffee_session', $cookie_value, time() + (86400 * 7), '/');
        
        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'redirect' => '/home'
        ]);
    }
    
    public function logout() {
        session_destroy();
        setcookie('coffee_session', '', time() - 3600, '/');
        header('Location: /home');
        exit;
    }
    
    public function register() {
        // Si ya está logueado, redirigir a home
        if (isset($_SESSION['user_id'])) {
            header('Location: /home');
            exit;
        }
        
        require_once __DIR__ . '/../views/register.php';
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
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validaciones
        if (empty($name) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
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
        
        if ($password !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
            return;
        }
        
        // Verificar si el email ya existe
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser) {
            echo json_encode(['success' => false, 'message' => 'Este email ya está registrado']);
            return;
        }
        
        // Crear usuario
        try {
            $userId = $this->userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => 'cliente' // Por defecto los nuevos usuarios son clientes
            ]);
            
            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'Error al crear el usuario']);
                return;
            }
            
            // Iniciar sesión automáticamente
            $_SESSION['user_id'] = (string)$userId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'cliente';
            
            // Cookie para persistencia (7 días)
            $cookie_value = base64_encode(json_encode([
                'user_id' => (string)$userId,
                'user_role' => 'cliente'
            ]));
            
            setcookie('coffee_session', $cookie_value, time() + (86400 * 7), '/');
            
            echo json_encode([
                'success' => true,
                'message' => '¡Registro exitoso! Bienvenido',
                'redirect' => '/home'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al registrar usuario: ' . $e->getMessage()]);
        }
    }
}
