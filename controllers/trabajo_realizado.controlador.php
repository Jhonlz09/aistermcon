<?php
require_once "../models/trabajo_realizado.modelo.php";

class ControladorTrabajoRealizado
{
    public $id, $nota, $id_cliente, $estado, $anio, $cliente, $fecha, $ext, $ruta, $isFinalizado;

    public function listarTrabajoRealizado()
    {
        $data = ModeloTrabajoRealizado::mdlListarTrabajoRealizado($this->anio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarTrabajoRealizado()
    {
        $year = date("Y", strtotime($this->fecha));
        $uploadDir = "/var/www/trabajo_realizado/$year/";
        $pdf_arr = [];
        $img_arr = [];
        if (isset($_FILES['trabajo_realizado_files'])) {
            $files = $_FILES['trabajo_realizado_files'];
            $baseName = $this->fecha . '   ' . $this->cliente;
            $this->procesarArchivos($files, $uploadDir, $baseName, $year, $pdf_arr, $img_arr);
        }
        // Convertir arrays a formato PostgreSQL TEXT[]
        $pdf_arr = !empty($pdf_arr) ? $this->arrayToPgArray($pdf_arr) : null;
        $img_arr = !empty($img_arr) ? $this->arrayToPgArray($img_arr) : null;
        /* ------------------- 📝 INSERTAR EN BD ------------------- */
        $data = ModeloTrabajoRealizado::mdlAgregarTrabajoRealizado($this->fecha, $this->cliente, $this->nota, $pdf_arr, $img_arr, $this->isFinalizado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarTrabajoRealizado()
    {
        $year = date("Y", strtotime($this->fecha));
        $uploadDir = "/var/www/trabajo_realizado/$year/";
        $pdf_arr = [];
        $img_arr = [];

        if (isset($_FILES['trabajo_realizado_files'])) {
            $files = $_FILES['trabajo_realizado_files'];
            $baseName = $this->fecha . '   ' . $this->cliente;
            $this->procesarArchivos($files, $uploadDir, $baseName, $year, $pdf_arr, $img_arr);
        }

        // Convertir arrays a formato PostgreSQL TEXT[]
        $pdf_arr = !empty($pdf_arr) ? $this->arrayToPgArray($pdf_arr) : null;
        $img_arr = !empty($img_arr) ? $this->arrayToPgArray($img_arr) : null;

        $data = ModeloTrabajoRealizado::mdlEditarTrabajoRealizado($this->id, $this->fecha, $this->cliente, $this->nota, $pdf_arr, $img_arr, $this->isFinalizado);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function procesarArchivos($files, $uploadDir, $baseName, $year, &$pdfArray, &$imgArray)
    {
        if (is_array($files['name'])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                $filePath = $uploadDir . $baseName . '.' . $ext;
                $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
                $dest = $uploadDir . $uniqueFileName;
                if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                    if ($ext === 'pdf') $pdfArray[] = "$year/$uniqueFileName";
                    elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) $imgArray[] = "$year/$uniqueFileName";
                }
            }
        } else {
            $ext = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
            $filePath = $uploadDir . $baseName . '.' . $ext;
            $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
            $dest = $uploadDir . $uniqueFileName;
            if (move_uploaded_file($files['tmp_name'], $dest)) {
                if ($ext === 'pdf') $pdfArray[] = "$year/$uniqueFileName";
                elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) $imgArray[] = "$year/$uniqueFileName";
            }
        }
    }

    private function arrayToPgArray($arr)
    {
        return '{' . implode(',', array_map(fn($v) => '"' . $v . '"', $arr)) . '}';
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
        return $uniqueFilePath;
    }

    public function eliminarTrabajoRealizado()
    {
        $data = ModeloTrabajoRealizado::mdlEliminarTrabajoRealizado($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerFiles()
    {
        $data = ModeloTrabajoRealizado::mdlObtenerTodosLosArchivos($this->id);
        if (empty($data)) {
            echo json_encode([
                'files' => [],
            ]);
            return;
        }
        // Si hay imágenes, retornarlas en el JSON
        echo json_encode([
            'files' => $data,
        ]);
    }

    public function eliminarFiles()
    {
        $data = ModeloTrabajoRealizado::mdlEliminarArchivo($this->id, $this->ruta, $this->ext);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function cambiarEstadoTrabajoRealizado()
    {
        $data = ModeloTrabajoRealizado::mdlCambiarEstadoTrabajoRealizado($this->id, $this->isFinalizado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorTrabajoRealizado();
    $data->anio = $_POST["anio"];
    $data->listarTrabajoRealizado();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorTrabajoRealizado();
        $data->cliente = $_POST["cliente"];
        $data->fecha = $_POST["fecha"];
        $data->nota = $_POST["nota"];
        $data->isFinalizado = isset($_POST["isFinalizado"]) ? (filter_var($_POST["isFinalizado"], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false') : 'true';
        $data->agregarTrabajoRealizado();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorTrabajoRealizado();
        $data->id = $_POST["id"];
        $data->cliente = $_POST["cliente"];
        $data->fecha = $_POST["fecha"];
        $data->nota = $_POST["nota"];
        $data->isFinalizado = isset($_POST["isFinalizado"]) ? (filter_var($_POST["isFinalizado"], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false') : 'true';
        $data->editarTrabajoRealizado();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorTrabajoRealizado();
        $data->id = $_POST["id"];
        $data->eliminarTrabajoRealizado();
    } else if ($_POST["accion"] == 4) {
        $data = new ControladorTrabajoRealizado();
        $data->id = $_POST["id"];
        $data->obtenerFiles();
    } else if ($_POST["accion"] == 5) {
        $data = new ControladorTrabajoRealizado();
        $data->id = $_POST["id"];
        $data->ruta = $_POST["ruta"];
        $data->ext = $_POST["ext"];
        $data->eliminarFiles();
    } else if ($_POST["accion"] == 6) {
        $data = new ControladorTrabajoRealizado();
        $data->id = $_POST["id"];
        $data->isFinalizado = isset($_POST["isFinalizado"]) ? filter_var($_POST["isFinalizado"], FILTER_VALIDATE_BOOLEAN) : true;
        $data->cambiarEstadoTrabajoRealizado();
    }
}
