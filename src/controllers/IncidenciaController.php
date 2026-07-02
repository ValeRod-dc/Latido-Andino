<?php

class IncidenciaController {
    private $tramiteModel;

    public function __construct() {
        $this->tramiteModel = new Tramite();
    }

    public function registrar() {
        header('Content-Type: application/json');

        if (!in_array($_SESSION['user_role'], ['aduanas', 'sag', 'pdi', 'admin'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $tramiteId = $_POST['tramite_id'] ?? null;
        $rut = trim($_POST['rut'] ?? '');
        $tipo = $_POST['tipo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';

        // Validar: al menos RUT o trámite
        if (empty($rut) && empty($tramiteId)) {
            echo json_encode(['success' => false, 'message' => 'Debe proporcionar RUT del viajero o un trámite asociado']);
            return;
        }

        if (empty($tipo) || empty($descripcion)) {
            echo json_encode(['success' => false, 'message' => 'Tipo y descripción son obligatorios']);
            return;
        }

        // Normalizar RUT si se proporciona
        if (!empty($rut)) {
            $rut = $this->normalizarRut($rut);
        }

        // Si no se proporciona RUT pero sí trámite, intentamos obtener el RUT del trámite
        if (empty($rut) && !empty($tramiteId)) {
            $tramite = $this->tramiteModel->findById($tramiteId);
            if ($tramite && !empty($tramite->viajero_rut)) {
                $rut = $this->normalizarRut($tramite->viajero_rut);
            }
        }

        $codigo = $this->tramiteModel->registrarIncidencia(
            $tramiteId,
            $tipo,
            $descripcion,
            $_SESSION['user_id'],
            $rut
        );

        echo json_encode([
            'success' => (bool)$codigo,
            'codigo' => $codigo ?: null
        ]);
    }

    // Método auxiliar para normalizar RUT
    private function normalizarRut($rut) {
        return strtolower(str_replace(['.', '-', ' '], '', trim($rut)));
    }

    public function listar() {
        // Implementar si se necesita ver incidencias
    }
}