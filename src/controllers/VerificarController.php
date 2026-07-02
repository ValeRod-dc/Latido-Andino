<?php

class VerificarController {
    public function index() {
        $codigo = $_GET['codigo'] ?? '';

        if (empty($codigo)) {
            http_response_code(400);
            echo "Código QR inválido";
            return;
        }

        $tramiteModel = new Tramite();
        $tramite = $tramiteModel->findByQR($codigo);

        if (!$tramite || $tramite->estado !== 'aprobado') {
            http_response_code(404);
            echo "Trámite no encontrado o no aprobado";
            return;
        }

        require_once __DIR__ . '/../views/verificar.php';
    }
}