<?php
require_once "../../utils/database/conexion.php";


if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $idAndFactura = $_POST['id'];
    $idAndIdArray = explode(',', $idAndFactura);

    $id_entrada = $idAndIdArray[0]; // Esto será 'edit'
    $id_boleta = $idAndIdArray[1];
}

if($action == 'edit'){
    $data = array(
        'codigo' => $_POST['codigo'],
        'cantidad' => $_POST['cantidad_entrada'],
        'precio' => $_POST['precio'],
        'id' => $id_entrada,
        'id_factura' => $id_boleta
    );

     // Conexión a la base de datos
    $conn = Conexion::ConexionDB();

    try {
         // Obtener el id_producto a partir del código
        $stmt_get_product_id = $conn->prepare("SELECT id FROM tblinventario WHERE codigo = :codigo");
        $stmt_get_product_id->bindParam(":codigo", $data['codigo'], PDO::PARAM_STR);
        $stmt_get_product_id->execute();
        $id_producto = $stmt_get_product_id->fetchColumn();

        if ($id_producto === false) {
            echo json_encode(array(
                'status' => 'danger',
                'm' => 'El código del producto no existe.'
            ));
            exit;
        }

        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM tblentradas WHERE id_producto = :id_producto AND id_factura = :id_factura AND id <> :id");
        $stmt_check->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt_check->bindParam(":id_factura", $data['id_factura'], PDO::PARAM_INT);
        $stmt_check->bindParam(":id", $data['id'], PDO::PARAM_INT);

        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            // El id_producto ya está relacionado con el id_boleta, no realizar la actualización
            echo json_encode(array(
                'status' => 'danger',
                'm' => 'El producto ya existe en la factura.'
            ));
        }else{
            $precio = str_replace('.', ',', $data['precio']);

            $stmt_update = $conn->prepare("UPDATE tblentradas SET id_producto = :id_producto, cantidad_entrada = :cantidad, precio_uni= :precio, id_factura = :id_factura WHERE id = :id");
            $stmt_update->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt_update->bindParam(":cantidad", $data['cantidad'], PDO::PARAM_STR);
            $stmt_update->bindParam(":precio", $precio, PDO::PARAM_STR);
            $stmt_update->bindParam(":id_factura", $data['id_factura'], PDO::PARAM_INT);
            $stmt_update->bindParam(":id", $data['id'], PDO::PARAM_INT);
            $stmt_update->execute();

            echo json_encode(array(
                'status' => 'success',
                'm' => 'Se ha actualizado el producto correctamente.'
            ));
        }

}catch (PDOException $e) {
    echo json_encode(array(
        'status' => 'danger',
        'm' => 'No se pudo actualizar el producto: ' . $e->getMessage()
    ));
}

}else if($action ==  'delete'){
    $data = array(
        'id' => $id_entrada,
    );

    try{
        $stmt = Conexion::ConexionDB()->prepare("DELETE FROM tblentradas WHERE id=:id");
    $stmt->bindParam(":id", $data['id'], PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(array(
        'status' => 'success',
        'm' => 'Se ha eliminado el producto correctamente.'
    ));
    }catch (PDOException $e) {
        echo json_encode(array(
            'status' => 'danger',
            'm' => 'No se pudo eliminar el producto: ' . $e->getMessage()
        ));
    }
}
