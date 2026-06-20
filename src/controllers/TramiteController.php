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

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                return;
            }

            $tipo = $_POST['tipo'] ?? 'ingreso';
            $rut = $_POST['rut'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $paso = $_POST['paso_fronterizo'] ?? '';
            $tieneMascota = isset($_POST['tiene_mascota']);
            $tieneVehiculo = isset($_POST['tiene_vehiculo']);
            $productos = $_POST['productos'] ?? [];

            if (empty($rut) || empty($nombre)) {
                echo json_encode(['success' => false, 'message' => 'RUT y nombre son requeridos']);
                return;
            }

            if ($tieneVehiculo && empty($_POST['patente'])) {
                echo json_encode(['success' => false, 'message' => 'La patente del vehículo es requerida']);
                return;
            }

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

            $vehiculoId = null;
            $vehiculoModel = null;
            if ($tieneVehiculo) {
                $vehiculoModel = new Vehiculo();
                $vehiculoId = $vehiculoModel->crear([
                    'patente' => $_POST['patente'],
                    'tramite_id' => (string)$tramiteId,
                    'viajero_rut' => $rut,
                    'marca' => $_POST['marca'] ?? null,
                    'modelo' => $_POST['modelo'] ?? null,
                    'anio' => $_POST['anio'] ?? null,
                    'color' => $_POST['color'] ?? null
                ]);
            }

            $datosValidacion = [];
            if ($tieneMascota) {
                $datosValidacion['mascota'] = true;
                $datosValidacion['vacuna_antirrabica'] = isset($_POST['vacuna_antirrabica']);
                $datosValidacion['microchip'] = isset($_POST['microchip']);
            }

            if (!empty($productos)) {
                $datosValidacion['productos'] = $productos;
            }

            if ($tieneVehiculo) {
                $datosValidacion['vehiculo'] = ['patente' => $_POST['patente']];
            }

            $validacion = $this->validacionService->validacionCruzada($rut, $datosValidacion);

            $estado = $validacion['aprobado'] ? 'aprobado' : 'rechazado';
            $observacion = implode('; ', $validacion['observaciones']);

            $this->tramiteModel->cambiarEstado($tramiteId, $estado, $observacion);
            $this->tramiteModel->actualizarValidacion($tramiteId, $validacion['resultados']);

            if ($vehiculoId && $vehiculoModel) {
                $vehiculoModel->cambiarEstado(
                    $vehiculoId,
                    $estado,
                    $validacion['resultados']['rnv']['observacion'] ?? null
                );
            }

            echo json_encode([
                'success' => true,
                'message' => $estado === 'aprobado' ? 'Trámite aprobado' : 'Trámite requiere revisión',
                'estado' => $estado,
                'tramite_id' => (string)$tramiteId,
                'tiempo_validacion_ms' => $validacion['tiempo_respuesta_ms']
            ]);

        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error interno al procesar el trámite: ' . $e->getMessage()
            ]);
        }
    }
    
    /* Consultar estado de un trámite */
    public function consultarEstado() {
        $rut = $_GET['rut'] ?? ($_SESSION['user_rut'] ?? '');

        if (empty($rut)) {
            // Sin RUT disponible: mostrar formulario de búsqueda
            require_once __DIR__ . '/../views/tramite/buscar-estado.php';
            return;
        }

        $tramites = $this->tramiteModel->findByRut($rut);

        require_once __DIR__ . '/../views/tramite/estado.php';
    }

    /* Redirige al Pase Ágil del último trámite aprobado del viajero logueado */
    public function misPaseAgil() {
        $rut = $_SESSION['user_rut'] ?? '';

        if (empty($rut)) {
            header('Location: /login');
            exit;
        }

        $tramites = $this->tramiteModel->findByRut($rut);
        $aprobado = null;
        foreach ($tramites as $t) {
            if (($t->estado ?? '') === 'aprobado') {
                $aprobado = $t;
                break;
            }
        }

        if ($aprobado) {
            header('Location: /tramite/pase-agil/' . (string)$aprobado->_id);
        } else {
            header('Location: /consulta-estado');
        }
        exit;
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

    public function registrarFlujo() {
    header('Content-Type: application/json');

    if (!in_array($_SESSION['user_role'], ['aduanas', 'sag', 'pdi', 'admin'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }

    $tramiteId = $_POST['tramite_id'] ?? '';
    $tipo = $_POST['tipo'] ?? 'ingreso'; // ingreso o egreso

    if (!$tramiteId) {
        echo json_encode(['success' => false, 'message' => 'ID de trámite requerido']);
        return;
    }

    $tramite = $this->tramiteModel->findById($tramiteId);
    if (!$tramite) {
        echo json_encode(['success' => false, 'message' => 'Trámite no encontrado']);
        return;
    }

    $actualizacion = [];
    if ($tipo === 'ingreso') {
        $actualizacion['hora_ingreso'] = new MongoDB\BSON\UTCDateTime();
    } elseif ($tipo === 'egreso') {
        $actualizacion['hora_egreso'] = new MongoDB\BSON\UTCDateTime();
        // Calcular tiempo de espera si existe ingreso
        if (isset($tramite->hora_ingreso)) {
            $ingreso = $tramite->hora_ingreso->toDateTime();
            $egreso = new DateTime();
            $tiempo = $egreso->getTimestamp() - $ingreso->getTimestamp();
            $actualizacion['tiempo_espera_segundos'] = $tiempo;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
        return;
    }

    $resultado = $this->tramiteModel->actualizarFlujo($tramiteId, $actualizacion);
    echo json_encode(['success' => $resultado]);
}
}