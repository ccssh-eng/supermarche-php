<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php'; // Connexion à MySQL

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les champs du formulaire
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérifications
    if (empty($nom) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = "Un compte existe déjà avec cet email.";
        } else {
            // Hasher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insérer dans la base
            $stmt = $pdo->prepare("INSERT INTO users (nom, email, mot_de_passe, role) VALUES (?, ?, ?)");
            if ($stmt->execute([$nom, $email, $hashed_password, $role])) {
                // Inscription réussie ➔ redirection vers connexion
                header("Location: /signin.php?success=1");
                exit;
            } else {
                $errors[] = "Erreur lors de l'insertion en base.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h2>Créer un compte</h2>

    <?php
    // Afficher erreurs
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
    ?>

    <form method="POST" action="">
        <input type="text" name="nom" placeholder="Nom" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br><br>
        <input type="password" name="confirm_password" placeholder="Confirmer mot de passe" required><br><br>
        <button type="submit">S'inscrire</button>
    </form>

    <p>Déjà inscrit ? <a href="signin.php">Se connecter</a></p>
</body>
</html>
