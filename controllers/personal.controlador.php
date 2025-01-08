<?php
require_once "../models/personal.modelo.php";

class ControladorPersonal
{
    public $id, $cedula, $nombre, $apellido, $fecha_ini, $fecha_cor, $sueldo, $ruta;

    public function listarPersonal()
    {
        $data = ModeloPersonal::mdlListarPersonal();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function agregarPersonal()
    {

        if (isset($_FILES['fileCedula']) && $_FILES['fileCedula']['type'] === 'application/pdf') {
            $fechaActual = date('d-m-Y');
            $uploadDir = '/var/www/cedula_personal/';
            $fullNameFinal = 'CI- '. $this->nombre . ' ' . $this->apellido . ' '.$fechaActual.'.pdf';
            $savePath = $uploadDir . $fullNameFinal;

            if (move_uploaded_file($_FILES['fileCedula']['tmp_name'], $savePath)) {
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
        }else{
            $fullNameFinal = '';
        }
        $data = ModeloPersonal::mdlAgregarPersonal($this->cedula, $this->nombre, $this->apellido, $this->fecha_ini, $this->fecha_cor, $this->sueldo, $fullNameFinal);
        // $this->notifyWebSocket($data);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function eliminarPersonal()
    {
        $data = ModeloPersonal::mdlEliminarPersonal($this->id);
        // $this->notifyWebSocket($data);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function editarPersonal()
    {
        
        $personalActual = ModeloPersonal::mdlObtenerPersonalPorId($this->id);

        if (!$personalActual) {
            echo json_encode(['status' => 'danger', 'm' => 'Personal no encontrado']);
            return;
        }
        // Obtener la información del personal actual
        // $personalActual = ModeloPersonal::mdlObtenerPersonalPorId($this->id);
        // Verificar si se ha subido un nuevo archivo PDF
        if (isset($_FILES['fileCedula']) && $_FILES['fileCedula']['type'] === 'application/pdf') {
            $fechaActual = date('d-m-Y');
            $uploadDir = '/var/www/cedula_personal/';
            $fullNameFinal = 'CI- '.$this->nombre . ' ' .$this->apellido.' '.$fechaActual.'.pdf';
            $savePath = $uploadDir . $fullNameFinal;

            // Eliminar el archivo PDF anterior si existe
            if (!empty($personalActual['ruta'])) {
                $oldPdfPath = $uploadDir . $personalActual['ruta'];
                if (file_exists($oldPdfPath)) {
                    unlink($oldPdfPath);
                }
            }
            // Guardar el nuevo archivo PDF
            if (move_uploaded_file($_FILES['fileCedula']['tmp_name'], $savePath)) {
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
        }else{
            $fullNameFinal = $this->ruta;
        }

        // Actualizar la información del personal en la base de datos
        $data = ModeloPersonal::mdlEditarPersonal($this->id, $this->cedula, $this->nombre, $this->apellido, $this->fecha_ini, $this->fecha_cor, $this->sueldo, $fullNameFinal);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

$data = new ControladorPersonal();

if (!isset($_POST["accion"])) {
    $data->listarPersonal();
} else {
    switch ($_POST["accion"]) {
        case 1: // Agregar empleados
        case 2: // Editar empleado
            $data->cedula = $_POST["cedula"];
            $data->nombre = $_POST["nombre"];
            $data->apellido = $_POST["apellido"];
            $data->fecha_ini = $_POST["fecha_ini"];
            $data->fecha_cor = $_POST["fecha_cor"];
            $data->sueldo = $_POST["sueldo"];
            if ($_POST["accion"] == 2) {
                $data->id = $_POST["id"];
                $data->ruta = $_POST["ruta"];
                $data->editarPersonal();
            } else {
                $data->agregarPersonal();
            }
            break;
        case 3: // Eliminar empleado
            $data->id = $_POST["id"];
            $data->eliminarPersonal();
            break;
        default:
            // Manejo de acciones no válidas
            echo json_encode(["error" => "Acción no válida"]);
    }
}
