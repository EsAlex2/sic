<div class="page-header">
    <div>
        <h1>Configuración de Aranceles</h1>
        <p class="page-subtitle">Gestiona los costos de trámites e inscripciones</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalNuevoArancel')">➕ Nuevo Arancel</button>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Concepto</th>
                    <th>Descripción</th>
                    <th>Monto (Bs.)</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($aranceles)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No hay aranceles configurados.</td></tr>
                <?php else: ?>
                    <?php foreach ($aranceles as $a): ?>
                        <tr>
                            <td><?= $a['id_arancel'] ?></td>
                            <td class="font-bold"><?= htmlspecialchars($a['nombre']) ?></td>
                            <td><?= htmlspecialchars($a['descripcion'] ?? '-') ?></td>
                            <td>Bs. <?= number_format($a['monto'], 2, ',', '.') ?></td>
                            <td><span class="badge badge-success">Activo</span></td>
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

<!-- Modal Nuevo Arancel -->
<div class="modal-overlay" id="modalNuevoArancel">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Nuevo Arancel</h3>
            <button class="modal-close" onclick="closeModal('modalNuevoArancel')">×</button>
        </div>
        <form action="<?= url('finanzas/aranceles/crear') ?>" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nombre del Concepto</label>
                    <input type="text" name="nombre" class="form-control" required placeholder="Ej: Inscripción Regular">
                </div>
                <div class="form-group">
                    <label class="form-label">Monto (Bs.)</label>
                    <input type="number" step="0.01" name="monto" class="form-control" required placeholder="0.00">
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalNuevoArancel')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Arancel</button>
            </div>
        </form>
    </div>
</div>
