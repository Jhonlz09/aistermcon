<?php

require_once "../utils/database/conexion.php";

class ModeloRegistro
{

    public $resultado;

    static public function mdlRegistrarEntrada($arr, $nro_factura, $proveedor, $fecha)
    {
        try {
            $hora = date('H:i:s');
            $fechaHora = $fecha . ' ' . $hora;

            $conexion = Conexion::ConexionDB();
            $stmtF = $conexion->prepare("INSERT INTO tblfactura(fecha,nombre,id_proveedor) VALUES(:fecha, :nro_factura,:id_proveedor)");
            $stmtF->bindParam(':fecha', $fechaHora, PDO::PARAM_STR);
            $stmtF->bindParam(':nro_factura', $nro_factura, PDO::PARAM_STR);
            $stmtF->bindParam(':id_proveedor', $proveedor, PDO::PARAM_INT);
            // $stmtB->execute();
            if ($stmtF->execute()) {
                $id_factura = $conexion->lastInsertId();
                // Recorre el array e inserta la entrada en tblentradas y actualiza tblinventario
                foreach ($arr as $data) {
                    // Divide la cadena en id, cantidad y precio
                    list($id, $cantidad, $precio) = explode(',', $data);

                    $precio = str_replace('.', ',', $precio);
                    // Inserta la entrada en tblentradas
                    $stmtE = Conexion::ConexionDB()->prepare("INSERT INTO tblentradas(id_factura,cantidad_entrada, id_producto, precio_uni)         
                        VALUES(:id_factura, :cantidad, :id, :precio)");
                    $stmtE->bindParam(':id_factura', $id_factura, PDO::PARAM_INT);
                    $stmtE->bindParam(':cantidad', $cantidad, PDO::PARAM_STR);
                    $stmtE->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmtE->bindParam(':precio', $precio, PDO::PARAM_STR);
                    $stmtE->execute();
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


    static public function mdlRegistrarSalida($arr, $orden, $nro_guia, $fecha, $conductor, $despachado, $responsable)
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
                        'm' => "No hay suficiente stock disponible para el producto con el codigo '" . $resultadoStock['codigo'] . "'"
                    );
                }
            }

            $stmtB = $conexion->prepare("INSERT INTO tblboleta(fecha, id_orden, nro_guia, id_conductor, id_despachado, id_responsable) VALUES(:fecha,:orden,:nro_guia, :conductor, :despachado, :responsable)");
            $stmtB->bindParam(':fecha', $fechaHora, PDO::PARAM_STR);
            $stmtB->bindParam(':orden', $orden, PDO::PARAM_INT);
            $stmtB->bindParam(':nro_guia', $nro_guia, PDO::PARAM_STR);
            $stmtB->bindParam(':conductor', $conductor, PDO::PARAM_INT);
            $stmtB->bindParam(':despachado', $despachado, PDO::PARAM_INT);
            $stmtB->bindParam(':responsable', $responsable, PDO::PARAM_INT);

            $stmtB->execute();
            $id_boleta = $conexion->lastInsertId();
            // Recorre el array e inserta la salida de cada producto en tblsalidas y actualiza tblinventario
            foreach ($arr as $data) {
                list($id, $cantidad) = explode(',', $data);
                $stmtE = Conexion::ConexionDB()->prepare("INSERT INTO tblsalidas(id_boleta,cantidad_salida, id_producto) VALUES(:id_boleta, :cantidad, :id )");
                $stmtE->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
                $stmtE->bindParam(':cantidad', $cantidad, PDO::PARAM_STR);
                // $stmtE->bindParam(':fab', $fab, PDO::PARAM_BOOL);
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

    static public function mdlEditarRegistroSalida($id_boleta, $orden, $nro_guia, $fecha, $conductor, $despachado, $responsable)
    {
        try {
            $conexion = Conexion::ConexionDB();

            // Prepara la consulta SQL para actualizar solo la fecha y no la hora
            $stmtB = $conexion->prepare("UPDATE tblboleta 
            SET fecha = to_timestamp(:fecha || ' ' || to_char(fecha, 'HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS'),
            id_conductor = :conductor,id_despachado = :despachado,
            id_responsable = :responsable,nro_guia = :nro_guia, id_orden = :orden 
            WHERE id=:id_boleta ");

            $stmtB->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stmtB->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
            $stmtB->bindParam(':orden', $orden, PDO::PARAM_INT);
            $stmtB->bindParam(':nro_guia', $nro_guia, PDO::PARAM_STR);
            $stmtB->bindParam(':conductor', $conductor, PDO::PARAM_INT);
            $stmtB->bindParam(':despachado', $despachado, PDO::PARAM_INT);
            $stmtB->bindParam(':responsable', $responsable, PDO::PARAM_INT);
            $stmtB->execute();

            return array(
                'status' => 'success',
                'm' => 'La guía fue actualizada correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo actualizar la guía: ' . $e->getMessage()
            );
        }
    }

    static public function mdlEditarRegistroEntrada($id_factura, $nro_factura, $proveedor, $fecha)
    {
        try {
            $conexion = Conexion::ConexionDB();

            // Prepara la consulta SQL para actualizar solo la fecha y no la hora
            $stmtB = $conexion->prepare("UPDATE tblfactura 
            SET fecha = to_timestamp(:fecha || ' ' || to_char(fecha, 'HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS'),
            id_proveedor = :id_proveedor, nombre = :nro_factura 
            WHERE id=:id_factura");
            $stmtB->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stmtB->bindParam(':id_factura', $id_factura, PDO::PARAM_INT);
            $stmtB->bindParam(':id_proveedor', $proveedor, PDO::PARAM_INT);
            $stmtB->bindParam(':nro_factura', $nro_factura, PDO::PARAM_STR);
            $stmtB->execute();
            return array(
                'status' => 'success',
                'm' => 'La factura fue actualizada correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo actualizar la factura: ' . $e->getMessage()
            );
        }
    }



    static public function mdlRegistrarRetorno($arr, $boleta, $fecha_retorno)
    {
        try {
            $db = Conexion::ConexionDB();
            $db->beginTransaction(); // Iniciar una transacción para asegurar consistencia
    
            foreach ($arr as $data) {
                list($id, $cantidad) = explode(',', $data);
    
                if ($cantidad === '') {
                    $cantidad = NULL;
                }
    
                // Verificar el valor actual de 'salidas' y 'entradas' para este producto
                $stmtCheck = $db->prepare("SELECT i.descripcion, s.cantidad_salida, s.retorno FROM tblsalidas s JOIN tblinventario i ON i.id = s.id_producto WHERE s.id = :id");
                $stmtCheck->bindParam(':id', $id, PDO::PARAM_INT);
                $stmtCheck->execute();
                $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    
                if ($row) {
                    $codigo = $row['descripcion'];
                    $salidas = $row['cantidad_salida'];
                    if ($cantidad !== NULL && $cantidad > $salidas) {
                        throw new Exception("La cantidad ingresada para el producto '$codigo' excede la cantidad permitida.");
                    }
    
                    // Realizar la actualización del campo 'retorno' y 'isentrada'
                    $stmtE = $db->prepare("UPDATE tblsalidas SET retorno = :nuevaEntrada, isentrada = :isEntrada WHERE id = :id");
                    $stmtE->bindParam(':nuevaEntrada', $cantidad, PDO::PARAM_STR);
                    $stmtE->bindParam(':isEntrada', $isEntrada, PDO::PARAM_BOOL);
                    $stmtE->bindParam(':id', $id, PDO::PARAM_INT);
    
                    // Determinar el valor de 'isentrada'
                    $isEntrada = $cantidad !== NULL ? 1 : 0;
    
                    $stmtE->execute();
                } else {
                    throw new Exception("Producto con ID $id no encontrado.");
                }
            }
    
            $stmtB = $db->prepare("UPDATE tblboleta SET retorno = true, fecha_retorno = :fecha_retorno WHERE id = :boleta");
            $stmtB->bindParam(':fecha_retorno', $fecha_retorno, PDO::PARAM_STR);
            $stmtB->bindParam(':boleta', $boleta, PDO::PARAM_STR);
            $stmtB->execute();
    
            $db->commit(); // Confirmar la transacción
    
            return array(
                'status' => 'success',
                'm' => 'La entrada fue registrada correctamente'
            );
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack(); // Revertir la transacción en caso de error
            }
    
            return array(
                'status' => 'danger',
                'm' => '' . $e->getMessage()
            );
        }
    }
    


    static public function mdlRegistrarPlantilla($arr, $nombre)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $stmtP = $conexion->prepare("INSERT INTO tblplantilla(nombre) VALUES(:nombre)");
            $stmtP->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmtP->execute();
            $id_plantilla = $conexion->lastInsertId();
            // Recorre el array e inserta la entrada en tblsalidas y actualiza tblinventario
            foreach ($arr as $data) {
                list($id, $tamanio) = explode(',', $data);
                $stmtE = Conexion::ConexionDB()->prepare("INSERT INTO tblplantilla_encabezado(id_plantilla, id_encabezado, tamanio)  VALUES(:id_plantilla, :id_encabezado, :tamanio)");
                $stmtE->bindParam(':id_plantilla', $id_plantilla, PDO::PARAM_INT);
                $stmtE->bindParam(':id_encabezado', $id, PDO::PARAM_INT);
                $stmtE->bindParam(':tamanio', $tamanio, PDO::PARAM_INT);
                $stmtE->execute();
            }
            $stmtE = Conexion::ConexionDB()->prepare("INSERT INTO tblplantilla_encabezado(id_plantilla, id_encabezado, tamanio)  VALUES(:id_plantilla, :id_encabezado, :tamanio)");
            $stmtE->bindParam(':id_plantilla', $id_plantilla, PDO::PARAM_INT);
            $stmtE->bindValue(':id_encabezado', 5, PDO::PARAM_INT);
            $stmtE->bindValue(':tamanio', 20, PDO::PARAM_INT);
            $stmtE->execute();

            $stmtE = Conexion::ConexionDB()->prepare("INSERT INTO tblplantilla_encabezado(id_plantilla, id_encabezado, tamanio)  VALUES(:id_plantilla, :id_encabezado, :tamanio)");
            $stmtE->bindParam(':id_plantilla', $id_plantilla, PDO::PARAM_INT);
            $stmtE->bindValue(':id_encabezado', 6, PDO::PARAM_INT);
            $stmtE->bindValue(':tamanio', 20, PDO::PARAM_INT);
            $stmtE->execute();
            return array(
                'status' => 'success',
                'm' => 'La plantilla fue registrada correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo registrar la plantilla: ' . $e->getMessage()
            );
        }
    }
}
