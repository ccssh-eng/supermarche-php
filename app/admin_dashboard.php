<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: signin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f5f6fa;
        }

        h1 {
            color: #2c3e50;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: 0.2s;
        }

        .card:hover {
            transform: scale(1.03);
            cursor: pointer;
        }

        .card a {
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <h1>Tableau de Bord Administrateur</h1>

    <div class="dashboard">
        <div class="card">
            <h3>Ajouter un Produit</h3>
            <a href="admin_produits.php">➕ Aller</a>
        </div>

        <div class="card">
            <h3>Ajouter un Employé</h3>
            <a href="register_employe.php">➕ Aller</a>
        </div>

        <div class="card">
            <h3>Voir Commandes</h3>
            <a href="admin_commandes.php">📋 Voir</a>
        </div>

        <div class="card">
            <h3>Commandes à Traiter</h3>
            <a href="commandes_a_traiter.php">🚚 Gérer</a>
        </div>

        <div class="card">
            <h3>Voir tous les utilisateurs</h3>
            <a href="admin_utilisateurs.php">👥 Liste</a>
        </div>

        <div class="card">
            <h3>Retour Accueil</h3>
            <a href="accueil.php">🏠 Retour</a>
        </div>
    </div>

</body>
</html>
