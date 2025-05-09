<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../utils/database/conexion.php';


class SesionModelo
{
    public static function mdlIniciarSesion($usuario, $password)
    {
        $stmt = Conexion::ConexionDB()->prepare("SELECT u.id, u.nombres, u.nombre_usuario, 
                            u.clave_usuario,p.nombre as perfil, u.id_perfil, m.vista FROM tblusuario u 
		                        JOIN tblperfil p ON p.id = u.id_perfil
                                JOIN tblperfil_modulo pm ON pm.id_perfil = u.id_perfil
                                JOIN tblmodulo m ON m.id = pm.id_modulo  
                            WHERE nombre_usuario = :usuario AND u.estado = true 
                            ORDER BY m.id LIMIT 1;");
        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_CLASS);
        if ($user) {
            if (password_verify($password, $user[0]->clave_usuario)) {
                $_SESSION["s_usuario"] = $user[0];
                $_SESSION['last_activity'] = time();
                $permisosUsuario = self::mdlObtenerPermisos($user[0]->id);
                $_SESSION["permisosUsuario"] = $permisosUsuario;
                foreach ($permisosUsuario as $permiso) {
                    $_SESSION["crear" . $permiso->id] = $permiso->crear;
                    $_SESSION["editar" . $permiso->id] = $permiso->editar;
                    $_SESSION["eliminar" . $permiso->id] = $permiso->eliminar;
                }
                $configuracion = self::mdlObtenerConfiguracion();
                $_SESSION["empresa"] = $configuracion[0]->empresa;
                $_SESSION["iva"] = $configuracion[0]->iva;
                $_SESSION["sbu"] = $configuracion[0]->sbu;
                $_SESSION["emisor"] = $configuracion[0]->emisor;
                $_SESSION["ruc"] = $configuracion[0]->ruc;
                $_SESSION["matriz"] = $configuracion[0]->matriz;
                $_SESSION["correo1"] = $configuracion[0]->correo1;
                $_SESSION["correo2"] = $configuracion[0]->correo2;
                $_SESSION["telefono"] = $configuracion[0]->telefonos;
                $_SESSION["entrada_mul"] = $configuracion[0]->entrada;
                $_SESSION["bodeguero"] = $configuracion[0]->bodeguero;
                $_SESSION["conductor"] = $configuracion[0]->conductor;
                $_SESSION["sc_cot"] = $configuracion[0]->sc_cot;
                return "success";
            } else {
                return "invalid_password";
            }
        } else {
            return "invalid_username";
        }
    }

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

    static public function mdlObtenerConfiguracion()
    {
        $stmt = Conexion::ConexionDB()->prepare("SELECT 
                        empresa, 
                        iva, 
                        emisor, 
                        ruc, 
                        matriz, 
                        correo1,  
                        correo2, 
                        telefonos, 
                        entradamultiple AS entrada, 
                        bodeguero, 
                        conductor, 
                        sbu,
                (SELECT last_value + increment_by FROM pg_sequences 
                    WHERE schemaname = 'public' AND sequencename = 'secuencia_cotizacion') AS sc_cot 
                    FROM tblconfiguracion;");
        // $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
}
