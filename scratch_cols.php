<?php
require_once __DIR__ . '/init.php';
$db = new Conexion();
$conexion = $db->getConexion();
$stmt = $conexion->query("SELECT * FROM unexca.horarios LIMIT 1");
$row = $stmt->fetch();
print_r($row);
