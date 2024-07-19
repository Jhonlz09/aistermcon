<?php
session_start();
require_once "../utils/database/conexion.php";

class ModeloOrden
{
    static public function mdlListarOrden($anio)
    {
        try {
            $consulta = "SELECT o.id, o.nombre, 
            c.nombre as cliente, o.descripcion, TO_CHAR(o.fecha, 'DD/MM/YYYY  HH24:MI') as fecha, o.estado_obra,
            '' as acciones, o.id_cliente, o.ruta
        FROM tblorden o
		JOIN tblestado_obra eo ON o.estado_obra = eo.id
        JOIN tblclientes c ON o.id_cliente = c.id
        WHERE o.estado = true AND EXTRACT(YEAR FROM o.fecha) = :anio
        ORDER BY o.id;";

            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarOrden($nombre,  $id_cliente, $orden, $estado, $ruta)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblorden(descripcion, id_cliente, nombre, estado_obra, ruta) VALUES (:des, :id_cliente, :orden, :estado, :ruta)");
            $a->bindParam(":des", $nombre, PDO::PARAM_STR);
            $a->bindParam(":orden", $orden, PDO::PARAM_STR);
            $a->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $a->bindParam(":ruta", $ruta, PDO::PARAM_STR);
            $a->bindParam(":estado", $estado, PDO::PARAM_INT);
            $a->execute();
            // if ($a->execute()) {
            //     $stm = $conexion->prepare("SELECT last_value + 1 AS secuencia_orden FROM secuencia_orden;");
            //     $stm->execute();
            //     $sec = $stm->fetch(PDO::FETCH_ASSOC);

            //     $_SESSION["secuencia_orden"] = $sec['secuencia_orden'];
            // }

            return array(
                'status' => 'success',
                'm' => 'La orden de trabajo se agregó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'La orden de trabajo ya existe para el cliente seleccionado.'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar la orden de trabajo: ' . $e->getMessage()
                );
            }
        }
    }






    static public function mdlEditarOrden($id, $nombre, $id_cliente, $orden, $estado, $ruta)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblorden SET descripcion=:des,id_cliente=:id_cliente, nombre=:orden, estado_obra=:estado, ruta=:ruta WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":des", $nombre, PDO::PARAM_STR);
            $u->bindParam(":orden", $orden, PDO::PARAM_STR);
            $u->bindParam(":id_cliente", $id_cliente, PDO::PARAM_STR);
            $u->bindParam(":estado", $estado, PDO::PARAM_INT);
            $u->bindParam(":ruta", $ruta, PDO::PARAM_STR);

            $u->execute();
            return array(
                'status' => 'success',
                'm' => 'La orden de trabajo se editó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'La orden de trabajo ya existe para el cliente seleccionado.'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar la orden de trabajo: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlEliminarOrden($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblorden SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó la orden de trbajo correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar la orden de trabajo: ' . $e->getMessage()
            );
        }
    }

    public static function mdlcambiarEstado($id, $estado)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblorden SET estado_obra=:estado WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->bindParam(":estado", $estado, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se actualizó el estado de la orden de trabajo corectamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo actualizar el estado de la orden de trabajo: ' . $e->getMessage()
            );
        }
    }

    public static function mdlObtenerIdOrden($nombre)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT COALESCE((SELECT id_cliente 
                FROM tblorden 
                    WHERE nombre = :nombre 
                AND (EXTRACT(YEAR FROM fecha) = :anioActual OR estado_obra IN (1, 2)) 
                AND estado = true), 0) AS id_cliente;");
            $e->bindParam(":nombre", $nombre, PDO::PARAM_INT);
            $e->bindParam(':anioActual', $anio_actual, PDO::PARAM_INT);
            $e->execute();
            return $e->fetchAll();
        } catch (PDOException $e) {
            if ($e->getCode() == '21000') {
                return array(
                    'status' => 'warning',
                    'm' => 'Existen varios clientes asociados al numero de orden, por favor seleccione manualmente el cliente'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo consultar el nro. orden: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlIsPdfOrden($id_orden){
        $e = Conexion::ConexionDB()->prepare("SELECT o.ruta FROM tblorden o WHERE o.id = :id_orden");
        $e->bindParam(':id_orden', $id_orden, PDO::PARAM_INT);

        $e->execute();
        $r = $e->fetch(PDO::FETCH_ASSOC);
        return $r['ruta'];
    }

}
