<?php
require('../assets/plugins/fpdf/fpdf.php');
require('../models/salidas.modelo.php');

$id_boleta = $_POST['id_boleta'];


class PDF extends FPDF

{
    private $widths;
    private $aligns;
    private $y0;
    private $startY;
    private $LineHeight; // nueva variable de clase
    private $id_boleta; // Propiedad para almacenar el ID de la boleta
    public $orden;
    public $cliente;

    function Header()
    {
        $data_detalle = ModeloSalidas::mdlBuscarDetalleBoleta($this->id_boleta);
        $this->orden = $data_detalle[0]['orden'];
        $this->cliente = $data_detalle[0]['cliente'];
        // // Arial bold 15
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(10, 0, 0);
        // Número de página
        $this->Cell(350, 25, '' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
        $this->Image('../assets/img/logo_pdf.jpeg', 48, -5, 118, 40);
        $this->Ln();
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, -4, iconv('UTF-8', 'windows-1252', 'Lotización Santa Adriana Mz 16 Solar 11 Mapasingue Este'), 0, 1, 'C');
        $this->Cell(0, 11, iconv('UTF-8', 'windows-1252', 'Sector A Telf.: 6052288   3082701 Cel.: 099851628'), 0, 1, 'C');
        $this->Cell(0, -5, iconv('UTF-8', 'windows-1252', 'e-mails: info@aistermcon.com • www.aistermcon.com'), 0, 1, 'C');
        $this->Cell(0, 11, iconv('UTF-8', 'windows-1252', 'Guayaquil - Ecuador'), 0, 1, 'C');
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', 'GUIA DE REMISION'), 0, 1, 'C');

        $this->SetFont('Arial', 'B', 9); // Configurar la fuente en negrita
        $this->Cell(10, 10, iconv('UTF-8', 'windows-1252', 'Fecha de iniciacion del traslado: '), 0, 1, 'L');
        $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', 'Fecha de terminacion del traslado: _______________________________'), 0, 1, 'L');
        $this->SetXY(68, 43);
        $this->SetFont('Arial', '', 9); // Configurar la fuente en normal
        $this->Cell(50, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['fecha']), 0, 0, 'L'); // Cambiar a nueva línea al final
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(10, 0, iconv('UTF-8', 'windows-1252', 'Punto de partida: _______________________________'), 0, 1, 'L');
        $this->SetFont('Arial', '', 9);
        $this->SetXY(148, 43);
        $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', 'BODEGA SANTA ADRIANA'), 0, 1, 'L');
        $this->SetXY(148, 48);
        $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['fecha']), 0, 1, 'L'); // Cambiar a nueva línea al final
        $this->SetXY(68, 48);
        $this->SetFont('Arial', '', 9); // Configurar la fuente en normal
        $this->Cell(50, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['fecha']), 0, 0, 'L'); // Cambiar a nueva línea al final
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(10, 0, iconv('UTF-8', 'windows-1252', 'Fecha de emisión: ______________________________'), 0, 1, 'L');
        $this->SetFont('Arial', 'B', 11); // Fuente ampliada
        $this->SetY(53);
        $this->Cell(10, 0, iconv('UTF-8', 'windows-1252', 'Destinatario'), 0, 1, 'L');
        $this->SetFont('Arial', 'B', 9); // Fuente ampliada
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Nombre o razón social: ______________________________________________'), 0, 1, 'L');
        $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', 'RUC/C.I.: ___________________________'), 0, 1, 'L');
        $this->SetXY(48, 58);
        $this->SetFont('Arial', '', 9); // Fuente ampliada
        $this->Cell(79, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['cliente']), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 9); // Fuente ampliada
        $this->Cell(30, 0, iconv('UTF-8', 'windows-1252', 'Número de orden: _________________________'), 0, 0, 'L');
        $this->SetFont('Arial', '', 9); // Fuente ampliada
        $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['orden']), 0, 0, 'L');
        $this->SetXY(25, 63);
        $this->Cell(47, 0, iconv('UTF-8', 'windows-1252', '5'), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 9); // Fuente ampliada
        $this->Cell(32, 0, iconv('UTF-8', 'windows-1252', 'Punto de llegada: _________________________________________________________'), 0, 0, 'L');
        $this->SetFont('Arial', '', 9); // Fuente ampliada
        $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['cliente']), 0, 1, 'L');
        $this->SetY(68);
        $this->SetFont('Arial', 'B', 11); // Fuente ampliada
        $this->Cell(10, 0, iconv('UTF-8', 'windows-1252', 'Identificacion del encargado del transporte'), 0, 1, 'L');
        $this->SetFont('Arial', 'B', 9); // Fuente ampliada
        $this->Cell(110, 10, iconv('UTF-8', 'windows-1252', 'Nombre o razón social: ____________________________________________________'), 0, 0, 'L');
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'RUC/C.I.: ___________________________'), 0, 1, 'R');
        $this->SetXY(48, 73);
        $this->SetFont('Arial', '', 9); // Fuente ampliada
        $this->Cell(107, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['conductor']), 0, 0, 'L');
        $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['cedula_conductor']), 0, 0, 'L');
        $this->SetY(78);
        $this->SetFont('Arial', 'B', 11); // Fuente ampliada
        $this->Cell(10, 0, iconv('UTF-8', 'windows-1252', 'Bienes transportados'), 0, 1, 'L');
    }

    // // Pie de página
    function __construct($id_boleta)
    {
        parent::__construct();
        $this->y0 = $this->GetY();
        $this->startY = $this->y0;
        $this->SetMargins(8, 0, 8);
        $this->id_boleta = $id_boleta;
    }

    function CheckPageBreak($h)
    {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            $this->SetY($this->startY); // establecer la posición actual en $startY
            return true;
        }
        return false;
    }

    function SetStartY($y) // nuevo método
    {
        $this->startY = $y;
    }

    function Row($data, $fontSizes, $b, $lineHeight = 10, $verticalAlignColumns = [])
    {
        // Calcular el alto de la fila
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }
        $h = $lineHeight * $nb;

        // Restablecer la altura de línea a la fuente actual después de calcular el alto de la fila
        $this->SetLineHeight($this->FontSize);

        // Comprobar si es la primera fila de la tabla
        if ($this->GetY() == $this->startY) {
            $this->SetY($this->GetY() + $h - $this->FontSize);
        }

        // Comprobar si la fila se ajusta a la página actual
        if ($this->CheckPageBreak($h))
            $this->Ln();

        // Dibujar las celdas
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);

            // Verificar si se debe alinear verticalmente la columna actual
            $shouldVerticalAlign = isset($verticalAlignColumns[$i]) && $verticalAlignColumns[$i];

            // Calcular el alto real del texto
            $textHeight = $this->getStringHeight($w, $data[$i], $fontSizes[$i]);

            // Calcular la posición Y para centrar el texto verticalmente si es necesario
            $yPos = $y;
            if ($shouldVerticalAlign && $nb >= $this->NbLines($w, $data[$i])) {
                $yPos += (($h - $textHeight) / 2);
            }

            // Verificar si el texto coincide exactamente con el alto de una sola línea de texto
            $isSingleLine = abs($textHeight - $lineHeight) < 0.001;

            $this->SetFont('Arial', $b, $fontSizes[$i]); // Establecer el tamaño de la fuente

            // Alinear el texto en la parte superior si es una sola línea o si el número de líneas de la celda es igual al número de líneas del texto
            if ($isSingleLine || $nb == $this->NbLines($w, $data[$i])) {
                $this->SetXY($x, $y); // Establecer la posición Y en la parte superior
            } else {
                $this->SetXY($x, $yPos); // Establecer la posición Y centrada
            }

            $this->MultiCell($w, $lineHeight, $data[$i], 0, $a, false);

            $this->SetXY($x + $w, $y);
        }

        $this->Ln($h);
    }
    // Función auxiliar para obtener el alto real del texto en una celda
    function getStringHeight($cellWidth, $text, $fontSize)
    {
        $this->SetFont('Arial', '', $fontSize); // Usar la fuente que desees
        $width = $cellWidth - $this->cMargin * 2;
        $lines = explode("\n", $text);
        $textHeight = 0;
        foreach ($lines as $line) {
            $lineWidth = $this->GetStringWidth($line);
            $lineHeight = ceil($lineWidth / $width) * $this->FontSize;
            $textHeight += $lineHeight;
        }
        return $textHeight;
    }

    function SetLineHeight($height)
    {
        $this->LineHeight = $height;
    }

    function SetWidths($w)
    {
        // Establecer las dimensiones de cada celda
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        // Establecer la alineación de cada celda
        $this->aligns = $a;
    }

    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}



