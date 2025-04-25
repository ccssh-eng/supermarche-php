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

// 🔄 Modifier un fournisseur
if (isset($_POST['update_id'])) {
    $stmt = $pdo->prepare("UPDATE Fournisseurs SET Nom_Fournisseur = ?, Contact = ?, Ville = ? WHERE ID_Fournisseur = ?");
    $stmt->execute([
        $_POST['nom'],
        $_POST['contact'],
        $_POST['ville'],
        $_POST['update_id']
    ]);
}

// ❌ Supprimer un fournisseur
if (isset($_POST['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM Fournisseurs WHERE ID_Fournisseur = ?");
        $stmt->execute([$_POST['delete_id']]);
    } catch (PDOException $e) {
        $error = "Impossible de supprimer ce fournisseur (utilisé par un produit ?)";
    }
}

// ➕ Ajouter un nouveau fournisseur
if (isset($_POST['ajouter'])) {
    $stmt = $pdo->prepare("INSERT INTO Fournisseurs (Nom_Fournisseur, Contact, Ville) VALUES (?, ?, ?)");
    $stmt->execute([
        $_POST['nom_fournisseur'],
        $_POST['contact'],
        $_POST['ville']
    ]);
}

// 📦 Récupérer tous les fournisseurs
$fournisseurs = $pdo->query("SELECT * FROM Fournisseurs ORDER BY Nom_Fournisseur")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Fournisseurs</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background: #343a40; color: white; }
        input[type="text"] { width: 150px; }
        form.inline { display: flex; gap: 10px; align-items: center; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Gestion des fournisseurs</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <table>
        <tr>
            <th>Nom</th>
            <th>Contact</th>
            <th>Ville</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($fournisseurs as $f): ?>
        <tr>
            <td>
                <form method="post" class="inline">
                    <input type="hidden" name="update_id" value="<?= $f['ID_Fournisseur'] ?>">
                    <input type="text" name="nom" value="<?= htmlspecialchars($f['Nom_Fournisseur']) ?>" required>
                    <input type="text" name="contact" value="<?= htmlspecialchars($f['Contact']) ?>">
                    <input type="text" name="ville" value="<?= htmlspecialchars($f['Ville']) ?>">
                    <button type="submit">✏️</button>
                </form>
            </td>
            <td colspan="3">
                <form method="post" onsubmit="return confirm('Supprimer ce fournisseur ?');">
                    <input type="hidden" name="delete_id" value="<?= $f['ID_Fournisseur'] ?>">
                    <button type="submit">🗑️ Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>Ajouter un nouveau fournisseur</h3>
    <form method="post">
        <input type="hidden" name="ajouter" value="1">
        <input type="text" name="nom_fournisseur" placeholder="Nom" required>
        <input type="text" name="contact" placeholder="Contact">
        <input type="text" name="ville" placeholder="Ville">
        <button type="submit">Ajouter</button>
    </form>

</body>
</html>
