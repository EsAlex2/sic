<?php
/**
 * SIC — Script de Migración
 * Ejecuta los archivos SQL para crear las tablas del sistema
 * Uso: php migrate.php
 */

// No usar session_start en CLI
if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
    session_start();
}

require_once __DIR__ . '/init.php';

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║   SIC — Migración de Base de Datos                        ║\n";
echo "║   UNEXCA — Sistema de Información y Control Académico     ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$db = new Conexion();
$conexion = $db->getConexion();

// Archivos SQL a ejecutar en orden
$archivos = [
    'config/database.sql' => 'Schema base (28 tablas)',
    'config/notas.sql'    => 'Sistema de notas (13 tablas + vistas + funciones)',
];

$exitoso = true;

foreach ($archivos as $archivo => $descripcion) {
    $ruta = __DIR__ . '/' . $archivo;

    if (!file_exists($ruta)) {
        echo "  ✗ Archivo no encontrado: {$archivo}\n";
        $exitoso = false;
        continue;
    }

    echo "  ► Ejecutando: {$descripcion}...\n";
    echo "    Archivo: {$archivo}\n";

    $sql = file_get_contents($ruta);

    // Separar por comandos (CREATE TABLE, CREATE OR REPLACE VIEW, CREATE OR REPLACE FUNCTION, etc.)
    // Usamos un approach basado en regex para separar los comandos SQL correctamente
    $comandos = preg_split('/;(?=\s*(?:CREATE|INSERT|ALTER|DROP|COMMENT)\s)/i', $sql);

    $tablas = 0;
    $errores = 0;

    foreach ($comandos as $comando) {
        $comando = trim($comando);
        if (empty($comando) || $comando === ';') continue;

        // Agregar el ; al final si no lo tiene
        if (!str_ends_with(rtrim($comando), ';') && !str_contains($comando, '$$ LANGUAGE')) {
            $comando .= ';';
        }

        try {
            $conexion->exec($comando);
            $tablas++;

            // Extraer nombre para mostrar
            if (preg_match('/CREATE\s+(?:TABLE|VIEW|OR\s+REPLACE\s+VIEW|OR\s+REPLACE\s+FUNCTION)\s+(?:IF\s+NOT\s+EXISTS\s+)?(\S+)/i', $comando, $matches)) {
                echo "    ✓ {$matches[1]}\n";
            }
        } catch (PDOException $e) {
            $errores++;
            // Ignorar si ya existe
            if (str_contains($e->getMessage(), 'already exists')) {
                if (preg_match('/CREATE\s+\w+\s+(?:IF\s+NOT\s+EXISTS\s+)?(\S+)/i', $comando, $matches)) {
                    echo "    ○ {$matches[1]} (ya existe)\n";
                }
            } else {
                echo "    ✗ Error: " . substr($e->getMessage(), 0, 100) . "\n";
                $exitoso = false;
            }
        }
    }

    echo "    Resumen: {$tablas} comandos ejecutados";
    if ($errores > 0) echo ", {$errores} con errores";
    echo "\n\n";
}

if ($exitoso) {
    echo "  ═══════════════════════════════════════════════════════\n";
    echo "  ✓ Migración completada exitosamente\n";
    echo "  ═══════════════════════════════════════════════════════\n";
    echo "\n  Siguiente paso: php seed.php\n\n";
} else {
    echo "  ═══════════════════════════════════════════════════════\n";
    echo "  ✗ Migración completada con errores\n";
    echo "  ═══════════════════════════════════════════════════════\n\n";
}