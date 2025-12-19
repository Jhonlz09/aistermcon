<?php
require_once "../models/solicitud_mh.modelo.php";

class ControladorSolicitudDespacho
{
    public $id, $num_sol, $id_orden, $id_boleta, $filas, $params, $subtotal, $id_responsable, $id_despachado, $id_autorizado;

    // Listar todas las solicitudes de despacho
    public function listarSolicitudes()
    {
        $data = ModeloSolicitudDespacho::mdlListarSolicitudes();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Consultar detalle de una solicitud específica
    public function consultarSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlConsultarSolicitud($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Consultar detalles de los productos en una solicitud
    public function consultarDetalleSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlConsultarDetalleSolicitud($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Crear nueva solicitud de despacho
    public function crearSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlCrearSolicitud($this->id_orden, $this->id_boleta, $this->id_responsable);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Agregar filas a una solicitud
    public function agregarFilasSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlAgregarFilasSolicitud($this->filas, $this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Actualizar solicitud
    public function actualizarSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlActualizarSolicitud($this->params);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Actualizar detalle de fila
    public function actualizarFilaSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlActualizarFilaSolicitud($this->id, $this->params);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Eliminar fila de solicitud
    public function eliminarFilaSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlEliminarFilaSolicitud($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Eliminar todas las filas de una solicitud
    public function eliminarFilasSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlEliminarFilasSolicitud($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Eliminar solicitud
    public function eliminarSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlEliminarSolicitud($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Cargar productos por categoría
    public function cargarProductosPorCategoria()
    {
        $data = ModeloSolicitudDespacho::mdlCargarProductosPorCategoria();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Autorizar solicitud
    public function autorizarSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlAutorizarSolicitud($this->id, $this->id_autorizado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Confirmar despacho
    public function confirmarDespacho()
    {
        $data = ModeloSolicitudDespacho::mdlConfirmarDespacho($this->id, $this->id_despachado);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Anular solicitud
    public function anularSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlAnularSolicitud($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

// Procesar acciones
if (!isset($_POST["accion"])) {
    $data = new ControladorSolicitudDespacho();
    $data->listarSolicitudes();
} else {
    $data = new ControladorSolicitudDespacho();
    
    if ($_POST["accion"] == 0) {
        // Listar
        $data->listarSolicitudes();
    } else if ($_POST["accion"] == 1) {
        // Consultar solicitud
        $data->id = $_POST["id"];
        $data->consultarSolicitud();
    } else if ($_POST["accion"] == 2) {
        // Consultar detalle
        $data->id = $_POST["id"];
        $data->consultarDetalleSolicitud();
    } else if ($_POST["accion"] == 3) {
        // Crear nueva solicitud
        $data->id_orden = $_POST["id_orden"] ?? null;
        $data->id_boleta = $_POST["id_boleta"] ?? null;
        $data->id_responsable = $_POST["id_responsable"] ?? null;
        $data->crearSolicitud();
    } else if ($_POST["accion"] == 4) {
        // Agregar filas
        $data->id = $_POST["id"];
        $data->filas = $_POST["filas"] ?? 1;
        $data->agregarFilasSolicitud();
    } else if ($_POST["accion"] == 5) {
        // Actualizar solicitud
        $params = [
            'id' => $_POST["id"] ?? null,
            'filas' => $_POST["filas"] ?? null,
            'id_orden' => $_POST["id_orden"] ?? null,
            'id_boleta' => $_POST["id_boleta"] ?? null,
            'id_responsable' => $_POST["id_responsable"] ?? null,
            'isFilas' => $_POST["isFilas"] ?? false,
        ];
        $data->params = $params;
        $data->actualizarSolicitud();
    } else if ($_POST["accion"] == 6) {
        // Eliminar fila
        $data->id = $_POST["id"];
        $data->eliminarFilaSolicitud();
    } else if ($_POST["accion"] == 7) {
        // Eliminar todas las filas
        $data->id = $_POST["id"];
        $data->eliminarFilasSolicitud();
    } else if ($_POST["accion"] == 8) {
        // Eliminar solicitud
        $data->id = $_POST["id"];
        $data->eliminarSolicitud();
    } else if ($_POST["accion"] == 9) {
        // Cargar productos por categoría
        $data->cargarProductosPorCategoria();
    } else if ($_POST["accion"] == 10) {
        // Autorizar solicitud
        $data->id = $_POST["id"];
        $data->id_autorizado = $_POST["id_autorizado"] ?? null;
        $data->autorizarSolicitud();
    } else if ($_POST["accion"] == 11) {
        // Confirmar despacho
        $data->id = $_POST["id"];
        $data->id_despachado = $_POST["id_despachado"] ?? null;
        $data->confirmarDespacho();
    } else if ($_POST["accion"] == 12) {
        // Anular solicitud
        $data->id = $_POST["id"];
        $data->anularSolicitud();
    }
}
?>
