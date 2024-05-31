<?php
require_once "../models/carga.modelo.php";

class ControladorCarga
{
    public $file;

    public function cargarProductos()
    {
        $data = ModeloCarga::mdlCargarProductos($this->file);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function cargarActualizacionInventario()
    {
        $data = ModeloCarga::mdlActualizacionInventario($this->file);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function cargarCategorias()
    {
        $data = ModeloCarga::mdlCargarCategorias($this->file);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function cargarUnidades()
    {
        $data = ModeloCarga::mdlCargarUnidades($this->file);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function cargarUbicacion()
    {
        $data = ModeloCarga::mdlCargarUbicacion($this->file);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function cargarProveedores()
    {
        $data = ModeloCarga::mdlCargarProveedores($this->file);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function cargarClientes()
    {
        $data = ModeloCarga::mdlCargarClientes($this->file);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function cargarEmpleados()
    {
        $data = ModeloCarga::mdlCargarEmpleados($this->file);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}


if ($_POST["accion"] == 1) {
    $data = new ControladorCarga();
    $data->file = $_FILES['filePro'];
    $data->cargarProductos();
} else if ($_POST["accion"] == 2) {
    $data = new ControladorCarga();
    $data->file = $_FILES['fileCat'];
    $data->cargarCategorias();
} else if ($_POST["accion"] == 3) {
    $data = new ControladorCarga();
    $data->file = $_FILES['fileUnd'];
    $data->cargarUnidades();
}else if ($_POST["accion"] == 4) {
    $data = new ControladorCarga();
    $data->file = $_FILES['fileUbi'];
    $data->cargarUbicacion();
}else if ($_POST["accion"] == 5) {
    $data = new ControladorCarga();
    $data->file = $_FILES['fileProveedores'];
    $data->cargarProveedores();
}else if ($_POST["accion"] == 6) {
    $data = new ControladorCarga();
    $data->file = $_FILES['fileClientes'];
    $data->cargarClientes();
}else if ($_POST["accion"] == 7) {
    $data = new ControladorCarga();
    $data->file = $_FILES['fileEmpleados'];
    $data->cargarEmpleados();
}else if ($_POST["accion"] == 8) {
    $data = new ControladorCarga();
    $data->file = $_FILES['fileInv'];
    $data->cargarActualizacionInventario();
}
