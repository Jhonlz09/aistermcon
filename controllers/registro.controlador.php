<?php

require_once "../models/registro.modelo.php";

class ajaxRegistro
{

    // public function ajaxObtenerNroBoleta()
    // {

    //     $nroBoleta = ModeloRegistro::mdlObtenerNroBoleta();

    //     echo json_encode($nroBoleta, JSON_UNESCAPED_UNICODE);
    // }

    public function registrarCompra($datos, $nro_factura, $proveedor, $fecha)
    {

        $data = ModeloRegistro::mdlRegistrarCompra($datos, $nro_factura, $proveedor, $fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function registrarSalida($datos, $orden, $cliente, $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo)
    {

        $data = ModeloRegistro::mdlRegistrarSalida($datos, $orden, $cliente, $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function registrarEntrada($datos, $orden, $cliente, $fecha,$fecha_entrada, $motivo, $conductor, $responsable, $despachado)
    {

        $data = ModeloRegistro::mdlRegistrarEntrada($datos, $orden, $cliente, $fecha, $fecha_entrada, $motivo, $conductor, $responsable, $despachado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarRegistroSalida($id_boleta,$orden, $cliente, $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo)
    {
        $data = ModeloRegistro::mdlEditarRegistroSalida($id_boleta, $orden, $cliente, $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarRegistroCompra($id_boleta,$nro_factura, $proveedor, $fecha)
    {
        $data = ModeloRegistro::mdlEditarRegistroCompra($id_boleta,$nro_factura, $proveedor, $fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function registrarRetorno($datos, $boleta,$fecha_retorno, $nro_guia)
    {

        $data = ModeloRegistro::mdlRegistrarRetorno($datos, $boleta, $fecha_retorno, $nro_guia);
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
    $entrada->registrarCompra($_POST["arr"], $_POST["nro_factura"],$_POST["proveedor"], $_POST["fecha"]);
} else if (isset($_POST["accion"]) && $_POST["accion"] == 2) { // LISTADO DE VENTAS POR RANGO DE FECHAS
    $salida = new ajaxRegistro();
    $salida->registrarSalida($_POST["arr"], $_POST["orden"], $_POST["cliente"], $_POST["nro_guia"], $_POST["fecha"], $_POST["conductor"], $_POST["despachado"], $_POST["responsable"],$_POST["motivo"]);
} else if (isset($_POST["accion"]) && $_POST["accion"] == 3) {
    $salida = new ajaxRegistro();
    $salida->registrarRetorno($_POST["arr"], $_POST["boleta"], $_POST["fecha_retorno"], $_POST["nro_guia"]);
} else if (isset($_POST["accion"]) && $_POST["accion"] == 4) { // LISTADO DE VENTAS POR RANGO DE FECHAS
    $salida = new ajaxRegistro();
    $salida->editarRegistroSalida($_POST["id_boleta"], $_POST["orden"],$_POST["cliente"], $_POST["nro_guia"], $_POST["fecha"], $_POST["conductor"], $_POST["despachado"], $_POST["responsable"],$_POST["motivo"]);
}else if (isset($_POST["accion"]) && $_POST["accion"] == 5) {
    $entrada = new ajaxRegistro();
    $entrada->editarRegistroCompra($_POST["id_factura"],$_POST["nro_factura"],$_POST["proveedor"], $_POST["fecha"]);
}else if (isset($_POST["accion"]) && $_POST["accion"] == 6) {
    $entrada = new ajaxRegistro();
    $entrada->registrarPlantilla($_POST["arr"], $_POST["nombre_pla"]);
}else if (isset($_POST["accion"]) && $_POST["accion"] == 7) {
    $entrada = new ajaxRegistro();
    $entrada->registrarProductosFab($_POST["arr"],$_POST["id_producto_fab"]);
}else if (isset($_POST["accion"]) && $_POST["accion"] == 8) {
    $entrada = new ajaxRegistro();
    $entrada->registrarEntrada($_POST["arr"], $_POST["orden"], $_POST["cliente"],$_POST["fecha"],$_POST["fecha_retorno"],$_POST["motivo"],$_POST["conductor"],$_POST["responsable"],$_POST["despachado"] );
}
