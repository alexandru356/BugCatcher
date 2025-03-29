<?php
/**
 * nouveau.php - Création d'un bogue
 * Ce fichier permet de créé un bogue à l'aide d'un formulaire
 * @author Alexandru Ciuca
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
// Initialisation des variables
$titre = '';
$description = '';
$erreurs = [];

// A FAIRE: implémenter le traitement du formulaire:
// 1. Vérifier si le formulaire a été soumis (méthode POST)
// 2. Récupérer et nettoyer les données du formulaire (titre, description)
// 3. Valider les champs:
//    - Titre requis et longueur minimale
//    - Description requise et longueur minimale
// 4. Si validation OK, insérer le nouveau bogue dans la base de données
//    - Utiliser l'ID de l'utilisateur connecté ($_SESSION['id_utilisateur'])
//    - Définir le statut initial du bogue sur "Nouveau" (id 1 dans la table statuts)
// 5. Rediriger vers la liste des bogues avec un message de succès
// 6. Sinon, afficher les messages d'erreur

// Récupérer tous les statuts pour le menu déroulant
// A FAIRE: implémenter cette fonctionnalité
// $statuts = obtenir_statuts($pdo);
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $titre = obtenir_parametre("titre");
    $description = obtenir_parametre("description");

    $erreurs = valider_formulaire_bogue($titre, $description, 1);

    if(empty($erreurs)){
        $utilisateur_id = $_SESSION['utilisateur']['id'];
        $statut_id = 1;

        if(inserer_bogue($titre, $description, $statut_id, $utilisateur_id)){
            $_SESSION['succes'] = "Le bogue a été signalé avec succès.";
            header("Location: " . BASE_URL . "/views/bogues/liste.php");
            exit();
        }else{
            $erreurs['general'] = "Erreur lors de l'insertion du bogue.";
        }
    }
}




include '../partials/_en_tete.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5>Signaler un nouveau bogue</h5>
            </div>
            <div class="card-body">
                <!-- A FAIRE: Afficher les messages d'erreur généraux ici -->
                <?php if(!empty($erreurs['general'])): ?>
                    <div class="alert alert-danger"><?php echo $erreurs['general']; ?></div>
                <?php endif; ?>

                <form method="post" action="">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre du bogue</label>
                    <input type="text" class="form-control <?php echo isset($erreurs['titre']) ? 'is-invalid' : ''; ?>" 
                        id="titre" name="titre" value="<?php echo htmlspecialchars($titre); ?>">
                    <?php if (isset($erreurs['titre'])): ?>
                        <div class="invalid-feedback"><?php echo $erreurs['titre']; ?></div>
                    <?php endif; ?>
                    <div class="form-text">Donnez un titre descriptif qui résume le problème.</div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description détaillée</label>
                    <textarea class="form-control <?php echo isset($erreurs['description']) ? 'is-invalid' : ''; ?>" 
                            id="description" name="description" rows="6"><?php echo htmlspecialchars($description); ?></textarea>
                    <?php if (isset($erreurs['description'])): ?>
                        <div class="invalid-feedback"><?php echo $erreurs['description']; ?></div>
                    <?php endif; ?>
                    <div class="form-text">
                        Décrivez le problème en détail. Incluez :
                        <ul>
                            <li>Les étapes pour reproduire le bogue</li>
                            <li>Ce qui se passe actuellement</li>
                            <li>Ce qui devrait se passer normalement</li>
                            <li>Tout message d'erreur que vous avez reçu</li>
                        </ul>
                    </div>
                </div>

                    
                    <!-- Implémenter la sélection du statut ici, ou utiliser le statut par défaut "Nouveau" -->
                    
                    <div class="mb-3">
                        <label for="statut_id" class="form-label">Statut</label>
                        <select class="form-select" id="statut_id" name="statut_id">
                            <option value="1" selected>Nouveau</option>
                        </select>
                    </div>
                   
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>/views/bogues/liste.php" class="btn btn-outline-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Soumettre le rapport</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/_pied_page.php'; ?>