<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <div class="flex items-center gap-3">
            <a href="<?= url('admin/cargas') ?>" class="text-slate-400 hover:text-blue-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Detalles de la Sección</h1>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1 ml-9">Historial académico y asignaciones de la sección</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Panel Izquierdo: Información de la Sección -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-1">
                <?= htmlspecialchars($seccion['cod_seccion']) ?>
            </h2>
            <div class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-500">
                <?= htmlspecialchars($seccion['capacidad_max']) ?> estudiantes max.
            </div>

            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 space-y-4">
                <div>
                    <span class="block text-xs font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Creada en</span>
                    <span class="text-sm text-slate-700 dark:text-slate-300">
                        <?= date('d/m/Y h:i A', strtotime($seccion['creado_en'])) ?>
                    </span>
                </div>
                <?php if ($seccion['actualizado_en']): ?>
                <div>
                    <span class="block text-xs font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Última Actualización</span>
                    <span class="text-sm text-slate-700 dark:text-slate-300">
                        <?= date('d/m/Y h:i A', strtotime($seccion['actualizado_en'])) ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Panel Derecho: Historial de Cargas Académicas -->
    <div class="lg:col-span-3">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Historial de Clases Impartidas</h3>
            
            <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Período</th>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Asignatura</th>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Docente Titular</th>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Sede</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($historial)): ?>
                            <tr><td colspan="4" class="px-4 py-8 text-sm text-center text-slate-500 dark:text-slate-500">Esta sección no ha sido asignada a ninguna carga académica.</td></tr>
                        <?php else: ?>
                            <?php foreach ($historial as $h): ?>
                                <tr class="hover:bg-slate-50 dark:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-md bg-blue-500/10 text-blue-500">
                                            <?= htmlspecialchars($h['periodo']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 border-t border-slate-200 dark:border-slate-800/50">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($h['asignatura']) ?></p>
                                        <p class="text-xs text-slate-500"><?= htmlspecialchars($h['asignatura_codigo']) ?></p>
                                    </td>
                                    <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-800 dark:text-slate-200 font-medium">
                                        <?= htmlspecialchars($h['docente_apellidos'] . ' ' . $h['docente_nombres']) ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-500 dark:text-slate-400">
                                        <?= htmlspecialchars($h['sede']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Nueva Tabla: Estudiantes Inscritos -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 mt-6">
            <div class="flex items-center justify-between mb-4 border-b border-slate-200 dark:border-slate-800 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Estudiantes Asociados a la Sección</h3>
                    <p class="text-sm text-slate-500">Histórico de estudiantes inscritos en cargas de esta sección</p>
                </div>
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                    Total: <?= count($inscritos ?? []) ?>
                </span>
            </div>
            
            <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Período</th>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Materia</th>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Cédula</th>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Nombres y Apellidos</th>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-center">Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($inscritos)): ?>
                            <tr><td colspan="5" class="px-4 py-8 text-sm text-center text-slate-500 dark:text-slate-500">Aún no hay estudiantes inscritos asociados a esta sección.</td></tr>
                        <?php else: ?>
                            <?php foreach ($inscritos as $alumno): ?>
                                <tr class="hover:bg-slate-50 dark:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-md bg-blue-500/10 text-blue-500">
                                            <?= htmlspecialchars($alumno['periodo']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 border-t border-slate-200 dark:border-slate-800/50 text-sm font-medium text-slate-700 dark:text-slate-300">
                                        <?= htmlspecialchars($alumno['asignatura']) ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-medium text-slate-600 dark:text-slate-400">
                                        <?= htmlspecialchars($alumno['cedula']) ?>
                                    </td>
                                    <td class="px-4 py-3 border-t border-slate-200 dark:border-slate-800/50">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                            <?= htmlspecialchars($alumno['apellidos'] . ' ' . $alumno['nombres']) ?>
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center">
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-md <?= ($alumno['nombre_estatus'] ?? 'Activo') == 'Activo' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-500/10 text-slate-500' ?>">
                                            <?= htmlspecialchars($alumno['nombre_estatus'] ?? 'Activo') ?>
                                        </span>
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
