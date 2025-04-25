<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<?php include 'navbar.php'; ?>

session_start();
require_once 'connexion.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employee') {
    header("Location: signin.php");
    exit;
}

// ✅ Marquer commande comme expédiée
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commande_id'])) {
    $stmt = $pdo->prepare("UPDATE Commandes SET Statut = 'Expédiée' WHERE ID_Commande = ?");
    $stmt->execute([$_POST['commande_id']]);
}

// 🔄 Récupérer les commandes "En attente"
$stmt = $pdo->query("
    SELECT C.ID_Commande, C.Date_Commande, Cl.Nom, Cl.prenom,
           D.Quantite, D.Prix_Unitaire, P.Nom_Produit
    FROM Commandes C
    JOIN Clients Cl ON C.ID_Client = Cl.ID_Client
    JOIN Details_Commande D ON C.ID_Commande = D.ID_Commande
    JOIN Produits P ON D.ID_Produit = P.ID_Produit
    WHERE C.Statut = 'En attente'
    ORDER BY C.Date_Commande ASC
");

$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Regroupement par commande
$grouped = [];
foreach ($commandes as $row) {
    $id = $row['ID_Commande'];
    if (!isset($grouped[$id])) {
        $grouped[$id] = [
            'date' => $row['Date_Commande'],
            'client' => $row['Nom'] . ' ' . $row['prenom'],
            'produits' => []
        ];
    }
    $grouped[$id]['produits'][] = [
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
    <title>Commandes à traiter</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; background: #f7f7f7; }
        .commande {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            margin-bottom: 20px;
        }
        ul { padding-left: 20px; }
        form { margin-top: 10px; }
    </style>
</head>
<body>

    <h2>Commandes à préparer</h2>

    <?php if (empty($grouped)): ?>
        <p>Aucune commande en attente.</p>
    <?php else: ?>
        <?php foreach ($grouped as $id => $commande): ?>
            <div class="commande">
                <h3>Commande #<?= $id ?> — Client : <?= htmlspecialchars($commande['client']) ?></h3>
                <p>Date : <?= $commande['date'] ?></p>

                <ul>
                    <?php foreach ($commande['produits'] as $prod): ?>
                        <li><?= $prod['quantite'] ?> × <?= htmlspecialchars($prod['nom']) ?> @ <?= number_format($prod['prix'], 2) ?> €</li>
                    <?php endforeach; ?>
                </ul>

                <form method="post">
                    <input type="hidden" name="commande_id" value="<?= $id ?>">
                    <button type="submit">✅ Marquer comme expédiée</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>
