<?php

class IntegracionMockService {
    
    /**
     * Simula consulta a PDI (Policía de Investigaciones)
     * Verifica arraigos, órdenes de detención, etc.
     */
    public function consultarPDI($rut) {
        // Simular tiempos de respuesta
        usleep(rand(100000, 500000)); // 100-500ms
        
        // Lista negra simulada
        $blacklist = ['11111111-1', '22222222-2'];
        
        if (in_array($rut, $blacklist)) {
            return [
                'aprobado' => false,
                'codigo' => 'ALERTA_PDI_001',
                'observacion' => 'Persona con orden de detención vigente',
                'detalle' => 'Solicitar verificación presencial'
            ];
        }
        
        return [
            'aprobado' => true,
            'codigo' => 'OK',
            'observacion' => 'Sin impedimentos',
            'fecha_consulta' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Simula consulta a SAG (Servicio Agrícola y Ganadero)
     * Verifica declaración de productos animales/vegetales
     */
    public function consultarSAG($datos) {
        usleep(rand(100000, 300000)); // 100-300ms
        
        $productosProhibidos = ['manzanas', 'naranjas', 'productos_carnicos'];
        
        if (isset($datos['productos']) && is_array($datos['productos'])) {
            foreach ($datos['productos'] as $producto) {
                if (in_array($producto, $productosProhibidos)) {
                    return [
                        'aprobado' => false,
                        'codigo' => 'SAG_PROHIBIDO',
                        'observacion' => "Producto prohibido: {$producto}",
                        'requiere_decomiso' => true
                    ];
                }
            }
        }
        
        if (isset($datos['mascota']) && $datos['mascota'] === true) {
            return $this->validarMascota($datos);
        }
        
        return [
            'aprobado' => true,
            'codigo' => 'SAG_OK',
            'observacion' => 'Declaración fitosanitaria aprobada'
        ];
    }
    
    /* Validación específica para mascotas */
    private function validarMascota($datos) {
        // Verificar vacunas, microchip, etc.
        $requisitos = [
            'vacuna_antirrabica' => $datos['vacuna_antirrabica'] ?? false,
            'microchip' => $datos['microchip'] ?? false,
            'certificado_salud' => $datos['certificado_salud'] ?? false
        ];
        
        foreach ($requisitos as $req => $cumple) {
            if (!$cumple) {
                return [
                    'aprobado' => false,
                    'codigo' => 'SAG_MASCOTA_INCOMPLETO',
                    'observacion' => "Falta requisito: {$req}",
                    'requisitos_pendientes' => $requisitos
                ];
            }
        }
        
        return [
            'aprobado' => true,
            'codigo' => 'SAG_MASCOTA_OK',
            'observacion' => 'Mascota cumple requisitos sanitarios'
        ];
    }
    
    /* Simula consulta a Interpol */
    public function consultarInterpol($rut) {
        usleep(rand(200000, 800000)); // 200-800ms
        
        // Simular documentos robados
        $documentosRobados = ['33333333-3', '44444444-4'];
        
        if (in_array($rut, $documentosRobados)) {
            return [
                'aprobado' => false,
                'codigo' => 'INTERPOL_ALERTA',
                'observacion' => 'Documento reportado como perdido/robado',
                'nivel_prioridad' => 'ALTO'
            ];
        }
        
        return [
            'aprobado' => true,
            'codigo' => 'INTERPOL_OK',
            'observacion' => 'Sin alertas internacionales'
        ];
    }
    
    /* Simula consulta a Registro Civil */
    public function consultarRegistroCivil($rut) {
        usleep(rand(50000, 200000)); // 50-200ms
        
        $personas = [
            '12345678-9' => ['nombre' => 'Juan Pérez', 'fecha_nacimiento' => '1990-01-15', 'vigente' => true],
            '87654321-k' => ['nombre' => 'María González', 'fecha_nacimiento' => '1985-05-20', 'vigente' => true]
        ];
        
        if (isset($personas[$rut])) {
            return [
                'aprobado' => true,
                'nombre' => $personas[$rut]['nombre'],
                'fecha_nacimiento' => $personas[$rut]['fecha_nacimiento'],
                'estado' => 'vigente'
            ];
        }
        
        return [
            'aprobado' => false,
            'observacion' => 'RUT no encontrado en Registro Civil',
            'requiere_verificacion' => true
        ];
    }
}