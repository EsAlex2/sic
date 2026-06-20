<?php
require_once __DIR__ . '/../init.php';

/**
 * Funcion para crear los datos iniciales en la base de datos
 */

function cargarSedes()
{
    $data = [
        ['turno' => 'Matutino', 'descripcion' => '']
    ];

    $db = new Conexion();
    $conexion = $db->getConexion();
    $mensaje = "Las sedes de la UNEXCA han sido añadidas a la base de datos";

    try {
        $conexion->beginTransaction();

        $query = "INSERT INTO unexca.sedes_unexca (nombre_sede, correo_institucional, direccion) VALUES (:sede, :correo, :dir)";
        $stmt = $conexion->prepare($query);

        foreach ($data as $sedes) {
            $stmt->execute([
                ':sede' => $sedes['nombre_sede'],
                ':correo' => $sedes['correo_institucional'],
                ':dir' => $sedes['direccion']
            ]);
        }

        $conexion->commit();
        echo $mensaje;
    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error al insertar datos: " . $e->getMessage();
    }
}