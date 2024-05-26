<?php
require_once "../models/empleados.modelo.php";
class ControladorEmpleados
{
    public $id,$cedula,$nombre, $apellido,$celular, $id_empresa, $id_rol, $placa;
    
    public function listarEmpleados()
    {
        $data = ModeloEmpleados::mdlListarEmpleados($this->id_empresa);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarEmpleados()
    {
        $data = ModeloEmpleados::mdlAgregarEmpleado($this->cedula, $this->nombre, $this->apellido, $this->celular, $this->id_empresa, $this->id_rol, $this->placa);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function eliminarEmpleado()
    {
        $data = ModeloEmpleados::mdlEliminarEmpleado($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarEmpleado()
    {
        $data = ModeloEmpleados::mdlEditarEmpleado($this->id,$this->cedula, $this->nombre, $this->apellido, $this->celular, $this->id_empresa, $this->id_rol, $this->placa);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (isset($_POST["accion"]) && $_POST["accion"] == 0) {
    $data = new ControladorEmpleados();
    $data->id_empresa = $_POST["id_empresa"];
    $data->listarEmpleados();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorEmpleados();
        $data->cedula = $_POST["cedula"];
        $data->nombre  = $_POST["nombre"];
        $data->apellido  = $_POST["apellido"];
        $data->celular  = $_POST["celular"];
        $data->id_empresa = $_POST["id_empresa"];
        $data->id_rol = $_POST["id_rol"];
        $data->placa = $_POST["id_placa"];
        $data->agregarEmpleados();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorEmpleados();
        $data->id = $_POST["id"];
        $data->cedula = $_POST["cedula"];
        $data->nombre  = $_POST["nombre"];
        $data->apellido  = $_POST["apellido"];
        $data->celular  = $_POST["celular"];
        $data->id_empresa = $_POST["id_empresa"];
        $data->id_rol = $_POST["id_rol"];
        $data->placa = $_POST["id_placa"];
        $data->editarEmpleado();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorEmpleados();
        $data->id = $_POST["id"];
        $data->eliminarEmpleado();
    }
}
