<?php

require_once "../utils/database/conexion.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class ModeloConfiguracion
{
    static public function mdlEditarConfigDatos($empresa)
    {
        try {

            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("UPDATE tblconfiguracion SET empresa=:nombre WHERE id=1");
            $a->bindParam(":nombre", $empresa, PDO::PARAM_STR);
            $a->execute();

            $_SESSION["empresa"] = $empresa;


            return array(
                'status' => 'success',
                'm' => 'La configuración de datos generales se editó correctamente'
            );
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    static public function mdlEditarConfigMov($isentrada)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("UPDATE tblconfiguracion SET entradamultiple=:isentrada WHERE id=1");
            $a->bindParam(":isentrada", $isentrada, PDO::PARAM_BOOL);
            $a->execute();

            $_SESSION["entrada_mul"] = $isentrada ? 1 : 0;

            return array(
                'status' => 'success',
                'm' => 'La configuración de movimientos se editó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar la configuracion: ' . $e->getMessage()
            );
        }
    }


    static public function mdlEditarConfigCompra($iva, $sc)
    {
        try {
            // Conexión a la base de datos
            $db = Conexion::ConexionDB();

            // Iniciar transacción
            $db->beginTransaction();

            // Actualizar el IVA
            $u = $db->prepare("UPDATE tblconfiguracion SET iva = :iva WHERE id = 1");
            $u->bindParam(":iva", $iva, PDO::PARAM_INT);
            $u->execute();

            // Actualizar la secuencia con ALTER SEQUENCE (construcción dinámica)
            $query = "SELECT setval('secuencia_cotizacion', $sc, true);";
            $db->exec($query);

            // Confirmar transacción
            $db->commit();

            // Almacenar valores en variables de sesión
            $_SESSION["iva"] = $iva;
            $_SESSION["sc_cot"] = $sc;

            return array(
                'status' => 'success',
                'm' => 'La configuración de compra y la secuencia se editaron correctamente',
                'iva' => $_SESSION["iva"],
                'sc' => $_SESSION["sc_cot"]
            );
        } catch (PDOException $e) {
            // Revertir transacción en caso de error
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar la configuración: ' . $e->getMessage()
            );
        }
    }



    public static function mdlEditarConfigGuia($ruc, $emisor, $dir, $tel, $correo1)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblconfiguracion SET ruc=:ruc,
            emisor=:emisor,matriz=:dir,telefonos=:tel,correo1=:correo1 WHERE id=1");
            $e->bindParam(":emisor", $emisor, PDO::PARAM_STR);
            $e->bindParam(":ruc", $ruc, PDO::PARAM_STR);
            $e->bindParam(":tel", $tel, PDO::PARAM_STR);
            $e->bindParam(":dir", $dir, PDO::PARAM_STR);
            $e->bindParam(":correo1", $correo1, PDO::PARAM_STR);
            $e->execute();

            $_SESSION["emisor"] = $emisor;
            $_SESSION["ruc"] = $ruc;
            $_SESSION["matriz"] = $dir;
            $_SESSION["correo1"] = $correo1;
            $_SESSION["telefono"] = $tel;

            return array(
                'status' => 'success',
                'm' => 'La configuración de guía se editó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar la configuracion: ' . $e->getMessage()
            );
        }
    }

    public static function mdlEditarConfigPref($id_bodeguero, $id_conductor)
    {
        try {
            $conexion = Conexion::ConexionDB();

            // Actualiza los valores de id_bodeguero y id_conductor en la tabla tblconfiguracion
            $sql = "UPDATE tblconfiguracion SET bodeguero = :id_bodeguero, conductor = :id_conductor";
            $e = $conexion->prepare($sql);
            $e->bindParam(':id_bodeguero', $id_bodeguero);
            $e->bindParam(':id_conductor', $id_conductor);
            $e->execute();

            // Actualiza las sesiones con los nuevos valores
            $_SESSION["bodeguero"] = $id_bodeguero;
            $_SESSION["conductor"] = $id_conductor;

            return array(
                'status' => 'success',
                'm' => 'La configuración de preferencias se editó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar la configuracion: ' . $e->getMessage()
            );
        }
    }
}
