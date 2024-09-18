<?php
require_once "../models/inventario.modelo.php";
class ControladorInventario
{
    public $id;
    public $codigo, $oldCod, $id_orden;
    public $nombre, $unidad, $categoria, $percha, $stock, $stock_min, $stock_mal, $img;

    static public function listarInventario()
    {
        $data = ModeloInventario::mdlListarInventario();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listarProductoFab()
    {
        $data = ModeloInventario::mdlListarProductoFab($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    static public function listarInventarioStock()
    {
        $data = ModeloInventario::mdlListarInventarioStock();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    static public function alertaStock()
    {
        $data = ModeloInventario::mdlAlertaStock();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarInventario()
    {
        $img = null; // Inicializamos la variable img

        $existeCodigo = ModeloInventario::mdlIsCodigoExits($this->codigo);

        if ($existeCodigo) {
            // Si el código ya existe, mostrar un mensaje de error
            echo json_encode(['status' => 'danger', 'm' => 'El código del producto ya existe'], JSON_UNESCAPED_UNICODE);
            return;
        }
        if (isset($_FILES['fileImg'])) {
            $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/webp'];
            $fileMimeType = $_FILES['fileImg']['type'];
            // Validar si el tipo MIME está permitido
            if (in_array($fileMimeType, $allowedMimeTypes)) {
                // Obtener la extensión correspondiente
                $extension = pathinfo($_FILES['fileImg']['name'], PATHINFO_EXTENSION);
                // Nombre final del archivo basado en el código
                $fullNameFinal = $this->codigo . '.' . $extension;
                // Ruta de subida
                $uploadDir = '../assets/img/products/';
                $uploadFile = $uploadDir . $fullNameFinal;

                // Mover el archivo subido a la carpeta de destino
                if (move_uploaded_file($_FILES['fileImg']['tmp_name'], $uploadFile)) {
                    // Guardar el nombre del archivo en la variable $img
                    $img = $fullNameFinal;
                } else {
                    echo json_encode(['status' => 'danger', 'm' => 'Error al subir la imagen'], JSON_UNESCAPED_UNICODE);
                    return;
                }
            } else {
                echo json_encode(['status' => 'danger', 'm' => 'Tipo de archivo de imagen no permitido'], JSON_UNESCAPED_UNICODE);
                return;
            }
        }
        $data = ModeloInventario::mdlAgregarInventario($this->codigo, $this->nombre, $this->stock, $this->stock_min, $this->stock_mal, $this->categoria, $this->unidad, $this->percha, $img);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    public function agregarInventarioFab()
    {
        $data = ModeloInventario::mdlAgregarInventarioFab($this->nombre, $this->unidad, $this->stock);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarInventario()
    {
        $img = $this->img; // Mantener la imagen actual por defecto

        // Verificar si el código ya existe en otro producto
        $existeCodigo = ModeloInventario::mdlIsCodigoExitsEnOtroProducto($this->codigo, $this->id);

        if ($existeCodigo) {
            // Si el código ya existe en otro producto, mostrar un mensaje de error
            echo json_encode(['status' => 'danger', 'm' => 'El código del producto ya existe en otro registro'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $uploadDir = '../assets/img/products/';

        // Si se subió una nueva imagen, validarla y guardarla
        if (isset($_FILES['fileImg']) && $_FILES['fileImg']['size'] > 0) {
            $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/webp'];
            $fileMimeType = $_FILES['fileImg']['type'];

            // Validar si el tipo MIME está permitido
            if (in_array($fileMimeType, $allowedMimeTypes)) {
                // Obtener la extensión correspondiente
                $extension = pathinfo($_FILES['fileImg']['name'], PATHINFO_EXTENSION);
                // Nombre final del archivo basado en el nuevo código
                $fullNameFinal = $this->codigo . '.' . $extension;
                $uploadFile = $uploadDir . $fullNameFinal;

                // **Eliminar la imagen anterior si existe**
                if (!empty($this->img)) {
                    $oldImagePath = $uploadDir . $this->img;

                    // Verificar si el archivo anterior existe y eliminarlo
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Mover el archivo subido a la carpeta de destino
                if (move_uploaded_file($_FILES['fileImg']['tmp_name'], $uploadFile)) {
                    // Guardar el nombre del archivo en la variable $img
                    $img = $fullNameFinal;
                } else {
                    echo json_encode(['status' => 'danger', 'm' => 'Error al subir la imagen'], JSON_UNESCAPED_UNICODE);
                    return;
                }
            } else {
                echo json_encode(['status' => 'danger', 'm' => 'Tipo de archivo de imagen no permitido'], JSON_UNESCAPED_UNICODE);
                return;
            }
        } else {
            // **Si no se subió una nueva imagen, pero se cambió el código, renombrar la imagen actual**
            if (!empty($this->img) && $this->oldCod !== $this->codigo) {
                // Obtener la extensión de la imagen actual
                $extension = pathinfo($this->img, PATHINFO_EXTENSION);
                // Nuevo nombre de la imagen basado en el nuevo código
                $newImageName = $this->codigo . '.' . $extension;
                $oldImagePath = $uploadDir . $this->img;
                $newImagePath = $uploadDir . $newImageName;

                // Renombrar la imagen
                if (file_exists($oldImagePath)) {
                    rename($oldImagePath, $newImagePath);
                    // Actualizar la variable $img con el nuevo nombre de la imagen
                    $img = $newImageName;
                }
            }
        }

        // Llamar a la función de modelo para editar el inventario
        $data = ModeloInventario::mdlEditarInventario($this->id, $this->codigo, $this->nombre, $this->stock, $this->stock_min, $this->stock_mal, $this->categoria, $this->unidad, $this->percha, $img);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }



    public function editarInventarioFab()
    {
        $data = ModeloInventario::mdlEditarInventarioFab($this->id, $this->nombre, $this->unidad, $this->stock, $this->id_orden);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarInventario()
    {
        $data = ModeloInventario::mdlEliminarInventario($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarCodigo()
    {
        $data = ModeloInventario::mdlBuscarCodigo($this->codigo);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarId()
    {
        $data = ModeloInventario::mdlBuscarId($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarProductos()
    {
        $data = ModeloInventario::mdlBuscarProductos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (isset($_POST["accion"]) && $_POST["accion"] == 0) {
    $data = new ControladorInventario();
    $data->listarInventario();
} else {
    if ($_POST["accion"] == 1) {
        $data = new ControladorInventario();
        $data->codigo = $_POST["cod"];
        $data->nombre = $_POST["des"];
        $data->stock = $_POST["sto"];
        $data->stock_min = $_POST["st_min"];
        $data->stock_mal = $_POST["st_mal"];
        $data->categoria = $_POST["cat"];
        $data->unidad = $_POST["uni"];
        $data->percha = $_POST["ubi"];
        $data->agregarInventario();
    } else if ($_POST["accion"] == 2) {
        $data = new ControladorInventario();
        $data->id = $_POST["id"];
        $data->codigo = $_POST["cod"];
        $data->nombre = $_POST["des"];
        $data->stock = $_POST["sto"];
        $data->stock_min = $_POST["st_min"];
        $data->stock_mal = $_POST["st_mal"];
        $data->categoria = $_POST["cat"];
        $data->unidad = $_POST["uni"];
        $data->percha = $_POST["ubi"];
        $data->img = $_POST["img"];
        $data->oldCod = $_POST["oldCod"];
        $data->editarInventario();
    } else if ($_POST["accion"] == 3) {
        $data = new ControladorInventario();
        $data->id = $_POST["id"];
        $data->eliminarInventario();
    } else if ($_POST["accion"] == 4) {
        $data = new ControladorInventario();
        $data->codigo = $_POST["id"];
        $data->buscarCodigo();
    } else if ($_POST["accion"] == 5) {
        $data = new ControladorInventario();
        $data->id = $_POST["id"];
        $data->buscarId();
    } else if ($_POST["accion"] == 6) {
        $data = new ControladorInventario();
        $data->listarInventarioStock();
    } else if ($_POST["accion"] == 7) {
        $data = new ControladorInventario();
        $data->buscarProductos();
    } else if ($_POST["accion"] == 8) {
        $data = new ControladorInventario();
        $data->alertaStock();
    } else if ($_POST["accion"] == 9) {
        $data = new ControladorInventario();
        $data->nombre = $_POST["nombre"];
        $data->unidad = $_POST["unidad"];
        $data->stock = $_POST["cantidad"];
        $data->agregarInventarioFab();
    } else if ($_POST["accion"] == 10) {
        $data = new ControladorInventario();
        $data->id = $_POST["id_e"];
        $data->nombre = $_POST["nombre"];
        $data->unidad = $_POST["unidad"];
        $data->stock = $_POST["cantidad"];
        $data->editarInventarioFab();
    } else if ($_POST["accion"] == 11) {
        $data = new ControladorInventario();
        $data->id = $_POST["id_producto_fab"];
        $data->listarProductoFab();
    }
}
