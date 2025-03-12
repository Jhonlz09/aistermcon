<?php
require_once "../models/fabricacion.modelo.php";
class ControladorFabricacion
{
    public $id;
    public $nombre, $id_prod_fab, $stock, $stock_ini,$stock_min, $id_boleta, $img;

    static public function listarGuiaProdFab()
    {
        $data = ModeloFabricacion::mdlListarGuiaProdFab();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarProductoFab()
    {
        $data = ModeloFabricacion::mdlListarProductoFab($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarProductoFab()
    {
        $data = ModeloFabricacion::mdlEliminarProductoFab($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}
$data = new ControladorFabricacion();
if (isset($_POST["accion"]) && $_POST["accion"] == 0) {
    $data->listarGuiaProdFab();
} else {
    if ($_POST["accion"] == 1) {
        $data->id_prod_fab = $_POST["id_prod_fab"];
        $data->listarProductoFabUtil();
    } else if ($_POST["accion"] == 2) {
        $data->id_prod_fab = $_POST["id_prod_fab"];
        $data->eliminarProductoFab();
    } else if ($_POST["accion"] == 3) {
        $data->id = $_POST["id"];
        $data->eliminarInventario();
    } else if ($_POST["accion"] == 4) {
        $data->codigo = $_POST["id"];
        $data->buscarCodigo();
    } else if ($_POST["accion"] == 5) {
        $data->id = $_POST["id"];
        $data->buscarId();
    } else if ($_POST["accion"] == 6) {
        $data->listarInventarioStock();
    } else if ($_POST["accion"] == 7) {
        $data->buscarProductos();
    } else if ($_POST["accion"] == 8) {
        $data->alertaStock();
    } else if ($_POST["accion"] == 9) {
        $data->nombre = $_POST["nombre"];
        $data->unidad = $_POST["unidad"];
        $data->stock = $_POST["cantidad"];
        $data->agregarInventarioFab();
    } else if ($_POST["accion"] == 10) {
        $data->id = $_POST["id_e"];
        $data->nombre = $_POST["nombre"];
        $data->unidad = $_POST["unidad"];
        $data->stock = $_POST["cantidad"];
        $data->editarInventarioFab();
    } else if ($_POST["accion"] == 11) {
        $data->id = $_POST["id_producto_fab"];
        $data->listarProductoFab();
    } else if ($_POST["accion"] == 12) {
        $data->id = $_POST["id_producto"];
        $data->anio = $_POST["anio"];
        $data->consultarHistorialProducto();
    }else if ($_POST["accion"] == 13) {
        $data->id = $_POST["id_producto"];
        $data->anio = $_POST["anio"];
        $data->consultarStockIniAnio();
    }
}
