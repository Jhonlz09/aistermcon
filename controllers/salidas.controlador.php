<?php
require_once "../models/salidas.modelo.php";
class ControladorSalidas
{
    public $id, $nombres, $fecha, $anio, $mes;

    public function listarSalidas()
    {
        $data = ModeloSalidas::mdlListarSalidas($this->anio, $this->mes);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarSalida()
    {
        $data = ModeloSalidas::mdlAgregarSalida($this->id, $this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function eliminarSalida()
    {
        $data = ModeloSalidas::mdlEliminarSalida($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    // public function editarSalida()
    // {
    //     $data = ModeloSalidas::mdlEditarSalida($this->id,$this->nombres,$this->fecha);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }

    public function buscarBoleta()
    {
        $data = ModeloSalidas::mdlBuscarBoleta($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function detalleBoleta()
    {
        $data = ModeloSalidas::mdlDetalleBoleta($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarOrdenFecha()
    {
        $data = ModeloSalidas::mdlBuscarOrdenFecha($this->id, $this->fecha);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // public function agregarRetorno()
    // {
    //     $data = ModeloSalidas::mdlAgregarRetorno($this->id, $this->nombres);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorSalidas();
    $data->anio = $_POST["anio"];
    $data->mes = $_POST["mes"];
    $data->listarSalidas();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorSalidas();
        $data->id = $_POST["id_boleta"];
        $data->nombres = $_POST["id_producto"];
        $data->agregarSalida();
    } //else if ($_POST["accion"] == 2){
    //     $data = new ControladorSalidas();
    //     $data->id = $_POST["id_empleado"];
    //     $data->nombres = $_POST["nombres_empleado"];
    //     $data->fecha = $_POST["fecha"];
    //     $data->editarSalida();
    else if ($_POST["accion"] == 3) {
        $data = new ControladorSalidas();
        $data->id = $_POST["id"];
        $data->eliminarSalida();
    } else if ($_POST["accion"] == 4) {
        $data = new ControladorSalidas();
        $data->id = $_POST["boleta"];
        $data->buscarBoleta();
    } else if ($_POST["accion"] == 5) {
        $data = new ControladorSalidas();
        $data->id = $_POST["orden"];
        $data->fecha = $_POST["fecha"];
        $data->buscarOrdenFecha();
        // }else if ($_POST["accion"] == 6){
        //     $data = new ControladorSalidas();
        //     $data->id = $_POST["id"];
        //     $data->nombres = $_POST["retorno"];
        //     $data->agregarRetorno();
    } else if ($_POST["accion"] == 7) {
        $data = new ControladorSalidas();
        $data->id = $_POST["boleta"];
        $data->detalleBoleta();
    }
}
