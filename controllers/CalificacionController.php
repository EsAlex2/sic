<?php
class CalificacionController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function mostrar(int $id_carga): void
    {
        renderView('docente/calificaciones', [
            'titulo' => 'Cargar Calificaciones',
            'id_carga' => $id_carga,
            'flash' => getFlash()
        ]);
    }

    public function guardar(int $id_carga): void
    {
        setFlash('success', 'Calificaciones guardadas exitosamente.');
        redirect('docente/calificaciones/' . $id_carga);
    }
}
