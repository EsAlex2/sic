<?php
require_once __DIR__ . '/../init.php';

/**
 * Funcion para cargar los estatus del sistema en la base de datos
 */

function cargarEstatus()
{
    $data = [
        ['nombre_estatus' => 'Activo', 'descripcion' => 'Registro activo y vigente en el sistema'],
        ['nombre_estatus' => 'Inactivo', 'descripcion' => 'Registro deshabilitado temporalmente'],
        ['nombre_estatus' => 'Graduado', 'descripcion' => 'Estudiante que ha completado todos los requisitos de grado'],
        ['nombre_estatus' => 'Suspendido', 'descripcion' => 'Registro suspendido por decisión administrativa'],
        ['nombre_estatus' => 'Cursando', 'descripcion' => 'Asignatura en curso durante el período académico actual'],
        ['nombre_estatus' => 'Retirada', 'descripcion' => 'Asignatura retirada formalmente por el estudiante'],
        ['nombre_estatus' => 'Reprobada', 'descripcion' => 'Asignatura no aprobada por no cumplir los requisitos mínimos'],
        ['nombre_estatus' => 'Convalidada', 'descripcion' => 'Asignatura reconocida por equivalencia con otra institución'],
        ['nombre_estatus' => 'Pago Pendiente', 'descripcion' => 'Pago registrado pero aún no realizado por el estudiante'],
        ['nombre_estatus' => 'Pago Reportado', 'descripcion' => 'Pago realizado y reportado, pendiente de verificación'],
        ['nombre_estatus' => 'Pago Conciliado', 'descripcion' => 'Pago verificado y conciliado exitosamente'],
        ['nombre_estatus' => 'Pago Rechazado', 'descripcion' => 'Pago rechazado por datos inválidos o inconsistencias'],
        ['nombre_estatus' => 'Exonerado', 'descripcion' => 'Exoneración de pago otorgada por la institución'],
        ['nombre_estatus' => 'Reembolsado', 'descripcion' => 'Pago devuelto al estudiante por concepto de reembolso'],
        ['nombre_estatus' => 'Entregado', 'descripcion' => 'Documento o recurso entregado satisfactoriamente'],
        ['nombre_estatus' => 'Pendiente', 'descripcion' => 'Solicitud o trámite en espera de ser procesado'],
        ['nombre_estatus' => 'En Revisión', 'descripcion' => 'Solicitud o documento en proceso de revisión'],
        ['nombre_estatus' => 'Rechazado', 'descripcion' => 'Solicitud o documento rechazado tras revisión'],
        ['nombre_estatus' => 'Inscrito', 'descripcion' => 'Estudiante formalmente inscrito en el período académico'],
        ['nombre_estatus' => 'Retirado', 'descripcion' => 'Estudiante que se ha retirado del período académico'],
        ['nombre_estatus' => 'Aprobado', 'descripcion' => 'Asignatura o trámite aprobado satisfactoriamente'],
        ['nombre_estatus' => 'Reprobado', 'descripcion' => 'Estudiante que no aprobó la asignatura o evaluación'],
        ['nombre_estatus' => 'Convalidado', 'descripcion' => 'Trámite de convalidación completado exitosamente'],
        ['nombre_estatus' => 'Preinscrito', 'descripcion' => 'Estudiante en proceso de preinscripción, pendiente de formalización'],
        ['nombre_estatus' => 'Regular', 'descripcion' => 'Estudiante con condición regular dentro de la institución'],
        ['nombre_estatus' => 'Nuevo Ingreso', 'descripcion' => 'Estudiante que ingresa por primera vez a la institución'],
        ['nombre_estatus' => 'Egresado', 'descripcion' => 'Estudiante que ha culminado su programa de formación'],
        ['nombre_estatus' => 'Cursando', 'descripcion' => 'Estudiante que está cursando actualmente'],
        
    ];

    $db = new Conexion();
    $conexion = $db->getConexion();
    $mensaje = "Los estatus han sido añadidos a la base de datos";

    try {
        $conexion->beginTransaction();

        $query = "INSERT INTO unexca.estatus (nombre_estatus, descripcion) VALUES (:nombre, :descripcion)";
        $stmt = $conexion->prepare($query);

        foreach ($data as $estatus) {
            $stmt->execute([
                ':nombre' => $estatus['nombre_estatus'],
                ':descripcion' => $estatus['descripcion']
            ]);
        }

        $conexion->commit();
        echo $mensaje;
    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error al insertar datos: " . $e->getMessage();
    }
}
