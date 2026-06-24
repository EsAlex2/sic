<?php
require_once __DIR__ . '/../init.php';

/**
 * Modelo para la gestión de auditoría de notas.
 *
 * Permite registrar y consultar los cambios realizados a las calificaciones,
 * manteniendo un historial completo de modificaciones con la nota anterior,
 * nueva, motivo y usuario responsable.
 *
 * Tabla: unexca.auditoria_notas
 */
class Auditoria
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    /**
     * Registra una entrada de auditoría de cambio de nota.
     *
     * @param int    $id_calificacion Identificador de la calificación modificada
     * @param float  $nota_anterior   Nota antes de la modificación
     * @param float  $nota_nueva      Nota después de la modificación
     * @param string $motivo          Motivo del cambio
     * @param int    $id_usuario      Identificador del usuario que realizó el cambio
     * @return int|false              ID del registro de auditoría o false si falla
     */
    public function registrar(int $id_calificacion, float $nota_anterior, float $nota_nueva, string $motivo, int $id_usuario): int|false
    {
        $sql = "INSERT INTO unexca.auditoria_notas
                    (id_calificacion, nota_anterior, nota_nueva, motivo, id_usuario)
                VALUES
                    (:id_calificacion, :nota_anterior, :nota_nueva, :motivo, :id_usuario)
                RETURNING id_auditoria";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
        $stmt->bindParam(':nota_anterior', $nota_anterior);
        $stmt->bindParam(':nota_nueva', $nota_nueva);
        $stmt->bindParam(':motivo', $motivo, PDO::PARAM_STR);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $resultado = $stmt->fetch();
            return $resultado ? (int) $resultado['id_auditoria'] : false;
        }

        return false;
    }

    /**
     * Lista todos los registros de auditoría de una calificación.
     *
     * @param int $id_calificacion Identificador de la calificación
     * @return array               Lista de registros de auditoría
     */
    public function listarPorCalificacion(int $id_calificacion): array
    {
        $sql = "SELECT * FROM unexca.auditoria_notas
                WHERE id_calificacion = :id_calificacion
                ORDER BY id_auditoria";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Lista todos los registros de auditoría realizados por un usuario.
     *
     * @param int $id_usuario Identificador del usuario
     * @return array          Lista de registros de auditoría del usuario
     */
    public function listarPorUsuario(int $id_usuario): array
    {
        $sql = "SELECT * FROM unexca.auditoria_notas
                WHERE id_usuario = :id_usuario
                ORDER BY id_auditoria";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Lista registros de auditoría dentro de un rango de fechas.
     *
     * @param string $fecha_inicio Fecha de inicio del rango (formato YYYY-MM-DD)
     * @param string $fecha_fin    Fecha de fin del rango (formato YYYY-MM-DD)
     * @return array               Lista de registros de auditoría en el rango
     */
    public function listarPorFecha(string $fecha_inicio, string $fecha_fin): array
    {
        $sql = "SELECT * FROM unexca.auditoria_notas
                WHERE fecha_modificacion::date BETWEEN :fecha_inicio AND :fecha_fin
                ORDER BY fecha_modificacion, id_auditoria";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $stmt->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
