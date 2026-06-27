<?php
require_once ROOT_PATH . '/models/CargaAcademica.php';

class AdminController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    // ═══════════════════════════════════════════════════════════════════
    //  USUARIOS
    // ═══════════════════════════════════════════════════════════════════

    public function usuarios(): void
    {
        $stmt = $this->conexion->query("
            SELECT u.*, tu.nombre_tipo, e.nombre_estatus,
                   dp.nombres, dp.apellidos
            FROM unexca.usuarios u
            JOIN unexca.tipos_usuario tu ON tu.id_tipo = u.id_tipo
            JOIN unexca.estatus e ON e.id_estatus = u.id_estatus
            LEFT JOIN unexca.datos_personas dp ON dp.id_persona = u.id_persona
            ORDER BY u.id_usuario DESC
        ");
        $usuarios = $stmt->fetchAll();

        $stmtRoles = $this->conexion->query("SELECT * FROM unexca.tipos_usuario ORDER BY id_tipo");
        $roles = $stmtRoles->fetchAll();

        renderView('admin/usuarios', [
            'titulo'   => 'Gestión de Usuarios',
            'usuarios' => $usuarios,
            'roles'    => $roles,
            'flash'    => getFlash()
        ]);
    }

    public function crearUsuario(): void
    {
        $id_persona = $_POST['id_persona'] ?? '';
        $cedula = $_POST['cedula'] ?? '';
        $id_tipo = $_POST['id_tipo'] ?? '';
        $correo = $_POST['correo_institucional'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($id_persona) || empty($cedula) || empty($id_tipo) || empty($correo)) {
            setFlash('error', 'Faltan datos obligatorios para crear el usuario.');
            redirect('admin/usuarios');
            return;
        }

        if (empty($password)) {
            $password = 'unexca' . $cedula;
        }
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $this->conexion->beginTransaction();

            $stmtEstatus = $this->conexion->query("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = 'Activo' LIMIT 1");
            $idEstatus = $stmtEstatus->fetch()['id_estatus'] ?? 1;

            $stmtUser = $this->conexion->prepare("
                INSERT INTO unexca.usuarios (id_persona, cedula, correo_institucional, password_hash, id_tipo, id_estatus)
                VALUES (:id_persona, :cedula, :correo, :hash, :id_tipo, :id_estatus)
            ");
            $stmtUser->execute([
                ':id_persona' => $id_persona,
                ':cedula' => $cedula,
                ':correo' => $correo,
                ':hash' => $password_hash,
                ':id_tipo' => $id_tipo,
                ':id_estatus' => $idEstatus
            ]);

            $stmtTipo = $this->conexion->prepare("SELECT nombre_tipo FROM unexca.tipos_usuario WHERE id_tipo = :id");
            $stmtTipo->execute([':id' => $id_tipo]);
            $tipoNombre = $stmtTipo->fetch()['nombre_tipo'] ?? '';

            if ($tipoNombre === 'Estudiante') {
                $stmtEst = $this->conexion->prepare("
                    INSERT INTO unexca.datos_estudiantes (id_persona, id_trayecto, id_pnf, id_sede, fecha_ingreso)
                    VALUES (:id_persona, 1, 1, 1, CURRENT_DATE)
                ");
                $stmtEst->execute([':id_persona' => $id_persona]);
            } elseif ($tipoNombre === 'Docente') {
                $stmtDoc = $this->conexion->prepare("
                    INSERT INTO unexca.datos_docentes (id_persona, id_pnf, id_sede, fecha_ingreso)
                    VALUES (:id_persona, 1, 1, CURRENT_DATE)
                ");
                $stmtDoc->execute([':id_persona' => $id_persona]);
            }

            $this->conexion->commit();
            setFlash('success', "Usuario creado exitosamente con el rol de {$tipoNombre}. Contraseña: {$password}");
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            setFlash('error', 'Error al crear el usuario: Puede que el correo o cédula ya existan.');
        }

        redirect('admin/usuarios');
    }

    public function editarUsuario(int $id): void
    {
        $stmt = $this->conexion->prepare("
            SELECT u.*, tu.nombre_tipo, e.nombre_estatus,
                   dp.nombres, dp.apellidos, dp.cedula_identidad
            FROM unexca.usuarios u
            JOIN unexca.tipos_usuario tu ON tu.id_tipo = u.id_tipo
            JOIN unexca.estatus e ON e.id_estatus = u.id_estatus
            LEFT JOIN unexca.datos_personas dp ON dp.id_persona = u.id_persona
            WHERE u.id_usuario = :id
        ");
        $stmt->execute([':id' => $id]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            setFlash('error', 'Usuario no encontrado.');
            redirect('admin/usuarios');
            return;
        }

        $stmtRoles = $this->conexion->query("SELECT * FROM unexca.tipos_usuario ORDER BY id_tipo");
        $roles = $stmtRoles->fetchAll();

        $stmtEstatus = $this->conexion->query("SELECT * FROM unexca.estatus WHERE nombre_estatus IN ('Activo', 'Inactivo', 'Suspendido') ORDER BY id_estatus");
        $estatusList = $stmtEstatus->fetchAll();

        renderView('admin/usuario_editar', [
            'titulo'      => 'Editar Usuario',
            'usuario'     => $usuario,
            'roles'       => $roles,
            'estatusList' => $estatusList,
            'flash'       => getFlash()
        ]);
    }

    public function actualizarUsuario(int $id): void
    {
        $id_tipo = $_POST['id_tipo'] ?? '';
        $correo = $_POST['correo_institucional'] ?? '';
        $id_estatus = $_POST['id_estatus'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($id_tipo) || empty($correo) || empty($id_estatus)) {
            setFlash('error', 'Todos los campos son obligatorios (excepto contraseña).');
            redirect("admin/usuarios/editar/{$id}");
            return;
        }

        try {
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $this->conexion->prepare("
                    UPDATE unexca.usuarios
                    SET id_tipo = :id_tipo,
                        correo_institucional = :correo,
                        id_estatus = :id_estatus,
                        password_hash = :hash
                    WHERE id_usuario = :id
                ");
                $stmt->execute([
                    ':id_tipo'    => $id_tipo,
                    ':correo'     => $correo,
                    ':id_estatus' => $id_estatus,
                    ':hash'       => $hash,
                    ':id'         => $id
                ]);
            } else {
                $stmt = $this->conexion->prepare("
                    UPDATE unexca.usuarios
                    SET id_tipo = :id_tipo,
                        correo_institucional = :correo,
                        id_estatus = :id_estatus
                    WHERE id_usuario = :id
                ");
                $stmt->execute([
                    ':id_tipo'    => $id_tipo,
                    ':correo'     => $correo,
                    ':id_estatus' => $id_estatus,
                    ':id'         => $id
                ]);
            }
            
            setFlash('success', 'Usuario actualizado exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al actualizar: ' . $e->getMessage());
        }

        redirect("admin/usuarios/editar/{$id}");
    }

    public function cambiarPassword(int $id): void
    {
        $newPassword = $_POST['new_password'] ?? '';

        if (empty($newPassword)) {
            setFlash('error', 'La contraseña no puede estar vacía.');
            redirect('admin/usuarios');
            return;
        }

        $hash = password_hash($newPassword, PASSWORD_BCRYPT);

        try {
            $stmt = $this->conexion->prepare("
                UPDATE unexca.usuarios
                SET password_hash = :hash
                WHERE id_usuario = :id
            ");
            $stmt->execute([':hash' => $hash, ':id' => $id]);
            setFlash('success', "Contraseña cambiada exitosamente. Nueva contraseña: {$newPassword}");
        } catch (PDOException $e) {
            setFlash('error', 'Error al cambiar la contraseña: ' . $e->getMessage());
        }

        redirect('admin/usuarios');
    }

    public function toggleEstatus(int $id): void
    {
        try {
            // Get current estatus name
            $stmt = $this->conexion->prepare("
                SELECT e.nombre_estatus
                FROM unexca.usuarios u
                JOIN unexca.estatus e ON e.id_estatus = u.id_estatus
                WHERE u.id_usuario = :id
            ");
            $stmt->execute([':id' => $id]);
            $current = $stmt->fetch();

            if (!$current) {
                setFlash('error', 'Usuario no encontrado.');
                redirect('admin/usuarios');
                return;
            }

            // Determine new estatus
            $newEstatusName = ($current['nombre_estatus'] === 'Activo') ? 'Inactivo' : 'Activo';

            $stmtNew = $this->conexion->prepare("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = :nombre LIMIT 1");
            $stmtNew->execute([':nombre' => $newEstatusName]);
            $newEstatus = $stmtNew->fetch();

            if (!$newEstatus) {
                setFlash('error', "No se encontró el estatus '{$newEstatusName}'.");
                redirect('admin/usuarios');
                return;
            }

            $stmtUpdate = $this->conexion->prepare("UPDATE unexca.usuarios SET id_estatus = :est WHERE id_usuario = :id");
            $stmtUpdate->execute([':est' => $newEstatus['id_estatus'], ':id' => $id]);

            setFlash('success', "Usuario cambiado a estado: {$newEstatusName}.");
        } catch (PDOException $e) {
            setFlash('error', 'Error al cambiar estatus: ' . $e->getMessage());
        }

        redirect('admin/usuarios');
    }

    // ═══════════════════════════════════════════════════════════════════
    //  PERÍODOS ACADÉMICOS
    // ═══════════════════════════════════════════════════════════════════

    public function periodos(): void
    {
        $stmt = $this->conexion->query("SELECT * FROM unexca.periodo_academico ORDER BY id_periodo DESC");
        $periodos = $stmt->fetchAll();

        renderView('admin/periodos', [
            'titulo'   => 'Períodos Académicos',
            'periodos' => $periodos,
            'flash'    => getFlash()
        ]);
    }

    public function crearPeriodo(): void
    {
        $periodo      = trim($_POST['periodo'] ?? '');
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_final  = $_POST['fecha_final'] ?? '';
        $estado       = $_POST['estado'] ?? '1';

        // Validations
        if (empty($periodo)) {
            setFlash('error', 'El código del período es obligatorio.');
            redirect('admin/periodos');
            return;
        }

        if (empty($fecha_inicio) || empty($fecha_final)) {
            setFlash('error', 'Las fechas de inicio y fin son obligatorias.');
            redirect('admin/periodos');
            return;
        }

        if ($fecha_final <= $fecha_inicio) {
            setFlash('error', 'La fecha final debe ser posterior a la fecha de inicio.');
            redirect('admin/periodos');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("
                INSERT INTO unexca.periodo_academico (periodo, fecha_inicio, fecha_final, estado)
                VALUES (:periodo, :fecha_inicio, :fecha_final, :estado)
            ");
            $stmt->execute([
                ':periodo'      => $periodo,
                ':fecha_inicio' => $fecha_inicio,
                ':fecha_final'  => $fecha_final,
                ':estado'       => (bool) $estado
            ]);
            setFlash('success', "Período '{$periodo}' creado exitosamente.");
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'unique') || str_contains($e->getMessage(), 'duplicate')) {
                setFlash('error', "Ya existe un período con el código '{$periodo}'.");
            } else {
                setFlash('error', 'Error al crear el período: ' . $e->getMessage());
            }
        }

        redirect('admin/periodos');
    }

    public function togglePeriodo(int $id): void
    {
        try {
            $stmt = $this->conexion->prepare("SELECT estado FROM unexca.periodo_academico WHERE id_periodo = :id");
            $stmt->execute([':id' => $id]);
            $periodo = $stmt->fetch();

            if (!$periodo) {
                setFlash('error', 'Período no encontrado.');
                redirect('admin/periodos');
                return;
            }

            $newEstado = !$periodo['estado'];

            $stmtUpdate = $this->conexion->prepare("UPDATE unexca.periodo_academico SET estado = :estado WHERE id_periodo = :id");
            $stmtUpdate->execute([':estado' => $newEstado, ':id' => $id]);

            $label = $newEstado ? 'Activado' : 'Cerrado';
            setFlash('success', "Período {$label} exitosamente.");
        } catch (PDOException $e) {
            setFlash('error', 'Error al cambiar estado: ' . $e->getMessage());
        }

        redirect('admin/periodos');
    }

    // ═══════════════════════════════════════════════════════════════════
    //  CARGA ACADÉMICA
    // ═══════════════════════════════════════════════════════════════════

    public function cargas(): void
    {
        $stmt = $this->conexion->query("
            SELECT ca.*,
                   pa.periodo as periodo,
                   dd.nombres as docente_nombres, dd.apellidos as docente_apellidos,
                   da.nombre as asignatura,
                   ds.cod_seccion as seccion,
                   s.nombre_sede as sede,
                   e.nombre_estatus
            FROM unexca.carga_academica ca
            JOIN unexca.periodo_academico pa ON pa.id_periodo = ca.id_periodo
            JOIN unexca.datos_docentes doc ON doc.id_docente = ca.id_docente
            JOIN unexca.datos_personas dd ON dd.id_persona = doc.id_persona
            JOIN unexca.asignatura da ON da.id_asignatura = ca.id_asignatura
            JOIN unexca.secciones ds ON ds.id_seccion = ca.id_seccion
            JOIN unexca.sedes_unexca s ON s.id_sede = ca.id_sede
            LEFT JOIN unexca.estatus e ON e.id_estatus = ca.id_estatus
            ORDER BY ca.id_carga DESC
        ");
        $cargas = $stmt->fetchAll();

        renderView('admin/cargas', [
            'titulo' => 'Asignación de Carga Académica',
            'cargas' => $cargas,
            'flash'  => getFlash()
        ]);
    }

    public function crearCarga(): void
    {
        $id_periodo    = $_POST['id_periodo'] ?? '';
        $id_docente    = $_POST['id_docente'] ?? '';
        $id_asignatura = $_POST['id_asignatura'] ?? '';
        $id_seccion    = $_POST['id_seccion'] ?? '';
        $id_sede       = $_POST['id_sede'] ?? '';

        if (empty($id_periodo) || empty($id_docente) || empty($id_asignatura) || empty($id_seccion) || empty($id_sede)) {
            setFlash('error', 'Todos los campos son obligatorios.');
            redirect('admin/cargas');
            return;
        }

        try {
            // Get active estatus
            $stmtEstatus = $this->conexion->query("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = 'Activo' LIMIT 1");
            $idEstatus = $stmtEstatus->fetch()['id_estatus'] ?? 1;

            $stmt = $this->conexion->prepare("
                INSERT INTO unexca.carga_academica (id_periodo, id_docente, id_asignatura, id_seccion, id_sede, id_estatus)
                VALUES (:id_periodo, :id_docente, :id_asignatura, :id_seccion, :id_sede, :id_estatus)
            ");
            $stmt->execute([
                ':id_periodo'    => $id_periodo,
                ':id_docente'    => $id_docente,
                ':id_asignatura' => $id_asignatura,
                ':id_seccion'    => $id_seccion,
                ':id_sede'       => $id_sede,
                ':id_estatus'    => $idEstatus
            ]);

            setFlash('success', 'Carga académica asignada exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al asignar carga: ' . $e->getMessage());
        }

        redirect('admin/cargas');
    }

    // ═══════════════════════════════════════════════════════════════════
    //  API — JSON ENDPOINTS
    // ═══════════════════════════════════════════════════════════════════

    public function apiDocentes(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $stmt = $this->conexion->query("
            SELECT doc.id_docente, dp.nombres, dp.apellidos
            FROM unexca.datos_docentes doc
            JOIN unexca.datos_personas dp ON dp.id_persona = doc.id_persona
            ORDER BY dp.apellidos, dp.nombres
        ");
        $docentes = $stmt->fetchAll();

        echo json_encode(['success' => true, 'data' => $docentes]);
        exit;
    }

    public function apiAsignaturas(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $stmt = $this->conexion->query("
            SELECT id_asignatura, nombre, codigo
            FROM unexca.asignatura
            ORDER BY nombre
        ");
        $asignaturas = $stmt->fetchAll();

        echo json_encode(['success' => true, 'data' => $asignaturas]);
        exit;
    }

    public function apiSecciones(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $stmt = $this->conexion->query("
            SELECT id_seccion, cod_seccion
            FROM unexca.secciones
            ORDER BY cod_seccion
        ");
        $secciones = $stmt->fetchAll();

        echo json_encode(['success' => true, 'data' => $secciones]);
        exit;
    }
}
