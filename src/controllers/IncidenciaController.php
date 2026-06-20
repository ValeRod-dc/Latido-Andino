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

        $tramiteId = $_POST['tramite_id'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';

        if (!$tramiteId || !$tipo || !$descripcion) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
            return;
        }

        $resultado = $this->tramiteModel->registrarIncidencia(
            $tramiteId,
            $tipo,
            $descripcion,
            $_SESSION['user_id']
        );

        echo json_encode(['success' => $resultado]);
    }

    public function listar() {
        // Implementar si se necesita ver incidencias
    }
}