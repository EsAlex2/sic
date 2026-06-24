<?php
require_once __DIR__ . '/../init.php';

/**
 * Modelo para la gestión del histórico académico.
 *
 * Permite registrar y consultar el historial de notas de un estudiante
 * a lo largo de los periodos académicos, incluyendo el conteo de intentos.
 *
 * Tabla: unexca.historico_academico
 */
class HistoricoAcademico
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Registra una entrada en el histórico académico.
     *
     * @param int   $id_estudiante Identificador del estudiante
     * @param int   $id_periodo    Identificador del periodo académico
     * @param int   $id_asignatura Identificador de la asignatura
     * @param float $nota          Nota obtenida
     * @param int   $uc            Unidades de crédito de la asignatura
     * @param int   $id_estatus    Identificador del estatus (Aprobado/Reprobado)
     * @param int   $intento       Número de intento
     * @return int|false           ID del registro creado o false si falla
     */
    public function registrar(int $id_estudiante, int $id_periodo, int $id_asignatura, float $nota, int $uc, int $id_estatus, int $intento): int|false
    {
        $sql = "INSERT INTO unexca.historico_academico
                    (id_estudiante, id_periodo, id_asignatura, nota, uc, id_estatus, intento)
                VALUES
                    (:id_estudiante, :id_periodo, :id_asignatura, :nota, :uc, :id_estatus, :intento)
                RETURNING id_historico";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->bindParam(':id_asignatura', $id_asignatura, PDO::PARAM_INT);
        $stmt->bindParam(':nota', $nota);
        $stmt->bindParam(':uc', $uc, PDO::PARAM_INT);
        $stmt->bindParam(':id_estatus', $id_estatus, PDO::PARAM_INT);
        $stmt->bindParam(':intento', $intento, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $resultado = $stmt->fetch();
            return $resultado ? (int) $resultado['id_historico'] : false;
        }

        return false;
    }

    /**
     * Obtiene todo el histórico académico de un estudiante.
     *
     * Ordenado por periodo y asignatura.
     *
     * @param int $id_estudiante Identificador del estudiante
     * @return array             Lista de registros del histórico académico
     */
    public function obtenerPorEstudiante(int $id_estudiante): array
    {
        $sql = "SELECT * FROM unexca.historico_academico
                WHERE id_estudiante = :id_estudiante
                ORDER BY id_periodo, id_asignatura";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Obtiene el histórico académico de un estudiante en un periodo específico.
     *
     * @param int $id_estudiante Identificador del estudiante
     * @param int $id_periodo    Identificador del periodo académico
     * @return array             Lista de registros del periodo
     */
    public function obtenerPorPeriodo(int $id_estudiante, int $id_periodo): array
    {
        $sql = "SELECT * FROM unexca.historico_academico
                WHERE id_estudiante = :id_estudiante AND id_periodo = :id_periodo
                ORDER BY id_asignatura";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Cuenta el número de intentos de un estudiante en una asignatura.
     *
     * @param int $id_estudiante Identificador del estudiante
     * @param int $id_asignatura Identificador de la asignatura
     * @return int               Número de intentos registrados
     */
    public function contarIntentos(int $id_estudiante, int $id_asignatura): int
    {
        $sql = "SELECT COUNT(*) AS total_intentos
                FROM unexca.historico_academico
                WHERE id_estudiante = :id_estudiante AND id_asignatura = :id_asignatura";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->bindParam(':id_asignatura', $id_asignatura, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch();
        return (int) $resultado['total_intentos'];
    }
}
