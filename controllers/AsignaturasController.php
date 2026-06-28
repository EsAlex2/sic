<?php
/**
 * Controlador para la gestión de Asignaturas
 */

class AsignaturasController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function index(): void
    {
        // Obtener listado de asignaturas con PNF y Trayecto
        $sql = "SELECT a.*, p.nombre_pnf, t.descripcion as trayecto_desc
                FROM unexca.asignatura a
                LEFT JOIN unexca.pnf p ON p.id_pnf = a.id_pnf
                LEFT JOIN unexca.trayectos t ON t.id_trayecto = a.id_trayecto
                ORDER BY a.id_asignatura DESC";
        $stmt = $this->conexion->query($sql);
        $asignaturas = $stmt->fetchAll();

        // Catálogos para el modal
        $pnfs = $this->conexion->query("SELECT * FROM unexca.pnf ORDER BY nombre_pnf")->fetchAll();
        $trayectos = $this->conexion->query("SELECT * FROM unexca.trayectos ORDER BY id_trayecto")->fetchAll();

        // Calcular Estadísticas para el Dashboard
        $stats = [
            'total' => count($asignaturas),
            'promedio_uc' => 0,
            'por_pnf' => [],
            'por_trayecto' => []
        ];

        $totalUC = 0;
        foreach ($asignaturas as $a) {
            $pnf = $a['nombre_pnf'] ?? 'Sin PNF';
            $trayecto = $a['trayecto_desc'] ?? 'Sin Trayecto';
            $uc = (int)($a['unidades_credito'] ?? 0);

            $stats['por_pnf'][$pnf] = ($stats['por_pnf'][$pnf] ?? 0) + 1;
            $stats['por_trayecto'][$trayecto] = ($stats['por_trayecto'][$trayecto] ?? 0) + 1;
            $totalUC += $uc;
        }

        if (count($asignaturas) > 0) {
            $stats['promedio_uc'] = round($totalUC / count($asignaturas), 1);
        }

        renderView('admin/asignaturas/index', [
            'titulo'      => 'Gestión de Asignaturas',
            'asignaturas' => $asignaturas,
            'pnfs'        => $pnfs,
            'trayectos'   => $trayectos,
            'stats'       => $stats,
            'flash'       => getFlash()
        ]);
    }

    public function crear(): void
    {
        $codigo = trim($_POST['codigo'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $id_pnf = $_POST['id_pnf'] ?? null;
        $id_trayecto = $_POST['id_trayecto'] ?? null;
        $unidades_credito = $_POST['unidades_credito'] ?? 0;
        $id_caracter = $_POST['id_caracter'] ?? 1;

        if (empty($codigo) || empty($nombre) || empty($id_pnf) || empty($id_trayecto)) {
            setFlash('error', 'El código, nombre, PNF y trayecto son obligatorios.');
            redirect('admin/asignaturas');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("
                INSERT INTO unexca.asignatura (codigo, nombre, id_pnf, id_trayecto, unidades_credito, id_caracter) 
                VALUES (:codigo, :nombre, :id_pnf, :id_trayecto, :uc, :caracter)
            ");
            $stmt->execute([
                ':codigo' => $codigo,
                ':nombre' => $nombre,
                ':id_pnf' => $id_pnf,
                ':id_trayecto' => $id_trayecto,
                ':uc' => $unidades_credito,
                ':caracter' => $id_caracter
            ]);
            setFlash('success', 'Asignatura registrada exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al registrar asignatura. ' . $e->getMessage());
        }

        redirect('admin/asignaturas');
    }

    public function actualizar(int $id): void
    {
        $codigo = trim($_POST['codigo'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $id_pnf = $_POST['id_pnf'] ?? null;
        $id_trayecto = $_POST['id_trayecto'] ?? null;
        $unidades_credito = $_POST['unidades_credito'] ?? 0;
        $id_caracter = $_POST['id_caracter'] ?? 1;

        if (empty($codigo) || empty($nombre) || empty($id_pnf) || empty($id_trayecto)) {
            setFlash('error', 'El código, nombre, PNF y trayecto son obligatorios.');
            redirect('admin/asignaturas');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("
                UPDATE unexca.asignatura 
                SET codigo = :codigo, 
                    nombre = :nombre, 
                    id_pnf = :id_pnf, 
                    id_trayecto = :id_trayecto,
                    unidades_credito = :uc,
                    id_caracter = :caracter,
                    actualizado_en = CURRENT_TIMESTAMP
                WHERE id_asignatura = :id
            ");
            $stmt->execute([
                ':codigo' => $codigo,
                ':nombre' => $nombre,
                ':id_pnf' => $id_pnf,
                ':id_trayecto' => $id_trayecto,
                ':uc' => $unidades_credito,
                ':caracter' => $id_caracter,
                ':id' => $id
            ]);
            setFlash('success', 'Asignatura actualizada exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al actualizar asignatura. ' . $e->getMessage());
        }

        redirect('admin/asignaturas');
    }

    public function ver(int $id): void
    {
        $stmt = $this->conexion->prepare("
            SELECT a.*, p.nombre_pnf, t.descripcion as trayecto_desc
            FROM unexca.asignatura a
            LEFT JOIN unexca.pnf p ON p.id_pnf = a.id_pnf
            LEFT JOIN unexca.trayectos t ON t.id_trayecto = a.id_trayecto
            WHERE a.id_asignatura = :id
        ");
        $stmt->execute([':id' => $id]);
        $asignatura = $stmt->fetch();

        if (!$asignatura) {
            setFlash('error', 'Asignatura no encontrada.');
            redirect('admin/asignaturas');
            return;
        }

        // Obtener docentes que dictan esta asignatura
        $stmtDocentes = $this->conexion->prepare("
            SELECT p.cedula_identidad, p.nombres, p.apellidos, d.fecha_ingreso
            FROM unexca.datos_docentes d
            JOIN unexca.datos_personas p ON p.id_persona = d.id_persona
            WHERE d.id_asignatura = :id
            ORDER BY p.nombres ASC
        ");
        $stmtDocentes->execute([':id' => $id]);
        $docentes = $stmtDocentes->fetchAll();

        renderView('admin/asignaturas/ver', [
            'titulo' => 'Detalle de Asignatura',
            'asignatura' => $asignatura,
            'docentes' => $docentes
        ]);
    }
}
