<?php
require_once "../models/inventario.modelo.php";
class ControladorInventario
{
    public $id;
    public $codigo, $oldCod, $id_orden, $anio;
    public $nombre, $unidad, $categoria, $percha, $stock, $stock_ini, $stock_min, $stock_mal, $img;

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
                $uploadDir = '../../products/';
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
        
        if ($data['status'] == 'success') {
            // Crear la primera versión del stock inicial
            $anio = date('Y');
            $motivo = isset($_POST['motivo_stock']) ? $_POST['motivo_stock'] : 'Stock inicial ingresado';
            ModeloInventario::mdlCrearVersionStockInicial($data['id_producto'], $anio, $this->stock, $motivo);
            
            if (isset($_POST['medidas'])) {
                $medidas = json_decode($_POST['medidas'], true);
                if (is_array($medidas) && !empty($medidas)) {
                    ModeloInventario::mdlGuardarMedidasProducto($data['id_producto'], $medidas);
                }
            }
        }

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

        $uploadDir = '../../products/';

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

        if (isset($_POST['medidas'])) {
            $medidas = json_decode($_POST['medidas'], true);
            if (is_array($medidas)) {
                ModeloInventario::mdlGuardarMedidasProducto($this->id, $medidas, true);
            }
        }
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

    public function consultarHistorialProducto()
    {
        $data = ModeloInventario::mdlConsultarHistorialProducto($this->id, $this->anio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function consultarStockIniAnio()
    {
        $data = ModeloInventario::mdlConsultarStockIniAnio($this->id, $this->anio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerMedidasProducto()
    {
        $data = ModeloInventario::mdlObtenerMedidasProducto($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerVersionesStockInicial()
    {
        $data = ModeloInventario::mdlObtenerVersionesStockInicial($this->id, $this->anio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function crearVersionStockInicial()
    {
        $data = ModeloInventario::mdlCrearVersionStockInicial($this->id, $this->anio, $this->stock_ini, isset($_POST['motivo']) ? $_POST['motivo'] : 'Ajuste de inventario');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function actualizarMotivoCambio()
    {
        $id_version = isset($_POST['id_version']) ? $_POST['id_version'] : 0;
        $motivo = isset($_POST['motivo']) ? $_POST['motivo'] : '';
        $data = ModeloInventario::mdlActualizarMotivoCambio($id_version, $motivo);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarVersionStockInicial()
    {
        $id_version = isset($_POST['id_version']) ? $_POST['id_version'] : 0;
        $data = ModeloInventario::mdlEliminarVersionStockInicial($id_version);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
$data = new ControladorInventario();
if (isset($_POST["accion"]) && $_POST["accion"] == 0) {
    $data->listarInventario();
} else {
    if ($_POST["accion"] == 1) {
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
        $data->id = $_POST["id"];
        $data->codigo = $_POST["cod"];
        $data->nombre = $_POST["des"];
        $data->stock = $_POST["sto"];
        $data->stock_min = $_POST["st_min"];
        $data->stock_mal = $_POST["st_mal"];
        $data->stock_ini = $_POST["st_ini"];
        $data->categoria = $_POST["cat"];
        $data->unidad = $_POST["uni"];
        $data->percha = $_POST["ubi"];
        $data->img = $_POST["img"];
        $data->oldCod = $_POST["oldCod"];
        $data->editarInventario();
    } else if ($_POST["accion"] == 3) {
        $data->id = $_POST["id"];
        $data->eliminarInventario();
    } else if ($_POST["accion"] == 4) {
        $data->codigo = $_POST["id"];
        $data->buscarCodigo();
    } else if ($_POST["accion"] == 5) {
        $data->id = $_POST["id"];
        $data->buscarId();
    } else if ($_POST["accion"] == 6) {
        $data->listarInventarioStock();
    } else if ($_POST["accion"] == 7) {
        $data->buscarProductos();
    } else if ($_POST["accion"] == 8) {
        $data->alertaStock();
    } else if ($_POST["accion"] == 9) {
        $data->nombre = $_POST["nombre"];
        $data->unidad = $_POST["unidad"];
        $data->stock = $_POST["cantidad"];
        $data->agregarInventarioFab();
    } else if ($_POST["accion"] == 10) {
        $data->id = $_POST["id_e"];
        $data->nombre = $_POST["nombre"];
        $data->unidad = $_POST["unidad"];
        $data->stock = $_POST["cantidad"];
        $data->editarInventarioFab();
    } else if ($_POST["accion"] == 11) {
        $data->id = $_POST["id_producto_fab"];
        $data->listarProductoFab();
    } else if ($_POST["accion"] == 12) {
        $data->id = $_POST["id_producto"];
        $data->anio = $_POST["anio"];
        $data->consultarHistorialProducto();
    } else if ($_POST["accion"] == 13) {
        $data->id = $_POST["id_producto"];
        $data->anio = $_POST["anio"];
        $data->consultarStockIniAnio();
    }  else if ($_POST["accion"] == 21) { // Consultar medidas
        $data->id = $_POST["id_producto"];
        echo json_encode(ModeloInventario::mdlObtenerMedidasProducto($data->id), JSON_UNESCAPED_UNICODE);
    } else if ($_POST["accion"] == 22) { // Guardar medida
        $id_producto = $_POST["id_producto"];
        $medidas = json_decode($_POST["medidas"], true);
        echo json_encode(ModeloInventario::mdlGuardarMedidasProducto($id_producto, $medidas), JSON_UNESCAPED_UNICODE);
    } else if ($_POST["accion"] == 23) { // Editar medida
        $id_medida = $_POST["id_medida"];
        $alto = $_POST["alto"];
        $ancho = $_POST["ancho"];
        $cantidad_und = $_POST["cantidad_und"];
        echo json_encode(ModeloInventario::mdlEditarMedidaProducto($id_medida, $alto, $ancho, $cantidad_und), JSON_UNESCAPED_UNICODE);
    } else if ($_POST["accion"] == 24) { // Eliminar medida
        $id_medida = $_POST["id_medida"];
        echo json_encode(ModeloInventario::mdlEliminarMedidaProducto($id_medida), JSON_UNESCAPED_UNICODE);
    } else if ($_POST["accion"] == 31) { // Obtener versiones de stock inicial
        $data->id = $_POST["id_producto"];
        $data->anio = $_POST["anio"];
        $data->obtenerVersionesStockInicial();
    } else if ($_POST["accion"] == 32) { // Crear nueva versión de stock inicial
        $data->id = $_POST["id_producto"];
        $data->anio = $_POST["anio"];
        $data->stock_ini = $_POST["stock_ini"];
        $data->crearVersionStockInicial();
    } else if ($_POST["accion"] == 33) { // Actualizar motivo de cambio
        $data->actualizarMotivoCambio();
    } else if ($_POST["accion"] == 34) { // Eliminar versión de stock inicial
        $data->eliminarVersionStockInicial();
    } else if ($_POST["accion"] == 100) { // INSTALAR TRIGGERS
        echo json_encode(ModeloInventario::mdlInstalarTriggerHistorico());
    }
}
