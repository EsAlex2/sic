<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Iniciar sesión en el Sistema de Información y Control Académico — UNEXCA">
    <title>Iniciar Sesión — SIC UNEXCA</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="<?= url('public/css/custom.css') ?>">
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-[Inter] min-h-screen overflow-hidden">
    <div class="min-h-screen flex items-center justify-center relative">
        <!-- Animated orbs -->
        <div class="absolute w-[600px] h-[600px] bg-blue-600 rounded-full -top-52 -right-28 blur-[120px] opacity-15 animate-float"></div>
        <div class="absolute w-[500px] h-[500px] bg-purple-600 rounded-full -bottom-40 -left-28 blur-[120px] opacity-15 animate-float-delay"></div>

        <div class="w-full max-w-md px-6 z-10">
            <div class="bg-slate-900/60 backdrop-blur-2xl border border-white/[0.06] rounded-3xl p-8 shadow-2xl shadow-black/40 animate-fade">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl font-extrabold text-white shadow-xl shadow-blue-500/30 animate-pulse-glow">
                        SIC
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-1">Bienvenido</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-500">Sistema de Información y Control Académico</p>
                </div>

                <?php if (!empty($flash)): ?>
                    <?php
                    $alertColors = match($flash['type']) {
                        'success' => 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400',
                        'error'   => 'bg-red-500/10 border-red-500/20 text-red-400',
                        default   => 'bg-blue-500/10 border-blue-500/20 text-blue-400',
                    };
                    ?>
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl border <?= $alertColors ?> mb-6 animate-fade">
                        <span class="text-sm"><?= htmlspecialchars($flash['message']) ?></span>
                        <button onclick="this.parentElement.remove()" class="ml-auto opacity-60 hover:opacity-100 text-lg">×</button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= url('login') ?>">
                    <div class="mb-5">
                        <label for="cedula" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2">Cédula de Identidad</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-500 text-base">🪪</span>
                            <input type="text" id="cedula" name="cedula"
                                   class="w-full pl-11 pr-4 py-3 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition text-[15px]"
                                   placeholder="V-12345678" required autocomplete="username" autofocus>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2">Contraseña</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-500 text-base">🔒</span>
                            <input type="password" id="password" name="password"
                                   class="w-full pl-11 pr-12 py-3 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition text-[15px]"
                                   placeholder="••••••••" required autocomplete="current-password">
                            <button type="button" onclick="togglePassword()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 transition cursor-pointer" id="toggleBtn">👁</button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 hover:-translate-y-0.5 transition-all duration-200 text-[15px] cursor-pointer">
                        Iniciar Sesión
                    </button>
                </form>

                <div class="text-center mt-7">
                    <p class="text-xs text-slate-600 leading-relaxed">Universidad Nacional Experimental<br>de la Gran Caracas</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const btn = document.getElementById('toggleBtn');
            if (input.type === 'password') { input.type = 'text'; btn.textContent = '🙈'; }
            else { input.type = 'password'; btn.textContent = '👁'; }
        }
    </script>
</body>
</html>
