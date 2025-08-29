<?php
require_once "../models/presupuesto.modelo.php";

class ControladorPresupuesto
{
    public $id, $descrip, $id_cliente, $estado, $presupuesto, $anio, $filePresupuesto, $cliente, $fecha, $nota;

    public function listarPresupuesto()
    {
        $data = ModeloPresupuesto::mdlListarPresupuesto($this->anio, $this->estado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarPresupuesto()
    {
        $finalPath = '';
        if (isset($_FILES['filePresupuesto']) && $_FILES['filePresupuesto']['type'] === 'application/pdf') {
            $year = date("Y");
            $uploadDir = '/var/www/presupuestos/';
            $fileName = basename($_FILES['filePresupuesto']['name']);
            $filePath = $uploadDir . $year . '/' . $fileName;
            $fullNameFinal = $this->presupuesto . '   ' . $this->cliente;
            $filePath = $this->generateUniqueFilePath($filePath, $fullNameFinal);

            $savePath = $uploadDir . $year .'/'. $filePath;
            $finalPath = $year .'/'. $filePath;

            if (move_uploaded_file($_FILES['filePresupuesto']['tmp_name'], $savePath)) {
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

        $data = ModeloPresupuesto::mdlAgregarPresupuesto($this->descrip, $this->id_cliente, $this->cliente, $this->presupuesto, $finalPath, $this->fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarPresupuesto()
    {
        $existingPdf = ModeloPresupuesto::mdlIsPdfPresupuesto($this->id);

        if (isset($_FILES['filePresupuesto']) && $_FILES['filePresupuesto']['type'] === 'application/pdf') {
            // $year = date("Y", strtotime($existingPdf['fecha_creacion'])); // Mantener el año de la creación
            list($year, $oldFileName) = explode('/', $existingPdf);
            $uploadDir = '/var/www/uploads/';
            $fileName = basename($_FILES['filePresupuesto']['name']);
            $filePath = $uploadDir . $year . '/' . $fileName;
            $fullNameFinal = $this->presupuesto . '   ' . $this->cliente;
            // Generar un nombre único si el archivo ya existe
            if (file_exists($uploadDir . $existingPdf)) {
                unlink($uploadDir . $existingPdf);
            }
            $filePath = $this->generateUniqueFilePath($filePath, $fullNameFinal);
            $savePath = $uploadDir . $year . '/' . $filePath;
            $finalPath = $year . '/' . $filePath;

            if (move_uploaded_file($_FILES['filePresupuesto']['tmp_name'], $savePath)) {
                
            } else {
                echo json_encode(['status' => 'danger', 'm' => 'Error al subir el archivo. ' .$savePath ], JSON_UNESCAPED_UNICODE);
                return;
            }

        } else {
            // Mantener la ruta del archivo actual si no se ha subido uno nuevo
            $finalPath = $existingPdf;
        }

        $data = ModeloPresupuesto::mdlEditarPresupuesto($this->id, $this->descrip, $this->id_cliente, $this->presupuesto, $finalPath);
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

    public function eliminarPresupuesto()
    {
        $data = ModeloPresupuesto::mdlEliminarPresupuesto($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function cambiarEstadoPresupuesto()
    {
        $data = ModeloPresupuesto::mdlCambiarEstado($this->id, $this->estado, $this->fecha, $this->nota );
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerIdPresupuesto()
    {
        $data = ModeloPresupuesto::mdlobtenerIdPresupuesto($this->descrip);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarPresupuestoes()
    {
        $data = ModeloPresupuesto::mdlBuscarPresupuestoes();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorPresupuesto();
    $data->anio = $_POST["anio"];
    $data->estado = $_POST["id_estado"];
    $data->listarPresupuesto();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorPresupuesto();
        $data->descrip = $_POST["nombre"];
        $data->presupuesto = $_POST["presupuesto"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->cliente = $_POST["cliente"];
        $data->fecha = $_POST["fecha"];
        $data->agregarPresupuesto();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorPresupuesto();
        $data->id = $_POST["id"];
        $data->descrip = $_POST["nombre"];
        $data->presupuesto = $_POST["presupuesto"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->cliente = $_POST["cliente"];
        // $data->estado = $_POST["estado"];
        $data->editarPresupuesto();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorPresupuesto();
        $data->id = $_POST["id"];
        $data->eliminarPresupuesto();
    } else if ($_POST["accion"] == 4) {
        $data = new ControladorPresupuesto();
        $data->descrip = $_POST["nombre"];
        $data->obtenerIdPresupuesto();
    } else if ($_POST["accion"] == 5) {
        $data = new ControladorPresupuesto();
        $data->id = $_POST["id"];
        $data->estado = $_POST["estado"];
        $data->fecha = $_POST["fecha"];
        $data->nota = $_POST["nota"];
        $data->cambiarEstadoPresupuesto();
    }else if ($_POST["accion"] == 6) {
        $data = new ControladorPresupuesto();
        $data->buscarPresupuestoes();
    }
}
