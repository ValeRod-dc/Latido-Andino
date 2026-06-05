<?php

class Database {
    private static $instance = null;
    private $manager;
    private $db;
    
    private function __construct() {
        $connectionString = sprintf(
            "mongodb://%s:%s",
            MONGO_HOST,
            MONGO_PORT
        );
        
        try {
            $this->manager = new MongoDB\Driver\Manager($connectionString);
            $this->db = MONGO_DB;
        } catch (Exception $e) {
            die("Error de conexión a MongoDB: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function find($collection, $filter = [], $options = []) {
        $query = new MongoDB\Driver\Query($filter, $options);
        $namespace = $this->db . '.' . $collection;
        
        try {
            $cursor = $this->manager->executeQuery($namespace, $query);
            return $cursor->toArray();
        } catch (Exception $e) {
            error_log("Error en find: " . $e->getMessage());
            return [];
        }
    }
    
    public function findOne($collection, $filter = []) {
        $options = ['limit' => 1];
        $results = $this->find($collection, $filter, $options);
        return !empty($results) ? $results[0] : null;
    }
    
    public function insert($collection, $document) {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert($document);
        
        $namespace = $this->db . '.' . $collection;
        
        try {
            $result = $this->manager->executeBulkWrite($namespace, $bulk);
            return $result->getInsertedCount() > 0;
        } catch (Exception $e) {
            error_log("Error en insert: " . $e->getMessage());
            return false;
        }
    }
    
    public function update($collection, $filter, $update) {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update($filter, ['$set' => $update]);
        
        $namespace = $this->db . '.' . $collection;
        
        try {
            $result = $this->manager->executeBulkWrite($namespace, $bulk);
            return $result->getModifiedCount() > 0;
        } catch (Exception $e) {
            error_log("Error en update: " . $e->getMessage());
            return false;
        }
    }
}
