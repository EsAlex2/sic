<?php
require_once __DIR__ . '/../init.php';

/**
 * Modelo para la gestión de notas finales.
 *
 * Permite registrar, consultar y generar en lote las notas finales de los
 * estudiantes asociadas a un acta de notas.
 *
 * Tabla: unexca.notas_finales
 */
class NotaFinal
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Registra una nota final individual.
     *
     * @param int         $id_acta             Identificador del acta de notas
     * @param int         $id_inscripcion_asig Identificador de la inscripción
     * @param float       $nota_definitiva     Nota definitiva calculada
     * @param int         $id_estatus          Identificador del estatus (Aprobado/Reprobado)
     * @param string|null $observacion         Observación opcional
     * @return int|false                       ID de la nota final creada o false si falla
     */
    public function registrar(int $id_acta, int $id_inscripcion_asig, float $nota_definitiva, int $id_estatus, ?string $observacion): int|false
    {
        $sql = "INSERT INTO unexca.notas_finales
                    (id_acta, id_inscripcion_asig, nota_definitiva, id_estatus, observacion)
                VALUES
                    (:id_acta, :id_inscripcion_asig, :nota_definitiva, :id_estatus, :observacion)
                RETURNING id_nota_final";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_acta', $id_acta, PDO::PARAM_INT);
        $stmt->bindParam(':id_inscripcion_asig', $id_inscripcion_asig, PDO::PARAM_INT);
        $stmt->bindParam(':nota_definitiva', $nota_definitiva);
        $stmt->bindParam(':id_estatus', $id_estatus, PDO::PARAM_INT);
        $stmt->bindParam(':observacion', $observacion);

        if ($stmt->execute()) {
            $resultado = $stmt->fetch();
            return $resultado ? (int) $resultado['id_nota_final'] : false;
        }

        return false;
    }

    /**
     * Obtiene una nota final por su ID.
     *
     * @param int $id_nota_final Identificador de la nota final
     * @return array|false       Datos de la nota final o false si no existe
     */
    public function obtenerPorId(int $id_nota_final): array|false
    {
        $sql = "SELECT * FROM unexca.notas_finales WHERE id_nota_final = :id_nota_final";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_nota_final', $id_nota_final, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch();
        return $resultado ?: false;
    }

    /**
     * Lista todas las notas finales de un acta.
     *
     * @param int $id_acta Identificador del acta de notas
     * @return array       Lista de notas finales del acta
     */
    public function listarPorActa(int $id_acta): array
    {
        $sql = "SELECT * FROM unexca.notas_finales
                WHERE id_acta = :id_acta
                ORDER BY id_nota_final";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_acta', $id_acta, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Lista todas las notas finales de un estudiante (JOIN con inscripcion_asignatura).
     *
     * @param int $id_estudiante Identificador del estudiante
     * @return array             Lista de notas finales del estudiante
     */
    public function listarPorEstudiante(int $id_estudiante): array
    {
        $sql = "SELECT nf.*
                FROM unexca.notas_finales nf
                INNER JOIN unexca.inscripcion_asignatura ia
                    ON ia.id_inscripcion_asig = nf.id_inscripcion_asig
                WHERE ia.id_estudiante = :id_estudiante
                ORDER BY nf.id_nota_final";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Genera las notas finales en lote para todos los estudiantes de una carga académica.
     *
     * Calcula la nota definitiva de cada estudiante usando la lógica de
     * SUM(nota * porcentaje / 100). Asigna estatus Aprobado si la nota >= 10,
     * Reprobado si < 10. Ejecuta todo dentro de una transacción.
     *
     * @param int $id_carga Identificador de la carga académica
     * @param int $id_acta  Identificador del acta de notas
     * @return bool         true si se generaron correctamente
     * @throws \RuntimeException Si no hay estudiantes inscritos o falla el proceso
     */
    public function generarNotasFinales(int $id_carga, int $id_acta): bool
    {
        $this->conexion->beginTransaction();

        try {
            // Obtener todos los estudiantes inscritos en esta carga
            $sqlEstudiantes = "SELECT id_inscripcion_asig
                               FROM unexca.inscripcion_asignatura
                               WHERE id_carga = :id_carga";

            $stmtEstudiantes = $this->conexion->prepare($sqlEstudiantes);
            $stmtEstudiantes->bindParam(':id_carga', $id_carga, PDO::PARAM_INT);
            $stmtEstudiantes->execute();

            $inscripciones = $stmtEstudiantes->fetchAll();

            if (empty($inscripciones)) {
                throw new \RuntimeException("No hay estudiantes inscritos en la carga académica {$id_carga}.");
            }

            // Obtener IDs de estatus Aprobado y Reprobado
            $sqlEstatus = "SELECT id_estatus, nombre_estatus FROM unexca.estatus
                           WHERE nombre_estatus IN ('Aprobado', 'Reprobado')";
            $stmtEstatus = $this->conexion->prepare($sqlEstatus);
            $stmtEstatus->execute();

            $estatusMap = [];
            while ($fila = $stmtEstatus->fetch()) {
                $estatusMap[$fila['nombre_estatus']] = (int) $fila['id_estatus'];
            }

            if (!isset($estatusMap['Aprobado'], $estatusMap['Reprobado'])) {
                throw new \RuntimeException("No se encontraron los estatus 'Aprobado' y 'Reprobado' en la tabla de estatus.");
            }

            // Preparar consultas reutilizables
            $sqlNota = "SELECT COALESCE(SUM(c.nota * pe.porcentaje / 100), 0) AS nota_definitiva
                        FROM unexca.calificaciones c
                        INNER JOIN unexca.plan_evaluacion pe ON pe.id_plan = c.id_plan
                        WHERE c.id_inscripcion_asig = :id_inscripcion_asig";

            $stmtNota = $this->conexion->prepare($sqlNota);

            $sqlInsert = "INSERT INTO unexca.notas_finales
                              (id_acta, id_inscripcion_asig, nota_definitiva, id_estatus, observacion)
                          VALUES
                              (:id_acta, :id_inscripcion_asig, :nota_definitiva, :id_estatus, NULL)";

            $stmtInsert = $this->conexion->prepare($sqlInsert);

            // Procesar cada inscripción
            foreach ($inscripciones as $inscripcion) {
                $idInscripcion = (int) $inscripcion['id_inscripcion_asig'];

                // Calcular nota definitiva
                $stmtNota->bindParam(':id_inscripcion_asig', $idInscripcion, PDO::PARAM_INT);
                $stmtNota->execute();
                $resultadoNota = $stmtNota->fetch();
                $notaDefinitiva = (float) $resultadoNota['nota_definitiva'];

                // Determinar estatus
                $idEstatus = $notaDefinitiva >= 10
                    ? $estatusMap['Aprobado']
                    : $estatusMap['Reprobado'];

                // Insertar nota final
                $stmtInsert->bindParam(':id_acta', $id_acta, PDO::PARAM_INT);
                $stmtInsert->bindParam(':id_inscripcion_asig', $idInscripcion, PDO::PARAM_INT);
                $stmtInsert->bindParam(':nota_definitiva', $notaDefinitiva);
                $stmtInsert->bindParam(':id_estatus', $idEstatus, PDO::PARAM_INT);
                $stmtInsert->execute();
            }

            $this->conexion->commit();
            return true;

        } catch (\Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }
}
