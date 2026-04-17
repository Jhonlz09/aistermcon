<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$ver_precios = isset($_SESSION["precios3"]) ? $_SESSION["precios3"] == 1 : false;

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
use PhpOffice\PhpSpreadsheet\Style\Color;

try {
    $id_orden = $_POST['id_orden'] ?? $_GET['id_orden'] ?? null;
    
    if (!$id_orden) {
        die("Error: ID de orden no recibido.");
    }

    $datos_detalle = ModeloInforme::mdlInformeDetalleOrden($id_orden);
    $datos_guias = ModeloInforme::mdlInformeFechaOrden($id_orden, true);
    
    if (empty($datos_detalle)) {
        die("Error: No se encontraron detalles para la orden.");
    }

    $spreadsheet = new Spreadsheet();
    $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(11);
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('FABRICACIÓN');

    $last_col = $ver_precios ? 'H' : 'G';
    
    // Anchos de columna
    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(80);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('E')->setWidth(15);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(15);
    if ($ver_precios) {
        $sheet->getColumnDimension('H')->setWidth(20);
    }

    // Encabezado principal
    $sheet->mergeCells('A1:' . $last_col . '1');
    $sheet->getStyle('A1:' . $last_col . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1:' . $last_col . '1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getRowDimension(1)->setRowHeight(25);
    $sheet->setCellValue('A1', 'INFORME DE FABRICACIÓN HERRAMIENTAS Y MATERIALES USADOS')->getStyle('A1')->getFont()->setSize(18)->setBold(true);
    $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FEA767');
    $sheet->getStyle('A1:' . $last_col . '1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Fila 3: Cliente y Orden
    $sheet->mergeCells('A3:B3');
    $sheet->setCellValue('A3', 'CLIENTE:')->getStyle('A3')->getFont()->setBold(true);
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A3:' . $last_col . '3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    
    $sheet->mergeCells('C3:D3');
    $sheet->setCellValue('C3', $datos_detalle[0]['cliente'])->getStyle('C3')->getFont()->setSize(14)->setBold(true);
    $sheet->getStyle('C3:D3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    $sheet->mergeCells('E3:F3');
    $sheet->setCellValue('E3', 'NRO. ORDEN:')->getStyle('E3')->getFont()->setBold(true);
    $sheet->getStyle('E3:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    
    $sheet->mergeCells('G3:' . $last_col . '3');
    $sheet->setCellValue('G3', $datos_detalle[0]['orden_nro'])->getStyle('G3')->getFont()->setSize(14)->setBold(true);
    $sheet->getStyle('G3:' . $last_col . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row = 5;

    if (!empty($datos_guias)) {
        foreach ($datos_guias as $guia) {
            $encargado = ($guia["responsable"] == '') ? 'N/A' : $guia["responsable"];
            
            $sheet->mergeCells('A' . $row . ':' . $last_col . $row);
            $sheet->setCellValue('A' . $row, 'GUÍA: ' . $guia["nro_guia"]);
            $sheet->getStyle('A' . $row)->getFont()->setSize(12)->setBold(true)->getColor()->setARGB(Color::COLOR_RED);
            $sheet->getStyle('A' . $row . ':' . $last_col . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('EAEAEA');
            $sheet->getStyle('A' . $row . ':' . $last_col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $row++;
            
            $sheet->mergeCells('B' . $row . ':C' . $row);
            $sheet->setCellValue('B' . $row, 'Fecha de salida: ' . $guia["fecha_emision"])->getStyle('B' . $row)->getFont()->setBold(true);
            $sheet->mergeCells('E' . $row . ':' . $last_col . $row);
            $sheet->setCellValue('E' . $row, 'Fecha de entrada: ' . $guia["fecha_retorno"])->getStyle('E' . $row)->getFont()->setBold(true);
            $row++;

            $sheet->mergeCells('B' . $row . ':C' . $row);
            $sheet->setCellValue('B' . $row, 'Conductor: ' . $guia["conductor"] . ' ' . $guia["placa"])->getStyle('B' . $row)->getFont()->setBold(true);
            $sheet->mergeCells('E' . $row . ':' . $last_col . $row);
            $sheet->setCellValue('E' . $row, 'Responsable: ' . $encargado)->getStyle('E' . $row)->getFont()->setBold(true);
            $row++;

            $sheet->mergeCells('B' . $row . ':C' . $row);
            $sheet->setCellValue('B' . $row, 'Motivo: ');
            $sheet->getStyle('B' . $row)->getFont()->setBold(true);
            $sheet->mergeCells('D' . $row . ':' . $last_col . $row);
            $sheet->setCellValue('D' . $row, $guia['motivo']);
            $sheet->getStyle('D' . $row)->getFont()->setItalic(true);
            $row++;
            $row++; // Spacing

            $id_guia = $guia["id_guia"];
            $total_capital_guia = 0;

            $data = ModeloInforme::mdlInformeOrdenFab($id_orden, $id_guia);

            foreach ($data as $fill) {
                // Producto Fabricado Header
                $sheet->mergeCells('B' . $row . ':' . $last_col . $row);
                $sheet->setCellValue('B' . $row, 'Producto fabricado:')->getStyle('B' . $row)->getFont()->setBold(true);
                $sheet->getStyle('B' . $row . ':' . $last_col . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FDE9D9');
                $row++;

                // Cabeceras de tabla
                $sheet->setCellValue('B' . $row, 'CÓDIGO')->getStyle('B' . $row)->getFont()->setBold(true);
                $sheet->setCellValue('C' . $row, 'DESCRIPCIÓN')->getStyle('C' . $row)->getFont()->setBold(true);
                $sheet->setCellValue('D' . $row, 'UNIDAD')->getStyle('D' . $row)->getFont()->setBold(true);
                $sheet->setCellValue('E' . $row, 'CANT. FAB.')->getStyle('E' . $row)->getFont()->setBold(true);
                $sheet->setCellValue('F' . $row, 'CANT. ENT.')->getStyle('F' . $row)->getFont()->setBold(true);
                
                if ($ver_precios) {
                    $sheet->setCellValue('H' . $row, 'COSTO $')->getStyle('H' . $row)->getFont()->setBold(true);
                }
                $sheet->getStyle('B' . $row . ':' . $last_col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('B' . $row . ':' . $last_col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $row++;

                $capital = floatval($fill["capital"]);
                $total_capital_guia += $capital;

                $sheet->setCellValue('B' . $row, $fill["codigo"]);
                $sheet->setCellValue('C' . $row, $fill["descripcion"]);
                $sheet->setCellValue('D' . $row, $fill["unidad"]);
                $sheet->setCellValue('E' . $row, $fill["cantidad_salida"]);
                $sheet->setCellValue('F' . $row, $fill["retorno"]);

                if ($ver_precios) {
                    $sheet->setCellValue('H' . $row, $capital);
                    $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
                }

                $sheet->getStyle('D' . $row . ':' . $last_col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $row . ':' . $last_col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $row++;
                
                $id_producto = $fill['id_producto'];
                $list_prod_util = ModeloInforme::mdlInformeOrdenFabUtil($id_producto);

                if (!empty($list_prod_util)) {
                    $row++;
                    // Materiales Usados Header
                    $sheet->mergeCells('C' . $row . ':' . $last_col . $row);
                    $sheet->setCellValue('C' . $row, 'Materiales usados en: ' . $fill["descripcion"])->getStyle('C' . $row)->getFont()->setBold(true);
                    $sheet->getStyle('C' . $row . ':' . $last_col . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E4DFEC');
                    $row++;

                    $sheet->setCellValue('C' . $row, 'CÓDIGO')->getStyle('C' . $row)->getFont()->setBold(true);
                    $sheet->setCellValue('D' . $row, 'DESCRIPCIÓN')->getStyle('D' . $row)->getFont()->setBold(true);
                    $sheet->mergeCells('E' . $row . ':F' . $row);
                    $sheet->setCellValue('E' . $row, 'USADO')->getStyle('E' . $row)->getFont()->setBold(true);
                    
                    if ($ver_precios) {
                        $sheet->setCellValue('H' . $row, 'COSTO $')->getStyle('H' . $row)->getFont()->setBold(true);
                    }
                    $sheet->getStyle('C' . $row . ':' . $last_col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle('C' . $row . ':' . $last_col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $row++;

                    foreach ($list_prod_util as $list) {
                        $capital_util = floatval($list["capital"]);
                        $total_capital_guia += $capital_util;

                        $sheet->setCellValue('C' . $row, $list["codigo"]);
                        $sheet->setCellValue('D' . $row, $list["descripcion"]);
                        $sheet->mergeCells('E' . $row . ':F' . $row);
                        $sheet->setCellValue('E' . $row, $list["cantidad_salida"]);

                        if ($ver_precios) {
                            $sheet->setCellValue('H' . $row, $capital_util);
                            $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
                        }

                        $sheet->getStyle('E' . $row . ':' . $last_col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle('C' . $row . ':' . $last_col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                        $row++;
                    }
                }
                $row++;
            }

            if ($ver_precios) {
                $sheet->mergeCells('B' . $row . ':G' . $row);
                $sheet->setCellValue('B' . $row, 'TOTAL COSTO DE GUÍA:');
                $sheet->setCellValue('H' . $row, $total_capital_guia);
                $sheet->getStyle('B' . $row . ':H' . $row)->getFont()->setBold(true);
                $sheet->getStyle('B' . $row . ':G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
                $sheet->getStyle('B' . $row . ':H' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('B' . $row . ':H' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('EAEAEA');
            }
            $row += 2;
        }

        // SECCION RESUMEN FABRICACION (Al final como en el PDF)
        $sheet->mergeCells('A' . $row . ':' . $last_col . $row);
        $sheet->setCellValue('A' . $row, 'RESUMEN DE FABRICACIÓN:')->getStyle('A' . $row)->getFont()->setSize(14)->setBold(true);
        $sheet->getStyle('A' . $row . ':' . $last_col . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
        $sheet->getStyle('A' . $row . ':' . $last_col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $row++;

        $sheet->setCellValue('B' . $row, 'CÓDIGO')->getStyle('B' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('C' . $row, 'DESCRIPCIÓN')->getStyle('C' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('D' . $row, 'UNIDAD')->getStyle('D' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('E' . $row, 'TOT. SALIDA')->getStyle('E' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('F' . $row, 'TOT. ENTRADA')->getStyle('F' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('G' . $row, 'TOT. UTIL.')->getStyle('G' . $row)->getFont()->setBold(true);
        if ($ver_precios) {
            $sheet->setCellValue('H' . $row, 'COSTO $')->getStyle('H' . $row)->getFont()->setBold(true);
        }

        $sheet->getStyle('B' . $row . ':' . $last_col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('B' . $row . ':' . $last_col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B' . $row . ':' . $last_col . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9E7FD');
        $row++;

        $data_resumen = ModeloInforme::mdlInformeOrdenResumen($id_orden, true);
        $total_capital_general = 0;

        foreach ($data_resumen as $fill) {
            $salida = $fill["cantidad_salida"];
            $entrada = $fill["retorno"];
            $util = $fill["utilizado"] == null || $fill["utilizado"] == '' ? $salida : $fill["utilizado"];

            $capital = floatval($fill["capital"]);
            $total_capital_general += $capital;

            $sheet->setCellValue('B' . $row, $fill["codigo"]);
            $sheet->setCellValue('C' . $row, $fill["descripcion"]);
            $sheet->setCellValue('D' . $row, $fill["unidad"]);
            $sheet->setCellValue('E' . $row, $salida);
            $sheet->setCellValue('F' . $row, $entrada);
            $sheet->setCellValue('G' . $row, $util);

            if ($ver_precios) {
                $sheet->setCellValue('H' . $row, $capital);
                $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
            }

            $sheet->getStyle('D' . $row . ':' . $last_col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row . ':' . $last_col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $row++;
        }

        if ($ver_precios) {
            $sheet->mergeCells('B' . $row . ':G' . $row);
            $sheet->setCellValue('B' . $row, 'TOTAL GENERAL DE MATERIALES:');
            $sheet->setCellValue('H' . $row, $total_capital_general);
            $sheet->getStyle('B' . $row . ':H' . $row)->getFont()->setBold(true);
            $sheet->getStyle('B' . $row . ':G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
            $sheet->getStyle('B' . $row . ':H' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('B' . $row . ':H' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('EAEAEA');
        }
    } else {
        $sheet->setCellValue('A5', 'NO SE ENCONTRARON DATOS PARA LA ORDEN.');
    }

    $file_name = 'FABRICACION ' . $datos_detalle[0]['orden_nro'] . '  ' . $datos_detalle[0]['cliente'] . '.xlsx';
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
