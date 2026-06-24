<?php
require_once __DIR__ . '/../init.php';

/**
 * Funcion para crear los datos iniciales en la base de datos
 */

function cargarAulas()
{
    $data = [
        // Piso 1
        ['piso' => 'Piso 1', 'nro_aula' => '101', 'nombre_aula' => 'Aula 101'],
        ['piso' => 'Piso 1', 'nro_aula' => '102', 'nombre_aula' => 'Aula 102'],
        ['piso' => 'Piso 1', 'nro_aula' => '103', 'nombre_aula' => 'Aula 103'],
        ['piso' => 'Piso 1', 'nro_aula' => '104', 'nombre_aula' => 'Aula 104'],
        ['piso' => 'Piso 1', 'nro_aula' => '105', 'nombre_aula' => 'Aula 105'],
        ['piso' => 'Piso 1', 'nro_aula' => '106', 'nombre_aula' => 'Aula 106'],
        ['piso' => 'Piso 1', 'nro_aula' => '107', 'nombre_aula' => 'Aula 107'],
        ['piso' => 'Piso 1', 'nro_aula' => '108', 'nombre_aula' => 'Aula 108'],
        ['piso' => 'Piso 1', 'nro_aula' => '109', 'nombre_aula' => 'Aula 109'],
        ['piso' => 'Piso 1', 'nro_aula' => '110', 'nombre_aula' => 'Aula 110'],

        // Piso 2
        ['piso' => 'Piso 2', 'nro_aula' => '201', 'nombre_aula' => 'Aula 201'],
        ['piso' => 'Piso 2', 'nro_aula' => '202', 'nombre_aula' => 'Aula 202'],
        ['piso' => 'Piso 2', 'nro_aula' => '203', 'nombre_aula' => 'Aula 203'],
        ['piso' => 'Piso 2', 'nro_aula' => '204', 'nombre_aula' => 'Aula 204'],
        ['piso' => 'Piso 2', 'nro_aula' => '205', 'nombre_aula' => 'Aula 205'],
        ['piso' => 'Piso 2', 'nro_aula' => '206', 'nombre_aula' => 'Aula 206'],
        ['piso' => 'Piso 2', 'nro_aula' => '207', 'nombre_aula' => 'Aula 207'],
        ['piso' => 'Piso 2', 'nro_aula' => '208', 'nombre_aula' => 'Aula 208'],
        ['piso' => 'Piso 2', 'nro_aula' => '209', 'nombre_aula' => 'Aula 209'],
        ['piso' => 'Piso 2', 'nro_aula' => '210', 'nombre_aula' => 'Aula 210'],

        // Piso 3
        ['piso' => 'Piso 3', 'nro_aula' => '301', 'nombre_aula' => 'Aula 301'],
        ['piso' => 'Piso 3', 'nro_aula' => '302', 'nombre_aula' => 'Aula 302'],
        ['piso' => 'Piso 3', 'nro_aula' => '303', 'nombre_aula' => 'Aula 303'],
        ['piso' => 'Piso 3', 'nro_aula' => '304', 'nombre_aula' => 'Aula 304'],
        ['piso' => 'Piso 3', 'nro_aula' => '305', 'nombre_aula' => 'Aula 305'],
        ['piso' => 'Piso 3', 'nro_aula' => '306', 'nombre_aula' => 'Aula 306'],
        ['piso' => 'Piso 3', 'nro_aula' => '307', 'nombre_aula' => 'Aula 307'],
        ['piso' => 'Piso 3', 'nro_aula' => '308', 'nombre_aula' => 'Aula 308'],
        ['piso' => 'Piso 3', 'nro_aula' => '309', 'nombre_aula' => 'Aula 309'],
        ['piso' => 'Piso 3', 'nro_aula' => '310', 'nombre_aula' => 'Aula 310'],

        // Piso 4
        ['piso' => 'Piso 4', 'nro_aula' => '401', 'nombre_aula' => 'Aula 401'],
        ['piso' => 'Piso 4', 'nro_aula' => '402', 'nombre_aula' => 'Aula 402'],
        ['piso' => 'Piso 4', 'nro_aula' => '403', 'nombre_aula' => 'Aula 403'],
        ['piso' => 'Piso 4', 'nro_aula' => '404', 'nombre_aula' => 'Aula 404'],
        ['piso' => 'Piso 4', 'nro_aula' => '405', 'nombre_aula' => 'Aula 405'],
        ['piso' => 'Piso 4', 'nro_aula' => '406', 'nombre_aula' => 'Aula 406'],
        ['piso' => 'Piso 4', 'nro_aula' => '407', 'nombre_aula' => 'Aula 407'],
        ['piso' => 'Piso 4', 'nro_aula' => '408', 'nombre_aula' => 'Aula 408'],
        ['piso' => 'Piso 4', 'nro_aula' => '409', 'nombre_aula' => 'Aula 409'],
        ['piso' => 'Piso 4', 'nro_aula' => '410', 'nombre_aula' => 'Aula 410'],

        // Piso 5
        ['piso' => 'Piso 5', 'nro_aula' => '501', 'nombre_aula' => 'Aula 501'],
        ['piso' => 'Piso 5', 'nro_aula' => '502', 'nombre_aula' => 'Aula 502'],
        ['piso' => 'Piso 5', 'nro_aula' => '503', 'nombre_aula' => 'Aula 503'],
        ['piso' => 'Piso 5', 'nro_aula' => '504', 'nombre_aula' => 'Aula 504'],
        ['piso' => 'Piso 5', 'nro_aula' => '505', 'nombre_aula' => 'Aula 505'],
        ['piso' => 'Piso 5', 'nro_aula' => '506', 'nombre_aula' => 'Aula 506'],
        ['piso' => 'Piso 5', 'nro_aula' => '507', 'nombre_aula' => 'Aula 507'],
        ['piso' => 'Piso 5', 'nro_aula' => '508', 'nombre_aula' => 'Aula 508'],
        ['piso' => 'Piso 5', 'nro_aula' => '509', 'nombre_aula' => 'Aula 509'],
        ['piso' => 'Piso 5', 'nro_aula' => '510', 'nombre_aula' => 'Aula 510'],

        // Piso 6
        ['piso' => 'Piso 6', 'nro_aula' => '601', 'nombre_aula' => 'Aula 601'],
        ['piso' => 'Piso 6', 'nro_aula' => '602', 'nombre_aula' => 'Aula 602'],
        ['piso' => 'Piso 6', 'nro_aula' => '603', 'nombre_aula' => 'Aula 603'],
        ['piso' => 'Piso 6', 'nro_aula' => '604', 'nombre_aula' => 'Aula 604'],
        ['piso' => 'Piso 6', 'nro_aula' => '605', 'nombre_aula' => 'Aula 605'],
        ['piso' => 'Piso 6', 'nro_aula' => '606', 'nombre_aula' => 'Aula 606'],
        ['piso' => 'Piso 6', 'nro_aula' => '607', 'nombre_aula' => 'Aula 607'],
        ['piso' => 'Piso 6', 'nro_aula' => '608', 'nombre_aula' => 'Aula 608'],
        ['piso' => 'Piso 6', 'nro_aula' => '609', 'nombre_aula' => 'Aula 609'],
        ['piso' => 'Piso 6', 'nro_aula' => '610', 'nombre_aula' => 'Aula 610'],
    ];

    $db = new Conexion();
    $conexion = $db->getConexion();
    $mensaje = "Los salones de la UNEXCA han sido añadidas a la base de datos";

    try {
        $conexion->beginTransaction();

        $query = "INSERT INTO unexca.aulas (piso, nro_aula, nombre_aula) VALUES (:piso, :nro, :nom)";
        $stmt = $conexion->prepare($query);

        foreach ($data as $aulas) {
            $stmt->execute([
                ':piso' => $aulas['piso'],
                ':nro' => $aulas['nro_aula'],
                ':nom' => $aulas['nombre_aula']
            ]);
        }

        $conexion->commit();
        echo $mensaje;
    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error al insertar datos: " . $e->getMessage();
    }
}
