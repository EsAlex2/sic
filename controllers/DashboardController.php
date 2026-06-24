<?php
/**
 * Controlador de Dashboard
 * Redirige al dashboard correcto según el rol del usuario
 */

require_once ROOT_PATH . '/helpers/Session.php';

class DashboardController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function index(): void
    {
        $usuario = Session::obtenerUsuario();
        $rol = $usuario['rol'];

        switch ($rol) {
            case ROL_ADMIN:
                $this->dashboardAdmin();
                break;
            case ROL_DOCENTE:
                $this->dashboardDocente();
                break;
            case ROL_ESTUDIANTE:
                $this->dashboardEstudiante();
                break;
            case ROL_CONTROL_ESTUDIOS:
                $this->dashboardControlEstudios();
                break;
            case ROL_FINANZAS:
                $this->dashboardFinanzas();
                break;
            default:
                redirect('login');
        }
    }

    private function dashboardAdmin(): void
    {
        // Estadísticas generales
        $stats = [];

        $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM unexca.datos_estudiantes");
        $stats['total_estudiantes'] = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM unexca.datos_docentes");
        $stats['total_docentes'] = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM unexca.usuarios");
        $stats['total_usuarios'] = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM unexca.periodo_academico WHERE estado = TRUE");
        $stats['periodos_activos'] = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM unexca.carga_academica");
        $stats['total_cargas'] = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM unexca.pnf");
        $stats['total_pnf'] = $stmt->fetch()['total'] ?? 0;

        renderView('dashboard/admin', [
            'titulo'  => 'Panel de Administración',
            'stats'   => $stats,
            'usuario' => Session::obtenerUsuario(),
            'flash'   => getFlash(),
        ]);
    }

    private function dashboardDocente(): void
    {
        $idUsuario = Session::obtenerIdUsuario();

        // Obtener el id_docente del usuario
        $stmt = $this->conexion->prepare("
            SELECT dd.id_docente FROM unexca.datos_docentes dd
            JOIN unexca.datos_personas dp ON dp.id_persona = dd.id_persona
            JOIN unexca.usuarios u ON u.id_persona = dp.id_persona
            WHERE u.id_usuario = :id_usuario
        ");
        $stmt->execute([':id_usuario' => $idUsuario]);
        $docente = $stmt->fetch();

        $stats = ['total_cargas' => 0, 'total_estudiantes' => 0, 'actas_pendientes' => 0];

        if ($docente) {
            $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM unexca.carga_academica WHERE id_docente = :id");
            $stmt->execute([':id' => $docente['id_docente']]);
            $stats['total_cargas'] = $stmt->fetch()['total'] ?? 0;

            $stmt = $this->conexion->prepare("
                SELECT COUNT(DISTINCT ia.id_estudiante) as total
                FROM unexca.inscripcion_asignatura ia
                JOIN unexca.carga_academica ca ON ca.id_carga = ia.id_carga
                WHERE ca.id_docente = :id
            ");
            $stmt->execute([':id' => $docente['id_docente']]);
            $stats['total_estudiantes'] = $stmt->fetch()['total'] ?? 0;
        }

        renderView('dashboard/docente', [
            'titulo'  => 'Panel del Docente',
            'stats'   => $stats,
            'usuario' => Session::obtenerUsuario(),
            'flash'   => getFlash(),
        ]);
    }

    private function dashboardEstudiante(): void
    {
        $idUsuario = Session::obtenerIdUsuario();
        $stats = ['materias_inscritas' => 0, 'promedio_actual' => '0.00', 'creditos_aprobados' => 0];

        $stmt = $this->conexion->prepare("
            SELECT de.id_estudiante FROM unexca.datos_estudiantes de
            JOIN unexca.datos_personas dp ON dp.id_persona = de.id_persona
            JOIN unexca.usuarios u ON u.id_persona = dp.id_persona
            WHERE u.id_usuario = :id_usuario
        ");
        $stmt->execute([':id_usuario' => $idUsuario]);
        $estudiante = $stmt->fetch();

        if ($estudiante) {
            $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM unexca.inscripcion_asignatura WHERE id_estudiante = :id");
            $stmt->execute([':id' => $estudiante['id_estudiante']]);
            $stats['materias_inscritas'] = $stmt->fetch()['total'] ?? 0;

            $stmt = $this->conexion->prepare("SELECT promedio_acumulado, creditos_aprobados FROM unexca.indice_academico WHERE id_estudiante = :id ORDER BY id_periodo DESC LIMIT 1");
            $stmt->execute([':id' => $estudiante['id_estudiante']]);
            $indice = $stmt->fetch();
            if ($indice) {
                $stats['promedio_actual'] = number_format($indice['promedio_acumulado'], 2);
                $stats['creditos_aprobados'] = $indice['creditos_aprobados'];
            }
        }

        renderView('dashboard/estudiante', [
            'titulo'  => 'Portal del Estudiante',
            'stats'   => $stats,
            'usuario' => Session::obtenerUsuario(),
            'flash'   => getFlash(),
        ]);
    }

    private function dashboardControlEstudios(): void
    {
        $stats = [];
        $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM unexca.inscripcion_nue_ingreso");
        $stats['inscripciones'] = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM unexca.actas_notas");
        $stats['actas'] = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM unexca.examenes_reparacion");
        $stats['reparaciones'] = $stmt->fetch()['total'] ?? 0;

        renderView('dashboard/control_estudios', [
            'titulo'  => 'Panel de Control de Estudios',
            'stats'   => $stats,
            'usuario' => Session::obtenerUsuario(),
            'flash'   => getFlash(),
        ]);
    }

    private function dashboardFinanzas(): void
    {
        $stats = [];
        $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM unexca.pagos");
        $stats['total_pagos'] = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM unexca.aranceles");
        $stats['total_aranceles'] = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->conexion->query("SELECT COALESCE(SUM(a.monto), 0) as total FROM unexca.pagos p JOIN unexca.aranceles a ON a.id_arancel = p.id_arancel");
        $stats['monto_recaudado'] = number_format($stmt->fetch()['total'] ?? 0, 2, ',', '.');

        renderView('dashboard/finanzas', [
            'titulo'  => 'Panel de Finanzas',
            'stats'   => $stats,
            'usuario' => Session::obtenerUsuario(),
            'flash'   => getFlash(),
        ]);
    }
}
