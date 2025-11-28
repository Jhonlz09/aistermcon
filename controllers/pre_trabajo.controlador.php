<?php
require_once "../models/pre_trabajo.modelo.php";

class ControladorPretrabajo
{
    public $id, $detalles, $id_cliente, $estado, $presupuesto, $anio, $cliente, $fecha, $precio_iva, $precio_total, $nota, $ext, $ruta, $carpeta, $tipo, $isManual, $cliente_manual;

    public function listarPretrabajo()
    {
        $data = ModeloPretrabajo::mdlListarPretrabajo($this->anio, $this->estado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarPretrabajo()
    {
        $year = date("Y", strtotime($this->fecha));
        $uploadDir = "/var/www/pre_trabajo/$year/";

        $pdf_arr = [];
        $img_arr = [];

        /* ------------------- 📄 ORDEN DE COMPRA (ARRAYS) ------------------- */
        if (isset($_FILES['pre_trabajo_files'])) {
            $files = $_FILES['pre_trabajo_files'];
            $baseName = $this->fecha . '   ' . $this->cliente;
            $this->procesarArchivos($files, $uploadDir, $baseName, $year, $pdf_arr, $img_arr);
        }

        // Convertir arrays a formato PostgreSQL TEXT[]
        $pdf_arr = !empty($pdf_arr) ? $this->arrayToPgArray($pdf_arr) : null;
        $img_arr = !empty($img_arr) ? $this->arrayToPgArray($img_arr) : null;

        /* ------------------- 📝 INSERTAR EN BD ------------------- */
        $data = ModeloPretrabajo::mdlAgregarPretrabajo($this->fecha,$this->cliente,$this->detalles, $pdf_arr,$img_arr);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarPretrabajo()
    {
        $year = date("Y", strtotime($this->fecha));
        $uploadDir = "/var/www/pre_trabajo/$year/";

        // Rutas nuevas (solo se llenan si llegan archivos nuevos)
        $pdf_pre = $xls_pre = $pdf_ord = $xls_ord = $doc_ae = $pdf_ae = null;
        $pdf_arr = [];
        $img_arr = [];

        /* ------------------- 📄 ORDEN DE COMPRA (ARRAYS) ------------------- */
        if (isset($_FILES['orden_compra_files'])) {
            $files = $_FILES['orden_compra_files'];
            $baseName = $this->fecha . '   ' . $this->cliente;
            $this->procesarArchivos($files, $uploadDir, $baseName, $year, $pdf_arr, $img_arr);
        }

        // Convertir arrays a formato PostgreSQL TEXT[]
        $pdf_arr = !empty($pdf_arr) ? $this->arrayToPgArray($pdf_arr) : null;
        $img_arr = !empty($img_arr) ? $this->arrayToPgArray($img_arr) : null;

        // Actualizar en BD SOLO los campos que tengan cambios
        $data = ModeloPretrabajo::mdlEditarPretrabajo($this->id,$this->cliente,$pdf_pre,$pdf_ord,$xls_pre,$xls_ord,$doc_ae,$pdf_ae,$pdf_arr,$img_arr,$this->fecha,$this->detalles
        );

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // private function procesarArchivos($files, $uploadDir, $baseName, $year, &$pdfVar, &$xlsOrDocVar)
    // {
    //     if (is_array($files['name'])) {
    //         for ($i = 0; $i < count($files['name']); $i++) {
    //             $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
    //             $filePath = $uploadDir . $baseName . '.' . $ext;
    //             $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
    //             $dest = $uploadDir . $uniqueFileName;
    //             if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
    //                 if ($ext === 'pdf') $pdfVar = "$year/$uniqueFileName";
    //                 else $xlsOrDocVar = "$year/$uniqueFileName";
    //             }
    //         }
    //     } else {
    //         $ext = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
    //         $filePath = $uploadDir . $baseName . '.' . $ext;
    //         $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
    //         $dest = $uploadDir . $uniqueFileName;
    //         if (move_uploaded_file($files['tmp_name'], $dest)) {
    //             if ($ext === 'pdf') $pdfVar = "$year/$uniqueFileName";
    //             else $xlsOrDocVar = "$year/$uniqueFileName";
    //         }
    //     }
    // }

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

    public function eliminarPretrabajo()
    {
        $data = ModeloPretrabajo::mdlEliminarPretrabajo($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function cambiarEstadoPretrabajo()
    {
        $data = ModeloPretrabajo::mdlCambiarEstado($this->id, $this->estado,);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerIdPretrabajo()
    {
        $data = ModeloPretrabajo::mdlobtenerIdPretrabajo($this->descrip);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerFiles()
    {
        $data = ModeloPretrabajo::mdlObtenerTodosLosArchivos($this->id);

        if (empty($data)) {
            // Si no hay imágenes, retorna un arreglo vacío
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

    public function eliminarFilePretrabajo()
    {
        $data = ModeloPretrabajo::mdlEliminarFilePretrabajo($this->id, $this->ruta, $this->ext);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarFiles()
    {
        $data = ModeloPretrabajo::mdlEliminarArchivo($this->id, $this->ruta, $this->ext, $this->carpeta, $this->tipo);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorPretrabajo();
    $data->anio = $_POST["anio"];
    $data->estado = $_POST["id_estado"];
    $data->listarPretrabajo();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorPretrabajo();
        $data->descrip = $_POST["des"];
        $data->presupuesto = $_POST["presupuesto"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->isManual = $_POST["isManual"] ?? false;
        $data->cliente_manual = $_POST["cliente_manual"] ?? '';
        $data->cliente = $_POST["cliente"];
        $data->fecha = $_POST["fecha"];
        $data->precio_iva = $_POST["precio_sin_iva"];
        $data->precio_total = $_POST["precio_con_iva"];
        $data->nota = $_POST["nota"];
        $data->agregarPretrabajo();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorPretrabajo();
        $data->id = $_POST["id"];
        $data->descrip = $_POST["des"];
        $data->presupuesto = $_POST["presupuesto"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->cliente = $_POST["cliente"];
        $data->fecha = $_POST["fecha"];
        $data->precio_iva = $_POST["precio_sin_iva"];
        $data->precio_total = $_POST["precio_con_iva"];
        $data->nota = $_POST["nota"];
        // $data->estado = $_POST["estado"];
        $data->editarPretrabajo();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorPretrabajo();
        $data->id = $_POST["id"];
        $data->eliminarPretrabajo();
    } else if ($_POST["accion"] == 4) {
        $data = new ControladorPretrabajo();
        $data->descrip = $_POST["descrip"];
        $data->obtenerIdPretrabajo();
    } else if ($_POST["accion"] == 5) {
        $data = new ControladorPretrabajo();
        $data->id = $_POST["id"];
        $data->estado = $_POST["estado"];
        // $data->id_cliente = $_POST["id_cliente"];
        $data->cambiarEstadoPretrabajo();
    } else if ($_POST["accion"] == 6) {
        $data = new ControladorPretrabajo();
        $data->id = $_POST["id"];
        $data->obtenerFiles();
    } //else if ($_POST["accion"] == 7) {
    //     $data = new ControladorPretrabajo();
    //     $data->id = $_POST["id"];
    //     $data->obtenerFilesPretrabajo();
    // } 
    else if ($_POST["accion"] == 8) {
        $data = new ControladorPretrabajo();
        $data->id = $_POST["id"];
        $data->ruta = $_POST["ruta"];
        $data->ext = $_POST["ext"];
        $data->eliminarFilePretrabajo();
    } else if ($_POST["accion"] == 9) {
        $data = new ControladorPretrabajo();
        $data->id = $_POST["id"];
        $data->ruta = $_POST["ruta"];
        $data->ext = $_POST["ext"];
        $data->carpeta = $_POST["carpeta"];
        $data->tipo = $_POST["tipo"];
        $data->eliminarFiles();
    } //else if ($_POST["accion"] == 10) {
    //     $data = new ControladorPretrabajo();
    //     $data->id = $_POST["id"];
    //     $data->obtenerFilesActaEntrega();
    // } else if ($_POST["accion"] == 11) {
    //     $data = new ControladorPretrabajo();
    //     $data->id = $_POST["id"];
    //     $data->obtenerFilesOrdenCompra();
    // }
}
