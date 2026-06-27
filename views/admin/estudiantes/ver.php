<!-- Page Header -->
<div class="flex items-center gap-4 mb-8">
    <a href="<?= url('admin/estudiantes') ?>" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Perfil del Estudiante</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Expediente académico completo</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Left Column: Profile Info -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm text-center">
            <!-- Avatar (Initials) -->
            <?php 
                $initials = strtoupper(substr($estudiante['nombres'], 0, 1) . substr($estudiante['apellidos'], 0, 1));
            ?>
            <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4 shadow-lg">
                <?= $initials ?>
            </div>
            
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-1">
                <?= htmlspecialchars($estudiante['nombres'] . ' ' . $estudiante['apellidos']) ?>
            </h2>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-4">
                V-<?= htmlspecialchars($estudiante['cedula_identidad']) ?>
            </p>
            
            <div class="flex justify-center mb-6">
                <?php
                $estatus_color = 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
                $dot_color = 'bg-slate-500';
                if (($estudiante['estatus_academico'] ?? '') === 'Cursando') { $estatus_color = 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'; $dot_color = 'bg-blue-500'; }
                if (($estudiante['estatus_academico'] ?? '') === 'Graduado' || ($estudiante['estatus_academico'] ?? '') === 'Egresado') { $estatus_color = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'; $dot_color = 'bg-emerald-500'; }
                if (($estudiante['estatus_academico'] ?? '') === 'Retirado') { $estatus_color = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'; $dot_color = 'bg-red-500'; }
                ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium <?= $estatus_color ?>">
                    <span class="w-1.5 h-1.5 rounded-full <?= $dot_color ?>"></span>
                    <?= htmlspecialchars($estudiante['estatus_academico'] ?? 'Cursando') ?>
                </span>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-4 text-left space-y-3 text-sm">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Sede</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300"><?= htmlspecialchars($estudiante['nombre_sede']) ?></span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Programa</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300"><?= htmlspecialchars($estudiante['nombre_pnf']) ?></span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Trayecto Actual</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300"><?= htmlspecialchars($estudiante['trayecto']) ?></span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Ingreso</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300"><?= date('d/m/Y', strtotime($estudiante['fecha_ingreso'])) ?></span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-4 uppercase tracking-wider">Contacto</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-3">
                    <span class="text-slate-400">📧</span>
                    <span class="text-slate-700 dark:text-slate-300 truncate" title="<?= htmlspecialchars($estudiante['correo_personal']) ?>">
                        <?= htmlspecialchars($estudiante['correo_personal']) ?>
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-slate-400">📱</span>
                    <span class="text-slate-700 dark:text-slate-300">
                        <?= htmlspecialchars($estudiante['telefono_personal'] ?: 'No registrado') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Academic Details -->
    <div class="lg:col-span-3 space-y-6">
        
        <!-- Tab Navigation (Visual only for now, all content stacked or scrollable) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                <span>🎓</span> Resumen Académico
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Materias Cursadas</div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-slate-100"><?= count($historico) ?></div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">UC Aprobadas</div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-slate-100"><?= $uc_aprobadas ?></div>
                </div>
            </div>

            <!-- Carga Actual -->
            <div class="mb-8">
                <h4 class="text-md font-bold text-slate-800 dark:text-slate-200 mb-4 border-b border-slate-200 dark:border-slate-800 pb-2">
                    Carga Académica Actual (<?= $periodoActual ? htmlspecialchars($periodoActual['nombre_periodo']) : 'Sin período activo' ?>)
                </h4>
                
                <?php if (empty($cargaActual)): ?>
                    <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/30 text-amber-800 dark:text-amber-400 text-sm">
                        El estudiante no tiene asignaturas inscritas en el período vigente.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Código</th>
                                    <th class="px-4 py-3">Asignatura</th>
                                    <th class="px-4 py-3">Sección</th>
                                    <th class="px-4 py-3">Estatus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <?php foreach ($cargaActual as $ca): ?>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100"><?= htmlspecialchars($ca['codigo_asignatura']) ?></td>
                                        <td class="px-4 py-3 text-slate-700 dark:text-slate-300"><?= htmlspecialchars($ca['nombre_asignatura']) ?></td>
                                        <td class="px-4 py-3 text-slate-600 dark:text-slate-400"><?= htmlspecialchars($ca['codigo_seccion']) ?></td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                <?= htmlspecialchars($ca['nombre_estatus']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Histórico -->
            <div>
                <h4 class="text-md font-bold text-slate-800 dark:text-slate-200 mb-4 border-b border-slate-200 dark:border-slate-800 pb-2">
                    Histórico de Notas (Récord)
                </h4>
                
                <?php if (empty($historico)): ?>
                    <div class="p-4 text-center text-slate-500 dark:text-slate-400 text-sm">
                        No hay historial de calificaciones registrado aún.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Período</th>
                                    <th class="px-4 py-3">Código</th>
                                    <th class="px-4 py-3">Asignatura</th>
                                    <th class="px-4 py-3 text-center">Nota</th>
                                    <th class="px-4 py-3 text-center">Intento</th>
                                    <th class="px-4 py-3">Condición</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <?php foreach ($historico as $h): ?>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                        <td class="px-4 py-3 text-slate-600 dark:text-slate-400"><?= htmlspecialchars($h['nombre_periodo']) ?></td>
                                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100"><?= htmlspecialchars($h['codigo_asignatura']) ?></td>
                                        <td class="px-4 py-3 text-slate-700 dark:text-slate-300"><?= htmlspecialchars($h['nombre_asignatura']) ?></td>
                                        <td class="px-4 py-3 text-center font-bold <?= $h['nota_definitiva'] >= 10 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' ?>">
                                            <?= number_format($h['nota_definitiva'], 2) ?>
                                        </td>
                                        <td class="px-4 py-3 text-center text-slate-500"><?= $h['intento'] ?></td>
                                        <td class="px-4 py-3">
                                            <?php if ($h['nota_definitiva'] >= 10): ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Aprobado</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Reprobado</span>
                                            <?php endif; ?>
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
