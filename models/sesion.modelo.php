<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../utils/database/conexion.php';


class SesionModelo
{
    public static function mdlIniciarSesion($usuario, $password)
    {
        // 1. Obtener SOLO los datos del usuario (NO módulos aquí)
        $stmt = Conexion::ConexionDB()->prepare("SELECT u.id, u.nombres, u.nombre_usuario, u.clave_usuario,p.nombre AS perfil, u.id_perfil
        FROM tblusuario u
        JOIN tblperfil p ON p.id = u.id_perfil
        WHERE u.nombre_usuario = :usuario
            AND u.estado = true
        LIMIT 1");

        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user) return "invalid_username";

        // 2. Validar clave
        if (!password_verify($password, $user->clave_usuario)) {
            return "invalid_password";
        }

        // 3. Guardar sesión básica
        $_SESSION["s_usuario"] = $user;
        $_SESSION['last_activity'] = time();

        // 4. Cargar permisos completos (módulos + CRUD)
        $permisosUsuario = self::mdlObtenerPermisos($user->id);

        // 5. Construir árbol del menú (recursivo)
        $_SESSION["menuTree"] = self::buildTree($permisosUsuario);

        // 6. Guardar los permisos CRUD individuales como antes:
        foreach ($permisosUsuario as $p) {
            $_SESSION["crear{$p->id}"]    = (bool)$p->crear;
            $_SESSION["editar{$p->id}"]   = (bool)$p->editar;
            $_SESSION["eliminar{$p->id}"] = (bool)$p->eliminar;
        }

        // 7. Configuración general
        $config = self::mdlObtenerConfiguracion()[0];

        $_SESSION["empresa"]     = $config->empresa;
        $_SESSION["iva"]         = $config->iva;
        $_SESSION["sbu"]         = $config->sbu;
        $_SESSION["emisor"]      = $config->emisor;
        $_SESSION["ruc"]         = $config->ruc;
        $_SESSION["matriz"]      = $config->matriz;
        $_SESSION["correo1"]     = $config->correo1;
        $_SESSION["correo2"]     = $config->correo2;
        $_SESSION["telefono"]    = $config->telefonos;
        $_SESSION["entrada_mul"] = $config->entrada;
        $_SESSION["bodeguero"]   = $config->bodeguero;
        $_SESSION["conductor"]   = $config->conductor;
        $_SESSION["sc_cot"]      = $config->sc_cot;

        return "success";
    }


    static private function normalizeParent($val)
    {
        // normaliza a null para raíces, o int para padres válidos
        if ($val === null) return null;
        if (is_string($val)) {
            $v = trim($val);
            if ($v === '' || strtoupper($v) === 'NULL') return null;
            if (ctype_digit($v)) return (int)$v;
        }
        if (is_numeric($val)) return (int)$val;
        return null;
    }

    static private function buildTree($modulos, $padre = null)
    {
        // normalizar el padre de entrada
        $padre = self::normalizeParent($padre);
        $menu = [];

        // Pré-process: convertir propiedades para evitar problemas de tipo
        foreach ($modulos as $m) {
            // asegurar que id e id_padre existan como propiedades
            if (!isset($m->id)) continue;

            $m->id = (int)$m->id;
            $m->id_padre = self::normalizeParent(isset($m->id_padre) ? $m->id_padre : null);

            // normalizar booleans (si vienen 'True'/'False' o '1'/'0')
            foreach (['crear', 'editar', 'eliminar'] as $b) {
                if (isset($m->$b)) {
                    if (is_string($m->$b)) {
                        $up = strtoupper($m->$b);
                        $m->$b = ($up === 'TRUE' || $up === '1' || $up === 't') ? true : false;
                    } else {
                        $m->$b = (bool)$m->$b;
                    }
                } else {
                    $m->$b = false;
                }
            }
        }

        foreach ($modulos as $m) {
            if ($m->id_padre === $padre) {
                $hijos = self::buildTree($modulos, $m->id);
                if ($hijos) $m->children = $hijos;
                $menu[] = $m;
            }
        }

        return $menu;
    }

    static public function mdlObtenerPermisos($id)
    {
        $stmt = Conexion::ConexionDB()->prepare("SELECT m.id, m.modulo, m.icon, m.vista, m.id_padre,
        pm.crear, pm.editar, pm.eliminar
        FROM tblusuario u
        JOIN tblperfil p ON p.id = u.id_perfil
        JOIN tblperfil_modulo pm ON pm.id_perfil = p.id
        JOIN tblmodulo m ON m.id = pm.id_modulo
        WHERE u.id = :id
        ORDER BY m.id_padre, m.id
    ");

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function mdlObtenerConfiguracion()
    {
        $stmt = Conexion::ConexionDB()->prepare("SELECT empresa, iva, emisor, ruc, matriz, correo1,  correo2, telefonos, entradamultiple AS entrada, bodeguero, conductor, sbu,
                (SELECT last_value + increment_by FROM pg_sequences 
                    WHERE schemaname = 'public' AND sequencename = 'secuencia_cotizacion') AS sc_cot 
                    FROM tblconfiguracion;");
        // $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
}
