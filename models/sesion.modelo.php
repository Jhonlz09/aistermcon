<?php
session_start(); // Inicializar la sesión
require_once "../utils/database/conexion.php";
class SesionModelo
{
    public static function mdlIniciarSesion($usuario, $password)
    {
        $stmt = Conexion::ConexionDB()->prepare("SELECT u.id,u.nombre_usuario,u.clave_usuario,u.id_perfil,m.vista FROM tblusuario u 
            JOIN tblperfil_modulo pm ON pm.id_perfil = u.id_perfil
            JOIN tblmodulo m ON m.id = pm.id_modulo  
            WHERE nombre_usuario=:usuario AND u.estado=true order by m.id");
        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_CLASS);
        if ($user) {
            if (password_verify($password, $user[0]->clave_usuario)) {
                $_SESSION["s_usuario"] = $user[0];
                return "success";
            } else {
                return "invalid_password";
            }
        } else {
            return "invalid_username";
        }
    }
}
