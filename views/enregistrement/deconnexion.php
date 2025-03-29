<?php
/**
 * deconnexion.php - Déconnexion du système
 * Ce fichier permet à l'utilisateur de ce déconnecté du système
 * @author Alexandru Ciuca
 * @date 2025-03-29
 */
include '../partials/_en_tete.php';

// A FAIRE: Vider les variables de session
$_SESSION = [];

// A FAIRE: Détruire la session
session_destroy();

// A FAIRE:  Redémarrer une nouvelle session propre
session_start();

// A FAIRE: VStocker un message flash de déconnexion réussie
$_SESSION['succes'] = "Vous avez été déconnecté avec succès.";

// Rediriger vers la page d'accueil
header("Location: " . BASE_URL . "/index.php");
exit;
?>

<?php include '../partials/_pied_page.php'; ?>