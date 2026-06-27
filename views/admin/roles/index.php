<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Gestión de Roles</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Administra los roles (tipos de usuario) del sistema</p>
    </div>
    <button type="button" onclick="document.getElementById('modalNuevoRol').classList.remove('hidden'); document.getElementById('modalNuevoRol').classList.add('flex');"
            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Rol
    </button>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Nombre del Rol</th>
                    <th class="px-6 py-4">Descripción</th>
                    <th class="px-6 py-4 text-center">Estatus</th>
                    <th class="px-6 py-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php if (empty($roles)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                            No hay roles registrados.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($roles as $r): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900 dark:text-slate-100">
                                <?= $r['id_tipo'] ?>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-900 dark:text-slate-100">
                                <?= htmlspecialchars($r['nombre_tipo']) ?>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                                <?= htmlspecialchars($r['descripcion'] ?? 'Sin descripción') ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($r['nombre_estatus'] === 'Activo'): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                        <?= $r['nombre_estatus'] ?>
                                    </span>
                                <?php elseif ($r['nombre_estatus'] === 'Suspendido'): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                        <?= $r['nombre_estatus'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/20">
                                        <?= $r['nombre_estatus'] ?? 'Inactivo' ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Botón Editar -->
                                    <button type="button" onclick="abrirModalEditarRol(<?= $r['id_tipo'] ?>, '<?= htmlspecialchars(addslashes($r['nombre_tipo'])) ?>', '<?= htmlspecialchars(addslashes($r['descripcion'] ?? '')) ?>', '<?= $r['id_estatus'] ?>')"
                                            class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg px-2.5 py-1.5 text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition inline-flex items-center gap-1" title="Editar Rol">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    <!-- Botón Toggle Estatus -->
                                    <form method="POST" action="<?= url("admin/roles/toggle/{$r['id_tipo']}") ?>" class="inline" onsubmit="return confirm('¿Cambiar estatus de este rol?')">
                                        <?php if ($r['nombre_estatus'] === 'Activo'): ?>
                                            <button type="submit"
                                                    class="bg-gradient-to-r from-amber-500 to-amber-600 text-white font-semibold rounded-lg px-2.5 py-1.5 text-xs hover:shadow-amber-500/25 transition inline-flex items-center gap-1" title="Suspender">
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

<!-- ═══════════ MODAL: Nuevo Rol ═══════════ -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalNuevoRol">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Crear Nuevo Rol</h3>
            <button type="button" onclick="document.getElementById('modalNuevoRol').classList.add('hidden'); document.getElementById('modalNuevoRol').classList.remove('flex');" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form action="<?= url('admin/roles/crear') ?>" method="POST">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nombre del Rol</label>
                    <input type="text" name="nombre_tipo" required
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Descripción</label>
                    <textarea name="descripcion" rows="3"
                              class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalNuevoRol').classList.add('hidden'); document.getElementById('modalNuevoRol').classList.remove('flex');"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:-translate-y-0.5 transition-all">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════════ MODAL: Editar Rol ═══════════ -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalEditarRol">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Editar Rol</h3>
            <button type="button" onclick="document.getElementById('modalEditarRol').classList.add('hidden'); document.getElementById('modalEditarRol').classList.remove('flex');" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form method="POST" id="formEditarRol">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nombre del Rol</label>
                    <input type="text" name="nombre_tipo" id="edit_nombre_tipo" required
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Descripción</label>
                    <textarea name="descripcion" id="edit_descripcion" rows="3"
                              class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Estatus</label>
                    <select name="id_estatus" id="edit_id_estatus" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <?php foreach ($estatusList as $e): ?>
                            <option value="<?= $e['id_estatus'] ?>"><?= htmlspecialchars($e['nombre_estatus']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalEditarRol').classList.add('hidden'); document.getElementById('modalEditarRol').classList.remove('flex');"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-emerald-600/25 hover:-translate-y-0.5 transition-all">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModalEditarRol(id, nombre, descripcion, id_estatus) {
    document.getElementById('formEditarRol').action = '<?= url("admin/roles/actualizar/") ?>' + id;
    document.getElementById('edit_nombre_tipo').value = nombre;
    document.getElementById('edit_descripcion').value = descripcion;
    document.getElementById('edit_id_estatus').value = id_estatus;
    
    document.getElementById('modalEditarRol').classList.remove('hidden');
    document.getElementById('modalEditarRol').classList.add('flex');
}
</script>
