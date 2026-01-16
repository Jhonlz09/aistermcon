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
                u.id as unidad_id, p.id as percha_id,i.img,
                (SELECT COUNT(*) FROM tblmedidas_producto m WHERE m.id_producto = i.id) AS cantidad_medidas,
                -- === STOCK INICIAL AUTOMÁTICO (AÑO ACTUAL) ===
                COALESCE((SELECT si.stock_ini 
                    FROM tblstock_inicial si 
                    WHERE si.id_producto = i.id
                      -- AQUÍ EL CAMBIO: Extraemos el año de la fecha actual del servidor
                      AND si.anio = CAST(EXTRACT(YEAR FROM CURRENT_DATE) AS INTEGER)
                    ORDER BY si.id DESC    
                    LIMIT 1                
                ), 0) AS stock_ini
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
            $sql .= ") VALUES (:cod, :des, :sto, :st_min, :st_mal, :cat, :uni, :ubi";

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
            $a->bindParam(":sto", $sto, PDO::PARAM_INT);
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
            $anio = date('Y');
            $sql_stock_ini = "INSERT INTO tblstock_inicial(id_producto, anio, stock_ini) VALUES (:id_producto, :anio, :stock_ini)";
            $b = $conn->prepare($sql_stock_ini);
            $b->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $b->bindParam(":anio", $anio, PDO::PARAM_INT);
            $b->bindParam(":stock_ini", $sto, PDO::PARAM_STR);
            $b->execute();
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

    public static function mdlConsultarStockIniAnio($id_producto, $anio)
    {
        try {
            // Usamos la misma lógica del CTE para garantizar que coincida con la tabla
            $sql = "SELECT stock_ini FROM tblstock_inicial 
            WHERE id_producto = :id_producto AND anio = :anio ORDER BY id DESC 
            LIMIT 1";
            $stmt = Conexion::ConexionDB()->prepare($sql);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'Error al calcular stock inicial: ' . $e->getMessage()
            );
        }
    }

    public static function mdlObtenerVersionesStockInicial($id_producto, $anio)
    {
        try {
            $sql = "SELECT id, id_producto, anio, stock_ini, 
                    TO_CHAR(fecha_registro, 'DD/MM/YYYY HH24:MI:SS') as fecha_registro,
                    motivo,
                    ROW_NUMBER() OVER (ORDER BY fecha_registro DESC) as numero_version
            FROM tblstock_inicial 
            WHERE id_producto = :id_producto AND anio = :anio 
            ORDER BY fecha_registro DESC";
            $stmt = Conexion::ConexionDB()->prepare($sql);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function mdlCrearVersionStockInicial($id_producto, $anio, $stock_ini, $motivo = 'Ajuste de inventario')
    {
        try {
            $conn = Conexion::ConexionDB();
            $sql = "INSERT INTO tblstock_inicial(id_producto, anio, stock_ini, motivo, fecha_registro) 
            VALUES (:id_producto, :anio, :stock_ini, :motivo, NOW()) RETURNING id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
            $stmt->bindParam(':stock_ini', $stock_ini);
            $stmt->bindParam(':motivo', $motivo, PDO::PARAM_STR);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return array(
                'status' => 'success',
                'm' => 'Versión de stock inicial creada',
                'id_version' => $result['id']
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'Error al crear versión: ' . $e->getMessage()
            );
        }
    }

    public static function mdlActualizarMotivoCambio($id_version, $motivo)
    {
        try {
            $sql = "UPDATE tblstock_inicial SET motivo = :motivo WHERE id = :id";
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

    public static function mdlEliminarVersionStockInicial($id_version)
    {
        try {
            $sql = "DELETE FROM tblstock_inicial WHERE id = :id";
            $stmt = Conexion::ConexionDB()->prepare($sql);
            $stmt->bindParam(':id', $id_version, PDO::PARAM_INT);
            $stmt->execute();
            
            return array(
                'status' => 'success',
                'm' => 'Versión eliminada correctamente'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'Error al eliminar versión: ' . $e->getMessage()
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
            -- 1. OBTENER SOLO LA ÚLTIMA VERSIÓN DEL STOCK INICIAL REGISTRADO
            ultima_version_stock AS (
                SELECT stock_ini
                FROM tblstock_inicial
                WHERE id_producto = (SELECT p_id FROM params)
                  AND anio = (SELECT p_anio FROM params)
                ORDER BY id DESC -- Tomamos el ID más alto (última versión)
                LIMIT 1
            ),
            combined_data AS (
                -- 2. TRANSACCIONES (Salidas, Retornos, Compras)
                -- Salidas
                SELECT 
                    b.fecha::date AS fecha_raw,
                    TO_CHAR(b.fecha, 'DD/MM/YYYY') AS fecha,
                    o.id AS id_orden,
                    p.num_orden AS orden_trabajo,
                    c.nombre AS empresa,
                    s.cantidad_salida AS salida,
                    0::numeric AS entrada, -- Usamos 0 explícito para facilitar el CASE final
                    0::numeric AS compras,
                    s.id_producto_fab,
                    2 AS tipo_orden
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
                    TO_CHAR(b.fecha_retorno, 'DD/MM/YYYY') AS fecha,
                    o.id AS id_orden,
                    p.num_orden AS orden_trabajo,
                    c.nombre AS empresa,
                    0 AS salida,
                    s.retorno AS entrada,
                    0::numeric AS compras,
                    s.id_producto_fab,
                    3 AS tipo_orden
                FROM tblsalidas s
                JOIN tblboleta b ON s.id_boleta = b.id
                JOIN tblorden o ON b.id_orden = o.id
                JOIN tblpresupuesto p ON p.id = o.id
                JOIN tblclientes c ON p.id_cliente = c.id
                CROSS JOIN params
                WHERE s.id_producto = params.p_id
                    AND s.retorno <> 0.00
                    AND EXTRACT(YEAR FROM b.fecha_retorno) = params.p_anio
                UNION ALL
                -- Compras
                SELECT 
                    f.fecha::date AS fecha_raw,
                    TO_CHAR(f.fecha, 'DD/MM/YYYY') AS fecha,
                    NULL AS id_orden,
                    NULL AS orden_trabajo,
                    'COMPRA' AS empresa,
                    0 AS salida,
                    0 AS entrada,
                    e.cantidad_entrada AS compras,
                    NULL AS id_producto_fab,
                    1 AS tipo_orden
                FROM tblentradas e
                LEFT JOIN tblfactura f ON e.id_factura = f.id
                JOIN tblproveedores pr ON f.id_proveedor = pr.id
                CROSS JOIN params
                WHERE e.id_producto = params.p_id
                    AND EXTRACT(YEAR FROM f.fecha) = params.p_anio),
                aggregated_data AS (
                    SELECT 
                        cd.fecha_raw,
                        cd.fecha,
                        cd.id_orden,
                        cd.orden_trabajo,
                        cd.empresa,
                        SUM(cd.salida) AS salida,
                        SUM(cd.entrada) AS entrada,
                        SUM(cd.compras) AS compras,
                        -- Inyectamos la última versión del stock (o 0 si no existe)
                        COALESCE((SELECT stock_ini FROM ultima_version_stock), 0) AS stock_inicial,
                        MAX(cd.id_producto_fab) AS id_producto_fab,
                        cd.tipo_orden
                    FROM combined_data cd
                    GROUP BY 
                        cd.fecha_raw, cd.fecha, cd.id_orden, cd.orden_trabajo, 
                        cd.empresa, cd.tipo_orden
                ),
                final_data AS (
                SELECT 
                    ad.fecha_raw, -- La necesitamos aquí para ordenar, pero no la seleccionaremos al final
                    ad.fecha,
                    ad.orden_trabajo,
                    ad.empresa,
                    ad.salida,
                    ad.entrada,
                    ad.compras,
                    -- Cálculo Matemático del Stock Acumulado
                    SUM(ad.compras + ad.entrada - ad.salida) 
                    OVER (
                        ORDER BY ad.fecha_raw, ad.tipo_orden
                        ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
                    ) + ad.stock_inicial AS stock,
                    
                    CASE WHEN ad.id_producto_fab IS NOT NULL THEN TRUE ELSE FALSE END AS producto_util,
                    ad.tipo_orden
                FROM aggregated_data ad
            )
            -- 3. SELECCIÓN FINAL CON FORMATEO VISUAL
            SELECT 
                fecha, 
                -- NULOS A VACÍOS
                COALESCE(orden_trabajo, '') AS orden_trabajo,
                COALESCE(empresa, '') AS empresa,
                -- CEROS A GUIONES (-) O NÚMEROS FORMATEADOS
                CASE WHEN salida = 0 THEN '-' ELSE TO_CHAR(salida, 'FM999,999,990.00') END AS salida,
                CASE WHEN entrada = 0 THEN '-' ELSE TO_CHAR(entrada, 'FM999,999,990.00') END AS entrada,
                CASE WHEN compras = 0 THEN '-' ELSE TO_CHAR(compras, 'FM999,999,990.00') END AS compras,
                -- STOCK FORMATEADO
                TO_CHAR(stock, 'FM999,999,990.00') AS stock,
                producto_util
            FROM final_data
            ORDER BY fecha_raw, tipo_orden;");

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
    /*=============================================
    INSTALAR TRIGGER HISTÓRICO
    =============================================*/
    public static function mdlInstalarTriggerHistorico()
    {
        try {
            $conn = Conexion::ConexionDB();

            // 1. Crear Función
            $sqlFunc = "CREATE OR REPLACE FUNCTION public.fn_propagar_stock_historico()
            RETURNS TRIGGER AS \$\$
            DECLARE
                r_anio_transaccion INTEGER;
                r_anio_actual INTEGER;
                r_delta NUMERIC := 0;
                r_anio_iter INTEGER;
                r_ultimo_stock_ini NUMERIC;
                r_id_producto INTEGER;
                r_motivo TEXT;
                r_fecha TIMESTAMP;
            BEGIN
                r_anio_actual := EXTRACT(YEAR FROM NOW())::INTEGER;
                
                -- Determinar ID, Fecha y Delta según la tabla y operación
                IF TG_TABLE_NAME = 'tblsalidas' THEN
                    IF TG_OP = 'DELETE' THEN
                        r_id_producto := OLD.id_producto;
                        -- Obtenemos fecha de la boleta relacionada
                        SELECT fecha INTO r_fecha FROM tblboleta WHERE id = OLD.id_boleta;
                        -- Borrar salida = Aumenta el stock disponible (Delta Positivo)
                        r_delta := (OLD.cantidad_salida - COALESCE(OLD.retorno, 0));
                    ELSIF TG_OP = 'INSERT' THEN
                        r_id_producto := NEW.id_producto;
                        SELECT fecha INTO r_fecha FROM tblboleta WHERE id = NEW.id_boleta;
                        -- Nueva salida = Disminuye el stock (Delta Negativo)
                        r_delta := - (NEW.cantidad_salida - COALESCE(NEW.retorno, 0));
                    ELSIF TG_OP = 'UPDATE' THEN
                        r_id_producto := NEW.id_producto;
                        SELECT fecha INTO r_fecha FROM tblboleta WHERE id = NEW.id_boleta;
                        -- Cambio neto: (Nuevo gasto) - (Viejo gasto). Si aumenta gasto, disminuye stock.
                        r_delta := - ((NEW.cantidad_salida - COALESCE(NEW.retorno, 0)) - (OLD.cantidad_salida - COALESCE(OLD.retorno, 0)));
                    END IF;
                
                ELSIF TG_TABLE_NAME = 'tblentradas' THEN
                    IF TG_OP = 'DELETE' THEN
                        r_id_producto := OLD.id_producto;
                        -- En entradas la fecha está en tblfactura
                        SELECT fecha INTO r_fecha FROM tblfactura WHERE id = OLD.id_factura;
                        -- Borrar entrada = Disminuye stock (Delta Negativo)
                        r_delta := - OLD.cantidad_entrada;
                    ELSIF TG_OP = 'INSERT' THEN
                        r_id_producto := NEW.id_producto;
                        SELECT fecha INTO r_fecha FROM tblfactura WHERE id = NEW.id_factura;
                        -- Nueva entrada = Aumenta stock (Delta Positivo)
                        r_delta := NEW.cantidad_entrada;
                    ELSIF TG_OP = 'UPDATE' THEN
                        r_id_producto := NEW.id_producto;
                        SELECT fecha INTO r_fecha FROM tblfactura WHERE id = NEW.id_factura;
                        -- Cambio neto
                        r_delta := NEW.cantidad_entrada - OLD.cantidad_entrada;
                    END IF;
                END IF;

                -- Si no hay fecha (error integridad), usar NOW
                IF r_fecha IS NULL THEN r_fecha := NOW(); END IF;
                
                r_anio_transaccion := EXTRACT(YEAR FROM r_fecha)::INTEGER;

                -- Solo actuar si la transacción es de un año ANTERIOR al actual
                IF r_anio_transaccion < r_anio_actual AND r_delta <> 0 THEN
                    
                    r_motivo := 'Ajuste automático: Cambio en ' || TG_TABLE_NAME || ' de ' || r_anio_transaccion;

                    -- Recorrer años futuros desde (Año transaccion + 1) hasta Año Actual
                    FOR r_anio_iter IN (r_anio_transaccion + 1) .. r_anio_actual LOOP
                        
                        -- Obtener el ÚLTIMO stock inicial registrado para ese año
                        SELECT stock_ini INTO  r_ultimo_stock_ini
                        FROM tblstock_inicial
                        WHERE id_producto = r_id_producto AND anio = r_anio_iter
                        ORDER BY fecha_registro DESC
                        LIMIT 1;

                        -- Si no existe registro previo para ese año, quizás deberíamos tomar el del año anterior, 
                        -- pero para simplificar asumimos que '0' si no hay nada o ignoramos.
                        -- Aquí asumiremos que si existe historial, lo ajustamos.
                        
                        IF r_ultimo_stock_ini IS NOT NULL THEN
                           INSERT INTO tblstock_inicial (id_producto, anio, stock_ini, fecha_registro, motivo)
                           VALUES (r_id_producto, r_anio_iter, r_ultimo_stock_ini + r_delta, NOW(), r_motivo);
                        END IF;

                    END LOOP;
                END IF;

                RETURN NULL;
            END;
            \$\$ LANGUAGE plpgsql;
            ";
            $conn->exec($sqlFunc);

            // 2. Crear Triggers (Dropear si existen para evitar duplicados)
            $conn->exec("DROP TRIGGER IF EXISTS tr_propagar_stock_historico_salida ON tblsalidas");
            $conn->exec("CREATE TRIGGER tr_propagar_stock_historico_salida
                         AFTER INSERT OR UPDATE OR DELETE ON tblsalidas
                         FOR EACH ROW EXECUTE PROCEDURE public.fn_propagar_stock_historico()");

            $conn->exec("DROP TRIGGER IF EXISTS tr_propagar_stock_historico_entrada ON tblentradas");
            $conn->exec("CREATE TRIGGER tr_propagar_stock_historico_entrada
                         AFTER INSERT OR UPDATE OR DELETE ON tblentradas
                         FOR EACH ROW EXECUTE PROCEDURE public.fn_propagar_stock_historico()");

            return array('status' => 'success', 'm' => 'Triggers históricos instalados correctamente');

        } catch (PDOException $e) {
            return array('status' => 'error', 'm' => $e->getMessage());
        }
    }
}
