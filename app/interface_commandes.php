<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<?php include 'navbar.php'; ?>

// interface_commandes.php : Vue pour les employés ou clients connectés
require('connexion.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employee') {
    header('Location: login.php');
    exit;
}

$id_client = $_SESSION['id'];
$nom = $_SESSION['nom'];
$role = $_SESSION['role'];

// Si c'est un employé, on peut voir toutes les commandes (optionnel)
if ($role === 'employe') {
    $stmt = $pdo->query("SELECT c.ID_Commande, c.Date_Commande, cl.Nom, cl.Prénom, c.Statut
                          FROM Commandes c
                          JOIN Clients cl ON c.ID_Client = cl.ID_Client
                          ORDER BY c.Date_Commande DESC");
} else {
    // Sinon, le client voit ses commandes uniquement (s'il est client réel)
    $stmt = $pdo->prepare("SELECT ID_Commande, Date_Commande, Statut FROM Commandes WHERE ID_Client = ? ORDER BY Date_Commande DESC");
    $stmt->execute([$id_client]);
}
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 20px; }
        .container { background: white; max-width: 800px; margin: auto; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        h2 { text-align: center; }
        .back { text-align: center; margin-top: 20px; }
        .btn { padding: 10px 20px; background: #007BFF; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
 <h2>Bienvenue Employé <?= htmlspecialchars($_SESSION['nom']) ?></h2>
    <ul>
        <li><a href="commandes_a_traiter.php">Commandes à traiter</a></li>
        <li><a href="logout.php">Se déconnecter</a></li>
    </ul>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Commandes <?= ($role === 'employe') ? 'des clients' : 'de '.$nom ?></h2>
    <table>
        <tr>
            <th>ID Commande</th>
            <th>Date</th>
            <?php if ($role === 'employe'): ?><th>Client</th><?php endif; ?>
            <th>Statut</th>
        </tr>
        <?php foreach ($commandes as $cmd): ?>
        <tr>
            <td><?= $cmd['ID_Commande'] ?></td>
            <td><?= $cmd['Date_Commande'] ?></td>
            <?php if ($role === 'employe'): ?><td><?= $cmd['Nom'] . ' ' . $cmd['Prénom'] ?></td><?php endif; ?>
            <td><?= $cmd['Statut'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <div class="back">
        <a href="accueil.php" class="btn">Retour à l'accueil</a>
    </div>
</div>
</body>
</html>
