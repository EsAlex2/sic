<div class="page-header">
    <div>
        <h1>Solvencias Administrativas</h1>
        <p class="page-subtitle">Verifica el estado de cuenta de los estudiantes</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex align-center gap-2">
            <input type="text" class="form-control" placeholder="Buscar por cédula o nombre..." style="max-width: 300px;">
            <button class="btn btn-primary">Buscar</button>
        </div>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID Estudiante</th>
                    <th>Nombre Completo</th>
                    <th>Pagos Registrados</th>
                    <th>Estado de Solvencia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($estudiantes)): ?>
                    <tr><td colspan="5" class="text-center text-muted">No hay estudiantes registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($estudiantes as $e): ?>
                        <tr>
                            <td><?= $e['id_estudiante'] ?></td>
                            <td><?= htmlspecialchars(trim($e['nombres'] . ' ' . $e['apellidos'])) ?></td>
                            <td><?= $e['pagos_realizados'] ?></td>
                            <td>
                                <?php if ($e['pagos_realizados'] > 0): ?>
                                    <span class="badge badge-success">Solvente</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Sin Pagos</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline">Ver Detalle</button>
                                <button class="btn btn-sm btn-primary">Emitir Certificado</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
