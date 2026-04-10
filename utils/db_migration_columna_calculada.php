<?php
require_once __DIR__ . '/database/conexion.php';

try {
    $conn = Conexion::ConexionDB();
    
    // Attempt adding the GENERATED column
    $sql = "ALTER TABLE tblinventario 
            ADD COLUMN valor_total_bodega NUMERIC(15,5) 
            GENERATED ALWAYS AS ((stock - COALESCE(stock_mal, 0)) * COALESCE(precio_total_iva, 0)) STORED;";
    
    $conn->exec($sql);
    echo "Columna valor_total_bodega creada con exito.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'already exists') !== false) {
        echo "La columna ya existe.\n";
    } else {
        echo "Error al crear columna: " . $e->getMessage() . "\n";
    }
}
