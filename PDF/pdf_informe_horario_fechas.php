<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['s_usuario'])) {
    header("Location: /aistermcon");
    exit();
}

require('../assets/plugins/fpdf/fpdf.php');
require('../models/horario.modelo.php');

// Validar parámetros POST
$fechasSeleccionadas = isset($_POST['fechas_seleccionadas']) && !empty($_POST['fechas_seleccionadas'])
    ? explode(',', $_POST['fechas_seleccionadas'])
    : [];
class PDF extends FPDF
{
    // private $startDate;
    // private $endDate;

    // function Header()
    // {
    //     $this->SetFont('Arial', '', 9); // letra más pequeña
    //
    // }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
    }
}
// Iniciar PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->SetTitle("INFORME DE GASTOS Y MANO DE OBRA");
// Si no hay fechas seleccionadas o ID de orden inválido
if (empty($fechasSeleccionadas)) {
    $pdf->SetXY(10, 20);
    $pdf->Cell(0, 20, iconv('UTF-8', 'windows-1252', 'NO SE SELECCIONARON FECHAS'), 0, 0, 'C');
    $pdf->SetTitle("Advertencia", true);
} else {
    if (count($fechasSeleccionadas) === 1) {
        $start = $end = $fechasSeleccionadas[0];
    } elseif (count($fechasSeleccionadas) >= 2) {
        // Ordenar por si acaso vienen desordenadas
        sort($fechasSeleccionadas);
        $start = $fechasSeleccionadas[0];
        $end = $fechasSeleccionadas[1];
    }

    $startDate = DateTime::createFromFormat('Y-m-d', $start)->format('d/m/Y');
    $endDate = DateTime::createFromFormat('Y-m-d', $end)->format('d/m/Y');


    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'INFORME DE GASTOS Y MANO DE OBRA POR FECHAS', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'DESDE ' . $startDate . ' HASTA ' . $endDate), 0, 1, 'C');
    $pdf->Ln(5);
    // Obtener datos desde el modelo
    $data_costos = ModeloHorario::mdlInformeHorarioFecha($start, $end);
    $pdf->SetFont('Arial', '', 10);

    $margen = 10;
    $espacioHorizontal = 5;
    $anchoTarjeta = (210 - ($margen * 2) - (2 * $espacioHorizontal)) / 3; // 3 tarjetas por fila con espacio
    $altoTarjeta = 40;

    $xInicial = $margen;
    $y = $pdf->GetY();
    $columna = 0;

    foreach ($data_costos as $dato) {
        if ($y + $altoTarjeta > 297 - $margen) { // Altura máxima A4
            $pdf->AddPage();
            $y = $margen;
        }

        $orden = iconv('UTF-8', 'windows-1252', $dato['orden'] ?? '');
        $costo_mano = $dato['suma_costo_mano_obra'] ?? '$0.00';
        $gasto_obra = $dato['suma_gasto_en_obra'] ?? '$0.00';
        $total = $dato['suma_total_costo'] ?? '$0.00';

        $x = $xInicial + ($columna * ($anchoTarjeta + $espacioHorizontal));

        // Rectángulo exterior
        $pdf->Rect($x, $y, $anchoTarjeta, $altoTarjeta);

        // Título: orden
        $pdf->SetXY($x + 2, $y + 2);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell($anchoTarjeta - 4, 5, $orden, 0, 'L');

        // Línea horizontal después del título
        $yActual = $pdf->GetY();
        $pdf->Line($x, $yActual + 2, $x + $anchoTarjeta, $yActual + 2);

        // GASTOS
        $pdf->SetXY($x + 2, $yActual + 3);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(($anchoTarjeta - 4) / 2, 6, 'GASTOS', 0, 0, 'L');
        $pdf->Cell(($anchoTarjeta - 4) / 2, 6, $gasto_obra, 0, 1, 'R');

        // MANO DE OBRA
        $pdf->SetX($x + 2);
        $pdf->Cell(($anchoTarjeta - 4) / 2, 6, 'MANO DE OBRA', 0, 0, 'L');
        $pdf->SetFont('Arial', 'U', 9);
        $pdf->Cell(($anchoTarjeta - 4) / 2, 6, $costo_mano, 0, 1, 'R');

        // TOTAL
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetX($x + 2);
        $pdf->Cell($anchoTarjeta - 4, 6, $total, 0, 1, 'R');

        // Avanzar a la siguiente columna
        $columna++;
        if ($columna == 3) {
            $columna = 0;
            $y += $altoTarjeta + 5; // Espacio entre filas
        }
    }
}
// Generar PDF
$pdf->Output('I', 'INFORME DE GASTOS Y MANO DE OBRA');
