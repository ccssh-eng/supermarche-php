<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande confirmée</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px; }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
            margin: auto;
        }
        a.button {
            display: inline-block;
            margin-top: 20px;
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        a.button:hover {
            background-color: #2ecc71;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🎉 Commande validée !</h2>
    <p>Merci pour votre achat, <?= htmlspecialchars($_SESSION['nom']) ?>.</p>
    <p>Votre commande a bien été enregistrée.</p>

    <a class="button" href="recu.php" target="_blank">📄 Télécharger mon reçu PDF</a>
    <br><br>
    <a href="accueil.php">↩ Retour à l'accueil</a>
</div>

</body>
</html>
