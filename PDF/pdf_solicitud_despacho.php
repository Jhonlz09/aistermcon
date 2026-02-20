<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!(isset($_SESSION['s_usuario']))) {
    header("Location: /aistermcon");
    exit();
}
require('../assets/plugins/fpdf/fpdf.php');
require('../models/solicitud_mh.modelo.php');

class PDF extends FPDF
{
    private $widths;
    private $aligns;
    private $y0;
    public $startY;
    public $currentY;

    function __construct()
    {
        parent::__construct();
        $this->y0 = $this->GetY();
        $this->startY = $this->y0;
    }

    function CheckPageBreak($h)
    {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            return true;
        }
        return false;
    }

    function Row($data, $fontSizes, $b, $lineHeight = 10, $verticalAlignColumns = [])
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }
        $h = $lineHeight * $nb;

        $this->SetLineHeight($this->FontSize);

        if ($this->GetY() == $this->startY) {
            $this->SetY($this->GetY() + $h - $this->FontSize);
        }

        if ($this->CheckPageBreak($h))
            $this->Ln();

        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);

            $shouldVerticalAlign = isset($verticalAlignColumns[$i]) && $verticalAlignColumns[$i];
            $textHeight = $this->getStringHeight($w, $data[$i], $fontSizes[$i]);

            $yPos = $y;
            if ($shouldVerticalAlign && $nb >= $this->NbLines($w, $data[$i])) {
                $yPos += (($h - $textHeight) / 2);
            }

            $isSingleLine = abs($textHeight - $lineHeight) < 0.001;

            $this->SetFont('Arial', $b, $fontSizes[$i]);

            if ($isSingleLine || $nb == $this->NbLines($w, $data[$i])) {
                $this->SetXY($x, $y);
            } else {
                $this->SetXY($x, $yPos);
            }

            $this->MultiCell($w, $lineHeight, $data[$i], 0, $a, false);
            $this->SetXY($x + $w, $y);
        }

        $this->Ln($h);
    }

    function getStringHeight($cellWidth, $text, $fontSize)
    {
        $this->SetFont('Arial', '', $fontSize);
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

    function SetLineHeight($height)
    {
        $this->LineHeight = $height;
    }

    function SetWidths($w)
    {
        $this->widths = $w;
    }

    function SetAligns($a)
    {
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

function generarHojaDespacho($pdf, $nro, $orden, $cliente, $dia, $mes, $ano, $observaciones, $items, $titulo_tipo) {
    if (empty($items)) return;

    $pdf->AddPage();

    // =============== HEADER ===============
    // LOGO CENTRADO
    $image_width = 85; // A bit larger and centered
    $x_logo = (210 - $image_width) / 2;
    $pdf->Image('../assets/img/logo_pdf.jpeg', $x_logo, 10, $image_width);
    
    $pdf->SetY(30);
    // Solicitud title
    $pdf->SetFont('ArialBlack', '', 11);
    $pdf->SetTextColor(0, 0, 0); // Black

    $titulo = 'SOLICITUD DE ' . $titulo_tipo . ' / INGRESO A BODEGA';
    $pdf->Cell(130, 6, iconv('UTF-8', 'windows-1252', $titulo), 0, 0, 'L');
    
    $pdf->SetTextColor(255, 0, 0); // Red number
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 6, iconv('UTF-8', 'windows-1252', 'Nro. ' . $nro), 0, 1, 'R');
    $pdf->SetTextColor(0, 0, 0); // Black

    // =============== FORM FIELDS SECTION ===============
    $yFields = $pdf->GetY() + 2;
    $pdf->SetY($yFields);
    
    $pdf->SetFont('Arial', '', 7);
    
    // Table width = 180 (Margin 15 + 180 + Right Margin 15 = 210)
    
    // ROW 1: ORDEN, FECHA (DIA, MES, AÑO headers)
    $pdf->Cell(120, 5, iconv('UTF-8', 'windows-1252', ' ORDEN DE TRABAJO No.: ' . $orden), 1, 0, 'L');
    $pdf->Cell(30, 5, iconv('UTF-8', 'windows-1252', 'FECHA DE SOLICITUD'), 1, 0, 'C');
    $pdf->Cell(10, 5, 'DIA', 1, 0, 'C');
    $pdf->Cell(10, 5, 'MES', 1, 0, 'C');
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', 'AÑO'), 1, 1, 'C');

    // ROW 2: CLIENTE, FECHA RETORNO (Values DIA, MES, AÑO for FECHA DE SOLICITUD)
    $pdf->Cell(120, 5, iconv('UTF-8', 'windows-1252', ' CLIENTE: ' . $cliente), 1, 0, 'L');
    $pdf->Cell(30, 5, iconv('UTF-8', 'windows-1252', 'FECHA RETORNO:'), 1, 0, 'C');
    $pdf->Cell(10, 5, $dia, 1, 0, 'C');
    $pdf->Cell(10, 5, $mes, 1, 0, 'C');
    $pdf->Cell(10, 5, $ano, 1, 1, 'C');

    // ROW 3: TIPO DE TRABAJO
    $pdf->Cell(180, 5, iconv('UTF-8', 'windows-1252', ' TIPO DE TRABAJO: '), 1, 1, 'L');
    
    $pdf->Ln(2);

    // =============== TABLE SECTION ===============
    $pdf->SetWidths(array(9, 21, 14, 14, 82, 20, 20)); // Total 180
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'L', 'C', 'C'));
    
    // Configura el salto automático a una distancia segura para el footer
    $altura_bloque_footer = 32;
    $pdf->SetAutoPageBreak(true, $altura_bloque_footer + 15); 
    
    $pdf->SetFont('Arial', 'B', 6.5);
    $header = array(
        'No.', 
        iconv('UTF-8', 'windows-1252', 'CÓDIGO'), 
        'CANT.', 
        'UNIDAD', 
        iconv('UTF-8', 'windows-1252', 'DESCRIPCIÓN DEL MATERIAL'), 
        sprintf("SALIDA DE\nBODEGA"), 
        sprintf("INGRESO A\nBODEGA")
    );
    
    // Height 3 for header block sizes
    $pdf->Row($header, array(6.5, 6.5, 6.5, 6.5, 6.5, 6, 6), 'B', 3.5, [true, true, true, true, true, true, true]);

    $count = 1;
    $total_items = 0;
    
    $pdf->SetFont('Arial', '', 7);
    foreach ($items as $fill) {
        $pdf->Row(array(
            $count,
            iconv('UTF-8', 'windows-1252', $fill["codigo"]),
            iconv('UTF-8', 'windows-1252', rtrim(rtrim((string)$fill["cant_sol"], '0'), '.')), // Remover trailing zeros de la BD
            iconv('UTF-8', 'windows-1252', $fill["unidad"]),
            iconv('UTF-8', 'windows-1252', $fill["descripcion"]),
            iconv('UTF-8', 'windows-1252', rtrim(rtrim((string)$fill["cant_apro"], '0'), '.')), // Salida 
            '', // Ingreso 
        ), array(7, 7, 7, 7, 7, 7, 7), '', 5.5, [true, true, true, true, true, true, true]);
        $count++;
        $total_items++;
    }

    // =========================================================================
    // --- LÓGICA DE FILAS VACÍAS ---
    // =========================================================================

    $altura_fila_vacia = 5.5;     

    // Llenamos hasta exactamente 27 items (como el talonario físico)
    while ($count <= 27) {
        $pdf->Row(array(
            $count, 
            '', 
            '', 
            '', 
            '', 
            '', 
            ''
        ), array(7, 7, 7, 7, 7, 7, 7), '', $altura_fila_vacia, [true, true, true, true, true, true, true]);
    
        $count++; 
        $total_items++; 
    }
    
    // =============== FOOTER SECTION ===============
    
    $xCurrent = $pdf->GetX();
    $yCurrent = $pdf->GetY();
    
    // Main boundary box over Observaciones and the rest
    $pdf->Rect($xCurrent, $yCurrent, 180, 22);
    
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetXY($xCurrent + 2, $yCurrent + 2);
    $pdf->Cell(35, 4, 'OBSERVACIONES:', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 7);
    // Move to right for manual text
    $pdf->SetXY($xCurrent + 35, $yCurrent + 2);
    $pdf->MultiCell(140, 4, iconv('UTF-8', 'windows-1252', $observaciones), 0, 'L');
    
    // Signatures lines
    $pdf->SetY($yCurrent + 14);
    $pdf->Cell(90, 4, '_______________________________', 0, 0, 'C');
    $pdf->Cell(90, 4, '_______________________________', 0, 1, 'C');
    
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(90, 4, 'RESPONSABLE PEDIDO', 0, 0, 'C');
    $pdf->Cell(90, 4, 'RESPONSABLE BODEGA', 0, 1, 'C');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $pdf = new PDF();
    $pdf->AddFont('ArialBlack', '', 'ariblk.php');
    $pdf->SetMargins(15, 10, 15); // Adjust top margin

    $data_solicitud = ModeloSolicitudDespacho::mdlConsultarSolicitud($id);

    if($data_solicitud == null || isset($data_solicitud['status']) && $data_solicitud['status'] == 'error'){
        echo 'No se encontraron datos para los parametros enviados';
        exit;
    }
    
    $nro = $data_solicitud['num_sol'];
    $fecha_sol = $data_solicitud['fecha']; // YYYY-MM-DD
    
    $fecha_parts = explode('-', $fecha_sol);
    $dia = isset($fecha_parts[2]) ? $fecha_parts[2] : '';
    $mes = isset($fecha_parts[1]) ? $fecha_parts[1] : '';
    $ano = isset($fecha_parts[0]) ? $fecha_parts[0] : '';

    $orden = isset($data_solicitud['orden']) ? $data_solicitud['orden'] : '';
    $cliente = isset($data_solicitud['cliente']) ? $data_solicitud['cliente'] : '';

    $observaciones = $data_solicitud['notas'];

    $archivo = 'SOLICITUD-DESPACHO-' . $nro;
    $pdf->SetTitle($archivo);

    // Separar items por categoria
    $data_detalle = ModeloSolicitudDespacho::mdlConsultarDetalleSolicitud($id);
    
    $materiales = [];
    $herramientas = [];

    if (is_array($data_detalle)) {
        foreach ($data_detalle as $fill) {
            if ($fill['id_categoria'] == 1) { // 1 = Materiales
                $materiales[] = $fill;
            } else {
                $herramientas[] = $fill; // Otros son Herramientas (categoría 2 u otros)
            }
        }
    }

    // Renderizar hojas. Si hay materiales, saca hoja de materiales. Si hay herramientas, hoja de herramientas.
    if (!empty($materiales)) {
        generarHojaDespacho($pdf, $nro, $orden, $cliente, $dia, $mes, $ano, $observaciones, $materiales, 'MATERIAL');
    }

    if (!empty($herramientas)) {
        generarHojaDespacho($pdf, $nro, $orden, $cliente, $dia, $mes, $ano, $observaciones, $herramientas, 'HERRAMIENTAS');
    }

    // Si ambos arrays estubieran vacios, de todas formas renderizamos una pagina basica
    if (empty($materiales) && empty($herramientas)) {
         generarHojaDespacho($pdf, $nro, $orden, $cliente, $dia, $mes, $ano, $observaciones, [], 'MATERIAL');
    }

    $pdf->Output('I', $archivo.'.pdf');
    exit;
} else {
    echo 'Se requieren los parametros';
}
?>
