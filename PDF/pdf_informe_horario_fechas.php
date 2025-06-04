<?php

use PhpOffice\PhpSpreadsheet\Writer\Pdf as WriterPdf;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Verificar si el usuario está autenticado
if (!(isset($_SESSION['s_usuario']))) {
    header("Location: /aistermcon");
    exit();
}

require('../assets/plugins/fpdf/fpdf.php');
require('../models/horario.modelo.php');

$idOrden = $_POST['id_orden'] ?? null;
$fechasSeleccionadas = isset($_POST['fechas_seleccionadas']) ? explode(',', $_POST['fechas_seleccionadas']) : [];
$data_costos = ModeloHorario::mdlInformeHorarioOrden($id_orden, $fechasSeleccionadas);

if (empty($fechasSeleccionadas)) {
    die("No se han seleccionado fechas.");
}else if (!$id_orden) {
    die("Error: ID de orden no recibido.");
}


class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'Informe de Horario por Fechas',0,1,'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Crear el PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,"ID de Orden: $idOrden",0,1);

$pdf->Ln(5);
$pdf->Cell(0,10,'Fechas Seleccionadas:',0,1);

foreach ($fechasSeleccionadas as $fecha) {
    $pdf->Cell(0,10,"- $fecha",0,1);
}

$costo_mano = $data_costos[0]['suma_costo_mano_obra'];
 $gasto_obra = $data_costos[0]['suma_gasto_en_obra'];
$total_general =$data_costos[0]['suma_total_costo'];


$pdf->Cell(0,10,"Costo Mano de Obra: $costo_mano",0,1);
$pdf->Cell(0,10,"Gasto en Obra: $gasto_obra",0,1);
$pdf->Cell(0,10,"Total General: $total_general",0,1);
$pdf->Output();
?>
// Configurar cabeceras para indicar el tipo de contenido y el nombre de descarga
$pdf->Output('I', $filename);