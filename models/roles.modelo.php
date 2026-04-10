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

                // Paso 3: Relacionar el nuevo perfil con todos los id_modulo existentes (Solo lectura inicial iterando tblaccion 'ver')
                $b = Conexion::ConexionDB()->prepare("INSERT INTO tblperfil_permiso (id_perfil, id_modulo, id_accion)
                                SELECT :id_perfil, id, (SELECT id FROM tblaccion WHERE nombre = 'ver' LIMIT 1)
                                FROM tblmodulo");
                $b->bindParam(":id_perfil", $id_perfil, PDO::PARAM_INT);
                $b->execute();
            }
            return array(
                'status' => 'success',
                'm' => 'El perfil se agregó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar el perfil: ' . $e->getMessage()
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
                'm' => 'El perfil se editó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el perfil debido a que ya existe'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el perfil: ' . $e->getMessage()
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
            // Get permissions
            $l = Conexion::ConexionDB()->prepare("SELECT pm.id_modulo, pm.id_accion, a.nombre AS accion
            FROM tblperfil_permiso pm
            JOIN tblaccion a ON a.id = pm.id_accion
            WHERE pm.id_perfil=:id ORDER BY pm.id_modulo, pm.id_accion");
            $l->bindParam(":id", $id, PDO::PARAM_INT);
            $l->execute();
            $permisos = $l->fetchAll(PDO::FETCH_ASSOC);

            // Get vista_inicio
            $v = Conexion::ConexionDB()->prepare("SELECT vista_inicio FROM tblperfil WHERE id = :id");
            $v->bindParam(":id", $id, PDO::PARAM_INT);
            $v->execute();
            $vista = $v->fetch(PDO::FETCH_ASSOC);

            return [
                "permisos" => $permisos,
                "vista_inicio" => $vista['vista_inicio']
            ];
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    public static function mdlSavePermisos($id, $permisos, $vistaInicioId = null)
    {
        $conexion = Conexion::ConexionDB();

        try {
            $conexion->beginTransaction();

            $vista_inicio_val = empty($vistaInicioId) ? null : $vistaInicioId;
            $upd = $conexion->prepare("UPDATE tblperfil SET vista_inicio = :vista WHERE id = :id");
            $upd->bindParam(":vista", $vista_inicio_val, PDO::PARAM_INT);
            $upd->bindParam(":id", $id, PDO::PARAM_INT);
            $upd->execute();

            $stmtDelete = $conexion->prepare("DELETE FROM tblperfil_permiso WHERE id_perfil = :id");
            $stmtDelete->bindParam(":id", $id, PDO::PARAM_INT);
            $stmtDelete->execute();

            if (!empty($permisos)) {
                $query = "INSERT INTO tblperfil_permiso (id_perfil, id_modulo, id_accion) VALUES (:id_perfil, :id_modulo, :id_accion)";
                $stmtInsert = $conexion->prepare($query);

                foreach ($permisos as $permiso) {
                    $stmtInsert->bindParam(":id_perfil", $id, PDO::PARAM_INT);
                    $stmtInsert->bindParam(":id_modulo", $permiso['id_modulo'], PDO::PARAM_INT);
                    $stmtInsert->bindParam(":id_accion", $permiso['id_accion'], PDO::PARAM_INT);
                    $stmtInsert->execute();
                }
            }

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Se guardaron los permisos del perfil correctamente.'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'Error al guardar: ' . $e->getMessage()
            );
        }
    }


    public static function mdlDeletePermisos($id)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("DELETE FROM tblperfil_permiso WHERE id_perfil = :id");
            $l->bindParam(":id", $id, PDO::PARAM_INT);

            $l->execute();
            return array(
                'status' => 'success',
                'm' => 'Se eliminaron los permisos del perfil correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar los permisos de el perfil: ' . $e->getMessage()
            );
        }
    }

    static public function mdlObtenerModulosConAcciones()
    {
        $sql = "SELECT m.id, m.modulo, m.icon, m.vista, m.id_padre,
            COALESCE(
                json_agg(
                    json_build_object('id', a.id, 'nombre', a.nombre, 'descripcion', a.descripcion)
                    ORDER BY a.id ASC
                ) FILTER (WHERE a.id IS NOT NULL), '[]'
            ) as acciones_permitidas
            FROM public.tblmodulo m
            LEFT JOIN public.tblmodulo_accion ma ON m.id = ma.id_modulo
            LEFT JOIN public.tblaccion a ON ma.id_accion = a.id
            GROUP BY m.id, m.modulo, m.icon, m.vista, m.id_padre
            ORDER BY m.id_padre ASC NULLS FIRST, m.id ASC";

        $stmt = Conexion::ConexionDB()->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    static public function mdlObtenerAcciones()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT id, nombre, descripcion FROM tblaccion ORDER BY id");
            $l->execute();
            return $l->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array();
        }
    }
}
