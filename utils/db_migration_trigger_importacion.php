<?php
require_once __DIR__ . '/database/conexion.php';

try {
    $conn = Conexion::ConexionDB();
    
    $sql = "
    CREATE OR REPLACE FUNCTION public.fn_actualizar_precio_producto()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
    AS \$BODY\$
    DECLARE
        fecha_nueva timestamp;
        fecha_maxima timestamp;
        precio_actual numeric(10,5);
        porcentaje_iva numeric;
        calc_iva numeric(10,5);
        calc_total numeric(10,5);
        es_importacion boolean := false;
        precio_evaluar numeric(15,5);
    BEGIN
        -- 1. Obtener la fecha de la factura actual y si es importacion
        IF NEW.id_factura IS NOT NULL THEN
            SELECT fecha, importacion INTO fecha_nueva, es_importacion FROM tblfactura WHERE id = NEW.id_factura;
        END IF;
        
        -- Si no hay factura, usamos NOW() y falso para importacion
        fecha_nueva := COALESCE(fecha_nueva, NOW());
        es_importacion := COALESCE(es_importacion, false);

        -- 2. Determinar qué precio usar
        IF es_importacion THEN
            precio_evaluar := NEW.precio_total_iva;
        ELSE
            precio_evaluar := NEW.precio_uni;
        END IF;

        -- 3. Validar que el precio entrante sea valido
        IF precio_evaluar IS NULL OR precio_evaluar <= 0 THEN
            RETURN NEW;
        END IF;

        -- 4. Obtener la fecha de factura más reciente registrada para este producto
        SELECT MAX(f.fecha) INTO fecha_maxima 
        FROM tblentradas e
        JOIN tblfactura f ON e.id_factura = f.id
        WHERE e.id_producto = NEW.id_producto
          AND e.id != NEW.id;

        -- 5. Si la fecha máxima es mayor a la nueva fecha, abortamos actualización de precios.
        IF fecha_maxima IS NOT NULL AND fecha_nueva < fecha_maxima THEN
            RETURN NEW;
        END IF;

        -- 6. Obtener el precio actual en inventario
        SELECT precio_uni INTO precio_actual FROM tblinventario WHERE id = NEW.id_producto;
        
        -- Si el precio no ha cambiado, no hacer nada (evita redundancia)
        IF precio_actual IS NOT NULL AND precio_actual = precio_evaluar THEN
            RETURN NEW;
        END IF;

        -- 7. Obtener IVA
        SELECT iva INTO porcentaje_iva FROM tblconfiguracion LIMIT 1;
        IF porcentaje_iva IS NULL THEN
            porcentaje_iva := 0;
        END IF;

        -- 8. Calcular totales
        calc_iva := precio_evaluar * (porcentaje_iva / 100.0);
        calc_total := precio_evaluar + calc_iva;

        -- 9. Actualizar inventario
        UPDATE tblinventario 
        SET precio_uni = precio_evaluar,
            precio_iva = calc_iva,
            precio_total_iva = calc_total
        WHERE id = NEW.id_producto;

        -- 10. Registrar historial
        INSERT INTO tblhistorial_precios (id_producto, precio_uni, precio_iva, precio_total_iva, motivo)
        VALUES (NEW.id_producto, precio_evaluar, calc_iva, calc_total, 'COMPRA FACTURA #' || COALESCE(NEW.id_factura::text, 'S/F'));

        RETURN NEW;
    END;
    \$BODY\$;
    ";
    
    $conn->exec($sql);
    echo "Trigger function fn_actualizar_precio_producto actualizada con lógica de importación correctamente.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
