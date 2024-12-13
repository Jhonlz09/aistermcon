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
            $query = "SELECT setval('secuencia_cotizacion', $sc, false);";
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

    public static function mdlEditarConfigOpe($correosJson)
    {
        try {
            $conexion = Conexion::ConexionDB();

            $correos = json_decode($correosJson);

            if (!is_array($correos)) {
                throw new Exception('Los correos deben ser un array');
            }
            $correosEscapados = array_map(function ($correo) {
                return "'" . addslashes($correo) . "'"; // Escapa las comillas en los correos
            }, $correos);

            // Crear el formato adecuado para PostgreSQL (un array de texto)
            $correos = '{' . implode(',', $correosEscapados) . '}';

            $sql = "UPDATE tblconfiguracion SET correo_destinatario = :correo";
            $e = $conexion->prepare($sql);
            $e->bindParam(':correo', $correos);
            $e->execute();

            return array(
                'status' => 'success',
                'm' => 'La configuración de operaciones se editó correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar la configuracion: ' . $e->getMessage()
            );
        }
    }

    public static function mdlObtenerCorreos()
    {
        try {
            $conexion = Conexion::ConexionDB();
            $sql = "SELECT correo_destinatario FROM tblconfiguracion LIMIT 1"; // Ajusta según tu consulta
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                // Extrae el valor de la cadena y elimina las llaves
                $correos = $result['correo_destinatario'];
                $correos = trim($correos, '{}'); // Elimina las llaves al principio y al final
                $correos = explode(',', $correos); // Convierte la cadena a un array usando la coma como delimitador
    
                // Limpia los espacios y las comillas alrededor de cada correo
                $correos = array_map(function($correo) {
                    return trim($correo, " '");
                }, $correos);
    
                return $correos; // Devuelve el array de correos
            }
    
            return [];
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'Error al obtener correos: ' . $e->getMessage()
            );
        }
    }
}
