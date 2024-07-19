<?php
require_once "../models/orden.modelo.php";

class ControladorOrden
{
    public $id, $nombres, $id_cliente, $estado, $orden, $anio, $fileOrden, $cliente;

    public function listarOrden()
    {
        $data = ModeloOrden::mdlListarOrden($this->anio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarOrden()
    {
        $finalPath = '';

        if (isset($_FILES['fileOrden']) && $_FILES['fileOrden']['type'] === 'application/pdf') {
            $year = date("Y");
            $uploadDir = '/var/www/uploads/';
            $fileName = basename($_FILES['fileOrden']['name']);
            $filePath = $uploadDir . $year . '/' . $fileName;

            // $pathInfo = pathinfo($filePath);
            // $baseName = $pathInfo['filename'];
            // $extension = $pathInfo['extension'];
            // $directory = $pathInfo['dirname'];

            // echo json_encode(['status' => 'error', 'm' => $directory], JSON_UNESCAPED_UNICODE);
            //     return;
            $fullNameFinal = $this->orden . '   ' . $this->cliente;
            // Generar un nombre único si el archivo ya existe
            $filePath = $this->generateUniqueFilePath($filePath, $fullNameFinal);
            $savePath = $uploadDir . $year .'/'. $filePath;
            $finalPath = $year .'/'. $filePath;

            if (move_uploaded_file($_FILES['fileOrden']['tmp_name'], $savePath)) {
                // Archivo subido exitosamente
            } else {
                echo json_encode(['status' => 'danger', 'm' => 'Error al subir el archivo.'], JSON_UNESCAPED_UNICODE);
                return;
            }
        }

        $data = ModeloOrden::mdlAgregarOrden($this->nombres, $this->id_cliente, $this->orden, $this->estado, $finalPath);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }



    // public function editarOrden()
    // {




    //     $data = ModeloOrden::mdlEditarOrden($this->id, $this->nombres, $this->id_cliente, $this->orden, $this->estado);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }


    public function editarOrden()
    {
        $existingPdf = ModeloOrden::mdlIsPdfOrden($this->id);

        if (isset($_FILES['fileOrden']) && $_FILES['fileOrden']['type'] === 'application/pdf') {
            // $year = date("Y", strtotime($existingPdf['fecha_creacion'])); // Mantener el año de la creación
            list($year, $oldFileName) = explode('/', $existingPdf);
            $uploadDir = '/var/www/uploads/';
            $fileName = basename($_FILES['fileOrden']['name']);
            $filePath = $uploadDir . $year . '/' . $fileName;

            $fullNameFinal = $this->orden . '   ' . $this->cliente;
            // Generar un nombre único si el archivo ya existe
            if (file_exists($uploadDir . $existingPdf)) {
                unlink($uploadDir . $existingPdf);
            }
            $filePath = $this->generateUniqueFilePath($filePath, $fullNameFinal);
            $savePath = $uploadDir . $year . '/' . $filePath;
            $finalPath = $year . '/' . $filePath;

            if (move_uploaded_file($_FILES['fileOrden']['tmp_name'], $savePath)) {
                
            } else {
                echo json_encode(['status' => 'danger', 'm' => 'Error al subir el archivo.'], JSON_UNESCAPED_UNICODE);
                return;
            }

        } else {
            // Mantener la ruta del archivo actual si no se ha subido uno nuevo
            $finalPath = $existingPdf;
        }

        $data = ModeloOrden::mdlEditarOrden($this->id, $this->nombres, $this->id_cliente, $this->orden, $this->estado, $finalPath);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function generateUniqueFilePath($filePath, $full)
    {
        $pathInfo = pathinfo($filePath);
        $baseName = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        $directory = $pathInfo['dirname'];
        $counter = 1;
        $uniqueFilePath = $full . '.' . $extension;

        while (file_exists($directory . '/' . $uniqueFilePath)) {
            $uniqueFilePath =  $full . '_' . $counter . '.' . $extension;
            $counter++;
        }
        // Devuelve el nombre original si no hubo conflictos
        return $uniqueFilePath;
    }

    public function eliminarOrden()
    {
        $data = ModeloOrden::mdlEliminarOrden($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function actualizarEstadoOrden()
    {
        $data = ModeloOrden::mdlcambiarEstado($this->id, $this->estado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerIdOrden()
    {
        $data = ModeloOrden::mdlobtenerIdOrden($this->nombres);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorOrden();
    $data->anio = $_POST["anio"];
    $data->listarOrden();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorOrden();
        $data->nombres = $_POST["nombre"];
        $data->orden = $_POST["orden"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->cliente = $_POST["cliente"];
        $data->estado = $_POST["estado"];
        $data->agregarOrden();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorOrden();
        $data->id = $_POST["id"];
        $data->nombres = $_POST["nombre"];
        $data->orden = $_POST["orden"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->cliente = $_POST["cliente"];
        $data->estado = $_POST["estado"];

        $data->editarOrden();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorOrden();
        $data->id = $_POST["id"];
        $data->eliminarOrden();
    } else if ($_POST["accion"] == 4) {
        $data = new ControladorOrden();
        $data->nombres = $_POST["nombre"];
        $data->obtenerIdOrden();
    } else if ($_POST["accion"] == 5) {
        $data = new ControladorOrden();
        $data->id = $_POST["id"];
        $data->estado = $_POST["estado"];
        $data->actualizarEstadoOrden();
    }
}
