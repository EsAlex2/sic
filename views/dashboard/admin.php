<div class="animate-fade">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Panel de Administración</h1>
        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Vista general del sistema académico UNEXCA</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <!-- Estudiantes -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-blue">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-2xl">🎓</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['total_estudiantes'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Estudiantes</div>
            </div>
        </div>
        <!-- Docentes -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-emerald">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-2xl">👨‍🏫</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['total_docentes'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Docentes</div>
            </div>
        </div>
        <!-- Usuarios -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-purple">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-2xl">👥</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['total_usuarios'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Usuarios del Sistema</div>
            </div>
        </div>
        <!-- Períodos Activos -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-amber">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-2xl">📅</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['periodos_activos'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Períodos Activos</div>
            </div>
        </div>
        <!-- Cargas Académicas -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-cyan">
            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 flex items-center justify-center text-2xl">📋</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['total_cargas'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Cargas Académicas</div>
            </div>
        </div>
        <!-- Programas PNF -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 stat-border-red">
            <div class="w-12 h-12 rounded-xl bg-red-500/10 flex items-center justify-center text-2xl">📚</div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-slate-100"><?= $stats['total_pnf'] ?></div>
                <div class="text-sm text-slate-500 dark:text-slate-500 mt-0.5">Programas (PNF)</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & System Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">⚡ Acciones Rápidas</h3>
            <div class="flex flex-wrap gap-2.5">
                <a href="<?= url('admin/usuarios') ?>" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl px-5 py-2.5 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all text-sm">👥 Gestionar Usuarios</a>
                <a href="<?= url('admin/periodos') ?>" class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition text-sm">📅 Períodos</a>
                <a href="<?= url('admin/cargas') ?>" class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition text-sm">📋 Cargas</a>
                <a href="<?= url('reparaciones') ?>" class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition text-sm">🔄 Reparaciones</a>
                <a href="<?= url('finanzas/pagos') ?>" class="border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl px-4 py-2 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200 transition text-sm">💰 Finanzas</a>
            </div>
        </div>

        <!-- System Info -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">📋 Información del Sistema</h3>
            <div class="flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Versión</span>
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-500/10 text-blue-400">SIC v1.0.0</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Base de Datos</span>
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-400">PostgreSQL 18</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600 dark:text-slate-400">PHP</span>
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-purple-500/10 text-purple-400">v<?= phpversion() ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Servidor</span>
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-500/10 text-amber-400">Apache/XAMPP</span>
                </div>
            </div>
        </div>
    </div>
</div>
