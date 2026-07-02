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

            $tipo          = $_POST['tipo']             ?? 'ingreso';
            $rut           = $_POST['rut']              ?? '';
            $nombre        = $_POST['nombre']           ?? '';
            $paso          = $_POST['paso_fronterizo']  ?? '';
            $tieneMascota  = isset($_POST['tiene_mascota']);
            $tieneVehiculo = isset($_POST['tiene_vehiculo']);
            $productos     = $_POST['productos']        ?? [];

            if (empty($rut) || empty($nombre)) {
                echo json_encode(['success' => false, 'message' => 'RUT y nombre son requeridos']);
                return;
            }

            if ($tieneVehiculo && empty($_POST['patente'])) {
                echo json_encode(['success' => false, 'message' => 'La patente del vehículo es requerida']);
                return;
            }

            $tramiteData = [
                'tipo'            => $tipo,
                'viajero_rut'     => $rut,
                'viajero_nombre'  => $nombre,
                'paso_fronterizo' => $paso
            ];

            if ($tipo === 'salida') {
                $tramiteData['destino']       = $_POST['destino']       ?? 'Argentina';
                $tramiteData['fecha_retorno'] = $_POST['fecha_retorno'] ?? null;
            }

            $tramiteId = $this->tramiteModel->crear($tramiteData);

            if (!$tramiteId) {
                echo json_encode(['success' => false, 'message' => 'Error al crear el trámite']);
                return;
            }

            $vehiculoId    = null;
            $vehiculoModel = null;
            if ($tieneVehiculo) {
                $vehiculoModel = new Vehiculo();
                $vehiculoId    = $vehiculoModel->crear([
                    'patente'    => $_POST['patente'],
                    'tramite_id' => (string)$tramiteId,
                    'viajero_rut'=> $rut,
                    'marca'      => $_POST['marca']  ?? null,
                    'modelo'     => $_POST['modelo'] ?? null,
                    'anio'       => $_POST['anio']   ?? null,
                    'color'      => $_POST['color']  ?? null
                ]);
            }

            $datosValidacion = [];
            if ($tieneMascota) {
                $datosValidacion['mascota']           = true;
                $datosValidacion['vacuna_antirrabica']= isset($_POST['vacuna_antirrabica']);
                $datosValidacion['microchip']         = isset($_POST['microchip']);
            }
            if (!empty($productos)) {
                $datosValidacion['productos'] = $productos;
            }
            if ($tieneVehiculo) {
                $datosValidacion['vehiculo'] = ['patente' => $_POST['patente']];
            }

            $validacion  = $this->validacionService->validacionCruzada($rut, $datosValidacion);
            $estado      = $validacion['aprobado'] ? 'aprobado' : 'rechazado';
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
                'success'             => true,
                'message'             => $estado === 'aprobado' ? 'Trámite aprobado' : 'Trámite requiere revisión',
                'estado'              => $estado,
                'tramite_id'          => (string)$tramiteId,
                'tiempo_validacion_ms'=> $validacion['tiempo_respuesta_ms']
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
            require_once __DIR__ . '/../views/tramite/buscar-estado.php';
            return;
        }

        $tramites = $this->tramiteModel->findByRut($rut);
        require_once __DIR__ . '/../views/tramite/estado.php';
    }

    public function tramitesPorRut() {
        header('Content-Type: application/json');
        $rut = $_GET['rut'] ?? '';

        if (empty($rut)) {
            echo json_encode(['success' => false, 'message' => 'RUT requerido']);
            return;
        }

        $tramites = $this->tramiteModel->findByRut($rut);
        echo json_encode([
            'success' => true,
            'tramites' => array_map(function($t) {
                return [
                    '_id' => (string)$t->_id,
                    'tipo' => $t->tipo ?? 'ingreso',
                    'estado' => $t->estado ?? 'pendiente'
                ];
            }, $tramites)
        ]);
    }

    /* Redirige al Pase Ágil del último trámite aprobado */
    public function misPaseAgil() {
        $rut = $_SESSION['user_rut'] ?? '';

        if (empty($rut)) {
            header('Location: /pre-registro');
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

        header('Location: ' . ($aprobado
            ? '/tramite/pase-agil/' . (string)$aprobado->_id
            : '/consulta-estado'
        ));
        exit;
    }
    
    /* Mostrar Pase Ágil QR */
    public function mostrarPaseAgil($tramiteId) {
        $tramite = $this->tramiteModel->findById($tramiteId);

        if (!$tramite || $tramite->estado !== 'aprobado') {
            header('Location: /consulta-estado');
            exit;
        }

        $host     = $_SERVER['HTTP_HOST'];
        $protocol = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false)
            ? 'http://'
            : ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://');

        $urlVerificacion = $protocol . $host . '/verificar?codigo=' . urlencode($tramite->pase_agil_qr);
        $qrData = $tramite->pase_agil_qr;
        $qrUrl  = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($urlVerificacion);

        require_once __DIR__ . '/../views/tramite/pase-agil.php';
    }

    /* Registrar flujo de ingreso/egreso (RF-06) */
    public function registrarFlujo() {
        header('Content-Type: application/json');

        if (!in_array($_SESSION['user_role'], ['aduanas', 'sag', 'pdi', 'admin'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $tramiteId = $_POST['tramite_id'] ?? '';
        $tipo      = $_POST['tipo']       ?? 'ingreso';

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
            if (isset($tramite->hora_ingreso)) {
                $ingreso  = $tramite->hora_ingreso->toDateTime();
                $egreso   = new DateTime();
                $actualizacion['tiempo_espera_segundos'] = $egreso->getTimestamp() - $ingreso->getTimestamp();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
            return;
        }

        $resultado = $this->tramiteModel->actualizarFlujo($tramiteId, $actualizacion);
        echo json_encode(['success' => $resultado]);
    }

    /* Cambiar estado de un trámite (aprobar/rechazar) */
    public function cambiarEstado() {
        header('Content-Type: application/json');

        if (!in_array($_SESSION['user_role'], ['admin', 'aduanas', 'sag', 'pdi'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $tramiteId   = $_POST['tramite_id'] ?? '';
        $nuevoEstado = $_POST['estado']     ?? '';

        if (!in_array($nuevoEstado, ['aprobado', 'rechazado'])) {
            echo json_encode(['success' => false, 'message' => 'Estado inválido']);
            return;
        }

        $tramite = $this->tramiteModel->findById($tramiteId);
        if (!$tramite) {
            echo json_encode(['success' => false, 'message' => 'Trámite no encontrado']);
            return;
        }

        if ($tramite->estado === $nuevoEstado) {
            echo json_encode(['success' => true, 'message' => 'Ya está en ese estado']);
            return;
        }

        $resultado = $this->tramiteModel->cambiarEstado($tramiteId, $nuevoEstado);
        echo json_encode(['success' => $resultado]);
    }
}