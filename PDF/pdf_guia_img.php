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

$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Evidencia Fotográfica'), 0, 1, 'C');

$x = 40;
$y = 20;
$maxImagesPerPage = 2;
$imageCount = 0;

$newWidth = 150;
$maxHeight = 120;

if (empty($data_boleta)) {
    $pdf->SetFont('Arial', '', 16);
    $pdf->setY(80);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'No se encontraron imágenes'), 0, 1, 'C');
} else {
    foreach ($data_boleta as $imagen) {
        $image_path = $dir . $imagen['nombre_imagen'];

        if (file_exists($image_path)) {
            $extension = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));

            // Si la imagen es WebP, convertirla temporalmente a PNG
            if ($extension === 'webp') {
                $image = imagecreatefromwebp($image_path);
                if ($image) {
                    $tempFile = tempnam(sys_get_temp_dir(), 'img') . '.png';
                    imagepng($image, $tempFile);
                    imagedestroy($image);
                    $image_path = $tempFile;
                }
            }

            list($width, $height) = getimagesize($image_path);
            $scale = $newWidth / $width;
            $newHeight = $height * $scale;

            if ($newHeight > $maxHeight) {
                $newHeight = $maxHeight;
                $newWidth = $width * ($maxHeight / $height);
            }

            if ($imageCount == $maxImagesPerPage) {
                $pdf->AddPage();
                $x = 40;
                $y = 15;
                $imageCount = 0;
            }

            $pdf->Image($image_path, $x, $y, $newWidth, $newHeight);
            $y += $newHeight + 10;
            $imageCount++;

            // Eliminar la imagen temporal si se creó
            if (isset($tempFile)) {
                unlink($tempFile);
                unset($tempFile);
            }
        } else {
            $pdf->Cell(0, 10, "Imagen no encontrada: " . $imagen['nombre_imagen'], 0, 1);
        }
    }
}

$pdf->Output();
