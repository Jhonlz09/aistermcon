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
            $l = Conexion::ConexionDB()->prepare("SELECT pm.id_modulo, pm.crear, pm.editar, pm.eliminar 
            FROM tblperfil_modulo pm
            JOIN tblmodulo m on m.id = pm.id_modulo
            WHERE id_perfil=:id ORDER BY id_modulo");
            $l->bindParam(":id", $id, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    public static function mdlSavePermisos($id, $permisos)
    {
        $conexion = Conexion::ConexionDB();

        try {
            // 1. Iniciar Transacción (Vital para integridad de datos)
            $conexion->beginTransaction();
            // 2. ELIMINAR permisos anteriores de este perfil
            // Esto limpia la "pizarra" para escribir los nuevos permisos
            $stmtDelete = $conexion->prepare("DELETE FROM tblperfil_modulo WHERE id_perfil = :id");
            $stmtDelete->bindParam(":id", $id, PDO::PARAM_INT);
            $stmtDelete->execute();
            // 3. INSERTAR los nuevos permisos
            $query = "INSERT INTO tblperfil_modulo (id_perfil, id_modulo, crear, editar, eliminar) 
                    VALUES (:id, :modulo, :crear, :editar, :eliminar)";

            $stmtInsert = $conexion->prepare($query);

            foreach ($permisos as $permiso) {
                // Casteo explícito de booleanos a enteros para PostgreSQL (true=1, false=0)
                // PostgreSQL a veces es estricto con booleanos en binding
                $crear = $permiso['crear'] === 'true' || $permiso['crear'] === true ? 'true' : 'false';
                $editar = $permiso['editar'] === 'true' || $permiso['editar'] === true ? 'true' : 'false';
                $eliminar = $permiso['eliminar'] === 'true' || $permiso['eliminar'] === true ? 'true' : 'false';
                $stmtInsert->bindParam(":id", $id, PDO::PARAM_INT);
                $stmtInsert->bindParam(":modulo", $permiso['id_modulo'], PDO::PARAM_INT);
                $stmtInsert->bindParam(":crear", $crear, PDO::PARAM_BOOL);
                $stmtInsert->bindParam(":editar", $editar, PDO::PARAM_BOOL);
                $stmtInsert->bindParam(":eliminar", $eliminar, PDO::PARAM_BOOL);
                $stmtInsert->execute();
            }

            // 4. Confirmar cambios
            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'Se guardaron los permisos del perfil correctamente.'
            );
        } catch (PDOException $e) {
            // Si algo falla, revertimos el borrado inicial
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
            $l = Conexion::ConexionDB()->prepare("DELETE FROM tblperfil_modulo WHERE id_perfil = :id");
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

    static public function mdlObtenerModulos()
    {

        $sql = "SELECT id, modulo, icon, vista, id_padre 
            FROM public.tblmodulo 
            ORDER BY id_padre ASC NULLS FIRST, id ASC";

        $stmt = Conexion::ConexionDB()->prepare($sql);

        $stmt->execute();

        // Retornamos como objetos anónimos (stdClass) para acceder como $row->modulo
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
