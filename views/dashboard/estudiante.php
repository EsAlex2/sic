<div class="animate-fade">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Portal del Estudiante</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Consulta tus notas, histórico e índice académico</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <!-- Materias Inscritas -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-blue">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-2xl">📚</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['materias_inscritas'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Materias Inscritas</div>
            </div>
        </div>
        <!-- Promedio Acumulado -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-emerald">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-2xl">📊</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['promedio_actual'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Promedio Acumulado</div>
            </div>
        </div>
        <!-- Créditos Aprobados -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-purple">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-2xl">🎯</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['creditos_aprobados'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Créditos Aprobados</div>
            </div>
        </div>
    </div>

    <!-- Quick Access & Período Actual -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Quick Access -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">⚡ Acceso Rápido</h3>
            <div class="flex flex-wrap gap-2.5">
                <a href="<?= url('estudiante/notas') ?>" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all text-sm">📝 Mis Notas</a>
                <a href="<?= url('estudiante/historico') ?>" class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition text-sm">📜 Histórico</a>
                <a href="<?= url('estudiante/indice') ?>" class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition text-sm">📈 Índice Académico</a>
            </div>
        </div>

        <!-- Período Actual -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">📋 Período Actual</h3>
            <p class="text-sm text-slate-500 dark:text-slate-500">Consulta tus calificaciones parciales y definitivas del período en curso desde la sección «Mis Notas».</p>
        </div>
    </div>
</div>
