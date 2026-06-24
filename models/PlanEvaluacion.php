<?php
require_once __DIR__ . '/../init.php';

/**
 * Modelo para la gestión de planes de evaluación.
 *
 * Permite crear y administrar evaluaciones asociadas a una carga académica,
 * incluyendo validación de porcentajes por corte.
 *
 * Tabla: unexca.plan_evaluacion
 */
class PlanEvaluacion
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Crea un nuevo plan de evaluación.
     *
     * @param int         $id_carga    Identificador de la carga académica
     * @param int         $id_tipo_eval Identificador del tipo de evaluación
     * @param string      $nombre      Nombre de la evaluación
     * @param float       $porcentaje  Porcentaje asignado a la evaluación
     * @param string|null $fecha       Fecha programada para la evaluación (opcional)
     * @param int         $nro_corte   Número de corte (1, 2 o 3)
     * @return int|false               ID del plan creado o false si falla
     */
    public function crear(int $id_carga, int $id_tipo_eval, string $nombre, float $porcentaje, ?string $fecha, int $nro_corte): int|false
    {
        $sql = "INSERT INTO unexca.plan_evaluacion
                    (id_carga, id_tipo_eval, nombre_evaluacion, porcentaje, fecha_evaluacion, nro_corte)
                VALUES
                    (:id_carga, :id_tipo_eval, :nombre, :porcentaje, :fecha, :nro_corte)
                RETURNING id_plan";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_carga', $id_carga, PDO::PARAM_INT);
        $stmt->bindParam(':id_tipo_eval', $id_tipo_eval, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':porcentaje', $porcentaje);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':nro_corte', $nro_corte, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $resultado = $stmt->fetch();
            return $resultado ? (int) $resultado['id_plan'] : false;
        }

        return false;
    }

    /**
     * Obtiene un plan de evaluación por su ID.
     *
     * @param int $id_plan Identificador del plan de evaluación
     * @return array|false Datos del plan o false si no existe
     */
    public function obtenerPorId(int $id_plan): array|false
    {
        $sql = "SELECT * FROM unexca.plan_evaluacion WHERE id_plan = :id_plan";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_plan', $id_plan, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch();
        return $resultado ?: false;
    }

    /**
     * Lista todos los planes de evaluación de una carga académica.
     *
     * @param int $id_carga Identificador de la carga académica
     * @return array        Lista de planes de evaluación
     */
    public function listarPorCarga(int $id_carga): array
    {
        $sql = "SELECT * FROM unexca.plan_evaluacion
                WHERE id_carga = :id_carga
                ORDER BY nro_corte, id_plan";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_carga', $id_carga, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Lista los planes de evaluación de una carga académica para un corte específico.
     *
     * @param int $id_carga  Identificador de la carga académica
     * @param int $nro_corte Número de corte (1, 2 o 3)
     * @return array         Lista de planes de evaluación del corte
     */
    public function listarPorCorte(int $id_carga, int $nro_corte): array
    {
        $sql = "SELECT * FROM unexca.plan_evaluacion
                WHERE id_carga = :id_carga AND nro_corte = :nro_corte
                ORDER BY id_plan";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_carga', $id_carga, PDO::PARAM_INT);
        $stmt->bindParam(':nro_corte', $nro_corte, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Valida la suma de porcentajes de una carga académica.
     *
     * Verifica que la suma total de porcentajes sea 100% y desglosa por corte.
     *
     * @param int $id_carga Identificador de la carga académica
     * @return array        Resultado con claves: 'valido' (bool), 'total' (float),
     *                      'por_corte' (array con totales por corte 1, 2 y 3)
     */
    public function validarSumaPorcentajes(int $id_carga): array
    {
        // Obtener suma total
        $sqlTotal = "SELECT COALESCE(SUM(porcentaje), 0) AS total
                     FROM unexca.plan_evaluacion
                     WHERE id_carga = :id_carga";

        $stmtTotal = $this->conexion->prepare($sqlTotal);
        $stmtTotal->bindParam(':id_carga', $id_carga, PDO::PARAM_INT);
        $stmtTotal->execute();
        $total = (float) $stmtTotal->fetch()['total'];

        // Obtener suma por corte
        $sqlCorte = "SELECT nro_corte, COALESCE(SUM(porcentaje), 0) AS total_corte
                     FROM unexca.plan_evaluacion
                     WHERE id_carga = :id_carga
                     GROUP BY nro_corte
                     ORDER BY nro_corte";

        $stmtCorte = $this->conexion->prepare($sqlCorte);
        $stmtCorte->bindParam(':id_carga', $id_carga, PDO::PARAM_INT);
        $stmtCorte->execute();

        $porCorte = [1 => 0.0, 2 => 0.0, 3 => 0.0];
        while ($fila = $stmtCorte->fetch()) {
            $porCorte[(int) $fila['nro_corte']] = (float) $fila['total_corte'];
        }

        return [
            'valido'    => abs($total - 100.0) < 0.01,
            'total'     => $total,
            'por_corte' => $porCorte,
        ];
    }

    /**
     * Actualiza un plan de evaluación.
     *
     * @param int   $id_plan Identificador del plan de evaluación
     * @param array $datos   Campos a actualizar (clave => valor)
     * @return bool          true si se actualizó correctamente
     */
    public function actualizar(int $id_plan, array $datos): bool
    {
        $camposPermitidos = ['id_tipo_eval', 'nombre_evaluacion', 'porcentaje', 'fecha_evaluacion', 'nro_corte'];
        $sets = [];
        $parametros = [':id_plan' => $id_plan];

        foreach ($datos as $campo => $valor) {
            if (in_array($campo, $camposPermitidos, true)) {
                $sets[] = "$campo = :$campo";
                $parametros[":$campo"] = $valor;
            }
        }

        if (empty($sets)) {
            return false;
        }

        $sql = "UPDATE unexca.plan_evaluacion
                SET " . implode(', ', $sets) . "
                WHERE id_plan = :id_plan";

        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute($parametros);
    }

    /**
     * Elimina un plan de evaluación.
     *
     * @param int $id_plan Identificador del plan de evaluación
     * @return bool        true si se eliminó correctamente
     */
    public function eliminar(int $id_plan): bool
    {
        $sql = "DELETE FROM unexca.plan_evaluacion WHERE id_plan = :id_plan";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_plan', $id_plan, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
