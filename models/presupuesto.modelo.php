<?php
session_start();
require_once "../utils/database/conexion.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Asegúrate de cargar PHPMailer correctamente

class ModeloPresupuesto
{
    static public function mdlListarPresupuesto($anio, $estado)
    {
        try {
            $consulta = "SELECT p.id, p.num_orden, 
                        c.nombre AS cliente, 
                        p.descripcion,  
                        p.precio_iva,
                        p.precio_total,
                        p.estado,
                        p.pdf_ord, 
                        p.xls_ord,
                        p.pdf_pre, 
                        p.xls_pre,
                        TO_CHAR(p.fecha, 'DD/MM/YYYY') AS fecha, 
                        p.nota, 
                        p.id_cliente, 
                        '' AS acciones
                    FROM tblpresupuesto p
                    JOIN tblclientes c ON p.id_cliente = c.id
                    WHERE p.anulado = false
                    AND EXTRACT(YEAR FROM p.fecha) = :anio ";
            if ($estado !== 'null') {
                $consulta .= "AND p.estado = :estado ";
            }
            $consulta .= "ORDER BY p.id;";

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

    static public function mdlAgregarPresupuesto($nombre, $id_cliente, $cliente, $presupuesto, $ruta, $fecha)
    {
        try {
            // Conexión a la base de datos e inserción
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblpresupuesto(descripcion, id_cliente, nombre, pdf_pre, fecha, fecha) VALUES (:des, :id_cliente, :presupuesto, :ruta, :fecha)");
            $a->bindParam(":des", $nombre, PDO::PARAM_STR);
            $a->bindParam(":presupuesto", $presupuesto, PDO::PARAM_STR);
            $a->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $a->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $a->bindParam(":ruta", $ruta, PDO::PARAM_STR);
            $a->execute();
            // Ejecutar el envío de correo en segundo plano
            self::enviarCorreoEnSegundoPlano($nombre, $presupuesto , $fecha, $cliente);
            // Respuesta al usuario
            return array(
                'status' => 'success',
                'm' => 'La presupuesto  de trabajo se agregó correctamente. Se esta procesando el envío del correo electrónico'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'La presupuesto  de trabajo ya existe para el cliente seleccionado.'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar la presupuesto  de trabajo: ' . $e->getMessage()
                );
            }
        }
    }



    static private function enviarCorreoEnSegundoPlano($descrip, $presupuesto , $fecha, $cliente)
    {
        $scriptPath = escapeshellarg(__DIR__ . DIRECTORY_SEPARATOR . 'send_email.php');
        $usuario = $_SESSION['s_usuario']->nombres;
        // $id_cliente = escapeshellarg($id_cliente);
        $descrip = escapeshellarg($descrip);
        $presupuesto  = escapeshellarg($presupuesto );
        $cliente = escapeshellarg($cliente);
        $fecha = escapeshellarg($fecha);

        // Comando para ejecutar en segundo plano
        $command = "php $scriptPath $descrip $presupuesto  $fecha $cliente $usuario > /dev/null 2>&1 &";
        exec($command);
    }


    // static private function enviarCorreoEnSegundoPlano($id_cliente, $nombre, $presupuesto , $ruta, $fecha)
    // {
    //     // Ruta al script que enviará el correo
    //     // $scriptPath = escapeshellarg(__DIR__ . '/send_email.php');
    //     $scriptPath = escapeshellarg(str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/send_email.php'));

    //     // var_dump($scriptPath); // Esto te ayudará a ver la ruta completa

    //     $id_cliente = escapeshellarg($id_cliente);
    //     $nombre = escapeshellarg($nombre);
    //     $presupuesto  = escapeshellarg($presupuesto );
    //     $ruta = escapeshellarg($ruta);
    //     $fecha = escapeshellarg($fecha);

    //     $command = "start /B php $scriptPath $id_cliente $nombre $presupuesto  $ruta $fecha > NUL 2>&1";
    //     file_put_contents(__DIR__ . '/log.txt', $command . PHP_EOL, FILE_APPEND);
    //     // Ejecutar el comando en segundo plano
    //     exec($command); 
    // }




    static public function mdlEditarPresupuesto($id, $nombre, $id_cliente, $presupuesto , $ruta)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblpresupuesto SET descripcion=:des, id_cliente=:id_cliente, nombre=:presupuesto, ruta=:ruta WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":des", $nombre, PDO::PARAM_STR);
            $u->bindParam(":presupuesto ", $presupuesto , PDO::PARAM_STR);
            $u->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $u->bindParam(":ruta", $ruta, PDO::PARAM_STR);
            $u->execute();
            return array(
                'status' => 'success',
                'm' => 'La presupuesto  de trabajo se editó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'La presupuesto de trabajo ya existe para el cliente seleccionado.'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar la presupuesto  de trabajo: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlEliminarPresupuesto($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblpresupuesto  SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó la presupuesto  de trbajo correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar la presupuesto  de trabajo: ' . $e->getMessage()
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
                '1' => 'fecha_ini',
                '2' => 'fecha_fin',
                '3' => 'fecha_fac',
                '4' => 'fecha_gar'
            );

            $consulta = "UPDATE tblpresupuesto  SET estado_obra=:estado, nota=:nota ";
            $consulta .= "," . $fechas[$estado] . " =:fecha ";
            $consulta .= "WHERE id=:id";


            $e = Conexion::ConexionDB()->prepare($consulta);
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            if ($nota === '') {
                $e->bindValue(":nota", null, PDO::PARAM_NULL);
            } else {
                $e->bindParam(":nota", $nota, PDO::PARAM_STR);
            }
            $e->bindParam(":estado", $estado, PDO::PARAM_INT);
            $e->bindParam(":fecha", $fechaHora, PDO::PARAM_STR);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se actualizó el estado de la presupuesto  de trabajo corectamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo actualizar el estado de la presupuesto  de trabajo: ' . $e->getMessage()
            );
        }
    }


    public static function mdlBuscarPresupuestoes()
    {
        try {

            $e = Conexion::ConexionDB()->prepare("SELECT o.id, 
            o.nombre || ' ' || c.nombre AS descripcion,e.estado_obra AS estado_obra, 
            EXTRACT(YEAR FROM o.fecha) AS anio 
                FROM tblpresupuesto  o 
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

    public static function mdlObtenerIdPresupuesto($nombre)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT COALESCE((SELECT id_cliente 
                FROM tblpresupuesto  
                    WHERE nombre = :nombre 
                AND (EXTRACT(YEAR FROM fecha) = :anioActual OR estado_obra IN (0, 1)) 
                AND estado = true), 0) AS id_cliente;");
            $e->bindParam(":nombre", $nombre, PDO::PARAM_INT);
            $e->bindParam(':anioActual', $anio_actual, PDO::PARAM_INT);
            $e->execute();
            return $e->fetchAll();
        } catch (PDOException $e) {
            if ($e->getCode() == '21000') {
                return array(
                    'status' => 'warning',
                    'm' => 'Existen varios clientes asociados al numero de presupuesto , por favor seleccione manualmente el cliente'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo consultar el nro. presupuesto : ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlIsPdfPresupuesto($id_presupuesto )
    {
        $e = Conexion::ConexionDB()->prepare("SELECT o.ruta FROM tblpresupuesto  o WHERE o.id = :id_presupuesto ");
        $e->bindParam(':id_presupuesto ', $id_presupuesto , PDO::PARAM_INT);

        $e->execute();
        $r = $e->fetch(PDO::FETCH_ASSOC);
        return $r['ruta'];
    }
}
