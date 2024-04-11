<?php
require_once "../../utils/database/conexion.php";

if($_POST['action']== 'edit'){
    $data = array(
        'codigo' => $_POST['codigo'],
        'cantidad' => $_POST['cantidad_salida'],
        'id' => $_POST['id'],

    );

    $stmt = Conexion::ConexionDB()->prepare("UPDATE tblsalidas SET id_producto=(SELECT id from tblinventario where codigo=:codigo), cantidad_salida=:cantidad WHERE id=:id");
    $stmt->bindParam(":codigo", $data['codigo'], PDO::PARAM_STR);
    $stmt->bindParam(":cantidad", $data['cantidad'], PDO::PARAM_STR);
    $stmt->bindParam(":id", $data['id'], PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode($_POST);
}

else if($_POST['action']== 'delete'){
    $data = array(
        'id' => $_POST['id'],
    );

    $stmt = Conexion::ConexionDB()->prepare("DELETE FROM tblsalidas WHERE id=:id");
    $stmt->bindParam(":id", $data['id'], PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode($_POST);
}
