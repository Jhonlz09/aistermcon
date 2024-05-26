<?php

require_once "../utils/database/conexion.php";

class ModeloHeaders
{
    static public function mdlListarHeaders()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT id, ciruc, nombre, direccion, telefono, correo,  '' as acciones FROM tblclientes WHERE estado=true ORDER BY id");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarHeaders($nombre)
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


    static public function mdlEditarHeaders($id, $nombre)
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

    public static function mdlEliminarHeaders($id)
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

    public static function mdlHeadersTable($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT id, nombre FROM tblencabezados WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return $e->fetch(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo consultar el encabezado: ' . $e->getMessage()
            );
        }
    }

    public static function mdlObtenerEncabezados($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT id, nombre FROM tblencabezados WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return $e->fetch(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo consultar el encabezado: ' . $e->getMessage()
            );
        }
    }
}
