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

            // Verificar si ya tiene usuario
            $checkU = $this->conexion->prepare("SELECT id_usuario FROM unexca.usuarios WHERE id_persona = :id");
            $checkU->execute([':id' => $persona['id_persona']]);
            if ($checkU->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Esta persona ya tiene un usuario asignado.']);
                exit;
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
