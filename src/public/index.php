<?php
session_start();

// Configuración
define('MONGO_HOST', getenv('MONGO_HOST') ?: 'localhost'); //mongodb??
define('MONGO_PORT', getenv('MONGO_PORT') ?: '27017');
define('MONGO_DB', getenv('MONGO_DB') ?: 'latido_andino');

// Autoload
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../controllers/' . $class . '.php',
        __DIR__ . '/../models/' . $class . '.php',
        __DIR__ . '/../core/' . $class . '.php',
        __DIR__ . '/../services/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Enrutador
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// Rutas públicas
$publicRoutes = ['', 'home', 'login', 'auth/login', 'register', 'auth/register', 
                 'tramite/pre-registro', 'tramite/procesar', 'tramite/estado'];

// Verificar autenticación para rutas protegidas
if (!in_array($uri, $publicRoutes) && empty($_SESSION['user_id'])) {
    // Para APIs, devolver 401
    if (strpos($uri, 'api/') === 0) {
        http_response_code(401);
        echo json_encode(['error' => 'No autenticado']);
        exit;
    }
    header('Location: /login');
    exit;
}

// Routing
switch ($uri) {
    // Página principal
    case '':
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'terminos':
    $controller = new HomeController();
    $controller->terminos();
    break;

    case 'privacidad':
        $controller = new HomeController();
        $controller->privacidad();
        break;

    case 'contacto':
        $controller = new HomeController();
        $controller->contacto();
        break;

    case 'contacto/enviar':
        $controller = new HomeController();
        $controller->procesarContacto();
        break;
    
    // Autenticación
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
    
    // Trámites (viajeros)
    case 'tramite/pre-registro':
        $controller = new TramiteController();
        $controller->preRegistro();
        break;
    
    case 'tramite/procesar':
        $controller = new TramiteController();
        $controller->procesarPreRegistro();
        break;
    
    case 'tramite/estado':
        $controller = new TramiteController();
        $controller->consultarEstado();
        break;
    
    // Si es pase-agil/{id}
    default:
        if (preg_match('/^tramite\/pase-agil\/(.+)$/', $uri, $matches)) {
            $controller = new TramiteController();
            $controller->mostrarPaseAgil($matches[1]);
            break;
        }
        
        // Dashboard funcionarios (requiere rol específico)
        if (strpos($uri, 'funcionario/') === 0 && isset($_SESSION['user_role'])) {
            $controller = new DashboardController();
            $controller->index();
            break;
        }
        
        http_response_code(404);
        echo "Página no encontrada";
        break;
}