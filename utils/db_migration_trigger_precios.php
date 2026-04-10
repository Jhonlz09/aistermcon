<?php

require_once __DIR__ . '/database/conexion.php';

try {
    $conn = Conexion::ConexionDB();
    
    $sql = "
    DROP TRIGGER IF EXISTS trg_actualizar_precio_producto ON tblentradas;
    DROP FUNCTION IF EXISTS public.precios() CASCADE;
    
    CREATE OR REPLACE FUNCTION public.fn_actualizar_precio_producto()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    AS \$BODY\$
    DECLARE
        ultimo_id integer;
        precio_actual numeric(10,5);
        porcentaje_iva numeric;
        calc_iva numeric(10,5);
        calc_total numeric(10,5);
    BEGIN
        -- 1. Validar que el precio entrante sea valido
        IF NEW.precio_uni IS NULL OR NEW.precio_uni <= 0 THEN
            RETURN NEW;
        END IF;

        -- 2. Validar que este registro es el último (cronológicamente) para este producto
        SELECT MAX(id) INTO ultimo_id FROM tblentradas WHERE id_producto = NEW.id_producto;
        
        -- Si estamos insertando o editando y el ID no es el mayor, es una compra vieja
        IF NEW.id < ultimo_id THEN
            RETURN NEW;
        END IF;

        -- 3. Obtener el precio actual en inventario
        SELECT precio_uni INTO precio_actual FROM tblinventario WHERE id = NEW.id_producto;
        
        -- Si el precio no ha cambiado, no hacer nada (evita redundancia)
        IF precio_actual IS NOT NULL AND precio_actual = NEW.precio_uni THEN
            RETURN NEW;
        END IF;

        -- 4. Obtener IVA
        SELECT iva INTO porcentaje_iva FROM tblconfiguracion LIMIT 1;
        IF porcentaje_iva IS NULL THEN
            porcentaje_iva := 0;
        END IF;

        -- 5. Calcular totales
        calc_iva := NEW.precio_uni * (porcentaje_iva / 100.0);
        calc_total := NEW.precio_uni + calc_iva;

        -- 6. Actualizar inventario
        UPDATE tblinventario 
        SET precio_uni = NEW.precio_uni,
            precio_iva = calc_iva,
            precio_total_iva = calc_total
        WHERE id = NEW.id_producto;

        -- 7. Registrar historial
        INSERT INTO tblhistorial_precios (id_producto, precio_uni, precio_iva, precio_total_iva, motivo)
        VALUES (NEW.id_producto, NEW.precio_uni, calc_iva, calc_total, 'COMPRA FACTURA #' || COALESCE(NEW.id_factura::text, 'S/F'));

        RETURN NEW;
    END;
    \$BODY\$;

    CREATE TRIGGER trg_actualizar_precio_producto
    AFTER INSERT OR UPDATE
    ON tblentradas
    FOR EACH ROW
    EXECUTE FUNCTION public.fn_actualizar_precio_producto();
    ";
    
    $conn->exec($sql);
    echo "Trigger de precios creado correctamente.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
