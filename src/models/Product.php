<?php

class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        return $this->db->find('products', ['active' => true]);
    }
    
    public function findById($id) {
        return $this->db->findOne('products', ['_id' => new MongoDB\BSON\ObjectId($id)]);
    }
    
    public function create($data) {
        $data['active'] = true;
        $data['created_at'] = new MongoDB\BSON\UTCDateTime();
        return $this->db->insert('products', $data);
    }
}
