<?php

require_once "../models/combo.modelo.php";

class ControladorCombos
{
    public $tabla;
    public $anio;
    public function listar()
    {
        $data = ModeloCombos::mdlListar($this->tabla);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarConductor()
    {

        $data = ModeloCombos::mdlListarConductor();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarNumOrden()
    {

        $data = ModeloCombos::mdlListarOrden($this->anio);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // public function listarClientesActivos()
    // {

    //     $data = ModeloCombos::mdlListarClientesActivos();

    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }

    // public function listarOrdenActivas(){

    //     $data = ModeloCombos::mdlListarOrdenActivas();

    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }

    public function listarDespachado()
    {

        $data = ModeloCombos::mdlListarDespachado();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarResponsable($lastName = false)
    {

        $data = ModeloCombos::mdlListarResponsable($lastName);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarProductoFab()
    {

        $data = ModeloCombos::mdlListarProductosFab();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function listarProductoFabCon()
    {

        $data = ModeloCombos::mdlListarProductosFabCon();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarEmpresaFilter()
    {

        $data = ModeloCombos::mdlListarEmpresaFilter();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarOrdenEstadoFilter()
    {

        $data = ModeloCombos::mdlListarOrdenEstadoFilter();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function listarOrdenHorario()
    {

        $data = ModeloCombos::mdlListarOrdenHorario($this->anio);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function listarOrdenActivaHorario()
    {

        $data = ModeloCombos::listarOrdenActivaHorario();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (isset($_POST['accion']) && $_POST['accion'] == 1) {
    $data = new ControladorCombos();
    $data->tabla = $_POST["tabla"];
    $data->listar();
} else if (isset($_POST['accion']) && $_POST['accion'] == 2) {
    $data = new ControladorCombos();
    $data->listarConductor();
} else if (isset($_POST['accion']) && $_POST['accion'] == 3) {
    $data = new ControladorCombos();
    $data->anio = $_POST["anio"];
    $data->listarNumOrden();
// } else if (isset($_POST['accion']) && $_POST['accion'] == 4) {
//     $data = new ControladorCombos();
//     $data->listarClientesActivos();
} else if (isset($_POST['accion']) && $_POST['accion'] == 6) {
    $data = new ControladorCombos();
    $data->listarDespachado();
} else if (isset($_POST['accion']) && $_POST['accion'] == 7) {
    $formatoInvertido = isset($_POST['invertido']) ? filter_var($_POST['invertido'], FILTER_VALIDATE_BOOLEAN) : false;
    $data = new ControladorCombos();
    $data->listarResponsable($formatoInvertido);
} else if (isset($_POST['accion']) && $_POST['accion'] == 8) {
    $data = new ControladorCombos();
    $data->listarProductoFab();
} else if (isset($_POST['accion']) && $_POST['accion'] == 9) {
    $data = new ControladorCombos();
    $data->listarProductoFabCon();
} else if (isset($_POST['accion']) && $_POST['accion'] == 10) {
    $data = new ControladorCombos();
    $data->listarEmpresaFilter();
} else if (isset($_POST['accion']) && $_POST['accion'] == 11) {
    $data = new ControladorCombos();
    $data->listarOrdenEstadoFilter();
}else if (isset($_POST['accion']) && $_POST['accion'] == 12) {
    $data = new ControladorCombos();
    $data->anio = $_POST["anio"];
    $data->listarOrdenHorario();
}else if (isset($_POST['accion']) && $_POST['accion'] == 13) {
    $data = new ControladorCombos();
    $data->listarOrdenActivaHorario();
}
