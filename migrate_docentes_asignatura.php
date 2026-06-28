<?php
require_once __DIR__ . '/init.php';

try {
    $db = new Conexion();
    $conexion = $db->getConexion();
    
    echo "Agregando columna id_asignatura a datos_docentes...\n";
    $conexion->exec("ALTER TABLE unexca.datos_docentes ADD COLUMN IF NOT EXISTS id_asignatura INTEGER REFERENCES unexca.asignatura(id_asignatura) ON DELETE SET NULL");
    
    echo "¡Base de datos actualizada con éxito!\n";

} catch (PDOException $e) {
    echo "ERROR:\n" . $e->getMessage() . "\n";
}
