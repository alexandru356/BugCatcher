<?php
// Configuration de la base de données
$host = 'localhost';
$db_name = 'bug_catcher';
$username = 'root';
$password = '';

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur de connexion: " . $e->getMessage());
}

// Configuration de l'application
define('APP_NAME', 'BugCatcher');
define('BASE_URL', 'http://localhost/Projet02-Ciuca');
?>