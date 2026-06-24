<?php
/**
 * SIC — Script de Seeders
 * Carga los datos iniciales del sistema incluyendo el usuario administrador
 * Uso: php seed.php
 */

if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
    session_start();
}

require_once __DIR__ . '/init.php';

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║   SIC — Carga de Datos Iniciales (Seeders)                ║\n";
echo "║   UNEXCA — Sistema de Información y Control Académico     ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Cargar todos los seeders
$seeders = [
    'seeders/estatus.php'              => 'cargarEstatus',
    'seeders/sedes_unexca.php'         => 'cargarSedes',
    'seeders/trayectos.php'            => 'cargarTrayectos',
    'seeders/turnos.php'               => 'cargarTurnos',
    'seeders/aulas.php'                => 'cargarAulas',
    'seeders/tipos_usuario.php'        => 'cargarTiposUsuario',
    'seeders/tipos_evaluacion.php'     => 'cargarTiposEvaluacion',
    'seeders/caracter_asignatura.php'  => 'cargarCaracterAsignatura',
    'seeders/pnf.php'                  => 'cargarPnf',
];

foreach ($seeders as $archivo => $funcion) {
    $ruta = __DIR__ . '/' . $archivo;

    if (!file_exists($ruta)) {
        echo "  ✗ Archivo no encontrado: {$archivo}\n";
        continue;
    }

    require_once $ruta;

    if (function_exists($funcion)) {
        echo "  ► Ejecutando: {$funcion}()... ";
        try {
            $funcion();
            echo "\n";
        } catch (Exception $e) {
            echo "✗ Error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "  ✗ Función no encontrada: {$funcion}\n";
    }
}

// ─── Crear usuario Administrador ────────────────────────────────────────
echo "\n  ► Creando usuario administrador...\n";

$db = new Conexion();
$conexion = $db->getConexion();

try {
    $conexion->beginTransaction();

    // Verificar si ya existe
    $check = $conexion->prepare("SELECT id_usuario FROM unexca.usuarios WHERE cedula = :cedula");
    $check->execute([':cedula' => '27391753']);

    if ($check->fetch()) {
        echo "    ○ El usuario administrador ya existe (cédula: 27391753)\n";
    } else {
        // Obtener id_estatus 'Activo'
        $stmtEstatus = $conexion->prepare("SELECT id_estatus FROM unexca.estatus WHERE nombre_estatus = 'Activo' LIMIT 1");
        $stmtEstatus->execute();
        $estatus = $stmtEstatus->fetch();
        $idEstatus = $estatus ? $estatus['id_estatus'] : 1;

        // Obtener id_tipo 'Administrador'
        $stmtTipo = $conexion->prepare("SELECT id_tipo FROM unexca.tipos_usuario WHERE nombre_tipo = 'Administrador' LIMIT 1");
        $stmtTipo->execute();
        $tipo = $stmtTipo->fetch();
        $idTipo = $tipo ? $tipo['id_tipo'] : 1;

        // Crear datos_personas primero
        $stmtPersona = $conexion->prepare("
            INSERT INTO unexca.datos_personas
                (id_estatus, cedula_identidad, nombres, apellidos, genero, fecha_nacimiento, correo_personal, telefono_personal, direccion_habitacion, fecha_ingreso)
            VALUES
                (:id_estatus, :cedula, :nombres, :apellidos, :genero, :nacimiento, :correo, :telefono, :direccion, CURRENT_DATE)
            RETURNING id_persona
        ");
        $stmtPersona->execute([
            ':id_estatus'  => $idEstatus,
            ':cedula'      => 27391753,
            ':nombres'     => 'Alex Jonfranc',
            ':apellidos'   => 'Madrid Marin',
            ':genero'      => 'Masculino',
            ':nacimiento'  => '2001-01-28',
            ':correo'      => 'alexmadrid326@gmail.com',
            ':telefono'    => '0414-0000000',
            ':direccion'   => 'Caracas, Venezuela',
        ]);
        $persona = $stmtPersona->fetch();
        $idPersona = $persona['id_persona'];

        // Crear usuario con password hasheado
        $passwordHash = password_hash('qwerty2801**', PASSWORD_BCRYPT);

        $stmtUsuario = $conexion->prepare("
            INSERT INTO unexca.usuarios
                (id_persona, cedula, correo_institucional, password_hash, id_tipo, id_estatus)
            VALUES
                (:id_persona, :cedula, :correo, :password, :id_tipo, :id_estatus)
        ");
        $stmtUsuario->execute([
            ':id_persona' => $idPersona,
            ':cedula'     => '27391753',
            ':correo'     => 'admin@unexca.edu.ve',
            ':password'   => $passwordHash,
            ':id_tipo'    => $idTipo,
            ':id_estatus' => $idEstatus,
        ]);

        echo "    ✓ Usuario administrador creado:\n";
        echo "      Cédula:     27391753\n";
        echo "      Nombre:     Alex Jonfranc Madrid Marin\n";
        echo "      Correo:     admin@unexca.edu.ve\n";
        echo "      Contraseña: qwerty2801**\n";
        echo "      Rol:        Administrador\n";
    }

    $conexion->commit();
} catch (PDOException $e) {
    $conexion->rollBack();
    echo "    ✗ Error al crear administrador: " . $e->getMessage() . "\n";
}

echo "\n  ═══════════════════════════════════════════════════════\n";
echo "  ✓ Seeders completados\n";
echo "  ═══════════════════════════════════════════════════════\n\n";
echo "  El sistema está listo. Accede a: http://localhost/sic/login\n\n";
