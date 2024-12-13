<?php
require_once "../models/configuracion.modelo.php";

class ControladorConfiguracion
{
    public $iva, $sc, $nombre, $logo, $emisor, $correo1, $correo2, $tel, $dir, $ruc, $isentrada;

    public function editarConfigDatos()
    {
        $data = ModeloConfiguracion::mdlEditarConfigDatos($this->nombre);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarConfigMov()
    {
        $data = ModeloConfiguracion::mdlEditarConfigMov($this->isentrada);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarConfigCompra()
    {
        $data = ModeloConfiguracion::mdlEditarConfigCompra($this->iva, $this->sc);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarConfigGuia()
    {
        $data = ModeloConfiguracion::mdlEditarConfigGuia($this->ruc,$this->emisor,$this->dir, $this->tel, $this->correo1);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarConfigPref()
    {
        $data = ModeloConfiguracion::mdlEditarConfigPref($this->nombre,$this->emisor);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarConfigOpe()
    {
        $data = ModeloConfiguracion::mdlEditarConfigOpe($this->correo1);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerCorreos()
    {
        $data = ModeloConfiguracion::mdlObtenerCorreos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (isset($_POST["accion"])) {
    $data = new ControladorConfiguracion();
    if ($_POST["accion"] == 1) {
        $data->nombre = $_POST["empresa"];
        $data->editarConfigDatos();
    } else if ($_POST["accion"] == 2) {
        $data->isentrada = $_POST["isentrada"];
        $data->editarConfigMov();
    } else if ($_POST["accion"] == 3) {
        $data->iva = $_POST["iva"];
        $data->sc = $_POST["nro_cot"];
        $data->editarConfigCompra();
    }else if ($_POST["accion"] == 4) {
        $data->ruc = $_POST["ruc"];
        $data->emisor = $_POST["emisor"];
        $data->dir = $_POST["dir"];
        $data->tel = $_POST["tel"];
        $data->correo1 = $_POST["correo1"];
        $data->editarConfigGuia();
    }else if ($_POST["accion"] == 5) {
        $data->nombre = $_POST["bodeguero"];
        $data->emisor = $_POST["conductor"];
        $data->editarConfigPref();
    }else if ($_POST["accion"] == 6) {
        $data->correo1 = $_POST["correos"];
        $data->editarConfigOpe();
    }
}else{
    $data = new ControladorConfiguracion();
    $data->obtenerCorreos();
}
