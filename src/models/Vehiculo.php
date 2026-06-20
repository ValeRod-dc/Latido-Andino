<?php

class Vehiculo {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function crear($datos) {
        $vehiculo = [
            'patente' => strtoupper($datos['patente']),
            'tramite_id' => $datos['tramite_id'] ?? null,
            'viajero_rut' => $datos['viajero_rut'],
            'marca' => $datos['marca'] ?? null,
            'modelo' => $datos['modelo'] ?? null,
            'anio' => $datos['anio'] ?? null,
            'color' => $datos['color'] ?? null,
            'estado' => 'pendiente',
            'observaciones' => null,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];
        return $this->db->insert('vehiculos', $vehiculo);
    }

    public function findById($id) {
        return $this->db->findOne('vehiculos', ['_id' => new MongoDB\BSON\ObjectId($id)]);
    }

    public function findByPatente($patente) {
        return $this->db->findOne('vehiculos', ['patente' => strtoupper($patente)]);
    }

    public function findByRut($rut) {
        return $this->db->find('vehiculos', ['viajero_rut' => $rut], ['sort' => ['created_at' => -1]]);
    }

    public function cambiarEstado($vehiculoId, $estado, $observacion = null) {
        $update = ['estado' => $estado];
        if ($observacion) {
            $update['observaciones'] = $observacion;
        }
        return $this->db->update('vehiculos',
            ['_id' => new MongoDB\BSON\ObjectId($vehiculoId)],
            $update
        );
    }
}