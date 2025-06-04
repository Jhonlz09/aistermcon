<?php

require_once "../utils/database/conexion.php";

class ModeloHorario
{
    static public function mdlListarHorario($start, $end)
    {
        try {
            $sql = "SELECT h.id, split_part(e.apellido, ' ', 1) || ' ' || split_part(e.nombre, ' ', 1) AS nombres,
                COALESCE(j.justificacion, o.nombre) AS orden,c.nombre AS cliente,
                UPPER(TO_CHAR(h.fecha, 'TMDy DD TMMON')) AS fecha,h.hn_val::MONEY,h.hs_val::MONEY,
                h.he_val::MONEY,h.ht_val::MONEY,h.adicional_1215::MONEY,h.decimo_tercer::MONEY,
                h.decimo_cuarto::MONEY,h.vacaciones::MONEY,h.fondo_reserva::MONEY,h.costo_mano_obra,
                h.gasto_en_obra,h.total_costo,h.id_empleado,h.id_orden,TO_CHAR(h.fecha, 'YYYY-MM-DD') AS fecha_val
            FROM public.tblhorario h
                LEFT JOIN tblorden o ON o.id = h.id_orden
                JOIN tblempleado e   ON e.id = h.id_empleado 
                LEFT JOIN tblclientes c ON c.id = o.id_cliente
                LEFT JOIN tbljustificacion j ON j.id = h.id_justificacion
            WHERE h.fecha BETWEEN :start AND :end
            ORDER BY h.fecha DESC, h.id;";
            // Preparamos, vinculamos y ejecutamos
            $stmt = Conexion::ConexionDB()->prepare($sql);
            $stmt->bindParam(":start", $start);
            $stmt->bindParam(":end", $end);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }


    static public function mdlListarFechaGasto($id_orden)
    {
        try {
            $stmt = Conexion::ConexionDB()->prepare("SELECT DATE(fecha) AS fecha,
                    COALESCE(SUM(total_costo), '$0.00') AS suma_total_costo
                    FROM public.tblhorario
                    WHERE id_orden = :id_orden
                    GROUP BY DATE(fecha)
                    ORDER BY fecha;");
            $stmt->bindParam(":id_orden", $id_orden, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarGastos($start, $end)
    {
        try {
            $sql = "SELECT h.id, split_part(e.apellido, ' ', 1) || ' ' || split_part(e.nombre, ' ', 1) AS nombres,
            COALESCE(j.justificacion, o.nombre) AS orden,c.nombre AS cliente,h.gm::MONEY,h.gt::MONEY,
            h.gc::MONEY,h.gh::MONEY,h.gg::MONEY,h.ga::MONEY,h.gasto_en_obra
            FROM public.tblhorario h
                LEFT JOIN tblorden o ON o.id = h.id_orden
                JOIN tblempleado e   ON e.id = h.id_empleado 
                LEFT JOIN tblclientes c ON c.id = o.id_cliente
                LEFT JOIN tbljustificacion j ON j.id = h.id_justificacion
            WHERE h.fecha BETWEEN :start AND :end
            ORDER BY h.fecha DESC, h.id;";
            // Preparamos, vinculamos y ejecutamos
            $stmt = Conexion::ConexionDB()->prepare($sql);
            $stmt->bindParam(":start", $start);
            $stmt->bindParam(":end", $end);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlConsultarHorario($id_horario)
    {
        try {
            // Construimos la consulta con BETWEEN
            $sql = "SELECT h.hn, h.hs, h.he, h.ht, h.gm, h.gt, h.gc, h.gh,
                h.gg,h.ga,h.id,h.id_empleado,h.id_orden,
                TO_CHAR(h.fecha, 'YYYY-MM-DD') AS fecha
            FROM public.tblhorario h
            WHERE h.id = :id_horario;";
            // Preparamos, vinculamos y ejecutamos
            $stmt = Conexion::ConexionDB()->prepare($sql);
            $stmt->bindParam(":id_horario", $id_horario);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarHorario($datos)
    {
        try {
            $conn = Conexion::ConexionDB();
            $stmt = $conn->prepare("INSERT INTO tblhorario (
                id_empleado,id_orden,fecha, hn, hs, he, gm, gt, ga, gh, gg, gc, id_justificacion
            ) VALUES (
                :id_empleado, :id_orden, :fecha, :hn, :hs, :he, :material, :trans, :agua, :hosp, :guard, :ali, :id_justificacion
            )");

            foreach ($datos as $r) {
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

    static public function mdlEditarHorario($datos)
    {
        try {
            $conn = Conexion::ConexionDB();
            $stmt = $conn->prepare("UPDATE tblhorario SET
            id_empleado = :id_empleado,
            id_orden = :id_orden,
            fecha = :fecha,
            hn = :hn,
            hs = :hs,
            he = :he,
            gm = :material,
            gt = :trans,
            ga = :agua,
            gh = :hosp,
            gg = :guard,
            gc = :ali,
            id_justificacion = :id_justificacion
        WHERE id = :id_horario");
            foreach ($datos as $r) {
                if (empty($r["id_horario"])) {
                    throw new Exception("Falta el campo id_horario en el registro a actualizar.");
                }

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
                $stmt->bindValue(":id_justificacion", $r["justificacion"], PDO::PARAM_INT);
                $stmt->bindValue(":id_horario", $r["id_horario"], PDO::PARAM_INT);
                $stmt->execute();
            }

            return array(
                'status' => 'success',
                'm' => 'El horario se editó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar el horario: ' . $e->getMessage()
            );
        } catch (Exception $e) {
            return array(
                'status' => 'danger',
                'm' => $e->getMessage()
            );
        }
    }

    static public function add_editHorario($datos, $datos_edit)
    {
        // Para acumular mensajes y estado
        $result = [
            'status' => 'success',
            'messages' => []
        ];
        try {
            // Si hay datos nuevos, los insertamos
            if (!empty($datos)) {
                $addResult = self::mdlAgregarHorario($datos);
                // Si falla el insert, propagamos el error
                if ($addResult['status'] !== 'success') {
                    return $addResult;
                }
                $result['messages'][] = $addResult['m'];
            }

            // Si hay datos para editar, los actualizamos
            if (!empty($datos_edit)) {
                $editResult = self::mdlEditarHorario($datos_edit);
                if ($editResult['status'] !== 'success') {
                    return $editResult;
                }
                $result['messages'][] = $editResult['m'];
            }

            // Si no había nada qué hacer
            if (empty($datos) && empty($datos_edit)) {
                throw new Exception("No hay datos para agregar ni editar.");
            }

            // Combinamos los mensajes en uno solo
            $result['m'] = implode(" | ", $result['messages']);
            return $result;
        } catch (PDOException $e) {
            return [
                'status' => 'danger',
                'm'      => 'Error al procesar horarios: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'status' => 'danger',
                'm'      => $e->getMessage()
            ];
        }
    }

    public static function mdlEliminarHorario($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("DELETE FROM public.tblhorario WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó el horario correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar el horario: ' . $e->getMessage()
            );
        }
    }
}
