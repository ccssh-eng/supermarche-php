<?php


<?php include 'navbar.php'; ?>

// register_client.php : création d'un nouveau client
require('connexion.php');
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';

    if (!empty($nom) && !empty($email)) {
        $stmt = $pdo->prepare("INSERT INTO Clients (Nom, prenom, Email, telephone) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $telephone]);
        $message = "✅ Client enregistré avec succès.";
    } else {
        $message = "Le nom et l'email sont obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un Client</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <h2>Créer un client</h2>
        <?php if ($message): ?><p class="msg"><?= $message ?></p><?php endif; ?>
        <form method="POST">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom">
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="telephone" placeholder="Téléphone">
            <button type="submit">Enregistrer</button>
        </form>
    </div>
</body>
</html>
