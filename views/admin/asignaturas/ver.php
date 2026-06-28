<!-- Page Header & Back Button -->
<div class="flex items-center gap-4 mb-8">
    <a href="<?= url('admin/asignaturas') ?>" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl p-2.5 transition shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Detalle de Asignatura</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Información general y docentes asignados a la materia</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información de la Asignatura -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm shrink-0">
                        <span class="text-2xl font-bold">📚</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold leading-tight"><?= htmlspecialchars($asignatura['nombre']) ?></h2>
                        <p class="text-blue-100 font-medium text-sm mt-1">Cód: <?= htmlspecialchars($asignatura['codigo'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4">Datos Académicos</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/50">
                        <span class="text-sm text-slate-500 dark:text-slate-400">PNF</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($asignatura['nombre_pnf'] ?? 'Sin PNF') ?></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/50">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Trayecto</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($asignatura['trayecto_desc'] ?? 'Sin Trayecto') ?></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/50">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Unidades de Crédito</span>
                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold px-2 py-0.5 rounded-md"><?= htmlspecialchars($asignatura['unidades_credito'] ?? '0') ?> UC</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/50">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Carácter</span>
                        <?php if (($asignatura['id_caracter'] ?? 1) == 1): ?>
                            <span class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold px-2 py-0.5 rounded-md">Obligatoria</span>
                        <?php else: ?>
                            <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-xs font-bold px-2 py-0.5 rounded-md">Electiva</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Creada en</span>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300"><?= date('d/m/Y', strtotime($asignatura['creado_en'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Docentes Asignados -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden h-full">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Docentes Capacitados</h3>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold px-2.5 py-1 rounded-full border border-slate-200 dark:border-slate-700"><?= count($docentes) ?> Docentes</span>
            </div>
            
            <div class="p-0">
                <?php if (empty($docentes)): ?>
                    <div class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <p class="font-medium">Aún no hay docentes asignados a esta materia.</p>
                        <p class="text-xs mt-1">Los docentes se asocian a las materias al registrarlos o editarlos en el módulo de Docentes.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 uppercase font-semibold">
                                <tr>
                                    <th class="px-6 py-4">Cédula</th>
                                    <th class="px-6 py-4">Nombre Completo</th>
                                    <th class="px-6 py-4 text-right">Fecha Ingreso</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <?php foreach ($docentes as $d): ?>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-slate-900 dark:text-slate-100">
                                            <?= htmlspecialchars($d['cedula_identidad']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-medium">
                                            <?= htmlspecialchars($d['nombres'] . ' ' . $d['apellidos']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-right">
                                            <?= date('d/m/Y', strtotime($d['fecha_ingreso'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
