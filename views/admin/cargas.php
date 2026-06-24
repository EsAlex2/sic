<div class="page-header">
    <div>
        <h1>Carga Académica</h1>
        <p class="page-subtitle">Asignación de asignaturas a docentes por sección</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalNuevaCarga')">➕ Asignar Carga</button>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Período</th>
                    <th>Docente</th>
                    <th>Asignatura</th>
                    <th>Sección</th>
                    <th>Sede</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($cargas)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No hay cargas asignadas.</td></tr>
                <?php else: ?>
                    <?php foreach ($cargas as $c): ?>
                        <tr>
                            <td><span class="badge badge-info"><?= htmlspecialchars($c['periodo']) ?></span></td>
                            <td><?= htmlspecialchars(trim($c['docente_nombres'] . ' ' . $c['docente_apellidos'])) ?></td>
                            <td class="font-bold text-primary"><?= htmlspecialchars($c['asignatura']) ?></td>
                            <td><span class="badge badge-purple"><?= htmlspecialchars($c['seccion']) ?></span></td>
                            <td><?= htmlspecialchars($c['sede']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline">Editar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nueva Carga -->
<div class="modal-overlay" id="modalNuevaCarga">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Asignar Carga Académica</h3>
            <button class="modal-close" onclick="closeModal('modalNuevaCarga')">×</button>
        </div>
        <form action="<?= url('admin/cargas/crear') ?>" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">ID Período (Demo)</label>
                    <input type="number" name="id_periodo" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">ID Docente (Demo)</label>
                    <input type="number" name="id_docente" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">ID Asignatura (Demo)</label>
                    <input type="number" name="id_asignatura" class="form-control" required>
                </div>
                <div class="d-flex gap-2">
                    <div class="form-group w-full">
                        <label class="form-label">ID Sección</label>
                        <input type="number" name="id_seccion" class="form-control" required>
                    </div>
                    <div class="form-group w-full">
                        <label class="form-label">ID Sede</label>
                        <input type="number" name="id_sede" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalNuevaCarga')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Asignación</button>
            </div>
        </form>
    </div>
</div>
