<?php

require_once "../utils/database/conexion.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class ModeloRegistro
{

    public $resultado;

    static public function mdlRegistrarCompra($arr, $nro_factura, $proveedor, $fecha)
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

                    // $precio = str_replace('.', ',', $precio);
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

    static public function mdlRegistrarSalida($arr, $orden, $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo, $img)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();
            // Validar el stock disponible para cada producto
            foreach ($arr as $data) {
                list($idProducto, $cantidad) = explode(',', $data);

                if ($cantidad == 0) {
                    throw new Exception("La cantidad de salida de los productos no puede ser 0");
                }

                $stmtStock = $conexion->prepare("SELECT (stock - stock_mal) as stock, descripcion FROM tblinventario WHERE id = :idProducto");
                $stmtStock->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
                $stmtStock->execute();
                $resultadoStock = $stmtStock->fetch(PDO::FETCH_ASSOC);

                if (!$resultadoStock || $resultadoStock['stock'] < $cantidad) {
                    throw new Exception("No hay suficiente stock disponible para el producto '{$resultadoStock['descripcion']}'");
                }
            }

            // Insertar boleta
            $id_boleta = self::insertarBoleta($conexion, $orden, $fecha, $nro_guia, $conductor, $despachado, $responsable, $motivo);

            // Insertar salidas
            self::insertarSalidas($conexion, $id_boleta, $arr);

            if (!empty($img)) {
                self::guardarImagenesSalida($conexion, $id_boleta, $img);
            }

            self::mdlCambioEstadoOrden($conexion, $orden, $fecha);

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'La guía de remision fue registrada correctamente.'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'No se pudo registrar la guía: ' . $e->getMessage()
            );
        } catch (Exception $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => '' . $e->getMessage()
            );
        }
    }

    private static function mdlCambioEstadoOrden($conexion, $orden, $fecha)
    {
        // Verificamos si el campo fecha_ini es NULL
        $stmt = $conexion->prepare("SELECT fecha_ope FROM tblorden WHERE id = :orden");
        $stmt->bindParam(':orden', $orden, PDO::PARAM_INT);
        $stmt->execute();

        // Recuperamos el valor de fecha_ini
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si fecha_ini es NULL, procedemos a actualizar
        if ($resultado['fecha_ope'] === null) {
            $hora = date('H:i:s');
            $fechaHora = $fecha . ' ' . $hora;

            // Actualizamos el estado y la fecha_ini
            $stmt = $conexion->prepare("UPDATE tblorden SET estado = 'OPERACION', fecha_ope = :fecha WHERE id = :orden");
            $stmt->bindParam(':orden', $orden, PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $fechaHora, PDO::PARAM_STR);
        } else {
            // Solo actualizamos el estado si fecha_ini no es NULL
            $stmt = $conexion->prepare("UPDATE tblorden SET estado = 'OPERACION' WHERE id = :orden");
            $stmt->bindParam(':orden', $orden, PDO::PARAM_INT);
        }

        // Ejecutamos la consulta
        $stmt->execute();
    }

    static public function mdlRegistrarFabricacion($datos, $orden, $nro_guia, $conductor, $despachado, $responsable, $fecha, $motivo, $tras, $img)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();
            $productos = json_decode($datos, true);
            if (!$productos) {
                throw new Exception("Los datos de productos son inválidos.");
            }
            $tras = filter_var($tras, FILTER_VALIDATE_BOOLEAN);

            $id_boleta = self::insertarBoleta($conexion, $orden, $fecha, $nro_guia, $conductor, $despachado, $responsable, $motivo, true, $tras);

            foreach ($productos as $producto) {
                $cantidadFabricada = $producto['cantidad'];
                $des = $producto['descripcion'];
                $uni = $producto['unidad'];
                $insumos = $producto['productos'];
                foreach ($insumos as $insumo) {
                    $codigoInsumo = $insumo['codigo'];
                    $cantidadUtilizada = $insumo['cantidad'];
                    $cantidadEntrada = $insumo['entrada'];
                    $stmtStock = $conexion->prepare("SELECT stock, descripcion FROM tblinventario WHERE id = :codigoInsumo");
                    $stmtStock->bindParam(':codigoInsumo', $codigoInsumo, PDO::PARAM_INT);
                    $stmtStock->execute();
                    $response = $stmtStock->fetch(PDO::FETCH_ASSOC);
                    $stockActual = $response['stock'];
                    $descripPro = $response['descripcion'];
                    if ($stockActual < $cantidadUtilizada) {
                        throw new Exception("Stock insuficiente para el producto $descripPro.");
                    }
                }
                $sqlInsertFab =  $tras ?  "INSERT INTO tblinventario(codigo,descripcion,stock,id_unidad,fabricado) VALUES (generar_codigo_pf(),:des,:salida,:uni, true)" : "INSERT INTO tblinventario(codigo,descripcion,stock,id_unidad,fabricado) VALUES (generar_codigo_pf(),:des,0,:uni, true)";
                $stmtInsertFabricado = $conexion->prepare($sqlInsertFab);
                $stmtInsertFabricado->bindParam(":des", $des, PDO::PARAM_STR);
                if ($tras) {
                    $stmtInsertFabricado->bindParam(":salida", $cantidadFabricada, PDO::PARAM_INT);
                }
                $stmtInsertFabricado->bindParam(":uni", $uni, PDO::PARAM_INT);
                $stmtInsertFabricado->execute();
                $id_producto_fab = $conexion->lastInsertId('tblinventario_id_seq');

                $sqlSalida = $tras  ? "INSERT INTO tblsalidas(id_boleta, cantidad_salida, id_producto, fabricado) VALUES(:id_boleta, :cantidad, :id, true)" : "INSERT INTO tblsalidas(id_boleta, retorno, id_producto, fabricado) VALUES(:id_boleta, :cantidad, :id, true)";
                $stmtSalida = $conexion->prepare($sqlSalida);
                $stmtSalida->bindParam(':id', $id_producto_fab, PDO::PARAM_INT);
                $stmtSalida->bindParam(':cantidad', $cantidadFabricada, PDO::PARAM_INT);
                $stmtSalida->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
                $stmtSalida->execute();

                self::relacionarProductoConInsumos($conexion, $id_producto_fab, $id_boleta, $insumos);
                self::mdlCambioEstadoOrden($conexion, $orden, $fecha);
            }

            if (!empty($img)) {
                self::guardarImagenesSalida($conexion, $id_boleta, $img);
            }

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'La fabricación se registró correctamente.'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'Error al registrar la fabricación: ' . $e->getMessage()
            );
        } catch (Exception $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => $e->getMessage()
            );
        }
    }

    static public function mdlActualizarDatosFabricacion($datos, $id_boleta, $orden, $nro_guia, $conductor, $despachado, $responsable, $fecha, $fecha_retorno, $motivo, $tras, $img)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            $productos = json_decode($datos, true);
            if (!$productos) {
                throw new Exception("Los datos de productos son inválidos.");
            }

            $tras = filter_var($tras, FILTER_VALIDATE_BOOLEAN);

            // 🧩 Verificar si esta boleta ya tenía traslado (tras = true)
            $stmtCheckTras = $conexion->prepare("SELECT tras FROM tblboleta WHERE id = :id_boleta");
            $stmtCheckTras->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
            $stmtCheckTras->execute();
            $trasExistente = filter_var($stmtCheckTras->fetchColumn(), FILTER_VALIDATE_BOOLEAN);

            // 🔄 Actualiza cabecera de boleta (puede marcar tras = true si corresponde)
            self::actualizarBoleta($conexion, $id_boleta, $orden, $fecha, $fecha_retorno, $nro_guia, $conductor, $despachado, $responsable, $motivo, $tras);

            foreach ($productos as $producto) {
                $id_prod_fab = $producto['id'];
                $cantidadFabricada = $producto['cantidad'];
                $cantidadEntrada = isset($producto['retorno']) && $producto['retorno'] !== ''
                    ? $producto['retorno']
                    : null;

                $des = $producto['descripcion'];
                $uni = $producto['unidad'];
                $insumos = $producto['productos'];
                foreach ($insumos as $insumo) {
                    $cantidadNueva = floatval($insumo['cantidad']);
                    $cantidadAnterior = floatval($insumo['cantidad_old'] ?? 0);
                    $id_producto_util = $insumo['id_producto'];
                    // 🔍 Consultar stock actual real
                    $stmtStock = $conexion->prepare("SELECT (stock - stock_mal) AS stock, descripcion  FROM tblinventario WHERE id = :insumo");
                    $stmtStock->bindParam(':insumo', $id_producto_util, PDO::PARAM_INT);
                    $stmtStock->execute();
                    $response = $stmtStock->fetch(PDO::FETCH_ASSOC);
                    $stockActual = floatval($response['stock']);
                    $descripPro = $response['descripcion'];
                    $diferencia = $cantidadNueva - $cantidadAnterior;

                    if ($diferencia > 0 && $stockActual < $diferencia) {
                        throw new Exception(
                            "Stock insuficiente para el producto '$descripPro'. " .
                                "Aumento requerido: $diferencia, Disponible: $stockActual"
                        );
                    }
                }
                // 🏭 Actualizar datos del producto fabricado
                $sqlUpdateFab = $tras
                    ? "UPDATE tblinventario SET descripcion = :des, id_unidad = :uni, stock = :salida WHERE id = :id_prod_fab"
                    : "UPDATE tblinventario SET descripcion = :des, id_unidad = :uni WHERE id = :id_prod_fab";
                $stmtUpdateFab = $conexion->prepare($sqlUpdateFab);
                $stmtUpdateFab->bindParam(":id_prod_fab", $id_prod_fab, PDO::PARAM_INT);
                $stmtUpdateFab->bindParam(":des", $des, PDO::PARAM_STR);
                $stmtUpdateFab->bindParam(":uni", $uni, PDO::PARAM_INT);
                if ($tras) {
                    $stmtUpdateFab->bindParam(":salida", $cantidadFabricada, PDO::PARAM_STR);
                }
                $stmtUpdateFab->execute();
                // 🧠 Determinar cómo actualizar tblsalidas según el estado de traslado
                if ($tras && !$trasExistente) {
                    // Primera vez: trasladar retorno -> salida
                    $sqlSalida = "UPDATE tblsalidas 
                                SET retorno = NULL, cantidad_salida = :cantidad
                                WHERE id_producto = :id AND id_boleta = :id_boleta";
                } elseif ($tras && $trasExistente) {
                    // Ya se trasladó antes: solo actualizar salida
                    $sqlSalida = "UPDATE tblsalidas 
                                SET cantidad_salida = :cantidad, retorno = :entrada 
                                WHERE id_producto = :id AND id_boleta = :id_boleta";
                } else {
                    // Modo normal (sin traslado)
                    $sqlSalida = "UPDATE tblsalidas 
                                SET retorno = :cantidad 
                                WHERE id_producto = :id AND id_boleta = :id_boleta";
                }
                $stmtSalida = $conexion->prepare($sqlSalida);
                $stmtSalida->bindParam(':id', $id_prod_fab, PDO::PARAM_INT);
                $stmtSalida->bindParam(':cantidad', $cantidadFabricada, PDO::PARAM_STR);
                if ($tras && $trasExistente) {
                    $stmtSalida->bindParam(':entrada', $cantidadEntrada, PDO::PARAM_STR);
                }
                $stmtSalida->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
                $stmtSalida->execute();
                // 🔗 Actualizar relación producto-insumo
                self::relacionarProductoConInsumosUpdate($conexion, $id_prod_fab, $id_boleta, $insumos);
            }
            // 🖼️ Guardar imágenes si existen
            if (!empty($img)) {
                self::guardarImagenesSalida($conexion, $id_boleta, $img);
            }

            $conexion->commit();

            return [
                'status' => 'success',
                'm' => 'La fabricación se editó correctamente.'
            ];
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'Error al registrar la fabricación: ' . $e->getMessage()
            );
        } catch (Exception $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => $e->getMessage()
            );
        }
    }

    private static function relacionarProductoConInsumos($conexion, $idProductoFabricado, $idBoleta, $insumos)
    {
        $stmtSalidaInsumo = $conexion->prepare(
            "INSERT INTO tblsalidas(id_boleta, cantidad_salida, id_producto, id_producto_fab, retorno) 
            VALUES(:id_boleta, :cantidad, :id_producto, :id_producto_fab, :retorno)"
        );

        foreach ($insumos as $insumo) {
            $stmtSalidaInsumo->bindParam(':id_boleta', $idBoleta, PDO::PARAM_INT);
            $stmtSalidaInsumo->bindParam(':cantidad', $insumo['cantidad'], PDO::PARAM_STR);
            $stmtSalidaInsumo->bindParam(':retorno', $insumo['entrada'], PDO::PARAM_STR);

            $stmtSalidaInsumo->bindParam(':id_producto', $insumo['codigo'], PDO::PARAM_INT);
            $stmtSalidaInsumo->bindParam(':id_producto_fab', $idProductoFabricado, PDO::PARAM_INT);
            $stmtSalidaInsumo->execute();
        }
    }

    private static function relacionarProductoConInsumosUpdate($conexion, $idProductoFabricado, $idBoleta, $insumos)
    {
        $stmtSalidaInsumo = $conexion->prepare("UPDATE tblsalidas SET id_boleta=:id_boleta, cantidad_salida=:cantidad, retorno=:retorno, id_producto_fab=:id_producto_fab WHERE id=:id_producto");
        foreach ($insumos as $insumo) {
            $stmtSalidaInsumo->bindParam(':id_boleta', $idBoleta, PDO::PARAM_INT);
            $stmtSalidaInsumo->bindParam(':cantidad', $insumo['cantidad'], PDO::PARAM_STR);
            $stmtSalidaInsumo->bindParam(':retorno', $insumo['retorno'], PDO::PARAM_STR);
            $stmtSalidaInsumo->bindParam(':id_producto', $insumo['codigo'], PDO::PARAM_INT);
            $stmtSalidaInsumo->bindParam(':id_producto_fab', $idProductoFabricado, PDO::PARAM_INT);
            $stmtSalidaInsumo->execute();
        }
    }

    static private function guardarImagenesSalida($conexion, $id_boleta, $imagenes)
    {
        // Directorio para almacenar las imágenes
        $uploadDir = __DIR__ . "/../../guia_img/";
        // Asegurar que $nro_guia tenga un valor válido
        foreach ($imagenes["name"] as $index => $name) {
            $tmpName = $imagenes["tmp_name"][$index];
            // Obtener la extensión del archivo
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            // Crear un nombre único para el archivo
            $uniqueName = $id_boleta . "_" . uniqid() . "." . $extension;
            // Ruta completa del archivo destino
            $targetFile = $uploadDir . $uniqueName;
            // Mover el archivo al directorio de destino
            if (move_uploaded_file($tmpName, $targetFile)) {
                // Guardar la información en la base de datos
                $stmt = $conexion->prepare("INSERT INTO tblimg_salida (id_boleta, nombre_imagen)
                    VALUES (:id_boleta, :ruta_imagen)
                ");
                $stmt->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
                $stmt->bindParam(':ruta_imagen', $uniqueName, PDO::PARAM_STR);
                $stmt->execute();
            } else {
                throw new Exception("Error al mover la imagen: $name");
            }
        }
    }

    static public function mdlSolicitudCotizacion($arrJSON, $proveedor, $comprador, $fecha)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();
            $motivo = ''; // Inicializar el string motivo
            $contador = 0;
            $arr = json_decode($arrJSON, true);
            // Validar el stock disponible para cada producto
            foreach ($arr as $data) {
                // list($cantidad, $unidad, $descripcion) = explode(',', $data);
                $cantidad = isset($data['cantidad']) ? (int)$data['cantidad'] : 0;
                // $unidad = $data['unidad'];
                $descripcion = isset($data['descripcion']) ? trim($data['descripcion']) : '';
                if ($cantidad == 0) {
                    throw new Exception("La cantidad de los productos no puede ser 0");
                }
                if ($contador < 5) {
                    $primeraPalabra = explode(' ', trim($descripcion))[0]; // Obtiene la primera palabra
                    $motivo .= ($contador > 0 ? ', ' : '') . $primeraPalabra; // Concatena al string motivo
                }
                $contador++; // Incrementa el contador
            }
            if ($contador > 5) {
                $motivo .= '...';
            }
            $motivo = mb_strtoupper($motivo, 'UTF-8');
            $id_cotizacion = self::insertarNroCotizacion($conexion, $proveedor, $comprador, $motivo, true, $fecha);
            // Insertar salidas
            self::insertarCotizacion($conexion, $id_cotizacion, $arr);
            $conexion->commit();
            return array(
                'status' => 'success',
                'm' => 'La solicitud de cotización fue registrada correctamente',
                'sc' => $_SESSION["sc_cot"]
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'No se pudo registrar la solicitud de cotización: ' . $e->getMessage()
            );
        }
    }

    static public function mdlOrdenCompra($arrJSON, $proveedor, $comprador, $fecha, $subtotal, $iva, $impuesto, $total, $desc)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            if ($desc == '0') {
                $desc = null;
            }

            $motivo = ''; // Inicializar el string motivo
            $contador = 0;
            $arr = json_decode($arrJSON, true);

            // Validar el stock disponible para cada producto
            foreach ($arr as $data) {
                // list($cantidad, $unidad, $descripcion, $precio) = explode(',', $data);
                $cantidad = isset($data['cantidad']) ? (float)$data['cantidad'] : 0;
                $descripcion = isset($data['descripcion']) ? trim($data['descripcion']) : '';
                $precio = isset($data['precio_uni']) ? (float)$data['precio_uni'] : 0;

                if ($cantidad == 0) {
                    throw new Exception("La cantidad de los productos no puede ser 0");
                }

                if ($precio == 0) {
                    throw new Exception("El precio no puede ser 0 o vacio");
                }

                if ($contador < 5) {
                    $primeraPalabra = explode(' ', trim($descripcion))[0]; // Obtiene la primera palabra
                    $motivo .= ($contador > 0 ? ', ' : '') . $primeraPalabra; // Concatena al string motivo
                }
                $contador++; // Incrementa el contador
            }

            if ($contador > 5) {
                $motivo .= '...';
            }

            $motivo = strtoupper($motivo);
            // Insertar nro cotizacion
            $id_orden_compra = self::insertarNroCompra($conexion, $proveedor, $comprador, $motivo, true, $fecha, $subtotal, $iva, $impuesto, $total, $desc);

            // Insertar salidas
            self::insertarOrdenCompra($conexion, $id_orden_compra, $arr);

            $conexion->commit();

            return array(
                'status' => 'success',
                'm' => 'La orden de compra fue registrada correctamente',
                'sc' => $_SESSION["sc_cot"]
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'No se pudo registrar la guía: ' . $e->getMessage()
            );
        }
    }

    static public function mdlRegistrarEntrada($arr, $orden, $fecha, $fecha_entrada, $motivo, $conductor, $responsable, $despachado)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();
            $id_boleta = self::insertarBoletaEntrada($conexion, $orden, $fecha, $fecha_entrada, $motivo, $conductor, $responsable, $despachado);
            // Insertar salidas
            self::insertarEntradas($conexion, $id_boleta, $arr);
            $conexion->commit();
            return array(
                'status' => 'success',
                'm' => 'La guía fue registrada correctamente'
            );
        } catch (PDOException $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'No se pudo registrar la guía: ' . $e->getMessage() . ' ' . $orden
            );
        } catch (Exception $e) {
            $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'No se pudo registrar la guía: ' . $e->getMessage()
            );
        }
    }

    static private function actualizarBoleta($conexion, $id_boleta, $id_orden, $fecha, $fecha_retorno, $nro_guia, $conductor, $despachado, $responsable, $motivo, $tras = false)
    {
        $fechaHora = $fecha . ' ' . date('H:i:s');
        // Campos base a actualizar
        $campos = [
            'fecha = :fecha',
            'fecha_retorno = :fecha_retorno',
            'id_orden = :orden',
            'nro_guia = :nro_guia',
            'id_conductor = :conductor',
            'id_despachado = :despachado',
            'id_responsable = :responsable',
            'motivo = :motivo'
        ];

        // Agregar campo "tras" solo si es verdadero
        if ($tras) {
            $campos[] = 'tras = :tras';
        }

        $sql = "UPDATE tblboleta SET " . implode(', ', $campos) . " WHERE id = :id_boleta";

        $stmtB = $conexion->prepare($sql);
        $stmtB->bindParam(':fecha', $fechaHora, PDO::PARAM_STR);
        $stmtB->bindParam(':orden', $id_orden, PDO::PARAM_INT);
        $stmtB->bindParam(':nro_guia', $nro_guia, PDO::PARAM_STR);
        $stmtB->bindParam(':motivo', $motivo, PDO::PARAM_STR);
        $stmtB->bindParam(':fecha_retorno', $fecha_retorno, PDO::PARAM_STR);
        $stmtB->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);

        if ($tras) {
            $stmtB->bindParam(':tras', $tras, PDO::PARAM_BOOL);
        }

        // Asignar valores o NULL según corresponda
        $stmtB->bindValue(':conductor', $conductor ?: null, $conductor ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmtB->bindValue(':despachado', $despachado ?: null, $despachado ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmtB->bindValue(':responsable', $responsable ?: null, $responsable ? PDO::PARAM_INT : PDO::PARAM_NULL);

        return $stmtB->execute();
    }

    static private function insertarBoleta($conexion, $id_orden, $fecha, $nro_guia, $conductor, $despachado, $responsable, $motivo, $fab = false, $tras = false)
    {
        $fechaHora = $fecha . ' ' . date('H:i:s');

        // Campos y valores base
        $campos = ['fecha', 'id_orden', 'nro_guia', 'id_conductor', 'id_despachado', 'id_responsable', 'motivo', 'fab'];
        $valores = [':fecha', ':orden', ':nro_guia', ':conductor', ':despachado', ':responsable', ':motivo', ':fabricacion'];

        // Agregar "tras" solo si es verdadero
        if ($tras) {
            $campos[] = 'tras';
            $valores[] = ':tras';
        }

        $sql = "INSERT INTO tblboleta(" . implode(', ', $campos) . ") VALUES (" . implode(', ', $valores) . ")";

        $stmtB = $conexion->prepare($sql);
        $stmtB->bindParam(':fecha', $fechaHora, PDO::PARAM_STR);
        $stmtB->bindParam(':orden', $id_orden, PDO::PARAM_INT);
        $stmtB->bindParam(':nro_guia', $nro_guia, PDO::PARAM_STR);
        $stmtB->bindParam(':motivo', $motivo, PDO::PARAM_STR);
        $stmtB->bindParam(':fabricacion', $fab, PDO::PARAM_BOOL);

        if ($tras) {
            $stmtB->bindParam(':tras', $tras, PDO::PARAM_BOOL);
        }

        // Asignar valores o NULL según corresponda
        $stmtB->bindValue(':conductor', $conductor ?: null, $conductor ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmtB->bindValue(':despachado', $despachado ?: null, $despachado ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmtB->bindValue(':responsable', $responsable ?: null, $responsable ? PDO::PARAM_INT : PDO::PARAM_NULL);

        $stmtB->execute();
        return $conexion->lastInsertId('tblboleta_id_seq');
    }



    // static private function insertarBoleta($conexion, $id_orden, $fecha, $nro_guia, $conductor, $despachado, $responsable, $motivo, $fab = false)
    // {
    //     $hora = date('H:i:s');
    //     $fechaHora = $fecha . ' ' . $hora;
    //     if ($fab) {
    //         $sql = "INSERT INTO tblboleta(fecha, id_orden, nro_guia, id_conductor, id_despachado, id_responsable, motivo, fabricacion) VALUES(:fecha, :orden, :nro_guia, :conductor, :despachado, :responsable, :motivo, true)";
    //     } else {
    //         $sql = "INSERT INTO tblboleta(fecha, id_orden, nro_guia, id_conductor, id_despachado, id_responsable, motivo) VALUES(:fecha, :orden, :nro_guia, :conductor, :despachado, :responsable, :motivo)";
    //     }
    //     $stmtB = $conexion->prepare($sql);
    //     $stmtB->bindParam(':fecha', $fechaHora, PDO::PARAM_STR);
    //     $stmtB->bindParam(':orden', $id_orden, PDO::PARAM_INT);
    //     $stmtB->bindParam(':nro_guia', $nro_guia, PDO::PARAM_STR);
    //     $stmtB->bindParam(':motivo', $motivo, PDO::PARAM_STR);

    //     if ($conductor === null || $conductor == '') {
    //         $stmtB->bindValue(':conductor', null, PDO::PARAM_NULL);
    //     } else {
    //         $stmtB->bindParam(':conductor', $conductor, PDO::PARAM_INT);
    //     }
    //     if ($despachado === null || $despachado == '') {
    //         $stmtB->bindValue(':despachado', null, PDO::PARAM_NULL);
    //     } else {
    //         $stmtB->bindParam(':despachado', $despachado, PDO::PARAM_INT);
    //     }

    //     if ($responsable === null || $responsable == '') {
    //         $stmtB->bindValue(':responsable', null, PDO::PARAM_NULL);
    //     } else {
    //         $stmtB->bindParam(':responsable', $responsable, PDO::PARAM_INT);
    //     }
    //     $stmtB->execute();
    //     return $conexion->lastInsertId();
    // }

    static private function insertarNroCotizacion($conexion, $proveedor, $comprador, $motivo, $estado_sol, $fecha)
    {
        $hora = date('H:i:s');
        $fechaHora = $fecha . ' ' . $hora;
        $stm = $conexion->prepare("INSERT INTO tblcotizacion(id_proveedor, comprador, motivo, estado_orden, fecha) VALUES(:proveedor, :comprador, :motivo, :estado_sol, :fecha)");
        $stm->bindParam(':proveedor', $proveedor, PDO::PARAM_INT);
        $stm->bindParam(':comprador', $comprador, PDO::PARAM_STR);
        $stm->bindParam(':motivo', $motivo, PDO::PARAM_STR);
        $stm->bindParam(':estado_sol', $estado_sol, PDO::PARAM_BOOL);
        $stm->bindParam(':fecha', $fechaHora, PDO::PARAM_STR);
        $stm->execute();

        $_SESSION["sc_cot"] = $conexion->lastInsertId('secuencia_cotizacion') + 1;

        return $conexion->lastInsertId('tblcotizacion_id_seq');
    }

    static private function insertarNroCompra($conexion, $proveedor, $comprador, $motivo, $estado_sol, $fecha, $subtotal, $iva, $impuesto, $total, $desc)
    {
        $hora = date('H:i:s');
        $fechaHora = $fecha . ' ' . $hora;
        $stm = $conexion->prepare("INSERT INTO tblcotizacion(id_proveedor, comprador, motivo, estado_orden, fecha, subtotal, iva, impuesto, total, otros) VALUES(:proveedor, :comprador, :motivo, :estado_sol, :fecha, :subtotal, :iva, :impuesto, :total, :des)");
        $stm->bindParam(':proveedor', $proveedor, PDO::PARAM_INT);
        $stm->bindParam(':comprador', $comprador, PDO::PARAM_STR);
        $stm->bindParam(':motivo', $motivo, PDO::PARAM_STR);
        $stm->bindParam(':estado_sol', $estado_sol, PDO::PARAM_BOOL);
        $stm->bindParam(':fecha', $fechaHora, PDO::PARAM_STR);
        $stm->bindParam(':subtotal', $subtotal, PDO::PARAM_INT);
        $stm->bindParam(':iva', $iva, PDO::PARAM_INT);
        $stm->bindParam(':impuesto', $impuesto, PDO::PARAM_INT);
        $stm->bindParam(':des', $desc, PDO::PARAM_INT);
        $stm->bindParam(':total', $total, PDO::PARAM_INT);
        $stm->execute();

        $_SESSION["sc_cot"] = $conexion->lastInsertId('secuencia_cotizacion') + 1;

        return $conexion->lastInsertId('tblcotizacion_id_seq');
    }

    static private function insertarBoletaEntrada($conexion, $id_orden, $fecha, $fecha_entrada, $motivo, $conductor, $responsable, $despachado)
    {
        $hora = date('H:i:s');
        $fechaHora = $fecha . ' ' . $hora;
        $fechaHora2 = $fecha_entrada . ' ' . $hora;

        $stmtB = $conexion->prepare("INSERT INTO tblboleta(fecha, fecha_retorno, id_orden, nro_guia, motivo, id_conductor, id_responsable, id_despachado) VALUES(:fecha, :fecha_retorno, :orden, generar_nro_guia(),:motivo, :conductor, :responsable, :despachado)");
        $stmtB->bindParam(':fecha', $fechaHora, PDO::PARAM_STR);
        $stmtB->bindParam(':fecha_retorno', $fechaHora2, PDO::PARAM_STR);
        $stmtB->bindParam(':orden', $id_orden, PDO::PARAM_INT);
        if ($conductor === null || $conductor == '') {
            $stmtB->bindValue(':conductor', null, PDO::PARAM_NULL);
        } else {
            $stmtB->bindParam(':conductor', $conductor, PDO::PARAM_INT);
        }
        if ($despachado === null || $despachado == '') {
            $stmtB->bindValue(':despachado', null, PDO::PARAM_NULL);
        } else {
            $stmtB->bindParam(':despachado', $despachado, PDO::PARAM_INT);
        }
        if ($responsable === null || $responsable == '') {
            $stmtB->bindValue(':responsable', null, PDO::PARAM_NULL);
        } else {
            $stmtB->bindParam(':responsable', $responsable, PDO::PARAM_INT);
        }
        $stmtB->bindParam(':motivo', $motivo, PDO::PARAM_STR);
        $stmtB->execute();
        return $conexion->lastInsertId('tblboleta_id_seq'); // Nota: 'tblboleta_id_seq' es el nombre de la secuencia
    }

    static private function insertarSalidas($conexion, $id_boleta, $arr)
    {
        foreach ($arr as $data) {
            list($id, $cantidad) = explode(',', $data);
            $stmtE = $conexion->prepare("INSERT INTO tblsalidas(id_boleta, cantidad_salida, id_producto) VALUES(:id_boleta, :cantidad, :id)");
            $stmtE->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
            $stmtE->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmtE->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtE->execute();
        }
    }

    static private function insertarSalidasFab($conexion, $id_boleta, $arr)
    {
        foreach ($arr as $data) {
            $id = $data['codigo'];
            $cant = $data['cantidad'];
            $stmtE = $conexion->prepare("INSERT INTO tblsalidas(id_boleta, cantidad_salida, id_producto) VALUES(:id_boleta, :cantidad, :id)");
            $stmtE->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
            $stmtE->bindParam(':cantidad', $cant, PDO::PARAM_INT);
            $stmtE->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtE->execute();
        }
    }

    static private function insertarCotizacion($conexion, $id_cotizacion, $arr)
    {
        foreach ($arr as $data) {
            $cantidad = (float)$data['cantidad'];
            $unidad = $data['id_unidad'];
            $descripcion = mb_strtoupper(trim($data['descripcion']), 'UTF-8');

            $stm = $conexion->prepare("INSERT INTO tbldetalle_cotizacion(id_cotizacion, cantidad, id_unidad, descripcion)
                VALUES(:id_cotizacion, :cantidad, :unidad, :descripcion)");

            $stm->bindParam(':id_cotizacion', $id_cotizacion, PDO::PARAM_INT);
            $stm->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stm->bindParam(':unidad', $unidad, PDO::PARAM_INT);
            $stm->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stm->execute();
        }
    }

    static private function insertarOrdenCompra($conexion, $id_cotizacion, $arr)
    {
        foreach ($arr as $data) {
            // list($cantidad, $unidad, $descripcion, $precio) = explode(',', $data);
            $cantidad = (float)$data['cantidad'];
            $unidad = $data['id_unidad'];
            $descripcion = mb_strtoupper(trim($data['descripcion']), 'UTF-8');
            $precio = (float)$data['precio_uni'];

            $stm = $conexion->prepare("INSERT INTO tbldetalle_cotizacion(id_cotizacion, cantidad, id_unidad, descripcion, precio_uni) VALUES(:id_cotizacion, :cantidad, :unidad, :descripcion, :precio)");
            $stm->bindParam(':id_cotizacion', $id_cotizacion, PDO::PARAM_INT);
            $stm->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stm->bindParam(':unidad', $unidad, PDO::PARAM_INT);
            $stm->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stm->bindParam(':precio', $precio, PDO::PARAM_INT);
            $stm->execute();
        }
    }


    static private function insertarEntradas($conexion, $id_boleta, $arr)
    {
        foreach ($arr as $data) {
            list($id, $cantidad) = explode(',', $data);
            $stmtE = $conexion->prepare("INSERT INTO tblsalidas(id_boleta, retorno, id_producto) VALUES(:id_boleta, :cantidad, :id)");
            $stmtE->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
            $stmtE->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmtE->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtE->execute();
        }
    }

    static public function mdlEditarRegistroSalida($id_boleta, $orden,  $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo, $img)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $stmtB = $conexion->prepare("UPDATE tblboleta
            SET fecha = to_timestamp(:fecha || ' ' || to_char(fecha, 'HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS'),
            id_conductor = :conductor,id_despachado = :despachado,
            id_responsable = :responsable,nro_guia = :nro_guia, id_orden = :orden, motivo=:motivo
            WHERE id=:id_boleta");

            $stmtB->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stmtB->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
            $stmtB->bindParam(':orden', $orden, PDO::PARAM_INT);
            $stmtB->bindParam(':nro_guia', $nro_guia, PDO::PARAM_STR);
            $stmtB->bindParam(':motivo', $motivo, PDO::PARAM_STR);
            $stmtB->bindParam(':conductor', $conductor, PDO::PARAM_INT);
            $stmtB->bindParam(':despachado', $despachado, PDO::PARAM_INT);
            if ($responsable === '') {
                $stmtB->bindValue(':responsable', null, PDO::PARAM_NULL);
            } else {
                $stmtB->bindParam(':responsable', $responsable, PDO::PARAM_INT);
            }
            if ($stmtB->execute()) {
                if (!empty($img)) {
                    self::guardarImagenesSalida($conexion, $id_boleta, $img);
                }
            };

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

    static public function mdlEditarRegistroCompra($id_factura, $nro_factura, $proveedor, $fecha)
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

    static public function mdlRegistrarRetorno($arr, $boleta, $fecha_retorno, $nro_guia)
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
                    // $codigo = $row['descripcion'];
                    // $salidas = $row['cantidad_salida'];
                    // if ($cantidad !== NULL && $cantidad > $salidas) {
                    //     throw new Exception("La cantidad ingresada para el producto '$codigo' excede la cantidad permitida.");
                    // }

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

    static public function mdlRegistrarProductosFab($arr, $id_producto_fab)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction(); // Comenzar transacción

            foreach ($arr as $data) {
                list($idProducto, $cantidad) = explode(',', $data);

                // Consultar el stock disponible para el producto
                $stmtStock = $conexion->prepare("SELECT (stock - stock_mal) as stock, descripcion FROM tblinventario WHERE id = :idProducto FOR UPDATE");
                $stmtStock->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
                $stmtStock->execute();
                $resultadoStock = $stmtStock->fetch(PDO::FETCH_ASSOC);

                if (!$resultadoStock || $resultadoStock['stock'] < $cantidad) {
                    throw new Exception("No hay suficiente stock disponible para el producto '{$resultadoStock['descripcion']}'");
                }

                // Insertar salida
                $stmtS = $conexion->prepare("INSERT INTO tblsalidas(cantidad_salida, id_producto, fabricado, fecha_fab, id_producto_fab) VALUES(:cantidad, :id, true, CURRENT_TIMESTAMP::timestamp without time zone, :id_producto_fab)");
                $stmtS->bindParam(':id_producto_fab', $id_producto_fab, PDO::PARAM_INT); // Asumiendo que id_producto_fab es igual a idProducto
                $stmtS->bindParam(':cantidad', $cantidad, PDO::PARAM_STR);
                $stmtS->bindParam(':id', $idProducto, PDO::PARAM_INT);
                $stmtS->execute();
            }

            $conexion->commit(); // Confirmar transacción
            return array(
                'status' => 'success',
                'm' => 'Los productos se agregaron a producción correctamente'
            );
        } catch (PDOException $e) {
            // Registro del error detallado para depuración
            error_log('Error al agregar productos a producción: ' . $e->getMessage());

            // Mensaje de error genérico para los usuarios
            return array(
                'status' => 'danger',
                'm' => 'No se pudo agregar los productos a producción. Por favor, inténtalo de nuevo más tarde.'
            );
        } catch (Exception $e) {
            // Registro del error detallado para depuración
            error_log('Error al agregar productos a producción: ' . $e->getMessage());

            // Mensaje de error específico para los usuarios
            return array(
                'status' => 'danger',
                'm' => $e->getMessage()
            );
        }
    }
}
