<?php
require_once __DIR__ . '/../init.php';

/**
 * Modelo para la gestión de calificaciones.
 *
 * Permite registrar, consultar y actualizar calificaciones de estudiantes.
 * Las actualizaciones generan un registro de auditoría automáticamente.
 *
 * Tabla: unexca.calificaciones
 */
class Calificacion
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Registra una nueva calificación.
     *
     * @param int         $id_inscripcion_asig Identificador de la inscripción
     * @param int         $id_plan             Identificador del plan de evaluación
     * @param float       $nota                Nota obtenida
     * @param int         $id_docente          Identificador del docente que registra
     * @param string|null $observacion         Observación opcional
     * @return int|false                       ID de la calificación creada o false si falla
     */
    public function registrar(int $id_inscripcion_asig, int $id_plan, float $nota, int $id_docente, ?string $observacion): int|false
    {
        $sql = "INSERT INTO unexca.calificaciones
                    (id_inscripcion_asig, id_plan, nota, calificado_por, observacion)
                VALUES
                    (:id_inscripcion_asig, :id_plan, :nota, :calificado_por, :observacion)
                RETURNING id_calificacion";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_inscripcion_asig', $id_inscripcion_asig, PDO::PARAM_INT);
        $stmt->bindParam(':id_plan', $id_plan, PDO::PARAM_INT);
        $stmt->bindParam(':nota', $nota);
        $stmt->bindParam(':calificado_por', $id_docente, PDO::PARAM_INT);
        $stmt->bindParam(':observacion', $observacion);

        if ($stmt->execute()) {
            $resultado = $stmt->fetch();
            return $resultado ? (int) $resultado['id_calificacion'] : false;
        }

        return false;
    }

    /**
     * Obtiene una calificación por su ID.
     *
     * @param int $id_calificacion Identificador de la calificación
     * @return array|false         Datos de la calificación o false si no existe
     */
    public function obtenerPorId(int $id_calificacion): array|false
    {
        $sql = "SELECT * FROM unexca.calificaciones WHERE id_calificacion = :id_calificacion";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch();
        return $resultado ?: false;
    }

    /**
     * Lista todas las calificaciones de una inscripción de asignatura.
     *
     * @param int $id_inscripcion_asig Identificador de la inscripción
     * @return array                   Lista de calificaciones
     */
    public function listarPorInscripcion(int $id_inscripcion_asig): array
    {
        $sql = "SELECT * FROM unexca.calificaciones
                WHERE id_inscripcion_asig = :id_inscripcion_asig
                ORDER BY id_calificacion";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_inscripcion_asig', $id_inscripcion_asig, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Lista todas las calificaciones de los estudiantes para una evaluación.
     *
     * @param int $id_plan Identificador del plan de evaluación
     * @return array       Lista de calificaciones de todos los estudiantes
     */
    public function listarPorPlan(int $id_plan): array
    {
        $sql = "SELECT * FROM unexca.calificaciones
                WHERE id_plan = :id_plan
                ORDER BY id_inscripcion_asig";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_plan', $id_plan, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Actualiza una calificación y registra la auditoría correspondiente.
     *
     * Crea un registro en unexca.auditoria_notas con la nota anterior,
     * la nota nueva y el motivo de la modificación.
     *
     * @param int    $id_calificacion       Identificador de la calificación
     * @param float  $nota_nueva            Nueva nota
     * @param int    $id_usuario_modificador Identificador del usuario que modifica
     * @param string $motivo                Motivo de la modificación
     * @return bool                         true si se actualizó correctamente
     * @throws \RuntimeException Si la calificación no existe
     */
    public function actualizar(int $id_calificacion, float $nota_nueva, int $id_usuario_modificador, string $motivo): bool
    {
        $this->conexion->beginTransaction();

        try {
            // Obtener la nota anterior
            $calificacion = $this->obtenerPorId($id_calificacion);
            if (!$calificacion) {
                throw new \RuntimeException("La calificación con ID {$id_calificacion} no existe.");
            }

            $nota_anterior = (float) $calificacion['nota'];

            // Registrar auditoría
            $sqlAuditoria = "INSERT INTO unexca.auditoria_notas
                                (id_calificacion, nota_anterior, nota_nueva, motivo, id_usuario)
                             VALUES
                                (:id_calificacion, :nota_anterior, :nota_nueva, :motivo, :id_usuario)";

            $stmtAuditoria = $this->conexion->prepare($sqlAuditoria);
            $stmtAuditoria->bindParam(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
            $stmtAuditoria->bindParam(':nota_anterior', $nota_anterior);
            $stmtAuditoria->bindParam(':nota_nueva', $nota_nueva);
            $stmtAuditoria->bindParam(':motivo', $motivo, PDO::PARAM_STR);
            $stmtAuditoria->bindParam(':id_usuario', $id_usuario_modificador, PDO::PARAM_INT);
            $stmtAuditoria->execute();

            // Actualizar la calificación
            $sqlUpdate = "UPDATE unexca.calificaciones
                          SET nota = :nota_nueva
                          WHERE id_calificacion = :id_calificacion";

            $stmtUpdate = $this->conexion->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':nota_nueva', $nota_nueva);
            $stmtUpdate->bindParam(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
            $stmtUpdate->execute();

            $this->conexion->commit();
            return true;

        } catch (\Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    /**
     * Calcula la nota definitiva de un estudiante en una asignatura.
     *
     * La nota definitiva es la sumatoria de (nota * porcentaje / 100) para
     * todas las evaluaciones del plan de la asignatura.
     *
     * @param int $id_inscripcion_asig Identificador de la inscripción
     * @return float                   Nota definitiva calculada
     */
    public function calcularNotaDefinitiva(int $id_inscripcion_asig): float
    {
        $sql = "SELECT COALESCE(SUM(c.nota * pe.porcentaje / 100), 0) AS nota_definitiva
                FROM unexca.calificaciones c
                INNER JOIN unexca.plan_evaluacion pe ON pe.id_plan = c.id_plan
                WHERE c.id_inscripcion_asig = :id_inscripcion_asig";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_inscripcion_asig', $id_inscripcion_asig, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch();
        return (float) $resultado['nota_definitiva'];
    }
}
