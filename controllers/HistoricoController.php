<?php
class HistoricoController
{
    public function historico(): void
    {
        renderView('estudiante/historico', [
            'titulo' => 'Histórico Académico',
            'flash' => getFlash()
        ]);
    }

    public function indice(): void
    {
        renderView('estudiante/indice', [
            'titulo' => 'Índice Académico',
            'flash' => getFlash()
        ]);
    }
}
