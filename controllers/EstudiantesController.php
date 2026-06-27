<?php
/**
 * Controlador para la gestión de Estudiantes desde Administración
 */

class EstudiantesController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function index(): void
    {
        // Leer filtros
        $buscar = $_GET['buscar'] ?? '';
        $pnf_id = $_GET['pnf'] ?? '';
        $sede_id = $_GET['sede'] ?? '';

        // Obtener listado de estudiantes con filtros
        $sql = "SELECT e.id_estudiante, e.fecha_ingreso, e.id_pnf, e.id_trayecto, e.id_sede, e.id_estatus,
                       p.nombres, p.apellidos, p.cedula_identidad, p.correo_personal,
                       pnf.nombre_pnf, s.nombre_sede, t.descripcion as trayecto,
                       est.nombre_estatus
                FROM unexca.datos_estudiantes e
                JOIN unexca.datos_personas p ON p.id_persona = e.id_persona
                JOIN unexca.pnf pnf ON pnf.id_pnf = e.id_pnf
                JOIN unexca.sedes_unexca s ON s.id_sede = e.id_sede
                JOIN unexca.trayectos t ON t.id_trayecto = e.id_trayecto
                LEFT JOIN unexca.estatus est ON est.id_estatus = e.id_estatus
                WHERE 1=1";
        
        $params = [];

        if (!empty($buscar)) {
            $sql .= " AND (p.cedula_identidad::text ILIKE :buscar OR p.nombres ILIKE :buscar OR p.apellidos ILIKE :buscar)";
            $params[':buscar'] = "%{$buscar}%";
        }
        if (!empty($pnf_id)) {
            $sql .= " AND e.id_pnf = :pnf";
            $params[':pnf'] = $pnf_id;
        }
        if (!empty($sede_id)) {
            $sql .= " AND e.id_sede = :sede";
            $params[':sede'] = $sede_id;
        }

        $sql .= " ORDER BY e.id_estudiante DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        $estudiantes = $stmt->fetchAll();

        // Obtener catálogos para el formulario
        $pnfs = $this->conexion->query("SELECT * FROM unexca.pnf ORDER BY nombre_pnf")->fetchAll();
        $sedes = $this->conexion->query("SELECT * FROM unexca.sedes_unexca ORDER BY nombre_sede")->fetchAll();
        $trayectos = $this->conexion->query("SELECT * FROM unexca.trayectos ORDER BY id_trayecto")->fetchAll();
        $estatus_list = $this->conexion->query("SELECT * FROM unexca.estatus WHERE nombre_estatus IN ('Cursando', 'Graduado', 'Egresado', 'Retirado', 'Activo') ORDER BY id_estatus")->fetchAll();

        // Estadísticas para el Dashboard
        $stats = [
            'total' => $this->conexion->query("SELECT COUNT(*) FROM unexca.datos_estudiantes")->fetchColumn(),
            'por_pnf' => $this->conexion->query("
                SELECT p.nombre_pnf, COUNT(e.id_estudiante) as total 
                FROM unexca.pnf p 
                LEFT JOIN unexca.datos_estudiantes e ON e.id_pnf = p.id_pnf 
                GROUP BY p.id_pnf, p.nombre_pnf
            ")->fetchAll(),
            'por_sede' => $this->conexion->query("
                SELECT s.nombre_sede, COUNT(e.id_estudiante) as total 
                FROM unexca.sedes_unexca s 
                LEFT JOIN unexca.datos_estudiantes e ON e.id_sede = s.id_sede 
                GROUP BY s.id_sede, s.nombre_sede
            ")->fetchAll()
        ];

        renderView('admin/estudiantes/index', [
            'titulo'      => 'Gestión de Estudiantes',
            'estudiantes' => $estudiantes,
            'pnfs'        => $pnfs,
            'sedes'       => $sedes,
            'trayectos'   => $trayectos,
            'estatus_list'=> $estatus_list,
            'stats'       => $stats,
            'flash'       => getFlash()
        ]);
    }

    public function crear(): void
    {
        $id_persona = $_POST['id_persona'] ?? '';
        $id_pnf = $_POST['id_pnf'] ?? '';
        $id_sede = $_POST['id_sede'] ?? '';
        $id_trayecto = $_POST['id_trayecto'] ?? '';
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';

        if (empty($id_persona) || empty($id_pnf) || empty($id_sede) || empty($id_trayecto) || empty($fecha_ingreso)) {
            setFlash('error', 'Todos los campos académicos son obligatorios.');
            redirect('admin/estudiantes');
            return;
        }

        try {
            // Buscamos el ID de Activo
            $id_cursando = $this->conexion->query("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = 'Activo' LIMIT 1")->fetchColumn();
            
            $stmt = $this->conexion->prepare("INSERT INTO unexca.datos_estudiantes (id_persona, id_pnf, id_sede, id_trayecto, fecha_ingreso, id_estatus) VALUES (:id_persona, :id_pnf, :id_sede, :id_trayecto, :fecha_ingreso, :id_estatus)");
            $stmt->execute([
                ':id_persona' => $id_persona,
                ':id_pnf' => $id_pnf,
                ':id_sede' => $id_sede,
                ':id_trayecto' => $id_trayecto,
                ':fecha_ingreso' => $fecha_ingreso,
                ':id_estatus' => $id_cursando ?: null
            ]);
            setFlash('success', 'Estudiante registrado exitosamente.');
        } catch (PDOException $e) {
            // Verificar si el error es de clave única (ya es estudiante)
            if ($e->getCode() == 23505 || strpos($e->getMessage(), 'Duplicate entry') !== false || strpos($e->getMessage(), 'unique') !== false) {
                setFlash('error', 'Esta persona ya está registrada como estudiante.');
            } else {
                setFlash('error', 'Error al registrar estudiante: ' . $e->getMessage());
            }
        }

        redirect('admin/estudiantes');
    }

    public function actualizar(int $id): void
    {
        $id_pnf = $_POST['id_pnf'] ?? '';
        $id_sede = $_POST['id_sede'] ?? '';
        $id_trayecto = $_POST['id_trayecto'] ?? '';
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';
        $id_estatus = $_POST['id_estatus'] ?? null;

        if (empty($id_pnf) || empty($id_sede) || empty($id_trayecto) || empty($fecha_ingreso) || empty($id_estatus)) {
            setFlash('error', 'Todos los campos son obligatorios.');
            redirect('admin/estudiantes');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("
                UPDATE unexca.datos_estudiantes 
                SET id_pnf = :id_pnf, 
                    id_sede = :id_sede, 
                    id_trayecto = :id_trayecto,
                    fecha_ingreso = :fecha_ingreso,
                    id_estatus = :id_estatus,
                    actualizado_en = CURRENT_TIMESTAMP
                WHERE id_estudiante = :id
            ");
            $stmt->execute([
                ':id_pnf' => $id_pnf,
                ':id_sede' => $id_sede,
                ':id_trayecto' => $id_trayecto,
                ':fecha_ingreso' => $fecha_ingreso,
                ':id_estatus' => $id_estatus,
                ':id' => $id
            ]);
            setFlash('success', 'Datos del estudiante actualizados exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al actualizar estudiante. ' . $e->getMessage());
        }

        redirect('admin/estudiantes');
    }

    public function ver(int $id): void
    {
        // 1. Obtener info personal y académica básica
        $stmtInfo = $this->conexion->prepare("
            SELECT e.id_estudiante, e.fecha_ingreso, e.id_estatus, est.nombre_estatus as estatus_academico,
                   p.nombres, p.apellidos, p.cedula_identidad, p.correo_personal, p.telefono_personal,
                   pnf.nombre_pnf, s.nombre_sede, t.descripcion as trayecto
            FROM unexca.datos_estudiantes e
            JOIN unexca.datos_personas p ON p.id_persona = e.id_persona
            JOIN unexca.pnf pnf ON pnf.id_pnf = e.id_pnf
            JOIN unexca.sedes_unexca s ON s.id_sede = e.id_sede
            JOIN unexca.trayectos t ON t.id_trayecto = e.id_trayecto
            LEFT JOIN unexca.estatus est ON est.id_estatus = e.id_estatus
            WHERE e.id_estudiante = :id
        ");
        $stmtInfo->execute([':id' => $id]);
        $estudiante = $stmtInfo->fetch();

        if (!$estudiante) {
            setFlash('error', 'Estudiante no encontrado.');
            redirect('admin/estudiantes');
            return;
        }

        // 2. Obtener Carga Académica Actual (Inscripciones del período vigente)
        // Por simplicidad, tomamos el período activo
        $stmtPeriodo = $this->conexion->query("SELECT id_periodo, periodo as nombre_periodo FROM unexca.periodo_academico WHERE estado = TRUE ORDER BY id_periodo DESC LIMIT 1");
        $periodoActual = $stmtPeriodo->fetch();

        $cargaActual = [];
        if ($periodoActual) {
            $stmtCarga = $this->conexion->prepare("
                SELECT a.nombre as nombre_asignatura, a.codigo as codigo_asignatura, sec.cod_seccion as codigo_seccion, est.nombre_estatus
                FROM unexca.inscripcion_asignatura ia
                JOIN unexca.carga_academica ca ON ca.id_carga = ia.id_carga
                JOIN unexca.asignatura a ON a.id_asignatura = ca.id_asignatura
                JOIN unexca.secciones sec ON sec.id_seccion = ca.id_seccion
                JOIN unexca.estatus est ON est.id_estatus = ia.id_estatus
                WHERE ia.id_estudiante = :id AND ca.id_periodo = :periodo
            ");
            $stmtCarga->execute([':id' => $id, ':periodo' => $periodoActual['id_periodo']]);
            $cargaActual = $stmtCarga->fetchAll();
        }

        // 3. Histórico Académico (Notas definitivas)
        $stmtHistorico = $this->conexion->prepare("
            SELECT a.nombre as nombre_asignatura, a.codigo as codigo_asignatura, pa.periodo as nombre_periodo, h.nota_definitiva, h.intento, est.nombre_estatus
            FROM unexca.historico_academico h
            JOIN unexca.asignatura a ON a.id_asignatura = h.id_asignatura
            JOIN unexca.periodo_academico pa ON pa.id_periodo = h.id_periodo
            JOIN unexca.estatus est ON est.id_estatus = h.id_estatus
            WHERE h.id_estudiante = :id
            ORDER BY pa.id_periodo DESC, a.nombre ASC
        ");
        $stmtHistorico->execute([':id' => $id]);
        $historico = $stmtHistorico->fetchAll();

        // Calcular unidades de crédito e índice (simplificado por ahora basado en el histórico si las UC estuvieran pero lo tomaremos general)
        // El histórico tiene `unidades_credito`.
        $uc_aprobadas = 0;
        foreach ($historico as $h) {
            if ($h['nota_definitiva'] >= 10) { // Aprobado
                $uc_aprobadas += 3; // Simplificación temporal si no está en el join
            }
        }

        renderView('admin/estudiantes/ver', [
            'titulo' => 'Perfil del Estudiante',
            'estudiante' => $estudiante,
            'periodoActual' => $periodoActual,
            'cargaActual' => $cargaActual,
            'historico' => $historico,
            'uc_aprobadas' => $uc_aprobadas
        ]);
    }
}
