<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!(isset($_SESSION['s_usuario']))) {
    header("Location: /aistermcon");
    exit();
}

require('../assets/plugins/fpdf/fpdf.php');
require('../models/salidas.modelo.php');
require('../models/fabricacion.modelo.php');



$id_boleta = $_POST['id_boleta'];
// $info_error = 'NRO. DE ORDEN';
// $datos_fecha = ModeloInforme::mdlInformeDetalleOrden($id_orden);
// $datos_guias = ModeloInforme::mdlInformeFechaOrden($id_orden, true);
$data_detalle = ModeloSalidas::mdlBuscarDetalleBoleta($id_boleta);
$datos_guias = ModeloFabricacion::mdlListarProdFabAndUtil($id_boleta);
if ($data_detalle) {
    $row = $data_detalle[0];
    $orden = $row['orden'];
    $cliente = $row['cliente'];
    $nro_guia = $row['id_boleta'];
    //     $cliente = $datos_fecha[0]['cliente'];
    //     $orden = $datos_fecha[0]['orden_nro'];
    //     $descripcion = $datos_fecha[0]['descripcion'];
    $filename = $orden . ' ' . $cliente;
    $info_header =  'NRO. ORDEN: ' . $orden . "         " . 'CLIENTE: ' . $cliente;
    // $info = 'CLIENTE: ' . $cliente . "\n" . 'NRO. ORDEN: ' . $orden;
}

class PDF extends FPDF
{
    private $widths;
    private $aligns;
    private $y0;
    private $startY = 25;
    private $LineHeight; // nueva variable de clase 
    private $encabezado = true;
    // Propiedad para almacenar el ID de la boleta

    function Header()
    {
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(10, 0, 0);
        $this->Cell(0, 25, '' . $this->PageNo() . ' / {nb}', 0, 0, 'R');
        if ($this->encabezado) {
            global $info_header;
            $this->SetFont('Arial', '', 10);
            $this->SetXY(15, 13);
            $this->MultiCell(0, 0, iconv('UTF-8', 'windows-1252', $info_header), 0, 'L', 0);
        }
    }

