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

    function Row($data, $fontSizes, $b, $lineHeight = 10, $verticalAlignColumns = [], $minRowHeight = 0)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }
        
        $textBlockHeight = $lineHeight * $nb;
        $h = max($minRowHeight, $textBlockHeight);

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
            
            $thisCellNbLines = $this->NbLines($w, $data[$i]);
            $thisCellTextHeight = $thisCellNbLines * $lineHeight;

            $yPos = $y;

            if ($shouldVerticalAlign) {
                // Centrado vertical dentro del bloque contenedor $h disponible
                $yPos += (($h - $thisCellTextHeight) / 2);
            }

            $this->SetFont('Arial', $b, $fontSizes[$i]);

            $this->SetXY($x, $yPos);
            
            // FPDF MultiCell asume $lineHeight como espaciado de renglones interno
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

function renderCeldaMultilineaDinamica($pdf, $w, $h, $txt) {
    // Permite que un texto con dobles líneas no rompa el diseño ajustando el contenedor
    $pdf->MultiCell($w, $h, iconv('UTF-8', 'windows-1252', $txt), 1, 'L');
}

function calcularDimensionesFooterDinamico($pdf, $observaciones) {
    // Se establece la fuente que usa observaciones para el cálculo preciso
    $pdf->SetFont('Arial', '', 8);
    $numLineas = $pdf->NbLines(140, iconv('UTF-8', 'windows-1252', $observaciones));
    if ($numLineas < 1) $numLineas = 1;
    
    // 5 de altura por línea (para fuente size 8)
    $altoTexto = $numLineas * 5; 
    
    // Si es un texto excepcionalmente largo, separamos bastante más las firmas para mejor estética
    $separacionFirma = ($numLineas > 2) ? 10 : 5;
    
    // Espacio: 3 arriba + texto + separación dinámica
    $offsetFirmas = 3 + $altoTexto + $separacionFirma; 
    
    // Proporción original adaptada para notas vaciás o cortas
    if ($offsetFirmas < 18) $offsetFirmas = 18; 
    
    return [ 'altura_footer' => $offsetFirmas + 10, 'offset_firmas' => $offsetFirmas ];
}

