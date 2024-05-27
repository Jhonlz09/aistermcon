<?php

require_once "../models/registro.modelo.php";

class ajaxRegistro
{

    // public function ajaxObtenerNroBoleta()
    // {

    //     $nroBoleta = ModeloRegistro::mdlObtenerNroBoleta();

    //     echo json_encode($nroBoleta, JSON_UNESCAPED_UNICODE);
    // }

    public function registrarEntrada($datos, $nro_factura, $proveedor, $fecha)
    {

        $data = ModeloRegistro::mdlRegistrarEntrada($datos, $nro_factura, $proveedor, $fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function registrarSalida($datos, $orden, $nro_guia, $fecha, $conductor, $despachado, $responsable)
    {

        $data = ModeloRegistro::mdlRegistrarSalida($datos, $orden, $nro_guia, $fecha, $conductor, $despachado, $responsable);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarRegistroSalida($id_boleta,$orden, $nro_guia, $fecha, $conductor, $despachado, $responsable)
    {

        $data = ModeloRegistro::mdlEditarRegistroSalida($id_boleta, $orden, $nro_guia, $fecha, $conductor, $despachado, $responsable);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarRegistroEntrada($id_boleta,$nro_factura, $proveedor, $fecha)
    {

        $data = ModeloRegistro::mdlEditarRegistroEntrada($id_boleta,$nro_factura, $proveedor, $fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function registrarRetorno($datos, $boleta,$fecha_retorno)
    {

        $data = ModeloRegistro::mdlRegistrarRetorno($datos, $boleta, $fecha_retorno);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function registrarPlantilla($datos, $nombre)
    {
        $data = ModeloRegistro::mdlRegistrarPlantilla($datos, $nombre);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function registrarProductosFab($datos, $id_producto_fab)
    {
        $data = ModeloRegistro::mdlRegistrarProductosFab($datos, $id_producto_fab);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (isset($_POST["accion"]) && $_POST["accion"] == 1) {
    $entrada = new ajaxRegistro();
    $entrada->registrarEntrada($_POST["arr"], $_POST["nro_factura"],$_POST["proveedor"], $_POST["fecha"]);
} else if (isset($_POST["accion"]) && $_POST["accion"] == 2) { // LISTADO DE VENTAS POR RANGO DE FECHAS
    $salida = new ajaxRegistro();
    $salida->registrarSalida($_POST["arr"], $_POST["orden"], $_POST["nro_guia"], $_POST["fecha"], $_POST["conductor"], $_POST["despachado"], $_POST["responsable"]);
} else if (isset($_POST["accion"]) && $_POST["accion"] == 3) {
    $salida = new ajaxRegistro();
    $salida->registrarRetorno($_POST["arr"], $_POST["boleta"], $_POST["fecha_retorno"]);
} else if (isset($_POST["accion"]) && $_POST["accion"] == 4) { // LISTADO DE VENTAS POR RANGO DE FECHAS
    $salida = new ajaxRegistro();
    $salida->editarRegistroSalida($_POST["id_boleta"], $_POST["orden"], $_POST["nro_guia"], $_POST["fecha"], $_POST["conductor"], $_POST["despachado"], $_POST["responsable"]);
}else if (isset($_POST["accion"]) && $_POST["accion"] == 5) {
    $entrada = new ajaxRegistro();
    $entrada->editarRegistroEntrada($_POST["id_factura"],$_POST["nro_factura"],$_POST["proveedor"], $_POST["fecha"]);
}else if (isset($_POST["accion"]) && $_POST["accion"] == 6) {
    $entrada = new ajaxRegistro();
    $entrada->registrarPlantilla($_POST["arr"], $_POST["nombre_pla"]);
}else if (isset($_POST["accion"]) && $_POST["accion"] == 7) {
    $entrada = new ajaxRegistro();
    $entrada->registrarProductosFab($_POST["arr"],$_POST["id_producto_fab"]);
}
