<?php

class Vehiculo {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Registrar vehículo para salida temporal (Acuerdo Chile-Argentina)
     */
    public function registrarSalidaTemporal($datos) {
        $vehiculo = [
            'patente' => strtoupper($datos['patente']),
            'marca' => $datos['marca'],
            'modelo' => $datos['modelo'],
            'año' => (int)$datos['año'],
            'color' => $datos['color'],
            'propietario_rut' => $datos['propietario_rut'],
            'propietario_nombre' => $datos['propietario_nombre'],
            'acuerdo_chile_argentina' => [
                'activo' => true,
                'fecha_emision' => new MongoDB\BSON\UTCDateTime(),
                'fecha_vencimiento' => new MongoDB\BSON\UTCDateTime(
                    strtotime('+180 days') * 1000
                ),
                'estado' => 'vigente'
            ],
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];
        
        $resultado = $this->db->insert('vehiculos', $vehiculo);
        
        if ($resultado) {
            // Generar formulario PDF
            $this->generarFormularioPDF($vehiculo);
        }
        
        return $resultado;
    }
    
    /**
     * Buscar vehículo por patente
     */
    public function findByPatente($patente) {
        return $this->db->findOne('vehiculos', ['patente' => strtoupper($patente)]);
    }
    
    /**
     * Obtener vehículos por RUT de propietario
     */
    public function findByPropietario($rut) {
        return $this->db->find('vehiculos', ['propietario_rut' => $rut]);
    }
    
    /**
     * Verificar vigencia del acuerdo bilateral
     */
    public function verificarVigencia($patente) {
        $vehiculo = $this->findByPatente($patente);
        if (!$vehiculo || !isset($vehiculo->acuerdo_chile_argentina)) {
            return ['vigente' => false, 'mensaje' => 'Vehículo no registrado'];
        }
        
        $fechaVencimiento = $vehiculo->acuerdo_chile_argentina->fecha_vencimiento->toDateTime();
        $ahora = new DateTime();
        
        if ($ahora > $fechaVencimiento) {
            return ['vigente' => false, 'mensaje' => 'Acuerdo vencido', 'vencimiento' => $fechaVencimiento];
        }
        
        $diasRestantes = $ahora->diff($fechaVencimiento)->days;
        return [
            'vigente' => true, 
            'mensaje' => 'Vehículo autorizado',
            'dias_restantes' => $diasRestantes,
            'vencimiento' => $fechaVencimiento
        ];
    }
    
    /* Generar formulario PDF (mock - se implementa con librería) */
    private function generarFormularioPDF($vehiculo) {
        // Aquí se integraría con iText/PDFLib para generar PDF
        // Por ahora solo registramos en log
        error_log("Generando formulario para vehículo: " . $vehiculo['patente']);
    }
}