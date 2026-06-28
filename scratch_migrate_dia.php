<?php
require_once __DIR__ . '/init.php';
$db = new Conexion();
$conexion = $db->getConexion();
try {
    $conexion->exec("ALTER TABLE unexca.horarios ADD COLUMN dia_semana VARCHAR(20) NOT NULL DEFAULT 'Lunes'");
    echo "Columna dia_semana agregada exitosamente.\n";
} catch (PDOException $e) {
    if ($e->getCode() == '42701') {
        echo "La columna dia_semana ya existe.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
