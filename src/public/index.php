<?php
session_start();

// Mostrar errores como JSON en vez de HTML crudo (rompía el fetch del front)
ini_set('display_errors', '0');
error_reporting(E_ALL);
set_exception_handler(function ($e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
    exit;
});
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

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
    'terminos', 'privacidad', 'contacto', 'contacto/enviar', 'accesibilidad',
    'pre-registro', 'api/tramite/procesar', 'consulta-estado', 'ayuda'
];

// Verificar autenticación para rutas protegidas
if (!in_array($uri, $publicRoutes) && strpos($uri, 'tramite/pase-agil') !== 0 && empty($_SESSION['user_id'])) {    
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
    case 'accesibilidad':
        $controller = new HomeController();
        $controller->accesibilidad();
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
    
    case 'verificar':
        $controller = new VerificarController();
        $controller->index();
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
        $controller = new TramiteController();
        $controller->preRegistro();
        break;
    case 'api/tramite/procesar':
        // El formulario de pre-registro.php hace fetch() a esta ruta
        $controller = new TramiteController();
        $controller->procesarPreRegistro();
        break;
    case 'consulta-estado':
        $controller = new TramiteController();
        $controller->consultarEstado();
        break;
    
    case 'ayuda':
        $controller = new HomeController();
        $controller->ayuda();
        break;
    case 'mi-pase-agil':
        $controller = new TramiteController();
        $controller->misPaseAgil();
        break;

    // Reportes
    case 'reporte':
        $controller = new ReporteController();
        $controller->index();
        break;
    case 'reporte/generar':
        $controller = new ReporteController();
        $controller->generar();
        break;

    // Incidencias
    case 'incidencia/registrar':
        $controller = new IncidenciaController();
        $controller->registrar();
        break;

    // Registro de flujo (se puede agregar a TramiteController)
    case 'tramite/registrar-flujo':
        $controller = new TramiteController();
        $controller->registrarFlujo();
        break;

    case 'api/tramite/cambiar-estado':
        $controller = new TramiteController();
        $controller->cambiarEstado();
        break;
    
    default:
        // Soporta /tramite/pase-agil/{id}, formato que usa el JS de pre-registro.php
        if (strpos($uri, 'tramite/pase-agil') === 0) {
            $partes = explode('/', $uri);
            $id = $partes[2] ?? ($_GET['id'] ?? null);
            if ($id) {
                $controller = new TramiteController();
                $controller->mostrarPaseAgil($id);
                break;
            }
        }
        http_response_code(404);
        echo "Página no encontrada";
        break;
}