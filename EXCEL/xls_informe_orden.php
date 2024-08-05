<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!(isset($_SESSION['s_usuario']))) {
    header("Location: /aistermcon");
    exit();
}
require_once "../utils/database/conexion.php";
require_once('../models/informe.modelo.php');

require_once('../models/inventario.modelo.php');
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;


$id_orden = $_POST['id_orden'];

$datos_detalle = ModeloInforme::mdlInformeDetalleOrden($id_orden);
$data_resumen = ModeloInforme::mdlInformeOrdenResumen($id_orden);


$spreadsheet = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(11);
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('RESUMEN');

// Merge and center cells
$sheet->mergeCells('A1:F1');
$sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:F1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Set the width of column A to 20
$sheet->getColumnDimension('A')->setWidth(26);
$sheet->getColumnDimension('B')->setWidth(69);
$sheet->getColumnDimension('C')->setWidth(10);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getRowDimension(4)->setRowHeight(20.25);

// Set the cell A1
$sheet->setCellValue('A1', 'RESUMEN DE OBRA')->getStyle('A1')->getFont()->setSize(18);
$sheet->getStyle('A1')->getFont()->setBold(true);
$sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FEA767');
$sheet->getStyle('A1:F1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Set the cell A3
$sheet->setCellValue('A3', 'CLIENTE:')->getStyle('A3')->getFont()->setBold(true);
$sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A3:F3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
// Set the cell B3
$sheet->mergeCells('B3:C3');
$sheet->setCellValue('B3', $datos_detalle[0]['cliente'])->getStyle('B3')->getFont()->setSize(16);
$sheet->getStyle('B3')->getFont()->setBold(true);
$sheet->getStyle('B3:C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B3:C3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Set the cell D3
$sheet->setCellValue('D3', 'FECHA INICIO:')->getStyle('D3')->getFont()->setBold(true);
$sheet->getStyle('D3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Set the cell E3
$sheet->mergeCells('E3:F3');
$sheet->setCellValue('E3', $datos_detalle[0]['fecha_ini'])->getStyle('E3')->getFont()->setSize(12);
$sheet->getStyle('E3:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E3:F3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Set the cell A4
$sheet->setCellValue('A4', 'RESPONSABLE DE OBRA:')->getStyle('A4')->getFont()->setBold(true);
$sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A4:F4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Set the cell B4
$sheet->mergeCells('B4:C4');
$sheet->setCellValue('B4', $datos_detalle[0]['encargado'])->getStyle('B4')->getFont()->setSize(12);
// $sheet->getStyle('B4')->getFont()->setBold(true);
$sheet->getStyle('B4:C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B4:C4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Set the cell D4
$sheet->setCellValue('D4', 'FECHA FIN:')->getStyle('D4')->getFont()->setBold(true);
$sheet->getStyle('D4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Set the cell E4
$sheet->mergeCells('E4:F4');
$sheet->setCellValue('E4', $datos_detalle[0]['fecha_fin'])->getStyle('E4')->getFont()->setSize(12);
$sheet->getStyle('E4:F4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E4:F4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Set the cell A5
$sheet->mergeCells('A5:A6');
$sheet->setCellValue('A5', 'DETALLE DE OBRA:')->getStyle('A5')->getFont()->setBold(true);
$sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A5:A6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Set the cell B5
$sheet->mergeCells('B5:C6');
$sheet->setCellValue('B5', $datos_detalle[0]['descripcion'])->getStyle('B5')->getFont()->setSize(12);
$sheet->getStyle('B5:C6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B5:C6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('B5:C6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Set the cell D5
$sheet->mergeCells('D5:D6');
$sheet->setCellValue('D5', 'NRO. ORDEN')->getStyle('D5')->getFont()->setBold(true);
$sheet->getStyle('D5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('D5:D6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Set the cell E5
$sheet->mergeCells('E5:F6');
$sheet->setCellValue('E5', $datos_detalle[0]['orden_nro'])->getStyle('E5')->getFont()->setSize(16);
$sheet->getStyle('E5')->getFont()->setBold(true);
$sheet->getStyle('E5:F6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E5:F6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('E5:F6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Set the cell A8
$sheet->mergeCells('A8:A10');

$sheet->setCellValue('A8', 'CÓDIGO')->getStyle('A8')->getFont()->setSize(10);
$sheet->getStyle('A8')->getFont()->setBold(true);
$sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A8')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A8:A10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
$sheet->getStyle('A8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9E7FD');

// Set the cell B8
$sheet->mergeCells('B8:B10');
$sheet->setCellValue('B8', 'DESCRIPCIÓN')->getStyle('B8')->getFont()->setSize(10);
$sheet->getStyle('B8')->getFont()->setBold(true);
$sheet->getStyle('B8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B8')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('B8:B10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
$sheet->getStyle('B8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9E7FD');

// Set the cell C8
$sheet->mergeCells('C8:C10');
$sheet->setCellValue('C8', 'UNIDAD')->getStyle('C8')->getFont()->setSize(10);
$sheet->getStyle('C8')->getFont()->setBold(true);
$sheet->getStyle('C8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C8')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('C8:C10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
$sheet->getStyle('C8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9E7FD');

// Set the cell D8
$sheet->mergeCells('D8:D10');
$sheet->setCellValue('D8', 'TOTAL SALIDA')->getStyle('D8')->getFont()->setSize(10);
$sheet->getStyle('D8')->getFont()->setBold(true);
$sheet->getStyle('D8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D8')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('D8:D10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
$sheet->getStyle('D8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F28E86');

// Set the cell E8
$sheet->mergeCells('E8:E10');
$sheet->setCellValue('E8', 'TOTAL ENTRADA')->getStyle('E8')->getFont()->setSize(10);
$sheet->getStyle('E8')->getFont()->setBold(true);
$sheet->getStyle('E8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E8')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('E8:E10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
$sheet->getStyle('E8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('A6E3B7');

// Set the cell F8
$sheet->mergeCells('F8:F10');
$sheet->setCellValue('F8', 'TOTAL MATERIAL Y HERRAMIENTA UTILIZADO')->getStyle('F8')->getFont()->setSize(10);
$sheet->getStyle('F8')->getFont()->setBold(true);
$sheet->getStyle('F8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('F8')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('F8')->getAlignment()->setWrapText(true); // Add this line to adjust the text
$sheet->getStyle('F8:F10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
$sheet->getStyle('F8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FEA767');


// Set the cell A11
$row = 11; // Starting row for data

foreach ($data_resumen as $resumen) {
    $sheet->setCellValue('A' . $row, $resumen['codigo']);
    $sheet->setCellValue('B' . $row, $resumen['descripcion']);
    $sheet->setCellValue('C' . $row, $resumen['unidad']);
    $sheet->setCellValue('D' . $row, $resumen['cantidad_salida']);
    $sheet->setCellValue('E' . $row, $resumen['retorno']);
    $sheet->setCellValue('F' . $row, $resumen['utilizado']);
    $sheet->getStyle('C'. $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D'. $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E'. $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F'. $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A'.$row.':F'.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
    $row++; // Increment row for the next data
}

// $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$file_name= $datos_detalle[0]['orden_nro'].'  '.$datos_detalle[0]['cliente'].'.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$file_name.'"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit();

