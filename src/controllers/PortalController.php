<?php

class PortalController {
    private $tramiteModel;
    private $validacionService;

    public function __construct() {
        $this->tramiteModel = new Tramite();
        $this->validacionService = new ValidacionService();
    }

    public function viajero() {
        if ($_SESSION['user_role'] !== 'viajero') {
            header('Location: /login');
            exit;
        }
        // Obtener estadísticas y último trámite del viajero
        $rut = $_SESSION['user_rut'] ?? '';
        $ultimosTramites = $this->tramiteModel->findByRut($rut);
        $ultimoTramite = !empty($ultimosTramites) ? $ultimosTramites[0] : null;
        $qrData = ($ultimoTramite && $ultimoTramite->estado === 'aprobado' && !empty($ultimoTramite->pase_agil_qr)) ? $ultimoTramite->pase_agil_qr : null;

        $stats = [
            'tramites_hoy' => $this->tramiteModel->contarPorEstado('aprobado', true), // se necesita implementar
            'tiempo_promedio' => 18,
            'aprobados_online' => 94.2,
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
        require_once __DIR__ . '/../views/portal/base.php';
    }

    public function admin() {
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        require_once __DIR__ . '/../views/portal/base.php';
    }
}