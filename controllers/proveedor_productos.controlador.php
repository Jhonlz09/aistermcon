<?php

require_once "../models/proveedor_productos.modelo.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ControladorProveedorProductos
{
    /**
     * Acción 1: Listar productos asociados a un proveedor
     */
    static public function listarPorProveedor($id_proveedor)
    {
        $data = ModeloProveedorProductos::mdlListarPorProveedor($id_proveedor);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Acción 2: Asociar producto a proveedor
     */
    static public function asociarProducto($id_proveedor, $id_producto, $codigo_prov, $nombre_prov, $precio)
    {
        $data = ModeloProveedorProductos::mdlAsociarProducto($id_proveedor, $id_producto, $codigo_prov, $nombre_prov, $precio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Acción 3: Desasociar producto (soft-delete)
     */
    static public function desasociarProducto($id)
    {
        $data = ModeloProveedorProductos::mdlDesasociarProducto($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Acción 4: Editar asociación
     */
    static public function editarAsociacion($id, $codigo_prov, $nombre_prov, $precio)
    {
        $data = ModeloProveedorProductos::mdlEditarAsociacion($id, $codigo_prov, $nombre_prov, $precio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Acción 5: Listar proveedores por producto (para pestaña inventario)
     */
    static public function listarPorProducto($id_producto)
    {
        $data = ModeloProveedorProductos::mdlListarPorProducto($id_producto);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Acción 6: Buscar productos del proveedor (mapa para autocomplete)
     */
    static public function buscarProductosProveedor($id_proveedor)
    {
        $data = ModeloProveedorProductos::mdlBuscarProductosProveedor($id_proveedor);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Acción 7: Editar asociaciones en batch (una sola petición)
     */
    static public function editarAsociacionBatch($items)
    {
        $data = ModeloProveedorProductos::mdlEditarAsociacionBatch($items);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Acción 8: Listar catálogo del proveedor para panel de compras
     */
    static public function listarCatalogoCompra($id_proveedor)
    {
        $data = ModeloProveedorProductos::mdlListarCatalogoCompra($id_proveedor);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

// ---- Dispatch por acción ----
if (isset($_POST["accion"])) {
    $accion = $_POST["accion"];

    if ($accion == 1) {
        // Listar productos por proveedor
        ControladorProveedorProductos::listarPorProveedor($_POST["id_proveedor"]);

    } else if ($accion == 2) {
        // Asociar producto — requiere permiso editar10
        if (isset($_SESSION["editar10"]) && $_SESSION["editar10"] === true) {
            ControladorProveedorProductos::asociarProducto(
                $_POST["id_proveedor"],
                $_POST["id_producto"],
                isset($_POST["codigo_proveedor"]) ? $_POST["codigo_proveedor"] : '',
                isset($_POST["nombre_proveedor"]) ? $_POST["nombre_proveedor"] : '',
                isset($_POST["precio_referencial"]) ? $_POST["precio_referencial"] : 0
            );
        } else {
            echo json_encode(array('status' => 'danger', 'm' => 'No tiene permisos para asociar productos.'), JSON_UNESCAPED_UNICODE);
        }

    } else if ($accion == 3) {
        // Desasociar producto — requiere permiso editar10
        if (isset($_SESSION["editar10"]) && $_SESSION["editar10"] === true) {
            ControladorProveedorProductos::desasociarProducto($_POST["id"]);
        } else {
            echo json_encode(array('status' => 'danger', 'm' => 'No tiene permisos para desasociar productos.'), JSON_UNESCAPED_UNICODE);
        }

    } else if ($accion == 4) {
        // Editar asociación — requiere permiso editar10
        if (isset($_SESSION["editar10"]) && $_SESSION["editar10"] === true) {
            ControladorProveedorProductos::editarAsociacion(
                $_POST["id"],
                isset($_POST["codigo_proveedor"]) ? $_POST["codigo_proveedor"] : '',
                isset($_POST["nombre_proveedor"]) ? $_POST["nombre_proveedor"] : '',
                isset($_POST["precio_referencial"]) ? $_POST["precio_referencial"] : 0
            );
        } else {
            echo json_encode(array('status' => 'danger', 'm' => 'No tiene permisos para editar asociaciones.'), JSON_UNESCAPED_UNICODE);
        }

    } else if ($accion == 5) {
        // Listar proveedores por producto
        ControladorProveedorProductos::listarPorProducto($_POST["id_producto"]);

    } else if ($accion == 6) {
        // Buscar productos para autocomplete filtrado por proveedor
        ControladorProveedorProductos::buscarProductosProveedor($_POST["id_proveedor"]);

    } else if ($accion == 7) {
        // Editar asociaciones en batch — requiere permiso editar10
        if (isset($_SESSION["editar10"]) && $_SESSION["editar10"] === true) {
            $items = isset($_POST["items"]) ? json_decode($_POST["items"], true) : [];
            if (!empty($items)) {
                ControladorProveedorProductos::editarAsociacionBatch($items);
            } else {
                echo json_encode(array('status' => 'warning', 'm' => 'No hay cambios que guardar.'), JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(array('status' => 'danger', 'm' => 'No tiene permisos para editar asociaciones.'), JSON_UNESCAPED_UNICODE);
        }

    } else if ($accion == 8) {
        // Listar catálogo del proveedor para panel de compras
        ControladorProveedorProductos::listarCatalogoCompra($_POST["id_proveedor"]);
    }
}
