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

// 🔄 Modifier une catégorie
if (isset($_POST['update_id'], $_POST['update_nom'])) {
    $stmt = $pdo->prepare("UPDATE Categories SET Nom_Categorie = ? WHERE ID_Categorie = ?");
    $stmt->execute([$_POST['update_nom'], $_POST['update_id']]);
}

// ❌ Supprimer une catégorie
if (isset($_POST['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM Categories WHERE ID_Categorie = ?");
        $stmt->execute([$_POST['delete_id']]);
    } catch (PDOException $e) {
        $error = "Impossible de supprimer cette catégorie (liée à un produit ?)";
    }
}

// ➕ Ajouter une nouvelle catégorie
if (isset($_POST['new_nom'])) {
    $stmt = $pdo->prepare("INSERT INTO Categories (Nom_Categorie) VALUES (?)");
    $stmt->execute([$_POST['new_nom']]);
}

// 🔍 Lire toutes les catégories
$categories = $pdo->query("SELECT * FROM Categories ORDER BY Nom_Categorie")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des catégories</title>
    <style>
        body { font-family: Arial; background: #f7f7f7; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background: #343a40; color: white; }
        form.inline { display: flex; gap: 10px; align-items: center; }
        input[type="text"] { width: 200px; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Gestion des catégories</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <table>
        <tr>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($categories as $cat): ?>
        <tr>
            <td>
                <form method="post" class="inline">
                    <input type="hidden" name="update_id" value="<?= $cat['ID_Categorie'] ?>">
                    <input type="text" name="update_nom" value="<?= htmlspecialchars($cat['Nom_Categorie']) ?>" required>
                    <button type="submit">✏️</button>
                </form>
            </td>
            <td>
                <form method="post" onsubmit="return confirm('Supprimer cette catégorie ?');">
                    <input type="hidden" name="delete_id" value="<?= $cat['ID_Categorie'] ?>">
                    <button type="submit">🗑️ Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>Ajouter une nouvelle catégorie</h3>
    <form method="post">
        <input type="text" name="new_nom" placeholder="Nom de la catégorie" required>
        <button type="submit">Ajouter</button>
    </form>

</body>
</html>
