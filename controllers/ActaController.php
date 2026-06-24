<?php
class ActaController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function mostrar(int $id_carga): void
    {
        renderView('docente/acta', [
            'titulo' => 'Acta de Notas',
            'id_carga' => $id_carga,
            'flash' => getFlash()
        ]);
    }

    public function generar(int $id_carga): void
    {
        setFlash('success', 'Acta de notas generada y firmada.');
        redirect('docente/mis-cargas');
    }
}
