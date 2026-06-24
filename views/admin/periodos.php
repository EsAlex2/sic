<div class="page-header">
    <div>
        <h1>Períodos Académicos</h1>
        <p class="page-subtitle">Gestiona los lapsos académicos de la universidad</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalNuevoPeriodo')">➕ Nuevo Período</button>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Fechas</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($periodos)): ?>
                    <tr><td colspan="5" class="text-center text-muted">No hay períodos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($periodos as $p): ?>
                        <tr>
                            <td><?= $p['id_periodo'] ?></td>
                            <td class="font-bold text-info"><?= htmlspecialchars($p['codigo']) ?></td>
                            <td>
                                <?= date('d/m/Y', strtotime($p['fecha_inicio'])) ?> - 
                                <?= date('d/m/Y', strtotime($p['fecha_fin'])) ?>
                            </td>
                            <td>
                                <?php if ($p['estado']): ?>
                                    <span class="badge badge-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Cerrado</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline">Editar</button>
                                <?php if ($p['estado']): ?>
                                    <button class="btn btn-sm btn-danger">Cerrar</button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-success">Activar</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nuevo Período -->
<div class="modal-overlay" id="modalNuevoPeriodo">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Registrar Período Académico</h3>
            <button class="modal-close" onclick="closeModal('modalNuevoPeriodo')">×</button>
        </div>
        <form action="<?= url('admin/periodos/crear') ?>" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Código del Período</label>
                    <input type="text" name="codigo" class="form-control" placeholder="Ej: 2026-I" required>
                </div>
                <div class="d-flex gap-2">
                    <div class="form-group w-full">
                        <label class="form-label">Fecha de Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" required>
                    </div>
                    <div class="form-group w-full">
                        <label class="form-label">Fecha de Fin</label>
                        <input type="date" name="fecha_fin" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Estado Inicial</label>
                    <select name="estado" class="form-control">
                        <option value="1">Activo (Abierto)</option>
                        <option value="0">Inactivo (Cerrado)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalNuevoPeriodo')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Período</button>
            </div>
        </form>
    </div>
</div>
