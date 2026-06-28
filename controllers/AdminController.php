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
            $stmtUpdate->execute([':estado' => $newEstado ? '1' : '0', ':id' => $id]);

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
            SELECT ca.id_periodo, ca.id_docente, ca.id_asignatura, ca.id_sede,
                   MIN(ca.id_carga) as id_carga,
                   COUNT(ca.id_seccion) as total_secciones,
                   pa.periodo as periodo,
                   dd.nombres as docente_nombres, dd.apellidos as docente_apellidos,
                   da.nombre as asignatura,
                   s.nombre_sede as sede
            FROM unexca.carga_academica ca
            JOIN unexca.periodo_academico pa ON pa.id_periodo = ca.id_periodo
            JOIN unexca.datos_docentes doc ON doc.id_docente = ca.id_docente
            JOIN unexca.datos_personas dd ON dd.id_persona = doc.id_persona
            JOIN unexca.asignatura da ON da.id_asignatura = ca.id_asignatura
            JOIN unexca.sedes_unexca s ON s.id_sede = ca.id_sede
            GROUP BY ca.id_periodo, ca.id_docente, ca.id_asignatura, ca.id_sede, pa.periodo, dd.nombres, dd.apellidos, da.nombre, s.nombre_sede
            ORDER BY MIN(ca.id_carga) DESC
        ");
        $cargas = $stmt->fetchAll();

        // Obtener todas las secciones para la nueva pestaña de gestión
        $stmtSec = $this->conexion->query("SELECT * FROM unexca.secciones ORDER BY id_seccion DESC");
        $secciones = $stmtSec->fetchAll();

        // Obtener periodos activos para el select de asignar carga
        $stmtPer = $this->conexion->query("SELECT id_periodo, periodo FROM unexca.periodo_academico WHERE estado = '1' ORDER BY id_periodo DESC");
        $periodos = $stmtPer->fetchAll();

        // Obtener sedes
        $stmtSedes = $this->conexion->query("SELECT * FROM unexca.sedes_unexca ORDER BY nombre_sede");
        $sedes = $stmtSedes->fetchAll();

        // Estadísticas
        $totalCargas = count($cargas);
        $totalSecciones = count($secciones);
        $stmtDocentes = $this->conexion->query("SELECT COUNT(DISTINCT id_docente) as total FROM unexca.carga_academica");
        $totalDocentesCarga = $stmtDocentes->fetch()['total'] ?? 0;

        renderView('admin/cargas', [
            'titulo' => 'Gestión de Carga Académica y Secciones',
            'cargas' => $cargas,
            'secciones' => $secciones,
            'periodos' => $periodos,
            'sedes' => $sedes,
            'stats' => [
                'cargas' => $totalCargas,
                'secciones' => $totalSecciones,
                'docentes' => $totalDocentesCarga
            ],
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

    public function crearClaseVista(): void
    {
        $stmtPer = $this->conexion->query("SELECT id_periodo, periodo FROM unexca.periodo_academico WHERE estado = '1' ORDER BY id_periodo DESC");
        $periodos = $stmtPer->fetchAll();

        $stmtSedes = $this->conexion->query("SELECT * FROM unexca.sedes_unexca ORDER BY nombre_sede");
        $sedes = $stmtSedes->fetchAll();

        $stmtSec = $this->conexion->query("SELECT * FROM unexca.secciones ORDER BY id_seccion DESC");
        $secciones = $stmtSec->fetchAll();

        $stmtDocentes = $this->conexion->query("
            SELECT doc.id_docente, doc.id_pnf, dp.nombres, dp.apellidos
            FROM unexca.datos_docentes doc
            JOIN unexca.datos_personas dp ON dp.id_persona = doc.id_persona
            ORDER BY dp.apellidos, dp.nombres
        ");
        $docentes = $stmtDocentes->fetchAll();

        $stmtAsignaturas = $this->conexion->query("SELECT * FROM unexca.asignatura ORDER BY nombre");
        $asignaturas = $stmtAsignaturas->fetchAll();

        // Obtener estudiantes activos
        $stmtEstudiantes = $this->conexion->query("
            SELECT e.id_estudiante, e.id_pnf, p.nombres, p.apellidos, p.cedula_identidad as cedula
            FROM unexca.datos_estudiantes e
            JOIN unexca.datos_personas p ON p.id_persona = e.id_persona
            ORDER BY p.apellidos, p.nombres
        ");
        $estudiantes = $stmtEstudiantes->fetchAll();

        // Obtener catálogos de PNF y Turnos
        $pnfs = $this->conexion->query("SELECT * FROM unexca.pnf ORDER BY nombre_pnf")->fetchAll();
        $turnos = $this->conexion->query("SELECT * FROM unexca.turnos ORDER BY id_turno")->fetchAll();

        // Obtener horarios creados con sus asignaturas y docentes
        $stmtHorarios = $this->conexion->query("
            SELECT h.*, a.nombre as asignatura_nombre, a.unidades_credito, dp.nombres, dp.apellidos, doc.id_pnf 
            FROM unexca.horarios h
            LEFT JOIN unexca.asignatura a ON a.id_asignatura = h.id_asignatura
            LEFT JOIN unexca.datos_docentes doc ON doc.id_docente = h.id_docente
            LEFT JOIN unexca.datos_personas dp ON dp.id_persona = doc.id_persona
            ORDER BY h.dia_semana, h.hora_inicio
        ");
        $horarios = $stmtHorarios->fetchAll();

        renderView('admin/cargas_clase', [
            'titulo' => 'Creador de Clases',
            'periodos' => $periodos,
            'sedes' => $sedes,
            'secciones' => $secciones,
            'docentes' => $docentes,
            'asignaturas' => $asignaturas,
            'estudiantes' => $estudiantes,
            'pnfs' => $pnfs,
            'turnos' => $turnos,
            'horarios' => $horarios,
            'flash' => getFlash()
        ]);
    }

    public function guardarClase(): void
    {
        $id_periodo = $_POST['id_periodo'] ?? '';
        $id_sede = $_POST['id_sede'] ?? '';
        $id_seccion = $_POST['id_seccion'] ?? '';
        
        $id_turno = $_POST['id_turno'] ?? '';
        
        // Array de materias
        $materias = $_POST['materias'] ?? [];
        
        $estudiantes = $_POST['estudiantes'] ?? [];

        if (empty($id_periodo) || empty($id_sede) || empty($id_seccion)) {
            setFlash('error', 'Faltan datos generales de la clase.');
            redirect('admin/cargas/clase');
            return;
        }

        if (empty($materias) || count($materias) > 6) {
            setFlash('error', 'Debes seleccionar entre 1 y 6 asignaturas.');
            redirect('admin/cargas/clase');
            return;
        }

        if (count($estudiantes) > 40) {
            setFlash('error', 'El máximo de estudiantes permitidos por sección es 40.');
            redirect('admin/cargas/clase');
            return;
        }

        try {
            $this->conexion->beginTransaction();

            // Actualizar el turno de la sección
            if (!empty($id_turno)) {
                $stmtTurno = $this->conexion->prepare("UPDATE unexca.secciones SET id_turno = :id_turno WHERE id_seccion = :id_seccion");
                $stmtTurno->execute([':id_turno' => $id_turno, ':id_seccion' => $id_seccion]);
            }

            // Crear las cargas académicas (1 por materia)
            $cargas_creadas = [];
            foreach ($materias as $materia) {
                $id_asignatura = $materia['id_asignatura'] ?? '';
                $id_docente = $materia['id_docente'] ?? '';

                if (empty($id_asignatura) || empty($id_docente)) continue;

                $stmtBusqueda = $this->conexion->prepare("
                    SELECT id_carga FROM unexca.carga_academica 
                    WHERE id_periodo = :id_periodo AND id_docente = :id_docente 
                    AND id_asignatura = :id_asignatura AND id_seccion = :id_seccion
                ");
                $stmtBusqueda->execute([
                    ':id_periodo'    => $id_periodo,
                    ':id_docente'    => $id_docente,
                    ':id_asignatura' => $id_asignatura,
                    ':id_seccion'    => $id_seccion
                ]);
                $id_carga_existente = $stmtBusqueda->fetchColumn();

                if ($id_carga_existente) {
                    $cargasCreadasIds[] = $id_carga_existente;
                } else {
                    $stmtCarga = $this->conexion->prepare("
                        INSERT INTO unexca.carga_academica (id_periodo, id_docente, id_asignatura, id_seccion, id_sede, id_estatus)
                        VALUES (:id_periodo, :id_docente, :id_asignatura, :id_seccion, :id_sede, 1)
                        RETURNING id_carga
                    ");
                    $stmtCarga->execute([
                        ':id_periodo'    => $id_periodo,
                        ':id_docente'    => $id_docente,
                        ':id_asignatura' => $id_asignatura,
                        ':id_seccion'    => $id_seccion,
                        ':id_sede'       => $id_sede
                    ]);
                    $cargasCreadasIds[] = $stmtCarga->fetchColumn();
                }
            }

            // 2. Inscribir a los estudiantes en cada una de las cargas creadas
            if (!empty($estudiantes) && !empty($cargasCreadasIds)) {
                $stmtChequeo = $this->conexion->prepare("SELECT id_inscripcion_asig FROM unexca.inscripcion_asignatura WHERE id_estudiante = :id_estudiante AND id_carga = :id_carga");
                
                $stmtInscripcion = $this->conexion->prepare("
                    INSERT INTO unexca.inscripcion_asignatura (id_estudiante, id_carga, id_estatus)
                    VALUES (:id_estudiante, :id_carga, 1)
                ");

                foreach ($cargasCreadasIds as $id_carga) {
                    foreach ($estudiantes as $id_estudiante) {
                        $stmtChequeo->execute([
                            ':id_estudiante' => $id_estudiante,
                            ':id_carga'      => $id_carga
                        ]);
                        if (!$stmtChequeo->fetchColumn()) {
                            $stmtInscripcion->execute([
                                ':id_estudiante' => $id_estudiante,
                                ':id_carga'      => $id_carga
                            ]);
                        }
                    }
                }
            }

            $this->conexion->commit();
            setFlash('success', 'Clase creada e inscripciones registradas exitosamente.');
            redirect('admin/cargas');

        } catch (Exception $e) {
            $this->conexion->rollBack();
            setFlash('error', 'Error al crear la clase: ' . $e->getMessage());
            redirect('admin/cargas/clase');
        }
    }

    public function actualizarCarga(int $id): void
    {
        $id_periodo    = $_POST['id_periodo'] ?? '';
        $id_docente    = $_POST['id_docente'] ?? '';
        $id_asignatura = $_POST['id_asignatura'] ?? '';
        $id_seccion    = $_POST['id_seccion'] ?? '';
        $id_sede       = $_POST['id_sede'] ?? '';

        if (empty($id_periodo) || empty($id_docente) || empty($id_asignatura) || empty($id_seccion) || empty($id_sede)) {
            setFlash('error', 'Todos los campos son obligatorios para actualizar la carga.');
            redirect('admin/cargas');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("
                UPDATE unexca.carga_academica 
                SET id_periodo = :id_periodo, 
                    id_docente = :id_docente, 
                    id_asignatura = :id_asignatura, 
                    id_seccion = :id_seccion, 
                    id_sede = :id_sede
                WHERE id_carga = :id
            ");
            $stmt->execute([
                ':id_periodo'    => $id_periodo,
                ':id_docente'    => $id_docente,
                ':id_asignatura' => $id_asignatura,
                ':id_seccion'    => $id_seccion,
                ':id_sede'       => $id_sede,
                ':id'            => $id
            ]);
            setFlash('success', 'Carga académica actualizada exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al actualizar carga: ' . $e->getMessage());
        }

        redirect('admin/cargas');
    }

    public function verCarga(int $id): void
    {
        // Obtener detalles completos de la carga académica
        $stmtCarga = $this->conexion->prepare("
            SELECT ca.*,
                   pa.periodo as periodo,
                   dd.nombres as docente_nombres, dd.apellidos as docente_apellidos, dd.cedula_identidad as docente_cedula,
                   da.nombre as asignatura, da.codigo as asignatura_codigo,
                   ds.cod_seccion as seccion,
                   s.nombre_sede as sede
            FROM unexca.carga_academica ca
            JOIN unexca.periodo_academico pa ON pa.id_periodo = ca.id_periodo
            JOIN unexca.datos_docentes doc ON doc.id_docente = ca.id_docente
            JOIN unexca.datos_personas dd ON dd.id_persona = doc.id_persona
            JOIN unexca.asignatura da ON da.id_asignatura = ca.id_asignatura
            JOIN unexca.secciones ds ON ds.id_seccion = ca.id_seccion
            JOIN unexca.sedes_unexca s ON s.id_sede = ca.id_sede
            WHERE ca.id_carga = :id
        ");
        $stmtCarga->execute([':id' => $id]);
        $baseCarga = $stmtCarga->fetch();

        if (!$baseCarga) {
            setFlash('error', 'Carga académica no encontrada.');
            redirect('admin/cargas');
            return;
        }

        // Buscar todas las secciones que coincidan con la agrupación (docente + materia + periodo + sede)
        $stmtSecciones = $this->conexion->prepare("
            SELECT ca.id_carga, ca.id_seccion, ds.cod_seccion as seccion
            FROM unexca.carga_academica ca
            JOIN unexca.secciones ds ON ds.id_seccion = ca.id_seccion
            WHERE ca.id_docente = :id_docente
              AND ca.id_asignatura = :id_asignatura
              AND ca.id_periodo = :id_periodo
              AND ca.id_sede = :id_sede
            ORDER BY ds.cod_seccion
        ");
        $stmtSecciones->execute([
            ':id_docente' => $baseCarga['id_docente'],
            ':id_asignatura' => $baseCarga['id_asignatura'],
            ':id_periodo' => $baseCarga['id_periodo'],
            ':id_sede' => $baseCarga['id_sede']
        ]);
        $seccionesList = $stmtSecciones->fetchAll();

        // Obtener todos los alumnos de esas secciones
        $seccionesCargaIds = array_column($seccionesList, 'id_carga');
        $inscritos = [];
        if (!empty($seccionesCargaIds)) {
            $inQuery = implode(',', array_map('intval', $seccionesCargaIds));
            $stmtInscritos = $this->conexion->prepare("
                SELECT i.id_inscripcion, i.id_carga, i.creado_en as fecha_inscripcion,
                       p.cedula_identidad as cedula, p.nombres, p.apellidos,
                       e.nombre_estatus
                FROM unexca.inscripcion i
                JOIN unexca.datos_estudiantes de ON de.id_estudiante = i.id_estudiante
                JOIN unexca.datos_personas p ON p.id_persona = de.id_persona
                LEFT JOIN unexca.estatus e ON e.id_estatus = i.id_estatus
                WHERE i.id_carga IN ($inQuery)
                ORDER BY p.apellidos, p.nombres
            ");
            try {
                $stmtInscritos->execute();
                $inscritos = $stmtInscritos->fetchAll();
            } catch (PDOException $e) {}
        }

        $seccionesConAlumnos = [];
        foreach ($seccionesList as $sec) {
            $sec['alumnos'] = [];
            foreach ($inscritos as $ins) {
                if ($ins['id_carga'] == $sec['id_carga']) {
                    $sec['alumnos'][] = $ins;
                }
            }
            $seccionesConAlumnos[] = $sec;
        }

        renderView('admin/cargas_ver', [
            'titulo' => 'Detalles de Carga Académica',
            'carga' => $baseCarga,
            'secciones_agrupadas' => $seccionesConAlumnos
        ]);
    }

    public function crearSeccion(): void
    {
        $cod_seccion = $_POST['cod_seccion'] ?? '';
        $capacidad = $_POST['capacidad_max'] ?? 40;

        if (empty($cod_seccion)) {
            setFlash('error', 'El código de la sección es obligatorio.');
            redirect('admin/cargas');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("INSERT INTO unexca.secciones (cod_seccion, capacidad_max) VALUES (:cod, :cap)");
            $stmt->execute([
                ':cod' => $cod_seccion,
                ':cap' => $capacidad
            ]);
            setFlash('success', 'Sección creada exitosamente.');
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'unique') || str_contains($e->getMessage(), 'duplicate')) {
                setFlash('error', 'Ya existe una sección con ese código.');
            } else {
                setFlash('error', 'Error al crear sección: ' . $e->getMessage());
            }
        }

        redirect('admin/cargas');
    }

    public function actualizarSeccion(int $id): void
    {
        $cod_seccion = $_POST['cod_seccion'] ?? '';
        $capacidad = $_POST['capacidad_max'] ?? 40;

        if (empty($cod_seccion)) {
            setFlash('error', 'El código de la sección es obligatorio.');
            redirect('admin/cargas');
            return;
        }

        try {
            $stmt = $this->conexion->prepare("
                UPDATE unexca.secciones 
                SET cod_seccion = :cod, capacidad_max = :cap, actualizado_en = CURRENT_TIMESTAMP 
                WHERE id_seccion = :id
            ");
            $stmt->execute([
                ':cod' => $cod_seccion,
                ':cap' => $capacidad,
                ':id'  => $id
            ]);
            setFlash('success', 'Sección actualizada exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al actualizar sección: ' . $e->getMessage());
        }

        redirect('admin/cargas');
    }

    public function verSeccion(int $id): void
    {
        // Obtener la sección
        $stmtSec = $this->conexion->prepare("SELECT * FROM unexca.secciones WHERE id_seccion = :id");
        $stmtSec->execute([':id' => $id]);
        $seccion = $stmtSec->fetch();

        if (!$seccion) {
            setFlash('error', 'Sección no encontrada.');
            redirect('admin/cargas');
            return;
        }

        // Obtener historial de carga académica para esta sección
        $stmtHistorial = $this->conexion->prepare("
            SELECT ca.*,
                   pa.periodo as periodo,
                   dd.nombres as docente_nombres, dd.apellidos as docente_apellidos,
                   da.nombre as asignatura,
                   da.codigo as asignatura_codigo,
                   s.nombre_sede as sede
            FROM unexca.carga_academica ca
            JOIN unexca.periodo_academico pa ON pa.id_periodo = ca.id_periodo
            JOIN unexca.datos_docentes doc ON doc.id_docente = ca.id_docente
            JOIN unexca.datos_personas dd ON dd.id_persona = doc.id_persona
            JOIN unexca.asignatura da ON da.id_asignatura = ca.id_asignatura
            JOIN unexca.sedes_unexca s ON s.id_sede = ca.id_sede
            WHERE ca.id_seccion = :id
            ORDER BY pa.id_periodo DESC, ca.id_carga DESC
        ");
        $stmtHistorial->execute([':id' => $id]);
        $historial = $stmtHistorial->fetchAll();

        // Obtener lista de estudiantes inscritos
        $inscritos = [];
        try {
            $stmtInscritos = $this->conexion->prepare("
                SELECT i.id_inscripcion, i.creado_en as fecha_inscripcion,
                       p.cedula_identidad as cedula, p.nombres, p.apellidos,
                       e.nombre_estatus,
                       pa.periodo as periodo,
                       da.nombre as asignatura
                FROM unexca.inscripcion i
                JOIN unexca.carga_academica ca ON ca.id_carga = i.id_carga
                JOIN unexca.periodo_academico pa ON pa.id_periodo = ca.id_periodo
                JOIN unexca.asignatura da ON da.id_asignatura = ca.id_asignatura
                JOIN unexca.datos_estudiantes de ON de.id_estudiante = i.id_estudiante
                JOIN unexca.datos_personas p ON p.id_persona = de.id_persona
                LEFT JOIN unexca.estatus e ON e.id_estatus = i.id_estatus
                WHERE ca.id_seccion = :id
                ORDER BY pa.id_periodo DESC, p.apellidos, p.nombres
            ");
            $stmtInscritos->execute([':id' => $id]);
            $inscritos = $stmtInscritos->fetchAll();
        } catch (PDOException $e) {}

        renderView('admin/secciones_ver', [
            'titulo' => 'Detalles de la Sección',
            'seccion' => $seccion,
            'historial' => $historial,
            'inscritos' => $inscritos
        ]);
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
