<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Script lancé<br>";

// Initialiser le panier si vide
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
    echo "Panier créé<br>";
}

// Vérifier si l'ID du produit est présent dans l'URL
if (isset($_GET['id'])) {
    $id_produit = (int)$_GET['id'];
    echo "ID produit : $id_produit<br>";

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

    // Ajouter ou incrémenter la quantité
    if (isset($_SESSION['panier'][$id_produit])) {
        $_SESSION['panier'][$id_produit]++;
        echo "Quantité augmentée<br>";
    } else {
        $_SESSION['panier'][$id_produit] = 1;
        echo "Produit ajouté au panier<br>";
    }

    // Redirection vers panier
    echo "Redirection vers panier.php...";
    header("Location: panier.php");
    exit;
} else {
    echo "Erreur : aucun ID produit reçu.<br>";
}
?>
