<?php
class FinanzasController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Lista todos los pagos registrados.
     * Fixed: JOIN on id_persona (not id_estudiante which doesn't exist in pagos).
     */
    public function pagos(): void
    {
        $stmt = $this->conexion->query("
            SELECT p.*, a.descripcion as arancel, a.monto,
                   dp.nombres, dp.apellidos, dp.cedula_identidad,
                   e.nombre_estatus
            FROM unexca.pagos p
            JOIN unexca.aranceles a ON a.id_arancel = p.id_arancel
            JOIN unexca.datos_personas dp ON dp.id_persona = p.id_persona
            JOIN unexca.estatus e ON e.id_estatus = p.id_estatus
            ORDER BY p.id_pago DESC
        ");
        $pagos = $stmt->fetchAll();

        // Obtener aranceles activos para el formulario
        $stmtAranceles = $this->conexion->query("
            SELECT a.*, e.nombre_estatus
            FROM unexca.aranceles a
            JOIN unexca.estatus e ON e.id_estatus = a.id_estatus
            WHERE e.nombre_estatus = 'Activo'
            ORDER BY a.descripcion
        ");
        $aranceles = $stmtAranceles->fetchAll();

        renderView('finanzas/pagos', [
            'titulo'    => 'Gestión de Pagos',
            'pagos'     => $pagos,
            'aranceles' => $aranceles,
            'flash'     => getFlash()
        ]);
    }

    /**
     * Registra un nuevo pago.
     * Busca persona por cédula, valida referencia única, inserta en pagos.
     */
    public function registrarPago(): void
    {
        $cedula     = trim($_POST['cedula_persona'] ?? '');
        $id_arancel = $_POST['id_arancel'] ?? '';
        $referencia = trim($_POST['referencia_bancaria'] ?? '');
        $fecha_pago = $_POST['fecha_pago'] ?? '';

        // Validar campos obligatorios
        if (empty($cedula) || empty($id_arancel) || empty($referencia) || empty($fecha_pago)) {
            setFlash('error', 'Todos los campos son obligatorios.');
            redirect('finanzas/pagos');
            return;
        }

        try {
            // 1. Buscar persona por cédula
            $stmtPersona = $this->conexion->prepare("
                SELECT id_persona FROM unexca.datos_personas
                WHERE cedula_identidad = :cedula
            ");
            $stmtPersona->execute([':cedula' => $cedula]);
            $persona = $stmtPersona->fetch();

            if (!$persona) {
                setFlash('error', 'No se encontró ninguna persona con la cédula ingresada.');
                redirect('finanzas/pagos');
                return;
            }

            // 2. Validar que la referencia bancaria sea única
            $stmtRef = $this->conexion->prepare("
                SELECT id_pago FROM unexca.pagos
                WHERE referencia_bancaria = :referencia
            ");
            $stmtRef->execute([':referencia' => $referencia]);

            if ($stmtRef->fetch()) {
                setFlash('error', 'La referencia bancaria ya fue registrada anteriormente.');
                redirect('finanzas/pagos');
                return;
            }

            // 3. Obtener estatus "Activo"
            $stmtEstatus = $this->conexion->query("
                SELECT id_estatus FROM unexca.estatus
                WHERE nombre_estatus = 'Activo' LIMIT 1
            ");
            $idEstatus = $stmtEstatus->fetch()['id_estatus'] ?? 1;

            // 4. Insertar el pago
            $stmtInsert = $this->conexion->prepare("
                INSERT INTO unexca.pagos (id_arancel, id_estatus, id_persona, referencia_bancaria, fecha_pago)
                VALUES (:id_arancel, :id_estatus, :id_persona, :referencia, :fecha_pago)
            ");
            $stmtInsert->execute([
                ':id_arancel'  => $id_arancel,
                ':id_estatus'  => $idEstatus,
                ':id_persona'  => $persona['id_persona'],
                ':referencia'  => $referencia,
                ':fecha_pago'  => $fecha_pago
            ]);

            setFlash('success', 'Pago registrado exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al registrar el pago: ' . $e->getMessage());
        }

        redirect('finanzas/pagos');
    }

    /**
     * Lista todos los aranceles.
     * Fixed: uses 'descripcion' (not 'nombre') and JOINs estatus.
     */
    public function aranceles(): void
    {
        $stmt = $this->conexion->query("
            SELECT a.*, e.nombre_estatus
            FROM unexca.aranceles a
            JOIN unexca.estatus e ON e.id_estatus = a.id_estatus
            ORDER BY a.id_arancel DESC
        ");
        $aranceles = $stmt->fetchAll();

        renderView('finanzas/aranceles', [
            'titulo'    => 'Configuración de Aranceles',
            'aranceles' => $aranceles,
            'flash'     => getFlash()
        ]);
    }

    /**
     * Crea un nuevo arancel.
     */
    public function crearArancel(): void
    {
        $descripcion = trim($_POST['descripcion'] ?? '');
        $monto       = $_POST['monto'] ?? '';

        if (empty($descripcion) || empty($monto)) {
            setFlash('error', 'La descripción y el monto son obligatorios.');
            redirect('finanzas/aranceles');
            return;
        }

        try {
            // Obtener estatus "Activo"
            $stmtEstatus = $this->conexion->query("
                SELECT id_estatus FROM unexca.estatus
                WHERE nombre_estatus = 'Activo' LIMIT 1
            ");
            $idEstatus = $stmtEstatus->fetch()['id_estatus'] ?? 1;

            $stmtInsert = $this->conexion->prepare("
                INSERT INTO unexca.aranceles (id_estatus, descripcion, monto)
                VALUES (:id_estatus, :descripcion, :monto)
            ");
            $stmtInsert->execute([
                ':id_estatus'  => $idEstatus,
                ':descripcion' => $descripcion,
                ':monto'       => $monto
            ]);

            setFlash('success', 'Arancel creado exitosamente.');
        } catch (PDOException $e) {
            setFlash('error', 'Error al crear el arancel: ' . $e->getMessage());
        }

        redirect('finanzas/aranceles');
    }

    /**
     * Muestra la solvencia administrativa de los estudiantes.
     * Fixed: uses id_persona instead of id_estudiante, removed broken estatus text check.
     */
    public function solvencias(): void
    {
        $stmt = $this->conexion->query("
            SELECT dp.id_persona, dp.nombres, dp.apellidos, dp.cedula_identidad,
                   (SELECT COUNT(*) FROM unexca.pagos p WHERE p.id_persona = dp.id_persona) as pagos_realizados
            FROM unexca.datos_personas dp
            JOIN unexca.datos_estudiantes de ON de.id_persona = dp.id_persona
            ORDER BY dp.apellidos
        ");
        $estudiantes = $stmt->fetchAll();

        renderView('finanzas/solvencias', [
            'titulo'      => 'Solvencias Administrativas',
            'estudiantes' => $estudiantes,
            'flash'       => getFlash()
        ]);
    }

    public function verificarSolvencia(int $id): void
    {
        // Placeholder para futuro detalle de solvencia por persona
        redirect('finanzas/solvencias');
    }
}
