<?php
class PlanEvalController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function mostrar(int $id_carga): void
    {
        renderView('docente/plan_evaluacion', [
            'titulo' => 'Plan de Evaluación',
            'id_carga' => $id_carga,
            'flash' => getFlash()
        ]);
    }

    public function guardar(int $id_carga): void
    {
        setFlash('success', 'Plan guardado exitosamente.');
        redirect('docente/plan-evaluacion/' . $id_carga);
    }
}
