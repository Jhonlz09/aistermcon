<?php

require_once "../utils/database/conexion.php";

class ModeloSalidas
{
    static public function mdlListarSalidas($anio, $mes)
    {
        try {
            $consulta = "SELECT s.id, i.codigo, s.cantidad_salida, u.nombre AS unidad, 
            i.descripcion, LPAD(b.id::TEXT, 7, '0') || ' - '|| c.nombre || ' '|| o.nombre  || ' - '|| TO_CHAR(b.fecha, 'DD/MM/YYYY') AS grupo,
            s.retorno, b.id as id_boleta, o.id as orden, c.id as cliente, TO_CHAR(b.fecha, 'YYYY-MM-DD') AS fecha, b.id_conductor,b.id_entrega,
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

    static public function mdlAgregarSalida($boleta, $id_producto)
    {
        try {
            $a = Conexion::ConexionDB()->prepare("INSERT INTO tblsalidas(id_boleta,id_producto) VALUES (:boleta,:id_producto)");
            $a->bindParam(":boleta", $boleta, PDO::PARAM_INT);
            $a->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $a->execute();
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

    // static public function mdlEditarSalida($id,  $nombres, $conductor)
    // {
    //     try {
    //         $u = Conexion::ConexionDB()->prepare("UPDATE tblempleado SET cedula=:cedula, nombres_empleado=:nombres, conductor=:conductor WHERE id_empleado=:id");
    //         $u->bindParam(":id", $id, PDO::PARAM_INT);
    //         $u->bindParam(":nombres", $nombres, PDO::PARAM_STR);
    //         $u->bindParam(":conductor", $conductor, PDO::PARAM_BOOL);
    //         $u->execute();
    //         return array(
    //             'status' => 'success',
    //             'm' => 'La salida se editó correctamente'
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

    public static function mdlEliminarSalida($id)
    {
        try {
            $pdo = Conexion::ConexionDB();
            $e = $pdo->prepare("DELETE FROM tblsalidas WHERE id_boleta =:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();

            $eB = $pdo->prepare("DELETE FROM tblboleta WHERE id = :id");
            $eB->bindParam(":id", $id, PDO::PARAM_INT);
            $eB->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó la guia con éxito.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar la guia: ' . $e->getMessage()
            );
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
            $l = Conexion::ConexionDB()->prepare("SELECT TO_CHAR(b.fecha, 'DD/MM/YYYY') as fecha,
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

    // static public function mdlAgregarRetorno($id, $retorno)
    // {
    //     try {
    //         $a = Conexion::ConexionDB()->prepare("UPDATE tblsalidas SET retorno=:retorno WHERE id =:id");
    //         $a->bindParam(":id", $id, PDO::PARAM_INT);
    //         $a->bindParam(":retorno", $retorno, PDO::PARAM_STR);
    //         $a->execute();

    //         return array(
    //             'status' => 'success',
    //             'm' => 'La entrada se agregó correctamente'
    //         );
    //     } catch (PDOException $e) {
    //         return array(
    //             'status' => 'danger',
    //             'm' => 'No se pudo agregar la entrada: ' . $e->getMessage()
    //         );
    //     }
    // }
}
