<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Gestión de Asignaturas</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Administra el pensum y las materias de los programas de formación</p>
    </div>
    <button type="button" onclick="document.getElementById('modalNuevaAsignatura').classList.remove('hidden'); document.getElementById('modalNuevaAsignatura').classList.add('flex');"
            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Registrar Asignatura
    </button>
</div>

<!-- Dashboard Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 animate-fade">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm flex flex-col justify-center">
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Asignaturas</p>
        <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2"><?= $stats['total'] ?></p>
    </div>
    
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm md:col-span-2">
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Asignaturas por PNF</p>
        <div class="grid grid-cols-2 gap-2 max-h-[80px] overflow-y-auto pr-1 custom-scrollbar">
            <?php foreach ($stats['por_pnf'] as $pnf => $count): ?>
                <div class="flex justify-between items-center bg-slate-50 dark:bg-slate-800/50 px-3 py-2 rounded-lg border border-slate-100 dark:border-slate-700/50">
                    <span class="text-sm text-slate-700 dark:text-slate-300 font-medium truncate mr-2" title="<?= htmlspecialchars($pnf) ?>"><?= htmlspecialchars($pnf) ?></span>
                    <span class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold px-2 py-0.5 rounded-md"><?= $count ?></span>
                </div>
            <?php endforeach; ?>
            <?php if(empty($stats['por_pnf'])): ?>
                <span class="text-sm text-slate-400 dark:text-slate-500">Sin datos</span>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm flex flex-col justify-center">
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Top Trayecto</p>
        <p class="text-1xl font-bold text-amber-600 dark:text-amber-400 mt-2"><?php arsort($stats['por_trayecto']); echo key($stats['por_trayecto']) ?: '-'; ?></p>
    </div>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 uppercase font-semibold">
                <tr>
                    <th class="px-4 py-4">Código</th>
                    <th class="px-4 py-4">Nombre de la Asignatura</th>
                    <th class="px-4 py-4">PNF</th>
                    <th class="px-4 py-4">Trayecto</th>
                    <th class="px-4 py-4 text-center">UC</th>
                    <th class="px-4 py-4">Registro</th>
                    <th class="px-4 py-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php if (empty($asignaturas)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                            No hay asignaturas registradas.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($asignaturas as $a): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-4 py-4 font-medium text-slate-900 dark:text-slate-100">
                                <?= htmlspecialchars($a['codigo'] ?? 'N/A') ?>
                            </td>
                            <td class="px-4 py-4 font-medium text-slate-900 dark:text-slate-100">
                                <?= htmlspecialchars($a['nombre']) ?>
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">
                                <?= htmlspecialchars($a['nombre_pnf'] ?? 'Sin PNF') ?>
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">
                                <?= htmlspecialchars($a['trayecto_desc'] ?? 'Sin Trayecto') ?>
                            </td>
                            <td class="px-4 py-4 text-center text-slate-600 dark:text-slate-400 font-semibold">
                                <?= htmlspecialchars($a['unidades_credito'] ?? '0') ?>
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">
                                <div class="text-[9px] leading-tight whitespace-nowrap">
                                    <span class="block">C: <?= $a['creado_en'] ? date('d/m/y H:i', strtotime($a['creado_en'])) : '-' ?></span>
                                    <span class="block text-slate-400">A: <?= $a['actualizado_en'] ? date('d/m/y H:i', strtotime($a['actualizado_en'])) : '-' ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= url("admin/asignaturas/ver/{$a['id_asignatura']}") ?>"
                                       class="bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-700 rounded-lg px-2.5 py-1.5 text-xs hover:bg-slate-50 dark:hover:bg-slate-700 transition inline-flex items-center gap-1 shadow-sm" title="Ver Detalles">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <button type="button" onclick="abrirModalEditar(<?= $a['id_asignatura'] ?>, '<?= addslashes($a['codigo'] ?? '') ?>', '<?= addslashes($a['nombre'] ?? '') ?>', '<?= $a['id_pnf'] ?? '' ?>', '<?= $a['id_trayecto'] ?? '' ?>', '<?= $a['unidades_credito'] ?? '0' ?>', '<?= $a['id_caracter'] ?? '1' ?>')"
                                            class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg px-2.5 py-1.5 text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition inline-flex items-center gap-1" title="Editar">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ═══════════ MODAL: Nueva Asignatura ═══════════ -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalNuevaAsignatura">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="sticky top-0 bg-white dark:bg-slate-900 z-10 flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Registrar Asignatura</h3>
            <button type="button" onclick="document.getElementById('modalNuevaAsignatura').classList.add('hidden'); document.getElementById('modalNuevaAsignatura').classList.remove('flex');" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form action="<?= url('admin/asignaturas/crear') ?>" method="POST">
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Código</label>
                        <input type="text" name="codigo" required
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nombre de Asignatura</label>
                        <input type="text" name="nombre" required
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">PNF (Área)</label>
                    <select name="id_pnf" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <option value="">Seleccione PNF...</option>
                        <?php foreach ($pnfs as $p): ?>
                            <option value="<?= $p['id_pnf'] ?>"><?= htmlspecialchars($p['nombre_pnf']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Trayecto</label>
                    <select name="id_trayecto" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <option value="">Seleccione Trayecto...</option>
                        <?php foreach ($trayectos as $t): ?>
                            <option value="<?= $t['id_trayecto'] ?>"><?= htmlspecialchars($t['descripcion']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Unidades de Crédito</label>
                        <input type="number" name="unidades_credito" required min="0" value="0"
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Carácter</label>
                        <select name="id_caracter" required
                                class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                            <option value="1">Obligatoria</option>
                            <option value="2">Electiva</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <div class="sticky bottom-0 bg-white dark:bg-slate-900 z-10 flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalNuevaAsignatura').classList.add('hidden'); document.getElementById('modalNuevaAsignatura').classList.remove('flex');"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all">
                    Registrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════════ MODAL: Editar Asignatura ═══════════ -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalEditarAsignatura">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Editar Asignatura</h3>
            <button type="button" onclick="document.getElementById('modalEditarAsignatura').classList.add('hidden'); document.getElementById('modalEditarAsignatura').classList.remove('flex');" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form method="POST" id="formEditarAsignatura">
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Código</label>
                        <input type="text" name="codigo" id="edit_codigo" required
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nombre de Asignatura</label>
                        <input type="text" name="nombre" id="edit_nombre" required
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">PNF (Área)</label>
                    <select name="id_pnf" id="edit_id_pnf" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <?php foreach ($pnfs as $p): ?>
                            <option value="<?= $p['id_pnf'] ?>"><?= htmlspecialchars($p['nombre_pnf']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Trayecto</label>
                    <select name="id_trayecto" id="edit_id_trayecto" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <?php foreach ($trayectos as $t): ?>
                            <option value="<?= $t['id_trayecto'] ?>"><?= htmlspecialchars($t['descripcion']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Unidades de Crédito</label>
                        <input type="number" name="unidades_credito" id="edit_unidades_credito" required min="0"
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Carácter</label>
                        <select name="id_caracter" id="edit_id_caracter" required
                                class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                            <option value="1">Obligatoria</option>
                            <option value="2">Electiva</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalEditarAsignatura').classList.add('hidden'); document.getElementById('modalEditarAsignatura').classList.remove('flex');"
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
function abrirModalEditar(id, codigo, nombre, id_pnf, id_trayecto, uc, caracter) {
    document.getElementById('formEditarAsignatura').action = '<?= url("admin/asignaturas/actualizar/") ?>' + id;
    
    document.getElementById('edit_codigo').value = codigo;
    document.getElementById('edit_nombre').value = nombre;
    if(id_pnf) document.getElementById('edit_id_pnf').value = id_pnf;
    if(id_trayecto) document.getElementById('edit_id_trayecto').value = id_trayecto;
    document.getElementById('edit_unidades_credito').value = uc;
    if(caracter) document.getElementById('edit_id_caracter').value = caracter;
    
    document.getElementById('modalEditarAsignatura').classList.remove('hidden');
    document.getElementById('modalEditarAsignatura').classList.add('flex');
}
</script>
