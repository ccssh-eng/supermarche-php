<?php
session_start();
require 'db.php';
require('lib/fpdf/fpdf.php'); // ← ajuste le chemin si nécessaire

// Vérification : utilisateur connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

// ID dernière commande (récupérée juste après validation)
$idClient = $_SESSION['user_id'];

// Récupérer la dernière commande
$stmt = $pdo->prepare("SELECT ID_Commande, Date_Commande FROM Commandes WHERE ID_Client = ? ORDER BY Date_Commande DESC LIMIT 1");
$stmt->execute([$idClient]);
$commande = $stmt->fetch();

if (!$commande) {
    echo "Aucune commande trouvée.";
    exit;
}

$idCommande = $commande['ID_Commande'];
$dateCommande = $commande['Date_Commande'];

// Récupérer les détails de la commande
$stmt = $pdo->prepare("
    SELECT p.Nom_Produit, d.Quantite, p.Prix_Unitaire
    FROM Details_Commande d
    JOIN Produits p ON d.ID_Produit = p.ID_Produit
    WHERE d.ID_Commande = ?
");
$stmt->execute([$idCommande]);
$produits = $stmt->fetchAll();

// Générer le PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Reçu de Commande",0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Ln(5);

$pdf->Cell(0,10,"Client : " . $_SESSION['nom'], 0,1);
$pdf->Cell(0,10,"Date : " . $dateCommande, 0,1);
$pdf->Cell(0,10,"Commande #: " . $idCommande, 0,1);
$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,10,"Produit",1);
$pdf->Cell(30,10,"Quantité",1);
$pdf->Cell(40,10,"Prix Unitaire",1);
$pdf->Cell(40,10,"Total",1);
$pdf->Ln();

$total = 0;
$pdf->SetFont('Arial','',12);
foreach ($produits as $p) {
    $lineTotal = $p['Quantite'] * $p['Prix_Unitaire'];
    $total += $lineTotal;
    $pdf->Cell(80,10,$p['Nom_Produit'],1);
    $pdf->Cell(30,10,$p['Quantite'],1,0,'C');
    $pdf->Cell(40,10,number_format($p['Prix_Unitaire'], 2)." €",1);
    $pdf->Cell(40,10,number_format($lineTotal, 2)." €",1);
    $pdf->Ln();
}

$pdf->SetFont('Arial','B',12);
$pdf->Cell(150,10,"Total général",1);
$pdf->Cell(40,10,number_format($total, 2)." €",1);

// Afficher ou forcer le téléchargement
$pdf->Output('I', 'recu_commande_'.$idCommande.'.pdf');
exit;
?>
