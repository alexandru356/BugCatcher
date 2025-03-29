<?php
/**
 * inscription.php - Création d'un compte 
 * Ce fichier permet de créé un compte avec le système, à l'aide d'un formulaire 
 * @author Alexandru Ciuca
 * @date 2025-03-29
 */
session_start();
require_once '../../lib/requetes.php';
require_once '../../lib/fonctions.php';

// A FAIRE: Vérifier si l'utilisateur est déjà connecté
// Si oui, le rediriger vers la page d'accueil
if(est_connecte()){
    header("Location: " . BASE_URL . "index.php");
    exit();
}
// Initialiser les variables
$email = '';
$erreurs = [];
$succes = '';
// A FAIRE: Traitement du formulaire d'inscription
// 1. Vérifier si le formulaire a été soumis (méthode POST)
// 2. Récupérer et nettoyer les données du formulaire (email, mot de passe, confirmation)
// 3. Valider les champs:
//    - Email requis et format valide
//    - Vérifier que l'email n'existe pas déjà dans la base de données
//    - Mot de passe requis et longueur minimale (8 caractères recommandés)
//    - Confirmation du mot de passe doit correspondre
// 4. Si validation OK, créer le nouvel utilisateur dans la base de données
// 5. Rediriger vers la page de connexion avec un message de succès
// 6. Sinon, afficher les messages d'erreur
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = obtenir_parametre('email');
    $mdp = obtenir_parametre('mot_de_passe');
    $confirmation = obtenir_parametre('mot_de_passe_confirmation');

    $erreurs = valider_formulaire_inscription($email, $mdp, $confirmation);

    if(empty($erreurs)){
        if(creer_utilisateur($email, $mdp)){
            $_SESSION['succes'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            header("Location: " . BASE_URL . "/views/enregistrement/connexion.php");
            exit();
        }
    }
}
include '../partials/_en_tete.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-header">
                <h5>Inscription</h5>
            </div>
            <div class="card-body">

            <?php if (isset($_SESSION['succes'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['succes']; unset($_SESSION['succes']); ?></div>
                <?php endif; ?>

                <?php if (isset($erreurs['general'])): ?>
                    <div class="alert alert-danger"><?= $erreurs['general']; ?></div>
                <?php endif; ?>

                <form method="post" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>

                        <input type="email" class="form-control <?= isset($erreurs['email']) ? 'is-invalid' : '' ?>" 
                               id="email" name="email" value="<?= htmlspecialchars($email) ?>">
                        <?php if (isset($erreurs['email'])): ?>
                            <div class="invalid-feedback"><?= $erreurs['email']; ?></div>
                        <?php endif; ?>

                        <!-- A FAIRE: Afficher les erreurs de validation pour l'email -->
                        <div class="form-text">Nous ne partagerons jamais votre email avec des tiers.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mot-passe" class="form-label">Mot de passe</label>

                        <input type="password" class="form-control <?= isset($erreurs['mot_de_passe']) ? 'is-invalid' : '' ?>" 
                               id="mot-passe" name="mot_de_passe">
                        <?php if (isset($erreurs['mot_de_passe'])): ?>
                            <div class="invalid-feedback"><?= $erreurs['mot_de_passe']; ?></div>
                        <?php endif; ?>

                        <!-- A FAIRE: Afficher les erreurs de validation pour le mot de passe -->
                        <div class="form-text">Le mot de passe doit contenir au moins 8 caractères.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirmation-mot-passe" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control <?= isset($erreurs['confirmation_mot_de_passe']) ? 'is-invalid' : '' ?>" 
                               id="confirmation-mot-passe" name="mot_de_passe_confirmation">
                        <?php if (isset($erreurs['confirmation_mot_de_passe'])): ?>
                            <div class="invalid-feedback"><?= $erreurs['confirmation_mot_de_passe']; ?></div>
                        <?php endif; ?>
                        <!-- A FAIRE: Afficher les erreurs de validation pour la confirmation du mot de passe -->
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </form>
                
                <div class="mt-4 text-center">
                    <p>Vous avez déjà un compte? <a href="<?php echo BASE_URL; ?>/views/enregistrement/connexion.php">Connectez-vous ici</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/_pied_page.php'; ?>