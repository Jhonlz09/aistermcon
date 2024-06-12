<?php
require_once "../../utils/database/conexion.php";

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $idAndBoleta = $_POST['id'];
    $idAndIdArray = explode(',', $idAndBoleta);

    $id_salida = $idAndIdArray[0]; // Esto será 'edit'
    $id_boleta = $idAndIdArray[1];
}
if ($action == 'edit') {
    $data = array(
        'codigo' => $_POST['codigo'],
        'cantidad' => $_POST['cantidad_salida'],
        'id' => $id_salida,
        'id_boleta' => $id_boleta
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

        // Verificar si el id_producto ya está relacionado con el id_boleta en tblsalidas
        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM tblsalidas WHERE id_producto = :id_producto AND id_boleta = :id_boleta AND id <> :id");
        $stmt_check->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt_check->bindParam(":id_boleta", $data['id_boleta'], PDO::PARAM_INT);
        $stmt_check->bindParam(":id", $data['id'], PDO::PARAM_INT);

        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            // El id_producto ya está relacionado con el id_boleta, no realizar la actualización
            echo json_encode(array(
                'status' => 'danger',
                'm' => 'El producto ya existe en la guía.'
            ));
        } else {
            // Verificar si hay suficiente stock antes de proceder con la actualización
            $stmt_stock = $conn->prepare("SELECT 
                COALESCE(s.cantidad_salida, 0) AS cantidad_salida,
                (i.stock - i.stock_mal) AS stock, 
                i.descripcion 
                FROM tblinventario i 
                LEFT JOIN tblsalidas s ON i.id = s.id_producto AND s.id = :id
                WHERE i.id = :id_producto");
            $stmt_stock->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt_stock->bindParam(":id", $data['id'], PDO::PARAM_INT);
            $stmt_stock->execute();
            $resultado_stock = $stmt_stock->fetch(PDO::FETCH_ASSOC);

            if (!$resultado_stock || $resultado_stock['stock'] + $resultado_stock['cantidad_salida'] < $data['cantidad']) {
                echo json_encode(array(
                    'status' => 'danger',
                    'm' => "No hay suficiente stock disponible para el producto '" . $resultado_stock['descripcion'] . "'"
                ));
            } else {
                // El id_producto no está relacionado con el id_boleta y hay suficiente stock, proceder con la actualización
                $stmt_update = $conn->prepare("UPDATE tblsalidas SET id_producto = :id_producto, cantidad_salida = :cantidad, id_boleta = :id_boleta WHERE id = :id");
                $stmt_update->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
                $stmt_update->bindParam(":cantidad", $data['cantidad'], PDO::PARAM_STR);
                $stmt_update->bindParam(":id_boleta", $data['id_boleta'], PDO::PARAM_INT);
                $stmt_update->bindParam(":id", $data['id'], PDO::PARAM_INT);
                $stmt_update->execute();

                echo json_encode(array(
                    'status' => 'success',
                    'm' => 'Se ha actualizado el producto correctamente.'
                ));
            }
        }
    } catch (PDOException $e) {
        echo json_encode(array(
            'status' => 'danger',
            'm' => 'No se pudo actualizar el producto: ' . $e->getMessage()
        ));
    }
} else if ($action == 'delete') {
    $data = array(
        'id' => $id_salida,
    );
    try {
        $stmt = Conexion::ConexionDB()->prepare("DELETE FROM tblsalidas WHERE id=:id");
        $stmt->bindParam(":id", $data['id'], PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(array(
            'status' => 'success',
            'm' => 'Se ha eliminado el producto correctamente.'
        ));
    } catch (PDOException $e) {
        echo json_encode(array(
            'status' => 'danger',
            'm' => 'No se pudo eliminar el producto: ' . $e->getMessage()
        ));
    }
}
