<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<?php include 'navbar.php'; ?>

require('connexion.php');
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header("Location: signin.php");
    exit;
}

// Modifier statut si formulaire envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commande_id'], $_POST['statut'])) {
    $stmt = $pdo->prepare("UPDATE Commandes SET Statut = ? WHERE ID_Commande = ?");
    $stmt->execute([$_POST['statut'], $_POST['commande_id']]);
}

// Récupérer toutes les commandes + détails
$stmt = $pdo->query("
    SELECT C.ID_Commande, C.Date_Commande, C.Statut, Cl.Nom, Cl.prenom,
           D.Quantite, D.Prix_Unitaire, P.Nom_Produit
    FROM Commandes C
    JOIN Clients Cl ON C.ID_Client = Cl.ID_Client
    JOIN Details_Commande D ON C.ID_Commande = D.ID_Commande
    JOIN Produits P ON D.ID_Produit = P.ID_Produit
    ORDER BY C.Date_Commande DESC
");

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Regrouper par commande
$commandes = [];
foreach ($rows as $row) {
    $id = $row['ID_Commande'];
    if (!isset($commandes[$id])) {
        $commandes[$id] = [
            'client' => $row['Nom'] . ' ' . $row['prenom'],
            'date' => $row['Date_Commande'],
            'statut' => $row['Statut'],
            'produits' => []
        ];
    }
    $commandes[$id]['produits'][] = [
        'nom' => $row['Nom_Produit'],
        'quantite' => $row['Quantite'],
        'prix' => $row['Prix_Unitaire']
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commandes</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .commande {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: #f8f8f8;
        }
        ul { padding-left: 20px; }
        form { margin-top: 10px; }
    </style>
</head>
<body>
    <h2>Gestion des commandes</h2>

    <?php foreach ($commandes as $id => $commande): ?>
        <div class="commande">
            <h3>Commande #<?= $id ?> — <?= htmlspecialchars($commande['client']) ?></h3>
            <p>Date : <?= $commande['date'] ?><br>
               Statut actuel : <strong><?= $commande['statut'] ?></strong></p>

            <ul>
                <?php foreach ($commande['produits'] as $prod): ?>
                    <li><?= $prod['quantite'] ?> × <?= htmlspecialchars($prod['nom']) ?> @ <?= number_format($prod['prix'], 2) ?> €</li>
                <?php endforeach; ?>
            </ul>

            <form method="post">
                <input type="hidden" name="commande_id" value="<?= $id ?>">
                <label>Changer le statut :</label>
                <select name="statut" required>
                    <option value="En attente" <?= $commande['statut'] === 'En attente' ? 'selected' : '' ?>>En attente</option>
                    <option value="Expédiée" <?= $commande['statut'] === 'Expédiée' ? 'selected' : '' ?>>Expédiée</option>
                    <option value="Livrée" <?= $commande['statut'] === 'Livrée' ? 'selected' : '' ?>>Livrée</option>
                    <option value="Annulée" <?= $commande['statut'] === 'Annulée' ? 'selected' : '' ?>>Annulée</option>
                </select>
                <button type="submit">Mettre à jour</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
