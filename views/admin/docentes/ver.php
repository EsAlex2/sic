<!-- Page Header & Back Button -->
<div class="flex items-center gap-4 mb-8">
    <a href="<?= url('admin/docentes') ?>" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl p-2.5 transition shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Perfil del Docente</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Información personal y asignación de materias</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información del Docente -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 text-white text-center">
                <div class="w-20 h-20 mx-auto bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm shadow-inner mb-4">
                    <span class="text-3xl font-bold">👨‍🏫</span>
                </div>
                <h2 class="text-xl font-bold leading-tight"><?= htmlspecialchars($docente['nombres'] . ' ' . $docente['apellidos']) ?></h2>
                <p class="text-blue-100 font-medium text-sm mt-1">V-<?= htmlspecialchars($docente['cedula_identidad']) ?></p>
            </div>
            <div class="p-6">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4">Datos de Contacto</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Correo Electrónico</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200 break-all"><?= htmlspecialchars($docente['correo_personal']) ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Teléfono</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200"><?= htmlspecialchars($docente['telefono_personal'] ?: 'No registrado') ?></p>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-slate-200 dark:bg-slate-800 my-6"></div>

                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4">Datos Académicos</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/50">
                        <span class="text-sm text-slate-500 dark:text-slate-400">PNF Adscrito</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($docente['nombre_pnf']) ?></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/50">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Sede Principal</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($docente['nombre_sede']) ?></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/50">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Fecha Ingreso</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200"><?= date('d/m/Y', strtotime($docente['fecha_ingreso'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Asignaturas y Horarios -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Asignatura Capacitada Principal -->
        <div class="bg-white dark:bg-slate-900 border border-emerald-200 dark:border-emerald-800/50 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 bg-emerald-50 dark:bg-emerald-900/10 border-b border-emerald-100 dark:border-emerald-800/30">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-200 dark:bg-emerald-800/50 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-emerald-900 dark:text-emerald-100">Asignatura Principal</h3>
                </div>
                <p class="text-sm text-emerald-700 dark:text-emerald-400 ml-11">
                    Materia para la que el docente se encuentra primariamente capacitado y registrado en el sistema.
                </p>
            </div>
            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <?php if ($docente['nombre_asignatura_principal']): ?>
                    <div>
                        <h4 class="text-xl font-bold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($docente['nombre_asignatura_principal']) ?></h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Cód: <?= htmlspecialchars($docente['codigo_asignatura']) ?></p>
                    </div>
                    <span class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold px-3 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-800 inline-block self-start sm:self-auto">Capacitado</span>
                <?php else: ?>
                    <div class="text-slate-500 dark:text-slate-400 italic">No tiene asignatura principal asignada.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Clases / Horarios (Materias Asignadas) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Clases / Secciones Asignadas</h3>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold px-2.5 py-1 rounded-full border border-slate-200 dark:border-slate-700"><?= count($horarios) ?> Clases</span>
            </div>
            
            <div class="p-0">
                <?php if (empty($horarios)): ?>
                    <div class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="font-medium">El docente no tiene clases asignadas en el horario actual.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 uppercase font-semibold">
                                <tr>
                                    <th class="px-6 py-4">Asignatura</th>
                                    <th class="px-6 py-4">Sección</th>
                                    <th class="px-6 py-4">Horario</th>
                                    <th class="px-6 py-4">Aula</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <?php foreach ($horarios as $h): ?>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-slate-900 dark:text-slate-100"><?= htmlspecialchars($h['nombre_asignatura']) ?></p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5"><?= htmlspecialchars($h['trayecto_desc'] ?? 'Sin trayecto específico') ?></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold px-2 py-0.5 rounded-md border border-blue-200 dark:border-blue-800">
                                                <?= htmlspecialchars($h['cod_seccion']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-slate-700 dark:text-slate-300 font-medium">
                                                <?php if ($h['hora_inicio'] && $h['hora_fin']): ?>
                                                    <?= date('h:i A', strtotime($h['hora_inicio'])) ?> - <?= date('h:i A', strtotime($h['hora_fin'])) ?>
                                                <?php else: ?>
                                                    <span class="text-slate-400 italic">Por definir</span>
                                                <?php endif; ?>
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                                            <?= htmlspecialchars($h['nombre_aula'] ?? 'Sin aula asignada') ?>
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
