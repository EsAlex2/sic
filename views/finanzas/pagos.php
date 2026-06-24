<div class="page-header">
    <div>
        <h1>Gestión de Pagos</h1>
        <p class="page-subtitle">Verifica y procesa los pagos de los estudiantes</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalNuevoPago')">➕ Registrar Pago</button>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Estudiante</th>
                    <th>Arancel</th>
                    <th>Monto (Bs.)</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pagos)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No hay pagos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($pagos as $p): ?>
                        <tr>
                            <td class="font-bold"><?= htmlspecialchars($p['referencia']) ?></td>
                            <td><?= htmlspecialchars(trim($p['nombres'] . ' ' . $p['apellidos'])) ?></td>
                            <td><?= htmlspecialchars($p['arancel']) ?></td>
                            <td><?= number_format($p['monto'], 2, ',', '.') ?></td>
                            <td><?= date('d/m/Y', strtotime($p['fecha_pago'])) ?></td>
                            <td>
                                <?php
                                $color = match($p['estatus']) {
                                    'Procesado' => 'success',
                                    'Pendiente' => 'warning',
                                    'Rechazado' => 'danger',
                                    default => 'info'
                                };
                                ?>
                                <span class="badge badge-<?= $color ?>"><?= htmlspecialchars($p['estatus']) ?></span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline">Ver</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nuevo Pago -->
<div class="modal-overlay" id="modalNuevoPago">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Registrar Pago</h3>
            <button class="modal-close" onclick="closeModal('modalNuevoPago')">×</button>
        </div>
        <form action="<?= url('finanzas/pagos/registrar') ?>" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Cédula del Estudiante</label>
                    <input type="text" name="cedula" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Arancel</label>
                    <select name="id_arancel" class="form-control" required>
                        <option value="">Seleccione arancel...</option>
                        <?php foreach ($aranceles as $a): ?>
                            <option value="<?= $a['id_arancel'] ?>"><?= htmlspecialchars($a['nombre']) ?> - Bs. <?= number_format($a['monto'], 2, ',', '.') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Referencia</label>
                    <input type="text" name="referencia" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalNuevoPago')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar Pago</button>
            </div>
        </form>
    </div>
</div>
