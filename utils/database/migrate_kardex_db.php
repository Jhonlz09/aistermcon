<?php
require_once __DIR__ . '/conexion.php';

try {
    $conn = Conexion::ConexionDB();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();

    echo "1. Creando tabla tblajustes_inventario...\n";
    $conn->exec("
        CREATE TABLE IF NOT EXISTS public.tblajustes_inventario (
            id SERIAL PRIMARY KEY,
            id_producto INTEGER NOT NULL,
            cantidad NUMERIC(15,2) NOT NULL,
            motivo TEXT NOT NULL DEFAULT 'Ajuste de inventario',
            fecha TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
            CONSTRAINT fk_ajuste_producto FOREIGN KEY (id_producto)
                REFERENCES public.tblinventario (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE CASCADE
        );
    ");

    echo "2. Eliminando triggers antiguos...\n";
    $conn->exec("DROP TRIGGER IF EXISTS tr_propagar_stock_historico_salida ON tblsalidas;");
    $conn->exec("DROP TRIGGER IF EXISTS tr_propagar_stock_historico_entrada ON tblentradas;");
    $conn->exec("DROP FUNCTION IF EXISTS public.fn_propagar_stock_historico();");

    echo "3. Homologando columnas en base de datos para prevenir perdida de fraccionales (NUMERIC)...\n";
    $conn->exec("DROP VIEW IF EXISTS vista_inventario_completa;");
    $conn->exec("ALTER TABLE tblinventario DROP COLUMN IF EXISTS valor_total_bodega;");
    $conn->exec("ALTER TABLE tblentradas DROP COLUMN IF EXISTS precio_total_iva;");
    
    $conn->exec("ALTER TABLE tblinventario ALTER COLUMN stock_ini TYPE numeric(15,2);");
    $conn->exec("ALTER TABLE tblinventario ALTER COLUMN stock_mal TYPE numeric(15,2);");
    $conn->exec("ALTER TABLE tblinventario ALTER COLUMN stock_min TYPE numeric(15,2);");
    $conn->exec("ALTER TABLE tblentradas ALTER COLUMN precio_total TYPE numeric(15,5) USING precio_total::numeric;");
    $conn->exec("ALTER TABLE tblentradas ALTER COLUMN precio_iva TYPE numeric(15,5) USING precio_iva::numeric;");
    
    $conn->exec("
        ALTER TABLE tblinventario ADD COLUMN valor_total_bodega numeric(15,5) 
        GENERATED ALWAYS AS (((stock - COALESCE(stock_mal, 0.00)) * COALESCE(precio_total_iva, 0.00))) STORED;
    ");

    $conn->exec("
        ALTER TABLE tblentradas ADD COLUMN precio_total_iva numeric(15,5) 
        GENERATED ALWAYS AS ((precio_total + precio_iva)) STORED;
    ");
    
    $conn->exec("
        CREATE VIEW vista_inventario_completa AS
        SELECT i.id,
            i.codigo,
            i.descripcion,
            c.nombre AS categoria,
            u.nombre AS unidad,
            p.nombre AS percha,
            i.stock_mal,
            i.stock,
            ''::text AS acciones,
            i.stock_min,
            c.id AS categoria_id,
            u.id AS unidad_id,
            p.id AS percha_id,
            i.img
        FROM tblinventario i
            JOIN tblcategoria c ON c.id = i.id_categoria
            JOIN tblunidad u ON u.id = i.id_unidad
            JOIN tblubicacion p ON p.id = i.id_percha
        WHERE i.estado = true
        ORDER BY i.id;
    ");

    
    echo "4. Recalculando baselines y migrando historial a Kardex Matemático...\n";
    
    // Obtenemos todos los productos y calculamos su desbalance matemático real
    $stmt = $conn->query("
        WITH total_entradas AS (
            SELECT id_producto, COALESCE(SUM(cantidad_entrada), 0) as total FROM tblentradas GROUP BY id_producto
        ),
        total_salidas AS (
            SELECT id_producto, COALESCE(SUM(cantidad_salida - COALESCE(retorno, 0)), 0) as total FROM tblsalidas GROUP BY id_producto
        )
        SELECT 
            i.id,
            i.stock as stock_real,
            COALESCE(e.total, 0) as entradas_totales,
            COALESCE(s.total, 0) as salidas_totales
        FROM tblinventario i
        LEFT JOIN total_entradas e ON i.id = e.id_producto
        LEFT JOIN total_salidas s ON i.id = s.id_producto
    ");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $insertAjuste = $conn->prepare("
        INSERT INTO tblajustes_inventario (id_producto, cantidad, motivo, fecha) 
        VALUES (:id_producto, :cantidad, 'STOCK INICIAL BASELINE (MIGRACION MATEMATICA)', '2000-01-01 00:00:00')
    ");

    foreach ($productos as $p) {
        $id = $p['id'];
        $stock_real = floatval($p['stock_real']);
        $entradas = floatval($p['entradas_totales']);
        $salidas = floatval($p['salidas_totales']);
        
        // Stock_Real = Ajustes + Entradas - Salidas
        // Ajustes = Stock_Real - Entradas + Salidas
        $ajuste_baseline = $stock_real - $entradas + $salidas;
        
        if (true) { 
            // Siempre insertamos un baseline aunque sea 0, para tener anclaje en el historial
            $insertAjuste->execute([
                ':id_producto' => $id,
                ':cantidad' => $ajuste_baseline
            ]);
        }
    }

    echo "5. Creando trigger de Ajustes de Inventario...\n";
    $conn->exec("
        CREATE OR REPLACE FUNCTION public.inventario_ajuste() RETURNS TRIGGER AS $$
        BEGIN
            IF TG_OP = 'INSERT' THEN
                UPDATE tblinventario SET stock = stock + NEW.cantidad WHERE id = NEW.id_producto;
            ELSIF TG_OP = 'DELETE' THEN
                UPDATE tblinventario SET stock = stock - OLD.cantidad WHERE id = OLD.id_producto;
            END IF;
            RETURN NULL;
        END;
        $$ LANGUAGE plpgsql;
        
        DROP TRIGGER IF EXISTS tr_inventario_ajuste ON tblajustes_inventario;
        CREATE TRIGGER tr_inventario_ajuste
        AFTER INSERT OR DELETE ON tblajustes_inventario
        FOR EACH ROW EXECUTE PROCEDURE public.inventario_ajuste();
    ");

    $conn->commit();
    echo "¡MIGRACIÓN COMPLETADA CON ÉXITO!\n";

} catch (Exception $e) {
    if (isset($conn)) $conn->rollBack();
    echo "ERROR DURANTE LA MIGRACIÓN: " . $e->getMessage() . "\n";
}
