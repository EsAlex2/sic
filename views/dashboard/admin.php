<div class="page-header">
    <div>
        <h1>Panel de Administración</h1>
        <p class="page-subtitle">Vista general del sistema académico UNEXCA</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon blue">🎓</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['total_estudiantes'] ?></div>
            <div class="stat-label">Estudiantes</div>
        </div>
    </div>
    <div class="stat-card emerald">
        <div class="stat-icon emerald">👨‍🏫</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['total_docentes'] ?></div>
            <div class="stat-label">Docentes</div>
        </div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon purple">👥</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['total_usuarios'] ?></div>
            <div class="stat-label">Usuarios del Sistema</div>
        </div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon amber">📅</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['periodos_activos'] ?></div>
            <div class="stat-label">Períodos Activos</div>
        </div>
    </div>
    <div class="stat-card cyan">
        <div class="stat-icon cyan">📋</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['total_cargas'] ?></div>
            <div class="stat-label">Cargas Académicas</div>
        </div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon red">📚</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['total_pnf'] ?></div>
            <div class="stat-label">Programas (PNF)</div>
        </div>
    </div>
</div>

<div class="content-grid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">⚡ Acciones Rápidas</h3>
        </div>
        <div class="card-body">
            <div class="d-flex gap-2" style="flex-wrap: wrap;">
                <a href="<?= url('admin/usuarios') ?>" class="btn btn-primary">👥 Gestionar Usuarios</a>
                <a href="<?= url('admin/periodos') ?>" class="btn btn-outline">📅 Períodos</a>
                <a href="<?= url('admin/cargas') ?>" class="btn btn-outline">📋 Cargas</a>
                <a href="<?= url('reparaciones') ?>" class="btn btn-outline">🔄 Reparaciones</a>
                <a href="<?= url('finanzas/pagos') ?>" class="btn btn-outline">💰 Finanzas</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📋 Información del Sistema</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div class="d-flex justify-between align-center">
                    <span class="text-secondary">Versión</span>
                    <span class="badge badge-info">SIC v1.0.0</span>
                </div>
                <div class="d-flex justify-between align-center">
                    <span class="text-secondary">Base de Datos</span>
                    <span class="badge badge-success">PostgreSQL 18</span>
                </div>
                <div class="d-flex justify-between align-center">
                    <span class="text-secondary">PHP</span>
                    <span class="badge badge-purple">v<?= phpversion() ?></span>
                </div>
                <div class="d-flex justify-between align-center">
                    <span class="text-secondary">Servidor</span>
                    <span class="badge badge-warning">Apache/XAMPP</span>
                </div>
            </div>
        </div>
    </div>
</div>
