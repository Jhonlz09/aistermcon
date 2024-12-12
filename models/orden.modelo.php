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
            $consulta = "SELECT o.id, o.nombre, 
                        c.nombre AS cliente, 
                        o.descripcion,  
                        o.estado_obra,
                        '' AS acciones, 
                        o.id_cliente, 
                        o.ruta, 
                        TO_CHAR(o.fecha, 'DD/MM/YYYY') AS fecha, 
                        COALESCE(TO_CHAR(o.fecha_ini, 'DD/MM/YYYY'), '') AS fecha_ini, 
                        COALESCE(TO_CHAR(o.fecha_fin, 'DD/MM/YYYY'), '') AS fecha_fin, 
                        COALESCE(TO_CHAR(o.fecha_fac, 'DD/MM/YYYY'), '') AS fecha_fac,
                        COALESCE(TO_CHAR(o.fecha_gar, 'DD/MM/YYYY'), '') AS fecha_gar,
                        o.nota
                    FROM tblorden o
                    JOIN tblestado_obra eo ON o.estado_obra = eo.id
                    JOIN tblclientes c ON o.id_cliente = c.id
                    WHERE o.estado = true 
                    AND EXTRACT(YEAR FROM o.fecha) = :anio ";
            if ($estado !== 'null') {
                $consulta .= "AND o.estado_obra = :estado ";
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

    // static public function mdlAgregarOrden($nombre, $id_cliente, $orden, $ruta, $fecha)
    // {
    //     try {
    //         // Conexión a la base de datos e inserción
    //         $conexion = Conexion::ConexionDB();
    //         $a = $conexion->prepare("INSERT INTO tblorden(descripcion, id_cliente, nombre, ruta, fecha, estado_obra) VALUES (:des, :id_cliente, :orden, :ruta, :fecha, 0)");
    //         $a->bindParam(":des", $nombre, PDO::PARAM_STR);
    //         $a->bindParam(":orden", $orden, PDO::PARAM_STR);
    //         $a->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
    //         $a->bindParam(":fecha", $fecha, PDO::PARAM_STR);
    //         $a->bindParam(":ruta", $ruta, PDO::PARAM_STR);
    //         $a->execute();

    //         // Si la inserción es exitosa, enviamos el correo
    //         $correoEnviado = self::enviarCorreo($id_cliente, $nombre, $orden, $ruta, $fecha);

    //         // Devuelve la respuesta dependiendo del resultado del envío del correo
    //         if ($correoEnviado) {
    //             return array(
    //                 'status' => 'success',
    //                 'm' => 'La orden de trabajo se agregó y se envió el correo correctamente.'
    //             );
    //         } else {
    //             return array(
    //                 'status' => 'warning',
    //                 'm' => 'La orden de trabajo se agregó, pero no se pudo enviar el correo.'
    //             );
    //         }
    //     } catch (PDOException $e) {
    //         if ($e->getCode() == '23505') {
    //             return array(
    //                 'status' => 'danger',
    //                 'm' => 'La orden de trabajo ya existe para el cliente seleccionado.'
    //             );
    //         } else {
    //             return array(
    //                 'status' => 'danger',
    //                 'm' => 'No se pudo agregar la orden de trabajo: ' . $e->getMessage()
    //             );
    //         }
    //     }
    // }

    // static private function enviarCorreo($id_cliente, $nombre, $orden, $ruta, $fecha)
    // {
    //     $mail = new PHPMailer(true);

    //     try {
    //         // Configuración del servidor SMTP
    //         $mail->isSMTP();
    //         $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
    //         $mail->SMTPAuth = true;
    //         $mail->Username = 'jdleon5@tes.edu.ec'; // Tu correo Gmail
    //         $mail->Password = 'meteoro123'; // Contraseña de Gmail (o contraseña de aplicación)
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //         $mail->Port = 587;

    //         // Configuración del remitente y destinatario
    //         $mail->setFrom('jdleon5@tes.edu.ec', 'Jhon Leon');
    //         $mail->addAddress('jhonleon2001@gmail.com', 'Jhon Leon'); // Cambia por el correo del cliente o destinatario

    //         // Contenido del correo
    //         $mail->isHTML(true);
    //         $mail->Subject = 'Nueva orden de trabajo abierta';
    //         $mail->Body = "
    //         <h2>Detalles de la nueva orden:</h2>
    //         <p><strong>Cliente ID:</strong> {$id_cliente}</p>
    //         <p><strong>Nombre:</strong> {$nombre}</p>
    //         <p><strong>Orden:</strong> {$orden}</p>
    //         <p><strong>Ruta:</strong> {$ruta}</p>
    //         <p><strong>Fecha:</strong> {$fecha}</p>";

    //         // Enviar el correo
    //         $mail->send();
    //         return true;
    //     } catch (Exception $e) {
    //         // Manejo de errores al enviar el correo
    //         error_log("Error al enviar el correo: {$mail->ErrorInfo}");
    //         return false;
    //     }
    // }


    // static public function mdlAgregarOrden($nombre, $id_cliente, $orden, $ruta, $fecha)
    // {
    //     try {
    //         // Conexión a la base de datos e inserción
    //         $conexion = Conexion::ConexionDB();
    //         $a = $conexion->prepare("INSERT INTO tblorden(descripcion, id_cliente, nombre, ruta, fecha, estado_obra) VALUES (:des, :id_cliente, :orden, :ruta, :fecha, 0)");
    //         $a->bindParam(":des", $nombre, PDO::PARAM_STR);
    //         $a->bindParam(":orden", $orden, PDO::PARAM_STR);
    //         $a->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
    //         $a->bindParam(":fecha", $fecha, PDO::PARAM_STR);
    //         $a->bindParam(":ruta", $ruta, PDO::PARAM_STR);
    //         $a->execute();

    //         // Si la inserción es exitosa, enviamos el correo en segundo plano
    //         self::enviarCorreoEnSegundoPlano($id_cliente, $nombre, $orden, $ruta, $fecha);

    //         return array(
    //             'status' => 'success',
    //             'm' => 'La orden de trabajo se agregó correctamente, el correo se está enviando en segundo plano.'
    //         );
    //     } catch (PDOException $e) {
    //         if ($e->getCode() == '23505') {
    //             return array(
    //                 'status' => 'danger',
    //                 'm' => 'La orden de trabajo ya existe para el cliente seleccionado.'
    //             );
    //         } else {
    //             return array(
    //                 'status' => 'danger',
    //                 'm' => 'No se pudo agregar la orden de trabajo: ' . $e->getMessage()
    //             );
    //         }
    //     }
    // }

    // static private function enviarCorreoEnSegundoPlano($id_cliente, $nombre, $orden, $ruta, $fecha)
    // {
    //     // Asegúrate de que el correo no bloquee el flujo principal
    //     ignore_user_abort(true);  // Permite que el script siga ejecutándose aunque el usuario se desconecte

    //     // Enviar el correo
    //     self::enviarCorreo($id_cliente, $nombre, $orden, $ruta, $fecha);

    //     // Si usas FastCGI, puedes terminar la solicitud inmediatamente
    //     if (function_exists('fastcgi_finish_request')) {
    //         fastcgi_finish_request();
    //     }
    // }

    // static private function enviarCorreo($id_cliente, $nombre, $orden, $ruta, $fecha)
    // {
    //     $mail = new PHPMailer(true);

    //     try {
    //         // Configuración del servidor SMTP
    //         $mail->isSMTP();
    //         $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
    //         $mail->SMTPAuth = true;
    //         $mail->Username = 'jdleon5@tes.edu.ec'; // Tu correo Gmail
    //         $mail->Password = 'meteoro123'; // Contraseña de Gmail (o contraseña de aplicación)
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //         $mail->Port = 587;

    //         // Configuración del remitente y destinatario
    //         $mail->setFrom('jdleon5@tes.edu.ec', 'Jhon Leon');
    //         $mail->addAddress('jhonleon2001@gmail.com', 'Jhon Leon'); // Cambia por el correo del cliente o destinatario

    //         // Contenido del correo
    //         $mail->isHTML(true);
    //         $mail->Subject = 'Nueva orden de trabajo abierta';
    //         $mail->Body = "
    //     <h2>Detalles de la nueva orden:</h2>
    //     <p><strong>Cliente ID:</strong> {$id_cliente}</p>
    //     <p><strong>Nombre:</strong> {$nombre}</p>
    //     <p><strong>Orden:</strong> {$orden}</p>
    //     <p><strong>Ruta:</strong> {$ruta}</p>
    //     <p><strong>Fecha:</strong> {$fecha}</p>";

    //         // Enviar el correo
    //         $mail->send();
    //         return true;
    //     } catch (Exception $e) {
    //         // Manejo de errores al enviar el correo
    //         error_log("Error al enviar el correo: {$mail->ErrorInfo}");
    //         return false;
    //     }
    // }


    static public function mdlAgregarOrden($nombre, $id_cliente, $cliente, $orden, $ruta, $fecha)
    {
        try {
            // Conexión a la base de datos e inserción
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblorden(descripcion, id_cliente, nombre, ruta, fecha, estado_obra) VALUES (:des, :id_cliente, :orden, :ruta, :fecha, 0)");
            $a->bindParam(":des", $nombre, PDO::PARAM_STR);
            $a->bindParam(":orden", $orden, PDO::PARAM_STR);
            $a->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $a->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $a->bindParam(":ruta", $ruta, PDO::PARAM_STR);
            $a->execute();
            // Ejecutar el envío de correo en segundo plano
            self::enviarCorreoEnSegundoPlano($nombre, $orden, $fecha, $cliente);
            // Respuesta al usuario
            return array(
                'status' => 'success',
                'm' => 'La orden de trabajo se agregó correctamente.'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'La orden de trabajo ya existe para el cliente seleccionado. Se esta procesando el envio de correo electronico'
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
        $command = "php $scriptPath $descrip $orden $fecha $cliente $usuario";

        // Ejecutar en segundo plano usando popen
        pclose(popen("start /B " . $command, "r"));
    }


    // static private function enviarCorreoEnSegundoPlano($id_cliente, $nombre, $orden, $ruta, $fecha)
    // {
    //     // Ruta al script que enviará el correo
    //     // $scriptPath = escapeshellarg(__DIR__ . '/send_email.php');
    //     $scriptPath = escapeshellarg(str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/send_email.php'));

    //     // var_dump($scriptPath); // Esto te ayudará a ver la ruta completa

    //     $id_cliente = escapeshellarg($id_cliente);
    //     $nombre = escapeshellarg($nombre);
    //     $orden = escapeshellarg($orden);
    //     $ruta = escapeshellarg($ruta);
    //     $fecha = escapeshellarg($fecha);

    //     $command = "start /B php $scriptPath $id_cliente $nombre $orden $ruta $fecha > NUL 2>&1";
    //     file_put_contents(__DIR__ . '/log.txt', $command . PHP_EOL, FILE_APPEND);
    //     // Ejecutar el comando en segundo plano
    //     exec($command); 
    // }




    static public function mdlEditarOrden($id, $nombre, $id_cliente, $orden, $ruta)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblorden SET descripcion=:des,id_cliente=:id_cliente, nombre=:orden, ruta=:ruta WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":des", $nombre, PDO::PARAM_STR);
            $u->bindParam(":orden", $orden, PDO::PARAM_STR);
            $u->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $u->bindParam(":ruta", $ruta, PDO::PARAM_STR);
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
                '1' => 'fecha_ini',
                '2' => 'fecha_fin',
                '3' => 'fecha_fac',
                '4' => 'fecha_gar'
            );

            $consulta = "UPDATE tblorden SET estado_obra=:estado, nota=:nota ";
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
                'm' => 'Se actualizó el estado de la orden de trabajo corectamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo actualizar el estado de la orden de trabajo: ' . $e->getMessage()
            );
        }
    }

    public static function mdlObtenerIdOrden($nombre)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT COALESCE((SELECT id_cliente 
                FROM tblorden 
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
        $e = Conexion::ConexionDB()->prepare("SELECT o.ruta FROM tblorden o WHERE o.id = :id_orden");
        $e->bindParam(':id_orden', $id_orden, PDO::PARAM_INT);

        $e->execute();
        $r = $e->fetch(PDO::FETCH_ASSOC);
        return $r['ruta'];
    }
}
