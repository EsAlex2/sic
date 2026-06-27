<div class="animate-fade">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Panel de Finanzas</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Gestión de pagos, aranceles y solvencias</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <!-- Pagos Registrados -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-emerald">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-2xl">💰</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['total_pagos'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Pagos Registrados</div>
            </div>
        </div>
        <!-- Aranceles Configurados -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-blue">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-2xl">📑</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['total_aranceles'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Aranceles Configurados</div>
            </div>
        </div>
        <!-- Monto Recaudado -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-amber">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-2xl">💵</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100">Bs. <?= $stats['monto_recaudado'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Monto Recaudado</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Resumen -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">⚡ Acciones Rápidas</h3>
            <div class="flex flex-wrap gap-2.5">
                <a href="<?= url('finanzas/pagos') ?>" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all text-sm">💰 Gestionar Pagos</a>
                <a href="<?= url('finanzas/aranceles') ?>" class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition text-sm">📑 Aranceles</a>
                <a href="<?= url('finanzas/solvencias') ?>" class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition text-sm">✅ Solvencias</a>
            </div>
        </div>

        <!-- Resumen -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">📊 Resumen</h3>
            <p class="text-sm text-slate-500 dark:text-slate-500">Desde el módulo de Finanzas puedes registrar pagos, configurar aranceles y verificar la solvencia de los estudiantes.</p>
        </div>
    </div>
</div>
