<?php
require_once "../models/presupuesto.modelo.php";

class ControladorPresupuesto
{
    public $id, $descrip, $id_cliente, $estado, $presupuesto, $anio, $cliente, $fecha, $precio_iva, $precio_total, $nota, $ext, $ruta;

    public function listarPresupuesto()
    {
        $data = ModeloPresupuesto::mdlListarPresupuesto($this->anio, $this->estado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarPresupuesto()
    {
        $year = date("Y");
        $uploadDirPres = "/var/www/presupuestos/$year/";
        $uploadDirOrd = "/var/www/ordenes/$year/";
        $uploadDirActs = "/var/www/actas_entrega/$year/";
        $uploadDirOrdCom = "/var/www/orden_compra/$year/";

        // if (!is_dir($uploadDirPres)) mkdir($uploadDirPres, 0777, true);
        // if (!is_dir($uploadDirOrd)) mkdir($uploadDirOrd, 0777, true);

        $pdf_pre = $xls_pre = $pdf_ord = $xls_ord = $doc_ae = $pdf_ae = $pdf_oc_arr = $img_oc_arr = null;

        // Procesar archivos de presupuesto
        if (isset($_FILES['presupuesto_files'])) {
            $files = $_FILES['presupuesto_files'];
            $baseName = 'PPT ' . $this->presupuesto . '   ' . $this->cliente;
            if (is_array($files['name'])) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                    $filePath = $uploadDirPres . $baseName . '.' . $ext;
                    $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
                    $dest = $uploadDirPres . $uniqueFileName;
                    if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                        if ($ext === 'pdf') $pdf_pre = "$year/$uniqueFileName";
                        if ($ext === 'xls' || $ext === 'xlsx') $xls_pre = "$year/$uniqueFileName";
                    }
                }
            } else {
                $ext = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
                $filePath = $uploadDirPres . $baseName . '.' . $ext;
                $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
                $dest = $uploadDirPres . $uniqueFileName;
                if (move_uploaded_file($files['tmp_name'], $dest)) {
                    if ($ext === 'pdf') $pdf_pre = "$year/$uniqueFileName";
                    if ($ext === 'xls' || $ext === 'xlsx') $xls_pre = "$year/$uniqueFileName";
                }
            }
        }

