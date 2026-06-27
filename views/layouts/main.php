<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIC — Sistema de Información y Control Académico de la UNEXCA">
    <title><?= $titulo ?? 'SIC' ?> — UNEXCA</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="<?= url('public/css/custom.css') ?>">
    <style type="text/tailwindcss">
        @theme {
            --color-slate-950: #020617;
            --color-slate-925: #0a101f;
        }
    </style>
    <script>
        // Prevenir flash de unstyled content (FOUC)
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-900 dark:text-slate-100 font-[Inter] min-h-screen overflow-x-hidden transition-colors duration-300">
    <div class="flex min-h-screen">
        <?php require VIEWS_PATH . '/components/sidebar.php'; ?>

        <main class="flex-1 ml-64 transition-all duration-300" id="main-content">
            <?php require VIEWS_PATH . '/components/topbar.php'; ?>

            <div class="p-6 max-w-[1440px] animate-fade">
                <?php
                $flash = $flash ?? getFlash();
                if ($flash):
                    $alertColors = match($flash['type']) {
                        'success' => 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400',
                        'error'   => 'bg-red-500/10 border-red-500/20 text-red-400',
                        'warning' => 'bg-amber-500/10 border-amber-500/20 text-amber-400',
                        default   => 'bg-blue-500/10 border-blue-500/20 text-blue-400',
                    };
                ?>
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl border <?= $alertColors ?> mb-6 animate-fade" id="flash-alert">
                        <span class="flex-1 text-sm font-medium"><?= htmlspecialchars($flash['message']) ?></span>
                        <button onclick="document.getElementById('flash-alert').remove()" class="opacity-60 hover:opacity-100 transition text-lg">×</button>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </main>
    </div>

    <script src="<?= url('public/js/app.js') ?>"></script>
</body>
</html>
