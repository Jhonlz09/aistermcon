<?php
require_once "../models/proveedores.modelo.php";

class ControladorProveedores
{
    public $id,$nombres;

    static public function listarProveedores()
    {
        $data = ModeloProveedores::mdlListarProveedores();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarProveedores()
    {
        $data = ModeloProveedores::mdlAgregarProveedores($this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarProveedor()
    {
        $data = ModeloProveedores::mdlEditarProveedor($this->id,$this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function eliminarProveedor()
    {
        $data = ModeloProveedores::mdlEliminarProveedor($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorProveedores();
    $data->listarProveedores();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorProveedores();
        $data->nombres = $_POST["nombre"];
        $data->agregarProveedores();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorProveedores();
        $data->id = $_POST["id"];
        $data->nombres = $_POST["nombre"];
        $data->editarProveedor();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorProveedores();
        $data->id = $_POST["id"];
        $data->eliminarProveedor();
    }
}
