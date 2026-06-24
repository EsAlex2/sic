<div class="page-header">
    <div>
        <h1>Panel de Finanzas</h1>
        <p class="page-subtitle">Gestión de pagos, aranceles y solvencias</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card emerald">
        <div class="stat-icon emerald">💰</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['total_pagos'] ?></div>
            <div class="stat-label">Pagos Registrados</div>
        </div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue">📑</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['total_aranceles'] ?></div>
            <div class="stat-label">Aranceles Configurados</div>
        </div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon amber">💵</div>
        <div class="stat-content">
            <div class="stat-value">Bs. <?= $stats['monto_recaudado'] ?></div>
            <div class="stat-label">Monto Recaudado</div>
        </div>
    </div>
</div>

<div class="content-grid">
    <div class="card">
        <div class="card-header"><h3 class="card-title">⚡ Acciones Rápidas</h3></div>
        <div class="card-body">
            <div class="d-flex gap-2" style="flex-wrap: wrap;">
                <a href="<?= url('finanzas/pagos') ?>" class="btn btn-primary">💰 Gestionar Pagos</a>
                <a href="<?= url('finanzas/aranceles') ?>" class="btn btn-outline">📑 Aranceles</a>
                <a href="<?= url('finanzas/solvencias') ?>" class="btn btn-outline">✅ Solvencias</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 class="card-title">📊 Resumen</h3></div>
        <div class="card-body">
            <p class="text-secondary text-sm">Desde el módulo de Finanzas puedes registrar pagos, configurar aranceles y verificar la solvencia de los estudiantes.</p>
        </div>
    </div>
</div>
