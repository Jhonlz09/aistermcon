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
    header("Location: /");
    exit();
}

// Función para limpiar valores monetarios
function limpiarNumero($valor)
{
    return floatval(preg_replace('/[^\d.-]/', '', $valor));
}

// Recibir datos POST
$idOrden = isset($_POST['orden_seleccionadas']) ? explode(',', $_POST['orden_seleccionadas']) : [];
$fechasSeleccionadas = isset($_POST['fechas_seleccionadas']) ? explode(',', $_POST['fechas_seleccionadas']) : [];

// Crear el documento
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Informe");

// Estilos
$styleTitulo = [
    'font' => ['bold' => true, 'size' => 12],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
];

$sheet->setCellValue('A1', 'INFORME DE GASTOS Y MANO DE OBRA POR ORDEN');
$sheet->mergeCells('A1:N1');
$sheet->getStyle('A1')->applyFromArray($styleTitulo);

$fila = 3;
$columnasPorFila = 3;
$anchoColumna = 10;

if (empty($idOrden) || empty($fechasSeleccionadas)) {
    $mensaje = empty($fechasSeleccionadas) ? 'NO SE SELECCIONARON FECHAS' : 'NO SE RECIBIÓ IDS DE ORDEN';
    $sheet->setCellValue("A$fila", $mensaje);
} else {
    $data_costos = ModeloHorario::mdlInformeHorarioOrden($idOrden, $fechasSeleccionadas);

    $colIndex = 0;
    $rowBase = $fila;

    foreach ($data_costos as $dato) {
        $colLetraBase = chr(ord('A') + ($colIndex * 5));
        $col1 = $colLetraBase;
        $col2 = chr(ord($col1) + 1);
        $col3 = chr(ord($col1) + 2);
        $col4 = chr(ord($col1) + 3);

        $orden = $dato['orden'] ?? '';
        $gasto = limpiarNumero($dato['suma_gasto_en_obra'] ?? 0);
        $mano  = limpiarNumero($dato['suma_costo_mano_obra'] ?? 0);
        $total = limpiarNumero($dato['suma_total_costo'] ?? 0);

        // TÍTULO ORDEN (con todos los bordes)
        $sheet->mergeCells("$col1$rowBase:$col4$rowBase");
        $sheet->setCellValue("$col1$rowBase", $orden);
        $sheet->getStyle("$col1$rowBase:$col4$rowBase")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THICK]],
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // GASTOS
        $sheet->mergeCells("$col1" . ($rowBase + 1) . ":$col3" . ($rowBase + 1));
        $sheet->setCellValue("$col1" . ($rowBase + 1), "GASTOS");
        $sheet->setCellValue("$col4" . ($rowBase + 1), $gasto);
        $sheet->getStyle("$col4" . ($rowBase + 1))->getNumberFormat()->setFormatCode('"$"#,##0.00');

        // MANO DE OBRA
        $sheet->mergeCells("$col1" . ($rowBase + 2) . ":$col3" . ($rowBase + 2));
        $sheet->setCellValue("$col1" . ($rowBase + 2), "MANO DE OBRA");
        $sheet->setCellValue("$col4" . ($rowBase + 2), $mano);
        $sheet->getStyle("$col4" . ($rowBase + 2))->getNumberFormat()->setFormatCode('"$"#,##0.00');
        $sheet->getStyle("$col4" . ($rowBase + 2))->getFont()->setUnderline(true);

        // TOTAL
        $sheet->mergeCells("$col1" . ($rowBase + 3) . ":$col3" . ($rowBase + 3));
        $sheet->setCellValue("$col1" . ($rowBase + 3), "");
        $sheet->setCellValue("$col4" . ($rowBase + 3), $total);
        $sheet->getStyle("$col4" . ($rowBase + 3))->getNumberFormat()->setFormatCode('"$"#,##0.00');

        // Ajustar ancho de columnas
        foreach (range(0, 3) as $offset) {
            $col = chr(ord($col1) + $offset);
            $sheet->getColumnDimension($col)->setWidth($anchoColumna);
        }

        // Borde exterior del bloque
        $rangoTarjeta = "$col1" . ($rowBase + 1) . ":$col4" . ($rowBase + 3);
        $sheet->getStyle($rangoTarjeta)->applyFromArray([
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_THICK]],
        ]);

        // Siguiente tarjeta
        $colIndex++;
        if ($colIndex === $columnasPorFila) {
            $colIndex = 0;
            $rowBase += 6;
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
