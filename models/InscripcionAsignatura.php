<?php
require_once __DIR__ . '/../init.php';

/**
 * Modelo para la gestión de inscripciones de asignaturas.
 *
 * Permite inscribir estudiantes en cargas académicas, consultar inscripciones
 * y gestionar el estatus de las mismas.
 *
 * Tabla: unexca.inscripcion_asignatura
 */
class InscripcionAsignatura
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Inscribe a un estudiante en una carga académica.
     *
     * @param int $id_estudiante Identificador del estudiante
     * @param int $id_carga      Identificador de la carga académica
     * @param int $id_estatus    Identificador del estatus de inscripción
     * @return int|false          ID de la inscripción creada o false si falla
     */
    public function inscribir(int $id_estudiante, int $id_carga, int $id_estatus): int|false
    {
        $sql = "INSERT INTO unexca.inscripcion_asignatura
                    (id_estudiante, id_carga, id_estatus)
                VALUES
                    (:id_estudiante, :id_carga, :id_estatus)
                RETURNING id_inscripcion_asig";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->bindParam(':id_carga', $id_carga, PDO::PARAM_INT);
        $stmt->bindParam(':id_estatus', $id_estatus, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $resultado = $stmt->fetch();
            return $resultado ? (int) $resultado['id_inscripcion_asig'] : false;
        }

        return false;
    }

    /**
     * Obtiene una inscripción de asignatura por su ID.
     *
     * @param int $id_inscripcion_asig Identificador de la inscripción
     * @return array|false             Datos de la inscripción o false si no existe
     */
    public function obtenerPorId(int $id_inscripcion_asig): array|false
    {
        $sql = "SELECT * FROM unexca.inscripcion_asignatura
                WHERE id_inscripcion_asig = :id_inscripcion_asig";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_inscripcion_asig', $id_inscripcion_asig, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch();
        return $resultado ?: false;
    }

    /**
     * Lista todas las inscripciones de un estudiante.
     *
     * @param int $id_estudiante Identificador del estudiante
     * @return array             Lista de inscripciones del estudiante
     */
    public function listarPorEstudiante(int $id_estudiante): array
    {
        $sql = "SELECT * FROM unexca.inscripcion_asignatura
                WHERE id_estudiante = :id_estudiante
                ORDER BY id_inscripcion_asig";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Lista todas las inscripciones de una carga académica.
     *
     * @param int $id_carga Identificador de la carga académica
     * @return array        Lista de inscripciones de la carga
     */
    public function listarPorCarga(int $id_carga): array
    {
        $sql = "SELECT * FROM unexca.inscripcion_asignatura
                WHERE id_carga = :id_carga
                ORDER BY id_inscripcion_asig";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_carga', $id_carga, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Cambia el estatus de una inscripción de asignatura.
     *
     * @param int $id_inscripcion_asig Identificador de la inscripción
     * @param int $id_estatus          Nuevo identificador de estatus
     * @return bool                    true si se actualizó correctamente
     */
    public function cambiarEstatus(int $id_inscripcion_asig, int $id_estatus): bool
    {
        $sql = "UPDATE unexca.inscripcion_asignatura
                SET id_estatus = :id_estatus
                WHERE id_inscripcion_asig = :id_inscripcion_asig";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_inscripcion_asig', $id_inscripcion_asig, PDO::PARAM_INT);
        $stmt->bindParam(':id_estatus', $id_estatus, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Elimina una inscripción de asignatura.
     *
     * @param int $id_inscripcion_asig Identificador de la inscripción
     * @return bool                    true si se eliminó correctamente
     */
    public function eliminar(int $id_inscripcion_asig): bool
    {
        $sql = "DELETE FROM unexca.inscripcion_asignatura
                WHERE id_inscripcion_asig = :id_inscripcion_asig";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_inscripcion_asig', $id_inscripcion_asig, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
