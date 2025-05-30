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
require('../models/informe.modelo.php');
require('../models/inventario.modelo.php');



$id_orden = $_POST['id_orden'] ?? $_GET['id_orden'] ?? null;

if (!$id_orden) {
    die("Error: ID de orden no recibido.");
}
$info_error = 'NRO. DE ORDEN';
$datos_fecha = ModeloInforme::mdlInformeDetalleOrden($id_orden);
$datos_guias = ModeloInforme::mdlInformeFechaOrden($id_orden, false);
if ($datos_fecha != null) {
    $cliente = $datos_fecha[0]['cliente'];
    $orden = $datos_fecha[0]['orden_nro'];
    $descripcion = $datos_fecha[0]['descripcion'];
    $filename = $orden . ' ' . $cliente;
    $info_header = $info_error . ': ' . $orden . "         " . 'CLIENTE: ' . $cliente;
    $info = 'CLIENTE: ' . $cliente . "\n" . 'NRO. ORDEN: ' . $orden;
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

    function Header()
    {
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(10, 0, 0);
        $this->SetY(0);
        $this->Cell(376, 25, '' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
        if ($this->encabezado) {
            global $info_header;
            $this->SetFont('Arial', '', 10);
            $this->SetXY(15, 13);
            $this->MultiCell(0, 0, iconv('UTF-8', 'windows-1252', $info_header), 0, 'L', 0);
            $this->Ln(5);
        }
    }

    function __construct()
    {
        parent::__construct();
        $this->y0 = $this->GetY();
        $this->startY = $this->y0;
        $this->SetMargins(12, 0, 12);
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
$pdf->desactivarEncabezado();
$pdf->AliasNbPages();
$pdf->AddPage();
if ($datos_guias == null) {
    $pdf->SetFont('Arial', '', 14);
    $pdf->SetXY(10, 20);
    $pdf->Cell(0, 20, iconv('UTF-8', 'windows-1252', 'NO SE ENCONTRARON DATOS PARA EL ' . $info_error), 0, 0, 'C');
    $pdf->SetTitle("Error", true);
} else {
    $pdf->SetTitle($orden . '  ' . $cliente);
    $pdf->Ln(50);
    $pdf->Image('../assets/img/logo_pdf.jpeg', 42, null, 128, 25);
    $pdf->SetFont('Arial', 'B', 22);
    $pdf->MultiCell(0, 10, 'INFORME DE TRASLADO DE HERRAMIENTAS' . "\n" . 'Y MATERIALES', 0, 'C', 0);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Ln();
    $pdf->SetX(20);
    $pdf->MultiCell(0, 10, '' . $info . '', 0, 'L', 0);
    $pdf->Ln(5);
    $pdf->SetX(20);
    $pdf->Cell(0, 0, 'DESCRIPCION:', 0, 'L', 0);
    $pdf->SetXY(63, 125);
    $pdf->MultiCell(0, 10, $descripcion, 0, 'L', 0);
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
    $pdf->SetAutoPageBreak(true, 20);
    // $pdf->SetY(30);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->SetMargins(12, 20, 12);
    $pdf->SetWidths(array(27, 80, 18, 21, 21, 21));
    $pdf->SetAligns(array('L', 'L', 'C', 'C', 'C', 'C'));
    $pdf->SetX(12);
    foreach ($datos_guias as $row) {
        if ($row["responsable"] == '') {
            $encargado = 'N/A';
        } else {
            $encargado = $row["responsable"];
        }
        $pdf->SetStartY(20);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Fecha de salida: ' . $row["fecha_emision"]), 0, 0, 'L');
        $pdf->SetTextColor(200, 0, 0);
        $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Nro. de Guía: ' . $row["nro_guia"]), 0, 1, 'R');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Fecha de entrada: ' . $row["fecha_retorno"]), 0, 1, 'L');
        $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Conductor: ' . $row["conductor"] . ' ' . $row["placa"]), 0, 0, 'L');
        $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Responsable: ' . $encargado), 0, 1, 'R');
        $pdf->StyledWriteLine(
            'Motivo: ',
            $row['motivo'],
            ['family' => 'Arial', 'style' => 'B', 'size' => 12],
            ['family' => 'Arial', 'style' => 'I', 'size' => 12]
        );
        $pdf->Ln(10);
        $id_guia = $row["id_guia"];
        $pdf->SetFont('Arial', 'B', 11);
        $header = array('Codigo', 'Descripcion', 'Unidad', 'Salida', 'Entrada', 'Tot. Util.');
        $pdf->Row($header, array(12, 12, 12, 12, 12, 12), 'B');
        $data = ModeloInforme::mdlInformeOrden($id_orden, $id_guia);
        foreach ($data as $fill) {
            $pdf->SetStartY(20);
            $pdf->SetFont('Arial', '', 10);
            $salida = $fill["cantidad_salida"];
            $entrada = $fill["retorno"];
            $util = $fill["utilizado"];
            $fab_pro = $fill['fabricado'];
            $id_producto = $fill['id_producto'];
            $pdf->Row(array(
                iconv('UTF-8', 'windows-1252', $fill["codigo"]),
                iconv('UTF-8', 'windows-1252', $fill["descripcion"]),
                iconv('UTF-8', 'windows-1252', $fill["unidad"]),
                iconv('UTF-8', 'windows-1252', $salida),
                iconv('UTF-8', 'windows-1252', $entrada),
                iconv('UTF-8', 'windows-1252', $util)
            ), array(10, 10, 10, 10, 10, 10), '', 7, [true, true, true, true, true, true]);
            // }
            // if ($fab_pro == true) {
            //     $data_fab = ModeloInventario::mdlListarProductoFab($id_producto);
            //     foreach ($data_fab as $fab) {
            //         $pdf->Row(array(
            //             iconv('UTF-8', 'windows-1252', $fab["codigo"]),
            //             iconv('UTF-8', 'windows-1252', $fab["descripcion"]),
            //             iconv('UTF-8', 'windows-1252', $fab["unidad"]),
            //             iconv('UTF-8', 'windows-1252', $fab["cantidad_salida"]),
            //             iconv('UTF-8', 'windows-1252', $fab["retorno"]),
            //             iconv('UTF-8', 'windows-1252', $fab["utilizado"])
            //         ), array(10, 10, 10, 10, 10, 10), '', 7, [true, true, true, true, true, true]);
            //     }
            // }
        }
        $pdf->Ln();
    }
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'RESUMEN: '), 0, 1, 'L');
    $pdf->Ln(3);
    $header_resumen = array('Codigo', 'Descripcion', 'Unidad', 'Tot. Salida', 'Tot. Entrada', 'Tot. Util.');
    $pdf->SetWidths(array(24, 70, 18, 24, 28, 24));
    $pdf->Row($header_resumen, array(12, 12, 12, 12, 12, 12), 'B');
    $data_resumen = ModeloInforme::mdlInformeOrdenResumen($id_orden, false);
    foreach ($data_resumen as $fill) {
        $pdf->SetStartY(20);
        $pdf->SetFont('Arial', '', 10);
        // $resaño = substr($fill["year"], -2);
        $salida = $fill["cantidad_salida"];
        $entrada = $fill["retorno"];
        $util = $fill["utilizado"];
        $fab_pro = $fill['fabricado'];
        $id_producto = $fill['id_producto'];
        $pdf->Row(array(
            iconv('UTF-8', 'windows-1252', $fill["codigo"]),
            iconv('UTF-8', 'windows-1252', $fill["descripcion"]),
            iconv('UTF-8', 'windows-1252', $fill["unidad"]),
            iconv('UTF-8', 'windows-1252', $salida),
            iconv('UTF-8', 'windows-1252', $entrada),
            iconv('UTF-8', 'windows-1252', $util)
        ), array(10, 10, 10, 10, 10, 10), '', 6, [true, true, true, true, true, true]);
    }
}
// Configurar cabeceras para indicar el tipo de contenido y el nombre de descarga

// Generar el PDF y almacenarlo temporalmente
$pdf->Output('I', $filename);


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