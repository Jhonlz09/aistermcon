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
        $stmt = Conexion::ConexionDB()->prepare("SELECT u.id, u.nombres, u.nombre_usuario, u.clave_usuario, p.nombre AS perfil, u.id_perfil, p.vista_inicio, m.vista AS ruta_inicio
        FROM tblusuario u
        JOIN tblperfil p ON p.id = u.id_perfil
        LEFT JOIN tblmodulo m ON p.vista_inicio = m.id
        WHERE u.nombre_usuario = :usuario
            AND u.estado = true
        LIMIT 1");

        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user)
            return "invalid_username";

        // 2. Validar clave
        if (!password_verify($password, $user->clave_usuario)) {
            return "invalid_password";
        }

        // 3. Guardar sesión básica
        $_SESSION["s_usuario"] = $user;
        $_SESSION['last_activity'] = time();

        // 4. Cargar permisos completos (módulos)
        $permisosUsuario = self::mdlObtenerPermisos($user->id);

        $vistaInicio = 'inicio';
        if ($user->ruta_inicio) {
            $vistaInicio = rtrim(str_replace('.php', '', $user->ruta_inicio), '/');
        }
        $_SESSION["vista_inicio"] = $vistaInicio;

        // 5. Construir árbol del menú (recursivo)
        $_SESSION["menuTree"] = self::buildTree($permisosUsuario);

        // 6. Guardar los permisos de matriz dinamica
        $stmtAcciones = Conexion::ConexionDB()->prepare("SELECT pp.id_modulo, a.nombre as accion
            FROM tblperfil_permiso pp
            JOIN tblaccion a ON a.id = pp.id_accion
            WHERE pp.id_perfil = :id_perfil");
        $stmtAcciones->bindParam(":id_perfil", $user->id_perfil);
        $stmtAcciones->execute();
        $accionesRow = $stmtAcciones->fetchAll(PDO::FETCH_OBJ);

        foreach ($accionesRow as $p) {
            $_SESSION[$p->accion . $p->id_modulo] = true;
        }

        // 7. Configuración general
        $config = self::mdlObtenerConfiguracion()[0];

        $_SESSION["empresa"] = $config->empresa;
        $_SESSION["iva"] = $config->iva;
        $_SESSION["sbu"] = $config->sbu;
        $_SESSION["emisor"] = $config->emisor;
        $_SESSION["ruc"] = $config->ruc;
        $_SESSION["matriz"] = $config->matriz;
        $_SESSION["correo1"] = $config->correo1;
        $_SESSION["correo2"] = $config->correo2;
        $_SESSION["telefono"] = $config->telefonos;
        $_SESSION["entrada_mul"] = $config->entrada;
        $_SESSION["bodeguero"] = $config->bodeguero;
        $_SESSION["autorizado"] = $config->autorizado;
        $_SESSION["conductor"] = $config->conductor;
        $_SESSION["sc_cot"] = $config->sc_cot;
        $_SESSION["sc_desp"] = $config->sc_desp;


        return "success";
    }


    static private function buildTree(array $elements, $parentId = 0)
    {
        $branch = array();

        // Aseguramos que parentId sea entero para comparaciones estrictas
        // (PostgreSQL puede devolver null, strings o ints)
        $parentId = ($parentId === null || strtoupper((string) $parentId) === 'NULL') ? 0 : (int) $parentId;

        foreach ($elements as $element) {
            // Normalización al vuelo (más rápido que pre-procesar todo el array antes)
            // Convertimos el id_padre actual a 0 si es null/vacío
            $elementParent = ($element->id_padre === null || strtoupper((string) $element->id_padre) === 'NULL') ? 0 : (int) $element->id_padre;

            if ($elementParent === $parentId) {
                $children = self::buildTree($elements, $element->id);

                if ($children) {
                    $element->children = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }

    static public function mdlObtenerPermisos($id)
    {
        $stmt = Conexion::ConexionDB()->prepare("WITH RECURSIVE arbol_modulos AS (
            -- 1. CASO BASE: Obtener los módulos asignados explícitamente al usuario
            SELECT 
                m.id, 
                m.modulo, 
                m.icon, 
                m.vista, 
                m.id_padre
            FROM tblusuario u
            JOIN tblperfil_permiso pm ON pm.id_perfil = u.id_perfil
            JOIN tblaccion a ON a.id = pm.id_accion
            JOIN tblmodulo m ON m.id = pm.id_modulo
            WHERE u.id = :id AND LOWER(a.nombre) = 'ver'

            UNION

            SELECT 
                padre.id,
                padre.modulo, 
                padre.icon, 
                padre.vista, 
                padre.id_padre
            FROM tblmodulo padre
            INNER JOIN arbol_modulos hijo ON hijo.id_padre = padre.id
        )
        -- 3. SELECCIÓN FINAL: Usamos DISTINCT ON para evitar duplicados
        -- (Si un padre fue asignado explícitamente y también encontrado recursivamente,
        --  nos quedamos con el primero que suele ser el explícito por el orden del UNION)
        SELECT DISTINCT ON (id) * FROM arbol_modulos 
        ORDER BY id, id_padre ASC NULLS FIRST");

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    static public function mdlObtenerConfiguracion()
    {
        $stmt = Conexion::ConexionDB()->prepare("SELECT empresa, iva, emisor, ruc, matriz, correo1,  correo2, telefonos, entradamultiple AS entrada, bodeguero, conductor, sbu, autorizado,
                (SELECT last_value + increment_by FROM pg_sequences 
                    WHERE schemaname = 'public' AND sequencename = 'secuencia_cotizacion') AS sc_cot ,
                (SELECT last_value + increment_by FROM pg_sequences 
                    WHERE schemaname = 'public' AND sequencename = 'secuencia_despacho') AS sc_desp 
                    FROM tblconfiguracion");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
}
