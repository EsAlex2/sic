<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Gestión de Usuarios</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Administra los usuarios y roles del sistema</p>
    </div>
    <button type="button" onclick="document.getElementById('modalNuevoUsuario').classList.remove('hidden'); document.getElementById('modalNuevoUsuario').classList.add('flex');"
            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Usuario
    </button>
</div>

<!-- Stats Summary -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <?php
        $totalUsers = count($usuarios);
        $activeUsers = count(array_filter($usuarios, fn($u) => $u['nombre_estatus'] === 'Activo'));
        $inactiveUsers = $totalUsers - $activeUsers;
    ?>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-500 uppercase tracking-wider">Total</p>
            <p class="text-xl font-bold text-slate-900 dark:text-slate-100"><?= $totalUsers ?></p>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-500 uppercase tracking-wider">Activos</p>
            <p class="text-xl font-bold text-emerald-400"><?= $activeUsers ?></p>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center text-red-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
        </div>
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-500 uppercase tracking-wider">Inactivos</p>
            <p class="text-xl font-bold text-red-400"><?= $inactiveUsers ?></p>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-100 dark:bg-slate-800/60">
                    <th class="text-center text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3">Cédula</th>
                    <th class="text-center text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3">Nombre Completo</th>
                    <th class="text-center text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3">Correo Institucional</th>
                    <th class="text-center text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3">Rol</th>
                    <th class="text-center text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3">Estatus</th>
                    <th class="text-center text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-600">No hay usuarios registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr class="hover:bg-slate-50 dark:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center text-slate-800 dark:text-slate-200"><?= htmlspecialchars($u['cedula']) ?></td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center text-slate-700 dark:text-slate-300"><?= htmlspecialchars(trim(($u['nombres'] ?? '') . ' ' . ($u['apellidos'] ?? ''))) ?></td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center text-slate-600 dark:text-slate-400"><?= htmlspecialchars($u['correo_institucional'] ?? '—') ?></td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center">
                                <?php
                                    $rolColor = match(strtolower($u['nombre_tipo'])) {
                                        'administrador' => 'bg-purple-500/10 text-purple-400',
                                        'docente'       => 'bg-blue-500/10 text-blue-400',
                                        'estudiante'    => 'bg-emerald-500/10 text-emerald-400',
                                        default         => 'bg-amber-500/10 text-amber-400',
                                    };
                                ?>
                                <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full <?= $rolColor ?>">
                                    <?= htmlspecialchars($u['nombre_tipo']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center">
                                <?php if ($u['nombre_estatus'] === 'Activo'): ?>
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-400">Activo</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-red-500/10 text-red-400">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center">
                                <div class="flex items-center gap-1.5 justify-center">
                                    <!-- Edit Button -->
                                    <a href="<?= url("admin/usuarios/editar/{$u['id_usuario']}") ?>"
                                       class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg px-2.5 py-1.5 text-xs hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition inline-flex items-center gap-1" title="Editar">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <!-- Toggle Status Button -->
                                    <form method="POST" action="<?= url("admin/usuarios/toggle/{$u['id_usuario']}") ?>" class="inline" onsubmit="return confirm('¿Cambiar estatus de este usuario?')">
                                        <?php if ($u['nombre_estatus'] === 'Activo'): ?>
                                            <button type="submit"
                                                    class="bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg px-2.5 py-1.5 text-xs hover:shadow-red-600/25 transition inline-flex items-center gap-1" title="Desactivar">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            </button>
                                        <?php else: ?>
                                            <button type="submit"
                                                    class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold rounded-lg px-2.5 py-1.5 text-xs hover:shadow-emerald-600/25 transition inline-flex items-center gap-1" title="Activar">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ═══════════ MODAL: Nuevo Usuario (2-step) ═══════════ -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalNuevoUsuario">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Registrar Usuario</h3>
            <button type="button" onclick="document.getElementById('modalNuevoUsuario').classList.add('hidden'); document.getElementById('modalNuevoUsuario').classList.remove('flex');" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>

        <form action="<?= url('admin/usuarios/crear') ?>" method="POST" id="formCrearUsuario">
            <div class="p-6 space-y-4">
                <!-- Step 1: Search -->
                <div id="paso1_busqueda">
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">
                        Ingrese la cédula de la persona. Debe estar registrada previamente en el
                        <a href="<?= url('admin/personas') ?>" class="text-blue-400 hover:underline">Módulo de Personas</a>.
                    </p>
                    <div class="flex items-center gap-2">
                        <div class="flex-1">
                            <input type="number" id="cedula_busqueda" name="cedula"
                                   class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                                   placeholder="Ej: 12345678" required>
                        </div>
                        <button type="button" onclick="buscarPersona()"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all whitespace-nowrap">
                            🔍 Buscar
                        </button>
                    </div>
                    <div id="busqueda_resultado" class="mt-2 text-sm"></div>
                </div>

                <!-- Step 2: User Data (hidden initially) -->
                <div id="paso2_datos" class="hidden pt-4 border-t border-slate-200 dark:border-slate-800 space-y-4">
                    <input type="hidden" id="id_persona_oculto" name="id_persona">

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Persona Encontrada</label>
                        <input type="text" id="nombre_encontrado"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-700 dark:text-slate-300 cursor-not-allowed" disabled>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Rol a Asignar</label>
                        <select name="id_tipo"
                                class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                            <option value="">Seleccione un rol...</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id_tipo'] ?>"><?= htmlspecialchars($r['nombre_tipo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Correo Institucional</label>
                        <input type="email" name="correo_institucional"
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                               placeholder="ejemplo@unexca.edu.ve" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Contraseña (Opcional)</label>
                        <input type="text" name="password"
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                               placeholder="Se asignará una por defecto si se deja en blanco">
                        <p class="text-xs text-slate-600 mt-1">Por defecto: "unexca" + Cédula</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalNuevoUsuario').classList.add('hidden'); document.getElementById('modalNuevoUsuario').classList.remove('flex');"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                    Cancelar
                </button>
                <button type="submit" id="btn_guardar_usuario"
                        class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-emerald-600/25 hover:shadow-emerald-600/40 hover:-translate-y-0.5 transition-all hidden">
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Search person by cédula (2-step user creation) ──
async function buscarPersona() {
    const cedula = document.getElementById('cedula_busqueda').value;
    const divRes = document.getElementById('busqueda_resultado');
    const paso2 = document.getElementById('paso2_datos');
    const btnGuardar = document.getElementById('btn_guardar_usuario');

    if (!cedula) {
        divRes.innerHTML = '<span class="text-red-400">Ingrese una cédula</span>';
        return;
    }

    divRes.innerHTML = '<span class="text-blue-400">Buscando...</span>';
    paso2.classList.add('hidden');
    btnGuardar.classList.add('hidden');

    try {
        const response = await fetch(`<?= url('api/personas/buscar/') ?>${cedula}?context=usuario`);
        const result = await response.json();

        if (result.success) {
            divRes.innerHTML = '<span class="text-emerald-400">✓ Persona encontrada</span>';
            document.getElementById('id_persona_oculto').value = result.data.id_persona;
            document.getElementById('nombre_encontrado').value = result.data.nombre_completo;

            // Auto-suggest email
            const nombreClean = result.data.nombre_completo.split(' ')[0].toLowerCase();
            document.querySelector('[name="correo_institucional"]').value = `${nombreClean}.${cedula}@unexca.edu.ve`;

            paso2.classList.remove('hidden');
            btnGuardar.classList.remove('hidden');
        } else {
            divRes.innerHTML = `<span class="text-red-400">✗ ${result.message}</span>`;
        }
    } catch (error) {
        divRes.innerHTML = '<span class="text-red-400">✗ Error de conexión</span>';
    }
}
</script>
