<?php
class EstudianteController
{
    public function misNotas(): void
    {
        renderView('estudiante/notas', [
            'titulo' => 'Mis Notas por Corte',
            'flash' => getFlash()
        ]);
    }
}
