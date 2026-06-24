<div class="page-header">
    <div>
        <h1>Panel del Docente</h1>
        <p class="page-subtitle">Gestiona tus cargas académicas y calificaciones</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon blue">📚</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['total_cargas'] ?></div>
            <div class="stat-label">Cargas Asignadas</div>
        </div>
    </div>
    <div class="stat-card emerald">
        <div class="stat-icon emerald">🎓</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['total_estudiantes'] ?></div>
            <div class="stat-label">Estudiantes</div>
        </div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon amber">📄</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['actas_pendientes'] ?></div>
            <div class="stat-label">Actas Pendientes</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">⚡ Acciones Rápidas</h3>
    </div>
    <div class="card-body">
        <div class="d-flex gap-2" style="flex-wrap: wrap;">
            <a href="<?= url('docente/mis-cargas') ?>" class="btn btn-primary">📚 Mis Cargas Académicas</a>
        </div>
        <p class="text-secondary mt-2 text-sm">
            Desde «Mis Cargas» puedes gestionar el plan de evaluación, cargar calificaciones y generar actas para cada sección.
        </p>
    </div>
</div>
