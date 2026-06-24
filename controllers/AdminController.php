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
            $password = 'unexca' . $cedula; // Contraseña por defecto
        }
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $this->conexion->beginTransaction();

            // 1. Obtener estatus activo
            $stmtEstatus = $this->conexion->query("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = 'Activo' LIMIT 1");
            $idEstatus = $stmtEstatus->fetch()['id_estatus'] ?? 1;

            // 2. Insertar en usuarios
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

            // 3. Condicional para Estudiante o Docente
            // IDs comunes: 2=Estudiante, 3=Docente (verificar nombre_tipo por si acaso)
            $stmtTipo = $this->conexion->prepare("SELECT nombre_tipo FROM unexca.tipos_usuario WHERE id_tipo = :id");
            $stmtTipo->execute([':id' => $id_tipo]);
            $tipoNombre = $stmtTipo->fetch()['nombre_tipo'] ?? '';

            if ($tipoNombre === 'Estudiante') {
                // Insertar en datos_estudiantes con PNF=1, Trayecto=1, Sede=1 por defecto
                $stmtEst = $this->conexion->prepare("
                    INSERT INTO unexca.datos_estudiantes (id_persona, id_trayecto, id_pnf, id_sede, fecha_ingreso)
                    VALUES (:id_persona, 1, 1, 1, CURRENT_DATE)
                ");
                $stmtEst->execute([':id_persona' => $id_persona]);
            } elseif ($tipoNombre === 'Docente') {
                // Insertar en datos_docentes con PNF=1, Sede=1 por defecto
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
            setFlash('error', 'Error al crear el usuario: Puede que el correo o cédula ya existan. ' . $e->getMessage());
        }

        redirect('admin/usuarios');
    }

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
        setFlash('success', 'Período creado exitosamente (Simulado).');
        redirect('admin/periodos');
    }

    public function cargas(): void
    {
        $stmt = $this->conexion->query("
            SELECT ca.*,
                   pa.codigo as periodo,
                   dd.nombres as docente_nombres, dd.apellidos as docente_apellidos,
                   da.nombre as asignatura,
                   ds.nombre as seccion,
                   s.nombre as sede
            FROM unexca.carga_academica ca
            JOIN unexca.periodo_academico pa ON pa.id_periodo = ca.id_periodo
            JOIN unexca.datos_docentes doc ON doc.id_docente = ca.id_docente
            JOIN unexca.datos_personas dd ON dd.id_persona = doc.id_persona
            JOIN unexca.datos_asignaturas da ON da.id_asignatura = ca.id_asignatura
            JOIN unexca.datos_secciones ds ON ds.id_seccion = ca.id_seccion
            JOIN unexca.sedes_unexca s ON s.id_sede = ca.id_sede
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
        setFlash('success', 'Carga asignada exitosamente (Simulado).');
        redirect('admin/cargas');
    }
}
