<?php
require_once "../models/horario.modelo.php";

class ControladorHorario
{
    public $id, $datos, $datos_edit, $start, $end ;

    public function listarHorario()
    {
        $data = ModeloHorario::mdlListarHorario($this->start, $this->end);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarHorario()
    {
        $data = ModeloHorario::mdlAgregarHorario($this->datos); // ahora usa $this->datos
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function consultarHorario()
    {
        $data = ModeloHorario::mdlConsultarHorario($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarHorario()
    {
        $data = ModeloHorario::mdlEditarHorario($this->datos);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarHorario()
    {
        $data = ModeloHorario::mdlEliminarHorario($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function add_editHorario()
    {
        $data = ModeloHorario::add_editHorario($this->datos, $this->datos_edit);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarGastos()
    {
        $data = ModeloHorario::mdlListarGastos($this->start, $this->end);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
$data = new ControladorHorario();

if (!isset($_POST["accion"])) {
    $data->start = $_POST["start"];
    $data->end = $_POST["end"];
    $data->listarHorario();
} else {
    if ($_POST["accion"] == 1) {
        $data->datos = json_decode($_POST["datos"], true); // Decodificar a array
        $data->agregarHorario();
    } else if ($_POST["accion"] == 2) {
        $data->id = $_POST["id"];
        $data->consultarHorario();
    } else if ($_POST["accion"] == 3) {
        $data->datos = json_decode($_POST["datos"], true); // Decodificar a array
        $data->editarHorario();
    } else if ($_POST["accion"] == 4) {
        $data->id = $_POST["id"];
        $data->eliminarHorario();
    } else if ($_POST["accion"] == 5) {
        $data->datos = json_decode($_POST["datos"], true);
        $data->datos_edit = json_decode($_POST["datos_edit"], true);
        $data->add_editHorario();
    } else if ($_POST["accion"] == 6) {
        $data->start = $_POST["start"];
        $data->end = $_POST["end"];
        $data->listarGastos();
    }
}
