<?php

require_once "../models/registro.modelo.php";

class ajaxRegistro
{

    // public function ajaxObtenerNroBoleta()
    // {

    //     $nroBoleta = ModeloRegistro::mdlObtenerNroBoleta();

    //     echo json_encode($nroBoleta, JSON_UNESCAPED_UNICODE);
    // }

    public function registrarEntrada($datos, $proveedor, $fecha)
    {

        $data = ModeloRegistro::mdlRegistrarEntrada($datos, $proveedor, $fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function registrarSalida($datos, $orden, $fecha,$conductor, $entrega)
    {

        $data = ModeloRegistro::mdlRegistrarSalida($datos, $orden, $fecha, $conductor, $entrega);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function registrarRetorno($datos, $boleta,$fecha_retorno)
    {

        $data = ModeloRegistro::mdlRegistrarRetorno($datos, $boleta, $fecha_retorno);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (isset($_POST["accion"]) && $_POST["accion"] == 1) {
    $entrada = new ajaxRegistro();
    $entrada->registrarEntrada($_POST["arr"], $_POST["proveedor"], $_POST["fecha"]);
} else if (isset($_POST["accion"]) && $_POST["accion"] == 2) { // LISTADO DE VENTAS POR RANGO DE FECHAS
    $salida = new ajaxRegistro();
    $salida->registrarSalida($_POST["arr"], $_POST["orden"], $_POST["fecha"], $_POST["conductor"], $_POST["entrega"]);
} else if (isset($_POST["accion"]) && $_POST["accion"] == 3) {
    $salida = new ajaxRegistro();
    $salida->registrarRetorno($_POST["arr"], $_POST["boleta"], $_POST["fecha_retorno"]);
}
