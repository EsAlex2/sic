<?php
require __DIR__ . '/init.php';
$c = (new Conexion())->getConexion();
print_r($c->query("SELECT column_name FROM information_schema.columns WHERE table_schema = 'unexca' AND table_name = 'secciones'")->fetchAll(PDO::FETCH_ASSOC));
