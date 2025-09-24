<?php
require_once "../models/orden.modelo.php";

class ControladorOrden
{
    public $id, $descrip, $id_cliente, $estado, $orden, $anio, $fileOrden, $cliente, $fecha, $nota;

    public function listarOrden()
    {
        $data = ModeloOrden::mdlListarOrden($this->anio, $this->estado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarOrden()
    {
        $finalPath = '';

        if (isset($_FILES['fileOrden']) && $_FILES['fileOrden']['type'] === 'application/pdf') {
            $year = date("Y");
            $uploadDir = '/var/www/ordenes/';
            $fileName = basename($_FILES['fileOrden']['name']);
            $filePath = $uploadDir . $year . '/' . $fileName;

            $fullNameFinal = $this->orden . '   ' . $this->cliente;
            // Generar un nombre único si el archivo ya existe
            $filePath = $this->generateUniqueFilePath($filePath, $fullNameFinal);

            $savePath = $uploadDir . $year . '/' . $filePath;
            $finalPath = $year . '/' . $filePath;

            if (move_uploaded_file($_FILES['fileOrden']['tmp_name'], $savePath)) {
                // Archivo subido exitosamente
            } else {
                $error = error_get_last();
                $errorMessage = 'Error al subir el archivo.';
                if ($error !== null) {
                    $errorMessage .= ' Detalles: ' . $error['message'] . ' en ' . $error['file'] . ' línea ' . $error['line'];
                }
                echo json_encode(['status' => 'danger', 'm' => $errorMessage]);
                return;
            }
        }

        $data = ModeloOrden::mdlAgregarOrden($this->descrip, $this->id_cliente, $this->cliente, $this->orden, $finalPath, $this->fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarOrden()
    {
        $existingPdf = ModeloOrden::mdlIsPdfOrden($this->id);

        if (isset($_FILES['fileOrden']) && $_FILES['fileOrden']['type'] === 'application/pdf') {
            // $year = date("Y", strtotime($existingPdf['fecha_creacion']));
            $uploadDir = '/var/www/ordenes/';
            $fileName = basename($_FILES['fileOrden']['name']);

            if ($existingPdf['pdf_ord'] == '') {
                $year = date("Y", strtotime($existingPdf['fecha']));
            } else {
                list($year, $oldFileName) = explode('/', $existingPdf['pdf_ord']);
                if (file_exists($uploadDir . $existingPdf['pdf_ord'])) {
                    unlink($uploadDir . $existingPdf['pdf_ord']);
                }
            }

            $filePath = $uploadDir . $year . '/' . $fileName;
            $fullNameFinal = $this->orden . '   ' . $this->cliente;

            $filePath = $this->generateUniqueFilePath($filePath, $fullNameFinal);
            $savePath = $uploadDir . $year . '/' . $filePath;
            $finalPath = $year . '/' . $filePath;

            if (move_uploaded_file($_FILES['fileOrden']['tmp_name'], $savePath)) {
            } else {
                echo json_encode(['status' => 'danger', 'm' => 'Error al subir el archivo. ' . $savePath], JSON_UNESCAPED_UNICODE);
                return;
            }
        } else {
            // Mantener la ruta del archivo actual si no se ha subido uno nuevo
            $finalPath = $existingPdf['pdf_ord'];
        }

        $data = ModeloOrden::mdlEditarOrden($this->id, $this->descrip, $this->id_cliente, $this->orden, $finalPath);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function generateUniqueFilePath($filePath, $full)
    {
        $pathInfo = pathinfo($filePath);
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

    public function cambiarEstadoOrden()
    {
        $data = ModeloOrden::mdlCambiarEstado($this->id, $this->estado, $this->fecha, $this->nota);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerIdOrden()
    {
        $data = ModeloOrden::mdlobtenerIdOrden($this->descrip, $this->fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarOrdenes()
    {
        $data = ModeloOrden::mdlBuscarOrdenes();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorOrden();
    $data->anio = $_POST["anio"];
    $data->estado = $_POST["id_estado"];
    $data->listarOrden();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorOrden();
        $data->descrip = $_POST["nombre"];
        $data->orden = $_POST["orden"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->cliente = $_POST["cliente"];
        // $data->estado = $_POST["estado"];
        $data->fecha = $_POST["fecha"];
        $data->agregarOrden();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorOrden();
        $data->id = $_POST["id"];
        $data->descrip = $_POST["nombre"];
        $data->orden = $_POST["orden"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->cliente = $_POST["cliente"];
        // $data->estado = $_POST["estado"];
        $data->editarOrden();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorOrden();
        $data->id = $_POST["id"];
        $data->eliminarOrden();
    } else if ($_POST["accion"] == 4) {
        $data = new ControladorOrden();
        $data->descrip = $_POST["nombre"];
        $data->fecha = $_POST["fecha"];
        $data->obtenerIdOrden();
    } else if ($_POST["accion"] == 5) {
        $data = new ControladorOrden();
        $data->id = $_POST["id"];
        $data->estado = $_POST["estado"];
        $data->fecha = $_POST["fecha"];
        $data->nota = $_POST["nota"];
        $data->cambiarEstadoOrden();
    } else if ($_POST["accion"] == 6) {
        $data = new ControladorOrden();
        $data->buscarOrdenes();
    }
}
