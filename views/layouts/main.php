<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIC — Sistema de Información y Control Académico de la UNEXCA">
    <title><?= $titulo ?? 'SIC' ?> — UNEXCA</title>
    <link rel="stylesheet" href="<?= url('public/css/main.css') ?>">
    <link rel="stylesheet" href="<?= url('public/css/components.css') ?>">
</head>
<body>
    <div class="app-layout">
        <?php require VIEWS_PATH . '/components/sidebar.php'; ?>

        <main class="app-main">
            <?php require VIEWS_PATH . '/components/topbar.php'; ?>

            <div class="app-content">
                <?php
                // Mostrar alertas flash
                $flash = $flash ?? getFlash();
                if ($flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?>">
                        <span><?= htmlspecialchars($flash['message']) ?></span>
                        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </main>
    </div>

    <script src="<?= url('public/js/app.js') ?>"></script>
</body>
</html>
