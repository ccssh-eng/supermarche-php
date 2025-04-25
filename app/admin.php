<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<?php include 'navbar.php'; ?>

require('connexion.php');
session_start();

// Vérifier que c’est un admin connecté
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Rediriger vers login.php
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}



// Traitement formulaire promotion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produit = $_POST['id_produit'];
    $pourcentage = $_POST['pourcentage'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    $stmt = $pdo->prepare("INSERT INTO Promotions (ID_Produit, Pourcentage_Remise, Date_Debut, Date_Fin) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_produit, $pourcentage, $date_debut, $date_fin]);
    $message = "✅ Promotion ajoutée pour le produit $id_produit.";
}

// Récupérer tous les produits
$produits = $pdo->query("SELECT ID_Produit, Nom_Produit, Prix_Unitaire FROM Produits")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">


    <title>Gestion des Promotions</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        h2 { margin-bottom: 10px; }
        form { margin-top: 10px; }
        .success { color: green; }
    </style>
</head>
<body>
     <?php include 'navbar.php'; ?>
    <h1>Bienvenue Admin, <?= htmlspecialchars($_SESSION['nom']) ?> !</h1>

    <ul>
        <li><a href="admin_commandes.php">Voir toutes les commandes</a></li>
        <li><a href="register_employe.php">Ajouter un employé</a></li>
        <li><a href="logout.php">Déconnexion</a></li>
    </ul>
   
    <a href="interface_commandes.php">Accéder aux commandes</a>
       <ul>
        <li><a href="register_employe.php">Créer un employé</a></li>
        <li><a href="admin_commandes.php">Voir toutes les commandes</a></li>
        <li><a href="logout.php">Se déconnecter</a></li>
    </ul>

    <h2>Interface Admin : Ajouter une promotion</h2>

    <?php if (!empty($message)) echo "<p class='success'>$message</p>"; ?>

    <form method="POST">
        <label>Produit :</label>
        <select name="id_produit" required>
            <?php foreach ($produits as $p): ?>
                <option value="<?= $p['ID_Produit'] ?>"><?= htmlspecialchars($p['Nom_Produit']) ?> (<?= $p['Prix_Unitaire'] ?> €)</option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Pourcentage de remise :</label>
        <input type="number" name="pourcentage" step="0.01" required><br><br>

        <label>Date de début :</label>
        <input type="date" name="date_debut" required><br><br>

        <label>Date de fin :</label>
        <input type="date" name="date_fin" required><br><br>

        <button type="submit">Ajouter la promotion</button>
    </form>
</body>
</html>
