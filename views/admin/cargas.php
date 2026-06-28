<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Carga Académica y Secciones</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Gestión de asignaciones académicas y catálogo de secciones</p>
    </div>
</div>

<!-- Tarjetas de Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Cargas</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100"><?= $stats['cargas'] ?></p>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Secciones Activas</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100"><?= $stats['secciones'] ?></p>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Docentes con Carga</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100"><?= $stats['docentes'] ?></p>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="mb-6 flex border-b border-slate-200 dark:border-slate-800">
    <button onclick="switchTab('cargas')" id="tabBtn-cargas" class="px-6 py-3 border-b-2 font-semibold text-sm transition-colors border-blue-600 text-blue-600 dark:text-blue-500">
        Carga Académica
    </button>
    <button onclick="switchTab('secciones')" id="tabBtn-secciones" class="px-6 py-3 border-b-2 border-transparent font-semibold text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
        Secciones
    </button>
    <a href="<?= url('admin/horarios') ?>" class="px-6 py-3 border-b-2 border-transparent font-semibold text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        Horarios
    </a>
</div>

<!-- Tab Content: Cargas -->
<div id="tab-cargas" class="block">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
        <!-- Buscador -->
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" id="buscadorCargas" placeholder="Buscar por docente, asignatura o sección..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors dark:text-slate-100">
        </div>

        <div class="flex gap-2">
            <a href="<?= url('admin/cargas/clase') ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-emerald-600/25 hover:shadow-emerald-600/40 hover:-translate-y-0.5 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Creador de Clases
            </a>
            <button onclick="openModal('modalNuevaCarga')"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-4 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Asignación Simple
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Período</th>
                        <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Docente</th>
                        <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Asignatura</th>
                        <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Secciones Asignadas</th>
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
                                        <?= htmlspecialchars($c['total_secciones']) ?> sección(es)
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-600 dark:text-slate-400">
                                    <?= htmlspecialchars($c['sede']) ?>
                                </td>
                                <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center">
                                    <a href="<?= url('admin/cargas/ver/' . $c['id_carga']) ?>" 
                                       class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 border border-blue-200 dark:border-blue-800/50 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg px-3 py-1.5 text-xs font-medium transition inline-block">
                                        Ver Detalles
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tab Content: Secciones -->
<div id="tab-secciones" class="hidden">
    <div class="flex justify-end mb-4">
        <button onclick="openModal('modalNuevaSeccion')"
                class="bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-purple-600/25 hover:shadow-purple-600/40 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nueva Sección
        </button>
    </div>
    
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Código de Sección</th>
                        <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-center">Capacidad Max</th>
                        <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Registro</th>
                        <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($secciones)): ?>
                        <tr><td colspan="4" class="px-4 py-8 text-sm text-center text-slate-500 dark:text-slate-500 border-t border-slate-200 dark:border-slate-800/50">No hay secciones registradas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($secciones as $s): ?>
                            <tr class="hover:bg-slate-50 dark:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-bold text-slate-900 dark:text-slate-100">
                                    <?= htmlspecialchars($s['cod_seccion']) ?>
                                </td>
                                <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center">
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                        <?= htmlspecialchars($s['capacidad_max']) ?> estudiantes
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                    <div class="text-[9.5px] text-slate-500 leading-tight whitespace-nowrap">
                                        <div><span class="font-medium text-slate-400">C:</span> <?= date('d/m/y H:i', strtotime($s['creado_en'])) ?></div>
                                        <?php if ($s['actualizado_en']): ?>
                                            <div><span class="font-medium text-slate-400">A:</span> <?= date('d/m/y H:i', strtotime($s['actualizado_en'])) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center flex items-center justify-center gap-2">
                                    <a href="<?= url('admin/secciones/ver/' . $s['id_seccion']) ?>" 
                                       class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 border border-blue-200 dark:border-blue-800/50 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg px-3 py-1.5 text-xs font-medium transition">
                                        Ver Detalles
                                    </a>
                                    <button onclick="editarSeccion(<?= $s['id_seccion'] ?>, '<?= htmlspecialchars($s['cod_seccion']) ?>', <?= $s['capacidad_max'] ?>)"
                                            class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg px-3 py-1.5 text-xs hover:bg-slate-50 dark:bg-slate-800 transition">
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
                            
                        <?php foreach ($periodos as $p): ?>
                            <option value="<?= $p['id_periodo'] ?>"><?= $p['periodo'] ?></option>
                        <?php endforeach; ?>
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
                            <option value="">Seleccione una sede...</option>
                            <?php foreach ($sedes as $s): ?>
                                <option value="<?= $s['id_sede'] ?>"><?= $s['nombre_sede'] ?></option>
                            <?php endforeach; ?>
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

