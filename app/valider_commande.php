<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

// Vérifier panier
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "Votre panier est vide.";
    exit;
}

try {
    $pdo->beginTransaction();

    // Créer la commande
    $stmt = $pdo->prepare("INSERT INTO Commandes (ID_Client, Date_Commande, Statut) VALUES (?, NOW(), 'En attente')");
    $stmt->execute([$_SESSION['user_id']]);
    $idCommande = $pdo->lastInsertId();

    // Ajouter les produits dans Details_Commande et MAJ stock
    foreach ($_SESSION['panier'] as $idProduit => $quantite) {
        // 1. Ajouter dans Details_Commande
        $stmt = $pdo->prepare("INSERT INTO Details_Commande (ID_Commande, ID_Produit, Quantite) VALUES (?, ?, ?)");
        $stmt->execute([$idCommande, $idProduit, $quantite]);

        // 2. Réduire le stock dans Produits
        $stmt = $pdo->prepare("UPDATE Produits SET Stock = Stock - ? WHERE ID_Produit = ?");
        $stmt->execute([$quantite, $idProduit]);
    }

    $pdo->commit();

    // Vider panier
    unset($_SESSION['panier']);

    echo "<h2>Commande validée et stock mis à jour !</h2>";
    echo '<p><a href="accueil.php">Retour à l\'accueil</a></p>';

    header("Location: commande_confirmee.php");
    exit;


} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erreur lors de la validation : " . $e->getMessage();
}
?>
