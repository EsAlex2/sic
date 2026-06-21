<?php
require_once __DIR__ . '/../init.php';

/**
 * Funcion para crear los datos iniciales en la base de datos
 */

function cargarPnf()
{
    $data = [
        [
            'id_sede' => 1,
            'cod_pnf' => 'PNF-ADM',
            'nombre_pnf' => 'PNF en Administración',
            'descripcion' => 'Formación en gestión y dirección de organizaciones.',
            'duracion_pnf' => 4,
            'unidad_total_creditos' => 180
        ],
        [
            'id_sede' => 1,
            'cod_pnf' => 'PNF-CP',
            'nombre_pnf' => 'PNF en Contaduría Pública',
            'descripcion' => 'Formación en sistemas de información contable y financiera.',
            'duracion_pnf' => 4,
            'unidad_total_creditos' => 185
        ],
        [
            'id_sede' => 1,
            'cod_pnf' => 'PNF-DL',
            'nombre_pnf' => 'PNF en Distribución y Logística',
            'descripcion' => 'Gestión de cadenas de suministro y procesos logísticos.',
            'duracion_pnf' => 4,
            'unidad_total_creditos' => 175
        ],
        [
            'id_sede' => 1,
            'cod_pnf' => 'PNF-EE',
            'nombre_pnf' => 'PNF en Educación Especial | Lenguaje de señas',
            'descripcion' => 'Especialización en atención a la diversidad y comunicación inclusiva.',
            'duracion_pnf' => 4,
            'unidad_total_creditos' => 170
        ],
        [
            'id_sede' => 1,
            'cod_pnf' => 'PNF-INF',
            'nombre_pnf' => 'PNF en Ingeniería Informática',
            'descripcion' => 'Desarrollo de software, redes y soluciones tecnológicas.',
            'duracion_pnf' => 4,
            'unidad_total_creditos' => 190
        ],
        [
            'id_sede' => 1,
            'cod_pnf' => 'PNF-TUR',
            'nombre_pnf' => 'PNF en Turismo',
            'descripcion' => 'Gestión de servicios turísticos y desarrollo sustentable.',
            'duracion_pnf' => 4,
            'unidad_total_creditos' => 165
        ],
    ];

    $db = new Conexion();
    $conexion = $db->getConexion();
    $mensaje = "Los pnf de la UNEXCA han sido añadidas a la base de datos";

    try {
        $conexion->beginTransaction();

        $query = "INSERT INTO unexca.pnf (id_sede, cod_pnf, nombre_pnf, descripcion, duracion_pnf, unidad_total_creditos) 
        VALUES (:id_sede, :cod, :nom, :desc, :duracion, :unidades)";
        $stmt = $conexion->prepare($query);

        foreach ($data as $pnf) {
            $stmt->execute([
                ':id_sede' => $pnf['id_sede'], 
                ':cod' => $pnf['cod_pnf'],
                ':nom' => $pnf['nombre_pnf'], 
                ':desc' => $pnf['descripcion'],
                ':duracion' => $pnf['duracion_pnf'],
                ':unidades' => $pnf['unidad_total_creditos']
            ]);
        }

        $conexion->commit();
        echo $mensaje;
    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error al insertar datos: " . $e->getMessage();
    }
}
