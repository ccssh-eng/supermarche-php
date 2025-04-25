<?php

ini_set('display_errors', 1);                                           
ini_set('display_startup_errors', 1);                                   
error_reporting(E_ALL);      

<?php include 'navbar.php'; ?>

require_once 'connexion.php'; // connexion à la base via PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sécurité basique : éviter les champs vides
    if (!isset($_POST['nom'], $_POST['email'], $_POST['MotDePasse'], $_POST['role'])) {
        die("Tous les champs sont requis.");
    }

    $nom = htmlspecialchars($_POST['nom']);
    $email = $_POST['email'];
    $motDePasse = $_POST['MotDePasse'];
    $role = $_POST['role'];

    // Vérifie rôle valide
    if (!in_array($role, ['employee', 'admin'])) {
        die("Rôle invalide.");
    }

    // Hashage du mot de passe
    $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

    // Insertion dans la base
    $stmt = $pdo->prepare("INSERT INTO Employes (Nom, Email, MotDePasse, Role) VALUES (?, ?, ?, ?)");

    try {
        $stmt->execute([$nom, $email, $motDePasseHash, $role]);
        echo "Compte employé créé avec succès ! <a href='login.php'>Se connecter</a>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "Cet email est déjà utilisé.";
        } else {
            echo "Erreur : " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Employé</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Créer un compte employé</h2>
    <form method="post" action="register_employe.php">
        <label>Nom:</label><br>
        <input type="text" name="nom" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Mot de passe:</label><br>
        <input type="password" name="MotDePasse" required><br><br>

        <label>Rôle:</label><br>
        <select name="role" required>
            <option value="employee">Employé</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit">Créer le compte</button>
    </form>
</body>
</html>
