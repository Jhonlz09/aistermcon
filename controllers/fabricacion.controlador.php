<?php
require_once "../models/fabricacion.modelo.php";
class ControladorFabricacion
{
    public $id;
    public $nombre, $cantidad, $descripcion,$unidad, $id_boleta, $id_producto_fab, $img;

   
    public function agregarProdFabricado()
    {
        $data = ModeloFabricacion::mdlAgregarProdFabricado($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarProdUtilFab()
    {
        $data = ModeloFabricacion::mdlAgregarProdUtilFab($this->id, $this->id_boleta, $this->id_producto_fab);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarProductoFab()
    {
        $data = ModeloFabricacion::mdlEliminarProductoFab($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function eliminarProdUtil()
    {
        $data = ModeloFabricacion::mdlEliminarProdUtil($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    static public function listarGuiaProdFab()
    {
        $data = ModeloFabricacion::mdlListarGuiaProdFab();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarProdFabAndUtil()
    {
        $data = ModeloFabricacion::mdlListarProdFabAndUtil($this->id_boleta);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


}
$data = new ControladorFabricacion();
if (isset($_POST["accion"]) && $_POST["accion"] == 0) {
    $data->listarGuiaProdFab();
} else {
    if ($_POST["accion"] == 1) {
        $data->id = $_POST["id_boleta"];
        $data->agregarProdFabricado();
    } else if ($_POST["accion"] == 2) {
        $data->id = $_POST["id"];
        $data->eliminarProductoFab();
    } else if ($_POST["accion"] == 3) {
        $data->id = $_POST["id_producto"];
        $data->id_boleta = $_POST["id_boleta"];
        $data->id_producto_fab = $_POST["id_prod_fab"];
        $data->agregarProdUtilFab();
    } else if ($_POST["accion"] == 4) {
        $data->id = $_POST["id"];
        $data->eliminarProdUtil();
    }else if ($_POST["accion"] == 5) {
        $data->id_boleta = $_POST["id_boleta"];
        $data->listarProdFabAndUtil();
    }
}
