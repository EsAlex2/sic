<div class="page-header">
    <div>
        <h1>Gestión de Usuarios</h1>
        <p class="page-subtitle">Administra los usuarios y roles del sistema</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalNuevoUsuario')">➕ Nuevo Usuario</button>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cédula</th>
                    <th>Nombre Completo</th>
                    <th>Rol</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u['id_usuario'] ?></td>
                            <td><?= htmlspecialchars($u['cedula']) ?></td>
                            <td><?= htmlspecialchars(trim(($u['nombres'] ?? '') . ' ' . ($u['apellidos'] ?? ''))) ?></td>
                            <td><span class="badge badge-info"><?= htmlspecialchars($u['nombre_tipo']) ?></span></td>
                            <td>
                                <span class="badge badge-<?= $u['nombre_estatus'] === 'Activo' ? 'success' : 'warning' ?>">
                                    <?= htmlspecialchars($u['nombre_estatus']) ?>
                                </span>
                            </td>
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

<!-- Modal Nuevo Usuario -->
<div class="modal-overlay" id="modalNuevoUsuario">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Registrar Usuario</h3>
            <button class="modal-close" onclick="closeModal('modalNuevoUsuario')">×</button>
        </div>
        <form action="<?= url('admin/usuarios/crear') ?>" method="POST" id="formCrearUsuario">
            <div class="modal-body">
                
                <!-- Paso 1: Búsqueda -->
                <div id="paso1_busqueda">
                    <p class="text-sm text-secondary mb-3">Ingrese la cédula de la persona. Debe estar registrada previamente en el <a href="<?= url('admin/personas') ?>">Módulo de Personas</a>.</p>
                    <div class="d-flex align-center gap-2">
                        <div class="form-group w-full" style="margin: 0;">
                            <input type="number" id="cedula_busqueda" name="cedula" class="form-control" placeholder="Ej: 12345678" required>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="buscarPersona()">🔍 Buscar</button>
                    </div>
                    <div id="busqueda_resultado" class="mt-2 text-sm"></div>
                </div>

                <!-- Paso 2: Datos del Usuario (Oculto inicialmente) -->
                <div id="paso2_datos" style="display: none; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                    <input type="hidden" id="id_persona_oculto" name="id_persona">
                    
                    <div class="form-group">
                        <label class="form-label">Persona Encontrada</label>
                        <input type="text" id="nombre_encontrado" class="form-control" disabled style="background: var(--bg-secondary);">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rol a Asignar</label>
                        <select name="id_tipo" class="form-control" required>
                            <option value="">Seleccione un rol...</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id_tipo'] ?>"><?= htmlspecialchars($r['nombre_tipo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Correo Institucional</label>
                        <input type="email" name="correo_institucional" class="form-control" placeholder="ejemplo@unexca.edu.ve" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contraseña (Opcional)</label>
                        <input type="text" name="password" class="form-control" placeholder="Se asignará una por defecto si se deja en blanco">
                        <p class="text-xs text-muted mt-1">Por defecto: "unexca" + Cédula</p>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalNuevoUsuario')">Cancelar</button>
                <button type="submit" class="btn btn-success" id="btn_guardar_usuario" style="display: none;">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>

<script>
async function buscarPersona() {
    const cedula = document.getElementById('cedula_busqueda').value;
    const divRes = document.getElementById('busqueda_resultado');
    const paso2 = document.getElementById('paso2_datos');
    const btnGuardar = document.getElementById('btn_guardar_usuario');

    if (!cedula) {
        divRes.innerHTML = '<span class="text-danger">Ingrese una cédula</span>';
        return;
    }

    divRes.innerHTML = '<span class="text-info">Buscando...</span>';
    paso2.style.display = 'none';
    btnGuardar.style.display = 'none';

    try {
        const response = await fetch(`<?= url('api/personas/buscar/') ?>${cedula}`);
        const result = await response.json();

        if (result.success) {
            divRes.innerHTML = '<span class="text-success">✓ Persona encontrada</span>';
            document.getElementById('id_persona_oculto').value = result.data.id_persona;
            document.getElementById('nombre_encontrado').value = result.data.nombre_completo;
            
            // Sugerir correo si no hay
            const nombreClean = result.data.nombre_completo.split(' ')[0].toLowerCase();
            document.querySelector('[name="correo_institucional"]').value = `${nombreClean}.${cedula}@unexca.edu.ve`;

            paso2.style.display = 'block';
            btnGuardar.style.display = 'inline-flex';
        } else {
            divRes.innerHTML = `<span class="text-danger">✗ ${result.message}</span>`;
        }
    } catch (error) {
        divRes.innerHTML = '<span class="text-danger">✗ Error de conexión</span>';
    }
}
</script>
