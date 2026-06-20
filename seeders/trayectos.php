<?php
require_once __DIR__ . '/../init.php';

/**
 * Funcion para crear los datos iniciales en la base de datos
 */

function cargarTrayectos()
{
    $data = [
        ['cod_trayecto' => 'T-I', 'descripcion' => 'Trayecto Inicial'],
        ['cod_trayecto' => 'T1-S1', 'descripcion' => 'Trayecto 1, Semestre 1'],
        ['cod_trayecto' => 'T1-S2', 'descripcion' => 'Trayecto 1, Semestre 2'],
        ['cod_trayecto' => 'T2-S1', 'descripcion' => 'Trayecto 2, Semestre 1'],
        ['cod_trayecto' => 'T2-S2', 'descripcion' => 'Trayecto 2, Semestre 2'],
        ['cod_trayecto' => 'T3-S1', 'descripcion' => 'Trayecto 3, Semestre 1'],
        ['cod_trayecto' => 'T3-S2', 'descripcion' => 'Trayecto 3, Semestre 2'],
        ['cod_trayecto' => 'T4-S1', 'descripcion' => 'Trayecto 4, Semestre 1'],
        ['cod_trayecto' => 'T4-S2', 'descripcion' => 'Trayecto 4, Semestre 2'],
    ];
    $db = new Conexion();
    $conexion = $db->getConexion();
    $mensaje = "Los trayectos/semestres de la UNEXCA han sido añadidas a la base de datos";

    try {
        $conexion->beginTransaction();
        $query =  "INSERT INTO unexca.trayectos (cod_trayecto, descripcion) VALUES (:cod, :desc)";
        $stmt = $conexion->prepare($query);

        foreach ($data as $trayectos) {
            $stmt->execute([
                ':cod' => $trayectos['cod_trayecto'],
                ':desc' => $trayectos['descripcion']
            ]);
        }
        $conexion->commit();
        echo $mensaje;
    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error al insertar: " . $e->getMessage();
    }
}
