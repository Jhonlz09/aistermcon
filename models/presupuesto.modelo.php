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
            $consulta = "SELECT p.id, p.num_orden,c.nombre AS cliente,p.descripcion,  
                        p.precio_iva,p.precio_total,p.estado,p.pdf_pre,p.xls_pre,p.pdf_ord,
                        p.xls_ord,p.pdf_ae,p.doc_ae, p.pdf_oc, p.img_oc, TO_CHAR(p.fecha, 'DD/MM/YYYY') AS fecha, 
                        p.nota, p.id_cliente, '' AS acciones
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

    static public function mdlAgregarPresupuesto($descrip, $id_cliente, $cliente, $presupuesto, $pdf_pre, $pdf_ord, $xls_pre, $xls_ord, $doc_ae, $pdf_ae, $pdf_oc, $img_oc, $fecha, $precio_iva, $precio_total, $nota)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare(
                "INSERT INTO tblpresupuesto(num_orden, descripcion, id_cliente, precio_iva, precio_total,pdf_pre, pdf_ord, xls_pre, xls_ord, pdf_ae, doc_ae, pdf_oc, img_oc, fecha, nota) VALUES (:num_orden, :descripcion, :id_cliente, :precio_iva, :precio_total,:pdf_pre, :pdf_ord, :xls_pre, :xls_ord, :pdf_ae, :doc_ae, :pdf_oc, :img_oc, :fecha, :nota)"
            );
            $a->bindParam(":num_orden", $presupuesto, PDO::PARAM_STR);
            $a->bindParam(":descripcion", $descrip, PDO::PARAM_STR);
            $a->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $a->bindParam(":precio_iva", $precio_iva, PDO::PARAM_STR);
            $a->bindParam(":precio_total", $precio_total, PDO::PARAM_STR);
            $a->bindParam(":pdf_pre", $pdf_pre, PDO::PARAM_STR);
            $a->bindParam(":pdf_ord", $pdf_ord, PDO::PARAM_STR);
            $a->bindParam(":xls_pre", $xls_pre, PDO::PARAM_STR);
            $a->bindParam(":xls_ord", $xls_ord, PDO::PARAM_STR);
            $a->bindParam(":doc_ae", $doc_ae, PDO::PARAM_STR);
            $a->bindParam(":pdf_ae", $pdf_ae, PDO::PARAM_STR);
            $a->bindParam(":pdf_oc", $pdf_oc, PDO::PARAM_STR);
            $a->bindParam(":img_oc", $img_oc, PDO::PARAM_STR);
            $a->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $a->bindParam(":nota", $nota, PDO::PARAM_STR);
            $a->execute();

            // Enviar correo en segundo plano
            // self::enviarCorreoEnSegundoPlano($descrip, $presupuesto, $fecha, $cliente);

            return array(
                'status' => 'success',
                'm' => 'El presupuesto se agregó correctamente.'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'La presupuesto ya existe para el cliente seleccionado.'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar la presupuesto ' . $e->getMessage()
                );
            }
        }
    }

    static private function enviarCorreoEnSegundoPlano($descrip, $presupuesto, $fecha, $cliente)
    {
        $scriptPath = escapeshellarg(__DIR__ . DIRECTORY_SEPARATOR . 'send_email.php');
        $usuario = $_SESSION['s_usuario']->nombres;
        // $id_cliente = escapeshellarg($id_cliente);
        $descrip = escapeshellarg($descrip);
        $presupuesto  = escapeshellarg($presupuesto);
        $cliente = escapeshellarg($cliente);
        $fecha = escapeshellarg($fecha);

        // Comando para ejecutar en segundo plano
        $command = "php $scriptPath $descrip $presupuesto  $fecha $cliente $usuario > /dev/null 2>&1 &";
        exec($command);
    }

    static public function mdlEditarPresupuesto($id, $nombre, $id_cliente, $presupuesto, $ruta)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblpresupuesto SET descripcion=:des, id_cliente=:id_cliente, nombre=:presupuesto, ruta=:ruta WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":des", $nombre, PDO::PARAM_STR);
            $u->bindParam(":presupuesto ", $presupuesto, PDO::PARAM_STR);
            $u->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $u->bindParam(":ruta", $ruta, PDO::PARAM_STR);
            $u->execute();
            return array(
                'status' => 'success',
                'm' => 'La presupuesto se editó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'La presupuesto ya existe para el cliente seleccionado.'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar la presupuesto ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlEliminarPresupuesto($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblpresupuesto SET anulado=true WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó la presupuesto correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar la presupuesto: ' . $e->getMessage()
            );
        }
    }

    public static function mdlCambiarEstado($id, $estado)
    {
        try {
            $consulta = "UPDATE tblpresupuesto SET estado=:estado WHERE id=:id";

            $e = Conexion::ConexionDB()->prepare($consulta);
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->bindParam(":estado", $estado, PDO::PARAM_STR);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se editó el estado del presupuesto corectamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar el estado del presupuesto: ' . $e->getMessage()
            );
        }
    }

    static public function mdlObtenerFilesOrden($id)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT unnest(array[pdf_ord, xls_ord]) AS nombre_file
                    FROM tblpresupuesto
                    WHERE id = :id");

            $l->bindParam(":id", $id, PDO::PARAM_INT);
            $l->execute();
            $files = $l->fetchAll(PDO::FETCH_ASSOC);

            return $files ?: []; // Retorna un arreglo vacío si no hay resultados
        } catch (PDOException $e) {
            // Retornar un mensaje de error descriptivo
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlObtenerFilesPresupuesto($id)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT unnest(array[pdf_pre, xls_pre]) AS nombre_file
                FROM tblpresupuesto
                WHERE id = :id");

            $l->bindParam(":id", $id, PDO::PARAM_INT);
            $l->execute();
            $files = $l->fetchAll(PDO::FETCH_ASSOC);

            return $files ?: []; // Retorna un arreglo vacío si no hay resultados
        } catch (PDOException $e) {
            // Retornar un mensaje de error descriptivo
            return "Error en la consulta: " . $e->getMessage();
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

    public static function mdlIsPdfPresupuesto($id_presupuesto)
    {
        $e = Conexion::ConexionDB()->prepare("SELECT o.ruta FROM tblpresupuesto  o WHERE o.id = :id_presupuesto ");
        $e->bindParam(':id_presupuesto ', $id_presupuesto, PDO::PARAM_INT);

        $e->execute();
        $r = $e->fetch(PDO::FETCH_ASSOC);
        return $r['ruta'];
    }

    static public function mdlEliminarFilePresupuesto($id, $ruta, $ext)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("UPDATE tblpresupuesto SET " . ($ext === 'pdf' ? 'pdf_pre' : 'xls_pre') . " = NULL
            WHERE id = :id");

            $l->bindParam(":id", $id, PDO::PARAM_INT);
            if ($l->execute()) {
                $uploadDir = "var/www/presupuestos/"; // Directorio donde están las imágenes
                $filePath = $uploadDir . $ruta;

                if (file_exists($filePath)) {
                    unlink($filePath); // Eliminar archivo
                }
            };
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlEliminarFileOrden($id, $ruta, $ext)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("UPDATE tblpresupuesto SET " . ($ext === 'pdf' ? 'pdf_ord' : 'xls_ord') . " = NULL WHERE id = :id");

            $l->bindParam(":id", $id, PDO::PARAM_INT);
            if ($l->execute()) {
                $uploadDir = "var/www/ordenes/"; // Directorio donde están las imágenes
                $filePath = $uploadDir . $ruta;

                if (file_exists($filePath)) {
                    unlink($filePath); // Eliminar archivo
                }
            };
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }
}
