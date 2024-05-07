<?php
require_once "../models/roles.modelo.php";

class ControladorRoles
{
    public $id, $nombres, $modulo, $crear, $editar, $eliminar;

    static public function listarRoles()
    {
        $data = ModeloRoles::mdlListarRoles();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarRoles()
    {
        $data = ModeloRoles::mdlAgregarRoles($this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function eliminarRol()
    {
        $data = ModeloRoles::mdlEliminarRol($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarRol()
    {
        $data = ModeloRoles::mdlEditarRol($this->id, $this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function getPermisos()
    {
        $data = ModeloRoles::mdlgetPermisos($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // public function savePermisos()
    // {
    //     $data = ModeloRoles::mdlSavePermisos($this->id,$this->modulo,$this->crear,$this->editar,$this->eliminar);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }

    public function savePermisos($datos)
    {
        $resultados = [];
        foreach ($datos as $permiso) {
            $resultado = ModeloRoles::mdlSavePermisos($this->id, $permiso['id_modulo'], $permiso['crear'], $permiso['editar'], $permiso['eliminar']);
            $resultados[] = $resultado;
        }
        echo json_encode($resultados, JSON_UNESCAPED_UNICODE);
    }

    public function deletePermisos()
    {
        $data = ModeloRoles::mdlDeletePermisos($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorRoles();
    $data->listarRoles();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorRoles();
        $data->nombres = $_POST["nombre"];
        $data->agregarRoles();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorRoles();
        $data->id = $_POST["id"];
        $data->nombres = $_POST["nombre"];
        $data->editarRol();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorRoles();
        $data->id = $_POST["id"];
        $data->eliminarRol();
    } else if ($_POST["accion"] == 4) {
        $data = new ControladorRoles();
        $data->id = $_POST["id_perfil"];
        $data->getPermisos();
    } else if ($_POST["accion"] == 5) {
        $data = new ControladorRoles();
        $data->id = $_POST["id_perfil"];
        $data->deletePermisos();
    } else if ($_POST["accion"] == 6) {
        $data = new ControladorRoles();
        $data->id = $_POST["id_perfil"];
        $datos = json_decode($_POST["datos"], true);
        $data->savePermisos($datos);
    }
}
