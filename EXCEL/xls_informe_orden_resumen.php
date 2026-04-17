<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$ver_precios = isset($_SESSION["precios3"]) ? $_SESSION["precios3"] == 1 : false;

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
use PhpOffice\PhpSpreadsheet\Exception as SpreadException;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

try {
    $id_orden = $_POST['id_orden'] ?? $_GET['id_orden'] ?? null;
    
    if (!$id_orden) {
        die("Error: ID de orden no recibido.");
    }

    $datos_detalle = ModeloInforme::mdlInformeDetalleOrden($id_orden);
    $data_resumen = ModeloInforme::mdlInformeOrdenResumen($id_orden, null, true);
    
    if (empty($datos_detalle)) {
        die("Error: No se encontraron detalles para la orden.");
    }

    $spreadsheet = new Spreadsheet();
    $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(11);
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('RESUMEN TOTAL');

    // Configuración de celdas
    $last_col = $ver_precios ? 'G' : 'F';
    $sheet->mergeCells('A1:' . $last_col . '1');
    $sheet->getStyle('A1:' . $last_col . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1:' . $last_col . '1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    
    $sheet->getColumnDimension('A')->setWidth(26);
    $sheet->getColumnDimension('B')->setWidth(69);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('E')->setWidth(15);
    $sheet->getColumnDimension('F')->setWidth(15);
    if ($ver_precios) {
        $sheet->getColumnDimension('G')->setWidth(20);
    }
    
    $sheet->getRowDimension(4)->setRowHeight(20.25);
    
    // Título
    $sheet->setCellValue('A1', 'RESUMEN DE USO DE HERRAMIENTAS Y MATERIALES')->getStyle('A1')->getFont()->setSize(18);
    $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FEA767');
    $sheet->getStyle('A1:' . $last_col . '1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    
    // Fila 3: Cliente
    $sheet->setCellValue('A3', 'CLIENTE:')->getStyle('A3')->getFont()->setBold(true);
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A3:' . $last_col . '3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    
    $sheet->mergeCells('B3:C3');
    $sheet->setCellValue('B3', $datos_detalle[0]['cliente'])->getStyle('B3')->getFont()->setSize(16);
    $sheet->getStyle('B3')->getFont()->setBold(true);
    $sheet->getStyle('B3:C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B3:C3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    
    $sheet->setCellValue('D3', 'FECHA INICIO:')->getStyle('D3')->getFont()->setBold(true);
    $sheet->getStyle('D3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    
    $sheet->mergeCells('E3:' . $last_col . '3');
    $sheet->setCellValue('E3', $datos_detalle[0]['fecha_ini'])->getStyle('E3')->getFont()->setSize(12);
    $sheet->getStyle('E3:' . $last_col . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E3:' . $last_col . '3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    
    // Fila 4: Encargado
    $sheet->setCellValue('A4', 'RESPONSABLE DE OBRA:')->getStyle('A4')->getFont()->setBold(true);
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A4:' . $last_col . '4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $sheet->mergeCells('B4:C4');
    $encargado_val = $datos_detalle[0]['encargado'] ?? '';
    $sheet->setCellValue('B4', $encargado_val)->getStyle('B4')->getFont()->setSize(12);
    $sheet->getStyle('B4:C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B4:C4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('D4', 'FECHA FIN:')->getStyle('D4')->getFont()->setBold(true);
    $sheet->getStyle('D4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->mergeCells('E4:' . $last_col . '4');
    $sheet->setCellValue('E4', $datos_detalle[0]['fecha_fin'])->getStyle('E4')->getFont()->setSize(12);
    $sheet->getStyle('E4:' . $last_col . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E4:' . $last_col . '4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    // Fila 5-6: Detalles y Orden
    $sheet->mergeCells('A5:A6');
    $sheet->setCellValue('A5', 'DETALLE DE OBRA:')->getStyle('A5')->getFont()->setBold(true);
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A5:A6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $sheet->mergeCells('B5:C6');
    $sheet->setCellValue('B5', $datos_detalle[0]['descripcion'])->getStyle('B5')->getFont()->setSize(12);
    $sheet->getStyle('B5:C6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B5:C6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('B5:C6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $sheet->mergeCells('D5:D6');
    $sheet->setCellValue('D5', 'NRO. ORDEN')->getStyle('D5')->getFont()->setBold(true);
    $sheet->getStyle('D5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('D5:D6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $sheet->mergeCells('E5:' . $last_col . '6');
    $sheet->setCellValue('E5', $datos_detalle[0]['orden_nro'])->getStyle('E5')->getFont()->setSize(16);
    $sheet->getStyle('E5')->getFont()->setBold(true);
    $sheet->getStyle('E5:' . $last_col . '6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E5:' . $last_col . '6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('E5:' . $last_col . '6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Cabeceras de tabla (Fila 8)
    $sheet->mergeCells('A8:A10');
    $sheet->setCellValue('A8', 'CÓDIGO')->getStyle('A8')->getFont()->setSize(10)->setBold(true);
    $sheet->getStyle('A8:A10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A8:A10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
    $sheet->getStyle('A8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9E7FD');

    $sheet->mergeCells('B8:B10');
    $sheet->setCellValue('B8', 'DESCRIPCIÓN')->getStyle('B8')->getFont()->setSize(10)->setBold(true);
    $sheet->getStyle('B8:B10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('B8:B10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
    $sheet->getStyle('B8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9E7FD');

    $sheet->mergeCells('C8:C10');
    $sheet->setCellValue('C8', 'UNIDAD')->getStyle('C8')->getFont()->setSize(10)->setBold(true);
    $sheet->getStyle('C8:C10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('C8:C10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
    $sheet->getStyle('C8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9E7FD');

    $sheet->mergeCells('D8:D10');
    $sheet->setCellValue('D8', 'TOTAL SALIDA')->getStyle('D8')->getFont()->setSize(10)->setBold(true);
    $sheet->getStyle('D8:D10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
    $sheet->getStyle('D8:D10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
    $sheet->getStyle('D8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F28E86');

    $sheet->mergeCells('E8:E10');
    $sheet->setCellValue('E8', 'TOTAL ENTRADA')->getStyle('E8')->getFont()->setSize(10)->setBold(true);
    $sheet->getStyle('E8:E10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
    $sheet->getStyle('E8:E10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
    $sheet->getStyle('E8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('A6E3B7');

    $sheet->mergeCells('F8:F10');
    $sheet->setCellValue('F8', 'TOTAL UTILIZADO')->getStyle('F8')->getFont()->setSize(10)->setBold(true);
    $sheet->getStyle('F8:F10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
    $sheet->getStyle('F8:F10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
    $sheet->getStyle('F8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FEA767');

    if ($ver_precios) {
        $sheet->mergeCells('G8:G10');
        $sheet->setCellValue('G8', 'COSTO $')->getStyle('G8')->getFont()->setSize(10)->setBold(true);
        $sheet->getStyle('G8:G10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        $sheet->getStyle('G8:G10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('G8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('EAEAEA');
    }

    $row = 11; // Fila de inicio de datos
    $total_capital_general = 0;

    foreach ($data_resumen as $res) {
        $sheet->setCellValue('A' . $row, $res['codigo']);
        $sheet->setCellValue('B' . $row, $res['descripcion']);
        $sheet->setCellValue('C' . $row, $res['unidad']);
        $sheet->setCellValue('D' . $row, $res['cantidad_salida']);
        $sheet->setCellValue('E' . $row, $res['retorno']);
        $sheet->setCellValue('F' . $row, $res['utilizado']);
        
        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        if ($ver_precios) {
            $capital = floatval($res['capital']);
            $total_capital_general += $capital;
            $sheet->setCellValue('G' . $row, $capital);
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }
        
        $sheet->getStyle('A' . $row . ':' . $last_col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $row++;
    }

    if ($ver_precios) {
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $sheet->setCellValue('A' . $row, 'TOTAL GENERAL:');
        $sheet->setCellValue('G' . $row, $total_capital_general);
        $sheet->getStyle('A' . $row . ':F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('A' . $row . ':G' . $row)->getFont()->setBold(true);
        $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
        $sheet->getStyle('A' . $row . ':G' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A' . $row . ':G' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('EAEAEA');
    }

    $file_name = 'RESUMEN ' . $datos_detalle[0]['orden_nro'] . '  ' . $datos_detalle[0]['cliente'] . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $file_name . '"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit();

} catch (SpreadException $e) {
    echo $e->getMessage();
    exit();
}
