<?php

class ValidacionService {
    private $integracionMock;
    
    public function __construct() {
        $this->integracionMock = new IntegracionMockService();
    }
    
    /**
     * Realiza validación cruzada con todos los sistemas externos
     * Simula consultas en paralelo a PDI, SAG, Interpol, etc.
     */
    public function validacionCruzada($rut, $datosAdicionales = []) {
        $inicio = microtime(true);
        
        // Simular consultas en paralelo
        $resultados = [
            'pdi' => $this->integracionMock->consultarPDI($rut),
            'sag' => $this->integracionMock->consultarSAG($datosAdicionales),
            'interpol' => $this->integracionMock->consultarInterpol($rut),
            'registro_civil' => $this->integracionMock->consultarRegistroCivil($rut)
        ];
        
        $tiempoRespuesta = (microtime(true) - $inicio) * 1000;
        
        // Determinar resultado global
        $esAprobado = true;
        $observaciones = [];
        
        foreach ($resultados as $sistema => $resultado) {
            if (!$resultado['aprobado']) {
                $esAprobado = false;
                $observaciones[] = $resultado['observacion'];
            }
        }
        
        return [
            'aprobado' => $esAprobado,
            'resultados' => $resultados,
            'observaciones' => $observaciones,
            'tiempo_respuesta_ms' => round($tiempoRespuesta, 2),
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /* Validación específica para menores de edad */
    public function validarMenor($rutMenor, $rutAdulto, $tipoAutorizacion) {
        // Verificar autorización notarial
        $autorizacionValida = $this->verificarAutorizacionNotarial($rutMenor, $rutAdulto);
        
        if (!$autorizacionValida) {
            return [
                'aprobado' => false,
                'motivo' => 'Autorización notarial requerida',
                'recomendacion' => 'Concurrir al Juzgado de Familia'
            ];
        }
        
        // Verificar identidad del menor
        $identidad = $this->integracionMock->consultarRegistroCivil($rutMenor);
        
        return [
            'aprobado' => $identidad['aprobado'],
            'menor_nombre' => $identidad['nombre'] ?? null,
            'tipo_autorizacion' => $tipoAutorizacion
        ];
    }
    
    private function verificarAutorizacionNotarial($rutMenor, $rutAdulto) {
        // En producción, esto consultaría un registro de autorizaciones
        // Por ahora, simulamos que siempre es válido
        return true;
    }
}