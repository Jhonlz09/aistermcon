<?php


require_once "../utils/database/conexion.php";

class ModeloEntradas
{
    static public function mdlListarEntradas($anio, $mes)
    {
        try {
            $consulta = "SELECT e.id, i.codigo, e.cantidad_entrada, u.nombre AS unidad, '$ ' || e.precio_uni AS precio, 
			e.precio_total, e.precio_iva, e.precio_total_iva,
            i.descripcion, b.nombre || '  ' || p.nombre || ' - '|| TO_CHAR(b.fecha, 'DD/MM/YYYY HH24:MI') AS grupo,
            p.nombre as pro,b.nombre as nro_fac,b.id as id_boleta, TO_CHAR(b.fecha, 'YYYY-MM-DD') AS fecha, 
            p.id as proveedor,ROW_NUMBER() OVER (PARTITION BY b.id ORDER BY e.id) AS fila, b.importacion
                FROM tblentradas e
                    JOIN tblinventario i ON e.id_producto = i.id
		            JOIN tblfactura b ON e.id_factura = b.id
                    JOIN tblproveedores p ON b.id_proveedor = p.id
                    JOIN tblunidad u ON i.id_unidad = u.id
		        WHERE EXTRACT(YEAR FROM b.fecha) =  :anio ";

            if ($mes !== '') {
                $consulta .= "AND EXTRACT(MONTH FROM b.fecha) = :mes ";
            }
            $consulta .= "ORDER BY b.fecha DESC, e.id;";
            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
            if ($mes !== '') {
                $l->bindParam(":mes", $mes, PDO::PARAM_INT);
            }
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarEntrada($factura, $codigo)
    {
        try {
            // Obtener la conexión a la base de datos
            $db = Conexion::ConexionDB();

            // Obtener el id_producto a partir del código
            $stmt_get_product_id = $db->prepare("SELECT id AS id_producto FROM tblinventario WHERE codigo = :codigo");
            $stmt_get_product_id->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $stmt_get_product_id->execute();
            $id_producto = $stmt_get_product_id->fetchColumn();

            if ($id_producto === false) {
                return array(
                    'status' => 'danger',
                    'm' => 'El código del producto no existe.'
                );
            }

            // Verificar si el id_producto ya está relacionado con el id_boleta en tblsalidas
            $stmt_check = $db->prepare("SELECT COUNT(*) FROM tblentradas WHERE id_producto = :id_producto AND id_factura = :boleta");
            $stmt_check->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt_check->bindParam(":boleta", $factura, PDO::PARAM_INT);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();

            if ($count > 0) {
                return array(
                    'status' => 'danger',
                    'm' => 'El producto ya existe en la factura'
                );
            }

            // Preparar la consulta para insertar en tblentradas
            $stmt_insert = $db->prepare("INSERT INTO tblentradas(id_factura, id_producto) VALUES (:boleta, :id_producto)");
            $stmt_insert->bindParam(":boleta", $factura, PDO::PARAM_INT);
            $stmt_insert->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt_insert->execute();

            return array(
                'status' => 'success',
                'm' => 'El producto se agregó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar el producto: ' . $e->getMessage()
            );
        }
    }

    // static public function mdlEditarEntrada($id, $cedula, $nombres, $conductor)
    // {
    //     try {
    //         $u = Conexion::ConexionDB()->prepare("UPDATE tblempleado SET cedula=:cedula, nombres_empleado=:nombres, conductor=:conductor WHERE id_empleado=:id");
    //         $u->bindParam(":id", $id, PDO::PARAM_INT);
    //         $u->bindParam(":cedula", $cedula, PDO::PARAM_STR);
    //         $u->bindParam(":nombres", $nombres, PDO::PARAM_STR);
    //         $u->bindParam(":conductor", $conductor, PDO::PARAM_BOOL);
    //         $u->execute();
    //         return array(
    //             'status' => 'success',
    //             'm' => 'El empleado se editó correctamente'
    //         );
    //     } catch (PDOException $e) {
    //         if ($e->getCode() == '23505') {
    //             return array(
    //                 'status' => 'danger',
    //                 'm' => 'No se pudo editar el empleado debido a que ya existe un empleado con la misma cedula'
    //             );
    //         } else {
    //             return array(
    //                 'status' => 'danger',
    //                 'm' => 'No se pudo editar el empleado: ' . $e->getMessage()
    //             );
    //         }
    //     }
    // }

    public static function mdlEliminarEntrada($id)
    {
        try {
            $pdo = Conexion::ConexionDB();

            $eB = $pdo->prepare("DELETE FROM tblfactura WHERE id = :id");
            $eB->bindParam(":id", $id, PDO::PARAM_INT);
            $eB->execute();

            return array(
                'status' => 'success',
                'm' => 'Se eliminó la factura con éxito.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar la factura: ' . $e->getMessage()
            );
        }
    }

    static public function mdlDetalleBoletaEntrada($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT e.id || ',' || b.id as id , i.codigo, e.cantidad_entrada,
            u.nombre AS unidad, e.precio_uni::numeric AS precio, e.precio_envio,e.precio_carga,e.precio_descuento, e.precio_iva, e.precio_total_iva, i.descripcion
            FROM tblentradas e
            JOIN tblinventario i ON e.id_producto = i.id
            JOIN tblfactura b ON e.id_factura = b.id 
            JOIN tblunidad u ON i.id_unidad = u.id
                WHERE b.id= :id
            ORDER BY b.fecha ASC, e.id;");

            $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
            $l->execute();

            $numero_filas = $l->rowCount();
            $result = $l->fetchAll();

            $data = array(
                'draw' => intval($_POST['draw']),
                'recordsTotal' => $numero_filas,
                'recordsFiltered' => $numero_filas,
                'data' => $result
            );

            return $data;
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }
}
