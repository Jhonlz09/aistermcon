<?php
require_once "../models/horario.modelo.php";

class ControladorHorario
{
    public $id, $registros, $id_empleado, $id_orden, $fecha, $hn, $hs, $he, $material, $trans,
        $ali, $hosp, $guard, $agua;

    static public function listarHorario()
    {
        $data = ModeloHorario::mdlListarHorario();
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
    $data->listarHorario();
} else {
    if ($_POST["accion"] == 1) {
        $data->registros = json_decode($_POST["registros"], true); // Decodificar a array
        $data->agregarHorario();
    }
}
