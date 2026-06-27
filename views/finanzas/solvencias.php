<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Solvencias Administrativas</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Verifica el estado de cuenta de los estudiantes</p>
    </div>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
    <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Cédula</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Nombre Completo</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Pagos Registrados</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Estado de Solvencia</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($estudiantes)): ?>
                    <tr><td colspan="5" class="px-4 py-8 text-sm text-center text-slate-500 dark:text-slate-500 border-t border-slate-200 dark:border-slate-800/50">No hay estudiantes registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($estudiantes as $e): ?>
                        <tr class="hover:bg-slate-50 dark:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-bold text-blue-400 font-mono">
                                <?= htmlspecialchars($e['cedula_identidad']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-800 dark:text-slate-200">
                                <?= htmlspecialchars(trim($e['nombres'] . ' ' . $e['apellidos'])) ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-500/10 text-blue-400">
                                    <?= $e['pagos_realizados'] ?> pago(s)
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <?php if ($e['pagos_realizados'] > 0): ?>
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-400">Solvente</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-500/10 text-amber-400">Sin Pagos</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-center">
                                <a href="<?= url('finanzas/solvencias/' . $e['id_persona']) ?>"
                                   class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg px-3 py-1.5 text-xs hover:bg-slate-50 dark:bg-slate-800 transition inline-block">
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
