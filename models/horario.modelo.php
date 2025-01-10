<?php

require_once "../utils/database/conexion.php";

class ModeloHorario
{
    static public function mdlListarHorario()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT h.id, e.nombre || ' ' || e.apellido as nombres,
             o.nombre as orden, c.nombre as cliente,  TO_CHAR(h.fecha, 'MM-DD-YYYY') , null as acciones
	            FROM public.tblhorario h
	            JOIN tblorden o ON o.id = h.id_orden
	            JOIN tblempleado e ON e.id = h.id_empleado 
	            JOIN tblclientes c ON c.id = o.id_cliente ORDER BY h.id");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarProveedores($ruc, $nombre, $dir, $correo, $tel)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblproveedores(ruc,nombre,direccion, correo, telefono) VALUES (:ruc,:nombre,:dir, :correo, :tel)");
            $a->bindParam(":ruc", $ruc, PDO::PARAM_STR);
            $a->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $a->bindParam(":dir", $dir, PDO::PARAM_STR);
            $a->bindParam(":correo", $correo, PDO::PARAM_STR);
            $a->bindParam(":tel", $tel, PDO::PARAM_STR);
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


    static public function mdlEditarProveedor($id, $ruc, $nombre, $dir, $correo, $tel)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblproveedores SET ruc=:ruc, nombre=:nombre, direccion = :dir, correo = :correo, telefono = :tel WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":ruc", $ruc, PDO::PARAM_STR);
            $u->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $u->bindParam(":dir", $dir, PDO::PARAM_STR);
            $u->bindParam(":correo", $correo, PDO::PARAM_STR);
            $u->bindParam(":tel", $tel, PDO::PARAM_STR);
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
