<?php
require_once __DIR__ . '/../init.php';

/**
 * Modelo para la gestión del índice académico.
 *
 * Permite calcular, guardar y consultar el promedio ponderado de un estudiante
 * tanto por periodo como acumulado.
 *
 * Tabla: unexca.indice_academico
 */
class IndiceAcademico
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Calcula y guarda el índice académico de un estudiante para un periodo.
     *
     * Calcula el promedio del periodo como SUM(nota * UC) / SUM(UC) usando
     * el histórico académico. También calcula el promedio acumulado considerando
     * todos los periodos del estudiante.
     *
     * Si ya existe un registro para el estudiante y periodo, lo actualiza;
     * de lo contrario, inserta uno nuevo.
     *
     * @param int $id_estudiante Identificador del estudiante
     * @param int $id_periodo    Identificador del periodo académico
     * @return bool              true si se calculó y guardó correctamente
     */
    public function calcularYGuardar(int $id_estudiante, int $id_periodo): bool
    {
        $this->conexion->beginTransaction();

        try {
            // Calcular promedio del periodo
            $sqlPeriodo = "SELECT
                                CASE WHEN SUM(unidades_credito) > 0
                                     THEN ROUND(SUM(nota_definitiva * unidades_credito)::NUMERIC / SUM(unidades_credito), 2)
                                     ELSE 0
                                END AS promedio_periodo,
                                COALESCE(SUM(unidades_credito), 0) AS creditos_cursados_periodo
                           FROM unexca.historico_academico
                           WHERE id_estudiante = :id_estudiante
                             AND id_periodo = :id_periodo";

            $stmtPeriodo = $this->conexion->prepare($sqlPeriodo);
            $stmtPeriodo->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
            $stmtPeriodo->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
            $stmtPeriodo->execute();

            $datosPeriodo = $stmtPeriodo->fetch();
            $promedioPeriodo = (float) $datosPeriodo['promedio_periodo'];
            $creditosCursados = (int) $datosPeriodo['creditos_cursados_periodo'];

            // Calcular promedio acumulado (todos los periodos)
            $sqlAcumulado = "SELECT
                                  CASE WHEN SUM(unidades_credito) > 0
                                       THEN ROUND(SUM(nota_definitiva * unidades_credito)::NUMERIC / SUM(unidades_credito), 2)
                                       ELSE 0
                                  END AS promedio_acumulado,
                                  COALESCE(SUM(CASE WHEN h.id_estatus = (SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = 'Aprobado' LIMIT 1) THEN unidades_credito ELSE 0 END), 0) AS creditos_aprobados,
                                  COALESCE(SUM(unidades_credito), 0) AS creditos_cursados
                             FROM unexca.historico_academico h
                             WHERE h.id_estudiante = :id_estudiante";

            $stmtAcumulado = $this->conexion->prepare($sqlAcumulado);
            $stmtAcumulado->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
            $stmtAcumulado->execute();

            $datosAcumulado = $stmtAcumulado->fetch();
            $promedioAcumulado = (float) $datosAcumulado['promedio_acumulado'];
            $creditosAprobados = (int) $datosAcumulado['creditos_aprobados'];
            $creditosCursadosTotal = (int) $datosAcumulado['creditos_cursados'];

            // Verificar si ya existe un registro para este estudiante y periodo
            $sqlExiste = "SELECT id_indice FROM unexca.indice_academico
                          WHERE id_estudiante = :id_estudiante AND id_periodo = :id_periodo";

            $stmtExiste = $this->conexion->prepare($sqlExiste);
            $stmtExiste->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
            $stmtExiste->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
            $stmtExiste->execute();

            $existe = $stmtExiste->fetch();

            if ($existe) {
                // Actualizar registro existente
                $sqlUpdate = "UPDATE unexca.indice_academico
                              SET promedio_periodo = :promedio_periodo,
                                  promedio_acumulado = :promedio_acumulado,
                                  creditos_aprobados = :creditos_aprobados,
                                  creditos_cursados = :creditos_cursados,
                                  calculado_en = CURRENT_TIMESTAMP
                              WHERE id_estudiante = :id_estudiante AND id_periodo = :id_periodo";

                $stmtUpdate = $this->conexion->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':promedio_periodo', $promedioPeriodo);
                $stmtUpdate->bindParam(':promedio_acumulado', $promedioAcumulado);
                $stmtUpdate->bindParam(':creditos_aprobados', $creditosAprobados, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':creditos_cursados', $creditosCursadosTotal, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
                $stmtUpdate->execute();
            } else {
                // Insertar nuevo registro
                $sqlInsert = "INSERT INTO unexca.indice_academico
                                  (id_estudiante, id_periodo, promedio_periodo, promedio_acumulado,
                                   creditos_aprobados, creditos_cursados)
                              VALUES
                                  (:id_estudiante, :id_periodo, :promedio_periodo, :promedio_acumulado,
                                   :creditos_aprobados, :creditos_cursados)";

                $stmtInsert = $this->conexion->prepare($sqlInsert);
                $stmtInsert->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
                $stmtInsert->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
                $stmtInsert->bindParam(':promedio_periodo', $promedioPeriodo);
                $stmtInsert->bindParam(':promedio_acumulado', $promedioAcumulado);
                $stmtInsert->bindParam(':creditos_aprobados', $creditosAprobados, PDO::PARAM_INT);
                $stmtInsert->bindParam(':creditos_cursados', $creditosCursadosTotal, PDO::PARAM_INT);
                $stmtInsert->execute();
            }

            $this->conexion->commit();
            return true;

        } catch (\Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene todos los registros de índice académico de un estudiante.
     *
     * @param int $id_estudiante Identificador del estudiante
     * @return array             Lista de registros de índice académico
     */
    public function obtenerPorEstudiante(int $id_estudiante): array
    {
        $sql = "SELECT * FROM unexca.indice_academico
                WHERE id_estudiante = :id_estudiante
                ORDER BY id_periodo";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Obtiene el último registro de índice académico de un estudiante.
     *
     * @param int $id_estudiante Identificador del estudiante
     * @return array|false       Último registro o false si no existe
     */
    public function obtenerUltimo(int $id_estudiante): array|false
    {
        $sql = "SELECT * FROM unexca.indice_academico
                WHERE id_estudiante = :id_estudiante
                ORDER BY id_periodo DESC
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch();
        return $resultado ?: false;
    }
}
