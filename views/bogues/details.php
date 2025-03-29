<?php
/**
 * details.php - Gestion des détails d'un bogue
 * Ce fichier permet d'afficher les détails d'un bogue, et de les modifier ou les effacés
 * @author Alexandru
 * @date 2025-03-29
 */
session_start();
require_once '../../lib/requetes.php';
require_once '../../lib/fonctions.php';
// Vérifier si l'utilisateur est connecté
// A FAIRE: implémenter la redirection si non connecté
// connexion_requise();

if(!est_connecte()){
    header("Location: " . BASE_URL . "/index.php");
    exit();
}
// Récupérer l'ID du bogue depuis l'URL
$id_bogue = $_GET['id'];

// Vérifier que l'ID est valide
if ($id_bogue <= 0) {
    // Rediriger vers la liste des bogues avec un message d'erreur
    header("Location: ./liste.php?erreur=id_invalide");
    exit;
}

// A FAIRE: Implémenter la récupération des détails du bogue
// 1. Récupérer les informations du bogue avec l'ID spécifié
// 2. Vérifier que le bogue existe
// 3. Vérifier que le bogue appartient à l'utilisateur connecté
// 4. Si le bogue n'existe pas ou n'appartient pas à l'utilisateur, rediriger avec message d'erreur
// $bogue = obtenir_bogue_par_id($pdo, $id_bogue);
// if (!$bogue || $bogue['id_utilisateur'] != $_SESSION['id_utilisateur']) {
//     header("Location: " . BASE_URL . "/views/bogues/liste.php?erreur=introuvable");
//     exit;
// }
$bogue = recuperer_bogue_par_id($id_bogue); 
if(!$bogue || $bogue['utilisateur_id'] != $_SESSION['utilisateur']['id']){
    header("Location: " . BASE_URL . "/views/bogues/liste.php?erreur=introuvable");
    exit();
}

// A FAIRE: Implémenter le traitement du formulaire de modification
// 1. Vérifier si le formulaire a été soumis (méthode POST)
// 2. Récupérer et nettoyer les données du formulaire (titre, description, statut)
// 3. Valider les champs
// 4. Si validation OK, mettre à jour le bogue dans la base de données
// 5. Rediriger vers la page de détails avec un message de succès
// 6. Sinon, afficher les messages d'erreur
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $titre = obtenir_parametre("titre");
    $description = obtenir_parametre("description");
    $statut_id = obtenir_parametre("statut_id");

    $erreurs = valider_formulaire_bogue($titre, $description, $statut_id);

    if(empty($erreurs)){
        $mise_a_jour = mettre_a_jour_bogue_par_id($titre, $description, $statut_id, $id_bogue);
        header("Location: " . BASE_URL . "/views/bogues/liste.php?success=updated");
        $_SESSION['success'] = "Le bogue à été mis à jour avec succès!";
        exit();
    } else{
        $_SESSION['erreurs'] = $erreurs;
    }

}
// A FAIRE: implémenter le traitement de la suppression
// 1. Vérifier si le bouton de suppression a été cliqué
// 2. Demander une confirmation (peut être fait via JavaScript ou une page intermédiaire)
// 3. Si confirmé, supprimer le bogue de la base de données
// 4. Rediriger vers la liste des bogues avec un message de succès
if(isset($_POST['delete'])){
    supprimer_bogue($id_bogue);
    $_SESSION['success'] = "Le bogue a été supprimé avec succès";
    header("Location: " . BASE_URL . "/views/bogues/liste.php?success=deleted");
    exit();
}
// A FAIRE: récupérer la liste des statuts pour le menu déroulant
// $statuts = getAllStatuts($pdo);
include '../partials/_en_tete.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Détails du bogue</h1>
        <a href="<?php echo BASE_URL; ?>/views/bogues/liste.php" class="btn btn-outline-secondary">
            Retour à la liste
        </a>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'updated'): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- A FAIRE: implémenter l'affichage des erreurs générales -->

    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Bogue #<?php echo $id_bogue; ?></h5>
            <span class="badge bg-<?php echo $bogue['couleur'] ?? 'secondary'; ?>">
                <?php echo $bogue['statut'] ?? 'Inconnu'; ?>
            </span>
        </div>
        <div class="card-body">
            <form method="post" action="">
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre"
                    value="<?php echo $bogue['titre'] ?? ''; ?>">
                <!-- Afficher les erreurs de validation pour le titre -->
                <?php if (isset($_SESSION['erreurs']['titre'])): ?>
                    <div class="text-danger"><?php echo $_SESSION['erreurs']['titre']; unset($_SESSION['erreurs']['titre']);?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"
                    rows="6"><?php echo $bogue['description'] ?? ''; ?></textarea>
                <!-- Afficher les erreurs de validation pour la description -->
                <?php if (isset($_SESSION['erreurs']['description'])): ?>
                    <div class="text-danger"><?php echo $_SESSION['erreurs']['description']; unset($_SESSION['erreurs']['description']);?></div>
                <?php endif; ?>
            </div>


                <div class="mb-3">
                    <label for="statut_id" class="form-label">Statut</label>
                    <select class="form-select" id="statut_id" name="statut_id">
                        <!-- A FAIRE: 

                    Note: la requete et la generation des options doivent etre faites dans le fichier includes appropriés   
                    
                    1. Récupérer tous les statuts depuis la base de données
                    2. Vérifier que la variable $statuts contient des données
                    3. Créer une boucle pour parcourir chaque statut
                    4. Pour chaque statut:
                        - Créer une option avec comme valeur l'ID du statut
                        - Marquer comme "selected" l'option correspondant au statut actuel du bogue
                        - Afficher le nom du statut comme texte de l'option
                    5. Fermer correctement les structures conditionnelles et la boucle
                    
                    -->
                    <?php 
                        $statuts = getAllStatuts(); 
                        if ($statuts): 
                    ?>
                        <?php foreach ($statuts as $statut): ?>
                            <option value="<?php echo $statut['id']; ?>" 
                                    <?php echo ($statut['id'] == $bogue['statut']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($statut['statut']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">Aucun statut disponible</option>
                    <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dates</label>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Créé le:</strong>
                                <?php echo isset($bogue['date_creation']) ? date('d/m/Y à H:i', strtotime($bogue['date_creation'])) : ''; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Dernière modification:</strong>
                                <?php echo isset($bogue['date_modification']) ? date('d/m/Y à H:i', strtotime($bogue['date_modification'])) : ''; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        Supprimer
                    </button>
                    <button type="submit" class="btn btn-primary" name="update">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce bogue? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="post" action="">
                    <button type="submit" class="btn btn-danger" name="delete">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/_pied_page.php'; ?>