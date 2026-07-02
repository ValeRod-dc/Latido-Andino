<?php

class PortalController {
    private $tramiteModel;
    private $validacionService;
    private $userModel;

    public function __construct() {
        $this->tramiteModel = new Tramite();
        $this->validacionService = new ValidacionService();
        $this->userModel = new User();
    }

    public function viajero() {
        if ($_SESSION['user_role'] !== 'viajero') {
            header('Location: /login');
            exit;
        }

        $rut            = $_SESSION['user_rut'] ?? '';
        $ultimosTramites= !empty($rut)
                        ? $this->tramiteModel->findByRut($rut)
                        : [];
        $ultimoTramite  = !empty($ultimosTramites) ? $ultimosTramites[0] : null;
        $qrData         = ($ultimoTramite
                        && ($ultimoTramite->estado ?? '') === 'aprobado'
                        && !empty($ultimoTramite->pase_agil_qr))
                        ? $ultimoTramite->pase_agil_qr : null;

        $stats = [
            'tramites_hoy'        => $this->tramiteModel->contarPorEstado('aprobado', true),
            'tiempo_promedio'     => 18,
            'aprobados_online'    => 94.2,
            'sistemas_integrados' => 7
        ];

        require_once __DIR__ . '/../views/portal/base.php';
    }

    public function funcionario() {
        if (!in_array($_SESSION['user_role'], ['aduanas', 'sag', 'pdi'])) {
            header('Location: /login');
            exit;
        }
        $tramitesPendientes = $this->tramiteModel->getPendientes(null, 20);
        
        // Obtener incidencias y resolver el nombre del viajero
        $incidencias = $this->tramiteModel->listarIncidencias(50);
        foreach ($incidencias as $inc) {
            if (!empty($inc->viajero_rut)) {
                $usuario = $this->userModel->findByRut($inc->viajero_rut);
                $inc->viajero_nombre = $usuario ? $usuario->name : 'Usuario no encontrado';
            } else {
                $inc->viajero_nombre = 'Sin RUT';
            }
        }

        require_once __DIR__ . '/../views/portal/base.php';
    }

    // Método auxiliar para normalizar RUT
    private function normalizarRut($rut) {
        return strtolower(str_replace(['.', '-', ' '], '', trim($rut)));
    }

    public function admin() {
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $tramitesPendientes = $this->tramiteModel->getPendientes(null, 50);
        require_once __DIR__ . '/../views/portal/base.php';
    }
}