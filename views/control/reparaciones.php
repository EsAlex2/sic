<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Gestión de Reparaciones</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Solicitudes y seguimiento de exámenes de reparación</p>
    </div>
    <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all text-sm cursor-pointer" onclick="openModal('modalNuevaReparacion')">
        ➕ Registrar Solicitud
    </button>
</div>

<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Período</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Estudiante</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Asignatura</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Nota Original</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Nota Reparación</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Estado</th>
                    <th class="bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-500 text-[11px] uppercase tracking-wider px-4 py-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reparaciones)): ?>
                    <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500 dark:text-slate-500 text-sm">No hay reparaciones registradas.</td></tr>
                <?php else: ?>
                    <?php foreach ($reparaciones as $r): ?>
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-500/10 text-blue-400"><?= htmlspecialchars($r['periodo']) ?></span>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-slate-800 dark:text-slate-200"><?= htmlspecialchars(trim($r['nombres'] . ' ' . $r['apellidos'])) ?></td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 font-semibold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($r['asignatura']) ?></td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50 text-red-400 font-semibold"><?= number_format($r['nota_original'], 2) ?></td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <?php if ($r['nota_reparacion'] !== null): ?>
                                    <span class="font-bold <?= $r['nota_reparacion'] >= 10 ? 'text-emerald-400' : 'text-red-400' ?>">
                                        <?= number_format($r['nota_reparacion'], 2) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-500 dark:text-slate-500 italic">Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <?php if ($r['nota_reparacion'] !== null): ?>
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-400">Calificado</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-500/10 text-amber-400">En proceso</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm border-t border-slate-200 dark:border-slate-800/50">
                                <button class="px-3 py-1.5 border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg text-xs font-medium hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">Ver</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nueva Reparacion -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4" id="modalNuevaReparacion" data-modal-overlay>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl" data-modal-card style="transition: all 0.15s ease;">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-semibold">Solicitar Reparación</h3>
            <button class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition cursor-pointer" onclick="closeModal('modalNuevaReparacion')">×</button>
        </div>
        <form action="<?= url('reparaciones/solicitar') ?>" method="POST">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">ID Inscripción Asignatura</label>
                    <input type="number" name="id_inscripcion" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">ID Período</label>
                    <input type="number" name="id_periodo" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nota Original (5.00 – 9.99)</label>
                    <input type="number" step="0.01" min="5" max="9.99" name="nota_original" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" required>
                </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                <button type="button" class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-sm font-medium hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition cursor-pointer" onclick="closeModal('modalNuevaReparacion')">Cancelar</button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 transition-all text-sm cursor-pointer">Registrar</button>
            </div>
        </form>
    </div>
</div>
