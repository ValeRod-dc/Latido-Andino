<?php
session_start();

// Configuración de la base de datos
define('MONGO_HOST', getenv('MONGO_HOST') ?: 'localhost');
define('MONGO_PORT', getenv('MONGO_PORT') ?: '27017');
define('MONGO_DB', getenv('MONGO_DB') ?: 'coffee_shop');

// URL base
define('BASE_URL', '/');

// Autoload de clases
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../controllers/' . $class . '.php',
        __DIR__ . '/../models/' . $class . '.php',
        __DIR__ . '/../core/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Enrutador simple
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// Rutas públicas (no requieren autenticación)
$publicRoutes = ['', 'home', 'login', 'auth/login', 'register', 'auth/register'];

// Verificar sesión solo para rutas protegidas
if (!in_array($uri, $publicRoutes) && empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// Rutas
switch ($uri) {
    case '':
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;
    
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;
    
    case 'auth/login':
        $controller = new AuthController();
        $controller->processLogin();
        break;
    
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    
    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;
    
    case 'auth/register':
        $controller = new AuthController();
        $controller->processRegister();
        break;
    
    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}
