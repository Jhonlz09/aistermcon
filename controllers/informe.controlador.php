<?php
require_once "../models/informe.modelo.php";
class ControladorInforme
{
    public $anio,$mes;
    
    public function listarInforme()
    {
        $data = ModeloInforme::mdlListarInforme($this->anio, $this->mes);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    // public function agregarSalida()
    // {
    //     $data = ModeloInforme::mdlAgregarSalida($this->id, $this->nombres);
    // //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // // }
    // public function eliminarSalida()
    // {
    //     $data = ModeloInforme::mdlEliminarSalida($this->id);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }
    // public function editarSalida()
    // {
    //     $data = ModeloInforme::mdlEditarSalida($this->id,$this->nombres,$this->fecha);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }

    // public function buscarBoleta()
    // {
    //     $data = ModeloInforme::mdlBuscarBoleta($this->id);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }

    // public function detalleBoleta()
    // {
    //     $data = ModeloInforme::mdlDetalleBoleta($this->id);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }

    // public function buscarOrdenFecha()
    // {
    //     $data = ModeloInforme::mdlBuscarOrdenFecha($this->id, $this->fecha);

    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }

    // public function agregarRetorno()
    // {
    //     $data = ModeloInforme::mdlAgregarRetorno($this->id, $this->nombres);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorInforme();
    $data->anio = $_POST["anio"];
    $data->mes = $_POST["mes"];
    $data->listarInforme();
} else {
    // if ($_POST["accion"] == 1){
    //     $data = new ControladorInforme();
    //     $data->id = $_POST["id_boleta"];
    //     $data->nombres = $_POST["id_producto"];
    //     $data->agregarSalida();
    // } else if ($_POST["accion"] == 2){
    //     $data = new ControladorInforme();
    //     $data->id = $_POST["id_empleado"];
    //     $data->nombres = $_POST["nombres_empleado"];
    //     $data->fecha = $_POST["fecha"];
    //     $data->editarSalida();
    // } else if ($_POST["accion"] == 3){
    //     $data = new ControladorInforme();
    //     $data->id = $_POST["id"];
    //     $data->eliminarSalida();
    // }else if ($_POST["accion"] == 4){
    //     $data = new ControladorInforme();
    //     $data->id = $_POST["boleta"];
    //     $data->buscarBoleta();
    // }else if ($_POST["accion"] == 5){
    //     $data = new ControladorInforme();
    //     $data->id = $_POST["orden"];
    //     $data->fecha = $_POST["fecha"];
    //     $data->buscarOrdenFecha();
    // // }else if ($_POST["accion"] == 6){
    // //     $data = new ControladorInforme();
    // //     $data->id = $_POST["id"];
    // //     $data->nombres = $_POST["retorno"];
    // //     $data->agregarRetorno();
    // }else if ($_POST["accion"] == 7){
    //     $data = new ControladorInforme();
    //     $data->id = $_POST["boleta"];
    //     $data->detalleBoleta();
    // }
}
