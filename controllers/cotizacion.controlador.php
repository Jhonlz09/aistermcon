<?php
require_once "../models/cotizacion.modelo.php";

class ControladorCotizacion
{
    public $id, $num_co, $anio, $pdf, $filas, $subtotal, $total, $impuestos, $iva, $desc, $isPrecio;

    public function listarCotizacion()
    {
        $data = ModeloCotizacion::mdlListarCotizacion($this->anio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function consultarCotizacion()
    {
        $data = ModeloCotizacion::mdlConsultarCotizacion($this->num_co);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function subirPDF()
    {

        if (isset($_FILES['filePdf']) && $_FILES['filePdf']['type'] === 'application/pdf') {
            $year = date("Y");
            $uploadDir = '/var/www/presupuesto_proveedor/';
            $fileName = basename($_FILES['filePdf']['name']);
            $filePath = $uploadDir . $year . '/' . $fileName;

            // $pathInfo = pathinfo($filePath);
            // $baseName = $pathInfo['filename'];
            // $extension = $pathInfo['extension'];
            // $directory = $pathInfo['dirname'];

            // echo json_encode(['status' => 'error', 'm' => $directory], JSON_UNESCAPED_UNICODE);
            //     return;
            $fullNameFinal = $this->id . '.pdf';
            // Generar un nombre único si el archivo ya existe
            // $filePath = $this->generateUniqueFilePath($filePath, $fullNameFinal);

            $savePath = $uploadDir . $year . '/' . $fullNameFinal;
            $finalPath = $year . '/' . $fullNameFinal;

            if (move_uploaded_file($_FILES['filePdf']['tmp_name'], $savePath)) {
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
        $data = ModeloCotizacion::mdlSubirPDF($this->id, $finalPath);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarFilasCotizacion()
    {
        $data = ModeloCotizacion::mdlAgregarFilasCotizacion($this->filas, $this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarCotizacion()
    {
        $data = ModeloCotizacion::mdlEditarCotizacion($this->num_co);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function actualizarFilasCotizacion()
    {
        $data = ModeloCotizacion::mdlActualizarFilasCotizacion($this->filas, $this->id, $this->subtotal, $this->total, $this->iva, $this->impuestos, $this->isPrecio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function actualizarTotalCotizacion()
    {
        $data = ModeloCotizacion::mdlActualizarTotalesCotizacion(null, $this->id, $this->subtotal, $this->total, $this->iva, $this->impuestos);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarCotizacion()
    {
        $data = ModeloCotizacion::mdlEliminarCotizacion($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarFilasIds()
    {
        $data = ModeloCotizacion::mdlEliminarFilasIds($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarFila()
    {
        $data = ModeloCotizacion::mdlEliminarFila($this->id, $this->num_co);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function datosProveedor()
    {
        $data = ModeloCotizacion::mdlDatosProveedor($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!isset($_POST["accion"])) {
    $data = new ControladorCotizacion();
    $data->anio = $_POST["anio"];
    $data->listarCotizacion();
} else {
    $data = new ControladorCotizacion();
    if ($_POST["accion"] == 0) {
        echo json_encode([], JSON_UNESCAPED_UNICODE);
    } else if ($_POST["accion"] == 1) {
        $data->num_co = $_POST["nro_cotiz"];
        $data->editarCotizacion();
    } else if ($_POST["accion"] == 3) {
        $data->id = $_POST["id"];
        $data->eliminarCotizacion();
    } else if ($_POST["accion"] == 4) {
        $data->id = $_POST["id_prove"];
        $data->datosProveedor();
    } else if ($_POST["accion"] == 5) {
        $data->id = $_POST["id_pdf"];
        $data->subirPDF();
    } else if ($_POST["accion"] == 6) {
        $data->filas = $_POST["filas"];
        $data->id = $_POST["id_cotizacion"];
        $data->subtotal = $_POST["subtotal"];
        $data->total = $_POST["total"];
        $data->iva = $_POST["iva"];
        $data->impuestos = $_POST["impuesto"];
        $data->desc = $_POST["descuento"];
        $data->isPrecio = $_POST["isPrecio"];
        $data->actualizarFilasCotizacion();
    } else if ($_POST["accion"] == 7) {
        $data->id = $_POST["id"];
        $data->eliminarFilasIds();
    } else if ($_POST["accion"] == 8) {
        $data->id = $_POST["id"];
        $data->num_co = $_POST["id_cotizacion"];
        $data->eliminarFila();
    } else if ($_POST["accion"] == 9) {
        $data->id = $_POST["id_cotizacion"];
        $data->subtotal = $_POST["subtotal"];
        $data->total = $_POST["total"];
        $data->iva = $_POST["iva"];
        $data->impuestos = $_POST["impuesto"];
        $data->actualizarTotalCotizacion();
    } else if ($_POST["accion"] == 10) {
        $data->id = $_POST["id_cotizacion"];
        $data->filas = $_POST["filasCantidad"];
        $data->agregarFilasCotizacion();
    }
}
