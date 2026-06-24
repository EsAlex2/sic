<?php
require_once __DIR__ . '/../init.php';

/**
 * Funcion para cargar los turnos académicos en la base de datos
 */

function cargarTurnos()
{
    $data = [
        [
            'turno' => 'Mañana',
            'descripcion' => 'Horario matutino (07:00 - 12:00)'
        ],
        [
            'turno' => 'Tarde',
            'descripcion' => 'Horario vespertino (13:00 - 18:00)'
        ],
        [
            'turno' => 'Noche',
            'descripcion' => 'Horario nocturno (18:00 - 22:00)'
        ],
    ];

    $db = new Conexion();
    $conexion = $db->getConexion();
    $mensaje = "Los turnos han sido añadidos a la base de datos";

    try {
        $conexion->beginTransaction();

        $query = "INSERT INTO unexca.turnos (turno, descripcion) VALUES (:turno, :descripcion)";
        $stmt = $conexion->prepare($query);

        foreach ($data as $turno) {
            $stmt->execute([
                ':turno' => $turno['turno'],
                ':descripcion' => $turno['descripcion']
            ]);
        }

        $conexion->commit();
        echo $mensaje;
    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error al insertar datos: " . $e->getMessage();
    }
}
