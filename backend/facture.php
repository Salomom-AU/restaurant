<?php
require_once __DIR__ . '/../../restaurant/backend/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    die('Code commande manquant');
}
function pdf_text($text) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text ?? '');
}
$queryCommande = "SELECT * FROM commande WHERE idcom = ?";
$stmt = $connect->prepare($queryCommande);
$stmt->bind_param("s", $id);
$stmt->execute();
$resultCommande = $stmt->get_result();
$rowsCommande = $resultCommande->fetch_assoc();

if (!$rowsCommande) {
    die('Commande introuvable');
}
$queryCommandeDetail = "SELECT cd.*, m.nomplat 
                        FROM commande_detail cd 
                        JOIN menu m ON cd.idplat = m.idplat 
                        WHERE cd.idcom = ?";
$stmtDetail = $connect->prepare($queryCommandeDetail);
$stmtDetail->bind_param("s", $id);
$stmtDetail->execute();
$resultDetail = $stmtDetail->get_result();
$isEmporter = ($rowsCommande['typecom'] === 'Emporter');
$nameTable  = '';

if (!$isEmporter && !empty($rowsCommande['idtable'])) {
    $nameTable = substr($rowsCommande['idtable'], 1);
}
$pdf = new \FPDF('P', 'mm', 'A4');
$pdf->StartPreview('Facture_' . $id . '.pdf');
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 12, pdf_text('NOM DU RESTAURANT'), 0, 1, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, pdf_text('Code Commande : ' . $id), 0, 1, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, pdf_text('Nom du Client : ' . $rowsCommande['nomcli']), 0, 1, 'L');
if (!$isEmporter) {
    $pdf->Cell(0, 7, pdf_text('Table : ' . $nameTable), 0, 1, 'L');
} else {
    $pdf->Cell(0, 7, pdf_text('Type : A emporter'), 0, 1, 'L');
}

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, pdf_text('Votre facture en detail'), 0, 1, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(240, 240, 240);

$pdf->Cell(80, 9, pdf_text('Menu'), 1, 0, 'L', true);
$pdf->Cell(35, 9, pdf_text('PU (Ar)'), 1, 0, 'C', true);
$pdf->Cell(30, 9, pdf_text('Unite'), 1, 0, 'C', true);
$pdf->Cell(45, 9, pdf_text('Total (Ar)'), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 11);
$totalGeneral = 0;

while ($row = $resultDetail->fetch_assoc()) {
    $pu     = (float) $row['prix_unitaire'];
    $qte    = (int) $row['quantite'];
    $total  = $pu * $qte;
    $totalGeneral += $total;

    $puFormat    = number_format($pu, 0, ',', '.');
    $totalFormat = number_format($total, 0, ',', '.');

    $pdf->Cell(80, 8, pdf_text($row['nomplat']), 1, 0, 'L');
    $pdf->Cell(35, 8, $puFormat, 1, 0, 'R');
    $pdf->Cell(30, 8, $qte, 1, 0, 'C');
    $pdf->Cell(45, 8, $totalFormat, 1, 1, 'R');
}
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 12);
$totalFormat = number_format($totalGeneral, 0, ',', '.');
$pdf->Cell(0, 10, pdf_text('TOTAL : ' . $totalFormat . ' Ariary'), 0, 1, 'R');

$pdf->Close();
exit;