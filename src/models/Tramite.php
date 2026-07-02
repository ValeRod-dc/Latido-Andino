<?php

class Tramite {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function crear($datos) {
        $tramite = [
            'tipo' => $datos['tipo'],
            'viajero_rut' => $datos['viajero_rut'],
            'viajero_nombre' => $datos['viajero_nombre'],
            'paso_fronterizo' => $datos['paso_fronterizo'],
            'estado' => 'pendiente',
            'documentos' => [],
            'validacion_cruzada' => [],
            'observaciones' => null,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];
        if (isset($datos['destino'])) {
            $tramite['destino'] = $datos['destino'];
        }
        return $this->db->insert('tramites', $tramite);
    }
    
    public function findById($id) {
        return $this->db->findOne('tramites', ['_id' => new MongoDB\BSON\ObjectId($id)]);
    }
    
    public function findByRut($rut) {
        return $this->db->find('tramites', ['viajero_rut' => $rut], ['sort' => ['created_at' => -1]]);
    }
    
    public function getPendientes($pasoFronterizo = null, $limit = 100) {
        $filter = ['estado' => 'pendiente'];
        if ($pasoFronterizo) {
            $filter['paso_fronterizo'] = $pasoFronterizo;
        }
        return $this->db->find('tramites', $filter, ['limit' => $limit, 'sort' => ['created_at' => 1]]);
    }
    
    public function actualizarValidacion($tramiteId, $resultados) {
        return $this->db->update('tramites', 
            ['_id' => new MongoDB\BSON\ObjectId($tramiteId)],
            ['validacion_cruzada' => $resultados]
        );
    }
    
    public function cambiarEstado($tramiteId, $estado, $observacion = null) {
        $update = ['estado' => $estado];
        if ($observacion) {
            $update['observaciones'] = $observacion;
        }
        if ($estado === 'aprobado') {
            $update['pase_agil_qr'] = $this->generarCodigoQR();
            $update['fecha_aprobacion'] = new MongoDB\BSON\UTCDateTime();
        }
        return $this->db->update('tramites', 
            ['_id' => new MongoDB\BSON\ObjectId($tramiteId)],
            $update
        );
    }
    
    private function generarCodigoQR() {
        return 'LAT-' . strtoupper(uniqid()) . '-' . date('Ymd');
    }
    
    public function contarPorEstado($estado, $soloHoy = false) {
        $filter = ['estado' => $estado];
        if ($soloHoy) {
            $hoyInicio = (new DateTime('today'))->getTimestamp() * 1000;
            $filter['created_at'] = ['$gte' => new MongoDB\BSON\UTCDateTime($hoyInicio)];
        }
        return $this->db->count('tramites', $filter);
    }

    public function getEstadisticas($fechaInicio, $fechaFin, $pasoFronterizo = null) {
        $match = [
            'created_at' => [
                '$gte' => new MongoDB\BSON\UTCDateTime($fechaInicio),
                '$lte' => new MongoDB\BSON\UTCDateTime($fechaFin)
            ]
        ];
        if ($pasoFronterizo) {
            $match['paso_fronterizo'] = $pasoFronterizo;
        }
        $pipeline = [
            ['$match' => $match],
            ['$group' => [
                '_id' => '$paso_fronterizo',
                'total_tramites' => ['$sum' => 1],
                'aprobados' => ['$sum' => ['$cond' => [['$eq' => ['$estado', 'aprobado']], 1, 0]]],
                'rechazados' => ['$sum' => ['$cond' => [['$eq' => ['$estado', 'rechazado']], 1, 0]]],
                'pendientes' => ['$sum' => ['$cond' => [['$eq' => ['$estado', 'pendiente']], 1, 0]]]
            ]]
        ];
        return $this->db->aggregate('tramites', $pipeline);
    }

    public function registrarIncidencia($tramiteId, $tipo, $descripcion, $funcionarioId, $viajeroRut = null) {
        $codigo = $this->siguienteCodigoIncidencia();

        $incidencia = [
            'codigo' => $codigo,
            'tramite_id' => $tramiteId,               // puede ser null
            'viajero_rut' => $viajeroRut,             // NUEVO: guardamos el RUT del viajero
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'funcionario_id' => $funcionarioId,
            'estado' => 'abierta',
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];

        $id = $this->db->insert('incidencias', $incidencia);
        return $id ? $codigo : false;
    }

    private function siguienteCodigoIncidencia() {
        // Contar todas las incidencias
        $total = $this->db->count('incidencias');
        // Si no hay incidencias, empezar desde 1
        $siguiente = $total + 1;
        return '#INC-' . str_pad((string)$siguiente, 3, '0', STR_PAD_LEFT);
    }

    public function actualizarFlujo($tramiteId, $datos) {
        return $this->db->update('tramites', ['_id' => new MongoDB\BSON\ObjectId($tramiteId)], $datos);
    }

    public function findByQR($codigo) {
        return $this->db->findOne('tramites', ['pase_agil_qr' => $codigo]);
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

    public function listarIncidencias($limit = 50) {
        return $this->db->find('incidencias', [], [
            'sort' => ['created_at' => -1],
            'limit' => $limit
        ]);
    }

    private function generarCodigoIncidencia() {
        return '#INC-' . strtoupper(uniqid());
    }
}