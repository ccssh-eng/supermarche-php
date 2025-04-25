<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<?php include 'navbar.php'; ?>

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: signin.php");
    exit;
}

$idCommande = $_GET['cmd'] ?? null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande confirmée</title>
</head>
<body>
    <h2>Merci pour votre commande !</h2>
    <?php if ($idCommande): ?>
        <p>Votre numéro de commande est : <strong>#<?= htmlspecialchars($idCommande) ?></strong></p>
    <?php else: ?>
        <p>Commande enregistrée.</p>
    <?php endif; ?>
    <a href="accueil.php">Retour à l'accueil</a>
</body>
</html>
