<?php
require_once "../models/empleados.modelo.php";
class ControladorEmpleados
{
    public $id,$cedula,$nombres,$conductor;
    
    static public function listarEmpleados()
    {
        $data = ModeloEmpleados::mdlListarEmpleados();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarEmpleados()
    {
        $data = ModeloEmpleados::mdlAgregarEmpleados($this->cedula, $this->nombres, $this->conductor);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function eliminarEmpleado()
    {
        $data = ModeloEmpleados::mdlEliminarEmpleado($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarEmpleado()
    {
        $data = ModeloEmpleados::mdlEditarEmpleado($this->id,$this->cedula,$this->nombres,$this->conductor);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorEmpleados();
    $data->listarEmpleados();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorEmpleados();
        $data->cedula = $_POST["cedula"];
        $data->nombres = $_POST["nombre"];
        $data->conductor = $_POST["conductor"];
        $data->agregarEmpleados();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorEmpleados();
        $data->id = $_POST["id"];
        $data->cedula = $_POST["cedula"];
        $data->nombres = $_POST["nombre"];
        $data->conductor = $_POST["conductor"];
        $data->editarEmpleado();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorEmpleados();
        $data->id = $_POST["id"];
        $data->eliminarEmpleado();
    }
}
