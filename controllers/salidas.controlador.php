<?php
require_once "../models/salidas.modelo.php";
class ControladorSalidas
{
    public $id, $nombres, $fecha, $anio, $mes;

    public function listarSalidas()
    {
        $data = ModeloSalidas::mdlListarSalidas($this->anio, $this->mes);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function agregarSalida()
    {
        $data = ModeloSalidas::mdlAgregarSalida($this->id, $this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function eliminarSalida()
    {
        $data = ModeloSalidas::mdlEliminarSalida($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarBoleta()
    {
        $data = ModeloSalidas::mdlBuscarBoleta($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarBoletaPDF()
    {
        $data = ModeloSalidas::mdlBuscarBoletaPDF($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // public function obtenerImgBoleta()
    // {
    //     $data = ModeloSalidas::mdlObtenerImgBoleta($this->id);
    //     echo $data; 
    // }

    public function obtenerImgBoleta()
    {
        $data = ModeloSalidas::mdlObtenerImgBoleta($this->id);

        if (empty($data)) {
            // Si no hay imágenes, retorna un arreglo vacío
            echo json_encode([
                'imagenes' => [],
            ]);
            return;
        }

        // Si hay imágenes, retornarlas en el JSON
        echo json_encode([
            'imagenes' => $data,
        ]);
    }


    public function eliminarImgBoleta()
    {
        $data = ModeloSalidas::mdlEliminarImgBoleta($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function detalleBoleta()
    {
        $data = ModeloSalidas::mdlDetalleBoleta($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarOrdenFecha()
    {
        $data = ModeloSalidas::mdlBuscarOrdenFecha($this->id, $this->fecha);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorSalidas();
    $data->anio = $_POST["anio"];
    $data->mes = $_POST["mes"];
    $data->listarSalidas();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorSalidas();
        $data->id = $_POST["id_boleta"];
        $data->nombres = $_POST["codigo"];
        $data->agregarSalida();
    } //else if ($_POST["accion"] == 2){
    //     $data = new ControladorSalidas();
    //     $data->id = $_POST["id_empleado"];
    //     $data->nombres = $_POST["nombres_empleado"];
    //     $data->fecha = $_POST["fecha"];
    //     $data->editarSalida();
    else if ($_POST["accion"] == 3) {
        $data = new ControladorSalidas();
        $data->id = $_POST["id"];
        $data->eliminarSalida();
    } else if ($_POST["accion"] == 4) {
        $data = new ControladorSalidas();
        $data->id = $_POST["boleta"];
        $data->buscarBoleta();
    } else if ($_POST["accion"] == 5) {
        $data = new ControladorSalidas();
        $data->id = $_POST["orden"];
        $data->fecha = $_POST["fecha"];
        $data->buscarOrdenFecha();
    } else if ($_POST["accion"] == 6) {
        $data = new ControladorSalidas();
        $data->id = $_POST["id"];
        $data->buscarBoletaPDF();
    } else if ($_POST["accion"] == 7) {
        $data = new ControladorSalidas();
        $data->id = $_POST["boleta"];
        $data->detalleBoleta();
    } else if ($_POST["accion"] == 8) {
        $data = new ControladorSalidas();
        $data->id = $_POST["boleta"];
        $data->obtenerImgBoleta();
    } else if ($_POST["accion"] == 9) {
        $data = new ControladorSalidas();
        $data->id = $_POST["nombre_imagen"];
        $data->eliminarImgBoleta();
    }
}