function renderFooterDinamico($pdf, $observaciones, $dimensiones) {
    $xCurrent = $pdf->GetX();
    $yCurrent = $pdf->GetY();
    
    // Contenedor principal con alto dinámico
    $pdf->Rect($xCurrent, $yCurrent, 180, $dimensiones['altura_footer']);
    
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetXY($xCurrent + 2, $yCurrent + 3);
    $pdf->Cell(35, 5, 'OBSERVACIONES:', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    
    // Texto multi-línea
    $pdf->SetXY($xCurrent + 35, $yCurrent + 3);
    $pdf->MultiCell(140, 5, iconv('UTF-8', 'windows-1252', $observaciones), 0, 'L');
    
    // Líneas de firma usando el Y dinámico
    $pdf->SetY($yCurrent + $dimensiones['offset_firmas']);
    $pdf->Cell(90, 5, '_______________________________', 0, 0, 'C');
    $pdf->Cell(90, 5, '_______________________________', 0, 1, 'C');
    
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(90, 5, 'RESPONSABLE PEDIDO', 0, 0, 'C');
    $pdf->Cell(90, 5, 'RESPONSABLE BODEGA', 0, 1, 'C');
}

function generarHojaDespacho($pdf, $nro, $orden, $cliente, $dia, $mes, $ano, $observaciones, $items, $titulo_tipo, $tipo_trabajo) {
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
    
    $pdf->SetFont('Arial', '', 8);
    
    // Table width = 180 (Margin 15 + 180 + Right Margin 15 = 210)
    
    // ROW 1: ORDEN, FECHA DE SOLICITUD
    $pdf->Cell(120, 6, iconv('UTF-8', 'windows-1252', ' ORDEN DE TRABAJO No.: ' . $orden), 1, 0, 'L');
    $pdf->Cell(30, 6, iconv('UTF-8', 'windows-1252', 'FECHA DE SOLICITUD:'), 1, 0, 'C');
    $pdf->Cell(10, 6, $dia, 1, 0, 'C');
    $pdf->Cell(10, 6, $mes, 1, 0, 'C');
    $pdf->Cell(10, 6, $ano, 1, 1, 'C');

    // ROW 2: CLIENTE, FECHA RETORNO
    $pdf->Cell(120, 6, iconv('UTF-8', 'windows-1252', ' CLIENTE: ' . $cliente), 1, 0, 'L');
    $pdf->Cell(30, 6, iconv('UTF-8', 'windows-1252', 'FECHA RETORNO:'), 1, 0, 'C');
    $pdf->Cell(10, 6, '', 1, 0, 'C'); // Día vacío
    $pdf->Cell(10, 6, '', 1, 0, 'C'); // Mes vacío
    $pdf->Cell(10, 6, '', 1, 1, 'C'); // Año vacío

    // ROW 3: TIPO DE TRABAJO
    renderCeldaMultilineaDinamica($pdf, 180, 6, ' TIPO DE TRABAJO: ' . $tipo_trabajo);
    
    $pdf->Ln(3);
    // =============== TABLE SECTION ===============
    $pdf->SetWidths(array(8, 22, 14, 14, 82, 20, 20)); // Total 180
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'L', 'C', 'C'));
    // Calculamos dimensiones del footer dinámico
    $dimensionesFooter = calcularDimensionesFooterDinamico($pdf, $observaciones);
    
    // IMPORTANTE: Dejamos el AutoPageBreak en un margen estandar de 15.
    // Esto permite que las filas de la tabla aprovechen toda la hoja hasta abajo.
    // Solo saltará automáticamente si trata de imprimir una celda pasando ese margen.
    $pdf->SetAutoPageBreak(true, 15); 
    
    $pdf->SetFont('Arial', 'B', 8);
    $header = array(
        'No.', 
        iconv('UTF-8', 'windows-1252', 'CÓDIGO'), 
        'CANT.', 
        'UNIDAD', 
        iconv('UTF-8', 'windows-1252', 'DESCRIPCIÓN DEL MATERIAL'), 
        sprintf("SALIDA DE\nBODEGA"), 
        sprintf("INGRESO A\nBODEGA")
    );
    
    // Height ajustado para las fuentes proporcionales (4.5 por linea, 9 en total)
    $pdf->Row($header, array(8, 8, 8, 8, 8, 8, 8), 'B', 4.5, [true, true, true, true, true, true, true]);

    $count = 1;
    $total_items = 0;
    
    $pdf->SetFont('Arial', '', 8);
    
    // Altura óptima matemáticamente calculada para que 27 filas encajen hermosamente
    // en una sola hoja A4 junto con las cabeceras y el recuadro de firmas al final.
    $altura_minima_fila = 6.8;
    // Poca separación entre renglones para los registros de material con descripciones largas
    $altura_texto = 3.5;

    foreach ($items as $fill) {
        $pdf->Row(array(
            $count,
            iconv('UTF-8', 'windows-1252', $fill["codigo"]),
            iconv('UTF-8', 'windows-1252', rtrim(rtrim((string)$fill["cant_sol"], '0'), '.')), // Remover trailing zeros de la BD
            iconv('UTF-8', 'windows-1252', $fill["unidad"]),
            iconv('UTF-8', 'windows-1252', $fill["descripcion"]),
            iconv('UTF-8', 'windows-1252', rtrim(rtrim((string)$fill["cant_apro"], '0'), '.')), // Salida 
            iconv('UTF-8', 'windows-1252', isset($fill["retorno_formateado"]) ? $fill["retorno_formateado"] : ''), // Ingreso a Bodega
        ), array(8, 8, 8, 8, 8, 8, 8), '', $altura_texto, [true, true, true, true, true, true, true], $altura_minima_fila);
        $count++;
        $total_items++;
    }

    // =========================================================================
    // --- LÓGICA DE FILAS VACÍAS ---
    // =========================================================================

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
        ), array(8, 8, 8, 8, 8, 8, 8), '', $altura_texto, [true, true, true, true, true, true, true], $altura_minima_fila);
    
        $count++; 
        $total_items++; 
    }
    
    // =============== FOOTER SECTION ===============
    // Verificamos matemáticamente si queda espacio suficiente en esta página
    // 297 es la altura estándar de un A4. Descontamos la posición actual y el margen inferior.
    $espacioLibre = 297 - $pdf->GetY() - 15;
    
    // Si la altura requerida para el footer es mayor al espacio que queda libre, saltamos la página manual.
    if ($espacioLibre < $dimensionesFooter['altura_footer']) {
        $pdf->AddPage();
    }
    
    renderFooterDinamico($pdf, $observaciones, $dimensionesFooter);
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
    $tipo_trabajo = isset($data_solicitud['tipo_trabajo']) ? $data_solicitud['tipo_trabajo'] : '';

    $observaciones = $data_solicitud['notas'];

    $archivo = 'SOLICITUD-DESPACHO-' . $nro;
    $pdf->SetTitle($archivo);

    // Separar items por categoria y consultar sus retornos
    $data_detalle = ModeloSolicitudDespacho::mdlConsultarDetalleSolicitud($id);
    
    $materiales = [];
    $herramientas = [];

    if (is_array($data_detalle)) {
        foreach ($data_detalle as &$fill) {
            // Consultar Retorno por Producto
            $retorno = ModeloSolicitudDespacho::mdlConsultarRetornoPorProductoYSolicitud($id, $fill['id_producto']);
            
            // Si el retorno es mayor a 0, formatearlo, si no, dejar vacío
            $fill['retorno_formateado'] = ($retorno > 0) ? rtrim(rtrim((string)$retorno, '0'), '.') : '';

            if ($fill['id_categoria'] == 1) { // 1 = Materiales
                $materiales[] = $fill;
            } else {
                $herramientas[] = $fill; // Otros son Herramientas (categoría 2 u otros)
            }
        }
    }

    // Renderizar hojas. Si hay materiales, saca hoja de materiales. Si hay herramientas, hoja de herramientas.
    if (!empty($materiales)) {
        generarHojaDespacho($pdf, $nro, $orden, $cliente, $dia, $mes, $ano, $observaciones, $materiales, 'MATERIAL', $tipo_trabajo);
    }

    if (!empty($herramientas)) {
        generarHojaDespacho($pdf, $nro, $orden, $cliente, $dia, $mes, $ano, $observaciones, $herramientas, 'HERRAMIENTAS', $tipo_trabajo);
    }

    // Si ambos arrays estubieran vacios, de todas formas renderizamos una pagina basica
    if (empty($materiales) && empty($herramientas)) {
         generarHojaDespacho($pdf, $nro, $orden, $cliente, $dia, $mes, $ano, $observaciones, [], 'MATERIAL', $tipo_trabajo);
    }

    $pdf->Output('I', $archivo.'.pdf');
    exit;
} else {
    echo 'Se requieren los parametros';
}
?>
