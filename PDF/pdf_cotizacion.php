<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!(isset($_SESSION['s_usuario']))) {
    header("Location: /aistermcon");
    exit();
}
require('../assets/plugins/fpdf/fpdf.php');
require('../models/cotizacion.modelo.php');

class PDF extends FPDF

{
    private $widths;
    private $aligns;
    private $y0;
    public $orden;
    public $cliente;
    public $currentY;


    // // Pie de página
    function __construct()
    {
        parent::__construct();
        $this->y0 = $this->GetY();
        $this->startY = $this->y0;
        // $this->SetMargins(40, 0, 40);
        // $this->id_boleta = $id_boleta;
        // $this->SetAligns(array('C', 'C', 'C', 'C'));
    }

    function CheckPageBreak($h)
    {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            // $this->SetY($this->startY); // establecer la posición actual en $startY
            return true;
        }
        return false;
    }

    // function SetStartY($y) // nuevo método
    // {
    //     $this->startY = $y;
    // }



    // function Row($data, $fontSizes, $b, $lineHeight = 10, $verticalAlignColumns = [])
    // {
    //     // Calcular el alto de la fila
    //     $nb = 0;
    //     for ($i = 0; $i < count($data); $i++) {
    //         $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
    //     }
    //     $h = $lineHeight * $nb;

    //     // Restablecer la altura de línea a la fuente actual después de calcular el alto de la fila
    //     $this->SetLineHeight($this->FontSize);

    //     // Comprobar si es la primera fila de la tabla
    //     if ($this->GetY() == $this->startY) {
    //         $this->SetY($this->GetY() + $h - $this->FontSize);
    //     }

    //     // Comprobar si la fila se ajusta a la página actual
    //     if ($this->CheckPageBreak($h))
    //         $this->Ln();

    //     // Dibujar las celdas
    //     for ($i = 0; $i < count($data); $i++) {
    //         $w = $this->widths[$i];
    //         $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
    //         $x = $this->GetX();
    //         $y = $this->GetY();
    //         $this->Rect($x, $y, $w, $h);

    //         // Verificar si se debe alinear verticalmente la columna actual
    //         $shouldVerticalAlign = isset($verticalAlignColumns[$i]) && $verticalAlignColumns[$i];

    //         // Calcular el alto real del texto
    //         $textHeight = $this->getStringHeight($w, $data[$i], $fontSizes[$i]);

    //         // Calcular la posición Y para centrar el texto verticalmente si es necesario
    //         $yPos = $y;
    //         if ($shouldVerticalAlign && $nb >= $this->NbLines($w, $data[$i])) {
    //             $yPos += (($h - $textHeight) / 2);
    //         }

    //         // Verificar si el texto coincide exactamente con el alto de una sola línea de texto
    //         $isSingleLine = abs($textHeight - $lineHeight) < 0.001;

    //         $this->SetFont('Arial', $b, $fontSizes[$i]); // Establecer el tamaño de la fuente

    //         // Alinear el texto en la parte superior si es una sola línea o si el número de líneas de la celda es igual al número de líneas del texto
    //         if ($isSingleLine || $nb == $this->NbLines($w, $data[$i])) {
    //             $this->SetXY($x, $y); // Establecer la posición Y en la parte superior
    //         } else {
    //             $this->SetXY($x, $yPos); // Establecer la posición Y centrada
    //         }

    //         $this->MultiCell($w, $lineHeight, $data[$i], 0, $a, false);

    //         $this->SetXY($x + $w, $y);
    //     }

    //     $this->Ln($h);
    // }

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

    // Método auxiliar para calcular el alto del texto
    function getStringHeight($cellWidth, $text, $fontSize)
    {
        $this->SetFont('Arial', '', $fontSize); // Usar la fuente que desees
        $width = $cellWidth - $this->cMargin * 3;
        $lines = explode("\n", $text);
        $textHeight = 0;
        foreach ($lines as $line) {
            $lineWidth = $this->GetStringWidth($line);
            $lineHeight = ceil($lineWidth / $width) * $this->FontSize;
            $textHeight += $lineHeight;
        }
        return $textHeight;
    }



    // Función auxiliar para obtener el alto real del texto en una celda
    // function getStringHeight($cellWidth, $text, $fontSize)
    // {
    //     $this->SetFont('Arial', '', $fontSize); // Usar la fuente que desees
    //     $width = $cellWidth - $this->cMargin * 2;
    //     $lines = explode("\n", $text);
    //     $textHeight = 0;
    //     foreach ($lines as $line) {
    //         $lineWidth = $this->GetStringWidth($line);
    //         $lineHeight = ceil($lineWidth / $width) * $this->FontSize;
    //         $textHeight += $lineHeight;
    //     }
    //     return $textHeight;
    // }

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


    // function CustomMultiCell($firstLineWidth, $subsequentLineWidth, $h, $text, $firstLineX, $subsequentLineX)
    // {
    //     // Split the text into lines using the improved WordWrap function
    //     $lines = $this->WordWrap($text, $firstLineWidth, $subsequentLineWidth);

    //     // Save the original Y position
    //     $originalY = $this->GetY();

    //     // Print each line
    //     foreach ($lines as $i => $line) {
    //         if ($i == 0) {
    //             // First line starts at the original X position (firstLineX)
    //             $this->SetX($firstLineX);
    //             $width = $firstLineWidth;
    //         } else {
    //             // Subsequent lines start at X position subsequentLineX with adjusted width
    //             $this->SetX($subsequentLineX);
    //             $width = $subsequentLineWidth;

    //             // Get current position
    //             $x = $this->GetX();
    //             $y = $this->GetY();

    //             // Draw the left and right fill rectangles for subsequent lines
    //             // $this->Rect($x - $leftFillWidth, $y, $leftFillWidth, $h, 'F'); // Left fill
    //             // $this->Rect($x + $width, $y, $rightFillWidth, $h, 'F'); // Right fill
    //         }

    //         // Print the text
    //         $this->MultiCell($width, $h, iconv('UTF-8', 'windows-1252', $line), 1, 'L');
    //     }

    //     // Restore Y position
    //     $this->SetY($originalY + count($lines) * $h);
    // }

    function CustomMultiCell($firstLineWidth, $subsequentLineWidth, $h, $firstLineX, $subsequentLineX, $border = true, $lines, $totalHeight)
    {
        // Split the text into lines using the improved WordWrap function
        // $lines = $this->WordWrap($text, $firstLineWidth, $subsequentLineWidth);

        $originalY = $this->GetY();

        // Calculate the total height of the content
        // $totalHeight = count($lines) * $h;
        // $totalWidth = $firstLineX  $subsequentLineX;

        // Draw the border if needed
        if ($border) {
            $this->Rect($subsequentLineX, $originalY, $subsequentLineWidth, $totalHeight);
        }

        // Print each line
        foreach ($lines as $i => $line) {
            if ($i == 0) {
                // First line starts at the original X position (firstLineX)
                $this->SetX($firstLineX);
                $width = $firstLineWidth;
            } else {
                // Subsequent lines start at X position subsequentLineX with adjusted width
                $this->SetX($subsequentLineX);
                $width = $subsequentLineWidth;
            }

            // Print the text
            $this->MultiCell($width, $h, iconv('UTF-8', 'windows-1252', $line), 0, 'L');
        }

        // Restore Y position
        $this->SetY($originalY + $totalHeight);
    }


    function WordWrap($text, $firstLineWidth, $subsequentLineWidth)
    {
        $text = trim($text);
        if ($text === '') {
            return array();
        }

        $lines = array();
        $words = explode(' ', $text);
        $currentLine = '';
        $currentWidth = $firstLineWidth;

        foreach ($words as $word) {
            $testLine = $currentLine . ($currentLine ? ' ' : '') . $word;
            $testWidth = $this->GetStringWidth($testLine) + 2.8;

            if ($testWidth <= $currentWidth) {
                $currentLine = $testLine;
            } else {
                if ($currentLine) {
                    $lines[] = $currentLine;
                    $currentWidth = $subsequentLineWidth;  // Change the width for subsequent lines
                }
                $currentLine = $word;
            }
        }

        if ($currentLine) {
            $lines[] = $currentLine;
        }

        return $lines;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['cotizacion'])) {
    $id = $_GET['id'];
    $tipo = $_GET['cotizacion'] === '1';

    $titulo = "ORDEN DE COMPRA";
    $extencion = "OC";

    if ($tipo) {
        $titulo = "SOLICITUD DE COTIZACION";
        $extencion = "SC";
    }


    // Aquí puedes usar el parámetro $id para generar contenido dinámico
    $pdf = new PDF();
    $data_cotizacion = ModeloCotizacion::mdlConsultarCotizacionPDF($id);

    if($data_cotizacion == null){
        echo 'No se encontraron datos para los parametros';
        exit;
    }
    $row = $data_cotizacion[0];
    $nro = $row['num_co'];
    $proveedor = $row['proveedor'];
    $fecha = $row['fecha'];
    $comprador = $row['comprador'];
    $direccion = $row['direccion'];
    $ruc = (!empty($row['ruc']) ? $row['ruc'] : '-');
    $telefono = $row['telefono'];
    $subtotal = $tipo ? '-' : $row["subtotal"];
    $impuesto = $tipo ? '-' : $row['impuesto'];
    $iva = $tipo ? '' : intval($row['iva']).'%';
    $total = $tipo ? '-' : $row['total'];
    $desc = $tipo ? '' :  '- '.$row['otros'];


    $archivo = $extencion .' '. $nro .' - '. $proveedor;
    $pdf->SetTitle($archivo);
    $pdf->AddFont('ArialBlack', '', 'ariblk.php'); // Nota: Usa el nombre sin extensión
    $pdf->SetMargins(22, 31.5, 22);

    $pdf->AddPage();

    

    $pdf->SetFont('ArialBlack', '', 17);
    $pdf->SetTextColor(48, 84, 150);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', $titulo), 0, 1, 'R');

    //PDF$pdfmero de página
    $pdf->SetFillColor(234, 234, 234); // Por ejemplo, un gris claro con valores R, G y B iguales
    // $pdf->Cell(0, 10, '' . $page->PageNo() . ' / {nb}', 0, 0, 'L');
    $pdf->Image('../assets/img/logo_pdf.jpeg', 20, 31.5, 73, 12);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetY(44);
    // if (!$tipo) {
    $pdf->SetFont('Arial', 'B', 9.5);
    $pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', 'RUC: 0992106034001'), 0, 1, 'L');
    // }
    $pdf->SetFont('Arial', '', 9.5);
    $pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', 'Lotización Santa Adriana Mz 16 Solar 11'), 0, 1, 'L');
    $pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', 'Mapasingue Este'), 0, 1, 'L');
    $pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', 'Guayaquil, Ecuador'), 0, 1, 'L');
    $pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', '6052288 - 3082701'), 0, 1, 'L');
    $pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', 'info@aistermcon.com'), 0, 1, 'L');
    $pdf->SetXY(153, 42.5);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 0.6, iconv('UTF-8', 'windows-1252', 'No. ' . $nro . ''), 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    // $pdf->SetXY(160,42);
    $pdf->Ln(2);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(230, 240, 255); // Color de fondo AZUL (RGB)
    $pdf->SetXY(139.7, 47.2);

    $pdf->Cell(46.6, 0, iconv('UTF-8', 'windows-1252', 'Fecha:'), 0, 1, 'C');
    $pdf->SetXY(139.7, 49.2);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(46.6, 4, iconv('UTF-8', 'windows-1252', $fecha), 0, 1, 'C', true);

    $pdf->SetXY(139.6, 44.9);
    $pdf->MultiCell(47, 8.5, " ", 1);

    $pdf->SetXY(139.7, 55.8);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(46.6, 0, iconv('UTF-8', 'windows-1252', 'Autorizado por:'), 0, 1, 'C');
    $pdf->SetXY(139.7, 57.9);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(46.6, 4, iconv('UTF-8', 'windows-1252', 'ABELARDO MUÑOZ'), 0, 1, 'C', true);

    $pdf->SetXY(139.6, 53.6);
    $pdf->MultiCell(47, 8.5, " ", 'RLB');

    $pdf->SetXY(139.7, 64.4);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(46.6, 0, iconv('UTF-8', 'windows-1252', 'Comprador:'), 0, 1, 'C');
    $pdf->SetXY(139.7, 66.6);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(46.6, 4, iconv('UTF-8', 'windows-1252', $comprador), 0, 1, 'C', true);

    $pdf->SetXY(139.6, 62.3);
    $pdf->MultiCell(47, 8.5, " ", 'RLB');

    $pdf->SetXY(23.2, 79.5);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(48, 84, 150);
    $pdf->SetMargins(22, 31.5, 23.4);

    $pdf->Cell(0, 0, iconv('UTF-8', 'windows-1252', "Proveedor:"), 0, 0, '', 0);
    $firstLineX = 44;
    $pdf->SetXY($firstLineX, 77);
    $subsequentLineX = 23.5;
    $firstLineWidth = 95.6; // Width for the first line
    $subsequentLineWidth = 95.6 + ($firstLineX - $subsequentLineX);
    $pdf->SetTextColor(0, 0, 0);

    $linesProvedor = $pdf->WordWrap($proveedor, $firstLineWidth, $subsequentLineWidth);
    $linesTelefono = $pdf->WordWrap($telefono, 31.1, 47);

    $totalHeightProve = count($linesProvedor) * 5;

    $totalHeightTelefono = count($linesTelefono) * 5;

    if ($totalHeightProve >= $totalHeightTelefono) {
        $totalHeight = $totalHeightProve;
    } else {
        $totalHeight = $totalHeightTelefono;
    }

    $pdf->CustomMultiCell($firstLineWidth, $subsequentLineWidth, 5, $firstLineX, $subsequentLineX, true, $linesProvedor, $totalHeight);

    $pdf->SetXY(139.5, 79.5);
    $pdf->SetTextColor(48, 84, 150);
    $pdf->Cell(0, 0, iconv('UTF-8', 'windows-1252', "Teléfono:"), 0, 0, '', 0);


    $firstLineX = 157;
    $pdf->SetXY($firstLineX, 77);
    // $leftFillWidth = 10; // Width of the left fill
    // $rightFillWidth = 10;
    $subsequentLineX = 139.6;
    $firstLineWidth = 31.1; // Width for the first line
    $subsequentLineWidth = 29.6 + (157 - 139.6);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetFont('Arial', '', 10);
    $pdf->CustomMultiCell($firstLineWidth, $subsequentLineWidth, 5, $firstLineX, $subsequentLineX, true, $linesTelefono, $totalHeight);


    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(48, 84, 150);
    $rowY = $pdf->GetY();

    $pdf->SetX(23.2);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', "Dirección:"), 0, 0, '', 0);    // Enviar el PDF directamente al navegador


    $firstLineX = 44;
    // $pdf->SetXY($firstLineX, 77);
    // $leftFillWidth = 10; // Width of the left fill
    // $rightFillWidth = 10;
    $subsequentLineX = 23.5;
    $firstLineWidth = 95.6; // Width for the first line
    $subsequentLineWidth = 95.6 + ($firstLineX - $subsequentLineX);
    $pdf->SetTextColor(0, 0, 0);

    $linesColum1 = $pdf->WordWrap($direccion, $firstLineWidth, $subsequentLineWidth);

    $totalHeightColumn1 = count($linesColum1) * 5;
    $totalHeight = $totalHeightColumn1;
    $pdf->SetFont('Arial', '', 10);

    $pdf->CustomMultiCell($firstLineWidth, $subsequentLineWidth, 5, $firstLineX, $subsequentLineX, true, $linesColum1, $totalHeight);



    $pdf->SetXY(139.5,  $rowY);
    $pdf->SetTextColor(48, 84, 150);
    $pdf->SetFont('Arial', 'B', 10);

    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', "Fax:"), 0, 0, '', 0);

    $pdf->CustomMultiCell(31.1, 47, 5, 157, 139.6, true, array(''), $totalHeight);


    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(48, 84, 150);
    $rowY = $pdf->GetY();
    $pdf->SetX(23.2);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', "R.U.C:"), 0, 0, '', 0);    // Enviar el PDF directamente al navegador

    // $firstLineX = 44;
    // $subsequentLineX = 23.5;
    // $firstLineWidth = 95.6; // Width for the first line
    // $subsequentLineWidth = 95.6 + ($firstLineX - $subsequentLineX);

    $pdf->SetTextColor(0, 0, 0);

    $linesColum1 = $pdf->WordWrap($ruc, 95.6, 116.1);

    $totalHeightColumn1 = count($linesColum1) * 5;
    $totalHeight = $totalHeightColumn1;
    $pdf->SetFont('Arial', '', 10);
    $pdf->CustomMultiCell(95.6, 116.1, 5, 44, 23.5, true, $linesColum1, $totalHeight);

    $pdf->SetXY(139.5,  $rowY);
    $pdf->CustomMultiCell(31.1, 47, 5, 157, 139.6, true, array(''), $totalHeight);


    $data_detalle = ModeloCotizacion::mdlConsultarCotizacion($id);
    $pdf->SetY($pdf->GetY() + 7);

    
    $pdf->SetWidths(array(25, 91.1, 20, 27));
    $pdf->SetAligns(array('L', 'L', 'C', 'C'));
    $pdf->SetAutoPageBreak(true, 35); // Habilitar salto de página automático con margen inferior
    $header = array(iconv('UTF-8', 'windows-1252', 'Cantidad'), iconv('UTF-8', 'windows-1252', 'Descripción'), 'Precio Uni', 'Precio total');
   
    $pdf->SetX($pdf->GetX() + 1.5);
    $pdf->SetMargins(23.5, 31.5, 22);

    $pdf->Row($header, array(10, 10, 10, 10), 'B', 6);
    $pdf->SetFont('Arial', '', 10, [true, true, true, true]);

    $count = 0;

    foreach ($data_detalle as $fill) {
        $precio_uni = $tipo ? '' : $fill["precio_uni"];
        $precio_final = $tipo ? '' : $fill["precio_final"];
        $pdf->Row(array(
            iconv('UTF-8', 'windows-1252', $fill["cantidad"] . ' ' . $fill["nombre"] . ' '),
            iconv('UTF-8', 'windows-1252', $fill["descripcion"]),
            iconv('UTF-8', 'windows-1252', $precio_uni),
            iconv('UTF-8', 'windows-1252', $precio_final),
        ), array(10, 10, 10, 10), '', 6, [true, true, true, true]);
        $count++;
    }

    while ($count < 10) {
        $pdf->Row(array(
            iconv('UTF-8', 'windows-1252', ''), // Columna 1 vacía
            iconv('UTF-8', 'windows-1252', ''), // Columna 2 vacía
            iconv('UTF-8', 'windows-1252', ''), // Columna 3 vacía
            iconv('UTF-8', 'windows-1252', ''), // Columna 4 vacía
        ), array(10, 10, 10, 10), '', 6, [true, true, true, true]);
    
        $count++; // Incrementa el contador
    }
        $pdf->SetX($pdf->GetX() + 120);
        $pdf->Cell(16.1, 5.5, iconv('UTF-8', 'windows-1252', "Subtotal"), 0, 0, 'R', 0);    // Enviar el PDF directamente al navegador
        // $pdf->SetX($pdf->GetX() + 2);
        $pdf->SetFillColor(255, 255, 204); // Color de fondo AZUL (RGB)
        $pdf->Cell(27, 5.5, iconv('UTF-8', 'windows-1252', $subtotal), 1, 1, 'C', 1);    // Enviar el PDF directamente al navegador
        $pdf->SetX($pdf->GetX() + 120);

        $pdf->Cell(16.1, 5, iconv('UTF-8', 'windows-1252', 'Impuestos'), 0, 0, 'R', 0);    // Enviar el PDF directamente al navegador
        $pdf->Cell(27, 5.5, iconv('UTF-8', 'windows-1252', $iva), 1, 1, 'C', 0);    // Enviar el PDF directamente al navegador

        $pdf->SetX($pdf->GetX() + 120);

        $pdf->Cell(16.1, 5, iconv('UTF-8', 'windows-1252', 'Impuesto ventas'), 0, 0, 'R', 0);    // Enviar el PDF directamente al navegador
        $pdf->Cell(27, 5.5, iconv('UTF-8', 'windows-1252', $impuesto), 1, 1, 'C', 1);    // Enviar el PDF directamente al navegador

        $pdf->SetX($pdf->GetX() + 120);

        $pdf->Cell(16.1, 5, iconv('UTF-8', 'windows-1252', 'Otros'), 0, 0, 'R', 0);    // Enviar el PDF directamente al navegador
        
        $pdf->Cell(27, 5.5, iconv('UTF-8', 'windows-1252', $desc), 1, 1, 'C', 0);    // Enviar el PDF directamente al navegador


        $pdf->SetX($pdf->GetX() + 120);
        $pdf->SetFont('Arial', 'B', 10);

        $pdf->Cell(16.1, 5, iconv('UTF-8', 'windows-1252', 'Total'), 0, 0, 'R', 0);    // Enviar el PDF directamente al navegador
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(27, 5.5, iconv('UTF-8', 'windows-1252', $total), 1, 1, 'C', 1);    // Enviar el PDF directamente al navegador
        
        $pdf->SetY($pdf->GetY() + 16);
        // $pdf->SetX($pdf->GetX() + 120);

        $pdf->Cell(80, 0, iconv('UTF-8', 'windows-1252', 'ABELARDO MUÑOZ'), 0, 1, 'C', 0);    // Enviar el PDF directamente al navegador
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 0, iconv('UTF-8', 'windows-1252', '_________________________________________'), 0, 1, 'L', 0);    // Enviar el PDF directamente al navegador
        $pdf->SetY($pdf->GetY() + 4);

        $pdf->Cell(80, 0, iconv('UTF-8', 'windows-1252', 'Firma Autorizada'), 0, 0, 'C', 0);    // Enviar el PDF directamente al navegador

        $pdf->Output('I', $archivo.'.pdf');
    exit;
} else {
    echo 'Se requieren los parametros';
}
