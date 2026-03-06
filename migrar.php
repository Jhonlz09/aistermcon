<?php
require_once "utils/database/conexion.php";
try {
    $db = Conexion::ConexionDB();
    $db->exec("ALTER TABLE tblboleta ADD COLUMN id_solicitud_despacho integer NULL;");
    $db->exec("ALTER TABLE tblboleta ADD COLUMN is_material boolean DEFAULT false;");
    echo "SUCCESS";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
