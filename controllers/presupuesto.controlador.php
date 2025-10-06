<?php
require_once "../models/presupuesto.modelo.php";

class ControladorPresupuesto
{
    public $id, $descrip, $id_cliente, $estado, $presupuesto, $anio, $cliente, $fecha, $precio_iva, $precio_total, $nota, $ext, $ruta, $carpeta, $tipo;

    public function listarPresupuesto()
    {
        $data = ModeloPresupuesto::mdlListarPresupuesto($this->anio, $this->estado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarPresupuesto()
    {
        $year = date("Y", strtotime($this->fecha));
        $uploadDirPres   = "/var/www/presupuestos/$year/";
        $uploadDirOrd    = "/var/www/ordenes/$year/";
        $uploadDirActs   = "/var/www/actas_entrega/$year/";
        $uploadDirOrdCom = "/var/www/orden_compra/$year/";

        // Variables de rutas relativas
        $pdf_pre = $xls_pre = $pdf_ord = $xls_ord = $doc_ae = $pdf_ae = null;
        $pdf_oc_arr = [];
        $img_oc_arr = [];

        /* ------------------- 📄 PRESUPUESTO ------------------- */
        if (isset($_FILES['presupuesto_files'])) {
            $files = $_FILES['presupuesto_files'];
            $baseName = 'PPTO ' . $this->presupuesto . '   ' . $this->cliente;
            $this->procesarArchivos($files, $uploadDirPres, $baseName, $year, $pdf_pre, $xls_pre);
        }

        /* ------------------- 📄 ACTAS ENTREGA ------------------- */
        if (isset($_FILES['actas_files'])) {
            $files = $_FILES['actas_files'];
            $baseName = 'AE ' . $this->presupuesto . '   ' . $this->cliente;
            $this->procesarArchivos($files, $uploadDirActs, $baseName, $year, $pdf_ae, $doc_ae);
        }

        /* ------------------- 📄 ORDEN DE TRABAJO ------------------- */
        if (isset($_FILES['orden_files'])) {
            $files = $_FILES['orden_files'];
            $baseName = 'OT ' . $this->presupuesto . '   ' . $this->cliente;
            $this->procesarArchivos($files, $uploadDirOrd, $baseName, $year, $pdf_ord, $xls_ord);
        }

        /* ------------------- 📄 ORDEN DE COMPRA (ARRAYS) ------------------- */
        if (isset($_FILES['orden_compra_files'])) {
            $files = $_FILES['orden_compra_files'];
            $baseName = $this->presupuesto . '   ' . $this->cliente;
            $this->procesarOrdenCompraArchivos($files, $uploadDirOrdCom, $baseName, $year, $pdf_oc_arr, $img_oc_arr);
        }

        // Convertir arrays a formato PostgreSQL TEXT[]
        $pdf_oc = !empty($pdf_oc_arr) ? $this->arrayToPgArray($pdf_oc_arr) : null;
        $img_oc = !empty($img_oc_arr) ? $this->arrayToPgArray($img_oc_arr) : null;

        /* ------------------- 📝 INSERTAR EN BD ------------------- */
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

    // public function editarPresupuesto()
    // {
    //     $existingPdf = ModeloPresupuesto::mdlIsPdfPresupuesto($this->id);

    //     if (isset($_FILES['filePresupuesto']) && $_FILES['filePresupuesto']['type'] === 'application/pdf') {
    //         // $year = date("Y", strtotime($existingPdf['fecha_creacion'])); // Mantener el año de la creación
    //         list($year, $oldFileName) = explode('/', $existingPdf);
    //         $uploadDir = '/var/www/uploads/';
    //         $fileName = basename($_FILES['filePresupuesto']['name']);
    //         $filePath = $uploadDir . $year . '/' . $fileName;
    //         $fullNameFinal = $this->presupuesto . '   ' . $this->cliente;
    //         // Generar un descrip único si el archivo ya existe
    //         if (file_exists($uploadDir . $existingPdf)) {
    //             unlink($uploadDir . $existingPdf);
    //         }
    //         $filePath = $this->generateUniqueFilePath($filePath, $fullNameFinal);
    //         $savePath = $uploadDir . $year . '/' . $filePath;
    //         $finalPath = $year . '/' . $filePath;

    //         if (move_uploaded_file($_FILES['filePresupuesto']['tmp_name'], $savePath)) {
    //         } else {
    //             echo json_encode(['status' => 'danger', 'm' => 'Error al subir el archivo. ' . $savePath], JSON_UNESCAPED_UNICODE);
    //             return;
    //         }
    //     } else {
    //         // Mantener la ruta del archivo actual si no se ha subido uno nuevo
    //         $finalPath = $existingPdf;
    //     }

    //     $data = ModeloPresupuesto::mdlEditarPresupuesto($this->id, $this->descrip, $this->id_cliente, $this->presupuesto, $finalPath);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }

    // public function editarPresupuesto()
    // {
    //     $year = date("Y", strtotime($this->fecha));
    //     $uploadDirPres = "/var/www/presupuestos/$year/";
    //     $uploadDirOrd = "/var/www/ordenes/$year/";
    //     $uploadDirActs = "/var/www/actas_entrega/$year/";
    //     $uploadDirOrdCom = "/var/www/orden_compra/$year/";

    //     $id = $_POST['id'];

    //     $pdf_pre = $xls_pre = $pdf_ord = $xls_ord = $doc_ae = $pdf_ae = null;
    //     $pdf_oc_arr = [];
    //     $img_oc_arr = [];

    //     if (isset($_FILES['presupuesto_files'])) {
    //         $files = $_FILES['presupuesto_files'];
    //         $baseName = 'PPT ' . $this->presupuesto . '   ' . $this->cliente;
    //         $pdf_pre = self::procesarArchivosSimples($files, $uploadDirPres, $baseName, $year, ['pdf', 'xls', 'xlsx']);
    //     }

    //     if (isset($_FILES['orden_files'])) {
    //         $files = $_FILES['orden_files'];
    //         $baseName = 'OT ' . $this->presupuesto . '   ' . $this->cliente;
    //         $pdf_ord = self::procesarArchivosSimples($files, $uploadDirOrd, $baseName, $year, ['pdf', 'xls', 'xlsx']);
    //     }

    //     if (isset($_FILES['actas_files'])) {
    //         $files = $_FILES['actas_files'];
    //         $baseName = 'AE ' . $this->presupuesto . '   ' . $this->cliente;
    //         $pdf_ae = self::procesarArchivosSimples($files, $uploadDirActs, $baseName, $year, ['pdf', 'doc', 'docx']);
    //     }

    //     // 📌 2️⃣ Procesar archivos de orden_compra para ARRAY_APPEND
    //     if (isset($_FILES['orden_compra_files'])) {
    //         $files = $_FILES['orden_compra_files'];
    //         $baseName = $this->presupuesto . '   ' . $this->cliente;

    //         if (is_array($files['name'])) {
    //             for ($i = 0; $i < count($files['name']); $i++) {
    //                 $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
    //                 $filePath = $uploadDirOrdCom . $baseName . '.' . $ext;
    //                 $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
    //                 $dest = $uploadDirOrdCom . $uniqueFileName;
    //                 if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
    //                     if ($ext === 'pdf') $pdf_oc_arr[] = "$year/$uniqueFileName";
    //                     elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) $img_oc_arr[] = "$year/$uniqueFileName";
    //                 }
    //             }
    //         } else {
    //             $ext = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
    //             $filePath = $uploadDirOrdCom . $baseName . '.' . $ext;
    //             $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
    //             $dest = $uploadDirOrdCom . $uniqueFileName;
    //             if (move_uploaded_file($files['tmp_name'], $dest)) {
    //                 if ($ext === 'pdf') $pdf_oc_arr[] = "$year/$uniqueFileName";
    //                 elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) $img_oc_arr[] = "$year/$uniqueFileName";
    //             }
    //         }
    //     }

    //     // 📌 3️⃣ Llamar al modelo para actualizar
    //     $data = ModeloPresupuesto::mdlEditarPresupuesto(
    //         $id,
    //         $this->descrip,
    //         $this->id_cliente,
    //         $this->cliente,
    //         $this->presupuesto,
    //         $pdf_pre,
    //         $pdf_ord,
    //         $xls_pre,
    //         $xls_ord,
    //         $doc_ae,
    //         $pdf_ae,
    //         $pdf_oc_arr,
    //         $img_oc_arr,
    //         $this->fecha,
    //         $this->precio_iva,
    //         $this->precio_total,
    //         $this->nota
    //     );

    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }

    public function editarPresupuesto()
    {
        $year = date("Y", strtotime($this->fecha));
        $uploadDirPres = "/var/www/presupuestos/$year/";
        $uploadDirOrd = "/var/www/ordenes/$year/";
        $uploadDirActs = "/var/www/actas_entrega/$year/";
        $uploadDirOrdCom = "/var/www/orden_compra/$year/";

        // Rutas nuevas (solo se llenan si llegan archivos nuevos)
        $pdf_pre = $xls_pre = $pdf_ord = $xls_ord = $doc_ae = $pdf_ae = null;
        $pdf_oc_arr = [];
        $img_oc_arr = [];

        /* ------------------- 📄 PRESUPUESTO ------------------- */
        if (isset($_FILES['presupuesto_files'])) {
            $files = $_FILES['presupuesto_files'];
            $baseName = 'PPTO ' . $this->presupuesto . '   ' . $this->cliente;
            $this->procesarArchivos($files, $uploadDirPres, $baseName, $year, $pdf_pre, $xls_pre);
        }

        /* ------------------- 📄 ACTAS ENTREGA ------------------- */
        if (isset($_FILES['actas_files'])) {
            $files = $_FILES['actas_files'];
            $baseName = 'AE ' . $this->presupuesto . '   ' . $this->cliente;
            $this->procesarArchivos($files, $uploadDirActs, $baseName, $year, $pdf_ae, $doc_ae);
        }

        /* ------------------- 📄 ORDEN DE TRABAJO ------------------- */
        if (isset($_FILES['orden_files'])) {
            $files = $_FILES['orden_files'];
            $baseName = 'OT ' . $this->presupuesto . '   ' . $this->cliente;
            $this->procesarArchivos($files, $uploadDirOrd, $baseName, $year, $pdf_ord, $xls_ord);
        }

        /* ------------------- 📄 ORDEN DE COMPRA (ARRAYS) ------------------- */
        if (isset($_FILES['orden_compra_files'])) {
            $files = $_FILES['orden_compra_files'];
            $baseName = $this->presupuesto . '   ' . $this->cliente;
            $this->procesarOrdenCompraArchivos($files, $uploadDirOrdCom, $baseName, $year, $pdf_oc_arr, $img_oc_arr);
        }

        // Convertir arrays a formato PostgreSQL TEXT[]
        $pdf_oc = !empty($pdf_oc_arr) ? $this->arrayToPgArray($pdf_oc_arr) : null;
        $img_oc = !empty($img_oc_arr) ? $this->arrayToPgArray($img_oc_arr) : null;

        // Actualizar en BD SOLO los campos que tengan cambios
        $data = ModeloPresupuesto::mdlEditarPresupuesto(
            $this->id,
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

    private function procesarArchivos($files, $uploadDir, $baseName, $year, &$pdfVar, &$xlsOrDocVar)
    {
        if (is_array($files['name'])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                $filePath = $uploadDir . $baseName . '.' . $ext;
                $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
                $dest = $uploadDir . $uniqueFileName;
                if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                    if ($ext === 'pdf') $pdfVar = "$year/$uniqueFileName";
                    else $xlsOrDocVar = "$year/$uniqueFileName";
                }
            }
        } else {
            $ext = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
            $filePath = $uploadDir . $baseName . '.' . $ext;
            $uniqueFileName = $this->generateUniqueFilePath($filePath, $baseName);
            $dest = $uploadDir . $uniqueFileName;
            if (move_uploaded_file($files['tmp_name'], $dest)) {
                if ($ext === 'pdf') $pdfVar = "$year/$uniqueFileName";
                else $xlsOrDocVar = "$year/$uniqueFileName";
            }
        }
    }

    private function procesarOrdenCompraArchivos($files, $uploadDir, $baseName, $year, &$pdfArray, &$imgArray)
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

    public function obtenerFiles()
    {
        $data = ModeloPresupuesto::mdlObtenerTodosLosArchivos($this->id);

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

    public function eliminarFiles()
    {
        $data = ModeloPresupuesto::mdlEliminarArchivo($this->id, $this->ruta, $this->ext, $this->carpeta, $this->tipo);
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
        $data->fecha = $_POST["fecha"];
        $data->precio_iva = $_POST["precio_sin_iva"];
        $data->precio_total = $_POST["precio_con_iva"];
        $data->nota = $_POST["nota"];
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
        $data->obtenerFiles();
    } //else if ($_POST["accion"] == 7) {
    //     $data = new ControladorPresupuesto();
    //     $data->id = $_POST["id"];
    //     $data->obtenerFilesPresupuesto();
    // } 
    else if ($_POST["accion"] == 8) {
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
        $data->carpeta = $_POST["carpeta"];
        $data->tipo = $_POST["tipo"];
        $data->eliminarFiles();
    } //else if ($_POST["accion"] == 10) {
    //     $data = new ControladorPresupuesto();
    //     $data->id = $_POST["id"];
    //     $data->obtenerFilesActaEntrega();
    // } else if ($_POST["accion"] == 11) {
    //     $data = new ControladorPresupuesto();
    //     $data->id = $_POST["id"];
    //     $data->obtenerFilesOrdenCompra();
    // }
}
