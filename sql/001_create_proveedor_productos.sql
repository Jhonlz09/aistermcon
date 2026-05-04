-- ============================================================
-- Tabla de homologación: Productos por Proveedor
-- Permite vincular productos del inventario interno con los
-- códigos, nombres comerciales y precios de cada proveedor.
-- ============================================================

CREATE TABLE IF NOT EXISTS tblproveedor_productos (
    id                  SERIAL PRIMARY KEY,
    id_proveedor        INTEGER NOT NULL REFERENCES tblproveedores(id) ON DELETE CASCADE,
    id_producto         INTEGER NOT NULL REFERENCES tblinventario(id) ON DELETE CASCADE,
    codigo_proveedor    VARCHAR(50),                -- Código interno del proveedor para el producto
    nombre_proveedor    VARCHAR(200),               -- Nombre comercial del proveedor (default: descripción inventario)
    precio_referencial  NUMERIC(12,4) DEFAULT 0,    -- Último precio de compra conocido
    ultima_compra       TIMESTAMP,                  -- Fecha de la última compra registrada
    estado              BOOLEAN DEFAULT TRUE,
    created_at          TIMESTAMP DEFAULT NOW(),
    UNIQUE(id_proveedor, id_producto)               -- Un producto solo se vincula una vez por proveedor
);

-- Índices para consultas frecuentes
CREATE INDEX IF NOT EXISTS idx_pp_proveedor ON tblproveedor_productos(id_proveedor) WHERE estado = true;
CREATE INDEX IF NOT EXISTS idx_pp_producto  ON tblproveedor_productos(id_producto)  WHERE estado = true;
