<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Gestión de Docentes</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Administra los profesores y su información académica</p>
    </div>
    <button type="button" onclick="document.getElementById('modalNuevoDocente').classList.remove('hidden'); document.getElementById('modalNuevoDocente').classList.add('flex');"
            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Registrar Docente
    </button>
</div>

<!-- Dashboard Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 animate-fade">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm">
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Docentes</p>
        <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2"><?= $stats['total'] ?></p>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm">
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Top PNF</p>
        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-2"><?php arsort($stats['por_pnf']); echo key($stats['por_pnf']) ?: '-'; ?></p>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm">
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Top Sede</p>
        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-2"><?php arsort($stats['por_sede']); echo key($stats['por_sede']) ?: '-'; ?></p>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm">
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Top Asignatura</p>
        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-2"><?php arsort($stats['por_asignatura']); echo key($stats['por_asignatura']) ?: '-'; ?></p>
    </div>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 uppercase font-semibold">
                <tr>
                    <th class="px-4 py-4">Cédula</th>
                    <th class="px-4 py-4">Nombre Completo</th>
                    <th class="px-4 py-4">PNF</th>
                    <th class="px-4 py-4">Sede</th>
                    <th class="px-4 py-4">Asignatura</th>
                    <th class="px-4 py-4">Registro</th>
                    <th class="px-4 py-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php if (empty($docentes)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                            No hay docentes registrados.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($docentes as $d): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-4 py-4 font-medium text-slate-900 dark:text-slate-100">
                                <?= htmlspecialchars($d['cedula_identidad']) ?>
                            </td>
                            <td class="px-4 py-4 font-medium text-slate-900 dark:text-slate-100">
                                <?= htmlspecialchars($d['nombres'] . ' ' . $d['apellidos']) ?>
                            </td>
                            <td class="px-3 py-4 text-slate-600 dark:text-slate-400">
                                <?= htmlspecialchars($d['nombre_pnf']) ?>
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">
                                <?= htmlspecialchars($d['nombre_sede']) ?>
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">
                                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2.5 py-1 rounded-md text-xs font-semibold">
                                    <?= htmlspecialchars($d['nombre_asignatura'] ?? 'Sin Asignatura') ?>
                                </span>
                            </td>
                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">
                                <div class="text-[9px] leading-tight whitespace-nowrap">
                                    <span class="block">C: <?= $d['creado_en'] ? date('d/m/y H:i', strtotime($d['creado_en'])) : '-' ?></span>
                                    <span class="block text-slate-400">A: <?= $d['actualizado_en'] ? date('d/m/y H:i', strtotime($d['actualizado_en'])) : '-' ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= url("admin/docentes/ver/{$d['id_docente']}") ?>"
                                       class="bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-700 rounded-lg px-2.5 py-1.5 text-xs hover:bg-slate-50 dark:hover:bg-slate-700 transition inline-flex items-center gap-1 shadow-sm" title="Ver Detalles">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <button type="button" onclick="abrirModalEditar(<?= $d['id_docente'] ?>, '<?= $d['id_pnf'] ?? '' ?>', '<?= $d['id_sede'] ?? '' ?>', '<?= $d['id_asignatura'] ?? '' ?>', '<?= $d['fecha_ingreso'] ?>')"
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

