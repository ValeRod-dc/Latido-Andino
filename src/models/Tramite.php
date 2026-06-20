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

    public function registrarIncidencia($tramiteId, $tipo, $descripcion, $funcionarioId) {
        $incidencia = [
            'tramite_id' => $tramiteId,
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'funcionario_id' => $funcionarioId,
            'estado' => 'abierta',
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];
        return $this->db->insert('incidencias', $incidencia);
    }

    public function actualizarFlujo($tramiteId, $datos) {
        return $this->db->update('tramites', ['_id' => new MongoDB\BSON\ObjectId($tramiteId)], $datos);
    }
}