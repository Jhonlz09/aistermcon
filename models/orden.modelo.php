<?php
session_start();
require_once "../utils/database/conexion.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Asegúrate de cargar PHPMailer correctamente

class ModeloOrden
{
    static public function mdlListarOrden($anio, $estado)
    {
        try {
            $consulta = "SELECT o.id, p.num_orden, 
                        c.nombre AS cliente, 
                        p.descripcion,  
                        o.estado,
                        '' AS acciones, 
                        p.id_cliente, 
                        p.pdf_ord, 
                        TO_CHAR(o.fecha, 'DD/MM/YYYY') AS fecha, 
                        COALESCE(TO_CHAR(o.fecha_ope, 'DD/MM/YYYY'), '') AS fecha_ope, 
                        COALESCE(TO_CHAR(o.fecha_fin, 'DD/MM/YYYY'), '') AS fecha_fin, 
                        COALESCE(TO_CHAR(o.fecha_fac, 'DD/MM/YYYY'), '') AS fecha_fac,
                        COALESCE(TO_CHAR(o.fecha_gar, 'DD/MM/YYYY'), '') AS fecha_gar,
                        o.nota
                    FROM tblorden o
                    JOIN tblpresupuesto p ON o.id = p.id
                    JOIN tblclientes c ON p.id_cliente = c.id
                    WHERE o.anulado = false 
                    AND EXTRACT(YEAR FROM o.fecha) = :anio ";
            if ($estado !== 'null') {
                $consulta .= "AND o.estado = :estado ";
            }
            $consulta .= "ORDER BY o.id;";
            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
            if ($estado !== 'null') {
                $l->bindParam(":estado", $estado, PDO::PARAM_INT);
            }
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarOrden($des, $id_cliente, $cliente, $orden, $pdf_orden, $fecha)
    {
        try {
            // Conexión a la base de datos e inserción
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblpresupuesto(descripcion, id_cliente, num_orden, pdf_ord, fecha, estado) VALUES (:des, :id_cliente, :orden, :pdf_orden, :fecha, 'APROBADO')");
            $a->bindParam(":des", $des, PDO::PARAM_STR);
            $a->bindParam(":orden", $orden, PDO::PARAM_STR);
            $a->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $a->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $a->bindParam(":pdf_orden", $pdf_orden, PDO::PARAM_STR);
            $a->execute();
            // Ejecutar el envío de correo en segundo plano
            self::enviarCorreoEnSegundoPlano($des, $orden, $fecha, $cliente);
            // Respuesta al usuario
            return array(
                'status' => 'success',
                'm' => 'La orden de trabajo se agregó correctamente. Se esta procesando el envío del correo electrónico'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'La orden de trabajo ya existe para el cliente seleccionado.'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar la orden de trabajo: ' . $e->getMessage()
                );
            }
        }
    }



    static private function enviarCorreoEnSegundoPlano($descrip, $orden, $fecha, $cliente)
    {
        $scriptPath = escapeshellarg(__DIR__ . DIRECTORY_SEPARATOR . 'send_email.php');
        $usuario = $_SESSION['s_usuario']->nombres;
        // $id_cliente = escapeshellarg($id_cliente);
        $descrip = escapeshellarg($descrip);
        $orden = escapeshellarg($orden);
        $cliente = escapeshellarg($cliente);
        $fecha = escapeshellarg($fecha);

        // Comando para ejecutar en segundo plano
        $command = "php $scriptPath $descrip $orden $fecha $cliente $usuario > /dev/null 2>&1 &";
        exec($command);

    }

    static public function mdlEditarOrden($id, $nombre, $id_cliente, $orden, $pdf_orden)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblorden SET descripcion=:des,id_cliente=:id_cliente, nombre=:orden, pdf_orden=:pdf_orden WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":des", $nombre, PDO::PARAM_STR);
            $u->bindParam(":orden", $orden, PDO::PARAM_STR);
            $u->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $u->bindParam(":pdf_orden", $pdf_orden, PDO::PARAM_STR);
            $u->execute();
            return array(
                'status' => 'success',
                'm' => 'La orden de trabajo se editó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'La orden de trabajo ya existe para el cliente seleccionado.'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar la orden de trabajo: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlEliminarOrden($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblorden SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó la orden de trbajo correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar la orden de trabajo: ' . $e->getMessage()
            );
        }
    }

    public static function mdlCambiarEstado($id, $estado, $fecha, $nota)
    {
        try {
            $hora = date('H:i:s');
            $fechaHora = $fecha . ' ' . $hora;

            $fechas = array(
                '0' => 'fecha',
                '1' => 'fecha_ope',
                '2' => 'fecha_fin',
                '3' => 'fecha_fac',
                '4' => 'fecha_gar'
            );

            $consulta = "UPDATE tblorden SET estado=:estado, nota=:nota ";
            $consulta .= "," . $fechas[$estado] . " =:fecha ";
            $consulta .= "WHERE id=:id";


            $e = Conexion::ConexionDB()->prepare($consulta);
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            if ($nota === '') {
                $e->bindValue(":nota", null, PDO::PARAM_NULL);
            } else {
                $e->bindParam(":nota", $nota, PDO::PARAM_STR);
            }
            $e->bindParam(":estado", $estado, PDO::PARAM_STR);
            $e->bindParam(":fecha", $fechaHora, PDO::PARAM_STR);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se actualizó el estado de la orden de trabajo corectamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo actualizar el estado de la orden de trabajo: ' . $e->getMessage()
            );
        }
    }


    public static function mdlBuscarOrdenes()
    {
        try {

            $e = Conexion::ConexionDB()->prepare("SELECT o.id, 
            o.nombre || ' ' || c.nombre AS descripcion,e.estado_obra AS estado_obra, 
            EXTRACT(YEAR FROM o.fecha) AS anio 
                FROM tblorden o 
                JOIN tblclientes c ON o.id_cliente = c.id
                JOIN tblestado_obra e ON o.estado_obra = e.id
                WHERE o.estado = true 
                AND o.estado_obra IN (0, 1, 4)
                ORDER BY o.id DESC;");
            $e->execute();
            return $e->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo obtener el producto: ' . $e->getMessage()
            );
        }
    }

    public static function mdlObtenerIdOrden($nombre)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT COALESCE((SELECT id_cliente 
                FROM tblpresupuesto
                    WHERE num_orden = :nombre 
                AND (EXTRACT(YEAR FROM fecha) = :anioActual)
                AND anulado = false), 0) AS id_cliente;");
            $e->bindParam(":nombre", $nombre, PDO::PARAM_INT);
            $e->bindParam(':anioActual', $anio_actual, PDO::PARAM_INT);
            $e->execute();
            return $e->fetchAll();
        } catch (PDOException $e) {
            if ($e->getCode() == '21000') {
                return array(
                    'status' => 'warning',
                    'm' => 'Existen varios clientes asociados al numero de orden, por favor seleccione manualmente el cliente'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo consultar el nro. orden: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlIsPdfOrden($id_orden)
    {
        $e = Conexion::ConexionDB()->prepare("SELECT o.pdf_orden FROM tblorden o WHERE o.id = :id_orden");
        $e->bindParam(':id_orden', $id_orden, PDO::PARAM_INT);

        $e->execute();
        $r = $e->fetch(PDO::FETCH_ASSOC);
        return $r['pdf_orden'];
    }
}
