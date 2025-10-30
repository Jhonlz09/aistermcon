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
                        p.nota, p.id_cliente, null AS acciones, TO_CHAR(o.fecha, 'DD/MM/YYYY') as fecha_ord, TO_CHAR(o.fecha_ope, 'DD/MM/YYYY') as fecha_ope, TO_CHAR(o.fecha_fin, 'DD/MM/YYYY') as fecha_fin, TO_CHAR(o.fecha_fac, 'DD/MM/YYYY') as fecha_fac, o.nota as nota_ord
                    FROM tblpresupuesto p
                    JOIN tblclientes c ON p.id_cliente = c.id
                    LEFT JOIN tblorden o ON o.id = p.id
                    WHERE p.anulado = false
                    AND EXTRACT(YEAR FROM p.fecha) = :anio ";
            if ($estado !== 'null') {
                $consulta .= "AND p.estado = :estado ";
            }
            $consulta .= "ORDER BY p.id DESC;";

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
            $a = $conexion->prepare("INSERT INTO tblpresupuesto(num_orden, descripcion, id_cliente, precio_iva, precio_total,pdf_pre, pdf_ord, xls_pre, xls_ord, pdf_ae, doc_ae, pdf_oc, img_oc, fecha, nota) VALUES (:num_orden, :descripcion, :id_cliente, :precio_iva, :precio_total,:pdf_pre, :pdf_ord, :xls_pre, :xls_ord, :pdf_ae, :doc_ae, :pdf_oc, :img_oc, :fecha, :nota)"
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
        // $fecha = escapeshellarg($fecha);

        // Comando para ejecutar en segundo plano
        $command = "php $scriptPath $descrip $presupuesto  $fecha $cliente $usuario > /dev/null 2>&1 &";
        exec($command);
    }

    // static public function mdlEditarPresupuesto($id, $nombre, $id_cliente, $presupuesto, $ruta)
    // {
    //     try {
    //         $u = Conexion::ConexionDB()->prepare("UPDATE tblpresupuesto SET descripcion=:des, id_cliente=:id_cliente, nombre=:presupuesto, ruta=:ruta WHERE id=:id");
    //         $u->bindParam(":id", $id, PDO::PARAM_INT);
    //         $u->bindParam(":des", $nombre, PDO::PARAM_STR);
    //         $u->bindParam(":presupuesto ", $presupuesto, PDO::PARAM_STR);
    //         $u->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
    //         $u->bindParam(":ruta", $ruta, PDO::PARAM_STR);
    //         $u->execute();
    //         return array(
    //             'status' => 'success',
    //             'm' => 'La presupuesto se editó correctamente'
    //         );
    //     } catch (PDOException $e) {
    //         if ($e->getCode() == '23505') {
    //             return array(
    //                 'status' => 'danger',
    //                 'm' => 'La presupuesto ya existe para el cliente seleccionado.'
    //             );
    //         } else {
    //             return array(
    //                 'status' => 'danger',
    //                 'm' => 'No se pudo editar la presupuesto ' . $e->getMessage()
    //             );
    //         }
    //     }
    // }

    // static public function mdlEditarPresupuesto(
    //     $id,
    //     $descrip,
    //     $id_cliente,
    //     $cliente,
    //     $presupuesto,
    //     $pdf_pre,
    //     $pdf_ord,
    //     $xls_pre,
    //     $xls_ord,
    //     $doc_ae,
    //     $pdf_ae,
    //     $pdf_oc_arr,
    //     $img_oc_arr,
    //     $fecha,
    //     $precio_iva,
    //     $precio_total,
    //     $nota
    // ) {
    //     try {
    //         $conexion = Conexion::ConexionDB();

    //         $sql = "UPDATE tblpresupuesto SET 
    //         num_orden = :num_orden,
    //         descripcion = :descripcion,
    //         id_cliente = :id_cliente,
    //         precio_iva = :precio_iva,
    //         precio_total = :precio_total,
    //         fecha = :fecha,
    //         nota = :nota";

    //         // 🔸 Solo actualizar campos de archivos si llegaron nuevos
    //         if ($pdf_pre) $sql .= ", pdf_pre = :pdf_pre";
    //         if ($pdf_ord) $sql .= ", pdf_ord = :pdf_ord";
    //         if ($xls_pre) $sql .= ", xls_pre = :xls_pre";
    //         if ($xls_ord) $sql .= ", xls_ord = :xls_ord";
    //         if ($doc_ae)  $sql .= ", doc_ae = :doc_ae";
    //         if ($pdf_ae)  $sql .= ", pdf_ae = :pdf_ae";

    //         $sql .= " WHERE id = :id";

    //         $stmt = $conexion->prepare($sql);
    //         $stmt->bindParam(":num_orden", $presupuesto);
    //         $stmt->bindParam(":descripcion", $descrip);
    //         $stmt->bindParam(":id_cliente", $id_cliente);
    //         $stmt->bindParam(":precio_iva", $precio_iva);
    //         $stmt->bindParam(":precio_total", $precio_total);
    //         $stmt->bindParam(":fecha", $fecha);
    //         $stmt->bindParam(":nota", $nota);
    //         $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    //         if ($pdf_pre) $stmt->bindParam(":pdf_pre", $pdf_pre);
    //         if ($pdf_ord) $stmt->bindParam(":pdf_ord", $pdf_ord);
    //         if ($xls_pre) $stmt->bindParam(":xls_pre", $xls_pre);
    //         if ($xls_ord) $stmt->bindParam(":xls_ord", $xls_ord);
    //         if ($doc_ae)  $stmt->bindParam(":doc_ae", $doc_ae);
    //         if ($pdf_ae)  $stmt->bindParam(":pdf_ae", $pdf_ae);

    //         $stmt->execute();

    //         // 📌 Actualizar arrays de pdf_oc y img_oc con array_append
    //         if (!empty($pdf_oc_arr)) {
    //             $arraySQL = "UPDATE tblpresupuesto SET pdf_oc = COALESCE(pdf_oc, '{}')";
    //             foreach ($pdf_oc_arr as $ruta) {
    //                 $arraySQL .= " || array['$ruta']";
    //             }
    //             $arraySQL .= " WHERE id = $id";
    //             $conexion->exec($arraySQL);
    //         }

    //         if (!empty($img_oc_arr)) {
    //             $arraySQL = "UPDATE tblpresupuesto SET img_oc = COALESCE(img_oc, '{}')";
    //             foreach ($img_oc_arr as $ruta) {
    //                 $arraySQL .= " || array['$ruta']";
    //             }
    //             $arraySQL .= " WHERE id = $id";
    //             $conexion->exec($arraySQL);
    //         }

    //         return ['status' => 'success', 'm' => 'Presupuesto actualizado correctamente.'];
    //     } catch (PDOException $e) {
    //         return ['status' => 'danger', 'm' => 'Error al editar: ' . $e->getMessage()];
    //     }
    // }


    static public function mdlEditarPresupuesto($id, $descrip, $id_cliente, $cliente, $presupuesto, $pdf_pre, $pdf_ord, $xls_pre, $xls_ord, $doc_ae, $pdf_ae, $pdf_oc, $img_oc, $fecha, $precio_iva, $precio_total, $nota)
   
    {

        try {
            //  var_dump( $id, $descrip, $id_cliente, $cliente, $presupuesto, $pdf_pre, $pdf_ord, $xls_pre, $xls_ord, $doc_ae, $pdf_ae, $pdf_oc, $img_oc, $fecha, $precio_iva, $precio_total, $nota);
            $conexion = Conexion::ConexionDB();

            $campos = [
                "num_orden = :num_orden",
                "descripcion = :descripcion",
                "id_cliente = :id_cliente",
                "precio_iva = :precio_iva",
                "precio_total = :precio_total",
                "fecha = :fecha",
                "nota = :nota"
            ];

            // Solo agregar columnas de archivo si hay valor nuevo
            $binds = [
                ":num_orden" => $presupuesto,
                ":descripcion" => $descrip,
                ":id_cliente" => $id_cliente,
                ":precio_iva" => $precio_iva,
                ":precio_total" => $precio_total,
                ":fecha" => $fecha,
                ":nota" => $nota,
                ":id" => $id
            ];

            $archivos = [
                'pdf_pre' => $pdf_pre,
                'pdf_ord' => $pdf_ord,
                'xls_pre' => $xls_pre,
                'xls_ord' => $xls_ord,
                'doc_ae'  => $doc_ae,
                'pdf_ae'  => $pdf_ae,
                'pdf_oc'  => $pdf_oc,
                'img_oc'  => $img_oc
            ];

            foreach ($archivos as $col => $valor) {
                if (!is_null($valor)) {
                    // Para pdf_oc y img_oc → concatenar arrays existentes
                    if (in_array($col, ['pdf_oc', 'img_oc'])) {
                        $campos[] = "$col = COALESCE($col, '{}') || :$col";
                    } else {
                        $campos[] = "$col = :$col";
                    }
                    $binds[":$col"] = $valor;
                }
            }

            $sql = "UPDATE tblpresupuesto SET " . implode(", ", $campos) . " WHERE id = :id";
            $stmt = $conexion->prepare($sql);

            foreach ($binds as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            // var_dump($stmt);
            $stmt->execute();

            return ['status' => 'success', 'm' => 'El presupuesto se actualizó correctamente.'];
        } catch (PDOException $e) {
            return ['status' => 'danger', 'm' => 'Error al actualizar: ' . $e->getMessage()];
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
        $con = Conexion::ConexionDB();

        // 1️⃣ Actualizamos el estado del presupuesto
        $consulta = "UPDATE tblpresupuesto SET estado = :estado WHERE id = :id";
        $e = $con->prepare($consulta);
        $e->bindParam(":id", $id, PDO::PARAM_INT);
        $e->bindParam(":estado", $estado, PDO::PARAM_STR);
        $e->execute();

        // 2️⃣ Si el estado es APROBADO, verificamos si ya se envió el correo
        if ($estado === 'APROBADO') {
            $verificar = $con->prepare("SELECT issend_email FROM tblpresupuesto WHERE id = :id");
            $verificar->bindParam(":id", $id, PDO::PARAM_INT);
            $verificar->execute();
            $row = $verificar->fetch(PDO::FETCH_ASSOC);

            if ($row && !$row['issend_email']) {
                // 3️⃣ Obtenemos datos del presupuesto y cliente
                $l = $con->prepare("
                    SELECT 
                        p.descripcion, 
                        p.num_orden, 
                        TO_CHAR(p.fecha, 'DD/MM/YYYY') AS fecha, 
                        c.nombre AS cliente 
                    FROM tblpresupuesto p
                    JOIN tblclientes c ON p.id_cliente = c.id
                    WHERE p.id = :id
                ");
                $l->bindParam(":id", $id, PDO::PARAM_INT);
                $l->execute();
                $data = $l->fetch(PDO::FETCH_ASSOC);

                if ($data) {
                    $descripcion = $data['descripcion'];
                    $numOrden    = $data['num_orden'];
                    $fecha       = $data['fecha'];
                    $cliente     = $data['cliente'];

                    // 4️⃣ Enviamos el correo en segundo plano
                    self::enviarCorreoEnSegundoPlano($descripcion, $numOrden, $fecha, $cliente);

                    // 5️⃣ Marcamos como enviado
                    $updateFlag = $con->prepare("UPDATE tblpresupuesto SET issend_email = TRUE WHERE id = :id");
                    $updateFlag->bindParam(":id", $id, PDO::PARAM_INT);
                    $updateFlag->execute();
                }
            }
        }

        return array(
            'status' => 'success',
            'm' => 'Se editó el estado del presupuesto correctamente.'
        );

    } catch (PDOException $ex) {
        return array(
            'status' => 'error',
            'm' => 'Error al cambiar el estado: ' . $ex->getMessage()
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

    static public function mdlObtenerTodosLosArchivos($id)
    {
        try {
            $l = Conexion::ConexionDB()->prepare(" SELECT unnest(ARRAY_REMOVE(ARRAY[pdf_ord, xls_ord], NULL)) AS nombre_file, 'ord' AS tipo
            FROM tblpresupuesto
            WHERE id = :id

            UNION ALL

            SELECT unnest(ARRAY_REMOVE(ARRAY[pdf_pre, xls_pre], NULL)) AS nombre_file, 'ppt' AS tipo
            FROM tblpresupuesto
            WHERE id = :id

            UNION ALL

            SELECT unnest(ARRAY_REMOVE(ARRAY[pdf_ae, doc_ae], NULL)) AS nombre_file, 'ae' AS tipo
            FROM tblpresupuesto
            WHERE id = :id

            UNION ALL

            SELECT unnest(pdf_oc || img_oc) AS nombre_file, 'oc' AS tipo
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

    static public function mdlObtenerFilesActaEntrega($id)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT unnest(array[pdf_ae, doc_ae]) AS nombre_file
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

    static public function mdlObtenerFilesOrdenCompra($id)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT unnest(pdf_oc || img_oc) AS nombre_file
                    FROM tblpresupuesto WHERE id =:id");

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

    // static public function mdlEliminarFileOrden($id, $ruta, $ext, $carpeta)
    // {
    //     try {
    //         $l = Conexion::ConexionDB()->prepare("UPDATE tblpresupuesto SET " . ($ext === 'pdf' ? 'pdf_ord' : 'xls_ord') . " = NULL WHERE id = :id");

    //         $l->bindParam(":id", $id, PDO::PARAM_INT);
    //         if ($l->execute()) {
    //             $uploadDir = "var/www/". $carpeta. "/" ; // Directorio donde están las imágenes
    //             $filePath = $uploadDir . $ruta;

    //             if (file_exists($filePath)) {
    //                 unlink($filePath); // Eliminar archivo
    //             }
    //         };
    //     } catch (PDOException $e) {
    //         return "Error en la consulta: " . $e->getMessage();
    //     }
    // }

    static public function mdlEliminarArchivo($id, $ruta, $ext, $carpeta, $tipo)
    {
        try {
            $db = Conexion::ConexionDB();

            // 📌 Mapeo de columnas normales
            $columnas = [
                'ppt' => ['pdf' => 'pdf_pre', 'xls' => 'xls_pre', 'xlsx' => 'xls_pre'],
                'ord' => ['pdf' => 'pdf_ord', 'xls' => 'xls_ord', 'xlsx' => 'xls_ord'],
                'ae'  => ['pdf' => 'pdf_ae', 'doc' => 'doc_ae', 'docx' => 'doc_ae'],
            ];

            if ($tipo === 'oc') {
                if ($ext === 'pdf') {
                    $columna = 'pdf_oc';
                } else {
                    $columna = 'img_oc';
                }

                // Eliminar la ruta específica del array en PostgreSQL
                $sql = "UPDATE tblpresupuesto 
                    SET {$columna} = array_remove({$columna}, :ruta)
                    WHERE id = :id";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':ruta', $ruta, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                // 🧠 CASO NORMAL: columnas simples
                if (!isset($columnas[$tipo][$ext])) {
                    throw new Exception("Tipo de archivo no soportado: $tipo / $ext");
                }
                $columna = $columnas[$tipo][$ext];
                $sql = "UPDATE tblpresupuesto SET {$columna} = NULL WHERE id = :id";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }

            // 🧹 Eliminar archivo físico
            $baseDir = '/var/www/' . $carpeta . '/';
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
