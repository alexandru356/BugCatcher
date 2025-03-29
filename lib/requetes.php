<?php
/**
 * Bibliothèque de fonctions pour les requêtes à la base de données
 * Ce fichier contient des fonctions qui interagissent avec la base de données
 * pour effectuer des opérations telles que la gestion des utilisateurs, des bogues
 * et des statuts.
 * 
 * @author Alexandru Ciuca
 * @date 2025-03-29
 */
require_once __DIR__ . "../../includes/config.php";


/**
 * Trouver utilisateur par email dans la base de données
 * @param string $email, email de l'utilisateur
 * @return array retourne tableau associative, sinon null
 */
function trouver_utilisateur_par_email($email){
    global $pdo;
    $requete = $pdo -> prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $requete -> execute([$email]);
    return $requete -> fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Crée un utilisateur dans la base de données
 * @param string $email, L'email de l'utilisateur
 * @param string $mot_de_passe, Le mot de passe de l'utilisateur
 * @return bool Retourne true si l'utilisateur a été créé ave succès
 */
function creer_utilisateur($email, $mot_de_passe){
    global $pdo;

    $existant = trouver_utilisateur_par_email($email);
    if($existant){
        return false;
    }
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    $requete = $pdo -> prepare("INSERT INTO utilisateurs (email, mot_de_passe) VALUES (?, ?)");
    return $requete -> execute([$email, $mot_de_passe_hache]);
}

/**
 * Vérifie si l'email est déjà utilisé
 * @param string $email, l'email à vérifier
 * @return bool Retourne true si l'email est déjà utilisé
 */
function email_deja_utilise($email){
    global $pdo;
    $requete = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
    $requete->execute([$email]);
    return $requete->fetchColumn() > 0;
}

/**
 * Insère un nouveau bogue dans la base de données
 * @param string $titre, Titre du bogue
 * @param string $description La description du bogue
 * @param int $statut L'ID du statut du bogue
 * @param int $utilisateur L'ID de l'utilisateur associé au bogue
 * @return bool Retourne true si l'insertion a réussi
 */
function inserer_bogue($titre, $description, $statut, $utilisateur){
    global $pdo;
    $requete = $pdo -> prepare("INSERT INTO bogues (titre, description, 
                        statut_id, utilisateur_id) VALUES (?, ?, ?, ?)");
    
    return $requete -> execute([$titre, $description, $statut, $utilisateur]);
}
/**
 * Récupère tous les bogues d'un utilisateur
 * @param int $utilisateur_id L'ID de l'utilisateur dont on souhaite obtenir les bogues
 * @return array un tableau contenant les bogues associés à l'utilisateur
 */
function obtenir_bogues_utilisateur($utilisateur_id){
    global $pdo;

    $requete = $pdo -> prepare
    ("SELECT B.id, B.titre, B.description, B.date_creation, S.statut AS statut, S.couleur as couleur
        FROM bogues B
        INNER JOIN statuts S ON B.statut_id = S.id
        WHERE B.utilisateur_id = ?
        ORDER BY B.date_creation DESC
    ");
    $requete -> execute([$utilisateur_id]);
    return $requete -> fetchAll(PDO::FETCH_ASSOC);
}
/**
 * Récupère un bogue par son ID
 * @param int $bogue_id L'ID du bogue à récupérer
 * @return array un tableau contenant les détails du bogue
 */
function recuperer_bogue_par_id($bogue_id){
    global $pdo;

    $requete = $pdo -> prepare("SELECT  B.id, B.titre, B.description, B.date_creation, B.date_modification, B.utilisateur_id, S.statut AS statut, S.couleur as couleur
                                FROM bogues B 
                                INNER JOIN statuts S ON B.statut_id = S.id
                                WHERE B.id = ?");
    $requete -> execute([$bogue_id]);
    return $requete -> fetch(PDO::FETCH_ASSOC);
}
/**
 * Met à jour un bogue par son ID
 * @param string $titre Le nouveau titre du bogue
 * @param string $description La nouvelle description du bogue
 * @param int $statut_id Le nouvel ID du statut du bogue
 * @param int $bogue_id L'ID du bogue à mettre à jour
 * @return bool Retourne true si la mise à jour a réussi
 */
function mettre_a_jour_bogue_par_id($titre, $description, $statut_id, $bogue_id){
    global $pdo;
    $requete = $pdo -> prepare("
        UPDATE bogues
        SET titre = ?, description = ?, statut_id = ?, date_modification = NOW()
        WHERE id = ? and utilisateur_id = ?
    ");
    $requete -> execute([$titre, $description, $statut_id, $bogue_id, $_SESSION['utilisateur']['id']]);
    return $requete -> rowCount() > 0;
}
/**
 * Récupère tous les statuts
 * Cette fonction récupère tous les statuts disponibles dans la base de données.
 * @return array Un tableau contenant les statuts
 */
function getAllStatuts(){
    global $pdo;
    $requete = $pdo -> query("SELECT id, statut FROM statuts");
    return $requete -> fetchAll(PDO::FETCH_ASSOC);
}
/**
 * Supprime un bogue par son ID
 * Cette fonction supprime un bogue spécifique de la base de données en fonction de son ID.
 * @param int $bogue_id L'ID du bogue à supprimer
 * @return void
 */
function supprimer_bogue($bogue_id){
    global $pdo;
    $requete = $pdo -> prepare("DELETE FROM bogues WHERE id = ?");
    $requete -> execute([$bogue_id]);
}