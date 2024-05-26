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


    static public function mdlEditarConfigCompra($iva)
    {
        try {
            $u = Conexion::ConexionDB()->prepare("UPDATE tblconfiguracion SET iva=:iva WHERE id=1");
            $u->bindParam(":iva", $iva, PDO::PARAM_INT);
            $u->execute();

            $_SESSION["iva"] = $iva;

            return array(
                'status' => 'success',
                'm' => 'La configuración de compra se editó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar la configuracion: ' . $e->getMessage()
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
}
