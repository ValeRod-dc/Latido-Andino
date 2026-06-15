<?php

class TramiteController {
    private $tramiteModel;
    private $validacionService;
    
    public function __construct() {
        $this->tramiteModel = new Tramite();
        $this->validacionService = new ValidacionService();
    }
    
    /* Muestra formulario de pre-registro para viajeros */
    public function preRegistro() {
        // Obtener pasos fronterizos disponibles
        $db = Database::getInstance();
        $pasos = $db->find('pasos_fronterizos', ['activo' => true]);
        
        require_once __DIR__ . '/../views/tramite/pre-registro.php';
    }
    
    /* Procesa el pre-registro */
    public function procesarPreRegistro() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $tipo = $_POST['tipo'] ?? 'ingreso';
        $rut = $_POST['rut'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $paso = $_POST['paso_fronterizo'] ?? '';
        $tieneMascota = isset($_POST['tiene_mascota']);
        $productos = $_POST['productos'] ?? [];
        
        // Validaciones básicas
        if (empty($rut) || empty($nombre)) {
            echo json_encode(['success' => false, 'message' => 'RUT y nombre son requeridos']);
            return;
        }
        
        // Crear trámite
        $tramiteData = [
            'tipo' => $tipo,
            'viajero_rut' => $rut,
            'viajero_nombre' => $nombre,
            'paso_fronterizo' => $paso
        ];
        
        if ($tipo === 'salida') {
            $tramiteData['destino'] = $_POST['destino'] ?? 'Argentina';
            $tramiteData['fecha_retorno'] = $_POST['fecha_retorno'] ?? null;
        }
        
        $tramiteId = $this->tramiteModel->crear($tramiteData);
        
        if (!$tramiteId) {
            echo json_encode(['success' => false, 'message' => 'Error al crear el trámite']);
            return;
        }
        
        // Realizar validación cruzada (simulada)
        $datosValidacion = [];
        if ($tieneMascota) {
            $datosValidacion['mascota'] = true;
            $datosValidacion['vacuna_antirrabica'] = isset($_POST['vacuna_antirrabica']);
            $datosValidacion['microchip'] = isset($_POST['microchip']);
        }
        
        if (!empty($productos)) {
            $datosValidacion['productos'] = $productos;
        }
        
        $validacion = $this->validacionService->validacionCruzada($rut, $datosValidacion);
        
        // Actualizar estado según validación
        $estado = $validacion['aprobado'] ? 'aprobado' : 'rechazado';
        $observacion = implode('; ', $validacion['observaciones']);
        
        $this->tramiteModel->cambiarEstado($tramiteId, $estado, $observacion);
        $this->tramiteModel->actualizarValidacion($tramiteId, $validacion['resultados']);
        
        echo json_encode([
            'success' => true,
            'message' => $estado === 'aprobado' ? 'Trámite aprobado' : 'Trámite requiere revisión',
            'estado' => $estado,
            'tramite_id' => (string)$tramiteId,
            'tiempo_validacion_ms' => $validacion['tiempo_respuesta_ms']
        ]);
    }
    
    /* Consultar estado de un trámite */
    public function consultarEstado() {
        $rut = $_GET['rut'] ?? '';
        
        if (empty($rut)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'RUT requerido']);
            return;
        }
        
        $tramites = $this->tramiteModel->findByRut($rut);
        
        require_once __DIR__ . '/../views/tramite/estado.php';
    }
    
    /* Mostrar Pase Ágil QR */
    public function mostrarPaseAgil($tramiteId) {
        $tramite = $this->tramiteModel->findById($tramiteId);
        
        if (!$tramite || $tramite->estado !== 'aprobado') {
            header('Location: /tramite/estado');
            exit;
        }
        
        // Generar QR (simulado con URL de API externa)
        $qrData = $tramite->pase_agil_qr;
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);
        
        require_once __DIR__ . '/../views/tramite/pase-agil.php';
    }
}