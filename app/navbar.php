<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
    nav {
        background-color: #2c3e50;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #ecf0f1;
        font-family: Arial, sans-serif;
        margin-bottom: 30px;
        border-radius: 8px;
    }

    nav .links a {
        color: #ecf0f1;
        text-decoration: none;
        margin: 0 10px;
        padding: 6px 10px;
        border-radius: 5px;
        transition: background 0.2s ease-in-out;
    }

    nav .links a:hover {
        background-color: #34495e;
    }

    nav .user {
        font-size: 0.95em;
    }
</style>

<nav>
    <div class="links">
        <?php if (isset($_SESSION['role'])): ?>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin.php">Accueil Admin</a>
                <a href="register_employe.php">Ajouter Employé</a>
                <a href="admin_commandes.php">Toutes les Commandes</a>
                <a href="commandes_a_traiter.php">Commandes à Traiter</a>
            
             <?php elseif ($_SESSION['role'] === 'employee'): ?>
                <a href="interface_commandes.php">Interface Employé</a>
                <a href="commandes_a_traiter.php">Commandes à Traiter</a>
            
             <?php elseif ($_SESSION['role'] === 'client'): ?>
                <a href="accueil.php">Accueil Client</a>
                <a href="panier.php">Mon Panier</a>
                <a href="mes_commandes.php">Mes Commandes</a> |
            <?php endif; ?>

            <a href="logout.php">Déconnexion</a>

        <?php else: ?>
            <a href="login.php">Se connecter</a>
            <a href="register_client.php">Créer un compte</a>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['role'])): ?>
        <div class="user">

            Bonjour, Connecté en tant que <strong><?= htmlspecialchars($_SESSION['nom']) ?></strong> (<?= htmlspecialchars($_SESSION['role']) ?>)
        </div>
    <?php endif; ?>
</nav>
