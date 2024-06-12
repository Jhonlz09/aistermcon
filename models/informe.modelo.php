<?php

require_once "../utils/database/conexion.php";

class ModeloInforme
{
    static public function mdlListarInforme($anio, $mes)
    {
        try {
            $consulta = "SELECT s.id, i.codigo,LPAD(b.nro_guia::TEXT,9,'0') as nro_guia,
            TO_CHAR(b.fecha, 'DD/MM/YYYY') AS fecha, 
            TO_CHAR(b.fecha_retorno, 'DD/MM/YYYY') AS fecha_retorno, 
            i.descripcion, u.nombre AS unidad, s.cantidad_salida, s.retorno, 
            o.nombre || ' '|| cl.nombre AS grupo, b.id as id_boleta, 
            o.id as id_orden, cl.id as cliente, b.id_conductor,b.id_despachado,
            b.id_responsable,ROW_NUMBER() OVER (PARTITION BY o.id ORDER BY s.id) AS fila
        FROM 
            tblsalidas s
            JOIN tblinventario i ON s.id_producto = i.id
            JOIN tblboleta b ON s.id_boleta = b.id 
            JOIN tblorden o ON b.id_orden = o.id
            JOIN tblclientes cl ON cl.id = o.id_cliente
            JOIN tblunidad u ON i.id_unidad = u.id
        WHERE EXTRACT(YEAR FROM b.fecha) = :anio ";
            if ($mes !== '') {
                $consulta .= "AND EXTRACT(MONTH FROM b.fecha) = :mes ";
            }
            $consulta .= "ORDER BY b.fecha_retorno , o.id desc , s.id;";

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

    static public function mdlInformeFechaOrden($id_orden)
    {
        try {
            $a = Conexion::ConexionDB()->prepare("			SELECT 
			TO_CHAR(DATE(b.fecha), 'DD/MM/YYYY') AS fecha_emision,
            TO_CHAR(DATE(b.fecha_retorno), 'DD/MM/YYYY') AS fecha_retorno,
			b.id as id_guia,
			b.motivo,
			LPAD(b.nro_guia::TEXT,9,'0') as nro_guia,
			e_despachado.nombre || ' '  || e_despachado.apellido AS despachado,
            e_responsable.nombre AS responsable,
			e.nombre || ' ' || e.apellido AS conductor,
			p.nombre AS placa
        FROM 
            tblboleta b
            JOIN tblorden o ON b.id_orden = o.id 
			JOIN tblclientes cl ON cl.id = o.id_cliente
			LEFT JOIN tblempleado_placa e_conductor ON e_conductor.id = b.id_conductor
            LEFT JOIN tblempleado e_despachado ON e_despachado.id = b.id_despachado
            LEFT JOIN tblempleado e_responsable ON e_responsable.id = b.id_responsable
            LEFT JOIN tblempleado e ON e.id = e_conductor.id_empleado
            LEFT JOIN tblplaca p ON p.id = e_conductor.id_placa
			WHERE 
				o.id = :id_orden
            ORDER BY DATE(b.fecha);");

            $a->bindParam(":id_orden", $id_orden, PDO::PARAM_INT);
            $a->execute();
            return $a->fetchAll();
        } catch (PDOException $e) {

            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlInformeDetalleOrden($id_orden)
    {
        try {
            $a = Conexion::ConexionDB()->prepare("SELECT 
            cl.nombre as cliente,
			o.nombre as orden_nro,
			o.descripcion
        FROM 
            tblorden o 
			JOIN tblclientes cl ON cl.id = o.id_cliente
			WHERE 
				o.id = :id_orden");

            $a->bindParam(":id_orden", $id_orden, PDO::PARAM_INT);
            $a->execute();
            return $a->fetchAll();
        } catch (PDOException $e) {

            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlInformeOrden($id_orden, $id_guia)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("SELECT i.id as id_producto, i.codigo, 
            s.cantidad_salida, u.nombre AS unidad, i.descripcion, i.fabricado,
            COALESCE(s.retorno::text, '-') AS retorno,
            COALESCE(s.diferencia::text, '-') as utilizado
        FROM 
            tblsalidas s
            JOIN tblinventario i ON s.id_producto = i.id
            JOIN tblboleta b ON s.id_boleta = b.id 
			JOIN tblorden o ON b.id_orden = o.id 
            JOIN tblunidad u ON i.id_unidad = u.id
			WHERE 
				o.id = :id_orden
				AND b.id = :id_guia;");
            $u->bindParam(":id_guia", $id_guia, PDO::PARAM_INT);
            $u->bindParam(":id_orden", $id_orden, PDO::PARAM_INT);
            $u->execute();
            return $u->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlInformeOrdenResumen($id_orden)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("SELECT i.id as id_producto, i.fabricado, i.codigo,i.descripcion, 
            u.nombre AS unidad, SUM(s.cantidad_salida) AS cantidad_salida, 
            SUM(s.retorno) AS retorno,
            SUM(s.cantidad_salida) - SUM(s.retorno) AS utilizado
        FROM 
            tblsalidas s
            JOIN tblinventario i ON s.id_producto = i.id
            JOIN tblboleta b ON s.id_boleta = b.id 
            JOIN tblorden o ON b.id_orden = o.id
            JOIN tblclientes cl ON cl.id = o.id_cliente
            JOIN tblunidad u ON i.id_unidad = u.id
        WHERE 
            o.id=:id_orden
        GROUP BY s.id_producto,i.codigo, i.descripcion, u.nombre,i.id,i.fabricado
        ORDER BY s.id_producto;");

            $u->bindParam(":id_orden", $id_orden, PDO::PARAM_INT);
            $u->execute();
            return $u->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlInformeOrdenResumenFab($id_fab)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("SELECT SUM(s.cantidad_salida) AS cantidad_salida, SUM(s.retorno) AS retorno, u.nombre as unidad,
            i.descripcion, SUM(s.cantidad_salida) - SUM(s.retorno) AS utilizado,
            i.codigo
            FROM tblsalidas s
            JOIN tblinventario i ON i.id = s.id_producto
            JOIN tblunidad u ON u.id = i.id_unidad
            WHERE s.id_producto_fab = :id_fab
		GROUP BY s.id_producto,i.codigo, i.descripcion, u.nombre,i.id,i.fabricado
		    ORDER BY s.id_producto;");
            $u->bindParam(":id_fab", $id_fab, PDO::PARAM_INT);
            $u->execute();
            return $u->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

   

    static public function mdlBuscarBoleta($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id, s.cantidad_salida,u.nombre AS unidad,
                i.descripcion, s.cantidad_salida as salidas, s.retorno, LPAD(b.id::TEXT, 7, '0') as id_boleta
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
            $l = Conexion::ConexionDB()->prepare("SELECT TO_CHAR(DATE(b.fecha), 'DD/MM/YYYY') as fecha,
            o.nombre as orden,
            c.nombre as cliente,
            e_entrega.nombre as entrega,
            e_conductor.nombre as conductor,
            e_conductor.cedula as cedula_conductor
                FROM tblboleta b
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblclientes c ON c.id = o.id_cliente
                JOIN tblempleado e_entrega ON e_entrega.id = b.id_entrega
                JOIN tblempleado e_conductor ON e_conductor.id = b.id_conductor
                WHERE b.id = :id;");
            $l->bindParam(":id", $id_boleta, PDO::PARAM_INT);
            $l->execute();

            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }
}
