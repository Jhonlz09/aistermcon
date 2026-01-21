<?php
require_once "../utils/database/conexion.php";

class ModeloCombos
{

    static public function mdlListar($tabla)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT id,nombre FROM $tabla WHERE estado=true ORDER BY id ASC");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarConductor()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT ep.id,
            split_part(e.nombre, ' ', 1) || ' ' || split_part(e.apellido, ' ', 1) || ' | ' || p.nombre AS nombre
        FROM tblempleado e
        JOIN tblempleado_placa ep ON e.id = ep.id_empleado AND ep.estado =true
        JOIN tblplaca p ON ep.id_placa = p.id
        WHERE e.estado = true;");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarDespachado()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT id, split_part(nombre, ' ', 1) || ' ' || split_part(apellido, ' ', 1) as nombre FROM tblempleado WHERE id_rol IN (1, 5) AND estado=true ORDER BY id ASC");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarResponsable($lastName = false)
    {
        try {
            if ($lastName) {
                $sql = "SELECT id, apellido || ' ' || nombre as nombre, CASE 
                        WHEN id_rol = 3 THEN 1
                        WHEN id_rol = 4 THEN 2
                        ELSE 3
                        END AS rol 
                    FROM tblempleado WHERE id_empresa=1 AND estado=true ORDER BY nombre ASC";
            } else if ($lastName == false) {
                $sql = "SELECT id, split_part(apellido, ' ', 1) || ' ' ||  split_part(nombre, ' ', 1) as nombre FROM tblempleado WHERE id_empresa=1 AND estado=true ORDER BY nombre ASC";
            }

            $l = Conexion::ConexionDB()->prepare($sql);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarOrden($anio)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT o.id, 
            p.num_orden || ' | ' || c.nombre AS nombre,COALESCE(b.tiene_boleta_fab, false) AS tiene_boleta_fab
            FROM tblorden o
				JOIN tblpresupuesto p ON p.id = o.id
				JOIN tblclientes c ON c.id = p.id_cliente
                LEFT JOIN (
                    SELECT DISTINCT id_orden, true AS tiene_boleta_fab
                    FROM tblboleta
                    WHERE fab = true
                ) b ON b.id_orden = o.id
                WHERE o.anulado = false
                AND EXTRACT(YEAR FROM o.fecha) = :anio
                ORDER BY o.id DESC;");
            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }


    // static public function mdlListarClientesActivos()
    // {
    //     try {
    //         $l = Conexion::ConexionDB()->prepare("SELECT c.id, c.nombre FROM tblclientes c JOIN tblorden o ON o.id_cliente = c.id WHERE o.obra_estado = true AND o.estado=true GROUP BY c.id");
    //         $l->execute();
    //         return $l->fetchAll();
    //     } catch (PDOException $e) {
    //         return "Error en la consulta: " . $e->getMessage();
    //     }
    // }

    static public function mdlListarProductosFab()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT i.id, i.stock::double precision || ' '|| u.nombre || ' | '||i.descripcion AS nombre,
            i.stock::double precision , u.id, i.descripcion
            from tblinventario i 
            JOIN tblunidad u on u.id = i.id_unidad
            where i.fabricado = true AND stock != 0 AND i.estado=true  ORDER BY i.id desc");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarProductosFabCon()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT i.id, i.codigo || ' | '||i.descripcion AS nombre
            from tblinventario i 
            JOIN tblunidad u on u.id = i.id_unidad
            where i.fabricado = true AND i.estado=true ORDER BY i.id desc");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarEmpresaFilter()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT 0 AS id, 'TODO' AS nombre
            UNION ALL
            SELECT id, nombre
            FROM tblempresa
            WHERE estado=true");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarOrdenEstadoFilter()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT null AS id, 'TODO' AS nombre
            UNION ALL
            SELECT id, estado_obra AS nombre
            FROM tblestado_obra");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarOrdenHorario($anio)
    {
        try {
            $sql = "SELECT o.id AS id, p.num_orden || ' ' || c.nombre AS nombre
            FROM tblhorario h
            JOIN tblorden o ON o.id = h.id_orden
			JOIN tblpresupuesto p ON o.id = p.id
            JOIN tblclientes c ON c.id = p.id_cliente
            WHERE 
                EXTRACT(YEAR FROM h.fecha) = :anio
            GROUP BY o.id, p.num_orden, c.nombre
            ORDER BY o.id;";

            $l = Conexion::ConexionDB()->prepare($sql);
            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function listarOrdenActivaHorario()
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT o.id, 
            p.num_orden || ' ' || c.nombre AS label, 
            o.estado AS cantidad, 
            EXTRACT(YEAR FROM o.fecha) AS anio,
            p.descripcion AS descripcion_orden
                FROM tblorden o 
				JOIN tblpresupuesto p ON o.id = p.id
                JOIN tblclientes c ON p.id_cliente = c.id
                WHERE o.anulado = false 
                AND o.estado IN ('ESPERA', 'OPERACION', 'FINALIZADO', 'GARANTIA')
                ORDER BY o.id DESC;");
            $e->execute();
            return $e->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo obtener el producto: ' . $e->getMessage()
            );
        }
    }
}
