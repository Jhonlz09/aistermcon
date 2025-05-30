<?php

require_once "../utils/database/conexion.php";

class ModeloPersonal
{
    public static function mdlListarPersonal()
    {
        try {
            $consulta = "SELECT e.id,e.cedula,e.nombre,e.apellido,TO_CHAR(p.fecha_ini, 'DD/MM/YYYY') AS fecha_ini,
                        TO_CHAR(p.fecha_cor, 'DD/MM/YYYY') AS fecha_cor,
                        p.sueldo,r.nombre as rol, e.id_rol, p.ruta, p.issbu
                        FROM tblempleado e
                        JOIN tblrol r ON e.id_rol = r.id
                        LEFT JOIN tblpersonal p ON e.id = p.id_empleado
                        WHERE e.estado = true AND
	                        e.id_empresa =1
                        ORDER BY e.id;";
            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarPersonal($cedula, $nombres, $apellido, $fecha_ini, $fecha_cor, $sueldo, $ruta)
    {
        try {
            $conexion = Conexion::ConexionDB();

            // Iniciar transacción
            $conexion->beginTransaction();
            if (empty($fecha_cor)) {
                $fecha_cor = null;
            }
            if (empty($fecha_ini)) {
                $fecha_ini = null;
            }
            if (empty($sueldo)) {
                $sueldo = null;
            }
            // Primera inserción: tblempleado
            $a = $conexion->prepare("INSERT INTO tblempleado(cedula, nombre, apellido) VALUES(:cedula, :nombres, :apellidos)");
            $a->bindParam(":cedula", $cedula, PDO::PARAM_STR);
            $a->bindParam(":nombres", $nombres, PDO::PARAM_STR);
            $a->bindParam(":apellidos", $apellido, PDO::PARAM_STR);
            $a->execute();
            // Obtener el último ID insertado
            $id_empleado = $conexion->lastInsertId();

            // Segunda inserción: tblpersonal
            $stmt = $conexion->prepare("INSERT INTO tblpersonal(id_empleado, fecha_ini, fecha_cor, sueldo, ruta, issbu) VALUES(:id_empleado, :fecha_ini, :fecha_cor, :sueldo, :ruta, :issbu)");
            $stmt->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_ini', $fecha_ini, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_cor', $fecha_cor, PDO::PARAM_STR);
            $stmt->bindParam(':sueldo', $sueldo, PDO::PARAM_INT);
            $stmt->bindParam(':ruta', $ruta, PDO::PARAM_STR);
            $stmt->bindParam(':issbu', $isSbu, PDO::PARAM_BOOL);
            $stmt->execute();
            // Confirmar la transacción
            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'El empleado se agregó correctamente'
            );
        } catch (PDOException $e) {
            // Revertir transacción en caso de error
            $conexion->rollBack();

            if ($e->getCode() == '23505') { // Error de clave duplicada
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
        } catch (Exception $e) {
            // Revertir transacción en caso de error general
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar el empleado: ' . $e->getMessage()
            );
        }
    }

    static public function mdlEditarPersonal($id, $cedula, $nombres, $apellido, $fecha_ini, $fecha_cor, $sueldo, $ruta, $isSbu = false)
    {
        try {
            $db = Conexion::ConexionDB();
            $db->beginTransaction();
            if (empty($fecha_cor)) {
                $fecha_cor = null;
            }
            if (empty($fecha_ini)) {
                $fecha_ini = null;
            }
            if (empty($sueldo)) {
                $sueldo = null;
            }
            // Actualizar la tabla tblempleado
            $u = $db->prepare("UPDATE tblempleado SET cedula=:cedula, nombre=:nombres, apellido=:apellido WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":cedula", $cedula, PDO::PARAM_STR);
            $u->bindParam(":nombres", $nombres, PDO::PARAM_STR);
            $u->bindParam(":apellido", $apellido, PDO::PARAM_STR);
            $u->execute();

            $check = $db->prepare("SELECT 1 FROM tblpersonal WHERE id_empleado = :id LIMIT 1");
            $check->bindParam(":id", $id, PDO::PARAM_INT);
            $check->execute();
            $exists = $check->fetchColumn();

            if (!$exists) {
                // Insertar si no existe
                $i = $db->prepare("INSERT INTO tblpersonal(id_empleado, fecha_ini, fecha_cor, sueldo, ruta, issbu) VALUES(:id_empleado, :fecha_ini, :fecha_cor, :sueldo, :ruta, :issbu)");
                $i->bindParam(":id_empleado", $id, PDO::PARAM_INT);
                $i->bindParam(":fecha_ini", $fecha_ini, PDO::PARAM_STR);
                $i->bindParam(":fecha_cor", $fecha_cor, PDO::PARAM_STR);
                $i->bindParam(":sueldo", $sueldo, PDO::PARAM_STR);
                $i->bindParam(":ruta", $ruta, PDO::PARAM_STR);
                $i->bindParam(":issbu", $isSbu, PDO::PARAM_BOOL);
                $i->execute();
            } else {
                // Actualizar si ya existe
                $i = $db->prepare("UPDATE tblpersonal SET fecha_ini=:fecha_ini, fecha_cor=:fecha_cor, sueldo=:sueldo, ruta=:ruta, issbu=:issbu WHERE id_empleado=:id");
                $i->bindParam(":id", $id, PDO::PARAM_INT);
                $i->bindParam(":fecha_ini", $fecha_ini, PDO::PARAM_STR);
                $i->bindParam(":fecha_cor", $fecha_cor, PDO::PARAM_STR);
                $i->bindParam(":sueldo", $sueldo, PDO::PARAM_STR);
                $i->bindParam(":ruta", $ruta, PDO::PARAM_STR);
                $i->bindParam(":issbu", $isSbu, PDO::PARAM_BOOL);
                $i->execute();
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

    public static function mdlEliminarPersonal($id)
    {
        try {
            $db = Conexion::ConexionDB();
            $e = $db->prepare("UPDATE tblempleado SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_STR);
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

    public static function mdlObtenerPersonalPorId($id) {
        try {
            $consulta = "SELECT p.ruta
                            FROM tblempleado e
                            LEFT JOIN tblpersonal p ON e.id = p.id_empleado
                            WHERE e.id = :id";
            $stmt = Conexion::ConexionDB()->prepare($consulta);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }
}
