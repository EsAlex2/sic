<?php $usuario = Session::obtenerUsuario(); ?>
<header class="h-16 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-b border-slate-200 dark:border-white/5 flex items-center justify-between px-6 sticky top-0 z-40 transition-colors duration-300">
    <div class="flex items-center gap-3">
        <button class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-800 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-100 dark:bg-slate-700 transition cursor-pointer text-sm" id="sidebar-toggle">☰</button>
        <h2 class="text-[15px] font-semibold text-slate-800 dark:text-slate-900 dark:text-slate-100"><?= htmlspecialchars($titulo ?? 'SIC') ?></h2>
    </div>
    <div class="flex items-center gap-4">
        <!-- Theme Toggle -->
        <button id="theme-toggle" class="w-9 h-9 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-100 dark:bg-slate-700 transition" title="Cambiar Tema">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
        </button>

        <span class="text-xs text-slate-500 dark:text-slate-500 dark:text-slate-400 font-medium"><?= date('d/m/Y') ?></span>
        <a href="<?= url('logout') ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-50 dark:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-800 dark:text-slate-200 transition">
            ⏻ Salir
        </a>
    </div>
</header>
