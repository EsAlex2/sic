<div class="page-header">
    <div>
        <h1>Mis Cargas Académicas</h1>
        <p class="page-subtitle">Período Académico Actual</p>
    </div>
</div>

<?php if (empty($cargas)): ?>
    <div class="card empty-state">
        <div class="empty-state-icon">📚</div>
        <h3 class="empty-state-title">No hay cargas asignadas</h3>
        <p>No tienes secciones asignadas en el período activo actual.</p>
    </div>
<?php else: ?>
    <div class="content-grid">
        <?php foreach ($cargas as $carga): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-info"><?= htmlspecialchars($carga['asignatura']) ?></h3>
                    <span class="badge badge-purple">Sección <?= htmlspecialchars($carga['seccion']) ?></span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-between mb-3 text-sm">
                        <span class="text-secondary">👥 Inscritos: <strong><?= $carga['inscritos'] ?></strong></span>
                        <span class="text-secondary">🏢 Sede: <?= htmlspecialchars($carga['sede']) ?></span>
                    </div>

                    <div class="d-flex flex-column gap-1">
                        <a href="<?= url('docente/plan-evaluacion/' . $carga['id_carga']) ?>" class="btn btn-outline w-full justify-center">
                            📝 Plan de Evaluación
                        </a>
                        <a href="<?= url('docente/calificaciones/' . $carga['id_carga']) ?>" class="btn btn-primary w-full justify-center">
                            ✏️ Calificaciones
                        </a>
                        <a href="<?= url('docente/acta/' . $carga['id_carga']) ?>" class="btn btn-outline w-full justify-center">
                            📄 Acta de Notas
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
