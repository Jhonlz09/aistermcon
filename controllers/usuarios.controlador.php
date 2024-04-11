<?php
require_once "../models/usuarios.modelo.php";

class ControladorUsuarios
{
    public $id,$nombres,$pass, $nombre_usuario, $id_perfil;

    static public function listarUsuarios()
    {
        $data = ModeloUsuarios::mdlListarUsuarios();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarUsuarios()
    {
        $data = ModeloUsuarios::mdlAgregarUsuarios($this->nombres, $this->nombre_usuario, $this->pass, $this->id_perfil);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarUsuario()
    {
        $data = ModeloUsuarios::mdlEditarUsuario($this->id,$this->nombres,$this->nombre_usuario,$this->id_perfil);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function editarClave()
    {
        $data = ModeloUsuarios::mdlEditarClave($this->id,$this->pass);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function eliminarUsuario()
    {
        $data = ModeloUsuarios::mdlEliminarUsuario($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorUsuarios();
    $data->listarUsuarios();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorUsuarios();
        $data->nombres = $_POST["nombres"];
        $data->nombre_usuario = $_POST["nombre_usuario"];
        $data->pass = $_POST["clave"];
        $data->id_perfil = $_POST["rol"];
        $data->agregarUsuarios();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorUsuarios();
        $data->id = $_POST["id"];
        $data->nombres = $_POST["nombres"];
        $data->nombre_usuario = $_POST["nombre_usuario"];
        $data->id_perfil = $_POST["rol"];
        $data->editarUsuario();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorUsuarios();
        $data->id = $_POST["id"];
        $data->eliminarUsuario();
    }else if ($_POST["accion"] == 4) {
        $data = new ControladorUsuarios();
        $data->id = $_POST["id"];
        $data->pass = $_POST["clave"];
        $data->editarClave();
    }
}
