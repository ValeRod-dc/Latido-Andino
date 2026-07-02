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
                $inc->viajero_nombre = $usuario ? $usuario->name : 'Usuario no encontrado (RUT: ' . $inc->viajero_rut . ')';
            } else {
                $inc->viajero_nombre = 'Sin RUT';
            }
        }

        require_once __DIR__ . '/../views/portal/base.php';
    }

    public function admin() {
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $tramitesPendientes = $this->tramiteModel->getPendientes(null, 50);
        $db = Database::getInstance();

        // Verificar si la bitácora tiene datos, si no, insertar algunos de ejemplo
        $countBitacora = $db->count('bitacora');
        if ($countBitacora == 0) {
            $db->insert('bitacora', ['hora' => '09:42', 'accion' => 'LOGIN', 'usuario' => 'admin@latidoandino.cl', 'detalle' => 'Inicio de sesión exitoso', 'tipo' => 'info', 'created_at' => new MongoDB\BSON\UTCDateTime()]);
            $db->insert('bitacora', ['hora' => '09:38', 'accion' => 'APROBAR', 'usuario' => 'aduanas@aduana.cl', 'detalle' => 'Trámite #A3F91 aprobado', 'tipo' => 'ok', 'created_at' => new MongoDB\BSON\UTCDateTime()]);
            $db->insert('bitacora', ['hora' => '09:31', 'accion' => 'RECHAZAR', 'usuario' => 'sag@sag.cl', 'detalle' => 'Trámite #B2C44 rechazado — doc. falso', 'tipo' => 'warn', 'created_at' => new MongoDB\BSON\UTCDateTime()]);
            $db->insert('bitacora', ['hora' => '09:15', 'accion' => 'CREAR', 'usuario' => 'admin@latidoandino.cl', 'detalle' => 'Usuario carmen.rios creado', 'tipo' => 'info', 'created_at' => new MongoDB\BSON\UTCDateTime()]);
            $db->insert('bitacora', ['hora' => '08:55', 'accion' => 'ALERTA', 'usuario' => 'SISTEMA', 'detalle' => 'API Interpol sin respuesta 2 min.', 'tipo' => 'error', 'created_at' => new MongoDB\BSON\UTCDateTime()]);
        }

        $bitacora = $db->find('bitacora', [], ['sort' => ['created_at' => -1], 'limit' => 50]);

        require_once __DIR__ . '/../views/portal/base.php';
    }
}