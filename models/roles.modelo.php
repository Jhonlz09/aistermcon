<?php

require_once "../utils/database/conexion.php";

class ModeloRoles
{
    static public function mdlListarRoles()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT id, nombre, '' as acciones FROM tblperfil WHERE estado=true ORDER BY id");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlAgregarRoles($nombre)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblperfil(nombre) VALUES (:nombre)");
            $a->bindParam(":nombre", $nombre, PDO::PARAM_STR);

            if ($a->execute()) {
                // Paso 2: Obtener el id_perfil generado automáticamente
                $id_perfil = $conexion->lastInsertId();

                // Paso 3: Relacionar el nuevo perfil con todos los id_modulo existentes
                $b = Conexion::ConexionDB()->prepare("INSERT INTO tblperfil_modulo (id_perfil, id_modulo)
                                SELECT :id_perfil, id
                                FROM tblmodulo");
                $b->bindParam(":id_perfil", $id_perfil, PDO::PARAM_INT);
                $b->execute();
            }



            return array(
                'status' => 'success',
                'm' => 'El rol se agregó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar el rol: ' . $e->getMessage()
            );
        }
    }


    static public function mdlEditarRol($id, $nombres)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblperfil SET nombre=:nombres WHERE id=:id");
            $u->bindParam(":id", $id, PDO::PARAM_INT);
            $u->bindParam(":nombres", $nombres, PDO::PARAM_STR);
            $u->execute();
            return array(
                'status' => 'success',
                'm' => 'El rol se editó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el rol debido a que ya existe un rol con la misma cedula'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el rol: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlEliminarRol($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblperfil SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminó el rol con éxito.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar el rol: ' . $e->getMessage()
            );
        }
    }

    public static function mdlgetPermisos($id)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT id_modulo, crear, editar, eliminar FROM tblperfil_modulo WHERE id_perfil=:id ORDER BY id_modulo");
            $l->bindParam(":id", $id, PDO::PARAM_INT);

            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    public static function mdlSavePermisos($id, $modulo, $crear, $editar, $eliminar)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("INSERT INTO tblperfil_modulo(id_perfil, id_modulo, crear, editar, eliminar)
            VALUES (:id,:modulo,:crear,:editar,:eliminar)");
            $l->bindParam(":id", $id, PDO::PARAM_INT);
            $l->bindParam(":modulo", $modulo, PDO::PARAM_INT);
            $l->bindParam(":crear", $crear, PDO::PARAM_BOOL);
            $l->bindParam(":editar", $editar, PDO::PARAM_BOOL);
            $l->bindParam(":eliminar", $eliminar, PDO::PARAM_BOOL);
            $l->execute();
            return array(
                'status' => 'success',
                'm' => 'Se guardo los permisos del rol con éxito.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo guardar los permisos de el rol: ' . $e->getMessage()
            );        }
    }

    public static function mdlDeletePermisos($id)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("DELETE FROM tblperfil_modulo WHERE id_perfil = :id");
            $l->bindParam(":id", $id, PDO::PARAM_INT);

            $l->execute();
            return array(
                'status' => 'success',
                'm' => 'Se editó los permisos del rol con éxito.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar los permisos de el rol: ' . $e->getMessage()
            );
        }
    }
}
