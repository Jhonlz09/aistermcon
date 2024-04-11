<?php

require_once "../utils/database/conexion.php";

class ModeloInicio
{
    static public function mdlListarTarjetas($anio)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT 	
            COALESCE((SELECT COUNT(*) FROM tblinventario WHERE estado=true), 0) AS productos,
            COALESCE(SUM(CASE WHEN tipo_boleta = 'S' THEN 1 ELSE 0 END), 0) AS retorno,
            COALESCE(SUM(CASE WHEN tipo_boleta = 'E' THEN 1 ELSE 0 END), 0) AS entradas,
            COALESCE(SUM(CASE WHEN tipo_boleta IN ('S', 'R') THEN 1 ELSE 0 END), 0) AS salidas
        FROM 
            tblboleta
        WHERE 
            tipo_boleta IN ('R', 'E', 'S') AND EXTRACT(YEAR FROM fecha) =:anio;");

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
            $a = Conexion::ConexionDB()->prepare("SELECT c.nombre AS cliente,
            SUM(CASE WHEN tipo_boleta IN ('S', 'R') THEN 1 ELSE 0 END) AS salidas
                FROM public.tblboleta b
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblclientes c ON o.id_cliente = c.id
                WHERE EXTRACT(MONTH FROM b.fecha) = :mes
                AND EXTRACT(YEAR FROM b.fecha) = :anio
                    GROUP BY c.nombre;");

            $a->bindParam(":mes", $mes, PDO::PARAM_INT);
            $a->bindParam(":anio", $anio, PDO::PARAM_INT);
            $a->execute();
            return $a->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlGraficoCategorias($categoria)
    {
        try {
            $a = Conexion::ConexionDB()->prepare("SELECT cl.nombre as cliente, 
            COUNT(s.id_producto) AS salidas
            FROM tblclientes cl
            JOIN tblorden o ON cl.id = o.id_cliente
            JOIN tblboleta b ON o.id = b.id_orden 
            JOIN tblsalidas s ON b.id = s.id_boleta AND s.retorno IS NULL
            JOIN tblinventario i ON s.id_producto = i.id AND i.id_categoria = :cat
            JOIN tblcategoria c ON c.id = i.id_categoria
            GROUP BY cl.nombre;");

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
            $u = Conexion::ConexionDB()->prepare("SELECT i.codigo, i.descripcion, 
            SUM(s.cantidad_salida) AS total_vendido
                FROM tblsalidas s
                JOIN tblinventario i ON s.id_producto = i.id
                JOIN tblboleta b ON s.id_boleta = b.id
                WHERE EXTRACT(MONTH FROM b.fecha) = :mes AND EXTRACT(YEAR FROM b.fecha) = :anio
                GROUP BY i.descripcion,i.codigo
                ORDER BY total_vendido DESC
                LIMIT 10;");
            $u->bindParam(":mes", $mes, PDO::PARAM_INT);
            $u->bindParam(":anio", $anio, PDO::PARAM_INT);
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
