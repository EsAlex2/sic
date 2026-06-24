<?php
class FinanzasController
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function pagos(): void
    {
        $stmt = $this->conexion->query("
            SELECT p.*, a.nombre as arancel, a.monto,
                   dp.nombres, dp.apellidos, de.id_estudiante
            FROM unexca.pagos p
            JOIN unexca.aranceles a ON a.id_arancel = p.id_arancel
            JOIN unexca.datos_estudiantes de ON de.id_estudiante = p.id_estudiante
            JOIN unexca.datos_personas dp ON dp.id_persona = de.id_persona
            ORDER BY p.id_pago DESC
        ");
        $pagos = $stmt->fetchAll();

        // Obtener aranceles para el form
        $stmtAranceles = $this->conexion->query("SELECT * FROM unexca.aranceles ORDER BY nombre");
        $aranceles = $stmtAranceles->fetchAll();

        renderView('finanzas/pagos', [
            'titulo' => 'Gestión de Pagos',
            'pagos' => $pagos,
            'aranceles' => $aranceles,
            'flash' => getFlash()
        ]);
    }

    public function registrarPago(): void
    {
        setFlash('success', 'Pago registrado exitosamente (Simulado).');
        redirect('finanzas/pagos');
    }

    public function aranceles(): void
    {
        $stmt = $this->conexion->query("SELECT * FROM unexca.aranceles ORDER BY id_arancel DESC");
        $aranceles = $stmt->fetchAll();

        renderView('finanzas/aranceles', [
            'titulo' => 'Configuración de Aranceles',
            'aranceles' => $aranceles,
            'flash' => getFlash()
        ]);
    }

    public function crearArancel(): void
    {
        setFlash('success', 'Arancel creado exitosamente (Simulado).');
        redirect('finanzas/aranceles');
    }

    public function solvencias(): void
    {
        $stmt = $this->conexion->query("
            SELECT de.id_estudiante, dp.nombres, dp.apellidos,
                   (SELECT COUNT(*) FROM unexca.pagos p WHERE p.id_estudiante = de.id_estudiante AND p.estatus = 'Procesado') as pagos_realizados
            FROM unexca.datos_estudiantes de
            JOIN unexca.datos_personas dp ON dp.id_persona = de.id_persona
            ORDER BY dp.apellidos
        ");
        $estudiantes = $stmt->fetchAll();

        renderView('finanzas/solvencias', [
            'titulo' => 'Solvencias Administrativas',
            'estudiantes' => $estudiantes,
            'flash' => getFlash()
        ]);
    }

    public function verificarSolvencia(int $id): void
    {
        // ...
    }
}
