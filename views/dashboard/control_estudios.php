<div class="animate-fade">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Panel de Control de Estudios</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Gestión de inscripciones, actas y expedientes académicos</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <!-- Inscripciones -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-blue">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-2xl">📝</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['inscripciones'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Inscripciones</div>
            </div>
        </div>
        <!-- Actas de Notas -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-emerald">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-2xl">📄</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['actas'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Actas de Notas</div>
            </div>
        </div>
        <!-- Reparaciones -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-amber">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-2xl">🔄</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['reparaciones'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Reparaciones</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">⚡ Acciones Rápidas</h3>
        <div class="flex flex-wrap gap-2.5">
            <a href="<?= url('control/inscripciones') ?>" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all text-sm">📝 Inscripciones</a>
            <a href="<?= url('reparaciones') ?>" class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition text-sm">🔄 Reparaciones</a>
        </div>
    </div>
</div>