<!-- ═══════════ MODAL: Nuevo Docente ═══════════ -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalNuevoDocente">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="sticky top-0 bg-white dark:bg-slate-900 z-10 flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Registrar Docente</h3>
            <button type="button" onclick="document.getElementById('modalNuevoDocente').classList.add('hidden'); document.getElementById('modalNuevoDocente').classList.remove('flex');" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form action="<?= url('admin/docentes/crear') ?>" method="POST" id="formNuevoDocente">
            <div class="p-6 space-y-6">
                <!-- Step 1: Search Person -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Buscar Persona (Cédula)</label>
                    <div class="flex gap-2">
                        <input type="text" id="cedula_busqueda"
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                               placeholder="Ej: 12345678">
                        <button type="button" onclick="buscarPersona()"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all whitespace-nowrap">
                            🔍 Buscar
                        </button>
                    </div>
                    <div id="busqueda_resultado" class="mt-2 text-sm"></div>
                </div>

                <!-- Step 2: Academic Data (hidden initially) -->
                <div id="paso2_datos" class="hidden pt-4 border-t border-slate-200 dark:border-slate-800 space-y-4">
                    <input type="hidden" id="id_persona_oculto" name="id_persona">

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Persona Encontrada</label>
                        <input type="text" id="nombre_encontrado"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-700 dark:text-slate-300 cursor-not-allowed" disabled>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Sede Principal</label>
                        <select name="id_sede" required
                                class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                            <option value="">Seleccione Sede...</option>
                            <?php foreach ($sedes as $s): ?>
                                <option value="<?= $s['id_sede'] ?>"><?= htmlspecialchars($s['nombre_sede']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Área / PNF Principal</label>
                        <select name="id_pnf" required
                                class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                            <option value="">Seleccione PNF...</option>
                            <?php foreach ($pnfs as $p): ?>
                                <option value="<?= $p['id_pnf'] ?>"><?= htmlspecialchars($p['nombre_pnf']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Asignatura Principal</label>
                        <select name="id_asignatura" required
                                class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                            <option value="">Seleccione Asignatura...</option>
                            <?php foreach ($asignaturas as $a): ?>
                                <option value="<?= $a['id_asignatura'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Fecha de Ingreso</label>
                        <input type="date" name="fecha_ingreso" required value="<?= date('Y-m-d') ?>"
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <div class="sticky bottom-0 bg-white dark:bg-slate-900 z-10 flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalNuevoDocente').classList.add('hidden'); document.getElementById('modalNuevoDocente').classList.remove('flex');"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                    Cancelar
                </button>
                <button type="submit" id="btn_guardar"
                        class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-emerald-600/25 hover:shadow-emerald-600/40 hover:-translate-y-0.5 transition-all hidden">
                    Registrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════════ MODAL: Editar Docente ═══════════ -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalEditarDocente">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Editar Docente</h3>
            <button type="button" onclick="document.getElementById('modalEditarDocente').classList.add('hidden'); document.getElementById('modalEditarDocente').classList.remove('flex');" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form method="POST" id="formEditarDocente">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Sede Principal</label>
                    <select name="id_sede" id="edit_id_sede" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <?php foreach ($sedes as $s): ?>
                            <option value="<?= $s['id_sede'] ?>"><?= htmlspecialchars($s['nombre_sede']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Área / PNF Principal</label>
                    <select name="id_pnf" id="edit_id_pnf" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <?php foreach ($pnfs as $p): ?>
                            <option value="<?= $p['id_pnf'] ?>"><?= htmlspecialchars($p['nombre_pnf']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Asignatura Principal</label>
                    <select name="id_asignatura" id="edit_id_asignatura" required
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                        <?php foreach ($asignaturas as $a): ?>
                            <option value="<?= $a['id_asignatura'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Fecha de Ingreso</label>
                    <input type="date" name="fecha_ingreso" id="edit_fecha_ingreso" required
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalEditarDocente').classList.add('hidden'); document.getElementById('modalEditarDocente').classList.remove('flex');"
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
// ── Search person by cédula (2-step user creation) ──
async function buscarPersona() {
    const cedula = document.getElementById('cedula_busqueda').value;
    const divRes = document.getElementById('busqueda_resultado');
    const paso2 = document.getElementById('paso2_datos');
    const btnGuardar = document.getElementById('btn_guardar');

    if (!cedula) {
        divRes.innerHTML = '<span class="text-red-400">Ingrese una cédula</span>';
        return;
    }

    divRes.innerHTML = '<span class="text-blue-400">Buscando...</span>';
    paso2.classList.add('hidden');
    btnGuardar.classList.add('hidden');

    try {
        const response = await fetch(`<?= url('api/personas/buscar/') ?>${cedula}?context=docente`);
        const result = await response.json();

        if (result.success) {
            divRes.innerHTML = '<span class="text-emerald-400">✓ Persona encontrada</span>';
            document.getElementById('id_persona_oculto').value = result.data.id_persona;
            document.getElementById('nombre_encontrado').value = result.data.nombre_completo;

            paso2.classList.remove('hidden');
            btnGuardar.classList.remove('hidden');
        } else {
            divRes.innerHTML = `<span class="text-red-400">✗ ${result.message}</span>`;
        }
    } catch (error) {
        divRes.innerHTML = '<span class="text-red-400">✗ Error de conexión</span>';
    }
}

function abrirModalEditar(id, id_pnf, id_sede, id_asignatura, fecha) {
    document.getElementById('formEditarDocente').action = '<?= url("admin/docentes/actualizar/") ?>' + id;
    
    // Set selects
    if(id_pnf) document.getElementById('edit_id_pnf').value = id_pnf;
    if(id_sede) document.getElementById('edit_id_sede').value = id_sede;
    if(id_asignatura) document.getElementById('edit_id_asignatura').value = id_asignatura;
    if(fecha) document.getElementById('edit_fecha_ingreso').value = fecha;
    
    document.getElementById('modalEditarDocente').classList.remove('hidden');
    document.getElementById('modalEditarDocente').classList.add('flex');
}
</script>
