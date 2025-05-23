<?php

require_once "../utils/database/conexion.php";

class ModeloHorario
{
    static public function mdlListarHorario($anio, $mes)
    {
        try {
            // Construimos la parte inicial de la consulta
            $sql = "SELECT
                    h.id,
                    split_part(e.apellido, ' ', 1) || ' ' || split_part(e.nombre, ' ', 1) AS nombres,
                    o.nombre AS orden,
                    c.nombre AS cliente,
                    UPPER(TO_CHAR(h.fecha, 'TMDy DD TMMON')) AS fecha,
                    h.hn_val::MONEY,
                    h.hs_val::MONEY,
                    h.he_val::MONEY,
                    h.ht_val::MONEY,
                    h.adicional_1215::MONEY,
                    h.decimo_tercer::MONEY,
                    h.decimo_cuarto::MONEY,
                    h.vacaciones::MONEY,
                    h.fondo_reserva::MONEY,
                    h.costo_mano_obra,
                    h.gasto_en_obra,
                    h.total_costo::numeric,
                    NULL AS acciones
                FROM public.tblhorario h
                    JOIN tblorden    o ON o.id = h.id_orden
                    JOIN tblempleado e ON e.id = h.id_empleado 
                    JOIN tblclientes c ON c.id = o.id_cliente
                WHERE
                    EXTRACT(YEAR FROM h.fecha) = :anio
                ";

            // Si nos pasan mes, agregamos la condición
            if ($mes !== '') {
                $sql .= " AND EXTRACT(MONTH FROM h.fecha) = :mes";
            }

            // Añadimos el ORDER BY al final
            $sql .= " ORDER BY h.fecha DESC, h.id";

            // Preparamos, vinculamos y ejecutamos
            $stmt = Conexion::ConexionDB()->prepare($sql);
            $stmt->bindParam(":anio", $anio, PDO::PARAM_INT);
            if ($mes !== '') {
                $stmt->bindParam(":mes", $mes, PDO::PARAM_INT);
            }
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarHorario($registros)
    {
        try {
            $conn = Conexion::ConexionDB();
            $stmt = $conn->prepare("INSERT INTO tblhorario (
                id_empleado, id_orden, fecha, hn, hs, he, gm, gt, ga, gh, gg, gc, id_justificacion
            ) VALUES (
                :id_empleado, :id_orden, :fecha, :hn, :hs, :he, :material, :trans, :agua, :hosp, :guard, :ali, :id_justificacion
            )");

            foreach ($registros as $r) {
                $stmt->bindValue(":id_empleado", $r["id_empleado"], PDO::PARAM_INT);
                $stmt->bindValue(":id_orden", $r["id_orden"], PDO::PARAM_INT);
                $stmt->bindValue(":fecha", $r["fecha"], PDO::PARAM_STR);
                $stmt->bindValue(":hn", $r["hn"]);
                $stmt->bindValue(":hs", $r["hs"]);
                $stmt->bindValue(":he", $r["he"]);
                $stmt->bindValue(":material", $r["material"]);
                $stmt->bindValue(":trans", $r["trans"]);
                $stmt->bindValue(":agua", $r["agua"]);
                $stmt->bindValue(":hosp", $r["hosp"]);
                $stmt->bindValue(":guard", $r["guard"]);
                $stmt->bindValue(":ali", $r["ali"]);
                $stmt->bindValue(":id_justificacion", $r["justificacion"]);
                $stmt->execute();
            }

            return array(
                'status' => 'success',
                'm' => 'El horario se agregó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar el horario: ' . $e->getMessage()
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
