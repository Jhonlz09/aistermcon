<?php
require_once('../vendor/autoload.php');

use setasign\Fpdi\Fpdi;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!(isset($_SESSION['s_usuario']))) {
    header("Location: /aistermcon");
    exit();
}

try {
    $route = $_GET['file'];
    $dir = $_GET['route'];

    $file = basename($_GET['file']);
    $file_path = '/var/www/' . $dir . '/' . $route;

    if (!file_exists($file_path)) {
        die("El archivo no existe: " . htmlspecialchars($file));
    }

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

    if ($ext === 'pdf') {
        // PDF: mostrar en navegador usando FPDI
        $pdf = new Fpdi();
        $pdf->setSourceFile($file_path);
        $pageCount = $pdf->setSourceFile($file_path);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $pdf->AddPage();
            $tplId = $pdf->importPage($pageNo);
            $pdf->useTemplate($tplId);
        }
        $pdf->SetTitle(iconv('UTF-8', 'windows-1252', $file));
        $pdf->Output('I', htmlspecialchars($file), true);
        exit;
    } else {
        // Excel u otro: forzar descarga
        $mimeTypes = [
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];
        $mime = isset($mimeTypes[$ext]) ? $mimeTypes[$ext] : 'application/octet-stream';

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    }
} catch (Exception $e) {
    die("Error al descargar el archivo: " . $e->getMessage());
}