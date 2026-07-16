<?php
require_once __DIR__ . '/../../restaurant/backend/db.php';
require_once __DIR__ . '/../vendor/autoload.php';
$id  = $_GET['id'] ?? "";
$plats = $_GET['plats'] ;
// echo $plats ;
print_r($plats) ;
// mysqli_begin_transaction($connect);
// try {
//     $queryCommande = "SELECT * FROM commande WHERE idcom = '$id'";
//     $queryCommandeDetail = "SELECT * FROM commande_detail WHERE idcom = '$id'";
//     $resultCommade = mysqli_query($connect, $queryCommande);
//     $resultCommadeDetail = mysqli_query($connect, $queryCommandeDetail);
//     if (!$resultCommade) {
//     }
//     if (!$resultCommadeDetail) {
//     }
//     $rowsCommande = mysqli_fetch_assoc($resultCommade);
//     $rowsCommandeDetail = mysqli_fetch_assoc($resultCommadeDetail);
//     $nameTable = substr($rowsCommande['idtable'], 2, 4);
//     $pdf = new \FPDF('L', 'mm', 'A4');
//     $pdf->AddPage();
//     $pdf->SetFont('Arial', '', '25');
//     $pdf->Cell(0, 10, 'FAST RESTO', 0, 1, 'C');
//     $pdf->SetFont('Arial', '', '15');
//     $pdf->Cell(0, 10, 'Code Commande : ' . $id, 0, 1, 'C');
    
//     $pdf->Cell(0, 10, 'Nom du Client : ' . $rowsCommande['nomcli'], 0, 1, 'r');
//     $pdf->Cell(0, 10, 'Table : '  . $nameTable, 0, 1, 'r');
//     $pdf->Cell(0, 20, 'Votre facture en detail', 0, 1, 'C');
//     $pdf->SetFillColor(250, 250, 250);
//     $pdf->Cell(100, 10, 'Menu', 1, 0, 'L', true);
//     $pdf->Cell(60, 10, 'PU (Ar)', 1, 0, 'C', true);
//     $pdf->Cell(60, 10, 'Unite.', 1, 0, 'C', true);
//     $pdf->Cell(60, 10, 'Total(Ar)', 1, 1, 'C', true);
    


//     $pdf->StartPreview();
//     $pdf->Close();
// } catch (Exception $e) {
// }





exit;
