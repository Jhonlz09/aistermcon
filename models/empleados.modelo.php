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
        JOIN tblrol r ON e.id_rol = r.id 
        JOIN tblempresa em ON e.id_empresa = em.id
        LEFT JOIN 
            tblempleado_placa ep ON e.id = ep.id_empleado  AND ep.estado = true
        WHERE e.estado=true ";
            if ($id_empresa !== '0') {
                $consulta .= "AND em.id = :id_empresa ";
            }
            $consulta .= "GROUP BY e.id, e.cedula, e.nombre, e.apellido, e.telefono, em.nombre, r.nombre, e.id_rol, e.id_empresa
        ORDER BY e.id;";

            $l = Conexion::ConexionDB()->prepare($consulta);
            if ($id_empresa !== '0') {
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
                if ($placas !== '') {
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
            $db->beginTransaction(); // Iniciar transacción

            // Actualizar la tabla tblempleado
            $u = $db->prepare("UPDATE tblempleado SET cedula=:cedula, nombre=:nombres, apellido=:apellidos, telefono=:celular, id_empresa=:id_empresa, id_rol=:id_rol WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":cedula", $cedula, PDO::PARAM_STR);
            $u->bindParam(":nombres", $nombres, PDO::PARAM_STR);
            $u->bindParam(":apellidos", $apellido, PDO::PARAM_STR);
            $u->bindParam(":celular", $celular, PDO::PARAM_STR);
            $u->bindParam(":id_empresa", $id_empresa, PDO::PARAM_STR);
            $u->bindParam(":id_rol", $id_rol, PDO::PARAM_STR);
            if ($u->execute()) {

                // Obtener todas las placas actuales del empleado
                $currentPlacasStmt = $db->prepare("SELECT id_placa FROM tblempleado_placa WHERE id_empleado=:id");
                $currentPlacasStmt->bindParam(":id", $id, PDO::PARAM_INT);
                $currentPlacasStmt->execute();
                $currentPlacas = $currentPlacasStmt->fetchAll(PDO::FETCH_COLUMN, 0);

                if ($placas !== '') {
                    $placasArray = explode(',', $placas);

                    // Convertir a arrays de enteros
                    $placasArray = array_map('intval', $placasArray);
                    $currentPlacas = array_map('intval', $currentPlacas);

                    // Placas a desactivar (estado=false)
                    $placasToDeactivate = array_diff($currentPlacas, $placasArray);
                    if (!empty($placasToDeactivate)) {
                        foreach ($placasToDeactivate as $placa) {
                            $deactivateStmt = $db->prepare("UPDATE tblempleado_placa SET estado=false WHERE id_empleado=:id AND id_placa=:placa");
                            $deactivateStmt->bindParam(":id", $id, PDO::PARAM_INT);
                            $deactivateStmt->bindParam(":placa", $placa, PDO::PARAM_INT);
                            $deactivateStmt->execute();
                        }
                    }

                    // Placas a activar (estado=true) o insertar
                    foreach ($placasArray as $placa) {
                        $activateStmt = $db->prepare("SELECT 1 FROM tblempleado_placa WHERE id_empleado=:id AND id_placa=:placa");
                        $activateStmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $activateStmt->bindParam(':placa', $placa, PDO::PARAM_INT);
                        $activateStmt->execute();

                        if ($activateStmt->fetch()) {
                            // Si la placa ya existe, actualizar estado a true
                            $updateStmt = $db->prepare("UPDATE tblempleado_placa SET estado=true WHERE id_empleado=:id AND id_placa=:placa");
                            $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $updateStmt->bindParam(':placa', $placa, PDO::PARAM_INT);
                            $updateStmt->execute();
                        } else {
                            // Si la placa no existe, insertar nueva fila con estado true
                            $insertStmt = $db->prepare("INSERT INTO tblempleado_placa(id_empleado, id_placa, estado) VALUES(:id_empleado, :id_placa, true)");
                            $insertStmt->bindParam(':id_empleado', $id, PDO::PARAM_INT);
                            $insertStmt->bindParam(':id_placa', $placa, PDO::PARAM_INT);
                            $insertStmt->execute();
                        }
                    }
                } else {
                    // Si no se proporcionan placas, desactivar todas las placas actuales
                    $deactivateAllStmt = $db->prepare("UPDATE tblempleado_placa SET estado=false WHERE id_empleado=:id");
                    $deactivateAllStmt->bindParam(":id", $id, PDO::PARAM_INT);
                    $deactivateAllStmt->execute();
                }
            }

            $db->commit(); // Confirmar transacción

            return array(
                'status' => 'success',
                'm' => 'El empleado se editó correctamente'
            );
        } catch (PDOException $e) {
            $db->rollBack(); // Revertir transacción en caso de error

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
            $e->bindParam(":id", $id, PDO::PARAM_STR);
            $e->execute();

            return array(
                'status' => 'success',
                'm' => 'Se eliminó el empleado con éxito.' . $id
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar el empleado: ' . $e->getMessage()
            );
        }
    }
}
