<?php
require_once "../models/orden.modelo.php";

class ControladorOrden
{
    public $id,$nombres, $id_cliente, $estado, $orden, $anio;

    public function listarOrden()
    {
        $data = ModeloOrden::mdlListarOrden($this->anio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarOrden()
    {
        $data = ModeloOrden::mdlAgregarOrden($this->nombres, $this->id_cliente, $this->orden);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarOrden()
    {
        $data = ModeloOrden::mdlEditarOrden($this->id,$this->nombres, $this->id_cliente, $this->orden);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function eliminarOrden()
    {
        $data = ModeloOrden::mdlEliminarOrden($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function actualizarEstadoOrden()
    {
        $data = ModeloOrden::mdlcambiarEstado($this->id,$this->estado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerIdOrden()
    {
        $data = ModeloOrden::mdlobtenerIdOrden($this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorOrden();
    $data->anio = $_POST["anio"];
    $data->listarOrden();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorOrden();
        $data->nombres = $_POST["nombre"];
        $data->orden = $_POST["orden"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->agregarOrden();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorOrden();
        $data->id = $_POST["id"];
        $data->nombres = $_POST["nombre"];
        $data->orden = $_POST["orden"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->editarOrden();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorOrden();
        $data->id = $_POST["id"];
        $data->eliminarOrden();
    }else if ($_POST["accion"] == 4) {
        $data = new ControladorOrden();
        $data->nombres = $_POST["nombre"];
        $data->obtenerIdOrden();
    } else if ($_POST["accion"] == 5) {
        $data = new ControladorOrden();
        $data->id = $_POST["id"];
        $data->estado = $_POST["estado"];
        $data->actualizarEstadoOrden();
    }
}
