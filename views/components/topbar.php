<?php $usuario = Session::obtenerUsuario(); ?>
<header class="topbar">
    <div class="d-flex align-center gap-2">
        <button class="topbar-btn" id="sidebar-toggle" title="Menú">☰</button>
        <h2 class="topbar-title"><?= htmlspecialchars($titulo ?? 'SIC') ?></h2>
    </div>
    <div class="topbar-actions">
        <span class="text-sm text-secondary"><?= date('d/m/Y') ?></span>
        <a href="<?= url('logout') ?>" class="btn btn-outline btn-sm" title="Cerrar Sesión">⏻ Salir</a>
    </div>
</header>
