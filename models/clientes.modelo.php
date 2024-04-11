<?php

require_once "../utils/database/conexion.php";

class ModeloClientes
{
    static public function mdlListarClientes()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT id, nombre, '' as acciones FROM tblclientes WHERE estado=true ORDER BY id");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarClientes($nombre)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblclientes(nombre) VALUES (:nombre)");
            $a->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $a->execute();

            return array(
                'status' => 'success',
                'm' => 'El cliente se agregó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar el cliente: ' . $e->getMessage()
            );
        }
    }


    static public function mdlEditarCliente($id, $nombre)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblclientes SET nombre=:nombre WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $u->execute();
            return array(
                'status' => 'success',
                'm' => 'El cliente se editó correctamente'
            );
        } catch (PDOException $e) {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el cliente: ' . $e->getMessage()
                );
            
        }
    }

    public static function mdlEliminarCliente($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblclientes SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó el cliente con éxito.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar el cliente: ' . $e->getMessage()
            );
        }
    }
}
