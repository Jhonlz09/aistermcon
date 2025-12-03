<?php
session_start();
require_once "../utils/database/conexion.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Asegúrate de cargar PHPMailer correctamente

class ModeloPretrabajo
{
    static public function mdlListarPretrabajo($anio)
    {
        try {
            $consulta = "SELECT p.id, TO_CHAR(p.fecha_inspeccion, 'DD/MM/YYYY') as fecha, p.cliente, p.detalle,p.pdf_arr, p.img_arr, null AS acciones, TO_CHAR(p.fecha_inspeccion, 'YYYY-MM-DD') AS fecha_inspeccion
                    FROM tblpre_trabajo p
                    WHERE EXTRACT(YEAR FROM p.fecha_inspeccion) = :anio ORDER BY p.id DESC;";

            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
            // if ($estado !== 'null') {
            //     $l->bindParam(":estado", $estado, PDO::PARAM_INT);
            // }
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarPretrabajo($fecha, $cliente, $detalles, $pdf_arr, $img_arr)
    {
        try {

            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblpre_trabajo(fecha_inspeccion, cliente, detalle, pdf_arr, img_arr) VALUES (:fecha, :cliente, :detalles, :pdf_arr, :img_arr)");

            $a->bindParam(":fecha", $fecha);
            $a->bindParam(":cliente", $cliente);
            $a->bindParam(":detalles", $detalles);
            $a->bindParam(":pdf_arr", $pdf_arr);
            $a->bindParam(":img_arr", $img_arr);

            $a->execute();
            return [
                'status' => 'success',
                'm' => 'El pre trabajo se agregó correctamente.'
            ];
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return [
                    'status' => 'danger',
                    'm' => 'El pre trabajo ya existe para este cliente.'
                ];
            }
            return [
                'status' => 'danger',
                'm' => 'Error al agregar presupuesto: ' . $e->getMessage()
            ];
        }
    }

    static public function mdlEditarPretrabajo($id, $fecha, $cliente, $detalles, $pdf_arr, $img_arr)
    {
        try {
            $conexion = Conexion::ConexionDB();

            $campos = [
                "fecha_inspeccion = :fecha",
                "cliente = :cliente",
                "detalle = :detalles",
            ];

            // Solo agregar columnas de archivo si hay valor nuevo
            $binds = [
                ":fecha" => $fecha,
                ":detalles" => $detalles,
                ":cliente" => $cliente,
                ":id" => $id
            ];

            $archivos = [
                'pdf_arr'  => $pdf_arr,
                'img_arr'  => $img_arr
            ];

            foreach ($archivos as $col => $valor) {
                if (!is_null($valor)) {
                    // Para pdf_oc y img_oc → concatenar arrays existentes
                    if (in_array($col, ['img_arr', 'pdf_arr'])) {
                        $campos[] = "$col = COALESCE($col, '{}') || :$col";
                    } else {
                        $campos[] = "$col = :$col";
                    }
                    $binds[":$col"] = $valor;
                }
            }

            $sql = "UPDATE tblpre_trabajo SET " . implode(", ", $campos) . " WHERE id = :id";
            $stmt = $conexion->prepare($sql);

            foreach ($binds as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            // var_dump($stmt);
            $stmt->execute();

            return ['status' => 'success', 'm' => 'El pre trabajo se actualizó correctamente.'];
        } catch (PDOException $e) {
            return ['status' => 'danger', 'm' => 'Error al actualizar: ' . $e->getMessage()];
        }
    }

    public static function mdlEliminarPretrabajo($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblpre_trabajo SET anulado=true WHERE id=:id");
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

    // public static function mdlCambiarEstado($id, $estado)
    // {
    //     try {
    //         $con = Conexion::ConexionDB();

    //         // Cambiar estado del presupuesto
    //         $consulta = "UPDATE tblpresupuesto SET estado = :estado WHERE id = :id";
    //         $e = $con->prepare($consulta);
    //         $e->bindParam(":id", $id);
    //         $e->bindParam(":estado", $estado);
    //         $e->execute();

    //         // 2️⃣ Solo si está aprobado → cambiar estado del cliente a TRUE
    //         if ($estado === 'APROBADO') {

    //             // Obtener cliente asociado
    //             $q = $con->prepare("
    //             SELECT id_cliente, issend_email 
    //             FROM tblpresupuesto 
    //             WHERE id = :id
    //         ");
    //             $q->bindParam(":id", $id);
    //             $q->execute();
    //             $data = $q->fetch(PDO::FETCH_ASSOC);

    //             if ($data) {

    //                 $id_cliente = $data['id_cliente'];

    //                 // Cambiar estado del cliente a TRUE
    //                 $updateCliente = $con->prepare("
    //                 UPDATE tblclientes 
    //                 SET estado = TRUE 
    //                 WHERE id = :id
    //             ");
    //                 $updateCliente->bindParam(":id", $id_cliente);
    //                 $updateCliente->execute();

    //                 // Envío de correo (si no se envió antes)
    //                 if (!$data['issend_email']) {

    //                     // Obtener información para el correo
    //                     $l = $con->prepare("
    //                     SELECT 
    //                         p.descripcion, 
    //                         p.num_orden, 
    //                         TO_CHAR(p.fecha, 'DD/MM/YYYY') AS fecha, 
    //                         c.nombre AS cliente 
    //                     FROM tblpresupuesto p
    //                     JOIN tblclientes c ON p.id_cliente = c.id
    //                     WHERE p.id = :id
    //                 ");
    //                     $l->bindParam(":id", $id);
    //                     $l->execute();
    //                     $info = $l->fetch(PDO::FETCH_ASSOC);

    //                     if ($info) {
    //                         self::enviarCorreoEnSegundoPlano(
    //                             $info['descripcion'],
    //                             $info['num_orden'],
    //                             $info['fecha'],
    //                             $info['cliente']
    //                         );

    //                         $updateFlag = $con->prepare("
    //                         UPDATE tblpresupuesto 
    //                         SET issend_email = TRUE 
    //                         WHERE id = :id
    //                     ");
    //                         $updateFlag->bindParam(":id", $id);
    //                         $updateFlag->execute();
    //                     }
    //                 }
    //             }
    //         }

    //         return [
    //             'status' => 'success',
    //             'm' => 'Estado actualizado correctamente.'
    //         ];
    //     } catch (PDOException $ex) {
    //         return [
    //             'status' => 'error',
    //             'm' => 'Error al cambiar el estado: ' . $ex->getMessage()
    //         ];
    //     }
    // }

    static public function mdlObtenerTodosLosArchivos($id)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT unnest(pdf_arr || img_arr) AS 
            nombre_file FROM tblpre_trabajo WHERE id =:id");

            $l->bindParam(":id", $id, PDO::PARAM_INT);
            $l->execute();
            $files = $l->fetchAll(PDO::FETCH_ASSOC);

            return $files ?: []; // Retorna un arreglo vacío si no hay resultados
        } catch (PDOException $e) {
            // Retornar un mensaje de error descriptivo
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlObtenerFilesPretrabajo($id)
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

    public static function mdlObtenerIdPretrabajo($nombre)
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

    public static function mdlIsPdfPretrabajo($id_presupuesto)
    {
        $e = Conexion::ConexionDB()->prepare("SELECT o.ruta FROM tblpresupuesto  o WHERE o.id = :id_presupuesto ");
        $e->bindParam(':id_presupuesto ', $id_presupuesto, PDO::PARAM_INT);

        $e->execute();
        $r = $e->fetch(PDO::FETCH_ASSOC);
        return $r['ruta'];
    }

    static public function mdlEliminarFilePretrabajo($id, $ruta, $ext)
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

    static public function mdlEliminarArchivo($id, $ruta, $ext)
    {
        try {
            $db = Conexion::ConexionDB();

            if ($ext === 'pdf') {
                $columna = 'pdf_arr';
            } else {
                $columna = 'img_arr';
            }

            $sql = "UPDATE tblpre_trabajo
                    SET {$columna} = array_remove({$columna}, :ruta)
                    WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':ruta', $ruta, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // 🧹 Eliminar archivo físico
            $baseDir = '/var/www/pre_trabajo/';
            $filePath = $baseDir . $ruta;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return true;
        } catch (PDOException $e) {
            return "Error en la eliminación: " . $e->getMessage();
        }
    }
}
