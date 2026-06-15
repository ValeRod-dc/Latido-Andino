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
            $this->db = MONGO_DB;  // 'latido_andino' desde index.php
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
    
    /* Busca múltiples documentos */
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
    
    /* Busca un solo documento */
    public function findOne($collection, $filter = []) {
        $options = ['limit' => 1];
        $results = $this->find($collection, $filter, $options);
        return !empty($results) ? $results[0] : null;
    }
    
    /* Inserta un documento */
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
    
    /* Actualiza un documento (por defecto usa $set) */
    public function update($collection, $filter, $update, $useSet = true) {
        $bulk = new MongoDB\Driver\BulkWrite;
        
        if ($useSet) {
            $bulk->update($filter, ['$set' => $update]);
        } else {
            $bulk->update($filter, $update);
        }
        
        $namespace = $this->db . '.' . $collection;
        
        try {
            $result = $this->manager->executeBulkWrite($namespace, $bulk);
            return $result->getModifiedCount() > 0;
        } catch (Exception $e) {
            error_log("Error en update: " . $e->getMessage());
            return false;
        }
    }
    
    /* Elimina documentos que cumplan el filtro */
    public function delete($collection, $filter, $limit = 0) {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete($filter, ['limit' => $limit]);
        
        $namespace = $this->db . '.' . $collection;
        
        try {
            $result = $this->manager->executeBulkWrite($namespace, $bulk);
            return $result->getDeletedCount();
        } catch (Exception $e) {
            error_log("Error en delete: " . $e->getMessage());
            return 0;
        }
    }
    
    /* Ejecuta una pipeline de agregación */
    public function aggregate($collection, $pipeline) {
        $command = new MongoDB\Driver\Command([
            'aggregate' => $collection,
            'pipeline' => $pipeline,
            'cursor' => new stdClass
        ]);
        
        try {
            $cursor = $this->manager->executeCommand($this->db, $command);
            return $cursor->toArray();
        } catch (Exception $e) {
            error_log("Error en aggregate: " . $e->getMessage());
            return [];
        }
    }
    
    /* Cuenta documentos que cumplen el filtro */
    public function count($collection, $filter = []) {
        $command = new MongoDB\Driver\Command([
            'count' => $collection,
            'query' => $filter
        ]);
        
        try {
            $cursor = $this->manager->executeCommand($this->db, $command);
            $result = current($cursor->toArray());
            return $result->n ?? 0;
        } catch (Exception $e) {
            error_log("Error en count: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtiene el ID del último insert (útil si se necesita)
     * Nota: este método requiere guardar el último ID manualmente
     */
    public function getLastInsertId() {
        // MongoDB no tiene autoincrement, pero podemos implementar una secuencia si es necesario
        // Por ahora retornamos null
        return null;
    }
}