<?php
session_start();
require_once "../utils/database/conexion.php";

class ModeloOrden
{
    static public function mdlListarOrden($anio)
    {
        try {
            $consulta = "SELECT o.id, o.nombre, 
            c.nombre as cliente, o.descripcion, TO_CHAR(o.fecha, 'DD/MM/YYYY  HH24:MI') as fecha, o.obra_estado,
            '' as acciones, o.id_cliente
        FROM tblorden o
        JOIN tblclientes c ON o.id_cliente = c.id
        WHERE o.estado = true AND EXTRACT(YEAR FROM o.fecha) = :anio
        ORDER BY  o.id;";

            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarOrden($nombre,  $id_cliente, $orden)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblorden(descripcion, id_cliente, nombre) VALUES (:des, :id_cliente, :orden)");
            $a->bindParam(":des", $nombre, PDO::PARAM_STR);
            $a->bindParam(":orden", $orden, PDO::PARAM_STR);
            $a->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $a->execute();
            // if ($a->execute()) {
            //     $stm = $conexion->prepare("SELECT last_value + 1 AS secuencia_orden FROM secuencia_orden;");
            //     $stm->execute();
            //     $sec = $stm->fetch(PDO::FETCH_ASSOC);

            //     $_SESSION["secuencia_orden"] = $sec['secuencia_orden'];
            // }

            return array(
                'status' => 'success',
                'm' => 'La orden de trabajo se agregó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar la orden de trabajo: ' . $e->getMessage()
            );
        }
    }


    static public function mdlEditarOrden($id, $nombre, $id_cliente, $orden)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblorden SET descripcion=:des,id_cliente=:id_cliente, nombre=:orden WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":des", $nombre, PDO::PARAM_STR);
            $u->bindParam(":orden", $orden, PDO::PARAM_STR);
            $u->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $u->execute();
            return array(
                'status' => 'success',
                'm' => 'La orden de trabajo se editó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar la orden de trabajo: ' . $e->getMessage()
            );
        }
    }

    public static function mdlEliminarOrden($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblorden SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó la orden de trbajo correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar la orden de trabajo: ' . $e->getMessage()
            );
        }
    }

    public static function mdlcambiarEstado($id, $estado)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblorden SET obra_estado=:estado WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->bindParam(":estado", $estado, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se actualizó el estado de la orden de trabajo corectamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo actualizar el estado de la orden de trabajo: ' . $e->getMessage()
            );
        }
    }

    public static function mdlObtenerIdOrden($nombre)
    {
        try {
            $anio_actual = date('Y');
            $e = Conexion::ConexionDB()->prepare("SELECT COALESCE((SELECT id_cliente 
            FROM tblorden 
            WHERE nombre = :nombre 
            AND (EXTRACT(YEAR FROM fecha) = :anioActual OR obra_estado=true) 
            AND estado =true), 0) AS id_cliente;");

            $e->bindParam(":nombre", $nombre, PDO::PARAM_INT);
            $e->bindParam(':anioActual', $anio_actual, PDO::PARAM_INT);
            $e->execute();
            return $e->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo consultar el nro. orden: ' . $e->getMessage()
            );
        }
    }
}
