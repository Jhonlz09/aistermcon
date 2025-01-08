
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!(isset($_SESSION['s_usuario']))) {
    header("Location: /aistermcon");
    exit();
}

// Obtener el nombre del archivo desde la URL
$file = $_GET['file']; // Ejemplo: 2025/3.pdf
$dir = $_GET['route']; // Ejemplo: presupuesto_proveedor

// Construir la ruta completa
$file_path = '/var/www/' . $dir . '/' . $file;

// Verificar si el archivo existe
if (file_exists($file_path)) {
    // Configurar las cabeceras HTTP
    header('HTTP/1.1 200 OK');
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($file) . '"');
    header('Content-Length: ' . filesize($file_path));

    // Enviar el contenido del archivo
    readfile($file_path);
    exit;
} else {
    // Mostrar un mensaje si el archivo no existe
    http_response_code(404);
    echo "El archivo no existe.";
}
?>