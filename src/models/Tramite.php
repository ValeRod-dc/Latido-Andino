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
}