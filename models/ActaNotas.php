<?php
require_once __DIR__ . '/../init.php';

/**
 * Modelo para la gestión de actas de notas.
 *
 * Permite crear, consultar y cerrar actas de notas asociadas a cargas
 * académicas y periodos.
 *
 * Tabla: unexca.actas_notas
 */
class ActaNotas
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Crea una nueva acta de notas.
     *
     * @param int         $id_carga       Identificador de la carga académica
     * @param int         $id_periodo     Identificador del periodo académico
     * @param string      $cod_acta       Código único del acta
     * @param string      $fecha_cierre   Fecha de cierre del acta
     * @param int         $id_estatus     Identificador del estatus del acta
     * @param string|null $observaciones  Observaciones opcionales
     * @return int|false                  ID del acta creada o false si falla
     */
    public function crear(int $id_carga, int $id_periodo, string $cod_acta, string $fecha_cierre, int $id_estatus, ?string $observaciones): int|false
    {
        $sql = "INSERT INTO unexca.actas_notas
                    (id_carga, id_periodo, cod_acta, fecha_cierre, id_estatus, observaciones)
                VALUES
                    (:id_carga, :id_periodo, :cod_acta, :fecha_cierre, :id_estatus, :observaciones)
                RETURNING id_acta";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_carga', $id_carga, PDO::PARAM_INT);
        $stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->bindParam(':cod_acta', $cod_acta, PDO::PARAM_STR);
        $stmt->bindParam(':fecha_cierre', $fecha_cierre, PDO::PARAM_STR);
        $stmt->bindParam(':id_estatus', $id_estatus, PDO::PARAM_INT);
        $stmt->bindParam(':observaciones', $observaciones);

        if ($stmt->execute()) {
            $resultado = $stmt->fetch();
            return $resultado ? (int) $resultado['id_acta'] : false;
        }

        return false;
    }

    /**
     * Obtiene un acta de notas por su ID.
     *
     * @param int $id_acta Identificador del acta
     * @return array|false Datos del acta o false si no existe
     */
    public function obtenerPorId(int $id_acta): array|false
    {
        $sql = "SELECT * FROM unexca.actas_notas WHERE id_acta = :id_acta";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_acta', $id_acta, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch();
        return $resultado ?: false;
    }

    /**
     * Lista todas las actas de notas de un periodo.
     *
     * @param int $id_periodo Identificador del periodo académico
     * @return array          Lista de actas del periodo
     */
    public function listarPorPeriodo(int $id_periodo): array
    {
        $sql = "SELECT * FROM unexca.actas_notas
                WHERE id_periodo = :id_periodo
                ORDER BY id_acta";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Cierra un acta de notas.
     *
     * Cambia el estatus del acta a cerrada y registra el usuario que la cerró.
     * Se asume que el estatus de "cerrada" tiene un ID conocido en el sistema.
     *
     * @param int $id_acta   Identificador del acta
     * @param int $id_usuario Identificador del usuario que cierra el acta
     * @return bool           true si se cerró correctamente
     * @throws \RuntimeException Si el acta no existe o ya está cerrada
     */
    public function cerrarActa(int $id_acta, int $id_usuario): bool
    {
        // Verificar que el acta existe y está abierta
        $acta = $this->obtenerPorId($id_acta);
        if (!$acta) {
            throw new \RuntimeException("El acta con ID {$id_acta} no existe.");
        }

        $sql = "UPDATE unexca.actas_notas
                SET id_estatus = (
                        SELECT id_estatus FROM unexca.estatus
                        WHERE nombre = 'Cerrada' LIMIT 1
                    ),
                    cerrada_por = :id_usuario,
                    fecha_cierre = CURRENT_DATE
                WHERE id_acta = :id_acta";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_acta', $id_acta, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Verifica si un acta de notas está abierta.
     *
     * @param int $id_acta Identificador del acta
     * @return bool        true si el acta está abierta, false en caso contrario
     */
    public function estaAbierta(int $id_acta): bool
    {
        $sql = "SELECT e.nombre
                FROM unexca.actas_notas a
                INNER JOIN unexca.estatus e ON e.id_estatus = a.id_estatus
                WHERE a.id_acta = :id_acta";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_acta', $id_acta, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch();
        if (!$resultado) {
            return false;
        }

        return strtolower($resultado['nombre']) === 'abierta';
    }
}
