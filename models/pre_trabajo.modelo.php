<?php
session_start();
require_once "../utils/database/conexion.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Asegúrate de cargar PHPMailer correctamente

class ModeloPretrabajo
{
    static public function mdlListarPretrabajo($anio, $estado)
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

    static public function mdlAgregarPretrabajo($fecha, $cliente, $detalles, $pdf_arr, $img_arr)
    {
        try {

            $conexion = Conexion::ConexionDB();

            // --------------------------------------------------
            // 1️⃣ INSERTAR CLIENTE MANUAL (si aplica)
            // --------------------------------------------------
            // if ($isManual) {
            //     // Normalizar nombre (quita espacios al inicio/fin)
            //     $cliente_manual_trim = trim($cliente_manual);

            //     try {
            //         // Iniciamos transacción para seguridad en concurrencia
            //         $conexion->beginTransaction();

            //         // 1) Intentar buscar previamente (búsqueda exacta; si quieres ignorar mayúsculas usar lower(nombre)=lower(:nm))
            //         $sel = $conexion->prepare("SELECT id FROM tblclientes WHERE nombre = :cliente_manual LIMIT 1");
            //         $sel->bindParam(":cliente_manual", $cliente_manual_trim, PDO::PARAM_STR);
            //         $sel->execute();
            //         $row = $sel->fetch(PDO::FETCH_ASSOC);
            //         if ($row && isset($row['id'])) {
            //             // Ya existe: tomar su id
            //             $id_cliente = $row['id'];
            //         } else {
            //             // No existe: insertar usando ON CONFLICT para evitar excepciones por condición de carrera
            //             $ins = $conexion->prepare("INSERT INTO tblclientes(nombre, id_tipo, estado, correo)
            //                     VALUES(:cliente_manual, 1, false, :correo) ON CONFLICT (nombre) DO NOTHING RETURNING id"
            //             );
            //             $correo_placeholder = 'POR AÑADIR';
            //             $ins->bindParam(":cliente_manual", $cliente_manual_trim, PDO::PARAM_STR);
            //             $ins->bindParam(":correo", $correo_placeholder, PDO::PARAM_STR);
            //             $ins->execute();

            //             $insertRow = $ins->fetch(PDO::FETCH_ASSOC);

            //             if ($insertRow && isset($insertRow['id'])) {
            //                 // Insert exitoso y RETURNING id
            //                 $id_cliente = $insertRow['id'];
            //             } else {
            //                 // Hubo conflicto (otro proceso insertó) — obtener id con SELECT final
            //                 $sel2 = $conexion->prepare("SELECT id FROM tblclientes WHERE nombre = :cliente_manual LIMIT 1");
            //                 $sel2->bindParam(":cliente_manual", $cliente_manual_trim, PDO::PARAM_STR);
            //                 $sel2->execute();
            //                 $row2 = $sel2->fetch(PDO::FETCH_ASSOC);

            //                 if ($row2 && isset($row2['id'])) {
            //                     $id_cliente = $row2['id'];
            //                 } else {
            //                     // Caso raro: no se puede obtener id
            //                     $conexion->rollBack();
            //                     return [
            //                         'status' => 'danger',
            //                         'm' => 'Error: no se pudo obtener el id del cliente después del intento de inserción.'
            //                     ];
            //                 }
            //             }
            //         }

            //         // Commit si todo OK
            //         $conexion->commit();
            //     } catch (PDOException $e) {
            //         // Rollback ante cualquier fallo
            //         if ($conexion->inTransaction()) {
            //             $conexion->rollBack();
            //         }
            //         return [
            //             'status' => 'danger',
            //             'm' => 'Error al procesar cliente manual: ' . $e->getMessage()
            //         ];
            //     }
            // }


            // --------------------------------------------------
            // 2️⃣ INSERTAR PRESUPUESTO
            // --------------------------------------------------
            $a = $conexion->prepare("
            INSERT INTO tblpresupuesto(
                num_orden, descripcion, id_cliente, precio_iva, precio_total,
                pdf_pre, pdf_ord, xls_pre, xls_ord, pdf_ae, doc_ae, pdf_oc,
                img_oc, fecha, nota
            )
            VALUES (
                :num_orden, :descripcion, :id_cliente, :precio_iva, :precio_total,
                :pdf_pre, :pdf_ord, :xls_pre, :xls_ord, :pdf_ae, :doc_ae, :pdf_oc,
                :img_oc, :fecha, :nota
            )
        ");

            $a->bindParam(":num_orden", $presupuesto);
            $a->bindParam(":descripcion", $descrip);
            $a->bindParam(":id_cliente", $id_cliente);
            $a->bindParam(":precio_iva", $precio_iva);
            $a->bindParam(":precio_total", $precio_total);
            $a->bindParam(":pdf_pre", $pdf_pre);
            $a->bindParam(":pdf_ord", $pdf_ord);
            $a->bindParam(":xls_pre", $xls_pre);
            $a->bindParam(":xls_ord", $xls_ord);
            $a->bindParam(":pdf_ae", $pdf_ae);
            $a->bindParam(":doc_ae", $doc_ae);
            $a->bindParam(":pdf_oc", $pdf_oc);
            $a->bindParam(":img_oc", $img_oc);
            $a->bindParam(":fecha", $fecha);
            $a->bindParam(":nota", $nota);

            $a->execute();

            return [
                'status' => 'success',
                'm' => 'El presupuesto se agregó correctamente.'
            ];
        } catch (PDOException $e) {

            if ($e->getCode() == '23505') {
                return [
                    'status' => 'danger',
                    'm' => 'El presupuesto ya existe para este cliente.'
                ];
            }

            return [
                'status' => 'danger',
                'm' => 'Error al agregar presupuesto: ' . $e->getMessage()
            ];
        }
    }

    // static private function enviarCorreoEnSegundoPlano($descrip, $presupuesto, $fecha, $cliente)
    // {
    //     $scriptPath = escapeshellarg(__DIR__ . DIRECTORY_SEPARATOR . 'send_email.php');
    //     $usuario = $_SESSION['s_usuario']->nombres;
    //     // $id_cliente = escapeshellarg($id_cliente);
    //     $descrip = escapeshellarg($descrip);
    //     $presupuesto  = escapeshellarg($presupuesto);
    //     $cliente = escapeshellarg($cliente);
    //     // $fecha = escapeshellarg($fecha);

    //     // Comando para ejecutar en segundo plano
    //     $command = "php $scriptPath $descrip $presupuesto  $fecha $cliente $usuario > /dev/null 2>&1 &";
    //     exec($command);
    // }


    static public function mdlEditarPretrabajo($id, $descrip, $id_cliente, $cliente, $presupuesto, $pdf_pre, $pdf_ord, $xls_pre, $xls_ord, $doc_ae, $pdf_ae, $pdf_oc, $img_oc, $fecha, $precio_iva, $precio_total, $nota)

    {

        try {
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

    // public static function mdlEliminarPretrabajo($id)
    // {
    //     try {
    //         $e = Conexion::ConexionDB()->prepare("UPDATE tblpresupuesto SET anulado=true WHERE id=:id");
    //         $e->bindParam(":id", $id, PDO::PARAM_INT);
    //         $e->execute();
    //         return array(
    //             'status' => 'success',
    //             'm' => 'Se eliminó la presupuesto correctamente.'
    //         );
    //     } catch (PDOException $e) {
    //         return array(
    //             'status' => 'danger',
    //             'm' => 'No se pudo eliminar la presupuesto: ' . $e->getMessage()
    //         );
    //     }
    // }

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
