<div class="page-header">
    <div>
        <h1>Panel de Control de Estudios</h1>
        <p class="page-subtitle">Gestión de inscripciones, actas y expedientes académicos</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon blue">📝</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['inscripciones'] ?></div>
            <div class="stat-label">Inscripciones</div>
        </div>
    </div>
    <div class="stat-card emerald">
        <div class="stat-icon emerald">📄</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['actas'] ?></div>
            <div class="stat-label">Actas de Notas</div>
        </div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon amber">🔄</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['reparaciones'] ?></div>
            <div class="stat-label">Reparaciones</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">⚡ Acciones Rápidas</h3></div>
    <div class="card-body">
        <div class="d-flex gap-2" style="flex-wrap: wrap;">
            <a href="<?= url('control/inscripciones') ?>" class="btn btn-primary">📝 Inscripciones</a>
            <a href="<?= url('reparaciones') ?>" class="btn btn-outline">🔄 Reparaciones</a>
        </div>
    </div>
</div>
