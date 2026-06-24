<?php
require_once __DIR__ . '/../init.php';

/**
 * Modelo para la gestión de exámenes de reparación.
 *
 * Permite solicitar, consultar y registrar notas de exámenes de reparación
 * para estudiantes que obtuvieron notas entre 5 y 10 (exclusivo).
 *
 * Tabla: unexca.examenes_reparacion
 */
class ExamenReparacion
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Solicita un examen de reparación para un estudiante.
     *
     * Valida que la nota original esté entre 5 (inclusive) y 10 (exclusivo)
     * antes de crear el registro.
     *
     * @param int      $id_inscripcion_asig Identificador de la inscripción
     * @param int      $id_periodo          Identificador del periodo académico
     * @param float    $nota_original       Nota original del estudiante
     * @param int|null $id_pago             Identificador del pago asociado (opcional)
     * @return int|false                    ID del registro o false si falla
     * @throws \RuntimeException Si la nota original no está en el rango permitido
     */
    public function solicitar(int $id_inscripcion_asig, int $id_periodo, float $nota_original, ?int $id_pago): int|false
    {
        // Validar que la nota esté en el rango permitido para reparación
        if ($nota_original < 5 || $nota_original >= 10) {
            throw new \RuntimeException(
                "La nota original ({$nota_original}) debe ser >= 5 y < 10 para solicitar examen de reparación."
            );
        }

        $sql = "INSERT INTO unexca.examenes_reparacion
                    (id_inscripcion_asig, id_periodo, nota_original, id_pago)
                VALUES
                    (:id_inscripcion_asig, :id_periodo, :nota_original, :id_pago)
                RETURNING id_reparacion";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_inscripcion_asig', $id_inscripcion_asig, PDO::PARAM_INT);
        $stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->bindParam(':nota_original', $nota_original);
        $stmt->bindParam(':id_pago', $id_pago, $id_pago !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);

        if ($stmt->execute()) {
            $resultado = $stmt->fetch();
            return $resultado ? (int) $resultado['id_reparacion'] : false;
        }

        return false;
    }

    /**
     * Obtiene un examen de reparación por su ID.
     *
     * @param int $id_reparacion Identificador del examen de reparación
     * @return array|false       Datos del examen o false si no existe
     */
    public function obtenerPorId(int $id_reparacion): array|false
    {
        $sql = "SELECT * FROM unexca.examenes_reparacion WHERE id_reparacion = :id_reparacion";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_reparacion', $id_reparacion, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch();
        return $resultado ?: false;
    }

    /**
     * Lista todos los exámenes de reparación de un periodo.
     *
     * @param int $id_periodo Identificador del periodo académico
     * @return array          Lista de exámenes de reparación
     */
    public function listarPorPeriodo(int $id_periodo): array
    {
        $sql = "SELECT * FROM unexca.examenes_reparacion
                WHERE id_periodo = :id_periodo
                ORDER BY id_reparacion";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Lista los exámenes de reparación de un estudiante (JOIN con inscripcion_asignatura).
     *
     * @param int $id_estudiante Identificador del estudiante
     * @return array             Lista de exámenes de reparación del estudiante
     */
    public function listarPorEstudiante(int $id_estudiante): array
    {
        $sql = "SELECT er.*
                FROM unexca.examenes_reparacion er
                INNER JOIN unexca.inscripcion_asignatura ia
                    ON ia.id_inscripcion_asig = er.id_inscripcion_asig
                WHERE ia.id_estudiante = :id_estudiante
                ORDER BY er.id_reparacion";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Registra la nota de un examen de reparación.
     *
     * Si la nota de reparación >= 10, la nota final posterior se fija en 10.00.
     * Si la nota de reparación < 10, la nota final posterior se mantiene como
     * la nota original.
     *
     * @param int   $id_reparacion   Identificador del examen de reparación
     * @param float $nota_reparacion Nota obtenida en el examen de reparación
     * @param int   $id_docente      Identificador del docente evaluador
     * @return bool                  true si se registró correctamente
     * @throws \RuntimeException Si el examen de reparación no existe
     */
    public function registrarNota(int $id_reparacion, float $nota_reparacion, int $id_docente): bool
    {
        // Obtener el registro de reparación para conocer la nota original
        $reparacion = $this->obtenerPorId($id_reparacion);
        if (!$reparacion) {
            throw new \RuntimeException("El examen de reparación con ID {$id_reparacion} no existe.");
        }

        $nota_original = (float) $reparacion['nota_original'];

        // Calcular nota final posterior según la regla de negocio
        $nota_final_post = $nota_reparacion >= 10 ? 10.00 : $nota_original;

        $sql = "UPDATE unexca.examenes_reparacion
                SET nota_reparacion = :nota_reparacion,
                    nota_final_post_reparacion = :nota_final_post,
                    id_docente_evaluador = :id_docente,
                    actualizado_en = CURRENT_TIMESTAMP
                WHERE id_reparacion = :id_reparacion";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nota_reparacion', $nota_reparacion);
        $stmt->bindParam(':nota_final_post', $nota_final_post);
        $stmt->bindParam(':id_docente', $id_docente, PDO::PARAM_INT);
        $stmt->bindParam(':id_reparacion', $id_reparacion, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Lista los estudiantes aplicables para examen de reparación en un periodo.
     *
     * Retorna estudiantes con nota definitiva >= 5 y < 10 en el periodo indicado.
     *
     * @param int $id_periodo Identificador del periodo académico
     * @return array          Lista de estudiantes aplicables con sus datos
     */
    public function listarAplicables(int $id_periodo): array
    {
        $sql = "SELECT ia.id_inscripcion_asig, ia.id_estudiante, ia.id_carga,
                       nf.nota_definitiva
                FROM unexca.notas_finales nf
                INNER JOIN unexca.inscripcion_asignatura ia
                    ON ia.id_inscripcion_asig = nf.id_inscripcion_asig
                INNER JOIN unexca.actas_notas an
                    ON an.id_acta = nf.id_acta
                WHERE an.id_periodo = :id_periodo
                  AND nf.nota_definitiva >= 5
                  AND nf.nota_definitiva < 10
                ORDER BY ia.id_estudiante, ia.id_carga";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
