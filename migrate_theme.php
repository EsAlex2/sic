<?php
/**
 * SIC — Migrate Theme Script
 * Este script automatiza la transformación de las clases Tailwind
 * que estaban hardcodeadas en oscuro, hacia un formato responsivo:
 * `bg-white dark:bg-slate-900`.
 */

$viewsDir = __DIR__ . '/views';

// Mapa de reemplazos comunes
$replacements = [
    // Backgrounds
    'bg-slate-900' => 'bg-white dark:bg-slate-900',
    'bg-slate-800' => 'bg-slate-50 dark:bg-slate-800',
    'bg-slate-700' => 'bg-slate-100 dark:bg-slate-700',
    'bg-slate-925' => 'bg-slate-50 dark:bg-slate-925',
    'bg-slate-950' => 'bg-slate-100 dark:bg-slate-950',
    
    // Text colors
    'text-slate-100' => 'text-slate-900 dark:text-slate-100',
    'text-slate-200' => 'text-slate-800 dark:text-slate-200',
    'text-slate-300' => 'text-slate-700 dark:text-slate-300',
    'text-slate-400' => 'text-slate-600 dark:text-slate-400',
    'text-slate-500' => 'text-slate-500 dark:text-slate-500',
    
    // Borders
    'border-slate-800' => 'border-slate-200 dark:border-slate-800',
    'border-slate-700' => 'border-slate-300 dark:border-slate-700',
    
    // Alphas backgrounds (e.g. bg-slate-800/50, bg-slate-800/60, bg-slate-800/30)
    'bg-slate-800/50' => 'bg-slate-100 dark:bg-slate-800/50',
    'bg-slate-800/60' => 'bg-slate-100 dark:bg-slate-800/60',
    'bg-slate-800/30' => 'bg-slate-50 dark:bg-slate-800/30',
    'border-slate-800/50' => 'border-slate-200 dark:border-slate-800/50',
    'border-slate-700/50' => 'border-slate-300 dark:border-slate-700/50',
    'border-slate-800/60' => 'border-slate-200 dark:border-slate-800/60',
    'border-white/5'      => 'border-slate-200 dark:border-white/5',
];

function processDirectory($dir, $replacements) {
    $files = scandir($dir);
    $count = 0;
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;

        if (is_dir($path)) {
            $count += processDirectory($path, $replacements);
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($path);
            $newContent = $content;

            foreach ($replacements as $search => $replace) {
                // regex with word boundaries around tailwind classes (considering quotes and spaces)
                $pattern = '/(?<=\s|["\'])' . preg_quote($search, '/') . '(?=\s|["\'])/';
                
                if (!str_contains($newContent, $replace)) {
                    $newContent = preg_replace($pattern, $replace, $newContent);
                }
            }

            if ($newContent !== $content) {
                file_put_contents($path, $newContent);
                echo "Modificado: $path\n";
                $count++;
            }
        }
    }
    return $count;
}

echo "Iniciando migración de clases para Modo Claro / Oscuro...\n";
$modificados = processDirectory($viewsDir, $replacements);
echo "Migración completada. Total archivos modificados: $modificados\n";
