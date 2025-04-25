<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
</head>
<body>
    <h2>Créer un compte</h2>
    <form action="register_logic.php" method="post">
        <label>Nom :</label><br>
        <input type="text" name="nom" required><br><br>

        <label>Prénom :</label><br>
        <input type="text" name="prenom"><br><br>

        <label>Email :</label><br>
        <input type="email" name="email" required><br><br>

        <label>Téléphone :</label><br>
        <input type="text" name="telephone"><br><br>

        <label>Mot de passe :</label><br>
        <input type="password" name="MotDePasse" required><br><br>

        <label>Rôle :</label><br>
        <select name="role" required>
            <option value="client">Client</option>
            <option value="employee">Employé</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit">S’inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="signin.php">Se connecter</a></p>
</body>
</html>
