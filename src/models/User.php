<?php

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function findByEmail($email) {
        return $this->db->findOne('users', ['email' => $email]);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function create($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = new MongoDB\BSON\UTCDateTime();
        return $this->db->insert('users', $data);
    }
}
