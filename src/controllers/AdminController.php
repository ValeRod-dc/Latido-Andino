<?php

class AdminController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Cambiar estado de usuario (activar/desactivar)
    public function cambiarEstadoUsuario() {
        header('Content-Type: application/json');

        // Solo admin puede hacer esto
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $usuarioId = $_POST['usuario_id'] ?? '';
        $activo = isset($_POST['activo']) ? filter_var($_POST['activo'], FILTER_VALIDATE_BOOLEAN) : null;

        if (empty($usuarioId) || $activo === null) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        // Evitar que el admin se desactive a sí mismo
        if ($usuarioId === $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'No puedes desactivar tu propia cuenta']);
            return;
        }

        $resultado = $this->userModel->cambiarEstado($usuarioId, $activo);

        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Estado actualizado correctamente' : 'Error al actualizar estado'
        ]);
    }

    // Obtener datos de un usuario para editar
    public function obtenerUsuario() {
        header('Content-Type: application/json');

        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $usuarioId = $_GET['id'] ?? '';
        if (empty($usuarioId)) {
            echo json_encode(['success' => false, 'message' => 'ID de usuario requerido']);
            return;
        }

        $usuario = $this->userModel->findById($usuarioId);
        if (!$usuario) {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            return;
        }

        echo json_encode([
            'success' => true,
            'usuario' => [
                'id' => (string)$usuario->_id,
                'name' => $usuario->name ?? '',
                'email' => $usuario->email ?? '',
                'rut' => $usuario->rut ?? '',
                'role' => $usuario->role ?? 'viajero',
                'nacionalidad' => $usuario->nacionalidad ?? 'Chilena',
                'activo' => $usuario->activo ?? true
            ]
        ]);
    }

    // Actualizar usuario
    public function actualizarUsuario() {
        header('Content-Type: application/json');

        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $usuarioId = $_POST['usuario_id'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $rut = trim($_POST['rut'] ?? '');
        $role = $_POST['role'] ?? 'viajero';
        $nacionalidad = $_POST['nacionalidad'] ?? 'Chilena';
        $activo = isset($_POST['activo']) ? filter_var($_POST['activo'], FILTER_VALIDATE_BOOLEAN) : true;

        if (empty($usuarioId) || empty($name) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Nombre y email son requeridos']);
            return;
        }

        $usuario = $this->userModel->findById($usuarioId);
        if (!$usuario) {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            return;
        }

        // Verificar que el email no esté en uso por otro usuario
        $existing = $this->userModel->findByEmail($email);
        if ($existing && (string)$existing->_id !== $usuarioId) {
            echo json_encode(['success' => false, 'message' => 'El email ya está en uso por otro usuario']);
            return;
        }

        $updateData = [
            'name' => $name,
            'email' => $email,
            'rut' => $rut,
            'role' => $role,
            'nacionalidad' => $nacionalidad,
            'activo' => $activo
        ];

        $resultado = $this->userModel->update($usuarioId, $updateData);

        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Usuario actualizado correctamente' : 'Error al actualizar usuario'
        ]);
    }
}