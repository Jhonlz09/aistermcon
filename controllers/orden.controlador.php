<?php
require_once "../models/orden.modelo.php";

class ControladorOrden
{
    public $id,$nombres, $id_cliente;

    static public function listarOrden()
    {
        $data = ModeloOrden::mdlListarOrden();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarOrden()
    {
        $data = ModeloOrden::mdlAgregarOrden($this->nombres, $this->id_cliente);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarOrden()
    {
        $data = ModeloOrden::mdlEditarOrden($this->id,$this->nombres, $this->id_cliente);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function eliminarOrden()
    {
        $data = ModeloOrden::mdlEliminarOrden($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorOrden();
    $data->listarOrden();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorOrden();
        $data->nombres = $_POST["nombre"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->agregarOrden();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorOrden();
        $data->id = $_POST["id"];
        $data->nombres = $_POST["nombre"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->editarOrden();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorOrden();
        $data->id = $_POST["id"];
        $data->eliminarOrden();
    }
}
