<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Conexion {

    private string $host = "localhost";
    private string $db_name = "admin_sql";
    private string $db_user = "postgres";
    private string $db_password = "qwerty2801**"; 
    private string $db_port = '5432';
    private string $db = 'pgsql';
    private PDO $conexion;

    public function __construct() {
        try {
            $dsn = "pgsql:host={$this->host};dbname={$this->db_name};";
            
            if (!is_string($dsn) || trim($dsn) === '') {
                die("DSN inválido: " . var_export($dsn, true));
            }
   
            $this->conexion = new PDO($dsn, $this->db_user, $this->db_password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage() . " -- DSN: " . var_export(isset($dsn) ? $dsn : null, true));
        }
    }
   
    public function getConexion(): PDO {
        return $this->conexion;
    }
}

//URL base del sistema

define('URL_BASE', 'http://localhost/sac');