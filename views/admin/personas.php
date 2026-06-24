<div class="page-header">
    <div>
        <h1>Registro de Personas</h1>
        <p class="page-subtitle">Gestiona la base de datos demográfica (paso previo a crear usuarios)</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalNuevaPersona')">➕ Nueva Persona</button>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Género</th>
                    <th>Contacto</th>
                    <th>Estado</th>
                    <th>¿Usuario?</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($personas)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No hay personas registradas.</td></tr>
                <?php else: ?>
                    <?php foreach ($personas as $p): ?>
                        <tr>
                            <td class="font-bold text-info"><?= htmlspecialchars($p['cedula_identidad']) ?></td>
                            <td><?= htmlspecialchars($p['nombres']) ?></td>
                            <td><?= htmlspecialchars($p['apellidos']) ?></td>
                            <td><?= htmlspecialchars($p['genero']) ?></td>
                            <td>
                                <div class="text-xs"><?= htmlspecialchars($p['correo_personal']) ?></div>
                                <div class="text-xs text-muted"><?= htmlspecialchars($p['telefono_personal'] ?? '') ?></div>
                            </td>
                            <td>
                                <span class="badge badge-<?= $p['nombre_estatus'] === 'Activo' ? 'success' : 'warning' ?>">
                                    <?= htmlspecialchars($p['nombre_estatus']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($p['tiene_usuario'] > 0): ?>
                                    <span class="badge badge-success">Sí</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">No</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nueva Persona -->
<div class="modal-overlay" id="modalNuevaPersona">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Registrar Persona</h3>
            <button class="modal-close" onclick="closeModal('modalNuevaPersona')">×</button>
        </div>
        <form action="<?= url('admin/personas/crear') ?>" method="POST">
            <div class="modal-body">
                <div class="d-flex gap-2 mb-2">
                    <div class="form-group w-full">
                        <label class="form-label">Cédula de Identidad</label>
                        <input type="number" name="cedula_identidad" class="form-control" required>
                    </div>
                    <div class="form-group w-full">
                        <label class="form-label">Género</label>
                        <select name="genero" class="form-control" required>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-2">
                    <div class="form-group w-full">
                        <label class="form-label">Nombres</label>
                        <input type="text" name="nombres" class="form-control" required>
                    </div>
                    <div class="form-group w-full">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" required>
                    </div>
                </div>

                <div class="form-group mb-2">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" required>
                </div>

                <div class="d-flex gap-2 mb-2">
                    <div class="form-group w-full">
                        <label class="form-label">Correo Personal</label>
                        <input type="email" name="correo_personal" class="form-control" required>
                    </div>
                    <div class="form-group w-full">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono_personal" class="form-control">
                    </div>
                </div>

                <div class="form-group mb-2">
                    <label class="form-label">Dirección de Habitación</label>
                    <textarea name="direccion_habitacion" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalNuevaPersona')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar Persona</button>
            </div>
        </form>
    </div>
</div>
