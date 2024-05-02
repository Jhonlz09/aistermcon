<?php

require_once "../utils/database/conexion.php";

class ModeloRegistro
{

    public $resultado;


    // static public function mdlRegistrarEntrada($arr, $proveedor, $fecha)
    // {
    //     try {
    //         $hora = date('H:i:s');
    //         $fechaHora = $fecha . ' ' . $hora;

    //         $stmt = Conexion::ConexionDB()->prepare("INSERT INTO tblentradas(cantidad_entrada, id_producto, precio, id_proveedor, fecha)         
    //                                             VALUES(:cantidad, :id, :precio, :id_proveedor, :fecha)");
    //         $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_STR);
    //         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    //         $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
    //         $stmt->bindParam(':id_proveedor', $proveedor, PDO::PARAM_INT);
    //         $stmt->bindParam(':fecha', $fechaHora, PDO::PARAM_STR);

    //         // Recorre el array y ejecuta la consulta para cada conjunto de datos
    //         foreach ($arr as $data) {
    //             // Divide la cadena en id, cantidad y precio
    //             list($id, $cantidad, $precio) = explode(',', $data);

    //             // Ejecuta la consulta preparada
    //             $stmt->execute();
    //         }

    //         return array(
    //             'status' => 'success',
    //             'm' => 'La entrada fue registrada correctamente'
    //         );
    //     } catch (PDOException $e) {
    //         return array(
    //             'status' => 'danger',
    //             'm' => 'No se pudo registrar la entrada: ' . $e->getMessage()
    //         );
    //     }
    // }

    static public function mdlRegistrarEntrada($arr, $proveedor, $fecha)
    {
        try {
            $hora = date('H:i:s');
            $fechaHora = $fecha . ' ' . $hora;

            $conexion = Conexion::ConexionDB();
            $stmtB = $conexion->prepare("INSERT INTO tblboleta(fecha,tipo_boleta,id_proveedor) VALUES(:fecha, 'E', :id_proveedor)");
            $stmtB->bindParam(':fecha', $fechaHora, PDO::PARAM_STR);
            $stmtB->bindParam(':id_proveedor', $proveedor, PDO::PARAM_INT);
            // $stmtB->execute();
            if ($stmtB->execute()) {

                $id_boleta = $conexion->lastInsertId();

                // Recorre el array e inserta la entrada en tblentradas y actualiza tblinventario
                foreach ($arr as $data) {
                    // Divide la cadena en id, cantidad y precio
                    list($id, $cantidad, $precio) = explode(',', $data);

                    // Inserta la entrada en tblentradas
                    $stmtE = Conexion::ConexionDB()->prepare("INSERT INTO tblentradas(id_boleta,cantidad_entrada, id_producto, precio)         
                        VALUES(:id_boleta, :cantidad, :id, :precio)");
                    $stmtE->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
                    $stmtE->bindParam(':cantidad', $cantidad, PDO::PARAM_STR);
                    $stmtE->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmtE->bindParam(':precio', $precio, PDO::PARAM_STR);
                    $stmtE->execute();

                    // Actualiza la cantidad en tblinventario
                    $stmtI = Conexion::ConexionDB()->prepare("UPDATE tblinventario SET stock = stock + :cantidad WHERE id = :id");
                    $stmtI->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
                    $stmtI->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmtI->execute();
                }
            }
            return array(
                'status' => 'success',
                'm' => 'La entrada fue registrada correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo registrar la entrada: ' . $e->getMessage()
            );
        }
    }


    static public function mdlRegistrarSalida($arr, $orden, $fecha, $conductor, $entrega)
    {
        try {

            $conexion = Conexion::ConexionDB();
            $hora = date('H:i:s');
            $fechaHora = $fecha . ' ' . $hora;

            foreach ($arr as $data) {
                list($idProducto, $cantidad) = explode(',', $data);

                // Consultar el stock disponible para el producto 
                $stmtStock = $conexion->prepare("SELECT (stock - stock_mal) as stock, codigo FROM tblinventario WHERE id = :idProducto");
                $stmtStock->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
                $stmtStock->execute();
                $resultadoStock = $stmtStock->fetch(PDO::FETCH_ASSOC);

                if (!$resultadoStock || $resultadoStock['stock'] < $cantidad) {
                    return array(
                        'status' => 'danger',
                        'm' => "No hay suficiente stock disponible para el producto con el codigo '" . $resultadoStock['codigo']. "'"
                    );
                }
            }

            $stmtB = $conexion->prepare("INSERT INTO tblboleta(fecha, id_orden, id_conductor, id_entrega, tipo_boleta) VALUES(:fecha,:orden,:conductor,:entrega, 'S')");
            $stmtB->bindParam(':fecha', $fechaHora, PDO::PARAM_STR);
            $stmtB->bindParam(':orden', $orden, PDO::PARAM_INT);
            $stmtB->bindParam(':conductor', $conductor, PDO::PARAM_INT);
            $stmtB->bindParam(':entrega', $entrega, PDO::PARAM_INT);
            $stmtB->execute();
            $id_boleta = $conexion->lastInsertId();
            // }
            // Recorre el array e inserta la salida de cada producto en tblsalidas y actualiza tblinventario
            foreach ($arr as $data) {
                list($id, $cantidad) = explode(',', $data);
                $stmtE = Conexion::ConexionDB()->prepare("INSERT INTO tblsalidas(id_boleta,cantidad_salida, id_producto)         
            VALUES(:id_boleta, :cantidad, :id)");
                $stmtE->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
                $stmtE->bindParam(':cantidad', $cantidad, PDO::PARAM_STR);
                $stmtE->bindParam(':id', $id, PDO::PARAM_INT);
                $stmtE->execute();
            }

            return array(
                'status' => 'success',
                'm' => 'La salida fue registrada correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo registrar la salida: ' . $e->getMessage()
            );
        }
    }


    static public function mdlRegistrarRetorno($arr, $boleta, $fecha_retorno)
    {
        try {
            // Recorre el array e inserta la entrada en tblsalidas y actualiza tblinventario
            foreach ($arr as $data) {
                list($id, $cantidad) = explode(',', $data);
                $stmtE = Conexion::ConexionDB()->prepare("UPDATE tblsalidas SET retorno=:cantidad WHERE id=:id");
                $stmtE->bindParam(':cantidad', $cantidad, PDO::PARAM_STR);
                $stmtE->bindParam(':id', $id, PDO::PARAM_INT);
                $stmtE->execute();
            }

            $stmtB = Conexion::ConexionDB()->prepare("UPDATE tblboleta SET tipo_boleta='R', fecha_retorno=:fecha_retorno WHERE id=:boleta");
            $stmtB->bindParam(':fecha_retorno', $fecha_retorno, PDO::PARAM_STR);
            $stmtB->bindParam(':boleta', $boleta, PDO::PARAM_STR);
            $stmtB->execute();

            return array(
                'status' => 'success',
                'm' => 'La entrada fue registrada correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo registrar la entrada: ' . $e->getMessage()
            );
        }
    }
}