    function __construct()
    {
        parent::__construct();
        // $this->y0 = $this->GetY();
        $this->startY = 25;
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

    function checkNewPage($pdf, $cellHeight)
    {
        $pageHeight = $pdf->GetPageHeight(); // Altura total de la página
        $bottomMargin = 20; // Ajusta esto según el margen inferior de la página
        $currentY = $pdf->GetY(); // Posición actual

        // Si al añadir la celda se pasa el margen inferior, hacer un salto de página
        if ($currentY + $cellHeight > $pageHeight - $bottomMargin) {
            $pdf->AddPage();
            $pdf->SetY(25); // Reiniciar en Y=25
        }
    }

    function StyledWriteLine($title, $content, $titleFont = [], $contentFont = [])
    {
        // Guarda estilo actual
        $savedFontFamily = $this->FontFamily;
        $savedFontStyle  = $this->FontStyle;
        $savedFontSize   = $this->FontSizePt;

        // Título con su estilo
        if (!empty($titleFont)) {
            $this->SetFont($titleFont['family'], $titleFont['style'], $titleFont['size']);
        }
        $this->Write(6, iconv('UTF-8', 'windows-1252', $title));

        // Contenido con otro estilo
        if (!empty($contentFont)) {
            $this->SetFont($contentFont['family'], $contentFont['style'], $contentFont['size']);
        }
        $this->Write(6, iconv('UTF-8', 'windows-1252', $content));

        // Restaurar fuente original
        $this->SetFont($savedFontFamily, $savedFontStyle, $savedFontSize);
    }
}

// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->SetTopMargin(50);
$pdf->desactivarEncabezado();
$pdf->SetMargins(12, 0, 12);
$pdf->AliasNbPages();

$pdf->AddPage();
if ($datos_guias == null) {
    $pdf->SetFont('Arial', '', 14);
    $pdf->SetXY(10, 20);
    $pdf->Cell(0, 20, iconv('UTF-8', 'windows-1252', 'NO SE ENCONTRARON DATOS PARA LA GUIA'), 0, 0, 'C');
    $pdf->SetTitle("Nro. Guia " . $nro_guia);
} else {
    $pdf->Ln(10);
    $pdf->Image('../assets/img/logo_pdf.jpeg', 48, null, 115, 16);
    $pdf->Ln(3);
    if ($row["responsable"] == '') {
        $encargado = 'N/A';
    } else {
        $encargado = $row["responsable"];
    }
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->checkNewPage($pdf, 6);
    $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Fecha de salida: ' . $row["fecha"]), 0, 0, 'L');
    $pdf->SetTextColor(200, 0, 0);
    $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Nro. de Guía: ' . $nro_guia), 0, 1, 'R');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->checkNewPage($pdf, 6);
    $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Fecha de entrada: ' . $row["fecha_retorno"]), 0, 1, 'L');
    $pdf->checkNewPage($pdf, 6);
    $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Conductor: ' . $row["conductor"] . ' ' . $row["placa"]), 0, 0, 'L');
    $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Responsable: ' . $encargado), 0, 1, 'R');
    $pdf->checkNewPage($pdf, 6);
    $pdf->StyledWriteLine(
        'Motivo: ',
        $row['motivo'],
        ['family' => 'Arial', 'style' => 'B', 'size' => 12],
        ['family' => 'Arial', 'style' => 'I', 'size' => 12]
    );
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->checkNewPage($pdf, 6);
    $header_fab = array('Codigo', 'Descripcion', 'Unidad', 'Cantidad Fab.');
    $header_start = true;
    $header = array('Codigo', 'Descripcion', 'Unidad', 'Usado');


    foreach ($datos_guias as $fill) {
        // $prod_fab_des = null;
        $pdf->SetStartY(20);
        $pdf->SetFont('Arial', '', 10);
        $salida = $fill["salidas"];
        $entrada = ($fill["retorno"] == null) ? $fill["salidas"] : $fill["retorno"];
        $util = $fill["utilizado"];
        $fab_pro = $fill['fabricado'];
        if ($fill['fabricado']) {
            $pdf->SetMargins(12, 0, 12);
            $pdf->SetX(12);
            $pdf->Ln(8);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Produto fabricado:'), 0, 1, 'L');
            $prod_fab_des = $fill["descripcion"];
            $pdf->SetMargins(12, 0, 12);
            $pdf->SetWidths(array(27, 113, 18, 30));
            $pdf->SetAligns(array('L', 'L', 'C', 'C'));
            $pdf->Row($header_fab, array(12, 12, 12, 12), 'B');
            $pdf->Row(array(
                iconv('UTF-8', 'windows-1252', $fill["codigo"]),
                iconv('UTF-8', 'windows-1252', $prod_fab_des),
                iconv('UTF-8', 'windows-1252', $fill["unidad"]),
                iconv('UTF-8', 'windows-1252', $entrada),
            ), array(10, 10, 10, 10), '', 7, [true, true, true, true]);
            $header_start = true;
            $pdf->Ln(3);
        } else {
            if ($header_start) {
                $pdf->SetWidths(array(27, 109, 18, 21));
                $pdf->SetAligns(array('L', 'L', 'C', 'C'));
                $pdf->SetMargins(25, 0, 12);
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->checkNewPage($pdf, 6);
                $pdf->SetX(25);
                $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Materiales usados en : ' . $prod_fab_des . ''), 0, 1, 'L');
                $pdf->Row($header, array(12, 12, 12, 12), 'B');
            }
            $pdf->Row(array(
                iconv('UTF-8', 'windows-1252', $fill["codigo"]),
                iconv('UTF-8', 'windows-1252',  $fill["descripcion"]),
                iconv('UTF-8', 'windows-1252', $fill["unidad"]),
                iconv('UTF-8', 'windows-1252', $util),
            ), array(10, 10, 10, 10), '', 7, [true, true, true, true]);
            $header_start = false;
        }
        // $pdf->Ln();
        // $pdf->SetMargins(12, 0, 12);
        // $pdf->SetX(12);
    }
    $pdf->SetMargins(12, 0, 12);
}
$pdf->Output('I', $filename);