        if (isset($_FILES['actas_files'])) {
            $files = $_FILES['actas_files'];
            $baseName = 'AE ' . $this->presupuesto . '   ' . $this->cliente;
            if (is_array($files['name'])) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                    $filePath = $uploadDirActs . $baseName . '.' . $ext;
                    $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
                    $dest = $uploadDirActs . $uniqueFileName;
                    if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                        if ($ext === 'pdf') $pdf_ae = "$year/$uniqueFileName";
                        if ($ext === 'doc' || $ext === 'docx') $doc_ae = "$year/$uniqueFileName";
                    }
                }
            } else {
                $ext = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
                $filePath = $uploadDirActs . $baseName . '.' . $ext;
                $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
                $dest = $uploadDirActs . $uniqueFileName;
                if (move_uploaded_file($files['tmp_name'], $dest)) {
                    if ($ext === 'pdf') $pdf_ae = "$year/$uniqueFileName";
                    if ($ext === 'doc' || $ext === 'docx') $doc_ae = "$year/$uniqueFileName";
                }
            }
        }

        // Procesar archivos de orden
        if (isset($_FILES['orden_files'])) {
            $files = $_FILES['orden_files'];
            $baseName = 'OT ' . $this->presupuesto . '   ' . $this->cliente;
            if (is_array($files['name'])) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                    $filePath = $uploadDirOrd . $baseName . '.' . $ext;
                    $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
                    $dest = $uploadDirOrd . $uniqueFileName;
                    if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                        if ($ext === 'pdf') $pdf_ord = "$year/$uniqueFileName";
                        if ($ext === 'xls' || $ext === 'xlsx') $xls_ord = "$year/$uniqueFileName";
                    }
                }
            } else {
                $ext = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
                $filePath = $uploadDirOrd . $baseName . '.' . $ext;
                $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
                $dest = $uploadDirOrd . $uniqueFileName;
                if (move_uploaded_file($files['tmp_name'], $dest)) {
                    if ($ext === 'pdf') $pdf_ord = "$year/$uniqueFileName";
                    if ($ext === 'xls' || $ext === 'xlsx') $xls_ord = "$year/$uniqueFileName";
                }
            }
        }

        if (isset($_FILES['orden_compra_files'])) {
            $files = $_FILES['orden_compra_files'];
            $baseName = $this->presupuesto . '   ' . $this->cliente;

            // Arrays para almacenar rutas relativas (ej: 2025/archivo.pdf)
            $pdf_oc_arr = [];
            $img_oc_arr = [];

            // Manejar múltiples archivos
            if (is_array($files['name'])) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                    $filePath = $uploadDirOrdCom . $baseName . '.' . $ext;
                    $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
                    $dest = $uploadDirOrdCom . $uniqueFileName;

                    if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                        if ($ext === 'pdf') {
                            $pdf_oc_arr[] = "$year/$uniqueFileName";
                        } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                            $img_oc_arr[] = "$year/$uniqueFileName";
                        }
                    }
                }
            } else {
                // Un solo archivo
                $ext = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
                $filePath = $uploadDirOrdCom . $baseName . '.' . $ext;
                $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
                $dest = $uploadDirOrdCom . $uniqueFileName;

                if (move_uploaded_file($files['tmp_name'], $dest)) {
                    if ($ext === 'pdf') {
                        $pdf_oc_arr[] = "$year/$uniqueFileName";
                    } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $img_oc_arr[] = "$year/$uniqueFileName";
                    }
                }
            }

            // Convertir arrays a formato PostgreSQL TEXT[]
            $pdf_oc = '{' . implode(',', array_map(function ($v) {
                return '"' . $v . '"';
            }, $pdf_oc_arr)) . '}';

            $img_oc = '{' . implode(',', array_map(function ($v) {
                return '"' . $v . '"';
            }, $img_oc_arr)) . '}';
        }



        $data = ModeloPresupuesto::mdlAgregarPresupuesto(
            $this->descrip,
            $this->id_cliente,
            $this->cliente,
            $this->presupuesto,
            $pdf_pre,
            $pdf_ord,
            $xls_pre,
            $xls_ord,
            $doc_ae,
            $pdf_ae,
            $pdf_oc,
            $img_oc,
            $this->fecha,
            $this->precio_iva,
            $this->precio_total,
            $this->nota
        );
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
            // Generar un descrip único si el archivo ya existe
            if (file_exists($uploadDir . $existingPdf)) {
                unlink($uploadDir . $existingPdf);
            }
            $filePath = $this->generateUniqueFilePath($filePath, $fullNameFinal);
            $savePath = $uploadDir . $year . '/' . $filePath;
            $finalPath = $year . '/' . $filePath;

            if (move_uploaded_file($_FILES['filePresupuesto']['tmp_name'], $savePath)) {
            } else {
                echo json_encode(['status' => 'danger', 'm' => 'Error al subir el archivo. ' . $savePath], JSON_UNESCAPED_UNICODE);
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
        // Devuelve el descrip original si no hubo conflictos
        return $uniqueFilePath;
    }

    public function eliminarPresupuesto()
    {
        $data = ModeloPresupuesto::mdlEliminarPresupuesto($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function cambiarEstadoPresupuesto()
    {
        $data = ModeloPresupuesto::mdlCambiarEstado($this->id, $this->estado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerIdPresupuesto()
    {
        $data = ModeloPresupuesto::mdlobtenerIdPresupuesto($this->descrip);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerFilesOrden()
    {
        $data = ModeloPresupuesto::mdlObtenerFilesOrden($this->id);

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

    public function obtenerFilesPresupuesto()
    {
        $data = ModeloPresupuesto::mdlObtenerFilesPresupuesto($this->id);

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
    public function eliminarFilePresupuesto()
    {
        $data = ModeloPresupuesto::mdlEliminarFilePresupuesto($this->id, $this->ruta, $this->ext);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function eliminarFileOrden()
    {
        $data = ModeloPresupuesto::mdlEliminarFileOrden($this->id, $this->ruta, $this->ext);
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
        $data->descrip = $_POST["des"];
        $data->presupuesto = $_POST["presupuesto"];
        $data->id_cliente = $_POST["id_cliente"];
        $data->cliente = $_POST["cliente"];
        $data->fecha = $_POST["fecha"];
        $data->precio_iva = $_POST["precio_sin_iva"];
        $data->precio_total = $_POST["precio_con_iva"];
        $data->nota = $_POST["nota"];
        $data->agregarPresupuesto();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorPresupuesto();
        $data->id = $_POST["id"];
        $data->descrip = $_POST["des"];
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
        $data->descrip = $_POST["descrip"];
        $data->obtenerIdPresupuesto();
    } else if ($_POST["accion"] == 5) {
        $data = new ControladorPresupuesto();
        $data->id = $_POST["id"];
        $data->estado = $_POST["estado"];
        $data->cambiarEstadoPresupuesto();
    } else if ($_POST["accion"] == 6) {
        $data = new ControladorPresupuesto();
        $data->id = $_POST["id"];
        $data->obtenerFilesOrden();
    } else if ($_POST["accion"] == 7) {
        $data = new ControladorPresupuesto();
        $data->id = $_POST["id"];
        $data->obtenerFilesPresupuesto();
    } else if ($_POST["accion"] == 8) {
        $data = new ControladorPresupuesto();
        $data->id = $_POST["id"];
        $data->ruta = $_POST["ruta"];
        $data->ext = $_POST["ext"];
        $data->eliminarFilePresupuesto();
    } else if ($_POST["accion"] == 9) {
        $data = new ControladorPresupuesto();
        $data->id = $_POST["id"];
        $data->ruta = $_POST["ruta"];
        $data->ext = $_POST["ext"];
        $data->eliminarFileOrden();
    }
}
