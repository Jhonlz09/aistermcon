<?php
use PhpOffice\PhpSpreadsheet\Writer\Pdf;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['s_usuario'])) {
    header("Location: /aistermcon");
    exit();
}

require('../assets/plugins/fpdf/fpdf.php');
require('../models/salidas.modelo.php');

$id_boleta = $_POST['id_boleta'];

// Función para obtener DPI real de la imagen (solo funciona con JPEG/TIFF con EXIF)
function obtenerDPI($ruta_imagen) {
    $exif = @exif_read_data($ruta_imagen);

    if ($exif && isset($exif['XResolution'], $exif['YResolution'], $exif['ResolutionUnit'])) {
        // Manejar fracciones (racionales)
        $xRes = $exif['XResolution'];
        $yRes = $exif['YResolution'];

        if (is_array($xRes) && count($xRes) == 2 && $xRes[1] != 0) {
            $xDPI = $xRes[0] / $xRes[1];
        } else {
            $xDPI = (float)$xRes;
        }

        if (is_array($yRes) && count($yRes) == 2 && $yRes[1] != 0) {
            $yDPI = $yRes[0] / $yRes[1];
        } else {
            $yDPI = (float)$yRes;
        }

        // ResolutionUnit: 2 = pulgadas, 3 = cm (no útil para DPI)
        if ($exif['ResolutionUnit'] == 2) {
            return ($xDPI + $yDPI) / 2; // Promedio DPI horizontal y vertical
        }
    }

    // Valor por defecto si no se detecta DPI real
    return 96;
}

if (!defined('MM_PER_INCH')) define('MM_PER_INCH', 25.4);

$data_boleta = ModeloSalidas::mdlObtenerImgBoleta($id_boleta);
$dir = __DIR__ . "/../../guia_img/";

$pdf = new FPDF();
$pdf->AliasNbPages();
$pdf->SetTitle(iconv('UTF-8', 'windows-1252', 'Evidencia fotográfica'));

if (empty($data_boleta)) {
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 16);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'No se encontraron imágenes'), 0, 1, 'C');
} else {
    foreach ($data_boleta as $imagen) {
        $image_path = $dir . $imagen['nombre_imagen'];

        if (!file_exists($image_path)) {
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, "Imagen no encontrada: " . $imagen['nombre_imagen'], 0, 1);
            continue;
        }

        // Manejar imágenes WebP
        $extension = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
        $tempFile = null;

        if ($extension === 'webp') {
            $im = imagecreatefromwebp($image_path);
            if ($im) {
                $tempFile = tempnam(sys_get_temp_dir(), 'img') . '.png';
                imagepng($im, $tempFile);
                imagedestroy($im);
                $image_path = $tempFile;
            }
        }

        list($widthPx, $heightPx) = getimagesize($image_path);

        // Obtener DPI real para la imagen
        $dpi = obtenerDPI($image_path);

        $widthMm  = ($widthPx  / $dpi) * MM_PER_INCH;
        $heightMm = ($heightPx / $dpi) * MM_PER_INCH;

        $pdf->AddPage('P', [$widthMm, $heightMm]);
        $pdf->SetMargins(0, 0, 0);

        // if ($firstPage) {
        //     $pdf->SetFont('Arial', 'B', 20);
        //     $pdf->SetY(5);
        //     $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Evidencia Fotográfica'), 0, 1, 'C');
        //     $firstPage = false;
        // }

        $pdf->Image($image_path, 0, 0, $widthMm, $heightMm);

        if ($tempFile && file_exists($tempFile)) {
            unlink($tempFile);
        }
    }
}

$pdf->Output();
