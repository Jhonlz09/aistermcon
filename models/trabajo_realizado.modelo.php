<?php
session_start();
require_once "../utils/database/conexion.php";
class ModeloTrabajoRealizado
{
    static public function mdlListarTrabajoRealizado($anio)
    {
        try {
            $consulta = "SELECT t.id, TO_CHAR(t.fecha, 'DD/MM/YYYY') as fecha, t.cliente, t.isfinalizado, t.pdf_arr, t.img_arr, null AS acciones, TO_CHAR(t.fecha, 'YYYY-MM-DD') AS fecha_finalizado, t.nota
                    FROM tbltrabajo_realizado t
                    WHERE EXTRACT(YEAR FROM t.fecha) = :anio ORDER BY t.id DESC;";
            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarTrabajoRealizado($fecha, $cliente, $nota, $pdf_arr, $img_arr, $isFinalizado)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tbltrabajo_realizado(fecha, cliente, nota, pdf_arr, img_arr, isFinalizado) VALUES (:fecha, :cliente, :nota, :pdf_arr, :img_arr, :isFinalizado)");
            $a->bindParam(":fecha", $fecha);
            $a->bindParam(":cliente", $cliente);
            $a->bindParam(":nota", $nota);
            $a->bindParam(":pdf_arr", $pdf_arr);
            $a->bindParam(":img_arr", $img_arr);
            $a->bindParam(":isFinalizado", $isFinalizado, PDO::PARAM_BOOL);
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

    static public function mdlEditarTrabajoRealizado($id, $fecha, $cliente, $nota, $pdf_arr, $img_arr, $isFinalizado)
    {
        try {
            $conexion = Conexion::ConexionDB();

            $campos = [
                "fecha = :fecha",
                "cliente = :cliente",
                "nota = :nota",
                "isFinalizado = :isFinalizado"
            ];

            // Solo agregar columnas de archivo si hay valor nuevo
            $binds = [
                ":fecha" => $fecha,
                ":nota" => $nota,
                ":cliente" => $cliente,
                ":id" => $id,
                ":isFinalizado" => $isFinalizado
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

            $sql = "UPDATE tbltrabajo_realizado SET " . implode(", ", $campos) . " WHERE id = :id";
            $stmt = $conexion->prepare($sql);

            foreach ($binds as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->execute();

            return ['status' => 'success', 'm' => 'El informe de trabajo se actualizó correctamente.'];
        } catch (PDOException $e) {
            return ['status' => 'danger', 'm' => 'Error al actualizar: ' . $e->getMessage()];
        }
    }

    public static function mdlEliminarTrabajoRealizado($id)
    {
        try {
            $db = Conexion::ConexionDB();

            // 1. Obtener archivos antes de borrar el registro
            $l = $db->prepare("
            SELECT unnest(pdf_arr || img_arr) AS nombre_file 
            FROM tblpre_trabajo 
            WHERE id = :id
        ");
            $l->bindParam(":id", $id, PDO::PARAM_INT);
            $l->execute();
            $files = $l->fetchAll(PDO::FETCH_COLUMN);

            // 2. Borrar registro
            $stmt = $db->prepare("DELETE FROM tblpre_trabajo WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            // Si no afectó filas, no seguir
            if ($stmt->rowCount() === 0) {
                return [
                    'status' => 'warning',
                    'm' => 'El registro no existe o ya fue eliminado.'
                ];
            }

            // 3. Eliminar archivos físicamente
            $uploadDir = "/var/www/pre_trabajo/";

            foreach ($files as $fileName) {
                $filePath = $uploadDir . $fileName;
                if ($fileName && file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            return [
                'status' => 'success',
                'm' => 'Se eliminó el pre-trabajo correctamente.'
            ];
        } catch (PDOException $e) {
            return [
                'status' => 'danger',
                'm' => 'No se pudo eliminar el pre-trabajo: ' . $e->getMessage()
            ];
        }
    }

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

    static public function mdlCambiarEstadoTrabajoRealizado($id, $isFinalizado)
    {
        try {
            $db = Conexion::ConexionDB();

            $sql = "UPDATE tbltrabajo_realizado
                    SET isFinalizado = :isFinalizado
                    WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':isFinalizado', $isFinalizado, PDO::PARAM_BOOL);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return [
                'status' => 'success',
                'm' => 'El estado del trabajo realizado se actualizó correctamente.'
            ];
        } catch (PDOException $e) {
            return [
                'status' => 'danger',
                'm' => 'Error al actualizar el estado: ' . $e->getMessage()
            ];
        }
    }
}
