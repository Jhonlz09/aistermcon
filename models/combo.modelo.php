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
            $l = Conexion::ConexionDB()->prepare("SELECT id,split_part(nombre, ' ', 1) || ' ' || split_part(apellido, ' ', 1) as nombre FROM tblempleado WHERE id_empresa=1 AND estado=true ORDER BY nombre ASC");
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
            $l = Conexion::ConexionDB()->prepare("SELECT c.id, c.nombre FROM tblclientes c JOIN tblorden o ON o.id_cliente = c.id WHERE o.obra_estado = true AND o.estado=true GROUP BY c.id");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    // static public function mdlListarOrdenActivas()
    // {
    //     try {
    //         $l = Conexion::ConexionDB()->prepare("SELECT o.id, o.nombre from tblorden o where o.obra_estado = true");
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
}
