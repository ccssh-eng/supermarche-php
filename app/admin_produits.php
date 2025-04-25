<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<?php include 'navbar.php'; ?>

session_start();
require_once 'connexion.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit;
}

// 🔄 Modifier produit si formulaire envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_id'])) {
        $stmt = $pdo->prepare("UPDATE Produits SET Prix_Unitaire = ?, Stock = ? WHERE ID_Produit = ?");
        $stmt->execute([$_POST['prix'], $_POST['stock'], $_POST['update_id']]);
    }

    // ➕ Ajout de produit
    if (isset($_POST['ajouter']) && $_POST['ajouter'] === '1') {
        $stmt = $pdo->prepare("INSERT INTO Produits (Nom_Produit, Prix_Unitaire, Stock, ID_Categorie, ID_Fournisseur) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nom_produit'],
            $_POST['prix_unitaire'],
            $_POST['stock'],
            $_POST['categorie'],
            $_POST['fournisseur']
        ]);
    }
}

// 📦 Récupérer les produits
$produits = $pdo->query("
    SELECT P.ID_Produit, P.Nom_Produit, P.Prix_Unitaire, P.Stock, 
           C.Nom_Categorie, F.Nom AS Fournisseur
    FROM Produits P
    LEFT JOIN Categories C ON P.ID_Categorie = C.ID_Categorie
    LEFT JOIN Fournisseurs F ON P.ID_Fournisseur = F.ID_Fournisseur
")->fetchAll(PDO::FETCH_ASSOC);

// 🗂️ Récupérer catégories et fournisseurs pour le formulaire d’ajout
$categories = $pdo->query("SELECT ID_Categorie, Nom_Categorie FROM Categories")->fetchAll();
$fournisseurs = $pdo->query("SELECT ID_Fournisseur, Nom FROM Fournisseurs")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des produits</title>
    <style>
        body { font-family: Arial; background: #f7f7f7; padding: 20px; }
        h2 { margin-top: 40px; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background: #007BFF; color: white; }
        tr:hover { background-color: #f1f1f1; }
        form.inline { display: flex; gap: 10px; align-items: center; }
        input[type="number"], input[type="text"] { width: 80px; }
        .section { background: #fff; padding: 20px; border-radius: 10px; margin-top: 20px; }
    </style>
</head>
<body>

    <h1>Interface de gestion des produits</h1>

    <div class="section">
        <h2>Liste des produits</h2>
        <table>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Fournisseur</th>
                <th>Prix (€)</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($produits as $prod): ?>
            <tr>
                <td><?= htmlspecialchars($prod['Nom_Produit']) ?></td>
                <td><?= htmlspecialchars($prod['Nom_Categorie']) ?></td>
                <td><?= htmlspecialchars($prod['Fournisseur']) ?></td>
                <td><?= number_format($prod['Prix_Unitaire'], 2) ?></td>
                <td><?= $prod['Stock'] ?></td>
                <td>
                    <form method="post" class="inline">
                        <input type="hidden" name="update_id" value="<?= $prod['ID_Produit'] ?>">
                        <input type="number" name="prix" step="0.01" value="<?= $prod['Prix_Unitaire'] ?>" required>
                        <input type="number" name="stock" value="<?= $prod['Stock'] ?>" required>
                        <button type="submit">💾</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="section">
        <h2>Ajouter un nouveau produit</h2>
        <form method="post">
            <input type="hidden" name="ajouter" value="1">
            <label>Nom :</label>
            <input type="text" name="nom_produit" required>
            <label>Prix (€) :</label>
            <input type="number" step="0.01" name="prix_unitaire" required>
            <label>Stock :</label>
            <input type="number" name="stock" required>
            <label>Catégorie :</label>
            <select name="categorie">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['ID_Categorie'] ?>"><?= $cat['Nom_Categorie'] ?></option>
                <?php endforeach; ?>
            </select>
            <label>Fournisseur :</label>
            <select name="fournisseur">
                <?php foreach ($fournisseurs as $f): ?>
                    <option value="<?= $f['ID_Fournisseur'] ?>"><?= $f['Nom'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Ajouter</button>
        </form>
    </div>

</body>
</html>
