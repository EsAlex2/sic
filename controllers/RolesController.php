<?php
/**
 * Controlador para la gestión de Roles (tipos_usuario)
 */

class RolesController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function index(): void
    {
        $sql = "SELECT r.*, e.nombre_estatus 
                FROM unexca.tipos_usuario r
                JOIN unexca.estatus e ON e.id_estatus = r.id_estatus
                ORDER BY r.id_tipo ASC";
        $stmt = $this->conexion->query($sql);
        $roles = $stmt->fetchAll();

        // Obtener estatus posibles (excepto 'Suspendido' tal vez? o todos)
        $stmtEst = $this->conexion->query("SELECT * FROM unexca.estatus WHERE nombre_estatus IN ('Activo', 'Inactivo', 'Suspendido') ORDER BY id_estatus");
        $estatusList = $stmtEst->fetchAll();

        renderView('admin/roles/index', [
            'titulo'      => 'Gestión de Roles',
            'roles'       => $roles,
            'estatusList' => $estatusList,
            'flash'       => getFlash()
        ]);
    }

    public function crear(): void
    {
        $nombre_tipo = trim($_POST['nombre_tipo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (empty($nombre_tipo)) {
            setFlash('error', 'El nombre del rol es obligatorio.');
            redirect('admin/roles');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("INSERT INTO unexca.tipos_usuario (nombre_tipo, descripcion, id_estatus) VALUES (:nombre, :descripcion, 1)");
            $stmt->execute([
                ':nombre' => $nombre_tipo,
                ':descripcion' => $descripcion
            ]);
            setFlash('success', 'Rol creado exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al crear rol (¿El nombre ya existe?). ' . $e->getMessage());
        }

        redirect('admin/roles');
    }

    public function actualizar(int $id): void
    {
        $nombre_tipo = trim($_POST['nombre_tipo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $id_estatus = $_POST['id_estatus'] ?? '';

        if (empty($nombre_tipo) || empty($id_estatus)) {
            setFlash('error', 'El nombre y el estatus son obligatorios.');
            redirect('admin/roles');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("
                UPDATE unexca.tipos_usuario 
                SET nombre_tipo = :nombre, 
                    descripcion = :descripcion, 
                    id_estatus = :id_estatus 
                WHERE id_tipo = :id
            ");
            $stmt->execute([
                ':nombre' => $nombre_tipo,
                ':descripcion' => $descripcion,
                ':id_estatus' => $id_estatus,
                ':id' => $id
            ]);
            setFlash('success', 'Rol actualizado exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al actualizar rol. ' . $e->getMessage());
        }

        redirect('admin/roles');
    }

    public function toggleEstatus(int $id): void
    {
        try {
            $stmt = $this->conexion->prepare("
                SELECT tu.id_tipo, e.nombre_estatus 
                FROM unexca.tipos_usuario tu
                JOIN unexca.estatus e ON e.id_estatus = tu.id_estatus
                WHERE tu.id_tipo = :id
            ");
            $stmt->execute([':id' => $id]);
            $rol = $stmt->fetch();

            if ($rol) {
                // Alternar entre Activo (1) e Inactivo/Suspendido (buscar el ID)
                $nuevoNombre = ($rol['nombre_estatus'] === 'Activo') ? 'Inactivo' : 'Activo';
                
                $stmtEstatus = $this->conexion->prepare("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = :nombre LIMIT 1");
                $stmtEstatus->execute([':nombre' => $nuevoNombre]);
                $nuevoEstatusId = $stmtEstatus->fetch()['id_estatus'];

                $updateStmt = $this->conexion->prepare("UPDATE unexca.tipos_usuario SET id_estatus = :estatus WHERE id_tipo = :id");
                $updateStmt->execute([
                    ':estatus' => $nuevoEstatusId,
                    ':id' => $id
                ]);

                setFlash('success', "Estatus cambiado a {$nuevoNombre}");
            }
        } catch (PDOException $e) {
            setFlash('error', 'Error al cambiar estatus: ' . $e->getMessage());
        }

        redirect('admin/roles');
    }
}
