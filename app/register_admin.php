<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<?php include 'navbar.php'; ?>

// register_admin.php : création manuelle d'un compte admin
require('connexion.php');
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($nom) && !empty($email) && !empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO Admins (Nom, Email, MotDePasse) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $hash]);
        $message = "✅ Compte admin créé avec succès.";
    } else {
        $message = "Tous les champs sont requis.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un Admin</title>
    <style>
        body { font-family: Arial; background: #f8f8f8; padding: 40px; }
        .box { background: white; max-width: 400px; margin: auto; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin: 10px 0; }
        button { background: #28a745; color: white; border: none; border-radius: 5px; }
        .msg { text-align: center; color: green; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="box">
        <h2>Créer un compte Admin</h2>
        <?php if ($message): ?><p class="msg"><?= $message ?></p><?php endif; ?>
        <form method="POST">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Créer</button>
        </form>
    </div>
</body>
</html>
