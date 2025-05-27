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
        $data = ModeloHorario::mdlEditarHorario($this->id, $this->id_empleado, $this->id_orden, $this->fecha, $this->hn, $this->hs, $this->he, $this->material, $this->trans, $this->ali, $this->hosp, $this->guard, $this->agua);
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
        $data->id = $_POST["id"];
        $data->id_empleado = $_POST["id_empleado"];
        $data->id_orden = $_POST["id_orden"];
        $data->fecha = $_POST["fecha"];
        $data->hn = $_POST["hn"];
        $data->hs = $_POST["hs"];
        $data->he = $_POST["he"];
        $data->material = $_POST["material"];
        $data->trans = $_POST["trans"];
        $data->ali = $_POST["ali"];
        $data->hosp = $_POST["hosp"];
        $data->guard = $_POST["guard"];
        $data->agua = $_POST["agua"];
        $data->editarHorario();
    }
}
