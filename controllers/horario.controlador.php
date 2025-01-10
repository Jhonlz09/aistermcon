<?php
require_once "../models/horario.modelo.php";

class ControladorHorario
{
    public $id, $ruc,$nombres,$dir, $tel, $correo;

    static public function listarHorario()
    {
        $data = ModeloHorario::mdlListarHorario();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarProveedores()
    {
        $data = ModeloHorario::mdlAgregarProveedores($this->ruc,$this->nombres,$this->dir,$this->correo,$this->tel);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarProveedor()
    {
        $data = ModeloHorario::mdlEditarProveedor($this->id,$this->ruc,$this->nombres,$this->dir,$this->correo,$this->tel);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function eliminarProveedor()
    {
        $data = ModeloHorario::mdlEliminarProveedor($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorHorario();
    $data->listarHorario();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorHorario();
        $data->ruc = $_POST["ruc"];
        $data->nombres = $_POST["nombre"];
        $data->tel = $_POST["tel"];
        $data->dir = $_POST["dir"];
        $data->correo = $_POST["correo"];
        $data->agregarProveedores();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorHorario();
        $data->id = $_POST["id"];
        $data->ruc = $_POST["ruc"];
        $data->nombres = $_POST["nombre"];
        $data->tel = $_POST["tel"];
        $data->dir = $_POST["dir"];
        $data->correo = $_POST["correo"];
        $data->editarProveedor();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorHorario();
        $data->id = $_POST["id"];
        $data->eliminarProveedor();
    }
}