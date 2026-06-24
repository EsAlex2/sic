<div class="page-header">
    <div>
        <h1>Portal del Estudiante</h1>
        <p class="page-subtitle">Consulta tus notas, histórico e índice académico</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon blue">📚</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['materias_inscritas'] ?></div>
            <div class="stat-label">Materias Inscritas</div>
        </div>
    </div>
    <div class="stat-card emerald">
        <div class="stat-icon emerald">📊</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['promedio_actual'] ?></div>
            <div class="stat-label">Promedio Acumulado</div>
        </div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon purple">🎯</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['creditos_aprobados'] ?></div>
            <div class="stat-label">Créditos Aprobados</div>
        </div>
    </div>
</div>

<div class="content-grid">
    <div class="card">
        <div class="card-header"><h3 class="card-title">⚡ Acceso Rápido</h3></div>
        <div class="card-body">
            <div class="d-flex gap-2" style="flex-wrap: wrap;">
                <a href="<?= url('estudiante/notas') ?>" class="btn btn-primary">📝 Mis Notas</a>
                <a href="<?= url('estudiante/historico') ?>" class="btn btn-outline">📜 Histórico</a>
                <a href="<?= url('estudiante/indice') ?>" class="btn btn-outline">📈 Índice Académico</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 class="card-title">📋 Período Actual</h3></div>
        <div class="card-body">
            <p class="text-secondary text-sm">Consulta tus calificaciones parciales y definitivas del período en curso desde la sección «Mis Notas».</p>
        </div>
    </div>
</div>
