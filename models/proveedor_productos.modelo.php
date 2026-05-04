<?php

require_once "../utils/database/conexion.php";

class ModeloProveedorProductos
{
    /**
     * Lista los productos asociados a un proveedor con datos del inventario.
     */
    static public function mdlListarPorProveedor($id_proveedor)
    {
        try {
            $stmt = Conexion::ConexionDB()->prepare("
                SELECT 
                    pp.id,
                    pp.id_producto,
                    i.codigo,
                    i.descripcion AS nombre_interno,
                    pp.codigo_proveedor,
                    pp.nombre_proveedor,
                    pp.precio_referencial,
                    TO_CHAR(pp.ultima_compra, 'DD/MM/YYYY') AS ultima_compra,
                    '' AS acciones
                FROM tblproveedor_productos pp
                INNER JOIN tblinventario i ON i.id = pp.id_producto
                WHERE pp.id_proveedor = :id_prov AND pp.estado = true
                ORDER BY i.descripcion
            ");
            $stmt->bindParam(':id_prov', $id_proveedor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('status' => 'danger', 'm' => 'Error al listar productos: ' . $e->getMessage());
        }
    }

    /**
     * Lista los proveedores que venden un producto (para pestaña inventario).
     */
    static public function mdlListarPorProducto($id_producto)
    {
        try {
            $stmt = Conexion::ConexionDB()->prepare("
                SELECT 
                    pp.id,
                    pp.id_proveedor,
                    p.nombre AS nombre_proveedor_empresa,
                    pp.codigo_proveedor,
                    pp.nombre_proveedor,
                    pp.precio_referencial,
                    TO_CHAR(pp.ultima_compra, 'DD/MM/YYYY') AS ultima_compra,
                    '' AS acciones
                FROM tblproveedor_productos pp
                INNER JOIN tblproveedores p ON p.id = pp.id_proveedor
                WHERE pp.id_producto = :id_prod AND pp.estado = true
                ORDER BY p.nombre
            ");
            $stmt->bindParam(':id_prod', $id_producto, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('status' => 'danger', 'm' => 'Error al listar proveedores: ' . $e->getMessage());
        }
    }

    /**
     * Asocia un producto del inventario a un proveedor.
     * Usa ON CONFLICT para reactivar si ya existía (soft-deleted).
     */
    static public function mdlAsociarProducto($id_proveedor, $id_producto, $codigo_prov, $nombre_prov, $precio)
    {
        try {
            $conexion = Conexion::ConexionDB();

            // Si nombre_proveedor viene vacío, usar la descripción del inventario
            if (empty(trim($nombre_prov))) {
                $stmtNombre = $conexion->prepare("SELECT descripcion FROM tblinventario WHERE id = :id");
                $stmtNombre->bindParam(':id', $id_producto, PDO::PARAM_INT);
                $stmtNombre->execute();
                $row = $stmtNombre->fetch(PDO::FETCH_ASSOC);
                $nombre_prov = $row ? $row['descripcion'] : '';
            }

            $stmt = $conexion->prepare("
                INSERT INTO tblproveedor_productos (id_proveedor, id_producto, codigo_proveedor, nombre_proveedor, precio_referencial)
                VALUES (:id_prov, :id_prod, :codigo, :nombre, :precio)
                ON CONFLICT (id_proveedor, id_producto) 
                DO UPDATE SET 
                    codigo_proveedor = EXCLUDED.codigo_proveedor,
                    nombre_proveedor = EXCLUDED.nombre_proveedor,
                    precio_referencial = EXCLUDED.precio_referencial,
                    estado = true
            ");
            $stmt->bindParam(':id_prov', $id_proveedor, PDO::PARAM_INT);
            $stmt->bindParam(':id_prod', $id_producto, PDO::PARAM_INT);
            $stmt->bindParam(':codigo', $codigo_prov, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre_prov, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->execute();

            return array(
                'status' => 'success',
                'm' => 'Producto asociado correctamente al proveedor.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo asociar el producto: ' . $e->getMessage()
            );
        }
    }

    /**
     * Soft-delete de una asociación.
     */
    static public function mdlDesasociarProducto($id)
    {
        try {
            $stmt = Conexion::ConexionDB()->prepare("UPDATE tblproveedor_productos SET estado = false WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return array(
                'status' => 'success',
                'm' => 'Producto desasociado correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo desasociar: ' . $e->getMessage()
            );
        }
    }

    /**
     * Edita los campos de una asociación existente.
     */
    static public function mdlEditarAsociacion($id, $codigo_prov, $nombre_prov, $precio)
    {
        try {
            $stmt = Conexion::ConexionDB()->prepare("
                UPDATE tblproveedor_productos 
                SET codigo_proveedor = :codigo, 
                    nombre_proveedor = :nombre, 
                    precio_referencial = :precio
                WHERE id = :id
            ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':codigo', $codigo_prov, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre_prov, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->execute();

            return array(
                'status' => 'success',
                'm' => 'Asociación actualizada correctamente.'
            );
        } catch (PDOException $e) {
            return array(
                'status' => 'danger',
                'm' => 'No se pudo editar la asociación: ' . $e->getMessage()
            );
        }
    }

    /**
     * Edita múltiples asociaciones en una sola transacción.
     * Recibe un array de items: [{id, codigo_proveedor, nombre_proveedor, precio_referencial}]
     */
    static public function mdlEditarAsociacionBatch($items)
    {
        try {
            $conexion = Conexion::ConexionDB();
            $conexion->beginTransaction();

            $stmt = $conexion->prepare("
                UPDATE tblproveedor_productos 
                SET codigo_proveedor = :codigo, 
                    nombre_proveedor = :nombre, 
                    precio_referencial = :precio
                WHERE id = :id
            ");

            $count = 0;
            foreach ($items as $item) {
                $stmt->bindParam(':id', $item['id'], PDO::PARAM_INT);
                $stmt->bindParam(':codigo', $item['codigo_proveedor'], PDO::PARAM_STR);
                $stmt->bindParam(':nombre', $item['nombre_proveedor'], PDO::PARAM_STR);
                $stmt->bindParam(':precio', $item['precio_referencial'], PDO::PARAM_STR);
                $stmt->execute();
                $count++;
            }

            $conexion->commit();
            return array(
                'status' => 'success',
                'm' => $count . ' asociación(es) actualizada(s).'
            );
        } catch (PDOException $e) {
            if (isset($conexion)) $conexion->rollBack();
            return array(
                'status' => 'danger',
                'm' => 'Error al actualizar asociaciones: ' . $e->getMessage()
            );
        }
    }

    /**
     * Retorna productos asociados al proveedor formateados para autocomplete/lookup.
     * Devuelve un mapa indexado por id_producto para lookup rápido en JS.
     */
    static public function mdlBuscarProductosProveedor($id_proveedor)
    {
        try {
            $stmt = Conexion::ConexionDB()->prepare("
                SELECT 
                    pp.id_producto,
                    pp.codigo_proveedor AS codigo_prov,
                    pp.nombre_proveedor AS nombre_prov,
                    pp.precio_referencial AS precio_ref
                FROM tblproveedor_productos pp
                WHERE pp.id_proveedor = :id_prov AND pp.estado = true
            ");
            $stmt->bindParam(':id_prov', $id_proveedor, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Indexar por id_producto para lookup O(1) en JS
            $result = array();
            foreach ($rows as $row) {
                $result[$row['id_producto']] = array(
                    'codigo_prov' => $row['codigo_prov'],
                    'nombre_prov' => $row['nombre_prov'],
                    'precio_ref'  => floatval($row['precio_ref'])
                );
            }
            return $result;
        } catch (PDOException $e) {
            return array();
        }
    }

    /**
     * Asocia automáticamente el producto al proveedor durante el registro de compra.
     * Si la asociación ya existe, actualiza precio y fecha.
     * Si no existe, la crea con el nombre del inventario como nombre_proveedor por defecto.
     */
    static public function mdlActualizarPrecioReferencial($conexion, $id_proveedor, $id_producto, $precio, $fecha)
    {
        $stmt = $conexion->prepare("
            INSERT INTO tblproveedor_productos (id_proveedor, id_producto, nombre_proveedor, precio_referencial, ultima_compra)
            VALUES (
                :id_prov, 
                :id_prod, 
                (SELECT descripcion FROM tblinventario WHERE id = :id_prod_sub),
                :precio, 
                :fecha
            )
            ON CONFLICT (id_proveedor, id_producto) 
            DO UPDATE SET 
                precio_referencial = EXCLUDED.precio_referencial, 
                ultima_compra = EXCLUDED.ultima_compra,
                estado = true
        ");
        $stmt->bindParam(':id_prov', $id_proveedor, PDO::PARAM_INT);
        $stmt->bindParam(':id_prod', $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(':id_prod_sub', $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->execute();
    }
}
