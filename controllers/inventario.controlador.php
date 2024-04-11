<?php
require_once "../models/inventario.modelo.php";
class ControladorInventario
{
    public $id;
    public $codigo;
    public $nombre, $unidad, $categoria, $percha, $stock, $stock_min, $stock_mal;
    static public function listarInventario()
    {
        $data = ModeloInventario::mdlListarInventario();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarInventario()
    {
        $data = ModeloInventario::mdlAgregarInventario($this->codigo,$this->nombre,$this->stock,$this->stock_min,$this->stock_mal,$this->categoria,$this->unidad,$this->percha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarInventario()
    {
        $data = ModeloInventario::mdlEditarInventario($this->id,$this->codigo,$this->nombre,$this->stock,$this->stock_min,$this->stock_mal,$this->categoria,$this->unidad,$this->percha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarInventario()
    {
        $data = ModeloInventario::mdlEliminarInventario($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarNombres()
    {
        $data = ModeloInventario::mdlBuscarProductos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarCodigo()
    {
        $data = ModeloInventario::mdlBuscarId($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
}

if (!isset($_POST["accion"])) {
    $data = new ControladorInventario();
    $data->listarInventario();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorInventario();
        $data->codigo = $_POST["cod"];
        $data->nombre = $_POST["des"];
        $data->stock = $_POST["sto"];
        $data->stock_min = $_POST["st_min"];
        $data->stock_mal = $_POST["st_mal"];
        $data->categoria = $_POST["cat"];
        $data->unidad = $_POST["uni"];
        $data->percha = $_POST["ubi"];
        $data->agregarInventario();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorInventario();
        $data->id = $_POST["id"];
        $data->codigo = $_POST["cod"];
        $data->nombre = $_POST["des"];
        $data->stock = $_POST["sto"];
        $data->stock_min = $_POST["st_min"];
        $data->stock_mal = $_POST["st_mal"];
        $data->categoria = $_POST["cat"];
        $data->unidad = $_POST["uni"];
        $data->percha = $_POST["ubi"];
        $data->editarInventario();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorInventario();
        $data->id = $_POST["id"];
        $data->eliminarInventario();
    }else if ($_POST["accion"] == 4) {
        $data = new ControladorInventario();
        // $data->codigo = $_POST["codigo"];
        $data->buscarNombres();
    }else if ($_POST["accion"] == 5) {
        $data = new ControladorInventario();
        $data->id = $_POST["id"];
        $data->buscarCodigo();
    }
}
