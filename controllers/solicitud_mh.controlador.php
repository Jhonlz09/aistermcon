<?php
require_once "../models/solicitud_mh.modelo.php";

class ControladorSolicitudDespacho
{
    public $id, $num_sol, $id_orden, $id_boleta, $filas, $params, $subtotal, $id_responsable, $id_despachado, $id_autorizado, $anio;

    // Listar todas las solicitudes de despacho
    public function listarSolicitudes()
    {
        $data = ModeloSolicitudDespacho::mdlListarSolicitudes($this->anio);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Consultar una solicitud específica
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

    // Cambiar estado solicitud (Aprobar/Desaprobar)
    public function aprobarSolicitud()
    {
        session_start();
        $id_usuario = $_SESSION['s_usuario']->id;
        $estado = $_POST["estado"];
        $data = ModeloSolicitudDespacho::mdlAprobarSolicitud($this->id, $id_usuario, $estado);
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

    // Guardar solicitud completa (Cabecera y Detalles)
    public function guardarSolicitudCompleta()
    {
        $data = ModeloSolicitudDespacho::mdlGuardarSolicitudCompleta($this->params);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    // Actualizar solicitud completa (Cabecera y Detalles)
    public function actualizarSolicitudCompleta()
    {
        $data = ModeloSolicitudDespacho::mdlActualizarSolicitudCompleta($this->params);
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

    // Anular solicitud
    public function anularSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlAnularSolicitud($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Desanular / Reanudar solicitud
    public function reanudarSolicitud()
    {
        $data = ModeloSolicitudDespacho::mdlReanudarSolicitud($this->id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    // Consultar producto por código (para autocompletado)
    public function listarSolicitudesAprobadasSinBoleta()
    {
        $respuesta = ModeloSolicitudDespacho::mdlListarSolicitudesAprobadasSinBoleta();
        echo json_encode($respuesta);
    }

    public function consultarDetalleSolicitudPorTipo()
    {
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
        $respuesta = ModeloSolicitudDespacho::mdlConsultarDetalleSolicitudPorTipo($this->id, $tipo);
        echo json_encode($respuesta);
    }

    public function consultarProductoPorCodigo()
    {
        $data = ModeloSolicitudDespacho::mdlConsultarProductoPorCodigo($this->id); // Usamos 'id' para pasar el código temporalmente o crear nueva propiedad
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

// Procesar acciones
if (!isset($_POST["accion"])) {
    $data = new ControladorSolicitudDespacho();
    $data->anio = $_POST["anio"];
    $data->listarSolicitudes();
} else {
    $data = new ControladorSolicitudDespacho();
if ($_POST["accion"] == 1) {
        // Consultar solicitud
        $data->id = $_POST["id"];
        $data->consultarSolicitud();
    } else if ($_POST["accion"] == 2) {
        // Consultar detalle
        $data->id = $_POST["id"];
        $data->consultarDetalleSolicitud();
    } else if ($_POST["accion"] == 3) {
        // Consultar producto por código
        $data->id = $_POST["codigo"]; // Reutilizamos propiedad id o pasamos directo
        $data->consultarProductoPorCodigo();
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
    } else if ($_POST["accion"] == 10) {
        // Aprobar solicitud
        $data->id = $_POST["id"];
        $data->aprobarSolicitud();
    } else if ($_POST["accion"] == 12) {
        // Anular solicitud
        $data->id = $_POST["id"];
        $data->anularSolicitud();
    } else if ($_POST["accion"] == 13) {
        // Reanudar solicitud
        $data->id = $_POST["id"];
        $data->reanudarSolicitud();
    } else if ($_POST["accion"] == 14) {
        // Guardar solicitud completa
        $params = [
            'id_orden' => $_POST["id_orden"] ?? null,
            'fecha' => $_POST["fecha"] ?? null,
            'id_responsable' => $_POST["id_responsable"] ?? null,
            'notas' => $_POST["notas"] ?? null,
            'filas' => $_POST["filas"] ?? '[]'
        ];
        $data->params = $params;
        $data->guardarSolicitudCompleta();
    } else if ($_POST["accion"] == 15) {
        // Actualizar solicitud completa
        $params = [
            'id_solicitud' => $_POST["id_solicitud"] ?? null,
            'id_orden' => $_POST["id_orden"] ?? null,
            'fecha' => $_POST["fecha"] ?? null,
            'id_responsable' => $_POST["id_responsable"] ?? null,
            'notas' => $_POST["notas"] ?? null,
            'filas' => $_POST["filas"] ?? '[]'
        ];
        $data->params = $params;
        $data->actualizarSolicitudCompleta();
    } else if ($_POST["accion"] == 16) {
        $data->listarSolicitudesAprobadasSinBoleta();
    } else if ($_POST["accion"] == 17) {
        // Consultar productos de una solicitud por tipo (MATERIAL / HERRAMIENTA)
        $data->id = $_POST["id"];
        $data->consultarDetalleSolicitudPorTipo();
    } else if ($_POST["accion"] == 18) {
        // Guardar y Aprobar de una sola vez
        $params = [
            'id_solicitud' => $_POST["id_solicitud"] ?? null,
            'id_orden' => $_POST["id_orden"] ?? null,
            'fecha' => $_POST["fecha"] ?? null,
            'id_responsable' => $_POST["id_responsable"] ?? null,
            'notas' => $_POST["notas"] ?? null,
            'id_usuario_autorizado' => $_SESSION["id_personal"] ?? null,
            'filas' => $_POST["filas"] ?? '[]'
        ];
        echo json_encode(ModeloSolicitudDespacho::mdlAprobarSolicitudCompleta($params));
    }
}
?>