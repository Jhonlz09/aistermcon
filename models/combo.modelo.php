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
            $l = Conexion::ConexionDB()->prepare("SELECT id, split_part(nombre, ' ', 1) || ' ' || split_part(apellido, ' ', 1) as nombre FROM tblempleado WHERE id_rol=1 AND estado=true ORDER BY id ASC");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarResponsable()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT id,split_part(nombre, ' ', 1) || ' ' || split_part(apellido, ' ', 1) as nombre FROM tblempleado WHERE id_rol=3 AND estado=true ORDER BY id ASC");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarOrden()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT o.id, o.nombre || ' | '|| c.nombre as nombre 
            FROM tblorden o
            JOIN tblclientes c ON c.id = o.id_cliente
            WHERE o.estado =true ORDER BY o.id DESC");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarClientesActivos()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT c.id, c.nombre from tblclientes c JOIN tblorden o ON o.id_cliente = c.id where o.obra_estado = true AND o.estado=true GROUP BY c.id");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarOrdenActivas()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT o.id, o.nombre from tblorden o where o.obra_estado = true");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarProductosFab()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT i.id, o.nombre || ' | '|| i.stock::double precision || ' '|| u.nombre || ' | '||i.descripcion AS nombre,
            o.id, i.stock::double precision , u.id, i.descripcion
            from tblinventario i 
            JOIN tblunidad u on u.id = i.id_unidad
            JOIN tblorden o ON o.id = i.id_orden
            JOIN tblclientes c ON c.id = o.id_cliente
            where i.fabricado = true AND stock != 0 AND i.estado=true");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarProductosFabCon()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT i.id, o.nombre || ' '|| c.nombre || ' | '||i.descripcion AS nombre
            from tblinventario i 
            JOIN tblunidad u on u.id = i.id_unidad
            JOIN tblorden o ON o.id = i.id_orden
            JOIN tblclientes c ON c.id = o.id_cliente
            where i.fabricado = true AND i.estado=true");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }
}
