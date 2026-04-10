<?php
require_once __DIR__ . '/database/conexion.php';

try {
    $conn = Conexion::ConexionDB();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Agregar columnas a tblinventario
    $sql_inventario = "
        ALTER TABLE public.tblinventario 
        ADD COLUMN IF NOT EXISTS precio_uni NUMERIC(10,5) DEFAULT 0,
        ADD COLUMN IF NOT EXISTS precio_iva NUMERIC(10,5) DEFAULT 0,
        ADD COLUMN IF NOT EXISTS precio_total_iva NUMERIC(10,5) DEFAULT 0;
    ";
    $conn->exec($sql_inventario);
    echo "Columnas añadidas a tblinventario.\n";

    // 2. Crear tabla tblhistorial_precios
    $sql_historial = "
        CREATE TABLE IF NOT EXISTS public.tblhistorial_precios
        (
            id serial PRIMARY KEY,
            id_producto integer NOT NULL,
            precio_uni numeric(10,5) NOT NULL DEFAULT 0,
            precio_iva numeric(10,5) NOT NULL DEFAULT 0,
            precio_total_iva numeric(10,5) NOT NULL DEFAULT 0,
            motivo text COLLATE pg_catalog.\"default\",
            fecha timestamp without time zone DEFAULT now(),
            CONSTRAINT fk_historial_producto FOREIGN KEY (id_producto)
                REFERENCES public.tblinventario (id) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE CASCADE
        );
    ";
    $conn->exec($sql_historial);
    echo "Tabla tblhistorial_precios creada.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
