<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Mis Cargas Académicas</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Período Académico Actual</p>
    </div>
</div>

<?php if (empty($cargas)): ?>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-12 text-center">
        <div class="text-5xl mb-4 opacity-50">📚</div>
        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">No hay cargas asignadas</h3>
        <p class="text-sm text-slate-500 dark:text-slate-500">No tienes secciones asignadas en el período activo actual.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($cargas as $carga): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 hover:-translate-y-0.5 hover:shadow-lg hover:border-slate-300 dark:border-slate-700 transition-all duration-200" data-animate>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold text-blue-400 truncate"><?= htmlspecialchars($carga['asignatura']) ?></h3>
                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full bg-purple-500/10 text-purple-400">
                        Sec. <?= htmlspecialchars($carga['seccion']) ?>
                    </span>
                </div>
                <div class="flex items-center justify-between mb-5 text-xs text-slate-500 dark:text-slate-500">
                    <span>👥 Inscritos: <strong class="text-slate-700 dark:text-slate-300"><?= $carga['inscritos'] ?></strong></span>
                    <span>🏢 <?= htmlspecialchars($carga['sede']) ?></span>
                </div>
                <div class="space-y-2">
                    <a href="<?= url('docente/plan-evaluacion/' . $carga['id_carga']) ?>" class="flex items-center justify-center gap-2 w-full px-4 py-2 border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-sm font-medium hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                        📝 Plan de Evaluación
                    </a>
                    <a href="<?= url('docente/calificaciones/' . $carga['id_carga']) ?>" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all text-sm">
                        ✏️ Calificaciones
                    </a>
                    <a href="<?= url('docente/acta/' . $carga['id_carga']) ?>" class="flex items-center justify-center gap-2 w-full px-4 py-2 border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-sm font-medium hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition">
                        📄 Acta de Notas
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
