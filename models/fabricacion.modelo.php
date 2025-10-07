<?php

require_once "../utils/database/conexion.php";

class ModeloFabricacion
{
    public static function mdlListarGuiaProdFab()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT id, descripcion, cantidad, id_unidad
	FROM public.tblpro_fab");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    public static function mdlListarProductoFab($id_producto_fab)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT i.id, s.cantidad_salida,  u.nombre as unidad,
            i.descripcion, 
            to_char(s.fecha_fab, 'DD/MM/YYYY HH24:MI') AS fecha_fab, i.codigo, COALESCE(s.retorno::text, '-') AS retorno,COALESCE(s.diferencia::text, '-') as utilizado
            FROM tblsalidas s
            JOIN tblinventario i ON i.id = s.id_producto
            JOIN tblunidad u ON u.id = i.id_unidad
            WHERE s.id_producto_fab = :id_producto_fab");
            $l->bindParam(":id_producto_fab", $id_producto_fab, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }


    public static function mdlListarProductoFabUtil($id_producto_fab)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id, s.cantidad_salida,  u.nombre as unidad,
            i.descripcion, 
            to_char(s.fecha_fab, 'DD/MM/YYYY HH24:MI') AS fecha_fab, i.codigo, COALESCE(s.retorno::text, '-') AS retorno,COALESCE(s.diferencia::text, '-') as utilizado
            FROM tblsalidas s
            JOIN tblinventario i ON i.id = s.id_producto
            JOIN tblunidad u ON u.id = i.id_unidad
            WHERE s.id_producto_fab = :id_producto_fab");
            $l->bindParam(":id_producto_fab", $id_producto_fab, PDO::PARAM_INT);
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    public static function mdlListarInventarioStock()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT i.id, i.codigo, i.descripcion, 
            c.nombre AS categoria, u.nombre AS unidad, p.nombre AS percha, i.stock_mal, 
                i.stock, '' AS acciones, i.stock_min, c.id AS categoria_id, u.id AS unidad_id, p.id AS percha_id, img
                FROM tblinventario i 
                JOIN tblcategoria c ON c.id = i.id_categoria
                JOIN tblunidad u ON u.id = i.id_unidad
                JOIN tblubicacion p ON p.id = i.id_percha
                WHERE 
                    i.estado = true
                    AND (i.stock - i.stock_mal) <= i.stock_min
                ORDER BY id ASC;");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    public static function mdlAgregarProdFabricado($id_boleta)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblinventario(codigo,descripcion,stock,id_unidad,fabricado) VALUES (generar_codigo_pf(),'',1,1, true)");
            if ($a->execute()) {
                $id_producto_fab = $conexion->lastInsertId('tblinventario_id_seq');
                $stmtSalida = $conexion->prepare("INSERT INTO tblsalidas(id_boleta, retorno, id_producto, fabricado) VALUES(:id_boleta, 1, :id, true)");
                $stmtSalida->bindParam(':id', $id_producto_fab, PDO::PARAM_INT);
                $stmtSalida->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
                $stmtSalida->execute();
            };
            return array(
                'status' => 'success',
                'm' => 'El producto se agregó correctamente',
                'id' => $id_producto_fab
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar el producto debido a que ya existe un producto con el mismo codigo' . $e->getMessage()
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar el producto: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlAgregarProdUtilFab($id_producto, $id_boleta, $id_producto_fab)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction(); // Iniciar una transacción

            // Verificar el stock disponible del producto
            $stmtStock = $conexion->prepare("SELECT (stock - stock_mal) as stock, descripcion FROM tblinventario WHERE id = :id_producto");
            $stmtStock->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmtStock->execute();
            $resultadoStock = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if (!$resultadoStock) {
                throw new Exception("El producto con ID '$id_producto' no existe en el inventario.");
            }

            if ($resultadoStock['stock'] <= 0) {
                throw new Exception("No hay suficiente stock disponible para el producto '{$resultadoStock['descripcion']}'.");
            }

            // Si hay stock disponible, proceder con la inserción
            $a = $conexion->prepare("INSERT INTO tblsalidas(id_boleta, cantidad_salida, id_producto, id_producto_fab) 
                                    VALUES(:id_boleta, 0, :id_producto, :id_producto_fab)");
            $a->bindParam(':id_producto_fab', $id_producto_fab, PDO::PARAM_INT);
            $a->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
            $a->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $a->execute();

            $conexion->commit(); // Confirmar la transacción

            return array(
                'status' => 'success',
                'm' => 'El producto se agregó correctamente',
                'id' => $conexion->lastInsertId('tblsalidas_id_seq')
            );
        } catch (Exception $e) {
            $conexion->rollBack(); // Revertir la transacción en caso de error
            return array(
                'status' => 'danger',
                'm' => 'Error: ' . $e->getMessage()
            );
        }
    }

    public static function mdlEditarInventarioFab($id, $des, $uni, $sto, $id_orden)
    {
        try {
            $conexion = Conexion::ConexionDB();
            // Cambiar la consulta SQL para hacer un UPDATE
            $a = $conexion->prepare("UPDATE tblinventario SET descripcion = :des, stock = :sto, id_unidad = :uni WHERE id = :id");
            // Vincular los parámetros
            $a->bindParam(":id", $id, PDO::PARAM_INT);
            $a->bindParam(":des", $des, PDO::PARAM_STR);
            $a->bindParam(":uni", $uni, PDO::PARAM_INT);
            $a->bindParam(":sto", $sto, PDO::PARAM_INT);
            // $a->bindParam(":id_orden", $id_orden, PDO::PARAM_INT);

            // Ejecutar la consulta
            $a->execute();

            // Retornar el resultado
            return array(
                'status' => 'success',
                'm' => 'El producto se editó correctamente'
            );
        } catch (PDOException $e) {
            // Manejar el error
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar el producto: ' . $e->getMessage()
            );
        }
    }

    public static function mdlEditarInventario($id, $codigo, $des, $sto, $st_min, $st_mal, $cat, $uni, $ubi, $img, $st_ini)
    {
        try {
            $conn = Conexion::ConexionDB();

            // Construir la consulta base
            $sql = "UPDATE tblinventario SET codigo=:codigo, descripcion=:des, stock=:sto, stock_min=:st_min, stock_mal=:st_mal, id_categoria=:cat, id_unidad=:uni, id_percha=:ubi";
            // Agregar el campo 'img' solo si no es null
            if ($img !== 'null') {
                $sql .= ", img=:img";
            }
            $sql .= " WHERE id=:id";
            $e = $conn->prepare($sql);
            // Asignar los valores a los parámetros
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $e->bindParam(":des", $des, PDO::PARAM_STR);
            $e->bindParam(":sto", $sto, PDO::PARAM_STR);
            $e->bindParam(":st_min", $st_min, PDO::PARAM_INT);
            $e->bindParam(":st_mal", $st_mal, PDO::PARAM_INT);
            $e->bindParam(":cat", $cat, PDO::PARAM_INT);
            $e->bindParam(":uni", $uni, PDO::PARAM_INT);
            $e->bindParam(":ubi", $ubi, PDO::PARAM_INT);
            // Si la imagen no es null, también enlazamos su parámetro
            if ($img !== 'null') {
                $e->bindParam(":img", $img, PDO::PARAM_STR);
            }
            $e->execute();
            $anio = date('Y');
            $sql_stock_ini = "UPDATE tblstock_inicial SET stock_ini=:stock_ini WHERE id_producto=:id_producto AND anio=:anio";
            $b = $conn->prepare($sql_stock_ini);
            $b->bindParam(":id_producto", $id, PDO::PARAM_INT);
            $b->bindParam(":anio", $anio, PDO::PARAM_INT);
            $b->bindParam(":stock_ini", $st_ini, PDO::PARAM_INT);
            $b->execute();


            return array(
                'status' => 'success',
                'm' => 'El producto se editó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el producto debido a que ya existe un producto con el mismo código'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo editar el producto: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlEliminarProductoFab($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("DELETE FROM tblinventario WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'El producto fabricado se eliminó correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar el producto fabricado: ' . $e->getMessage()
            );
        }
    }

    public static function mdlEliminarProdUtil($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("DELETE FROM tblsalidas WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'El producto utilizado se eliminó correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar el producto utilizado: ' . $e->getMessage()
            );
        }
    }

    public static function mdlBuscarCodigo($codigo)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT i.id, i.descripcion,'1' as cantidad, u.nombre, '' as acciones, i.codigo,
            CASE 
                WHEN i.fabricado THEN 'disabled' ELSE ''
                    END as fabricado
            FROM tblinventario i 
            JOIN tblunidad u on i.id_unidad = u.id
            WHERE i.codigo = :codigo");
            $e->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $e->execute();

            return $e->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo obtener el producto: ' . $e->getMessage()
            );
        }
    }

    public static function mdlIsCodigoExits($codigo)
    {
        try {
            $stmt = Conexion::ConexionDB()->prepare("SELECT COUNT(*) FROM tblinventario WHERE codigo = :codigo");
            $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo validar el codigo: ' . $e->getMessage()
            );
        }
    }

    public static function mdlIsCodigoExitsEnOtroProducto($codigo, $id)
    {
        try {
            // Preparar la consulta para verificar si el código ya existe en otro producto
            $stmt = Conexion::ConexionDB()->prepare("SELECT COUNT(*) FROM tblinventario WHERE codigo = :codigo AND id != :id");
            $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo validar el codigo: ' . $e->getMessage()
            );
        }
    }

    public static function mdlBuscarId($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT i.id, i.descripcion,'1' as cantidad, u.nombre, '0.00' as precio, '' as acciones
            FROM tblinventario i 
            JOIN tblunidad u on i.id_unidad = u.id
            WHERE i.id = :id");

            $e->bindParam(":id", $id, PDO::PARAM_STR);
            $e->execute();
            return $e->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo obtener el producto: ' . $e->getMessage()
            );
        }
    }

    public static function mdlBuscarProductos()
    {
        try {

            $e = Conexion::ConexionDB()->prepare("SELECT i.codigo, i.codigo || ' - ' || i.descripcion AS descripcion, 
            (i.stock - i.stock_mal) AS cantidad, '' as a
                FROM tblinventario i WHERE i.estado = true 
                AND NOT (i.fabricado = true AND i.stock = 0)
                ORDER BY i.descripcion;");
            $e->execute();
            return $e->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo obtener el producto: ' . $e->getMessage()
            );
        }
    }

    public static function mdlConsultarStockIniAnio($id_producto, $anio)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT stock_ini
                    FROM tblstock_inicial  
                    WHERE id_producto = :id_producto AND anio = :anio;");
            $e->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $e->bindParam(':anio', $anio, PDO::PARAM_INT);
            $e->execute();
            return $e->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo obtener el producto: ' . $e->getMessage()
            );
        }
    }

    public static function mdlListarProdFabAndUtil($id_boleta)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT s.id, i.descripcion, u.nombre AS unidad,
            s.cantidad_salida AS salidas, s.retorno, i.codigo, 
            COALESCE(s.diferencia::text, '-') as utilizado, s.fabricado, b.tras
            FROM tblsalidas s
                JOIN tblinventario i ON s.id_producto = i.id
                JOIN tblboleta b ON s.id_boleta = b.id 
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblunidad u ON i.id_unidad = u.id
            WHERE 
                b.id = :id_boleta
            ORDER BY 
                CASE 
                    WHEN s.fabricado THEN i.id
                    ELSE s.id_producto_fab
                END,
                s.fabricado DESC,
            	i.descripcion ASC,
                s.id;");
            $e->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
            $e->execute();
            return $e->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo obtener el producto: ' . $e->getMessage()
            );
        }
    }

    public static function mdlConsultarHistorialProducto($id_producto, $anio)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT fecha, orden_trabajo, empresa, salida,      entrada, compras, stock
                FROM mv_stock_producto_anual
                WHERE id_producto = :id_producto
                  AND anio = :anio
                ORDER BY fecha_raw;");
            $e->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $e->bindParam(':anio', $anio, PDO::PARAM_INT);
            $e->execute();
            return $e->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo obtener el producto: ' . $e->getMessage()
            );
        }
    }
}
