<?php

require_once "../utils/database/conexion.php";

class ModeloClientes
{
    static public function mdlListarClientes()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT c.id, t.nombre as tipo, c.ruc, c.cedula, c.nombre, c.razon_social,c.direccion,
            c.telefono, c.correo, '' as acciones, t.id as id_tipo
            FROM tblclientes c
            JOIN tbltipo t ON t.id = c.id_tipo  
            WHERE c.estado=true ORDER BY c.id");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarClientes($id_tipo, $ruc, $ci, $nombre, $razon, $dir, $correo, $tel)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblclientes(id_tipo, ruc, cedula, nombre, razon_social, direccion, correo, telefono) 
            VALUES (:id_tipo, :ruc, :ced, :nombre, :razon, :dir, :correo, :tel)");
            $a->bindParam(":id_tipo", $id_tipo, PDO::PARAM_INT);
            $a->bindParam(":ruc", $ruc, PDO::PARAM_STR);
            $a->bindParam(":ced", $ci, PDO::PARAM_STR);
            $a->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $a->bindParam(":razon", $razon, PDO::PARAM_STR);
            $a->bindParam(":dir", $dir, PDO::PARAM_STR);
            $a->bindParam(":correo", $correo, PDO::PARAM_STR);
            $a->bindParam(":tel", $tel, PDO::PARAM_STR);
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



    static public function mdlEditarCliente($id, $id_tipo, $ruc, $ci, $nombre, $razon, $dir, $correo, $tel)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblclientes 
                                            SET id_tipo = :id_tipo, ruc = :ruc, cedula = :ced, nombre = :nombre, 
                                                razon_social = :razon, direccion = :dir, correo = :correo, telefono = :tel  
                                            WHERE id = :id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":id_tipo", $id_tipo, PDO::PARAM_INT);
            $u->bindParam(":ruc", $ruc, PDO::PARAM_STR);
            $u->bindParam(":ced", $ci, PDO::PARAM_STR);
            $u->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $u->bindParam(":razon", $razon, PDO::PARAM_STR);
            $u->bindParam(":dir", $dir, PDO::PARAM_STR);
            $u->bindParam(":correo", $correo, PDO::PARAM_STR);
            $u->bindParam(":tel", $tel, PDO::PARAM_STR);
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
