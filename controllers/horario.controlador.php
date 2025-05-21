<?php
require_once "../models/horario.modelo.php";

class ControladorHorario
{
    public $id, $registros, $id_empleado, $id_orden, $fecha, $hn, $hs, $he, $material, $trans,
        $ali, $hosp, $guard, $agua, $mes, $anio;

    public function listarHorario()
    {
        $data = ModeloHorario::mdlListarHorario($this->anio, $this->mes);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarHorario()
    {
        $data = ModeloHorario::mdlAgregarHorario($this->registros); // ahora usa $this->registros
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
$data = new ControladorHorario();

if (!isset($_POST["accion"])) {
    $data->anio = $_POST["anio"];
    $data->mes = $_POST["mes"];
    $data->listarHorario();
} else {
    if ($_POST["accion"] == 1) {
        $data->registros = json_decode($_POST["registros"], true); // Decodificar a array
        $data->agregarHorario();
    }
}
