<?php
require_once "../utils/database/conexion.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ModeloUsuarios
{
    static public function mdlListarUsuarios()
    {
        try {
            $id_perfil = $_SESSION["s_usuario"]->id_perfil;
            $consulta = "SELECT u.id, u.nombres, u.nombre_usuario, p.nombre, '' as acciones, u.id_perfil
            FROM tblusuario u 
            JOIN tblperfil p on p.id= u.id_perfil
            WHERE u.estado=true ";
            if ($id_perfil != 1) {
                $consulta .= "AND u.id != 1 ";
            }
            $consulta .= "ORDER BY u.id";
            $l = Conexion::ConexionDB()->prepare($consulta);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarUsuarios($nombres, $usuario, $pass, $id_perfil)
    {
        try {
            $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);
            $a = Conexion::ConexionDB()->prepare("INSERT INTO tblusuario(nombres,nombre_usuario,clave_usuario,id_perfil) VALUES (:nombres,:usuario,:pass,:id_perfil)");
            $a->bindParam(":nombres", $nombres, PDO::PARAM_STR);
            $a->bindParam(":usuario", $usuario, PDO::PARAM_STR);
            $a->bindParam(":pass", $hashedPassword, PDO::PARAM_STR);
            $a->bindParam(":id_perfil", $id_perfil, PDO::PARAM_INT);
            $a->execute();
            return array(
                'status' => 'success',
                'm' => 'El usuario se agregó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar el usuario debido a que ya existe ese usuario'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar el usuario: ' . $e->getMessage()
                );
            }
        }
    }

    static public function mdlEditarUsuario($id, $nombres, $usuario, $id_perfil)
    {
        try {
            $id_rol = $_SESSION["s_usuario"]->id_perfil;
            if($id_rol == 1 && $id_perfil == ''){
                $id_perfil = 1;
            }
            $u = Conexion::ConexionDB()->prepare("UPDATE tblusuario SET nombres=:nombres, nombre_usuario=:usuario, id_perfil=:id_perfil WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":nombres", $nombres, PDO::PARAM_STR);
            $u->bindParam(":usuario", $usuario, PDO::PARAM_STR);
            $u->bindParam(":id_perfil", $id_perfil, PDO::PARAM_INT);
            $u->execute();

            if ($id == $_SESSION["s_usuario"]->id) {
                // Actualizar las variables de sesión
                $_SESSION["s_usuario"]->nombres = $nombres;
                $_SESSION["s_usuario"]->nombre_usuario = $usuario;
                $_SESSION["s_usuario"]->id_perfil = $id_perfil;
                // Puedes agregar más variables de sesión si es necesario
            }
            
            return array(
                'status' => 'success',
                'm' => 'El usuario se editó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el usuario debido a que ya existe ese usuario'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el usuario: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlEliminarUsuario($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblusuario SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó el usuario con éxito.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar el usuario: ' . $e->getMessage()
            );
        }
    }

    static public function mdlEditarClave($id, $pass)
    {
        try {
            $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);
            $p = Conexion::ConexionDB()->prepare("UPDATE tblusuario SET clave_usuario=:pass WHERE id=:id");
            $p->bindParam(":id", $id, PDO::PARAM_INT);
            $p->bindParam(":pass", $hashedPassword, PDO::PARAM_STR);
            $p->execute();
            return array(
                'status' => 'success',
                'm' => 'La contraseña se restableció correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo restablecer la contraseña: ' . $e->getMessage()
            );
        }
    }
}
