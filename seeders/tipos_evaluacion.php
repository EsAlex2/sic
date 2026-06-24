<?php
require_once __DIR__ . '/../init.php';

/**
 * Funcion para cargar los tipos de evaluación en la base de datos
 */

function cargarTiposEvaluacion()
{
    $data = [
        ['nombre' => 'Parcial', 'descripcion' => 'Examen parcial escrito'],
        ['nombre' => 'Quiz', 'descripcion' => 'Evaluación corta sorpresa o programada'],
        ['nombre' => 'Taller', 'descripcion' => 'Actividad práctica en clase'],
        ['nombre' => 'Exposición', 'descripcion' => 'Presentación oral de investigación'],
        ['nombre' => 'Proyecto', 'descripcion' => 'Trabajo de desarrollo o investigación'],
        ['nombre' => 'Informe', 'descripcion' => 'Documento escrito de análisis'],
        ['nombre' => 'Laboratorio', 'descripcion' => 'Práctica en laboratorio'],
        ['nombre' => 'Participación', 'descripcion' => 'Evaluación continua de participación'],
        ['nombre' => 'Defensa', 'descripcion' => 'Sustentación oral de proyecto'],
        ['nombre' => 'Trabajo de Campo', 'descripcion' => 'Actividad práctica fuera del aula'],
    ];

    $db = new Conexion();
    $conexion = $db->getConexion();
    $mensaje = "Los tipos de evaluación han sido añadidos a la base de datos";

    try {
        $conexion->beginTransaction();

        $query = "INSERT INTO unexca.tipos_evaluacion (nombre, descripcion) VALUES (:nombre, :descripcion)";
        $stmt = $conexion->prepare($query);

        foreach ($data as $tipo) {
            $stmt->execute([
                ':nombre' => $tipo['nombre'],
                ':descripcion' => $tipo['descripcion']
            ]);
        }

        $conexion->commit();
        echo $mensaje;
    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error al insertar datos: " . $e->getMessage();
    }
}
