<?php
require_once "../models/empleados.modelo.php";

class ControladorEmpleados {
    public $id, $cedula, $nombre, $apellido, $celular, $id_empresa, $id_rol, $placa;
    
    public function listarEmpleados() {
        $data = ModeloEmpleados::mdlListarEmpleados($this->id_empresa);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function agregarEmpleados() {
        $data = ModeloEmpleados::mdlAgregarEmpleado($this->cedula, $this->nombre, $this->apellido, $this->celular, $this->id_empresa, $this->id_rol, $this->placa);
        $this->notifyWebSocket($data);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function eliminarEmpleado() {
        $data = ModeloEmpleados::mdlEliminarEmpleado($this->id);
        $this->notifyWebSocket($data);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function editarEmpleado() {
        $data = ModeloEmpleados::mdlEditarEmpleado($this->id, $this->cedula, $this->nombre, $this->apellido, $this->celular, $this->id_empresa, $this->id_rol, $this->placa);
        $this->notifyWebSocket($data);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    private function notifyWebSocket($data) {
        if ($data['status'] == 'success') {
            $wsData = [
                'action' => 'update',
                'data' => $data
            ];
            $wsData = json_encode($wsData);

            // Conectar con el servidor WebSocket y enviar el mensaje
            $host = '192.168.100.50'; // Cambia esto por la IP o dominio de tu servidor WebSocket
            $port = 8080;
            $fp = fsockopen($host, $port, $errno, $errstr, 30);
            if (!$fp) {
                error_log("Error: $errno - $errstr");
            } else {
                fwrite($fp, $wsData);
                fclose($fp);
            }
        }
    }
}

if (isset($_POST["accion"]) && $_POST["accion"] == 0) {
    $data = new ControladorEmpleados();
    $data->id_empresa = $_POST["id_empresa"];
    $data->listarEmpleados();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorEmpleados();
        $data->cedula = $_POST["cedula"];
        $data->nombre  = $_POST["nombre"];
        $data->apellido  = $_POST["apellido"];
        $data->celular  = $_POST["celular"];
        $data->id_empresa = $_POST["id_empresa"];
        $data->id_rol = $_POST["id_rol"];
        $data->placa = $_POST["id_placa"];
        $data->agregarEmpleados();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorEmpleados();
        $data->id = $_POST["id"];
        $data->cedula = $_POST["cedula"];
        $data->nombre  = $_POST["nombre"];
        $data->apellido  = $_POST["apellido"];
        $data->celular  = $_POST["celular"];
        $data->id_empresa = $_POST["id_empresa"];
        $data->id_rol = $_POST["id_rol"];
        $data->placa = $_POST["id_placa"];
        $data->editarEmpleado();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorEmpleados();
        $data->id = $_POST["id"];
        $data->eliminarEmpleado();
    }
}
