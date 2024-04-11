<?php

require_once "../utils/database/conexion.php";

class ModeloEntradas
{
    static public function mdlListarEntradas($anio, $mes)
    {
        try {
            $consulta = "SELECT e.id, i.codigo, e.cantidad_entrada, u.nombre AS unidad, '$ ' || e.precio::text AS precio,
            i.descripcion, LPAD(b.id::TEXT, 7, '0') || ' - ' || p.nombre || ' - '|| TO_CHAR(b.fecha, 'DD/MM/YYYY') AS grupo,
            b.id as id_boleta, TO_CHAR(b.fecha, 'YYYY-MM-DD') AS fecha, p.id as proveedor, ROW_NUMBER() OVER (PARTITION BY b.id ORDER BY e.id) AS fila
        FROM tblentradas e
        JOIN tblinventario i ON e.id_producto = i.id
		JOIN tblboleta b ON e.id_boleta = b.id
        JOIN tblproveedores p ON b.id_proveedor = p.id
        JOIN tblunidad u ON i.id_unidad = u.id
		WHERE EXTRACT(YEAR FROM b.fecha) = :anio ";
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

    static public function mdlAgregarEntrada($boleta, $id_producto)
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
            $e = $pdo->prepare("DELETE FROM tblentradas WHERE id_boleta =:id");
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

    static public function mdlDetalleBoletaEntrada($id_boleta)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT e.id, i.codigo, e.cantidad_entrada,
            u.nombre AS unidad,'$ ' || e.precio::text AS precio, i.descripcion
            FROM tblentradas e
            JOIN tblinventario i ON e.id_producto = i.id
            JOIN tblboleta b ON e.id_boleta = b.id 
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
