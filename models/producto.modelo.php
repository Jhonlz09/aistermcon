<?php

require_once "../utils/database/conexion.php";

class ModeloProductos
{
    static public function mdlListar($tabla)
    {
        try {
            $sql = "SELECT * , '' AS acciones FROM $tabla WHERE estado=true ORDER BY id";
            $l = Conexion::ConexionDB()->prepare($sql);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregar($nombre, $tabla)
    {
        try {
            $sql = "INSERT INTO $tabla (nombre) VALUES (:nombre)";
            $a = Conexion::ConexionDB()->prepare($sql);
            $a->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $a->execute();
            return array(
                'status' => 'success',
                'm' => 'Se agregó correctamente'
            );
        } catch (PDOException $e) {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar: ' . $e->getMessage()
                );
        }
    }

    static public function mdlEditar($id, $nombre, $tabla)
    {
        try {
            $sql= "UPDATE $tabla SET nombre=:nombre WHERE id=:id";
            $u = Conexion::ConexionDB()->prepare($sql);
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $u->execute();
            return array(
                'status' => 'success',
                'm' => 'Se editó correctamente'
            );
        } catch (PDOException $e) {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar: ' . $e->getMessage()
                );
        }
    }

    public static function mdlEliminar($id, $tabla)
    {
        try {
            $sql= "UPDATE $tabla SET estado=false WHERE id=:id";
            $e = Conexion::ConexionDB()->prepare($sql);
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar: ' . $e->getMessage()
            );
        }
    }

    static public function mdlAgregarOrden($nombre, $id_cliente)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $sql = "INSERT INTO tblorden (nombre, id_cliente) VALUES (:nombre, :id_cliente)";
            $a = $conexion->prepare($sql);
            $a->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $a->bindParam(":id_cliente", $id_cliente, PDO::PARAM_INT);
            $a->execute();
            
            $id_orden_add = $conexion->lastInsertId();

            return array(
                'status' => 'success',
                'm' => 'Se agregó correctamente',
                'res' => $id_orden_add
            );
        } catch (PDOException $e) {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar: ' . $e->getMessage()
                );
        }
    }

    static public function mdlEditarOrden($id, $nombre, $id_cliente)
    {
        try {
            $sql= "UPDATE tblorden SET nombre=:nombre, id_cliente=:id_cliente WHERE id=:id";
            $u = Conexion::ConexionDB()->prepare($sql);
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $u->bindParam(":id_cliente", $id_cliente, PDO::PARAM_INT);
            $u->execute();
            return array(
                'status' => 'success',
                'm' => 'Se editó correctamente'
            );
        } catch (PDOException $e) {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar: ' . $e->getMessage()
                );
        }
    }

    // public static function mdlEliminarOrden($id)
    // {
    //     try {
    //         $sql= "UPDATE tblorden SET estado=false WHERE id=:id";
    //         $e = Conexion::ConexionDB()->prepare($sql);
    //         $e->bindParam(":id", $id, PDO::PARAM_INT);
    //         $e->execute();
    //         return array(
    //             'status' => 'success',
    //             'm' => 'Se eliminó correctamente.'
    //         );
    //     } catch (PDOException $e) {
    //         return array(
    //             'status' => 'danger',
    //             'm' => 'No se pudo eliminar: ' . $e->getMessage()
    //         );
    //     }
    // }

    
}
