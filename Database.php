<?php

class Database {
    public $conn;
    /**
     * Constructor for Database class
     * 
     * @param array $config Database configuration
     * @return void
     */
    public function __construct($config){
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        
        // Set options for PDO
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Fetch data in associative array
            // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // Fetch data in object form
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ];

        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Query the database
     * 
     * @param string $query
     * 
     * @return PDOStatement
     * @throws PDOException
     */
    public function query($query){
        try{
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }
        catch (PDOException $e) {
            throw new Exception("Database query error: " . $e->getMessage());
        }
    }
}