<!-- Modal Editar Carga -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalEditarCarga" data-modal-overlay>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-y-auto max-h-[90vh]" data-modal-card>
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Editar Carga Académica</h3>
            <button onclick="closeModal('modalEditarCarga')" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form id="formEditarCarga" action="" method="POST">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Período Académico</label>
                    <select id="edit_id_periodo" name="id_periodo" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                        <option value="">Seleccione un período...</option>
                        <?php foreach ($periodos as $p): ?>
                            <option value="<?= $p['id_periodo'] ?>"><?= $p['periodo'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Docente</label>
                    <select id="edit_selectDocentes" name="id_docente" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                        <option value="">Cargando docentes...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Asignatura</label>
                    <select id="edit_selectAsignaturas" name="id_asignatura" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                        <option value="">Cargando asignaturas...</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Sección</label>
                        <select id="edit_selectSecciones" name="id_seccion" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                            <option value="">Cargando secciones...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Sede</label>
                        <select id="edit_id_sede" name="id_sede" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                            <option value="">Seleccione una sede...</option>
                            <?php foreach ($sedes as $s): ?>
                                <option value="<?= $s['id_sede'] ?>"><?= $s['nombre_sede'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeModal('modalEditarCarga')"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Nueva Sección -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalNuevaSeccion" data-modal-overlay>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-sm shadow-2xl overflow-y-auto max-h-[90vh]" data-modal-card>
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Nueva Sección</h3>
            <button onclick="closeModal('modalNuevaSeccion')" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form action="<?= url('admin/secciones/crear') ?>" method="POST">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Código de Sección</label>
                    <input type="text" name="cod_seccion" required placeholder="Ej: 30111"
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Capacidad Máxima</label>
                    <input type="number" name="capacidad_max" min="1" max="100" value="40" required
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeModal('modalNuevaSeccion')"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-purple-600/25 hover:shadow-purple-600/40 hover:-translate-y-0.5 transition-all">
                    Guardar Sección
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Sección -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="modalEditarSeccion" data-modal-overlay>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-sm shadow-2xl overflow-y-auto max-h-[90vh]" data-modal-card>
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Editar Sección</h3>
            <button onclick="closeModal('modalEditarSeccion')" class="text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition text-xl leading-none">&times;</button>
        </div>
        <form id="formEditarSeccion" action="" method="POST">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Código de Sección</label>
                    <input type="text" id="edit_cod_seccion" name="cod_seccion" required
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Capacidad Máxima</label>
                    <input type="number" id="edit_capacidad_max" name="capacidad_max" min="1" max="100" required
                           class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeModal('modalEditarSeccion')"
                        class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editarSeccion(id, cod, capacidad) {
    document.getElementById('edit_cod_seccion').value = cod;
    document.getElementById('edit_capacidad_max').value = capacidad;
    document.getElementById('formEditarSeccion').action = '<?= url("admin/secciones/actualizar/") ?>' + id;
    openModal('modalEditarSeccion');
}

function switchTab(tab) {
    document.getElementById('tab-cargas').classList.add('hidden');
    document.getElementById('tab-cargas').classList.remove('block');
    document.getElementById('tab-secciones').classList.add('hidden');
    document.getElementById('tab-secciones').classList.remove('block');
    
    const btnCargas = document.getElementById('tabBtn-cargas');
    const btnSecciones = document.getElementById('tabBtn-secciones');
    
    btnCargas.className = "px-6 py-3 border-b-2 border-transparent font-semibold text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors";
    btnSecciones.className = "px-6 py-3 border-b-2 border-transparent font-semibold text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors";
    
    document.getElementById('tab-' + tab).classList.remove('hidden');
    document.getElementById('tab-' + tab).classList.add('block');
    
    const btnActive = document.getElementById('tabBtn-' + tab);
    btnActive.className = "px-6 py-3 border-b-2 font-semibold text-sm transition-colors border-blue-600 text-blue-600 dark:text-blue-500";
}

document.addEventListener('DOMContentLoaded', async () => {
    try {
        // Cargar Docentes
        const resDocentes = await fetch('<?= url("api/cargas/docentes") ?>');
        const dataDocentes = await resDocentes.json();
        const selDoc = document.getElementById('selectDocentes');
        selDoc.innerHTML = '<option value="">Seleccione docente...</option>';
        if (dataDocentes.success) {
            let options = '<option value="">Seleccione docente...</option>';
            dataDocentes.data.forEach(d => {
                options += `<option value="${d.id_docente}">${d.apellidos} ${d.nombres}</option>`;
            });
            selDoc.innerHTML = options;
            document.getElementById('edit_selectDocentes').innerHTML = options;
        }
        
        // Cargar Asignaturas
        const resAsig = await fetch('<?= url("api/cargas/asignaturas") ?>');
        const dataAsig = await resAsig.json();
        const selAsig = document.getElementById('selectAsignaturas');
        if (dataAsig.success) {
            let options = '<option value="">Seleccione asignatura...</option>';
            dataAsig.data.forEach(a => {
                options += `<option value="${a.id_asignatura}">[${a.codigo}] ${a.nombre}</option>`;
            });
            selAsig.innerHTML = options;
            document.getElementById('edit_selectAsignaturas').innerHTML = options;
        }
        
        // Cargar Secciones
        const resSec = await fetch('<?= url("api/cargas/secciones") ?>');
        const dataSec = await resSec.json();
        const selSec = document.getElementById('selectSecciones');
        if (dataSec.success) {
            let options = '<option value="">Seleccione sección...</option>';
            dataSec.data.forEach(s => {
                options += `<option value="${s.id_seccion}">${s.cod_seccion}</option>`;
            });
            selSec.innerHTML = options;
            document.getElementById('edit_selectSecciones').innerHTML = options;
        }
    } catch (e) {
        console.error("Error cargando selects: ", e);
    }
});

function editarCarga(id, idPeriodo, idDocente, idAsignatura, idSeccion, idSede) {
    document.getElementById('edit_id_periodo').value = idPeriodo;
    document.getElementById('edit_selectDocentes').value = idDocente;
    document.getElementById('edit_selectAsignaturas').value = idAsignatura;
    document.getElementById('edit_selectSecciones').value = idSeccion;
    document.getElementById('edit_id_sede').value = idSede;
    
    document.getElementById('formEditarCarga').action = '<?= url("admin/cargas/actualizar/") ?>' + id;
    openModal('modalEditarCarga');
}

// Filtro de búsqueda en tiempo real
document.getElementById('buscadorCargas').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tab-cargas tbody tr');

    rows.forEach(row => {
        // Obtenemos los textos de las columnas relevantes (Docente, Asignatura)
        const docente = row.cells[1].textContent.toLowerCase();
        const asignatura = row.cells[2].textContent.toLowerCase();
        
        if (docente.includes(filter) || asignatura.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
