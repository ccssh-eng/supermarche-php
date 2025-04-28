<?php
// db.php : fichier de connexion à la base de données

$host = 'mysql';
$dbname = 'supermarche'; // Remplace si besoin par le vrai nom de ta base
$user = 'marche_user';          // Ou ton utilisateur MySQL
$pass = 'sup_pass321';              // Mot de passe MySQL (souvent vide en local)
$dbport = '3306';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
