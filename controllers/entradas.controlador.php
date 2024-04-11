<?php
require_once "../models/entradas.modelo.php";
class ControladorEntradas
{
    public $id, $anio, $mes;

    public function listarEntradas()
    {
        $data = ModeloEntradas::mdlListarEntradas($this->anio, $this->mes);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    // public function agregarEntradas()
    // {
    //     $data = ModeloEntradas::mdlAgregarEntradas($this->cedula, $this->nombres, $this->conductor);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }
    public function eliminarEntrada()
    {
        $data = ModeloEntradas::mdlEliminarEntrada($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function detalleBoletaEntrada()
    {
        $data = ModeloEntradas::mdlDetalleBoletaEntrada($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    // public function editarEntrada()
    // {
    //     $data = ModeloEntradas::mdlEditarEntrada($this->id,$this->cedula,$this->nombres,$this->conductor);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorEntradas();
    $data->anio = $_POST["anio"];
    $data->mes = $_POST["mes"];
    $data->listarEntradas();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorEntradas();
        $data->id = $_POST["id"];
        $data->eliminarEntrada();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorEntradas();
        $data->id = $_POST["boleta"];
        $data->detalleBoletaEntrada();
    } // else if ($_POST["accion"] == 2) {
    //     $data = new ControladorEntradas();
    //     $data->id = $_POST["id_empleado"];
    //     $data->cedula = $_POST["cedula"];
    //     $data->nombres = $_POST["nombres_empleado"];
    //     $data->conductor = $_POST["conductor"];
    //     $data->editarEntrada();
    // } else if ($_POST["accion"] == 3) {
    //     $data = new ControladorEntradas();
    //     $data->id = $_POST["id_empleado"];
    //     $data->eliminarEntrada();
    // }
}
