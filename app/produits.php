<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';
include 'navbar.php';

// Récupérer les produits depuis la base
$stmt = $pdo->query("SELECT ID_Produit, Nom_Produit, Prix_Unitaire, Stock FROM Produits");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nos Produits</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: auto;
            background-color: white;
            box-shadow: 0 0 10px #ccc;
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

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        a.btn {
            background: #27ae60;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        a.btn:hover {
            background: #2ecc71;
        }
    </style>
</head>
<body>

<h1>🛒 Nos Produits</h1>

<?php if (empty($produits)): ?>
    <p style="text-align:center;">Aucun produit disponible pour le moment.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Nom</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>
        <?php foreach ($produits as $prod): ?>
            <tr>
                <td><?= htmlspecialchars($prod['Nom_Produit']) ?></td>
                <td><?= number_format($prod['Prix_Unitaire'], 2) ?> €</td>
                <td><?= $prod['Stock'] ?></td>
                <td>
                    <a class="btn" href="ajouter_panier.php?id=<?= $prod['ID_Produit'] ?>">Ajouter au panier</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>
