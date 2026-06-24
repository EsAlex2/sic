<?php
require_once __DIR__ . '/../init.php';

/**
 * Funcion para cargar los caracteres de asignatura en la base de datos
 */

function cargarCaracterAsignatura()
{
    $data = [
        ['nombre' => 'Obligatoria', 'descripcion' => 'Asignatura de carácter obligatorio dentro del pensum'],
        ['nombre' => 'Electiva', 'descripcion' => 'Asignatura de libre elección por el estudiante'],
        ['nombre' => 'Acreditable', 'descripcion' => 'Actividad acreditable complementaria'],
        ['nombre' => 'Proyecto', 'descripcion' => 'Asignatura tipo proyecto socio-tecnológico'],
    ];

    $db = new Conexion();
    $conexion = $db->getConexion();
    $mensaje = "Los caracteres de asignatura han sido añadidos a la base de datos";

    try {
        $conexion->beginTransaction();

        $query = "INSERT INTO unexca.caracter_asignatura (nombre, descripcion) VALUES (:nombre, :descripcion)";
        $stmt = $conexion->prepare($query);

        foreach ($data as $caracter) {
            $stmt->execute([
                ':nombre' => $caracter['nombre'],
                ':descripcion' => $caracter['descripcion']
            ]);
        }

        $conexion->commit();
        echo $mensaje;
    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error al insertar datos: " . $e->getMessage();
    }
}
