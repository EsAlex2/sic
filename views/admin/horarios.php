
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Gestión de Horarios</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Asigna horarios a las clases y materias.</p>
            </div>
        </div>
        <div>
            <a href="<?= url('admin/cargas') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium transition-colors">
                Volver a Cargas
            </a>
        </div>
    </div>

    <?php if (isset($flash['error'])): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-medium"><?= htmlspecialchars($flash['error']) ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($flash['success'])): ?>
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-medium"><?= htmlspecialchars($flash['success']) ?></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario -->
        <div class="lg:col-span-1">
            <form action="<?= url('admin/horarios/guardar') ?>" method="POST" class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-slate-800 pb-2">Crear Nuevo Horario</h2>
                
                <div class="space-y-4">
                    <!-- Selector de PNF Global -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">PNF (Filtro Global)</label>
                        <select id="filtroPnf" class="w-full bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-blue-700 dark:text-blue-300 font-medium">
                            <option value="">Todos los PNF...</option>
                            <?php foreach($pnfs as $pnf): ?>
                                <option value="<?= $pnf['id_pnf'] ?>"><?= htmlspecialchars($pnf['nombre_pnf']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Asignatura</label>
                        <select name="id_asignatura" id="select_asignatura" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                            <option value="">Seleccione...</option>
                            <?php foreach($asignaturas as $asig): ?>
                                <option value="<?= $asig['id_asignatura'] ?>" data-pnf="<?= $asig['id_pnf'] ?>"><?= htmlspecialchars($asig['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sección</label>
                        <select name="id_seccion" id="select_seccion" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                            <option value="">Seleccione...</option>
                            <?php foreach($secciones as $sec): ?>
                                <option value="<?= $sec['id_seccion'] ?>"><?= htmlspecialchars($sec['cod_seccion']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Docente</label>
                        <select name="id_docente" id="select_docente" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                            <option value="">Seleccione...</option>
                            <?php foreach($docentes as $doc): ?>
                                <option value="<?= $doc['id_docente'] ?>" data-pnf="<?= $doc['id_pnf'] ?>"><?= htmlspecialchars($doc['apellidos'] . ' ' . $doc['nombres']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Turno</label>
                            <select name="id_turno" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                                <option value="">Seleccione...</option>
                                <?php foreach($turnos as $turno): ?>
                                    <option value="<?= $turno['id_turno'] ?>"><?= htmlspecialchars($turno['turno']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Aula</label>
                            <select name="id_aula" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                                <option value="">Seleccione...</option>
                                <?php foreach($aulas as $au): ?>
                                    <option value="<?= $au['id_aula'] ?>"><?= htmlspecialchars($au['nombre_aula']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Día de la Semana</label>
                            <select name="dia_semana" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                                <option value="">Seleccione día...</option>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Hora Inicio</label>
                            <input type="time" name="hora_inicio" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Hora Fin</label>
                            <input type="time" name="hora_fin" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-100">
                        </div>
                    </div>

                    <div class="pt-4 mt-6 border-t border-slate-200 dark:border-slate-800">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-xl transition-colors shadow-sm shadow-blue-500/30">
                            Guardar Horario
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabla -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Sección</th>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Asignatura</th>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Horario</th>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Detalles</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            <?php if (empty($horarios)): ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                        No hay horarios registrados.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($horarios as $h): ?>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium">
                                                <?= htmlspecialchars($h['seccion']) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-slate-100">
                                            <?= htmlspecialchars($h['asignatura']) ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($h['dia_semana']) ?></span>
                                                <span><?= htmlspecialchars(substr($h['hora_inicio'], 0, 5) . ' - ' . substr($h['hora_fin'], 0, 5)) ?></span>
                                                <span class="text-xs text-slate-400"><?= htmlspecialchars($h['turno']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">
                                            <div class="flex flex-col">
                                                <span><?= htmlspecialchars($h['apellidos'] . ' ' . $h['nombres']) ?></span>
                                                <span class="text-xs text-slate-400">Aula: <?= htmlspecialchars($h['aula']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <form action="<?= url('admin/horarios/eliminar/' . $h['id_horario']) ?>" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este horario?');" class="inline">
                                                <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TomSelect CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<!-- TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar TomSelect en los selects para hacerlos buscables
    const tsAsignatura = new TomSelect("#select_asignatura", { create: false, sortField: { field: "text", direction: "asc" }});
    const tsDocente = new TomSelect("#select_docente", { create: false, sortField: { field: "text", direction: "asc" }});
    const tsSeccion = new TomSelect("#select_seccion", { create: false, sortField: { field: "text", direction: "asc" }});

    // Guardar las opciones originales en memoria para poder filtrarlas
    const originAsignaturas = Array.from(document.querySelectorAll('#select_asignatura option')).map(opt => ({
        value: opt.value,
        text: opt.textContent,
        pnf: opt.getAttribute('data-pnf')
    })).filter(o => o.value !== "");

    const originDocentes = Array.from(document.querySelectorAll('#select_docente option')).map(opt => ({
        value: opt.value,
        text: opt.textContent,
        pnf: opt.getAttribute('data-pnf')
    })).filter(o => o.value !== "");

    document.getElementById('filtroPnf').addEventListener('change', function() {
        const filter = this.value;

        // Limpiar TomSelects
        tsAsignatura.clear();
        tsAsignatura.clearOptions();
        tsAsignatura.addOption({value: '', text: 'Seleccione...'});
        
        tsDocente.clear();
        tsDocente.clearOptions();
        tsDocente.addOption({value: '', text: 'Seleccione...'});

        // Filtrar y agregar
        originAsignaturas.forEach(opt => {
            if (!filter || opt.pnf == filter) {
                tsAsignatura.addOption(opt);
            }
        });

        originDocentes.forEach(opt => {
            if (!filter || opt.pnf == filter) {
                tsDocente.addOption(opt);
            }
        });
    });
});
</script>

