<?php
class PersonaController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function listar(): void
    {
        $stmt = $this->conexion->query("
            SELECT dp.*, e.nombre_estatus,
                   (SELECT COUNT(*) FROM unexca.usuarios u WHERE u.id_persona = dp.id_persona) as tiene_usuario
            FROM unexca.datos_personas dp
            JOIN unexca.estatus e ON e.id_estatus = dp.id_estatus
            ORDER BY dp.id_persona DESC
        ");
        $personas = $stmt->fetchAll();

        renderView('admin/personas', [
            'titulo' => 'Registro de Personas',
            'personas' => $personas,
            'flash' => getFlash()
        ]);
    }

    public function crear(): void
    {
        $cedula = $_POST['cedula_identidad'] ?? '';
        $nombres = $_POST['nombres'] ?? '';
        $apellidos = $_POST['apellidos'] ?? '';
        $genero = $_POST['genero'] ?? '';
        $fecha_nac = $_POST['fecha_nacimiento'] ?? '';
        $correo = $_POST['correo_personal'] ?? '';
        $telefono = $_POST['telefono_personal'] ?? '';
        $direccion = $_POST['direccion_habitacion'] ?? '';

        if (empty($cedula) || empty($nombres) || empty($apellidos) || empty($correo)) {
            setFlash('error', 'Faltan campos obligatorios.');
            redirect('admin/personas');
            return;
        }

        try {
            // Verificar si existe la cédula
            $check = $this->conexion->prepare("SELECT id_persona FROM unexca.datos_personas WHERE cedula_identidad = :cedula");
            $check->execute([':cedula' => $cedula]);
            if ($check->fetch()) {
                setFlash('error', 'La cédula ya está registrada.');
                redirect('admin/personas');
                return;
            }

            // Obtener estatus 'Activo'
            $stmtEstatus = $this->conexion->query("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = 'Activo' LIMIT 1");
            $idEstatus = $stmtEstatus->fetch()['id_estatus'] ?? 1;

            $stmt = $this->conexion->prepare("
                INSERT INTO unexca.datos_personas (
                    id_estatus, cedula_identidad, nombres, apellidos, genero, 
                    fecha_nacimiento, correo_personal, telefono_personal, direccion_habitacion, fecha_ingreso
                ) VALUES (
                    :id_estatus, :cedula, :nombres, :apellidos, :genero, 
                    :fecha_nac, :correo, :telefono, :direccion, CURRENT_DATE
                )
            ");
            $stmt->execute([
                ':id_estatus' => $idEstatus,
                ':cedula' => $cedula,
                ':nombres' => $nombres,
                ':apellidos' => $apellidos,
                ':genero' => $genero,
                ':fecha_nac' => $fecha_nac,
                ':correo' => $correo,
                ':telefono' => $telefono,
                ':direccion' => $direccion
            ]);

            setFlash('success', 'Persona registrada exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error en base de datos: ' . $e->getMessage());
        }

        redirect('admin/personas');
    }

    public function editar(int $id): void
    {
        $stmt = $this->conexion->prepare("
            SELECT dp.*, e.nombre_estatus
            FROM unexca.datos_personas dp
            JOIN unexca.estatus e ON e.id_estatus = dp.id_estatus
            WHERE dp.id_persona = :id
        ");
        $stmt->execute([':id' => $id]);
        $persona = $stmt->fetch();

        if (!$persona) {
            setFlash('error', 'Persona no encontrada.');
            redirect('admin/personas');
            return;
        }

        renderView('admin/persona_editar', [
            'titulo' => 'Editar Persona',
            'persona' => $persona,
            'flash' => getFlash()
        ]);
    }

    public function actualizar(int $id): void
    {
        $nombres = $_POST['nombres'] ?? '';
        $apellidos = $_POST['apellidos'] ?? '';
        $genero = $_POST['genero'] ?? '';
        $fecha_nac = $_POST['fecha_nacimiento'] ?? '';
        $correo = $_POST['correo_personal'] ?? '';
        $telefono = $_POST['telefono_personal'] ?? '';
        $direccion = $_POST['direccion_habitacion'] ?? '';

        if (empty($nombres) || empty($apellidos) || empty($correo)) {
            setFlash('error', 'Faltan campos obligatorios.');
            redirect('admin/personas/editar/' . $id);
            return;
        }

        try {
            $stmt = $this->conexion->prepare("
                UPDATE unexca.datos_personas SET
                    nombres = :nombres,
                    apellidos = :apellidos,
                    genero = :genero,
                    fecha_nacimiento = :fecha_nac,
                    correo_personal = :correo,
                    telefono_personal = :telefono,
                    direccion_habitacion = :direccion
                WHERE id_persona = :id
            ");
            $stmt->execute([
                ':nombres' => $nombres,
                ':apellidos' => $apellidos,
                ':genero' => $genero,
                ':fecha_nac' => $fecha_nac,
                ':correo' => $correo,
                ':telefono' => $telefono,
                ':direccion' => $direccion,
                ':id' => $id
            ]);

            setFlash('success', 'Datos actualizados exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al actualizar: ' . $e->getMessage());
        }

        redirect('admin/personas');
    }

    public function eliminar(int $id): void
    {
        try {
            // Obtener id_estatus de 'Inactivo'
            $stmtEstatus = $this->conexion->prepare("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = 'Inactivo' LIMIT 1");
            $stmtEstatus->execute();
            $estatus = $stmtEstatus->fetch();

            if (!$estatus) {
                setFlash('error', 'No se encontró el estatus "Inactivo" en el sistema.');
                redirect('admin/personas');
                return;
            }

            $stmt = $this->conexion->prepare("
                UPDATE unexca.datos_personas
                SET id_estatus = :id_estatus
                WHERE id_persona = :id
            ");
            $stmt->execute([
                ':id_estatus' => $estatus['id_estatus'],
                ':id' => $id
            ]);

            setFlash('success', 'Persona desactivada exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al desactivar: ' . $e->getMessage());
        }

        redirect('admin/personas');
    }

    public function activar(int $id): void
    {
        try {
            // Obtener id_estatus de 'Activo'
            $stmtEstatus = $this->conexion->prepare("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = 'Activo' LIMIT 1");
            $stmtEstatus->execute();
            $estatus = $stmtEstatus->fetch();

            if (!$estatus) {
                setFlash('error', 'No se encontró el estatus "Activo" en el sistema.');
                redirect('admin/personas');
                return;
            }

            $stmt = $this->conexion->prepare("
                UPDATE unexca.datos_personas
                SET id_estatus = :id_estatus
                WHERE id_persona = :id
            ");
            $stmt->execute([
                ':id_estatus' => $estatus['id_estatus'],
                ':id' => $id
            ]);

            setFlash('success', 'Persona activada exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al activar: ' . $e->getMessage());
        }

        redirect('admin/personas');
    }

    public function ver(int $id): void
    {
        $stmt = $this->conexion->prepare("
            SELECT dp.*, e.nombre_estatus
            FROM unexca.datos_personas dp
            JOIN unexca.estatus e ON e.id_estatus = dp.id_estatus
            WHERE dp.id_persona = :id
        ");
        $stmt->execute([':id' => $id]);
        $persona = $stmt->fetch();

        if (!$persona) {
            setFlash('error', 'Persona no encontrada.');
            redirect('admin/personas');
            return;
        }

        // Buscar si tiene usuario asociado
        $stmtUsuario = $this->conexion->prepare("
            SELECT u.id_usuario, u.cedula, u.correo_institucional, 
                   t.nombre_tipo, eu.nombre_estatus as estatus_usuario
            FROM unexca.usuarios u
            JOIN unexca.tipos_usuario t ON t.id_tipo = u.id_tipo
            JOIN unexca.estatus eu ON eu.id_estatus = u.id_estatus
            WHERE u.id_persona = :id
        ");
        $stmtUsuario->execute([':id' => $id]);
        $usuario = $stmtUsuario->fetch();

        renderView('admin/persona_detalle', [
            'titulo' => 'Detalle de Persona',
            'persona' => $persona,
            'usuario' => $usuario ?: null,
            'flash' => getFlash()
        ]);
    }

    public function buscarAjax(int $cedula): void
    {
        header('Content-Type: application/json');

        try {
            // Buscar persona por cédula
            $stmt = $this->conexion->prepare("
                SELECT id_persona, nombres, apellidos, correo_personal 
                FROM unexca.datos_personas 
                WHERE cedula_identidad = :cedula
            ");
            $stmt->execute([':cedula' => $cedula]);
            $persona = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$persona) {
                echo json_encode(['success' => false, 'message' => 'Persona no encontrada en el sistema.']);
                exit;
            }

            $context = $_GET['context'] ?? 'usuario';

            if ($context === 'usuario') {
                $checkU = $this->conexion->prepare("SELECT id_usuario FROM unexca.usuarios WHERE id_persona = :id");
                $checkU->execute([':id' => $persona['id_persona']]);
                if ($checkU->fetch()) {
                    echo json_encode(['success' => false, 'message' => 'Esta persona ya tiene un usuario asignado.']);
                    exit;
                }
            } elseif ($context === 'estudiante') {
                $checkE = $this->conexion->prepare("SELECT id_estudiante FROM unexca.datos_estudiantes WHERE id_persona = :id");
                $checkE->execute([':id' => $persona['id_persona']]);
                if ($checkE->fetch()) {
                    echo json_encode(['success' => false, 'message' => 'Esta persona ya está registrada como estudiante.']);
                    exit;
                }
            } elseif ($context === 'docente') {
                $checkD = $this->conexion->prepare("SELECT id_docente FROM unexca.datos_docentes WHERE id_persona = :id");
                $checkD->execute([':id' => $persona['id_persona']]);
                if ($checkD->fetch()) {
                    echo json_encode(['success' => false, 'message' => 'Esta persona ya está registrada como docente.']);
                    exit;
                }
            }

            echo json_encode([
                'success' => true,
                'data' => [
                    'id_persona' => $persona['id_persona'],
                    'nombre_completo' => trim($persona['nombres'] . ' ' . $persona['apellidos'])
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error de conexión.']);
        }
        exit;
    }
}
