<?php
require_once "../../utils/database/conexion.php";


if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $idAndFactura = $_POST['id'];
    $idAndIdArray = explode(',', $idAndFactura);

    $id_entrada = $idAndIdArray[0]; // Esto será 'edit'
    $id_factura = $idAndIdArray[1];

    if ($action == 'edit') {
        // var_dump($id_factura);
    // 1. Recogemos los datos básicos (Siempre existen)
    $codigo = $_POST['codigo'];
    
    // Limpieza de números (quitar símbolos de moneda si los hay)
    $cantidad = floatval(str_replace(['$', ','], ['', ''], $_POST['cantidad_entrada']));
    $precio = floatval(str_replace(['$', ','], ['', ''], $_POST['precio']));

    // 2. Recogemos los datos "Opcionales" (Solo existen si el Checkbox estaba activo)
    // Usamos el operador '??' para asignar 0 si el campo no fue enviado por Tabledit
    $envio = floatval(str_replace(['$', ','], ['', ''], $_POST['precio_envio'] ?? 0));
    $descuento = floatval(str_replace(['$', ','], ['', ''], $_POST['precio_descuento'] ?? 0));
    $carga = floatval(str_replace(['$', ','], ['', ''], $_POST['precio_carga'] ?? 0));
    
    // El IVA manual solo lo tomamos si existe, si no, enviamos 0 (el Trigger lo corregirá si es modo normal)
    $iva_manual = floatval(str_replace(['$', ','], ['', ''], $_POST['precio_iva'] ?? 0));

    // 3. CÁLCULO DE LA BASE IMPONIBLE (PRECIO TOTAL) EN EL SERVIDOR
    // Fórmula: (Cantidad * Precio) + Envio + Carga - Descuento
    $precio_bruto = $cantidad * $precio;
    $precio_total_calculado = $precio_bruto + $envio + $carga - $descuento;

    // Validación para no guardar negativos
    if ($precio_total_calculado < 0) $precio_total_calculado = 0;

    $conn = Conexion::ConexionDB();

    try {
        // --- (Tu validación de producto existente se mantiene igual) ---
        $stmt_get_product_id = $conn->prepare("SELECT id FROM tblinventario WHERE codigo = :codigo");
        $stmt_get_product_id->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        $stmt_get_product_id->execute();
        $id_producto = $stmt_get_product_id->fetchColumn();

        if ($id_producto === false) {
            echo json_encode(['status' => 'danger', 'm' => 'El código del producto no existe.']);
            exit;
        }

        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM tblentradas WHERE id_producto = :id_producto AND id_factura = :id_factura AND id <> :id");
        $stmt_check->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt_check->bindParam(":id_factura", $id_factura, PDO::PARAM_INT);
        $stmt_check->bindParam(":id", $id_entrada, PDO::PARAM_INT);
        $stmt_check->execute();
        
        if ($stmt_check->fetchColumn() > 0) {
            echo json_encode(['status' => 'danger', 'm' => 'El producto ya existe en la factura.']);
            exit;
        } 
        
        // --- ACTUALIZACIÓN ---
        
        $sql = "UPDATE tblentradas SET 
                    id_producto = :id_producto, 
                    cantidad_entrada = :cantidad, 
                    precio_uni = :precio, 
                    precio_envio = :precio_envio, 
                    precio_descuento = :precio_descuento, 
                    precio_carga = :precio_carga, 
                    precio_iva = :precio_iva, 
                    precio_total = :precio_total,  -- IMPORTANTE: Actualizamos la base imponible
                    id_factura = :id_factura 
                WHERE id = :id";

        $stmt_update = $conn->prepare($sql);
        
        // Bind Parameters
        $stmt_update->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt_update->bindParam(":cantidad", $cantidad, PDO::PARAM_STR);
        $stmt_update->bindParam(":precio", $precio, PDO::PARAM_STR);
        
        // Campos Extras (serán 0 si es modo normal)
        $stmt_update->bindParam(":precio_envio", $envio, PDO::PARAM_STR);
        $stmt_update->bindParam(":precio_descuento", $descuento, PDO::PARAM_STR);
        $stmt_update->bindParam(":precio_carga", $carga, PDO::PARAM_STR);
        $stmt_update->bindParam(":precio_iva", $iva_manual, PDO::PARAM_STR);
        
        // Campo Calculado (Base Imponible)
        $stmt_update->bindParam(":precio_total", $precio_total_calculado, PDO::PARAM_STR);
        
        $stmt_update->bindParam(":id_factura", $id_factura, PDO::PARAM_INT);
        $stmt_update->bindParam(":id", $id_entrada, PDO::PARAM_INT);
        
        if ($stmt_update->execute()) {
            echo json_encode([
                'status' => 'success', 
                'm' => 'Se ha actualizado el producto correctamente.'
            ]);
        } else {
            throw new Exception("Error al ejecutar la actualización.");
        }

    } catch (Exception $e) {
        echo json_encode(['status' => 'danger', 'm' => 'Error: ' . $e->getMessage()]);
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
}


