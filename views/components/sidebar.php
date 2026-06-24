<?php
/**
 * Componente Sidebar — Navegación lateral dinámica por rol
 */
$usuario = Session::obtenerUsuario();
$rol = $usuario['rol'] ?? '';
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function isActive(string $path): string {
    global $currentPath;
    return str_contains($currentPath, $path) ? 'active' : '';
}
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">SIC</div>
        <div class="sidebar-brand">
            <span class="sidebar-brand-name">UNEXCA</span>
            <span class="sidebar-brand-sub">Sistema Académico</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <div class="sidebar-section">
            <a href="<?= url('dashboard') ?>" class="sidebar-link <?= isActive('/dashboard') ?>">
                <span class="icon">📊</span>
                <span>Dashboard</span>
            </a>
        </div>

        <?php if ($rol === ROL_ADMIN): ?>
        <!-- Admin -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Administración</div>
            <a href="<?= url('admin/personas') ?>" class="sidebar-link <?= isActive('/admin/personas') ?>">
                <span class="icon">🪪</span><span>Personas</span>
            </a>
            <a href="<?= url('admin/usuarios') ?>" class="sidebar-link <?= isActive('/admin/usuarios') ?>">
                <span class="icon">👥</span><span>Usuarios</span>
            </a>
            <a href="<?= url('admin/periodos') ?>" class="sidebar-link <?= isActive('/admin/periodos') ?>">
                <span class="icon">📅</span><span>Períodos Académicos</span>
            </a>
            <a href="<?= url('admin/cargas') ?>" class="sidebar-link <?= isActive('/admin/cargas') ?>">
                <span class="icon">📋</span><span>Carga Académica</span>
            </a>
        </div>
        <div class="sidebar-section">
            <div class="sidebar-section-title">Académico</div>
            <a href="<?= url('reparaciones') ?>" class="sidebar-link <?= isActive('/reparaciones') ?>">
                <span class="icon">🔄</span><span>Reparaciones</span>
            </a>
        </div>
        <div class="sidebar-section">
            <div class="sidebar-section-title">Finanzas</div>
            <a href="<?= url('finanzas/pagos') ?>" class="sidebar-link <?= isActive('/finanzas/pagos') ?>">
                <span class="icon">💰</span><span>Pagos</span>
            </a>
            <a href="<?= url('finanzas/aranceles') ?>" class="sidebar-link <?= isActive('/finanzas/aranceles') ?>">
                <span class="icon">📑</span><span>Aranceles</span>
            </a>
            <a href="<?= url('finanzas/solvencias') ?>" class="sidebar-link <?= isActive('/finanzas/solvencias') ?>">
                <span class="icon">✅</span><span>Solvencias</span>
            </a>
        </div>
        <?php endif; ?>

        <?php if ($rol === ROL_DOCENTE): ?>
        <!-- Docente -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Docencia</div>
            <a href="<?= url('docente/mis-cargas') ?>" class="sidebar-link <?= isActive('/docente/mis-cargas') ?>">
                <span class="icon">📚</span><span>Mis Cargas</span>
            </a>
        </div>
        <?php endif; ?>

        <?php if ($rol === ROL_ESTUDIANTE): ?>
        <!-- Estudiante -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Académico</div>
            <a href="<?= url('estudiante/notas') ?>" class="sidebar-link <?= isActive('/estudiante/notas') ?>">
                <span class="icon">📝</span><span>Mis Notas</span>
            </a>
            <a href="<?= url('estudiante/historico') ?>" class="sidebar-link <?= isActive('/estudiante/historico') ?>">
                <span class="icon">📜</span><span>Histórico Académico</span>
            </a>
            <a href="<?= url('estudiante/indice') ?>" class="sidebar-link <?= isActive('/estudiante/indice') ?>">
                <span class="icon">📈</span><span>Índice Académico</span>
            </a>
        </div>
        <?php endif; ?>

        <?php if ($rol === ROL_CONTROL_ESTUDIOS): ?>
        <!-- Control de Estudios -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Control de Estudios</div>
            <a href="<?= url('control/inscripciones') ?>" class="sidebar-link <?= isActive('/control/inscripciones') ?>">
                <span class="icon">📝</span><span>Inscripciones</span>
            </a>
            <a href="<?= url('reparaciones') ?>" class="sidebar-link <?= isActive('/reparaciones') ?>">
                <span class="icon">🔄</span><span>Reparaciones</span>
            </a>
        </div>
        <?php endif; ?>

        <?php if ($rol === ROL_FINANZAS): ?>
        <!-- Finanzas -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Finanzas</div>
            <a href="<?= url('finanzas/pagos') ?>" class="sidebar-link <?= isActive('/finanzas/pagos') ?>">
                <span class="icon">💰</span><span>Pagos</span>
            </a>
            <a href="<?= url('finanzas/aranceles') ?>" class="sidebar-link <?= isActive('/finanzas/aranceles') ?>">
                <span class="icon">📑</span><span>Aranceles</span>
            </a>
            <a href="<?= url('finanzas/solvencias') ?>" class="sidebar-link <?= isActive('/finanzas/solvencias') ?>">
                <span class="icon">✅</span><span>Solvencias</span>
            </a>
        </div>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                <?= strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name"><?= htmlspecialchars(Session::nombreCompleto()) ?></div>
                <div class="sidebar-user-role"><?= htmlspecialchars($rol) ?></div>
            </div>
        </div>
    </div>
</aside>
