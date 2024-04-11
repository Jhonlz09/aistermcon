<?php
require('../assets/plugins/fpdf/fpdf.php');
require('../models/informe.modelo.php');

$tab = $_POST['tabs'];

if ($tab == '1') {
    $id_orden = $_POST['orden'];
    $datos_fecha = ModeloInforme::mdlInformeFechaOrden($id_orden);
    if ($datos_fecha != null) {
        $cliente = $datos_fecha[0]['cliente'];
        $orden = $datos_fecha[0]['orden_nro'];
        $info_header = 'NRO. ORDEN: ' . $orden . "         " . 'CLIENTE: ' . $cliente;
        $info = 'CLIENTE: ' . $cliente . "\n" . 'NRO. ORDEN: ' . $orden;
    }

    // $info_2 =  'NRO. ORDEN: '.$datos_fecha[0]['orden_nro'];
} else if ($tab == '2') {
} else if ($tab == '3') {
} else {
}

class PDF extends FPDF

{
    private $widths;
    private $aligns;
    private $y0;
    private $startY;
    private $LineHeight; // nueva variable de clase 
    private $encabezado = true;
    // Propiedad para almacenar el ID de la boleta

    // function Header()
    // {
    //     $this->SetFont('Arial', '', 11);
    //     $this->SetTextColor(10, 0, 0);
    //     // Número de página
    //     $this->Cell(350, 25, '' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
    //     $this->Image('../assets/img/logo_pdf.jpeg', 48, -5, 118, 40);
    //     $this->Ln();
    //     $this->SetFont('Arial', '', 8);
    //     $this->Cell(0, -4, iconv('UTF-8', 'windows-1252', 'Lotización Santa Adriana Mz 16 Solar 11 Mapasingue Este'), 0, 1, 'C');
    //     $this->Cell(0, 11, iconv('UTF-8', 'windows-1252', 'Sector A Telf.: 6052288   3082701 Cel.: 099851628'), 0, 1, 'C');
    //     $this->Cell(0, -5, iconv('UTF-8', 'windows-1252', 'e-mails: info@aistermcon.com • www.aistermcon.com'), 0, 1, 'C');
    //     $this->Cell(0, 11, iconv('UTF-8', 'windows-1252', 'Guayaquil - Ecuador'), 0, 1, 'C');
    //     $this->SetFont('Arial', 'B', 14);
    //     $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', 'GUIA DE REMISION'), 0, 1, 'C');

