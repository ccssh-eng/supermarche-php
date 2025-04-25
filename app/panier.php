<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre Panier</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: auto;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Votre Panier</h2>

<table>
    <tr>
        <th>Produit</th>
        <th>Quantité</th>
    </tr>

<?php
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $idProduit => $quantite) {
        $stmt = $pdo->prepare("SELECT Nom_Produit FROM Produits WHERE ID_Produit = ?");
        $stmt->execute([$idProduit]);
        $produit = $stmt->fetch();

        if ($produit) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($produit['Nom_Produit']) . "</td>";
            echo "<td>" . (int)$quantite . "</td>";
            echo "</tr>";
        }
    }
} else {
    echo "<tr><td colspan='2'>Votre panier est vide.</td></tr>";
}
?>

</table>

<?php if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])): ?>
    <div style="text-align: center; margin-top: 20px;">
        <a href="payer.php" style="
            display: inline-block;
            background-color: #27ae60;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
        ">💳 Procéder au paiement</a>
    </div>
<?php endif; ?>

</body>
</html>
