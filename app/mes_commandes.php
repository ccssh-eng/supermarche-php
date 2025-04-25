<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$idClient = $_SESSION['user_id'];

// Récupérer toutes les commandes du client
$stmt = $pdo->prepare("
    SELECT c.ID_Commande, c.Date_Commande, c.Statut_Livraison, d.ID_Produit, d.Quantite, p.Nom_Produit, p.Prix_Unitaire
    FROM Commandes c
    JOIN Details_Commande d ON c.ID_Commande = d.ID_Commande
    JOIN Produits p ON d.ID_Produit = p.ID_Produit
    WHERE c.ID_Client = ?
    ORDER BY c.Date_Commande DESC
");

$stmt->execute([$idClient]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Commandes</title>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h2>Mes Commandes</h2>

    <?php if (empty($commandes)): ?>
        <p>Vous n'avez pas encore passé de commande.</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Commande #</th>
                <th>Date</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
            </tr>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td><?= htmlspecialchars($commande['ID_Commande']) ?></td>
                    <td><?= htmlspecialchars($commande['Date_Commande']) ?></td>
                    <td><?= htmlspecialchars($commande['Nom_Produit']) ?></td>
                    <td><?= (int)$commande['Quantite'] ?></td>
                    <td><?= number_format($commande['Prix_Unitaire'], 2) ?> €</td>
                </tr>
            <?php endforeach; ?>


<table border="1" cellpadding="10">
    <tr>
        <th>Commande #</th>
        <th>Date</th>
        <th>Statut Livraison</th>
        <th>Produit</th>
        <th>Quantité</th>
        <th>Prix Unitaire</th>
    </tr>
    <?php foreach ($commandes as $commande): ?>
        <tr>
            <td><?= htmlspecialchars($commande['ID_Commande']) ?></td>
            <td><?= htmlspecialchars($commande['Date_Commande']) ?></td>
            <td><?= htmlspecialchars($commande['Statut_Livraison']) ?></td>
            <td><?= htmlspecialchars($commande['Nom_Produit']) ?></td>
            <td><?= (int)$commande['Quantite'] ?></td>
            <td><?= number_format($commande['Prix_Unitaire'], 2) ?> €</td>
        </tr>
    <?php endforeach; ?>
</table>

        </table>
    <?php endif; ?>

    <br>
    <a href="accueil.php">Retour à l'accueil</a>
</body>
</html>
