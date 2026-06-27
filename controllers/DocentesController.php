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
        $sql = "SELECT d.id_docente, d.fecha_ingreso,
                       p.nombres, p.apellidos, p.cedula_identidad, p.correo_personal,
                       pnf.nombre_pnf, s.nombre_sede
                FROM unexca.datos_docentes d
                JOIN unexca.datos_personas p ON p.id_persona = d.id_persona
                JOIN unexca.pnf pnf ON pnf.id_pnf = d.id_pnf
                JOIN unexca.sedes_unexca s ON s.id_sede = d.id_sede
                ORDER BY d.id_docente DESC";
        $stmt = $this->conexion->query($sql);
        $docentes = $stmt->fetchAll();

        // Obtener catálogos para el formulario
        $pnfs = $this->conexion->query("SELECT * FROM unexca.pnf ORDER BY nombre_pnf")->fetchAll();
        $sedes = $this->conexion->query("SELECT * FROM unexca.sedes_unexca ORDER BY nombre_sede")->fetchAll();

        renderView('admin/docentes/index', [
            'titulo'   => 'Gestión de Docentes',
            'docentes' => $docentes,
            'pnfs'     => $pnfs,
            'sedes'    => $sedes,
            'flash'    => getFlash()
        ]);
    }

    public function crear(): void
    {
        $id_persona = $_POST['id_persona'] ?? '';
        $id_pnf = $_POST['id_pnf'] ?? '';
        $id_sede = $_POST['id_sede'] ?? '';
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';

        if (empty($id_persona) || empty($id_pnf) || empty($id_sede) || empty($fecha_ingreso)) {
            setFlash('error', 'Todos los campos académicos son obligatorios.');
            redirect('admin/docentes');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("INSERT INTO unexca.datos_docentes (id_persona, id_pnf, id_sede, fecha_ingreso) VALUES (:id_persona, :id_pnf, :id_sede, :fecha_ingreso)");
            $stmt->execute([
                ':id_persona' => $id_persona,
                ':id_pnf' => $id_pnf,
                ':id_sede' => $id_sede,
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
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';

        if (empty($id_pnf) || empty($id_sede) || empty($fecha_ingreso)) {
            setFlash('error', 'Todos los campos son obligatorios.');
            redirect('admin/docentes');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("
                UPDATE unexca.datos_docentes 
                SET id_pnf = :id_pnf, 
                    id_sede = :id_sede, 
                    fecha_ingreso = :fecha_ingreso,
                    actualizado_en = CURRENT_TIMESTAMP
                WHERE id_docente = :id
            ");
            $stmt->execute([
                ':id_pnf' => $id_pnf,
                ':id_sede' => $id_sede,
                ':fecha_ingreso' => $fecha_ingreso,
                ':id' => $id
            ]);
            setFlash('success', 'Datos del docente actualizados exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al actualizar docente. ' . $e->getMessage());
        }

        redirect('admin/docentes');
    }
}