    //     $this->SetFont('Arial', 'B', 9); // Configurar la fuente en negrita
    //     $this->Cell(10, 10, iconv('UTF-8', 'windows-1252', 'Fecha de iniciacion del traslado: '), 0, 1, 'L');
    //     $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', 'Fecha de terminacion del traslado: _______________________________'), 0, 1, 'L');
    //     $this->SetXY(68, 43);
    //     $this->SetFont('Arial', '', 9); // Configurar la fuente en normal
    //     $this->Cell(50, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['fecha']), 0, 0, 'L'); // Cambiar a nueva línea al final
    //     $this->SetFont('Arial', 'B', 9);
    //     $this->Cell(10, 0, iconv('UTF-8', 'windows-1252', 'Punto de partida: _______________________________'), 0, 1, 'L');
    //     $this->SetFont('Arial', '', 9);
    //     $this->SetXY(148, 43);
    //     $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', 'BODEGA SANTA ADRIANA'), 0, 1, 'L');
    //     $this->SetXY(148, 48);
    //     $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['fecha']), 0, 1, 'L'); // Cambiar a nueva línea al final
    //     $this->SetXY(68, 48);
    //     $this->SetFont('Arial', '', 9); // Configurar la fuente en normal
    //     $this->Cell(50, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['fecha']), 0, 0, 'L'); // Cambiar a nueva línea al final
    //     $this->SetFont('Arial', 'B', 9);
    //     $this->Cell(10, 0, iconv('UTF-8', 'windows-1252', 'Fecha de emisión: ______________________________'), 0, 1, 'L');
    //     $this->SetFont('Arial', 'B', 11); // Fuente ampliada
    //     $this->SetY(53);
    //     $this->Cell(10, 0, iconv('UTF-8', 'windows-1252', 'Destinatario'), 0, 1, 'L');
    //     $this->SetFont('Arial', 'B', 9); // Fuente ampliada
    //     $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Nombre o razón social: ______________________________________________'), 0, 1, 'L');
    //     $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', 'RUC/C.I.: ___________________________'), 0, 1, 'L');
    //     $this->SetXY(48, 58);
    //     $this->SetFont('Arial', '', 9); // Fuente ampliada
    //     $this->Cell(79, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['cliente']), 0, 0, 'L');
    //     $this->SetFont('Arial', 'B', 9); // Fuente ampliada
    //     $this->Cell(30, 0, iconv('UTF-8', 'windows-1252', 'Número de orden: _________________________'), 0, 0, 'L');
    //     $this->SetFont('Arial', '', 9); // Fuente ampliada
    //     $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['orden']), 0, 0, 'L');
    //     $this->SetXY(25, 63);
    //     $this->Cell(47, 0, iconv('UTF-8', 'windows-1252', '5'), 0, 0, 'L');
    //     $this->SetFont('Arial', 'B', 9); // Fuente ampliada
    //     $this->Cell(32, 0, iconv('UTF-8', 'windows-1252', 'Punto de llegada: _________________________________________________________'), 0, 0, 'L');
    //     $this->SetFont('Arial', '', 9); // Fuente ampliada
    //     $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['cliente']), 0, 1, 'L');
    //     $this->SetY(68);
    //     $this->SetFont('Arial', 'B', 11); // Fuente ampliada
    //     $this->Cell(10, 0, iconv('UTF-8', 'windows-1252', 'Identificacion del encargado del transporte'), 0, 1, 'L');
    //     $this->SetFont('Arial', 'B', 9); // Fuente ampliada
    //     $this->Cell(110, 10, iconv('UTF-8', 'windows-1252', 'Nombre o razón social: ____________________________________________________'), 0, 0, 'L');
    //     $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'RUC/C.I.: ___________________________'), 0, 1, 'R');
    //     $this->SetXY(48, 73);
    //     $this->SetFont('Arial', '', 9); // Fuente ampliada
    //     $this->Cell(107, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['conductor']), 0, 0, 'L');
    //     $this->Cell(0, 0, iconv('UTF-8', 'windows-1252', $data_detalle[0]['cedula_conductor']), 0, 0, 'L');
    //     $this->SetY(78);
    //     $this->SetFont('Arial', 'B', 11); // Fuente ampliada
    //     $this->Cell(10, 0, iconv('UTF-8', 'windows-1252', 'Bienes transportados'), 0, 1, 'L');
    // }

