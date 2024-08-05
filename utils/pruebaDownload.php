<?php
// Obtén el nombre del archivo de la variable GET y asegúrate de que sea seguro
if(isset($_GET['file'])) {
    $file = basename($_GET['file']);
    $file_path = '/var/www/uploads/' . $file;

    // Verifica que el archivo exista
    if(file_exists($file_path)) {
        // Definir encabezados para la descarga
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        
        // Leer el archivo y enviarlo al output buffer
        readfile($file_path);
        exit;
    } else {
        // El archivo no existe
        echo "El archivo no existe.";
    }
} else {
    // No se especificó ningún archivo
    echo "No se especificó ningún archivo.";
}
?>
