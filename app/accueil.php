<?php
// Affichage des erreurs pour debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session
session_start();

// Connexion à la base
require 'db.php';

// Vérifier que l'utilisateur est connecté et que c'est bien un client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: signin.php");
    exit;
}

// Inclure la barre de navigation
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
        }

        h1 {
            color: #2c3e50;
        }

        .container {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue sur Supermarché, <?= htmlspecialchars($_SESSION['nom']) ?> !</h1>
        <p>Ceci est votre page d'accueil client.</p>

        <ul>
            <li><a href="produits.php">🛒 Voir les produits</a></li>
            <li><a href="panier.php">🧺 Mon Panier</a></li>
            <li><a href="mes_commandes.php">📦 Mes Commandes</a></li>
        </ul>
    </div>
</body>
</html>
