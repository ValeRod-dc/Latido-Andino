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

    // Buscar usuario por RUT (normalizando el formato)
    public function findByRut($rut) {
        if (empty($rut)) return null;
        // Normalizar: eliminar puntos, guiones y espacios, convertir a minúsculas
        $rutNormalizado = $this->normalizarRut($rut);
        // Buscar en la BD usando el RUT normalizado o el RUT tal cual (por si acaso)
        return $this->db->findOne('usuarios', [
            '$or' => [
                ['rut' => $rutNormalizado],
                ['rut' => $rut]
            ]
        ]);
    }

    // Método auxiliar para normalizar RUT
    private function normalizarRut($rut) {
        return strtolower(str_replace(['.', '-', ' '], '', trim($rut)));
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function create($data) {
        // Normalizar RUT antes de guardar
        $rut = isset($data['rut']) ? $this->normalizarRut($data['rut']) : null;
        $user = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'viajero',
            'rut' => $rut,
            'nacionalidad' => $data['nacionalidad'] ?? 'Chilena',
            'activo' => true,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];
        return $this->db->insert('usuarios', $user);
    }
    
    public function findById($id) {
        return $this->db->findOne('usuarios', ['_id' => new MongoDB\BSON\ObjectId($id)]);
    }
    
    public function update($id, $data) {
        return $this->db->update('usuarios', ['_id' => new MongoDB\BSON\ObjectId($id)], $data);
    }
}