// Creación del objeto de la clase heredada
$pdf = new PDF($id_boleta);
$pdf->AliasNbPages();
$pdf->AddPage();
$data_boleta = ModeloSalidas::mdlBuscarBoleta($id_boleta);
$pdf->SetTitle("Nro. Guia " . $data_boleta[0]["id_boleta"]);
$pdf->SetY($pdf->GetY() + 4);
$pdf->SetWidths(array(30, 20, 144));
$pdf->SetAligns(array('C', 'C', 'C'));
// $pdf->SetMargins(9, 0, 8);
$pdf->SetAutoPageBreak(true, 8); // Habilitar salto de página automático con margen inferior

// $pdf->SetFont('Arial', 'B', 12);
$pdf->Row(array('Cantidad', 'Unidad', 'Descripcion'), array(12, 12, 12), 'B', 7);
$rowsPrinted = 0;
$totalRowsPrinted = 0;



foreach ($data_boleta as $fill) {
    // Verificar si se alcanzó el límite de filas por página
    if ($rowsPrinted >= 24) {
        // Si se alcanza el límite, dibujar la fila adicional y reiniciar el contador de filas
        $pdf->SetWidths(array(194));
        $pdf->SetAligns(array('C'));

        $pdf->Row(array(''. "\n" . ' RECIBIDO POR                                                                              ENTREGADO POR',), array(9), '', 16);
        $rowsPrinted = 0;

        // Agregar una nueva página si es necesario
        if ($pdf->GetY() + 40 > $pdf->GetPageHeight() - 10) {
            $pdf->AddPage();
            // Dibujar encabezado de tabla en la nueva página
            $pdf->SetY($pdf->GetY() + 4);
            $pdf->SetWidths(array(30, 20, 144));
            $pdf->SetAligns(array('C', 'C', 'C'));
            $pdf->Row(array('Cantidad', 'Unidad', 'Descripcion'), array(12, 12, 12), 'B', 7);
            $totalRowsPrinted = 0; // Reiniciar el contador total de filas impresas para la nueva página
        }
    }

    // Dibujar fila de datos
    $pdf->SetAligns(array('C', 'C', 'L'));
    $pdf->Row(array(
        iconv('UTF-8', 'windows-1252', $fill["cantidad_salida"]),
        iconv('UTF-8', 'windows-1252', $fill["unidad"]),
        iconv('UTF-8', 'windows-1252', $fill["descripcion"]),
    ), array(8, 8, 8), '', 6);

    // Incrementar el contador de filas
    $rowsPrinted++;
    $totalRowsPrinted++;
}

