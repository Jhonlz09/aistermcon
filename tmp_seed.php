<?php
require_once "utils/database/conexion.php";

try {
    $conexion = Conexion::ConexionDB();
    if (!$conexion) {
        die("No connection\n");
    }

    // Checking if there is any data in tblmodulo_accion
    $res = $conexion->query("SELECT COUNT(*) FROM tblmodulo_accion");
    $count = $res->fetchColumn();

    if ($count == 0) {
        echo "Poblando tblmodulo_accion...\n";
        $conexion->exec("
            INSERT INTO tblmodulo_accion (id_modulo, id_accion)
            SELECT m.id, a.id
            FROM tblmodulo m
            CROSS JOIN tblaccion a
            ON CONFLICT DO NOTHING;
        ");
        echo "Tabla poblada correctamente con todas las combinaciones iniciales (fallback).\n";
    } else {
        echo "La tabla tblmodulo_accion ya tiene $count registros. No se hizo nada.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
