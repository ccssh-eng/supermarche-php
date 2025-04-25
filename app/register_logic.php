<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<?php include 'navbar.php'; ?>

require_once 'connexion.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'] ?? null;
    $email = $_POST['email'];
    $telephone = $_POST['telephone'] ?? null;
    $mdp = $_POST['MotDePasse'];
    $role = $_POST['role'];

    $hash = password_hash($mdp, PASSWORD_DEFAULT);

    if ($role === 'client') {
        $stmt = $pdo->prepare("
            INSERT INTO Clients (Nom, prenom, Email, telephone, MotDePasse) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nom, $prenom, $email, $telephone, $hash]);
    } elseif (in_array($role, ['admin', 'employee'])) {
        $stmt = $pdo->prepare("
            INSERT INTO Employes (Nom, Email, MotDePasse, Role) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$nom, $email, $hash, $role]);
    } else {
        die("Rôle invalide.");
    }

    header("Location: signin.php?registered=1");
    exit;
}
?>
