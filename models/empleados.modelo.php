<?php

require_once "../utils/database/conexion.php";

class ModeloEmpleados
{
    static public function mdlListarEmpleados()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT id, cedula, nombre, conductor, '' as acciones FROM tblempleado WHERE estado=true ORDER BY id");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarEmpleados($cedula, $nombres, $conductor)
    {
        try {
            $a = Conexion::ConexionDB()->prepare("INSERT INTO tblempleado(cedula,nombre,conductor) VALUES (:cedula,:nombres,:conductor)");

            $a->bindParam(":cedula", $cedula, PDO::PARAM_STR);
            $a->bindParam(":nombres", $nombres, PDO::PARAM_STR);
            $a->bindParam(":conductor", $conductor, PDO::PARAM_BOOL);
            $a->execute();
            return array(
                'status' => 'success',
                'm' => 'El empleado se agregó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'No se puede agregar al empleado porque ya existe un empleado registrado con la misma cédula' 
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar el empleado: ' . $e->getMessage()
                );
            }
        }
    }

    static public function mdlEditarEmpleado($id, $cedula, $nombres, $conductor)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblempleado SET cedula=:cedula, nombre=:nombres, conductor=:conductor WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":cedula", $cedula, PDO::PARAM_STR);
            $u->bindParam(":nombres", $nombres, PDO::PARAM_STR);
            $u->bindParam(":conductor", $conductor, PDO::PARAM_BOOL);
            $u->execute();
            return array(
                'status' => 'success',
                'm' => 'El empleado se editó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'No se puede editar el empleado porque ya existe un empleado registrado con la misma cédula' 
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el empleado: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlEliminarEmpleado($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblempleado SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó el empleado con éxito.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar el empleado: ' . $e->getMessage()
            );
        }
    }
}
