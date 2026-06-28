<?php
require_once __DIR__ . '/init.php';
$db = new Conexion();
$conexion = $db->getConexion();

try {
    $conexion->beginTransaction();

    // 1. Crear tabla turnos
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS unexca.turnos (
            id_turno SERIAL PRIMARY KEY,
            nombre_turno VARCHAR(50) NOT NULL UNIQUE
        )
    ");

    // Insertar turnos base
    $conexion->exec("
        INSERT INTO unexca.turnos (nombre_turno) 
        VALUES ('Mañana'), ('Tarde'), ('Noche') 
        ON CONFLICT (nombre_turno) DO NOTHING
    ");

    // 2. Crear tabla horarios (plantilla general)
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS unexca.horarios (
            id_horario SERIAL PRIMARY KEY,
            nombre_horario VARCHAR(100) NOT NULL,
            id_pnf INT NOT NULL REFERENCES unexca.pnf(id_pnf) ON DELETE CASCADE,
            id_turno INT NOT NULL REFERENCES unexca.turnos(id_turno) ON DELETE CASCADE,
            creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // 3. Crear tabla horario_detalle (asignaturas del horario)
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS unexca.horario_detalle (
            id_detalle SERIAL PRIMARY KEY,
            id_horario INT NOT NULL REFERENCES unexca.horarios(id_horario) ON DELETE CASCADE,
            id_asignatura INT NOT NULL REFERENCES unexca.asignatura(id_asignatura) ON DELETE CASCADE,
            dia_semana VARCHAR(20) NOT NULL,
            hora_inicio TIME NOT NULL,
            hora_fin TIME NOT NULL
        )
    ");

    // 4. Añadir id_horario a secciones (para saber qué horario tiene la sección)
    $conexion->exec("
        ALTER TABLE unexca.secciones 
        ADD COLUMN IF NOT EXISTS id_horario INT REFERENCES unexca.horarios(id_horario) ON DELETE SET NULL
    ");

    $conexion->exec("
        ALTER TABLE unexca.secciones 
        ADD COLUMN IF NOT EXISTS id_turno INT REFERENCES unexca.turnos(id_turno) ON DELETE SET NULL
    ");

    $conexion->commit();
    echo "Migración de Horarios y Turnos completada con éxito.\n";

} catch (Exception $e) {
    $conexion->rollBack();
    echo "Error en la migración: " . $e->getMessage() . "\n";
}
