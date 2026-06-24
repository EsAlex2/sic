<?php
class CargaController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function misCargas(): void
    {
        $idUsuario = Session::obtenerIdUsuario();

        // Obtener el id_docente del usuario
        $stmtDocente = $this->conexion->prepare("
            SELECT dd.id_docente FROM unexca.datos_docentes dd
            JOIN unexca.usuarios u ON u.id_persona = dd.id_persona
            WHERE u.id_usuario = :id_usuario
        ");
        $stmtDocente->execute([':id_usuario' => $idUsuario]);
        $docente = $stmtDocente->fetch();

        $cargas = [];
        if ($docente) {
            $stmtCargas = $this->conexion->prepare("
                SELECT ca.*,
                       pa.codigo as periodo,
                       da.nombre as asignatura,
                       ds.nombre as seccion,
                       s.nombre as sede,
                       (SELECT COUNT(*) FROM unexca.inscripcion_asignatura ia WHERE ia.id_carga = ca.id_carga) as inscritos
                FROM unexca.carga_academica ca
                JOIN unexca.periodo_academico pa ON pa.id_periodo = ca.id_periodo
                JOIN unexca.datos_asignaturas da ON da.id_asignatura = ca.id_asignatura
                JOIN unexca.datos_secciones ds ON ds.id_seccion = ca.id_seccion
                JOIN unexca.sedes_unexca s ON s.id_sede = ca.id_sede
                WHERE ca.id_docente = :id_docente
                  AND pa.estado = TRUE
                ORDER BY ca.id_carga DESC
            ");
            $stmtCargas->execute([':id_docente' => $docente['id_docente']]);
            $cargas = $stmtCargas->fetchAll();
        }

        renderView('docente/mis_cargas', [
            'titulo' => 'Mis Cargas Académicas',
            'cargas' => $cargas,
            'flash'  => getFlash()
        ]);
    }
}
