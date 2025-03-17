<?php

require_once "../models/registro.modelo.php";

class ajaxRegistro
{
    public function registrarCompra($datos, $nro_factura, $proveedor, $fecha)
    {

        $data = ModeloRegistro::mdlRegistrarCompra($datos, $nro_factura, $proveedor, $fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function registrarSalida($datos, $orden, $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo, $img)
    {
        if ($img === null) {
            $img = []; // Define un array vacío si no se enviaron imágenes
        }
        $data = ModeloRegistro::mdlRegistrarSalida($datos, $orden, $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo, $img);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
   // public function registrarSalida($datos, $orden, $cliente, $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo, $imagenes)
    // {
    //     // Guardar la salida en la base de datos
    //     $data = ModeloRegistro::mdlRegistrarSalida($datos, $orden, $cliente, $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo);

    //     if ($data["status"] === "success") {
    //         $idSalida = $data["id_salida"]; // Supongamos que esta función retorna el ID de la salida creada

    //         // Directorio de almacenamiento para imágenes
    //         $uploadDir = __DIR__ . "/uploads/salidas/";
    //         if (!is_dir($uploadDir)) {
    //             mkdir($uploadDir, 0777, true);
    //         }

    //         // Guardar cada imagen
    //         if (!empty($imagenes["name"][0])) {
    //             foreach ($imagenes["name"] as $index => $name) {
    //                 $tmpName = $imagenes["tmp_name"][$index];
    //                 $uniqueName = uniqid() . "_" . basename($name);
    //                 $targetFile = $uploadDir . $uniqueName;

    //                 if (move_uploaded_file($tmpName, $targetFile)) {
    //                     // Guardar la relación en la base de datos
    //                     ModeloRegistro::mdlGuardarImagenSalida($idSalida, $uniqueName);
    //                 } else {
    //                     echo json_encode([
    //                         "status" => "error",
    //                         "m" => "Error al subir la imagen $name"
    //                     ]);
    //                     return;
    //                 }
    //             }
    //         }
    //     }

    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }

    public function registrarEntrada($datos, $orden, $fecha, $fecha_entrada, $motivo, $conductor, $responsable, $despachado)
    {

        $data = ModeloRegistro::mdlRegistrarEntrada($datos, $orden, $fecha, $fecha_entrada, $motivo, $conductor, $responsable, $despachado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarRegistroSalida($id_boleta, $orden, $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo, $imagenes)
    {

        if ($imagenes === null) {
            $imagenes = []; // Define un array vacío si no se enviaron imágenes
        }

        $data = ModeloRegistro::mdlEditarRegistroSalida($id_boleta, $orden, $nro_guia, $fecha, $conductor, $despachado, $responsable, $motivo, $imagenes);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarRegistroCompra($id_boleta, $nro_factura, $proveedor, $fecha)
    {
        $data = ModeloRegistro::mdlEditarRegistroCompra($id_boleta, $nro_factura, $proveedor, $fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function registrarRetorno($datos, $boleta, $fecha_retorno, $nro_guia)
    {

        $data = ModeloRegistro::mdlRegistrarRetorno($datos, $boleta, $fecha_retorno, $nro_guia);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function registrarPlantilla($datos, $nombre)
    {
        $data = ModeloRegistro::mdlRegistrarPlantilla($datos, $nombre);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function registrarProductosFab($datos, $id_producto_fab)
    {
        $data = ModeloRegistro::mdlRegistrarProductosFab($datos, $id_producto_fab);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function registrarSolicitudCotizacion($datos, $proveedor, $comprador, $fecha)
    {
        $data = ModeloRegistro::mdlSolicitudCotizacion($datos, $proveedor, $comprador, $fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function registrarOrdenCompra($datos, $proveedor, $comprador, $fecha, $subtotal, $iva, $impuesto, $total, $desc)
    {
        $data = ModeloRegistro::mdlOrdenCompra($datos, $proveedor, $comprador, $fecha, $subtotal, $iva, $impuesto, $total, $desc);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function registrarFabricacion($datos, $orden, $nro_guia, $conductor, $despachado, $responsable, $fecha, $motivo, $img)
    {
        $data = ModeloRegistro::mdlRegistrarFabricacion($datos, $orden, $nro_guia, $conductor, $despachado, $responsable, $fecha, $motivo, $img);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function actualizarDatosFabricacion($datos, $id_boleta, $orden, $nro_guia, $conductor, $despachado, $responsable, $fecha, $motivo, $img)
    {
        $data = ModeloRegistro::mdlActualizarDatosFabricacion($datos, $id_boleta, $orden, $nro_guia, $conductor, $despachado, $responsable, $fecha, $motivo, $img);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    // public function editarFabricacion($datos, $orden, $nro_guia, $conductor, $despachado, $responsable, $fecha, $motivo, $img)
    // {
    //     $data = ModeloRegistro::mdlActualizarDatosFabricacion($datos, $orden, $nro_guia, $conductor, $despachado, $responsable, $fecha, $motivo, $img);
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // }
}

if (isset($_POST["accion"])) {
    $accion = $_POST["accion"];
    $registro = new ajaxRegistro();
    if ($accion == 1) {
        $registro->registrarCompra($_POST["arr"], $_POST["nro_factura"], $_POST["proveedor"], $_POST["fecha"]);
    } else if ($accion == 2) {
        $imagenes = isset($_FILES["imagenes"]) ? $_FILES["imagenes"] : null;
        $registro->registrarSalida($_POST["arr"], $_POST["orden"], $_POST["nro_guia"], $_POST["fecha"], $_POST["conductor"], $_POST["despachado"], $_POST["responsable"], $_POST["motivo"], $imagenes);
    } else if ($accion == 3) {
        $registro->registrarRetorno($_POST["arr"], $_POST["boleta"], $_POST["fecha_retorno"], $_POST["nro_guia"]);
    } else if ($accion == 4) {
        $imagenes = isset($_FILES["imagenes"]) ? $_FILES["imagenes"] : null;
        $registro->editarRegistroSalida($_POST["id_boleta"], $_POST["orden"], $_POST["nro_guia"], $_POST["fecha"], $_POST["conductor"], $_POST["despachado"], $_POST["responsable"], $_POST["motivo"],  $imagenes);
    } else if ($accion == 5) {
        $registro->editarRegistroCompra($_POST["id_factura"], $_POST["nro_factura"], $_POST["proveedor"], $_POST["fecha"]);
    } else if ($accion == 6) {
        $registro->registrarPlantilla($_POST["arr"], $_POST["nombre_pla"]);
    } else if ($accion == 7) {
        $registro->registrarProductosFab($_POST["arr"], $_POST["id_producto_fab"]);
    } else if ($accion == 8) {
        $registro->registrarEntrada($_POST["arr"], $_POST["orden"], $_POST["fecha"], $_POST["fecha_retorno"], $_POST["motivo"], $_POST["conductor"], $_POST["responsable"], $_POST["despachado"]);
    } else if ($accion == 9) {
        $registro->registrarSolicitudCotizacion($_POST["arr"], $_POST["proveedor"], $_POST["comprador"], $_POST["fecha"]);
    } else if ($accion == 10) {
        $registro->registrarOrdenCompra($_POST["arr"], $_POST["proveedor"], $_POST["comprador"], $_POST["fecha"], $_POST["subtotal"], $_POST["iva"], $_POST["impuesto"], $_POST["total"], $_POST["descuento"]);
    }else if ($accion == 11) {
        $imagenes = isset($_FILES["imagenes"]) ? $_FILES["imagenes"] : [];
        $registro->registrarFabricacion($_POST["datos"], $_POST["orden"], $_POST["nro_guia"], $_POST["conductor"], $_POST["despachado"], $_POST["responsable"], $_POST["fecha"], $_POST["motivo"],$imagenes);
    }else if ($accion == 12) {
        $imagenes = isset($_FILES["imagenes"]) ? $_FILES["imagenes"] : [];
        $registro->actualizarDatosFabricacion($_POST["datos"], $_POST["id_boleta"], $_POST["orden"], $_POST["nro_guia"], $_POST["conductor"], $_POST["despachado"], $_POST["responsable"], $_POST["fecha"], $_POST["motivo"],$imagenes);
    }
}
