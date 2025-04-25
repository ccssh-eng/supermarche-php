<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mdp = $_POST['MotDePasse'];
    $role = $_POST['role'];

    if ($role === 'client') {
        $stmt = $pdo->prepare("SELECT ID_Client AS id, Nom, MotDePasse FROM Clients WHERE Email = ?");
    } elseif (in_array($role, ['admin', 'employee'])) {
        $stmt = $pdo->prepare("SELECT ID_Employe AS id, Nom, MotDePasse, Role FROM Employes WHERE Email = ?");
    } else {
        die("Rôle inconnu.");
    }

    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mdp, $user['MotDePasse'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['Nom'];
        $_SESSION['role'] = $role === 'client' ? 'client' : $user['Role'];

        switch ($_SESSION['role']) {
            case 'admin':
                header("Location: admin.php");
                break;
            case 'employee':
                header("Location: interface_commandes.php");
                break;
            case 'client':
                header("Location: accueil.php");
                break;
        }
        exit;
    } else {
        $error = "Email ou mot de passe invalide.";
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
    <h2>Connexion</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($_GET['registered'])) echo "<p style='color:green;'>Compte créé avec succès. Vous pouvez vous connecter.</p>"; ?>

    <form method="post" action="signin.php">
        <label>Email :</label><br>
        <input type="email" name="email" required><br><br>

        <label>Mot de passe :</label><br>
        <input type="password" name="MotDePasse" required><br><br>

        <label>Rôle :</label><br>
        <select name="role" required>
            <option value="client">Client</option>
            <option value="employee">Employé</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
