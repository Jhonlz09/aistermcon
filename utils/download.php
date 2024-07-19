<?php
require_once('../vendor/autoload.php');

use setasign\Fpdi\Fpdi;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!(isset($_SESSION['s_usuario']))) {
    header("Location: /aistermcon");
    exit();
}

// Obtener el nombre del archivo desde la URL
$route = $_GET['file'];
$file = basename($_GET['file']);
$file_path = 'C:/xampp/uploads/' . $route;

// Verificar si el archivo existe
if (file_exists($file_path)) {
    // Crear una instancia de FPDI
    $pdf = new Fpdi();

    // Cargar el PDF existente
    $pdf->setSourceFile($file_path);

    // Obtener el número total de páginas en el archivo PDF
    $pageCount = $pdf->setSourceFile($file_path);

    // Importar todas las páginas del PDF
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        $pdf->AddPage(); // Añadir una nueva página al documento
        $tplId = $pdf->importPage($pageNo); // Importar la página actual
        $pdf->useTemplate($tplId); // Usar la plantilla importada
    }
    
    // Modificar los metadatos
    $pdf->SetTitle(iconv('UTF-8', 'windows-1252', $file));
    // Enviar encabezados apropiados para mostrar el PDF
    // header('Content-Type: application/pdf');
    // header('Content-Disposition: inline; filename="' . htmlspecialchars($file) . '"');
    // header('Content-Transfer-Encoding: binary');
    // header('Content-Length: ' . filesize($file_path));

    // // Limpiar el búfer de salida
    // flush();

    // Enviar el PDF al navegador
    $pdf->Output('I', htmlspecialchars($file), true);
    exit;
} else {
    die("El archivo no existe: " . $file);
}
