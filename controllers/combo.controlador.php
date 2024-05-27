<?php

require_once "../models/combo.modelo.php";

class ControladorCombos{
    public $tabla;

    public function listar()
    {
        $data = ModeloCombos::mdlListar($this->tabla);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarConductor(){

        $data = ModeloCombos::mdlListarConductor();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarNumOrden(){

        $data = ModeloCombos::mdlListarOrden();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarClientesActivos(){

        $data = ModeloCombos::mdlListarClientesActivos();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function listarOrdenActivas(){

        $data = ModeloCombos::mdlListarOrdenActivas();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarDespachado(){

        $data = ModeloCombos::mdlListarDespachado();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarResponsable(){

        $data = ModeloCombos::mdlListarResponsable();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarProductoFab(){

        $data = ModeloCombos::mdlListarProductosFab();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function listarProductoFabCon(){

        $data = ModeloCombos::mdlListarProductosFabCon();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (isset($_POST['accion']) && $_POST['accion'] == 1) {
    $data = new ControladorCombos();
    $data->tabla = $_POST["tabla"];
    $data->listar();
}else if(isset($_POST['accion']) && $_POST['accion'] == 2){
    $data = new ControladorCombos();
    $data->listarConductor();
}else if(isset($_POST['accion']) && $_POST['accion'] == 3){
    $data = new ControladorCombos();
    $data->listarNumOrden();
}else if(isset($_POST['accion']) && $_POST['accion'] == 4){
    $data = new ControladorCombos();
    $data->listarClientesActivos();
}else if(isset($_POST['accion']) && $_POST['accion'] == 5){
    $data = new ControladorCombos();
    $data->listarOrdenActivas();
}else if(isset($_POST['accion']) && $_POST['accion'] == 6){
    $data = new ControladorCombos();
    $data->listarDespachado();
}else if(isset($_POST['accion']) && $_POST['accion'] == 7){
    $data = new ControladorCombos();
    $data->listarResponsable();
}else if(isset($_POST['accion']) && $_POST['accion'] == 8){
    $data = new ControladorCombos();
    $data->listarProductoFab();
}else if(isset($_POST['accion']) && $_POST['accion'] == 9){
    $data = new ControladorCombos();
    $data->listarProductoFabCon();
}