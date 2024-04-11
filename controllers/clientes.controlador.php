<?php
require_once "../models/clientes.modelo.php";

class ControladorClientes
{
    public $id,$nombres;

    static public function listarClientes()
    {
        $data = ModeloClientes::mdlListarClientes();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarClientes()
    {
        $data = ModeloClientes::mdlAgregarClientes($this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarCliente()
    {
        $data = ModeloClientes::mdlEditarCliente($this->id,$this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function eliminarCliente()
    {
        $data = ModeloClientes::mdlEliminarCliente($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorClientes();
    $data->listarClientes();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorClientes();
        $data->nombres = $_POST["nombre"];
        $data->agregarClientes();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorClientes();
        $data->id = $_POST["id"];
        $data->nombres = $_POST["nombre"];
        $data->editarCliente();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorClientes();
        $data->id = $_POST["id"];
        $data->eliminarCliente();
    }
}
