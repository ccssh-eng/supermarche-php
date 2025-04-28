<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialiser le panier si vide
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Vérifier si l'ID du produit est présent dans l'URL
if (isset($_GET['id'])) {
    $id_produit = (int)$_GET['id'];

    // Ajouter ou incrémenter la quantité
    if (isset($_SESSION['panier'][$id_produit])) {
        $_SESSION['panier'][$id_produit]++;
    } else {
        $_SESSION['panier'][$id_produit] = 1;
    }

    // ✅ Redirection directe sans echo
    header("Location: panier.php");
    exit;
} else {
    // Si pas d'id ➔ redirection vers produits
    header("Location: produits.php");
    exit;
}
?>
