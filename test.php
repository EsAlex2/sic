<?php
require __DIR__ . '/init.php';
$c = (new Conexion())->getConexion();
$id = 2;
$stmtHorarios = $c->prepare("
    SELECT h.cod_horario, h.hora_inicio, h.hora_fin,
           a.nombre as nombre_asignatura, a.codigo as codigo_asignatura,
           sec.cod_seccion, au.nombre_aula, au.piso, au.nro_aula,
           tr.descripcion as trayecto_desc
    FROM unexca.carga_academica ca
    JOIN unexca.periodo_academico pa ON pa.id_periodo = ca.id_periodo
    JOIN unexca.asignatura a ON a.id_asignatura = ca.id_asignatura
    JOIN unexca.secciones sec ON sec.id_seccion = ca.id_seccion
    LEFT JOIN unexca.horarios h ON h.id_docente = ca.id_docente AND h.id_asignatura = ca.id_asignatura AND h.id_seccion = ca.id_seccion
    LEFT JOIN unexca.aulas au ON au.id_aula = h.id_aula
    LEFT JOIN unexca.trayectos tr ON tr.id_trayecto = sec.id_trayecto
    WHERE ca.id_docente = :id AND pa.estado = '1'
    ORDER BY h.hora_inicio ASC
");
$stmtHorarios->execute([':id' => $id]);
print_r($stmtHorarios->fetchAll(PDO::FETCH_ASSOC));
