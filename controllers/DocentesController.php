<?php
/**
 * Controlador para la gestión de Docentes desde Administración
 */

class DocentesController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function index(): void
    {
        // Obtener listado de docentes
        $sql = "SELECT d.id_docente, d.fecha_ingreso, d.id_pnf, d.id_sede, d.id_asignatura, d.creado_en, d.actualizado_en,
                       p.nombres, p.apellidos, p.cedula_identidad, p.correo_personal,
                       pnf.nombre_pnf, s.nombre_sede, a.nombre as nombre_asignatura
                FROM unexca.datos_docentes d
                JOIN unexca.datos_personas p ON p.id_persona = d.id_persona
                JOIN unexca.pnf pnf ON pnf.id_pnf = d.id_pnf
                JOIN unexca.sedes_unexca s ON s.id_sede = d.id_sede
                LEFT JOIN unexca.asignatura a ON a.id_asignatura = d.id_asignatura
                ORDER BY d.id_docente DESC";
        $stmt = $this->conexion->query($sql);
        $docentes = $stmt->fetchAll();

        // Obtener catálogos para el formulario
        $pnfs = $this->conexion->query("SELECT * FROM unexca.pnf ORDER BY nombre_pnf")->fetchAll();
        $sedes = $this->conexion->query("SELECT * FROM unexca.sedes_unexca ORDER BY nombre_sede")->fetchAll();
        $asignaturas = $this->conexion->query("SELECT * FROM unexca.asignatura ORDER BY nombre")->fetchAll();

        // Calcular Estadísticas para el Dashboard
        $stats = [
            'total' => count($docentes),
            'por_pnf' => [],
            'por_sede' => [],
            'por_asignatura' => []
        ];

        foreach ($docentes as $d) {
            $pnf = $d['nombre_pnf'] ?? 'Sin PNF';
            $sede = $d['nombre_sede'] ?? 'Sin Sede';
            $asig = $d['nombre_asignatura'] ?? 'Sin Asignatura';

            $stats['por_pnf'][$pnf] = ($stats['por_pnf'][$pnf] ?? 0) + 1;
            $stats['por_sede'][$sede] = ($stats['por_sede'][$sede] ?? 0) + 1;
            $stats['por_asignatura'][$asig] = ($stats['por_asignatura'][$asig] ?? 0) + 1;
        }

        renderView('admin/docentes/index', [
            'titulo'      => 'Gestión de Docentes',
            'docentes'    => $docentes,
            'pnfs'        => $pnfs,
            'sedes'       => $sedes,
            'asignaturas' => $asignaturas,
            'stats'       => $stats,
            'flash'       => getFlash()
        ]);
    }

    public function crear(): void
    {
        $id_persona = $_POST['id_persona'] ?? '';
        $id_pnf = $_POST['id_pnf'] ?? '';
        $id_sede = $_POST['id_sede'] ?? '';
        $id_asignatura = $_POST['id_asignatura'] ?? null;
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';

        if (empty($id_persona) || empty($id_pnf) || empty($id_sede) || empty($id_asignatura) || empty($fecha_ingreso)) {
            setFlash('error', 'Todos los campos académicos son obligatorios.');
            redirect('admin/docentes');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("INSERT INTO unexca.datos_docentes (id_persona, id_pnf, id_sede, id_asignatura, fecha_ingreso) VALUES (:id_persona, :id_pnf, :id_sede, :id_asignatura, :fecha_ingreso)");
            $stmt->execute([
                ':id_persona' => $id_persona,
                ':id_pnf' => $id_pnf,
                ':id_sede' => $id_sede,
                ':id_asignatura' => $id_asignatura,
                ':fecha_ingreso' => $fecha_ingreso
            ]);
            setFlash('success', 'Docente registrado exitosamente.');
        } catch (PDOException $e) {
            if ($e->getCode() == 23505 || strpos($e->getMessage(), 'Duplicate entry') !== false || strpos($e->getMessage(), 'unique') !== false) {
                setFlash('error', 'Esta persona ya está registrada como docente.');
            } else {
                setFlash('error', 'Error al registrar docente: ' . $e->getMessage());
            }
        }

        redirect('admin/docentes');
    }

    public function actualizar(int $id): void
    {
        $id_pnf = $_POST['id_pnf'] ?? '';
        $id_sede = $_POST['id_sede'] ?? '';
        $id_asignatura = $_POST['id_asignatura'] ?? null;
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';

        if (empty($id_pnf) || empty($id_sede) || empty($id_asignatura) || empty($fecha_ingreso)) {
            setFlash('error', 'Todos los campos son obligatorios.');
            redirect('admin/docentes');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("
                UPDATE unexca.datos_docentes 
                SET id_pnf = :id_pnf, 
                    id_sede = :id_sede, 
                    id_asignatura = :id_asignatura,
                    fecha_ingreso = :fecha_ingreso,
                    actualizado_en = CURRENT_TIMESTAMP
                WHERE id_docente = :id
            ");
            $stmt->execute([
                ':id_pnf' => $id_pnf,
                ':id_sede' => $id_sede,
                ':id_asignatura' => $id_asignatura,
                ':fecha_ingreso' => $fecha_ingreso,
                ':id' => $id
            ]);
            setFlash('success', 'Datos del docente actualizados exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al actualizar docente. ' . $e->getMessage());
        }

        redirect('admin/docentes');
    }

    public function ver(int $id): void
    {
        // 1. Obtener datos del docente y persona
        $stmt = $this->conexion->prepare("
            SELECT d.id_docente, d.fecha_ingreso,
                   p.cedula_identidad, p.nombres, p.apellidos, p.correo_personal, p.telefono_personal, p.genero,
                   pnf.nombre_pnf, s.nombre_sede, a.nombre as nombre_asignatura_principal, a.codigo as codigo_asignatura
            FROM unexca.datos_docentes d
            JOIN unexca.datos_personas p ON p.id_persona = d.id_persona
            JOIN unexca.pnf pnf ON pnf.id_pnf = d.id_pnf
            JOIN unexca.sedes_unexca s ON s.id_sede = d.id_sede
            LEFT JOIN unexca.asignatura a ON a.id_asignatura = d.id_asignatura
            WHERE d.id_docente = :id
        ");
        $stmt->execute([':id' => $id]);
        $docente = $stmt->fetch();

        if (!$docente) {
            setFlash('error', 'Docente no encontrado.');
            redirect('admin/docentes');
            return;
        }

        // 2. Obtener horarios (materias asignadas en clases reales)
        $stmtHorarios = $this->conexion->prepare("
            SELECT h.cod_horario, h.hora_inicio, h.hora_fin,
                   a.nombre as nombre_asignatura, a.codigo as codigo_asignatura,
                   sec.cod_seccion, au.nombre_aula, au.piso, au.nro_aula,
                   tr.descripcion as trayecto_desc
            FROM unexca.horarios h
            JOIN unexca.asignatura a ON a.id_asignatura = h.id_asignatura
            JOIN unexca.secciones sec ON sec.id_seccion = h.id_seccion
            JOIN unexca.aulas au ON au.id_aula = h.id_aula
            JOIN unexca.trayectos tr ON tr.id_trayecto = h.id_trayecto
            WHERE h.id_docente = :id
            ORDER BY h.hora_inicio ASC
        ");
        $stmtHorarios->execute([':id' => $id]);
        $horarios = $stmtHorarios->fetchAll();

        renderView('admin/docentes/ver', [
            'titulo' => 'Detalle del Docente',
            'docente' => $docente,
            'horarios' => $horarios
        ]);
    }
}
