<?php
require_once "../models/horario.modelo.php";

class ControladorHorario
{
    public $id, $registros, $id_empleado, $id_orden, $fecha, $hn, $hs, $he, $material, $trans,
        $ali, $hosp, $guard, $agua, $start, $end;

    public function listarHorario()
    {
        $data = ModeloHorario::mdlListarHorario($this->start, $this->end);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarHorario()
    {
        $data = ModeloHorario::mdlAgregarHorario($this->registros); // ahora usa $this->registros
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function consultarHorario()
    {
        $data = ModeloHorario::mdlConsultarHorario($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarHorario()
    {
        $data = ModeloHorario::mdlEditarHorario($this->registros);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarHorario()
    {
        $data = ModeloHorario::mdlEliminarHorario($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agg_editHorario()
    {
        $data = ModeloHorario::mdlEliminarHorario($this->id);
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
        $data->registros = json_decode($_POST["registros"], true); // Decodificar a array
        $data->agregarHorario();
    } else if ($_POST["accion"] == 2) {
        $data->id = $_POST["id"];
        $data->consultarHorario();
    } else if ($_POST["accion"] == 3) {
        $data->registros = json_decode($_POST["registros"], true); // Decodificar a array
        $data->editarHorario();
    } else if ($_POST["accion"] == 4) {
        $data->id = $_POST["id"];
        $data->eliminarHorario();
    } else if ($_POST["accion"] == 5) {
        $data->id = $_POST["id"];
        $data->agg_editHorario();
    } else if ($_POST["accion"] == 6) {
        $data->start = $_POST["start"];
        $data->end = $_POST["end"];
        $data->listarGastos();
    }
}
