<?php

use PhpOffice\PhpSpreadsheet\Writer\Pdf;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!(isset($_SESSION['s_usuario']))) {
    header("Location: /aistermcon");
    exit();
}

require('../assets/plugins/fpdf/fpdf.php');
require('../models/salidas.modelo.php');

$id_boleta = $_POST['id_boleta'];

$pdf = new FPDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$data_boleta = ModeloSalidas::mdlObtenerImgBoleta($id_boleta);
$pdf->SetTitle("Evidencia fotografica");
$dir = __DIR__ . "/../../guia_img/";

$pdf->SetFont('Arial', 'B', 20); // Fuente para el título
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Evidencia Fotográfica'), 0, 1, 'C'); // Cel

$x = 40; // Margen izquierdo
$y = 20; // Margen superior inicial
$maxImagesPerPage = 2; // Máximo de imágenes por página
$imageCount = 0; // Contador de imágenes por página

// Altura y ancho para las imágenes
$newWidth = 150; // Ancho deseado
$maxHeight = 120; // Altura máxima por imagen (ajustada para dos imágenes por página)

if (empty($data_boleta)) {
    $pdf->SetFont('Arial', '', 16);
    $pdf->setY(80);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'No se encontraron imágenes'), 0, 1, 'C');
} else {
    foreach ($data_boleta as $imagen) {
        $image_path = $dir . $imagen['nombre_imagen'];

        // Comprobar si la imagen existe
        if (file_exists($image_path)) {
            // Obtener dimensiones de la imagen
            list($width, $height) = getimagesize($image_path);

            // Escalar la imagen proporcionalmente para el ancho máximo permitido
            $scale = $newWidth / $width;
            $newHeight = $height * $scale;

            // Ajustar la altura si excede el máximo permitido
            if ($newHeight > $maxHeight) {
                $newHeight = $maxHeight;
                $newWidth = $width * ($maxHeight / $height);
            }

            // Verificar si se necesitan dos imágenes por página
            if ($imageCount == $maxImagesPerPage) {
                $pdf->AddPage(); // Añadir nueva página
                $x = 40; // Reiniciar posición horizontal
                $y = 15; // Reiniciar posición vertical
                $imageCount = 0; // Reiniciar contador de imágenes
            }

            // Añadir la imagen al PDF
            $pdf->Image($image_path, $x, $y, $newWidth, $newHeight);
            $y += $newHeight + 10; // Mover hacia abajo para la próxima imagen
            $imageCount++; // Incrementar contador de imágenes
        } else {
            // Registrar un mensaje de error si la imagen no existe
            $pdf->Cell(0, 10, "Imagen no encontrada: " . $imagen['nombre_imagen'], 0, 1);
        }
    }
}
// Iterar sobre las imágenes

// Salida del PDF
$pdf->Output();
