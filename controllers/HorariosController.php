<?php

class HorariosController
{
    private $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function index(): void
    {
        $stmtHorarios = $this->conexion->query("
            SELECT h.*, a.nombre as asignatura, s.cod_seccion as seccion, 
                   dp.nombres, dp.apellidos, t.turno, au.nombre_aula as aula
            FROM unexca.horarios h
            LEFT JOIN unexca.asignatura a ON a.id_asignatura = h.id_asignatura
            LEFT JOIN unexca.secciones s ON s.id_seccion = h.id_seccion
            LEFT JOIN unexca.datos_docentes doc ON doc.id_docente = h.id_docente
            LEFT JOIN unexca.datos_personas dp ON dp.id_persona = doc.id_persona
            LEFT JOIN unexca.turnos t ON t.id_turno = h.id_turno
            LEFT JOIN unexca.aulas au ON au.id_aula = h.id_aula
            ORDER BY h.id_horario DESC
        ");
        $horarios = $stmtHorarios->fetchAll();

        $asignaturas = $this->conexion->query("SELECT * FROM unexca.asignatura ORDER BY nombre")->fetchAll();
        $secciones = $this->conexion->query("SELECT * FROM unexca.secciones ORDER BY cod_seccion")->fetchAll();
        
        $docentes = $this->conexion->query("
            SELECT doc.id_docente, doc.id_pnf, dp.nombres, dp.apellidos
            FROM unexca.datos_docentes doc
            JOIN unexca.datos_personas dp ON doc.id_persona = dp.id_persona
            ORDER BY dp.apellidos, dp.nombres
        ")->fetchAll();

        $pnfs = $this->conexion->query("SELECT * FROM unexca.pnf ORDER BY nombre_pnf")->fetchAll();

        $aulas = $this->conexion->query("SELECT * FROM unexca.aulas ORDER BY nombre_aula")->fetchAll();
        $turnos = $this->conexion->query("SELECT * FROM unexca.turnos ORDER BY turno")->fetchAll();
        $trayectos = $this->conexion->query("SELECT * FROM unexca.trayectos ORDER BY cod_trayecto")->fetchAll();

        renderView('admin/horarios', [
            'titulo' => 'Gestión de Horarios',
            'pnfs' => $pnfs,
            'horarios' => $horarios,
            'asignaturas' => $asignaturas,
            'secciones' => $secciones,
            'docentes' => $docentes,
            'aulas' => $aulas,
            'turnos' => $turnos,
            'trayectos' => $trayectos,
            'flash' => getFlash()
        ]);
    }

    public function guardar(): void
    {
        $id_asignatura = $_POST['id_asignatura'] ?? null;
        $id_seccion = $_POST['id_seccion'] ?? null;
        $id_docente = $_POST['id_docente'] ?? null;
        $id_aula = $_POST['id_aula'] ?? null;
        $id_turno = $_POST['id_turno'] ?? null;
        $id_trayecto = $_POST['id_trayecto'] ?? null;
        $cod_horario = $_POST['cod_horario'] ?? 'HOR-' . time();
        $hora_inicio = $_POST['hora_inicio'] ?? null;
        $hora_fin = $_POST['hora_fin'] ?? null;
        $dia_semana = $_POST['dia_semana'] ?? null;

        if (empty($id_asignatura) || empty($id_seccion) || empty($id_docente) || empty($hora_inicio) || empty($hora_fin) || empty($dia_semana)) {
            setFlash('error', 'Faltan datos requeridos.');
            redirect('admin/horarios');
            return;
        }

        try {
            // Validaciones de Choques
            $stmtConflictos = $this->conexion->prepare("
                SELECT h.id_seccion, h.id_docente, h.id_aula 
                FROM unexca.horarios h
                WHERE h.dia_semana = :dia_semana
                  AND (:hora_inicio < h.hora_fin AND :hora_fin > h.hora_inicio)
                  AND (h.id_seccion = :id_seccion OR h.id_docente = :id_docente OR h.id_aula = :id_aula)
            ");
            $stmtConflictos->execute([
                ':dia_semana' => $dia_semana,
                ':hora_inicio' => $hora_inicio,
                ':hora_fin' => $hora_fin,
                ':id_seccion' => $id_seccion,
                ':id_docente' => $id_docente,
                ':id_aula' => $id_aula
            ]);

            $conflictos = $stmtConflictos->fetchAll();

            foreach ($conflictos as $conflicto) {
                if ($conflicto['id_seccion'] == $id_seccion) {
                    setFlash('error', 'La sección ya tiene una clase asignada en ese horario y día.');
                    redirect('admin/horarios');
                    return;
                }
                if ($conflicto['id_docente'] == $id_docente) {
                    setFlash('error', 'El docente ya tiene una clase asignada en ese horario y día.');
                    redirect('admin/horarios');
                    return;
                }
                if ($conflicto['id_aula'] == $id_aula) {
                    setFlash('error', 'El aula ya está ocupada en ese horario y día.');
                    redirect('admin/horarios');
                    return;
                }
            }
            $stmt = $this->conexion->prepare("
                INSERT INTO unexca.horarios (
                    id_asignatura, id_seccion, id_docente, id_aula, id_turno, 
                    id_trayecto, cod_horario, hora_inicio, hora_fin, dia_semana
                ) VALUES (
                    :id_asignatura, :id_seccion, :id_docente, :id_aula, :id_turno, 
                    :id_trayecto, :cod_horario, :hora_inicio, :hora_fin, :dia_semana
                )
            ");
            $stmt->execute([
                ':id_asignatura' => $id_asignatura ?: null,
                ':id_seccion'    => $id_seccion ?: null,
                ':id_docente'    => $id_docente ?: null,
                ':id_aula'       => $id_aula ?: null,
                ':id_turno'      => $id_turno ?: null,
                ':id_trayecto'   => $id_trayecto ?: null,
                ':cod_horario'   => $cod_horario,
                ':hora_inicio'   => $hora_inicio,
                ':hora_fin'      => $hora_fin,
                ':dia_semana'    => $dia_semana,
            ]);

            setFlash('success', 'Horario guardado exitosamente.');
        } catch (Exception $e) {
            setFlash('error', 'Error al guardar horario: ' . $e->getMessage());
        }

        redirect('admin/horarios');
    }

    public function eliminar(int $id): void
    {
        try {
            $stmt = $this->conexion->prepare("DELETE FROM unexca.horarios WHERE id_horario = :id");
            $stmt->execute([':id' => $id]);
            setFlash('success', 'Horario eliminado correctamente.');
        } catch (Exception $e) {
            setFlash('error', 'Error al eliminar el horario.');
        }
        redirect('admin/horarios');
    }
}
