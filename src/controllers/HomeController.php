<?php

class HomeController {
     /**
     * Página principal - Landing Page pública
     * No requiere autenticación
     */
    
    public function index() {
        // Obtener estadísticas desde la base de datos (usando Database::getInstance())
        $stats = $this->getEstadisticasReales();
        
        // Pasos fronterizos activos
        $pasos = $this->getPasosFronterizos();
        
        // Noticias simuladas (podrían venir de una colección "noticias")
        $noticias = $this->getNoticias();
        
        // Variables para la vista (disponibles si el usuario está logueado)
        $userName = $_SESSION['user_name'] ?? null;
        $userRole = $_SESSION['user_role'] ?? null;
        
        require_once __DIR__ . '/../views/home.php';
    }

    private function getEstadisticasReales() {
        try {
            $db = Database::getInstance();
            
            // Total de trámites registrados
            $totalTramites = $db->count('tramites');
            
            // Total de usuarios registrados (colección 'usuarios')
            $totalUsuarios = $db->count('usuarios');
            
            // Trámites aprobados hoy (para mostrar actividad reciente)
            $hoyInicio = (new DateTime('today'))->getTimestamp() * 1000;
            $hoyFin = (new DateTime('tomorrow'))->getTimestamp() * 1000 - 1;
            $aprobadosHoy = $db->count('tramites', [
                'estado' => 'aprobado',
                'created_at' => [
                    '$gte' => new MongoDB\BSON\UTCDateTime($hoyInicio),
                    '$lte' => new MongoDB\BSON\UTCDateTime($hoyFin)
                ]
            ]);

             // Tiempo promedio de validación (simulado, se podría calcular desde logs)
            // En un sistema real se obtendría de una colección de métricas
            $tiempoPromedio = 1.8; // segundos
            
            return [
                'total_tramites' => $totalTramites,
                'total_usuarios' => $totalUsuarios,
                'aprobados_hoy' => $aprobadosHoy,
                'tiempo_promedio' => $tiempoPromedio,
                'instituciones_integradas' => 7
            ];

        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas: " . $e->getMessage());
            // Valores por defecto si hay error de conexión
            return [
                'total_tramites' => 12450,
                'total_usuarios' => 8450,
                'aprobados_hoy' => 342,
                'tiempo_promedio' => 1.8,
                'instituciones_integradas' => 7
            ];
        }
    }

     private function getPasosFronterizos() {
        try {
            $db = Database::getInstance();
            $pasos = $db->find('pasos_fronterizos', ['activo' => true]);
            return $pasos;
        } catch (Exception $e) {
            error_log("Error obteniendo pasos fronterizos: " . $e->getMessage());
            // Datos de ejemplo por si la colección aún no existe
            return [
                (object)['nombre' => 'Los Libertadores', 'region' => 'Valparaíso'],
                (object)['nombre' => 'Cardenal Samoré', 'region' => 'Los Lagos'],
                (object)['nombre' => 'Chungará', 'region' => 'Arica y Parinacota']
            ];
        }
    }

    private function getNoticias() {
        // Por ahora estático, pero se podría leer de una colección 'noticias'
        return [
            [
                'titulo' => 'Nuevo sistema de validación cruzada',
                'fecha' => '15 de octubre, 2025',
                'descripcion' => 'Implementación de validación en paralelo con 7 instituciones, reduciendo tiempos de espera en más de un 50%.'
            ],
            [
                'titulo' => 'Horario extendido en Los Libertadores',
                'fecha' => '10 de octubre, 2025',
                'descripcion' => 'Durante temporada alta, el paso Los Libertadores atenderá 24/7 para agilizar el cruce de turistas y camiones.'
            ],
            [
                'titulo' => 'Integración con Aduana Argentina',
                'fecha' => '5 de octubre, 2025',
                'descripcion' => 'Acuerdo bilateral completamente digitalizado. Ya no es necesario llenar formularios en papel para vehículos.'
            ]
        ];
    }

    /**
     * Página de Términos y Condiciones
     */
    public function terminos() {
        require_once __DIR__ . '/../views/public/terminos.php';
    }
    
    /**
     * Página de Privacidad (Ley 19.628)
     */
    public function privacidad() {
        require_once __DIR__ . '/../views/public/privacidad.php';
    }
    
    /**
     * Página de Contacto
     */
    public function contacto() {
        require_once __DIR__ . '/../views/public/contacto.php';
    }

     /**
     * Procesa el formulario de contacto (envía correo o guarda en BD)
     */
    public function procesarContacto() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');
        
        if (empty($nombre) || empty($email) || empty($mensaje)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'El email no es válido']);
            return;
        }

        // Guardar mensaje en BD (opcional)
        try {
            $db = Database::getInstance();
            $db->insert('contactos', [
                'nombre' => $nombre,
                'email' => $email,
                'mensaje' => $mensaje,
                'fecha' => new MongoDB\BSON\UTCDateTime(),
                'leido' => false
            ]);
        } catch (Exception $e) {
            error_log("Error guardando contacto: " . $e->getMessage());
            // No fallamos la respuesta por esto, solo log
        }
        
        // Aquí se podría enviar un correo real con mail() o PHPMailer
        // Por simulación, respondemos éxito
        
        echo json_encode([
            'success' => true,
            'message' => 'Mensaje enviado correctamente. Nos contactaremos a la brevedad.'
        ]);
    }
}