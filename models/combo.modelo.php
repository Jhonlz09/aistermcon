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
            $l = Conexion::ConexionDB()->prepare("SELECT id,nombre FROM tblempleado WHERE conductor=true AND estado=true ORDER BY id ASC");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlListarOrden()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT o.id, o.nombre || ' - '|| c.nombre as nombre 
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
            $l = Conexion::ConexionDB()->prepare("		SELECT c.id, c.nombre from tblclientes c JOIN tblorden o ON o.id_cliente = c.id where o.obra_estado = true AND o.estado=true GROUP BY c.id");
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
}
