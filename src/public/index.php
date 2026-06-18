<?php
session_start();

// Configuración
define('MONGO_HOST', getenv('MONGO_HOST') ?: 'mongodb');
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

// Rutas públicas (no requieren autenticación)
$publicRoutes = [
    '', 'home', 
    'login', 'auth/login', 
    'register', 'auth/register',
    'terminos', 'privacidad', 'contacto', 'contacto/enviar'
];

// Verificar autenticación para rutas protegidas
if (!in_array($uri, $publicRoutes) && empty($_SESSION['user_id'])) {
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
    // Página principal (landing)
    case '':
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    // Informativas
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
    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;
    case 'auth/register':
        $controller = new AuthController();
        $controller->processRegister();
        break;
    
    // Portal por rol
    case 'portal/viajero':
        $controller = new PortalController();
        $controller->viajero();
        break;
    case 'portal/funcionario':
        $controller = new PortalController();
        $controller->funcionario();
        break;
    case 'portal/admin':
        $controller = new PortalController();
        $controller->admin();
        break;
    
    // Trámites (públicos o protegidos según corresponda)
    case 'pre-registro':
        // Si quieres mantener la página de pre-registro
        $controller = new TramiteController();
        $controller->preRegistro();
        break;
    case 'consulta-estado':
        $controller = new TramiteController();
        $controller->consultarEstado();
        break;
    case 'tramite/pase-agil':
        // Manejo de parámetro
        if (isset($_GET['id'])) {
            $controller = new TramiteController();
            $controller->mostrarPaseAgil($_GET['id']);
        } else {
            http_response_code(404);
            echo "Página no encontrada";
        }
        break;
    
    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}