<?php
class ReparacionController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function listar(): void
    {
        $usuario = Session::obtenerUsuario();
        
        if ($usuario['rol'] === ROL_DOCENTE) {
            // El docente ve las reparaciones que debe calificar de sus alumnos
            $stmt = $this->conexion->prepare("
                SELECT r.*, 
                       pa.codigo as periodo,
                       dp.nombres, dp.apellidos,
                       da.nombre as asignatura
                FROM unexca.examenes_reparacion r
                JOIN unexca.inscripcion_asignatura ia ON ia.id_inscripcion_asig = r.id_inscripcion_asig
                JOIN unexca.datos_estudiantes de ON de.id_estudiante = ia.id_estudiante
                JOIN unexca.datos_personas dp ON dp.id_persona = de.id_persona
                JOIN unexca.carga_academica ca ON ca.id_carga = ia.id_carga
                JOIN unexca.datos_asignaturas da ON da.id_asignatura = ca.id_asignatura
                JOIN unexca.periodo_academico pa ON pa.id_periodo = r.id_periodo
                JOIN unexca.datos_docentes doc ON doc.id_docente = ca.id_docente
                JOIN unexca.usuarios u ON u.id_persona = doc.id_persona
                WHERE u.id_usuario = :id_usuario
                ORDER BY r.id_reparacion DESC
            ");
            $stmt->execute([':id_usuario' => $usuario['id_usuario']]);
            $reparaciones = $stmt->fetchAll();
            
            renderView('control/reparaciones_docente', [
                'titulo' => 'Exámenes de Reparación a Calificar',
                'reparaciones' => $reparaciones,
                'flash' => getFlash()
            ]);
        } else {
            // Admin o Control de Estudios ven todas para gestionarlas
            $stmt = $this->conexion->query("
                SELECT r.*, 
                       pa.codigo as periodo,
                       dp.nombres, dp.apellidos, de.id_estudiante,
                       da.nombre as asignatura
                FROM unexca.examenes_reparacion r
                JOIN unexca.inscripcion_asignatura ia ON ia.id_inscripcion_asig = r.id_inscripcion_asig
                JOIN unexca.datos_estudiantes de ON de.id_estudiante = ia.id_estudiante
                JOIN unexca.datos_personas dp ON dp.id_persona = de.id_persona
                JOIN unexca.carga_academica ca ON ca.id_carga = ia.id_carga
                JOIN unexca.datos_asignaturas da ON da.id_asignatura = ca.id_asignatura
                JOIN unexca.periodo_academico pa ON pa.id_periodo = r.id_periodo
                ORDER BY r.id_reparacion DESC
            ");
            $reparaciones = $stmt->fetchAll();

            renderView('control/reparaciones', [
                'titulo' => 'Gestión de Reparaciones',
                'reparaciones' => $reparaciones,
                'flash' => getFlash()
            ]);
        }
    }

    public function solicitar(): void
    {
        setFlash('success', 'Solicitud de reparación registrada.');
        redirect('reparaciones');
    }

    public function calificar(int $id): void
    {
        setFlash('success', 'Calificación de reparación guardada.');
        redirect('reparaciones');
    }
}
