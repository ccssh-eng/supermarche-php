<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session
session_start();

// Connexion à la base de données
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = "Tous les champs sont obligatoires.";
    } else {
        // Chercher l'utilisateur par email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Connexion réussie : enregistrer les infos en session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['role'] = $user['role'];   //ligne 31

            // Redirection propre
// Redirection selon le rôle
switch ($_SESSION['role']) {
    case 'admin':
        header("Location: /admin_dashboard.php");
        break;
    case 'employe':
        header("Location: /interface_commandes.php");
        break;
    case 'client':    // ligne 44
    default:
        header("Location: /accueil.php");
        break;
}
exit;

        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h2>Se connecter</h2>

    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
    ?>

    <form method="POST" action="signin.php">
        <input type="email" name="email" placeholder="Adresse email" required><br><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br><br>
        <button type="submit">Se connecter</button>
    </form>

    <p>Pas encore inscrit ? <a href="signup.php">Créer un compte</a></p>
</body>
</html>
