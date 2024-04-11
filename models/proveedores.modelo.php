<?php

require_once "../utils/database/conexion.php";

class ModeloProveedores
{
    static public function mdlListarProveedores()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT id, nombre, '' as acciones FROM tblproveedores WHERE estado=true ORDER BY id");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarProveedores($nombre)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblproveedores(nombre) VALUES (:nombre)");
            $a->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $a->execute();

            return array(
                'status' => 'success',
                'm' => 'El proveedor se agregó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar el proveedor: ' . $e->getMessage()
            );
        }
    }


    static public function mdlEditarProveedor($id, $nombre)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblproveedores SET nombre=:nombre WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":nombre", $nombre, PDO::PARAM_STR);
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

    public static function mdlEliminarProveedor($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblproveedores SET estado=false WHERE id=:id");
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
