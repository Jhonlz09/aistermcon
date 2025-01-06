
Si deseas mostrar tu archivo PDF directamente a través de la web sin procesarlo con FPDF u otra librería, puedes hacerlo configurando correctamente las cabeceras HTTP en un archivo PHP para servir el PDF. Aquí tienes una solución simple:

1. Código para Mostrar un PDF
Crea un archivo PHP (por ejemplo, mostrar_pdf.php) con el siguiente código:

php
Copy code
<?php
// Obtener el nombre del archivo desde la URL
$file = $_GET['file']; // Ejemplo: 2025/3.pdf
$dir = $_GET['route']; // Ejemplo: presupuesto_proveedor

// Construir la ruta completa
$file_path = '/var/www/' . $dir . '/' . $file;

// Verificar si el archivo existe
if (file_exists($file_path)) {
    // Configurar las cabeceras HTTP
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