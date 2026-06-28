<?php
/**
 * Componente Sidebar — Navegación lateral dinámica por rol (Tailwind v4)
 */
$usuario = Session::obtenerUsuario();
$rol = $usuario['rol'] ?? '';
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function isActive(string $path): string {
    global $currentPath;
    return str_contains($currentPath, $path) ? 'bg-blue-500/10 text-blue-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-800 dark:text-slate-200';
}
?>
<aside class="fixed top-0 left-0 w-64 h-screen bg-slate-50 dark:bg-slate-925 border-r border-slate-200 dark:border-slate-800/60 flex flex-col z-50 overflow-y-auto overflow-x-hidden transition-all duration-300" id="sidebar">
    <!-- Header -->
    <div class="px-5 py-5 border-b border-slate-200 dark:border-slate-800/60 flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center text-white font-extrabold text-sm shrink-0 shadow-lg shadow-blue-500/20">
            SIC
        </div>
        <div>
            <div class="text-[15px] font-bold text-slate-900 dark:text-slate-100 leading-tight">UNEXCA</div>
            <div class="text-[10px] text-slate-500 dark:text-slate-500 uppercase tracking-widest">Sistema Académico</div>
        </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 py-4 space-y-1">
        <!-- Dashboard -->
        <div class="px-3">
            <a href="<?= url('dashboard') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 <?= isActive('/dashboard') ?>">
                <span class="text-lg w-5 text-center">📊</span>
                <span>Dashboard</span>
            </a>
        </div>

        <?php if ($rol === ROL_ADMIN): ?>
        <!-- Admin -->
        <div class="px-3 mt-4">
            <div class="text-[10px] font-semibold text-slate-600 uppercase tracking-[0.12em] px-3 mb-2">Administración</div>
            <a href="<?= url('admin/personas') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/admin/personas') ?>">
                <span class="text-lg w-5 text-center">🪪</span><span>Personas</span>
            </a>
            <a href="<?= url('admin/usuarios') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/admin/usuarios') ?>">
                <span class="text-lg w-5 text-center">👥</span><span>Usuarios</span>
            </a>
            <a href="<?= url('admin/estudiantes') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/admin/estudiantes') ?>">
                <span class="text-lg w-5 text-center">🎓</span><span>Estudiantes</span>
            </a>
            <a href="<?= url('admin/docentes') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/admin/docentes') ?>">
                <span class="text-lg w-5 text-center">👨‍🏫</span><span>Docentes</span>
            </a>
            <a href="<?= url('admin/asignaturas') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/admin/asignaturas') ?>">
                <span class="text-lg w-5 text-center">📚</span><span>Asignaturas</span>
            </a>
            <a href="<?= url('admin/periodos') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/admin/periodos') ?>">
                <span class="text-lg w-5 text-center">📅</span><span>Períodos Académicos</span>
            </a>
            <a href="<?= url('admin/cargas') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 <?= isActive('/admin/cargas') ?>">
                <span class="text-lg w-5 text-center">📋</span><span>Carga Académica</span>
            </a>
        </div>
        <div class="px-3 mt-4">
            <div class="text-[10px] font-semibold text-slate-600 uppercase tracking-[0.12em] px-3 mb-2">Seguridad</div>
            <a href="<?= url('admin/roles') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/admin/roles') ?>">
                <span class="text-lg w-5 text-center">🎭</span><span>Roles</span>
            </a>
            <a href="<?= url('admin/permisos') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 <?= isActive('/admin/permisos') ?>">
                <span class="text-lg w-5 text-center">🔐</span><span>Permisos</span>
            </a>
        </div>
        <div class="px-3 mt-4">
            <div class="text-[10px] font-semibold text-slate-600 uppercase tracking-[0.12em] px-3 mb-2">Académico</div>
            <a href="<?= url('reparaciones') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 <?= isActive('/reparaciones') ?>">
                <span class="text-lg w-5 text-center">🔄</span><span>Reparaciones</span>
            </a>
        </div>
        <div class="px-3 mt-4">
            <div class="text-[10px] font-semibold text-slate-600 uppercase tracking-[0.12em] px-3 mb-2">Finanzas</div>
            <a href="<?= url('finanzas/pagos') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/finanzas/pagos') ?>">
                <span class="text-lg w-5 text-center">💰</span><span>Pagos</span>
            </a>
            <a href="<?= url('finanzas/aranceles') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/finanzas/aranceles') ?>">
                <span class="text-lg w-5 text-center">📑</span><span>Aranceles</span>
            </a>
            <a href="<?= url('finanzas/solvencias') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 <?= isActive('/finanzas/solvencias') ?>">
                <span class="text-lg w-5 text-center">✅</span><span>Solvencias</span>
            </a>
        </div>
        <?php endif; ?>

        <?php if ($rol === ROL_DOCENTE): ?>
        <div class="px-3 mt-4">
            <div class="text-[10px] font-semibold text-slate-600 uppercase tracking-[0.12em] px-3 mb-2">Docencia</div>
            <a href="<?= url('docente/mis-cargas') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 <?= isActive('/docente/mis-cargas') ?>">
                <span class="text-lg w-5 text-center">📚</span><span>Mis Cargas</span>
            </a>
        </div>
        <?php endif; ?>

        <?php if ($rol === ROL_ESTUDIANTE): ?>
        <div class="px-3 mt-4">
            <div class="text-[10px] font-semibold text-slate-600 uppercase tracking-[0.12em] px-3 mb-2">Académico</div>
            <a href="<?= url('estudiante/notas') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/estudiante/notas') ?>">
                <span class="text-lg w-5 text-center">📝</span><span>Mis Notas</span>
            </a>
            <a href="<?= url('estudiante/historico') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/estudiante/historico') ?>">
                <span class="text-lg w-5 text-center">📜</span><span>Histórico Académico</span>
            </a>
            <a href="<?= url('estudiante/indice') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 <?= isActive('/estudiante/indice') ?>">
                <span class="text-lg w-5 text-center">📈</span><span>Índice Académico</span>
            </a>
        </div>
        <?php endif; ?>

        <?php if ($rol === ROL_CONTROL_ESTUDIOS): ?>
        <div class="px-3 mt-4">
            <div class="text-[10px] font-semibold text-slate-600 uppercase tracking-[0.12em] px-3 mb-2">Control de Estudios</div>
            <a href="<?= url('control/inscripciones') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/control/inscripciones') ?>">
                <span class="text-lg w-5 text-center">📝</span><span>Inscripciones</span>
            </a>
            <a href="<?= url('reparaciones') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 <?= isActive('/reparaciones') ?>">
                <span class="text-lg w-5 text-center">🔄</span><span>Reparaciones</span>
            </a>
        </div>
        <?php endif; ?>

        <?php if ($rol === ROL_FINANZAS): ?>
        <div class="px-3 mt-4">
            <div class="text-[10px] font-semibold text-slate-600 uppercase tracking-[0.12em] px-3 mb-2">Finanzas</div>
            <a href="<?= url('finanzas/pagos') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/finanzas/pagos') ?>">
                <span class="text-lg w-5 text-center">💰</span><span>Pagos</span>
            </a>
            <a href="<?= url('finanzas/aranceles') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5 <?= isActive('/finanzas/aranceles') ?>">
                <span class="text-lg w-5 text-center">📑</span><span>Aranceles</span>
            </a>
            <a href="<?= url('finanzas/solvencias') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 <?= isActive('/finanzas/solvencias') ?>">
                <span class="text-lg w-5 text-center">✅</span><span>Solvencias</span>
            </a>
        </div>
        <?php endif; ?>
    </nav>

    <!-- Footer -->
    <div class="px-4 py-4 border-t border-slate-200 dark:border-slate-800/60">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-gradient-to-br from-purple-500 to-violet-700 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                <?= strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-[13px] font-semibold text-slate-800 dark:text-slate-200 truncate"><?= htmlspecialchars(Session::nombreCompleto()) ?></div>
                <div class="text-[11px] text-slate-500 dark:text-slate-500"><?= htmlspecialchars($rol) ?></div>
            </div>
        </div>
    </div>
</aside>
