<?php
session_start();
require_once "utils/database/conexion.php";
class PermisosModelo
{
    static public function mdlObtenerPermisos($id)
    {
        $stmt = Conexion::ConexionDB()->prepare("SELECT m.id,m.modulo,m.icon,m.vista, pm.crear, pm.editar,pm.eliminar
            FROM tblusuario u JOIN tblperfil p on u.id_perfil = p.id
            JOIN tblperfil_modulo pm on pm.id_perfil = p.id
            JOIN tblmodulo m on m.id = pm.id_modulo
            WHERE u.id = :id AND m.id_padre is null
            ORDER BY m.id");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function mdlObtenerPermisoSubmenu($id_usuario, $modulo)
    {
        $stmt = Conexion::ConexionDB()->prepare("SELECT m.id,m.modulo,m.icon,m.vista, 
            pm.crear, pm.editar,pm.eliminar
            FROM tblusuario u  
            JOIN tblperfil p on u.id_perfil = p.id
            JOIN tblperfil_modulo pm on pm.id_perfil = p.id
            JOIN tblmodulo m on m.id = pm.id_modulo
            WHERE u.id = :id and m.id_padre = :modulo
            ORDER BY m.id");
        $stmt->bindParam(":id", $id_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":modulo", $modulo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function mdlObtenerNav($id)
    {
        $stmt = Conexion::ConexionDB()->prepare("SELECT guia, ppt FROM tblperfil p
        WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function mdlObtenerConfiguracion()
    {
        $stmt = Conexion::ConexionDB()->prepare("SELECT empresa, iva, emisor, ruc, matriz, correo1, 
        correo2, telefonos, entradamultiple AS entrada, 
        (SELECT last_value FROM secuencia_orden) + 1 AS secuencia_orden FROM tblconfiguracion");
        // $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
}
