<?php
require_once "../models/inicio.modelo.php";
class ControladorInicio
{
    public $anio, $mes, $categoria;
    
    public function listarTarjetas()
    {
        $data = ModeloInicio::mdlListarTarjetas($this->anio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function graficoSalidas()
    {
        $data = ModeloInicio::mdlGraficoSalidas($this->mes, $this->anio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function tblTop10()
    {
        $data = ModeloInicio::mdlTblTop10($this->mes, $this->anio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function tblPocoStock()
    {
        $data = ModeloInicio::mdlTblPocoStock();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function graficoCategorias()
    {
        $data = ModeloInicio::mdlGraficoCategorias($this->categoria);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorInicio();
    $data->anio = $_POST["anio"];
    $data->listarTarjetas();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorInicio();
        $data->anio = $_POST["anio"];
        $data->mes = $_POST["mes"];
        $data->graficoSalidas();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorInicio();
        $data->anio = $_POST["anio"];
        $data->mes = $_POST["mes"];
        $data->tblTop10();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorInicio();
        $data->tblPocoStock();
    }else if ($_POST["accion"] == 4) {
        $data = new ControladorInicio();
        $data->categoria = $_POST["categoria"];
        $data->graficoCategorias();
    }
}