    function Header()
    {

        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(10, 0, 0);
        // Número de página
        $this->Cell(350, 25, '' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
        if ($this->encabezado) {

            global $info_header;
            // // Arial bold 15

            // Movernos a la derecha
            // $this->Cell(80);
            $this->SetFont('Arial', '', 10);
            $this->SetXY(15, 13);
            $this->MultiCell(0, 0, iconv('UTF-8', 'windows-1252', $info_header), 0, 'L', 0);
        }
    }

    function __construct()
    {
        parent::__construct();
        $this->y0 = $this->GetY();
        $this->startY = $this->y0;
        $this->SetMargins(8, 0, 8);
    }

    function desactivarEncabezado()
    {
        $this->encabezado = false;
    }

    function activarEncabezado()
    {
        $this->encabezado = true;
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
$pdf = new PDF();
$pdf->desactivarEncabezado();
$pdf->AliasNbPages();
$pdf->AddPage();
if ($datos_fecha == null) {
    $pdf->SetFont('Arial', '', 14);
    $pdf->SetXY(10, 20);
    $pdf->Cell(0, 20, iconv('UTF-8', 'windows-1252', 'NO SE ENCONTRARON DATOS PARA EL NRO. DE ORDEN'), 0, 0, 'C');
    $pdf->SetTitle("Error", true);
} else {
    $pdf->SetTitle("Nro. Orden " . $datos_fecha[0]['orden_nro']);
    $pdf->Ln(50);
    $pdf->Image('../assets/img/logo_pdf.jpeg', 42, null, 128, 45);
    $pdf->SetFont('Arial', 'B', 22);
    $pdf->MultiCell(0, 10, 'INFORME DE MOVIMIENTOS DE HERRAMIENTAS' . "\n" . 'Y MATERIALES', 0, 'C', 0);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Ln();
    $pdf->SetX(20);
    $pdf->MultiCell(0, 10, '' . $info . '', 0, 'L', 0);
    $pdf->Ln();
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 12);
    /************************************************
    CELDA ELABORADO POR
     *************************************************/
    $pdf->SetXY(20, 210);
    $pdf->MultiCell(86, 10, iconv('UTF-8', 'windows-1252', 'Elaborado por:' . "\n\n" . ' ' . "\n" . ' '), 1, 'L', 0);
    $pdf->SetXY(20, 210);
    /************************************************
    CELDA APROBADO POR
     *************************************************/
    $pdf->SetXY(106, 210);
    $pdf->MultiCell(86, 10, iconv('UTF-8', 'windows-1252', 'Aprobado por:' . "\n\n" . ' ' . "\n" . ' '), 1, 'L', 0);
    $pdf->SetXY(106, 210);
    $pdf->activarEncabezado();
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(true, 50);
    $pdf->SetY(25);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->SetMargins(15, 0, 15);
    $pdf->SetWidths(array(25, 95, 20, 20, 20));
    $pdf->SetAligns(array('L', 'L', 'C', 'C', 'C'));
    $pdf->SetX(15);
    foreach ($datos_fecha as $row) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Fecha de inicio: ' . $row["fecha_emision"]), 0, 1, 'L');
        $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Fecha de retorno: ' . $row["fecha_retorno"]), 0, 0, 'L');
        $pdf->Ln(10);
        $id_fecha = $row["fecha_retorno"];
        $pdf->SetFont('Arial', 'B', 11);
        $header = array('Codigo', 'Descripcion', 'Unidad', 'Salida', 'Entrada');
        $pdf->Row($header, array(12, 12, 12, 12, 12), 'B');
        $data = ModeloInforme::mdlInformeOrden($id_orden, $id_fecha);
        foreach ($data as $fill) {
            $pdf->SetStartY(20);
            $pdf->SetFont('Arial', '', 10);
            // $resaño = substr($fill["year"], -2);
            $pdf->Row(array(
                iconv('UTF-8', 'windows-1252', $fill["codigo"]),
                iconv('UTF-8', 'windows-1252', $fill["descripcion"]),
                iconv('UTF-8', 'windows-1252', $fill["unidad"]),
                iconv('UTF-8', 'windows-1252', $fill["cantidad_salida"]),
                iconv('UTF-8', 'windows-1252', $fill["retorno"])
            ), array(10, 10, 10, 10, 10), '', 7, [true, true, true, true, true]);
        }
        $pdf->Ln();
    }
}
// Configurar cabeceras para indicar el tipo de contenido y el nombre de descarga

$pdf->Output();



// $pdf->SetFont('Arial', 'B', 12);
// $pdf->Row(array('Cantidad', 'Unidad', 'Descripcion'), array(12, 12, 12), 'B', 7);
// $rowsPrinted = 0;
// $totalRowsPrinted = 0;

// foreach ($data_boleta as $fill) {
//     // Verificar si se alcanzó el límite de filas por página
//     if ($rowsPrinted >= 24) {
//         // Si se alcanza el límite, dibujar la fila adicional y reiniciar el contador de filas
//         $pdf->SetWidths(array(194));
//         $pdf->SetAligns(array('C'));

//         $pdf->Row(array(''. "\n" . ' RECIBIDO POR                                                                              ENTREGADO POR',), array(9), '', 16);
//         $rowsPrinted = 0;

//         // Agregar una nueva página si es necesario
//         if ($pdf->GetY() + 40 > $pdf->GetPageHeight() - 10) {
//             $pdf->AddPage();
//             // Dibujar encabezado de tabla en la nueva página
//             $pdf->SetY($pdf->GetY() + 4);
//             $pdf->SetWidths(array(30, 20, 144));
//             $pdf->SetAligns(array('C', 'C', 'C'));
//             $pdf->Row(array('Cantidad', 'Unidad', 'Descripcion'), array(12, 12, 12), 'B', 7);
//             $totalRowsPrinted = 0; // Reiniciar el contador total de filas impresas para la nueva página
//         }
//     }

//     // Dibujar fila de datos
//     $pdf->SetAligns(array('C', 'C', 'L'));
//     $pdf->Row(array(
//         iconv('UTF-8', 'windows-1252', $fill["cantidad_salida"]),
//         iconv('UTF-8', 'windows-1252', $fill["unidad"]),
//         iconv('UTF-8', 'windows-1252', $fill["descripcion"]),
//     ), array(8, 8, 8), '', 6);

//     // Incrementar el contador de filas
//     $rowsPrinted++;
//     $totalRowsPrinted++;
// }