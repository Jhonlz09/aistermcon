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
$anchoColumna = 10;

// Estilo para las tarjetas
$styleTarjeta = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THICK,
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
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
$sheet->mergeCells('A1:N1');
$sheet->getStyle('A1')->applyFromArray($styleTitulo);
$fila = 3;

if (empty($idOrden) || empty($fechasSeleccionadas)) {
    $mensaje = empty($fechasSeleccionadas) ? 'NO SE SELECCIONARON FECHAS' : 'NO SE RECIBIÓ IDS DE ORDEN';
    $sheet->setCellValue("A$fila", $mensaje);
} else {
    $data_costos = ModeloHorario::mdlInformeHorarioOrden($idOrden, $fechasSeleccionadas);
    $colIndex = 0;
    $rowBase = $fila;

    foreach ($data_costos as $dato) {
        $colLetraBase = chr(ord('A') + ($colIndex * 5)); // A, F, K...

        $orden = $dato['orden'] ?? '';
        $gasto = $dato['suma_gasto_en_obra'] ?? '$0.00';
        $mano = $dato['suma_costo_mano_obra'] ?? '$0.00';
        $total = $dato['suma_total_costo'] ?? '$0.00';

        // Establecer posiciones
        $col1 = $colLetraBase;
        $col2 = chr(ord($col1) + 1);
        $col3 = chr(ord($col1) + 2);
        $col4 = chr(ord($col1) + 3);

        // Título ORDEN
        $sheet->mergeCells("$col1$rowBase:$col4$rowBase");
        $sheet->setCellValue("$col1$rowBase", "$orden");
        $sheet->getStyle("$col1$rowBase")->applyFromArray($styleTarjeta);
        $sheet->getStyle("$col1$rowBase")->getFont()->setBold(true);

        // GASTOS
        $sheet->mergeCells("$col1" . ($rowBase + 1) . ":$col3" . ($rowBase + 1));
        $sheet->setCellValue("$col1" . ($rowBase + 1), "GASTOS");
        $sheet->setCellValue("$col4" . ($rowBase + 1), $gasto);

        // MANO DE OBRA
        $sheet->mergeCells("$col1" . ($rowBase + 2) . ":$col3" . ($rowBase + 2));
        $sheet->setCellValue("$col1" . ($rowBase + 2), "MANO DE OBRA");
        $sheet->setCellValue("$col4" . ($rowBase + 2), $mano);

        // TOTAL
        $sheet->mergeCells("$col1" . ($rowBase + 3) . ":$col3" . ($rowBase + 3));
        $sheet->setCellValue("$col1" . ($rowBase + 3), "");
        $sheet->setCellValue("$col4" . ($rowBase + 3), $total);

        // Aplicar bordes y estilos a toda la tarjeta (4x4)
        foreach (range(0, 3) as $rOffset) {
            $row = $rowBase + $rOffset;
            foreach (range(0, 3) as $cOffset) {
                $col = chr(ord($col1) + $cOffset);
                $sheet->getStyle("$col$row")->applyFromArray($styleTarjeta);
                $sheet->getColumnDimension($col)->setWidth($anchoColumna);
            }
        }

        // Siguiente tarjeta
        $colIndex++;
        if ($colIndex === $columnasPorFila) {
            $colIndex = 0;
            $rowBase += 6; // Espaciado entre filas de tarjetas
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
