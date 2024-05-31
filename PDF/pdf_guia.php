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
    public $currentY;

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
            $testWidth = $this->GetStringWidth($testLine);

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


    function CustomMultiCell($firstLineWidth, $subsequentLineWidth, $h, $text, $firstLineX, $subsequentLineX, $leftFillWidth, $rightFillWidth)
    {
        // Split the text into lines using the improved WordWrap function
        $lines = $this->WordWrap($text, $firstLineWidth, $subsequentLineWidth);

        // Save the original Y position
        $originalY = $this->GetY();

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

                // Get current position
                $x = $this->GetX();
                $y = $this->GetY();

                // Draw the left and right fill rectangles for subsequent lines
                $this->Rect($x - $leftFillWidth, $y, $leftFillWidth, $h, 'F'); // Left fill
                $this->Rect($x + $width, $y, $rightFillWidth, $h, 'F'); // Right fill
            }

            // Print the text
            $this->MultiCell($width, $h, iconv('UTF-8', 'windows-1252', $line), 0, 'L', 1);
        }

        // Restore Y position
        $this->SetY($originalY + count($lines) * $h);
    }


    function Header()
    {
        $data_detalle = ModeloSalidas::mdlBuscarDetalleBoleta($this->id_boleta);
        $row = $data_detalle[0];
        $this->orden = $row['orden'];
        $this->cliente = $row['cliente'];
        // // Arial bold 15
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(10, 0, 0);
        // Número de página
        $this->SetFillColor(234, 234, 234); // Por ejemplo, un gris claro con valores R, G y B iguales
        $this->Cell(0, 10, '' . $this->PageNo() . ' / {nb}', 0, 0, 'L');
        $this->Image('../assets/img/logo_pdf.jpeg', 4.2, 9, 93.2, 16);
        $this->Ln();
        $this->SetFont('Arial', 'B', 14);
        $this->SetXY(100, 0);
        $this->Cell(0, 6, '', 0, 1, 'R', true);
        $this->SetX(41);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'GUIA DE REMISIÓN'), 0, 0, 'C');
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'No.002-001-' . $row["id_boleta"]), 0, 1, 'R');

        $this->SetFont('Arial', 'B', 11);
        $this->SetX(100);
        $this->SetFillColor(235, 235, 235); // Por ejemplo, un gris claro con valores R, G y B iguales

        $this->MultiCell(0, 12, iconv('UTF-8', 'windows-1252', "  Número de Autorización:"), 0, 'L', 1);
        $this->SetXY(100, 25);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', "  "), 0, 'L', 1);
        $this->SetFont('Arial', 'B', 11);
        $this->SetX(100);
        $this->MultiCell(0, 12, iconv('UTF-8', 'windows-1252', "  Fecha y hora de Autorización: "), 0, 'L', 1);
        $this->SetXY(100, 38);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', "  "), 0, 'L', 1);

        $this->SetFont('Arial', 'B', 11);
        $this->SetXY(100, 42);
        $this->Cell(0, 12, iconv('UTF-8', 'windows-1252', '  Ambiente:'), 0, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->SetX(120);
        $this->Cell(0, 12, iconv('UTF-8', 'windows-1252', '  PRODUCCION'), 0, 1, 'L');

        $this->SetFont('Arial', 'B', 11);
        $this->SetXY(100, 50);
        $this->Cell(0, 6, iconv('UTF-8', 'windows-1252', '  Emisión:'), 0, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->SetX(120);
        $this->Cell(0, 6, iconv('UTF-8', 'windows-1252', '  NORMAL'), 0, 1, 'L');

        $this->SetFont('Arial', 'B', 11);
        $this->SetXY(100, 56);
        $this->Cell(0, 4, iconv('UTF-8', 'windows-1252', '  Clave de Acceso:'), 0, 1, 'L', true);
        $this->SetX(100);
        $this->Cell(0, 3, iconv('UTF-8', 'windows-1252', '  '), 0, 0, 'L', true);

        $this->SetFont('Arial', 'B', 11);
        $this->SetXY(6, 30);
        $this->Cell(89, 10, iconv('UTF-8', 'windows-1252', "  Emisor:"), 0, 1, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $this->SetXY(26, 33);

        $data_config = ModeloSalidas::mdlBoletaConfig();
        $row_config = $data_config[0];
        $emisor = $row_config['emisor'];
        $ruc = $row_config['ruc'];
        $matriz = $row_config['matriz'];
        $correo1 = $row_config['correo1'];
        $telefono = $row_config['telefonos'];

        $firstLineX = 26; // Initial X position for the first line
        $subsequentLineX = 8;
        $firstLineWidth = 64; // Width for the first line
        $subsequentLineWidth = 64 + ($firstLineX - $subsequentLineX);
        $leftFillWidth = 2; // Width of the left fill
        $rightFillWidth = 5; // Width of the right fill

        $this->CustomMultiCell($firstLineWidth, $subsequentLineWidth, 4.2, $emisor, $firstLineX, $subsequentLineX, $leftFillWidth, $rightFillWidth);

        $this->SetFont('Arial', 'B', 11);
        $this->SetX(6);
        $this->Cell(89, 6, iconv('UTF-8', 'windows-1252', "  RUC:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $this->SetX(20);
        $this->Cell(0, 6, iconv('UTF-8', 'windows-1252', $ruc), 0, 1, 'L');

        $this->SetFont('Arial', 'B', 11);
        $this->SetX(6);
        $this->Cell(89, 4.5, iconv('UTF-8', 'windows-1252', "  Matriz:"), 0, 0, 'L', 1);

        $this->SetFont('Arial', '', 10);
        $firstLineX = 22; // Initial X position for the first line
        $leftFillWidth = 2; // Width of the left fill
        $rightFillWidth = 3;
        $firstLineWidth = 70; // Width for the first line
        $subsequentLineWidth = 70 + ($firstLineX - $subsequentLineX);
        $this->CustomMultiCell($firstLineWidth, $subsequentLineWidth, 4.2, $matriz, $firstLineX, $subsequentLineX, $leftFillWidth, $rightFillWidth);

        $this->SetFont('Arial', 'B', 11);
        $this->SetX(6);
        $this->Cell(89, 6, iconv('UTF-8', 'windows-1252', "  Correo:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $this->SetX(23);
        $this->Cell(0, 6, iconv('UTF-8', 'windows-1252', $correo1), 0, 1, 'L');

        $this->SetFont('Arial', 'B', 11);
        $this->SetX(6);
        $this->Cell(89, 5, iconv('UTF-8', 'windows-1252', "  Teléfono:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $this->SetX(26);
        $this->Cell(0, 5, iconv('UTF-8', 'windows-1252', $telefono), 0, 1, 'L');

        $this->SetFont('Arial', 'B', 11);
        $this->SetX(6);
        $this->Cell(89, 5, iconv('UTF-8', 'windows-1252', "  Obligado a llevar contabilidad:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $this->SetX(66);
        $this->Cell(0, 5, iconv('UTF-8', 'windows-1252', "SI"), 0, 1, 'L');

        $this->SetFont('Arial', 'B', 11);
        $this->SetX(6);
        $this->Cell(89, 5, iconv('UTF-8', 'windows-1252', "  Agente de Retención"), 0, 1, 'L', 1);
        $this->SetX(6);
        $this->Cell(89, 5, iconv('UTF-8', 'windows-1252', "  Resolución Nro. NAC-DNCRASC20-00000001"), 0, 1, 'L', 1);
        $this->SetX(6);
        $this->Cell(89, 2.5, '', 0, 0, 'L', 1);

        $this->Ln(8);
        $conductor = $row['conductor'];
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', "  Transportista:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $this->SetX(35);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', $conductor), 0, 0, 'L');

        $this->SetFont('Arial', 'B', 11);
        $this->SetX(138);
        $cedula_conductor = $row['cedula_conductor'];
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'RUC/CI:'), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->SetX(154);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', $cedula_conductor), 0, 1, 'L');


        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 3, iconv('UTF-8', 'windows-1252', "  Dirección Partida:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $this->SetX(42);
        $this->Cell(0, 3, iconv('UTF-8', 'windows-1252', 'SANTA ADRIANA'), 0, 0, 'L');


        $telefono_conductor = $row['telefono_conductor'];
        $this->SetFont('Arial', 'B', 11);
        $this->SetX(138);
        $this->Cell(0, 3, iconv('UTF-8', 'windows-1252', "Teléfono:"), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->SetX(157);
        $this->Cell(0, 3, iconv('UTF-8', 'windows-1252', $telefono_conductor), 0, 1, 'L');


        $fecha = $row['fecha'];
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', "  Fecha Inicio:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $this->SetX(33);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', $fecha), 0, 0, 'L');

        $this->SetX(55);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', "Fecha Fin:"), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->SetX(75);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', $fecha), 0, 0, 'L');

        $placa = $row['placa'];
        $this->SetFont('Arial', 'B', 11);
        $this->SetX(138);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', "Placa:"), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->SetX(150);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', $placa), 0, 1, 'L');


        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 3, iconv('UTF-8', 'windows-1252', "  Fecha Emision:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $this->SetX(38);
        $this->Cell(0, 3, iconv('UTF-8', 'windows-1252', $fecha), 0, 0, 'L');

        // $placa = $row['placa'];
        $this->SetFont('Arial', 'B', 11);
        $this->SetX(138);
        $this->Cell(0, 3, iconv('UTF-8', 'windows-1252', "Correo:"), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->SetY(120);
        $this->Cell(0, 3, '', 0, 0, '', 1);
        $this->SetXY(153, 117);
        $this->Cell(0, 3, iconv('UTF-8', 'windows-1252', $correo1), 0, 1, 'L', 1);



        $this->Ln(9);
        $cliente_des = $row['cliente'];
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', "  Destinatario:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $firstLineX = 33; // Initial X position for the first line
        $this->SetXY($firstLineX, 131.1);
        // $subsequentLineX = 6;
        $leftFillWidth = 2; // Width of the left fill
        $rightFillWidth = 7;
        $firstLineWidth = 99; // Width for the first line
        $subsequentLineWidth = 99 + ($firstLineX - $subsequentLineX);
        $this->CustomMultiCell($firstLineWidth, $subsequentLineWidth, 6, $cliente_des, $firstLineX, $subsequentLineX, $leftFillWidth, $rightFillWidth);


        $direccion_cliente = $row['direccion_cliente'];
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 6, iconv('UTF-8', 'windows-1252', "  Dirección:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $firstLineX = 29; // Initial X position for the first line
        $rightFillWidth = 72;
        $this->CustomMultiCell($firstLineWidth, $subsequentLineWidth, 6, $direccion_cliente, $firstLineX, $subsequentLineX, $leftFillWidth, $rightFillWidth);

        $this->SetX(6);
        $this->SetFont('Arial', 'B', 11);
        $motivo = $row['motivo'];
        $this->Cell(0, 6, iconv('UTF-8', 'windows-1252', '  Motivo:'), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 10);
        $firstLineX = 24;
        $rightFillWidth = 72;
        $this->CustomMultiCell($firstLineWidth, $subsequentLineWidth, 6, $motivo, $firstLineX, $subsequentLineX, $leftFillWidth, $rightFillWidth);

        $this->Cell(0, 1.9, '', 0, 1, '', 1);

        // $this->Ln(10);
        $this->SetY($this->GetY() + 6);
        $this->SetWidths(array(33, 119.5, 26, 20));
        $this->SetAligns(array('C', 'C', 'C', 'C'));
        $this->SetAutoPageBreak(true, 6); // Habilitar salto de página automático con margen inferior
        $header = array(iconv('UTF-8', 'windows-1252', 'Código'), ' Descripcion', 'Cantidad', 'Unidad');
        $this->Row($header, array(10, 10, 10, 10), 'B', 5);
        $this->currentY = $this->GetY();


        $this->SetY(129);
        $this->SetFont('Arial', 'B', 11);
        $this->SetX(138);
        $ruc = $row['ruc'];
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'RUC/CI:'), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->SetX(154);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', $ruc), 0, 1, 'L');

        $this->SetXY(138, 137);
        $this->SetFont('Arial', 'B', 11);
        $cliente_telefono = $row['cliente_telefono'];
        $this->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Teléfono:'), 0, 0, 'L', 1);

        $this->SetFont('Arial', '', 10);
        $this->SetX(156);
        $this->Cell(0, 6, iconv('UTF-8', 'windows-1252', $cliente_telefono), 0, 1, 'L');

        $this->SetX(138);
        $this->SetFont('Arial', 'B', 11);
        $orden_trabajo = $row['orden'];
        $this->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Nro. Orden:'), 0, 0, 'L', 1);

        $this->SetFont('Arial', '', 10);
        $this->SetX(160);
        $this->Cell(0, 6, iconv('UTF-8', 'windows-1252', $orden_trabajo), 0, 0, 'L');


        // $this->SetY($this->GetY() + 14);

        
}

    // // Pie de página
    function __construct($id_boleta)
    {
        parent::__construct();
        $this->y0 = $this->GetY();
        $this->startY = $this->y0;
        $this->SetMargins(6, 0, 6);
        $this->id_boleta = $id_boleta;
        $this->SetAligns(array('C', 'C', 'C', 'C'));
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

    function SetStartY($y) // nuevo método
    {
        $this->startY = $y;
    }

    function Row($data, $fontSizes, $b, $lineHeight = 10, $fillColor = [235, 235, 235], $borderColor = [255, 255, 255],  $verticalAlignColumns = [],)
    {
        // Calcular el alto de la fila
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }
        $h = $lineHeight * $nb;

        // Restablecer la altura de línea a la fuente actual después de calcular el alto de la fila
        $this->SetLineHeight($this->FontSize);

        // // Comprobar si es la primera fila de la tabla
        // if ($this->GetY() == $this->startY) {
        //     $this->SetY($this->GetY() + $h - $this->FontSize);
        // }

        // Comprobar si la fila se ajusta a la página actual
        if ($this->CheckPageBreak($h)){
            // $this->Ln();
            $this->SetY($this->GetY()+ 19);
            $this->SetAligns(array('L', 'L', 'C', 'C'));

        }
             

        // Establecer el color del borde
        $this->SetDrawColor($borderColor[0], $borderColor[1], $borderColor[2]);
        $this->SetLineWidth(1.2);

        // Establecer el color de relleno
        $this->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]);

        // Dibujar las celdas
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();

            // Dibujar el fondo de la celda
            $this->Rect($x, $y, $w, $h, 'F');

            // Dibujar el borde solo en el lado derecho de la celda
            $this->Line($x + $w, ($y + 0.57), $x + $w, $y + $h);

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

            // Cambiar el parámetro de relleno a false
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
$data_boleta = ModeloSalidas::mdlBuscarBoletaPDF($id_boleta);
$pdf->SetTitle("Nro. Guia " . $data_boleta[0]["id_boleta"]);
$pdf->SetFillColor(235, 235, 235); // Por ejemplo, un gris claro con valores R, G y B iguales

// $pdf->SetStartY($pdf->GetY()); // Establecer la posición Y inicial en 30

$total = 0;
$pdf->SetY($pdf->currentY);

foreach ($data_boleta as $fill) {
    $cantidad_salida = floatval($fill["cantidad_salida"]);
    $pdf->SetAligns(array('L', 'L', 'C', 'C'));
    $pdf->Row(array(
        iconv('UTF-8', 'windows-1252', '  ' . $fill["codigo"]),
        iconv('UTF-8', 'windows-1252', ' ' . $fill["descripcion"]),
        iconv('UTF-8', 'windows-1252', $fill["cantidad_salida"]),
        iconv('UTF-8', 'windows-1252', $fill["unidad"]),
    ), array(9, 9, 9, 9), '', 5, array(247, 247, 245));

    $total += $cantidad_salida;
   
}

$pdf->SetAligns(array('C', 'R', 'C', 'C'));

$pdf->Row(
    array('', 'Total de productos:  ', $total, ''),
    array(9, 9, 9, 9),
    '',
    5,
    array(247, 247, 245)
);

$filename =  $data_boleta[0]["id_boleta"] . '_' . $pdf->cliente . '_' . $pdf->orden . ".pdf";

$pdf->Output('I', $filename);
