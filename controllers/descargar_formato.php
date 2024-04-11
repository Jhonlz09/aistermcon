<?php
if(isset($_GET['archivo'])) {
    $archivo = $_GET['archivo'];
    $ruta_archivo = '../utils/docs/' . $archivo;

    if(file_exists($ruta_archivo)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $archivo . '"');
        header('Content-Length: ' . filesize($ruta_archivo));
        readfile($ruta_archivo);
        exit;
    } else {
        echo "El archivo no existe.";
    }
} else {
    echo "El archivo no se ha especificado.";
}
?>
