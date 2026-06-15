<?php

class Tramite {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /* Crear un nuevo trámite de ingreso/salida */
    public function crear($datos) {
        $tramite = [
            'tipo' => $datos['tipo'], // 'ingreso' o 'salida'
            'viajero_rut' => $datos['viajero_rut'],
            'viajero_nombre' => $datos['viajero_nombre'],
            'paso_fronterizo' => $datos['paso_fronterizo'],
            'fecha_tramite' => new MongoDB\BSON\UTCDateTime(),
            'estado' => 'pendiente', // pendiente, en_validacion, aprobado, rechazado
            'documentos' => [],
            'validacion_cruzada' => [],
            'observaciones' => null,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];
        
        // Agregar datos específicos según tipo
        if ($datos['tipo'] === 'salida') {
            $tramite['destino'] = $datos['destino'] ?? 'Argentina';
            $tramite['fecha_retorno_estimada'] = $datos['fecha_retorno'] ?? null;
        }
        
        return $this->db->insert('tramites', $tramite);
    }
    
    /* Obtener trámite por ID */
    public function findById($id) {
        return $this->db->findOne('tramites', ['_id' => new MongoDB\BSON\ObjectId($id)]);
    }
    
    /* Obtener trámites por RUT de viajero */
    public function findByRut($rut) {
        return $this->db->find('tramites', ['viajero_rut' => $rut], ['sort' => ['created_at' => -1]]);
    }
    
    /* Obtener trámites pendientes de validación */
    public function getPendientes($pasoFronterizo = null, $limit = 100) {
        $filter = ['estado' => 'pendiente'];
        if ($pasoFronterizo) {
            $filter['paso_fronterizo'] = $pasoFronterizo;
        }
        return $this->db->find('tramites', $filter, ['limit' => $limit, 'sort' => ['created_at' => 1]]);
    }
    
    /* Actualizar estado de validación cruzada */
    public function actualizarValidacion($tramiteId, $resultados) {
        return $this->db->update('tramites', 
            ['_id' => new MongoDB\BSON\ObjectId($tramiteId)],
            ['validacion_cruzada' => $resultados]
        );
    }
    
    /* Cambiar estado del trámite */
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
    
    /* Generar código único para QR */
    private function generarCodigoQR() {
        return 'LAT-' . strtoupper(uniqid()) . '-' . date('Ymd');
    }

    //Contar por estado
    public function contarPorEstado($estado) {
        return $this->db->count('tramites', ['estado' => $estado]);
    }
    
    /* Obtener estadísticas de flujo */
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
    
    /* Registrar incidencia */
    public function registrarIncidencia($tramiteId, $tipo, $descripcion, $funcionarioId) {
        $incidencia = [
            'tramite_id' => $tramiteId,
            'tipo' => $tipo, // documentacion_invalida, alerta_sanitaria, inconsistencia
            'descripcion' => $descripcion,
            'funcionario_id' => $funcionarioId,
            'estado' => 'abierta',
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];
        return $this->db->insert('incidencias', $incidencia);
    }
}