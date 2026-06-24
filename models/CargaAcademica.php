<?php
require_once __DIR__ . '/../init.php';

/**
 * Modelo para la gestión de carga académica.
 *
 * Permite crear, consultar, actualizar y eliminar registros de carga académica
 * que relacionan periodos, docentes, asignaturas, secciones y sedes.
 *
 * Tabla: unexca.carga_academica
 */
class CargaAcademica
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Crea un nuevo registro de carga académica.
     *
     * @param int $id_periodo   Identificador del periodo académico
     * @param int $id_docente   Identificador del docente
     * @param int $id_asignatura Identificador de la asignatura
     * @param int $id_seccion   Identificador de la sección
     * @param int $id_sede      Identificador de la sede
     * @param int $id_estatus   Identificador del estatus
     * @return int|false         ID del registro creado o false si falla
     */
    public function crear(int $id_periodo, int $id_docente, int $id_asignatura, int $id_seccion, int $id_sede, int $id_estatus): int|false
    {
        $sql = "INSERT INTO unexca.carga_academica
                    (id_periodo, id_docente, id_asignatura, id_seccion, id_sede, id_estatus)
                VALUES
                    (:id_periodo, :id_docente, :id_asignatura, :id_seccion, :id_sede, :id_estatus)
                RETURNING id_carga";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->bindParam(':id_docente', $id_docente, PDO::PARAM_INT);
        $stmt->bindParam(':id_asignatura', $id_asignatura, PDO::PARAM_INT);
        $stmt->bindParam(':id_seccion', $id_seccion, PDO::PARAM_INT);
        $stmt->bindParam(':id_sede', $id_sede, PDO::PARAM_INT);
        $stmt->bindParam(':id_estatus', $id_estatus, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $resultado = $stmt->fetch();
            return $resultado ? (int) $resultado['id_carga'] : false;
        }

        return false;
    }

    /**
     * Obtiene un registro de carga académica por su ID.
     *
     * @param int $id_carga Identificador de la carga académica
     * @return array|false  Datos del registro o false si no existe
     */
    public function obtenerPorId(int $id_carga): array|false
    {
        $sql = "SELECT * FROM unexca.carga_academica WHERE id_carga = :id_carga";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_carga', $id_carga, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch();
        return $resultado ?: false;
    }

    /**
     * Lista todas las cargas académicas de un periodo.
     *
     * @param int $id_periodo Identificador del periodo académico
     * @return array          Lista de registros de carga académica
     */
    public function listarPorPeriodo(int $id_periodo): array
    {
        $sql = "SELECT * FROM unexca.carga_academica
                WHERE id_periodo = :id_periodo
                ORDER BY id_carga";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Lista las cargas académicas de un docente en un periodo específico.
     *
     * @param int $id_docente Identificador del docente
     * @param int $id_periodo Identificador del periodo académico
     * @return array          Lista de registros de carga académica del docente
     */
    public function listarPorDocente(int $id_docente, int $id_periodo): array
    {
        $sql = "SELECT * FROM unexca.carga_academica
                WHERE id_docente = :id_docente AND id_periodo = :id_periodo
                ORDER BY id_carga";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_docente', $id_docente, PDO::PARAM_INT);
        $stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Actualiza un registro de carga académica.
     *
     * @param int   $id_carga Identificador de la carga académica
     * @param array $datos    Campos a actualizar (clave => valor)
     * @return bool           true si se actualizó correctamente
     */
    public function actualizar(int $id_carga, array $datos): bool
    {
        $camposPermitidos = ['id_periodo', 'id_docente', 'id_asignatura', 'id_seccion', 'id_sede', 'id_estatus'];
        $sets = [];
        $parametros = [':id_carga' => $id_carga];

        foreach ($datos as $campo => $valor) {
            if (in_array($campo, $camposPermitidos, true)) {
                $sets[] = "$campo = :$campo";
                $parametros[":$campo"] = $valor;
            }
        }

        if (empty($sets)) {
            return false;
        }

        $sql = "UPDATE unexca.carga_academica
                SET " . implode(', ', $sets) . "
                WHERE id_carga = :id_carga";

        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute($parametros);
    }

    /**
     * Elimina un registro de carga académica.
     *
     * @param int $id_carga Identificador de la carga académica
     * @return bool         true si se eliminó correctamente
     */
    public function eliminar(int $id_carga): bool
    {
        $sql = "DELETE FROM unexca.carga_academica WHERE id_carga = :id_carga";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_carga', $id_carga, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
