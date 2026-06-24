<?php
class InscripcionController
{
    public function listar(): void
    {
        renderView('control/inscripciones', [
            'titulo' => 'Inscripciones',
            'flash' => getFlash()
        ]);
    }
    public function crear(): void
    {
        setFlash('success', 'Inscripción procesada.');
        redirect('control/inscripciones');
    }
}
