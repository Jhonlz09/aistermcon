<?php
require_once "../models/encabezado.modelo.php";

class ControladorHeaders
{
    public $id,$nombres;

    static public function listarHeaders()
    {
        $data = ModeloHeaders::mdlListarHeaders();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarHeaders()
    {
        $data = ModeloHeaders::mdlAgregarHeaders($this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarHeaders()
    {
        $data = ModeloHeaders::mdlEditarHeaders($this->id,$this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function eliminarHeaders()
    {
        $data = ModeloHeaders::mdlEliminarHeaders($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function headersTable()
    {
        $data = ModeloHeaders::mdlHeadersTable($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerEncabezados()
    {
        $data = ModeloHeaders::mdlObtenerEncabezados($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorHeaders();
    $data->listarHeaders();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorHeaders();
        $data->nombres = $_POST["nombre"];
        $data->agregarHeaders();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorHeaders();
        $data->id = $_POST["id"];
        $data->nombres = $_POST["nombre"];
        $data->editarHeaders();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorHeaders();
        $data->id = $_POST["id"];
        $data->eliminarHeaders();
    }
    else if ($_POST["accion"] == 4) {
        $data = new ControladorHeaders();
        $data->id = $_POST["id_encabezado"];
        $data->headersTable();
    }else if ($_POST["accion"] == 5) {
        $data = new ControladorHeaders();
        $data->id = $_POST["id_plantilla"];
        $data->obtenerEncabezados();
    }
}
