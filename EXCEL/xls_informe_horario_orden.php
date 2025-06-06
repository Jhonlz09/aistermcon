<?php
require '../vendor/autoload.php';
require('../models/horario.modelo.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Validar sesión
session_start();
if (!isset($_SESSION['s_usuario'])) {
    header("Location: /aistermcon");
    exit();
}

// Recibir datos POST
$idOrden = isset($_POST['orden_seleccionadas']) ? explode(',', $_POST['orden_seleccionadas']) : [];
$fechasSeleccionadas = isset($_POST['fechas_seleccionadas']) ? explode(',', $_POST['fechas_seleccionadas']) : [];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Informe");

$colInicio = 'A';
$fila = 1;
$columnasPorFila = 3;
$anchoColumna = 25;

// Estilo para las tarjetas
$styleTarjeta = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_TOP,
        'wrapText' => true,
    ],
];

// Estilo para los títulos
$styleTitulo = [
    'font' => [
        'bold' => true,
        'size' => 12,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
];

$sheet->setCellValue('A1', 'INFORME DE GASTOS Y MANO DE OBRA POR ORDEN');
$sheet->mergeCells('A1:I1');
$sheet->getStyle('A1')->applyFromArray($styleTitulo);
$fila = 3;

if (empty($idOrden) || empty($fechasSeleccionadas)) {
    $mensaje = empty($fechasSeleccionadas) ? 'NO SE SELECCIONARON FECHAS' : 'NO SE RECIBIÓ IDS DE ORDEN';
    $sheet->setCellValue("A$fila", $mensaje);
} else {
    $data_costos = ModeloHorario::mdlInformeHorarioOrden($idOrden, $fechasSeleccionadas);

    $colIndex = 0;
    foreach ($data_costos as $dato) {
        $colLetra = chr(ord('A') + ($colIndex * 3)); // A, D, G...
        $orden = $dato['orden'] ?? '';
        $gasto = $dato['suma_gasto_en_obra'] ?? '$0.00';
        $mano = $dato['suma_costo_mano_obra'] ?? '$0.00';
        $total = $dato['suma_total_costo'] ?? '$0.00';

        // Cabecera (orden)
        $sheet->setCellValue("$colLetra$fila", "ORDEN: $orden");
        $sheet->mergeCells("$colLetra$fila:" . chr(ord($colLetra)+2) . "$fila");
        $sheet->getStyle("$colLetra$fila")->getFont()->setBold(true);

        // Gasto en obra
        $fila++;
        $sheet->setCellValue("$colLetra$fila", "GASTOS");
        $sheet->setCellValue(chr(ord($colLetra)+2) . "$fila", $gasto);

        // Mano de obra
        $fila++;
        $sheet->setCellValue("$colLetra$fila", "MANO DE OBRA");
        $sheet->setCellValue(chr(ord($colLetra)+2) . "$fila", $mano);

        // Total
        $fila++;
        $sheet->setCellValue("$colLetra$fila", "TOTAL");
        $sheet->setCellValue(chr(ord($colLetra)+2) . "$fila", $total);

        // Aplicar estilos y ancho
        foreach (range(0, 2) as $offset) {
            $col = chr(ord($colLetra) + $offset);
            $sheet->getColumnDimension($col)->setWidth($anchoColumna);
            foreach (range($fila - 3, $fila) as $f) {
                $sheet->getStyle("$col$f")->applyFromArray($styleTarjeta);
            }
        }

        // Siguiente columna o fila
        $colIndex++;
        if ($colIndex === $columnasPorFila) {
            $colIndex = 0;
            $fila += 5;
        }
    }
}

// Salida del archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Informe_Horario.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;