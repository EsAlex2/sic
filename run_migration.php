<?php
require_once __DIR__ . '/init.php';

$db = new Conexion();
$conexion = $db->getConexion();

$sql = file_get_contents(__DIR__ . '/config/001_add_estatus_roles_permisos.sql');

try {
    $conexion->exec($sql);
    echo "Migración ejecutada con éxito.\n";
} catch (PDOException $e) {
    echo "Error ejecutando migración: " . $e->getMessage() . "\n";
}
