<?php

require_once "../utils/database/conexion.php";

class ModeloInicio
{
    static public function mdlListarTarjetas($anio)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT
            COALESCE((SELECT COUNT(*) FROM tblinventario WHERE estado=true AND fabricado=false), 0) AS pro,
            COALESCE((SELECT COUNT(*) FROM tblfactura f WHERE EXTRACT(YEAR FROM f.fecha) = :anio), 0) AS com,
            COUNT(*) AS mov,
            COALESCE((SELECT COUNT(*) FROM tblorden o WHERE o.estado=true AND EXTRACT(YEAR FROM o.fecha) = :anio AND o.estado_obra=1), 0) AS ope
            FROM
                tblboleta b
            WHERE EXTRACT(YEAR FROM b.fecha) = :anio;");

            $l->bindParam(":anio", $anio, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlGraficoSalidas($mes, $anio)
    {
        try {
            // Base de la consulta
            $sql = "SELECT c.nombre AS cliente, COUNT(*) AS salidas 
                FROM public.tblboleta b
                    JOIN tblorden o ON b.id_orden = o.id
                    JOIN tblclientes c ON o.id_cliente = c.id
                WHERE EXTRACT(YEAR FROM b.fecha) = :anio";
            // Agregar condición del mes si $mes no es 0
            if ($mes != 0) {
                $sql .= " AND EXTRACT(MONTH FROM b.fecha) = :mes";
            }

            $sql .= " GROUP BY c.nombre;";
            // Preparar la consulta
            $a = Conexion::ConexionDB()->prepare($sql);
            // Vincular parámetros
            $a->bindParam(":anio", $anio, PDO::PARAM_INT);
            if ($mes != 0) {
                $a->bindParam(":mes", $mes, PDO::PARAM_INT);
            }

            // Ejecutar la consulta
            $a->execute();
            return $a->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlGraficoCategorias($categoria)
    {
        try {
            $a = Conexion::ConexionDB()->prepare("SELECT cl.nombre  || ' - '|| o.nombre as cliente, 
            COUNT(s.id_producto) AS salidas
            FROM tblclientes cl
            JOIN tblorden o ON cl.id = o.id_cliente
            JOIN tblboleta b ON o.id = b.id_orden 
            JOIN tblsalidas s ON b.id = s.id_boleta AND s.retorno IS NULL
            JOIN tblinventario i ON s.id_producto = i.id AND i.id_categoria = :cat
            JOIN tblcategoria c ON c.id = i.id_categoria
            GROUP BY cl.nombre, o.nombre;");

            $a->bindParam(":cat", $categoria, PDO::PARAM_INT);
            // $a->bindParam(":anio", $anio, PDO::PARAM_INT);
            $a->execute();
            return $a->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlTblTop10($mes, $anio)
    {
        try {
            // Base de la consulta
            $sql = "SELECT i.codigo, i.descripcion, 
                    to_char(SUM(s.cantidad_salida), 'FM999,999,999,999.00') AS total_vendido
                    FROM tblsalidas s
                    JOIN tblinventario i ON s.id_producto = i.id
                    JOIN tblboleta b ON s.id_boleta = b.id
                    WHERE EXTRACT(YEAR FROM b.fecha) = :anio";
            // Agregar condición del mes si $mes no es 0
            if ($mes != 0) {
                $sql .= " AND EXTRACT(MONTH FROM b.fecha) = :mes";
            }
            $sql .= " GROUP BY i.descripcion, i.codigo
                        ORDER BY SUM(s.cantidad_salida) DESC
                        LIMIT 10;";
    
            // Preparar la consulta
            $u = Conexion::ConexionDB()->prepare($sql);
    
            // Vincular parámetros
            $u->bindParam(":anio", $anio, PDO::PARAM_INT);
            if ($mes != 0) {
                $u->bindParam(":mes", $mes, PDO::PARAM_INT);
            }
    
            // Ejecutar la consulta
            $u->execute();
            return $u->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    public static function mdlTblPocoStock()
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT codigo, descripcion, stock_min, (stock- stock_mal)as disponible
            FROM tblinventario
            WHERE stock < stock_min AND estado=true;");
            $e->execute();

            return $e->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }
}