// Verificar si hay espacio en la página para la fila adicional
// if ($pdf->GetY() + 50 <= $pdf->GetPageHeight() - 10) {
//     $pdf->SetWidths(array(195));
//     $pdf->Row(array('' . "\n" . ' RECIBIDO POR                                                                              ENTREGADO POR',), array(9), '', 10);
// } else {
//     // Si no hay espacio, agregar una nueva página y dibujar la fila adicional
//     $pdf->AddPage();
//     $pdf->SetY($pdf->GetY() + 40);
//     $pdf->SetWidths(array(195));
//     $pdf->Row(array('' . "\n" . ' RECIBIDO POR                                                                              ENTREGADO POR',), array(9), '', 10);
// }

//Agregar filas en blanco si es necesario para completar 24 filas en la página final
$rowsPerPage = 24;
$remainingRows = $rowsPerPage - $totalRowsPrinted % $rowsPerPage;
if ($remainingRows > 0) {
    $blankData = array('', '', ''); // Datos en blanco para las columnas
    for ($i = 0; $i < $remainingRows; $i++) {
        $pdf->Row($blankData, array(8, 8, 8), '', 6);
    }
    $pdf->SetWidths(array(194));
    $pdf->SetAligns(array('C'));

    $pdf->Row(array(''. "\n" . ' RECIBIDO POR                                                                              ENTREGADO POR',), array(9), '', 17);
    $rowsPrinted = 0;
}

