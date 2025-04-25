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

require_once 'connexion.php';

// Récupération des produits
$stmt = $pdo->query("
    SELECT P.ID_Produit, P.Nom_Produit, P.Prix_Unitaire, P.Stock, C.Nom_Categorie
    FROM Produits P
    LEFT JOIN Categories C ON P.ID_Categorie = C.ID_Categorie
");

$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Produits disponibles</title>
<form method="post" action="ajouter_panier.php">
    <table>
        <tr>
            <th>Produit</th>
            <th>Catégorie</th>
            <th>Prix unitaire</th>
            <th>Stock</th>
            <th>Ajouter</th>
        </tr>
        <?php foreach ($produits as $produit): ?>
        <tr>
            <td><?= htmlspecialchars($produit['Nom_Produit']) ?></td>
            <td><?= htmlspecialchars($produit['Nom_Categorie'] ?? '—') ?></td>
            <td><?= number_format($produit['Prix_Unitaire'], 2) ?> €</td>
            <td><?= $produit['Stock'] ?></td>
            <td>
                <button type="submit" name="ajouter" value="<?= $produit['ID_Produit'] ?>">🛒 Ajouter</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</form>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h2>Liste des produits disponibles</h2>
    <table>
        <tr>
            <th>Produit</th>
            <th>Catégorie</th>
            <th>Prix unitaire</th>
            <th>Stock</th>
        </tr>
        <?php foreach ($produits as $produit): ?>
        <tr>
            <td><?= htmlspecialchars($produit['Nom_Produit']) ?></td>
            <td><?= htmlspecialchars($produit['Nom_Categorie'] ?? '—') ?></td>
            <td><?= number_format($produit['Prix_Unitaire'], 2) ?> €</td>
            <td><?= $produit['Stock'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
