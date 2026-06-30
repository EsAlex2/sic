<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <div class="flex items-center gap-3">
            <a href="<?= url('admin/cargas') ?>" class="text-slate-400 hover:text-blue-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Detalles de Carga Académica</h1>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1 ml-9">Perfil completo de la asignación y lista de inscritos</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Panel Izquierdo: Información de la Asignación -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-1 leading-tight">
                Cohorte: <?= htmlspecialchars($cohorte['seccion']) ?>
            </h2>
            <div class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 mb-6">
                Sede: <?= htmlspecialchars($cohorte['sede']) ?>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                    <div>
                        <span class="block text-xs font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Período</span>
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-blue-500/10 text-blue-500">
                            <?= htmlspecialchars($cohorte['periodo']) ?>
                        </span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                    <span class="block text-xs font-semibold text-slate-500 dark:text-slate-500 uppercase tracking-wider mb-1">Turno</span>
                    <span class="text-sm text-slate-700 dark:text-slate-300">
                        <?= htmlspecialchars($cohorte['turno'] ?? 'No asignado') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Derecho: Secciones y Estudiantes -->
    <div class="lg:col-span-3 space-y-6">
        
        <!-- Materias y Docentes -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4 border-b border-slate-200 dark:border-slate-800 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                        Asignaturas y Docentes
                    </h3>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        Total: <?= count($materias) ?>
                    </span>
                </div>
            </div>
            
            <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Código</th>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Asignatura</th>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Docente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($materias)): ?>
                            <tr><td colspan="3" class="px-4 py-8 text-sm text-center text-slate-500 dark:text-slate-500">No hay materias asignadas en esta cohorte.</td></tr>
                        <?php else: ?>
                            <?php foreach ($materias as $m): ?>
                                <tr class="hover:bg-slate-50 dark:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-medium text-slate-600 dark:text-slate-400">
                                        <?= htmlspecialchars($m['asignatura_codigo']) ?>
                                    </td>
                                    <td class="px-4 py-3 border-t border-slate-200 dark:border-slate-800/50">
                                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100">
                                            <?= htmlspecialchars($m['asignatura']) ?>
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 border-t border-slate-200 dark:border-slate-800/50">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                            <?= htmlspecialchars($m['docente_nombres'] . ' ' . $m['docente_apellidos']) ?>
                                        </p>
                                        <span class="text-xs text-slate-500">C.I: <?= htmlspecialchars($m['docente_cedula']) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Estudiantes -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4 border-b border-slate-200 dark:border-slate-800 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                        Alumnos Inscritos
                    </h3>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        Alumnos: <?= count($alumnos) ?>
                    </span>
                </div>
            </div>
            
            <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Cédula</th>
                            <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Nombres y Apellidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($alumnos)): ?>
                            <tr><td colspan="2" class="px-4 py-8 text-sm text-center text-slate-500 dark:text-slate-500">Aún no hay estudiantes inscritos en esta cohorte.</td></tr>
                        <?php else: ?>
                            <?php foreach ($alumnos as $alumno): ?>
                                <tr class="hover:bg-slate-50 dark:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-medium text-slate-600 dark:text-slate-400">
                                        <?= htmlspecialchars($alumno['cedula']) ?>
                                    </td>
                                    <td class="px-4 py-3 border-t border-slate-200 dark:border-slate-800/50">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                            <?= htmlspecialchars($alumno['apellidos'] . ' ' . $alumno['nombres']) ?>
                                        </p>
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
