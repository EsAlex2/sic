<div class="page-header">
    <div>
        <h1>Gestión de Reparaciones</h1>
        <p class="page-subtitle">Solicitudes y seguimiento de exámenes de reparación</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalNuevaReparacion')">➕ Registrar Solicitud</button>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Período</th>
                    <th>Estudiante</th>
                    <th>Asignatura</th>
                    <th>Nota Original</th>
                    <th>Nota Reparación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reparaciones)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No hay reparaciones registradas.</td></tr>
                <?php else: ?>
                    <?php foreach ($reparaciones as $r): ?>
                        <tr>
                            <td><span class="badge badge-info"><?= htmlspecialchars($r['periodo']) ?></span></td>
                            <td><?= htmlspecialchars(trim($r['nombres'] . ' ' . $r['apellidos'])) ?></td>
                            <td class="font-bold"><?= htmlspecialchars($r['asignatura']) ?></td>
                            <td class="text-danger"><?= number_format($r['nota_original'], 2) ?></td>
                            <td>
                                <?php if ($r['nota_reparacion'] !== null): ?>
                                    <strong class="text-<?= $r['nota_reparacion'] >= 10 ? 'success' : 'danger' ?>">
                                        <?= number_format($r['nota_reparacion'], 2) ?>
                                    </strong>
                                <?php else: ?>
                                    <span class="text-muted">Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($r['nota_reparacion'] !== null): ?>
                                    <span class="badge badge-success">Calificado</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">En proceso</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline">Ver Detalle</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nueva Reparacion -->
<div class="modal-overlay" id="modalNuevaReparacion">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Solicitar Reparación</h3>
            <button class="modal-close" onclick="closeModal('modalNuevaReparacion')">×</button>
        </div>
        <form action="<?= url('reparaciones/solicitar') ?>" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">ID Inscripción Asignatura (Demo)</label>
                    <input type="number" name="id_inscripcion" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">ID Período</label>
                    <input type="number" name="id_periodo" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nota Original (Entre 5 y 9.99)</label>
                    <input type="number" step="0.01" name="nota_original" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalNuevaReparacion')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar Solicitud</button>
            </div>
        </form>
    </div>
</div>