// $rowsPerPage = 24;
// $remainingRows = $rowsPerPage - $rowsPrinted;
// if ($remainingRows > 0) {
//     $blankData = array('', '', ''); // Datos en blanco para las columnas
//     for ($i = 0; $i < $remainingRows; $i++) {
//         $pdf->Row($blankData, array(8, 8, 8), '', 6);
//     }
// }


// $validacionGrafico = InformeModelo::mdlGenerarGrafico($fechaIni, $fechaFin);
// if ($validacionGrafico == null) {
//     $pdf->Cell(0, 20, iconv('UTF-8', 'windows-1252', 'NO SE ENCONTRARON DATOS EN EL RANGO DE FECHAS'), 0, 0, 'C');
// } else {
// Logo
//$pdf->Image('../assets/img/logoTesHeader.png', 30, 25, 150);

// $pdf->Ln(80);
// $pdf->SetFont('Arial', 'B', 22);
// // $pdf->Cell(0, 10, 'INFORME DEL ' . $dia_d . ' AL ' . $dia_h . ' DE ' . $mes_h . ' DE ' . $ano_h . '', 0, 0, 'C');
// $pdf->Ln();
// $pdf->SetFont('Arial', 'B', 20);
// $pdf->Cell(0, 30, iconv('UTF-8', 'windows-1252', 'CONTRALORÍA ACADÉMICA'), 0, 0, 'C');
// $pdf->Ln();
// $pdf->SetFont('Arial', 'B', 16);
// $pdf->SetFont('Arial', 'B', 12);
// /************************************************
//         CELDA ELABORADO POR
//  *************************************************/
// $pdf->SetXY(20, 210);
// $pdf->MultiCell(86, 10, iconv('UTF-8', 'windows-1252', 'Elaborado por:' . "\n\n" . ' ' . "\n" . ' '), 1, 'L', 0);
// $pdf->SetXY(20, 210);
// // $pdf->Cell(0, 40, iconv('UTF-8', 'windows-1252', $config[0]['elaboradopor']));
// $pdf->SetXY(20, 210);
// // $pdf->Cell(0, 50, iconv('UTF-8', 'windows-1252', $config[0]['cargoelaborado']));

// /************************************************
//         CELDA APROBADO POR
//  *************************************************/
// $pdf->SetXY(106, 210);
// $pdf->MultiCell(86, 10, iconv('UTF-8', 'windows-1252', 'Aprobado por:' . "\n\n" . ' ' . "\n" . ' '), 1, 'L', 0);
// $pdf->SetXY(106, 210);
// // $pdf->Cell(0, 40, iconv('UTF-8', 'windows-1252', $config[0]['aprobadopor']));
// $pdf->SetXY(106, 210);
// // $pdf->Cell(0, 50, iconv('UTF-8', 'windows-1252', $config[0]['cargoaprobado']));
// $pdf->AddPage();
// $pdf->SetAutoPageBreak(true, 50);
// // $dataDireccion = InformeModelo::mdlDireccion($fechaIni, $fechaFin);
// $pdf->SetY(25);
// $pdf->SetFont('Arial', 'B', 19);
// // $pdf->Cell(0, 0, iconv('UTF-8', 'windows-1252', 'Desarrollo:'), 0, 0, 'L');
// $pdf->Ln();
// // $pdf->Cell(0, 20, iconv('UTF-8', 'windows-1252', '        - Revisión de sílabos y micros del  periodo ' . $ano_h . ''), 0, 0, 'L');
// $pdf->SetWidths(array(40, 40, 40, 30, 35));
// $pdf->SetAligns(array('L', 'L', 'L', 'C', 'C'));
// $pdf->SetY(40);
// foreach ($dataDireccion as $row) {
//     $pdf->SetFont('Arial', 'B', 18);
//     $pdf->Cell(0, 20, iconv('UTF-8', 'windows-1252', $row["direccion"]), 0, 0, 'C');
//     $pdf->Ln();
//     $id_direccion = $row["id_direccion"];

//     $pdf->SetFont('Arial', 'B', 11);

