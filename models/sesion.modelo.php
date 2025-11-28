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


    static private function buildTree(array $elements, $parentId = 0) 
{
    $branch = array();

    // Aseguramos que parentId sea entero para comparaciones estrictas
    // (PostgreSQL puede devolver null, strings o ints)
    $parentId = ($parentId === null || strtoupper((string)$parentId) === 'NULL') ? 0 : (int)$parentId;

    foreach ($elements as $element) {
        // Normalización al vuelo (más rápido que pre-procesar todo el array antes)
        // Convertimos el id_padre actual a 0 si es null/vacío
        $elementParent = ($element->id_padre === null || strtoupper((string)$element->id_padre) === 'NULL') ? 0 : (int)$element->id_padre;
        
        if ($elementParent === $parentId) {
            $children = self::buildTree($elements, $element->id);
            
            if ($children) {
                $element->children = $children;
            }
            
            // Normalización de booleanos para el frontend (si es necesario)
            $element->crear = filter_var($element->crear, FILTER_VALIDATE_BOOLEAN);
            $element->editar = filter_var($element->editar, FILTER_VALIDATE_BOOLEAN);
            $element->eliminar = filter_var($element->eliminar, FILTER_VALIDATE_BOOLEAN);

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
                m.id_padre,
                pm.crear,
                pm.editar,
                pm.eliminar
            FROM tblusuario u
            JOIN tblperfil_modulo pm ON pm.id_perfil = u.id_perfil
            JOIN tblmodulo m ON m.id = pm.id_modulo
            WHERE u.id = :id

            UNION

            -- 2. RECURSIVIDAD: Buscar los padres de los módulos encontrados
            -- (Aunque no tengan permisos explícitos en tblperfil_modulo)
            SELECT 
                padre.id, 
                padre.modulo, 
                padre.icon, 
                padre.vista, 
                padre.id_padre,
                false AS crear,    -- Los padres autogenerados no tienen CRUD activo
                false AS editar,
                false AS eliminar
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
