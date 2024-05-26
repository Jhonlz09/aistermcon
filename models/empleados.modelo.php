<?php

require_once "../utils/database/conexion.php";

class ModeloEmpleados
{
    public static function mdlListarEmpleados($id_empresa)
    {
        try {
            $consulta = "SELECT e.id, e.cedula, e.nombre, e.apellido, 
            e.telefono, em.nombre AS nombre_empresa, r.nombre AS nombre_rol, '' as acciones, array_agg(ep.id_placa) AS id_placa,
            e.id_rol, e.id_empresa
        FROM tblempleado e
        JOIN tblroles r ON e.id_rol = r.id 
        JOIN tblempresa em ON e.id_empresa = em.id
        LEFT JOIN 
            tblempleado_placa ep ON e.id = ep.id_empleado
        WHERE e.estado=true ";
            if ($id_empresa !== '1') {
                $consulta .= "AND em.id = :id_empresa ";
            }
            $consulta .= "GROUP BY e.id, e.cedula, e.nombre, e.apellido, e.telefono, em.nombre, r.nombre, e.id_rol, e.id_empresa
        ORDER BY e.id;";

            $l = Conexion::ConexionDB()->prepare($consulta);
            if ($id_empresa !== '1') {
                $l->bindParam(":id_empresa", $id_empresa, PDO::PARAM_INT);
            }
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarEmpleado($cedula, $nombres, $apellido, $celular, $id_empresa, $id_rol, $placas)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblempleado(cedula,nombre,apellido,telefono,id_empresa, id_rol) VALUES (:cedula,:nombres,:apellidos, :celular, :id_empresa, :id_rol)");
            $a->bindParam(":cedula", $cedula, PDO::PARAM_STR);
            $a->bindParam(":nombres", $nombres, PDO::PARAM_STR);
            $a->bindParam(":apellidos", $apellido, PDO::PARAM_STR);
            $a->bindParam(":celular", $celular, PDO::PARAM_STR);
            $a->bindParam(":id_empresa", $id_empresa, PDO::PARAM_STR);
            $a->bindParam(":id_rol", $id_rol, PDO::PARAM_STR);
            if ($a->execute()) {
                if ($id_rol == '2') {
                    $id_empleado = $conexion->lastInsertId();
                    $placas = explode(',', $placas);
                    foreach ($placas as $placa) {
                        $stmt = Conexion::ConexionDB()->prepare("INSERT INTO tblempleado_placa(id_empleado,id_placa) VALUES(:id_empleado, :id_placa)");
                        $stmt->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
                        $stmt->bindParam(':id_placa', $placa, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
            }
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

    static public function mdlEditarEmpleado($id, $cedula, $nombres, $apellido, $celular, $id_empresa, $id_rol, $placas)
    {
        try {
            $db = Conexion::ConexionDB();
            $u = $db->prepare("UPDATE tblempleado SET cedula=:cedula, nombre=:nombres, apellido=:apellidos, telefono=:celular, id_empresa=:id_empresa, id_rol=:id_rol WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":cedula", $cedula, PDO::PARAM_STR);
            $u->bindParam(":nombres", $nombres, PDO::PARAM_STR);
            $u->bindParam(":apellidos", $apellido, PDO::PARAM_STR);
            $u->bindParam(":celular", $celular, PDO::PARAM_STR);
            $u->bindParam(":id_empresa", $id_empresa, PDO::PARAM_STR);
            $u->bindParam(":id_rol", $id_rol, PDO::PARAM_STR);
            if ($u->execute()) {
                if ($id_rol == '2') {
                    $e = $db->prepare("UPDATE tblempleado_placa SET estado=false WHERE id_empleado=:id");
                    $e->bindParam(":id", $id, PDO::PARAM_INT);
                    if ($e->execute()) {
                        $placas = explode(',', $placas);
                        foreach ($placas as $placa) {
                            $stmt = $db->prepare("INSERT INTO tblempleado_placa(id_empleado,id_placa) VALUES(:id_empleado, :id_placa)");
                            $stmt->bindParam(':id_empleado', $id, PDO::PARAM_INT);
                            $stmt->bindParam(':id_placa', $placa, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                } else {
                    $e = $db->prepare("UPDATE tblempleado_placa SET estado=false WHERE id_empleado=:id");
                    $e->bindParam(":id", $id, PDO::PARAM_INT);
                    $e->execute();
                }
            }
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
            $db = Conexion::ConexionDB();
            $e = $db->prepare("UPDATE tblempleado SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
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