//     // Definir el encabezado de la tabla
//     $silabos = 0;
//     $micros = 0;
//     $header = array('Carrera', 'Asignatura', 'Profesor', 'Documento', 'Fecha revision');
//     $pdf->Row($header, array(12, 12, 12, 12, 12), 'B');
//     if($id_direccion==12){
//         // $data = InformeModelo::mdlListarRangoFechaCapel($fechaIni, $fechaFin);
//     } else{
//         // $data = InformeModelo::mdlListarRangoFecha($fechaIni, $fechaFin, $id_direccion);
//     }
//     // $dataGrafico = InformeModelo::mdlGenerarGrafico($fechaIni, $fechaFin);
//     foreach ($data as $fill) {
//         $pdf->SetStartY(20);
//         $pdf->SetFont('Arial', '', 6);
//         $resaño = substr($fill["year"], -2);

//         $pdf->Row(array(
//             iconv('UTF-8', 'windows-1252', $fill["carreras"]),
//             iconv('UTF-8', 'windows-1252', $fill["nombre_materia"]),
//             iconv('UTF-8', 'windows-1252', $fill["profesor"]),
//             iconv('UTF-8', 'windows-1252', $fill["documento"] . " " . $fill["semestre_modulo"] . "-" . $resaño),
//             iconv('UTF-8', 'windows-1252', $fill["fecha_entrega"])
//         ), array(6, 6, 6, 8, 9), '', 5,[true, true, true,true,true] );
//         if ($fill["documento"] == "SILABO") {
//             $silabos++;
//         } else {
//             $micros++;
//         }
//     }
//     if ($micros > 0) {
//         $micros =  $micros . " Micros";
//     } else {
//         $micros = '';
//     }
//     $pdf->Row(array(
//         '', '', '',
//         iconv('UTF-8', 'windows-1252', $silabos . " Silabos\n" . $micros),
//         ''
//     ), array(6, 6, 6, 9, 6), 'B', 4);
// // }
// $pdf->Ln(5);

// $pdf->Ln(10);
// $pdf->SetFont('Arial', 'B', 14);
// $pdf->SetX(20);
// // $pdf->Cell(0, 0, iconv('UTF-8', 'windows-1252', 'Silabos y Micros por carrera '));
// $pdf->SetX(0);
// $pdf->image($pic, 20, $pdf->GetY() + 10, 170, 80, "png");


// $pdf->SetX(20);
// $pdf->Row(array('Direccion', 'Silabo', 'Micros'), array(12, 12, 12), 'B');
// Initialize variables to keep track of the total sum
$totalSilabos = 0;
$totalMicros = 0;
// foreach ($dataGrafico as $fill) {
//     $totalMicros += $fill["micros"];
//     $totalSilabos += $fill["silabos"];
//     $pdf->SetX(20);
//     $pdf->SetFont('Arial', '', 7);
//     $pdf->Row(array(
//         iconv('UTF-8', 'windows-1252', $fill["direccion"]),
//         iconv('UTF-8', 'windows-1252', $fill["silabos"]),
//         iconv('UTF-8', 'windows-1252', $fill["micros"]),
//     ), array(12, 12, 12), '', 8);
// }
// $pdf->Row(array('TOTAL', $totalSilabos, $totalMicros), array(12, 12, 12), 'B');
// }

// Definir el nombre predeterminado del archivo PDF
$filename =  $data_boleta[0]["id_boleta"] .'_'.$pdf->cliente. '_'.$pdf->orden. ".pdf";

// Generar el PDF y almacenarlo temporalmente
$pdf->Output('I', $filename);

// // Comprobar si es un dispositivo móvil
// function isMobileDevice() {
//     return preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $_SERVER['HTTP_USER_AGENT']);
// }

// // Cambiar el nombre del archivo si es un dispositivo móvil
// if (isMobileDevice()) {
//     $newFilename = "nuevo_nombre.pdf";
//     rename($filename, $newFilename);
//     $filename = $newFilename;
// }

// // Enviar encabezados de acuerdo al dispositivo
// if (isMobileDevice()) {
//     // Descarga directa en dispositivos móviles
//     header('Content-Disposition: attachment; filename="' . $filename . '"');
//     readfile($filename);
// } else {
//     // Visualización en el navegador en computadoras
//     header('Content-type: application/pdf');
//     header('Content-Disposition: inline; filename="' . $filename . '"');
//     readfile($filename);
// }

// // Eliminar el archivo temporal
// unlink($filename);
?>
