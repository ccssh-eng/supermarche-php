
<?php
ini_set('display_errors', 1);                                           
ini_set('display_startup_errors', 1);                                   
error_reporting(E_ALL);                                                                           

<?php include 'navbar.php'; ?>

session_start();
require_once 'connexion.php';

// Vérification des champs
if (!isset($_POST['email'], $_POST['MotDePasse'], $_POST['role'])) {
    die('Tous les champs sont requis.');
}

$email = $_POST['email'];
$password = $_POST['MotDePasse'];
$role = $_POST['role'];

// Sélection de la table selon le rôle
$role = strtolower(trim($role)); // nettoie un peu
if ($role === 'client') {
    $stmt = $pdo->prepare("SELECT ID_Client AS id, Nom, Email, MotDePasse FROM Clients WHERE Email = ?");
} elseif ($role === 'employee' || $role === 'admin') {
    $stmt = $pdo->prepare("SELECT ID_Employe AS id, Nom, Email, MotDePasse, Role FROM Employes WHERE Email = ?");
} else {
    die('Rôle invalide.');
}

$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['MotDePasse'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nom'] = $user['Nom'];
    $_SESSION['email'] = $user['Email'];
    $_SESSION['role'] = $role === 'admin' ? 'admin' : ($user['Role'] ?? $role); // Sécurité

    // Redirection selon le rôle
    switch ($_SESSION['role']) {
        case 'admin':
            header('Location: admin.php');
            break;
        case 'employee':
            header('Location: interface_commandes.php');
            break;
        case 'client':
            header('Location: accueil.php');
            break;
        default:
            echo "Rôle non reconnu.";
    }
    exit;
} else {
    echo "Email ou mot de passe incorrect.";
}
?>

<form action="login.php" method="post"><?php include 'navbar.php'; ?>
    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>Mot de passe:</label>
    <input type="password" name="MotDePasse" required><br>

    <label>Rôle:</label>
    <select name="role" required>
        <option value="client">Client</option>
        <option value="employee">Employé</option>
        <option value="admin">Admin</option>
    </select><br>

    <button type="submit">Se connecter</button>
</form>
