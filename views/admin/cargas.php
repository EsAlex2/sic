<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Carga Académica</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Asignación de asignaturas a docentes por sección</p>
    </div>
    <button onclick="openModal('modalNuevaCarga')"
            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Asignar Carga
    </button>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
    <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Período</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Docente</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Asignatura</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Sección</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Sede</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($cargas)): ?>
                    <tr><td colspan="6" class="px-4 py-8 text-sm text-center text-slate-500 dark:text-slate-500 border-t border-slate-200 dark:border-slate-800/50">No hay cargas asignadas.</td></tr>
                <?php else: ?>
                    <?php foreach ($cargas as $c): ?>
                        <tr class="hover:bg-slate-50 dark:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-500/10 text-blue-400">
                                    <?= htmlspecialchars($c['periodo']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-800 dark:text-slate-200">
                                <?= htmlspecialchars(trim($c['docente_nombres'] . ' ' . $c['docente_apellidos'])) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-bold text-slate-900 dark:text-slate-100">
                                <?= htmlspecialchars($c['asignatura']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-purple-500/10 text-purple-400">
                                    <?= htmlspecialchars($c['seccion']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-600 dark:text-slate-400">
                                <?= htmlspecialchars($c['sede']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center">
                                <button class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg px-3 py-1.5 text-xs hover:bg-slate-50 dark:bg-slate-800 transition">
                                    Editar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nueva Carga -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalNuevaCarga" data-modal-overlay>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-y-auto max-h-[90vh]" data-modal-card>
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Asignar Carga Académica</h3>
            <button onclick="closeModal('modalNuevaCarga')" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form action="<?= url('admin/cargas/crear') ?>" method="POST">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Período Académico</label>
                    <select name="id_periodo" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                        <option value="">Seleccione un período...</option>
                        <!-- To be populated dynamically -->
                        <option value="1">1 - Período Actual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Docente</label>
                    <select id="selectDocentes" name="id_docente" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                        <option value="">Cargando docentes...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Asignatura</label>
                    <select id="selectAsignaturas" name="id_asignatura" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                        <option value="">Cargando asignaturas...</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Sección</label>
                        <select id="selectSecciones" name="id_seccion" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                            <option value="">Cargando secciones...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Sede</label>
                        <select name="id_sede" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                            <option value="1">Sede Principal</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeModal('modalNuevaCarga')"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all">
                    Guardar Asignación
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // Cargar Docentes
        const resDocentes = await fetch('<?= url("api/cargas/docentes") ?>');
        const dataDocentes = await resDocentes.json();
        const selDoc = document.getElementById('selectDocentes');
        selDoc.innerHTML = '<option value="">Seleccione docente...</option>';
        if (dataDocentes.success) {
            dataDocentes.data.forEach(d => {
                selDoc.innerHTML += `<option value="${d.id_docente}">${d.apellidos} ${d.nombres}</option>`;
            });
        }
        
        // Cargar Asignaturas
        const resAsig = await fetch('<?= url("api/cargas/asignaturas") ?>');
        const dataAsig = await resAsig.json();
        const selAsig = document.getElementById('selectAsignaturas');
        selAsig.innerHTML = '<option value="">Seleccione asignatura...</option>';
        if (dataAsig.success) {
            dataAsig.data.forEach(a => {
                selAsig.innerHTML += `<option value="${a.id_asignatura}">[${a.codigo}] ${a.nombre}</option>`;
            });
        }
        
        // Cargar Secciones
        const resSec = await fetch('<?= url("api/cargas/secciones") ?>');
        const dataSec = await resSec.json();
        const selSec = document.getElementById('selectSecciones');
        selSec.innerHTML = '<option value="">Seleccione sección...</option>';
        if (dataSec.success) {
            dataSec.data.forEach(s => {
                selSec.innerHTML += `<option value="${s.id_seccion}">${s.cod_seccion}</option>`;
            });
        }
    } catch (e) {
        console.error("Error cargando selects: ", e);
    }
});
</script>
