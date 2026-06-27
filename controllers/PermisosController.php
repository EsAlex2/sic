<?php
/**
 * Controlador para la gestión de Permisos (permisos)
 */

class PermisosController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function index(): void
    {
        $sql = "SELECT p.*, e.nombre_estatus, m.nombre_modulo
                FROM unexca.permisos p
                JOIN unexca.estatus e ON e.id_estatus = p.id_estatus
                LEFT JOIN unexca.modulos m ON m.id_modulo = p.id_modulo
                ORDER BY p.id_permiso ASC";
        $stmt = $this->conexion->query($sql);
        $permisos = $stmt->fetchAll();

        $stmtEst = $this->conexion->query("SELECT * FROM unexca.estatus WHERE nombre_estatus IN ('Activo', 'Inactivo', 'Suspendido') ORDER BY id_estatus");
        $estatusList = $stmtEst->fetchAll();
        
        $stmtMod = $this->conexion->query("SELECT * FROM unexca.modulos ORDER BY nombre_modulo");
        $modulos = $stmtMod->fetchAll();

        renderView('admin/permisos/index', [
            'titulo'      => 'Gestión de Permisos',
            'permisos'    => $permisos,
            'estatusList' => $estatusList,
            'modulos'     => $modulos,
            'flash'       => getFlash()
        ]);
    }

    public function crear(): void
    {
        $nombre_permiso = trim($_POST['nombre_permiso'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $id_modulo = $_POST['id_modulo'] ?? null;
        if (empty($id_modulo)) $id_modulo = null;

        if (empty($nombre_permiso)) {
            setFlash('error', 'El nombre del permiso es obligatorio.');
            redirect('admin/permisos');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("INSERT INTO unexca.permisos (nombre_permiso, descripcion, id_modulo, id_estatus) VALUES (:nombre, :descripcion, :id_modulo, 1)");
            $stmt->execute([
                ':nombre' => $nombre_permiso,
                ':descripcion' => $descripcion,
                ':id_modulo' => $id_modulo
            ]);
            setFlash('success', 'Permiso creado exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al crear permiso (¿El nombre ya existe?). ' . $e->getMessage());
        }

        redirect('admin/permisos');
    }

    public function actualizar(int $id): void
    {
        $nombre_permiso = trim($_POST['nombre_permiso'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $id_modulo = $_POST['id_modulo'] ?? null;
        if (empty($id_modulo)) $id_modulo = null;
        $id_estatus = $_POST['id_estatus'] ?? '';

        if (empty($nombre_permiso) || empty($id_estatus)) {
            setFlash('error', 'El nombre y el estatus son obligatorios.');
            redirect('admin/permisos');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("
                UPDATE unexca.permisos 
                SET nombre_permiso = :nombre, 
                    descripcion = :descripcion, 
                    id_modulo = :id_modulo,
                    id_estatus = :id_estatus 
                WHERE id_permiso = :id
            ");
            $stmt->execute([
                ':nombre' => $nombre_permiso,
                ':descripcion' => $descripcion,
                ':id_modulo' => $id_modulo,
                ':id_estatus' => $id_estatus,
                ':id' => $id
            ]);
            setFlash('success', 'Permiso actualizado exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al actualizar permiso. ' . $e->getMessage());
        }

        redirect('admin/permisos');
    }

    public function toggleEstatus(int $id): void
    {
        try {
            $stmt = $this->conexion->prepare("
                SELECT p.id_permiso, e.nombre_estatus 
                FROM unexca.permisos p
                JOIN unexca.estatus e ON e.id_estatus = p.id_estatus
                WHERE p.id_permiso = :id
            ");
            $stmt->execute([':id' => $id]);
            $permiso = $stmt->fetch();

            if ($permiso) {
                // Alternar entre Activo e Inactivo
                $nuevoNombre = ($permiso['nombre_estatus'] === 'Activo') ? 'Inactivo' : 'Activo';
                
                $stmtEstatus = $this->conexion->prepare("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = :nombre LIMIT 1");
                $stmtEstatus->execute([':nombre' => $nuevoNombre]);
                $nuevoEstatusId = $stmtEstatus->fetch()['id_estatus'];

                $updateStmt = $this->conexion->prepare("UPDATE unexca.permisos SET id_estatus = :estatus WHERE id_permiso = :id");
                $updateStmt->execute([
                    ':estatus' => $nuevoEstatusId,
                    ':id' => $id
                ]);

                setFlash('success', "Estatus cambiado a {$nuevoNombre}");
            }
        } catch (PDOException $e) {
            setFlash('error', 'Error al cambiar estatus: ' . $e->getMessage());
        }

        redirect('admin/permisos');
    }
}
