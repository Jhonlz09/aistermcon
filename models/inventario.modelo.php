<?php

require_once "../utils/database/conexion.php";

class ModeloInventario
{
    public static function mdlListarInventario()
    {
        try {
            $l = Conexion::ConexionDB()->prepare("SELECT i.id, i.codigo, i.descripcion, 
            c.nombre as categoria,u.nombre as unidad, p.nombre as percha, i.stock_mal, i.stock,
            '' as acciones, i.stock_min,c.id as categoria_id,u.id as unidad_id,p.id as percha_id,
            i.img, si.stock_ini
                FROM tblinventario i 
                JOIN tblcategoria c on c.id= i.id_categoria
                JOIN tblunidad u on u.id= i.id_unidad
                JOIN tblubicacion p on p.id= i.id_percha
                LEFT JOIN tblstock_inicial si on si.id_producto = i.id
                WHERE i.estado=true
                ORDER BY id ASC");
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

            $e = Conexion::ConexionDB()->prepare("SELECT i.codigo, i.codigo || ' - ' || i.descripcion AS descripcion, 
            (i.stock - i.stock_mal) AS cantidad, null as a
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


    public static function mdlConsultarHistorialProducto($id_producto, $anio)
    {
        try {
            $e = Conexion::ConexionDB()->prepare("WITH combined_data AS (
    -- Salidas
    SELECT 
        b.fecha::date AS fecha_raw, 
        TO_CHAR(b.fecha, 'DD/MM/YYYY') AS fecha,
        o.id AS id_orden, 
        o.nombre AS orden_trabajo, 
        c.nombre AS empresa,
        s.cantidad_salida AS salida, 
        NULL::numeric AS entrada, 
        NULL::numeric AS compras,
        s.id_producto_fab -- Se agrega para determinar si es utilizado
    FROM tblsalidas s
    JOIN tblboleta b ON s.id_boleta = b.id
    JOIN tblorden o ON b.id_orden = o.id
    JOIN tblclientes c ON o.id_cliente = c.id
    WHERE s.id_producto = :id_producto
        AND EXTRACT(YEAR FROM b.fecha) = :anio
    UNION ALL
    -- Retornos
    SELECT 
        b.fecha_retorno::date AS fecha_raw, 
        TO_CHAR(b.fecha_retorno, 'DD/MM/YYYY') AS fecha,
        o.id AS id_orden, 
        o.nombre AS orden_trabajo, 
        c.nombre AS empresa, 
        NULL AS salida,
        s.retorno AS entrada, 
        NULL::numeric AS compras,
        s.id_producto_fab -- Se agrega para determinar si es utilizado
    FROM tblsalidas s
    JOIN tblboleta b ON s.id_boleta = b.id
    JOIN tblorden o ON b.id_orden = o.id
    JOIN tblclientes c ON o.id_cliente = c.id
    WHERE s.id_producto = :id_producto
        AND s.retorno <> 0.00
        AND EXTRACT(YEAR FROM b.fecha_retorno) = :anio
    UNION ALL
    -- Compras
    SELECT 
        f.fecha::date AS fecha_raw, 
        TO_CHAR(f.fecha, 'DD/MM/YYYY') AS fecha,
        NULL AS id_orden, 
        NULL AS orden_trabajo, 
        pr.nombre AS empresa, 
        NULL AS salida,
        NULL AS entrada, 
        e.cantidad_entrada AS compras,
        NULL AS id_producto_fab -- No aplica para compras
    FROM tblentradas e
    LEFT JOIN tblfactura f ON e.id_factura = f.id
    JOIN tblproveedores pr ON f.id_proveedor = pr.id
    WHERE e.id_producto = :id_producto
        AND EXTRACT(YEAR FROM f.fecha) = :anio
),
aggregated_data AS (
    -- Agregar stock inicial y calcular acumulado
    SELECT 
        cd.fecha_raw, 
        cd.fecha, 
        cd.id_orden, 
        cd.orden_trabajo,
        cd.empresa,
        SUM(COALESCE(cd.salida, NULL)) AS salida,
        SUM(COALESCE(cd.entrada, NULL)) AS entrada,
        SUM(COALESCE(cd.compras, NULL)) AS compras,
        COALESCE(si.stock_ini, NULL) AS stock_inicial,
        MAX(cd.id_producto_fab) AS id_producto_fab -- Mantener si existe en algún registro
    FROM combined_data cd
    LEFT JOIN tblstock_inicial si ON si.id_producto = :id_producto AND si.anio = :anio
    GROUP BY 
        cd.fecha_raw, cd.fecha, cd.id_orden, cd.orden_trabajo, 
        cd.empresa, si.stock_ini
),
final_data AS (
    SELECT 
        ad.fecha_raw, 
        ad.fecha, 
        ad.orden_trabajo, 
        ad.empresa, 
        ad.salida, 
        ad.entrada,
        ad.compras, 
        ad.stock_inicial, 
        SUM(COALESCE(-ad.salida, 0) + COALESCE(ad.entrada, 0) + COALESCE(ad.compras, 0)) 
            OVER (ORDER BY ad.fecha_raw ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) + 
            ad.stock_inicial AS stock,
        CASE 
            WHEN ad.id_producto_fab IS NOT NULL THEN TRUE 
            ELSE FALSE 
        END AS producto_util -- Nueva columna booleana
    FROM aggregated_data ad
)
SELECT 
    fecha, 
    orden_trabajo, 
    empresa, 
    salida, 
    entrada, 
    compras, 
    stock, 
    producto_util
FROM final_data
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
