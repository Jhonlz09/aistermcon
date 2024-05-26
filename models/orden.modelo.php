<?php

require_once "../utils/database/conexion.php";

class ModeloOrden
{
    static public function mdlListarOrden()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT o.id, o.nombre, 
            c.nombre as cliente, o.descripcion, TO_CHAR(o.fecha, 'DD/MM/YYYY  HH24:MI') as fecha, 
            '' as acciones, o.id_cliente
        FROM tblorden o
        JOIN tblclientes c ON o.id_cliente = c.id
        WHERE o.estado = true 
        ORDER BY  o.id;");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarOrden($nombre,  $id_cliente)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblorden(descripcion, id_cliente) VALUES (:des, :id_cliente)");
            $a->bindParam(":des", $nombre, PDO::PARAM_STR);
            $a->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);

            $a->execute();

            return array(
                'status' => 'success',
                'm' => 'La orden se generó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo generar la orden: ' . $e->getMessage()
            );
        }
    }


    static public function mdlEditarOrden($id, $nombre, $id_cliente)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblorden SET descripcion=:des,id_cliente=:id_cliente WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":des", $nombre, PDO::PARAM_STR);
            $u->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $u->execute();
            return array(
                'status' => 'success',
                'm' => 'El proveedor se editó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar el proveedor: ' . $e->getMessage()
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
                'm' => 'Se eliminó el proveedor con éxito.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar el proveedor: ' . $e->getMessage()
            );
        }
    }
}
