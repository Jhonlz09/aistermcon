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
$idOrden = $_POST['id_orden'] ?? null;
$fechasSeleccionadas = isset($_POST['fechas_seleccionadas']) && !empty($_POST['fechas_seleccionadas'])
    ? explode(',', $_POST['fechas_seleccionadas'])
    : [];

// Crear clase FPDF personalizada
class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Informe de Horario por Fechas', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Iniciar PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
// Si no hay fechas seleccionadas o ID de orden inválido
if (empty($fechasSeleccionadas) || !$idOrden) {
    $mensaje = !$idOrden ? 'NO SE RECIBIÓ ID DE ORDEN' : 'NO SE SELECCIONARON FECHAS';
    $pdf->SetXY(10, 20);
    $pdf->Cell(0, 20, iconv('UTF-8', 'windows-1252', $mensaje), 0, 0, 'C');
    $pdf->SetTitle("Advertencia", true);
} else {
    // Obtener datos desde el modelo
    $data_costos = ModeloHorario::mdlInformeHorarioOrden($idOrden, $fechasSeleccionadas);

    $pdf->Cell(0, 10, "ID de Orden: $idOrden", 0, 1);
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'Fechas Seleccionadas:', 0, 1);

    foreach ($fechasSeleccionadas as $fecha) {
        $pdf->Cell(0, 10, "- $fecha", 0, 1);
    }

    // Valores retornados (si hay resultados)
    $costo_mano = $data_costos[0]['suma_costo_mano_obra'] ?? '$0.00';
    $gasto_obra = $data_costos[0]['suma_gasto_en_obra'] ?? '$0.00';
    $total_general = $data_costos[0]['suma_total_costo'] ?? '$0.00';

    $pdf->Ln(5);
    $pdf->Cell(0, 10, "Costo Mano de Obra: $costo_mano", 0, 1);
    $pdf->Cell(0, 10, "Gasto en Obra: $gasto_obra", 0, 1);
    $pdf->Cell(0, 10, "Total General: $total_general", 0, 1);
}

// Generar PDF
$pdf->Output();
// Configurar cabeceras para indicar el tipo de contenido y el nombre de descarga
