<?php

require_once "../utils/database/conexion.php";

class ModeloSalidas
{
    static public function mdlListarSalidas($anio, $mes)
    {
        try {
            $consulta = "SELECT s.id, i.codigo, s.cantidad_salida,
            u.nombre AS unidad, i.descripcion,  
            o.nombre || ' '|| c.nombre  || ' - '|| LPAD(b.nro_guia::TEXT, 9, '0') || ' - '|| TO_CHAR(b.fecha, 'DD/MM/YYYY') AS grupo, 
            s.fabricado,s.retorno, o.nombre as orden, c.nombre as cliente,
            LPAD(b.nro_guia::TEXT, 9, '0') as boleta, b.id as id_boleta, 
            o.id as id_orden, c.id as id_cliente, TO_CHAR(b.fecha, 'YYYY-MM-DD') AS fecha,
            b.id_conductor,b.id_despachado, b.id_responsable,b.nro_guia,
            ROW_NUMBER() OVER (PARTITION BY b.id ORDER BY s.id) AS fila
        FROM 
            tblsalidas s
            JOIN tblinventario i ON s.id_producto = i.id
            JOIN tblboleta b ON s.id_boleta = b.id 
            JOIN tblorden o ON b.id_orden = o.id
            JOIN tblclientes c ON c.id = o.id_cliente
            JOIN tblunidad u ON i.id_unidad = u.id
            WHERE 
                EXTRACT(YEAR FROM b.fecha) = :anio ";
            if ($mes !== '') {
                $consulta .= "AND EXTRACT(MONTH FROM b.fecha) = :mes ";
            }
            $consulta .= "ORDER BY b.fecha DESC, s.id;";

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

    static public function mdlAgregarSalida($boleta, $codigo)
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
            $stmt_check = $db->prepare("SELECT COUNT(*) FROM tblsalidas WHERE id_producto = :id_producto AND id_boleta = :boleta");
            $stmt_check->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt_check->bindParam(":boleta", $boleta, PDO::PARAM_INT);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();

            if ($count > 0) {
                return array(
                    'status' => 'danger',
                    'm' => 'El producto ya existe en la guía'
                );
            }

            // Preparar la consulta para insertar en tblsalidas
            $stmt_insert = $db->prepare("INSERT INTO tblsalidas(id_boleta, id_producto) VALUES (:boleta, :id_producto)");
            $stmt_insert->bindParam(":boleta", $boleta, PDO::PARAM_INT);
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

    public static function mdlEliminarSalida($id)
    {
        try {
            $pdo = Conexion::ConexionDB();

            // Preparar la consulta para eliminar de tblboleta (esto eliminará también las entradas relacionadas en tblsalidas)
            $e = $pdo->prepare("DELETE FROM tblboleta WHERE id = :id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó la guía con éxito.'
            );

        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar la guia: ' . $e->getMessage()
            );
        }
    }

    static public function mdlBuscarBoletaPDF($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id, i.codigo, s.cantidad_salida, u.nombre AS unidad, i.descripcion, s.cantidad_salida AS salidas, 
            s.retorno, LPAD(b.nro_guia::TEXT, 9, '0') AS id_boleta
                FROM tblsalidas s
                JOIN tblinventario i ON s.id_producto = i.id
                JOIN tblboleta b ON s.id_boleta = b.id 
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblunidad u ON i.id_unidad = u.id
                WHERE b.id = :id
                AND (s.fabricado = false OR NOT EXISTS (
                    SELECT 1
                    FROM tblsalidas s_inner
                    JOIN tblinventario i_inner ON s_inner.id_producto = i_inner.id
                    WHERE s_inner.id_boleta = b.id
                    AND i_inner.fabricado = true))
                    ORDER BY b.fecha ASC, s.id;");

            $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
            $l->execute();

            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlBuscarBoleta($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id, s.cantidad_salida,u.nombre AS unidad,
            i.descripcion, s.cantidad_salida as salidas, s.retorno, LPAD(b.id::TEXT, 7, '0') as id_boleta, 
            (s.cantidad_salida - s.retorno) as utilizado, i.codigo, s.isentrada
            FROM tblsalidas s
            JOIN tblinventario i ON s.id_producto = i.id
            JOIN tblboleta b ON s.id_boleta = b.id 
            JOIN tblorden o ON b.id_orden = o.id
            JOIN tblunidad u ON i.id_unidad = u.id
                WHERE b.id=:id
            ORDER BY b.fecha ASC, s.id");
            $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
            $l->execute();

            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlDetalleBoleta($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id, i.codigo, s.cantidad_salida,u.nombre AS unidad,
                i.descripcion, s.cantidad_salida as salidas, s.retorno
                FROM tblsalidas s
                JOIN tblinventario i ON s.id_producto = i.id
                JOIN tblboleta b ON s.id_boleta = b.id 
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblunidad u ON i.id_unidad = u.id
		            WHERE b.id=:id
                ORDER BY b.fecha ASC, s.id;");
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

    static public function mdlBuscarOrdenFecha($id_orden, $fecha = null)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id,s.cantidad_salida, 
            u.nombre AS unidad, i.descripcion, s.cantidad_salida as salidas, s.retorno
                FROM tblsalidas s
                JOIN tblinventario i ON s.id_producto = i.id
                JOIN tblboleta b ON s.id_boleta = b.id 
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblunidad u ON i.id_unidad = u.id
		        WHERE o.id = :id AND DATE(b.fecha) = :fecha
                ORDER BY b.fecha ASC");
            $l->bindParam(":id", $id_orden, PDO::PARAM_INT);
            $l->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $l->execute();

            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlBuscarDetalleBoleta($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT b.id,
            TO_CHAR(b.fecha, 'DD/MM/YYYY') as fecha, b.id,
            TO_CHAR(b.fecha_retorno, 'DD/MM/YYYY') as fecha_retorno,
            o.nombre as orden,c.nombre as cliente, c.ruc, c.direccion as direccion_cliente, c.telefono as cliente_telefono, b.motivo,
            e_despachado.nombre as despachado,
			e_responsable.nombre as responsable,
            e.apellido || ' '|| e.nombre as conductor,
			e.cedula as cedula_conductor,
			e.telefono as telefono_conductor,
			p.nombre as placa,
            LPAD(b.nro_guia::TEXT, 9, '0') as id_boleta
                FROM tblboleta b
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblclientes c ON c.id = o.id_cliente
                JOIN tblempleado_placa e_conductor ON e_conductor.id = b.id_conductor
				JOIN tblempleado e_despachado ON e_despachado.id = b.id_despachado
				JOIN tblempleado e_responsable ON e_responsable.id = b.id_responsable
				JOIN tblempleado e ON e.id = e_conductor.id_empleado
				JOIN tblplaca p ON p.id = e_conductor.id_placa
                WHERE b.id = :id;");
            $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
            $l->execute();

            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlBoletaConfig()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT emisor, ruc, matriz, correo1, correo2, telefonos FROM tblconfiguracion");
            $l->execute();

            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }
}
