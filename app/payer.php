<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

// Vérifier qu’il y a un panier
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "<p>Votre panier est vide.</p>";
    exit;
}

// Traitement du paiement simulé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ici on simule toujours un "paiement accepté"
    header("Location: valider_commande.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 30px; }
        form { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #2ecc71; }
    </style>
</head>
<body>

    <h2 style="text-align:center;">Paiement sécurisé</h2>

    <form method="POST" action="payer.php">
        <label for="nom">Nom sur la carte</label>
        <input type="text" id="nom" name="nom" placeholder="Jean Dupont" required>

        <label for="carte">Numéro de carte</label>
        <input type="text" id="carte" name="carte" placeholder="1234 5678 9012 3456" required pattern="\d{16}" title="16 chiffres">

        <button type="submit">Payer maintenant</button>
    </form>

</body>
</html>
