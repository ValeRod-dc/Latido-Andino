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
        
        // Limpiar RUT: eliminar puntos, guiones, espacios, convertir a minúsculas (para k)
        $rutLimpio = strtolower(preg_replace('/[^0-9kK]/', '', $rut));
        
        // Intentar buscar de varias formas:
        // 1. Coincidencia exacta con el campo rut (tal como está guardado)
        $usuario = $this->db->findOne('usuarios', ['rut' => $rut]);
        if ($usuario) return $usuario;
        
        // 2. Coincidencia con el RUT limpio (sin caracteres especiales)
        $usuario = $this->db->findOne('usuarios', ['rut' => $rutLimpio]);
        if ($usuario) return $usuario;
        
        // 3. Búsqueda por expresión regular (que contenga el RUT limpio, con ignorar mayúsculas)
        $usuario = $this->db->findOne('usuarios', [
            'rut' => ['$regex' => $rutLimpio, '$options' => 'i']
        ]);
        if ($usuario) return $usuario;
        
        // 4. Intentar buscar por RUT que coincida en la parte numérica (sacando el dígito verificador)
        // Esto es un fallback por si el dígito verificador no coincide
        $numerosRut = preg_replace('/[^0-9]/', '', $rutLimpio);
        if (strlen($numerosRut) > 1) {
            $usuario = $this->db->findOne('usuarios', [
                'rut' => ['$regex' => $numerosRut, '$options' => 'i']
            ]);
            if ($usuario) return $usuario;
        }
        
        return null;
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
    
    public function update($id, $data) {
        return $this->db->update('usuarios', ['_id' => new MongoDB\BSON\ObjectId($id)], $data);
    }
}