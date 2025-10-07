<?php
require_once "../models/producto.modelo.php";
class ControladorProductos
{
    public $id,$nombres,$tabla, $id_cliente;

    public function listar()
    {
        $data = ModeloProductos::mdlListar($this->tabla);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregar()
    {
        $data = ModeloProductos::mdlAgregar($this->nombres, $this->tabla);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function eliminar()
    {
        $data = ModeloProductos::mdlEliminar($this->id, $this->tabla);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editar()
    {
        $data = ModeloProductos::mdlEditar($this->id,$this->nombres,$this->tabla);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarOrden()
    {
        $data = ModeloProductos::mdlAgregarOrden($this->nombres, $this->id_cliente);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarOrden()
    {
        $data = ModeloProductos::mdlEditarOrden($this->id,$this->nombres,$this->id_cliente);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // public function eliminarOrden()
    // {
    //     $data = ModeloProductos::mdlEliminarOrden($this->id);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }
    
}
    if ($_POST["accion"] == 1) {
        $data = new ControladorProductos();
        $data->nombres = $_POST["nombre"];
        $data->tabla = $_POST["tabla"];
        $data->agregar();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorProductos();
        $data->id = $_POST["id"];
        $data->nombres = $_POST["nombre"];
        $data->tabla = $_POST["tabla"];
        $data->editar();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorProductos();
        $data->id = $_POST["id"];
        $data->tabla = $_POST["tabla"];
        $data->eliminar();
    }else if ($_POST["accion"] == 4) {
        $data = new ControladorProductos();
        $data->nombres = $_POST["num_orden"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->agregarOrden();
    }else if ($_POST["accion"] == 5) {
        $data = new ControladorProductos();
        $data->id = $_POST["id"];
        $data->nombres = $_POST["num_orden"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->editarOrden();
    // }else if ($_POST["accion"] == 6) {
    //     $data = new ControladorProductos();
    //     $data->id = $_POST["id"];
    //     $data->eliminarOrden();
    }
