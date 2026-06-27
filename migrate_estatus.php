<?php
require_once __DIR__ . '/init.php';

try {
    $db = new Conexion();
    $conexion = $db->getConexion();
    
    echo "Agregando columna id_estatus a datos_estudiantes...\n";
    // Si la columna estatus_academico existe, la eliminamos
    $conexion->exec("ALTER TABLE unexca.datos_estudiantes DROP COLUMN IF EXISTS estatus_academico");
    
    // Agregamos id_estatus
    $conexion->exec("ALTER TABLE unexca.datos_estudiantes ADD COLUMN IF NOT EXISTS id_estatus INTEGER REFERENCES unexca.estatus(id_estatus)");
    
    echo "¡Base de datos actualizada con éxito!\n";

} catch (PDOException $e) {
    echo "ERROR:\n" . $e->getMessage() . "\n";
}
