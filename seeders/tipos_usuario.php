<?php
require_once __DIR__ . '/../init.php';

/**
 * Funcion para cargar los tipos de usuario en la base de datos
 */

function cargarTiposUsuario()
{
    $data = [
        ['nombre_tipo' => 'Administrador', 'descripcion' => 'Superusuario con permisos totales'],
        ['nombre_tipo' => 'Estudiante', 'descripcion' => 'Usuario inscrito en programas académicos'],
        ['nombre_tipo' => 'Docente', 'descripcion' => 'Personal académico encargado de materias y calificaciones'],
        ['nombre_tipo' => 'Control de Estudios', 'descripcion' => 'Personal que gestiona expedientes académicos'],
        ['nombre_tipo' => 'Finanzas', 'descripcion' => 'Personal de recepción de pagos y solvencias'],
    ];

    $db = new Conexion();
    $conexion = $db->getConexion();
    $mensaje = "Los tipos de usuario han sido añadidos a la base de datos";

    try {
        $conexion->beginTransaction();

        $query = "INSERT INTO unexca.tipos_usuario (nombre_tipo, descripcion) VALUES (:nombre, :descripcion)";
        $stmt = $conexion->prepare($query);

        foreach ($data as $tipo) {
            $stmt->execute([
                ':nombre' => $tipo['nombre_tipo'],
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
