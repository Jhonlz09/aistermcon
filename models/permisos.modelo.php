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
            WHERE u.id = :id
            ORDER BY m.id");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    // static public function mdlObtenerPermisos($id){
    //     $stmt = Conexion::ConexionDB()->prepare("SELECT p.crear,p.editar,p.eliminar
    //     FROM tblusuario u JOIN tblperfil p on u.id_perfil = p.id
    //     WHERE u.id = :id");
    //     $stmt->bindParam(":id", $id, PDO::PARAM_STR);
    //     $stmt->execute();
    //     return $stmt-> fetchAll(PDO::FETCH_CLASS);
    // }
}
