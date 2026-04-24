<?php

require_once "../utils/database/conexion.php";

class ModeloInventario
{
    public static function mdlListarInventario()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT i.id,i.codigo,
                i.descripcion, c.nombre as categoria,u.nombre as unidad,p.nombre as percha, 
                i.stock_mal,i.stock, '' as acciones,i.stock_min, c.id as categoria_id, 
                u.id as unidad_id, p.id as percha_id,i.img, i.precio_uni, i.precio_iva, i.precio_total_iva, i.valor_total_bodega,
                (SELECT COUNT(*) FROM tblmedidas_producto m WHERE m.id_producto = i.id) AS cantidad_medidas,
                -- === ELIMINADO: Stock inicial por año ===
                0 AS stock_ini
                -- =============================================
            FROM tblinventario i
            JOIN tblcategoria c on c.id= i.id_categoria
            JOIN tblunidad u on u.id= i.id_unidad
            JOIN tblubicacion p on p.id= i.id_percha
            WHERE i.estado=true
            ORDER BY i.id ASC;");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    public static function mdlListarProductoFab($id_producto_fab)
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT s.id, s.cantidad_salida,  u.nombre as unidad,
            i.descripcion, 
            to_char(s.fecha_fab, 'DD/MM/YYYY HH24:MI') AS fecha_fab, i.codigo, COALESCE(s.retorno::text, '-') AS retorno,COALESCE(s.diferencia::text, '-') as utilizado, i.id as id_producto
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

