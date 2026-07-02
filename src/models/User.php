<?php

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function findByEmail($email) {
        return $this->db->findOne('usuarios', [
            'email' => $email,
            '$or' => [
                ['activo' => true],
                ['activo' => ['$exists' => false]]
            ]
        ]);
    }

    public function findByRut($rut) {
        if (empty($rut)) return null;
        return $this->db->findOne('usuarios', ['rut' => $rut]);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function create($data) {
        $user = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'viajero',
            'rut' => $data['rut'] ?? null,
            'nacionalidad' => $data['nacionalidad'] ?? 'Chilena',
            'activo' => true,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];
        return $this->db->insert('usuarios', $user);
    }
    
    public function findById($id) {
        return $this->db->findOne('usuarios', ['_id' => new MongoDB\BSON\ObjectId($id)]);
    }
    
    // NUEVO: Actualizar usuario completo
    public function update($id, $data) {
        return $this->db->update('usuarios', ['_id' => new MongoDB\BSON\ObjectId($id)], $data);
    }

    // NUEVO: Cambiar estado (activar/desactivar)
    public function cambiarEstado($id, $activo) {
        return $this->db->update('usuarios', ['_id' => new MongoDB\BSON\ObjectId($id)], ['activo' => $activo]);
    }

    // NUEVO: Listar todos los usuarios (con filtro opcional)
    public function listar($filter = [], $options = []) {
        return $this->db->find('usuarios', $filter, $options);
    }
}