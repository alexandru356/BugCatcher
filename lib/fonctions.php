<?php
/**
 * fonctions.php - Bibliothèque de fonctions générales pour l'application
 * Ce fichier contient des fonctions utilitaires utilisées dans l'application,
 * telles que la gestion des alertes, la validation de formulaires et la gestion des sessions.
 * @author Alexandru Ciuca
 * @date 2025-03-29
 */

require_once "requetes.php";


 /**
  * Vérifie si un utilisateur est connecté
  * @return bool true si l'utilisateur est connecté, false sinon
  */

 function est_connecte()
 {
    // a définir. Utiliser temporairement true ou false pour tester
    //  return true;
     //check si la session est declarer ou si elle n'est pas vide
     return isset($_SESSION['utilisateur']['id']) && !empty($_SESSION['utilisateur']['id']);
 }

/**
 * Affiche un message d'alerte
 * 
 * @param string $message Le message à afficher
 * @param string $type Le type de message (success, info, warning, danger)
 * 
 * @return string Le message d'alerte
 */
function alert($message, $type = 'success')
{
  return '<div class="alert alert-' . $type . '">' . $message . '</div>';
}

/** 
 * Affiche un message d'alerte à partir d'une variable de session
 * 
 * @return string Le message d'alerte
 */
function afficher_message_flash()
{
  if (isset($_SESSION['flash'])) {
    $message = $_SESSION['flash']['message'];
    $type = $_SESSION['flash']['type'];
    unset($_SESSION['flash']);
    return alert($message, $type);
  }
  return '';
}

/**
 * Obtenir les parametres soumis dans la barre de recherche
 * @param string $parametre Nom du parametre a obtenir
 * @return string Valeur du parametre
 */
function obtenir_parametre($parametre){
  return htmlspecialchars(trim($_POST[$parametre] ?? ""));
}

/**
 * Valider les champs pour verifier s'ils sont vides
 * @param string $donnees, les données à vérifier
 * @param string $champs, les champs à vérifier
 * @return string $erreurs, les erreurs à retourner, si vide = aucunes erreurs
 */
function valider_champ_obligatoires($donnees, $champs){
  $erreurs = [];
  foreach($champs as $champ){
    if(empty($donnees[$champ])){
      $erreurs[$champ] = "Ce champ est obligatoire";
    }
  }
  return $erreurs;
}

/**
 * Valider les champs pour s'inscrire
 * @param string $email Email à valider
 * @param string $mot_de_passe Mot de passe à valider
 * @param string $confirmation, confirmation du mot de passe
 * @return array Tableau des erreurs, vide si validation réussie
 */
function valider_formulaire_inscription($email, $mot_de_passe, $confirmation){
  $erreurs = [];

  if(empty($email)){
    $erreurs['email'] = "L'email est obligatoire";
  } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $erreurs['email'] = "Format d'email invalide";
  } elseif (email_deja_utilise($email)) {
    $erreurs['email'] = "Cet email est déjà utilisé";
  }

  if(empty($mot_de_passe)){
    $erreurs['mot_de_passe'] = "Le mot de passe est obligatoire";
  }elseif(strlen($mot_de_passe) < 8){
    $erreurs['mot_de_passe'] = "Le mot de passe doit contenir au moins 8 caractères";
  }
  if(empty($confirmation)){
    $erreurs['confirmation_mot_de_passe'] = "La confirmation de mot de passe est obligatoire";
  }
  if($mot_de_passe !== $confirmation){
    $erreurs['confirmation_mot_de_passe'] = "Les mots de passe ne correspondent pas";
  }
  return $erreurs;
}
/**
 * Valider les champs pour la mise à jour d'un bogue
 * 
 * Cette fonction valide les champs nécessaires pour mettre à jour un bogue,
 * y compris le titre, la description et le statut.
 *
 * @param string $titre Le titre du bogue
 * @param string $description La description du bogue
 * @param string $statut Le statut du bogue
 * @return array Un tableau associatif contenant les erreurs de validation
 */
function valider_formulaire_bogue($titre, $description, $statut){
  $erreurs = [];
  if(strlen($titre) < 5 ){
    $erreurs['titre'] = "La longueur minimale pour le titre est de 5 caractères";
  }
  if(empty($titre)){
    $erreurs['titre'] = "Il faut mettre un titre";
  }
  if(strlen($description) > 100){
    $erreurs['description'] = "La longueur maximale pour le titre est de 100 caractères";
  }
  if(empty($description)){
    $erreurs['description'] = "Il faut mettre une description";
  }

  if(empty($statut)){
    $erreurs['statut'] = "Il faut mettre un statut";
  }
  
  return $erreurs;
}