    public static function mdlAlertaStock()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT COUNT(*) AS poco_stock
            FROM tblinventario WHERE estado = true  
            AND CASE WHEN stock - stock_mal <= stock_min THEN true ELSE false END;");
            $l->execute();
            return $l->fetchAll();
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    public static function mdlObtenerMedidasProducto($id_producto)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("SELECT id, alto, ancho, cantidad, area_m2_total FROM tblmedidas_producto WHERE id_producto = :id_producto ORDER BY id ASC");
            $e->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $e->execute();
            return $e->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function mdlGuardarMedidasProducto($id_producto, $medidas, $editar = false)
    {
        try {
            $conn = Conexion::ConexionDB();
            if ($editar) {
                $conn->prepare("DELETE FROM tblmedidas_producto WHERE id_producto = :id_producto")
                    ->execute([':id_producto' => $id_producto]);
            }
            foreach ($medidas as $m) {
                $alto = $m['alto'];
                $ancho = $m['ancho'];
                $cantidad = $m['cantidad'];
                $area_m2_total = $m['area_m2_total'];
                $stmt = $conn->prepare("INSERT INTO tblmedidas_producto (id_producto, alto, ancho, cantidad, area_m2_total) VALUES (:id_producto, :alto, :ancho, :cantidad, :area_m2_total)");
                $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
                $stmt->bindParam(':alto', $alto);
                $stmt->bindParam(':ancho', $ancho);
                $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
                $stmt->bindParam(':area_m2_total', $area_m2_total);
                $stmt->execute();
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function mdlEditarMedidaProducto($id_medida, $alto, $ancho, $cantidad)
    {
        try {
            $area_m2_total = $alto * $ancho * $cantidad;
            $stmt = Conexion::ConexionDB()->prepare("UPDATE tblmedidas_producto SET alto=:alto, ancho=:ancho, cantidad=:cantidad, area_m2_total=:area_m2_total WHERE id=:id");
            $stmt->bindParam(':id', $id_medida, PDO::PARAM_INT);
            $stmt->bindParam(':alto', $alto);
            $stmt->bindParam(':ancho', $ancho);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':area_m2_total', $area_m2_total);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function mdlEliminarMedidaProducto($id_medida)
    {
        try {
            $stmt = Conexion::ConexionDB()->prepare("DELETE FROM tblmedidas_producto WHERE id=:id");
            $stmt->bindParam(':id', $id_medida, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function mdlAgregarInventario($cod, $des, $sto, $st_min, $st_mal, $cat, $uni, $ubi, $img)
    {
        try {
            $conn = Conexion::ConexionDB();
            $sql = "INSERT INTO tblinventario(codigo, descripcion, stock, stock_min, stock_mal, id_categoria, id_unidad, id_percha";
            // Agregar el campo 'img' solo si no es null
            if ($img !== null) {
                $sql .= ", img";
            }
            $sql .= ") VALUES (:cod, :des, 0, :st_min, :st_mal, :cat, :uni, :ubi";

            // Agregar el valor de la imagen solo si no es null
            if ($img !== null) {
                $sql .= ", :img";
            }
            $sql .= ")";
            // Preparar la consulta
            $a = $conn->prepare($sql);
            // Asignar los valores a los parámetros
            $a->bindParam(":cod", $cod, PDO::PARAM_STR);
            $a->bindParam(":des", $des, PDO::PARAM_STR);
            $a->bindParam(":st_min", $st_min, PDO::PARAM_INT);
            $a->bindParam(":st_mal", $st_mal, PDO::PARAM_INT);
            $a->bindParam(":cat", $cat, PDO::PARAM_INT);
            $a->bindParam(":uni", $uni, PDO::PARAM_INT);
            $a->bindParam(":ubi", $ubi, PDO::PARAM_INT);

            // Si la imagen no es null, también enlazamos su parámetro
            if ($img !== null) {
                $a->bindParam(":img", $img, PDO::PARAM_STR);
            }
            // Ejecutar la consulta
            $a->execute();
            $id_producto = $conn->lastInsertId();
            
            // Insertar el stock inicial como el primer ajuste
            if ($sto > 0) {
                $sql_stock_ini = "INSERT INTO tblajustes_inventario(id_producto, cantidad, motivo, fecha) VALUES (:id_producto, :cantidad, 'Stock inicial (Alta de producto)', NOW())";
                $b = $conn->prepare($sql_stock_ini);
                $b->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
                $b->bindParam(":cantidad", $sto, PDO::PARAM_STR);
                $b->execute();
            }
            
            return array(
                'status' => 'success',
                'm' => 'El producto se agregó correctamente'
            );
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar el producto debido a que ya existe un producto con el mismo código'
                );
            } else {
                return array(
                    'status' => 'danger',
                    'm' => 'No se pudo agregar el producto: ' . $e->getMessage()
                );
            }
        }
    }

    public static function mdlAgregarInventarioFab($des, $uni, $sto)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $a = $conexion->prepare("INSERT INTO tblinventario(codigo,descripcion,stock,id_unidad,fabricado) VALUES (generar_codigo_pf(),:des,:sto,:uni, true)");
            $a->bindParam(":des", $des, PDO::PARAM_STR);
            $a->bindParam(":uni", $uni, PDO::PARAM_INT);
            $a->bindParam(":sto", $sto, PDO::PARAM_INT);
            // $a->bindParam(":id_orden", $id_orden, PDO::PARAM_INT);

            $a->execute();
            return array(
                'status' => 'success',
                'm' => 'El producto se agregó correctamente'
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

    public static function mdlEditarInventario($id, $codigo, $des, $sto, $st_min, $st_mal, $cat, $uni, $ubi, $img)
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

    public static function mdlEliminarInventario($id)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("UPDATE tblinventario SET estado=false WHERE id=:id");
            $e->bindParam(":id", $id, PDO::PARAM_INT);
            $e->execute();
            return array(
                'status' => 'success',
                'm' => 'El producto se eliminó correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo eliminar el producto: ' . $e->getMessage()
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

            $e = Conexion::ConexionDB()->prepare("SELECT i.id, i.codigo, i.codigo || ' - ' || i.descripcion AS descripcion, 
            (i.stock - i.stock_mal) AS cantidad, i.img
                FROM tblinventario i WHERE i.estado = true 
                AND i.fabricado = false
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

    public static function mdlActualizarMotivoAjuste($id_version, $motivo)
    {
        try {
            $sql = "UPDATE tblajustes_inventario SET motivo = :motivo WHERE id = :id";
            $stmt = Conexion::ConexionDB()->prepare($sql);
            $stmt->bindParam(':id', $id_version, PDO::PARAM_INT);
            $stmt->bindParam(':motivo', $motivo, PDO::PARAM_STR);
            $stmt->execute();
            
            return array(
                'status' => 'success',
                'm' => 'Motivo actualizado correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'Error al actualizar motivo: ' . $e->getMessage()
            );
        }
    }

    public static function mdlEliminarAjusteInventario($id_version)
    {
        try {
            $sql = "DELETE FROM tblajustes_inventario WHERE id = :id";
            $stmt = Conexion::ConexionDB()->prepare($sql);
            $stmt->bindParam(':id', $id_version, PDO::PARAM_INT);
            $stmt->execute();
            
            return array(
                'status' => 'success',
                'm' => 'Ajuste eliminado correctamente (el stock se recalculó automáticamente)'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'Error al eliminar ajuste: ' . $e->getMessage()
            );
        }
    }

    public static function mdlObtenerHistorialAjustes($id_producto)
    {
        try {
            $sql = "SELECT id, id_producto, cantidad as delta, 
                    TO_CHAR(fecha, 'DD/MM/YYYY HH24:MI:SS') as fecha_registro,
                    motivo,
                    ROW_NUMBER() OVER (ORDER BY fecha DESC) as numero_version
            FROM tblajustes_inventario 
            WHERE id_producto = :id_producto
            ORDER BY fecha DESC";
            $stmt = Conexion::ConexionDB()->prepare($sql);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function mdlCrearAjusteInventario($id_producto, $stock_fisico, $motivo = 'Ajuste de inventario')
    {
        try {
            $conn = Conexion::ConexionDB();
            
            // Obtener el stock actual
            $stmt_stock = $conn->prepare("SELECT stock FROM tblinventario WHERE id = :id");
            $stmt_stock->bindParam(':id', $id_producto, PDO::PARAM_INT);
            $stmt_stock->execute();
            $stock_actual = $stmt_stock->fetchColumn();
            
            $delta = floatval($stock_fisico) - floatval($stock_actual);
            
            if ($delta == 0) {
                return array('status' => 'success', 'm' => 'El stock físico es igual al actual. No se generó ajuste.');
            }

            $sql = "INSERT INTO tblajustes_inventario(id_producto, cantidad, motivo, fecha) 
            VALUES (:id_producto, :cantidad, :motivo, NOW()) RETURNING id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $delta);
            $stmt->bindParam(':motivo', $motivo, PDO::PARAM_STR);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return array(
                'status' => 'success',
                'm' => 'Ajuste de inventario registrado correctamente',
                'id_version' => $result['id']
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'Error al crear ajuste: ' . $e->getMessage()
            );
        }
    }

    public static function mdlConsultarHistorialProducto($id_producto, $anio)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("WITH params AS (
                SELECT 
                    CAST(:id_producto AS INTEGER) AS p_id, 
                    CAST(:anio AS INTEGER) AS p_anio
            ),
            -- 1. CALCULAR STOCK INICIAL DINÁMICO (Todo antes del año)
            stock_historico AS (
                SELECT 
                    COALESCE(SUM(cantidad), 0) AS suma_ajustes
                FROM tblajustes_inventario 
                WHERE id_producto = (SELECT p_id FROM params)
                  AND EXTRACT(YEAR FROM fecha) < (SELECT p_anio FROM params)
            ),
            entradas_historicas AS (
                SELECT COALESCE(SUM(e.cantidad_entrada), 0) AS suma_entradas
                FROM tblentradas e
                JOIN tblfactura f ON e.id_factura = f.id
                WHERE e.id_producto = (SELECT p_id FROM params)
                  AND EXTRACT(YEAR FROM f.fecha) < (SELECT p_anio FROM params)
            ),
            salidas_historicas AS (
                SELECT COALESCE(SUM(s.cantidad_salida - COALESCE(s.retorno, 0)), 0) AS suma_salidas
                FROM tblsalidas s
                JOIN tblboleta b ON s.id_boleta = b.id
                WHERE s.id_producto = (SELECT p_id FROM params)
                  AND EXTRACT(YEAR FROM b.fecha) < (SELECT p_anio FROM params)
            ),
            stock_base AS (
                SELECT (sh.suma_ajustes + eh.suma_entradas - sah.suma_salidas) AS stock_inicial
                FROM stock_historico sh
                CROSS JOIN entradas_historicas eh
                CROSS JOIN salidas_historicas sah
            ),
            combined_data AS (
                -- Salidas
                SELECT 
                    b.fecha::date AS fecha_raw,
                    b.fecha AS fecha_time,
                    TO_CHAR(b.fecha, 'DD/MM/YYYY') AS fecha,
                    o.id AS id_orden,
                    p.num_orden AS orden_trabajo,
                    c.nombre AS empresa,
                    s.cantidad_salida AS salida,
                    0::numeric AS entrada,
                    0::numeric AS compras,
                    0::numeric AS ajustes,
                    s.id_producto_fab,
                    2 AS tipo_orden,
                    'SALIDA' AS descripcion_mov
                FROM tblsalidas s
                JOIN tblboleta b ON s.id_boleta = b.id
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblpresupuesto p ON p.id = o.id
                JOIN tblclientes c ON p.id_cliente = c.id
                CROSS JOIN params
                WHERE s.id_producto = params.p_id
                    AND EXTRACT(YEAR FROM b.fecha) = params.p_anio
                UNION ALL
                -- Retornos
                SELECT 
                    b.fecha_retorno::date AS fecha_raw,
                    b.fecha_retorno AS fecha_time,
                    TO_CHAR(b.fecha_retorno, 'DD/MM/YYYY') AS fecha,
                    o.id AS id_orden,
                    p.num_orden AS orden_trabajo,
                    c.nombre AS empresa,
                    0 AS salida,
                    s.retorno AS entrada,
                    0::numeric AS compras,
                    0::numeric AS ajustes,
                    s.id_producto_fab,
                    3 AS tipo_orden,
                    'RETORNO' AS descripcion_mov
                FROM tblsalidas s
                JOIN tblboleta b ON s.id_boleta = b.id
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblpresupuesto p ON p.id = o.id
                JOIN tblclientes c ON p.id_cliente = c.id
                CROSS JOIN params
                WHERE s.id_producto = params.p_id
                    AND s.retorno > 0
                    AND EXTRACT(YEAR FROM b.fecha_retorno) = params.p_anio
                UNION ALL
                -- Compras
                SELECT 
                    f.fecha::date AS fecha_raw,
                    f.fecha AS fecha_time,
                    TO_CHAR(f.fecha, 'DD/MM/YYYY') AS fecha,
                    NULL AS id_orden,
                    NULL AS orden_trabajo,
                    'COMPRA' AS empresa,
                    0 AS salida,
                    0 AS entrada,
                    e.cantidad_entrada AS compras,
                    0::numeric AS ajustes,
                    NULL AS id_producto_fab,
                    1 AS tipo_orden,
                    'COMPRA' AS descripcion_mov
                FROM tblentradas e
                LEFT JOIN tblfactura f ON e.id_factura = f.id
                CROSS JOIN params
                WHERE e.id_producto = params.p_id
                    AND EXTRACT(YEAR FROM f.fecha) = params.p_anio
                UNION ALL
                -- Ajustes
                SELECT 
                    a.fecha::date AS fecha_raw,
                    a.fecha AS fecha_time,
                    TO_CHAR(a.fecha, 'DD/MM/YYYY') AS fecha,
                    NULL AS id_orden,
                    NULL AS orden_trabajo,
                    'AJUSTE' AS empresa,
                    0 AS salida,
                    0 AS entrada,
                    0 AS compras,
                    a.cantidad AS ajustes,
                    NULL AS id_producto_fab,
                    4 AS tipo_orden,
                    a.motivo AS descripcion_mov
                FROM tblajustes_inventario a
                CROSS JOIN params
                WHERE a.id_producto = params.p_id
                    AND EXTRACT(YEAR FROM a.fecha) = params.p_anio
            ),
            aggregated_data AS (
                SELECT 
                    cd.fecha_raw,
                    cd.fecha_time,
                    cd.fecha,
                    cd.id_orden,
                    cd.orden_trabajo,
                    cd.empresa,
                    SUM(cd.salida) AS salida,
                    SUM(cd.entrada) AS entrada,
                    SUM(cd.compras) AS compras,
                    SUM(cd.ajustes) AS ajustes,
                    MAX(cd.id_producto_fab) AS id_producto_fab,
                    cd.tipo_orden,
                    MAX(cd.descripcion_mov) AS descripcion_mov
                FROM combined_data cd
                GROUP BY 
                    cd.fecha_raw, cd.fecha_time, cd.fecha, cd.id_orden, cd.orden_trabajo, 
                    cd.empresa, cd.tipo_orden
            ),
            final_data AS (
                SELECT 
                    ad.fecha_raw,
                    ad.fecha_time,
                    ad.fecha,
                    ad.orden_trabajo,
                    ad.empresa,
                    ad.salida,
                    ad.entrada,
                    ad.compras,
                    ad.ajustes,
                    -- Cálculo Matemático del Stock Acumulado
                    SUM(ad.compras + ad.entrada + ad.ajustes - ad.salida) 
                    OVER (
                        ORDER BY ad.fecha_time, ad.tipo_orden
                        ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
                    ) + COALESCE((SELECT stock_inicial FROM stock_base), 0) AS stock,
                    
                    CASE WHEN ad.id_producto_fab IS NOT NULL THEN TRUE ELSE FALSE END AS producto_util,
                    ad.tipo_orden,
                    ad.descripcion_mov
                FROM aggregated_data ad
            )
            -- 3. SELECCIÓN FINAL CON FORMATEO VISUAL
            SELECT 
                fecha, 
                COALESCE(orden_trabajo, '') AS orden_trabajo,
                COALESCE(empresa, '') AS empresa,
                CASE WHEN salida = 0 AND ajustes >= 0 THEN '-' 
                     WHEN ajustes < 0 THEN TO_CHAR(ABS(ajustes), 'FM999,999,990.00') 
                     ELSE TO_CHAR(salida, 'FM999,999,990.00') 
                END AS salida,
                CASE WHEN entrada = 0 AND ajustes <= 0 THEN '-' ELSE 
                    TO_CHAR(entrada + CASE WHEN ajustes > 0 THEN ajustes ELSE 0 END, 'FM999,999,990.00') 
                END AS entrada,
                CASE WHEN compras = 0 THEN '-' ELSE TO_CHAR(compras, 'FM999,999,990.00') END AS compras,
                TO_CHAR(stock, 'FM999,999,990.00') AS stock,
                producto_util,
                descripcion_mov,
                ajustes
            FROM final_data
            ORDER BY fecha_time, tipo_orden;");

            $e->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $e->bindParam(':anio', $anio, PDO::PARAM_INT);
            $e->execute();
            
            $result = $e->fetchAll(PDO::FETCH_ASSOC);
            
            // Fetch baseline stock for info
            $sb = Conexion::ConexionDB()->prepare("
            WITH params AS (
                SELECT CAST(:id_producto AS INTEGER) AS p_id, CAST(:anio AS INTEGER) AS p_anio
            ),
            stock_historico AS (
                SELECT COALESCE(SUM(cantidad), 0) AS suma_ajustes
                FROM tblajustes_inventario WHERE id_producto = (SELECT p_id FROM params) AND EXTRACT(YEAR FROM fecha) < (SELECT p_anio FROM params)
            ),
            entradas_historicas AS (
                SELECT COALESCE(SUM(e.cantidad_entrada), 0) AS suma_entradas
                FROM tblentradas e JOIN tblfactura f ON e.id_factura = f.id WHERE e.id_producto = (SELECT p_id FROM params) AND EXTRACT(YEAR FROM f.fecha) < (SELECT p_anio FROM params)
            ),
            salidas_historicas AS (
                SELECT COALESCE(SUM(s.cantidad_salida - COALESCE(s.retorno, 0)), 0) AS suma_salidas
                FROM tblsalidas s JOIN tblboleta b ON s.id_boleta = b.id WHERE s.id_producto = (SELECT p_id FROM params) AND EXTRACT(YEAR FROM b.fecha) < (SELECT p_anio FROM params)
            )
            SELECT (sh.suma_ajustes + eh.suma_entradas - sah.suma_salidas) AS stock_inicial
            FROM stock_historico sh CROSS JOIN entradas_historicas eh CROSS JOIN salidas_historicas sah");
            $sb->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $sb->bindParam(':anio', $anio, PDO::PARAM_INT);
            $sb->execute();
            $stock_base = $sb->fetchColumn();

            return [
                'stock_inicial' => $stock_base !== false ? number_format((float)$stock_base, 2, '.', ',') : '0.00',
                'data' => $result
            ];
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo obtener el producto: ' . $e->getMessage()
            );
        }
    }
    

    public static function mdlObtenerIvaConfiguracion()
    {
        try {
            $stmt = Conexion::ConexionDB()->prepare("SELECT iva FROM tblconfiguracion LIMIT 1");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['iva' => 0];
        }
    }

    public static function mdlListarHistorialPrecios($id_producto)
    {
        try {
            $stmt = Conexion::ConexionDB()->prepare("SELECT id_producto, precio_uni, precio_iva, precio_total_iva, motivo, TO_CHAR(fecha, 'DD/MM/YYYY HH24:MI:SS') as fecha FROM tblhistorial_precios WHERE id_producto = :id_producto ORDER BY fecha DESC");
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function mdlActualizarPrecio($id_producto, $precio_uni, $motivo)
    {
        try {
            $conn = Conexion::ConexionDB();
            
            // VERIFICAR REDUNDANCIA DE PRECIO
            $stmtCheck = $conn->prepare("SELECT precio_uni FROM tblinventario WHERE id = :id");
            $stmtCheck->bindParam(':id', $id_producto, PDO::PARAM_INT);
            $stmtCheck->execute();
            $currRow = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if ($currRow && floatval($currRow['precio_uni']) == floatval($precio_uni)) {
                return array('status' => 'warning', 'm' => 'El precio ingresado es igual al actual. No se realizaron cambios redundantes.');
            }
            
            $stmtIva = $conn->prepare("SELECT iva FROM tblconfiguracion LIMIT 1");
            $stmtIva->execute();
            $ivaRow = $stmtIva->fetch(PDO::FETCH_ASSOC);
            $porcentaje_iva = $ivaRow ? floatval($ivaRow['iva']) : 0;
            
            $calc_iva = $precio_uni * ($porcentaje_iva / 100);
            $calc_total = $precio_uni + $calc_iva;

            $stmtUpdatePrecio = $conn->prepare("UPDATE tblinventario SET precio_uni = :p_uni, precio_iva = :p_iva, precio_total_iva = :p_total WHERE id = :id");
            $stmtUpdatePrecio->bindParam(':id', $id_producto, PDO::PARAM_INT);
            $stmtUpdatePrecio->bindParam(':p_uni', $precio_uni, PDO::PARAM_STR);
            $stmtUpdatePrecio->bindParam(':p_iva', $calc_iva, PDO::PARAM_STR);
            $stmtUpdatePrecio->bindParam(':p_total', $calc_total, PDO::PARAM_STR);
            $stmtUpdatePrecio->execute();

            $stmtHistorial = $conn->prepare("INSERT INTO tblhistorial_precios (id_producto, precio_uni, precio_iva, precio_total_iva, motivo) VALUES (:id, :p_uni, :p_iva, :p_total, :motivo)");
            $stmtHistorial->bindParam(':id', $id_producto, PDO::PARAM_INT);
            $stmtHistorial->bindParam(':p_uni', $precio_uni, PDO::PARAM_STR);
            $stmtHistorial->bindParam(':p_iva', $calc_iva, PDO::PARAM_STR);
            $stmtHistorial->bindParam(':p_total', $calc_total, PDO::PARAM_STR);
            $stmtHistorial->bindParam(':motivo', $motivo, PDO::PARAM_STR);
            $stmtHistorial->execute();

            return array('status' => 'success', 'm' => 'Precio actualizado y registrado en el historial');
        } catch (PDOException $e) {
            return array('status' => 'danger', 'm' => 'Error al actualizar el precio: ' . $e->getMessage());
        }
    }

    public static function mdlCalcularValorTotal()
    {
        try {
            $conn = Conexion::ConexionDB();
            
            // Total Global
            $stmtGlobal = $conn->prepare("SELECT SUM(valor_total_bodega) as valor_total FROM tblinventario WHERE estado = true");
            $stmtGlobal->execute();
            $global = $stmtGlobal->fetch(PDO::FETCH_ASSOC);

            // Total por Categoría
            $stmtCat = $conn->prepare("
                SELECT c.nombre AS categoria, SUM(i.valor_total_bodega) as valor_total
                FROM tblinventario i
                LEFT JOIN tblcategoria c ON i.id_categoria = c.id
                WHERE i.estado = true AND i.valor_total_bodega > 0
                GROUP BY c.nombre
                ORDER BY valor_total DESC
            ");
            $stmtCat->execute();
            $categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

            return [
                'valor_total' => $global['valor_total'] ?? 0,
                'categorias' => $categorias
            ];
        } catch (PDOException $e) {
            return ['valor_total' => 0, 'categorias' => []];
        }
    }

}
